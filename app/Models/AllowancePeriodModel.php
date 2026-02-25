<?php

namespace App\Models;

use CodeIgniter\Model;

class AllowancePeriodModel extends Model
{
    protected $table            = 'allowance_periods';
    protected $primaryKey       = 'id_period';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pembayaran',
        'status',
        'total_pemagang',
        'total_nominal',
        'calculated_at',
        'calculated_by',
        'approved_at',
        'approved_by',
        'paid_at',
        'paid_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all periods with summary
     */
    public function getAllWithSummary()
    {
        return $this->select('allowance_periods.*, 
                             u1.nama_lengkap as calculated_by_name,
                             u2.nama_lengkap as approved_by_name,
                             u3.nama_lengkap as paid_by_name')
            ->join('users u1', 'u1.id_user = allowance_periods.calculated_by', 'left')
            ->join('users u2', 'u2.id_user = allowance_periods.approved_by', 'left')
            ->join('users u3', 'u3.id_user = allowance_periods.paid_by', 'left')
            ->orderBy('tanggal_mulai', 'DESC')
            ->findAll();
    }

    /**
     * Get active period (status != 'paid')
     */
    public function getActivePeriod()
    {
        return $this->where('status !=', 'paid')
            ->orderBy('tanggal_mulai', 'DESC')
            ->first();
    }

    /**
     * Get latest period
     */
    public function getLatestPeriod()
    {
        return $this->orderBy('tanggal_mulai', 'DESC')->first();
    }
}
