<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // 35 interns × hari kerja (Sep 2025 - Feb 2026) = ~4000+ records
        // Process in batches untuk avoid timeout

        $internIds = range(9, 43); // User ID 9-43 (35 interns)
        $startDate = new \DateTime('2025-09-01');
        $endDate = new \DateTime('2026-02-22'); // Sampai hari ini untuk demo

        $batchSize = 500;
        $attendances = [];
        $totalRecords = 0;

        foreach ($internIds as $userId) {
            $currentDate = clone $startDate;

            while ($currentDate <= $endDate) {
                // Skip weekends
                if ($currentDate->format('N') >= 6) {
                    $currentDate->modify('+1 day');
                    continue;
                }

                // Simulasi pola kehadiran realistis
                $rand = rand(1, 100);

                if ($rand <= 85) {
                    // 85% hadir tepat waktu
                    $jamMasuk = '08:' . str_pad(rand(0, 14), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
                    $jamKeluar = '17:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
                    $status = 'hadir';
                } elseif ($rand <= 92) {
                    // 7% terlambat (> 15 menit)
                    $jamMasuk = '08:' . str_pad(rand(16, 45), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
                    $jamKeluar = '17:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
                    $status = 'terlambat';
                } elseif ($rand <= 97) {
                    // 5% izin/sakit
                    $jamMasuk = null;
                    $jamKeluar = null;
                    $status = (rand(0, 1) == 0) ? 'izin' : 'sakit';
                } else {
                    // 3% alpha
                    $jamMasuk = null;
                    $jamKeluar = null;
                    $status = 'alpha';
                }

                $attendances[] = [
                    'id_user' => $userId,
                    'tanggal' => $currentDate->format('Y-m-d'),
                    'jam_masuk' => $jamMasuk,
                    'jam_keluar' => $jamKeluar,
                    'latitude_masuk' => ($status == 'hadir' || $status == 'terlambat') ? -6.2088 + (rand(-50, 50) / 100000) : null,
                    'longitude_masuk' => ($status == 'hadir' || $status == 'terlambat') ? 106.8456 + (rand(-50, 50) / 100000) : null,
                    'distance_masuk' => ($status == 'hadir' || $status == 'terlambat') ? rand(10, 95) : null,
                    'foto_masuk' => ($status == 'hadir' || $status == 'terlambat') ? 'uploads/attendance/' . $userId . '_' . $currentDate->format('Ymd') . '_in.jpg' : null,
                    'latitude_keluar' => ($status == 'hadir' || $status == 'terlambat') ? -6.2088 + (rand(-50, 50) / 100000) : null,
                    'longitude_keluar' => ($status == 'hadir' || $status == 'terlambat') ? 106.8456 + (rand(-50, 50) / 100000) : null,
                    'distance_keluar' => ($status == 'hadir' || $status == 'terlambat') ? rand(10, 95) : null,
                    'foto_keluar' => ($status == 'hadir' || $status == 'terlambat') ? 'uploads/attendance/' . $userId . '_' . $currentDate->format('Ymd') . '_out.jpg' : null,
                    'status' => $status,
                    'keterangan' => ($status == 'izin' || $status == 'sakit') ? 'Keperluan mendadak' : null,
                    'is_manual' => 0,
                    'created_at' => $currentDate->format('Y-m-d H:i:s'),
                    'updated_at' => $currentDate->format('Y-m-d H:i:s')
                ];

                $totalRecords++;

                // Insert per batch
                if (count($attendances) >= $batchSize) {
                    $this->db->table('attendances')->insertBatch($attendances);
                    echo "  ✓ Inserted " . count($attendances) . " records (Total: $totalRecords)\n";
                    $attendances = []; // Reset
                }

                $currentDate->modify('+1 day');
            }
        }

        // Insert remaining records
        if (!empty($attendances)) {
            $this->db->table('attendances')->insertBatch($attendances);
            echo "  ✓ Inserted " . count($attendances) . " records (Total: $totalRecords)\n";
        }

        echo "\n✅ Total $totalRecords attendance records berhasil di-seed!\n";
        echo "📊 Distribusi: ~85% hadir, ~7% terlambat, ~5% izin/sakit, ~3% alpha\n";
    }
}
