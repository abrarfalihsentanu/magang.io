<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveModel extends Model
{
    protected $table            = 'leaves';
    protected $primaryKey       = 'id_leave';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'alasan',
        'dokumen_pendukung',
        'status_approval',
        'approved_by',
        'approved_at',
        'catatan_approval'
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
        'jenis_cuti' => 'required|in_list[cuti,izin,sakit]',
        'tanggal_mulai' => 'required|valid_date',
        'tanggal_selesai' => 'required|valid_date',
        'jumlah_hari' => 'required|integer',
        'alasan' => 'required|min_length[10]'
    ];

    protected $validationMessages = [
        'id_user' => [
            'required' => 'User ID wajib diisi',
            'integer' => 'User ID harus berupa angka'
        ],
        'jenis_cuti' => [
            'required' => 'Jenis cuti wajib dipilih',
            'in_list' => 'Jenis cuti tidak valid'
        ],
        'tanggal_mulai' => [
            'required' => 'Tanggal mulai wajib diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'tanggal_selesai' => [
            'required' => 'Tanggal selesai wajib diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'jumlah_hari' => [
            'required' => 'Jumlah hari wajib diisi',
            'integer' => 'Jumlah hari harus berupa angka'
        ],
        'alasan' => [
            'required' => 'Alasan wajib diisi',
            'min_length' => 'Alasan minimal 10 karakter'
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
     * Get leaves with user details
     */
    public function getLeavesWithUser($userId = null)
    {
        $builder = $this->db->table($this->table)
            ->select('leaves.*, 
                     users.nama_lengkap, 
                     users.nik, 
                     users.email,
                     approver.nama_lengkap as approver_name')
            ->join('users', 'users.id_user = leaves.id_user')
            ->join('users as approver', 'approver.id_user = leaves.approved_by', 'left');

        if ($userId) {
            $builder->where('leaves.id_user', $userId);
        }

        return $builder->orderBy('leaves.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get pending leaves for approval
     */
    public function getPendingLeaves($mentorId = null)
    {
        $builder = $this->db->table($this->table)
            ->select('leaves.*, users.nama_lengkap, users.nik')
            ->join('users', 'users.id_user = leaves.id_user')
            ->where('leaves.status_approval', 'pending');

        if ($mentorId) {
            $builder->join('interns', 'interns.id_user = leaves.id_user')
                ->where('interns.id_mentor', $mentorId);
        }

        return $builder->orderBy('leaves.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get leave statistics for user
     */
    public function getUserLeaveStats($userId, $year = null)
    {
        $builder = $this->where('id_user', $userId)
            ->where('status_approval', 'approved');

        if ($year) {
            $builder->where("YEAR(tanggal_mulai)", $year);
        }

        $leaves = $builder->findAll();

        $stats = [
            'total_cuti' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_hari' => 0
        ];

        foreach ($leaves as $leave) {
            $stats['total_' . $leave['jenis_cuti']] += $leave['jumlah_hari'];
            $stats['total_hari'] += $leave['jumlah_hari'];
        }

        return $stats;
    }

    /**
     * Check if user has overlapping leave
     */
    public function hasOverlappingLeave($userId, $startDate, $endDate, $excludeId = null)
    {
        $builder = $this->where('id_user', $userId)
            ->where('status_approval', 'approved')
            ->groupStart()
            ->where('tanggal_mulai <=', $endDate)
            ->where('tanggal_selesai >=', $startDate)
            ->groupEnd();

        if ($excludeId) {
            $builder->where('id_leave !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get approved leaves in date range
     */
    public function getApprovedLeavesInRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('leaves.*, users.nama_lengkap, users.nik')
            ->join('users', 'users.id_user = leaves.id_user')
            ->where('leaves.status_approval', 'approved')
            ->where('leaves.tanggal_mulai <=', $endDate)
            ->where('leaves.tanggal_selesai >=', $startDate)
            ->get()
            ->getResultArray();
    }

    /**
     * Get leave calendar data
     */
    public function getLeaveCalendar($userId, $year, $month)
    {
        $yearMonth = sprintf('%04d-%02d', $year, $month);
        $leaves = $this->where('id_user', $userId)
            ->where('status_approval', 'approved')
            ->where("DATE_FORMAT(tanggal_mulai, '%Y-%m') <=", $yearMonth)
            ->where("DATE_FORMAT(tanggal_selesai, '%Y-%m') >=", $yearMonth)
            ->findAll();

        $calendar = [];
        foreach ($leaves as $leave) {
            $start = new \DateTime($leave['tanggal_mulai']);
            $end = new \DateTime($leave['tanggal_selesai']);
            $end->modify('+1 day');

            $interval = new \DateInterval('P1D');
            $period = new \DatePeriod($start, $interval, $end);

            foreach ($period as $date) {
                if ($date->format('Y-m') === $yearMonth) {
                    $day = (int) $date->format('j');
                    $calendar[$day] = $leave['jenis_cuti'];
                }
            }
        }

        return $calendar;
    }
}
