<?php

namespace App\Controllers;

use App\Models\KpiIndicatorModel;

class KpiIndicatorController extends BaseController
{
    protected $indicatorModel;

    public function __construct()
    {
        $this->indicatorModel = new KpiIndicatorModel();
        helper(['form']);
    }

    /**
     * List all KPI indicators
     */
    public function index()
    {
        $indicators = $this->indicatorModel
            ->orderBy('kategori', 'ASC')
            ->orderBy('bobot', 'DESC')
            ->findAll();

        $statistics = $this->indicatorModel->getStatistics();
        $validation = $this->indicatorModel->validateTotalBobot();

        $data = [
            'title' => 'KPI Indicators Management',
            'indicators' => $indicators,
            'statistics' => $statistics,
            'validation' => $validation
        ];

        return view('admin/kpi/indicators/index', $data);
    }

    /**
     * Create page
     */
    public function create()
    {
        $validation = $this->indicatorModel->validateTotalBobot();

        $data = [
            'title' => 'Tambah KPI Indicator',
            'validation' => $validation
        ];

        return view('admin/kpi/indicators/create', $data);
    }

    /**
     * Store new indicator
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $rules = [
            'nama_indicator' => 'required|min_length[5]|max_length[100]',
            'kategori' => 'required|in_list[kehadiran,aktivitas,project]',
            'bobot' => 'required|decimal|greater_than[0]',
            'deskripsi' => 'required|min_length[10]',
            'is_auto_calculate' => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Validate total bobot
        $currentBobot = $this->indicatorModel->validateTotalBobot();
        $newBobot = $this->request->getPost('bobot');

        if (($currentBobot['total'] + $newBobot) > 100) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Total bobot tidak boleh melebihi 100%',
                'errors' => [
                    'bobot' => "Sisa bobot tersedia: {$currentBobot['remaining']}%"
                ]
            ]);
        }

        $data = [
            'nama_indicator' => $this->request->getPost('nama_indicator'),
            'kategori' => $this->request->getPost('kategori'),
            'bobot' => $this->request->getPost('bobot'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'formula' => $this->request->getPost('formula'),
            'is_auto_calculate' => $this->request->getPost('is_auto_calculate'),
            'is_active' => 1
        ];

        if ($this->indicatorModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Indicator berhasil ditambahkan'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menyimpan indicator',
            'errors' => $this->indicatorModel->errors()
        ]);
    }

    /**
     * Edit page
     */
    public function edit($id)
    {
        $indicator = $this->indicatorModel->find($id);

        if (!$indicator) {
            return redirect()->to(base_url('admin/kpi/indicators'))
                ->with('error', 'Indicator tidak ditemukan');
        }

        $validation = $this->indicatorModel->validateTotalBobot($id);

        $data = [
            'title' => 'Edit KPI Indicator',
            'indicator' => $indicator,
            'validation' => $validation
        ];

        return view('admin/kpi/indicators/edit', $data);
    }

    /**
     * Update indicator
     */
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $indicator = $this->indicatorModel->find($id);

        if (!$indicator) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Indicator tidak ditemukan'
            ]);
        }

        $rules = [
            'nama_indicator' => 'required|min_length[5]|max_length[100]',
            'kategori' => 'required|in_list[kehadiran,aktivitas,project]',
            'bobot' => 'required|decimal|greater_than[0]',
            'deskripsi' => 'required|min_length[10]',
            'is_auto_calculate' => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Validate total bobot (exclude current indicator)
        $currentBobot = $this->indicatorModel->validateTotalBobot($id);
        $newBobot = $this->request->getPost('bobot');

        if (($currentBobot['total'] + $newBobot) > 100) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Total bobot tidak boleh melebihi 100%',
                'errors' => [
                    'bobot' => "Sisa bobot tersedia: {$currentBobot['remaining']}%"
                ]
            ]);
        }

        $data = [
            'nama_indicator' => $this->request->getPost('nama_indicator'),
            'kategori' => $this->request->getPost('kategori'),
            'bobot' => $this->request->getPost('bobot'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'formula' => $this->request->getPost('formula'),
            'is_auto_calculate' => $this->request->getPost('is_auto_calculate')
        ];

        if ($this->indicatorModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Indicator berhasil diupdate'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengupdate indicator',
            'errors' => $this->indicatorModel->errors()
        ]);
    }

    /**
     * Toggle status
     */
    public function toggleStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $indicator = $this->indicatorModel->find($id);

        if (!$indicator) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Indicator tidak ditemukan'
            ]);
        }

        if ($this->indicatorModel->toggleStatus($id)) {
            $newStatus = $indicator['is_active'] ? 'nonaktif' : 'aktif';

            return $this->response->setJSON([
                'success' => true,
                'message' => "Indicator berhasil diubah menjadi {$newStatus}"
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengubah status'
        ]);
    }

    /**
     * Delete indicator
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $indicator = $this->indicatorModel->find($id);

        if (!$indicator) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Indicator tidak ditemukan'
            ]);
        }

        // Check if indicator is being used
        $db = \Config\Database::connect();
        $usageCount = $db->table('kpi_assessments')
            ->where('id_indicator', $id)
            ->countAllResults();

        if ($usageCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Indicator tidak dapat dihapus karena sudah digunakan dalam assessment'
            ]);
        }

        if ($this->indicatorModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Indicator berhasil dihapus'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus indicator'
        ]);
    }
}
