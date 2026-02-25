<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // General Settings
            [
                'setting_key' => 'app_name',
                'setting_value' => 'Sistem Manajemen Pemagang BMI',
                'setting_type' => 'string',
                'category' => 'general',
                'description' => 'Nama aplikasi',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'app_version',
                'setting_value' => '2.0.0',
                'setting_type' => 'string',
                'category' => 'general',
                'description' => 'Versi aplikasi',
                'is_editable' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'company_name',
                'setting_value' => 'Bank Muamalat Indonesia',
                'setting_type' => 'string',
                'category' => 'general',
                'description' => 'Nama perusahaan',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'company_address',
                'setting_value' => 'Gedung Arthaloka, Jl. Jend. Sudirman Kav. 2, Jakarta Pusat',
                'setting_type' => 'string',
                'category' => 'general',
                'description' => 'Alamat kantor pusat',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Attendance Settings
            [
                'setting_key' => 'office_latitude',
                'setting_value' => '-6.2088',
                'setting_type' => 'string',
                'category' => 'attendance',
                'description' => 'Koordinat latitude kantor pusat',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'office_longitude',
                'setting_value' => '106.8456',
                'setting_type' => 'string',
                'category' => 'attendance',
                'description' => 'Koordinat longitude kantor pusat',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'attendance_radius',
                'setting_value' => '100',
                'setting_type' => 'number',
                'category' => 'attendance',
                'description' => 'Radius maksimal untuk absensi (meter)',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'work_start_time',
                'setting_value' => '08:00:00',
                'setting_type' => 'string',
                'category' => 'attendance',
                'description' => 'Jam masuk kerja standar',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'work_end_time',
                'setting_value' => '17:00:00',
                'setting_type' => 'string',
                'category' => 'attendance',
                'description' => 'Jam pulang kerja standar',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'late_tolerance_minutes',
                'setting_value' => '15',
                'setting_type' => 'number',
                'category' => 'attendance',
                'description' => 'Toleransi keterlambatan (menit)',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Allowance Settings
            [
                'setting_key' => 'allowance_rate_per_day',
                'setting_value' => '100000',
                'setting_type' => 'number',
                'category' => 'allowance',
                'description' => 'Nominal uang saku per hari (Rp)',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'allowance_calculation_date',
                'setting_value' => '15',
                'setting_type' => 'number',
                'category' => 'allowance',
                'description' => 'Tanggal cut-off perhitungan uang saku',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'allowance_payment_date',
                'setting_value' => '25',
                'setting_type' => 'number',
                'category' => 'allowance',
                'description' => 'Tanggal pembayaran uang saku',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // KPI Settings
            [
                'setting_key' => 'kpi_attendance_weight',
                'setting_value' => '30',
                'setting_type' => 'number',
                'category' => 'kpi',
                'description' => 'Bobot KPI Kehadiran (%)',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'kpi_activity_weight',
                'setting_value' => '35',
                'setting_type' => 'number',
                'category' => 'kpi',
                'description' => 'Bobot KPI Aktivitas (%)',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'kpi_project_weight',
                'setting_value' => '35',
                'setting_type' => 'number',
                'category' => 'kpi',
                'description' => 'Bobot KPI Project (%)',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'intern_period_months',
                'setting_value' => '6',
                'setting_type' => 'number',
                'category' => 'general',
                'description' => 'Durasi periode magang (bulan)',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Email Settings
            [
                'setting_key' => 'email_from',
                'setting_value' => 'noreply@muamalatbank.com',
                'setting_type' => 'string',
                'category' => 'email',
                'description' => 'Email pengirim sistem',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_key' => 'email_from_name',
                'setting_value' => 'BMI Magang System',
                'setting_type' => 'string',
                'category' => 'email',
                'description' => 'Nama pengirim email',
                'is_editable' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('settings')->insertBatch($data);

        echo "✅ 19 Settings berhasil di-seed!\n";
    }
}
