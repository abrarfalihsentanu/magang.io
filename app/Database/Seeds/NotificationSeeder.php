<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        echo "   🔔 Seeding Notifications...\n";

        // Get all users
        $users = $this->db->table('users')
            ->select('id_user, id_role, nama_lengkap')
            ->where('status', 'active')
            ->get()->getResultArray();

        if (empty($users)) {
            echo "   ⚠️  No users found.\n";
            return;
        }

        // Separate by role
        $admins = array_filter($users, fn($u) => $u['id_role'] == 1);
        $hrs = array_filter($users, fn($u) => $u['id_role'] == 2);
        $finances = array_filter($users, fn($u) => $u['id_role'] == 3);
        $mentors = array_filter($users, fn($u) => $u['id_role'] == 4);
        $interns = array_filter($users, fn($u) => $u['id_role'] == 5);

        $data = [];

        // 1. Notifications for INTERNS
        $internNotifTemplates = [
            [
                'type'    => 'activity_approved',
                'title'   => 'Aktivitas Harian Disetujui',
                'message' => 'Aktivitas harian Anda tanggal {date} telah disetujui oleh mentor.',
                'link'    => '/activity/my',
            ],
            [
                'type'    => 'activity_rejected',
                'title'   => 'Aktivitas Harian Ditolak',
                'message' => 'Aktivitas harian Anda tanggal {date} ditolak. Silakan perbaiki dan submit ulang.',
                'link'    => '/activity/my',
            ],
            [
                'type'    => 'project_assessed',
                'title'   => 'Project Mingguan Dinilai',
                'message' => 'Project mingguan Anda telah dinilai oleh mentor. Lihat feedback di halaman project.',
                'link'    => '/project/my',
            ],
            [
                'type'    => 'kpi_updated',
                'title'   => 'KPI Bulanan Diperbarui',
                'message' => 'Hasil KPI bulanan Anda telah diperbarui. Periksa ranking Anda.',
                'link'    => '/intern/kpi/history',
            ],
            [
                'type'    => 'leave_approved',
                'title'   => 'Pengajuan Cuti Disetujui',
                'message' => 'Pengajuan cuti/izin Anda tanggal {date} telah disetujui.',
                'link'    => '/leave/my',
            ],
            [
                'type'    => 'leave_rejected',
                'title'   => 'Pengajuan Cuti Ditolak',
                'message' => 'Pengajuan cuti/izin Anda tanggal {date} ditolak. Periksa alasan penolakan.',
                'link'    => '/leave/my',
            ],
            [
                'type'    => 'payment_processed',
                'title'   => 'Uang Saku Telah Ditransfer',
                'message' => 'Uang saku periode {date} telah ditransfer ke rekening Anda.',
                'link'    => '/allowance/my',
            ],
            [
                'type'    => 'correction_approved',
                'title'   => 'Koreksi Absensi Disetujui',
                'message' => 'Permintaan koreksi absensi tanggal {date} telah disetujui.',
                'link'    => '/attendance/correction',
            ],
            [
                'type'    => 'correction_rejected',
                'title'   => 'Koreksi Absensi Ditolak',
                'message' => 'Permintaan koreksi absensi tanggal {date} ditolak.',
                'link'    => '/attendance/correction',
            ],
            [
                'type'    => 'reminder_logbook',
                'title'   => 'Pengingat: Isi Logbook Harian',
                'message' => 'Anda belum mengisi logbook aktivitas harian hari ini. Segera lengkapi!',
                'link'    => '/activity/my',
            ],
        ];

        // Generate 3-8 notifications per intern
        foreach ($interns as $intern) {
            $numNotifs = rand(3, 8);
            for ($i = 0; $i < $numNotifs; $i++) {
                $template = $internNotifTemplates[array_rand($internNotifTemplates)];
                $daysAgo = rand(0, 60);
                $dateStr = date('d M Y', strtotime("-{$daysAgo} days"));
                $isRead = ($daysAgo > 3) ? (rand(1, 100) <= 80) : (rand(1, 100) <= 20);

                $data[] = [
                    'id_user'    => $intern['id_user'],
                    'type'       => $template['type'],
                    'title'      => $template['title'],
                    'message'    => str_replace('{date}', $dateStr, $template['message']),
                    'link'       => $template['link'],
                    'is_read'    => $isRead ? 1 : 0,
                    'read_at'    => $isRead ? date('Y-m-d H:i:s', strtotime("-{$daysAgo} days +2 hours")) : null,
                    'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                ];
            }
        }

        // 2. Notifications for MENTORS
        $mentorNotifTemplates = [
            [
                'type'    => 'activity_submitted',
                'title'   => 'Aktivitas Baru Menunggu Review',
                'message' => 'Ada aktivitas harian baru dari bimbingan Anda yang perlu direview.',
                'link'    => '/activity/approval',
            ],
            [
                'type'    => 'project_submitted',
                'title'   => 'Project Mingguan Menunggu Penilaian',
                'message' => 'Ada project mingguan baru yang menunggu penilaian Anda.',
                'link'    => '/project/assessment',
            ],
            [
                'type'    => 'leave_request',
                'title'   => 'Pengajuan Cuti Baru',
                'message' => 'Ada pengajuan cuti/izin baru dari bimbingan Anda yang perlu diproses.',
                'link'    => '/leave/approval',
            ],
            [
                'type'    => 'correction_request',
                'title'   => 'Koreksi Absensi Baru',
                'message' => 'Ada permintaan koreksi absensi baru yang perlu Anda tinjau.',
                'link'    => '/attendance/correction_approval',
            ],
            [
                'type'    => 'kpi_reminder',
                'title'   => 'Pengingat: Penilaian KPI',
                'message' => 'Ada indikator KPI manual yang belum Anda nilai bulan ini.',
                'link'    => '/mentor/kpi/assessment',
            ],
        ];

        foreach ($mentors as $mentor) {
            $numNotifs = rand(5, 12);
            for ($i = 0; $i < $numNotifs; $i++) {
                $template = $mentorNotifTemplates[array_rand($mentorNotifTemplates)];
                $daysAgo = rand(0, 45);
                $isRead = ($daysAgo > 2) ? (rand(1, 100) <= 85) : (rand(1, 100) <= 30);

                $data[] = [
                    'id_user'    => $mentor['id_user'],
                    'type'       => $template['type'],
                    'title'      => $template['title'],
                    'message'    => $template['message'],
                    'link'       => $template['link'],
                    'is_read'    => $isRead ? 1 : 0,
                    'read_at'    => $isRead ? date('Y-m-d H:i:s', strtotime("-{$daysAgo} days +1 hour")) : null,
                    'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                ];
            }
        }

        // 3. Notifications for HR/Admin
        $adminNotifTemplates = [
            [
                'type'    => 'kpi_finalized',
                'title'   => 'KPI Bulanan Telah Difinalisasi',
                'message' => 'Hasil KPI bulanan telah difinalisasi dan ranking sudah dihitung.',
                'link'    => '/admin/kpi/monthly',
            ],
            [
                'type'    => 'allowance_calculated',
                'title'   => 'Uang Saku Periodik Telah Dihitung',
                'message' => 'Perhitungan uang saku periode baru telah selesai dan siap disetujui.',
                'link'    => '/allowance/period',
            ],
            [
                'type'    => 'new_intern',
                'title'   => 'Pemagang Baru Terdaftar',
                'message' => 'Ada pemagang baru yang telah terdaftar di sistem.',
                'link'    => '/intern',
            ],
            [
                'type'    => 'system_alert',
                'title'   => 'Alert Sistem',
                'message' => 'Terdapat beberapa pemagang yang belum mengisi logbook lebih dari 3 hari berturut-turut.',
                'link'    => '/report/activity',
            ],
        ];

        $staffUsers = array_merge(array_values($admins), array_values($hrs), array_values($finances));
        foreach ($staffUsers as $staff) {
            $numNotifs = rand(3, 8);
            for ($i = 0; $i < $numNotifs; $i++) {
                $template = $adminNotifTemplates[array_rand($adminNotifTemplates)];
                $daysAgo = rand(0, 30);
                $isRead = ($daysAgo > 1) ? (rand(1, 100) <= 90) : (rand(1, 100) <= 40);

                $data[] = [
                    'id_user'    => $staff['id_user'],
                    'type'       => $template['type'],
                    'title'      => $template['title'],
                    'message'    => $template['message'],
                    'link'       => $template['link'],
                    'is_read'    => $isRead ? 1 : 0,
                    'read_at'    => $isRead ? date('Y-m-d H:i:s', strtotime("-{$daysAgo} days +30 minutes")) : null,
                    'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                ];
            }
        }

        if (!empty($data)) {
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                $this->db->table('notifications')->insertBatch($chunk);
            }
        }

        $totalRead = count(array_filter($data, fn($n) => $n['is_read'] == 1));
        $totalUnread = count($data) - $totalRead;

        echo "   ✅ Created " . count($data) . " notifications (Read: {$totalRead}, Unread: {$totalUnread})\n";
    }
}
