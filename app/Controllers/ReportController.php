<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\DailyActivityModel;
use App\Models\WeeklyProjectModel;
use App\Models\KpiMonthlyResultModel;
use App\Models\KpiPeriodResultModel;
use App\Models\AllowanceModel;
use App\Models\AllowancePeriodModel;
use App\Models\UserModel;
use App\Models\InternModel;
use App\Models\DivisiModel;

class ReportController extends BaseController
{
    protected $attendanceModel;
    protected $activityModel;
    protected $projectModel;
    protected $kpiMonthlyModel;
    protected $kpiPeriodModel;
    protected $allowanceModel;
    protected $allowancePeriodModel;
    protected $userModel;
    protected $internModel;
    protected $divisiModel;
    protected $db;

    public function __construct()
    {
        $this->attendanceModel = new AttendanceModel();
        $this->activityModel = new DailyActivityModel();
        $this->projectModel = new WeeklyProjectModel();
        $this->kpiMonthlyModel = new KpiMonthlyResultModel();
        $this->kpiPeriodModel = new KpiPeriodResultModel();
        $this->allowanceModel = new AllowanceModel();
        $this->allowancePeriodModel = new AllowancePeriodModel();
        $this->userModel = new UserModel();
        $this->internModel = new InternModel();
        $this->divisiModel = new DivisiModel();
        $this->db = \Config\Database::connect();
    }

    // ========================================
    // LAPORAN ABSENSI
    // ========================================
    public function attendance()
    {
        $role = session()->get('role_code');
        $userId = session()->get('user_id');

        // Get filter parameters
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $divisiId = $this->request->getGet('divisi');
        $status = $this->request->getGet('status');

        // Build query
        $builder = $this->db->table('attendances a')
            ->select('a.*, u.nama_lengkap, u.nik, d.nama_divisi, i.universitas')
            ->join('users u', 'u.id_user = a.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->join('interns i', 'i.id_user = a.id_user', 'left')
            ->where('MONTH(a.tanggal)', $bulan)
            ->where('YEAR(a.tanggal)', $tahun);

        // Mentor can only see their mentees
        if ($role === 'mentor') {
            $builder->where('i.id_mentor', $userId);
        }

        // Apply filters
        if ($divisiId) {
            $builder->where('u.id_divisi', $divisiId);
        }
        if ($status) {
            $builder->where('a.status', $status);
        }

        $attendances = $builder->orderBy('a.tanggal', 'DESC')
            ->orderBy('u.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        // Calculate summary statistics
        $summary = $this->calculateAttendanceSummary($attendances);

        // Get divisions for filter
        $divisions = $this->divisiModel->where('is_active', 1)->findAll();

        // Get available months
        $availableMonths = $this->getAvailableAttendanceMonths();

        $data = [
            'title' => 'Laporan Absensi',
            'attendances' => $attendances,
            'summary' => $summary,
            'divisions' => $divisions,
            'availableMonths' => $availableMonths,
            'filters' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'divisi' => $divisiId,
                'status' => $status
            ]
        ];

        return view('report/attendance', $data);
    }

    /**
     * Calculate attendance summary statistics
     */
    private function calculateAttendanceSummary($attendances)
    {
        $summary = [
            'total' => count($attendances),
            'hadir' => 0,
            'terlambat' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpha' => 0,
            'cuti' => 0,
            'persentase_kehadiran' => 0
        ];

        foreach ($attendances as $att) {
            if (isset($summary[$att['status']])) {
                $summary[$att['status']]++;
            }
        }

        // Calculate attendance percentage
        if ($summary['total'] > 0) {
            $present = $summary['hadir'] + $summary['terlambat'];
            $summary['persentase_kehadiran'] = round(($present / $summary['total']) * 100, 1);
        }

        return $summary;
    }

    /**
     * Get available attendance months
     */
    private function getAvailableAttendanceMonths()
    {
        return $this->db->table('attendances')
            ->select('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun')
            ->groupBy('MONTH(tanggal), YEAR(tanggal)')
            ->orderBy('tahun', 'DESC')
            ->orderBy('bulan', 'DESC')
            ->get()
            ->getResultArray();
    }

    // ========================================
    // LAPORAN AKTIVITAS
    // ========================================
    public function activity()
    {
        $role = session()->get('role_code');
        $userId = session()->get('user_id');

        // Get filter parameters
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $divisiId = $this->request->getGet('divisi');
        $kategori = $this->request->getGet('kategori');
        $statusApproval = $this->request->getGet('status_approval');

        // Build query
        $builder = $this->db->table('daily_activities da')
            ->select('da.*, u.nama_lengkap, u.nik, d.nama_divisi, 
                     m.nama_lengkap as mentor_name')
            ->join('users u', 'u.id_user = da.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->join('interns i', 'i.id_user = da.id_user', 'left')
            ->join('users m', 'm.id_user = i.id_mentor', 'left')
            ->where('MONTH(da.tanggal)', $bulan)
            ->where('YEAR(da.tanggal)', $tahun);

        // Mentor can only see their mentees
        if ($role === 'mentor') {
            $builder->where('i.id_mentor', $userId);
        }

        // Apply filters
        if ($divisiId) {
            $builder->where('u.id_divisi', $divisiId);
        }
        if ($kategori) {
            $builder->where('da.kategori', $kategori);
        }
        if ($statusApproval) {
            $builder->where('da.status_approval', $statusApproval);
        }

        $activities = $builder->orderBy('da.tanggal', 'DESC')
            ->orderBy('u.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        // Calculate summary statistics
        $summary = $this->calculateActivitySummary($activities);

        // Get divisions for filter
        $divisions = $this->divisiModel->where('is_active', 1)->findAll();

        // Get available months
        $availableMonths = $this->getAvailableActivityMonths();

        $data = [
            'title' => 'Laporan Aktivitas',
            'activities' => $activities,
            'summary' => $summary,
            'divisions' => $divisions,
            'availableMonths' => $availableMonths,
            'filters' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'divisi' => $divisiId,
                'kategori' => $kategori,
                'status_approval' => $statusApproval
            ]
        ];

        return view('report/activity', $data);
    }

    /**
     * Calculate activity summary statistics
     */
    private function calculateActivitySummary($activities)
    {
        $summary = [
            'total' => count($activities),
            'approved' => 0,
            'pending' => 0,
            'rejected' => 0,
            'draft' => 0,
            'by_kategori' => [
                'learning' => 0,
                'task' => 0,
                'meeting' => 0,
                'training' => 0,
                'other' => 0
            ],
            'total_jam' => 0
        ];

        foreach ($activities as $act) {
            // Count by status
            if ($act['status_approval'] === 'approved') {
                $summary['approved']++;
            } elseif ($act['status_approval'] === 'submitted') {
                $summary['pending']++;
            } elseif ($act['status_approval'] === 'rejected') {
                $summary['rejected']++;
            } else {
                $summary['draft']++;
            }

            // Count by kategori
            if (isset($summary['by_kategori'][$act['kategori']])) {
                $summary['by_kategori'][$act['kategori']]++;
            }

            // Calculate total hours
            if (!empty($act['jam_mulai']) && !empty($act['jam_selesai'])) {
                $start = strtotime($act['jam_mulai']);
                $end = strtotime($act['jam_selesai']);
                $summary['total_jam'] += ($end - $start) / 3600;
            }
        }

        $summary['total_jam'] = round($summary['total_jam'], 1);

        return $summary;
    }

    /**
     * Get available activity months
     */
    private function getAvailableActivityMonths()
    {
        return $this->db->table('daily_activities')
            ->select('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun')
            ->groupBy('MONTH(tanggal), YEAR(tanggal)')
            ->orderBy('tahun', 'DESC')
            ->orderBy('bulan', 'DESC')
            ->get()
            ->getResultArray();
    }

    // ========================================
    // LAPORAN KPI
    // ========================================
    public function kpi()
    {
        $role = session()->get('role_code');
        $userId = session()->get('user_id');

        // Get filter parameters
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $divisiId = $this->request->getGet('divisi');
        $kategori = $this->request->getGet('kategori');

        // Build query for monthly KPI
        $builder = $this->db->table('kpi_monthly_results kmr')
            ->select('kmr.*, u.nama_lengkap, u.nik, u.foto, d.nama_divisi, i.universitas')
            ->join('users u', 'u.id_user = kmr.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->join('interns i', 'i.id_user = kmr.id_user', 'left')
            ->where('kmr.bulan', $bulan)
            ->where('kmr.tahun', $tahun);

        // Mentor can only see their mentees
        if ($role === 'mentor') {
            $builder->where('i.id_mentor', $userId);
        }

        // Apply filters
        if ($divisiId) {
            $builder->where('u.id_divisi', $divisiId);
        }
        if ($kategori) {
            $builder->where('kmr.kategori_performa', $kategori);
        }

        $kpiResults = $builder->orderBy('kmr.rank_bulan_ini', 'ASC')
            ->get()
            ->getResultArray();

        // Calculate summary statistics
        $summary = $this->calculateKpiSummary($kpiResults);

        // Get period results for comparison
        $periodResults = $this->kpiPeriodModel->getPeriodRanking();

        // Get divisions for filter
        $divisions = $this->divisiModel->where('is_active', 1)->findAll();

        // Get available months
        $availableMonths = $this->kpiMonthlyModel->getAvailableMonths();

        $data = [
            'title' => 'Laporan KPI',
            'kpiResults' => $kpiResults,
            'periodResults' => $periodResults,
            'summary' => $summary,
            'divisions' => $divisions,
            'availableMonths' => $availableMonths,
            'filters' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'divisi' => $divisiId,
                'kategori' => $kategori
            ]
        ];

        return view('report/kpi', $data);
    }

    /**
     * Calculate KPI summary statistics
     */
    private function calculateKpiSummary($kpiResults)
    {
        $summary = [
            'total' => count($kpiResults),
            'avg_score' => 0,
            'max_score' => 0,
            'min_score' => 0,
            'by_kategori' => [
                'excellent' => 0,
                'good' => 0,
                'average' => 0,
                'below_average' => 0,
                'poor' => 0
            ]
        ];

        if (empty($kpiResults)) {
            return $summary;
        }

        $scores = array_column($kpiResults, 'total_score');
        $summary['avg_score'] = round(array_sum($scores) / count($scores), 2);
        $summary['max_score'] = max($scores);
        $summary['min_score'] = min($scores);

        foreach ($kpiResults as $kpi) {
            $kategori = $kpi['kategori_performa'] ?? 'average';
            if (isset($summary['by_kategori'][$kategori])) {
                $summary['by_kategori'][$kategori]++;
            }
        }

        return $summary;
    }

    // ========================================
    // LAPORAN UANG SAKU / KEUANGAN
    // ========================================
    public function allowance()
    {
        $role = session()->get('role_code');

        // Get filter parameters
        $periodId = $this->request->getGet('period');
        $divisiId = $this->request->getGet('divisi');
        $statusPembayaran = $this->request->getGet('status');

        // Get periods for filter
        $periods = $this->allowancePeriodModel->orderBy('tanggal_mulai', 'DESC')->findAll();

        // Default to latest period if not specified
        if (!$periodId && !empty($periods)) {
            $periodId = $periods[0]['id_period'];
        }

        // Build query
        $allowances = [];
        if ($periodId) {
            $builder = $this->db->table('allowances al')
                ->select('al.*, u.nama_lengkap, u.nik, d.nama_divisi, i.universitas,
                         ap.nama_periode, ap.tanggal_mulai, ap.tanggal_selesai')
                ->join('users u', 'u.id_user = al.id_user')
                ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
                ->join('interns i', 'i.id_user = al.id_user', 'left')
                ->join('allowance_periods ap', 'ap.id_period = al.id_period')
                ->where('al.id_period', $periodId);

            // Apply filters
            if ($divisiId) {
                $builder->where('u.id_divisi', $divisiId);
            }
            if ($statusPembayaran) {
                $builder->where('al.status_pembayaran', $statusPembayaran);
            }

            $allowances = $builder->orderBy('u.nama_lengkap', 'ASC')
                ->get()
                ->getResultArray();
        }

        // Calculate summary statistics
        $summary = $this->calculateAllowanceSummary($allowances);

        // Get divisions for filter
        $divisions = $this->divisiModel->where('is_active', 1)->findAll();

        $data = [
            'title' => 'Laporan Keuangan',
            'allowances' => $allowances,
            'summary' => $summary,
            'periods' => $periods,
            'divisions' => $divisions,
            'filters' => [
                'period' => $periodId,
                'divisi' => $divisiId,
                'status' => $statusPembayaran
            ]
        ];

        return view('report/allowance', $data);
    }

    /**
     * Calculate allowance summary statistics
     */
    private function calculateAllowanceSummary($allowances)
    {
        $summary = [
            'total_intern' => count($allowances),
            'total_uang_saku' => 0,
            'total_dibayar' => 0,
            'total_pending' => 0,
            'by_status' => [
                'pending' => 0,
                'calculated' => 0,
                'approved' => 0,
                'paid' => 0
            ]
        ];

        foreach ($allowances as $al) {
            $summary['total_uang_saku'] += (float)($al['total_uang_saku'] ?? 0);

            if ($al['status_pembayaran'] === 'paid') {
                $summary['total_dibayar'] += (float)($al['total_uang_saku'] ?? 0);
            } else {
                $summary['total_pending'] += (float)($al['total_uang_saku'] ?? 0);
            }

            $status = $al['status_pembayaran'] ?? 'pending';
            if (isset($summary['by_status'][$status])) {
                $summary['by_status'][$status]++;
            }
        }

        return $summary;
    }

    // ========================================
    // EXPORT REPORT
    // ========================================
    public function export()
    {
        $type = $this->request->getPost('type');
        $format = $this->request->getPost('format') ?? 'excel';
        $filters = $this->request->getPost('filters') ?? [];

        try {
            switch ($type) {
                case 'attendance':
                    return $this->exportAttendance($filters, $format);
                case 'activity':
                    return $this->exportActivity($filters, $format);
                case 'kpi':
                    return $this->exportKpi($filters, $format);
                case 'allowance':
                    return $this->exportAllowance($filters, $format);
                default:
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Tipe laporan tidak valid'
                    ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal export: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export attendance report to CSV
     */
    private function exportAttendance($filters, $format)
    {
        $bulan = $filters['bulan'] ?? date('m');
        $tahun = $filters['tahun'] ?? date('Y');
        $role = session()->get('role_code');
        $userId = session()->get('user_id');

        $builder = $this->db->table('attendances a')
            ->select('a.tanggal, u.nama_lengkap, u.nik, d.nama_divisi, 
                     a.jam_masuk, a.jam_keluar, a.status, a.keterangan')
            ->join('users u', 'u.id_user = a.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->join('interns i', 'i.id_user = a.id_user', 'left')
            ->where('MONTH(a.tanggal)', $bulan)
            ->where('YEAR(a.tanggal)', $tahun);

        if ($role === 'mentor') {
            $builder->where('i.id_mentor', $userId);
        }
        if (!empty($filters['divisi'])) {
            $builder->where('u.id_divisi', $filters['divisi']);
        }

        $data = $builder->orderBy('a.tanggal', 'ASC')
            ->orderBy('u.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        $namaBulan = $this->getNamaBulan($bulan);
        $filename = "Laporan_Absensi_{$namaBulan}_{$tahun}.csv";

        return $this->generateCsvResponse($data, $filename, [
            'Tanggal',
            'Nama',
            'NIK',
            'Divisi',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Keterangan'
        ]);
    }

    /**
     * Export activity report to CSV
     */
    private function exportActivity($filters, $format)
    {
        $bulan = $filters['bulan'] ?? date('m');
        $tahun = $filters['tahun'] ?? date('Y');
        $role = session()->get('role_code');
        $userId = session()->get('user_id');

        $builder = $this->db->table('daily_activities da')
            ->select('da.tanggal, u.nama_lengkap, u.nik, d.nama_divisi, 
                     da.judul_aktivitas, da.kategori, da.jam_mulai, da.jam_selesai, 
                     da.status_approval')
            ->join('users u', 'u.id_user = da.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->join('interns i', 'i.id_user = da.id_user', 'left')
            ->where('MONTH(da.tanggal)', $bulan)
            ->where('YEAR(da.tanggal)', $tahun);

        if ($role === 'mentor') {
            $builder->where('i.id_mentor', $userId);
        }
        if (!empty($filters['divisi'])) {
            $builder->where('u.id_divisi', $filters['divisi']);
        }

        $data = $builder->orderBy('da.tanggal', 'ASC')
            ->orderBy('u.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        $namaBulan = $this->getNamaBulan($bulan);
        $filename = "Laporan_Aktivitas_{$namaBulan}_{$tahun}.csv";

        return $this->generateCsvResponse($data, $filename, [
            'Tanggal',
            'Nama',
            'NIK',
            'Divisi',
            'Judul Aktivitas',
            'Kategori',
            'Jam Mulai',
            'Jam Selesai',
            'Status'
        ]);
    }

    /**
     * Export KPI report to CSV
     */
    private function exportKpi($filters, $format)
    {
        $bulan = $filters['bulan'] ?? date('m');
        $tahun = $filters['tahun'] ?? date('Y');
        $role = session()->get('role_code');
        $userId = session()->get('user_id');

        $builder = $this->db->table('kpi_monthly_results kmr')
            ->select('u.nama_lengkap, u.nik, d.nama_divisi, 
                     kmr.total_score, kmr.rank_bulan_ini, kmr.kategori_performa')
            ->join('users u', 'u.id_user = kmr.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->join('interns i', 'i.id_user = kmr.id_user', 'left')
            ->where('kmr.bulan', $bulan)
            ->where('kmr.tahun', $tahun);

        if ($role === 'mentor') {
            $builder->where('i.id_mentor', $userId);
        }
        if (!empty($filters['divisi'])) {
            $builder->where('u.id_divisi', $filters['divisi']);
        }

        $data = $builder->orderBy('kmr.rank_bulan_ini', 'ASC')
            ->get()
            ->getResultArray();

        $namaBulan = $this->getNamaBulan($bulan);
        $filename = "Laporan_KPI_{$namaBulan}_{$tahun}.csv";

        return $this->generateCsvResponse($data, $filename, [
            'Nama',
            'NIK',
            'Divisi',
            'Total Score',
            'Ranking',
            'Kategori Performa'
        ]);
    }

    /**
     * Export allowance report to CSV
     */
    private function exportAllowance($filters, $format)
    {
        $periodId = $filters['period'] ?? null;

        if (!$periodId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Periode tidak dipilih'
            ]);
        }

        $builder = $this->db->table('allowances al')
            ->select('u.nama_lengkap, u.nik, d.nama_divisi, 
                     al.total_hari_kerja, al.total_hadir, al.total_alpha,
                     al.rate_per_hari, al.total_uang_saku, al.status_pembayaran,
                     al.tanggal_transfer')
            ->join('users u', 'u.id_user = al.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('al.id_period', $periodId);

        if (!empty($filters['divisi'])) {
            $builder->where('u.id_divisi', $filters['divisi']);
        }

        $data = $builder->orderBy('u.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        $period = $this->allowancePeriodModel->find($periodId);
        $periodName = str_replace(' ', '_', $period['nama_periode'] ?? 'Periode');
        $filename = "Laporan_Keuangan_{$periodName}.csv";

        return $this->generateCsvResponse($data, $filename, [
            'Nama',
            'NIK',
            'Divisi',
            'Hari Kerja',
            'Hadir',
            'Alpha',
            'Rate/Hari',
            'Total Uang Saku',
            'Status',
            'Tanggal Transfer'
        ]);
    }

    /**
     * Generate CSV response
     */
    private function generateCsvResponse($data, $filename, $headers)
    {
        $output = fopen('php://temp', 'r+');

        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Write headers
        fputcsv($output, $headers);

        // Write data
        foreach ($data as $row) {
            fputcsv($output, array_values($row));
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    /**
     * Get month name in Indonesian
     */
    private function getNamaBulan($bulan)
    {
        $bulanNames = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
        ];
        return $bulanNames[$bulan] ?? $bulan;
    }
}
