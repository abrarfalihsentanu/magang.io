<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id_role';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_role',
        'kode_role',
        'deskripsi',
        'permissions',
        'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // PERBAIKAN: Tambahkan ? untuk nullable JSON
    protected array $casts = [
        'permissions' => '?json',
        'is_active'   => 'boolean'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation - DIPERBAIKI: is_unique dihapus untuk insert juga akan dihandle di controller
    protected $validationRules = [
        'nama_role' => 'required|min_length[3]|max_length[50]',
        'kode_role' => 'required|alpha_dash|min_length[2]|max_length[20]',
        'deskripsi' => 'permit_empty|max_length[500]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nama_role' => [
            'required' => 'Nama role wajib diisi',
            'min_length' => 'Nama role minimal 3 karakter',
            'max_length' => 'Nama role maksimal 50 karakter'
        ],
        'kode_role' => [
            'required' => 'Kode role wajib diisi',
            'alpha_dash' => 'Kode role hanya boleh berisi huruf, angka, underscore dan dash',
            'min_length' => 'Kode role minimal 2 karakter',
            'max_length' => 'Kode role maksimal 20 karakter'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setDefaultPermissions', 'checkUniqueKodeInsert'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Set default permissions jika null
     */
    protected function setDefaultPermissions(array $data): array
    {
        if (!isset($data['data']['permissions']) || $data['data']['permissions'] === null) {
            $data['data']['permissions'] = json_encode([]);
        }

        return $data;
    }

    /**
     * Check unique kode_role saat insert
     */
    protected function checkUniqueKodeInsert(array $data): array
    {
        if (isset($data['data']['kode_role'])) {
            $existing = $this->where('kode_role', $data['data']['kode_role'])->first();
            if ($existing) {
                $this->errors = ['kode_role' => 'Kode role sudah digunakan'];
                return $data;
            }
        }
        return $data;
    }

    /**
     * Get all active roles
     */
    public function getActiveRoles()
    {
        return $this->where('is_active', 1)
            ->orderBy('nama_role', 'ASC')
            ->findAll();
    }

    /**
     * Get role with user count
     */
    public function getRoleWithUserCount($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('roles.*, COUNT(users.id_user) as total_users')
            ->join('users', 'users.id_role = roles.id_role', 'left')
            ->groupBy('roles.id_role');

        if ($id !== null) {
            $builder->where('roles.id_role', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->orderBy('roles.nama_role', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Check if role can be deleted
     */
    public function canDelete($id)
    {
        $userModel = new \App\Models\UserModel();
        $userCount = $userModel->where('id_role', $id)->countAllResults();

        return $userCount === 0;
    }

    /**
     * Get role by kode
     */
    public function getRoleByKode($kode)
    {
        return $this->where('kode_role', $kode)->first();
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        $role = $this->find($id);
        if (!$role) {
            return false;
        }

        return $this->update($id, [
            'is_active' => !$role['is_active']
        ]);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total' => $this->countAll(),
            'active' => $this->where('is_active', 1)->countAllResults(),
            'inactive' => $this->where('is_active', 0)->countAllResults()
        ];
    }
}
