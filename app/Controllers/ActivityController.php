<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DailyActivityModel;
use App\Models\InternModel;
use App\Models\DivisiModel;
use CodeIgniter\HTTP\ResponseInterface;

class ActivityController extends BaseController
{
    protected $activityModel;
    protected $internModel;
    protected $divisiModel;
    protected $db;

    public function __construct()
    {
        $this->activityModel = new DailyActivityModel();
        $this->internModel = new InternModel();
        $this->divisiModel = new DivisiModel();
        $this->db = \Config\Database::connect();
        helper(['form', 'filesystem']);
        date_default_timezone_set('Asia/Jakarta');
    }

    private function getCurrentDateTime($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    // ============================================
    // INTERN SIDE - MY ACTIVITIES
    // ============================================

    /**
     * List activities untuk intern
     */
    public function my()
    {
        $userId = session()->get('user_id');
        $month = $this->request->getGet('month') ?? date('Y-m');

        // Get activities
        $filters = [
            'start_date' => $month . '-01',
            'end_date' => date('Y-m-t', strtotime($month . '-01'))
        ];

        $activities = $this->activityModel->getActivitiesWithUser($userId, $filters);
        $statistics = $this->activityModel->getUserStatistics($userId, $month);

        // Get calendar data
        list($year, $monthNum) = explode('-', $month);
        $calendarData = $this->activityModel->getActivitiesForCalendar($userId, $year, $monthNum);

        $data = [
            'title' => 'Aktivitas Harian Saya',
            'activities' => $activities,
            'statistics' => $statistics,
            'calendar_data' => $calendarData,
            'selected_month' => $month
        ];

        return view('activity/my', $data);
    }

    /**
     * Detail activity view
     */
    public function detail($id)
    {
        $userId = session()->get('user_id');
        $kodeRole = session()->get('kode_role');

        // Get activity with user details
        $activity = $this->db->table('daily_activities')
            ->select('daily_activities.*, 
                 users.nama_lengkap, 
                 users.nik, 
                 divisi.nama_divisi,
                 mentor.nama_lengkap as mentor_name')
            ->join('users', 'users.id_user = daily_activities.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('users as mentor', 'mentor.id_user = daily_activities.approved_by', 'left')
            ->where('daily_activities.id_activity', $id)
            ->get()
            ->getRowArray();

        if (!$activity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Aktivitas tidak ditemukan'
                ]);
            }

            return redirect()->to(base_url('activity/my'))
                ->with('error', 'Aktivitas tidak ditemukan');
        }

        // Check permission
        if ($kodeRole === 'intern') {
            if ($activity['id_user'] != $userId) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses'
                    ]);
                }

                return redirect()->to(base_url('activity/my'))
                    ->with('error', 'Anda tidak memiliki akses');
            }
        } elseif ($kodeRole === 'mentor') {
            $intern = $this->internModel->where('id_user', $activity['id_user'])->first();

            if (!$intern || $intern['id_mentor'] != $userId) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses'
                    ]);
                }

                return redirect()->to(base_url('activity/approval'))
                    ->with('error', 'Anda tidak memiliki akses');
            }
        }

        // For AJAX request (modal)
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'activity' => $activity
            ]);
        }

        // For normal request (page)
        $data = [
            'title' => 'Detail Aktivitas',
            'activity' => $activity
        ];

        return view('activity/detail', $data);
    }

    /**
     * Create activity page
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Aktivitas Harian'
        ];

        return view('activity/create', $data);
    }

    /**
     * Store new activity
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $userId = session()->get('user_id');

        // Validation rules
        $rules = [
            'tanggal' => 'required|valid_date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'judul_aktivitas' => 'required|min_length[5]|max_length[200]',
            'deskripsi' => 'required|min_length[50]',
            'kategori' => 'required|in_list[learning,task,meeting,training,other]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $tanggal = $this->request->getPost('tanggal');

        // Validate: cannot input more than 3 days ago
        $today = date('Y-m-d');
        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));

        if ($tanggal < $threeDaysAgo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak dapat menginput aktivitas lebih dari 3 hari ke belakang'
            ]);
        }

        if ($tanggal > $today) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tanggal tidak boleh lebih dari hari ini'
            ]);
        }

        // Validate jam
        $jamMulai = $this->request->getPost('jam_mulai');
        $jamSelesai = $this->request->getPost('jam_selesai');

        if ($jamSelesai <= $jamMulai) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Jam selesai harus lebih besar dari jam mulai'
            ]);
        }

        // Handle file upload
        $attachmentName = null;
        $attachment = $this->request->getFile('attachment');

        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            // Validate file
            if ($attachment->getSize() > 5 * 1024 * 1024) { // 5MB
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ukuran file maksimal 5MB'
                ]);
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!in_array($attachment->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'File harus berupa gambar (JPG, PNG) atau PDF'
                ]);
            }

            $attachmentName = 'activity_' . $userId . '_' . time() . '.' . $attachment->getExtension();
            $attachment->move(WRITEPATH . 'uploads/activities', $attachmentName);
        }

        // Get status from request
        $statusApproval = $this->request->getPost('status_approval') ?? 'draft';

        if (!in_array($statusApproval, ['draft', 'submitted'])) {
            $statusApproval = 'draft';
        }

        $data = [
            'id_user' => $userId,
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'judul_aktivitas' => $this->request->getPost('judul_aktivitas'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'kategori' => $this->request->getPost('kategori'),
            'attachment' => $attachmentName,
            'status_approval' => $statusApproval
        ];

        if (!$this->activityModel->insert($data)) {
            // Delete uploaded file if insert fails
            if ($attachmentName) {
                $filePath = WRITEPATH . 'uploads/activities/' . $attachmentName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan aktivitas',
                'errors' => $this->activityModel->errors()
            ]);
        }

        $message = $statusApproval === 'draft' ?
            'Aktivitas berhasil disimpan sebagai draft' :
            'Aktivitas berhasil disubmit untuk approval';

        // Kirim notifikasi ke mentor jika status submitted
        if ($statusApproval === 'submitted') {
            $internData = $this->internModel->where('id_user', $userId)->first();
            if ($internData && !empty($internData['id_mentor'])) {
                (new \App\Libraries\NotificationService())->activitySubmitted(
                    (int) $internData['id_mentor'],
                    session()->get('nama_lengkap') ?? 'Pemagang',
                    $this->request->getPost('judul_aktivitas')
                );
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Edit activity page
     */
    public function edit($id)
    {
        $userId = session()->get('user_id');

        if (!$this->activityModel->canEdit($id, $userId)) {
            return redirect()->to(base_url('activity/my'))
                ->with('error', 'Anda tidak dapat mengedit aktivitas ini');
        }

        $activity = $this->activityModel->find($id);

        if (!$activity) {
            return redirect()->to(base_url('activity/my'))
                ->with('error', 'Aktivitas tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Aktivitas',
            'activity' => $activity
        ];

        return view('activity/edit', $data);
    }

    /**
     * Update activity
     */
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $userId = session()->get('user_id');

        if (!$this->activityModel->canEdit($id, $userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak dapat mengedit aktivitas ini'
            ]);
        }

        $activity = $this->activityModel->find($id);

        if (!$activity) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aktivitas tidak ditemukan'
            ]);
        }

        // Validation rules (same as store)
        $rules = [
            'tanggal' => 'required|valid_date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'judul_aktivitas' => 'required|min_length[5]|max_length[200]',
            'deskripsi' => 'required|min_length[50]',
            'kategori' => 'required|in_list[learning,task,meeting,training,other]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $tanggal = $this->request->getPost('tanggal');
        $today = date('Y-m-d');
        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));

        if ($tanggal < $threeDaysAgo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak dapat menginput aktivitas lebih dari 3 hari ke belakang'
            ]);
        }

        if ($tanggal > $today) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tanggal tidak boleh lebih dari hari ini'
            ]);
        }

        $jamMulai = $this->request->getPost('jam_mulai');
        $jamSelesai = $this->request->getPost('jam_selesai');

        if ($jamSelesai <= $jamMulai) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Jam selesai harus lebih besar dari jam mulai'
            ]);
        }

        // Handle file upload
        $attachmentName = $activity['attachment'];
        $attachment = $this->request->getFile('attachment');

        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            // Validate file
            if ($attachment->getSize() > 5 * 1024 * 1024) { // 5MB
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ukuran file maksimal 5MB'
                ]);
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!in_array($attachment->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'File harus berupa gambar (JPG, PNG) atau PDF'
                ]);
            }

            // Delete old file
            if ($attachmentName) {
                $oldFile = WRITEPATH . 'uploads/activities/' . $attachmentName;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $attachmentName = 'activity_' . $userId . '_' . time() . '.' . $attachment->getExtension();
            $attachment->move(WRITEPATH . 'uploads/activities', $attachmentName);
        }

        $statusApproval = $this->request->getPost('status_approval') ?? 'draft';

        if (!in_array($statusApproval, ['draft', 'submitted'])) {
            $statusApproval = 'draft';
        }

        $data = [
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'judul_aktivitas' => $this->request->getPost('judul_aktivitas'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'kategori' => $this->request->getPost('kategori'),
            'attachment' => $attachmentName,
            'status_approval' => $statusApproval
        ];

        if (!$this->activityModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate aktivitas',
                'errors' => $this->activityModel->errors()
            ]);
        }

        $message = $statusApproval === 'draft' ?
            'Aktivitas berhasil diupdate' :
            'Aktivitas berhasil disubmit untuk approval';

        // Kirim notifikasi ke mentor jika re-submit
        if ($statusApproval === 'submitted') {
            $actData = $this->activityModel->find($id);
            $internUserId = $actData ? (int) $actData['id_user'] : (int) session()->get('user_id');
            $internData = $this->internModel->where('id_user', $internUserId)->first();
            if ($internData && !empty($internData['id_mentor'])) {
                (new \App\Libraries\NotificationService())->activitySubmitted(
                    (int) $internData['id_mentor'],
                    session()->get('nama_lengkap') ?? 'Pemagang',
                    $this->request->getPost('judul_aktivitas')
                );
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Delete activity
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $userId = session()->get('user_id');

        if (!$this->activityModel->canDelete($id, $userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus aktivitas ini'
            ]);
        }

        $activity = $this->activityModel->find($id);

        if (!$activity) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aktivitas tidak ditemukan'
            ]);
        }

        // Delete attachment if exists
        if ($activity['attachment']) {
            $filePath = WRITEPATH . 'uploads/activities/' . $activity['attachment'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if (!$this->activityModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus aktivitas'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Aktivitas berhasil dihapus'
        ]);
    }
    
// ============================================
    // MENTOR SIDE - APPROVAL
    // ============================================

    /**
     * Approval page for mentor
     */
    public function approval()
    {
        $mentorId = session()->get('user_id');
        $kodeRole = session()->get('kode_role');

        // Get pending activities
        if ($kodeRole === 'mentor') {
            // Mentor hanya bisa lihat mentee-nya
            $pendingActivities = $this->activityModel->getPendingForMentor($mentorId);
        } else {
            // Admin/HR bisa lihat semua
            $pendingActivities = $this->activityModel
                ->select('daily_activities.*, users.nama_lengkap, users.nik, divisi.nama_divisi')
                ->join('users', 'users.id_user = daily_activities.id_user')
                ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
                ->where('daily_activities.status_approval', 'submitted')
                ->orderBy('daily_activities.created_at', 'ASC')
                ->findAll();
        }

        // Get statistics
        $stats = [
            'pending' => count($pendingActivities),
            'today' => count(array_filter($pendingActivities, function ($act) {
                return $act['tanggal'] === date('Y-m-d');
            }))
        ];

        $data = [
            'title' => 'Approval Aktivitas',
            'activities' => $pendingActivities,
            'stats' => $stats
        ];

        return view('activity/approval', $data);
    }

    /**
     * Approve activity
     */
    public function approve($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $mentorId = session()->get('user_id');
        $kodeRole = session()->get('kode_role');

        $activity = $this->activityModel->find($id);

        if (!$activity) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aktivitas tidak ditemukan'
            ]);
        }

        // Check permission
        if ($kodeRole === 'mentor') {
            // Check if this intern is mentee of current mentor
            $intern = $this->internModel->where('id_user', $activity['id_user'])->first();

            if (!$intern || $intern['id_mentor'] != $mentorId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk approve aktivitas ini'
                ]);
            }
        }

        // Check status
        if ($activity['status_approval'] !== 'submitted') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status aktivitas tidak valid untuk di-approve'
            ]);
        }

        $catatan = $this->request->getPost('catatan');

        $updateData = [
            'status_approval' => 'approved',
            'approved_by' => $mentorId,
            'approved_at' => $this->getCurrentDateTime(),
            'catatan_mentor' => $catatan
        ];

        if (!$this->activityModel->update($id, $updateData)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal approve aktivitas'
            ]);
        }

        // Kirim notifikasi ke pemagang
        (new \App\Libraries\NotificationService())->activityApproved(
            (int) $activity['id_user'],
            $activity['judul_aktivitas'] ?? 'Aktivitas',
            session()->get('nama_lengkap') ?? 'Mentor'
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Aktivitas berhasil di-approve'
        ]);
    }

    /**
     * Reject activity
     */
    public function reject($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $mentorId = session()->get('user_id');
        $kodeRole = session()->get('kode_role');

        $activity = $this->activityModel->find($id);

        if (!$activity) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aktivitas tidak ditemukan'
            ]);
        }

        // Check permission
        if ($kodeRole === 'mentor') {
            $intern = $this->internModel->where('id_user', $activity['id_user'])->first();

            if (!$intern || $intern['id_mentor'] != $mentorId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk reject aktivitas ini'
                ]);
            }
        }

        // Check status
        if ($activity['status_approval'] !== 'submitted') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status aktivitas tidak valid untuk di-reject'
            ]);
        }

        $catatan = $this->request->getPost('catatan');

        if (empty($catatan)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Alasan penolakan wajib diisi'
            ]);
        }

        $updateData = [
            'status_approval' => 'rejected',
            'approved_by' => $mentorId,
            'approved_at' => $this->getCurrentDateTime(),
            'catatan_mentor' => $catatan
        ];

        if (!$this->activityModel->update($id, $updateData)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal reject aktivitas'
            ]);
        }

        // Kirim notifikasi ke pemagang
        (new \App\Libraries\NotificationService())->activityRejected(
            (int) $activity['id_user'],
            $activity['judul_aktivitas'] ?? 'Aktivitas',
            $catatan
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Aktivitas berhasil di-reject'
        ]);
    }

    /**
     * Batch approve activities
     */
    public function batchApprove()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $mentorId = session()->get('user_id');
        $activityIds = $this->request->getPost('activity_ids');

        if (empty($activityIds) || !is_array($activityIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pilih minimal 1 aktivitas'
            ]);
        }

        $approved = 0;
        $failed = 0;

        foreach ($activityIds as $id) {
            $activity = $this->activityModel->find($id);

            if (!$activity || $activity['status_approval'] !== 'submitted') {
                $failed++;
                continue;
            }

            $updateData = [
                'status_approval' => 'approved',
                'approved_by' => $mentorId,
                'approved_at' => $this->getCurrentDateTime()
            ];

            if ($this->activityModel->update($id, $updateData)) {
                $approved++;
                // Kirim notifikasi ke pemagang
                (new \App\Libraries\NotificationService())->activityApproved(
                    (int) $activity['id_user'],
                    $activity['judul_aktivitas'] ?? 'Aktivitas',
                    session()->get('nama_lengkap') ?? 'Mentor'
                );
            } else {
                $failed++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "$approved aktivitas berhasil di-approve" . ($failed > 0 ? ", $failed gagal" : "")
        ]);
    }

    // ============================================
    // ADMIN/HR SIDE - MONITORING
    // ============================================

    /**
     * Admin index - All activities with advanced filters
     */
    public function index()
    {
        $perPage = (int) ($this->request->getGet('perPage') ?? 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $currentPage = $this->request->getGet('page') ?? 1;

        // Get filters from query params
        $filters = [
            'start_date' => $this->request->getGet('start_date') ?? date('Y-m-01'),
            'end_date' => $this->request->getGet('end_date') ?? date('Y-m-t'),
            'status' => $this->request->getGet('status'),
            'kategori' => $this->request->getGet('kategori'),
            'divisi' => $this->request->getGet('divisi'),
            'search' => $this->request->getGet('search')
        ];

        // Get activities with pagination
        $activities = $this->activityModel->getActivitiesPaginated(null, $filters, $perPage);
        $pager = $this->activityModel->pager;

        // Get filter options
        $divisiList = $this->divisiModel->where('is_active', 1)->findAll();

        $data = [
            'title' => 'Semua Aktivitas',
            'activities' => $activities,
            'pager' => $pager,
            'total' => $pager->getTotal(),
            'perPage' => $perPage,
            'divisi_list' => $divisiList,
            'filters' => $filters,
            'currentPage' => $currentPage,
        ];

        return view('activity/index', $data);
    }

    /**
     * Admin dashboard - Statistics and charts
     */
    public function dashboard()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');

        $filters = [
            'start_date' => $month . '-01',
            'end_date' => date('Y-m-t', strtotime($month . '-01'))
        ];

        // Get statistics
        $stats = $this->activityModel->getAdminStatistics($filters);

        // Get most active interns
        $topInterns = $this->activityModel->getMostActiveInterns(5, $month);

        // Get activities by status over time (for chart)
        $statusTrend = $this->activityModel->getCountByStatus(
            $filters['start_date'],
            $filters['end_date']
        );

        // Get average activities per intern per week
        $totalInterns = $this->internModel->where('status_magang', 'active')->countAllResults();
        $weeksInMonth = 4;
        $avgPerWeek = $totalInterns > 0 ?
            round($stats['total'] / $totalInterns / $weeksInMonth, 1) : 0;

        // Calculate approval rate
        $totalProcessed = $stats['approved'] + $stats['rejected'];
        $approvalRate = $totalProcessed > 0 ?
            round(($stats['approved'] / $totalProcessed) * 100, 1) : 0;

        // Calculate average response time (hours)
        $avgResponseTime = $this->calculateAverageResponseTime($filters);

        $data = [
            'title' => 'Dashboard Aktivitas',
            'stats' => $stats,
            'top_interns' => $topInterns,
            'status_trend' => $statusTrend,
            'avg_per_week' => $avgPerWeek,
            'approval_rate' => $approvalRate,
            'avg_response_time' => $avgResponseTime,
            'selected_month' => $month
        ];

        return view('activity/dashboard', $data);
    }

    /**
     * Calculate average response time (submit to approval)
     */
    private function calculateAverageResponseTime($filters)
    {
        $activities = $this->db->table('daily_activities')
            ->select('TIMESTAMPDIFF(HOUR, created_at, approved_at) as hours')
            ->whereIn('status_approval', ['approved', 'rejected'])  // ✅ GANTI whereIn
            ->where('tanggal >=', $filters['start_date'])
            ->where('tanggal <=', $filters['end_date'])
            ->where('approved_at IS NOT NULL')  // ✅ GANTI cara check NULL
            ->get()
            ->getResultArray();

        if (empty($activities)) {
            return 0;
        }

        $totalHours = array_sum(array_column($activities, 'hours'));
        return round($totalHours / count($activities), 1);
    }

    /**
     * Export activities to Excel
     */
    public function export()
    {
        // Get filters
        $filters = [
            'start_date' => $this->request->getGet('start_date') ?? date('Y-m-01'),
            'end_date' => $this->request->getGet('end_date') ?? date('Y-m-t'),
            'status' => $this->request->getGet('status'),
            'kategori' => $this->request->getGet('kategori'),
            'divisi' => $this->request->getGet('divisi')
        ];

        $activities = $this->activityModel->getActivitiesWithUser(null, $filters);

        // Create CSV
        $filename = 'activities_export_' . date('YmdHis') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, [
            'Tanggal',
            'NIK',
            'Nama',
            'Divisi',
            'Judul Aktivitas',
            'Kategori',
            'Jam Mulai',
            'Jam Selesai',
            'Status',
            'Approved By',
            'Approved At'
        ]);

        // Data
        foreach ($activities as $act) {
            fputcsv($output, [
                $act['tanggal'],
                $act['nik'],
                $act['nama_lengkap'],
                $act['nama_divisi'] ?? '-',
                $act['judul_aktivitas'],
                ucfirst($act['kategori']),
                $act['jam_mulai'],
                $act['jam_selesai'],
                ucfirst($act['status_approval']),
                $act['mentor_name'] ?? '-',
                $act['approved_at'] ?? '-'
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * View attachment
     */
    public function viewAttachment($id)
    {
        $activity = $this->activityModel->find($id);

        if (!$activity || !$activity['attachment']) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/activities/' . $activity['attachment'];

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $mimeType = mime_content_type($filePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $activity['attachment'] . '"')
            ->setBody(file_get_contents($filePath));
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($id)
    {
        $activity = $this->activityModel->find($id);

        if (!$activity || !$activity['attachment']) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/activities/' . $activity['attachment'];

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return $this->response->download($filePath, null);
    }
}
