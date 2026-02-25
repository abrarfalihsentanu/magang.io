<?php
function daysLeft(string $date): int
{
    return (int)ceil((strtotime($date) - time()) / 86400);
}
function periodBadge(string $date): array
{
    $days = daysLeft($date);
    if ($days < 0)  return ['Selesai',           'secondary'];
    if ($days <= 7)  return ['Berakhir ' . $days . ' hari lagi', 'danger'];
    if ($days <= 30) return ['Berakhir ' . $days . ' hari lagi', 'warning'];
    return ['Aktif',                 'success'];
}
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Arsip Pemagang</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-semibold">
                        <i class="ri-archive-line text-primary me-2"></i>Arsip Pemagang
                    </h4>
                    <p class="text-muted mb-0">Kelola dan arsipkan data pemagang yang telah menyelesaikan masa magang</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4 g-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="avatar avatar-lg flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-user-star-line ri-28px"></i>
                        </span>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold"><?= $stats['active'] ?></div>
                        <small class="text-muted">Pemagang Aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="avatar avatar-lg flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line ri-28px"></i>
                        </span>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold"><?= $stats['expiring'] ?></div>
                        <small class="text-muted">Berakhir &le; 30 Hari</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="avatar avatar-lg flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-archive-drawer-line ri-28px"></i>
                        </span>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold"><?= $stats['archived'] ?></div>
                        <small class="text-muted">Sudah Diarsipkan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            <i class="ri-alert-line me-2"></i><?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="card">
        <div class="card-header border-0">
            <ul class="nav nav-tabs card-header-tabs" id="archiveTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-active" data-bs-toggle="tab"
                        data-bs-target="#pane-active" type="button" role="tab">
                        <i class="ri-user-star-line me-1"></i>Pemagang Aktif
                        <span class="badge bg-primary ms-1"><?= $stats['active'] ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-archived" data-bs-toggle="tab"
                        data-bs-target="#pane-archived" type="button" role="tab">
                        <i class="ri-archive-line me-1"></i>Sudah Diarsipkan
                        <span class="badge bg-secondary ms-1"><?= $stats['archived'] ?></span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content">

            <!-- =====================================================
                 TAB 1 – Active interns (archive candidates)
                 ===================================================== -->
            <div class="tab-pane fade show active" id="pane-active" role="tabpanel">
                <?php if (empty($activeInterns)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="ri-user-smile-line ri-48px opacity-25 d-block mb-2"></i>
                        <p class="mb-0 fw-semibold">Tidak ada pemagang aktif saat ini</p>
                    </div>
                <?php else: ?>
                    <!-- Toolbar -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label small text-muted" for="checkAll">Pilih semua</label>
                            </div>
                            <span class="badge bg-label-primary d-none" id="selectedCount">0 dipilih</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-danger btn-sm d-none" id="btnArchiveSelected" type="button">
                                <i class="ri-archive-line me-1"></i>Arsipkan yang Dipilih
                            </button>
                            <button class="btn btn-warning btn-sm" id="btnArchiveAll" type="button">
                                <i class="ri-archive-2-line me-1"></i>Proses Arsip Semua (<?= count($activeInterns) ?>)
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-paginated align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width:40px"></th>
                                    <th>Pemagang</th>
                                    <th>Divisi</th>
                                    <th>Periode Magang</th>
                                    <th>Mentor</th>
                                    <th>Status Periode</th>
                                    <th class="text-center" style="width:110px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activeInterns as $intern): ?>
                                    <?php [$badgeLabel, $badgeColor] = periodBadge($intern['periode_selesai']); ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input intern-check" type="checkbox"
                                                    value="<?= $intern['id_intern'] ?>"
                                                    data-name="<?= esc($intern['nama_lengkap']) ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar avatar-sm flex-shrink-0">
                                                    <?php if (!empty($intern['foto'])): ?>
                                                        <img src="<?= base_url('uploads/' . ltrim($intern['foto'], '/uploads/')) ?>"
                                                            alt="" class="rounded-circle" style="width:36px;height:36px;object-fit:cover">
                                                    <?php else: ?>
                                                        <span class="avatar-initial rounded-circle bg-label-primary" style="font-size:13px">
                                                            <?= mb_strtoupper(mb_substr($intern['nama_lengkap'], 0, 1)) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </span>
                                                <div>
                                                    <div class="fw-semibold small"><?= esc($intern['nama_lengkap']) ?></div>
                                                    <div class="text-muted" style="font-size:11px"><?= esc($intern['nik']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small"><?= esc($intern['nama_divisi'] ?? '-') ?></span>
                                        </td>
                                        <td>
                                            <div class="small"><?= date('d M Y', strtotime($intern['periode_mulai'])) ?></div>
                                            <div class="text-muted" style="font-size:11px">
                                                s/d <?= date('d M Y', strtotime($intern['periode_selesai'])) ?>
                                            </div>
                                        </td>
                                        <td><span class="small"><?= esc($intern['nama_mentor'] ?? '-') ?></span></td>
                                        <td>
                                            <span class="badge bg-<?= $badgeColor ?>">
                                                <?= $badgeLabel ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger btn-archive-single"
                                                type="button"
                                                data-id="<?= $intern['id_intern'] ?>"
                                                data-name="<?= esc($intern['nama_lengkap']) ?>"
                                                title="Arsipkan">
                                                <i class="ri-archive-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- =====================================================
                 TAB 2 – Archived interns list
                 ===================================================== -->
            <div class="tab-pane fade" id="pane-archived" role="tabpanel">
                <?php if (empty($archivedList)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="ri-archive-line ri-48px opacity-25 d-block mb-2"></i>
                        <p class="mb-0 fw-semibold">Belum ada pemagang yang diarsipkan</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-paginated align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width:50px">#</th>
                                    <th>Nama</th>
                                    <th>Divisi</th>
                                    <th>Periode</th>
                                    <th>KPI Score</th>
                                    <th>Kehadiran</th>
                                    <th>Uang Saku</th>
                                    <th>Diarsipkan</th>
                                    <th class="text-center" style="width:80px">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($archivedList as $i => $arc): ?>
                                    <tr>
                                        <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                                        <td>
                                            <div class="fw-semibold small"><?= esc($arc['nama_lengkap']) ?></div>
                                            <div class="text-muted" style="font-size:11px"><?= esc($arc['nik']) ?></div>
                                        </td>
                                        <td><span class="small"><?= esc($arc['divisi']) ?></span></td>
                                        <td>
                                            <div class="small"><?= date('d M Y', strtotime($arc['periode_mulai'])) ?></div>
                                            <div class="text-muted" style="font-size:11px">
                                                s/d <?= date('d M Y', strtotime($arc['periode_selesai'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $kpi = (float)$arc['final_kpi_score'];
                                            $kc  = $kpi >= 90 ? 'success' : ($kpi >= 75 ? 'primary' : ($kpi >= 60 ? 'warning' : 'danger'));
                                            ?>
                                            <span class="badge bg-label-<?= $kc ?>"><?= number_format($kpi, 1) ?></span>
                                        </td>
                                        <td>
                                            <span class="small"><?= number_format((float)$arc['persentase_kehadiran'], 1) ?>%</span>
                                        </td>
                                        <td>
                                            <span class="small">Rp <?= number_format((float)$arc['total_uang_saku'], 0, ',', '.') ?></span>
                                        </td>
                                        <td>
                                            <div class="small"><?= date('d M Y', strtotime($arc['archived_at'])) ?></div>
                                            <div class="text-muted" style="font-size:11px">
                                                oleh <?= esc($arc['archived_by_name'] ?? '-') ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('archive/view/' . $arc['id_archive']) ?>"
                                                class="btn btn-icon btn-sm btn-outline-primary" title="Lihat Detail">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div><!-- /tab-content -->
    </div><!-- /card -->
</div>

<!-- ===================================================================
     Archive Confirmation Modal
     =================================================================== -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-archive-line text-danger me-2"></i>Konfirmasi Arsipkan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="ri-alert-line me-1"></i>
                    <strong>Perhatian:</strong> Proses ini akan mengarsipkan pemagang dan menonaktifkan akun mereka.
                    Tindakan ini <strong>tidak dapat dibatalkan</strong>.
                </div>
                <p>Anda akan mengarsipkan:</p>
                <ul id="archiveNameList" class="mb-3"></ul>
                <div class="mb-0">
                    <label class="form-label fw-semibold">Keterangan <span class="text-muted fw-normal">(opsional)</span></label>
                    <textarea class="form-control" id="archiveKeterangan" rows="2"
                        placeholder="Contoh: Selesai masa magang, mengundurkan diri, dll."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnConfirmArchive">
                    <span class="spinner-border spinner-border-sm d-none me-1" id="archiveSpinner"></span>
                    <i class="ri-archive-line me-1" id="archiveBtnIcon"></i>Ya, Arsipkan
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const archiveUrl = '<?= base_url('archive/process') ?>';
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';

        let pendingIds = [];
        const modalEl = document.getElementById('archiveModal');
        if (!modalEl) return;
        const modal = new bootstrap.Modal(modalEl);
        const nameList = document.getElementById('archiveNameList');
        const spinner = document.getElementById('archiveSpinner');
        const btnIcon = document.getElementById('archiveBtnIcon');
        const confirmBtn = document.getElementById('btnConfirmArchive');

        // ── Select all checkbox ──────────────────────────────────────
        const checkAll = document.getElementById('checkAll');
        const countBadge = document.getElementById('selectedCount');
        const bulkBtn = document.getElementById('btnArchiveSelected');

        function updateBulkUi() {
            const checked = document.querySelectorAll('.intern-check:checked');
            const n = checked.length;
            if (countBadge) {
                if (n > 0) {
                    countBadge.textContent = n + ' dipilih';
                    countBadge.classList.remove('d-none');
                    if (bulkBtn) bulkBtn.classList.remove('d-none');
                } else {
                    countBadge.classList.add('d-none');
                    if (bulkBtn) bulkBtn.classList.add('d-none');
                }
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                document.querySelectorAll('.intern-check').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateBulkUi();
            });
        }

        document.querySelectorAll('.intern-check').forEach(cb => {
            cb.addEventListener('change', updateBulkUi);
        });

        // ── Open modal helpers ────────────────────────────────────────
        function openModal(ids, names) {
            pendingIds = ids;
            nameList.innerHTML = names.map(n => `<li class="fw-semibold">${n}</li>`).join('');
            document.getElementById('archiveKeterangan').value = '';
            modal.show();
        }

        // ── Single archive button ─────────────────────────────────────
        document.querySelectorAll('.btn-archive-single').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openModal([parseInt(this.dataset.id)], [this.dataset.name]);
            });
        });

        // ── Bulk archive selected button ──────────────────────────────
        if (bulkBtn) {
            bulkBtn.addEventListener('click', function() {
                const checked = document.querySelectorAll('.intern-check:checked');
                const ids = [];
                const names = [];
                checked.forEach(cb => {
                    ids.push(parseInt(cb.value));
                    names.push(cb.dataset.name);
                });
                if (ids.length === 0) return;
                openModal(ids, names);
            });
        }

        // ── Archive ALL button ────────────────────────────────────────
        const btnArchiveAll = document.getElementById('btnArchiveAll');
        if (btnArchiveAll) {
            btnArchiveAll.addEventListener('click', function() {
                const allCheckboxes = document.querySelectorAll('.intern-check');
                const ids = [];
                const names = [];
                allCheckboxes.forEach(cb => {
                    ids.push(parseInt(cb.value));
                    names.push(cb.dataset.name);
                });
                if (ids.length === 0) return;
                openModal(ids, names);
            });
        }

        // ── Execute archive (shared helper) ───────────────────────────
        async function executeArchive() {
            spinner.classList.remove('d-none');
            btnIcon.classList.add('d-none');
            confirmBtn.disabled = true;

            const keterangan = document.getElementById('archiveKeterangan').value.trim();

            const body = new FormData();
            pendingIds.forEach(id => body.append('id_interns[]', id));
            if (keterangan) body.append('keterangan', keterangan);
            body.append(csrfName, csrfHash);

            try {
                const res = await fetch(archiveUrl, {
                    method: 'POST',
                    body
                });
                const data = await res.json();

                modal.hide();
                spinner.classList.add('d-none');
                btnIcon.classList.remove('d-none');
                confirmBtn.disabled = false;

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                }
            } catch (e) {
                modal.hide();
                spinner.classList.add('d-none');
                btnIcon.classList.remove('d-none');
                confirmBtn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan jaringan',
                    confirmButtonText: 'OK'
                });
            }
        }

        // ── Confirm archive ───────────────────────────────────────────
        confirmBtn.addEventListener('click', executeArchive);
    });
</script>
<?= $this->endSection() ?>