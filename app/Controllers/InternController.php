<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InternModel;
use App\Models\UserModel;
use App\Models\DivisiModel;
use CodeIgniter\HTTP\ResponseInterface;

class InternController extends BaseController
{
    protected $internModel;
    protected $userModel;
    protected $divisiModel;

    public function __construct()
    {
        $this->internModel = new InternModel();
        $this->userModel = new UserModel();
        $this->divisiModel = new DivisiModel();
        helper(['form', 'filesystem']);
    }

    // GET /intern
    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $perPage = (int) ($this->request->getGet('perPage') ?? 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        // Get paginated data
        $interns = $this->internModel->getInternPaginated($perPage, $search, $status);
        $pager = $this->internModel->pager;

        $data = [
            'interns' => $interns,
            'pager' => $pager,
            'total' => $pager->getTotal(),
            'perPage' => $perPage,
            'statistics' => $this->internModel->getStatistics(),
            'search' => $search,
            'status' => $status,
            'currentPage' => $this->request->getGet('page') ?? 1,
        ];

        return view('admin/intern/index', $data);
    }

    // GET /intern/create
    public function create()
    {
        $data = [
            'divisi' => $this->divisiModel->where('is_active', 1)->findAll(),
            'mentors' => $this->userModel->getMentorList(),
            'nextNIK' => $this->userModel->generateNextNIK('intern')
        ];

        return view('admin/intern/create', $data);
    }

    // POST /intern/store
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        // Validation rules
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'no_hp' => 'required|min_length[10]|max_length[20]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tanggal_lahir' => 'required|valid_date',
            'alamat' => 'required',
            'id_divisi' => 'required|integer',
            'id_mentor' => 'permit_empty|integer',
            'universitas' => 'required|min_length[3]',
            'jurusan' => 'required|min_length[3]',
            'periode_mulai' => 'required|valid_date',
            'periode_selesai' => 'required|valid_date',
            'durasi_bulan' => 'required|integer|greater_than[0]',
            'dokumen_surat_magang' => [
                'rules' => 'uploaded[dokumen_surat_magang]|max_size[dokumen_surat_magang,2048]|ext_in[dokumen_surat_magang,pdf]',
                'errors' => [
                    'uploaded' => 'Surat magang wajib diunggah',
                    'max_size' => 'Ukuran file maksimal 2MB',
                    'ext_in' => 'File harus berformat PDF'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Upload dokumen
            $dokumen = $this->request->getFile('dokumen_surat_magang');
            $dokumenName = null;

            if ($dokumen && $dokumen->isValid() && !$dokumen->hasMoved()) {
                $dokumenName = $dokumen->getRandomName();
                $dokumen->move(WRITEPATH . 'uploads/surat_magang', $dokumenName);
            }

            // Get role intern
            $roleModel = new \App\Models\RoleModel();
            $internRole = $roleModel->where('kode_role', 'intern')->first();

            if (!$internRole) {
                throw new \Exception('Role intern tidak ditemukan');
            }

            // Generate NIK
            $nik = $this->userModel->generateNextNIK('intern');

            // Insert user data
            $userData = [
                'id_role' => $internRole['id_role'],
                'id_divisi' => $this->request->getPost('id_divisi'),
                'nik' => $nik,
                'nama_lengkap' => trim($this->request->getPost('nama_lengkap')),
                'email' => trim($this->request->getPost('email')),
                'no_hp' => $this->request->getPost('no_hp'),
                'password' => password_hash('password123', PASSWORD_DEFAULT), // Default password
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'alamat' => $this->request->getPost('alamat'),
                'status' => 'active'
            ];

            if (!$this->userModel->insert($userData)) {
                throw new \Exception('Gagal menyimpan data user');
            }

            $userId = $this->userModel->getInsertID();

            // Insert intern data
            $internData = [
                'id_user' => $userId,
                'id_mentor' => $this->request->getPost('id_mentor') ?: null,
                'universitas' => trim($this->request->getPost('universitas')),
                'jurusan' => trim($this->request->getPost('jurusan')),
                'periode_mulai' => $this->request->getPost('periode_mulai'),
                'periode_selesai' => $this->request->getPost('periode_selesai'),
                'durasi_bulan' => $this->request->getPost('durasi_bulan'),
                'status_magang' => 'active',
                'dokumen_surat_magang' => $dokumenName,
                'catatan' => $this->request->getPost('catatan')
            ];

            if (!$this->internModel->insert($internData)) {
                throw new \Exception('Gagal menyimpan data pemagang');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pemagang berhasil ditambahkan',
                'redirect' => base_url('intern')
            ]);
        } catch (\Exception $e) {
            $db->transRollback();

            // Hapus file jika ada
            if (isset($dokumenName) && $dokumenName) {
                $filePath = WRITEPATH . 'uploads/surat_magang/' . $dokumenName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // GET /intern/detail/{id}
    public function detail($id)
    {
        $intern = $this->internModel->getInternWithDetails($id);

        if (!$intern) {
            return redirect()->to(base_url('intern'))->with('error', 'Data pemagang tidak ditemukan');
        }

        $data = [
            'intern' => $intern,
            'attendance_summary' => $this->internModel->getAttendanceSummary($intern['id_user']),
            'activity_summary' => $this->internModel->getActivitySummary($intern['id_user'])
        ];

        return view('admin/intern/detail', $data);
    }

    // GET /intern/edit/{id}
    public function edit($id)
    {
        $intern = $this->internModel->getInternWithDetails($id);

        if (!$intern) {
            return redirect()->to(base_url('intern'))->with('error', 'Data pemagang tidak ditemukan');
        }

        $data = [
            'intern' => $intern,
            'divisi' => $this->divisiModel->where('is_active', 1)->findAll(),
            'mentors' => $this->userModel->getMentorList()
        ];

        return view('admin/intern/edit', $data);
    }

    // POST /intern/update/{id}
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $intern = $this->internModel->find($id);
        if (!$intern) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pemagang tidak ditemukan'
            ]);
        }

        // Get user data
        $user = $this->userModel->find($intern['id_user']);

        // Validation rules
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'email' => "required|valid_email|is_unique[users.email,id_user,{$user['id_user']}]",
            'no_hp' => 'required|min_length[10]|max_length[20]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tanggal_lahir' => 'required|valid_date',
            'alamat' => 'required',
            'id_divisi' => 'required|integer',
            'id_mentor' => 'permit_empty|integer',
            'universitas' => 'required|min_length[3]',
            'jurusan' => 'required|min_length[3]',
            'periode_mulai' => 'required|valid_date',
            'periode_selesai' => 'required|valid_date',
            'durasi_bulan' => 'required|integer|greater_than[0]',
            'status_magang' => 'required|in_list[active,completed,terminated]'
        ];

        // Add dokumen validation only if uploaded
        if ($this->request->getFile('dokumen_surat_magang')->isValid()) {
            $rules['dokumen_surat_magang'] = [
                'rules' => 'max_size[dokumen_surat_magang,2048]|ext_in[dokumen_surat_magang,pdf]',
                'errors' => [
                    'max_size' => 'Ukuran file maksimal 2MB',
                    'ext_in' => 'File harus berformat PDF'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Handle dokumen upload
            $dokumenName = $intern['dokumen_surat_magang'];
            $dokumen = $this->request->getFile('dokumen_surat_magang');

            if ($dokumen && $dokumen->isValid() && !$dokumen->hasMoved()) {
                // Delete old file
                if ($dokumenName) {
                    $oldFile = WRITEPATH . 'uploads/surat_magang/' . $dokumenName;
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                // Upload new file
                $dokumenName = $dokumen->getRandomName();
                $dokumen->move(WRITEPATH . 'uploads/surat_magang', $dokumenName);
            }

            // Update user data
            $userData = [
                'id_divisi' => $this->request->getPost('id_divisi'),
                'nama_lengkap' => trim($this->request->getPost('nama_lengkap')),
                'email' => trim($this->request->getPost('email')),
                'no_hp' => $this->request->getPost('no_hp'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'alamat' => $this->request->getPost('alamat')
            ];

            if (!$this->userModel->update($user['id_user'], $userData)) {
                throw new \Exception('Gagal mengupdate data user');
            }

            // Update intern data
            $internData = [
                'id_mentor' => $this->request->getPost('id_mentor') ?: null,
                'universitas' => trim($this->request->getPost('universitas')),
                'jurusan' => trim($this->request->getPost('jurusan')),
                'periode_mulai' => $this->request->getPost('periode_mulai'),
                'periode_selesai' => $this->request->getPost('periode_selesai'),
                'durasi_bulan' => $this->request->getPost('durasi_bulan'),
                'status_magang' => $this->request->getPost('status_magang'),
                'dokumen_surat_magang' => $dokumenName,
                'catatan' => $this->request->getPost('catatan')
            ];

            if (!$this->internModel->update($id, $internData)) {
                throw new \Exception('Gagal mengupdate data pemagang');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pemagang berhasil diperbarui',
                'redirect' => base_url('intern')
            ]);
        } catch (\Exception $e) {
            $db->transRollback();

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // DELETE /intern/delete/{id}
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $intern = $this->internModel->find($id);
        if (!$intern) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pemagang tidak ditemukan'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete dokumen file
            if ($intern['dokumen_surat_magang']) {
                $filePath = WRITEPATH . 'uploads/surat_magang/' . $intern['dokumen_surat_magang'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete intern record (will cascade delete user due to FK)
            if (!$this->internModel->delete($id)) {
                throw new \Exception('Gagal menghapus data pemagang');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pemagang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $db->transRollback();

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // POST /intern/toggle-status/{id}
    public function toggleStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $intern = $this->internModel->find($id);
        if (!$intern) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pemagang tidak ditemukan'
            ]);
        }

        // Get new status from request or toggle automatically
        $jsonData = $this->request->getJSON(true);
        $newStatus = $jsonData['new_status'] ?? null;

        if (!$newStatus) {
            // Auto toggle: active -> completed -> active
            $newStatus = match ($intern['status_magang']) {
                'active' => 'completed',
                'completed' => 'active',
                'terminated' => 'active',
                default => 'active'
            };
        }

        if (!$this->internModel->update($id, ['status_magang' => $newStatus])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengubah status'
            ]);
        }

        $statusLabels = [
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'terminated' => 'Diberhentikan'
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status berhasil diubah menjadi ' . $statusLabels[$newStatus]
        ]);
    }

    // GET /intern/download-document/{id}
    public function downloadDocument($id)
    {
        $intern = $this->internModel->find($id);

        if (!$intern) {
            return redirect()->to(base_url('intern'))->with('error', 'Data pemagang tidak ditemukan');
        }

        if (!$intern['dokumen_surat_magang']) {
            return redirect()->back()->with('error', 'Dokumen tidak tersedia');
        }

        $filePath = WRITEPATH . 'uploads/surat_magang/' . $intern['dokumen_surat_magang'];

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File dokumen tidak ditemukan');
        }

        // Set headers untuk display PDF di browser
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $intern['dokumen_surat_magang'] . '"')
            ->setBody(file_get_contents($filePath));
    }
}
