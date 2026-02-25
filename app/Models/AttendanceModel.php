<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table            = 'attendances';
    protected $primaryKey       = 'id_attendance';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'latitude_masuk',
        'longitude_masuk',
        'distance_masuk',
        'foto_masuk',
        'latitude_keluar',
        'longitude_keluar',
        'distance_keluar',
        'foto_keluar',
        'status',
        'keterangan',
        'is_manual'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_manual' => 'boolean'
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
        'id_user' => 'required|integer',
        'tanggal' => 'required|valid_date',
        'status' => 'required|in_list[hadir,terlambat,izin,sakit,alpha,cuti]'
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
        'status' => [
            'required' => 'Status wajib diisi',
            'in_list' => 'Status tidak valid'
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
     * Get attendance with user details
     */
    public function getAttendanceWithUser($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('attendances.*, 
                     users.nama_lengkap, 
                     users.nik, 
                     users.email,
                     divisi.nama_divisi')
            ->join('users', 'users.id_user = attendances.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left');

        if ($id !== null) {
            $builder->where('attendances.id_attendance', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->orderBy('attendances.tanggal', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get attendance with pagination
     */
    public function getAttendancePaginated($filters = [], $perPage = 10)
    {
        $this->select('attendances.*, users.nama_lengkap, users.nik, divisi.nama_divisi, roles.kode_role')
            ->join('users', 'users.id_user = attendances.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('roles', 'roles.id_role = users.id_role');

        // Filter by month
        if (!empty($filters['month'])) {
            $this->where("DATE_FORMAT(attendances.tanggal, '%Y-%m')", $filters['month']);
        }

        // Filter for mentor - only show mentees
        if (!empty($filters['mentor_id'])) {
            $this->join('interns', 'interns.id_user = attendances.id_user')
                ->where('interns.id_mentor', $filters['mentor_id']);
        }

        // Filter by divisi
        if (!empty($filters['divisi'])) {
            $this->where('users.id_divisi', $filters['divisi']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $this->where('attendances.status', $filters['status']);
        }

        // Search filter
        if (!empty($filters['search'])) {
            $this->groupStart()
                ->like('users.nama_lengkap', $filters['search'])
                ->orLike('users.nik', $filters['search'])
                ->groupEnd();
        }

        return $this->orderBy('attendances.tanggal', 'DESC')
            ->orderBy('attendances.jam_masuk', 'ASC')
            ->paginate($perPage);
    }

    /**
     * Get today's attendance for user
     */
    public function getTodayAttendance($userId)
    {
        return $this->where('id_user', $userId)
            ->where('tanggal', date('Y-m-d'))
            ->first();
    }

    /**
     * Get attendance by date range
     */
    public function getAttendanceByDateRange($userId, $startDate, $endDate)
    {
        return $this->where('id_user', $userId)
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }

    /**
     * Get attendance by month
     */
    public function getAttendanceByMonth($userId, $year, $month)
    {
        $yearMonth = sprintf('%04d-%02d', $year, $month);
        return $this->where('id_user', $userId)
            ->where("DATE_FORMAT(tanggal, '%Y-%m')", $yearMonth)
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }

    /**
     * Get attendance summary for user
     */
    public function getUserAttendanceSummary($userId, $year = null, $month = null)
    {
        $builder = $this->where('id_user', $userId);

        if ($year && $month) {
            $yearMonth = sprintf('%04d-%02d', $year, $month);
            $builder->where("DATE_FORMAT(tanggal, '%Y-%m')", $yearMonth);
        }

        $total = $builder->countAllResults(false);
        $hadir = $builder->whereIn('status', ['hadir', 'terlambat'])->countAllResults(false);
        $terlambat = $builder->where('status', 'terlambat')->countAllResults(false);
        $izin = $builder->where('status', 'izin')->countAllResults(false);
        $sakit = $builder->where('status', 'sakit')->countAllResults(false);
        $alpha = $builder->where('status', 'alpha')->countAllResults();

        $persentaseHadir = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

        return [
            'total_hari' => $total,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha,
            'persentase_hadir' => $persentaseHadir
        ];
    }

    /**
     * Get late attendance count
     */
    public function getLateAttendanceCount($userId, $year = null, $month = null)
    {
        $builder = $this->where('id_user', $userId)
            ->where('status', 'terlambat');

        if ($year && $month) {
            $yearMonth = sprintf('%04d-%02d', $year, $month);
            $builder->where("DATE_FORMAT(tanggal, '%Y-%m')", $yearMonth);
        }

        return $builder->countAllResults();
    }

    /**
     * Check if user already checked in today
     */
    public function hasCheckedInToday($userId)
    {
        $today = date('Y-m-d');
        $attendance = $this->where('id_user', $userId)
            ->where('tanggal', $today)
            ->first();

        return $attendance && $attendance['jam_masuk'] !== null;
    }

    /**
     * Check if user already checked out today
     */
    public function hasCheckedOutToday($userId)
    {
        $today = date('Y-m-d');
        $attendance = $this->where('id_user', $userId)
            ->where('tanggal', $today)
            ->first();

        return $attendance && $attendance['jam_keluar'] !== null;
    }

    /**
     * Get attendance statistics for admin dashboard
     */
    public function getDashboardStatistics($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $total = $this->where('tanggal', $date)->countAllResults(false);
        $hadir = $this->where('tanggal', $date)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->countAllResults(false);
        $izin = $this->where('tanggal', $date)
            ->where('status', 'izin')
            ->countAllResults(false);
        $sakit = $this->where('tanggal', $date)
            ->where('status', 'sakit')
            ->countAllResults(false);
        $alpha = $this->where('tanggal', $date)
            ->where('status', 'alpha')
            ->countAllResults();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha
        ];
    }

    /**
     * Get recent attendance activities
     */
    public function getRecentActivities($limit = 10)
    {
        return $this->db->table($this->table)
            ->select('attendances.*, users.nama_lengkap, users.foto')
            ->join('users', 'users.id_user = attendances.id_user')
            ->orderBy('attendances.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get attendance calendar data for month
     */
    public function getCalendarData($userId, $year, $month)
    {
        $yearMonth = sprintf('%04d-%02d', $year, $month);
        $attendances = $this->where('id_user', $userId)
            ->where("DATE_FORMAT(tanggal, '%Y-%m')", $yearMonth)
            ->findAll();

        $calendar = [];
        foreach ($attendances as $att) {
            $day = date('j', strtotime($att['tanggal']));
            $calendar[$day] = $att['status'];
        }

        return $calendar;
    }

    /**
     * Export attendance data to array for reports
     */
    public function exportData($filters = [])
    {
        $builder = $this->db->table($this->table)
            ->select('attendances.*, 
                     users.nama_lengkap, 
                     users.nik, 
                     divisi.nama_divisi')
            ->join('users', 'users.id_user = attendances.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left');

        if (isset($filters['start_date'])) {
            $builder->where('attendances.tanggal >=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $builder->where('attendances.tanggal <=', $filters['end_date']);
        }

        if (isset($filters['divisi_id'])) {
            $builder->where('users.id_divisi', $filters['divisi_id']);
        }

        if (isset($filters['status'])) {
            $builder->where('attendances.status', $filters['status']);
        }

        return $builder->orderBy('attendances.tanggal', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get working days count in month (excluding weekends)
     */
    public function getWorkingDaysInMonth($year, $month)
    {
        $firstDay = strtotime("$year-$month-01");
        $lastDay = strtotime(date("Y-m-t", $firstDay));
        $workingDays = 0;

        for ($day = $firstDay; $day <= $lastDay; $day += 86400) {
            $dayOfWeek = date('N', $day);
            if ($dayOfWeek < 6) { // Monday to Friday
                $workingDays++;
            }
        }

        return $workingDays;
    }

    /**
     * Calculate attendance percentage
     */
    public function calculateAttendancePercentage($userId, $year, $month)
    {
        $workingDays = $this->getWorkingDaysInMonth($year, $month);
        $summary = $this->getUserAttendanceSummary($userId, $year, $month);

        if ($workingDays == 0) {
            return 0;
        }

        return round(($summary['hadir'] / $workingDays) * 100, 2);
    }
}
