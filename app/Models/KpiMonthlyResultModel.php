<?php

namespace App\Models;

use CodeIgniter\Model;

class KpiMonthlyResultModel extends Model
{
    protected $table            = 'kpi_monthly_results';
    protected $primaryKey       = 'id_result';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'bulan',
        'tahun',
        'total_score',
        'rank_bulan_ini',
        'kategori_performa',
        'is_finalized',
        'finalized_at',
        'finalized_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get monthly results with user info for a specific month
     */
    public function getMonthlyRanking($bulan, $tahun)
    {
        return $this->select('kpi_monthly_results.*, u.nama_lengkap, u.nik, u.foto, d.nama_divisi')
            ->join('users u', 'u.id_user = kpi_monthly_results.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('kpi_monthly_results.bulan', $bulan)
            ->where('kpi_monthly_results.tahun', $tahun)
            ->orderBy('kpi_monthly_results.rank_bulan_ini', 'ASC')
            ->findAll();
    }

    /**
     * Get finalized monthly results
     */
    public function getFinalizedResults($bulan, $tahun)
    {
        return $this->getMonthlyRanking($bulan, $tahun);
    }

    /**
     * Get user's monthly history
     */
    public function getUserHistory($userId, $limit = 12)
    {
        return $this->where('id_user', $userId)
            ->orderBy('tahun', 'DESC')
            ->orderBy('bulan', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Upsert monthly result
     */
    public function upsertResult($data)
    {
        $existing = $this->where([
            'id_user' => $data['id_user'],
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun']
        ])->first();

        if ($existing) {
            if ($existing['is_finalized']) {
                return false; // Cannot update finalized results
            }
            return $this->update($existing['id_result'], $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Calculate and assign rankings for a month
     */
    public function calculateRankings($bulan, $tahun)
    {
        $results = $this->where(['bulan' => $bulan, 'tahun' => $tahun])
            ->orderBy('total_score', 'DESC')
            ->findAll();

        $rank = 1;
        foreach ($results as $result) {
            $kategori = $this->determineKategori($result['total_score']);
            $this->update($result['id_result'], [
                'rank_bulan_ini' => $rank,
                'kategori_performa' => $kategori
            ]);
            $rank++;
        }

        return count($results);
    }

    /**
     * Determine performance category from score
     */
    public function determineKategori($score)
    {
        if ($score >= 90) return 'excellent';
        if ($score >= 75) return 'good';
        if ($score >= 60) return 'average';
        if ($score >= 40) return 'below_average';
        return 'poor';
    }

    /**
     * Finalize all results for a month
     */
    public function finalizeMonth($bulan, $tahun, $userId = null)
    {
        return $this->where(['bulan' => $bulan, 'tahun' => $tahun])
            ->set('is_finalized', 1)
            ->set('finalized_at', date('Y-m-d H:i:s'))
            ->set('finalized_by', $userId)
            ->update();
    }

    /**
     * Check if month is finalized
     */
    public function isMonthFinalized($bulan, $tahun)
    {
        $count = $this->where([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'is_finalized' => 1
        ])->countAllResults();

        return $count > 0;
    }

    /**
     * Get available months that have results
     */
    public function getAvailableMonths()
    {
        return $this->select('bulan, tahun, MAX(is_finalized) as is_finalized, COUNT(*) as total_users')
            ->groupBy('bulan, tahun')
            ->orderBy('tahun', 'DESC')
            ->orderBy('bulan', 'DESC')
            ->findAll();
    }

    /**
     * Get statistics for a month
     */
    public function getMonthlyStats($bulan, $tahun)
    {
        $results = $this->where(['bulan' => $bulan, 'tahun' => $tahun])->findAll();

        if (empty($results)) {
            return [
                'total_users' => 0,
                'avg_score' => 0,
                'max_score' => 0,
                'min_score' => 0,
                'is_finalized' => false
            ];
        }

        $scores = array_column($results, 'total_score');
        return [
            'total_users' => count($results),
            'avg_score' => round(array_sum($scores) / count($scores), 2),
            'max_score' => max($scores),
            'min_score' => min($scores),
            'is_finalized' => (bool)$results[0]['is_finalized']
        ];
    }
}
