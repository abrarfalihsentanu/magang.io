<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyActivityModel extends Model
{
    protected $table            = 'daily_activities';
    protected $primaryKey       = 'id_activity';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'judul_aktivitas',
        'deskripsi',
        'kategori',
        'attachment',
        'status_approval',
        'approved_by',
        'approved_at',
        'catatan_mentor'
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
    protected $validationRules = [
        'id_user' => 'required|integer',
        'tanggal' => 'required|valid_date',
        'judul_aktivitas' => 'required|min_length[5]|max_length[200]',
        'deskripsi' => 'required|min_length[50]',
        'kategori' => 'required|in_list[learning,task,meeting,training,other]'
    ];

    protected $validationMessages = [
        'id_user' => [
            'required' => 'User ID wajib diisi',
            'integer' => 'User ID harus berupa angka'
        ],
        'tanggal' => [
            'required' => 'Tanggal wajib diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'judul_aktivitas' => [
            'required' => 'Judul aktivitas wajib diisi',
            'min_length' => 'Judul aktivitas minimal 5 karakter',
            'max_length' => 'Judul aktivitas maksimal 200 karakter'
        ],
        'deskripsi' => [
            'required' => 'Deskripsi wajib diisi',
            'min_length' => 'Deskripsi minimal 50 karakter'
        ],
        'kategori' => [
            'required' => 'Kategori wajib dipilih',
            'in_list' => 'Kategori tidak valid'
        ]
    ];

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
     * Get activities with user details
     */
    public function getActivitiesWithUser($userId = null, $filters = [])
    {
        $builder = $this->db->table($this->table)
            ->select('daily_activities.*, 
                     users.nama_lengkap, 
                     users.nik, 
                     divisi.nama_divisi,
                     mentor.nama_lengkap as mentor_name')
            ->join('users', 'users.id_user = daily_activities.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('users as mentor', 'mentor.id_user = daily_activities.approved_by', 'left');

        if ($userId) {
            $builder->where('daily_activities.id_user', $userId);
        }

        // Apply filters
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $builder->where('daily_activities.tanggal >=', $filters['start_date']);
            $builder->where('daily_activities.tanggal <=', $filters['end_date']);
        }

        if (isset($filters['status'])) {
            $builder->where('daily_activities.status_approval', $filters['status']);
        }

        if (isset($filters['kategori'])) {
            $builder->where('daily_activities.kategori', $filters['kategori']);
        }

        if (isset($filters['divisi'])) {
            $builder->where('users.id_divisi', $filters['divisi']);
        }

        return $builder->orderBy('daily_activities.tanggal', 'DESC')
            ->orderBy('daily_activities.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get activities with pagination
     */
    public function getActivitiesPaginated($userId = null, $filters = [], $perPage = 10)
    {
        $this->select('daily_activities.*, 
                     users.nama_lengkap, 
                     users.nik, 
                     divisi.nama_divisi,
                     mentor.nama_lengkap as mentor_name')
            ->join('users', 'users.id_user = daily_activities.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('users as mentor', 'mentor.id_user = daily_activities.approved_by', 'left');

        if ($userId) {
            $this->where('daily_activities.id_user', $userId);
        }

        // Apply filters
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->where('daily_activities.tanggal >=', $filters['start_date']);
            $this->where('daily_activities.tanggal <=', $filters['end_date']);
        }

        if (!empty($filters['status'])) {
            $this->where('daily_activities.status_approval', $filters['status']);
        }

        if (!empty($filters['kategori'])) {
            $this->where('daily_activities.kategori', $filters['kategori']);
        }

        if (!empty($filters['divisi'])) {
            $this->where('users.id_divisi', $filters['divisi']);
        }

        if (!empty($filters['search'])) {
            $this->groupStart()
                ->like('users.nama_lengkap', $filters['search'])
                ->orLike('daily_activities.judul_aktivitas', $filters['search'])
                ->orLike('daily_activities.deskripsi', $filters['search'])
                ->groupEnd();
        }

        return $this->orderBy('daily_activities.tanggal', 'DESC')
            ->orderBy('daily_activities.created_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Get activities by month for calendar
     */
    public function getActivitiesForCalendar($userId, $year, $month)
    {
        $yearMonth = sprintf('%04d-%02d', $year, $month);

        return $this->where('id_user', $userId)
            ->where("DATE_FORMAT(tanggal, '%Y-%m')", $yearMonth)
            ->orderBy('tanggal', 'ASC')
            ->findAll();
    }

    /**
     * Get statistics for user
     */
    public function getUserStatistics($userId, $month = null)
    {
        $builder = $this->where('id_user', $userId);

        if ($month) {
            $builder->where("DATE_FORMAT(tanggal, '%Y-%m')", $month);
        }

        $total = $builder->countAllResults(false);
        $draft = $builder->where('status_approval', 'draft')->countAllResults(false);
        $submitted = $builder->where('status_approval', 'submitted')->countAllResults(false);
        $approved = $builder->where('status_approval', 'approved')->countAllResults(false);
        $rejected = $builder->where('status_approval', 'rejected')->countAllResults();

        return [
            'total' => $total,
            'draft' => $draft,
            'submitted' => $submitted,
            'approved' => $approved,
            'rejected' => $rejected
        ];
    }

    /**
     * Get pending activities for mentor
     */
    public function getPendingForMentor($mentorId)
    {
        return $this->db->table($this->table)
            ->select('daily_activities.*, 
                     users.nama_lengkap, 
                     users.nik,
                     interns.universitas')
            ->join('users', 'users.id_user = daily_activities.id_user')
            ->join('interns', 'interns.id_user = daily_activities.id_user')
            ->where('daily_activities.status_approval', 'submitted')
            ->where('interns.id_mentor', $mentorId)
            ->orderBy('daily_activities.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get admin dashboard statistics
     */
    public function getAdminStatistics($filters = [])
    {
        $builder = $this->db->table($this->table);

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $builder->where('tanggal >=', $filters['start_date']);
            $builder->where('tanggal <=', $filters['end_date']);
        }

        $total = $builder->countAllResults(false);
        $pending = $builder->where('status_approval', 'submitted')->countAllResults(false);
        $approved = $builder->where('status_approval', 'approved')->countAllResults(false);
        $rejected = $builder->where('status_approval', 'rejected')->countAllResults();

        // Get activities by category
        $byCategory = $this->select('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->findAll();

        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'by_category' => $byCategory
        ];
    }

    /**
     * Check if activity can be edited
     */
    public function canEdit($activityId, $userId)
    {
        $activity = $this->find($activityId);

        if (!$activity) {
            return false;
        }

        // Only owner can edit
        if ($activity['id_user'] != $userId) {
            return false;
        }

        // Only draft can be edited
        if ($activity['status_approval'] != 'draft') {
            return false;
        }

        return true;
    }

    /**
     * Check if activity can be deleted
     */
    public function canDelete($activityId, $userId)
    {
        $activity = $this->find($activityId);

        if (!$activity) {
            return false;
        }

        // Only owner can delete
        if ($activity['id_user'] != $userId) {
            return false;
        }

        // Only draft can be deleted
        if ($activity['status_approval'] != 'draft') {
            return false;
        }

        return true;
    }

    /**
     * Get activities count by status for period
     */
    public function getCountByStatus($startDate, $endDate, $userId = null)
    {
        $builder = $this->db->table($this->table)
            ->select('status_approval, COUNT(*) as total')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate);

        if ($userId) {
            $builder->where('id_user', $userId);
        }

        return $builder->groupBy('status_approval')
            ->get()
            ->getResultArray();
    }

    /**
     * Get most active interns
     */
    public function getMostActiveInterns($limit = 5, $month = null)
    {
        $builder = $this->db->table($this->table)
            ->select('daily_activities.id_user, 
                     users.nama_lengkap, 
                     users.nik,
                     divisi.nama_divisi,
                     COUNT(*) as total_activities')
            ->join('users', 'users.id_user = daily_activities.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->where('daily_activities.status_approval', 'approved');

        if ($month) {
            $builder->where("DATE_FORMAT(daily_activities.tanggal, '%Y-%m')", $month);
        }

        return $builder->groupBy('daily_activities.id_user')
            ->orderBy('total_activities', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
