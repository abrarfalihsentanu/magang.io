<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('project/my') ?>">Weekly Project</a></li>
                <li class="breadcrumb-item active">Detail Project</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-file-text-line me-2"></i>Detail Project
                </h4>
                <p class="mb-0 text-muted">Informasi lengkap weekly project</p>
            </div>
            <div>
                <a href="<?= base_url('project/my') ?>" class="btn btn-label-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Project Details Card -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">Informasi Project</h5>
                <?php
                $statusClass = [
                    'draft' => 'warning',
                    'submitted' => 'info',
                    'assessed' => 'success'
                ];
                $statusIcon = [
                    'draft' => 'ri-draft-line',
                    'submitted' => 'ri-send-plane-line',
                    'assessed' => 'ri-check-line'
                ];
                $status = $project['status_submission'];
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
                                                <?= strtoupper(substr($project['nama_lengkap'], 0, 2)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <strong><?= esc($project['nama_lengkap']) ?></strong>
                                            <small class="d-block text-muted"><?= $project['nik'] ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Divisi</small>
                                    <strong class="d-block mt-1"><?= esc($project['nama_divisi']) ?? '-' ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Week & Periode Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="ri-calendar-line me-1"></i> Week
                        </label>
                        <p class="fw-semibold mb-0">
                            Week <?= $project['week_number'] ?> - <?= $project['tahun'] ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="ri-calendar-check-line me-1"></i> Periode
                        </label>
                        <p class="fw-semibold mb-0">
                            <?= date('d M', strtotime($project['periode_mulai'])) ?> - 
                            <?= date('d M Y', strtotime($project['periode_selesai'])) ?>
                        </p>
                    </div>
                </div>

                <hr>

                <!-- Tipe & Progress -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="ri-folder-line me-1"></i> Tipe Project
                        </label>
                        <?php
                        $tipeClass = [
                            'inisiatif' => 'primary',
                            'assigned' => 'secondary'
                        ];
                        ?>
                        <p>
                            <span class="badge bg-label-<?= $tipeClass[$project['tipe_project']] ?>">
                                <?= ucfirst($project['tipe_project']) ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="ri-line-chart-line me-1"></i> Progress
                        </label>
                        <div class="d-flex align-items-center">
                            <div class="progress w-100 me-2" style="height: 12px;">
                                <div class="progress-bar" role="progressbar" 
                                    style="width: <?= $project['progress'] ?>%"
                                    aria-valuenow="<?= $project['progress'] ?>" 
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <span class="fw-semibold"><?= $project['progress'] ?>%</span>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Judul Project -->
                <div class="mb-4">
                    <label class="form-label text-muted">
                        <i class="ri-file-text-line me-1"></i> Judul Project
                    </label>
                    <h5 class="mb-0"><?= esc($project['judul_project']) ?></h5>
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="form-label text-muted">
                        <i class="ri-align-left me-1"></i> Deskripsi Project
                    </label>
                    <p class="mb-0" style="white-space: pre-wrap;"><?= esc($project['deskripsi']) ?></p>
                </div>

                <!-- Deliverables -->
                <div class="mb-4">
                    <label class="form-label text-muted">
                        <i class="ri-gift-line me-1"></i> Deliverables / Output
                    </label>
                    <p class="mb-0" style="white-space: pre-wrap;"><?= esc($project['deliverables']) ?></p>
                </div>

                <!-- Attachment -->
                <?php if (!empty($project['attachment'])): ?>
                    <div class="mb-4">
                        <label class="form-label text-muted">
                            <i class="ri-attachment-line me-1"></i> Attachment
                        </label>
                        <div class="alert alert-secondary d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="ri-file-line me-2 fs-4"></i>
                                <div>
                                    <strong><?= esc($project['attachment']) ?></strong>
                                    <small class="d-block text-muted">File attachment</small>
                                </div>
                            </div>
                            <div>
                                <a href="<?= base_url('project/attachment/view/' . $project['id_project']) ?>"
                                    target="_blank" class="btn btn-sm btn-primary me-1">
                                    <i class="ri-eye-line me-1"></i> Lihat
                                </a>
                                <a href="<?= base_url('project/attachment/download/' . $project['id_project']) ?>"
                                    class="btn btn-sm btn-label-primary">
                                    <i class="ri-download-line me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Ratings -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="ri-star-line me-1"></i> Self Rating
                        </label>
                        <?php if ($project['self_rating']): ?>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2" style="font-size: 18px;">
                                    <i class="ri-star-fill"></i> <?= number_format($project['self_rating'], 1) ?>
                                </span>
                                <small class="text-muted">/ 5.0</small>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">-</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="ri-star-fill me-1"></i> Mentor Rating
                        </label>
                        <?php if ($project['mentor_rating']): ?>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="font-size: 18px;">
                                    <i class="ri-star-fill"></i> <?= number_format($project['mentor_rating'], 1) ?>
                                </span>
                                <small class="text-muted">/ 5.0</small>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Belum dinilai</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mentor Feedback -->
                <?php if (!empty($project['feedback_mentor'])): ?>
                    <div class="mb-0">
                        <label class="form-label text-muted">
                            <i class="ri-message-line me-1"></i> Feedback dari Mentor
                        </label>
                        <div class="alert alert-info">
                            <?php if (!empty($project['assessor_name'])): ?>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar avatar-xs me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <?= strtoupper(substr($project['assessor_name'], 0, 1)) ?>
                                        </span>
                                    </div>
                                    <small><strong><?= esc($project['assessor_name']) ?></strong></small>
                                </div>
                            <?php endif; ?>
                            <p class="mb-0" style="white-space: pre-wrap;"><?= esc($project['feedback_mentor']) ?></p>
                            <?php if (!empty($project['assessed_at'])): ?>
                                <small class="text-muted d-block mt-2">
                                    <i class="ri-time-line me-1"></i>
                                    <?= date('d F Y, H:i', strtotime($project['assessed_at'])) ?> WIB
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Card Footer - Actions -->
            <?php if ($status === 'draft'): ?>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="<?= base_url('project/edit/' . $project['id_project']) ?>"
                        class="btn btn-primary">
                        <i class="ri-pencil-line me-1"></i> Edit Project
                    </a>
                    <button type="button" class="btn btn-danger"
                        onclick="deleteProject(<?= $project['id_project'] ?>, '<?= esc($project['judul_project']) ?>')">
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
                    <a href="<?= base_url('project/my') ?>" class="btn btn-outline-primary">
                        <i class="ri-list-check me-1"></i> Lihat Semua Project
                    </a>
                    <?php if ($status === 'draft'): ?>
                        <a href="<?= base_url('project/edit/' . $project['id_project']) ?>"
                            class="btn btn-outline-warning">
                            <i class="ri-pencil-line me-1"></i> Edit Project Ini
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title m-0">
                    <i class="ri-history-line me-1"></i> Timeline
                </h6>
            </div>
            <div class="card-body">
                <ul class="timeline mb-0">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Created</h6>
                                <small class="text-muted">
                                    <?= date('d M Y, H:i', strtotime($project['created_at'])) ?>
                                </small>
                            </div>
                            <p class="mb-0">Project dibuat</p>
                        </div>
                    </li>

                    <?php if ($project['updated_at'] != $project['created_at']): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-warning"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Updated</h6>
                                    <small class="text-muted">
                                        <?= date('d M Y, H:i', strtotime($project['updated_at'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0">Project diperbarui</p>
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
                                <p class="mb-0">Menunggu assessment</p>
                            </div>
                        </li>
                    <?php endif; ?>

                    <?php if ($status === 'assessed'): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-success"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Assessed</h6>
                                    <small class="text-muted">
                                        <?= !empty($project['assessed_at']) ? date('d M Y, H:i', strtotime($project['assessed_at'])) : '-' ?>
                                    </small>
                                </div>
                                <?php if (!empty($project['assessor_name'])): ?>
                                    <p class="mb-0">Oleh: <?= esc($project['assessor_name']) ?></p>
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
                    <span class="text-muted">ID Project</span>
                    <code>#<?= $project['id_project'] ?></code>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Created At</span>
                    <span class="small"><?= date('d/m/Y H:i', strtotime($project['created_at'])) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Updated At</span>
                    <span class="small"><?= date('d/m/Y H:i', strtotime($project['updated_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteProject(id, judul) {
        Swal.fire({
            title: 'Hapus Project?',
            html: `Project "<strong>${judul}</strong>" akan dihapus permanen.`,
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

                fetch(`<?= base_url('project/delete/') ?>${id}`, {
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
                                window.location.href = '<?= base_url('project/my') ?>';
                            });
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
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
</style>

<?= $this->endSection() ?>