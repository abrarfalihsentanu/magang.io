<?php
$role = session()->get('role_code');

$typeLabels = [
    'activity_submitted'   => 'Aktivitas Baru',
    'activity_approved'    => 'Aktivitas Disetujui',
    'activity_rejected'    => 'Aktivitas Ditolak',
    'correction_submitted' => 'Koreksi Absensi',
    'correction_approved'  => 'Koreksi Disetujui',
    'correction_rejected'  => 'Koreksi Ditolak',
    'leave_submitted'      => 'Pengajuan Cuti/Izin',
    'leave_approved'       => 'Cuti/Izin Disetujui',
    'leave_rejected'       => 'Cuti/Izin Ditolak',
    'allowance_paid'       => 'Uang Saku',
    'kpi_assessed'         => 'KPI Dinilai',
    'kpi_calculated'       => 'KPI Periode',
];

$typeIcons = [
    'activity_submitted'   => ['icon' => 'ri-file-add-line',          'color' => 'warning'],
    'activity_approved'    => ['icon' => 'ri-checkbox-circle-line',   'color' => 'success'],
    'activity_rejected'    => ['icon' => 'ri-close-circle-line',      'color' => 'danger'],
    'correction_submitted' => ['icon' => 'ri-calendar-todo-line',     'color' => 'warning'],
    'correction_approved'  => ['icon' => 'ri-calendar-check-line',    'color' => 'success'],
    'correction_rejected'  => ['icon' => 'ri-calendar-close-line',    'color' => 'danger'],
    'leave_submitted'      => ['icon' => 'ri-article-line',           'color' => 'warning'],
    'leave_approved'       => ['icon' => 'ri-check-double-line',      'color' => 'success'],
    'leave_rejected'       => ['icon' => 'ri-close-circle-line',      'color' => 'danger'],
    'allowance_paid'       => ['icon' => 'ri-money-dollar-circle-line', 'color' => 'primary'],
    'kpi_assessed'         => ['icon' => 'ri-bar-chart-line',         'color' => 'info'],
    'kpi_calculated'       => ['icon' => 'ri-award-line',             'color' => 'success'],
];

function notifIcon(string $type, array $typeIcons): array
{
    return $typeIcons[$type] ?? ['icon' => 'ri-notification-3-line', 'color' => 'secondary'];
}

function timeAgoPhp(?string $datetime): string
{
    if (empty($datetime)) return '-';
    $diff = time() - strtotime($datetime);
    if ($diff < 60)     return 'Baru saja';
    if ($diff < 3600)   return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400)  return floor($diff / 3600) . ' jam lalu';
    if ($diff < 604800) return floor($diff / 86400) . ' hari lalu';
    return date('d M Y', strtotime($datetime));
}
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-semibold">
                        <i class="ri-notification-3-line text-primary me-2"></i>Notifikasi
                    </h4>
                    <p class="text-muted mb-0">
                        <?php if ($unreadCount > 0): ?>
                            Anda memiliki <span class="fw-bold text-primary"><?= $unreadCount ?></span> notifikasi belum dibaca
                        <?php else: ?>
                            Semua notifikasi sudah dibaca
                        <?php endif; ?>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <?php if ($unreadCount > 0): ?>
                        <button class="btn btn-outline-primary btn-sm" id="btnMarkAllRead">
                            <i class="ri-check-double-line me-1"></i>Tandai Semua Dibaca
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="avatar avatar-sm mx-auto mb-2">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="ri-notification-3-line ri-16px"></i>
                        </span>
                    </div>
                    <h5 class="mb-0 fw-bold"><?= count($notifications) ?></h5>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="avatar avatar-sm mx-auto mb-2">
                        <span class="avatar-initial rounded-circle bg-label-danger">
                            <i class="ri-mail-unread-line ri-16px"></i>
                        </span>
                    </div>
                    <h5 class="mb-0 fw-bold text-danger"><?= $unreadCount ?></h5>
                    <small class="text-muted">Belum Dibaca</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="avatar avatar-sm mx-auto mb-2">
                        <span class="avatar-initial rounded-circle bg-label-warning">
                            <i class="ri-time-line ri-16px"></i>
                        </span>
                    </div>
                    <h5 class="mb-0 fw-bold"><?= count(array_filter($notifications, fn($n) => strtotime($n['created_at']) > strtotime('-24 hours'))) ?></h5>
                    <small class="text-muted">Hari Ini</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="avatar avatar-sm mx-auto mb-2">
                        <span class="avatar-initial rounded-circle bg-label-success">
                            <i class="ri-check-double-line ri-16px"></i>
                        </span>
                    </div>
                    <h5 class="mb-0 fw-bold"><?= count($notifications) - $unreadCount ?></h5>
                    <small class="text-muted">Sudah Dibaca</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card border-0 shadow-sm mb-0">
        <div class="card-header bg-transparent border-bottom-0 pb-0">
            <ul class="nav nav-tabs card-header-tabs" id="notifTabs">
                <li class="nav-item">
                    <a class="nav-link <?= $filter === 'all' ? 'active' : '' ?>"
                        href="<?= base_url('notifications?filter=all') ?>">
                        <i class="ri-list-check me-1"></i>Semua
                        <span class="badge rounded-pill bg-label-secondary ms-1"><?= count($notifications) ?></span>
                    </a>
                </li>
                <?php if ($unreadCount > 0): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $filter === 'unread' ? 'active' : '' ?>"
                            href="<?= base_url('notifications?filter=unread') ?>">
                            <i class="ri-mail-unread-line me-1"></i>Belum Dibaca
                            <span class="badge rounded-pill bg-danger ms-1"><?= $unreadCount ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="card-body p-0">
            <?php if (empty($notifications)): ?>
                <!-- Empty State -->
                <div class="d-flex flex-column align-items-center justify-content-center py-5 px-3">
                    <div class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="ri-notification-off-line ri-40px"></i>
                        </span>
                    </div>
                    <h5 class="mb-1 text-secondary">Tidak Ada Notifikasi</h5>
                    <p class="text-muted mb-0">Belum ada notifikasi <?= $filter === 'unread' ? 'yang belum dibaca' : '' ?> untuk Anda.</p>
                </div>

            <?php else: ?>
                <!-- Notifications List -->
                <ul class="list-group list-group-flush" id="notificationList">
                    <?php foreach ($notifications as $notif):
                        $meta = notifIcon($notif['type'], $typeIcons);
                        $isUnread = !(bool) $notif['is_read'];
                    ?>
                        <li class="list-group-item list-group-item-action px-4 py-3 notif-item <?= $isUnread ? 'bg-light-subtle' : '' ?>"
                            id="notif-<?= $notif['id_notification'] ?>"
                            data-id="<?= $notif['id_notification'] ?>"
                            data-read="<?= $notif['is_read'] ?>"
                            data-link="<?= esc($notif['link'] ?? '') ?>">
                            <div class="d-flex gap-3 align-items-start">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-sm">
                                        <span class="avatar-initial rounded-circle bg-label-<?= $meta['color'] ?>">
                                            <i class="<?= $meta['icon'] ?> ri-18px"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <h6 class="mb-0 small fw-semibold <?= $isUnread ? 'text-body' : 'text-muted' ?>">
                                                <?= esc($notif['title']) ?>
                                            </h6>
                                            <?php if ($isUnread): ?>
                                                <span class="badge rounded-pill bg-danger badge-xs" style="font-size: 9px; padding: 2px 6px;">Baru</span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted text-nowrap ms-2 flex-shrink-0">
                                            <?= timeAgoPhp($notif['created_at']) ?>
                                        </small>
                                    </div>

                                    <p class="mb-1 small text-muted text-truncate-2"><?= esc($notif['message']) ?></p>

                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <small class="badge bg-label-secondary text-secondary py-1 px-2">
                                            <?= $typeLabels[$notif['type']] ?? ucfirst(str_replace('_', ' ', $notif['type'])) ?>
                                        </small>
                                        <small class="text-muted"><?= date('d M Y H:i', strtotime($notif['created_at'])) ?></small>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex-shrink-0 d-flex align-items-center gap-1">
                                    <?php if (!empty($notif['link'])): ?>
                                        <a href="<?= esc($notif['link']) ?>"
                                            class="btn btn-icon btn-sm btn-outline-primary rounded-circle"
                                            title="Buka halaman terkait"
                                            onclick="handleNotifClick(event, <?= $notif['id_notification'] ?>, '<?= esc($notif['link']) ?>')">
                                            <i class="ri-external-link-line"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($isUnread): ?>
                                        <button class="btn btn-icon btn-sm btn-outline-success rounded-circle btn-mark-read"
                                            data-id="<?= $notif['id_notification'] ?>"
                                            title="Tandai sudah dibaca">
                                            <i class="ri-check-line"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php if (!empty($notifications)): ?>
            <div class="card-footer bg-transparent border-top py-2 px-4">
                <small class="text-muted">Menampilkan <?= count($notifications) ?> notifikasi terbaru</small>
            </div>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const MARK_READ_URL = '<?= base_url('api/notification/mark-read') ?>';
    const MARK_ALL_URL = '<?= base_url('api/notifications/mark-all-read') ?>';
    const CSRF_TOKEN_NAME = '<?= csrf_token() ?>';
    const CSRF_TOKEN_VALUE = '<?= csrf_hash() ?>';

    // ── Mark single read ──────────────────────────────────
    document.querySelectorAll('.btn-mark-read').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation();
            const id = btn.dataset.id;
            await markRead(id);
        });
    });

    async function markRead(id) {
        try {
            const res = await csrfFetch(`${MARK_READ_URL}/${id}`, {
                method: 'POST'
            });
            if (res.success) {
                const item = document.getElementById(`notif-${id}`);
                if (item) {
                    item.classList.remove('bg-light-subtle');
                    item.querySelector('.btn-mark-read')?.remove();
                    item.querySelector('.badge.bg-danger')?.remove();
                    item.setAttribute('data-read', '1');

                    const titleEl = item.querySelector('h6.small');
                    if (titleEl) {
                        titleEl.classList.remove('text-body');
                        titleEl.classList.add('text-muted');
                    }
                }
                updateBadgeCounts(res.count);
            }
        } catch (err) {
            console.error('Mark read error:', err);
        }
    }

    // ── Mark all read ─────────────────────────────────────
    document.getElementById('btnMarkAllRead')?.addEventListener('click', async () => {
        const btn = document.getElementById('btnMarkAllRead');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';

        try {
            const res = await csrfFetch(MARK_ALL_URL, {
                method: 'POST'
            });
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Semua notifikasi telah ditandai sudah dibaca',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-check-double-line me-1"></i>Tandai Semua Dibaca';
        }
    });

    // ── Handle notif click (mark read + navigate) ─────────
    function handleNotifClick(e, id, link) {
        e.preventDefault();
        markRead(id).then(() => {
            if (link) window.location.href = link;
        });
    }

    // ── Update badge counts in UI ─────────────────────────
    function updateBadgeCounts(count) {
        // Update page text
        const subText = document.querySelector('.card-title-text, .text-muted');
        if (count === 0) {
            // Reload to show clean state
        }

        // Update navbar bell badge (global)
        const navBadge = document.querySelector('.badge-notifications');
        if (navBadge) {
            if (count === 0) navBadge.style.display = 'none';
        }
    }

    // ── Auto mark read on link click ──────────────────────
    document.querySelectorAll('.notif-item').forEach(item => {
        item.addEventListener('click', (e) => {
            if (e.target.closest('button') || e.target.closest('a')) return;

            const id = item.dataset.id;
            const link = item.dataset.link;
            const read = item.dataset.read;

            if (read === '0') {
                markRead(id).then(() => {
                    if (link) window.location.href = link;
                });
            } else if (link) {
                window.location.href = link;
            }
        });
        if (item.dataset.link) item.style.cursor = 'pointer';
    });
</script>

<style>
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .bg-light-subtle {
        background-color: rgba(var(--bs-primary-rgb), 0.04) !important;
        border-left: 3px solid var(--bs-primary) !important;
    }

    .notif-item {
        transition: background-color 0.2s ease;
    }

    .notif-item:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.06) !important;
    }
</style>
<?= $this->endSection() ?>