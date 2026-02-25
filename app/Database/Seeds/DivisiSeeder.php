<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_divisi' => 'Information Technology',
                'kode_divisi' => 'IT',
                'kepala_divisi' => 'Budi Santoso, S.Kom',
                'deskripsi' => 'Mengelola sistem informasi, infrastruktur IT, dan digital banking',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_divisi' => 'Human Capital',
                'kode_divisi' => 'HC',
                'kepala_divisi' => 'Siti Aminah, S.Psi, M.M',
                'deskripsi' => 'Mengelola SDM, recruitment, training & development',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_divisi' => 'Finance & Accounting',
                'kode_divisi' => 'FIN',
                'kepala_divisi' => 'Ahmad Wijaya, S.E, Ak',
                'deskripsi' => 'Mengelola keuangan, akuntansi, dan pelaporan keuangan',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_divisi' => 'Marketing',
                'kode_divisi' => 'MKT',
                'kepala_divisi' => 'Rina Kusuma, S.E',
                'deskripsi' => 'Mengelola pemasaran produk dan layanan bank',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_divisi' => 'Operations',
                'kode_divisi' => 'OPS',
                'kepala_divisi' => 'Hendra Gunawan, S.E',
                'deskripsi' => 'Mengelola operasional harian bank',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_divisi' => 'Risk Management',
                'kode_divisi' => 'RISK',
                'kepala_divisi' => 'Dr. Fajar Nugroho, M.M',
                'deskripsi' => 'Mengelola risiko operasional dan kepatuhan',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_divisi' => 'Legal & Compliance',
                'kode_divisi' => 'LEG',
                'kepala_divisi' => 'Dewi Lestari, S.H, M.H',
                'deskripsi' => 'Mengelola aspek hukum dan kepatuhan regulasi',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_divisi' => 'Wealth Management',
                'kode_divisi' => 'WM',
                'kepala_divisi' => 'Muhammad Yusuf Bayuaji, S.E, MBA',
                'deskripsi' => 'Mengelola layanan wealth management dan investasi',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('divisi')->insertBatch($data);

        echo "✅ 8 Divisi berhasil di-seed!\n";
    }
}
