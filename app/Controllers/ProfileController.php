<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    // ========================================
    // SHOW PROFILE PAGE
    // ========================================
    public function index()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get user data with role and divisi info
        $user = $this->db->table('users u')
            ->select('u.*, r.nama_role, r.kode_role, d.nama_divisi')
            ->join('roles r', 'r.id_role = u.id_role', 'left')
            ->join('divisi d', 'd.id_divisi = u.id_divisi', 'left')
            ->where('u.id_user', $userId)
            ->get()
            ->getRowArray();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan');
        }

        $data = [
            'title' => 'Profil Saya',
            'user' => $user
        ];

        return view('profile/index', $data);
    }

    // ========================================
    // UPDATE PROFILE
    // ========================================
    public function update()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama_lengkap' => [
                'rules' => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi',
                    'min_length' => 'Nama lengkap minimal 3 karakter',
                    'max_length' => 'Nama lengkap maksimal 150 karakter'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|max_length[100]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter'
                ]
            ],
            'no_hp' => [
                'rules' => 'permit_empty|min_length[10]|max_length[20]|regex_match[/^[0-9]+$/]',
                'errors' => [
                    'min_length' => 'Nomor HP minimal 10 digit',
                    'max_length' => 'Nomor HP maksimal 20 digit',
                    'regex_match' => 'Nomor HP hanya boleh berisi angka'
                ]
            ],
            'jenis_kelamin' => [
                'rules' => 'permit_empty|in_list[L,P]',
                'errors' => [
                    'in_list' => 'Jenis kelamin harus L atau P'
                ]
            ],
            'tanggal_lahir' => [
                'rules' => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Format tanggal lahir tidak valid'
                ]
            ],
            'alamat' => [
                'rules' => 'permit_empty|max_length[500]',
                'errors' => [
                    'max_length' => 'Alamat maksimal 500 karakter'
                ]
            ],
            'foto' => [
                'rules' => 'permit_empty|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran foto maksimal 2MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'Format foto harus JPG, JPEG, atau PNG'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validation->getErrors()
            ]);
        }

        // Get current user data
        $currentUser = $this->userModel->find($userId);

        // Check if email already exists (except current user)
        $emailExists = $this->userModel
            ->where('email', $this->request->getPost('email'))
            ->where('id_user !=', $userId)
            ->first();

        if ($emailExists) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email sudah digunakan oleh user lain'
            ]);
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp') ?: null,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin') ?: null,
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat' => $this->request->getPost('alamat') ?: null,
            'nomor_rekening' => $this->request->getPost('nomor_rekening') ?: null,
            'nama_bank' => $this->request->getPost('nama_bank') ?: null,
            'atas_nama' => $this->request->getPost('atas_nama') ?: null
        ];

        // Handle foto upload
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Delete old foto if exists
            if ($currentUser['foto'] && $currentUser['foto'] !== 'default-avatar.png') {
                $oldFotoPath = WRITEPATH . 'uploads/users/' . $currentUser['foto'];
                if (file_exists($oldFotoPath)) {
                    @unlink($oldFotoPath);
                }
            }

            // Generate unique filename
            $newName = 'user_' . $userId . '_' . time() . '.' . $foto->getExtension();

            // Move to writable/uploads/users/
            $uploadPath = WRITEPATH . 'uploads/users/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $foto->move($uploadPath, $newName);
            $data['foto'] = $newName;
        }

        try {
            $this->userModel->update($userId, $data);

            // Update session data
            session()->set([
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'foto' => $data['foto'] ?? $currentUser['foto']
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'foto_url' => isset($data['foto']) ? base_url('profile/photo/' . $data['foto']) : null
            ]);
        } catch (\Exception $e) {
            // Log error for debugging
            log_message('error', 'Profile Update Error: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ]);
        }
    }

    // ========================================
    // CHANGE PASSWORD
    // ========================================
    public function changePassword()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }

        $validation = \Config\Services::validation();

        $rules = [
            'current_password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password lama harus diisi'
                ]
            ],
            'new_password' => [
                'rules' => 'required|min_length[6]|max_length[255]',
                'errors' => [
                    'required' => 'Password baru harus diisi',
                    'min_length' => 'Password baru minimal 6 karakter',
                    'max_length' => 'Password baru maksimal 255 karakter'
                ]
            ],
            'confirm_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Konfirmasi password tidak cocok'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validation->getErrors()
            ]);
        }

        // Get current user
        $user = $this->userModel->find($userId);

        // Verify current password
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password lama tidak sesuai'
            ]);
        }

        // Check if new password is same as old password
        if (password_verify($this->request->getPost('new_password'), $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama'
            ]);
        }

        $newPasswordHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);

        try {
            $this->userModel->update($userId, [
                'password' => $newPasswordHash
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengubah password: ' . $e->getMessage()
            ]);
        }
    }

    // ========================================
    // SERVE PROFILE PHOTO
    // ========================================
    public function photo($filename)
    {
        $filePath = WRITEPATH . 'uploads/users/' . $filename;

        if (!file_exists($filePath)) {
            // Return default avatar
            return redirect()->to(base_url('assets/img/avatars/1.png'));
        }

        $mimeType = mime_content_type($filePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Length', filesize($filePath))
            ->setBody(file_get_contents($filePath));
    }
}
