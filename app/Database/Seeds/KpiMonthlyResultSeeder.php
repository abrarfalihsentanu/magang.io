<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KpiMonthlyResultSeeder extends Seeder
{
    public function run()
    {
        echo "   📈 Seeding KPI Monthly Results...\n";

        // Get all active interns
        $interns = $this->db->table('interns')
            ->select('interns.id_user')
            ->where('interns.status_magang', 'active')
            ->get()->getResultArray();

        if (empty($interns)) {
            echo "   ⚠️  No active interns found.\n";
            return;
        }

        $months = [
            ['bulan' => 9, 'tahun' => 2025],
            ['bulan' => 10, 'tahun' => 2025],
            ['bulan' => 11, 'tahun' => 2025],
            ['bulan' => 12, 'tahun' => 2025],
            ['bulan' => 1, 'tahun' => 2026],
            ['bulan' => 2, 'tahun' => 2026],
        ];

        // HR user (id=2) or Admin (id=1) finalizes results
        $finalizerIds = [1, 2];

        $data = [];
        $totalCount = 0;

        foreach ($months as $period) {
            // Older months finalized, recent months may still be draft
            $isOldMonth = ($period['tahun'] < 2026) || ($period['tahun'] == 2026 && $period['bulan'] <= 1);

            // Calculate total scores from kpi_assessments for ranking
            $monthScores = [];
            foreach ($interns as $intern) {
                $result = $this->db->table('kpi_assessments')
                    ->selectSum('nilai_weighted', 'total')
                    ->where('id_user', $intern['id_user'])
                    ->where('bulan', $period['bulan'])
                    ->where('tahun', $period['tahun'])
                    ->get()->getRowArray();

                $totalScore = round((float)($result['total'] ?? 0), 2);

                // If no assessments exist for this month, generate a synthetic score
                if ($totalScore == 0) {
                    $baseModifier = (($intern['id_user'] % 7) - 3) * 2;
                    $totalScore = round(rand(55, 90) + $baseModifier, 2);
                    $totalScore = max(30, min(100, $totalScore));
                }

                $monthScores[] = [
                    'id_user'     => $intern['id_user'],
                    'total_score' => $totalScore,
                ];
            }

            // Sort by total_score descending for ranking
            usort($monthScores, fn($a, $b) => $b['total_score'] <=> $a['total_score']);

            $rank = 1;
            foreach ($monthScores as $ms) {
                $isFinalized = $isOldMonth ? (rand(1, 100) <= 90) : (rand(1, 100) <= 30);

                $kategori = $this->getPerformaCategory($ms['total_score']);

                $finalizedAt = null;
                $finalizedBy = null;
                if ($isFinalized) {
                    $finalizedBy = $finalizerIds[array_rand($finalizerIds)];
                    // Finalized ~5 days after month end
                    $finalizedAt = date('Y-m-d H:i:s', mktime(
                        rand(8, 17),
                        rand(0, 59),
                        0,
                        $period['bulan'] + 1,
                        rand(1, 5),
                        $period['tahun']
                    ));
                }

                $data[] = [
                    'id_user'            => $ms['id_user'],
                    'bulan'              => $period['bulan'],
                    'tahun'              => $period['tahun'],
                    'total_score'        => $ms['total_score'],
                    'rank_bulan_ini'     => $rank,
                    'kategori_performa'  => $kategori,
                    'is_finalized'       => $isFinalized ? 1 : 0,
                    'finalized_at'       => $finalizedAt,
                    'finalized_by'       => $finalizedBy,
                    'created_at'         => date('Y-m-d H:i:s', mktime(0, 0, 0, $period['bulan'], 28, $period['tahun'])),
                    'updated_at'         => date('Y-m-d H:i:s', mktime(0, 0, 0, $period['bulan'], 28, $period['tahun'])),
                ];
                $totalCount++;
                $rank++;
            }
        }

        if (!empty($data)) {
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                $this->db->table('kpi_monthly_results')->insertBatch($chunk);
            }
        }

        echo "   ✅ Created {$totalCount} KPI monthly results (" . count($interns) . " interns × " . count($months) . " months)\n";
    }

    private function getPerformaCategory(float $score): string
    {
        if ($score >= 90) return 'excellent';
        if ($score >= 75) return 'good';
        if ($score >= 60) return 'average';
        if ($score >= 45) return 'below_average';
        return 'poor';
    }
}
