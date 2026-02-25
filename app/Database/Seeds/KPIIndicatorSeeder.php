<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KpiIndicatorSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // ========================================
            // KATEGORI: KEHADIRAN (30%)
            // ========================================
            [
                'nama_indicator' => 'Persentase Kehadiran',
                'kategori' => 'kehadiran',
                'bobot' => 20.00,
                'deskripsi' => 'Persentase kehadiran dari total hari kerja dalam periode',
                'formula' => '(COUNT(status IN [hadir,terlambat]) / total_working_days) × 100',
                'is_auto_calculate' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_indicator' => 'Ketepatan Waktu Masuk',
                'kategori' => 'kehadiran',
                'bobot' => 10.00,
                'deskripsi' => 'Persentase hadir tepat waktu dari total kehadiran',
                'formula' => '(COUNT(status=hadir) / COUNT(status IN [hadir,terlambat])) × 100',
                'is_auto_calculate' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // ========================================
            // KATEGORI: AKTIVITAS (35%)
            // ========================================
            [
                'nama_indicator' => 'Konsistensi Input Logbook',
                'kategori' => 'aktivitas',
                'bobot' => 15.00,
                'deskripsi' => 'Persentase hari dengan aktivitas yang diinput dari total hari kerja',
                'formula' => '(COUNT(DISTINCT tanggal_aktivitas) / total_working_days) × 100',
                'is_auto_calculate' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_indicator' => 'Kualitas Deskripsi Aktivitas',
                'kategori' => 'aktivitas',
                'bobot' => 10.00,
                'deskripsi' => 'Penilaian subjektif mentor terhadap kualitas deskripsi aktivitas (1-5 scale)',
                'formula' => 'Manual assessment by mentor (avg 1-5 → convert to 100 scale)',
                'is_auto_calculate' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_indicator' => 'Approval Rate Aktivitas',
                'kategori' => 'aktivitas',
                'bobot' => 10.00,
                'deskripsi' => 'Persentase aktivitas yang di-approve dari total yang disubmit',
                'formula' => '(COUNT(status=approved) / COUNT(status!=draft)) × 100',
                'is_auto_calculate' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // ========================================
            // KATEGORI: PROJECT (35%)
            // ========================================
            [
                'nama_indicator' => 'Jumlah Project Completed',
                'kategori' => 'project',
                'bobot' => 15.00,
                'deskripsi' => 'Jumlah project dengan progress 100% (max 5 projects = 100%)',
                'formula' => 'COUNT(progress=100) × 20 (max 5 projects = 100%)',
                'is_auto_calculate' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_indicator' => 'Kualitas Hasil Project',
                'kategori' => 'project',
                'bobot' => 10.00,
                'deskripsi' => 'Rata-rata mentor rating untuk project (1-5 scale)',
                'formula' => 'AVG(mentor_rating) × 20 (rating 1-5 → convert to 100 scale)',
                'is_auto_calculate' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_indicator' => 'Inisiatif Project',
                'kategori' => 'project',
                'bobot' => 10.00,
                'deskripsi' => 'Persentase project inisiatif dari total project',
                'formula' => '(COUNT(tipe=inisiatif) / COUNT(*)) × 100',
                'is_auto_calculate' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('kpi_indicators')->insertBatch($data);

        echo "✅ Seeded 8 KPI Indicators successfully!\n";
        echo "   - Kehadiran: 2 indicators (30% total)\n";
        echo "   - Aktivitas: 3 indicators (35% total)\n";
        echo "   - Project: 3 indicators (35% total)\n";
        echo "   Total Bobot: 100%\n";
    }
}
