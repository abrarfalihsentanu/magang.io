<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InternModel;

class ArchiveController extends BaseController
{
    protected InternModel $internModel;
    protected $db;

    public function __construct()
    {
        $this->internModel = new InternModel();
        $this->db          = \Config\Database::connect();
        helper(['form']);
    }

    // ================================================================
    // INDEX – two tabs: active interns (candidates) + archived list
    // ================================================================
    public function index(): string
    {
        // --- Active interns (belum diarsipkan) -----------------------
        $activeInterns = $this->db->table('interns i')
            ->select('
                i.id_intern, i.id_user, i.periode_mulai, i.periode_selesai,
                i.durasi_bulan, i.universitas, i.jurusan, i.status_magang,
                u.nik, u.nama_lengkap, u.email, u.foto, u.status as user_status,
                d.nama_divisi,
                m.nama_lengkap as nama_mentor
            ')
            ->join('users u',        'u.id_user = i.id_user',       'left')
            ->join('divisi d',       'd.id_divisi = u.id_divisi',   'left')
            ->join('users m',        'm.id_user = i.id_mentor',     'left')
            ->where('i.status_magang', 'active')
            ->orderBy('i.periode_selesai', 'ASC')
            ->get()
            ->getResultArray();

        // --- Already archived ----------------------------------------
        $archivedList = $this->db->table('archived_interns ai')
            ->select('
                ai.id_archive, ai.id_intern, ai.id_user,
                ai.nama_lengkap, ai.nik, ai.divisi,
                ai.periode_mulai, ai.periode_selesai,
                ai.persentase_kehadiran, ai.final_kpi_score, ai.final_rank,
                ai.total_uang_saku, ai.archived_at, ai.keterangan,
                archiver.nama_lengkap as archived_by_name
            ')
            ->join('users archiver', 'archiver.id_user = ai.archived_by', 'left')
            ->orderBy('ai.archived_at', 'DESC')
            ->get()
            ->getResultArray();

        // --- Stats cards ---------------------------------------------
        $stats = [
            'active'   => count($activeInterns),
            'archived' => count($archivedList),
            'expiring' => 0, // interns with periode_selesai <= 30 days from now
        ];
        foreach ($activeInterns as $intern) {
            $daysLeft = (strtotime($intern['periode_selesai']) - time()) / 86400;
            if ($daysLeft >= 0 && $daysLeft <= 30) {
                $stats['expiring']++;
            }
        }

        return view('archive/index', [
            'title'         => 'Arsip Pemagang',
            'activeInterns' => $activeInterns,
            'archivedList'  => $archivedList,
            'stats'         => $stats,
        ]);
    }

    // ================================================================
    // VIEW – detail profil arsip satu intern
    // ================================================================
    public function view(int $id): string
    {
        $archive = $this->db->table('archived_interns ai')
            ->select('ai.*, archiver.nama_lengkap as archived_by_name')
            ->join('users archiver', 'archiver.id_user = ai.archived_by', 'left')
            ->where('ai.id_archive', $id)
            ->get()
            ->getRowArray();

        if (!$archive) {
            return redirect()->to(base_url('archive'))
                ->with('error', 'Data arsip tidak ditemukan.');
        }

        // Decode summary JSON
        $summary = [];
        if (!empty($archive['summary_data'])) {
            $summary = is_string($archive['summary_data'])
                ? (json_decode($archive['summary_data'], true) ?? [])
                : (array)$archive['summary_data'];
        }

        return view('archive/view', [
            'title'   => 'Detail Arsip – ' . $archive['nama_lengkap'],
            'archive' => $archive,
            'summary' => $summary,
        ]);
    }

    // ================================================================
    // PROCESS – arsipkan satu atau banyak intern (POST)
    // ================================================================
    public function process()
    {
        // Validate – expect id_interns array or single id_intern
        $idInterns  = $this->request->getPost('id_interns') ?? [];
        if (!is_array($idInterns)) {
            $idInterns = [$idInterns];
        }
        $idInterns  = array_filter(array_map('intval', $idInterns));
        $keterangan = trim($this->request->getPost('keterangan') ?? '');
        $archivedBy = (int)session()->get('user_id');

        if (empty($idInterns)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pilih minimal satu pemagang untuk diarsipkan.',
            ]);
        }

        $this->db->transStart();

        $processed = 0;
        $skipped   = [];

        foreach ($idInterns as $idIntern) {
            // --- Fetch intern with user data -------------------------
            $intern = $this->db->table('interns i')
                ->select('
                    i.id_intern, i.id_user, i.periode_mulai, i.periode_selesai,
                    i.durasi_bulan, i.universitas, i.jurusan, i.status_magang,
                    u.nik, u.nama_lengkap, u.email, u.foto,
                    d.nama_divisi
                ')
                ->join('users u', 'u.id_user = i.id_user', 'left')
                ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
                ->where('i.id_intern', $idIntern)
                ->get()->getRowArray();

            if (!$intern) {
                $skipped[] = "ID {$idIntern}: tidak ditemukan";
                continue;
            }

            if ($intern['status_magang'] !== 'active') {
                $skipped[] = $intern['nama_lengkap'] . ' (bukan pemagang aktif)';
                continue;
            }

            // Check if already archived
            $alreadyArchived = $this->db->table('archived_interns')
                ->where('id_intern', $idIntern)
                ->countAllResults();
            if ($alreadyArchived > 0) {
                $skipped[] = $intern['nama_lengkap'] . ' (sudah diarsipkan)';
                continue;
            }

            $idUser = (int)$intern['id_user'];

            // --- Attendance stats ------------------------------------
            $attStats = $this->db->table('allowances')
                ->selectSum('total_hadir',       'total_hari_hadir')
                ->selectSum('total_hari_kerja',  'total_hari_kerja')
                ->where('id_user', $idUser)
                ->get()->getRowArray();

            $totalHadir    = (int)($attStats['total_hari_hadir']  ?? 0);
            $totalHarKerja = (int)($attStats['total_hari_kerja']  ?? 0);

            // Fallback: count directly from attendances if no allowance data
            if ($totalHarKerja === 0) {
                $totalHadir    = (int)$this->db->table('attendances')
                    ->selectCount('DISTINCT tanggal', 'cnt')
                    ->where('id_user', $idUser)
                    ->get()->getRow()->cnt;
                // Estimate working days from period
                $periodDays    = max(1, (int)round(
                    (strtotime($intern['periode_selesai']) - strtotime($intern['periode_mulai'])) / 86400
                ));
                $totalHarKerja = (int)ceil($periodDays * 5 / 7); // ~weekdays
            }

            $pctHadir = $totalHarKerja > 0
                ? round($totalHadir / $totalHarKerja * 100, 2)
                : 0.00;

            // --- KPI stats ------------------------------------------
            $kpiRow = $this->db->table('kpi_period_results')
                ->where('id_intern', $idIntern)
                ->orderBy('created_at', 'DESC')
                ->limit(1)
                ->get()->getRowArray();

            $finalKpi  = $kpiRow ? (float)$kpiRow['avg_total_score'] : 0.00;
            $finalRank = $kpiRow ? (int)$kpiRow['final_rank']        : 0;

            // --- Allowance total ------------------------------------
            $allowanceRow = $this->db->table('allowances')
                ->selectSum('total_uang_saku', 'total')
                ->where('id_user', $idUser)
                ->where('status_pembayaran', 'paid')
                ->get()->getRowArray();
            $totalUangSaku = (float)($allowanceRow['total'] ?? 0);

            // --- Summary JSON ----------------------------------------
            $activityCount = (int)$this->db->table('daily_activities')
                ->where('id_user', $idUser)->countAllResults();
            $projectCount  = (int)$this->db->table('weekly_projects')
                ->where('id_user', $idUser)->countAllResults();
            $leaveCount    = (int)$this->db->table('leaves')
                ->where('id_user', $idUser)
                ->where('status_approval', 'approved')
                ->countAllResults();

            $summaryData = json_encode([
                'universitas'        => $intern['universitas'],
                'jurusan'            => $intern['jurusan'],
                'durasi_bulan'       => $intern['durasi_bulan'],
                'total_aktivitas'    => $activityCount,
                'total_proyek'       => $projectCount,
                'total_cuti_diizin'  => $leaveCount,
                'avg_kpi_score'      => $finalKpi,
                'kpi_kategori'       => $this->kpiKategori($finalKpi),
            ]);

            // --- Insert archived_interns ----------------------------
            $this->db->table('archived_interns')->insert([
                'id_intern'            => $idIntern,
                'id_user'              => $idUser,
                'nama_lengkap'         => $intern['nama_lengkap'],
                'nik'                  => $intern['nik'],
                'divisi'               => $intern['nama_divisi'] ?? '-',
                'periode_mulai'        => $intern['periode_mulai'],
                'periode_selesai'      => $intern['periode_selesai'],
                'total_hari_hadir'     => $totalHadir,
                'total_hari_kerja'     => $totalHarKerja,
                'persentase_kehadiran' => $pctHadir,
                'final_kpi_score'      => $finalKpi,
                'final_rank'           => $finalRank,
                'total_uang_saku'      => $totalUangSaku,
                'summary_data'         => $summaryData,
                'archived_at'          => date('Y-m-d H:i:s'),
                'archived_by'          => $archivedBy,
                'keterangan'           => $keterangan ?: null,
            ]);

            // --- Update intern status --------------------------------
            $this->db->table('interns')
                ->where('id_intern', $idIntern)
                ->update(['status_magang' => 'completed']);

            // --- Update user status ---------------------------------
            $this->db->table('users')
                ->where('id_user', $idUser)
                ->update(['status' => 'archived']);

            $processed++;
        }

        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Transaksi gagal. Silakan coba lagi.',
            ]);
        }

        $msg = "{$processed} pemagang berhasil diarsipkan.";
        if (!empty($skipped)) {
            $msg .= ' Dilewati: ' . implode(', ', $skipped) . '.';
        }

        return $this->response->setJSON([
            'success'   => true,
            'message'   => $msg,
            'processed' => $processed,
            'skipped'   => $skipped,
        ]);
    }

    // ================================================================
    // Private helpers
    // ================================================================
    private function kpiKategori(float $score): string
    {
        if ($score >= 90) return 'Sangat Baik';
        if ($score >= 75) return 'Baik';
        if ($score >= 60) return 'Cukup';
        if ($score >= 40) return 'Kurang';
        return 'Sangat Kurang';
    }
}
