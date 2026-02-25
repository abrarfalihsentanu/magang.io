<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KpiPeriodResultModel;
use App\Models\KpiMonthlyResultModel;
use App\Libraries\NotificationService;

class KpiPeriodController extends BaseController
{
    protected $periodModel;
    protected $monthlyModel;

    public function __construct()
    {
        $this->periodModel  = new KpiPeriodResultModel();
        $this->monthlyModel = new KpiMonthlyResultModel();
        helper(['form']);
    }

    /**
     * Period Results Dashboard
     */
    public function index()
    {
        $periodResults = $this->periodModel->getPeriodRanking();

        // Get available finalized months count
        $finalizedMonths = $this->monthlyModel
            ->select('bulan, tahun')
            ->where('is_finalized', 1)
            ->groupBy('bulan, tahun')
            ->findAll();

        $bestIntern = $this->periodModel->getBestIntern();

        $data = [
            'title'           => 'Hasil KPI Periode',
            'periodResults'   => $periodResults,
            'finalizedMonths' => $finalizedMonths,
            'bestIntern'      => $bestIntern,
            'totalResults'    => count($periodResults),
        ];

        return view('admin/kpi/period/index', $data);
    }

    /**
     * Calculate/recalculate period results
     */
    public function calculatePeriod()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        try {
            $count = $this->periodModel->calculatePeriodResults();

            // Notify all processed interns
            try {
                if ($count > 0) {
                    $internUsers = db_connect()
                        ->table('kpi_period_results kpr')
                        ->select('u.id_user')
                        ->join('interns i', 'i.id_intern = kpr.id_intern')
                        ->join('users u', 'u.id_user = i.id_user')
                        ->get()
                        ->getResultArray();
                    $internUserIds = array_column($internUsers, 'id_user');
                    $periodLabel   = date('Y');
                    $notifService  = new NotificationService();
                    $notifService->kpiPeriodCalculated($internUserIds, $periodLabel);
                }
            } catch (\Exception $e) {
                log_message('error', 'KpiPeriod calculated notification error: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Perhitungan periode selesai. {$count} intern diproses.",
                'data'    => ['processed_count' => $count]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghitung: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Best Interns page
     */
    public function bestInterns()
    {
        $periodResults = $this->periodModel->getPeriodRanking();
        $bestIntern = $this->periodModel->getBestIntern();

        // Get top 3
        $top3 = array_slice($periodResults, 0, 3);

        $data = [
            'title'         => 'Pemagang Terbaik',
            'periodResults' => $periodResults,
            'bestIntern'    => $bestIntern,
            'top3'          => $top3,
        ];

        return view('admin/kpi/period/best_interns', $data);
    }

    /**
     * Generate certificate (placeholder)
     */
    public function generateCertificate($periodResultId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        // TODO: Implement certificate generation
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Fitur sertifikat akan segera tersedia'
        ]);
    }

    /**
     * Download certificate (placeholder)
     */
    public function downloadCertificate($periodResultId)
    {
        return redirect()->back()->with('error', 'Fitur sertifikat akan segera tersedia');
    }
}
