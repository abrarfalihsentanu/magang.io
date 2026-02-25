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
                    <a href="<?= base_url('role') ?>">Data Role</a>
                </li>
                <li class="breadcrumb-item active">Detail Role</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-shield-user-line me-2"></i>Detail Role
                </h4>
                <p class="mb-0 text-muted">Informasi lengkap tentang role</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('role/edit/' . $role['id_role']) ?>" class="btn btn-primary">
                    <i class="ri-pencil-line me-1"></i> Edit
                </a>
                <a href="<?= base_url('role') ?>" class="btn btn-outline-secondary">
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
                                <span class="avatar-initial rounded bg-label-<?= $role['is_active'] ? 'primary' : 'secondary' ?>">
                                    <i class="ri-shield-user-line ri-26px"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama Role</small>
                                <h5 class="mb-0"><?= esc($role['nama_role']) ?></h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <small class="text-muted d-block mb-1">Kode Role</small>
                        <span class="badge bg-label-info" style="font-size: 14px;">
                            <?= esc($role['kode_role']) ?>
                        </span>
                    </div>

                    <div class="col-12 mb-4">
                        <small class="text-muted d-block mb-1">Deskripsi</small>
                        <p class="mb-0">
                            <?= esc($role['deskripsi']) ?: '<span class="text-muted">Tidak ada deskripsi</span>' ?>
                        </p>
                    </div>

                    <div class="col-12 col-md-6 mb-4 mb-md-0">
                        <small class="text-muted d-block mb-1">Status</small>
                        <?php if ($role['is_active']): ?>
                            <span class="badge bg-label-success">
                                <i class="ri-checkbox-circle-line me-1"></i> Aktif
                            </span>
                        <?php else: ?>
                            <span class="badge bg-label-secondary">
                                <i class="ri-close-circle-line me-1"></i> Nonaktif
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-md-6">
                        <small class="text-muted d-block mb-1">Total User</small>
                        <span class="badge bg-label-success rounded-pill" style="font-size: 14px;">
                            <?= $role['total_users'] ?> User
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
                                <h6 class="mb-0">Role Dibuat</h6>
                                <small class="text-muted">
                                    <?= date('d M Y, H:i', strtotime($role['created_at'])) ?>
                                </small>
                            </div>
                            <p class="mb-0">Role berhasil ditambahkan ke sistem</p>
                        </div>
                    </li>

                    <?php if ($role['updated_at'] !== $role['created_at']): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-info"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Terakhir Diupdate</h6>
                                    <small class="text-muted">
                                        <?= date('d M Y, H:i', strtotime($role['updated_at'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0">Informasi role diperbarui</p>
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
                                Role sedang
                                <?php if ($role['is_active']): ?>
                                    <span class="badge bg-label-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-label-secondary">Nonaktif</span>
                                <?php endif; ?>
                                dan digunakan oleh <strong><?= $role['total_users'] ?></strong> user
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
                    <a href="<?= base_url('role/edit/' . $role['id_role']) ?>" class="btn btn-outline-primary">
                        <i class="ri-pencil-line me-1"></i> Edit Role
                    </a>
                    <button type="button" class="btn btn-outline-warning" onclick="toggleStatus()">
                        <i class="ri-toggle-line me-1"></i> Toggle Status
                    </button>
                    <?php if ($role['total_users'] == 0): ?>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteRole()">
                            <i class="ri-delete-bin-6-line me-1"></i> Hapus Role
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-secondary" disabled>
                            <i class="ri-delete-bin-6-line me-1"></i> Tidak Dapat Dihapus
                        </button>
                        <small class="text-muted text-center">
                            Role masih digunakan oleh user
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
                        <small class="text-muted d-block">ID Role</small>
                        <strong>#<?= $role['id_role'] ?></strong>
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
                        <strong><?= $role['total_users'] ?> User</strong>
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
                        <strong><?= $role['is_active'] ? 'Aktif' : 'Nonaktif' ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-<?= $role['is_active'] ? 'success' : 'secondary' ?>">
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
                        <small>Role ini dibuat pada <?= date('d M Y', strtotime($role['created_at'])) ?></small>
                    </li>
                    <li class="mb-2">
                        <small>Terakhir diupdate <?= date('d M Y', strtotime($role['updated_at'])) ?></small>
                    </li>
                    <li>
                        <small>Kode role: <code><?= $role['kode_role'] ?></code></small>
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
            text: 'Status role akan diubah',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= base_url('role/toggle-status/' . $role['id_role']) ?>`, {
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

    // Delete Role
    function deleteRole() {
        Swal.fire({
            title: 'Hapus Role?',
            html: `Role <strong><?= esc($role['nama_role']) ?></strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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

                fetch(`<?= base_url('role/delete/' . $role['id_role']) ?>`, {
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
                                window.location.href = '<?= base_url('role') ?>';
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