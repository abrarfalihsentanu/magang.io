<?php
// Ambil data user dari session yang benar
$userName = session()->get('nama_lengkap') ?? 'Guest User';
$userRole = session()->get('role_name') ?? 'Guest';
$userEmail = session()->get('email') ?? '';
$userPhoto = session()->get('foto') ?? 'default-avatar.png';
$roleCode = session()->get('role_code') ?? 'guest';

// Build full path untuk foto - gunakan route ProfileController::photo()
$userPhotoPath = $userPhoto && $userPhoto !== 'default-avatar.png'
    ? base_url('profile/photo/' . $userPhoto)
    : base_url('assets/img/avatars/1.png');

// Ambil jumlah notifikasi belum dibaca dari database
$notifModel = new \App\Models\NotificationModel();
$userId = session()->get('user_id');
$unreadNotifications = $userId ? $notifModel->countUnread((int)$userId) : 0;
?>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <!-- Mobile Menu Toggle - FIXED -->
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ri-menu-line ri-24px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center flex-grow-1">
            <div class="nav-item d-flex align-items-center w-100" style="max-width: 400px;">
                <i class="ri-search-line ri-20px me-2"></i>
                <input
                    type="text"
                    class="form-control border-0 shadow-none ps-2"
                    placeholder="Cari pemagang, aktivitas..."
                    aria-label="Search"
                    style="background: transparent;" />
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Quick Actions berdasarkan Role -->
            <?php if ($roleCode === 'intern'): ?>
                <!-- Quick Check-in untuk Intern -->
                <li class="nav-item me-2 me-xl-3">
                    <a class="nav-link btn btn-sm btn-primary d-flex align-items-center" href="<?= base_url('attendance/checkin') ?>">
                        <i class="ri-map-pin-user-line ri-18px me-1"></i>
                        <span class="d-none d-sm-inline">Check-in</span>
                    </a>
                </li>
            <?php elseif (in_array($roleCode, ['admin', 'hr', 'mentor'])): ?>
                <!-- Quick Access untuk Admin/HR/Mentor -->
                <li class="nav-item dropdown me-2 me-xl-3">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <div class="avatar avatar-online">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="ri-apps-2-line ri-20px"></i>
                            </span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-2" style="width: 250px;">
                        <li class="dropdown-header">
                            <div class="d-flex align-items-center py-2">
                                <i class="ri-rocket-line ri-24px text-primary me-2"></i>
                                <h6 class="mb-0">Quick Access</h6>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('attendance/all') ?>">
                                <i class="ri-calendar-check-line ri-20px me-3 text-success"></i>
                                <div>
                                    <span class="fw-medium">Data Absensi</span>
                                    <small class="text-muted d-block">Kelola kehadiran</small>
                                </div>
                            </a>
                        </li>
                        <?php if (in_array($roleCode, ['admin', 'hr'])): ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('intern') ?>">
                                    <i class="ri-user-star-line ri-20px me-3 text-info"></i>
                                    <div>
                                        <span class="fw-medium">Data Pemagang</span>
                                        <small class="text-muted d-block">Kelola pemagang</small>
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('report/attendance') ?>">
                                <i class="ri-file-chart-line ri-20px me-3 text-warning"></i>
                                <div>
                                    <span class="fw-medium">Laporan</span>
                                    <small class="text-muted d-block">Export & analisis</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- Notification Bell -->
            <li class="nav-item navbar-dropdown dropdown-notifications dropdown me-2 me-xl-3">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <span class="position-relative">
                        <div class="avatar avatar-online">
                            <span class="avatar-initial rounded-circle bg-label-warning">
                                <i class="ri-notification-3-line ri-20px"></i>
                            </span>
                        </div>
                        <?php if ($unreadNotifications > 0): ?>
                            <span class="badge rounded-pill bg-danger badge-dot badge-notifications border border-2 border-white"></span>
                        <?php endif; ?>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-0" style="width: 380px; max-height: 500px;">
                    <!-- Header -->
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3 px-4">
                            <div class="me-auto">
                                <h6 class="mb-0">Notifikasi</h6>
                                <small class="text-muted">Anda memiliki <?= $unreadNotifications ?> notifikasi baru</small>
                            </div>
                            <?php if ($unreadNotifications > 0): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill bg-label-primary"><?= $unreadNotifications ?> Baru</span>
                                    <a href="javascript:void(0);" class="text-body" data-bs-toggle="tooltip" title="Tandai semua sudah dibaca">
                                        <i class="ri-check-double-line ri-20px"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>

                    <!-- Notifications List (loaded via AJAX) -->
                    <li class="dropdown-notifications-list scrollable-container" style="max-height: 320px; overflow-y: auto;">
                        <ul class="list-group list-group-flush" id="navbarNotifList">
                            <!-- Loading state -->
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-center py-4" id="notifLoading">
                                    <span class="spinner-border spinner-border-sm text-primary me-2"></span>
                                    <small class="text-muted">Memuat notifikasi...</small>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Footer -->
                    <li class="border-top">
                        <div class="d-grid p-3">
                            <a class="btn btn-sm btn-outline-primary" href="<?= base_url('notifications') ?>">
                                <i class="ri-notification-4-line me-1"></i>
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="<?= $userPhotoPath ?>" alt="<?= $userName ?>" class="rounded-circle" onerror="this.src='<?= base_url('assets/img/avatars/1.png') ?>'" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 280px;">
                    <!-- User Info -->
                    <li>
                        <a class="dropdown-item" href="<?= base_url('profile') ?>">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="<?= $userPhotoPath ?>" alt="<?= $userName ?>" class="rounded-circle" onerror="this.src='<?= base_url('assets/img/avatars/1.png') ?>'" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?= $userName ?></h6>
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="ri-shield-user-line ri-14px me-1"></i>
                                        <?= $userRole ?>
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1">
                    </li>

                    <!-- Profile Menu -->
                    <li>
                        <a class="dropdown-item" href="<?= base_url('profile') ?>">
                            <i class="ri-user-3-line ri-20px me-2"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>

                    <!-- Admin Specific Menu -->
                    <?php if ($roleCode === 'admin'): ?>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('settings') ?>">
                                <i class="ri-settings-3-line ri-20px me-2"></i>
                                <span>Pengaturan</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('audit') ?>">
                                <i class="ri-history-line ri-20px me-2"></i>
                                <span>Audit Log</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Intern Specific Menu -->
                    <?php if ($roleCode === 'intern'): ?>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('allowance/my') ?>">
                                <i class="ri-money-dollar-circle-line ri-20px me-2"></i>
                                <span>Uang Saku Saya</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('kpi/my') ?>">
                                <i class="ri-line-chart-line ri-20px me-2"></i>
                                <span>KPI Saya</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <hr class="dropdown-divider my-1">
                    </li>

                    <!-- Help & Support -->
                    <li>
                        <a class="dropdown-item" href="<?= base_url('help') ?>">
                            <i class="ri-question-line ri-20px me-2"></i>
                            <span>Bantuan</span>
                        </a>
                    </li>

                    <!-- Logout -->
                    <li>
                        <div class="d-grid px-3 pt-2 pb-1">
                            <a class="btn btn-sm btn-danger d-flex align-items-center justify-content-center"
                                href="<?= base_url('logout') ?>"
                                onclick="return confirm('Apakah Anda yakin ingin logout?')">
                                <i class="ri-logout-box-r-line ri-18px me-2"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Additional CSS for better mobile experience -->
<style>
    /* Mobile Menu Toggle Fix */
    .layout-menu-toggle {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .layout-menu-toggle:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        border-radius: 0.375rem;
    }

    /* Smooth scrolling for notifications */
    .scrollable-container {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
    }

    .scrollable-container::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-container::-webkit-scrollbar-track {
        background: transparent;
    }

    .scrollable-container::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    /* Notification badge animation */
    .badge-dot {
        width: 8px;
        height: 8px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(var(--bs-danger-rgb), 0.7);
        }

        70% {
            box-shadow: 0 0 0 6px rgba(var(--bs-danger-rgb), 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(var(--bs-danger-rgb), 0);
        }
    }

    /* Dropdown item hover */
    .dropdown-notifications-item:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }

    /* Responsive adjustments */
    @media (max-width: 1199.98px) {
        .navbar-nav-right {
            justify-content: flex-end !important;
        }
    }

    @media (max-width: 575.98px) {
        .dropdown-menu {
            max-width: calc(100vw - 2rem) !important;
        }

        .dropdown-notifications .dropdown-menu {
            width: calc(100vw - 2rem) !important;
        }
    }
</style>

<!-- Session Check Script -->
<script>
    // Check session every 5 minutes
    setInterval(function() {
        fetch('<?= base_url('api/check-session') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'expired') {
                    alert('Sesi Anda telah berakhir. Silakan login kembali.');
                    window.location.href = '<?= base_url('login') ?>';
                }
            })
            .catch(error => console.log('Session check error:', error));
    }, 300000); // 5 minutes

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // ── Notification Bell: Load on dropdown open ──────────
    const notifTypeIcons = {
        activity_submitted: {
            icon: 'ri-file-add-line',
            color: 'warning'
        },
        activity_approved: {
            icon: 'ri-checkbox-circle-line',
            color: 'success'
        },
        activity_rejected: {
            icon: 'ri-close-circle-line',
            color: 'danger'
        },
        correction_submitted: {
            icon: 'ri-calendar-todo-line',
            color: 'warning'
        },
        correction_approved: {
            icon: 'ri-calendar-check-line',
            color: 'success'
        },
        correction_rejected: {
            icon: 'ri-calendar-close-line',
            color: 'danger'
        },
        leave_submitted: {
            icon: 'ri-article-line',
            color: 'warning'
        },
        leave_approved: {
            icon: 'ri-check-double-line',
            color: 'success'
        },
        leave_rejected: {
            icon: 'ri-close-circle-line',
            color: 'danger'
        },
        allowance_paid: {
            icon: 'ri-money-dollar-circle-line',
            color: 'primary'
        },
        kpi_assessed: {
            icon: 'ri-bar-chart-line',
            color: 'info'
        },
        kpi_calculated: {
            icon: 'ri-award-line',
            color: 'success'
        },
    };

    let notifLoaded = false;

    const notifDropdown = document.querySelector('.dropdown-notifications');
    if (notifDropdown) {
        notifDropdown.addEventListener('show.bs.dropdown', function() {
            if (!notifLoaded) loadNavbarNotifications();
        });
    }

    async function loadNavbarNotifications() {
        try {
            const data = await fetch('<?= base_url('api/notifications') ?>', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const res = await data.json();
            if (!res.success) return;

            const list = document.getElementById('navbarNotifList');
            if (!list) return;

            notifLoaded = true;

            // Update badge count
            updateNavBadge(res.count);

            // Update header text
            const headerSmall = document.querySelector('.dropdown-notifications .dropdown-header small.text-muted');
            if (headerSmall) {
                headerSmall.textContent = res.count > 0 ?
                    `Anda memiliki ${res.count} notifikasi baru` :
                    'Tidak ada notifikasi baru';
            }
            const headerBadge = document.querySelector('.dropdown-notifications .badge.bg-label-primary');
            if (headerBadge) {
                headerBadge.style.display = res.count > 0 ? '' : 'none';
                headerBadge.textContent = res.count + ' Baru';
            }

            if (res.notifications.length === 0) {
                list.innerHTML = `
                    <li class="list-group-item">
                        <div class="d-flex flex-column align-items-center justify-content-center py-4">
                            <span class="avatar-initial rounded-circle bg-label-secondary p-3 mb-2">
                                <i class="ri-notification-off-line ri-24px"></i>
                            </span>
                            <h6 class="mb-1 small">Tidak Ada Notifikasi</h6>
                            <p class="text-muted mb-0 small">Belum ada notifikasi baru</p>
                        </div>
                    </li>`;
                return;
            }

            list.innerHTML = res.notifications.map(n => {
                const meta = notifTypeIcons[n.type] || {
                    icon: 'ri-notification-3-line',
                    color: 'secondary'
                };
                return `
                <li class="list-group-item list-group-item-action dropdown-notifications-item px-3 py-2"
                    style="cursor:pointer;" onclick="handleNavNotifClick(${n.id}, '${n.link || ''}')">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="flex-shrink-0">
                            <span class="avatar-initial rounded-circle bg-label-${meta.color} p-2">
                                <i class="${meta.icon} ri-18px"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="mb-0 small fw-semibold">${escHtml(n.title)}</h6>
                                <small class="text-muted text-nowrap ms-1">${n.time_ago}</small>
                            </div>
                            <small class="text-muted d-block" style="white-space:normal;line-height:1.4">${escHtml(n.message)}</small>
                        </div>
                        ${!n.is_read ? '<span class="badge-dot bg-primary rounded-circle flex-shrink-0 mt-1" style="width:8px;height:8px;display:inline-block;"></span>' : ''}
                    </div>
                </li>`;
            }).join('');

        } catch (err) {
            console.warn('Navbar notifications load error:', err);
        }
    }

    async function handleNavNotifClick(id, link) {
        try {
            await csrfFetch(`<?= base_url('api/notification/mark-read') ?>/${id}`, {
                method: 'POST'
            });
        } catch {}
        if (link) window.location.href = link;
    }

    // ── Mark all read from navbar ──────────────────────────
    document.querySelector('[title="Tandai semua sudah dibaca"]')?.addEventListener('click', async (e) => {
        e.stopPropagation();
        try {
            const res = await csrfFetch('<?= base_url('api/notifications/mark-all-read') ?>', {
                method: 'POST'
            });
            if (res.success) {
                updateNavBadge(0);
                notifLoaded = false; // force reload next open
                loadNavbarNotifications();
            }
        } catch {}
    });

    function updateNavBadge(count) {
        const dot = document.querySelector('.badge-notifications');
        const span = document.querySelector('.dropdown-notifications .badge.bg-label-primary');
        if (dot) dot.style.display = count > 0 ? '' : 'none';
        if (span) span.textContent = count + ' Baru';
    }

    function escHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str || ''));
        return div.innerHTML;
    }

    // ── Poll every 60s to refresh unread count ────────────
    setInterval(async () => {
        try {
            const data = await fetch('<?= base_url('api/notifications') ?>', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const res = await data.json();
            if (res.success) {
                updateNavBadge(res.count);
                notifLoaded = false; // will reload on next open
            }
        } catch {}
    }, 60000);
</script>