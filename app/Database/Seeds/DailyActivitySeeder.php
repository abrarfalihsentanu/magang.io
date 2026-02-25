<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DailyActivitySeeder extends Seeder
{
    public function run()
    {
        echo "   🗓️  Seeding Daily Activities...\n";

        // Ambil semua intern users (id_user 9-43)
        $interns = $this->db->table('users')
            ->select('users.id_user, users.id_divisi')
            ->join('interns', 'interns.id_user = users.id_user')
            ->where('interns.status_magang', 'active')
            ->get()
            ->getResultArray();

        if (empty($interns)) {
            echo "   ⚠️  No active interns found. Run InternSeeder first.\n";
            return;
        }

        // Kategori aktivitas dan contoh aktivitas
        $activityTemplates = [
            'learning' => [
                ['judul' => 'Mempelajari dokumentasi framework CodeIgniter 4', 'durasi' => 2],
                ['judul' => 'Training online tentang best practices PHP', 'durasi' => 3],
                ['judul' => 'Membaca dokumentasi API internal perusahaan', 'durasi' => 1.5],
                ['judul' => 'Belajar menggunakan Git version control', 'durasi' => 2],
                ['judul' => 'Mempelajari database design dan normalisasi', 'durasi' => 2.5],
                ['judul' => 'Mengikuti tutorial Laravel Livewire', 'durasi' => 2],
                ['judul' => 'Belajar konsep RESTful API', 'durasi' => 1.5],
                ['judul' => 'Mempelajari konsep MVC architecture', 'durasi' => 2],
            ],
            'task' => [
                ['judul' => 'Mengerjakan fitur CRUD data master', 'durasi' => 4],
                ['judul' => 'Fixing bug pada halaman dashboard', 'durasi' => 2],
                ['judul' => 'Membuat laporan harian dalam format Excel', 'durasi' => 1.5],
                ['judul' => 'Implementasi validasi form input', 'durasi' => 3],
                ['judul' => 'Optimasi query database untuk performa', 'durasi' => 2.5],
                ['judul' => 'Membuat unit testing untuk modul baru', 'durasi' => 3],
                ['judul' => 'Integrasi API payment gateway', 'durasi' => 4],
                ['judul' => 'Refactoring code untuk clean architecture', 'durasi' => 3],
                ['judul' => 'Membuat fitur export PDF', 'durasi' => 2.5],
                ['judul' => 'Setup environment development', 'durasi' => 2],
            ],
            'meeting' => [
                ['judul' => 'Daily standup meeting dengan tim', 'durasi' => 0.5],
                ['judul' => 'Sprint planning meeting', 'durasi' => 2],
                ['judul' => 'Meeting review progress dengan mentor', 'durasi' => 1],
                ['judul' => 'Brainstorming session fitur baru', 'durasi' => 1.5],
                ['judul' => 'Meeting koordinasi dengan tim design', 'durasi' => 1],
                ['judul' => 'Retrospective meeting sprint', 'durasi' => 1],
            ],
            'training' => [
                ['judul' => 'Mengikuti workshop internal tentang security', 'durasi' => 3],
                ['judul' => 'Training penggunaan tools project management', 'durasi' => 2],
                ['judul' => 'Onboarding session untuk sistem baru', 'durasi' => 2],
                ['judul' => 'Workshop clean code dan code review', 'durasi' => 2.5],
                ['judul' => 'Training soft skills dan komunikasi', 'durasi' => 2],
            ],
            'other' => [
                ['judul' => 'Dokumentasi teknis untuk modul yang dikerjakan', 'durasi' => 2],
                ['judul' => 'Setup dan konfigurasi laptop kerja', 'durasi' => 1.5],
                ['judul' => 'Membantu rekan menyelesaikan bug', 'durasi' => 1],
                ['judul' => 'Review dan testing fitur dari rekan', 'durasi' => 1.5],
            ],
        ];

        $deskripsiTemplates = [
            'learning' => [
                'Melakukan pembelajaran mandiri tentang topik terkait. Mencatat poin-poin penting dan membuat ringkasan materi untuk referensi di kemudian hari. Materi yang dipelajari sangat membantu untuk meningkatkan pemahaman dan skill.',
                'Mengikuti materi pembelajaran online dan praktik langsung. Membuat notes dan dokumentasi dari hasil belajar untuk sharing knowledge dengan tim.',
                'Studi mendalam mengenai teknologi yang digunakan di project. Melakukan hands-on practice untuk memastikan pemahaman yang baik.',
            ],
            'task' => [
                'Mengerjakan task yang diberikan oleh mentor dengan fokus dan teliti. Melakukan testing untuk memastikan hasil kerja sesuai requirement. Jika ada kendala, langsung berkonsultasi dengan mentor.',
                'Menyelesaikan tugas sesuai timeline yang ditetapkan. Melakukan dokumentasi pada code yang dibuat dan memastikan code clean dan readable.',
                'Fokus mengerjakan deliverable sesuai prioritas. Melakukan self-review sebelum submit dan memastikan tidak ada bugs.',
            ],
            'meeting' => [
                'Menghadiri meeting dan aktif berpartisipasi dalam diskusi. Mencatat action items dan follow up yang perlu dilakukan setelah meeting.',
                'Koordinasi dengan tim terkait progress dan blockers. Menyampaikan update status pekerjaan dan rencana selanjutnya.',
            ],
            'training' => [
                'Mengikuti training dengan antusias dan aktif bertanya. Mencoba langsung praktik dari materi yang disampaikan dan mendiskusikan dengan peserta lain.',
                'Melakukan hands-on practice sesuai modul training. Menyimpan materi dan notes untuk referensi di kemudian hari.',
            ],
            'other' => [
                'Melakukan aktivitas pendukung yang membantu kelancaran pekerjaan tim. Berkolaborasi dengan baik dan memastikan output yang berkualitas.',
                'Menyelesaikan tugas administratif dan pendukung lainnya dengan baik dan tepat waktu.',
            ],
        ];

        $data = [];
        $totalCount = 0;

        // Generate untuk 3 bulan terakhir (Dec 2025, Jan 2026, Feb 2026)
        $startDate = strtotime('2025-12-01');
        $endDate = strtotime('2026-02-21'); // sampai kemarin

        foreach ($interns as $intern) {
            $currentDate = $startDate;
            $activitiesPerIntern = 0;

            while ($currentDate <= $endDate) {
                // Skip weekend
                $dayOfWeek = date('N', $currentDate);
                if ($dayOfWeek >= 6) {
                    $currentDate = strtotime('+1 day', $currentDate);
                    continue;
                }

                // 80% chance ada aktivitas per hari kerja
                if (rand(1, 100) <= 80) {
                    // 1-3 aktivitas per hari
                    $activitiesCount = rand(1, 3);

                    for ($a = 0; $a < $activitiesCount; $a++) {
                        // Random kategori
                        $categories = array_keys($activityTemplates);
                        $kategori = $categories[array_rand($categories)];

                        // Random aktivitas dari kategori
                        $activity = $activityTemplates[$kategori][array_rand($activityTemplates[$kategori])];
                        $deskripsi = $deskripsiTemplates[$kategori][array_rand($deskripsiTemplates[$kategori])];

                        // Jam mulai random antara 08:00 - 15:00
                        $jamMulai = sprintf('%02d:%02d:00', rand(8, 15), rand(0, 5) * 10);
                        $durasiMenit = $activity['durasi'] * 60;
                        $jamSelesai = date('H:i:s', strtotime($jamMulai) + ($durasiMenit * 60));

                        // Status approval (lebih banyak approved untuk data lama)
                        $isOldData = $currentDate < strtotime('-14 days');
                        if ($isOldData) {
                            $statusChance = rand(1, 100);
                            if ($statusChance <= 70) {
                                $statusApproval = 'approved';
                            } elseif ($statusChance <= 85) {
                                $statusApproval = 'submitted';
                            } elseif ($statusChance <= 95) {
                                $statusApproval = 'rejected';
                            } else {
                                $statusApproval = 'draft';
                            }
                        } else {
                            $statusChance = rand(1, 100);
                            if ($statusChance <= 40) {
                                $statusApproval = 'approved';
                            } elseif ($statusChance <= 70) {
                                $statusApproval = 'submitted';
                            } else {
                                $statusApproval = 'draft';
                            }
                        }

                        // Get mentor for this intern
                        $internData = $this->db->table('interns')
                            ->where('id_user', $intern['id_user'])
                            ->get()
                            ->getRowArray();
                        $mentorId = $internData['id_mentor'] ?? null;

                        $approvedBy = null;
                        $approvedAt = null;
                        $catatanMentor = null;

                        if ($statusApproval === 'approved' && $mentorId) {
                            $approvedBy = $mentorId;
                            $approvedAt = date('Y-m-d H:i:s', strtotime('+1 day', $currentDate));
                            $catatanMentor = $this->getRandomApprovalComment();
                        } elseif ($statusApproval === 'rejected' && $mentorId) {
                            $approvedBy = $mentorId;
                            $approvedAt = date('Y-m-d H:i:s', strtotime('+1 day', $currentDate));
                            $catatanMentor = $this->getRandomRejectionComment();
                        }

                        $data[] = [
                            'id_user' => $intern['id_user'],
                            'tanggal' => date('Y-m-d', $currentDate),
                            'jam_mulai' => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                            'judul_aktivitas' => $activity['judul'],
                            'deskripsi' => $deskripsi,
                            'kategori' => $kategori,
                            'attachment' => null,
                            'status_approval' => $statusApproval,
                            'approved_by' => $approvedBy,
                            'approved_at' => $approvedAt,
                            'catatan_mentor' => $catatanMentor,
                            'created_at' => date('Y-m-d H:i:s', $currentDate),
                            'updated_at' => date('Y-m-d H:i:s', $currentDate),
                        ];

                        $totalCount++;
                        $activitiesPerIntern++;
                    }
                }

                $currentDate = strtotime('+1 day', $currentDate);
            }
        }

        // Insert in batches
        if (!empty($data)) {
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                $this->db->table('daily_activities')->insertBatch($chunk);
            }
        }

        echo "   ✅ Created {$totalCount} daily activities for " . count($interns) . " interns\n";
    }

    private function getRandomApprovalComment()
    {
        $comments = [
            'Bagus, lanjutkan kerja yang baik!',
            'Approved. Pertahankan kualitas kerja.',
            'Aktivitas sesuai target. Good job!',
            'OK, dokumentasi sudah lengkap.',
            'Approved. Tetap semangat!',
            null, // kadang tidak ada komentar
            null,
        ];
        return $comments[array_rand($comments)];
    }

    private function getRandomRejectionComment()
    {
        $comments = [
            'Mohon lengkapi deskripsi aktivitas dengan lebih detail.',
            'Durasi aktivitas tidak sesuai, mohon review kembali.',
            'Perlu penjelasan lebih lanjut mengenai output yang dihasilkan.',
            'Mohon sertakan bukti/attachment untuk aktivitas ini.',
        ];
        return $comments[array_rand($comments)];
    }
}
