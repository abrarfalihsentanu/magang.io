<?php

namespace App\Models;

use CodeIgniter\Model;

class KpiAssessmentModel extends Model
{
    protected $table            = 'kpi_assessments';
    protected $primaryKey       = 'id_assessment';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'id_indicator',
        'bulan',
        'tahun',
        'nilai_raw',
        'nilai_weighted',
        'penilai_id',
        'catatan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get assessments for a user in a specific month
     */
    public function getUserMonthlyAssessments($userId, $bulan, $tahun)
    {
        return $this->select('kpi_assessments.*, kpi_indicators.nama_indicator, kpi_indicators.kategori, kpi_indicators.bobot, kpi_indicators.is_auto_calculate')
            ->join('kpi_indicators', 'kpi_indicators.id_indicator = kpi_assessments.id_indicator')
            ->where('kpi_assessments.id_user', $userId)
            ->where('kpi_assessments.bulan', $bulan)
            ->where('kpi_assessments.tahun', $tahun)
            ->orderBy('kpi_indicators.kategori', 'ASC')
            ->orderBy('kpi_indicators.bobot', 'DESC')
            ->findAll();
    }

    /**
     * Get or create assessment for a specific user/indicator/month
     */
    public function getOrCreate($userId, $indicatorId, $bulan, $tahun)
    {
        $existing = $this->where([
            'id_user' => $userId,
            'id_indicator' => $indicatorId,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->first();

        return $existing;
    }

    /**
     * Upsert assessment (insert or update)
     */
    public function upsertAssessment($data)
    {
        $existing = $this->where([
            'id_user' => $data['id_user'],
            'id_indicator' => $data['id_indicator'],
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun']
        ])->first();

        if ($existing) {
            return $this->update($existing['id_assessment'], $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Get all assessments for a month with user info (for monthly overview)
     */
    public function getMonthlyOverview($bulan, $tahun)
    {
        return $this->db->table('kpi_assessments a')
            ->select('a.id_user, u.nama_lengkap, u.nik, d.nama_divisi, 
                      SUM(a.nilai_weighted) as total_score,
                      COUNT(a.id_assessment) as indicator_count')
            ->join('users u', 'u.id_user = a.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('a.bulan', $bulan)
            ->where('a.tahun', $tahun)
            ->groupBy('a.id_user')
            ->orderBy('total_score', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Check if manual assessments are complete for a user/month
     */
    public function hasManualAssessments($userId, $bulan, $tahun)
    {
        $count = $this->db->table('kpi_assessments a')
            ->join('kpi_indicators i', 'i.id_indicator = a.id_indicator')
            ->where('a.id_user', $userId)
            ->where('a.bulan', $bulan)
            ->where('a.tahun', $tahun)
            ->where('i.is_auto_calculate', 0)
            ->countAllResults();

        $totalManual = $this->db->table('kpi_indicators')
            ->where('is_auto_calculate', 0)
            ->where('is_active', 1)
            ->countAllResults();

        return $count >= $totalManual;
    }

    /**
     * Get mentor's pending assessments (interns without manual assessment this month)
     */
    public function getMentorPendingAssessments($mentorId, $bulan, $tahun)
    {
        // Get mentees
        $mentees = $this->db->table('interns i')
            ->select('i.id_user, u.nama_lengkap, u.nik, d.nama_divisi')
            ->join('users u', 'u.id_user = i.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('i.id_mentor', $mentorId)
            ->where('i.status_magang', 'active')
            ->where('u.status', 'active')
            ->get()
            ->getResultArray();

        foreach ($mentees as &$mentee) {
            $mentee['has_manual_assessment'] = $this->hasManualAssessments($mentee['id_user'], $bulan, $tahun);
        }

        return $mentees;
    }
}
