<?php

namespace App\Controllers\Mentor;

use App\Controllers\BaseController;
use App\Models\KpiIndicatorModel;
use App\Models\KpiAssessmentModel;
use App\Models\InternModel;

class KpiAssessmentController extends BaseController
{
    protected $indicatorModel;
    protected $assessmentModel;
    protected $internModel;

    public function __construct()
    {
        $this->indicatorModel  = new KpiIndicatorModel();
        $this->assessmentModel = new KpiAssessmentModel();
        $this->internModel     = new InternModel();
        helper(['form']);
    }

    /**
     * Assessment Dashboard - show mentees and their assessment status
     */
    public function index()
    {
        $bulan = (int)($this->request->getGet('bulan') ?: date('n'));
        $tahun = (int)($this->request->getGet('tahun') ?: date('Y'));

        $userId = session()->get('id_user');
        $roleCode = session()->get('role_code');

        // Get manual indicators
        $manualIndicators = $this->indicatorModel->getManualIndicators();

        // If admin/hr, show all interns; if mentor, show only mentees
        if (in_array($roleCode, ['admin', 'hr'])) {
            $mentees = $this->getAllActiveInterns();
        } else {
            $mentees = $this->getMentees($userId);
        }

        // Check assessment status for each mentee
        foreach ($mentees as &$mentee) {
            $assessments = $this->assessmentModel->getUserMonthlyAssessments(
                $mentee['id_user'],
                $bulan,
                $tahun
            );
            $manualAssessments = array_filter($assessments, fn($a) => !$a['is_auto_calculate']);
            $mentee['is_assessed'] = count($manualAssessments) >= count($manualIndicators) && count($manualIndicators) > 0;
            $mentee['manual_count'] = count($manualAssessments);
            $mentee['total_manual'] = count($manualIndicators);
        }

        $data = [
            'title'            => 'Penilaian KPI Manual',
            'bulan'            => $bulan,
            'tahun'            => $tahun,
            'mentees'          => $mentees,
            'manualIndicators' => $manualIndicators,
            'totalMentees'     => count($mentees),
            'assessedCount'    => count(array_filter($mentees, fn($m) => $m['is_assessed'])),
        ];

        return view('mentor/kpi/assessment/index', $data);
    }

    /**
     * Assessment form for a specific intern
     */
    public function assessmentForm($internUserId)
    {
        $bulan = (int)($this->request->getGet('bulan') ?: date('n'));
        $tahun = (int)($this->request->getGet('tahun') ?: date('Y'));

        // Get intern info
        $intern = db_connect()->table('users u')
            ->select('u.id_user, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('u.id_user', $internUserId)
            ->get()
            ->getRowArray();

        if (!$intern) {
            return redirect()->to(base_url('kpi/assessment'))->with('error', 'Intern tidak ditemukan');
        }

        // Get manual indicators
        $manualIndicators = $this->indicatorModel->getManualIndicators();

        // Get existing assessments
        $existingAssessments = [];
        foreach ($manualIndicators as $indicator) {
            $assessment = $this->assessmentModel->getOrCreate(
                $internUserId,
                $indicator['id_indicator'],
                $bulan,
                $tahun
            );
            $existingAssessments[$indicator['id_indicator']] = $assessment;
        }

        // Get auto-calculated assessments for context (read-only)
        $autoAssessments = $this->assessmentModel
            ->select('kpi_assessments.*, kpi_indicators.nama_indicator, kpi_indicators.bobot, kpi_indicators.kategori')
            ->join('kpi_indicators', 'kpi_indicators.id_indicator = kpi_assessments.id_indicator')
            ->where('kpi_assessments.id_user', $internUserId)
            ->where('kpi_assessments.bulan', $bulan)
            ->where('kpi_assessments.tahun', $tahun)
            ->where('kpi_indicators.is_auto_calculate', 1)
            ->findAll();

        $data = [
            'title'               => 'Form Penilaian KPI - ' . $intern['nama_lengkap'],
            'bulan'               => $bulan,
            'tahun'               => $tahun,
            'intern'              => $intern,
            'manualIndicators'    => $manualIndicators,
            'existingAssessments' => $existingAssessments,
            'autoAssessments'     => $autoAssessments,
        ];

        return view('mentor/kpi/assessment/form', $data);
    }

    /**
     * Submit manual assessment scores via AJAX
     */
    public function submitAssessment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $userId     = (int)$this->request->getPost('id_user');
        $bulan      = (int)$this->request->getPost('bulan');
        $tahun      = (int)$this->request->getPost('tahun');
        $scores     = $this->request->getPost('scores');     // array: indicator_id => score (1-5)
        $catatanArr = $this->request->getPost('catatan');     // array: indicator_id => catatan
        $penilaiId  = session()->get('id_user');

        if (!$userId || !$bulan || !$tahun || empty($scores)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data penilaian tidak lengkap'
            ]);
        }

        $manualIndicators = $this->indicatorModel->getManualIndicators();
        $savedCount = 0;

        foreach ($manualIndicators as $indicator) {
            $indId = $indicator['id_indicator'];
            if (!isset($scores[$indId])) continue;

            $score = (float)$scores[$indId];
            // Validate score 1-5
            if ($score < 1 || $score > 5) continue;

            // Convert 1-5 scale to 0-100: score * 20
            $nilaiRaw = $score * 20;
            $nilaiWeighted = round(($nilaiRaw / 100) * $indicator['bobot'], 2);

            $this->assessmentModel->upsertAssessment([
                'id_user'        => $userId,
                'id_indicator'   => $indId,
                'bulan'          => $bulan,
                'tahun'          => $tahun,
                'nilai_raw'      => $nilaiRaw,
                'nilai_weighted' => $nilaiWeighted,
                'penilai_id'     => $penilaiId,
                'catatan'        => $catatanArr[$indId] ?? null,
            ]);

            $savedCount++;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Penilaian berhasil disimpan ({$savedCount} indikator)",
            'data'    => ['saved_count' => $savedCount]
        ]);
    }

    /**
     * View assessment history for an intern
     */
    public function history($internUserId)
    {
        $intern = db_connect()->table('users u')
            ->select('u.id_user, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('u.id_user', $internUserId)
            ->get()
            ->getRowArray();

        if (!$intern) {
            return redirect()->to(base_url('kpi/assessment'))->with('error', 'Intern tidak ditemukan');
        }

        // Get all assessments grouped by month
        $assessments = $this->assessmentModel
            ->select('kpi_assessments.*, kpi_indicators.nama_indicator, kpi_indicators.bobot, kpi_indicators.kategori')
            ->join('kpi_indicators', 'kpi_indicators.id_indicator = kpi_assessments.id_indicator')
            ->where('kpi_assessments.id_user', $internUserId)
            ->orderBy('kpi_assessments.tahun', 'DESC')
            ->orderBy('kpi_assessments.bulan', 'DESC')
            ->orderBy('kpi_indicators.kategori', 'ASC')
            ->findAll();

        // Group by month
        $grouped = [];
        foreach ($assessments as $assessment) {
            $key = $assessment['tahun'] . '-' . str_pad($assessment['bulan'], 2, '0', STR_PAD_LEFT);
            $grouped[$key][] = $assessment;
        }

        $data = [
            'title'    => 'Riwayat Penilaian - ' . $intern['nama_lengkap'],
            'intern'   => $intern,
            'grouped'  => $grouped,
        ];

        return view('mentor/kpi/assessment/history', $data);
    }

    /**
     * Get mentees for a mentor
     */
    private function getMentees($mentorId)
    {
        return db_connect()->table('interns i')
            ->select('i.id_intern, i.id_user, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('users u', 'u.id_user = i.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('i.id_mentor', $mentorId)
            ->where('i.status_magang', 'active')
            ->where('u.status', 'active')
            ->orderBy('u.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get all active interns (for admin/hr)
     */
    private function getAllActiveInterns()
    {
        return db_connect()->table('interns i')
            ->select('i.id_intern, i.id_user, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('users u', 'u.id_user = i.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('i.status_magang', 'active')
            ->where('u.status', 'active')
            ->orderBy('u.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();
    }
}
