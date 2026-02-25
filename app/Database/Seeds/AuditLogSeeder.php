<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run()
    {
        echo "   📝 Seeding Audit Logs...\n";

        $data = [];
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Mobile/15E148 Safari/604.1',
        ];

        $ipAddresses = [
            '192.168.1.' . rand(10, 254),
            '10.0.0.' . rand(10, 254),
            '172.16.0.' . rand(10, 254),
            '103.28.12.' . rand(10, 254),
            '202.134.5.' . rand(10, 254),
        ];

        // 1. Login/Logout logs for various users
        $activeUsers = $this->db->table('users')
            ->select('id_user, nama_lengkap, id_role')
            ->where('status', 'active')
            ->get()->getResultArray();

        foreach ($activeUsers as $user) {
            // 1-5 login events per user over past 60 days
            $loginCount = rand(1, 5);
            for ($i = 0; $i < $loginCount; $i++) {
                $daysAgo = rand(0, 60);
                $loginTime = date('Y-m-d H:i:s', strtotime("-{$daysAgo} days " . rand(7, 9) . ':' . rand(0, 59) . ':00'));

                $data[] = [
                    'id_user'    => $user['id_user'],
                    'action'     => 'login',
                    'module'     => 'auth',
                    'record_id'  => $user['id_user'],
                    'old_data'   => null,
                    'new_data'   => json_encode(['nama' => $user['nama_lengkap'], 'time' => $loginTime]),
                    'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                    'user_agent' => $userAgents[array_rand($userAgents)],
                    'created_at' => $loginTime,
                ];

                // Corresponding logout
                $logoutTime = date('Y-m-d H:i:s', strtotime($loginTime . ' +' . rand(4, 10) . ' hours'));
                $data[] = [
                    'id_user'    => $user['id_user'],
                    'action'     => 'logout',
                    'module'     => 'auth',
                    'record_id'  => $user['id_user'],
                    'old_data'   => null,
                    'new_data'   => json_encode(['time' => $logoutTime]),
                    'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                    'user_agent' => $userAgents[array_rand($userAgents)],
                    'created_at' => $logoutTime,
                ];
            }
        }

        // 2. Attendance check-in/out logs (~30 sample entries)
        for ($i = 0; $i < 30; $i++) {
            $userId = rand(9, 43);
            $daysAgo = rand(0, 30);
            $date = date('Y-m-d', strtotime("-{$daysAgo} days"));

            $data[] = [
                'id_user'    => $userId,
                'action'     => 'create',
                'module'     => 'attendance',
                'record_id'  => rand(1, 4000),
                'old_data'   => null,
                'new_data'   => json_encode([
                    'tanggal' => $date,
                    'status' => ['hadir', 'terlambat'][array_rand(['hadir', 'terlambat'])],
                    'jam_masuk' => '08:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00',
                ]),
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => date('Y-m-d 08:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00', strtotime("-{$daysAgo} days")),
            ];
        }

        // 3. Activity approval logs by mentors
        for ($i = 0; $i < 20; $i++) {
            $mentorId = rand(4, 8);
            $daysAgo = rand(0, 30);

            $data[] = [
                'id_user'    => $mentorId,
                'action'     => 'approve',
                'module'     => 'activity',
                'record_id'  => rand(1, 2000),
                'old_data'   => json_encode(['status_approval' => 'submitted']),
                'new_data'   => json_encode(['status_approval' => 'approved', 'catatan_mentor' => 'Good job!']),
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days " . rand(9, 17) . ':' . rand(0, 59) . ':00')),
            ];
        }

        // 4. KPI finalization logs by HR/Admin
        $months = ['September 2025', 'Oktober 2025', 'November 2025', 'Desember 2025', 'Januari 2026'];
        foreach ($months as $idx => $month) {
            $data[] = [
                'id_user'    => [1, 2][array_rand([1, 2])],
                'action'     => 'update',
                'module'     => 'kpi',
                'record_id'  => null,
                'old_data'   => json_encode(['is_finalized' => false]),
                'new_data'   => json_encode(['is_finalized' => true, 'bulan' => $month]),
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => date('Y-m-d 14:30:00', strtotime("-" . ((count($months) - $idx) * 30) . " days")),
            ];
        }

        // 5. Allowance period status changes by Finance
        for ($i = 0; $i < 8; $i++) {
            $daysAgo = rand(5, 90);
            $actions = ['create', 'update'];
            $statuses = ['draft', 'calculated', 'approved', 'paid'];

            $data[] = [
                'id_user'    => 3, // Finance
                'action'     => $actions[array_rand($actions)],
                'module'     => 'allowance',
                'record_id'  => rand(1, 6),
                'old_data'   => json_encode(['status' => $statuses[array_rand(array_slice($statuses, 0, 3))]]),
                'new_data'   => json_encode(['status' => $statuses[array_rand(array_slice($statuses, 1, 3))]]),
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days " . rand(9, 17) . ':00:00')),
            ];
        }

        // 6. Leave approval logs
        for ($i = 0; $i < 15; $i++) {
            $mentorId = rand(4, 8);
            $daysAgo = rand(0, 45);
            $action = (rand(1, 100) <= 75) ? 'approve' : 'update';

            $data[] = [
                'id_user'    => $mentorId,
                'action'     => $action,
                'module'     => 'leave',
                'record_id'  => rand(1, 100),
                'old_data'   => json_encode(['status_approval' => 'pending']),
                'new_data'   => json_encode(['status_approval' => ($action === 'approve') ? 'approved' : 'rejected']),
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
            ];
        }

        // 7. Settings update logs by Admin
        for ($i = 0; $i < 5; $i++) {
            $daysAgo = rand(10, 90);
            $settingKeys = ['attendance_radius', 'late_tolerance_minutes', 'allowance_rate_per_day', 'work_start_time', 'work_end_time'];
            $key = $settingKeys[array_rand($settingKeys)];

            $data[] = [
                'id_user'    => 1,
                'action'     => 'update',
                'module'     => 'settings',
                'record_id'  => rand(1, 19),
                'old_data'   => json_encode(['setting_key' => $key, 'setting_value' => 'old_value']),
                'new_data'   => json_encode(['setting_key' => $key, 'setting_value' => 'new_value']),
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days 10:00:00")),
            ];
        }

        // 8. User profile update logs
        for ($i = 0; $i < 10; $i++) {
            $userId = rand(9, 43);
            $daysAgo = rand(5, 60);

            $data[] = [
                'id_user'    => $userId,
                'action'     => 'update',
                'module'     => 'profile',
                'record_id'  => $userId,
                'old_data'   => json_encode(['alamat' => 'Jakarta Area']),
                'new_data'   => json_encode(['alamat' => 'Jakarta Selatan, Kec. Kebayoran Baru']),
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
            ];
        }

        // Sort all by created_at descending
        usort($data, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));

        if (!empty($data)) {
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                $this->db->table('audit_logs')->insertBatch($chunk);
            }
        }

        echo "   ✅ Created " . count($data) . " audit log entries across " . count(array_unique(array_column($data, 'module'))) . " modules\n";
    }
}
