<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('activity/my') ?>">Aktivitas Harian</a>
                </li>
                <li class="breadcrumb-item active">Detail Aktivitas</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-file-text-line me-2"></i>Detail Aktivitas
                </h4>
                <p class="mb-0 text-muted">Informasi lengkap aktivitas harian</p>
            </div>
            <div>
                <a href="<?= base_url('activity/my') ?>" class="btn btn-label-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Activity Details Card -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">Informasi Aktivitas</h5>
                <?php
                $statusClass = [
                    'draft' => 'warning',
                    'submitted' => 'info',
                    'approved' => 'success',
                    'rejected' => 'danger'
                ];
                $statusIcon = [
                    'draft' => 'ri-draft-line',
                    'submitted' => 'ri-send-plane-line',
                    'approved' => 'ri-check-line',
                    'rejected' => 'ri-close-line'
                ];
                $status = $activity['status_approval'] ?? 'draft';
                ?>
                <span class="badge bg-label-<?= $statusClass[$status] ?>">
                    <i class="<?= $statusIcon[$status] ?> me-1"></i>
                    <?= ucfirst($status) ?>
                </span>
            </div>
            <div class="card-body">
                <!-- User Info -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-secondary">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Intern</small>
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?= strtoupper(substr($activity['nama_lengkap'], 0, 2)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <strong><?= esc($activity['nama_lengkap']) ?></strong>
                                            <small class="d-block text-muted"><?= $activity['nik'] ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Divisi</small>
                                    <strong class="d-block mt-1"><?= esc($activity['nama_divisi']) ?? '-' ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date & Time Info -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-muted">
                            <i class="ri-calendar-line me-1"></i> Tanggal
                        </label>
                        <p class="fw-semibold mb-0">
                            <?= date('d F Y', strtotime($activity['tanggal'])) ?>
                        </p>
                        <small class="text-muted"><?= date('l', strtotime($activity['tanggal'])) ?></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">
                            <i class="ri-time-line me-1"></i> Jam Mulai
                        </label>
                        <p class="fw-semibold mb-0"><?= $activity['jam_mulai'] ?> WIB</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">
                            <i class="ri-time-line me-1"></i> Jam Selesai
                        </label>
                        <p class="fw-semibold mb-0"><?= $activity['jam_selesai'] ?> WIB</p>
                        <?php
                        $start = strtotime($activity['jam_mulai']);
                        $end = strtotime($activity['jam_selesai']);
                        $duration = ($end - $start) / 3600;
                        ?>
                        <small class="text-muted">Durasi: <?= number_format($duration, 1) ?> jam</small>
                    </div>
                </div>

                <hr>

                <!-- Category -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="ri-folder-line me-1"></i> Kategori
                        </label>
                        <?php
                        $kategoriClass = [
                            'learning' => 'info',
                            'task' => 'primary',
                            'meeting' => 'warning',
                            'training' => 'success',
                            'other' => 'secondary'
                        ];
                        ?>
                        <p>
                            <span class="badge bg-label-<?= $kategoriClass[$activity['kategori']] ?>">
                                <?= ucfirst($activity['kategori']) ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="ri-calendar-check-line me-1"></i> Dibuat Pada
                        </label>
                        <p class="mb-0">
                            <?= date('d F Y, H:i', strtotime($activity['created_at'])) ?> WIB
                        </p>
                    </div>
                </div>

                <hr>

                <!-- Title -->
                <div class="mb-4">
                    <label class="form-label text-muted">
                        <i class="ri-file-text-line me-1"></i> Judul Aktivitas
                    </label>
                    <h5 class="mb-0"><?= esc($activity['judul_aktivitas']) ?></h5>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label text-muted">
                        <i class="ri-align-left me-1"></i> Deskripsi Detail
                    </label>
                    <p class="mb-0" style="white-space: pre-wrap;"><?= esc($activity['deskripsi']) ?></p>
                </div>

                <!-- Attachment -->
                <?php if (!empty($activity['attachment'])): ?>
                    <div class="mb-4">
                        <label class="form-label text-muted">
                            <i class="ri-attachment-line me-1"></i> Lampiran
                        </label>
                        <div class="alert alert-secondary d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="ri-file-line me-2 fs-4"></i>
                                <div>
                                    <strong><?= esc($activity['attachment']) ?></strong>
                                    <small class="d-block text-muted">File attachment</small>
                                </div>
                            </div>
                            <div>
                                <a href="<?= base_url('activity/attachment/view/' . $activity['id_activity']) ?>"
                                    target="_blank" class="btn btn-sm btn-primary me-1">
                                    <i class="ri-eye-line me-1"></i> Lihat
                                </a>
                                <a href="<?= base_url('activity/attachment/download/' . $activity['id_activity']) ?>"
                                    class="btn btn-sm btn-label-primary">
                                    <i class="ri-download-line me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Mentor Feedback -->
                <?php if (!empty($activity['catatan_mentor'])): ?>
                    <div class="mb-0">
                        <label class="form-label text-muted">
                            <i class="ri-message-line me-1"></i> Feedback dari Mentor
                        </label>
                        <div class="alert alert-<?= $status === 'rejected' ? 'danger' : 'info' ?>">
                            <?php if (!empty($activity['mentor_name'])): ?>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar avatar-xs me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <?= strtoupper(substr($activity['mentor_name'], 0, 1)) ?>
                                        </span>
                                    </div>
                                    <small><strong><?= esc($activity['mentor_name']) ?></strong></small>
                                </div>
                            <?php endif; ?>
                            <p class="mb-0" style="white-space: pre-wrap;"><?= esc($activity['catatan_mentor']) ?></p>
                            <?php if (!empty($activity['approved_at'])): ?>
                                <small class="text-muted d-block mt-2">
                                    <i class="ri-time-line me-1"></i>
                                    <?= date('d F Y, H:i', strtotime($activity['approved_at'])) ?> WIB
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Card Footer - Actions -->
            <?php if ($status === 'draft'): ?>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="<?= base_url('activity/edit/' . $activity['id_activity']) ?>"
                        class="btn btn-primary">
                        <i class="ri-pencil-line me-1"></i> Edit Aktivitas
                    </a>
                    <button type="button" class="btn btn-danger"
                        onclick="deleteActivity(<?= $activity['id_activity'] ?>, '<?= esc($activity['judul_aktivitas']) ?>')">
                        <i class="ri-delete-bin-line me-1"></i> Hapus
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title m-0">
                    <i class="ri-flash-line me-1"></i> Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('activity/my') ?>" class="btn btn-outline-primary">
                        <i class="ri-list-check me-1"></i> Lihat Semua Aktivitas
                    </a>
                    <a href="<?= base_url('activity/create') ?>" class="btn btn-outline-success">
                        <i class="ri-add-line me-1"></i> Tambah Aktivitas Baru
                    </a>
                    <?php if ($status === 'draft'): ?>
                        <a href="<?= base_url('activity/edit/' . $activity['id_activity']) ?>"
                            class="btn btn-outline-warning">
                            <i class="ri-pencil-line me-1"></i> Edit Aktivitas Ini
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title m-0">
                    <i class="ri-history-line me-1"></i> Riwayat
                </h6>
            </div>
            <div class="card-body">
                <ul class="timeline mb-0">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Dibuat</h6>
                                <small class="text-muted">
                                    <?= date('d M Y, H:i', strtotime($activity['created_at'])) ?>
                                </small>
                            </div>
                            <p class="mb-0">Aktivitas dibuat</p>
                        </div>
                    </li>

                    <?php if ($activity['updated_at'] != $activity['created_at']): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-warning"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Diperbarui</h6>
                                    <small class="text-muted">
                                        <?= date('d M Y, H:i', strtotime($activity['updated_at'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0">Aktivitas diperbarui</p>
                            </div>
                        </li>
                    <?php endif; ?>

                    <?php if ($status === 'submitted'): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-info"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Submitted</h6>
                                </div>
                                <p class="mb-0">Menunggu approval</p>
                            </div>
                        </li>
                    <?php endif; ?>

                    <?php if (in_array($status, ['approved', 'rejected'])): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-<?= $status === 'approved' ? 'success' : 'danger' ?>"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0"><?= $status === 'approved' ? 'Disetujui' : 'Ditolak' ?></h6>
                                    <small class="text-muted">
                                        <?= !empty($activity['approved_at']) ? date('d M Y, H:i', strtotime($activity['approved_at'])) : '-' ?>
                                    </small>
                                </div>
                                <?php if (!empty($activity['mentor_name'])): ?>
                                    <p class="mb-0">Oleh: <?= esc($activity['mentor_name']) ?></p>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title m-0">
                    <i class="ri-information-line me-1"></i> Informasi Meta
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">ID Aktivitas</span>
                    <code>#<?= $activity['id_activity'] ?></code>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Created At</span>
                    <span class="small"><?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Updated At</span>
                    <span class="small"><?= date('d/m/Y H:i', strtotime($activity['updated_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteActivity(id, judul) {
        Swal.fire({
            title: 'Hapus Aktivitas?',
            html: `Aktivitas "<strong>${judul}</strong>" akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`<?= base_url('activity/delete/') ?>${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
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
                            }).then(() => {
                                window.location.href = '<?= base_url('activity/my') ?>';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan sistem'
                        });
                    });
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

    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?= session()->getFlashdata('error') ?>',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    <?php endif; ?>
</script>

<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
        list-style: none;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0;
        bottom: -1.5rem;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-point {
        position: absolute;
        left: -1.8rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .timeline-point-primary {
        background: #696cff;
        box-shadow: 0 0 0 2px #696cff;
    }

    .timeline-point-warning {
        background: #ff9f43;
        box-shadow: 0 0 0 2px #ff9f43;
    }

    .timeline-point-info {
        background: #00cfe8;
        box-shadow: 0 0 0 2px #00cfe8;
    }

    .timeline-point-success {
        background: #28c76f;
        box-shadow: 0 0 0 2px #28c76f;
    }

    .timeline-point-danger {
        background: #ea5455;
        box-shadow: 0 0 0 2px #ea5455;
    }
</style>

<?= $this->endSection() ?>