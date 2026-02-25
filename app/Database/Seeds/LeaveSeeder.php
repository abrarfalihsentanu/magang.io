<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LeaveSeeder extends Seeder
{
    public function run()
    {
        echo "   🏖️  Seeding Leaves (Cuti/Izin/Sakit)...\n";

        // Ambil semua intern users
        $interns = $this->db->table('users')
            ->select('users.id_user, interns.id_mentor')
            ->join('interns', 'interns.id_user = users.id_user')
            ->where('interns.status_magang', 'active')
            ->get()
            ->getResultArray();

        if (empty($interns)) {
            echo "   ⚠️  No active interns found. Run InternSeeder first.\n";
            return;
        }

        // Template alasan berdasarkan jenis
        $alasanTemplates = [
            'cuti' => [
                'Menghadiri acara keluarga (pernikahan saudara)',
                'Acara wisuda keluarga',
                'Keperluan keluarga mendesak',
                'Mengurus keperluan administrasi kampus',
                'Menghadiri acara penting kampus',
                'Liburan keluarga yang sudah direncanakan',
                'Mengurus dokumen penting (KTP/SIM)',
            ],
            'izin' => [
                'Menghadiri sidang skripsi/thesis',
                'Konsultasi dengan dosen pembimbing',
                'Mengikuti kegiatan kampus wajib',
                'Mengurus administrasi beasiswa',
                'Menghadiri seminar/workshop kampus',
                'Keperluan mendadak yang tidak bisa ditunda',
                'Mengurus perpanjangan visa/dokumen',
            ],
            'sakit' => [
                'Demam dan flu, perlu istirahat sesuai anjuran dokter',
                'Sakit perut/maag, memerlukan istirahat',
                'Migrain/sakit kepala yang cukup parah',
                'Tidak enak badan dan perlu istirahat',
                'Kontrol kesehatan rutin ke dokter',
                'Sakit gigi, perlu ke dokter gigi',
                'Alergi yang memerlukan istirahat',
            ],
        ];

        $data = [];
        $totalCount = 0;

        // Generate leaves untuk periode Dec 2025 - Feb 2026
        foreach ($interns as $intern) {
            // Setiap intern punya 0-3 leaves selama 3 bulan
            $leaveCount = rand(0, 3);

            for ($i = 0; $i < $leaveCount; $i++) {
                // Random jenis leave (sakit lebih sering)
                $jenisChance = rand(1, 100);
                if ($jenisChance <= 50) {
                    $jenisCuti = 'sakit';
                } elseif ($jenisChance <= 80) {
                    $jenisCuti = 'izin';
                } else {
                    $jenisCuti = 'cuti';
                }

                // Random tanggal dalam periode Dec 2025 - Feb 2026
                $startTimestamp = strtotime('2025-12-01');
                $endTimestamp = strtotime('2026-02-20');
                $randomDay = rand($startTimestamp, $endTimestamp);

                // Skip weekend
                while (date('N', $randomDay) >= 6) {
                    $randomDay = strtotime('+1 day', $randomDay);
                }

                $tanggalMulai = date('Y-m-d', $randomDay);

                // Durasi berdasarkan jenis
                if ($jenisCuti === 'sakit') {
                    $jumlahHari = rand(1, 3);
                } elseif ($jenisCuti === 'izin') {
                    $jumlahHari = rand(1, 2);
                } else {
                    $jumlahHari = rand(1, 5);
                }

                $tanggalSelesai = date('Y-m-d', strtotime("+{$jumlahHari} days - 1 day", strtotime($tanggalMulai)));

                // Alasan
                $alasan = $alasanTemplates[$jenisCuti][array_rand($alasanTemplates[$jenisCuti])];

                // Status approval (data lama lebih banyak approved)
                $isOldData = strtotime($tanggalMulai) < strtotime('-14 days');
                if ($isOldData) {
                    $statusChance = rand(1, 100);
                    if ($statusChance <= 75) {
                        $status = 'approved';
                    } elseif ($statusChance <= 90) {
                        $status = 'rejected';
                    } else {
                        $status = 'pending';
                    }
                } else {
                    $statusChance = rand(1, 100);
                    if ($statusChance <= 50) {
                        $status = 'approved';
                    } elseif ($statusChance <= 70) {
                        $status = 'pending';
                    } else {
                        $status = 'rejected';
                    }
                }

                $approvedBy = null;
                $approvedAt = null;
                $catatanApproval = null;

                if ($status === 'approved') {
                    $approvedBy = $intern['id_mentor'];
                    $approvedAt = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($tanggalMulai)));
                    $catatanApproval = 'Disetujui';
                } elseif ($status === 'rejected') {
                    $approvedBy = $intern['id_mentor'];
                    $approvedAt = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($tanggalMulai)));
                    $catatanApproval = $this->getRandomRejectionReason();
                }

                // Dokumen pendukung (untuk sakit biasanya ada surat dokter)
                $dokumenPendukung = null;
                if ($jenisCuti === 'sakit' && rand(1, 100) <= 70) {
                    $dokumenPendukung = 'uploads/leaves/surat_dokter_' . $intern['id_user'] . '_' . date('Ymd', $randomDay) . '.pdf';
                }

                $data[] = [
                    'id_user' => $intern['id_user'],
                    'jenis_cuti' => $jenisCuti,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'jumlah_hari' => $jumlahHari,
                    'alasan' => $alasan,
                    'dokumen_pendukung' => $dokumenPendukung,
                    'status_approval' => $status,
                    'approved_by' => $approvedBy,
                    'approved_at' => $approvedAt,
                    'catatan_approval' => $catatanApproval,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 days', strtotime($tanggalMulai))),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day', strtotime($tanggalMulai))),
                ];

                $totalCount++;
            }
        }

        // Insert data
        if (!empty($data)) {
            $this->db->table('leaves')->insertBatch($data);
        }

        // Summary
        $approved = count(array_filter($data, fn($l) => $l['status_approval'] === 'approved'));
        $pending = count(array_filter($data, fn($l) => $l['status_approval'] === 'pending'));
        $rejected = count(array_filter($data, fn($l) => $l['status_approval'] === 'rejected'));

        echo "   ✅ Created {$totalCount} leave requests (Approved: {$approved}, Pending: {$pending}, Rejected: {$rejected})\n";
    }

    private function getRandomRejectionReason()
    {
        $reasons = [
            'Pengajuan tidak memenuhi syarat H-3 untuk cuti.',
            'Mohon ajukan dengan waktu yang lebih awal.',
            'Jadwal tersebut bertepatan dengan deadline project penting.',
            'Dokumen pendukung tidak lengkap.',
            'Kuota izin bulan ini sudah habis.',
        ];

        return $reasons[array_rand($reasons)];
    }
}
