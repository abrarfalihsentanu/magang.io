<?php

namespace App\Models;

use CodeIgniter\Model;

class AllowanceSlipModel extends Model
{
    protected $table            = 'allowance_slips';
    protected $primaryKey       = 'id_slip';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_allowance',
        'nomor_slip',
        'file_path',
        'generated_at',
        'generated_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
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
     * Get slip by allowance ID
     */
    public function getSlipByAllowance($idAllowance)
    {
        return $this->where('id_allowance', $idAllowance)->first();
    }

    /**
     * Generate unique slip number
     */
    public function generateSlipNumber()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "SLIP/{$year}/{$month}/";

        // Get last number
        $lastSlip = $this->like('nomor_slip', $prefix, 'after')
            ->orderBy('nomor_slip', 'DESC')
            ->first();

        if ($lastSlip) {
            // Extract number from last slip
            $parts = explode('/', $lastSlip['nomor_slip']);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
