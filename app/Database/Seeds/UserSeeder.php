<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash('password123', PASSWORD_DEFAULT);

        // Daftar bank untuk random assignment
        $banks = ['Bank Muamalat', 'Bank BCA', 'Bank BNI', 'Bank BRI', 'Bank Mandiri', 'Bank BSI'];

        $data = [
            // 1. Super Admin
            [
                'id_role' => 1, // admin
                'id_divisi' => 1, // IT
                'nik' => 'ADM001',
                'nama_lengkap' => 'Administrator System',
                'email' => 'admin@muamalatbank.com',
                'no_hp' => '081234567890',
                'password' => $password,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-15',
                'alamat' => 'Jakarta Selatan',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '3210001001',
                'nama_bank' => 'Bank Muamalat',
                'atas_nama' => 'Administrator System',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // 2. HR Staff
            [
                'id_role' => 2, // hr
                'id_divisi' => 2, // HC
                'nik' => 'HR001',
                'nama_lengkap' => 'Siti Rahayu',
                'email' => 'hr@muamalatbank.com',
                'no_hp' => '081234567891',
                'password' => $password,
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1988-05-20',
                'alamat' => 'Jakarta Pusat',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '3210002001',
                'nama_bank' => 'Bank Muamalat',
                'atas_nama' => 'Siti Rahayu',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // 3. Finance Staff
            [
                'id_role' => 3, // finance
                'id_divisi' => 3, // Finance
                'nik' => 'FIN001',
                'nama_lengkap' => 'Budi Hartono',
                'email' => 'finance@muamalatbank.com',
                'no_hp' => '081234567892',
                'password' => $password,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1985-08-10',
                'alamat' => 'Jakarta Barat',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '3210003001',
                'nama_bank' => 'Bank Muamalat',
                'atas_nama' => 'Budi Hartono',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // 4-8. Mentors (5 mentors untuk ~35 interns = 7 intern per mentor)
            [
                'id_role' => 4, // mentor
                'id_divisi' => 1, // IT
                'nik' => 'MEN001',
                'nama_lengkap' => 'Agus Prasetyo',
                'email' => 'mentor.it@muamalatbank.com',
                'no_hp' => '081234567893',
                'password' => $password,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1987-03-15',
                'alamat' => 'Tangerang Selatan',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '3210004001',
                'nama_bank' => 'Bank Muamalat',
                'atas_nama' => 'Agus Prasetyo',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id_role' => 4,
                'id_divisi' => 3, // Finance
                'nik' => 'MEN002',
                'nama_lengkap' => 'Rina Wijayanti',
                'email' => 'mentor.finance@muamalatbank.com',
                'no_hp' => '081234567894',
                'password' => $password,
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1989-07-22',
                'alamat' => 'Jakarta Timur',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '3210004002',
                'nama_bank' => 'Bank Muamalat',
                'atas_nama' => 'Rina Wijayanti',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id_role' => 4,
                'id_divisi' => 4, // Marketing
                'nik' => 'MEN003',
                'nama_lengkap' => 'Hendra Kusuma',
                'email' => 'mentor.marketing@muamalatbank.com',
                'no_hp' => '081234567895',
                'password' => $password,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1986-11-05',
                'alamat' => 'Depok',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '3210004003',
                'nama_bank' => 'Bank Muamalat',
                'atas_nama' => 'Hendra Kusuma',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id_role' => 4,
                'id_divisi' => 5, // Operations
                'nik' => 'MEN004',
                'nama_lengkap' => 'Dewi Lestari',
                'email' => 'mentor.ops@muamalatbank.com',
                'no_hp' => '081234567896',
                'password' => $password,
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1990-04-18',
                'alamat' => 'Bekasi',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '3210004004',
                'nama_bank' => 'Bank Muamalat',
                'atas_nama' => 'Dewi Lestari',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id_role' => 4,
                'id_divisi' => 8, // Wealth Management
                'nik' => 'MEN005',
                'nama_lengkap' => 'Fajar Nugroho',
                'email' => 'mentor.wm@muamalatbank.com',
                'no_hp' => '081234567897',
                'password' => $password,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1988-09-30',
                'alamat' => 'Jakarta Selatan',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '3210004005',
                'nama_bank' => 'Bank Muamalat',
                'atas_nama' => 'Fajar Nugroho',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert staff first
        $this->db->table('users')->insertBatch($data);

        // Now insert 35 interns
        $interns = [];
        $namaDepan = [
            'Ahmad',
            'Budi',
            'Candra',
            'Dani',
            'Eko',
            'Faisal',
            'Gilang',
            'Hadi',
            'Indra',
            'Joko',
            'Siti',
            'Rina',
            'Dewi',
            'Fitri',
            'Ani',
            'Lina',
            'Maya',
            'Nur',
            'Putri',
            'Ratih'
        ];
        $namaBelakang = [
            'Santoso',
            'Wijaya',
            'Kusuma',
            'Pratama',
            'Nugroho',
            'Lestari',
            'Purnama',
            'Utama',
            'Permana',
            'Saputra',
            'Wati',
            'Sari'
        ];

        $divisiIds = [1, 1, 1, 1, 1, 1, 1, 1, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 8, 8, 8, 8, 8, 8, 8]; // Distribusi ke divisi
        $mentorIds = [4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 8, 8, 8, 8, 8, 8, 8]; // Mapping ke mentor

        for ($i = 1; $i <= 35; $i++) {
            $nama = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
            $nikNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $bankName = $banks[array_rand($banks)];

            $interns[] = [
                'id_role' => 5, // intern
                'id_divisi' => $divisiIds[$i - 1],
                'nik' => 'INT' . $nikNumber,
                'nama_lengkap' => $nama,
                'email' => 'intern' . $nikNumber . '@muamalatbank.com',
                'no_hp' => '0812345678' . str_pad($i + 10, 2, '0', STR_PAD_LEFT),
                'password' => $password,
                'jenis_kelamin' => ($i % 2 == 0) ? 'P' : 'L',
                'tanggal_lahir' => date('Y-m-d', strtotime('-22 years -' . rand(0, 365) . ' days')),
                'alamat' => 'Jakarta Area',
                'foto' => 'default-avatar.png',
                'nomor_rekening' => '80' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'nama_bank' => $bankName,
                'atas_nama' => $nama,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $this->db->table('users')->insertBatch($interns);

        echo "✅ " . (8 + 35) . " Users berhasil di-seed (8 staff + 35 interns)!\n";
        echo "📝 Default password untuk semua user: password123\n";
    }
}
