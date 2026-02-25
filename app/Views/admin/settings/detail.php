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
                    <a href="<?= base_url('settings') ?>">Pengaturan Sistem</a>
                </li>
                <li class="breadcrumb-item active">Detail Setting</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-settings-3-line me-2"></i>Detail Setting
                </h4>
                <p class="mb-0 text-muted">Informasi lengkap tentang konfigurasi setting</p>
            </div>
            <div class="d-flex gap-2">
                <?php if ($setting['is_editable']): ?>
                    <a href="<?= base_url('settings/edit/' . $setting['id_setting']) ?>" class="btn btn-primary">
                        <i class="ri-pencil-line me-1"></i> Edit
                    </a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>
                        <i class="ri-lock-line me-1"></i> Locked
                    </button>
                <?php endif; ?>
                <a href="<?= base_url('settings') ?>" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Info -->
    <div class="col-12 col-lg-8">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Dasar</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded bg-label-<?= $setting['is_editable'] ? 'primary' : 'secondary' ?>">
                                    <i class="ri-settings-3-line ri-26px"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Setting Key</small>
                                <h5 class="mb-0"><?= esc($setting['setting_key']) ?></h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <small class="text-muted d-block mb-1">Kategori</small>
                        <span class="badge bg-label-primary text-capitalize" style="font-size: 14px;">
                            <?= esc($setting['category']) ?>
                        </span>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <small class="text-muted d-block mb-1">Tipe Data</small>
                        <span class="badge bg-label-info text-capitalize" style="font-size: 14px;">
                            <?= esc($setting['setting_type']) ?>
                        </span>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <small class="text-muted d-block mb-1">Status</small>
                        <?php if ($setting['is_editable']): ?>
                            <span class="badge bg-label-success">
                                <i class="ri-edit-box-line me-1"></i> Editable
                            </span>
                        <?php else: ?>
                            <span class="badge bg-label-secondary">
                                <i class="ri-lock-line me-1"></i> Locked
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 mb-4">
                        <small class="text-muted d-block mb-2">Nilai Setting</small>
                        <div class="p-3 bg-label-dark rounded">
                            <?php if ($setting['setting_type'] === 'json'): ?>
                                <pre class="mb-0"><code><?= json_encode(json_decode($setting['setting_value']), JSON_PRETTY_PRINT) ?></code></pre>
                            <?php elseif ($setting['setting_type'] === 'boolean'): ?>
                                <div class="d-flex align-items-center">
                                    <i class="ri-toggle-line me-2" style="font-size: 20px;"></i>
                                    <strong><?= $setting['setting_value'] === 'true' ? 'True' : 'False' ?></strong>
                                </div>
                            <?php elseif ($setting['setting_type'] === 'number'): ?>
                                <div class="d-flex align-items-center">
                                    <i class="ri-hashtag me-2" style="font-size: 20px;"></i>
                                    <strong><?= number_format($setting['setting_value'], 0, ',', '.') ?></strong>
                                </div>
                            <?php elseif ($setting['setting_type'] === 'date'): ?>
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-line me-2" style="font-size: 20px;"></i>
                                    <strong><?= date('d F Y', strtotime($setting['setting_value'])) ?></strong>
                                </div>
                            <?php else: ?>
                                <div class="d-flex align-items-center">
                                    <i class="ri-text me-2" style="font-size: 20px;"></i>
                                    <strong><?= esc($setting['setting_value']) ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block mb-1">Deskripsi</small>
                        <p class="mb-0">
                            <?= esc($setting['description']) ?: '<span class="text-muted">Tidak ada deskripsi</span>' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline/History -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Riwayat Perubahan</h5>
            </div>
            <div class="card-body">
                <ul class="timeline mb-0">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-info"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Terakhir Diupdate</h6>
                                <small class="text-muted">
                                    <?= date('d M Y, H:i', strtotime($setting['updated_at'])) ?>
                                </small>
                            </div>
                            <p class="mb-0">
                                Setting diperbarui
                                <?php if ($setting['updated_by']): ?>
                                    oleh User #<?= $setting['updated_by'] ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </li>

                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-<?= $setting['is_editable'] ? 'success' : 'secondary' ?>"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Status Saat Ini</h6>
                                <small class="text-muted">
                                    <?= date('d M Y, H:i') ?>
                                </small>
                            </div>
                            <p class="mb-0">
                                Setting sedang
                                <?php if ($setting['is_editable']): ?>
                                    <span class="badge bg-label-success">Editable</span>
                                    dan dapat diubah atau dihapus
                                <?php else: ?>
                                    <span class="badge bg-label-secondary">Locked</span>
                                    dan tidak dapat diubah
                                <?php endif; ?>
                            </p>
                        </div>
                    </li>

                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Informasi Teknis</h6>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex mb-2">
                                    <small class="text-muted" style="width: 120px;">ID Setting:</small>
                                    <small><strong>#<?= $setting['id_setting'] ?></strong></small>
                                </div>
                                <div class="d-flex mb-2">
                                    <small class="text-muted" style="width: 120px;">Key:</small>
                                    <small><code><?= esc($setting['setting_key']) ?></code></small>
                                </div>
                                <div class="d-flex mb-2">
                                    <small class="text-muted" style="width: 120px;">Kategori:</small>
                                    <small><span class="badge bg-label-primary"><?= esc($setting['category']) ?></span></small>
                                </div>
                                <div class="d-flex">
                                    <small class="text-muted" style="width: 120px;">Tipe Data:</small>
                                    <small><span class="badge bg-label-info"><?= esc($setting['setting_type']) ?></span></small>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-12 col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($setting['is_editable']): ?>
                        <a href="<?= base_url('settings/edit/' . $setting['id_setting']) ?>" class="btn btn-outline-primary">
                            <i class="ri-pencil-line me-1"></i> Edit Setting
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteSetting()">
                            <i class="ri-delete-bin-6-line me-1"></i> Hapus Setting
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-secondary" disabled>
                            <i class="ri-lock-line me-1"></i> Setting Terkunci
                        </button>
                        <small class="text-muted text-center">
                            Setting ini tidak dapat diubah atau dihapus
                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Detail Informasi</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <small class="text-muted d-block">ID Setting</small>
                        <strong>#<?= $setting['id_setting'] ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-hashtag"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <small class="text-muted d-block">Kategori</small>
                        <strong class="text-capitalize"><?= esc($setting['category']) ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-folder-2-line"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <small class="text-muted d-block">Tipe Data</small>
                        <strong class="text-capitalize"><?= esc($setting['setting_type']) ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-code-s-slash-line"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <small class="text-muted d-block">Status</small>
                        <strong><?= $setting['is_editable'] ? 'Editable' : 'Locked' ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-<?= $setting['is_editable'] ? 'success' : 'secondary' ?>">
                            <i class="ri-<?= $setting['is_editable'] ? 'edit-box' : 'lock' ?>-line"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Last Update</small>
                        <strong><?= date('d M Y', strtotime($setting['updated_at'])) ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-dark">
                            <i class="ri-time-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card bg-info-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-information-line me-1"></i> Informasi
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Key: <code><?= esc($setting['setting_key']) ?></code></small>
                    </li>
                    <li class="mb-2">
                        <small>Terakhir diupdate <?= date('d M Y', strtotime($setting['updated_at'])) ?></small>
                    </li>
                    <?php if ($setting['updated_by']): ?>
                        <li class="mb-2">
                            <small>Oleh User #<?= $setting['updated_by'] ?></small>
                        </li>
                    <?php endif; ?>
                    <li>
                        <small>
                            <?= $setting['is_editable'] ? 'Dapat diubah dan dihapus' : 'Terkunci permanen' ?>
                        </small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Delete Setting
    function deleteSetting() {
        Swal.fire({
            title: 'Hapus Setting?',
            html: `Setting <strong><?= esc($setting['setting_key']) ?></strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`<?= base_url('settings/delete/' . $setting['id_setting']) ?>`, {
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
                                window.location.href = '<?= base_url('settings') ?>';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message,
                                confirmButtonColor: '#696cff'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan sistem',
                            confirmButtonColor: '#696cff'
                        });
                    });
            }
        });
    }
</script>

<?= $this->endSection() ?>