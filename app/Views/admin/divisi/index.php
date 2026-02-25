<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="icon-base ri-building-4-line me-2"></i>Data Divisi
                </h4>
                <p class="mb-0 text-muted">Kelola divisi dan unit kerja perusahaan</p>
            </div>
            <div>
                <a href="<?= base_url('divisi/create') ?>" class="btn btn-primary">
                    <i class="icon-base ri-add-line me-1"></i> Tambah Divisi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total Divisi</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['total'] ?></h4>
                        </div>
                        <small class="mb-0">Divisi terdaftar</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ri-building-4-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Divisi Aktif</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['active'] ?></h4>
                        </div>
                        <small class="mb-0">Dapat digunakan</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ri-checkbox-circle-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Divisi Nonaktif</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['inactive'] ?></h4>
                        </div>
                        <small class="mb-0">Tidak dapat digunakan</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="icon-base ri-close-circle-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Divisi</h5>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text"><i class="icon-base ri-search-line"></i></span>
                <input type="text" class="form-control" placeholder="Cari divisi..." id="searchInput">
            </div>
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover table-paginated" id="divisiTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama Divisi</th>
                    <th width="12%">Kode</th>
                    <th width="18%">Kepala Divisi</th>
                    <th width="25%">Deskripsi</th>
                    <th width="10%">Jumlah User</th>
                    <th width="10%">Status</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php if (empty($divisi)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="icon-base ri-folder-open-line" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mb-0 mt-3 text-muted">Belum ada data divisi</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1;
                    foreach ($divisi as $div): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded bg-label-<?= $div['is_active'] ? 'primary' : 'secondary' ?>">
                                            <i class="icon-base ri-building-4-line"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= esc($div['nama_divisi']) ?></h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info"><?= esc($div['kode_divisi']) ?></span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= esc($div['kepala_divisi'] ?: '-') ?>
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= esc($div['deskripsi'] ?: 'Tidak ada deskripsi') ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-label-success rounded-pill">
                                    <?= $div['total_users'] ?> User
                                </span>
                            </td>
                            <td>
                                <?php if ($div['is_active']): ?>
                                    <span class="badge rounded-pill bg-label-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-label-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                        ⋮
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('divisi/detail/' . $div['id_divisi']) ?>">
                                                <i class="ri-eye-line me-2"></i>
                                                <span>Detail</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('divisi/edit/' . $div['id_divisi']) ?>">
                                                <i class="ri-pencil-line me-2"></i>
                                                <span>Edit</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="toggleStatus(<?= $div['id_divisi'] ?>)">
                                                <i class="ri-refresh-line me-2"></i>
                                                <span>Toggle Status</span>
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteDivisi(<?= $div['id_divisi'] ?>, '<?= esc($div['nama_divisi']) ?>', <?= $div['total_users'] ?>)">
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

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#divisiTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Toggle Status
    function toggleStatus(id) {
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
                fetch(`<?= base_url('divisi/toggle-status/') ?>${id}`, {
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
    function deleteDivisi(id, namaDivisi, totalUsers) {
        if (totalUsers > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Dapat Dihapus',
                text: `Divisi "${namaDivisi}" masih digunakan oleh ${totalUsers} user. Hapus atau pindahkan user terlebih dahulu.`,
                confirmButtonColor: '#696cff'
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Divisi?',
            html: `Divisi <strong>${namaDivisi}</strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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

                fetch(`<?= base_url('divisi/delete/') ?>${id}`, {
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
                    });
            }
        });
    }
</script>

<?= $this->endSection() ?>