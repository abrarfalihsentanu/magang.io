<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="icon-base ri-user-settings-line me-2"></i>Data User
                </h4>
                <p class="mb-0 text-muted">Kelola data pengguna sistem</p>
            </div>
            <div>
                <a href="<?= base_url('user/create') ?>" class="btn btn-primary">
                    <i class="icon-base ri-add-line me-1"></i> Tambah User
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total User</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['total'] ?></h4>
                        </div>
                        <small class="mb-0">User terdaftar</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ri-user-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">User Aktif</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['active'] ?></h4>
                        </div>
                        <small class="mb-0">Dapat login</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ri-user-check-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">User Nonaktif</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['inactive'] ?></h4>
                        </div>
                        <small class="mb-0">Tidak dapat login</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="icon-base ri-user-forbid-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Role Tersedia</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= count($statistics['by_role']) ?></h4>
                        </div>
                        <small class="mb-0">Tipe pengguna</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ri-shield-user-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0">Daftar User</h5>
        <div class="d-flex gap-2 flex-wrap">
            <form action="<?= current_url() ?>" method="get" class="d-flex gap-2">
                <select name="role" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_role'] ?>" <?= ($role ?? '') == $r['id_role'] ? 'selected' : '' ?>>
                            <?= esc($r['nama_role']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari user..." value="<?= esc($search ?? '') ?>">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
                <?php if (!empty($search) || !empty($role)): ?>
                    <a href="<?= base_url('user') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-close-line"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover" id="userTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">NIK</th>
                    <th width="20%">Nama Lengkap</th>
                    <th width="15%">Email</th>
                    <th width="12%">Role</th>
                    <th width="12%">Divisi</th>
                    <th width="10%">Status</th>
                    <th width="16%">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="icon-base ri-folder-open-line" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mb-0 mt-3 text-muted">
                                    <?= !empty($search) || !empty($role) ? 'Tidak ada data yang sesuai filter' : 'Belum ada data user' ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $startNo = (($currentPage ?? 1) - 1) * 10 + 1;
                    foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?= $startNo + $index ?></td>
                            <td>
                                <span class="badge bg-label-dark"><?= esc($user['nik']) ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="<?= base_url('uploads/users/' . ($user['foto'] ?? 'default-avatar.png')) ?>"
                                            alt="<?= esc($user['nama_lengkap']) ?>"
                                            class="rounded-circle"
                                            onerror="this.src='<?= base_url('assets/img/avatars/1.png') ?>'">
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= esc($user['nama_lengkap']) ?></h6>
                                        <small class="text-muted"><?= esc($user['no_hp'] ?? '-') ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small><?= esc($user['email']) ?></small>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= esc($user['nama_divisi'] ?? '-') ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($user['status'] === 'active'): ?>
                                    <span class="badge rounded-pill bg-label-success">Aktif</span>
                                <?php elseif ($user['status'] === 'inactive'): ?>
                                    <span class="badge rounded-pill bg-label-secondary">Nonaktif</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-label-dark">Archived</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                        ⋮
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('user/detail/' . $user['id_user']) ?>">
                                                <i class="ri-eye-line me-2"></i>
                                                <span>Detail</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('user/edit/' . $user['id_user']) ?>">
                                                <i class="ri-pencil-line me-2"></i>
                                                <span>Edit</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="toggleStatus(<?= $user['id_user'] ?>)">
                                                <i class="ri-refresh-line me-2"></i>
                                                <span>Toggle Status</span>
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                onclick="deleteUser(<?= $user['id_user'] ?>, '<?= esc($user['nama_lengkap']) ?>')">
                                                <i class="ri-delete-bin-line me-2"></i>
                                                <span>Hapus</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="card-footer">
        <?php if (isset($pager)): ?>
            <?= view('components/pagination_wrapper', [
                'pager' => $pager,
                'total' => $total ?? 0,
                'perPage' => $perPage ?? 10,
                'currentPage' => $currentPage ?? 1
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<!-- SweetAlert2 & Custom JS -->
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Show flash message
    <?php if (session()->getFlashdata('success')): ?>
        Toast.fire({
            icon: 'success',
            title: '<?= session()->getFlashdata('success') ?>'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        Toast.fire({
            icon: 'error',
            title: '<?= session()->getFlashdata('error') ?>'
        });
    <?php endif; ?>

    // Toggle Status
    function toggleStatus(id) {
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
                // Implement toggle status via AJAX
                Toast.fire({
                    icon: 'info',
                    title: 'Fitur dalam pengembangan'
                });
            }
        });
    }

    // Delete User
    function deleteUser(id, namaUser) {
        Swal.fire({
            title: 'Hapus User?',
            html: `User <strong>${namaUser}</strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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

                fetch(`<?= base_url('user/delete/') ?>${id}`, {
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
                                location.reload();
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