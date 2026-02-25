<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ========================================
    // SHOW LOGIN PAGE
    // ========================================
    public function login()
    {
        return view('auth/login');
    }

    // ========================================
    // PROCESS LOGIN
    // ========================================
    public function processLogin()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Get user with role and divisi info
        $user = $this->db->table('users as u')
            ->select('u.*, r.nama_role, r.kode_role, r.permissions, d.nama_divisi, d.kode_divisi')
            ->join('roles as r', 'u.id_role = r.id_role', 'left')
            ->join('divisi as d', 'u.id_divisi = d.id_divisi', 'left')
            ->where('u.email', $email)
            ->where('u.status', 'active')
            ->get()
            ->getRow();

        // Check if user exists
        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email tidak terdaftar atau akun tidak aktif');
        }

        // Verify password
        if (!password_verify($password, $user->password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Password salah');
        }

        // Check if user is intern, get intern data
        $internData = null;
        if ($user->kode_role === 'intern') {
            $internData = $this->db->table('interns as i')
                ->select('i.*, m.nama_lengkap as mentor_name, m.email as mentor_email')
                ->join('users as m', 'i.id_mentor = m.id_user', 'left')
                ->where('i.id_user', $user->id_user)
                ->get()
                ->getRow();
        }

        // Set session data
        $sessionData = [
            'logged_in' => true,
            'user_id' => $user->id_user,
            'nik' => $user->nik,
            'nama_lengkap' => $user->nama_lengkap,
            'email' => $user->email,
            'foto' => $user->foto ?? 'default-avatar.png',
            'id_role' => $user->id_role,
            'role_name' => $user->nama_role,
            'role_code' => $user->kode_role,
            'permissions' => json_decode($user->permissions, true) ?? [],
            'id_divisi' => $user->id_divisi,
            'nama_divisi' => $user->nama_divisi ?? '-',
            'kode_divisi' => $user->kode_divisi ?? '-',
        ];

        // Add intern-specific data if applicable
        if ($internData) {
            $sessionData['is_intern'] = true;
            $sessionData['id_intern'] = $internData->id_intern;
            $sessionData['id_mentor'] = $internData->id_mentor;
            $sessionData['mentor_name'] = $internData->mentor_name;
            $sessionData['periode_mulai'] = $internData->periode_mulai;
            $sessionData['periode_selesai'] = $internData->periode_selesai;
            $sessionData['universitas'] = $internData->universitas;
            $sessionData['jurusan'] = $internData->jurusan;
        }

        session()->set($sessionData);

        // Update last login
        $this->db->table('users')
            ->where('id_user', $user->id_user)
            ->update(['last_login' => date('Y-m-d H:i:s')]);

        // Log audit
        $this->logAudit($user->id_user, 'login', 'auth', null, 'User logged in successfully');

        // Redirect based on role
        return redirect()->to('/dashboard')
            ->with('success', 'Selamat datang, ' . $user->nama_lengkap . '!');
    }

    // ========================================
    // LOGOUT
    // ========================================
    public function logout()
    {
        $userId = session()->get('user_id');

        // Log audit before destroy session
        if ($userId) {
            $this->logAudit($userId, 'logout', 'auth', null, 'User logged out');
        }

        // Destroy session
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Anda telah berhasil logout');
    }

    // ========================================
    // HELPER: LOG AUDIT
    // ========================================
    private function logAudit($userId, $action, $module, $recordId = null, $description = '')
    {
        $this->db->table('audit_logs')->insert([
            'id_user' => $userId,
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    // ========================================
    // CHECK SESSION (AJAX ENDPOINT)
    // ========================================
    public function checkSession()
    {
        if (session()->has('logged_in')) {
            return $this->response->setJSON([
                'status' => 'active',
                'user' => [
                    'name' => session()->get('nama_lengkap'),
                    'role' => session()->get('role_name')
                ]
            ]);
        }

        return $this->response->setJSON([
            'status' => 'expired'
        ])->setStatusCode(401);
    }
}
