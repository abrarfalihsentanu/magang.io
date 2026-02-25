<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\SettingModel;
use CodeIgniter\HTTP\ResponseInterface;

class AttendanceController extends BaseController
{
    protected $attendanceModel;
    protected $settingModel;
    protected $db;

    public function __construct()
    {
        $this->attendanceModel = new AttendanceModel();
        $this->settingModel = new SettingModel();
        $this->db = \Config\Database::connect();
        helper(['form', 'filesystem']);
        date_default_timezone_set('Asia/Jakarta');
    }

    // ========================================
    // HELPER: Get Current DateTime in WIB
    // ========================================
    private function getCurrentDateTime($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    private function getCurrentDate($format = 'Y-m-d')
    {
        return date($format);
    }

    private function getCurrentTime($format = 'H:i:s')
    {
        return date($format);
    }

    // ========================================
    // ATTENDANCE HISTORY (INTERN VIEW)
    // ========================================
    public function index()
    {
        $userId = session()->get('user_id');
        $month = $this->request->getGet('month') ?? $this->getCurrentDate('Y-m');

        $attendances = $this->attendanceModel
            ->where('id_user', $userId)
            ->where("DATE_FORMAT(tanggal, '%Y-%m')", $month)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        $summary = $this->getAttendanceSummary($userId, $month);

        $data = [
            'title' => 'Riwayat Absensi Saya',
            'attendances' => $attendances,
            'summary' => $summary,
            'selected_month' => $month,
            'today' => $this->getCurrentDate()
        ];

        return view('attendance/index', $data);
    }

    // ========================================
    // CHECK IN PAGE
    // ========================================
    public function checkin()
    {
        $userId = session()->get('user_id');
        $today = $this->getCurrentDate();

        $todayAttendance = $this->attendanceModel
            ->where('id_user', $userId)
            ->where('tanggal', $today)
            ->first();

        $settings = $this->getAttendanceSettings();

        $data = [
            'title' => 'Check In / Check Out',
            'today_attendance' => $todayAttendance,
            'settings' => $settings,
            'today' => $today,
            'current_time' => $this->getCurrentTime()
        ];

        return view('attendance/checkin', $data);
    }

    // ========================================
    // PROCESS CHECK IN (AJAX)
    // ========================================
    public function processCheckin()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $userId = session()->get('user_id');
        $today = $this->getCurrentDate();

        $existing = $this->attendanceModel
            ->where('id_user', $userId)
            ->where('tanggal', $today)
            ->first();

        if ($existing && $existing['jam_masuk']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini'
            ]);
        }

        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        if (!$latitude || !$longitude) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Lokasi GPS tidak terdeteksi. Pastikan Anda mengizinkan akses lokasi.'
            ]);
        }

        $settings = $this->getAttendanceSettings();
        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            $settings['office_lat'],
            $settings['office_lng']
        );

        if ($distance > $settings['max_radius']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Anda berada {$distance}m dari kantor. Maksimal radius {$settings['max_radius']}m",
                'distance' => $distance
            ]);
        }

        $photoName = null;
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = 'checkin_' . $userId . '_' . time() . '.' . $photo->getExtension();
            $photo->move(WRITEPATH . 'uploads/attendance', $photoName);
        }

        $jamMasuk = $this->getCurrentTime();
        $jamMasukSetting = $settings['jam_masuk'];
        $status = ($jamMasuk <= $jamMasukSetting) ? 'hadir' : 'terlambat';

        $data = [
            'id_user' => $userId,
            'tanggal' => $today,
            'jam_masuk' => $jamMasuk,
            'latitude_masuk' => $latitude,
            'longitude_masuk' => $longitude,
            'distance_masuk' => $distance,
            'foto_masuk' => $photoName,
            'status' => $status,
            'is_manual' => 0
        ];

        if ($existing) {
            $this->attendanceModel->update($existing['id_attendance'], $data);
        } else {
            $this->attendanceModel->insert($data);
        }

        $this->logAudit($userId, 'checkin', 'attendance', null, "Check-in at {$jamMasuk}, status: {$status}");

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'data' => [
                'status' => $status,
                'jam_masuk' => $jamMasuk,
                'distance' => $distance
            ]
        ]);
    }

    // ========================================
    // PROCESS CHECK OUT (AJAX)
    // ========================================
    public function processCheckout()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $userId = session()->get('user_id');
        $today = $this->getCurrentDate();

        $attendance = $this->attendanceModel
            ->where('id_user', $userId)
            ->where('tanggal', $today)
            ->first();

        if (!$attendance || !$attendance['jam_masuk']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini'
            ]);
        }

        if ($attendance['jam_keluar']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini'
            ]);
        }

        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        if (!$latitude || !$longitude) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Lokasi GPS tidak terdeteksi'
            ]);
        }

        $settings = $this->getAttendanceSettings();
        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            $settings['office_lat'],
            $settings['office_lng']
        );

        if ($distance > $settings['max_radius']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Anda berada {$distance}m dari kantor. Maksimal radius {$settings['max_radius']}m",
                'distance' => $distance
            ]);
        }

        $photoName = null;
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = 'checkout_' . $userId . '_' . time() . '.' . $photo->getExtension();
            $photo->move(WRITEPATH . 'uploads/attendance', $photoName);
        }

        $jamKeluar = $this->getCurrentTime();
        $updateData = [
            'jam_keluar' => $jamKeluar,
            'latitude_keluar' => $latitude,
            'longitude_keluar' => $longitude,
            'distance_keluar' => $distance,
            'foto_keluar' => $photoName
        ];

        $this->attendanceModel->update($attendance['id_attendance'], $updateData);

        $jamMasuk = new \DateTime($attendance['jam_masuk']);
        $jamKeluarObj = new \DateTime($jamKeluar);
        $diff = $jamMasuk->diff($jamKeluarObj);
        $totalJam = $diff->h . ' jam ' . $diff->i . ' menit';

        $this->logAudit($userId, 'checkout', 'attendance', $attendance['id_attendance'], "Check-out at {$jamKeluar}");

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Check-out berhasil!',
            'data' => [
                'jam_keluar' => $jamKeluar,
                'total_jam' => $totalJam,
                'distance' => $distance
            ]
        ]);
    }

    // ========================================
    // VIEW ALL ATTENDANCE (ADMIN/HR/MENTOR)
    // ✅ FIXED: Mentor hanya lihat mentee-nya
    // ========================================
    public function viewAll()
    {
        $perPage = (int) ($this->request->getGet('perPage') ?? 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $currentPage = $this->request->getGet('page') ?? 1;

        $month = $this->request->getGet('month') ?? $this->getCurrentDate('Y-m');
        $divisiId = $this->request->getGet('divisi') ?? null;
        $status = $this->request->getGet('status') ?? null;
        $search = $this->request->getGet('search') ?? null;

        $role = session()->get('kode_role');
        $userId = session()->get('user_id');

        $filters = [
            'month' => $month,
            'divisi' => $divisiId,
            'status' => $status,
            'search' => $search,
            'role' => $role,
            'mentor_id' => ($role === 'mentor') ? $userId : null
        ];

        $attendances = $this->attendanceModel->getAttendancePaginated($filters, $perPage);
        $pager = $this->attendanceModel->pager;

        $divisiModel = new \App\Models\DivisiModel();
        $divisiList = $divisiModel->where('is_active', 1)->findAll();

        $stats = $this->getOverallStatistics($month, $role, $userId);

        $data = [
            'title' => 'Semua Absensi',
            'attendances' => $attendances,
            'divisi_list' => $divisiList,
            'stats' => $stats,
            'selected_month' => $month,
            'selected_divisi' => $divisiId,
            'selected_status' => $status,
            'search' => $search,
            'pager' => $pager,
            'total' => $pager->getTotal(),
            'perPage' => $perPage,
            'currentPage' => $currentPage
        ];

        return view('attendance/all', $data);
    }

    // ========================================
    // CORRECTION REQUEST (INTERN)
    // ========================================
    public function correction()
    {
        $userId = session()->get('user_id');

        $corrections = $this->db->table('attendance_corrections as ac')
            ->select('ac.*, a.jam_masuk as old_jam_masuk, a.jam_keluar as old_jam_keluar, 
                     approver.nama_lengkap as approver_name')
            ->join('attendances as a', 'a.id_attendance = ac.id_attendance', 'left')
            ->join('users as approver', 'approver.id_user = ac.approved_by', 'left')
            ->where('ac.id_user', $userId)
            ->orderBy('ac.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Koreksi Absensi',
            'corrections' => $corrections
        ];

        return view('attendance/correction', $data);
    }

    // ========================================
    // SUBMIT CORRECTION (AJAX)
    // ========================================
    public function submitCorrection()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $userId = session()->get('user_id');

        $rules = [
            'tanggal_koreksi' => 'required|valid_date',
            'jenis_koreksi' => 'required|in_list[masuk,keluar,both]',
            'alasan' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $tanggalKoreksi = $this->request->getPost('tanggal_koreksi');
        $attendance = $this->attendanceModel
            ->where('id_user', $userId)
            ->where('tanggal', $tanggalKoreksi)
            ->first();

        $buktiName = null;
        $bukti = $this->request->getFile('bukti_foto');

        if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
            $buktiName = 'correction_' . $userId . '_' . time() . '.' . $bukti->getExtension();
            $bukti->move(WRITEPATH . 'uploads/corrections', $buktiName);
        }

        $data = [
            'id_attendance' => $attendance ? $attendance['id_attendance'] : null,
            'id_user' => $userId,
            'tanggal_koreksi' => $tanggalKoreksi,
            'jenis_koreksi' => $this->request->getPost('jenis_koreksi'),
            'jam_masuk_baru' => $this->request->getPost('jam_masuk_baru'),
            'jam_keluar_baru' => $this->request->getPost('jam_keluar_baru'),
            'alasan' => $this->request->getPost('alasan'),
            'bukti_foto' => $buktiName,
            'status_approval' => 'pending'
        ];

        if (!$this->db->table('attendance_corrections')->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengajukan koreksi'
            ]);
        }

        // Kirim notifikasi ke admin dan HR
        $adminHrs = $this->db->table('users u')
            ->select('u.id_user')
            ->join('roles r', 'r.id_role = u.id_role')
            ->whereIn('r.kode_role', ['admin', 'hr'])
            ->where('u.status !=', 'archived')
            ->get()->getResultArray();
        $adminHrIds = array_column($adminHrs, 'id_user');
        (new \App\Libraries\NotificationService())->correctionSubmitted(
            $adminHrIds,
            session()->get('nama_lengkap') ?? 'Pemagang',
            $tanggalKoreksi
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Permintaan koreksi berhasil diajukan'
        ]);
    }

    // ========================================
    // CORRECTION APPROVAL (ADMIN/HR/MENTOR)
    // ✅ FIXED: Mentor hanya lihat mentee-nya
    // ========================================
    public function correctionApproval()
    {
        $role = session()->get('kode_role');
        $userId = session()->get('user_id');

        $builder = $this->db->table('attendance_corrections as ac')
            ->select('ac.*, u.nama_lengkap, u.nik, a.jam_masuk as old_jam_masuk, a.jam_keluar as old_jam_keluar')
            ->join('users as u', 'u.id_user = ac.id_user')
            ->join('attendances as a', 'a.id_attendance = ac.id_attendance', 'left')
            ->where('ac.status_approval', 'pending');

        // ✅ MENTOR: Hanya lihat mentee yang ia mentori
        if ($role === 'mentor') {
            $builder->join('interns as i', 'i.id_user = ac.id_user')
                ->where('i.id_mentor', $userId);
        }

        $corrections = $builder->orderBy('ac.created_at', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Approval Koreksi Absensi',
            'corrections' => $corrections
        ];

        return view('attendance/correction_approval', $data);
    }

    // ========================================
    // APPROVE CORRECTION (AJAX)
    // ✅ FIXED: Validasi mentor-mentee
    // ========================================
    public function approveCorrection($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $correction = $this->db->table('attendance_corrections')->where('id_correction', $id)->get()->getRowArray();

        if (!$correction) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data koreksi tidak ditemukan'
            ]);
        }

        // ✅ VALIDASI: Mentor hanya bisa approve mentee-nya
        $role = session()->get('kode_role');
        $currentUserId = session()->get('user_id');

        if ($role === 'mentor') {
            $isMentee = $this->db->table('interns')
                ->where('id_user', $correction['id_user'])
                ->where('id_mentor', $currentUserId)
                ->countAllResults() > 0;

            if (!$isMentee) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk approve koreksi ini'
                ]);
            }
        }

        $this->db->transStart();

        try {
            $this->db->table('attendance_corrections')->where('id_correction', $id)->update([
                'status_approval' => 'approved',
                'approved_by' => $currentUserId,
                'approved_at' => $this->getCurrentDateTime(),
                'catatan_approval' => $this->request->getPost('catatan')
            ]);

            if ($correction['id_attendance']) {
                $updateData = [];

                if ($correction['jenis_koreksi'] === 'masuk' || $correction['jenis_koreksi'] === 'both') {
                    $updateData['jam_masuk'] = $correction['jam_masuk_baru'];
                }

                if ($correction['jenis_koreksi'] === 'keluar' || $correction['jenis_koreksi'] === 'both') {
                    $updateData['jam_keluar'] = $correction['jam_keluar_baru'];
                }

                if (!empty($updateData)) {
                    $this->attendanceModel->update($correction['id_attendance'], $updateData);
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            // Kirim notifikasi ke pemagang
            (new \App\Libraries\NotificationService())->correctionApproved(
                (int) $correction['id_user'],
                $correction['tanggal_koreksi']
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Koreksi berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // ========================================
    // REJECT CORRECTION (AJAX)
    // ✅ FIXED: Validasi mentor-mentee
    // ========================================
    public function rejectCorrection($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $correction = $this->db->table('attendance_corrections')->where('id_correction', $id)->get()->getRowArray();

        if (!$correction) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data koreksi tidak ditemukan'
            ]);
        }

        // ✅ VALIDASI: Mentor hanya bisa reject mentee-nya
        $role = session()->get('kode_role');
        $currentUserId = session()->get('user_id');

        if ($role === 'mentor') {
            $isMentee = $this->db->table('interns')
                ->where('id_user', $correction['id_user'])
                ->where('id_mentor', $currentUserId)
                ->countAllResults() > 0;

            if (!$isMentee) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk reject koreksi ini'
                ]);
            }
        }

        $catatan = $this->request->getPost('catatan');

        if (empty($catatan)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Alasan penolakan wajib diisi'
            ]);
        }

        $this->db->table('attendance_corrections')->where('id_correction', $id)->update([
            'status_approval' => 'rejected',
            'approved_by' => $currentUserId,
            'approved_at' => $this->getCurrentDateTime(),
            'catatan_approval' => $catatan
        ]);

        // Kirim notifikasi ke pemagang
        (new \App\Libraries\NotificationService())->correctionRejected(
            (int) $correction['id_user'],
            $correction['tanggal_koreksi'],
            $catatan
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Koreksi berhasil ditolak'
        ]);
    }

    // ========================================
    // HELPER FUNCTIONS
    // ========================================

    private function getAttendanceSettings()
    {
        $settings = [
            'office_lat' => -6.200000,
            'office_lng' => 106.816666,
            'max_radius' => 100,
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '17:00:00'
        ];

        $officeLatSetting = $this->settingModel->getByKey('office_latitude');
        $officeLngSetting = $this->settingModel->getByKey('office_longitude');
        $radiusSetting = $this->settingModel->getByKey('attendance_max_radius');
        $jamMasukSetting = $this->settingModel->getByKey('jam_masuk');
        $jamKeluarSetting = $this->settingModel->getByKey('jam_keluar');

        if ($officeLatSetting) $settings['office_lat'] = (float) $officeLatSetting['setting_value'];
        if ($officeLngSetting) $settings['office_lng'] = (float) $officeLngSetting['setting_value'];
        if ($radiusSetting) $settings['max_radius'] = (int) $radiusSetting['setting_value'];
        if ($jamMasukSetting) $settings['jam_masuk'] = $jamMasukSetting['setting_value'];
        if ($jamKeluarSetting) $settings['jam_keluar'] = $jamKeluarSetting['setting_value'];

        return $settings;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance);
    }

    private function getAttendanceSummary($userId, $month)
    {
        $builder = $this->attendanceModel
            ->where('id_user', $userId)
            ->where("DATE_FORMAT(tanggal, '%Y-%m')", $month);

        $total = $builder->countAllResults(false);
        $hadir = $builder->whereIn('status', ['hadir', 'terlambat'])->countAllResults(false);
        $izin = $builder->where('status', 'izin')->countAllResults(false);
        $sakit = $builder->where('status', 'sakit')->countAllResults(false);
        $alpha = $builder->where('status', 'alpha')->countAllResults();

        $persentase = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

        return [
            'total' => $total,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha,
            'persentase' => $persentase
        ];
    }

    // ✅ FIXED: Tambah parameter untuk filter mentor
    private function getOverallStatistics($month, $role = null, $userId = null)
    {
        $builder = $this->db->table('attendances as a')
            ->where("DATE_FORMAT(a.tanggal, '%Y-%m')", $month);

        // ✅ Filter untuk mentor
        if ($role === 'mentor' && $userId) {
            $builder->join('interns as i', 'i.id_user = a.id_user')
                ->where('i.id_mentor', $userId);
        }

        $total = $builder->countAllResults(false);
        $hadir = $builder->whereIn('a.status', ['hadir', 'terlambat'])->countAllResults(false);
        $izin = $builder->where('a.status', 'izin')->countAllResults(false);
        $sakit = $builder->where('a.status', 'sakit')->countAllResults(false);
        $alpha = $builder->where('a.status', 'alpha')->countAllResults();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha
        ];
    }

    private function logAudit($userId, $action, $module, $recordId = null, $description = '')
    {
        $this->db->table('audit_logs')->insert([
            'id_user' => $userId,
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => $this->getCurrentDateTime()
        ]);
    }
}
