<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AllowancePeriodSeeder extends Seeder
{
    public function run()
    {
        echo "   💰 Seeding Allowance Periods...\n";

        // Count active interns
        $internCount = $this->db->table('interns')
            ->where('status_magang', 'active')
            ->countAllResults();

        // Finance user (id=3) calculates & approves, Admin (id=1) can also approve
        $financeId = 3;
        $adminId = 1;

        // Create 6 monthly periods: Sep 2025 to Feb 2026
        // Period: 15th to 15th, payment on 25th of next month
        $periods = [
            [
                'nama_periode'       => 'Periode 15 Sep - 15 Okt 2025',
                'tanggal_mulai'      => '2025-09-15',
                'tanggal_selesai'    => '2025-10-15',
                'tanggal_pembayaran' => '2025-10-25',
                'status'             => 'paid',
            ],
            [
                'nama_periode'       => 'Periode 15 Okt - 15 Nov 2025',
                'tanggal_mulai'      => '2025-10-15',
                'tanggal_selesai'    => '2025-11-15',
                'tanggal_pembayaran' => '2025-11-25',
                'status'             => 'paid',
            ],
            [
                'nama_periode'       => 'Periode 15 Nov - 15 Des 2025',
                'tanggal_mulai'      => '2025-11-15',
                'tanggal_selesai'    => '2025-12-15',
                'tanggal_pembayaran' => '2025-12-25',
                'status'             => 'paid',
            ],
            [
                'nama_periode'       => 'Periode 15 Des 2025 - 15 Jan 2026',
                'tanggal_mulai'      => '2025-12-15',
                'tanggal_selesai'    => '2026-01-15',
                'tanggal_pembayaran' => '2026-01-25',
                'status'             => 'approved',
            ],
            [
                'nama_periode'       => 'Periode 15 Jan - 15 Feb 2026',
                'tanggal_mulai'      => '2026-01-15',
                'tanggal_selesai'    => '2026-02-15',
                'tanggal_pembayaran' => '2026-02-25',
                'status'             => 'calculated',
            ],
            [
                'nama_periode'       => 'Periode 15 Feb - 15 Mar 2026',
                'tanggal_mulai'      => '2026-02-15',
                'tanggal_selesai'    => '2026-03-15',
                'tanggal_pembayaran' => '2026-03-25',
                'status'             => 'draft',
            ],
        ];

        $ratePerDay = 100000; // Rp 100.000

        $data = [];
        foreach ($periods as $period) {
            // Estimate working days in period (~22 days)
            $workingDays = $this->countWorkingDays($period['tanggal_mulai'], $period['tanggal_selesai']);
            // Estimate total nominal (all interns * avg attendance * rate)
            $avgAttendance = round($workingDays * 0.88); // ~88% average attendance
            $totalNominal = $internCount * $avgAttendance * $ratePerDay;

            $calculatedAt = null;
            $calculatedBy = null;
            $approvedAt = null;
            $approvedBy = null;
            $paidAt = null;
            $paidBy = null;

            if (in_array($period['status'], ['calculated', 'approved', 'paid'])) {
                $calculatedAt = date('Y-m-d H:i:s', strtotime($period['tanggal_selesai'] . ' +2 days'));
                $calculatedBy = $financeId;
            }
            if (in_array($period['status'], ['approved', 'paid'])) {
                $approvedAt = date('Y-m-d H:i:s', strtotime($period['tanggal_selesai'] . ' +4 days'));
                $approvedBy = $adminId;
            }
            if ($period['status'] === 'paid') {
                $paidAt = date('Y-m-d H:i:s', strtotime($period['tanggal_pembayaran']));
                $paidBy = $financeId;
            }

            $data[] = [
                'nama_periode'       => $period['nama_periode'],
                'tanggal_mulai'      => $period['tanggal_mulai'],
                'tanggal_selesai'    => $period['tanggal_selesai'],
                'tanggal_pembayaran' => $period['tanggal_pembayaran'],
                'status'             => $period['status'],
                'total_pemagang'     => ($period['status'] !== 'draft') ? $internCount : 0,
                'total_nominal'      => ($period['status'] !== 'draft') ? $totalNominal : 0,
                'calculated_at'      => $calculatedAt,
                'calculated_by'      => $calculatedBy,
                'approved_at'        => $approvedAt,
                'approved_by'        => $approvedBy,
                'paid_at'            => $paidAt,
                'paid_by'            => $paidBy,
                'created_at'         => date('Y-m-d H:i:s', strtotime($period['tanggal_mulai'])),
                'updated_at'         => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('allowance_periods')->insertBatch($data);

        echo "   ✅ Created " . count($data) . " allowance periods (Sep 2025 - Mar 2026)\n";
        echo "      Paid: 3, Approved: 1, Calculated: 1, Draft: 1\n";
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
