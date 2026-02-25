<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AllowancePeriodModel;
use App\Models\AllowanceModel;
use App\Models\AllowanceSlipModel;
use App\Models\UserModel;
use App\Models\InternModel;
use App\Models\AttendanceModel;
use App\Models\SettingModel;

class AllowanceController extends BaseController
{
    protected $periodModel;
    protected $allowanceModel;
    protected $slipModel;
    protected $userModel;
    protected $internModel;
    protected $attendanceModel;
    protected $settingModel;
    protected $db;

    public function __construct()
    {
        $this->periodModel = new AllowancePeriodModel();
        $this->allowanceModel = new AllowanceModel();
        $this->slipModel = new AllowanceSlipModel();
        $this->userModel = new UserModel();
        $this->internModel = new InternModel();
        $this->attendanceModel = new AttendanceModel();
        $this->settingModel = new SettingModel();
        $this->db = \Config\Database::connect();
    }

    // ========================================
    // INTERN: MY ALLOWANCES
    // ========================================
    public function my()
    {
        $userId = session()->get('user_id');
        $allowances = $this->allowanceModel->getAllowancesByUser($userId);

        // Get slip info for each allowance
        foreach ($allowances as &$allowance) {
            $slip = $this->slipModel->getSlipByAllowance($allowance['id_allowance']);
            $allowance['slip'] = $slip;
        }

        $data = [
            'title' => 'Uang Saku Saya',
            'allowances' => $allowances
        ];

        return view('allowance/my', $data);
    }

    // ========================================
    // ADMIN/HR/FINANCE: PERIOD MANAGEMENT
    // ========================================
    public function period()
    {
        $periods = $this->periodModel->getAllWithSummary();

        $data = [
            'title' => 'Periode Pembayaran Uang Saku',
            'periods' => $periods
        ];

        return view('allowance/period', $data);
    }

    // ========================================
    // ADMIN/HR: CREATE PERIOD
    // ========================================
    public function createPeriod()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'nama_periode' => 'required|min_length[3]|max_length[100]',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validation->getErrors()
            ]);
        }

        // Validate end date must be after start date
        $startDate = $this->request->getPost('tanggal_mulai');
        $endDate = $this->request->getPost('tanggal_selesai');

        if (strtotime($endDate) <= strtotime($startDate)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tanggal selesai harus lebih besar dari tanggal mulai'
            ]);
        }

        try {
            // Auto-calculate tanggal_pembayaran (tanggal 25 bulan setelah periode selesai)
            $paymentDate = date('Y-m-25', strtotime($endDate . ' +1 month'));

            $this->periodModel->insert([
                'nama_periode' => $this->request->getPost('nama_periode'),
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'tanggal_pembayaran' => $paymentDate,
                'status' => 'draft'
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Periode berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membuat periode: ' . $e->getMessage()
            ]);
        }
    }

    // ========================================
    // ADMIN/HR/FINANCE: ALLOWANCE INDEX
    // ========================================
    public function index()
    {
        $periodId = $this->request->getGet('period');

        if (!$periodId) {
            // Get latest period
            $latestPeriod = $this->periodModel->getLatestPeriod();
            if ($latestPeriod) {
                return redirect()->to('/allowance?period=' . $latestPeriod['id_period']);
            }
        }

        $period = $this->periodModel->find($periodId);
        $allowances = $this->allowanceModel->getAllowancesByPeriod($periodId);
        $periods = $this->periodModel->orderBy('tanggal_mulai', 'DESC')->findAll();

        $data = [
            'title' => 'Data Uang Saku',
            'period' => $period,
            'periods' => $periods,
            'allowances' => $allowances
        ];

        return view('allowance/index', $data);
    }

    // ========================================
    // ADMIN/HR: CALCULATE ALLOWANCES
    // ========================================
    public function calculate()
    {
        $periodId = $this->request->getPost('id_period');

        if (!$periodId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID periode tidak valid'
            ]);
        }

        $period = $this->periodModel->find($periodId);

        if (!$period) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Periode tidak ditemukan'
            ]);
        }

        if ($period['status'] !== 'draft') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Periode sudah dikalkulasi sebelumnya'
            ]);
        }

        try {
            $this->db->transStart();

            // Get rate per hari from settings (default 100000)
            $rateSetting = $this->settingModel->where('setting_key', 'allowance_rate_per_day')->first();
            $ratePerHari = $rateSetting ? (float) $rateSetting['setting_value'] : 100000;

            // Get all active interns
            $interns = $this->db->table('interns as i')
                ->select('i.id_user, u.nama_lengkap, u.nik')
                ->join('users as u', 'u.id_user = i.id_user')
                ->where('i.status_magang', 'active')
                ->get()
                ->getResultArray();

            if (empty($interns)) {
                throw new \Exception('Tidak ada pemagang aktif');
            }

            $totalPemagang = 0;
            $totalNominal = 0;

            foreach ($interns as $intern) {
                $userId = $intern['id_user'];

                // Check if already calculated
                if ($this->allowanceModel->existsForUserPeriod($userId, $periodId)) {
                    continue;
                }

                // Count working days (exclude weekends)
                $workingDays = $this->countWorkingDays($period['tanggal_mulai'], $period['tanggal_selesai']);

                // Get attendance summary
                $attendanceSummary = $this->db->table('attendances')
                    ->select("
                        SUM(CASE WHEN status IN ('hadir','terlambat') THEN 1 ELSE 0 END) as total_hadir,
                        SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as total_alpha,
                        SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as total_izin,
                        SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as total_sakit
                    ")
                    ->where('id_user', $userId)
                    ->where('tanggal >=', $period['tanggal_mulai'])
                    ->where('tanggal <=', $period['tanggal_selesai'])
                    ->get()
                    ->getRowArray();

                $totalHadir = (int) ($attendanceSummary['total_hadir'] ?? 0);
                $totalAlpha = (int) ($attendanceSummary['total_alpha'] ?? 0);
                $totalIzin = (int) ($attendanceSummary['total_izin'] ?? 0);
                $totalSakit = (int) ($attendanceSummary['total_sakit'] ?? 0);

                // Calculate total uang saku
                $totalUangSaku = $totalHadir * $ratePerHari;

                // Get bank info from user
                $userData = $this->userModel->find($userId);

                // Insert allowance
                $this->allowanceModel->insert([
                    'id_period' => $periodId,
                    'id_user' => $userId,
                    'total_hari_kerja' => $workingDays,
                    'total_hadir' => $totalHadir,
                    'total_alpha' => $totalAlpha,
                    'total_izin' => $totalIzin,
                    'total_sakit' => $totalSakit,
                    'rate_per_hari' => $ratePerHari,
                    'total_uang_saku' => $totalUangSaku,
                    'nomor_rekening' => $userData['nomor_rekening'] ?? null,
                    'nama_bank' => $userData['nama_bank'] ?? null,
                    'atas_nama' => $userData['atas_nama'] ?? $userData['nama_lengkap'] ?? null,
                    'status_pembayaran' => 'pending'
                ]);

                $totalPemagang++;
                $totalNominal += $totalUangSaku;
            }

            // Update period status
            $this->periodModel->update($periodId, [
                'status' => 'calculated',
                'total_pemagang' => $totalPemagang,
                'total_nominal' => $totalNominal,
                'calculated_at' => date('Y-m-d H:i:s'),
                'calculated_by' => session()->get('user_id')
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Berhasil menghitung uang saku untuk {$totalPemagang} pemagang. Total: Rp " . number_format($totalNominal, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghitung uang saku: ' . $e->getMessage()
            ]);
        }
    }

    // ========================================
    // FINANCE: PAYMENT PAGE
    // ========================================
    public function payment()
    {
        $allowances = $this->allowanceModel->getPendingPayments();

        $data = [
            'title' => 'Proses Pembayaran Uang Saku',
            'allowances' => $allowances
        ];

        return view('allowance/payment', $data);
    }

    // ========================================
    // FINANCE: PROCESS PAYMENT
    // ========================================
    public function processPayment($id)
    {
        $allowance = $this->allowanceModel->find($id);

        if (!$allowance) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data uang saku tidak ditemukan'
            ]);
        }

        if ($allowance['status_pembayaran'] !== 'pending') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pembayaran sudah diproses sebelumnya'
            ]);
        }

        $validation = \Config\Services::validation();
        $rules = [
            'tanggal_transfer' => 'required|valid_date',
            'bukti_transfer' => 'permit_empty|max_size[bukti_transfer,2048]|is_image[bukti_transfer]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $this->db->transStart();

            $data = [
                'status_pembayaran' => 'paid',
                'tanggal_transfer' => $this->request->getPost('tanggal_transfer'),
                'catatan' => $this->request->getPost('catatan')
            ];

            // Handle bukti transfer upload
            $bukti = $this->request->getFile('bukti_transfer');
            if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
                $newName = 'bukti_transfer_' . $id . '_' . time() . '.' . $bukti->getExtension();
                $uploadPath = WRITEPATH . 'uploads/bukti_transfer/';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $bukti->move($uploadPath, $newName);
                $data['bukti_transfer'] = $newName;
            }

            // Update allowance
            $this->allowanceModel->update($id, $data);

            // Generate slip
            $this->generateSlip($id);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data');
            }

            // Kirim notifikasi ke pemagang
            $period = $this->periodModel->find($allowance['id_period']);
            $periodName = $period ? $period['nama_periode'] : 'Periode';
            (new \App\Libraries\NotificationService())->allowancePaid(
                (int) $allowance['id_user'],
                $periodName,
                (float) ($allowance['total_uang_saku'] ?? 0)
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses dan slip telah dibuat'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    // ========================================
    // INTERN: DOWNLOAD SLIP
    // ========================================
    public function downloadSlip($idAllowance)
    {
        $userId = session()->get('user_id');

        // Verify ownership
        $allowance = $this->allowanceModel->find($idAllowance);
        if (!$allowance || $allowance['id_user'] != $userId) {
            return redirect()->to('/allowance/my')->with('error', 'Akses ditolak');
        }

        $slip = $this->slipModel->getSlipByAllowance($idAllowance);

        if (!$slip) {
            return redirect()->to('/allowance/my')->with('error', 'Slip belum tersedia');
        }

        $filePath = WRITEPATH . 'uploads/slips/' . $slip['file_path'];

        if (!file_exists($filePath)) {
            return redirect()->to('/allowance/my')->with('error', 'File slip tidak ditemukan');
        }

        return $this->response->download($filePath, null)->setFileName($slip['nomor_slip'] . '.html');
    }

    // ========================================
    // HELPER: COUNT WORKING DAYS
    // ========================================
    private function countWorkingDays($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end->modify('+1 day'); // Include end date

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($start, $interval, $end);

        $workingDays = 0;
        foreach ($dateRange as $date) {
            // Exclude Saturday (6) and Sunday (0)
            if ($date->format('N') < 6) {
                $workingDays++;
            }
        }

        return $workingDays;
    }

    // ========================================
    // HELPER: GENERATE SLIP PDF
    // ========================================
    private function generateSlip($idAllowance)
    {
        // Check if slip already exists
        $existingSlip = $this->slipModel->getSlipByAllowance($idAllowance);
        if ($existingSlip) {
            return $existingSlip['nomor_slip']; // Return existing slip number
        }

        // Get allowance data with user info
        $allowance = $this->db->table('allowances as a')
            ->select('a.*, u.nama_lengkap, u.nik, d.nama_divisi, ap.nama_periode, ap.tanggal_mulai, ap.tanggal_selesai')
            ->join('users u', 'u.id_user = a.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->join('allowance_periods ap', 'ap.id_period = a.id_period')
            ->where('a.id_allowance', $idAllowance)
            ->get()
            ->getRowArray();

        if (!$allowance) {
            throw new \Exception('Data uang saku tidak ditemukan');
        }

        // Generate slip number
        $nomorSlip = $this->slipModel->generateSlipNumber();

        // Create simple HTML slip
        $html = $this->generateSlipHTML($allowance, $nomorSlip);

        // Save as HTML for now (in production, use DOMPDF or similar)
        $fileName = 'slip_' . $idAllowance . '_' . time() . '.html';
        $uploadPath = WRITEPATH . 'uploads/slips/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        file_put_contents($uploadPath . $fileName, $html);

        // Save slip record
        $this->slipModel->insert([
            'id_allowance' => $idAllowance,
            'nomor_slip' => $nomorSlip,
            'file_path' => $fileName,
            'generated_at' => date('Y-m-d H:i:s'),
            'generated_by' => session()->get('user_id')
        ]);

        return $nomorSlip;
    }

    // ========================================
    // HELPER: GENERATE SLIP HTML
    // ========================================
    private function generateSlipHTML($allowance, $nomorSlip)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Slip Uang Saku - ' . $nomorSlip . '</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h2 { margin: 0; color: #333; }
                .header p { margin: 5px 0; color: #666; }
                .slip-number { text-align: right; margin-bottom: 20px; font-size: 12px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #f5f5f5; font-weight: bold; }
                .total-row { font-weight: bold; font-size: 16px; background-color: #f9f9f9; }
                .footer { margin-top: 50px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>PT BANK MUAMALAT INDONESIA TBK</h2>
                <p>SLIP PEMBAYARAN UANG SAKU PEMAGANG</p>
            </div>

            <div class="slip-number">
                Nomor Slip: <strong>' . $nomorSlip . '</strong><br>
                Tanggal Cetak: ' . date('d F Y H:i') . '
            </div>

            <table>
                <tr><th style="width: 200px;">Periode</th><td>' . $allowance['nama_periode'] . '</td></tr>
                <tr><th>Nama Lengkap</th><td>' . $allowance['nama_lengkap'] . '</td></tr>
                <tr><th>NIK</th><td>' . $allowance['nik'] . '</td></tr>
                <tr><th>Divisi</th><td>' . ($allowance['nama_divisi'] ?? '-') . '</td></tr>
            </table>

            <table>
                <tr>
                    <th style="width: 200px;">Keterangan</th>
                    <th style="width: 100px; text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Nominal</th>
                </tr>
                <tr>
                    <td>Total Hari Kerja</td>
                    <td style="text-align: center;">' . $allowance['total_hari_kerja'] . ' hari</td>
                    <td style="text-align: right;">-</td>
                </tr>
                <tr>
                    <td>Total Hadir</td>
                    <td style="text-align: center;">' . $allowance['total_hadir'] . ' hari</td>
                    <td style="text-align: right;">-</td>
                </tr>
                <tr>
                    <td>Total Alpha</td>
                    <td style="text-align: center;">' . $allowance['total_alpha'] . ' hari</td>
                    <td style="text-align: right;">-</td>
                </tr>
                <tr>
                    <td>Total Izin</td>
                    <td style="text-align: center;">' . $allowance['total_izin'] . ' hari</td>
                    <td style="text-align: right;">-</td>
                </tr>
                <tr>
                    <td>Total Sakit</td>
                    <td style="text-align: center;">' . $allowance['total_sakit'] . ' hari</td>
                    <td style="text-align: right;">-</td>
                </tr>
                <tr>
                    <td>Rate Per Hari</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: right;">Rp ' . number_format($allowance['rate_per_hari'], 0, ',', '.') . '</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2">TOTAL UANG SAKU</td>
                    <td style="text-align: right;">Rp ' . number_format($allowance['total_uang_saku'], 0, ',', '.') . '</td>
                </tr>
            </table>

            <table>
                <tr><th style="width: 200px;">Rekening Tujuan</th></tr>
                <tr><td><strong>' . ($allowance['nama_bank'] ?? '-') . '</strong></td></tr>
                <tr><td>' . ($allowance['nomor_rekening'] ?? '-') . '</td></tr>
                <tr><td>a/n ' . ($allowance['atas_nama'] ?? '-') . '</td></tr>
            </table>

            <table>
                <tr><th style="width: 200px;">Tanggal Transfer</th><td>' . ($allowance['tanggal_transfer'] ? date('d F Y', strtotime($allowance['tanggal_transfer'])) : '-') . '</td></tr>
                <tr><th>Catatan</th><td>' . ($allowance['catatan'] ?? '-') . '</td></tr>
            </table>

            <div class="footer">
                <p>Dokumen ini digenerate otomatis oleh sistem MIP (Muamalat Internship Program).</p>
                <p>Untuk informasi lebih lanjut, silakan hubungi HR Department.</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}
