<?php

namespace App\Models;

use CodeIgniter\Model;

class KpiPeriodResultModel extends Model
{
    protected $table            = 'kpi_period_results';
    protected $primaryKey       = 'id_period_result';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_intern',
        'periode_mulai',
        'periode_selesai',
        'avg_total_score',
        'final_rank',
        'is_best_intern',
        'sertifikat_file'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get period results with user info
     */
    public function getPeriodRanking()
    {
        return $this->select('kpi_period_results.*, u.nama_lengkap, u.nik, u.foto, d.nama_divisi,
                              i.periode_mulai, i.periode_selesai')
            ->join('interns i', 'i.id_intern = kpi_period_results.id_intern')
            ->join('users u', 'u.id_user = i.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->orderBy('kpi_period_results.final_rank', 'ASC')
            ->findAll();
    }

    /**
     * Calculate period results from monthly results
     */
    public function calculatePeriodResults()
    {
        // Get all active interns
        $interns = $this->db->table('interns i')
            ->select('i.id_intern, i.id_user, i.periode_mulai, i.periode_selesai, u.nama_lengkap')
            ->join('users u', 'u.id_user = i.id_user')
            ->where('i.status_magang', 'active')
            ->where('u.status', 'active')
            ->get()
            ->getResultArray();

        $monthlyModel = new KpiMonthlyResultModel();
        $results = [];

        foreach ($interns as $intern) {
            // First try to get finalized results, if none, get all monthly results
            $monthlyResults = $monthlyModel->where([
                'id_user' => $intern['id_user'],
                'is_finalized' => 1
            ])->findAll();

            // If no finalized results, use all available monthly results
            if (empty($monthlyResults)) {
                $monthlyResults = $monthlyModel->where('id_user', $intern['id_user'])->findAll();
            }

            if (empty($monthlyResults)) continue;

            $scores = array_column($monthlyResults, 'total_score');
            $avgScore = round(array_sum($scores) / count($scores), 2);

            $results[] = [
                'id_intern' => $intern['id_intern'],
                'periode_mulai' => $intern['periode_mulai'],
                'periode_selesai' => $intern['periode_selesai'],
                'avg_total_score' => $avgScore,
                'is_best_intern' => 0
            ];
        }

        // Sort by avg_total_score descending
        usort($results, fn($a, $b) => $b['avg_total_score'] <=> $a['avg_total_score']);

        // Assign ranks
        $rank = 1;
        foreach ($results as &$result) {
            $result['final_rank'] = $rank;
            if ($rank === 1) {
                $result['is_best_intern'] = 1;
            }
            $rank++;
        }

        // Upsert results
        foreach ($results as $result) {
            $existing = $this->where('id_intern', $result['id_intern'])->first();
            if ($existing) {
                $this->update($existing['id_period_result'], $result);
            } else {
                $this->insert($result);
            }
        }

        return count($results);
    }

    /**
     * Get best intern
     */
    public function getBestIntern()
    {
        return $this->select('kpi_period_results.*, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('interns i', 'i.id_intern = kpi_period_results.id_intern')
            ->join('users u', 'u.id_user = i.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('kpi_period_results.is_best_intern', 1)
            ->first();
    }

    /**
     * Get result for a specific intern
     */
    public function getInternResult($internId)
    {
        return $this->select('kpi_period_results.*, u.nama_lengkap, u.nik, d.nama_divisi')
            ->join('interns i', 'i.id_intern = kpi_period_results.id_intern')
            ->join('users u', 'u.id_user = i.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('kpi_period_results.id_intern', $internId)
            ->first();
    }
}
