<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AllowanceSeeder extends Seeder
{
    public function run()
    {
        echo "   💵 Seeding Allowances...\n";

        // Get all allowance periods that have been calculated
        $periods = $this->db->table('allowance_periods')
            ->where('status !=', 'draft')
            ->orderBy('tanggal_mulai', 'ASC')
            ->get()->getResultArray();

        if (empty($periods)) {
            echo "   ⚠️  No calculated allowance periods found. Run AllowancePeriodSeeder first.\n";
            return;
        }

        // Get all active interns with bank info
        $interns = $this->db->table('users')
            ->select('users.id_user, users.nomor_rekening, users.nama_bank, users.atas_nama, users.nama_lengkap')
            ->join('interns', 'interns.id_user = users.id_user')
            ->where('interns.status_magang', 'active')
            ->get()->getResultArray();

        if (empty($interns)) {
            echo "   ⚠️  No active interns found.\n";
            return;
        }

        $ratePerDay = 100000; // Rp 100.000
        $data = [];
        $totalCount = 0;

        foreach ($periods as $period) {
            $periodId = $period['id_period'];
            $periodStatus = $period['status'];
            $workingDays = $this->countWorkingDays($period['tanggal_mulai'], $period['tanggal_selesai']);

            foreach ($interns as $intern) {
                // Simulate attendance-based calculation
                // Most interns attend 85-100% of days
                $attendanceRate = rand(80, 100) / 100;
                $totalHadir = (int) round($workingDays * $attendanceRate);
                $totalAlpha = rand(0, max(0, (int)($workingDays * 0.05)));
                $totalIzin = rand(0, max(0, (int)($workingDays * 0.05)));
                $totalSakit = rand(0, max(0, (int)($workingDays * 0.05)));

                // Ensure total doesn't exceed working days
                $totalAbsent = $totalAlpha + $totalIzin + $totalSakit;
                if ($totalHadir + $totalAbsent > $workingDays) {
                    $totalHadir = $workingDays - $totalAbsent;
                }
                $totalHadir = max(0, $totalHadir);

                $totalUangSaku = $totalHadir * $ratePerDay;

                // Status based on period status
                $statusPembayaran = 'pending';
                $tanggalTransfer = null;
                $buktiTransfer = null;

                if ($periodStatus === 'paid') {
                    $statusPembayaran = 'paid';
                    $tanggalTransfer = $period['tanggal_pembayaran'];
                    $buktiTransfer = 'uploads/bukti_transfer/period_' . $periodId . '_user_' . $intern['id_user'] . '.pdf';
                } elseif ($periodStatus === 'approved') {
                    $statusPembayaran = 'approved';
                }
                // calculated => stays pending

                $data[] = [
                    'id_period'          => $periodId,
                    'id_user'            => $intern['id_user'],
                    'total_hari_kerja'   => $workingDays,
                    'total_hadir'        => $totalHadir,
                    'total_alpha'        => $totalAlpha,
                    'total_izin'         => $totalIzin,
                    'total_sakit'        => $totalSakit,
                    'rate_per_hari'      => $ratePerDay,
                    'total_uang_saku'    => $totalUangSaku,
                    'nomor_rekening'     => $intern['nomor_rekening'],
                    'nama_bank'          => $intern['nama_bank'],
                    'atas_nama'          => $intern['atas_nama'] ?? $intern['nama_lengkap'],
                    'status_pembayaran'  => $statusPembayaran,
                    'tanggal_transfer'   => $tanggalTransfer,
                    'bukti_transfer'     => $buktiTransfer,
                    'catatan'            => null,
                    'created_at'         => date('Y-m-d H:i:s', strtotime($period['tanggal_selesai'] . ' +2 days')),
                    'updated_at'         => date('Y-m-d H:i:s'),
                ];
                $totalCount++;
            }
        }

        if (!empty($data)) {
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                $this->db->table('allowances')->insertBatch($chunk);
            }
        }

        echo "   ✅ Created {$totalCount} allowance records (" . count($interns) . " interns × " . count($periods) . " periods)\n";
    }

    private function countWorkingDays(string $start, string $end): int
    {
        $count = 0;
        $current = strtotime($start);
        $endTs = strtotime($end);
        while ($current <= $endTs) {
            if (date('N', $current) < 6) {
                $count++;
            }
            $current = strtotime('+1 day', $current);
        }
        return $count;
    }
}
