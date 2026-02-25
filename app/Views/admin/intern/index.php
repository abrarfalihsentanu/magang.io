<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="icon-base ri-user-star-line me-2"></i>Data Pemagang
                </h4>
                <p class="mb-0 text-muted">Kelola data pemagang dan informasi magang</p>
            </div>
            <div>
                <a href="<?= base_url('intern/create') ?>" class="btn btn-primary">
                    <i class="icon-base ri-add-line me-1"></i> Tambah Pemagang
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
                        <span class="text-heading">Total Pemagang</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['total'] ?></h4>
                        </div>
                        <small class="mb-0">Terdaftar</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ri-user-star-line ri-26px"></i>
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
                        <span class="text-heading">Aktif Magang</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['active'] ?></h4>
                        </div>
                        <small class="mb-0">Sedang berlangsung</small>
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

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Selesai</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['completed'] ?></h4>
                        </div>
                        <small class="mb-0">Telah menyelesaikan</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ri-check-double-line ri-26px"></i>
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
                        <span class="text-heading">Terminated</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['terminated'] ?></h4>
                        </div>
                        <small class="mb-0">Diberhentikan</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger">
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
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0">Daftar Pemagang</h5>
        <div class="d-flex gap-2 flex-wrap">
            <form action="<?= current_url() ?>" method="get" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Selesai</option>
                    <option value="terminated" <?= ($status ?? '') === 'terminated' ? 'selected' : '' ?>>Terminated</option>
                </select>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari pemagang..." value="<?= esc($search ?? '') ?>">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
                <?php if (!empty($search) || !empty($status)): ?>
                    <a href="<?= base_url('intern') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-close-line"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover" id="internTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">NIK / Nama</th>
                    <th width="15%">Email / No HP</th>
                    <th width="15%">Universitas / Jurusan</th>
                    <th width="12%">Divisi</th>
                    <th width="15%">Periode</th>
                    <th width="10%">Status</th>
                    <th width="8%">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php if (empty($interns)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="icon-base ri-folder-open-line" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mb-0 mt-3 text-muted">
                                    <?= !empty($search) || !empty($status) ? 'Tidak ada data yang sesuai filter' : 'Belum ada data pemagang' ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $startNo = (($currentPage ?? 1) - 1) * 10 + 1;
                    foreach ($interns as $index => $intern): ?>
                        <tr>
                            <td><?= $startNo + $index ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <?php if (!empty($intern['foto']) && $intern['foto'] !== 'default-avatar.png'): ?>
                                            <img src="<?= base_url('uploads/users/' . $intern['foto']) ?>"
                                                alt="<?= esc($intern['nama_lengkap']) ?>"
                                                class="rounded-circle"
                                                onerror="this.onerror=null; this.src='<?= base_url('assets/img/avatars/1.png') ?>'">
                                        <?php else: ?>
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?= strtoupper(substr($intern['nama_lengkap'], 0, 2)) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= esc($intern['nama_lengkap']) ?></h6>
                                        <small class="text-muted"><?= esc($intern['nik']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small class="d-block"><?= esc($intern['email']) ?></small>
                                <small class="text-muted"><?= esc($intern['no_hp']) ?></small>
                            </td>
                            <td>
                                <small class="d-block"><?= esc($intern['universitas']) ?></small>
                                <small class="text-muted"><?= esc($intern['jurusan']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-label-info"><?= esc($intern['nama_divisi'] ?? '-') ?></span>
                            </td>
                            <td>
                                <small class="d-block"><?= date('d M Y', strtotime($intern['periode_mulai'])) ?></small>
                                <small class="text-muted">s/d <?= date('d M Y', strtotime($intern['periode_selesai'])) ?></small>
                                <br>
                                <small class="text-muted">(<?= $intern['durasi_bulan'] ?> bulan)</small>
                            </td>
                            <td>
                                <?php
                                $statusColors = [
                                    'active' => 'success',
                                    'completed' => 'info',
                                    'terminated' => 'danger'
                                ];
                                $statusLabels = [
                                    'active' => 'Aktif',
                                    'completed' => 'Selesai',
                                    'terminated' => 'Diberhentikan'
                                ];
                                ?>
                                <span class="badge rounded-pill bg-label-<?= $statusColors[$intern['status_magang']] ?>">
                                    <?= $statusLabels[$intern['status_magang']] ?>
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                        ⋮
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('intern/detail/' . $intern['id_intern']) ?>">
                                                <i class="ri-eye-line me-2"></i>Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('intern/edit/' . $intern['id_intern']) ?>">
                                                <i class="ri-pencil-line me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="toggleStatus(<?= $intern['id_intern'] ?>, '<?= $intern['status_magang'] ?>')">
                                                <i class="ri-refresh-line me-2"></i>Toggle Status
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                onclick="deleteIntern(<?= $intern['id_intern'] ?>, '<?= esc($intern['nama_lengkap']) ?>')">
                                                <i class="ri-delete-bin-line me-2"></i>Hapus
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

<!-- SweetAlert2 -->
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Flash messages
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

    // Delete
    function deleteIntern(id, nama) {
        Swal.fire({
            title: 'Hapus Pemagang?',
            html: `Data pemagang <strong>${nama}</strong> akan dihapus permanen.<br>Semua data terkait (absensi, aktivitas, dll) juga akan terhapus.`,
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

                fetch(`<?= base_url('intern/delete/') ?>${id}`, {
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
                            }).then(() => location.reload());
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
</script>

<?= $this->endSection() ?>