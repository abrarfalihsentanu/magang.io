<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LeaveModel;
use App\Models\AttendanceModel;
use App\Models\SettingModel;
use CodeIgniter\HTTP\ResponseInterface;

class LeaveController extends BaseController
{
    protected $leaveModel;
    protected $attendanceModel;
    protected $settingModel;
    protected $db;

    public function __construct()
    {
        $this->leaveModel = new LeaveModel();
        $this->attendanceModel = new AttendanceModel();
        $this->settingModel = new SettingModel();
        $this->db = \Config\Database::connect();
        helper(['form', 'filesystem']);

        date_default_timezone_set('Asia/Jakarta');
    }

    private function getCurrentDateTime($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    // ========================================
    // MY LEAVES (INTERN VIEW)
    // ========================================
    public function my()
    {
        $userId = session()->get('user_id');

        $leaves = $this->db->table('leaves as l')
            ->select('l.*, approver.nama_lengkap as approver_name')
            ->join('users as approver', 'approver.id_user = l.approved_by', 'left')
            ->where('l.id_user', $userId)
            ->orderBy('l.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $stats = [
            'pending' => count(array_filter($leaves, fn($l) => $l['status_approval'] === 'pending')),
            'approved' => count(array_filter($leaves, fn($l) => $l['status_approval'] === 'approved')),
            'rejected' => count(array_filter($leaves, fn($l) => $l['status_approval'] === 'rejected')),
            'total_days' => array_sum(array_column(array_filter($leaves, fn($l) => $l['status_approval'] === 'approved'), 'jumlah_hari'))
        ];

        $data = [
            'title' => 'Cuti/Izin/Sakit Saya',
            'leaves' => $leaves,
            'stats' => $stats
        ];

        return view('leave/my', $data);
    }

    // ========================================
    // CREATE LEAVE PAGE
    // ========================================
    public function create()
    {
        return view('leave/create', ['title' => 'Ajukan Cuti/Izin/Sakit']);
    }

    // ========================================
    // SUBMIT LEAVE (AJAX)
    // ========================================
    public function submit()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $userId = session()->get('user_id');

        $rules = [
            'jenis_cuti' => 'required|in_list[cuti,izin,sakit]',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'alasan' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $jenisCuti = $this->request->getPost('jenis_cuti');
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalSelesai = $this->request->getPost('tanggal_selesai');

        if (strtotime($tanggalSelesai) < strtotime($tanggalMulai)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tanggal selesai harus >= tanggal mulai'
            ]);
        }

        $today = date('Y-m-d');
        if ($tanggalMulai < $today) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak bisa mengajukan untuk tanggal yang sudah lewat'
            ]);
        }

        $start = new \DateTime($tanggalMulai);
        $end = new \DateTime($tanggalSelesai);
        $diff = $start->diff($end);
        $jumlahHari = $diff->days + 1;

        $maxIzinDays = 3;
        $maxIzinSetting = $this->settingModel->getByKey('max_izin_days');
        if ($maxIzinSetting) {
            $maxIzinDays = (int) $maxIzinSetting['setting_value'];
        }

        if ($jenisCuti === 'izin' && $jumlahHari > $maxIzinDays) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Izin maksimal $maxIzinDays hari berturut-turut"
            ]);
        }

        if ($jenisCuti === 'cuti') {
            $todayDate = new \DateTime($today);
            $diffDays = $todayDate->diff($start)->days;
            if ($diffDays < 3) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cuti harus diajukan minimal H-3'
                ]);
            }
        }

        $dokumenName = null;
        $dokumen = $this->request->getFile('dokumen_pendukung');

        if ($dokumen && $dokumen->isValid() && !$dokumen->hasMoved()) {
            if ($jenisCuti === 'sakit' && !$dokumen->isValid()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Surat keterangan dokter wajib dilampirkan untuk sakit'
                ]);
            }

            $dokumenName = 'leave_' . $userId . '_' . time() . '.' . $dokumen->getExtension();
            $dokumen->move(WRITEPATH . 'uploads/leaves', $dokumenName);
        } elseif ($jenisCuti === 'sakit') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Surat keterangan dokter wajib untuk pengajuan sakit'
            ]);
        }

        $data = [
            'id_user' => $userId,
            'jenis_cuti' => $jenisCuti,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'jumlah_hari' => $jumlahHari,
            'alasan' => $this->request->getPost('alasan'),
            'dokumen_pendukung' => $dokumenName,
            'status_approval' => 'pending'
        ];

        if (!$this->leaveModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan pengajuan'
            ]);
        }

        // Kirim notifikasi ke mentor dan HR
        $internData = $this->db->table('interns')->where('id_user', $userId)->get()->getRowArray();
        $approverIds = [];
        if ($internData && !empty($internData['id_mentor'])) {
            $approverIds[] = (int) $internData['id_mentor'];
        }
        $hrUsers = $this->db->table('users u')
            ->select('u.id_user')
            ->join('roles r', 'r.id_role = u.id_role')
            ->where('r.kode_role', 'hr')
            ->where('u.status !=', 'archived')
            ->get()->getResultArray();
        foreach ($hrUsers as $hr) {
            if (!in_array((int) $hr['id_user'], $approverIds)) {
                $approverIds[] = (int) $hr['id_user'];
            }
        }
        (new \App\Libraries\NotificationService())->leaveSubmitted(
            $approverIds,
            session()->get('nama_lengkap') ?? 'Pemagang',
            $jenisCuti,
            $tanggalMulai
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pengajuan berhasil disubmit'
        ]);
    }

    // ========================================
    // APPROVAL PAGE (ADMIN/HR/MENTOR)
    // ✅ FIXED: Mentor tetap bisa akses walaupun kosong
    // ========================================
    public function approval()
    {
        $role = session()->get('kode_role');
        $userId = session()->get('user_id');

        $builder = $this->db->table('leaves as l')
            ->select('l.*, u.nama_lengkap, u.nik')
            ->join('users as u', 'u.id_user = l.id_user')
            ->where('l.status_approval', 'pending');

        // ✅ MENTOR: Hanya lihat mentee yang ia mentori
        if ($role === 'mentor') {
            $builder->join('interns as i', 'i.id_user = l.id_user')
                ->where('i.id_mentor', $userId);
        }

        $leaves = $builder->orderBy('l.created_at', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Approval Cuti/Izin/Sakit',
            'leaves' => $leaves // ✅ Bisa kosong, tidak masalah
        ];

        return view('leave/approval', $data);
    }

    // ========================================
    // APPROVE LEAVE (AJAX)
    // ✅ FIXED: Validasi mentor-mentee
    // ========================================
    public function approve($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $leave = $this->leaveModel->find($id);

        if (!$leave) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // ✅ VALIDASI: Mentor hanya bisa approve mentee-nya
        $role = session()->get('kode_role');
        $currentUserId = session()->get('user_id');

        if ($role === 'mentor') {
            $isMentee = $this->db->table('interns')
                ->where('id_user', $leave['id_user'])
                ->where('id_mentor', $currentUserId)
                ->countAllResults() > 0;

            if (!$isMentee) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk approve pengajuan ini'
                ]);
            }
        }

        $this->db->transStart();

        try {
            $this->leaveModel->update($id, [
                'status_approval' => 'approved',
                'approved_by' => $currentUserId,
                'approved_at' => $this->getCurrentDateTime(),
                'catatan_approval' => $this->request->getPost('catatan')
            ]);

            $startDate = new \DateTime($leave['tanggal_mulai']);
            $endDate = new \DateTime($leave['tanggal_selesai']);
            $endDate->modify('+1 day');

            $interval = new \DateInterval('P1D');
            $dateRange = new \DatePeriod($startDate, $interval, $endDate);

            foreach ($dateRange as $date) {
                $tanggal = $date->format('Y-m-d');

                $existing = $this->attendanceModel
                    ->where('id_user', $leave['id_user'])
                    ->where('tanggal', $tanggal)
                    ->first();

                if (!$existing) {
                    $this->attendanceModel->insert([
                        'id_user' => $leave['id_user'],
                        'tanggal' => $tanggal,
                        'status' => $leave['jenis_cuti'],
                        'keterangan' => 'Approved leave request #' . $id,
                        'is_manual' => 1
                    ]);
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi gagal');
            }

            // Kirim notifikasi ke pemagang
            (new \App\Libraries\NotificationService())->leaveApproved(
                (int) $leave['id_user'],
                $leave['jenis_cuti'],
                $leave['tanggal_mulai']
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Permohonan berhasil disetujui dan attendance records telah dibuat'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // ========================================
    // REJECT LEAVE (AJAX)
    // ✅ FIXED: Validasi mentor-mentee
    // ========================================
    public function reject($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $leave = $this->leaveModel->find($id);

        if (!$leave) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // ✅ VALIDASI: Mentor hanya bisa reject mentee-nya
        $role = session()->get('kode_role');
        $currentUserId = session()->get('user_id');

        if ($role === 'mentor') {
            $isMentee = $this->db->table('interns')
                ->where('id_user', $leave['id_user'])
                ->where('id_mentor', $currentUserId)
                ->countAllResults() > 0;

            if (!$isMentee) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk reject pengajuan ini'
                ]);
            }
        }

        $catatan = $this->request->getPost('catatan');

        if (empty($catatan)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Alasan penolakan wajib diisi'
            ]);
        }

        $this->leaveModel->update($id, [
            'status_approval' => 'rejected',
            'approved_by' => $currentUserId,
            'approved_at' => $this->getCurrentDateTime(),
            'catatan_approval' => $catatan
        ]);

        // Kirim notifikasi ke pemagang
        (new \App\Libraries\NotificationService())->leaveRejected(
            (int) $leave['id_user'],
            $leave['jenis_cuti'],
            $catatan
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Permohonan berhasil ditolak'
        ]);
    }
}
