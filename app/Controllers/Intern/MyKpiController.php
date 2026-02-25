<?php

namespace App\Controllers\Intern;

use App\Controllers\BaseController;
use App\Models\KpiAssessmentModel;
use App\Models\KpiMonthlyResultModel;
use App\Models\KpiPeriodResultModel;
use App\Models\KpiIndicatorModel;

class MyKpiController extends BaseController
{
    protected $assessmentModel;
    protected $monthlyModel;
    protected $periodModel;
    protected $indicatorModel;

    public function __construct()
    {
        $this->assessmentModel = new KpiAssessmentModel();
        $this->monthlyModel    = new KpiMonthlyResultModel();
        $this->periodModel     = new KpiPeriodResultModel();
        $this->indicatorModel  = new KpiIndicatorModel();
        helper(['form']);
    }

    /**
     * Intern's KPI Dashboard
     */
    public function dashboard()
    {
        $userId = session()->get('id_user');
        $bulan  = (int)date('n');
        $tahun  = (int)date('Y');

        // Current month result
        $currentResult = $this->monthlyModel->where([
            'id_user' => $userId,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
        ])->first();

        // Current month assessments
        $assessments = $this->assessmentModel->getUserMonthlyAssessments($userId, $bulan, $tahun);

        // Group by category
        $grouped = [];
        foreach ($assessments as $a) {
            $grouped[$a['kategori']][] = $a;
        }

        // Monthly history (for chart)
        $history = $this->monthlyModel->getUserHistory($userId, 6);

        // Period result (if exists)
        $intern = db_connect()->table('interns')
            ->where('id_user', $userId)
            ->get()
            ->getRowArray();

        $periodResult = null;
        if ($intern) {
            $periodResult = $this->periodModel->getInternResult($intern['id_intern']);
        }

        // Total ranking this month
        $totalRanked = $this->monthlyModel->where(['bulan' => $bulan, 'tahun' => $tahun])->countAllResults();

        $data = [
            'title'         => 'KPI Saya',
            'currentResult' => $currentResult,
            'assessments'   => $assessments,
            'grouped'       => $grouped,
            'history'       => array_reverse($history),
            'periodResult'  => $periodResult,
            'totalRanked'   => $totalRanked,
            'bulan'         => $bulan,
            'tahun'         => $tahun,
        ];

        return view('intern/kpi/dashboard', $data);
    }

    /**
     * View specific month detail
     */
    public function monthlyDetail($bulan, $tahun)
    {
        $userId = session()->get('id_user');

        $result = $this->monthlyModel->where([
            'id_user' => $userId,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
        ])->first();

        $assessments = $this->assessmentModel->getUserMonthlyAssessments($userId, $bulan, $tahun);

        $grouped = [];
        foreach ($assessments as $a) {
            $grouped[$a['kategori']][] = $a;
        }

        $data = [
            'title'       => 'Detail KPI Bulanan',
            'result'      => $result,
            'assessments' => $assessments,
            'grouped'     => $grouped,
            'bulan'       => $bulan,
            'tahun'       => $tahun,
        ];

        return view('intern/kpi/monthly_detail', $data);
    }

    /**
     * Breakdown by indicator (all-time)
     */
    public function breakdown()
    {
        $userId    = session()->get('id_user');
        $indicators = $this->indicatorModel->getActiveIndicators();

        $breakdownData = [];
        foreach ($indicators as $ind) {
            $avgResult = $this->assessmentModel
                ->select('AVG(nilai_raw) as avg_raw, AVG(nilai_weighted) as avg_weighted, COUNT(*) as total_months')
                ->where('id_user', $userId)
                ->where('id_indicator', $ind['id_indicator'])
                ->first();

            $breakdownData[] = [
                'indicator'    => $ind,
                'avg_raw'      => round((float)($avgResult['avg_raw'] ?? 0), 2),
                'avg_weighted' => round((float)($avgResult['avg_weighted'] ?? 0), 2),
                'total_months' => (int)($avgResult['total_months'] ?? 0),
            ];
        }

        $data = [
            'title'         => 'Breakdown KPI per Indikator',
            'breakdownData' => $breakdownData,
        ];

        return view('intern/kpi/breakdown', $data);
    }

    /**
     * Full monthly history
     */
    public function history()
    {
        $userId = session()->get('id_user');
        $history = $this->monthlyModel->getUserHistory($userId, 24);

        $data = [
            'title'   => 'Riwayat KPI',
            'history' => $history,
        ];

        return view('intern/kpi/history', $data);
    }
}
