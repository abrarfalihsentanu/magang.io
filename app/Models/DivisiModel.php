<?php

namespace App\Models;

use CodeIgniter\Model;

class DivisiModel extends Model
{
    protected $table            = 'divisi';
    protected $primaryKey       = 'id_divisi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_divisi',
        'kode_divisi',
        'kepala_divisi',
        'deskripsi',
        'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama_divisi' => 'required|min_length[3]|max_length[100]',
        'kode_divisi' => 'required|alpha_numeric|min_length[2]|max_length[20]',
        'kepala_divisi' => 'permit_empty|max_length[100]',
        'deskripsi' => 'permit_empty|max_length[500]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nama_divisi' => [
            'required' => 'Nama divisi wajib diisi',
            'min_length' => 'Nama divisi minimal 3 karakter',
            'max_length' => 'Nama divisi maksimal 100 karakter'
        ],
        'kode_divisi' => [
            'required' => 'Kode divisi wajib diisi',
            'alpha_numeric' => 'Kode divisi hanya boleh berisi huruf dan angka',
            'min_length' => 'Kode divisi minimal 2 karakter',
            'max_length' => 'Kode divisi maksimal 20 karakter'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['checkUniqueKodeInsert'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Check unique kode_divisi saat insert
     */
    protected function checkUniqueKodeInsert(array $data): array
    {
        if (isset($data['data']['kode_divisi'])) {
            $existing = $this->where('kode_divisi', $data['data']['kode_divisi'])->first();
            if ($existing) {
                $this->errors = ['kode_divisi' => 'Kode divisi sudah digunakan'];
                return $data;
            }
        }
        return $data;
    }

    /**
     * Get all active divisi
     */
    public function getActiveDivisi()
    {
        return $this->where('is_active', 1)
            ->orderBy('nama_divisi', 'ASC')
            ->findAll();
    }

    /**
     * Get divisi with user count
     */
    public function getDivisiWithUserCount($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('divisi.*, COUNT(users.id_user) as total_users')
            ->join('users', 'users.id_divisi = divisi.id_divisi', 'left')
            ->groupBy('divisi.id_divisi');

        if ($id !== null) {
            $builder->where('divisi.id_divisi', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->orderBy('divisi.nama_divisi', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Check if divisi can be deleted
     */
    public function canDelete($id)
    {
        $userModel = new \App\Models\UserModel();
        $userCount = $userModel->where('id_divisi', $id)->countAllResults();

        return $userCount === 0;
    }

    /**
     * Get divisi by kode
     */
    public function getDivisiByKode($kode)
    {
        return $this->where('kode_divisi', $kode)->first();
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        $divisi = $this->find($id);
        if (!$divisi) {
            return false;
        }

        return $this->update($id, [
            'is_active' => !$divisi['is_active']
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
