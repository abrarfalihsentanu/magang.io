<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DivisiModel;
use CodeIgniter\HTTP\ResponseInterface;

class DivisiController extends BaseController
{
    protected $divisiModel;

    public function __construct()
    {
        $this->divisiModel = new DivisiModel();
        helper(['form']);
    }

    // GET /divisi
    public function index()
    {
        $data = [
            'divisi' => $this->divisiModel->getDivisiWithUserCount(),
            'statistics' => $this->divisiModel->getStatistics(),
        ];

        return view('admin/divisi/index', $data);
    }

    // GET /divisi/create
    public function create()
    {
        return view('admin/divisi/create');
    }

    // POST /divisi/store
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $data = [
            'nama_divisi' => trim($this->request->getPost('nama_divisi')),
            'kode_divisi' => strtoupper(trim($this->request->getPost('kode_divisi'))),
            'kepala_divisi' => trim($this->request->getPost('kepala_divisi')),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!$this->divisiModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => $this->divisiModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Divisi berhasil ditambahkan',
            'redirect' => base_url('divisi')
        ]);
    }

    // GET /divisi/edit/{id}
    public function edit($id)
    {
        $divisi = $this->divisiModel->find($id);
        if (!$divisi) {
            return redirect()->to(base_url('divisi'))->with('error', 'Divisi tidak ditemukan');
        }

        return view('admin/divisi/edit', ['divisi' => $divisi]);
    }

    // POST /divisi/update/{id}
    // POST /divisi/update/{id}
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $divisi = $this->divisiModel->find($id);
        if (!$divisi) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Divisi tidak ditemukan'
            ]);
        }

        // Ambil data dari request
        $kodeDivisi = strtoupper(trim($this->request->getPost('kode_divisi')));

        // Cek apakah kode_divisi sudah digunakan oleh divisi lain (bukan diri sendiri)
        $existingDivisi = $this->divisiModel
            ->where('kode_divisi', $kodeDivisi)
            ->where('id_divisi !=', $id)
            ->first();

        if ($existingDivisi) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => [
                    'kode_divisi' => 'Kode divisi sudah digunakan'
                ],
            ]);
        }

        $data = [
            'nama_divisi' => trim($this->request->getPost('nama_divisi')),
            'kode_divisi' => $kodeDivisi,
            'kepala_divisi' => trim($this->request->getPost('kepala_divisi')),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Validasi data tanpa is_unique
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_divisi' => [
                'label' => 'Nama divisi',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama divisi wajib diisi',
                    'min_length' => 'Nama divisi minimal 3 karakter',
                    'max_length' => 'Nama divisi maksimal 100 karakter'
                ]
            ],
            'kode_divisi' => [
                'label' => 'Kode divisi',
                'rules' => 'required|alpha_numeric|min_length[2]|max_length[20]',
                'errors' => [
                    'required' => 'Kode divisi wajib diisi',
                    'alpha_numeric' => 'Kode divisi hanya boleh berisi huruf dan angka',
                    'min_length' => 'Kode divisi minimal 2 karakter',
                    'max_length' => 'Kode divisi maksimal 20 karakter'
                ]
            ],
            'kepala_divisi' => 'permit_empty|max_length[100]',
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
        $updated = $this->divisiModel->update($id, $data);

        if (!$updated) {
            log_message('error', 'Failed to update divisi ID ' . $id . ': ' . json_encode($this->divisiModel->errors()));

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate divisi. Silakan coba lagi.',
                'errors'  => $this->divisiModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Divisi berhasil diperbarui',
            'redirect' => base_url('divisi')
        ]);
    }

    // GET /divisi/detail/{id}
    public function detail($id)
    {
        $divisi = $this->divisiModel->getDivisiWithUserCount($id);
        if (!$divisi) {
            return redirect()->to(base_url('divisi'))->with('error', 'Divisi tidak ditemukan');
        }

        return view('admin/divisi/detail', ['divisi' => $divisi]);
    }

    // DELETE /divisi/delete/{id}
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $divisi = $this->divisiModel->find($id);
        if (!$divisi) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Divisi tidak ditemukan'
            ]);
        }

        if (!$this->divisiModel->canDelete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Divisi tidak dapat dihapus karena masih digunakan oleh user'
            ]);
        }

        if (!$this->divisiModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus divisi'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Divisi berhasil dihapus'
        ]);
    }

    // POST /divisi/toggle-status/{id}
    public function toggleStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $updated = $this->divisiModel->toggleStatus($id);
        if (!$updated) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Divisi tidak ditemukan atau gagal mengubah status'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status divisi berhasil diubah'
        ]);
    }
}
