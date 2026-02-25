<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WeeklyProjectSeeder extends Seeder
{
    public function run()
    {
        echo "   📦 Seeding Weekly Projects...\n";

        // Ambil semua intern users
        $interns = $this->db->table('users')
            ->select('users.id_user, users.id_divisi, users.nama_lengkap')
            ->join('interns', 'interns.id_user = users.id_user')
            ->where('interns.status_magang', 'active')
            ->get()
            ->getResultArray();

        if (empty($interns)) {
            echo "   ⚠️  No active interns found. Run InternSeeder first.\n";
            return;
        }

        // Template project berdasarkan divisi
        $projectTemplates = [
            'IT' => [
                ['judul' => 'Pengembangan Modul Dashboard Admin', 'tipe' => 'assigned'],
                ['judul' => 'Implementasi REST API untuk Mobile App', 'tipe' => 'assigned'],
                ['judul' => 'Bug Fixing dan Optimasi Database Query', 'tipe' => 'assigned'],
                ['judul' => 'Membuat Dokumentasi Teknis Sistem', 'tipe' => 'inisiatif'],
                ['judul' => 'Setup CI/CD Pipeline untuk Deployment', 'tipe' => 'assigned'],
                ['judul' => 'Implementasi Unit Testing untuk Modul Core', 'tipe' => 'inisiatif'],
                ['judul' => 'Integrasi Third Party Payment Gateway', 'tipe' => 'assigned'],
                ['judul' => 'Migrasi Data dari Legacy System', 'tipe' => 'assigned'],
                ['judul' => 'Membuat Automation Script untuk Report', 'tipe' => 'inisiatif'],
                ['judul' => 'Pengembangan Fitur Notification System', 'tipe' => 'assigned'],
            ],
            'Finance' => [
                ['judul' => 'Analisis Laporan Keuangan Bulanan', 'tipe' => 'assigned'],
                ['judul' => 'Rekonsiliasi Data Transaksi', 'tipe' => 'assigned'],
                ['judul' => 'Pembuatan Template Report Excel', 'tipe' => 'inisiatif'],
                ['judul' => 'Review dan Validasi Invoice Vendor', 'tipe' => 'assigned'],
                ['judul' => 'Penginputan Data Budget Planning', 'tipe' => 'assigned'],
                ['judul' => 'Analisis Cost Reduction Opportunity', 'tipe' => 'inisiatif'],
                ['judul' => 'Pembuatan Dashboard Finance KPI', 'tipe' => 'assigned'],
                ['judul' => 'Audit Internal Petty Cash', 'tipe' => 'assigned'],
            ],
            'Marketing' => [
                ['judul' => 'Riset Kompetitor dan Market Analysis', 'tipe' => 'assigned'],
                ['judul' => 'Pembuatan Konten Social Media', 'tipe' => 'assigned'],
                ['judul' => 'Analisis Campaign Performance', 'tipe' => 'assigned'],
                ['judul' => 'Desain Materi Promosi Event', 'tipe' => 'inisiatif'],
                ['judul' => 'Customer Survey dan Analysis', 'tipe' => 'assigned'],
                ['judul' => 'Proposal Strategi Digital Marketing', 'tipe' => 'inisiatif'],
                ['judul' => 'Update Website Content', 'tipe' => 'assigned'],
                ['judul' => 'Koordinasi Event Promotional', 'tipe' => 'assigned'],
            ],
            'Operations' => [
                ['judul' => 'Audit Prosedur Operasional', 'tipe' => 'assigned'],
                ['judul' => 'Optimasi Workflow Process', 'tipe' => 'inisiatif'],
                ['judul' => 'Inventory Management System Update', 'tipe' => 'assigned'],
                ['judul' => 'SOP Documentation Review', 'tipe' => 'assigned'],
                ['judul' => 'Vendor Performance Evaluation', 'tipe' => 'assigned'],
                ['judul' => 'Quality Control Improvement', 'tipe' => 'inisiatif'],
                ['judul' => 'Logistics Optimization Project', 'tipe' => 'assigned'],
                ['judul' => 'Warehouse Management Improvement', 'tipe' => 'assigned'],
            ],
            'General' => [
                ['judul' => 'Riset dan Analisis Data', 'tipe' => 'assigned'],
                ['judul' => 'Pembuatan Report Mingguan', 'tipe' => 'assigned'],
                ['judul' => 'Dokumentasi Proses Kerja', 'tipe' => 'inisiatif'],
                ['judul' => 'Supporting Tim untuk Project Utama', 'tipe' => 'assigned'],
                ['judul' => 'Administrative Task Management', 'tipe' => 'assigned'],
                ['judul' => 'Cross-functional Team Collaboration', 'tipe' => 'assigned'],
            ],
        ];

        $deliverableTemplates = [
            'Dokumen laporan dalam format Word/PDF yang sudah direview',
            'Source code yang sudah di-commit ke repository dengan dokumentasi',
            'Spreadsheet Excel dengan analisis data lengkap',
            'Presentasi PowerPoint untuk tim',
            'Prototype/mockup yang siap untuk review',
            'Database/sistem yang sudah diupdate dan berjalan',
            'SOP/dokumentasi teknis yang sudah divalidasi',
            'Report analisis dengan insights dan rekomendasi',
        ];

        $data = [];
        $totalCount = 0;

        // Generate untuk 12 minggu terakhir (Dec 2025 - Feb 2026)
        $weekStart = strtotime('2025-12-01'); // Minggu pertama Desember
        $currentWeek = $weekStart;

        while ($currentWeek <= strtotime('2026-02-22')) {
            $weekNumber = date('W', $currentWeek);
            $year = date('Y', $currentWeek);
            $periodeSelesai = strtotime('+4 days', $currentWeek); // Jumat

            foreach ($interns as $intern) {
                // 85% chance submit project per minggu
                if (rand(1, 100) > 85) {
                    continue;
                }

                // Determine divisi untuk template
                $divisi = $this->getDivisiName($intern['id_divisi']);
                $templates = $projectTemplates[$divisi] ?? $projectTemplates['General'];

                // Random project dari template
                $project = $templates[array_rand($templates)];

                // Progress (lebih tinggi untuk minggu yang sudah lewat)
                $isOldWeek = $currentWeek < strtotime('-2 weeks');
                $progress = $isOldWeek ? rand(70, 100) : rand(30, 100);

                // Self rating (1-5 dengan desimal)
                $selfRating = round(rand(35, 50) / 10, 2);

                // Status submission
                if ($isOldWeek) {
                    $statusChance = rand(1, 100);
                    if ($statusChance <= 70) {
                        $status = 'assessed';
                    } elseif ($statusChance <= 90) {
                        $status = 'submitted';
                    } else {
                        $status = 'draft';
                    }
                } else {
                    $statusChance = rand(1, 100);
                    if ($statusChance <= 30) {
                        $status = 'assessed';
                    } elseif ($statusChance <= 60) {
                        $status = 'submitted';
                    } else {
                        $status = 'draft';
                    }
                }

                // Get mentor
                $internData = $this->db->table('interns')
                    ->where('id_user', $intern['id_user'])
                    ->get()
                    ->getRowArray();
                $mentorId = $internData['id_mentor'] ?? null;

                $mentorRating = null;
                $feedbackMentor = null;
                $assessedBy = null;
                $assessedAt = null;

                if ($status === 'assessed' && $mentorId) {
                    $mentorRating = round(rand(30, 50) / 10, 2);
                    $feedbackMentor = $this->getRandomMentorFeedback($mentorRating);
                    $assessedBy = $mentorId;
                    $assessedAt = date('Y-m-d H:i:s', strtotime('+' . rand(1, 3) . ' days', $periodeSelesai));
                }

                $data[] = [
                    'id_user' => $intern['id_user'],
                    'week_number' => (int)$weekNumber,
                    'tahun' => $year,
                    'periode_mulai' => date('Y-m-d', $currentWeek),
                    'periode_selesai' => date('Y-m-d', $periodeSelesai),
                    'judul_project' => $project['judul'],
                    'tipe_project' => $project['tipe'],
                    'deskripsi' => $this->generateProjectDescription($project['judul']),
                    'progress' => $progress,
                    'deliverables' => $deliverableTemplates[array_rand($deliverableTemplates)],
                    'attachment' => null,
                    'self_rating' => $selfRating,
                    'status_submission' => $status,
                    'mentor_rating' => $mentorRating,
                    'feedback_mentor' => $feedbackMentor,
                    'assessed_by' => $assessedBy,
                    'assessed_at' => $assessedAt,
                    'created_at' => date('Y-m-d H:i:s', $currentWeek),
                    'updated_at' => date('Y-m-d H:i:s', $currentWeek),
                ];

                $totalCount++;
            }

            // Move ke minggu berikutnya
            $currentWeek = strtotime('+7 days', $currentWeek);
        }

        // Insert in batches
        if (!empty($data)) {
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                $this->db->table('weekly_projects')->insertBatch($chunk);
            }
        }

        echo "   ✅ Created {$totalCount} weekly projects\n";
    }

    private function getDivisiName($idDivisi)
    {
        $divisiMap = [
            1 => 'IT',
            2 => 'Finance',
            3 => 'Marketing',
            4 => 'Operations',
            5 => 'General',
            6 => 'General',
            7 => 'General',
            8 => 'General',
        ];
        return $divisiMap[$idDivisi] ?? 'General';
    }

    private function generateProjectDescription($judul)
    {
        $descriptions = [
            "Project ini bertujuan untuk menyelesaikan {$judul} dengan timeline yang sudah ditentukan. " .
                "Deliverable yang diharapkan adalah output yang berkualitas dan sesuai dengan standar perusahaan. " .
                "Selama pengerjaan, saya melakukan koordinasi dengan mentor dan tim terkait untuk memastikan hasil yang optimal.",

            "Dalam minggu ini, saya fokus mengerjakan {$judul}. " .
                "Proses pengerjaan meliputi analisis kebutuhan, implementasi, dan testing. " .
                "Kendala yang dihadapi dapat diselesaikan dengan baik melalui diskusi dengan mentor.",

            "Project {$judul} merupakan bagian dari inisiatif pengembangan yang sedang berjalan. " .
                "Saya melakukan research terlebih dahulu sebelum implementasi untuk memastikan pendekatan yang tepat. " .
                "Hasil pekerjaan sudah direview oleh mentor dengan feedback yang konstruktif.",
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomMentorFeedback($rating)
    {
        if ($rating >= 4.5) {
            $feedbacks = [
                'Excellent work! Kualitas output sangat baik dan sesuai ekspektasi. Pertahankan!',
                'Very good job! Inisiatif dan kualitas kerja sangat memuaskan.',
                'Outstanding performance! Project selesai dengan baik dan tepat waktu.',
            ];
        } elseif ($rating >= 4.0) {
            $feedbacks = [
                'Good work! Ada beberapa improvement yang bisa dilakukan untuk hasil lebih optimal.',
                'Bagus, target tercapai dengan baik. Terus tingkatkan kemampuan.',
                'Kerja yang baik, dokumentasi sudah lengkap. Keep it up!',
            ];
        } elseif ($rating >= 3.0) {
            $feedbacks = [
                'Cukup baik, namun perlu lebih detail dalam dokumentasi dan testing.',
                'Average performance. Mohon tingkatkan kualitas dan ketelitian.',
                'OK, tapi masih ada ruang untuk improvement. Diskusi lebih intensif.',
            ];
        } else {
            $feedbacks = [
                'Perlu improvement signifikan. Mari diskusi untuk perbaikan ke depan.',
                'Below expectation. Mohon lebih fokus dan konsultasi jika ada kendala.',
            ];
        }

        return $feedbacks[array_rand($feedbacks)];
    }
}
