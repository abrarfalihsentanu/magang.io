<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\DivisiModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $divisiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->divisiModel = new DivisiModel();
        helper(['form']);
    }

    // GET /user
    public function index()
    {
        $search = $this->request->getGet('search');
        $role = $this->request->getGet('role');
        $perPage = (int) ($this->request->getGet('perPage') ?? 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $users = $this->userModel->getUserPaginated($perPage, $search, $role);
        $pager = $this->userModel->pager;

        $data = [
            'users' => $users,
            'pager' => $pager,
            'total' => $pager->getTotal(),
            'perPage' => $perPage,
            'statistics' => $this->userModel->getStatistics(),
            'roles' => $this->roleModel->findAll(),
            'search' => $search,
            'role' => $role,
            'currentPage' => $this->request->getGet('page') ?? 1,
        ];

        return view('admin/user/index', $data);
    }

    // GET /user/create
    public function create()
    {
        $data = [
            'roles' => $this->roleModel->getActiveRoles(),
            'divisi' => $this->divisiModel->getActiveDivisi(),
        ];

        return view('admin/user/create', $data);
    }

    // POST /user/store
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        // Generate NIK otomatis
        $idRole = $this->request->getPost('id_role');
        $nik = $this->generateNIK($idRole);

        // Handle password
        $password = $this->request->getPost('password');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'id_role' => $idRole,
            'id_divisi' => $this->request->getPost('id_divisi') ?: null,
            'nik' => $nik,
            'nama_lengkap' => trim($this->request->getPost('nama_lengkap')),
            'email' => trim($this->request->getPost('email')),
            'no_hp' => $this->request->getPost('no_hp'),
            'password' => $hashedPassword,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat' => $this->request->getPost('alamat'),
            'nomor_rekening' => $this->request->getPost('nomor_rekening') ?: null,
            'nama_bank' => $this->request->getPost('nama_bank') ?: null,
            'atas_nama' => $this->request->getPost('atas_nama') ?: null,
            'status' => 'active',
        ];

        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_role' => 'required|integer',
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'no_hp' => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'password' => 'required|min_length[6]',
            'jenis_kelamin' => 'permit_empty|in_list[L,P]',
        ], [
            'id_role' => [
                'required' => 'Role wajib dipilih',
                'integer' => 'Role tidak valid'
            ],
            'nama_lengkap' => [
                'required' => 'Nama lengkap wajib diisi',
                'min_length' => 'Nama lengkap minimal 3 karakter',
                'max_length' => 'Nama lengkap maksimal 150 karakter'
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid',
                'is_unique' => 'Email sudah digunakan'
            ],
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
        ]);

        if (!$validation->run($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors' => $validation->getErrors(),
            ]);
        }

        if (!$this->userModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menambahkan user',
                'errors' => $this->userModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User berhasil ditambahkan dengan NIK: ' . $nik,
            'nik' => $nik,
            'redirect' => base_url('user')
        ]);
    }

    // GET /user/detail/{id}
    public function detail($id)
    {
        $user = $this->userModel->getUserDetail($id);
        if (!$user) {
            return redirect()->to(base_url('user'))->with('error', 'User tidak ditemukan');
        }

        return view('admin/user/detail', ['user' => $user]);
    }

    // GET /user/edit/{id}
    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('user'))->with('error', 'User tidak ditemukan');
        }

        $data = [
            'user' => $user,
            'roles' => $this->roleModel->getActiveRoles(),
            'divisi' => $this->divisiModel->getActiveDivisi(),
        ];

        return view('admin/user/edit', $data);
    }

    // POST /user/update/{id}
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        // Cek email unique (kecuali email sendiri)
        $email = trim($this->request->getPost('email'));
        $existingUser = $this->userModel
            ->where('email', $email)
            ->where('id_user !=', $id)
            ->first();

        if ($existingUser) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => ['email' => 'Email sudah digunakan']
            ]);
        }

        $data = [
            'id_role' => $this->request->getPost('id_role'),
            'id_divisi' => $this->request->getPost('id_divisi') ?: null,
            'nama_lengkap' => trim($this->request->getPost('nama_lengkap')),
            'email' => $email,
            'no_hp' => $this->request->getPost('no_hp'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat' => $this->request->getPost('alamat'),
            'nomor_rekening' => $this->request->getPost('nomor_rekening') ?: null,
            'nama_bank' => $this->request->getPost('nama_bank') ?: null,
            'atas_nama' => $this->request->getPost('atas_nama') ?: null,
            'status' => $this->request->getPost('status'),
        ];

        // Update password jika diisi
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Validasi
        $validation = \Config\Services::validation();
        $rules = [
            'id_role' => 'required|integer',
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'email' => 'required|valid_email',
            'no_hp' => 'permit_empty|numeric|min_length[10]|max_length[15]',
        ];

        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validation->getErrors(),
            ]);
        }

        if (!$this->userModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate user',
                'errors' => $this->userModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'redirect' => base_url('user')
        ]);
    }

    // DELETE /user/delete/{id}
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        // Cek apakah bisa dihapus
        if (!$this->userModel->canDelete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak dapat dihapus karena memiliki data terkait'
            ]);
        }

        if (!$this->userModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus user'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }

    // Helper: Generate NIK otomatis
    private function generateNIK($idRole)
    {
        $role = $this->roleModel->find($idRole);
        if (!$role) {
            return 'USR' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        }

        // Prefix berdasarkan kode role
        $kodeRole = strtoupper($role['kode_role']);
        $prefix = match ($kodeRole) {
            'ADMIN' => 'ADM',
            'HR' => 'HR',
            'FINANCE' => 'FIN',
            'MENTOR' => 'MEN',
            'INTERN' => 'INT',
            default => 'USR',
        };

        // Ambil nomor terakhir untuk prefix ini
        $lastUser = $this->userModel
            ->like('nik', $prefix, 'after')
            ->orderBy('id_user', 'DESC')
            ->first();

        if ($lastUser) {
            // Extract nomor dari NIK terakhir
            $lastNumber = (int) substr($lastUser['nik'], strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // AJAX: Get next NIK preview
    public function getNextNIK()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false
            ]);
        }

        $idRole = $this->request->getPost('id_role');
        $nik = $this->generateNIK($idRole);

        return $this->response->setJSON([
            'success' => true,
            'nik' => $nik
        ]);
    }
}
