<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateBankInfoSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $banks = ['Bank Muamalat', 'Bank BCA', 'Bank BNI', 'Bank BRI', 'Bank Mandiri', 'Bank BSI'];

        // Update semua user yang belum memiliki nomor rekening
        $users = $db->table('users')
            ->where('nomor_rekening IS NULL')
            ->get()
            ->getResultArray();

        $updated = 0;
        foreach ($users as $user) {
            $bankName = $banks[array_rand($banks)];
            $nomorRekening = '80' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

            $db->table('users')
                ->where('id_user', $user['id_user'])
                ->update([
                    'nomor_rekening' => $nomorRekening,
                    'nama_bank' => $bankName,
                    'atas_nama' => $user['nama_lengkap'],
                ]);

            $updated++;
        }

        echo "✅ {$updated} users berhasil diupdate dengan informasi rekening bank!\n";

        // Update juga data allowances yang sudah ada dengan status pending
        $allowances = $db->table('allowances a')
            ->select('a.id_allowance, a.id_user, u.nomor_rekening, u.nama_bank, u.atas_nama, u.nama_lengkap')
            ->join('users u', 'u.id_user = a.id_user')
            ->where('a.status_pembayaran', 'pending')
            ->where('a.nomor_rekening IS NULL')
            ->get()
            ->getResultArray();

        $updatedAllowances = 0;
        foreach ($allowances as $allowance) {
            $db->table('allowances')
                ->where('id_allowance', $allowance['id_allowance'])
                ->update([
                    'nomor_rekening' => $allowance['nomor_rekening'],
                    'nama_bank' => $allowance['nama_bank'],
                    'atas_nama' => $allowance['atas_nama'] ?? $allowance['nama_lengkap'],
                ]);
            $updatedAllowances++;
        }

        echo "✅ {$updatedAllowances} data allowances berhasil diupdate dengan informasi rekening!\n";
    }
}
