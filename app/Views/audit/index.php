<?php
// ── helper maps ──────────────────────────────────────────────────────────
$actionMeta = [
    'login'    => ['label' => 'Login',    'color' => 'success',   'icon' => 'ri-login-box-line'],
    'logout'   => ['label' => 'Logout',   'color' => 'secondary', 'icon' => 'ri-logout-box-line'],
    'create'   => ['label' => 'Buat',     'color' => 'primary',   'icon' => 'ri-add-circle-line'],
    'update'   => ['label' => 'Ubah',     'color' => 'warning',   'icon' => 'ri-edit-line'],
    'delete'   => ['label' => 'Hapus',    'color' => 'danger',    'icon' => 'ri-delete-bin-line'],
    'approve'  => ['label' => 'Setuju',   'color' => 'success',   'icon' => 'ri-checkbox-circle-line'],
    'reject'   => ['label' => 'Tolak',    'color' => 'danger',    'icon' => 'ri-close-circle-line'],
    'submit'   => ['label' => 'Submit',   'color' => 'info',      'icon' => 'ri-send-plane-line'],
    'export'   => ['label' => 'Export',   'color' => 'dark',      'icon' => 'ri-download-2-line'],
    'upload'   => ['label' => 'Upload',   'color' => 'primary',   'icon' => 'ri-upload-2-line'],
    'finalize' => ['label' => 'Finalize', 'color' => 'warning',   'icon' => 'ri-lock-line'],
    'calculate' => ['label' => 'Hitung',   'color' => 'info',      'icon' => 'ri-calculator-line'],
];

$moduleMeta = [
    'auth'       => ['color' => 'dark',    'icon' => 'ri-shield-keyhole-line'],
    'activity'   => ['color' => 'primary', 'icon' => 'ri-file-list-3-line'],
    'attendance' => ['color' => 'info',    'icon' => 'ri-calendar-line'],
    'leave'      => ['color' => 'warning', 'icon' => 'ri-article-line'],
    'allowance'  => ['color' => 'success', 'icon' => 'ri-money-dollar-circle-line'],
    'kpi'        => ['color' => 'primary', 'icon' => 'ri-bar-chart-line'],
    'user'       => ['color' => 'primary', 'icon' => 'ri-user-line'],
    'intern'     => ['color' => 'info',    'icon' => 'ri-user-star-line'],
    'role'       => ['color' => 'dark',    'icon' => 'ri-key-line'],
    'divisi'     => ['color' => 'warning', 'icon' => 'ri-building-line'],
    'settings'   => ['color' => 'secondary', 'icon' => 'ri-settings-3-line'],
    'report'     => ['color' => 'info',    'icon' => 'ri-file-chart-line'],
];

function getActionMeta(string $action, array $map): array
{
    return $map[$action] ?? ['label' => ucfirst($action), 'color' => 'secondary', 'icon' => 'ri-alert-line'];
}
function getModuleMeta(string $module, array $map): array
{
    return $map[$module] ?? ['color' => 'secondary', 'icon' => 'ri-apps-line'];
}

// build query string helper (keep existing filters + override page)
function auditPageUrl(array $filters, int $page): string
{
    $q = array_filter($filters, fn($v) => $v !== '');
    $q['page'] = $page;
    return base_url('audit') . '?' . http_build_query($q);
}
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- ── Breadcrumb ─────────────────────────────────────────────────── -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Audit Log</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-semibold">
                        <i class="ri-shield-check-line text-primary me-2"></i>Audit Log
                    </h4>
                    <p class="text-muted mb-0">Rekam jejak seluruh aktivitas pengguna di sistem</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Stats Cards ───────────────────────────────────────────────── -->
    <div class="row mb-4 g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="avatar avatar-lg flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-list-check ri-28px"></i>
                        </span>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold"><?= number_format($stats['total']) ?></div>
                        <small class="text-muted">Total Log</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="avatar avatar-lg flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-calendar-today-line ri-28px"></i>
                        </span>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold"><?= number_format($stats['today']) ?></div>
                        <small class="text-muted">Aktivitas Hari Ini</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="avatar avatar-lg flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-team-line ri-28px"></i>
                        </span>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold"><?= number_format($stats['unique_users']) ?></div>
                        <small class="text-muted">Pengguna Aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="avatar avatar-lg flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-apps-2-line ri-28px"></i>
                        </span>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold"><?= number_format($stats['modules']) ?></div>
                        <small class="text-muted">Modul Tercatat</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Filter Card ───────────────────────────────────────────────── -->
    <div class="card mb-4">
        <div class="card-header border-0 pb-0">
            <h5 class="card-title mb-0">
                <i class="ri-filter-3-line me-2 text-primary"></i>Filter Log
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('audit') ?>" id="filterForm">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Cari</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" class="form-control" name="search"
                                placeholder="Nama, aksi, modul, IP..."
                                value="<?= esc($filters['search']) ?>">
                        </div>
                    </div>

                    <!-- Module -->
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Modul</label>
                        <select class="form-select" name="module">
                            <option value="">Semua Modul</option>
                            <?php foreach ($modules as $m): ?>
                                <option value="<?= esc($m) ?>"
                                    <?= $filters['module'] === $m ? 'selected' : '' ?>>
                                    <?= ucfirst($m) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Action -->
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Aksi</label>
                        <select class="form-select" name="action">
                            <option value="">Semua Aksi</option>
                            <?php foreach ($actions as $a): ?>
                                <option value="<?= esc($a) ?>"
                                    <?= $filters['action'] === $a ? 'selected' : '' ?>>
                                    <?= ucfirst($a) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- User -->
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Pengguna</label>
                        <select class="form-select" name="user_id">
                            <option value="">Semua Pengguna</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id_user'] ?>"
                                    <?= (string)$filters['user_id'] === (string)$u['id_user'] ? 'selected' : '' ?>>
                                    <?= esc($u['nama_lengkap']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Dari Tanggal</label>
                        <input type="date" class="form-control" name="date_from"
                            value="<?= esc($filters['date_from']) ?>">
                    </div>

                    <!-- Date To -->
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Sampai Tanggal</label>
                        <input type="date" class="form-control" name="date_to"
                            value="<?= esc($filters['date_to']) ?>">
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-6 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>Terapkan Filter
                        </button>
                        <a href="<?= base_url('audit') ?>" class="btn btn-outline-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ── Log Table ─────────────────────────────────────────────────── -->
    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="card-title mb-0">
                    <i class="ri-history-line me-2 text-primary"></i>Riwayat Aktivitas
                </h5>
                <small class="text-muted">
                    <?php if ($totalRows > 0): ?>
                        Menampilkan <?= (($page - 1) * $perPage) + 1 ?>–<?= min($page * $perPage, $totalRows) ?>
                        dari <?= number_format($totalRows) ?> log
                    <?php else: ?>
                        Tidak ada log ditemukan
                    <?php endif; ?>
                </small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:50px">#</th>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>Record</th>
                        <th>IP Address</th>
                        <th class="text-center" style="width:80px">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center gap-2 text-muted">
                                    <i class="ri-inbox-line ri-48px opacity-25"></i>
                                    <p class="mb-1 fw-semibold">Tidak ada log ditemukan</p>
                                    <small>Coba ubah filter pencarian Anda</small>
                                    <?php if (array_filter($filters)): ?>
                                        <a href="<?= base_url('audit') ?>" class="btn btn-sm btn-outline-primary mt-1">
                                            <i class="ri-refresh-line me-1"></i>Reset Filter
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $i => $log): ?>
                            <?php
                            $am  = getActionMeta($log['action'], $actionMeta);
                            $mm  = getModuleMeta($log['module'], $moduleMeta);
                            $no  = (($page - 1) * $perPage) + $i + 1;
                            $hasData = !empty($log['old_data']) || !empty($log['new_data']);
                            ?>
                            <tr class="<?= $hasData ? 'cursor-pointer' : '' ?>"
                                <?php if ($hasData): ?>
                                onclick="showDetail(<?= $log['id_log'] ?>, this)"
                                title="Klik untuk lihat detail"
                                <?php endif; ?>>

                                <td class="ps-4 text-muted small"><?= $no ?></td>

                                <!-- Waktu -->
                                <td>
                                    <div class="fw-semibold small"><?= date('d M Y', strtotime($log['created_at'])) ?></div>
                                    <div class="text-muted" style="font-size:11px"><?= date('H:i:s', strtotime($log['created_at'])) ?></div>
                                </td>

                                <!-- Pengguna -->
                                <td>
                                    <?php if (!empty($log['nama_lengkap'])): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar avatar-xs">
                                                <span class="avatar-initial rounded-circle bg-label-primary" style="font-size:10px">
                                                    <?= mb_strtoupper(mb_substr($log['nama_lengkap'], 0, 1)) ?>
                                                </span>
                                            </span>
                                            <div>
                                                <div class="fw-semibold small"><?= esc($log['nama_lengkap']) ?></div>
                                                <div class="text-muted" style="font-size:11px"><?= esc($log['email'] ?? '') ?></div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Modul -->
                                <td>
                                    <span class="badge bg-label-<?= $mm['color'] ?> d-inline-flex align-items-center gap-1">
                                        <i class="<?= $mm['icon'] ?>" style="font-size:13px"></i>
                                        <?= ucfirst($log['module']) ?>
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td>
                                    <span class="badge bg-<?= $am['color'] ?> d-inline-flex align-items-center gap-1">
                                        <i class="<?= $am['icon'] ?>" style="font-size:12px"></i>
                                        <?= $am['label'] ?>
                                    </span>
                                </td>

                                <!-- Record ID -->
                                <td>
                                    <?php if (!empty($log['record_id'])): ?>
                                        <span class="badge bg-label-secondary">#<?= esc($log['record_id']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- IP -->
                                <td>
                                    <span class="text-muted small font-monospace"><?= esc($log['ip_address'] ?? '—') ?></span>
                                </td>

                                <!-- Detail button -->
                                <td class="text-center">
                                    <?php if ($hasData): ?>
                                        <button class="btn btn-icon btn-sm btn-outline-primary"
                                            onclick="event.stopPropagation(); showDetail(<?= $log['id_log'] ?>, this)"
                                            title="Lihat perubahan data">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ── Pagination ──────────────────────────────────────────── -->
        <?php if ($totalPages > 1): ?>
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Halaman <?= $page ?> dari <?= $totalPages ?>
                </small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <!-- First -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= auditPageUrl($filters, 1) ?>">
                                <i class="ri-skip-back-line"></i>
                            </a>
                        </li>
                        <!-- Prev -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= auditPageUrl($filters, $page - 1) ?>">
                                <i class="ri-arrow-left-s-line"></i>
                            </a>
                        </li>

                        <?php
                        $startP = max(1, $page - 2);
                        $endP   = min($totalPages, $page + 2);
                        if ($startP > 1): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>

                        <?php for ($p = $startP; $p <= $endP; $p++): ?>
                            <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= auditPageUrl($filters, $p) ?>"><?= $p ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($endP < $totalPages): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>

                        <!-- Next -->
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= auditPageUrl($filters, $page + 1) ?>">
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                        </li>
                        <!-- Last -->
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= auditPageUrl($filters, $totalPages) ?>">
                                <i class="ri-skip-forward-line"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ── Detail Modal ──────────────────────────────────────────────────── -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">
                        <i class="ri-eye-line me-2 text-primary"></i>Detail Perubahan Data
                    </h5>
                    <small class="text-muted" id="detailMeta"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="detailLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Memuat data...</p>
                </div>
                <div id="detailContent" class="d-none">
                    <div class="row g-0">
                        <!-- Old Data -->
                        <div class="col-md-6 border-end">
                            <div class="p-3 bg-label-danger border-bottom">
                                <h6 class="mb-0 text-danger">
                                    <i class="ri-arrow-go-back-line me-1"></i>Data Sebelum
                                </h6>
                            </div>
                            <div class="p-3">
                                <pre id="oldDataPre" class="mb-0 small" style="max-height:400px;overflow-y:auto;background:transparent;border:none;padding:0;white-space:pre-wrap;word-break:break-all;"></pre>
                                <p id="oldDataEmpty" class="text-muted small mb-0 d-none">
                                    <i class="ri-minus-line me-1"></i>Tidak ada data sebelumnya
                                </p>
                            </div>
                        </div>
                        <!-- New Data -->
                        <div class="col-md-6">
                            <div class="p-3 bg-label-success border-bottom">
                                <h6 class="mb-0 text-success">
                                    <i class="ri-arrow-right-up-line me-1"></i>Data Sesudah
                                </h6>
                            </div>
                            <div class="p-3">
                                <pre id="newDataPre" class="mb-0 small" style="max-height:400px;overflow-y:auto;background:transparent;border:none;padding:0;white-space:pre-wrap;word-break:break-all;"></pre>
                                <p id="newDataEmpty" class="text-muted small mb-0 d-none">
                                    <i class="ri-minus-line me-1"></i>Tidak ada data baru
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Changed fields highlight -->
                    <div id="diffSection" class="border-top p-3 d-none">
                        <h6 class="mb-3 text-warning">
                            <i class="ri-git-diff-line me-1"></i>Field Yang Berubah
                        </h6>
                        <div id="diffTable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- inline log data to avoid extra AJAX call ─────────────────────────── -->
<script>
    // Store all log data inline so detail modal works without extra request
    const auditLogs = {
        <?php foreach ($logs as $log): ?>
            <?= $log['id_log'] ?>: {
                id: <?= $log['id_log'] ?>,
                action: "<?= esc($log['action']) ?>",
                module: "<?= esc($log['module']) ?>",
                record_id: "<?= esc($log['record_id'] ?? '') ?>",
                user: "<?= esc($log['nama_lengkap'] ?? 'Sistem') ?>",
                created_at: "<?= esc($log['created_at']) ?>",
                ip: "<?= esc($log['ip_address'] ?? '') ?>",
                user_agent: "<?= esc(addslashes(substr($log['user_agent'] ?? '', 0, 120))) ?>",
                old_data: <?= !empty($log['old_data']) ? (is_string($log['old_data']) ? $log['old_data'] : json_encode($log['old_data'])) : 'null' ?>,
                new_data: <?= !empty($log['new_data']) ? (is_string($log['new_data']) ? $log['new_data'] : json_encode($log['new_data'])) : 'null' ?>,
            },
        <?php endforeach; ?>
    };

    function showDetail(logId, triggerEl) {
        const log = auditLogs[logId];
        if (!log) return;

        // Set meta label
        document.getElementById('detailMeta').textContent =
            `${log.action.toUpperCase()} · ${log.module} · ${log.user} · ${log.created_at}`;

        // Render old data
        const oldPre = document.getElementById('oldDataPre');
        const oldEmpty = document.getElementById('oldDataEmpty');
        if (log.old_data) {
            oldPre.textContent = JSON.stringify(log.old_data, null, 2);
            oldPre.classList.remove('d-none');
            oldEmpty.classList.add('d-none');
        } else {
            oldPre.classList.add('d-none');
            oldEmpty.classList.remove('d-none');
        }

        // Render new data
        const newPre = document.getElementById('newDataPre');
        const newEmpty = document.getElementById('newDataEmpty');
        if (log.new_data) {
            newPre.textContent = JSON.stringify(log.new_data, null, 2);
            newPre.classList.remove('d-none');
            newEmpty.classList.add('d-none');
        } else {
            newPre.classList.add('d-none');
            newEmpty.classList.remove('d-none');
        }

        // Diff section — show changed fields side-by-side
        const diffSection = document.getElementById('diffSection');
        const diffTable = document.getElementById('diffTable');
        if (log.old_data && log.new_data) {
            const oldObj = typeof log.old_data === 'object' ? log.old_data : {};
            const newObj = typeof log.new_data === 'object' ? log.new_data : {};
            const keys = new Set([...Object.keys(oldObj), ...Object.keys(newObj)]);
            let rows = '';
            keys.forEach(key => {
                const oldVal = JSON.stringify(oldObj[key] ?? null);
                const newVal = JSON.stringify(newObj[key] ?? null);
                const changed = oldVal !== newVal;
                if (changed) {
                    rows += `
                <div class="d-flex align-items-start gap-2 mb-2 p-2 rounded border border-warning-subtle bg-warning-subtle">
                    <span class="badge bg-label-warning text-nowrap fw-semibold" style="min-width:120px">${key}</span>
                    <div class="flex-grow-1 d-flex gap-2 flex-wrap">
                        <span class="text-danger small text-decoration-line-through">${escHtml(oldVal)}</span>
                        <i class="ri-arrow-right-line text-muted align-self-center"></i>
                        <span class="text-success small fw-semibold">${escHtml(newVal)}</span>
                    </div>
                </div>`;
                }
            });
            if (rows) {
                diffTable.innerHTML = rows;
                diffSection.classList.remove('d-none');
            } else {
                diffSection.classList.add('d-none');
            }
        } else {
            diffSection.classList.add('d-none');
        }

        document.getElementById('detailLoading').classList.add('d-none');
        document.getElementById('detailContent').classList.remove('d-none');

        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // Reset loading state when modal hides
    document.getElementById('detailModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('detailLoading').classList.remove('d-none');
        document.getElementById('detailContent').classList.add('d-none');
        document.getElementById('diffSection').classList.add('d-none');
    });
</script>

<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .cursor-pointer:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.04) !important;
    }

    pre {
        font-family: 'Menlo', 'Consolas', monospace;
        font-size: 12px;
        line-height: 1.6;
    }
</style>

<?= $this->endSection() ?>