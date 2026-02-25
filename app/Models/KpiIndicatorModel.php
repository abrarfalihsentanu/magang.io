<?php

namespace App\Models;

use CodeIgniter\Model;

class KpiIndicatorModel extends Model
{
    protected $table = 'kpi_indicators';
    protected $primaryKey = 'id_indicator';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'nama_indicator',
        'kategori',
        'bobot',
        'deskripsi',
        'formula',
        'is_auto_calculate',
        'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_indicator' => 'required|min_length[5]|max_length[100]',
        'kategori' => 'required|in_list[kehadiran,aktivitas,project]',
        'bobot' => 'required|decimal|greater_than[0]|less_than_equal_to[100]',
        'is_auto_calculate' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nama_indicator' => [
            'required' => 'Nama indicator wajib diisi',
            'min_length' => 'Nama indicator minimal 5 karakter'
        ],
        'bobot' => [
            'required' => 'Bobot wajib diisi',
            'less_than_equal_to' => 'Bobot maksimal 100%'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all active indicators
     */
    public function getActiveIndicators()
    {
        return $this->where('is_active', 1)
            ->orderBy('kategori', 'ASC')
            ->orderBy('bobot', 'DESC')
            ->findAll();
    }

    /**
     * Get indicators by category
     */
    public function getByCategory($kategori)
    {
        return $this->where('kategori', $kategori)
            ->where('is_active', 1)
            ->orderBy('bobot', 'DESC')
            ->findAll();
    }

    /**
     * Get auto-calculable indicators
     */
    public function getAutoCalculableIndicators()
    {
        return $this->where('is_active', 1)
            ->where('is_auto_calculate', 1)
            ->findAll();
    }

    /**
     * Get manual assessment indicators
     */
    public function getManualIndicators()
    {
        return $this->where('is_active', 1)
            ->where('is_auto_calculate', 0)
            ->findAll();
    }

    /**
     * Validate total bobot = 100%
     */
    public function validateTotalBobot($excludeId = null)
    {
        $builder = $this->where('is_active', 1);

        if ($excludeId) {
            $builder->where('id_indicator !=', $excludeId);
        }

        $total = $builder->selectSum('bobot')->get()->getRow()->bobot ?? 0;

        return [
            'total' => $total,
            'is_valid' => $total <= 100,
            'remaining' => 100 - $total
        ];
    }

    /**
     * Get indicator statistics
     */
    public function getStatistics()
    {
        $total = $this->countAll();
        $active = $this->where('is_active', 1)->countAllResults();
        $autoCalc = $this->where('is_auto_calculate', 1)->countAllResults();
        $manual = $this->where('is_auto_calculate', 0)->countAllResults();

        $byCategory = $this->select('kategori, COUNT(*) as total, SUM(bobot) as total_bobot')
            ->where('is_active', 1)
            ->groupBy('kategori')
            ->findAll();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active,
            'auto_calculate' => $autoCalc,
            'manual' => $manual,
            'by_category' => $byCategory
        ];
    }

    /**
     * Toggle indicator status
     */
    public function toggleStatus($id)
    {
        $indicator = $this->find($id);

        if (!$indicator) {
            return false;
        }

        $newStatus = $indicator['is_active'] ? 0 : 1;

        return $this->update($id, ['is_active' => $newStatus]);
    }
}
