<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Semua Project</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-file-list-3-line me-2"></i>Semua Weekly Project
                </h4>
                <p class="mb-0 text-muted">Monitoring dan analisis seluruh project intern</p>
            </div>
            <div>
                <a href="<?= base_url('project/dashboard') ?>" class="btn btn-label-primary me-2">
                    <i class="ri-dashboard-line me-1"></i> Dashboard
                </a>
                <button type="button" class="btn btn-success" onclick="exportData()">
                    <i class="ri-file-excel-line me-1"></i> Export Excel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title m-0 me-2">
            <i class="ri-filter-3-line me-2"></i>Filter Data
        </h5>
    </div>
    <div class="card-body">
        <form id="filterForm" method="GET">
            <div class="row g-3">
                <!-- Search -->
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" class="form-control" name="search" placeholder="Nama / judul project..."
                        value="<?= $filters['search'] ?? '' ?>">
                </div>

                <!-- Tahun -->
                <div class="col-md-1">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" name="tahun">
                        <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                            <option value="<?= $y ?>" <?= $filters['tahun'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Week Number -->
                <div class="col-md-2">
                    <label class="form-label">Week</label>
                    <select class="form-select" name="week_number">
                        <option value="">Semua Week</option>
                        <?php for ($w = 1; $w <= 52; $w++): ?>
                            <option value="<?= $w ?>" <?= ($filters['week_number'] ?? '') == $w ? 'selected' : '' ?>>Week <?= $w ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="submitted" <?= ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                        <option value="assessed" <?= ($filters['status'] ?? '') === 'assessed' ? 'selected' : '' ?>>Assessed</option>
                    </select>
                </div>

                <!-- Tipe Project -->
                <div class="col-md-2">
                    <label class="form-label">Tipe</label>
                    <select class="form-select" name="tipe_project">
                        <option value="">Semua Tipe</option>
                        <option value="inisiatif" <?= ($filters['tipe_project'] ?? '') === 'inisiatif' ? 'selected' : '' ?>>Inisiatif</option>
                        <option value="assigned" <?= ($filters['tipe_project'] ?? '') === 'assigned' ? 'selected' : '' ?>>Assigned</option>
                    </select>
                </div>

                <!-- Divisi -->
                <div class="col-md-2">
                    <label class="form-label">Divisi</label>
                    <select class="form-select" name="divisi">
                        <option value="">Semua Divisi</option>
                        <?php foreach ($divisi_list as $div): ?>
                            <option value="<?= $div['id_divisi'] ?>"
                                <?= ($filters['divisi'] ?? '') == $div['id_divisi'] ? 'selected' : '' ?>>
                                <?= esc($div['nama_divisi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i> Filter
                    </button>
                    <a href="<?= base_url('project') ?>" class="btn btn-label-secondary">
                        <i class="ri-refresh-line me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Projects Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 me-2">Daftar Project</h5>
        <span class="badge bg-label-primary"><?= isset($total) ? $total : count($projects) ?> Records</span>
    </div>
    <div class="card-body">

        <?php if (empty($projects)): ?>
            <div class="text-center py-5">
                <i class="ri-file-list-line" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada project ditemukan</p>
                <small class="text-muted">Coba ubah filter atau periode</small>
            </div>
        <?php else: ?>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Week</th>
                            <th>Intern</th>
                            <th>Divisi</th>
                            <th>Judul Project</th>
                            <th>Tipe</th>
                            <th>Progress</th>
                            <th>Self Rating</th>
                            <th>Mentor Rating</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = ($currentPage - 1) * 10 + 1;
                        $statusClass = [
                            'draft' => 'warning',
                            'submitted' => 'info',
                            'assessed' => 'success'
                        ];
                        $tipeClass = [
                            'inisiatif' => 'primary',
                            'assigned' => 'secondary'
                        ];
                        foreach ($projects as $proj):
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong>Week <?= $proj['week_number'] ?></strong><br>
                                    <small class="text-muted"><?= $proj['tahun'] ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?= strtoupper(substr($proj['nama_lengkap'], 0, 2)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <strong><?= esc($proj['nama_lengkap']) ?></strong>
                                            <small class="d-block text-muted"><?= $proj['nik'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small><?= esc($proj['nama_divisi']) ?? '-' ?></small>
                                </td>
                                <td>
                                    <strong class="d-block mb-1"><?= esc($proj['judul_project']) ?></strong>
                                    <small class="text-muted">
                                        <?= substr(esc($proj['deskripsi']), 0, 50) ?>...
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $tipeClass[$proj['tipe_project']] ?>">
                                        <?= ucfirst($proj['tipe_project']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center" style="min-width: 100px;">
                                        <div class="progress w-100 me-2" style="height: 6px;">
                                            <div class="progress-bar" style="width: <?= $proj['progress'] ?>%"></div>
                                        </div>
                                        <small><?= $proj['progress'] ?>%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($proj['self_rating']): ?>
                                        <span class="badge bg-label-warning">
                                            <i class="ri-star-fill"></i> <?= $proj['self_rating'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($proj['mentor_rating']): ?>
                                        <span class="badge bg-label-success">
                                            <i class="ri-star-fill"></i> <?= $proj['mentor_rating'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $statusClass[$proj['status_submission']] ?>">
                                        <?= ucfirst($proj['status_submission']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('project/detail/' . $proj['id_project']) ?>">
                                                    <i class="ri-eye-line me-2"></i> Lihat Detail
                                                </a>
                                            </li>
                                            <?php if ($proj['attachment']): ?>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="<?= base_url('project/attachment/view/' . $proj['id_project']) ?>"
                                                        target="_blank">
                                                        <i class="ri-attachment-line me-2"></i> Lihat Attachment
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (isset($pager)): ?>
                <?= view('components/pagination_wrapper', [
                    'pager' => $pager,
                    'total' => $total ?? 0,
                    'perPage' => $perPage ?? 10,
                    'currentPage' => $currentPage ?? 1
                ]) ?>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<script>
    function exportData() {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams(new FormData(form));
        window.location.href = '<?= base_url('project/export') ?>?' + params.toString();
    }
</script>

<?= $this->endSection() ?>