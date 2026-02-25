<?php

namespace App\Models;

use CodeIgniter\Model;

class InternModel extends Model
{
    protected $table            = 'interns';
    protected $primaryKey       = 'id_intern';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'id_mentor',
        'universitas',
        'jurusan',
        'periode_mulai',
        'periode_selesai',
        'durasi_bulan',
        'status_magang',
        'dokumen_surat_magang',
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
     * Get intern with full details (user, divisi, mentor)
     */
    public function getInternWithDetails($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('interns.*, 
                      users.id_divisi,
                      users.nik,
                      users.nama_lengkap,
                      users.email,
                      users.no_hp,
                      users.jenis_kelamin,
                      users.tanggal_lahir,
                      users.alamat,
                      users.foto,
                      users.status as user_status,
                      divisi.nama_divisi,
                      divisi.kode_divisi,
                      mentor.nama_lengkap as nama_mentor')
            ->join('users', 'users.id_user = interns.id_user', 'left')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('users as mentor', 'mentor.id_user = interns.id_mentor', 'left');

        if ($id !== null) {
            $builder->where('interns.id_intern', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->orderBy('interns.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get intern with pagination
     */
    public function getInternPaginated($perPage = 10, $search = null, $status = null)
    {
        $builder = $this->select('interns.*, 
                      users.id_divisi,
                      users.nik,
                      users.nama_lengkap,
                      users.email,
                      users.no_hp,
                      users.jenis_kelamin,
                      users.tanggal_lahir,
                      users.alamat,
                      users.foto,
                      users.status as user_status,
                      divisi.nama_divisi,
                      divisi.kode_divisi,
                      mentor.nama_lengkap as nama_mentor')
            ->join('users', 'users.id_user = interns.id_user', 'left')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('users as mentor', 'mentor.id_user = interns.id_mentor', 'left');

        // Apply search filter
        if ($search) {
            $builder->groupStart()
                ->like('users.nama_lengkap', $search)
                ->orLike('users.nik', $search)
                ->orLike('users.email', $search)
                ->orLike('interns.universitas', $search)
                ->orLike('interns.jurusan', $search)
                ->groupEnd();
        }

        // Apply status filter
        if ($status) {
            $builder->where('interns.status_magang', $status);
        }

        return $builder->orderBy('interns.created_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total' => $this->countAll(),
            'active' => $this->where('status_magang', 'active')->countAllResults(),
            'completed' => $this->where('status_magang', 'completed')->countAllResults(),
            'terminated' => $this->where('status_magang', 'terminated')->countAllResults()
        ];
    }

    /**
     * Get attendance summary for intern
     */
    public function getAttendanceSummary($userId)
    {
        $attendanceModel = new \App\Models\AttendanceModel();

        $total = $attendanceModel->where('id_user', $userId)->countAllResults();
        $hadir = $attendanceModel->where('id_user', $userId)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->countAllResults();
        $izin = $attendanceModel->where('id_user', $userId)
            ->where('status', 'izin')
            ->countAllResults();
        $sakit = $attendanceModel->where('id_user', $userId)
            ->where('status', 'sakit')
            ->countAllResults();
        $alpha = $attendanceModel->where('id_user', $userId)
            ->where('status', 'alpha')
            ->countAllResults();

        $persentase = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

        return [
            'total' => $total,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha,
            'persentase' => $persentase
        ];
    }

    /**
     * Get activity summary for intern
     */
    public function getActivitySummary($userId)
    {
        $activityModel = new \App\Models\DailyActivityModel();

        $total = $activityModel->where('id_user', $userId)->countAllResults();
        $approved = $activityModel->where('id_user', $userId)
            ->where('status_approval', 'approved')
            ->countAllResults();
        $pending = $activityModel->where('id_user', $userId)
            ->where('status_approval', 'submitted')
            ->countAllResults();
        $rejected = $activityModel->where('id_user', $userId)
            ->where('status_approval', 'rejected')
            ->countAllResults();

        return [
            'total' => $total,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected
        ];
    }

    /**
     * Get active interns
     */
    public function getActiveInterns()
    {
        return $this->where('status_magang', 'active')
            ->orderBy('periode_mulai', 'DESC')
            ->findAll();
    }

    /**
     * Check if intern period is ending soon (within 30 days)
     */
    public function getEndingSoonInterns($days = 30)
    {
        return $this->db->table($this->table)
            ->select('interns.*, users.nama_lengkap')
            ->join('users', 'users.id_user = interns.id_user')
            ->where('interns.status_magang', 'active')
            ->where('DATEDIFF(interns.periode_selesai, CURDATE()) <=', $days)
            ->where('DATEDIFF(interns.periode_selesai, CURDATE()) >', 0)
            ->orderBy('interns.periode_selesai', 'ASC')
            ->get()
            ->getResultArray();
    }
}
