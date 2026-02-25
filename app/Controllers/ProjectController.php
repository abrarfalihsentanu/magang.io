<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WeeklyProjectModel;
use App\Models\InternModel;
use App\Models\DivisiModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProjectController extends BaseController
{
    protected $projectModel;
    protected $internModel;
    protected $divisiModel;
    protected $db;

    public function __construct()
    {
        $this->projectModel = new WeeklyProjectModel();
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
    // INTERN SIDE - MY PROJECTS
    // ============================================

    /**
     * List projects untuk intern
     */
    public function my()
    {
        $userId = session()->get('user_id');
        $year = $this->request->getGet('year') ?? date('Y');

        // Get projects
        $projects = $this->projectModel->getProjectsWithUser($userId, ['tahun' => $year]);
        $statistics = $this->projectModel->getUserStatistics($userId, $year);
        $weekInfo = $this->projectModel->getCurrentWeekInfo();
        $hasSubmittedThisWeek = $this->projectModel->hasSubmittedThisWeek($userId);

        $data = [
            'title' => 'Project Mingguan Saya',
            'projects' => $projects,
            'statistics' => $statistics,
            'week_info' => $weekInfo,
            'has_submitted' => $hasSubmittedThisWeek,
            'can_submit' => $this->projectModel->canSubmitThisWeek(),
            'selected_year' => $year
        ];

        return view('project/my', $data);
    }

    /**
     * Detail project view
     */
    public function detail($id)
    {
        $userId = session()->get('user_id');
        $kodeRole = session()->get('kode_role');

        // Get project with user details
        $project = $this->db->table('weekly_projects')
            ->select('weekly_projects.*, 
                 users.nama_lengkap, 
                 users.nik, 
                 divisi.nama_divisi,
                 assessor.nama_lengkap as assessor_name')
            ->join('users', 'users.id_user = weekly_projects.id_user')
            ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
            ->join('users as assessor', 'assessor.id_user = weekly_projects.assessed_by', 'left')
            ->where('weekly_projects.id_project', $id)
            ->get()
            ->getRowArray();

        if (!$project) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project tidak ditemukan'
                ]);
            }

            return redirect()->to(base_url('project/my'))
                ->with('error', 'Project tidak ditemukan');
        }

        // Check permission
        if ($kodeRole === 'intern') {
            if ($project['id_user'] != $userId) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses'
                    ]);
                }

                return redirect()->to(base_url('project/my'))
                    ->with('error', 'Anda tidak memiliki akses');
            }
        } elseif ($kodeRole === 'mentor') {
            $intern = $this->internModel->where('id_user', $project['id_user'])->first();

            if (!$intern || $intern['id_mentor'] != $userId) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses'
                    ]);
                }

                return redirect()->to(base_url('project/assessment'))
                    ->with('error', 'Anda tidak memiliki akses');
            }
        }

        // For AJAX request (modal)
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'project' => $project
            ]);
        }

        // For normal request (page)
        $data = [
            'title' => 'Detail Project',
            'project' => $project
        ];

        return view('project/detail', $data);
    }

    /**
     * Create project page
     */
    public function create()
    {
        $userId = session()->get('user_id');
        $weekInfo = $this->projectModel->getCurrentWeekInfo();

        // Check if already submitted this week
        if ($this->projectModel->hasSubmittedThisWeek($userId)) {
            return redirect()->to(base_url('project/my'))
                ->with('error', 'Anda sudah submit project untuk minggu ini');
        }

        // Check if still can submit (Max Friday)
        if (!$this->projectModel->canSubmitThisWeek()) {
            return redirect()->to(base_url('project/my'))
                ->with('error', 'Waktu submit project sudah habis. Submit maksimal hari Jumat.');
        }

        $data = [
            'title' => 'Submit Weekly Project',
            'week_info' => $weekInfo
        ];

        return view('project/create', $data);
    }

    /**
     * Store new project
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
        $weekInfo = $this->projectModel->getCurrentWeekInfo();

        // Check if already submitted
        if ($this->projectModel->hasSubmittedThisWeek($userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda sudah submit project untuk minggu ini'
            ]);
        }

        // Check deadline
        if (!$this->projectModel->canSubmitThisWeek()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Waktu submit sudah habis. Submit maksimal hari Jumat.'
            ]);
        }

        // Validation rules
        $rules = [
            'judul_project' => 'required|min_length[10]|max_length[200]',
            'tipe_project' => 'required|in_list[inisiatif,assigned]',
            'deskripsi' => 'required|min_length[100]',
            'progress' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'deliverables' => 'required|min_length[20]',
            'self_rating' => 'required|decimal|greater_than_equal_to[1.0]|less_than_equal_to[5.0]',
            'attachment' => 'uploaded[attachment]|max_size[attachment,10240]|ext_in[attachment,pdf,jpg,jpeg,png,zip]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Handle file upload
        $attachmentName = null;
        $attachment = $this->request->getFile('attachment');

        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            if ($attachment->getSize() > 10 * 1024 * 1024) { // 10MB
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ukuran file maksimal 10MB'
                ]);
            }

            $attachmentName = 'project_' . $userId . '_week' . $weekInfo['week_number'] . '_' . time() . '.' . $attachment->getExtension();
            $attachment->move(WRITEPATH . 'uploads/projects', $attachmentName);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Attachment wajib diupload'
            ]);
        }

        // Get status from request
        $status = $this->request->getPost('status_submission') ?? 'draft';

        if (!in_array($status, ['draft', 'submitted'])) {
            $status = 'draft';
        }

        $data = [
            'id_user' => $userId,
            'week_number' => $weekInfo['week_number'],
            'tahun' => $weekInfo['tahun'],
            'periode_mulai' => $weekInfo['periode_mulai'],
            'periode_selesai' => $weekInfo['periode_selesai'],
            'judul_project' => $this->request->getPost('judul_project'),
            'tipe_project' => $this->request->getPost('tipe_project'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'progress' => $this->request->getPost('progress'),
            'deliverables' => $this->request->getPost('deliverables'),
            'attachment' => $attachmentName,
            'self_rating' => $this->request->getPost('self_rating'),
            'status_submission' => $status
        ];

        if (!$this->projectModel->insert($data)) {
            // Delete uploaded file if insert fails
            if ($attachmentName) {
                $filePath = WRITEPATH . 'uploads/projects/' . $attachmentName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan project',
                'errors' => $this->projectModel->errors()
            ]);
        }

        $message = $status === 'draft' ?
            'Project berhasil disimpan sebagai draft' :
            'Project berhasil disubmit untuk assessment';

        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Edit project page
     */
    public function edit($id)
    {
        $userId = session()->get('user_id');

        if (!$this->projectModel->canEdit($id, $userId)) {
            return redirect()->to(base_url('project/my'))
                ->with('error', 'Anda tidak dapat mengedit project ini');
        }

        $project = $this->projectModel->find($id);

        if (!$project) {
            return redirect()->to(base_url('project/my'))
                ->with('error', 'Project tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Project',
            'project' => $project
        ];

        return view('project/edit', $data);
    }

    /**
     * Update project
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

        if (!$this->projectModel->canEdit($id, $userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak dapat mengedit project ini'
            ]);
        }

        $project = $this->projectModel->find($id);

        if (!$project) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project tidak ditemukan'
            ]);
        }

        // Validation rules (same as store, but attachment optional)
        $rules = [
            'judul_project' => 'required|min_length[10]|max_length[200]',
            'tipe_project' => 'required|in_list[inisiatif,assigned]',
            'deskripsi' => 'required|min_length[100]',
            'progress' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'deliverables' => 'required|min_length[20]',
            'self_rating' => 'required|decimal|greater_than_equal_to[1.0]|less_than_equal_to[5.0]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Handle file upload
        $attachmentName = $project['attachment'];
        $attachment = $this->request->getFile('attachment');

        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            if ($attachment->getSize() > 10 * 1024 * 1024) { // 10MB
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ukuran file maksimal 10MB'
                ]);
            }

            // Delete old file
            if ($attachmentName) {
                $oldFile = WRITEPATH . 'uploads/projects/' . $attachmentName;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $attachmentName = 'project_' . $userId . '_week' . $project['week_number'] . '_' . time() . '.' . $attachment->getExtension();
            $attachment->move(WRITEPATH . 'uploads/projects', $attachmentName);
        }

        $status = $this->request->getPost('status_submission') ?? 'draft';

        if (!in_array($status, ['draft', 'submitted'])) {
            $status = 'draft';
        }

        $data = [
            'judul_project' => $this->request->getPost('judul_project'),
            'tipe_project' => $this->request->getPost('tipe_project'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'progress' => $this->request->getPost('progress'),
            'deliverables' => $this->request->getPost('deliverables'),
            'attachment' => $attachmentName,
            'self_rating' => $this->request->getPost('self_rating'),
            'status_submission' => $status
        ];

        if (!$this->projectModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate project',
                'errors' => $this->projectModel->errors()
            ]);
        }

        $message = $status === 'draft' ?
            'Project berhasil diupdate' :
            'Project berhasil disubmit untuk assessment';

        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Delete project
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

        if (!$this->projectModel->canDelete($id, $userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus project ini'
            ]);
        }

        $project = $this->projectModel->find($id);

        if (!$project) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project tidak ditemukan'
            ]);
        }

        // Delete attachment if exists
        if ($project['attachment']) {
            $filePath = WRITEPATH . 'uploads/projects/' . $project['attachment'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if (!$this->projectModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus project'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Project berhasil dihapus'
        ]);
    }

    // ============================================
    // MENTOR SIDE - ASSESSMENT
    // ============================================

    /**
     * Assessment page for mentor
     */
    public function assessment()
    {
        $mentorId = session()->get('user_id');
        $kodeRole = session()->get('kode_role');

        // Get pending projects
        if ($kodeRole === 'mentor') {
            $pendingProjects = $this->projectModel->getPendingForMentor($mentorId);
        } else {
            // Admin/HR bisa lihat semua
            $pendingProjects = $this->projectModel
                ->select('weekly_projects.*, users.nama_lengkap, users.nik, divisi.nama_divisi')
                ->join('users', 'users.id_user = weekly_projects.id_user')
                ->join('divisi', 'divisi.id_divisi = users.id_divisi', 'left')
                ->where('weekly_projects.status_submission', 'submitted')
                ->orderBy('weekly_projects.created_at', 'ASC')
                ->findAll();
        }

        $data = [
            'title' => 'Assessment Project',
            'projects' => $pendingProjects,
            'pending_count' => count($pendingProjects)
        ];

        return view('project/assessment', $data);
    }

    /**
     * Assess project
     */
    public function assess($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $mentorId = session()->get('user_id');
        $kodeRole = session()->get('kode_role');

        $project = $this->projectModel->find($id);

        if (!$project) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project tidak ditemukan'
            ]);
        }

        // Check permission
        if ($kodeRole === 'mentor') {
            $intern = $this->internModel->where('id_user', $project['id_user'])->first();

            if (!$intern || $intern['id_mentor'] != $mentorId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk assess project ini'
                ]);
            }
        }

        // Check status
        if ($project['status_submission'] !== 'submitted') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status project tidak valid untuk di-assess'
            ]);
        }

        // Validation
        $rules = [
            'mentor_rating' => 'required|decimal|greater_than_equal_to[1.0]|less_than_equal_to[5.0]',
            'feedback_mentor' => 'required|min_length[30]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $action = $this->request->getPost('action') ?? 'assess';
        $mentorRating = $this->request->getPost('mentor_rating');
        $feedback = $this->request->getPost('feedback_mentor');

        $updateData = [
            'mentor_rating' => $mentorRating,
            'feedback_mentor' => $feedback,
            'status_submission' => 'assessed',
            'assessed_by' => $mentorId,
            'assessed_at' => $this->getCurrentDateTime()
        ];

        if (!$this->projectModel->update($id, $updateData)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan assessment'
            ]);
        }

        $message = 'Assessment berhasil disimpan';

        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    // ============================================
    // ADMIN/HR SIDE - MONITORING
    // ============================================

    /**
     * Admin index - All projects
     */
    public function index()
    {
        $perPage = (int) ($this->request->getGet('perPage') ?? 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $currentPage = $this->request->getGet('page') ?? 1;

        $filters = [
            'tahun' => $this->request->getGet('tahun') ?? date('Y'),
            'week_number' => $this->request->getGet('week_number'),
            'status' => $this->request->getGet('status'),
            'tipe_project' => $this->request->getGet('tipe_project'),
            'divisi' => $this->request->getGet('divisi'),
            'search' => $this->request->getGet('search')
        ];

        $projects = $this->projectModel->getProjectsPaginated(null, $filters, $perPage);
        $pager = $this->projectModel->pager;
        $divisiList = $this->divisiModel->where('is_active', 1)->findAll();

        $data = [
            'title' => 'Semua Project',
            'projects' => $projects,
            'divisi_list' => $divisiList,
            'filters' => $filters,
            'pager' => $pager,
            'total' => $pager->getTotal(),
            'perPage' => $perPage,
            'currentPage' => $currentPage
        ];

        return view('project/index', $data);
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $year = $this->request->getGet('year') ?? date('Y');

        $filters = ['tahun' => $year];

        $stats = $this->projectModel->getAdminStatistics($filters);
        $topPerformers = $this->projectModel->getTopPerformers(5, $year);
        $ratingDistribution = $this->projectModel->getRatingDistribution($year);

        $data = [
            'title' => 'Dashboard Project',
            'stats' => $stats,
            'top_performers' => $topPerformers,
            'rating_distribution' => $ratingDistribution,
            'selected_year' => $year
        ];

        return view('project/dashboard', $data);
    }

    /**
     * Export projects
     */
    public function export()
    {
        $filters = [
            'tahun' => $this->request->getGet('tahun') ?? date('Y'),
            'week_number' => $this->request->getGet('week_number'),
            'status' => $this->request->getGet('status')
        ];

        $projects = $this->projectModel->getProjectsWithUser(null, $filters);

        $filename = 'projects_export_' . date('YmdHis') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, [
            'Week',
            'Periode',
            'NIK',
            'Nama',
            'Divisi',
            'Judul Project',
            'Tipe',
            'Progress',
            'Self Rating',
            'Mentor Rating',
            'Status',
            'Assessed By'
        ]);

        // Data
        foreach ($projects as $proj) {
            fputcsv($output, [
                'Week ' . $proj['week_number'] . ' - ' . $proj['tahun'],
                date('d M', strtotime($proj['periode_mulai'])) . ' - ' . date('d M Y', strtotime($proj['periode_selesai'])),
                $proj['nik'],
                $proj['nama_lengkap'],
                $proj['nama_divisi'] ?? '-',
                $proj['judul_project'],
                ucfirst($proj['tipe_project']),
                $proj['progress'] . '%',
                $proj['self_rating'] ?? '-',
                $proj['mentor_rating'] ?? '-',
                ucfirst($proj['status']),
                $proj['assessor_name'] ?? '-'
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
        $project = $this->projectModel->find($id);

        if (!$project || !$project['attachment']) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/projects/' . $project['attachment'];

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $mimeType = mime_content_type($filePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $project['attachment'] . '"')
            ->setBody(file_get_contents($filePath));
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($id)
    {
        $project = $this->projectModel->find($id);

        if (!$project || !$project['attachment']) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/projects/' . $project['attachment'];

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return $this->response->download($filePath, null);
    }
}
