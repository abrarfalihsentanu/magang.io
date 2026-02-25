<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KpiPeriodResultSeeder extends Seeder
{
    public function run()
    {
        echo "   🏆 Seeding KPI Period Results...\n";

        // Get all interns with their intern IDs
        $interns = $this->db->table('interns')
            ->select('interns.id_intern, interns.id_user, interns.periode_mulai, interns.periode_selesai')
            ->where('interns.status_magang', 'active')
            ->get()->getResultArray();

        if (empty($interns)) {
            echo "   ⚠️  No active interns found.\n";
            return;
        }

        $data = [];
        $periodStart = '2025-09-01';
        $periodEnd = '2026-02-28';

        // Compute average total_score across all months from kpi_monthly_results
        $scores = [];
        foreach ($interns as $intern) {
            $result = $this->db->table('kpi_monthly_results')
                ->selectAvg('total_score', 'avg_score')
                ->where('id_user', $intern['id_user'])
                ->get()->getRowArray();

            $avgScore = round((float)($result['avg_score'] ?? 0), 2);

            // If no monthly results, generate synthetic
            if ($avgScore == 0) {
                $baseModifier = (($intern['id_user'] % 7) - 3) * 2;
                $avgScore = round(rand(55, 88) + $baseModifier, 2);
                $avgScore = max(30, min(100, $avgScore));
            }

            $scores[] = [
                'id_intern'  => $intern['id_intern'],
                'id_user'    => $intern['id_user'],
                'avg_score'  => $avgScore,
            ];
        }

        // Sort by avg_score descending for final ranking
        usort($scores, fn($a, $b) => $b['avg_score'] <=> $a['avg_score']);

        $rank = 1;
        $totalInterns = count($scores);
        // Top 3 are best interns
        $bestInternCount = min(3, $totalInterns);

        foreach ($scores as $index => $item) {
            $isBest = ($index < $bestInternCount) ? 1 : 0;

            $sertifikat = null;
            if ($isBest) {
                $sertifikat = 'uploads/sertifikat/best_intern_rank_' . ($index + 1) . '.pdf';
            }

            $data[] = [
                'id_intern'       => $item['id_intern'],
                'periode_mulai'   => $periodStart,
                'periode_selesai' => $periodEnd,
                'avg_total_score' => $item['avg_score'],
                'final_rank'      => $rank,
                'is_best_intern'  => $isBest,
                'sertifikat_file' => $sertifikat,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
            $rank++;
        }

        if (!empty($data)) {
            $this->db->table('kpi_period_results')->insertBatch($data);
        }

        echo "   ✅ Created " . count($data) . " KPI period results (Top {$bestInternCount} marked as best intern)\n";
    }
}
