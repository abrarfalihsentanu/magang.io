<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id_log';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'action',
        'module',
        'record_id',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'old_data' => 'json',
        'new_data' => 'json'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

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
     * Get logs with user info
     */
    public function getLogsWithUser($limit = 100, $offset = 0)
    {
        return $this->select('audit_logs.*, users.nama_lengkap, users.email')
            ->join('users', 'users.id_user = audit_logs.id_user', 'left')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->findAll($limit, $offset);
    }

    /**
     * Get logs by module
     */
    public function getLogsByModule($module, $limit = 50)
    {
        return $this->select('audit_logs.*, users.nama_lengkap')
            ->join('users', 'users.id_user = audit_logs.id_user', 'left')
            ->where('audit_logs.module', $module)
            ->orderBy('audit_logs.created_at', 'DESC')
            ->findAll($limit);
    }

    /**
     * Get logs by user
     */
    public function getLogsByUser($userId, $limit = 50)
    {
        return $this->where('id_user', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity($limit = 10)
    {
        return $this->select('audit_logs.*, users.nama_lengkap, users.foto')
            ->join('users', 'users.id_user = audit_logs.id_user', 'left')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->findAll($limit);
    }

    /**
     * Clean old logs (older than X days)
     */
    public function cleanOldLogs($days = 90)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $date)->delete();
    }

    /**
     * Get filtered & paginated logs with user info
     */
    public function getFilteredLogs(array $filters = [], int $perPage = 25, int $page = 1): array
    {
        $builder = $this->db->table('audit_logs al')
            ->select('al.*, u.nama_lengkap, u.email, u.foto')
            ->join('users u', 'u.id_user = al.id_user', 'left')
            ->orderBy('al.created_at', 'DESC');

        if (!empty($filters['search'])) {
            $s = $this->db->escapeLikeString($filters['search']);
            $builder->groupStart()
                ->like('u.nama_lengkap', $s)
                ->orLike('al.action', $s)
                ->orLike('al.module', $s)
                ->orLike('al.ip_address', $s)
                ->groupEnd();
        }
        if (!empty($filters['module']))    $builder->where('al.module', $filters['module']);
        if (!empty($filters['action']))    $builder->where('al.action', $filters['action']);
        if (!empty($filters['user_id']))   $builder->where('al.id_user', $filters['user_id']);
        if (!empty($filters['date_from'])) $builder->where('al.created_at >=', $filters['date_from'] . ' 00:00:00');
        if (!empty($filters['date_to']))   $builder->where('al.created_at <=', $filters['date_to'] . ' 23:59:59');

        $offset = ($page - 1) * $perPage;
        return $builder->limit($perPage, $offset)->get()->getResultArray();
    }

    /**
     * Count filtered logs for pagination
     */
    public function countFiltered(array $filters = []): int
    {
        $builder = $this->db->table('audit_logs al')
            ->join('users u', 'u.id_user = al.id_user', 'left');

        if (!empty($filters['search'])) {
            $s = $this->db->escapeLikeString($filters['search']);
            $builder->groupStart()
                ->like('u.nama_lengkap', $s)
                ->orLike('al.action', $s)
                ->orLike('al.module', $s)
                ->orLike('al.ip_address', $s)
                ->groupEnd();
        }
        if (!empty($filters['module']))    $builder->where('al.module', $filters['module']);
        if (!empty($filters['action']))    $builder->where('al.action', $filters['action']);
        if (!empty($filters['user_id']))   $builder->where('al.id_user', $filters['user_id']);
        if (!empty($filters['date_from'])) $builder->where('al.created_at >=', $filters['date_from'] . ' 00:00:00');
        if (!empty($filters['date_to']))   $builder->where('al.created_at <=', $filters['date_to'] . ' 23:59:59');

        return (int)$builder->countAllResults();
    }

    /**
     * Get distinct modules for filter dropdown
     */
    public function getDistinctModules(): array
    {
        return $this->db->table('audit_logs')
            ->select('module')
            ->distinct()
            ->orderBy('module', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get distinct actions for filter dropdown
     */
    public function getDistinctActions(): array
    {
        return $this->db->table('audit_logs')
            ->select('action')
            ->distinct()
            ->orderBy('action', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get summary statistics for dashboard cards
     */
    public function getStats(): array
    {
        $today    = date('Y-m-d');
        $db       = $this->db;
        return [
            'total'        => (int)$db->table('audit_logs')->countAllResults(),
            'today'        => (int)$db->table('audit_logs')->where('DATE(created_at)', $today)->countAllResults(),
            'unique_users' => (int)$db->table('audit_logs')->select('COUNT(DISTINCT id_user) as cnt')->get()->getRow()->cnt,
            'modules'      => (int)$db->table('audit_logs')->select('COUNT(DISTINCT module) as cnt')->get()->getRow()->cnt,
        ];
    }
}
