<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InternSeeder extends Seeder
{
    public function run()
    {
        $periodeStart = date('Y-m-d', strtotime('2025-09-01')); // 1 September 2025
        $periodeEnd = date('Y-m-d', strtotime('2026-02-28')); // 28 Februari 2026 (6 bulan)

        $universitas = [
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'Institut Pertanian Bogor',
            'Universitas Brawijaya',
            'Universitas Padjadjaran',
            'Universitas Diponegoro',
            'Institut Teknologi Sepuluh Nopember',
            'Universitas Bina Sarana Informatika',
            'Universitas Bina Nusantara',
            'Universitas Gunadarma',
            'Universitas Trisakti'
        ];

        $jurusan = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Ilmu Komputer',
            'Manajemen',
            'Akuntansi',
            'Ekonomi',
            'Keuangan',
            'Manajemen Keuangan',
            'Manajemen Pemasaran',
            'Administrasi Bisnis'
        ];

        // Mapping mentor berdasarkan UserSeeder
        // User ID 4-8 adalah mentors (id_user starts from 1)
        $mentorMapping = [
            1 => 4,
            2 => 4,
            3 => 4,
            4 => 4,
            5 => 4,
            6 => 4,
            7 => 4, // 7 interns untuk mentor 1 (IT)
            8 => 5,
            9 => 5,
            10 => 5,
            11 => 5,
            12 => 5,
            13 => 5,
            14 => 5, // 7 interns untuk mentor 2 (Finance)
            15 => 6,
            16 => 6,
            17 => 6,
            18 => 6,
            19 => 6,
            20 => 6,
            21 => 6, // 7 interns untuk mentor 3 (Marketing)
            22 => 7,
            23 => 7,
            24 => 7,
            25 => 7,
            26 => 7,
            27 => 7,
            28 => 7, // 7 interns untuk mentor 4 (Ops)
            29 => 8,
            30 => 8,
            31 => 8,
            32 => 8,
            33 => 8,
            34 => 8,
            35 => 8  // 7 interns untuk mentor 5 (WM)
        ];

        $data = [];
        for ($i = 1; $i <= 35; $i++) {
            // id_user untuk intern dimulai dari 9 (karena 1-8 adalah staff)
            $userId = 8 + $i;

            $data[] = [
                'id_user' => $userId,
                'id_mentor' => $mentorMapping[$i],
                'universitas' => $universitas[array_rand($universitas)],
                'jurusan' => $jurusan[array_rand($jurusan)],
                'periode_mulai' => $periodeStart,
                'periode_selesai' => $periodeEnd,
                'durasi_bulan' => 6,
                'status_magang' => 'active',
                'dokumen_surat_magang' => 'uploads/surat_magang/intern_' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.pdf',
                'catatan' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $this->db->table('interns')->insertBatch($data);

        echo "✅ 35 Interns berhasil di-seed!\n";
        echo "📅 Periode: $periodeStart s/d $periodeEnd (6 bulan)\n";
        echo "👥 Distribusi: 7 interns per mentor\n";
    }
}
