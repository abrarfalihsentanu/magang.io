<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KpiMonthlyResultModel;
use App\Models\KpiAssessmentModel;
use App\Models\KpiIndicatorModel;
use App\Libraries\NotificationService;

class KpiMonthlyController extends BaseController
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
     * Monthly Results Overview
     */
    public function index()
    {
        $bulan = (int)($this->request->getGet('bulan') ?: date('n'));
        $tahun = (int)($this->request->getGet('tahun') ?: date('Y'));

        $ranking = $this->monthlyModel->getMonthlyRanking($bulan, $tahun);
        $stats   = $this->monthlyModel->getMonthlyStats($bulan, $tahun);
        $availableMonths = $this->monthlyModel->getAvailableMonths();

        // Get kategori distribution
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

        $data = [
            'title'           => 'Hasil KPI Bulanan',
            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'ranking'         => $ranking,
            'stats'           => $stats,
            'availableMonths' => $availableMonths,
            'distribution'    => $distribution,
        ];

        return view('admin/kpi/monthly/index', $data);
    }

    /**
     * View detailed monthly result for a specific user
     */
    public function view($bulan, $tahun)
    {
        $userId = $this->request->getGet('user');
        if (!$userId) {
            return redirect()->to(base_url("kpi/monthly?bulan={$bulan}&tahun={$tahun}"))->with('error', 'User tidak ditemukan');
        }

        // Get user info
        $user = db_connect()->table('users u')
            ->select('u.id_user, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('u.id_user', $userId)
            ->get()
            ->getRowArray();

        if (!$user) {
            return redirect()->to(base_url("kpi/monthly?bulan={$bulan}&tahun={$tahun}"))->with('error', 'User tidak ditemukan');
        }

        // Get assessments for this user/month
        $assessments = $this->assessmentModel->getUserMonthlyAssessments($userId, $bulan, $tahun);

        // Get monthly result
        $monthlyResult = $this->monthlyModel->where([
            'id_user' => $userId,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->first();

        // Group assessments by category
        $grouped = [];
        foreach ($assessments as $a) {
            $grouped[$a['kategori']][] = $a;
        }

        $data = [
            'title'         => 'Detail KPI - ' . $user['nama_lengkap'],
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'user'          => $user,
            'assessments'   => $assessments,
            'grouped'       => $grouped,
            'monthlyResult' => $monthlyResult,
        ];

        return view('admin/kpi/monthly/detail', $data);
    }

    /**
     * Finalize monthly results
     */
    public function finalize($bulan)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $tahun = (int)$this->request->getPost('tahun');

        if ($this->monthlyModel->isMonthFinalized($bulan, $tahun)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bulan ini sudah di-finalize sebelumnya'
            ]);
        }

        // Check if there are results to finalize
        $count = $this->monthlyModel->where(['bulan' => $bulan, 'tahun' => $tahun])->countAllResults();
        if ($count === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak ada data untuk di-finalize. Lakukan perhitungan KPI terlebih dahulu.'
            ]);
        }

        $userId = session()->get('id_user');
        $this->monthlyModel->finalizeMonth($bulan, $tahun, $userId);

        // Notify each intern whose KPI was finalized
        try {
            $bulanNames = [
                '',
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];
            $bulanName    = $bulanNames[(int)$bulan] ?? (string)$bulan;
            $affectedUsers = $this->monthlyModel
                ->select('id_user')
                ->where(['bulan' => $bulan, 'tahun' => $tahun])
                ->findAll();
            $notifService = new NotificationService();
            foreach ($affectedUsers as $user) {
                $notifService->kpiAssessed((int)$user['id_user'], $bulanName, (string)$tahun);
            }
        } catch (\Exception $e) {
            log_message('error', 'KpiMonthly finalize notification error: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data KPI bulan ini berhasil di-finalize'
        ]);
    }

    /**
     * Export monthly results
     */
    public function export()
    {
        $bulan = (int)($this->request->getGet('bulan') ?: date('n'));
        $tahun = (int)($this->request->getGet('tahun') ?: date('Y'));

        $ranking = $this->monthlyModel->getMonthlyRanking($bulan, $tahun);

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $filename = "KPI_Bulanan_{$namaBulan[$bulan]}_{$tahun}.csv";

        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', "attachment; filename=\"{$filename}\"");

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Rank', 'NIK', 'Nama', 'Divisi', 'Total Score', 'Kategori', 'Status']);

        foreach ($ranking as $r) {
            $kategoriLabel = match ($r['kategori_performa']) {
                'excellent'     => 'Sangat Baik',
                'good'          => 'Baik',
                'average'       => 'Cukup',
                'below_average' => 'Kurang',
                'poor'          => 'Sangat Kurang',
                default         => '-'
            };

            fputcsv($output, [
                $r['rank_bulan_ini'],
                $r['nik'],
                $r['nama_lengkap'],
                $r['nama_divisi'] ?? '-',
                $r['total_score'],
                $kategoriLabel,
                $r['is_finalized'] ? 'Final' : 'Draft'
            ]);
        }

        fclose($output);
        return $this->response;
    }
}
