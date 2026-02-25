<?php

namespace App\Libraries;

use App\Models\NotificationModel;

/**
 * NotificationService
 * 
 * Reusable service untuk mengirim notifikasi dari controller manapun.
 * Gunakan: $notif = new \App\Libraries\NotificationService();
 *
 * Business process triggers:
 *  - Pemagang submit aktivitas  → Notifikasi ke MENTOR
 *  - Mentor approve/reject aktivitas → Notifikasi ke PEMAGANG
 *  - Pemagang submit koreksi absensi → Notifikasi ke ADMIN/HR
 *  - Admin approve/reject koreksi   → Notifikasi ke PEMAGANG
 *  - Pemagang submit cuti/izin/sakit → Notifikasi ke MENTOR/HR
 *  - Admin approve/reject cuti       → Notifikasi ke PEMAGANG
 *  - Finance proses pembayaran uang saku → Notifikasi ke PEMAGANG
 *  - KPI dinilai                     → Notifikasi ke PEMAGANG
 */
class NotificationService
{
    protected NotificationModel $model;

    public function __construct()
    {
        $this->model = new NotificationModel();
    }

    // ============================================
    // CORE SEND METHOD
    // ============================================

    public function send(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): void {
        try {
            $this->model->createNotification($userId, $type, $title, $message, $link);
        } catch (\Throwable $e) {
            // Fail silently — notifikasi tidak boleh mengganggu proses utama
            log_message('error', 'NotificationService::send failed: ' . $e->getMessage());
        }
    }

    // ============================================
    // ACTIVITY NOTIFICATIONS
    // ============================================

    /**
     * Pemagang submit aktivitas → notifikasi ke Mentor
     */
    public function activitySubmitted(int $mentorId, string $internName, string $activityTitle): void
    {
        $this->send(
            $mentorId,
            'activity_submitted',
            'Aktivitas Baru Menunggu Approval',
            "{$internName} mengajukan aktivitas \"{$activityTitle}\" untuk di-review.",
            base_url('activity/approval')
        );
    }

    /**
     * Mentor approve aktivitas → notifikasi ke Pemagang
     */
    public function activityApproved(int $internId, string $activityTitle, string $mentorName): void
    {
        $this->send(
            $internId,
            'activity_approved',
            'Aktivitas Disetujui',
            "Aktivitas \"{$activityTitle}\" Anda telah disetujui oleh {$mentorName}.",
            base_url('activity/my')
        );
    }

    /**
     * Mentor reject aktivitas → notifikasi ke Pemagang
     */
    public function activityRejected(int $internId, string $activityTitle, string $reason = ''): void
    {
        $message = "Aktivitas \"{$activityTitle}\" Anda ditolak.";
        if (!empty($reason)) {
            $message .= " Alasan: {$reason}";
        }

        $this->send(
            $internId,
            'activity_rejected',
            'Aktivitas Ditolak',
            $message,
            base_url('activity/my')
        );
    }

    // ============================================
    // ATTENDANCE CORRECTION NOTIFICATIONS
    // ============================================

    /**
     * Pemagang submit koreksi → notifikasi ke HR/Admin (semua user dengan role admin/hr)
     */
    public function correctionSubmitted(array $adminHrIds, string $internName, string $tanggal): void
    {
        foreach ($adminHrIds as $userId) {
            $this->send(
                (int) $userId,
                'correction_submitted',
                'Koreksi Absensi Menunggu Approval',
                "{$internName} mengajukan koreksi absensi tanggal {$tanggal}.",
                base_url('attendance/correction/approval')
            );
        }
    }

    /**
     * Admin approve koreksi → notifikasi ke Pemagang
     */
    public function correctionApproved(int $internId, string $tanggal): void
    {
        $this->send(
            $internId,
            'correction_approved',
            'Koreksi Absensi Disetujui',
            "Koreksi absensi Anda untuk tanggal {$tanggal} telah disetujui.",
            base_url('attendance')
        );
    }

    /**
     * Admin reject koreksi → notifikasi ke Pemagang
     */
    public function correctionRejected(int $internId, string $tanggal, string $reason = ''): void
    {
        $message = "Koreksi absensi Anda untuk tanggal {$tanggal} ditolak.";
        if (!empty($reason)) {
            $message .= " Alasan: {$reason}";
        }

        $this->send(
            $internId,
            'correction_rejected',
            'Koreksi Absensi Ditolak',
            $message,
            base_url('attendance/correction')
        );
    }

    // ============================================
    // LEAVE (CUTI/IZIN/SAKIT) NOTIFICATIONS
    // ============================================

    /**
     * Pemagang submit cuti/izin → notifikasi ke Mentor & HR
     */
    public function leaveSubmitted(array $approverIds, string $internName, string $jenisCuti, string $tanggal): void
    {
        $jenisLabel = match ($jenisCuti) {
            'cuti'  => 'Cuti',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            default => ucfirst($jenisCuti),
        };

        foreach ($approverIds as $userId) {
            $this->send(
                (int) $userId,
                'leave_submitted',
                "Pengajuan {$jenisLabel} Menunggu Approval",
                "{$internName} mengajukan {$jenisLabel} mulai tanggal {$tanggal}.",
                base_url('leave/approval')
            );
        }
    }

    /**
     * Admin/Mentor approve cuti → notifikasi ke Pemagang
     */
    public function leaveApproved(int $internId, string $jenisCuti, string $tanggalMulai): void
    {
        $jenisLabel = match ($jenisCuti) {
            'cuti'  => 'Cuti',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            default => ucfirst($jenisCuti),
        };

        $this->send(
            $internId,
            'leave_approved',
            "{$jenisLabel} Disetujui",
            "Permohonan {$jenisLabel} Anda mulai tanggal {$tanggalMulai} telah disetujui.",
            base_url('leave/my')
        );
    }

    /**
     * Admin/Mentor reject cuti → notifikasi ke Pemagang
     */
    public function leaveRejected(int $internId, string $jenisCuti, string $reason = ''): void
    {
        $jenisLabel = match ($jenisCuti) {
            'cuti'  => 'Cuti',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            default => ucfirst($jenisCuti),
        };

        $message = "Permohonan {$jenisLabel} Anda ditolak.";
        if (!empty($reason)) {
            $message .= " Alasan: {$reason}";
        }

        $this->send(
            $internId,
            'leave_rejected',
            "{$jenisLabel} Ditolak",
            $message,
            base_url('leave/my')
        );
    }

    // ============================================
    // ALLOWANCE (UANG SAKU) NOTIFICATIONS
    // ============================================

    /**
     * Finance proses pembayaran → notifikasi ke Pemagang
     */
    public function allowancePaid(int $internId, string $periodName, int|float $amount): void
    {
        $formatted = 'Rp ' . number_format($amount, 0, ',', '.');

        $this->send(
            $internId,
            'allowance_paid',
            'Uang Saku Telah Ditransfer',
            "Uang saku periode {$periodName} sebesar {$formatted} telah ditransfer ke rekening Anda.",
            base_url('allowance/my')
        );
    }

    // ============================================
    // KPI NOTIFICATIONS
    // ============================================

    /**
     * Mentor selesai menilai KPI → notifikasi ke Pemagang
     */
    public function kpiAssessed(int $internId, string $bulan, string $tahun): void
    {
        $this->send(
            $internId,
            'kpi_assessed',
            'KPI Bulanan Telah Dinilai',
            "KPI Anda untuk bulan {$bulan} {$tahun} telah selesai dinilai. Cek hasilnya sekarang.",
            base_url('kpi/my')
        );
    }

    /**
     * Hasil KPI periode dihitung → notifikasi ke semua pemagang aktif
     */
    public function kpiPeriodCalculated(array $internIds, string $periodLabel): void
    {
        foreach ($internIds as $userId) {
            $this->send(
                (int) $userId,
                'kpi_calculated',
                'Hasil KPI Periode Tersedia',
                "Hasil KPI periode {$periodLabel} telah dihitung. Lihat peringkat Anda.",
                base_url('kpi/my')
            );
        }
    }
}
