<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KpiIndicatorModel;
use App\Models\KpiAssessmentModel;
use App\Models\KpiMonthlyResultModel;
use App\Models\AttendanceModel;
use App\Models\DailyActivityModel;
use App\Models\WeeklyProjectModel;
use App\Models\InternModel;

class KpiCalculationController extends BaseController
{
    protected $indicatorModel;
    protected $assessmentModel;
    protected $monthlyModel;
    protected $attendanceModel;
    protected $activityModel;
    protected $projectModel;
    protected $internModel;

    public function __construct()
    {
        $this->indicatorModel  = new KpiIndicatorModel();
        $this->assessmentModel = new KpiAssessmentModel();
        $this->monthlyModel    = new KpiMonthlyResultModel();
        $this->attendanceModel = new AttendanceModel();
        $this->activityModel   = new DailyActivityModel();
        $this->projectModel    = new WeeklyProjectModel();
        $this->internModel     = new InternModel();
        helper(['form']);
    }

    /**
     * KPI Calculation Dashboard
     */
    public function index()
    {
        $bulan = (int)($this->request->getGet('bulan') ?: date('n'));
        $tahun = (int)($this->request->getGet('tahun') ?: date('Y'));

        // Get active indicators
        $indicators = $this->indicatorModel->getActiveIndicators();

        // Get active interns
        $interns = $this->getActiveInterns();

        // Check existing calculations for this month
        $existingResults = $this->monthlyModel->where(['bulan' => $bulan, 'tahun' => $tahun])->findAll();
        $isCalculated = !empty($existingResults);
        $isFinalized = $this->monthlyModel->isMonthFinalized($bulan, $tahun);

        // Get assessment overview if calculated
        $assessmentOverview = [];
        if ($isCalculated) {
            $assessmentOverview = $this->assessmentModel->getMonthlyOverview($bulan, $tahun);
        }

        // Check manual assessments status
        $manualIndicators = $this->indicatorModel->getManualIndicators();
        $manualPendingCount = 0;
        if (!empty($manualIndicators)) {
            foreach ($interns as $intern) {
                if (!$this->assessmentModel->hasManualAssessments($intern['id_user'], $bulan, $tahun)) {
                    $manualPendingCount++;
                }
            }
        }

        $data = [
            'title'              => 'Perhitungan KPI',
            'bulan'              => $bulan,
            'tahun'              => $tahun,
            'indicators'         => $indicators,
            'interns'            => $interns,
            'isCalculated'       => $isCalculated,
            'isFinalized'        => $isFinalized,
            'assessmentOverview' => $assessmentOverview,
            'manualPendingCount' => $manualPendingCount,
            'totalInterns'       => count($interns),
            'stats'              => $this->monthlyModel->getMonthlyStats($bulan, $tahun),
        ];

        return view('admin/kpi/calculation/index', $data);
    }

    /**
     * Execute KPI calculation for all interns in a month
     */
    public function calculate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $bulan = (int)$this->request->getPost('bulan');
        $tahun = (int)$this->request->getPost('tahun');

        if (!$bulan || !$tahun) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bulan dan tahun wajib diisi'
            ]);
        }

        // Check if already finalized
        if ($this->monthlyModel->isMonthFinalized($bulan, $tahun)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data bulan ini sudah di-finalize, tidak bisa dihitung ulang'
            ]);
        }

        $indicators = $this->indicatorModel->getActiveIndicators();
        $interns = $this->getActiveInterns();

        if (empty($indicators) || empty($interns)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak ada indicator aktif atau intern aktif'
            ]);
        }

        $successCount = 0;
        $errorCount   = 0;
        $errors       = [];

        foreach ($interns as $intern) {
            try {
                $totalScore = $this->calculateInternKpi($intern['id_user'], $indicators, $bulan, $tahun);

                // Upsert monthly result
                $this->monthlyModel->upsertResult([
                    'id_user'     => $intern['id_user'],
                    'bulan'       => $bulan,
                    'tahun'       => $tahun,
                    'total_score' => $totalScore,
                    'is_finalized' => 0
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = $intern['nama_lengkap'] . ': ' . $e->getMessage();
            }
        }

        // Calculate rankings
        $this->monthlyModel->calculateRankings($bulan, $tahun);

        return $this->response->setJSON([
            'success' => true,
            'message' => "Perhitungan KPI selesai. Berhasil: {$successCount}, Gagal: {$errorCount}",
            'data' => [
                'success_count' => $successCount,
                'error_count'   => $errorCount,
                'errors'        => $errors
            ]
        ]);
    }

    /**
     * Recalculate KPI for a single user
     */
    public function recalculate($userId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $bulan = (int)$this->request->getPost('bulan');
        $tahun = (int)$this->request->getPost('tahun');

        if ($this->monthlyModel->isMonthFinalized($bulan, $tahun)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data bulan ini sudah di-finalize'
            ]);
        }

        $indicators = $this->indicatorModel->getActiveIndicators();

        try {
            $totalScore = $this->calculateInternKpi($userId, $indicators, $bulan, $tahun);

            $this->monthlyModel->upsertResult([
                'id_user'     => $userId,
                'bulan'       => $bulan,
                'tahun'       => $tahun,
                'total_score' => $totalScore,
                'is_finalized' => 0
            ]);

            // Recalculate rankings
            $this->monthlyModel->calculateRankings($bulan, $tahun);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'KPI berhasil dihitung ulang',
                'data'    => ['total_score' => $totalScore]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghitung: ' . $e->getMessage()
            ]);
        }
    }

    // ========================================================
    // PRIVATE CALCULATION ENGINE
    // ========================================================

    /**
     * Calculate all KPI indicators for a single intern in a month
     */
    private function calculateInternKpi($userId, $indicators, $bulan, $tahun)
    {
        $totalWeightedScore = 0;

        foreach ($indicators as $indicator) {
            $nilaiRaw = 0;

            if ($indicator['is_auto_calculate']) {
                $nilaiRaw = $this->calculateAutoIndicator($indicator, $userId, $bulan, $tahun);
            } else {
                // Manual indicator - check if already assessed by mentor
                $existing = $this->assessmentModel->getOrCreate(
                    $userId,
                    $indicator['id_indicator'],
                    $bulan,
                    $tahun
                );
                if ($existing) {
                    $nilaiRaw = (float)$existing['nilai_raw'];
                }
                // If no manual assessment, nilai_raw stays 0
            }

            // Cap at 0-100
            $nilaiRaw = max(0, min(100, round($nilaiRaw, 2)));

            // Calculate weighted score: (nilai_raw / 100) * bobot
            $nilaiWeighted = round(($nilaiRaw / 100) * $indicator['bobot'], 2);

            // Upsert assessment record
            $this->assessmentModel->upsertAssessment([
                'id_user'        => $userId,
                'id_indicator'   => $indicator['id_indicator'],
                'bulan'          => $bulan,
                'tahun'          => $tahun,
                'nilai_raw'      => $nilaiRaw,
                'nilai_weighted' => $nilaiWeighted,
                'penilai_id'     => $indicator['is_auto_calculate'] ? null : null, // Keep mentor's ID if manual
            ]);

            $totalWeightedScore += $nilaiWeighted;
        }

        return round($totalWeightedScore, 2);
    }

    /**
     * Auto-calculate a specific indicator based on its formula
     */
    private function calculateAutoIndicator($indicator, $userId, $bulan, $tahun)
    {
        $nama = strtolower($indicator['nama_indicator']);

        // Determine which calculation based on indicator name/formula
        if (strpos($nama, 'persentase kehadiran') !== false) {
            return $this->calcPersentaseKehadiran($userId, $bulan, $tahun);
        }
        if (strpos($nama, 'ketepatan waktu') !== false) {
            return $this->calcKetepatanWaktu($userId, $bulan, $tahun);
        }
        if (strpos($nama, 'konsistensi') !== false && strpos($nama, 'logbook') !== false) {
            return $this->calcKonsistensiLogbook($userId, $bulan, $tahun);
        }
        if (strpos($nama, 'approval rate') !== false) {
            return $this->calcApprovalRate($userId, $bulan, $tahun);
        }
        if (strpos($nama, 'jumlah project') !== false || strpos($nama, 'project completed') !== false) {
            return $this->calcProjectCompleted($userId, $bulan, $tahun);
        }
        if (strpos($nama, 'kualitas') !== false && strpos($nama, 'project') !== false) {
            return $this->calcKualitasProject($userId, $bulan, $tahun);
        }
        if (strpos($nama, 'inisiatif') !== false) {
            return $this->calcInisiatifProject($userId, $bulan, $tahun);
        }

        return 0; // Unknown indicator
    }

    /**
     * 1. Persentase Kehadiran = (hadir + terlambat) / total_working_days * 100
     */
    private function calcPersentaseKehadiran($userId, $bulan, $tahun)
    {
        $workingDays = $this->attendanceModel->getWorkingDaysInMonth($tahun, $bulan);
        if ($workingDays <= 0) return 0;

        $attendances = $this->attendanceModel
            ->where('id_user', $userId)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->countAllResults();

        return round(($attendances / $workingDays) * 100, 2);
    }

    /**
     * 2. Ketepatan Waktu = hadir / (hadir + terlambat) * 100
     */
    private function calcKetepatanWaktu($userId, $bulan, $tahun)
    {
        $hadir = $this->attendanceModel
            ->where('id_user', $userId)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->where('status', 'hadir')
            ->countAllResults();

        $terlambat = $this->attendanceModel
            ->where('id_user', $userId)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->where('status', 'terlambat')
            ->countAllResults();

        $total = $hadir + $terlambat;
        if ($total <= 0) return 0;

        return round(($hadir / $total) * 100, 2);
    }

    /**
     * 3. Konsistensi Input Logbook = distinct_activity_days / working_days * 100
     */
    private function calcKonsistensiLogbook($userId, $bulan, $tahun)
    {
        $workingDays = $this->attendanceModel->getWorkingDaysInMonth($tahun, $bulan);
        if ($workingDays <= 0) return 0;

        $distinctDays = $this->activityModel
            ->select('COUNT(DISTINCT tanggal) as total_days')
            ->where('id_user', $userId)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->first();

        $days = $distinctDays ? (int)$distinctDays['total_days'] : 0;

        return round(($days / $workingDays) * 100, 2);
    }

    /**
     * 4. Approval Rate = approved / submitted (non-draft) * 100
     */
    private function calcApprovalRate($userId, $bulan, $tahun)
    {
        $submitted = $this->activityModel
            ->where('id_user', $userId)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->where('status_approval !=', 'draft')
            ->countAllResults();

        if ($submitted <= 0) return 0;

        $approved = $this->activityModel
            ->where('id_user', $userId)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->where('status_approval', 'approved')
            ->countAllResults();

        return round(($approved / $submitted) * 100, 2);
    }

    /**
     * 5. Jumlah Project Completed = count(progress=100) * 20, max 100
     *    (5 completed projects = 100%)
     */
    private function calcProjectCompleted($userId, $bulan, $tahun)
    {
        // Get week numbers that fall in the target month
        $startDate = sprintf('%04d-%02d-01', $tahun, $bulan);
        $endDate   = date('Y-m-t', strtotime($startDate));

        $completed = $this->projectModel
            ->where('id_user', $userId)
            ->where('progress', 100)
            ->where('periode_mulai >=', $startDate)
            ->where('periode_selesai <=', $endDate)
            ->countAllResults();

        return min(100, $completed * 20);
    }

    /**
     * 6. Kualitas Hasil Project = AVG(mentor_rating) * 20
     */
    private function calcKualitasProject($userId, $bulan, $tahun)
    {
        $startDate = sprintf('%04d-%02d-01', $tahun, $bulan);
        $endDate   = date('Y-m-t', strtotime($startDate));

        $result = $this->projectModel
            ->select('AVG(mentor_rating) as avg_rating')
            ->where('id_user', $userId)
            ->where('mentor_rating IS NOT NULL')
            ->where('mentor_rating >', 0)
            ->where('periode_mulai >=', $startDate)
            ->where('periode_selesai <=', $endDate)
            ->first();

        $avgRating = $result ? (float)$result['avg_rating'] : 0;

        return round($avgRating * 20, 2);
    }

    /**
     * 7. Inisiatif Project = count(tipe=inisiatif) / count(*) * 100
     */
    private function calcInisiatifProject($userId, $bulan, $tahun)
    {
        $startDate = sprintf('%04d-%02d-01', $tahun, $bulan);
        $endDate   = date('Y-m-t', strtotime($startDate));

        $total = $this->projectModel
            ->where('id_user', $userId)
            ->where('periode_mulai >=', $startDate)
            ->where('periode_selesai <=', $endDate)
            ->countAllResults();

        if ($total <= 0) return 0;

        $inisiatif = $this->projectModel
            ->where('id_user', $userId)
            ->where('tipe_project', 'inisiatif')
            ->where('periode_mulai >=', $startDate)
            ->where('periode_selesai <=', $endDate)
            ->countAllResults();

        return round(($inisiatif / $total) * 100, 2);
    }

    /**
     * Get all active interns with user data
     */
    private function getActiveInterns()
    {
        return db_connect()->table('interns i')
            ->select('i.id_intern, i.id_user, i.id_mentor, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('users u', 'u.id_user = i.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('i.status_magang', 'active')
            ->where('u.status', 'active')
            ->orderBy('u.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();
    }
}
