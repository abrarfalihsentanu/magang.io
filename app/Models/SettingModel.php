<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id_setting';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'setting_key',
        'setting_value',
        'setting_type',
        'category',
        'description',
        'is_editable',
        'updated_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_editable' => 'boolean'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false; // Settings hanya punya updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Validation
    protected $validationRules = [
        'setting_key' => 'required|min_length[3]|max_length[100]',
        'setting_value' => 'required',
        'setting_type' => 'required|in_list[string,number,json,boolean,date]',
        'category' => 'required|max_length[50]',
        'description' => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'setting_key' => [
            'required' => 'Setting key wajib diisi',
            'min_length' => 'Setting key minimal 3 karakter',
            'max_length' => 'Setting key maksimal 100 karakter'
        ],
        'setting_value' => [
            'required' => 'Setting value wajib diisi'
        ],
        'setting_type' => [
            'required' => 'Tipe setting wajib dipilih',
            'in_list' => 'Tipe setting tidak valid'
        ],
        'category' => [
            'required' => 'Kategori wajib diisi',
            'max_length' => 'Kategori maksimal 50 karakter'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['checkUniqueKeyInsert'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setUpdatedBy'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Check unique setting_key saat insert
     */
    protected function checkUniqueKeyInsert(array $data): array
    {
        if (isset($data['data']['setting_key'])) {
            $existing = $this->where('setting_key', $data['data']['setting_key'])->first();
            if ($existing) {
                $this->errors = ['setting_key' => 'Setting key sudah digunakan'];
                return $data;
            }
        }
        return $data;
    }

    /**
     * Set updated_by before update
     */
    protected function setUpdatedBy(array $data): array
    {
        if (session()->has('user_id')) {
            $data['data']['updated_by'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Get settings grouped by category
     */
    public function getSettingsGroupedByCategory()
    {
        $settings = $this->orderBy('category', 'ASC')
            ->orderBy('setting_key', 'ASC')
            ->findAll();

        $grouped = [];
        foreach ($settings as $setting) {
            $grouped[$setting['category']][] = $setting;
        }

        return $grouped;
    }

    /**
     * Get setting by key
     */
    public function getByKey($key)
    {
        return $this->where('setting_key', $key)->first();
    }

    /**
     * Get settings by category
     */
    public function getByCategory($category)
    {
        return $this->where('category', $category)
            ->orderBy('setting_key', 'ASC')
            ->findAll();
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        return $this->select('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderBy('category', 'ASC')
            ->findAll();
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $total = $this->countAll();
        $editable = $this->where('is_editable', 1)->countAllResults();
        $locked = $this->where('is_editable', 0)->countAllResults();

        $byCategory = $this->select('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderBy('total', 'DESC')
            ->findAll();

        return [
            'total' => $total,
            'editable' => $editable,
            'locked' => $locked,
            'by_category' => $byCategory
        ];
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate($settings)
    {
        $this->db->transStart();

        foreach ($settings as $key => $value) {
            $this->where('setting_key', $key)->update(['setting_value' => $value]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Check if setting can be deleted
     */
    public function canDelete($id)
    {
        $setting = $this->find($id);
        if (!$setting) {
            return false;
        }

        // Setting yang tidak editable tidak bisa dihapus
        return (bool) $setting['is_editable'];
    }

    /**
     * Format value based on type
     */
    public function formatValue($value, $type)
    {
        switch ($type) {
            case 'number':
                return is_numeric($value) ? number_format($value, 0, ',', '.') : $value;
            case 'boolean':
                return $value ? 'Ya' : 'Tidak';
            case 'json':
                return json_encode($value, JSON_PRETTY_PRINT);
            default:
                return $value;
        }
    }
}
