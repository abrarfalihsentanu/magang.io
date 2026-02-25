<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KpiMonthlyResultModel;
use App\Models\KpiAssessmentModel;
use App\Models\KpiIndicatorModel;

class KpiAnalyticsController extends BaseController
{
    protected $monthlyModel;
    protected $assessmentModel;
    protected $indicatorModel;

    public function __construct()
    {
        $this->monthlyModel    = new KpiMonthlyResultModel();
        $this->assessmentModel = new KpiAssessmentModel();
        $this->indicatorModel  = new KpiIndicatorModel();
        helper(['form']);
    }

    /**
     * Analytics Dashboard
     */
    public function index()
    {
        $availableMonths = $this->monthlyModel->getAvailableMonths();

        // Get trend data (last 6 months)
        $trendData = [];
        foreach (array_slice($availableMonths, 0, 6) as $m) {
            $stats = $this->monthlyModel->getMonthlyStats($m['bulan'], $m['tahun']);
            $trendData[] = array_merge($m, $stats);
        }

        $data = [
            'title'           => 'Analitik KPI',
            'availableMonths' => $availableMonths,
            'trendData'       => array_reverse($trendData),
        ];

        return view('admin/kpi/analytics/index', $data);
    }

    /**
     * Performance distribution
     */
    public function distribution()
    {
        $bulan = (int)($this->request->getGet('bulan') ?: date('n'));
        $tahun = (int)($this->request->getGet('tahun') ?: date('Y'));

        $ranking = $this->monthlyModel->getMonthlyRanking($bulan, $tahun);

        $distribution = [
            'excellent'     => 0,
            'good'          => 0,
            'average'       => 0,
            'below_average' => 0,
            'poor'          => 0,
        ];

        foreach ($ranking as $r) {
            if (isset($distribution[$r['kategori_performa']])) {
                $distribution[$r['kategori_performa']]++;
            }
        }

        // Score ranges
        $scoreRanges = [
            '90-100' => 0,
            '80-89' => 0,
            '70-79' => 0,
            '60-69' => 0,
            '50-59' => 0,
            '0-49' => 0,
        ];

        foreach ($ranking as $r) {
            $s = (float)$r['total_score'];
            if ($s >= 90) $scoreRanges['90-100']++;
            elseif ($s >= 80) $scoreRanges['80-89']++;
            elseif ($s >= 70) $scoreRanges['70-79']++;
            elseif ($s >= 60) $scoreRanges['60-69']++;
            elseif ($s >= 50) $scoreRanges['50-59']++;
            else $scoreRanges['0-49']++;
        }

        $data = [
            'title'        => 'Distribusi Performa',
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'distribution' => $distribution,
            'scoreRanges'  => $scoreRanges,
            'ranking'      => $ranking,
        ];

        return view('admin/kpi/analytics/distribution', $data);
    }

    /**
     * Trends over time
     */
    public function trends()
    {
        $availableMonths = $this->monthlyModel->getAvailableMonths();

        $trends = [];
        foreach ($availableMonths as $m) {
            $stats = $this->monthlyModel->getMonthlyStats($m['bulan'], $m['tahun']);
            $trends[] = [
                'bulan'     => $m['bulan'],
                'tahun'     => $m['tahun'],
                'avg_score' => $stats['avg_score'],
                'max_score' => $stats['max_score'],
                'min_score' => $stats['min_score'],
                'total'     => $stats['total_users'],
            ];
        }

        $data = [
            'title'  => 'Tren KPI',
            'trends' => array_reverse($trends),
        ];

        return view('admin/kpi/analytics/trends', $data);
    }

    /**
     * Export report
     */
    public function exportReport()
    {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Fitur export report akan segera tersedia'
        ]);
    }
}
