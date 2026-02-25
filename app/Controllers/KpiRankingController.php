<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KpiMonthlyResultModel;

class KpiRankingController extends BaseController
{
    protected $monthlyModel;

    public function __construct()
    {
        $this->monthlyModel = new KpiMonthlyResultModel();
        helper(['form']);
    }

    /**
     * Public Leaderboard
     */
    public function index()
    {
        $bulan = (int)($this->request->getGet('bulan') ?: date('n'));
        $tahun = (int)($this->request->getGet('tahun') ?: date('Y'));

        $ranking = $this->monthlyModel->getMonthlyRanking($bulan, $tahun);
        $availableMonths = $this->monthlyModel->getAvailableMonths();
        $stats = $this->monthlyModel->getMonthlyStats($bulan, $tahun);

        $data = [
            'title'           => 'Leaderboard KPI',
            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'ranking'         => $ranking,
            'availableMonths' => $availableMonths,
            'stats'           => $stats,
        ];

        return view('kpi/ranking/index', $data);
    }

    /**
     * Filter by division
     */
    public function byDivision($divisionId)
    {
        $bulan = (int)($this->request->getGet('bulan') ?: date('n'));
        $tahun = (int)($this->request->getGet('tahun') ?: date('Y'));

        $ranking = $this->monthlyModel
            ->select('kpi_monthly_results.*, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('users u', 'u.id_user = kpi_monthly_results.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('kpi_monthly_results.bulan', $bulan)
            ->where('kpi_monthly_results.tahun', $tahun)
            ->where('u.id_divisi', $divisionId)
            ->orderBy('kpi_monthly_results.total_score', 'DESC')
            ->findAll();

        // Re-rank within division
        $rank = 1;
        foreach ($ranking as &$r) {
            $r['rank_bulan_ini'] = $rank++;
        }

        $divisions = db_connect()->table('divisi')->orderBy('nama_divisi')->get()->getResultArray();
        $currentDivision = db_connect()->table('divisi')->where('id_divisi', $divisionId)->get()->getRowArray();
        $availableMonths = $this->monthlyModel->getAvailableMonths();

        $data = [
            'title'           => 'Leaderboard KPI - ' . ($currentDivision['nama_divisi'] ?? 'Divisi'),
            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'ranking'         => $ranking,
            'divisions'       => $divisions,
            'currentDivision' => $currentDivision,
            'availableMonths' => $availableMonths,
        ];

        return view('kpi/ranking/index', $data);
    }
}
