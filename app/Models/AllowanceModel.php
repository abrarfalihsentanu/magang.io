<?php

namespace App\Models;

use CodeIgniter\Model;

class AllowanceModel extends Model
{
    protected $table            = 'allowances';
    protected $primaryKey       = 'id_allowance';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_period',
        'id_user',
        'total_hari_kerja',
        'total_hadir',
        'total_alpha',
        'total_izin',
        'total_sakit',
        'rate_per_hari',
        'total_uang_saku',
        'nomor_rekening',
        'nama_bank',
        'atas_nama',
        'status_pembayaran',
        'tanggal_transfer',
        'bukti_transfer',
        'catatan'
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
     * Get allowances by period with user info
     */
    public function getAllowancesByPeriod($idPeriod)
    {
        return $this->select('allowances.*, 
                             u.nama_lengkap, 
                             u.nik, 
                             u.email,
                             d.nama_divisi,
                             i.universitas')
            ->join('users u', 'u.id_user = allowances.id_user')
            ->join('interns i', 'i.id_user = allowances.id_user', 'left')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('allowances.id_period', $idPeriod)
            ->orderBy('u.nama_lengkap', 'ASC')
            ->findAll();
    }

    /**
     * Get allowances by user (for intern)
     */
    public function getAllowancesByUser($idUser)
    {
        return $this->select('allowances.*, 
                             ap.nama_periode,
                             ap.tanggal_mulai,
                             ap.tanggal_selesai,
                             ap.tanggal_pembayaran,
                             ap.status as status_periode')
            ->join('allowance_periods ap', 'ap.id_period = allowances.id_period')
            ->where('allowances.id_user', $idUser)
            ->orderBy('ap.tanggal_mulai', 'DESC')
            ->findAll();
    }

    /**
     * Get allowances pending payment (for finance)
     */
    public function getPendingPayments()
    {
        return $this->select('allowances.*, 
                             u.nama_lengkap, 
                             u.nik,
                             d.nama_divisi,
                             ap.nama_periode')
            ->join('users u', 'u.id_user = allowances.id_user')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->join('allowance_periods ap', 'ap.id_period = allowances.id_period')
            ->where('allowances.status_pembayaran', 'pending')
            ->orderBy('u.nama_lengkap', 'ASC')
            ->findAll();
    }

    /**
     * Check if allowance exists for user in period
     */
    public function existsForUserPeriod($idUser, $idPeriod)
    {
        return $this->where('id_user', $idUser)
            ->where('id_period', $idPeriod)
            ->first() !== null;
    }
}
