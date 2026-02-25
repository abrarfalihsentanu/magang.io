<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AllowanceSlipSeeder extends Seeder
{
    public function run()
    {
        echo "   🧾 Seeding Allowance Slips...\n";

        // Get all paid allowances
        $allowances = $this->db->table('allowances a')
            ->select('a.id_allowance, a.id_user, a.id_period, ap.nama_periode')
            ->join('allowance_periods ap', 'ap.id_period = a.id_period')
            ->where('a.status_pembayaran', 'paid')
            ->get()->getResultArray();

        if (empty($allowances)) {
            echo "   ⚠️  No paid allowances found. Run AllowanceSeeder first.\n";
            return;
        }

        // Finance user generates slips
        $financeId = 3;

        $data = [];
        $slipCounter = 1;

        foreach ($allowances as $allowance) {
            $nomorSlip = 'SLP/' . date('Y') . '/' . str_pad($slipCounter, 5, '0', STR_PAD_LEFT);

            $data[] = [
                'id_allowance' => $allowance['id_allowance'],
                'nomor_slip'   => $nomorSlip,
                'file_path'    => 'uploads/slips/slip_' . $allowance['id_allowance'] . '.pdf',
                'generated_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')),
                'generated_by' => $financeId,
            ];
            $slipCounter++;
        }

        if (!empty($data)) {
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                $this->db->table('allowance_slips')->insertBatch($chunk);
            }
        }

        echo "   ✅ Created " . count($data) . " allowance slips for paid allowances\n";
    }
}
