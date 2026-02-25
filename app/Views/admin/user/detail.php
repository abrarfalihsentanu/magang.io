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
                    <a href="<?= base_url('user') ?>">Data User</a>
                </li>
                <li class="breadcrumb-item active">Detail User</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-user-line me-2"></i>Detail User
                </h4>
                <p class="mb-0 text-muted">Informasi lengkap tentang user</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('user/edit/' . $user['id_user']) ?>" class="btn btn-primary">
                    <i class="ri-pencil-line me-1"></i> Edit
                </a>
                <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Info -->
    <div class="col-12 col-lg-8">
        <!-- Profile Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start gap-4 mb-4">
                    <div class="avatar avatar-xl">
                        <img src="<?= base_url('uploads/users/' . ($user['foto'] ?? 'default-avatar.png')) ?>"
                            alt="<?= esc($user['nama_lengkap']) ?>"
                            class="rounded-circle"
                            onerror="this.src='<?= base_url('assets/img/avatars/1.png') ?>'">
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-1"><?= esc($user['nama_lengkap']) ?></h4>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <?php
                            $roleBadge = match ($user['kode_role']) {
                                'admin' => 'primary',
                                'hr' => 'success',
                                'finance' => 'info',
                                'mentor' => 'warning',
                                'intern' => 'secondary',
                                default => 'dark'
                            };
                            ?>
                            <span class="badge bg-label-<?= $roleBadge ?>">
                                <?= esc($user['nama_role']) ?>
                            </span>
                            <?php if ($user['status'] === 'active'): ?>
                                <span class="badge bg-label-success">Aktif</span>
                            <?php elseif ($user['status'] === 'inactive'): ?>
                                <span class="badge bg-label-secondary">Nonaktif</span>
                            <?php else: ?>
                                <span class="badge bg-label-dark">Archived</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <small class="text-muted">
                                <i class="ri-mail-line me-1"></i>
                                <?= esc($user['email']) ?>
                            </small>
                            <?php if ($user['no_hp']): ?>
                                <small class="text-muted">
                                    <i class="ri-phone-line me-1"></i>
                                    <?= esc($user['no_hp']) ?>
                                </small>
                            <?php endif; ?>
                            <?php if ($user['nama_divisi']): ?>
                                <small class="text-muted">
                                    <i class="ri-building-4-line me-1"></i>
                                    <?= esc($user['nama_divisi']) ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Personal Information -->
                <h6 class="mb-3">Informasi Personal</h6>
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">NIK</small>
                        <strong><?= esc($user['nik']) ?></strong>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Jenis Kelamin</small>
                        <strong><?= $user['jenis_kelamin'] === 'L' ? 'Laki-laki' : ($user['jenis_kelamin'] === 'P' ? 'Perempuan' : '-') ?></strong>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                        <strong>
                            <?php if ($user['tanggal_lahir']): ?>
                                <?= date('d F Y', strtotime($user['tanggal_lahir'])) ?>
                                <small class="text-muted">(<?= floor((time() - strtotime($user['tanggal_lahir'])) / 31556926) ?> tahun)</small>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </strong>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Last Login</small>
                        <strong>
                            <?= $user['last_login'] ? date('d M Y, H:i', strtotime($user['last_login'])) : 'Belum pernah login' ?>
                        </strong>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block mb-1">Alamat</small>
                        <strong><?= esc($user['alamat']) ?: '-' ?></strong>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Bank Information -->
                <h6 class="mb-3"><i class="ri-bank-card-line me-1"></i> Informasi Rekening Bank</h6>
                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <small class="text-muted d-block mb-1">Nama Bank</small>
                        <strong><?= esc($user['nama_bank'] ?? '') ?: '-' ?></strong>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <small class="text-muted d-block mb-1">Nomor Rekening</small>
                        <strong><?= esc($user['nomor_rekening'] ?? '') ?: '-' ?></strong>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <small class="text-muted d-block mb-1">Atas Nama</small>
                        <strong><?= esc($user['atas_nama'] ?? '') ?: esc($user['nama_lengkap']) ?></strong>
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
                                <h6 class="mb-0">User Dibuat</h6>
                                <small class="text-muted">
                                    <?= date('d M Y, H:i', strtotime($user['created_at'])) ?>
                                </small>
                            </div>
                            <p class="mb-0">User berhasil ditambahkan ke sistem dengan NIK <span class="badge bg-label-dark"><?= esc($user['nik']) ?></span></p>
                        </div>
                    </li>

                    <?php if ($user['updated_at'] !== $user['created_at']): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-info"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Terakhir Diupdate</h6>
                                    <small class="text-muted">
                                        <?= date('d M Y, H:i', strtotime($user['updated_at'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0">Informasi user diperbarui</p>
                            </div>
                        </li>
                    <?php endif; ?>

                    <?php if ($user['last_login']): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Last Login</h6>
                                    <small class="text-muted">
                                        <?= date('d M Y, H:i', strtotime($user['last_login'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0">User terakhir login ke sistem</p>
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
                                User sedang
                                <?php if ($user['status'] === 'active'): ?>
                                    <span class="badge bg-label-success">Aktif</span>
                                <?php elseif ($user['status'] === 'inactive'): ?>
                                    <span class="badge bg-label-secondary">Nonaktif</span>
                                <?php else: ?>
                                    <span class="badge bg-label-dark">Archived</span>
                                <?php endif; ?>
                                dengan role <strong><?= esc($user['nama_role']) ?></strong>
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
                    <a href="<?= base_url('user/edit/' . $user['id_user']) ?>" class="btn btn-outline-primary">
                        <i class="ri-pencil-line me-1"></i> Edit User
                    </a>
                    <button type="button" class="btn btn-outline-warning" onclick="toggleStatus()">
                        <i class="ri-toggle-line me-1"></i> Toggle Status
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="resetPassword()">
                        <i class="ri-lock-password-line me-1"></i> Reset Password
                    </button>
                    <?php if ($user['id_user'] != session()->get('id_user')): ?>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteUser()">
                            <i class="ri-delete-bin-6-line me-1"></i> Hapus User
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-secondary" disabled>
                            <i class="ri-delete-bin-6-line me-1"></i> Tidak Dapat Dihapus
                        </button>
                        <small class="text-muted text-center">Tidak dapat menghapus diri sendiri</small>
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
                        <small class="text-muted d-block">ID User</small>
                        <strong>#<?= $user['id_user'] ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-hashtag"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted d-block">Role</small>
                        <strong><?= esc($user['nama_role']) ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-<?= $roleBadge ?>">
                            <i class="ri-shield-user-line"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Status</small>
                        <strong><?= ucfirst($user['status']) ?></strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
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
                        <small>User dibuat pada <?= date('d M Y', strtotime($user['created_at'])) ?></small>
                    </li>
                    <li class="mb-2">
                        <small>Terakhir diupdate <?= date('d M Y', strtotime($user['updated_at'])) ?></small>
                    </li>
                    <li>
                        <small>NIK: <code><?= $user['nik'] ?></code></small>
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
            text: 'Status user akan diubah',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({
                    icon: 'info',
                    title: 'Fitur dalam pengembangan'
                });
            }
        });
    }

    // Reset Password
    function resetPassword() {
        Swal.fire({
            title: 'Reset Password?',
            html: 'Password akan direset ke default.<br><small class="text-muted">User harus mengubah password setelah login</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({
                    icon: 'info',
                    title: 'Fitur dalam pengembangan'
                });
            }
        });
    }

    // Delete User
    function deleteUser() {
        Swal.fire({
            title: 'Hapus User?',
            html: `User <strong><?= esc($user['nama_lengkap']) ?></strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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

                fetch(`<?= base_url('user/delete/' . $user['id_user']) ?>`, {
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
                                window.location.href = '<?= base_url('user') ?>';
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