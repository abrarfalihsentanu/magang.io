<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Get user role from session (middleware already checked auth)
        $roleCode = session()->get('role_code');
        $userId = session()->get('user_id');

        // Prepare common data
        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => session()->get('nama_lengkap'),
                'email' => session()->get('email'),
                'role' => session()->get('role_name'),
                'foto' => session()->get('foto'),
                'divisi' => session()->get('nama_divisi')
            ]
        ];

        // Load dashboard based on role with specific data
        switch ($roleCode) {
            case 'admin':
                $data['stats'] = $this->getAdminStats();
                return view('dashboard/admin', $data);

            case 'hr':
                $data['stats'] = $this->getHRStats();
                return view('dashboard/hr', $data);

            case 'finance':
                $data['stats'] = $this->getFinanceStats();
                return view('dashboard/finance', $data);

            case 'mentor':
                $data['stats'] = $this->getMentorStats($userId);
                return view('dashboard/mentor', $data);

            case 'intern':
                $data['stats'] = $this->getInternStats($userId);
                return view('dashboard/intern', $data);

            default:
                return redirect()->to('/login')->with('error', 'Role tidak valid');
        }
    }

    // ========================================
    // GET STATS FOR ADMIN
    // ========================================
    private function getAdminStats()
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        return [
            'total_interns' => $this->db->table('interns')
                ->where('status_magang', 'active')
                ->countAllResults(),

            'attendance_today' => $this->db->table('attendances')
                ->where('tanggal', $today)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->countAllResults(),

            'pending_approvals' => $this->db->table('attendance_corrections')
                ->where('status_approval', 'pending')
                ->countAllResults() +
                $this->db->table('leaves')
                ->where('status_approval', 'pending')
                ->countAllResults(),

            'total_allowance' => 0 // Will calculate later
        ];
    }

    // ========================================
    // GET STATS FOR HR
    // ========================================
    private function getHRStats()
    {
        return $this->getAdminStats(); // Same stats as admin
    }

    // ========================================
    // GET STATS FOR FINANCE
    // ========================================
    private function getFinanceStats()
    {
        return [
            'pending_payments' => 0,
            'completed_payments' => 0,
            'total_this_month' => 0,
            'next_payment_date' => '25 ' . date('F Y')
        ];
    }

    // ========================================
    // GET STATS FOR MENTOR
    // ========================================
    private function getMentorStats($mentorId)
    {
        // Get mentee user IDs for this mentor
        $menteeIds = $this->db->table('interns')
            ->select('id_user')
            ->where('id_mentor', $mentorId)
            ->get()->getResultArray();
        $menteeUserIds = array_column($menteeIds, 'id_user');

        $pendingActivities = 0;
        $pendingProjects = 0;
        if (!empty($menteeUserIds)) {
            $pendingActivities = $this->db->table('daily_activities')
                ->whereIn('id_user', $menteeUserIds)
                ->where('status', 'pending')
                ->countAllResults();
            $pendingProjects = $this->db->table('weekly_projects')
                ->whereIn('id_user', $menteeUserIds)
                ->where('status', 'pending')
                ->countAllResults();
        }

        return [
            'total_mentees' => $this->db->table('interns')
                ->where('id_mentor', $mentorId)
                ->where('status_magang', 'active')
                ->countAllResults(),
            'pending_activities' => $pendingActivities,
            'pending_projects' => $pendingProjects,
            'avg_kpi' => 0
        ];
    }

    // ========================================
    // GET STATS FOR INTERN
    // ========================================
    private function getInternStats($userId)
    {
        $thisMonth = date('Y-m');

        // Get attendance count this month
        $attendance = $this->db->table('attendances')
            ->select('COUNT(*) as total, SUM(CASE WHEN status IN ("hadir", "terlambat") THEN 1 ELSE 0 END) as hadir')
            ->where('id_user', $userId)
            ->where('DATE_FORMAT(tanggal, "%Y-%m")', $thisMonth)
            ->get()
            ->getRow();

        // Count submitted activities
        $activitiesSubmitted = $this->db->table('daily_activities')
            ->where('id_user', $userId)
            ->countAllResults();

        return [
            'attendance_this_month' => [
                'hadir' => $attendance->hadir ?? 0,
                'total_days' => $attendance->total ?? 0
            ],
            'kpi_score' => 0,
            'activities_submitted' => $activitiesSubmitted,
            'allowance_this_period' => 0
        ];
    }

    // ----------------------------------------
    // JSON endpoints for charts / tables
    // ----------------------------------------
    public function attendanceTrend()
    {
        $days = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $days[] = date('d M', strtotime($d));
            $counts[] = (int) $this->db->table('attendances')
                ->where('tanggal', $d)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->countAllResults();
        }

        return $this->response->setJSON(['labels' => $days, 'series' => $counts]);
    }

    public function internsByDivision()
    {
        try {
            $rows = $this->db->table('interns as i')
                ->select('d.nama_divisi, COUNT(i.id_intern) as total')
                ->join('users as u', 'u.id_user = i.id_user', 'left')
                ->join('divisi as d', 'u.id_divisi = d.id_divisi', 'left')
                ->where('i.status_magang', 'active')
                ->groupBy('d.nama_divisi')
                ->get()
                ->getResultArray();

            $labels = [];
            $data = [];
            foreach ($rows as $r) {
                $labels[] = $r['nama_divisi'] ?? 'Unknown';
                $data[] = (int) $r['total'];
            }

            return $this->response->setJSON(['labels' => $labels, 'series' => $data]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['labels' => [], 'series' => []]);
        }
    }

    public function financeSummary()
    {
        try {
            $thisMonth = date('Y-m');
            // Summarize allowances by status
            $pending = (int) $this->db->table('allowances')
                ->where('status_pembayaran', 'pending')
                ->countAllResults();

            $completed = (int) $this->db->table('allowances')
                ->where('status_pembayaran', 'paid')
                ->countAllResults();

            $totalAmount = (float) ($this->db->table('allowances')
                ->select('IFNULL(SUM(total_uang_saku),0) as total')
                ->get()
                ->getRow()
                ->total ?? 0);

            return $this->response->setJSON(['pending' => $pending, 'completed' => $completed, 'total' => $totalAmount]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['pending' => 0, 'completed' => 0, 'total' => 0]);
        }
    }

    public function mentorMentees()
    {
        $mentorId = session()->get('user_id');
        try {
            $rows = $this->db->table('interns')
                ->select('status_magang, COUNT(id_intern) as total')
                ->where('id_mentor', $mentorId)
                ->groupBy('status_magang')
                ->get()
                ->getResultArray();

            $labels = [];
            $data = [];
            foreach ($rows as $r) {
                $labels[] = $r['status_magang'];
                $data[] = (int) $r['total'];
            }
            return $this->response->setJSON(['labels' => $labels, 'series' => $data]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['labels' => [], 'series' => []]);
        }
    }

    public function internActivities()
    {
        $userId = session()->get('user_id');
        $labels = [];
        $data = [];
        try {
            // last 4 weeks
            for ($w = 3; $w >= 0; $w--) {
                $start = date('Y-m-d', strtotime("-{$w} week monday"));
                $end = date('Y-m-d', strtotime("-{$w} week sunday"));
                $labels[] = date('d M', strtotime($start));
                $count = (int) $this->db->table('daily_activities')
                    ->where('id_user', $userId)
                    ->where('DATE(tanggal) >=', $start)
                    ->where('DATE(tanggal) <=', $end)
                    ->countAllResults();
                $data[] = $count;
            }

            return $this->response->setJSON(['labels' => $labels, 'series' => $data]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['labels' => [], 'series' => []]);
        }
    }

    // ----------------------------------------
    // Additional endpoints for requested features
    // ----------------------------------------
    public function roleDistribution()
    {
        try {
            $rows = $this->db->table('users as u')
                ->select('r.nama_role, COUNT(u.id_user) as total')
                ->join('roles as r', 'u.id_role = r.id_role', 'left')
                ->groupBy('r.nama_role')
                ->get()
                ->getResultArray();

            $labels = [];
            $data = [];
            foreach ($rows as $r) {
                $labels[] = $r['nama_role'] ?? 'Unknown';
                $data[] = (int)$r['total'];
            }
            return $this->response->setJSON(['labels' => $labels, 'series' => $data]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['labels' => [], 'series' => []]);
        }
    }

    public function loginActivity()
    {
        // last 7 days login counts based on audit_logs login action
        $labels = [];
        $data = [];
        try {
            // Check if user_logins table exists, fallback to audit_logs
            $tableExists = $this->db->tableExists('user_logins');
            for ($i = 6; $i >= 0; $i--) {
                $d = date('Y-m-d', strtotime("-{$i} days"));
                $labels[] = date('d M', strtotime($d));
                if ($tableExists) {
                    $count = $this->db->table('user_logins')->where('DATE(created_at)', $d)->countAllResults();
                } else {
                    // Fallback: count login events from audit_logs
                    $count = $this->db->table('audit_logs')
                        ->where('DATE(created_at)', $d)
                        ->where('action', 'login')
                        ->countAllResults();
                }
                $data[] = (int)$count;
            }
        } catch (\Throwable $e) {
            // If both fail, return empty data for remaining days
            while (count($data) < 7) {
                if (count($labels) < 7) {
                    $i = 6 - count($labels);
                    $labels[] = date('d M', strtotime("-{$i} days"));
                }
                $data[] = 0;
            }
        }
        return $this->response->setJSON(['labels' => $labels, 'series' => $data]);
    }

    public function internGrowth()
    {
        // monthly new interns for last 6 months
        $labels = [];
        $data = [];
        for ($m = 5; $m >= 0; $m--) {
            $month = date('Y-m', strtotime("-{$m} month"));
            $labels[] = date('M Y', strtotime($month . '-01'));
            try {
                $count = $this->db->table('interns')->where('DATE_FORMAT(created_at, "%Y-%m")', $month)->countAllResults();
            } catch (\Throwable $e) {
                $count = 0;
            }
            $data[] = (int)$count;
        }
        return $this->response->setJSON(['labels' => $labels, 'series' => $data]);
    }

    public function attendance3Months()
    {
        // returns attendance per month for last 3 months
        $labels = [];
        $present = [];
        $total = [];
        for ($m = 2; $m >= 0; $m--) {
            $month = date('Y-m', strtotime("-{$m} month"));
            $labels[] = date('M Y', strtotime($month . '-01'));
            try {
                $t = $this->db->table('attendances')->where('DATE_FORMAT(tanggal, "%Y-%m")', $month)->countAllResults();
                $p = $this->db->table('attendances')->where('DATE_FORMAT(tanggal, "%Y-%m")', $month)->whereIn('status', ['hadir', 'terlambat'])->countAllResults();
            } catch (\Throwable $e) {
                $t = 0;
                $p = 0;
            }
            $total[] = (int)$t;
            $present[] = (int)$p;
        }
        return $this->response->setJSON(['labels' => $labels, 'total' => $total, 'present' => $present]);
    }

    public function pendingCorrections()
    {
        try {
            $count = $this->db->table('attendance_corrections')->where('status_approval', 'pending')->countAllResults();
            return $this->response->setJSON(['pending' => $count]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['pending' => 0]);
        }
    }

    public function dailyAttendanceByDivision()
    {
        $today = date('Y-m-d');
        try {
            $rows = $this->db->table('attendances as a')
                ->select('d.nama_divisi, SUM(CASE WHEN a.status IN ("hadir","terlambat") THEN 1 ELSE 0 END) as hadir')
                ->join('users as u', 'a.id_user=u.id_user', 'left')
                ->join('divisi as d', 'u.id_divisi=d.id_divisi', 'left')
                ->where('DATE(a.tanggal)', $today)
                ->groupBy('d.nama_divisi')
                ->get()->getResultArray();

            $labels = [];
            $data = [];
            foreach ($rows as $r) {
                $labels[] = $r['nama_divisi'] ?? 'Unknown';
                $data[] = (int)$r['hadir'];
            }
            return $this->response->setJSON(['labels' => $labels, 'series' => $data]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['labels' => [], 'series' => []]);
        }
    }

    public function mentorMenteesDetail()
    {
        $mentorId = session()->get('user_id');
        try {
            $rows = $this->db->table('interns as i')
                ->select('i.id_intern, u.nama_lengkap, i.status_magang, i.id_user')
                ->join('users as u', 'i.id_user = u.id_user', 'left')
                ->where('i.id_mentor', $mentorId)
                ->get()->getResultArray();

            // Calculate attendance percentage for each mentee
            foreach ($rows as &$row) {
                $total = $this->db->table('attendances')
                    ->where('id_user', $row['id_user'])
                    ->countAllResults();
                $present = $this->db->table('attendances')
                    ->where('id_user', $row['id_user'])
                    ->whereIn('status', ['hadir', 'terlambat'])
                    ->countAllResults();
                $row['attendance_pct'] = $total > 0 ? round(($present / $total) * 100) : 0;
            }

            return $this->response->setJSON(['mentees' => $rows]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['mentees' => []]);
        }
    }

    public function mentorActivityFeed()
    {
        $mentorId = session()->get('user_id');
        try {
            // recent activities by mentees
            $rows = $this->db->table('daily_activities as a')
                ->select('a.id_activity, a.judul_aktivitas as judul, a.tanggal, u.nama_lengkap')
                ->join('interns as i', 'a.id_user = i.id_user', 'left')
                ->join('users as u', 'i.id_user = u.id_user', 'left')
                ->where('i.id_mentor', $mentorId)
                ->orderBy('a.tanggal', 'DESC')
                ->limit(20)
                ->get()->getResultArray();
            return $this->response->setJSON(['feed' => $rows]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['feed' => []]);
        }
    }

    public function financePayments()
    {
        try {
            $rows = $this->db->table('allowances al')
                ->select('al.id_allowance, al.id_user, u.nama_lengkap, al.total_uang_saku, al.status_pembayaran, al.updated_at')
                ->join('users u', 'u.id_user = al.id_user', 'left')
                ->orderBy('al.updated_at', 'DESC')->limit(50)->get()->getResultArray();
            return $this->response->setJSON(['payments' => $rows]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['payments' => []]);
        }
    }

    public function financeByDivision()
    {
        try {
            $rows = $this->db->table('allowances as al')
                ->select('d.nama_divisi, COUNT(al.id_allowance) as total_payments, SUM(al.total_uang_saku) as total_amount')
                ->join('users as u', 'u.id_user = al.id_user', 'left')
                ->join('divisi as d', 'u.id_divisi=d.id_divisi', 'left')
                ->groupBy('d.nama_divisi')
                ->get()->getResultArray();
            return $this->response->setJSON(['rows' => $rows]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['rows' => []]);
        }
    }

    public function attendanceCalendar()
    {
        $userId = session()->get('user_id');
        try {
            $rows = $this->db->table('attendances')->select('tanggal, status')->where('id_user', $userId)->orderBy('tanggal', 'DESC')->limit(365)->get()->getResultArray();
            return $this->response->setJSON(['events' => $rows]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['events' => []]);
        }
    }

    public function attendanceVsTarget()
    {
        $userId = session()->get('user_id');
        try {
            // example: target 22 working days per month
            $target = 22;
            $month = date('Y-m');
            $present = $this->db->table('attendances')->where('id_user', $userId)->where('DATE_FORMAT(tanggal, "%Y-%m")', $month)->whereIn('status', ['hadir', 'terlambat'])->countAllResults();
            return $this->response->setJSON(['target' => $target, 'present' => (int)$present]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['target' => 22, 'present' => 0]);
        }
    }

    public function allowanceHistory()
    {
        $userId = session()->get('user_id');
        try {
            $rows = $this->db->table('allowances')
                ->select('id_allowance, total_uang_saku, status_pembayaran, updated_at')
                ->where('id_user', $userId)
                ->orderBy('updated_at', 'DESC')->limit(12)->get()->getResultArray();
            return $this->response->setJSON(['history' => $rows]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['history' => []]);
        }
    }
}
