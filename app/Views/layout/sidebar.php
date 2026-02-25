<?php
// Ambil role_code dari session
$role = session()->get('role_code') ?? 'guest';
$userName = session()->get('nama_lengkap') ?? 'Guest';
$userPhoto = session()->get('foto') ?? 'default-avatar.png';
$userId = session()->get('user_id');

// ✅ GET DYNAMIC COUNTS
$db = \Config\Database::connect();

// Count pending corrections (for Admin/HR/Mentor)
$pendingCorrections = 0;
if (in_array($role, ['admin', 'hr', 'mentor'])) {
    $builder = $db->table('attendance_corrections as ac')
        ->where('ac.status_approval', 'pending');

    // Mentor only sees their mentee's corrections
    if ($role === 'mentor') {
        $builder->join('interns as i', 'i.id_user = ac.id_user')
            ->where('i.id_mentor', $userId);
    }

    $pendingCorrections = $builder->countAllResults();
}

// Count pending leaves (for Admin/HR/Mentor)
$pendingLeaves = 0;
if (in_array($role, ['admin', 'hr', 'mentor'])) {
    $builder = $db->table('leaves as l')
        ->where('l.status_approval', 'pending');

    // Mentor only sees their mentee's leaves
    if ($role === 'mentor') {
        $builder->join('interns as i', 'i.id_user = l.id_user')
            ->where('i.id_mentor', $userId);
    }

    $pendingLeaves = $builder->countAllResults();
}

// Count pending activities (for Mentor)
$pendingActivities = 0;
if ($role === 'mentor') {
    $pendingActivities = $db->table('daily_activities as da')
        ->join('interns as i', 'i.id_user = da.id_user')
        ->where('da.status_approval', 'submitted')
        ->where('i.id_mentor', $userId)
        ->countAllResults();
}

// Count pending projects (for Mentor)
$pendingProjects = 0;
if ($role === 'mentor') {
    $pendingProjects = $db->table('weekly_projects as wp')
        ->join('interns as i', 'i.id_user = wp.id_user')
        ->where('wp.status_submission', 'submitted')
        ->where('i.id_mentor', $userId)
        ->countAllResults();
}

// Count pending allowance payments (for Finance)
$pendingPayments = 0;
if ($role === 'finance') {
    $pendingPayments = $db->table('allowances')
        ->where('status_pembayaran', 'approved')
        ->countAllResults();
}
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- Brand -->
    <div class="app-brand demo">
        <a href="<?= base_url('/') ?>" class="app-brand-link">
            <span class="app-brand-logo demo me-1">
                <span class="text-primary">
                    <!-- Bank Muamalat Logo Icon -->
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7V10H22V7L12 2Z" fill="currentColor" />
                        <path d="M4 11V20H8V11H4Z" fill="currentColor" opacity="0.6" />
                        <path d="M10 11V20H14V11H10Z" fill="currentColor" opacity="0.8" />
                        <path d="M16 11V20H20V11H16Z" fill="currentColor" opacity="0.6" />
                        <path d="M2 21H22V22H2V21Z" fill="currentColor" />
                    </svg>
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-semibold ms-2">MIP</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- DASHBOARD (All Roles) -->
        <li class="menu-item <?= (uri_string() == '' || uri_string() == 'dashboard') ? 'active' : '' ?>">
            <a href="<?= base_url('dashboard') ?>" class="menu-link">
                <i class="menu-icon icon-base ri-dashboard-3-line"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- ============================================ -->
        <!-- MANAJEMEN USER (Admin & HR Only) -->
        <!-- ============================================ -->
        <?php if (in_array($role, ['admin', 'hr'])): ?>
            <li class="menu-header mt-4">
                <span class="menu-header-text">Manajemen User</span>
            </li>

            <?php if ($role == 'admin'): ?>
                <li class="menu-item <?= (strpos(uri_string(), 'role') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('role') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-shield-user-line"></i>
                        <div>Data Role</div>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Data User - Accessible by both Admin & HR -->
            <li class="menu-item <?= (strpos(uri_string(), 'user') !== false && strpos(uri_string(), 'intern') === false) ? 'active' : '' ?>">
                <a href="<?= base_url('user') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-user-settings-line"></i>
                    <div>Data User</div>
                </a>
            </li>

            <?php if ($role == 'admin'): ?>
                <li class="menu-item <?= (strpos(uri_string(), 'divisi') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('divisi') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-building-4-line"></i>
                        <div>Data Divisi</div>
                    </a>
                </li>
            <?php endif; ?>

            <li class="menu-item <?= (strpos(uri_string(), 'intern') !== false && strpos(uri_string(), 'best-interns') === false) ? 'active' : '' ?>">
                <a href="<?= base_url('intern') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-user-star-line"></i>
                    <div>Data Pemagang</div>
                </a>
            </li>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- ABSENSI -->
        <!-- ============================================ -->
        <li class="menu-header mt-4">
            <span class="menu-header-text">Absensi</span>
        </li>

        <?php if ($role == 'intern'): ?>
            <!-- Pemagang View -->
            <li class="menu-item <?= (uri_string() == 'attendance/checkin') ? 'active' : '' ?>">
                <a href="<?= base_url('attendance/checkin') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-map-pin-user-line"></i>
                    <div>Check-In / Check-Out</div>
                </a>
            </li>
            <li class="menu-item <?= (uri_string() == 'attendance' || uri_string() == 'attendance/index') ? 'active' : '' ?>">
                <a href="<?= base_url('attendance') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-calendar-check-line"></i>
                    <div>Rekap Absensi</div>
                </a>
            </li>
            <li class="menu-item <?= (strpos(uri_string(), 'attendance/correction') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('attendance/correction') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-edit-box-line"></i>
                    <div>Koreksi Absensi</div>
                </a>
            </li>
            <li class="menu-item <?= (strpos(uri_string(), 'leave') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('leave/my') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-calendar-event-line"></i>
                    <div>Cuti/Izin/Sakit</div>
                </a>
            </li>

        <?php else: ?>
            <!-- Admin/Mentor/HR/Finance View -->
            <li class="menu-item <?= (uri_string() == 'attendance/all') ? 'active' : '' ?>">
                <a href="<?= base_url('attendance/all') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-calendar-check-line"></i>
                    <div>Data Absensi</div>
                </a>
            </li>

            <?php if (in_array($role, ['admin', 'hr', 'mentor'])): ?>
                <li class="menu-item <?= (strpos(uri_string(), 'attendance/correction/approval') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('attendance/correction/approval') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-checkbox-circle-line"></i>
                        <div>Approval Koreksi</div>
                        <?php if ($pendingCorrections > 0): ?>
                            <span class="badge rounded-pill bg-label-warning ms-auto"><?= $pendingCorrections ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="menu-item <?= (strpos(uri_string(), 'leave/approval') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('leave/approval') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-calendar-check-fill"></i>
                        <div>Approval Cuti/Izin</div>
                        <?php if ($pendingLeaves > 0): ?>
                            <span class="badge rounded-pill bg-label-warning ms-auto"><?= $pendingLeaves ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- AKTIVITAS & PROJECT (Except Finance) -->
        <!-- ============================================ -->
        <?php if ($role != 'finance'): ?>
            <li class="menu-header mt-4">
                <span class="menu-header-text">Aktivitas & Project</span>
            </li>

            <?php if ($role == 'intern'): ?>
                <!-- Pemagang View -->
                <li class="menu-item <?= (strpos(uri_string(), 'activity') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('activity/my') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-file-list-3-line"></i>
                        <div>Aktivitas Harian</div>
                    </a>
                </li>
                <li class="menu-item <?= (strpos(uri_string(), 'project') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('project/my') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-folder-3-line"></i>
                        <div>Project Mingguan</div>
                    </a>
                </li>

            <?php elseif ($role == 'mentor'): ?>
                <!-- Mentor View -->
                <li class="menu-item <?= (strpos(uri_string(), 'activity/approval') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('activity/approval') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-checkbox-circle-line"></i>
                        <div>Approval Aktivitas</div>
                        <?php if ($pendingActivities > 0): ?>
                            <span class="badge rounded-pill bg-label-info ms-auto"><?= $pendingActivities ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="menu-item <?= (strpos(uri_string(), 'project/assessment') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('project/assessment') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-star-line"></i>
                        <div>Assessment Project</div>
                        <?php if ($pendingProjects > 0): ?>
                            <span class="badge rounded-pill bg-label-info ms-auto"><?= $pendingProjects ?></span>
                        <?php endif; ?>
                    </a>
                </li>

            <?php else: ?>
                <!-- Admin/HR View -->
                <li class="menu-item <?= (uri_string() == 'activity' || uri_string() == 'activity/index') ? 'active' : '' ?>">
                    <a href="<?= base_url('activity') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-file-list-3-line"></i>
                        <div>Data Aktivitas</div>
                    </a>
                </li>
                <li class="menu-item <?= (uri_string() == 'project' || uri_string() == 'project/index') ? 'active' : '' ?>">
                    <a href="<?= base_url('project') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-folder-3-line"></i>
                        <div>Data Project</div>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- KPI (Except Finance) -->
        <!-- ============================================ -->
        <?php if ($role != 'finance'): ?>
            <li class="menu-header mt-4">
                <span class="menu-header-text">KPI Performance</span>
            </li>

            <?php if ($role == 'admin'): ?>
                <li class="menu-item <?= (strpos(uri_string(), 'kpi/indicators') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('kpi/indicators') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-list-check-2"></i>
                        <div>Master Indikator KPI</div>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($role == 'intern'): ?>
                <!-- Pemagang View -->
                <li class="menu-item <?= (uri_string() == 'kpi/my' || strpos(uri_string(), 'kpi/my/') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('kpi/my') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-line-chart-line"></i>
                        <div>KPI Saya</div>
                    </a>
                </li>
                <li class="menu-item <?= (strpos(uri_string(), 'kpi/ranking') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('kpi/ranking') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-trophy-line"></i>
                        <div>Ranking Pemagang</div>
                    </a>
                </li>

            <?php else: ?>
                <!-- Admin/HR/Mentor management items -->

                <?php if (in_array($role, ['admin', 'hr'])): ?>
                    <li class="menu-item <?= (strpos(uri_string(), 'kpi/calculation') !== false) ? 'active' : '' ?>">
                        <a href="<?= base_url('kpi/calculation') ?>" class="menu-link">
                            <i class="menu-icon icon-base ri-calculator-line"></i>
                            <div>Kalkulasi KPI</div>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="menu-item <?= (strpos(uri_string(), 'kpi/assessment') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('kpi/assessment') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-edit-2-line"></i>
                        <div>Penilaian Manual</div>
                    </a>
                </li>

                <li class="menu-item <?= (strpos(uri_string(), 'kpi/monthly') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('kpi/monthly') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-calendar-2-line"></i>
                        <div>KPI Bulanan</div>
                    </a>
                </li>

                <?php if (in_array($role, ['admin', 'hr'])): ?>
                    <li class="menu-item <?= (strpos(uri_string(), 'kpi/period') !== false && strpos(uri_string(), 'best') === false) ? 'active' : '' ?>">
                        <a href="<?= base_url('kpi/period') ?>" class="menu-link">
                            <i class="menu-icon icon-base ri-calendar-check-line"></i>
                            <div>KPI Periode</div>
                        </a>
                    </li>
                    <li class="menu-item <?= (strpos(uri_string(), 'best-interns') !== false) ? 'active' : '' ?>">
                        <a href="<?= base_url('kpi/period/best-interns') ?>" class="menu-link">
                            <i class="menu-icon icon-base ri-medal-line"></i>
                            <div>Pemagang Terbaik</div>
                        </a>
                    </li>
                    <li class="menu-item <?= (strpos(uri_string(), 'kpi/analytics') !== false) ? 'active' : '' ?>">
                        <a href="<?= base_url('kpi/analytics') ?>" class="menu-link">
                            <i class="menu-icon icon-base ri-bar-chart-box-line"></i>
                            <div>Analitik KPI</div>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="menu-item <?= (strpos(uri_string(), 'kpi/ranking') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('kpi/ranking') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-trophy-line"></i>
                        <div>Ranking Pemagang</div>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- UANG SAKU -->
        <!-- ============================================ -->
        <li class="menu-header mt-4">
            <span class="menu-header-text">Uang Saku</span>
        </li>

        <?php if ($role == 'intern'): ?>
            <!-- Pemagang View -->
            <li class="menu-item <?= (uri_string() == 'allowance/my') ? 'active' : '' ?>">
                <a href="<?= base_url('allowance/my') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-money-dollar-circle-line"></i>
                    <div>Uang Saku Saya</div>
                </a>
            </li>

        <?php elseif (in_array($role, ['admin', 'hr', 'finance'])): ?>
            <!-- Admin/HR/Finance View -->
            <li class="menu-item <?= (strpos(uri_string(), 'allowance/period') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('allowance/period') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-calendar-line"></i>
                    <div>Periode Pembayaran</div>
                </a>
            </li>
            <li class="menu-item <?= (uri_string() == 'allowance' || uri_string() == 'allowance/index') ? 'active' : '' ?>">
                <a href="<?= base_url('allowance') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-money-dollar-circle-line"></i>
                    <div>Data Uang Saku</div>
                </a>
            </li>

            <?php if ($role == 'finance'): ?>
                <li class="menu-item <?= (strpos(uri_string(), 'allowance/payment') !== false) ? 'active' : '' ?>">
                    <a href="<?= base_url('allowance/payment') ?>" class="menu-link">
                        <i class="menu-icon icon-base ri-bank-card-line"></i>
                        <div>Proses Pembayaran</div>
                        <?php if ($pendingPayments > 0): ?>
                            <span class="badge rounded-pill bg-label-success ms-auto"><?= $pendingPayments ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- LAPORAN (Except Intern) -->
        <!-- ============================================ -->
        <?php if ($role != 'intern'): ?>
            <li class="menu-header mt-4">
                <span class="menu-header-text">Laporan</span>
            </li>
            <li class="menu-item <?= (strpos(uri_string(), 'report') !== false) ? 'active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri-file-chart-line"></i>
                    <div>Laporan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item <?= (strpos(uri_string(), 'report/attendance') !== false) ? 'active' : '' ?>">
                        <a href="<?= base_url('report/attendance') ?>" class="menu-link">
                            <div>Laporan Absensi</div>
                        </a>
                    </li>
                    <?php if ($role != 'finance'): ?>
                        <li class="menu-item <?= (strpos(uri_string(), 'report/activity') !== false) ? 'active' : '' ?>">
                            <a href="<?= base_url('report/activity') ?>" class="menu-link">
                                <div>Laporan Aktivitas</div>
                            </a>
                        </li>
                        <li class="menu-item <?= (strpos(uri_string(), 'report/kpi') !== false) ? 'active' : '' ?>">
                            <a href="<?= base_url('report/kpi') ?>" class="menu-link">
                                <div>Laporan KPI</div>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="menu-item <?= (strpos(uri_string(), 'report/allowance') !== false) ? 'active' : '' ?>">
                        <a href="<?= base_url('report/allowance') ?>" class="menu-link">
                            <div>Laporan Keuangan</div>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- ARCHIVE (Admin & HR Only) -->
        <!-- ============================================ -->
        <?php if (in_array($role, ['admin', 'hr'])): ?>
            <li class="menu-header mt-4">
                <span class="menu-header-text">Archive</span>
            </li>
            <li class="menu-item <?= (strpos(uri_string(), 'archive') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('archive') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-archive-line"></i>
                    <div>Data Arsip</div>
                </a>
            </li>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- PENGATURAN (Admin Only) -->
        <!-- ============================================ -->
        <?php if ($role == 'admin'): ?>
            <li class="menu-header mt-4">
                <span class="menu-header-text">Sistem</span>
            </li>
            <li class="menu-item <?= (strpos(uri_string(), 'settings') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('settings') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-settings-3-line"></i>
                    <div>Pengaturan</div>
                </a>
            </li>
            <li class="menu-item <?= (strpos(uri_string(), 'audit') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('audit') ?>" class="menu-link">
                    <i class="menu-icon icon-base ri-history-line"></i>
                    <div>Audit Log</div>
                </a>
            </li>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- PROFILE & LOGOUT (All Roles) -->
        <!-- ============================================ -->
        <li class="menu-header mt-4">
            <span class="menu-header-text">Akun</span>
        </li>
        <li class="menu-item <?= (strpos(uri_string(), 'profile') !== false) ? 'active' : '' ?>">
            <a href="<?= base_url('profile') ?>" class="menu-link">
                <i class="menu-icon icon-base ri-user-3-line"></i>
                <div>Profil Saya</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="<?= base_url('logout') ?>" class="menu-link">
                <i class="menu-icon icon-base ri-logout-box-line"></i>
                <div>Logout</div>
            </a>
        </li>
    </ul>
</aside>