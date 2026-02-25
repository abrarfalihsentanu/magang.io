<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Approval Aktivitas</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-checkbox-circle-line me-2"></i>Approval Aktivitas Mentee
                </h4>
                <p class="mb-0 text-muted">Review dan setujui aktivitas harian mentee Anda</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Pending Approval</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-info"><?= $stats['pending'] ?></h3>
                        </div>
                        <small class="mb-0">Menunggu review</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-time-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card bg-label-warning">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Aktivitas Hari Ini</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $stats['today'] ?></h3>
                        </div>
                        <small class="mb-0">Dari total pending</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-calendar-check-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<?php if (!empty($activities)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-primary d-flex align-items-center justify-content-between">
                <div>
                    <i class="ri-lightbulb-line me-2"></i>
                    <strong>Quick Action:</strong> Pilih beberapa aktivitas untuk approve sekaligus
                </div>
                <button type="button" class="btn btn-sm btn-primary" onclick="bulkApprove()">
                    <i class="ri-check-double-line me-1"></i> Approve Terpilih
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Activities List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 me-2">Daftar Pending Aktivitas</h5>
        <?php if (!empty($activities)): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAllTable">
                <label class="form-check-label" for="selectAllTable">
                    Pilih Semua
                </label>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-body">

        <?php if (empty($activities)): ?>
            <div class="text-center py-5">
                <i class="ri-checkbox-circle-line" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada aktivitas yang perlu di-approve</p>
            </div>
        <?php else: ?>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-paginated">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" class="form-check-input" id="selectAllHeader">
                            </th>
                            <th>Intern</th>
                            <th>Tanggal</th>
                            <th>Aktivitas</th>
                            <th>Kategori</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php foreach ($activities as $act): ?>
                            <?php
                            $kategoriClass = [
                                'learning' => 'info',
                                'task' => 'primary',
                                'meeting' => 'warning',
                                'training' => 'success',
                                'other' => 'secondary'
                            ];
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input activity-checkbox"
                                        value="<?= $act['id_activity'] ?>">
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
                                    <strong><?= date('d M Y', strtotime($act['tanggal'])) ?></strong><br>
                                    <small class="text-muted"><?= date('l', strtotime($act['tanggal'])) ?></small>
                                </td>
                                <td>
                                    <strong><?= esc($act['judul_aktivitas']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= substr(esc($act['deskripsi']), 0, 60) ?>...
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $kategoriClass[$act['kategori']] ?>">
                                        <?= ucfirst($act['kategori']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <i class="ri-time-line me-1"></i>
                                        <?= $act['jam_mulai'] ?> - <?= $act['jam_selesai'] ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-icon btn-label-info"
                                            onclick="viewDetail(<?= $act['id_activity'] ?>)"
                                            title="Lihat Detail">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon btn-label-success"
                                            onclick="approveActivity(<?= $act['id_activity'] ?>, '<?= esc($act['judul_aktivitas']) ?>')"
                                            title="Approve">
                                            <i class="ri-check-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon btn-label-danger"
                                            onclick="rejectActivity(<?= $act['id_activity'] ?>, '<?= esc($act['judul_aktivitas']) ?>')"
                                            title="Reject">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal-activity-approval" tabindex="-1" aria-labelledby="detailModal-activity-approvalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModal-activity-approvalLabel">Detail Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent-activity-approval">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger" id="btnRejectFromModal">
                    <i class="ri-close-line me-1"></i> Reject
                </button>
                <button type="button" class="btn btn-success" id="btnApproveFromModal">
                    <i class="ri-check-line me-1"></i> Approve
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let detailModal;
    let currentActivityId = null;

    document.addEventListener('DOMContentLoaded', function() {
        detailModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-activity-approval'));

        // Select All functionality
        document.getElementById('selectAllTable')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.activity-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        document.getElementById('selectAllHeader')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.activity-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    });

    // View Detail
    function viewDetail(id) {
        currentActivityId = id;

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
        const duration = calculateDuration(activity.jam_mulai, activity.jam_selesai);

        const html = `
        <div class="row">
            <div class="col-12 mb-3">
                <div class="alert alert-secondary">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Intern</small>
                            <strong>${activity.nama_lengkap}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">NIK</small>
                            <strong>${activity.nik}</strong>
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
                <small class="d-block text-muted">${duration} jam</small>
            </div>
            <div class="col-md-4 mb-3">
                <small class="text-muted d-block">Kategori</small>
                <span class="badge bg-label-primary">${activity.kategori}</span>
            </div>
            
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Judul Aktivitas</small>
                <h6>${activity.judul_aktivitas}</h6>
            </div>
            
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Deskripsi</small>
                <p style="white-space: pre-wrap;">${activity.deskripsi}</p>
            </div>
            
            ${activity.attachment ? `
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-2">Lampiran</small>
                <div class="alert alert-info">
                    <i class="ri-attachment-2 me-2"></i>
                    <strong>${activity.attachment}</strong>
                    <a href="<?= base_url('activity/attachment/view/') ?>${activity.id_activity}" 
                       target="_blank" class="btn btn-sm btn-primary ms-2">
                        <i class="ri-eye-line me-1"></i> Lihat
                    </a>
                </div>
            </div>
            ` : ''}
        </div>
    `;

        document.getElementById('detailContent-activity-approval').innerHTML = html;
        detailModal.show();
    }

    function calculateDuration(start, end) {
        const startTime = new Date('2000-01-01 ' + start);
        const endTime = new Date('2000-01-01 ' + end);
        return ((endTime - startTime) / 3600000).toFixed(1);
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

    // Modal action buttons — wait for hide transition before opening next dialog
    document.getElementById('btnApproveFromModal').addEventListener('click', function() {
        if (currentActivityId) {
            const id = currentActivityId;
            const modalEl = document.getElementById('detailModal-activity-approval');
            modalEl.addEventListener('hidden.bs.modal', function handler() {
                modalEl.removeEventListener('hidden.bs.modal', handler);
                approveActivity(id);
            });
            detailModal.hide();
        }
    });

    document.getElementById('btnRejectFromModal').addEventListener('click', function() {
        if (currentActivityId) {
            const id = currentActivityId;
            const modalEl = document.getElementById('detailModal-activity-approval');
            modalEl.addEventListener('hidden.bs.modal', function handler() {
                modalEl.removeEventListener('hidden.bs.modal', handler);
                rejectActivity(id);
            });
            detailModal.hide();
        }
    });

    // Approve Activity
    function approveActivity(id, judul = '') {
        Swal.fire({
            title: 'Approve Aktivitas?',
            html: `
            ${judul ? `<p>Aktivitas: <strong>${judul}</strong></p>` : ''}
            <textarea id="catatan" class="form-control mt-2" placeholder="Catatan untuk intern (opsional)" rows="3"></textarea>
        `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28c76f',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Approve',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            preConfirm: () => {
                return document.getElementById('catatan').value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                processApproval(id, result.value);
            }
        });
    }

    function processApproval(id, catatan) {
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`<?= base_url('activity/approve/') ?>${id}`, {
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
            })
            .catch(() => {
                Swal.fire('Error', 'Terjadi kesalahan', 'error');
            });
    }

    // Reject Activity
    function rejectActivity(id, judul = '') {
        Swal.fire({
            title: 'Reject Aktivitas?',
            html: `
            ${judul ? `<p>Aktivitas: <strong>${judul}</strong></p>` : ''}
            <textarea id="alasan" class="form-control mt-2" placeholder="Alasan penolakan (wajib diisi)" rows="3" required></textarea>
            <small class="text-danger d-block mt-1">* Alasan penolakan wajib diisi</small>
        `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ea5455',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Reject',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            preConfirm: () => {
                const alasan = document.getElementById('alasan').value.trim();
                if (!alasan) {
                    Swal.showValidationMessage('Alasan penolakan wajib diisi');
                    return false;
                }
                return alasan;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                processRejection(id, result.value);
            }
        });
    }

    function processRejection(id, alasan) {
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`<?= base_url('activity/reject/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'catatan=' + encodeURIComponent(alasan)
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
            })
            .catch(() => {
                Swal.fire('Error', 'Terjadi kesalahan', 'error');
            });
    }

    // Bulk Approve
    function bulkApprove() {
        const selected = Array.from(document.querySelectorAll('.activity-checkbox:checked'))
            .map(cb => cb.value);

        if (selected.length === 0) {
            Swal.fire('Info', 'Pilih minimal 1 aktivitas', 'info');
            return;
        }

        Swal.fire({
            title: `Approve ${selected.length} Aktivitas?`,
            text: 'Semua aktivitas yang dipilih akan di-approve',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28c76f',
            confirmButtonText: 'Ya, Approve Semua',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                processBulkApprove(selected);
            }
        });
    }

    function processBulkApprove(ids) {
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('<?= base_url('activity/batch-approve') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'activity_ids=' + JSON.stringify(ids)
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

    // Flash messages
    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success') ?>',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    <?php endif; ?>
</script>

<?= $this->endSection() ?>