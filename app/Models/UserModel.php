<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_role',
        'id_divisi',
        'nik',
        'nama_lengkap',
        'email',
        'no_hp',
        'password',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'foto',
        'status',
        'last_login',
        'nomor_rekening',
        'nama_bank',
        'atas_nama'
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
     * Get users with role and divisi info
     */
    public function getUserWithRoleAndDivisi($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('users.*, roles.nama_role, roles.kode_role, divisi.nama_divisi, divisi.kode_divisi')
            ->join('roles', 'users.id_role = roles.id_role', 'left')
            ->join('divisi', 'users.id_divisi = divisi.id_divisi', 'left')
            ->where('users.status !=', 'archived');

        if ($id !== null) {
            $builder->where('users.id_user', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->orderBy('users.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get users with pagination, search and role filter
     */
    public function getUserPaginated($perPage = 10, $search = null, $role = null)
    {
        $builder = $this->select('users.*, roles.nama_role, roles.kode_role, divisi.nama_divisi, divisi.kode_divisi')
            ->join('roles', 'users.id_role = roles.id_role', 'left')
            ->join('divisi', 'users.id_divisi = divisi.id_divisi', 'left')
            ->where('users.status !=', 'archived');

        if ($search) {
            $builder->groupStart()
                ->like('users.nama_lengkap', $search)
                ->orLike('users.email', $search)
                ->orLike('users.nik', $search)
                ->orLike('users.no_hp', $search)
                ->groupEnd();
        }

        if ($role) {
            $builder->where('users.id_role', $role);
        }

        return $builder->orderBy('users.created_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Get user detail with all related info
     */
    public function getUserDetail($id)
    {
        return $this->db->table($this->table)
            ->select('users.*, roles.nama_role, roles.kode_role, divisi.nama_divisi, divisi.kode_divisi')
            ->join('roles', 'users.id_role = roles.id_role', 'left')
            ->join('divisi', 'users.id_divisi = divisi.id_divisi', 'left')
            ->where('users.id_user', $id)
            ->get()
            ->getRowArray();
    }

    /**
     * Get active users only
     */
    public function getActiveUsers()
    {
        return $this->where('status', 'active')
            ->orderBy('nama_lengkap', 'ASC')
            ->findAll();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($roleId)
    {
        return $this->where('id_role', $roleId)
            ->where('status', 'active')
            ->findAll();
    }

    /**
     * Get users by divisi
     */
    public function getUsersByDivisi($divisiId)
    {
        return $this->where('id_divisi', $divisiId)
            ->where('status', 'active')
            ->findAll();
    }

    /**
     * Check if user can be deleted
     */
    public function canDelete($id)
    {
        // Cek apakah user adalah intern yang punya data di tabel interns
        $internCount = $this->db->table('interns')
            ->where('id_user', $id)
            ->countAllResults();

        if ($internCount > 0) {
            return false;
        }

        // Cek apakah user adalah mentor yang masih punya intern
        $mentorCount = $this->db->table('interns')
            ->where('id_mentor', $id)
            ->countAllResults();

        if ($mentorCount > 0) {
            return false;
        }

        return true;
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $total = $this->where('status !=', 'archived')->countAllResults();
        $active = $this->where('status', 'active')->countAllResults();
        $inactive = $this->where('status', 'inactive')->countAllResults();

        // Count by role
        $byRole = $this->db->table($this->table)
            ->select('roles.nama_role, COUNT(users.id_user) as total')
            ->join('roles', 'users.id_role = roles.id_role')
            ->where('users.status', 'active')
            ->groupBy('roles.id_role')
            ->get()
            ->getResultArray();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'by_role' => $byRole
        ];
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }

        $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';

        return $this->update($id, ['status' => $newStatus]);
    }

    /**
     * Search users
     */
    public function searchUsers($keyword)
    {
        return $this->like('nama_lengkap', $keyword)
            ->orLike('email', $keyword)
            ->orLike('nik', $keyword)
            ->where('status !=', 'archived')
            ->findAll();
    }

    /**
     * Get mentor list (users with role mentor/admin/hr)
     */
    public function getMentorList()
    {
        return $this->db->table($this->table)
            ->select('users.id_user, users.nama_lengkap, divisi.nama_divisi, roles.nama_role')
            ->join('roles', 'roles.id_role = users.id_role')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->whereIn('roles.kode_role', ['mentor', 'admin', 'hr'])
            ->where('users.status', 'active')
            ->orderBy('users.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Generate next NIK based on role
     */
    public function generateNextNIK($roleCode = 'intern')
    {
        $roleModel = new \App\Models\RoleModel();
        $role = $roleModel->where('kode_role', $roleCode)->first();

        if (!$role) {
            return null;
        }

        // Get last NIK for this role
        $lastUser = $this->where('id_role', $role['id_role'])
            ->orderBy('id_user', 'DESC')
            ->first();

        $year = date('Y');
        $rolePrefix = strtoupper(substr($roleCode, 0, 3)); // INT for intern, ADM for admin, etc.

        if ($lastUser && strpos($lastUser['nik'], $rolePrefix . $year) === 0) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastUser['nik'], -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Start from 0001 for new year or new role
            $newNumber = '0001';
        }

        return $rolePrefix . $year . $newNumber;
        // Example: INT20250001, INT20250002, ADM20250001, etc.
    }
}
