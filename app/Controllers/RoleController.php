<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use CodeIgniter\HTTP\ResponseInterface;

class RoleController extends BaseController
{
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        helper(['form']);
    }

    // GET /role
    public function index()
    {
        $data = [
            'roles' => $this->roleModel->getRoleWithUserCount(),
            'statistics' => $this->roleModel->getStatistics(),
        ];

        return view('admin/role/index', $data);
    }

    // GET /role/create
    public function create()
    {
        return view('admin/role/create');
    }

    // POST /role/store
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $data = [
            'nama_role' => trim($this->request->getPost('nama_role')),
            'kode_role' => strtolower(trim($this->request->getPost('kode_role'))),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!$this->roleModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => $this->roleModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Role berhasil ditambahkan',
            'redirect' => base_url('role')
        ]);
    }

    // GET /role/edit/{id}
    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        if (!$role) {
            return redirect()->to(base_url('role'))->with('error', 'Role tidak ditemukan');
        }

        return view('admin/role/edit', ['role' => $role]);
    }

    // PUT /role/update/{id} - DIPERBAIKI
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak ditemukan'
            ]);
        }

        // Ambil data dari request
        $kodeRole = strtolower(trim($this->request->getPost('kode_role')));

        // Cek apakah kode_role sudah digunakan oleh role lain (bukan diri sendiri)
        $existingRole = $this->roleModel
            ->where('kode_role', $kodeRole)
            ->where('id_role !=', $id)
            ->first();

        if ($existingRole) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => [
                    'kode_role' => 'Kode role sudah digunakan'
                ],
            ]);
        }

        $data = [
            'nama_role' => trim($this->request->getPost('nama_role')),
            'kode_role' => $kodeRole,
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Validasi data tanpa is_unique
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_role' => [
                'label' => 'Nama role',
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Nama role wajib diisi',
                    'min_length' => 'Nama role minimal 3 karakter',
                    'max_length' => 'Nama role maksimal 50 karakter'
                ]
            ],
            'kode_role' => [
                'label' => 'Kode role',
                'rules' => 'required|alpha_dash|min_length[2]|max_length[20]',
                'errors' => [
                    'required' => 'Kode role wajib diisi',
                    'alpha_dash' => 'Kode role hanya boleh berisi huruf, angka, underscore dan dash',
                    'min_length' => 'Kode role minimal 2 karakter',
                    'max_length' => 'Kode role maksimal 20 karakter'
                ]
            ],
            'deskripsi' => 'permit_empty|max_length[500]'
        ]);

        if (!$validation->run($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => $validation->getErrors(),
            ]);
        }

        // Update data
        $updated = $this->roleModel->update($id, $data);

        if (!$updated) {
            // Log error untuk debugging
            log_message('error', 'Failed to update role ID ' . $id . ': ' . json_encode($this->roleModel->errors()));

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate role. Silakan coba lagi.',
                'errors'  => $this->roleModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Role berhasil diperbarui',
            'redirect' => base_url('role')
        ]);
    }

    // GET /role/detail/{id}
    public function detail($id)
    {
        $role = $this->roleModel->getRoleWithUserCount($id);
        if (!$role) {
            return redirect()->to(base_url('role'))->with('error', 'Role tidak ditemukan');
        }

        return view('admin/role/detail', ['role' => $role]);
    }

    // DELETE /role/delete/{id}
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak ditemukan'
            ]);
        }

        if (!$this->roleModel->canDelete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak dapat dihapus karena masih digunakan oleh user'
            ]);
        }

        if (!$this->roleModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus role'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Role berhasil dihapus'
        ]);
    }

    // POST /role/toggle-status/{id}
    public function toggleStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $updated = $this->roleModel->toggleStatus($id);
        if (!$updated) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak ditemukan atau gagal mengubah status'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status role berhasil diubah'
        ]);
    }
}
