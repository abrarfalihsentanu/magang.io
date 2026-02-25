<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use CodeIgniter\HTTP\ResponseInterface;

class SettingController extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        helper(['form']);
    }

    // GET /settings
    public function index()
    {
        $data = [
            'settings' => $this->settingModel->getSettingsGroupedByCategory(),
            'statistics' => $this->settingModel->getStatistics(),
            'categories' => $this->settingModel->getCategories()
        ];

        return view('admin/settings/index', $data);
    }

    // GET /settings/create
    public function create()
    {
        $data = [
            'categories' => $this->settingModel->getCategories()
        ];

        return view('admin/settings/create', $data);
    }

    // POST /settings/store
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $data = [
            'setting_key' => strtolower(trim(str_replace(' ', '_', $this->request->getPost('setting_key')))),
            'setting_value' => $this->request->getPost('setting_value'),
            'setting_type' => $this->request->getPost('setting_type'),
            'category' => trim($this->request->getPost('category')),
            'description' => $this->request->getPost('description'),
            'is_editable' => $this->request->getPost('is_editable') ? 1 : 0,
            'updated_by' => session()->get('user_id')
        ];

        if (!$this->settingModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => $this->settingModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Setting berhasil ditambahkan',
            'redirect' => base_url('settings')
        ]);
    }

    // GET /settings/edit/{id}
    public function edit($id)
    {
        $setting = $this->settingModel->find($id);
        if (!$setting) {
            return redirect()->to(base_url('settings'))->with('error', 'Setting tidak ditemukan');
        }

        $data = [
            'setting' => $setting,
            'categories' => $this->settingModel->getCategories()
        ];

        return view('admin/settings/edit', $data);
    }

    // POST /settings/update/{id}
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $setting = $this->settingModel->find($id);
        if (!$setting) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Setting tidak ditemukan'
            ]);
        }

        // Cek apakah setting_key sudah digunakan oleh setting lain (bukan diri sendiri)
        $settingKey = strtolower(trim(str_replace(' ', '_', $this->request->getPost('setting_key'))));
        $existingSetting = $this->settingModel
            ->where('setting_key', $settingKey)
            ->where('id_setting !=', $id)
            ->first();

        if ($existingSetting) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => [
                    'setting_key' => 'Setting key sudah digunakan'
                ],
            ]);
        }

        $data = [
            'setting_key' => $settingKey,
            'setting_value' => $this->request->getPost('setting_value'),
            'setting_type' => $this->request->getPost('setting_type'),
            'category' => trim($this->request->getPost('category')),
            'description' => $this->request->getPost('description'),
            'is_editable' => $this->request->getPost('is_editable') ? 1 : 0,
            'updated_by' => session()->get('user_id')
        ];

        // Validasi manual
        $validation = \Config\Services::validation();
        $validation->setRules([
            'setting_key' => 'required|min_length[3]|max_length[100]',
            'setting_value' => 'required',
            'setting_type' => 'required|in_list[string,number,json,boolean,date]',
            'category' => 'required|max_length[50]'
        ]);

        if (!$validation->run($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => $validation->getErrors(),
            ]);
        }

        // Update data
        if (!$this->settingModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate setting. Silakan coba lagi.',
                'errors'  => $this->settingModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Setting berhasil diperbarui',
            'redirect' => base_url('settings')
        ]);
    }

    // GET /settings/detail/{id}
    public function detail($id)
    {
        $setting = $this->settingModel->find($id);
        if (!$setting) {
            return redirect()->to(base_url('settings'))->with('error', 'Setting tidak ditemukan');
        }

        $data = [
            'setting' => $setting
        ];

        return view('admin/settings/detail', $data);
    }

    // DELETE /settings/delete/{id}
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $setting = $this->settingModel->find($id);
        if (!$setting) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Setting tidak ditemukan'
            ]);
        }

        if (!$this->settingModel->canDelete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Setting ini tidak dapat dihapus karena tidak editable'
            ]);
        }

        if (!$this->settingModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus setting'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Setting berhasil dihapus'
        ]);
    }

    // POST /settings/bulk-update
    public function bulkUpdate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $settings = $this->request->getJSON(true);

        if (empty($settings)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak ada data yang diupdate'
            ]);
        }

        if ($this->settingModel->bulkUpdate($settings)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Settings berhasil diperbarui'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengupdate settings'
        ]);
    }
}
