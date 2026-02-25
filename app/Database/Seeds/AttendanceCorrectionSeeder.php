<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AttendanceCorrectionSeeder extends Seeder
{
    public function run()
    {
        echo "   ✏️  Seeding Attendance Corrections...\n";

        // Get some attendance records that could need correction (late or alpha)
        $problematicAttendances = $this->db->table('attendances')
            ->select('id_attendance, id_user, tanggal, jam_masuk, jam_keluar, status')
            ->whereIn('status', ['terlambat', 'alpha'])
            ->orderBy('RAND()')
            ->limit(50) // Only get ~50 to create corrections for
            ->get()->getResultArray();

        if (empty($problematicAttendances)) {
            echo "   ⚠️  No problematic attendance records found.\n";
            return;
        }

        // Get intern-mentor mapping
        $internMentors = $this->db->table('interns')
            ->select('id_user, id_mentor')
            ->where('status_magang', 'active')
            ->get()->getResultArray();

        $mentorMap = [];
        foreach ($internMentors as $im) {
            $mentorMap[$im['id_user']] = $im['id_mentor'];
        }

        $alasanTemplates = [
            'masuk' => [
                'Terjebak kemacetan di jalan, terlambat sampai kantor. Mohon koreksi jam masuk.',
                'Ada kendala transportasi umum, KRL delay 30 menit.',
                'Lupa tap/absen saat masuk, padahal sudah hadir di kantor.',
                'Aplikasi absensi error saat check-in, jam masuk tidak terekam.',
                'Hujan deras menyebabkan banjir di jalan, terlambat hadir.',
            ],
            'keluar' => [
                'Lupa melakukan check-out saat pulang. Mohon koreksi jam keluar.',
                'Aplikasi hang saat clock-out, jam keluar tidak terekam.',
                'Diminta lembur oleh mentor, pulang lebih lambat dari biasa.',
                'Meeting berjalan overtime, lupa absen keluar tepat waktu.',
            ],
            'both' => [
                'HP mengalami error sehingga tidak bisa absen masuk dan keluar. Sudah dikonfirmasi hadir oleh mentor.',
                'Absensi online bermasalah seharian, mohon koreksi kedua jam.',
                'Kehadiran manual karena HP rusak, mohon dikoreksi.',
            ],
        ];

        $data = [];
        $totalCount = 0;

        foreach ($problematicAttendances as $att) {
            // 60% chance to create correction for each problematic attendance
            if (rand(1, 100) > 60) continue;

            $mentorId = $mentorMap[$att['id_user']] ?? null;
            if (!$mentorId) continue;

            // Determine correction type based on issue
            if ($att['status'] === 'terlambat') {
                $jenisKoreksi = 'masuk';
                $jamMasukBaru = '07:' . str_pad(rand(50, 59), 2, '0', STR_PAD_LEFT) . ':00';
                $jamKeluarBaru = null;
            } elseif ($att['status'] === 'alpha' && rand(0, 1)) {
                $jenisKoreksi = 'both';
                $jamMasukBaru = '08:' . str_pad(rand(0, 10), 2, '0', STR_PAD_LEFT) . ':00';
                $jamKeluarBaru = '17:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00';
            } else {
                $jenisKoreksi = 'keluar';
                $jamMasukBaru = null;
                $jamKeluarBaru = '17:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00';
            }

            $alasan = $alasanTemplates[$jenisKoreksi][array_rand($alasanTemplates[$jenisKoreksi])];

            // Determine approval status
            $isOld = strtotime($att['tanggal']) < strtotime('-14 days');
            if ($isOld) {
                $statusChance = rand(1, 100);
                if ($statusChance <= 65) $status = 'approved';
                elseif ($statusChance <= 85) $status = 'rejected';
                else $status = 'pending';
            } else {
                $statusChance = rand(1, 100);
                if ($statusChance <= 30) $status = 'approved';
                elseif ($statusChance <= 50) $status = 'rejected';
                else $status = 'pending';
            }

            $approvedBy = null;
            $approvedAt = null;
            $catatanApproval = null;

            if ($status === 'approved') {
                $approvedBy = $mentorId;
                $approvedAt = date('Y-m-d H:i:s', strtotime($att['tanggal'] . ' +1 day'));
                $catatanApproval = 'Koreksi disetujui. Sudah dikonfirmasi kehadiran yang bersangkutan.';
            } elseif ($status === 'rejected') {
                $approvedBy = $mentorId;
                $approvedAt = date('Y-m-d H:i:s', strtotime($att['tanggal'] . ' +1 day'));
                $rejectionReasons = [
                    'Bukti tidak cukup untuk koreksi ini.',
                    'Alasan tidak sesuai, mohon ajukan ulang dengan bukti yang valid.',
                    'Koreksi ditolak karena tidak ada konfirmasi dari rekan/tim.',
                    'Mohon lampirkan screenshot error/bukti pendukung.',
                ];
                $catatanApproval = $rejectionReasons[array_rand($rejectionReasons)];
            }

            // Some corrections have bukti foto
            $buktiFoto = null;
            if (rand(1, 100) <= 40) {
                $buktiFoto = 'uploads/corrections/bukti_' . $att['id_user'] . '_' . date('Ymd', strtotime($att['tanggal'])) . '.jpg';
            }

            $data[] = [
                'id_attendance'    => $att['id_attendance'],
                'id_user'          => $att['id_user'],
                'tanggal_koreksi'  => $att['tanggal'],
                'jenis_koreksi'    => $jenisKoreksi,
                'jam_masuk_baru'   => $jamMasukBaru,
                'jam_keluar_baru'  => $jamKeluarBaru,
                'alasan'           => $alasan,
                'bukti_foto'       => $buktiFoto,
                'status_approval'  => $status,
                'approved_by'      => $approvedBy,
                'approved_at'      => $approvedAt,
                'catatan_approval' => $catatanApproval,
                'created_at'       => date('Y-m-d H:i:s', strtotime($att['tanggal'] . ' +0 day 18:00:00')),
                'updated_at'       => date('Y-m-d H:i:s', strtotime($att['tanggal'] . ' +1 day')),
            ];
            $totalCount++;
        }

        if (!empty($data)) {
            $this->db->table('attendance_corrections')->insertBatch($data);
        }

        $approved = count(array_filter($data, fn($d) => $d['status_approval'] === 'approved'));
        $pending = count(array_filter($data, fn($d) => $d['status_approval'] === 'pending'));
        $rejected = count(array_filter($data, fn($d) => $d['status_approval'] === 'rejected'));

        echo "   ✅ Created {$totalCount} attendance corrections (Approved: {$approved}, Pending: {$pending}, Rejected: {$rejected})\n";
    }
}
