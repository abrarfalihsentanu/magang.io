<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KpiAssessmentSeeder extends Seeder
{
    public function run()
    {
        echo "   📊 Seeding KPI Assessments...\n";

        // Get all active interns with mentor
        $interns = $this->db->table('interns')
            ->select('interns.id_intern, interns.id_user, interns.id_mentor')
            ->where('interns.status_magang', 'active')
            ->get()->getResultArray();

        if (empty($interns)) {
            echo "   ⚠️  No active interns found. Run InternSeeder first.\n";
            return;
        }

        // Get all active KPI indicators
        $indicators = $this->db->table('kpi_indicators')
            ->where('is_active', 1)
            ->get()->getResultArray();

        if (empty($indicators)) {
            echo "   ⚠️  No KPI indicators found. Run KPIIndicatorSeeder first.\n";
            return;
        }

        // Generate assessments for months: Sep 2025 (9) to Feb 2026 (2)
        $months = [
            ['bulan' => 9, 'tahun' => 2025],
            ['bulan' => 10, 'tahun' => 2025],
            ['bulan' => 11, 'tahun' => 2025],
            ['bulan' => 12, 'tahun' => 2025],
            ['bulan' => 1, 'tahun' => 2026],
            ['bulan' => 2, 'tahun' => 2026],
        ];

        $data = [];
        $totalCount = 0;

        foreach ($interns as $intern) {
            foreach ($months as $period) {
                // Older months more likely to have full assessments
                $isOldMonth = ($period['tahun'] < 2026) || ($period['tahun'] == 2026 && $period['bulan'] <= 1);

                foreach ($indicators as $indicator) {
                    // Skip some assessments for recent months (not yet assessed)
                    if (!$isOldMonth && rand(1, 100) > 70) {
                        continue;
                    }

                    // Generate raw score based on category
                    $nilaiRaw = $this->generateRawScore($indicator['kategori'], $intern['id_user']);
                    $bobot = (float) $indicator['bobot'];
                    $nilaiWeighted = round(($nilaiRaw / 100) * $bobot, 2);

                    // Penilai is the mentor for manual indicators, or system (null) for auto-calculated
                    $penilaiId = null;
                    if ($indicator['is_auto_calculate'] == 0) {
                        $penilaiId = $intern['id_mentor'];
                    }

                    $catatan = null;
                    if ($penilaiId && rand(1, 100) <= 40) {
                        $catatan = $this->getRandomCatatan($indicator['kategori'], $nilaiRaw);
                    }

                    $data[] = [
                        'id_user'        => $intern['id_user'],
                        'id_indicator'   => $indicator['id_indicator'],
                        'bulan'          => $period['bulan'],
                        'tahun'          => $period['tahun'],
                        'nilai_raw'      => $nilaiRaw,
                        'nilai_weighted' => $nilaiWeighted,
                        'penilai_id'     => $penilaiId,
                        'catatan'        => $catatan,
                        'created_at'     => date('Y-m-d H:i:s', mktime(0, 0, 0, $period['bulan'], 28, $period['tahun'])),
                        'updated_at'     => date('Y-m-d H:i:s', mktime(0, 0, 0, $period['bulan'], 28, $period['tahun'])),
                    ];
                    $totalCount++;
                }
            }
        }

        if (!empty($data)) {
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                $this->db->table('kpi_assessments')->insertBatch($chunk);
            }
        }

        echo "   ✅ Created {$totalCount} KPI assessments for " . count($interns) . " interns × " . count($months) . " months\n";
    }

    /**
     * Generate realistic raw score (0-100) based on indicator category
     */
    private function generateRawScore(string $kategori, int $userId): float
    {
        // Use userId to create some variance - some interns are consistently better
        $baseModifier = (($userId % 7) - 3) * 3; // -9 to +9

        switch ($kategori) {
            case 'kehadiran':
                // Attendance scores tend to be high (70-100)
                $base = rand(70, 100);
                break;
            case 'aktivitas':
                // Activity scores are moderate-high (55-95)
                $base = rand(55, 95);
                break;
            case 'project':
                // Project scores have wider range (50-100)
                $base = rand(50, 100);
                break;
            default:
                $base = rand(60, 90);
        }

        $score = $base + $baseModifier;
        return round(max(20, min(100, $score)), 2);
    }

    /**
     * Generate assessment notes
     */
    private function getRandomCatatan(string $kategori, float $nilai): ?string
    {
        if ($nilai >= 85) {
            $comments = [
                'Performa sangat baik, pertahankan!',
                'Excellent! Kualitas kerja di atas rata-rata.',
                'Sangat memuaskan, bisa menjadi contoh bagi intern lainnya.',
                'Outstanding performance bulan ini!',
            ];
        } elseif ($nilai >= 70) {
            $comments = [
                'Performa baik, terus tingkatkan.',
                'Good job, ada beberapa area yang bisa diperbaiki.',
                'Cukup konsisten, perlu sedikit peningkatan.',
                'Baik, tetap semangat dan fokus.',
            ];
        } else {
            $comments = [
                'Perlu peningkatan di area ini.',
                'Below average, mohon diskusi dengan mentor untuk improvement plan.',
                'Perlu perhatian lebih di aspek ini. Mari evaluasi bersama.',
                'Kurang memenuhi target, akan ada mentoring khusus.',
            ];
        }

        return $comments[array_rand($comments)];
    }
}
