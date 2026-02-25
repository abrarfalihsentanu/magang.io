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
                    <a href="<?= base_url('divisi') ?>">Data Divisi</a>
                </li>
                <li class="breadcrumb-item active">Detail Divisi</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-building-4-line me-2"></i>Detail Divisi
                </h4>
                <p class="mb-0 text-muted">Informasi lengkap tentang divisi</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('divisi/edit/' . $divisi['id_divisi']) ?>" class="btn btn-primary">
                    <i class="ri-pencil-line me-1"></i> Edit
                </a>
                <a href="<?= base_url('divisi') ?>" class="btn btn-outline-secondary">
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
                                <span class="avatar-initial rounded bg-label-<?= $divisi['is_active'] ? 'primary' : 'secondary' ?>">
                                    <i class="ri-building-4-line ri-26px"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama Divisi</small>
                                <h5 class="mb-0"><?= esc($divisi['nama_divisi']) ?></h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <small class="text-muted d-block mb-1">Kode Divisi</small>
                        <span class="badge bg-label-info" style="font-size: 14px;">
                            <?= esc($divisi['kode_divisi']) ?>
                        </span>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <small class="text-muted d-block mb-1">Kepala Divisi</small>
                        <p class="mb-0">
                            <?php if ($divisi['kepala_divisi']): ?>
                                <i class="ri-user-star-line me-1"></i>
                                <?= esc($divisi['kepala_divisi']) ?>
                            <?php else: ?>
                                <span class="text-muted">Belum ditentukan</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <small class="text-muted d-block mb-1">Status</small>
                        <?php if ($divisi['is_active']): ?>
                            <span class="badge bg-label-success">
                                <i class="ri-checkbox-circle-line me-1"></i> Aktif
                            </span>
                        <?php else: ?>
                            <span class="badge bg-label-secondary">
                                <i class="ri-close-circle-line me-1"></i> Nonaktif
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 mb-4">
                        <small class="text-muted d-block mb-1">Deskripsi</small>
                        <p class="mb-0">
                            <?= esc($divisi['deskripsi']) ?: '<span class="text-muted">Tidak ada deskripsi</span>' ?>
                        </p>
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block mb-1">Total User</small>
                        <span class="badge bg-label-success rounded-pill" style="font-size: 14px;">
                            <?= $divisi['total_users'] ?> User
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline/History -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Riwayat</h5>
            </div>
            <div class="card-body">
                <ul class="timeline mb-0">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Divisi Dibuat</h6>
                                <small class="text-muted">
                                    <?= date('d M Y, H:i', strtotime($divisi['created_at'])) ?>
                                </small>
                            </div>
                            <p class="mb-0">Divisi berhasil ditambahkan ke sistem</p>
                        </div>
                    </li>

                    <?php if ($divisi['updated_at'] !== $divisi['created_at']): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-info"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Terakhir Diupdate</h6>
                                    <small class="text-muted">
                                        <?= date('d M Y, H:i', strtotime($divisi['updated_at'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0">Informasi divisi diperbarui</p>
                            </div>
                        </li>
                    <?php endif; ?>

                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-warning"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Status Saat Ini</h6>
                                <small class="text-muted">
                                    <?= date('d M Y, H:i') ?>
                                </small>
                            </div>
                            <p class="mb-0">
                                Divisi sedang
                                <?php if ($divisi['is_active']): ?>
                                    <span class="badge bg-label-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-label-secondary">Nonaktif</span>
                                <?php endif; ?>
                                dan digunakan oleh <strong><?= $divisi['total_users'] ?></strong> user
                            </p>
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
                    <a href="<?= base_url('divisi/edit/' . $divisi['id_divisi']) ?>" class="btn btn-outline-primary">
                        <i class="ri-pencil-line me-1"></i> Edit Divisi
                    </a>
                    <button type="button" class="btn btn-outline-warning" onclick="toggleStatus()">
                        <i class="ri-toggle-line me-1"></i> Toggle Status
                    </button>
                    <?php if ($divisi['total_users'] == 0): ?>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteDivisi()">
                            <i class="ri-delete-bin-6-line me-1"></i> Hapus Divisi
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-secondary" disabled>
                            <i class="ri-delete-bin-6-line me-1"></i> Tidak Dapat Dihapus
                        </button>
                        <small class="text-muted text-center">
                            Divisi masih digunakan oleh user
                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Statistik</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted d-block">ID Divisi</small>
                        <strong>#<?= $divisi['id_divisi'] ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-hashtag"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted d-block">Total User</small>
                        <strong><?= $divisi['total_users'] ?> User</strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-user-line"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Status</small>
                        <strong><?= $divisi['is_active'] ? 'Aktif' : 'Nonaktif' ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-<?= $divisi['is_active'] ? 'success' : 'secondary' ?>">
                            <i class="ri-checkbox-circle-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="card bg-info-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-information-line me-1"></i> Informasi
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Divisi ini dibuat pada <?= date('d M Y', strtotime($divisi['created_at'])) ?></small>
                    </li>
                    <li class="mb-2">
                        <small>Terakhir diupdate <?= date('d M Y', strtotime($divisi['updated_at'])) ?></small>
                    </li>
                    <li>
                        <small>Kode divisi: <code><?= $divisi['kode_divisi'] ?></code></small>
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

    // Toggle Status
    function toggleStatus() {
        Swal.fire({
            title: 'Ubah Status?',
            text: 'Status divisi akan diubah',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= base_url('divisi/toggle-status/' . $divisi['id_divisi']) ?>`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                title: data.message
                            });
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message
                            });
                        }
                    });
            }
        });
    }

    // Delete Divisi
    function deleteDivisi() {
        Swal.fire({
            title: 'Hapus Divisi?',
            html: `Divisi <strong><?= esc($divisi['nama_divisi']) ?></strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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

                fetch(`<?= base_url('divisi/delete/' . $divisi['id_divisi']) ?>`, {
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
                                window.location.href = '<?= base_url('divisi') ?>';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message,
                                confirmButtonColor: '#696cff'
                            });
                        }
                    });
            }
        });
    }
</script>

<?= $this->endSection() ?>