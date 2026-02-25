<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="icon-base ri-settings-3-line me-2"></i>Pengaturan Sistem
                </h4>
                <p class="mb-0 text-muted">Kelola konfigurasi dan pengaturan aplikasi</p>
            </div>
            <div>
                <a href="<?= base_url('settings/create') ?>" class="btn btn-primary">
                    <i class="icon-base ri-add-line me-1"></i> Tambah Setting
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
                        <span class="text-heading">Total Settings</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['total'] ?></h4>
                        </div>
                        <small class="mb-0">Setting terdaftar</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ri-settings-3-line ri-26px"></i>
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
                        <span class="text-heading">Editable</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['editable'] ?></h4>
                        </div>
                        <small class="mb-0">Dapat diubah</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ri-edit-box-line ri-26px"></i>
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
                        <span class="text-heading">Locked</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= $statistics['locked'] ?></h4>
                        </div>
                        <small class="mb-0">Tidak dapat diubah</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="icon-base ri-lock-line ri-26px"></i>
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
                        <span class="text-heading">Kategori</span>
                        <div class="d-flex align-items-center my-2">
                            <h4 class="mb-0 me-2"><?= count($categories) ?></h4>
                        </div>
                        <small class="mb-0">Grup setting</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ri-folder-settings-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settings by Category -->
<?php if (empty($settings)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="icon-base ri-folder-open-line" style="font-size: 48px; opacity: 0.3;"></i>
            <p class="mb-0 mt-3 text-muted">Belum ada data setting</p>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($settings as $category => $items): ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 text-capitalize">
                        <i class="icon-base ri-folder-2-line me-2"></i><?= esc($category) ?>
                    </h5>
                    <small class="text-muted"><?= count($items) ?> setting(s)</small>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-paginated">
                    <thead>
                        <tr>
                            <th width="25%">Setting Key</th>
                            <th width="30%">Value</th>
                            <th width="10%">Type</th>
                            <th width="25%">Description</th>
                            <th width="5%">Status</th>
                            <th width="5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $setting): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded bg-label-<?= $setting['is_editable'] ? 'success' : 'secondary' ?>">
                                                <i class="ri-<?= $setting['is_editable'] ? 'edit' : 'lock' ?>-line"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 small"><?= esc($setting['setting_key']) ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="bg-label-dark px-2 py-1 rounded">
                                        <?= esc(strlen($setting['setting_value']) > 50 ? substr($setting['setting_value'], 0, 50) . '...' : $setting['setting_value']) ?>
                                    </code>
                                </td>
                                <td>
                                    <span class="badge bg-label-info"><?= esc($setting['setting_type']) ?></span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= esc($setting['description'] ?: '-') ?></small>
                                </td>
                                <td>
                                    <?php if ($setting['is_editable']): ?>
                                        <span class="badge bg-label-success">Editable</span>
                                    <?php else: ?>
                                        <span class="badge bg-label-secondary">Locked</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('settings/detail/' . $setting['id_setting']) ?>">
                                                    <i class="ri-eye-line me-2"></i>Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('settings/edit/' . $setting['id_setting']) ?>">
                                                    <i class="ri-pencil-line me-2"></i>Edit
                                                </a>
                                            </li>
                                            <?php if ($setting['is_editable']): ?>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                        onclick="deleteSetting(<?= $setting['id_setting'] ?>, '<?= esc($setting['setting_key']) ?>')">
                                                        <i class="ri-delete-bin-line me-2"></i>Hapus
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

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

    // Delete Setting
    function deleteSetting(id, settingKey) {
        Swal.fire({
            title: 'Hapus Setting?',
            html: `Setting <strong>${settingKey}</strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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

                fetch(`<?= base_url('settings/delete/') ?>${id}`, {
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