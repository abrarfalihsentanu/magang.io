<?php

namespace App\Models;

use CodeIgniter\Model;

class WeeklyProjectModel extends Model
{
    protected $table            = 'weekly_projects';
    protected $primaryKey       = 'id_project';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'week_number',
        'tahun',
        'periode_mulai',
        'periode_selesai',
        'judul_project',
        'tipe_project',
        'deskripsi',
        'progress',
        'deliverables',
        'attachment',
        'self_rating',
        'mentor_rating',
        'feedback_mentor',
        'status_submission',
        'assessed_by',
        'assessed_at'
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
        'week_number' => 'required|integer|greater_than[0]|less_than[54]',
        'tahun' => 'required|integer|min_length[4]|max_length[4]',
        'judul_project' => 'required|min_length[10]|max_length[200]',
        'tipe_project' => 'required|in_list[inisiatif,assigned]',
        'deskripsi' => 'required|min_length[100]',
        'progress' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'self_rating' => 'permit_empty|decimal|greater_than_equal_to[1.0]|less_than_equal_to[5.0]'
    ];

    protected $validationMessages = [
        'id_user' => [
            'required' => 'User ID wajib diisi',
            'integer' => 'User ID harus berupa angka'
        ],
        'week_number' => [
            'required' => 'Week number wajib diisi',
            'greater_than' => 'Week number tidak valid',
            'less_than' => 'Week number tidak valid'
        ],
        'judul_project' => [
            'required' => 'Judul project wajib diisi',
            'min_length' => 'Judul project minimal 10 karakter',
            'max_length' => 'Judul project maksimal 200 karakter'
        ],
        'deskripsi' => [
            'required' => 'Deskripsi wajib diisi',
            'min_length' => 'Deskripsi minimal 100 karakter'
        ],
        'progress' => [
            'required' => 'Progress wajib diisi',
            'greater_than_equal_to' => 'Progress minimal 0%',
            'less_than_equal_to' => 'Progress maksimal 100%'
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
     * Get projects with user details
     */
    public function getProjectsWithUser($userId = null, $filters = [])
    {
        $builder = $this->db->table($this->table)
            ->select('weekly_projects.*, 
                     users.nama_lengkap, 
                     users.nik, 
                     divisi.nama_divisi,
                     assessor.nama_lengkap as assessor_name')
            ->join('users', 'users.id_user = weekly_projects.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('users as assessor', 'assessor.id_user = weekly_projects.assessed_by', 'left');

        if ($userId) {
            $builder->where('weekly_projects.id_user', $userId);
        }

        // Apply filters
        if (isset($filters['tahun'])) {
            $builder->where('weekly_projects.tahun', $filters['tahun']);
        }

        if (isset($filters['week_number'])) {
            $builder->where('weekly_projects.week_number', $filters['week_number']);
        }

        if (isset($filters['status'])) {
            $builder->where('weekly_projects.status', $filters['status']);
        }

        if (isset($filters['tipe_project'])) {
            $builder->where('weekly_projects.tipe_project', $filters['tipe_project']);
        }

        if (isset($filters['divisi'])) {
            $builder->where('users.id_divisi', $filters['divisi']);
        }

        return $builder->orderBy('weekly_projects.tahun', 'DESC')
            ->orderBy('weekly_projects.week_number', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get projects with pagination
     */
    public function getProjectsPaginated($userId = null, $filters = [], $perPage = 10)
    {
        $this->select('weekly_projects.*, 
                     users.nama_lengkap, 
                     users.nik, 
                     divisi.nama_divisi,
                     assessor.nama_lengkap as assessor_name')
            ->join('users', 'users.id_user = weekly_projects.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('users as assessor', 'assessor.id_user = weekly_projects.assessed_by', 'left');

        if ($userId) {
            $this->where('weekly_projects.id_user', $userId);
        }

        // Apply filters
        if (!empty($filters['tahun'])) {
            $this->where('weekly_projects.tahun', $filters['tahun']);
        }

        if (!empty($filters['week_number'])) {
            $this->where('weekly_projects.week_number', $filters['week_number']);
        }

        if (!empty($filters['status'])) {
            $this->where('weekly_projects.status_submission', $filters['status']);
        }

        if (!empty($filters['tipe_project'])) {
            $this->where('weekly_projects.tipe_project', $filters['tipe_project']);
        }

        if (!empty($filters['divisi'])) {
            $this->where('users.id_divisi', $filters['divisi']);
        }

        if (!empty($filters['search'])) {
            $this->groupStart()
                ->like('users.nama_lengkap', $filters['search'])
                ->orLike('weekly_projects.judul_project', $filters['search'])
                ->orLike('weekly_projects.deskripsi', $filters['search'])
                ->groupEnd();
        }

        return $this->orderBy('weekly_projects.tahun', 'DESC')
            ->orderBy('weekly_projects.week_number', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Check if user already submitted project for current week
     */
    public function hasSubmittedThisWeek($userId, $weekNumber = null, $year = null)
    {
        $weekNumber = $weekNumber ?? date('W');
        $year = $year ?? date('Y');

        return $this->where('id_user', $userId)
            ->where('week_number', $weekNumber)
            ->where('tahun', $year)
            ->countAllResults() > 0;
    }

    /**
     * Get current week info
     */
    public function getCurrentWeekInfo()
    {
        $weekNumber = (int) date('W');
        $year = (int) date('Y');
        $monday = date('Y-m-d', strtotime('monday this week'));
        $sunday = date('Y-m-d', strtotime('sunday this week'));

        return [
            'week_number' => $weekNumber,
            'tahun' => $year,
            'periode_mulai' => $monday,
            'periode_selesai' => $sunday,
            'week_label' => "Week $weekNumber - $year",
            'periode_label' => date('d M', strtotime($monday)) . ' - ' . date('d M Y', strtotime($sunday))
        ];
    }

    /**
     * Get week info by week number and year
     */
    public function getWeekInfo($weekNumber, $year)
    {
        $dto = new \DateTime();
        $dto->setISODate($year, $weekNumber);
        $monday = $dto->format('Y-m-d');

        $dto->modify('+6 days');
        $sunday = $dto->format('Y-m-d');

        return [
            'week_number' => $weekNumber,
            'tahun' => $year,
            'periode_mulai' => $monday,
            'periode_selesai' => $sunday,
            'week_label' => "Week $weekNumber - $year",
            'periode_label' => date('d M', strtotime($monday)) . ' - ' . date('d M Y', strtotime($sunday))
        ];
    }

    /**
     * Get statistics for user
     */
    public function getUserStatistics($userId, $year = null)
    {
        $builder = $this->where('id_user', $userId);

        if ($year) {
            $builder->where('tahun', $year);
        }

        $total = $builder->countAllResults(false);
        $draft = $builder->where('status_submission', 'draft')->countAllResults(false);
        $submitted = $builder->where('status_submission', 'submitted')->countAllResults(false);
        $assessed = $builder->where('status_submission', 'assessed')->countAllResults();

        // Get average ratings
        $ratings = $this->select('AVG(self_rating) as avg_self, AVG(mentor_rating) as avg_mentor')
            ->where('id_user', $userId)
            ->where('status_submission', 'assessed')
            ->first();

        return [
            'total' => $total,
            'draft' => $draft,
            'submitted' => $submitted,
            'assessed' => $assessed,
            'avg_self_rating' => $ratings['avg_self'] ? round($ratings['avg_self'], 2) : 0,
            'avg_mentor_rating' => $ratings['avg_mentor'] ? round($ratings['avg_mentor'], 2) : 0
        ];
    }

    /**
     * Get pending projects for mentor
     */
    public function getPendingForMentor($mentorId)
    {
        return $this->db->table($this->table)
            ->select('weekly_projects.*, 
                     users.nama_lengkap, 
                     users.nik,
                     divisi.nama_divisi,
                     interns.universitas')
            ->join('users', 'users.id_user = weekly_projects.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('interns', 'interns.id_user = weekly_projects.id_user')
            ->where('weekly_projects.status_submission', 'submitted')
            ->where('interns.id_mentor', $mentorId)
            ->orderBy('weekly_projects.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get admin dashboard statistics
     */
    public function getAdminStatistics($filters = [])
    {
        $builder = $this->db->table($this->table);

        if (isset($filters['tahun'])) {
            $builder->where('tahun', $filters['tahun']);
        }

        if (isset($filters['week_number'])) {
            $builder->where('week_number', $filters['week_number']);
        }

        $total = $builder->countAllResults(false);
        $pending = $builder->where('status_submission', 'submitted')->countAllResults(false);
        $assessed = $builder->where('status_submission', 'assessed')->countAllResults();

        // Get projects by type
        $byType = $this->select('tipe_project, COUNT(*) as total')
            ->groupBy('tipe_project')
            ->findAll();

        // Get average ratings
        $ratings = $this->select('AVG(self_rating) as avg_self, AVG(mentor_rating) as avg_mentor')
            ->where('status_submission', 'assessed')
            ->first();

        return [
            'total' => $total,
            'pending' => $pending,
            'assessed' => $assessed,
            'by_type' => $byType,
            'avg_self_rating' => $ratings['avg_self'] ? round($ratings['avg_self'], 2) : 0,
            'avg_mentor_rating' => $ratings['avg_mentor'] ? round($ratings['avg_mentor'], 2) : 0
        ];
    }

    /**
     * Check if project can be edited
     */
    public function canEdit($projectId, $userId)
    {
        $project = $this->find($projectId);

        if (!$project) {
            return false;
        }

        // Only owner can edit
        if ($project['id_user'] != $userId) {
            return false;
        }

        // Only draft can be edited
        if ($project['status_submission'] != 'draft') {
            return false;
        }

        return true;
    }

    /**
     * Check if project can be deleted
     */
    public function canDelete($projectId, $userId)
    {
        $project = $this->find($projectId);

        if (!$project) {
            return false;
        }

        // Only owner can delete
        if ($project['id_user'] != $userId) {
            return false;
        }

        // Only draft can be deleted
        if ($project['status_submission'] != 'draft') {
            return false;
        }

        return true;
    }

    /**
     * Get top performers based on mentor rating
     */
    public function getTopPerformers($limit = 5, $year = null)
    {
        $builder = $this->db->table($this->table)
            ->select('weekly_projects.id_user, 
                     users.nama_lengkap, 
                     users.nik,
                     divisi.nama_divisi,
                     AVG(weekly_projects.mentor_rating) as avg_rating,
                     COUNT(*) as total_projects')
            ->join('users', 'users.id_user = weekly_projects.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->where('weekly_projects.status_submission', 'assessed')
            ->where('weekly_projects.mentor_rating IS NOT NULL');

        if ($year) {
            $builder->where('weekly_projects.tahun', $year);
        }

        return $builder->groupBy('weekly_projects.id_user')
            ->orderBy('avg_rating', 'DESC')
            ->orderBy('total_projects', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get rating distribution
     */
    public function getRatingDistribution($year = null)
    {
        $builder = $this->db->table($this->table)
            ->select('
                CASE 
                    WHEN mentor_rating BETWEEN 4.6 AND 5.0 THEN "Excellent (4.6-5.0)"
                    WHEN mentor_rating BETWEEN 4.0 AND 4.5 THEN "Good (4.0-4.5)"
                    WHEN mentor_rating BETWEEN 3.0 AND 3.9 THEN "Average (3.0-3.9)"
                    WHEN mentor_rating BETWEEN 2.0 AND 2.9 THEN "Below Average (2.0-2.9)"
                    WHEN mentor_rating BETWEEN 1.0 AND 1.9 THEN "Poor (1.0-1.9)"
                END as rating_category,
                COUNT(*) as total,
                MAX(mentor_rating) as max_rating
            ')
            ->where('status_submission', 'assessed')
            ->where('mentor_rating IS NOT NULL');

        if ($year) {
            $builder->where('tahun', $year);
        }

        return $builder->groupBy('rating_category')
            ->orderBy('max_rating', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Check if it's still Friday (deadline to submit)
     */
    public function canSubmitThisWeek()
    {
        $today = date('N'); // 1 (Monday) to 7 (Sunday)
        return $today <= 5; // Monday to Friday
    }
}
