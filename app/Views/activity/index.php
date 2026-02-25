<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Semua Aktivitas</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-file-list-3-line me-2"></i>Semua Aktivitas Harian
                </h4>
                <p class="mb-0 text-muted">Monitoring dan analisis aktivitas seluruh intern</p>
            </div>
            <div>
                <a href="<?= base_url('activity/dashboard') ?>" class="btn btn-label-primary me-2">
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
                    <input type="text" class="form-control" name="search" placeholder="Nama / judul aktivitas..."
                        value="<?= $filters['search'] ?? '' ?>">
                </div>

                <!-- Date Range -->
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" name="start_date"
                        value="<?= $filters['start_date'] ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="end_date"
                        value="<?= $filters['end_date'] ?>">
                </div>

                <!-- Status -->
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="submitted" <?= ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                        <option value="approved" <?= ($filters['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>

                <!-- Kategori -->
                <div class="col-md-1">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="kategori">
                        <option value="">Semua</option>
                        <option value="learning" <?= ($filters['kategori'] ?? '') === 'learning' ? 'selected' : '' ?>>Learning</option>
                        <option value="task" <?= ($filters['kategori'] ?? '') === 'task' ? 'selected' : '' ?>>Task</option>
                        <option value="meeting" <?= ($filters['kategori'] ?? '') === 'meeting' ? 'selected' : '' ?>>Meeting</option>
                        <option value="training" <?= ($filters['kategori'] ?? '') === 'training' ? 'selected' : '' ?>>Training</option>
                        <option value="other" <?= ($filters['kategori'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
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

                <!-- Action Buttons -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i> Terapkan Filter
                    </button>
                    <button type="button" class="btn btn-label-secondary" onclick="resetFilter()">
                        <i class="ri-refresh-line me-1"></i> Reset
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Activities Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 me-2">Daftar Aktivitas</h5>
        <span class="badge bg-label-primary"><?= isset($total) ? $total : count($activities) ?> Records</span>
    </div>
    <div class="card-body">

        <?php if (empty($activities)): ?>
            <div class="text-center py-5">
                <i class="ri-file-list-line" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada aktivitas ditemukan</p>
                <small class="text-muted">Coba ubah filter atau periode tanggal</small>
            </div>
        <?php else: ?>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="activitiesTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Intern</th>
                            <th>Divisi</th>
                            <th>Aktivitas</th>
                            <th>Kategori</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Mentor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = ($currentPage - 1) * 10 + 1;
                        $statusClass = [
                            'draft' => 'warning',
                            'submitted' => 'info',
                            'approved' => 'success',
                            'rejected' => 'danger'
                        ];
                        $kategoriClass = [
                            'learning' => 'info',
                            'task' => 'primary',
                            'meeting' => 'warning',
                            'training' => 'success',
                            'other' => 'secondary'
                        ];
                        foreach ($activities as $act):
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= date('d/m/Y', strtotime($act['tanggal'])) ?></strong><br>
                                    <small class="text-muted"><?= date('D', strtotime($act['tanggal'])) ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?= strtoupper(substr($act['nama_lengkap'], 0, 2)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <strong><?= esc($act['nama_lengkap']) ?></strong>
                                            <small class="d-block text-muted"><?= $act['nik'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small><?= esc($act['nama_divisi']) ?? '-' ?></small>
                                </td>
                                <td>
                                    <strong class="d-block mb-1"><?= esc($act['judul_aktivitas']) ?></strong>
                                    <small class="text-muted">
                                        <?= substr(esc($act['deskripsi']), 0, 50) ?>...
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $kategoriClass[$act['kategori']] ?>">
                                        <?= ucfirst($act['kategori']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <?= $act['jam_mulai'] ?><br>
                                        <?= $act['jam_selesai'] ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $statusClass[$act['status_approval']] ?>">
                                        <?= ucfirst($act['status_approval']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?= esc($act['mentor_name']) ?? '-' ?></small>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="viewDetail(<?= $act['id_activity'] ?>)">
                                                    <i class="ri-eye-line me-2"></i> Lihat Detail
                                                </a>
                                            </li>
                                            <?php if ($act['attachment']): ?>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="<?= base_url('activity/attachment/view/' . $act['id_activity']) ?>"
                                                        target="_blank">
                                                        <i class="ri-attachment-line me-2"></i> Lihat Lampiran
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($act['status_approval'] === 'submitted'): ?>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-success" href="javascript:void(0);"
                                                        onclick="approveActivity(<?= $act['id_activity'] ?>)">
                                                        <i class="ri-check-line me-2"></i> Approve
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                        onclick="rejectActivity(<?= $act['id_activity'] ?>)">
                                                        <i class="ri-close-line me-2"></i> Reject
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

<!-- Detail Modal -->
<div class="modal fade" id="detailModal-activity-index" tabindex="-1" aria-labelledby="detailModal-activity-indexLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModal-activity-indexLabel">Detail Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent-activity-index">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    let detailModal;

    document.addEventListener('DOMContentLoaded', function() {
        detailModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-activity-index'));
    });

    function resetFilter() {
        window.location.href = '<?= base_url('activity') ?>';
    }

    function exportData() {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams(new FormData(form));
        window.location.href = '<?= base_url('activity/export') ?>?' + params.toString();
    }

    function viewDetail(id) {
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`<?= base_url('activity/detail/') ?>${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    showDetailModal(data.activity);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire('Error', 'Terjadi kesalahan', 'error');
            });
    }

    function showDetailModal(activity) {
        const statusClass = {
            'draft': 'warning',
            'submitted': 'info',
            'approved': 'success',
            'rejected': 'danger'
        };

        const html = `
        <div class="row">
            <div class="col-12 mb-3">
                <div class="alert alert-secondary">
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Intern</small>
                            <strong>${activity.nama_lengkap}</strong>
                            <small class="d-block">${activity.nik}</small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Divisi</small>
                            <strong>${activity.nama_divisi || '-'}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-${statusClass[activity.status_approval]}">
                                ${activity.status_approval}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <small class="text-muted d-block">Tanggal</small>
                <strong>${formatDate(activity.tanggal)}</strong>
            </div>
            <div class="col-md-4 mb-3">
                <small class="text-muted d-block">Waktu</small>
                <strong>${activity.jam_mulai} - ${activity.jam_selesai}</strong>
            </div>
            <div class="col-md-4 mb-3">
                <small class="text-muted d-block">Kategori</small>
                <span class="badge bg-label-primary">${activity.kategori}</span>
            </div>
            
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Judul</small>
                <h6>${activity.judul_aktivitas}</h6>
            </div>
            
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Deskripsi</small>
                <p style="white-space: pre-wrap;">${activity.deskripsi}</p>
            </div>
            
            ${activity.catatan_mentor ? `
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Catatan Mentor</small>
                <div class="alert alert-${activity.status_approval === 'rejected' ? 'danger' : 'info'}">
                    ${activity.catatan_mentor}
                </div>
            </div>
            ` : ''}
            
            ${activity.attachment ? `
            <div class="col-12">
                <small class="text-muted d-block mb-2">Lampiran</small>
                <div class="alert alert-info">
                    <i class="ri-attachment-2 me-2"></i>
                    <strong>${activity.attachment}</strong>
                    <a href="<?= base_url('activity/attachment/view/') ?>${activity.id_activity}" 
                       target="_blank" class="btn btn-sm btn-primary ms-2">
                        Lihat
                    </a>
                </div>
            </div>
            ` : ''}
        </div>
    `;

        document.getElementById('detailContent-activity-index').innerHTML = html;
        detailModal.show();
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const options = {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        };
        return date.toLocaleDateString('id-ID', options);
    }

    function approveActivity(id) {
        Swal.fire({
            title: 'Approve Aktivitas?',
            html: '<textarea id="catatan" class="form-control" placeholder="Catatan (opsional)" rows="3"></textarea>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28c76f',
            confirmButtonText: 'Ya, Approve',
            reverseButtons: true,
            preConfirm: () => document.getElementById('catatan').value
        }).then((result) => {
            if (result.isConfirmed) {
                processAction('approve', id, result.value);
            }
        });
    }

    function rejectActivity(id) {
        Swal.fire({
            title: 'Reject Aktivitas?',
            html: '<textarea id="alasan" class="form-control" placeholder="Alasan penolakan (wajib)" rows="3" required></textarea>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ea5455',
            confirmButtonText: 'Ya, Reject',
            reverseButtons: true,
            preConfirm: () => {
                const alasan = document.getElementById('alasan').value.trim();
                if (!alasan) {
                    Swal.showValidationMessage('Alasan wajib diisi');
                    return false;
                }
                return alasan;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                processAction('reject', id, result.value);
            }
        });
    }

    function processAction(action, id, catatan) {
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`<?= base_url('activity/') ?>${action}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'catatan=' + encodeURIComponent(catatan)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            });
    }
</script>

<?= $this->endSection() ?>