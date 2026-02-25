<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/indicators') ?>">KPI</a></li>
                <li class="breadcrumb-item active">Indicators Management</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-bar-chart-box-line me-2"></i>KPI Indicators Management
                </h4>
                <p class="mb-0 text-muted">Kelola indikator penilaian kinerja intern</p>
            </div>
            <div>
                <a href="<?= base_url('kpi/indicators/create') ?>" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i> Tambah Indicator
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
                        <span class="text-heading">Total Indicators</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $statistics['total'] ?></h3>
                        </div>
                        <small class="mb-0">Semua indikator</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-list-check ri-26px"></i>
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
                        <span class="text-heading">Active</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-success"><?= $statistics['active'] ?></h3>
                        </div>
                        <small class="mb-0">Aktif digunakan</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-checkbox-circle-line ri-26px"></i>
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
                        <span class="text-heading">Auto Calculate</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-info"><?= $statistics['auto_calculate'] ?></h3>
                        </div>
                        <small class="mb-0">Otomatis</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-flashlight-line ri-26px"></i>
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
                        <span class="text-heading">Manual Assessment</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-warning"><?= $statistics['manual'] ?></h3>
                        </div>
                        <small class="mb-0">Perlu dinilai</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-edit-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bobot Validation Alert -->
<div class="row mb-4">
    <div class="col-12">
        <?php if ($validation['is_valid'] && $validation['total'] == 100): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="ri-checkbox-circle-line me-2 fs-4"></i>
                <div>
                    <strong>Bobot Valid!</strong> Total bobot indikator aktif = <strong>100%</strong>
                </div>
            </div>
        <?php elseif ($validation['total'] < 100): ?>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="ri-error-warning-line me-2 fs-4"></i>
                <div>
                    <strong>Peringatan!</strong> Total bobot = <strong><?= $validation['total'] ?>%</strong>.
                    Sisa bobot tersedia: <strong><?= $validation['remaining'] ?>%</strong>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="ri-alert-line me-2 fs-4"></i>
                <div>
                    <strong>Error!</strong> Total bobot melebihi 100% (<?= $validation['total'] ?>%)
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Indicators by Category -->
<div class="row g-4">
    <?php
    $categories = [
        'kehadiran' => [
            'label' => 'Kehadiran',
            'color' => 'primary',
            'icon' => 'ri-calendar-check-line'
        ],
        'aktivitas' => [
            'label' => 'Aktivitas',
            'color' => 'success',
            'icon' => 'ri-file-list-3-line'
        ],
        'project' => [
            'label' => 'Project',
            'color' => 'warning',
            'icon' => 'ri-folder-2-line'
        ]
    ];

    foreach ($categories as $catKey => $catInfo):
        $catIndicators = array_filter($indicators, fn($ind) => $ind['kategori'] === $catKey);
        $catBobot = array_sum(array_column($catIndicators, 'bobot'));
    ?>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="<?= $catInfo['icon'] ?> me-1"></i>
                        <?= $catInfo['label'] ?>
                    </h5>
                    <span class="badge bg-<?= $catInfo['color'] ?>"><?= number_format($catBobot, 2) ?>%</span>
                </div>
                <div class="card-body">
                    <?php if (empty($catIndicators)): ?>
                        <div class="text-center py-3">
                            <i class="<?= $catInfo['icon'] ?>" style="font-size: 48px; opacity: 0.2;"></i>
                            <p class="text-muted mb-0 mt-2">Belum ada indicator</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($catIndicators as $ind): ?>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1 me-2">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <strong><?= esc($ind['nama_indicator']) ?></strong>
                                            <?php if ($ind['is_auto_calculate']): ?>
                                                <span class="badge bg-label-info" title="Auto Calculate">
                                                    <i class="ri-flashlight-line"></i>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-label-warning" title="Manual Assessment">
                                                    <i class="ri-edit-line"></i>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!$ind['is_active']): ?>
                                                <span class="badge bg-label-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted d-block mb-1">
                                            <?= esc(substr($ind['deskripsi'], 0, 80)) ?><?= strlen($ind['deskripsi']) > 80 ? '...' : '' ?>
                                        </small>
                                        <span class="badge bg-label-<?= $catInfo['color'] ?>"><?= $ind['bobot'] ?>%</span>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="viewDetail(<?= $ind['id_indicator'] ?>)">
                                                    <i class="ri-eye-line me-2"></i> Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="<?= base_url('kpi/indicators/edit/' . $ind['id_indicator']) ?>">
                                                    <i class="ri-pencil-line me-2"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="toggleStatus(<?= $ind['id_indicator'] ?>, '<?= esc($ind['nama_indicator']) ?>')">
                                                    <i class="ri-toggle-line me-2"></i> Toggle Status
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                    onclick="deleteIndicator(<?= $ind['id_indicator'] ?>, '<?= esc($ind['nama_indicator']) ?>')">
                                                    <i class="ri-delete-bin-line me-2"></i> Hapus
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal-admin-kpi-indicators" tabindex="-1" aria-labelledby="detailModal-admin-kpi-indicatorsLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModal-admin-kpi-indicatorsLabel">Detail Indicator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent-admin-kpi-indicators">
                <!-- Content loaded via JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const detailModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-admin-kpi-indicators'));

    // View Detail
    function viewDetail(id) {
        const indicator = <?= json_encode($indicators) ?>.find(ind => ind.id_indicator == id);

        if (!indicator) return;

        const categoryBadge = {
            'kehadiran': 'primary',
            'aktivitas': 'success',
            'project': 'warning'
        };

        const html = `
            <div class="mb-3">
                <small class="text-muted d-block mb-1">Nama Indicator</small>
                <strong>${indicator.nama_indicator}</strong>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block mb-1">Kategori</small>
                <span class="badge bg-label-${categoryBadge[indicator.kategori]}">
                    ${indicator.kategori.charAt(0).toUpperCase() + indicator.kategori.slice(1)}
                </span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block mb-1">Bobot</small>
                <strong>${indicator.bobot}%</strong>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block mb-1">Deskripsi</small>
                <p class="mb-0">${indicator.deskripsi}</p>
            </div>
            ${indicator.formula ? `
            <div class="mb-3">
                <small class="text-muted d-block mb-1">Formula</small>
                <code class="d-block p-2 bg-light rounded">${indicator.formula}</code>
            </div>
            ` : ''}
            <div class="mb-3">
                <small class="text-muted d-block mb-1">Tipe Perhitungan</small>
                ${indicator.is_auto_calculate ? 
                    '<span class="badge bg-label-info"><i class="ri-flashlight-line me-1"></i> Auto Calculate</span>' : 
                    '<span class="badge bg-label-warning"><i class="ri-edit-line me-1"></i> Manual Assessment</span>'
                }
            </div>
            <div>
                <small class="text-muted d-block mb-1">Status</small>
                ${indicator.is_active ? 
                    '<span class="badge bg-label-success">Aktif</span>' : 
                    '<span class="badge bg-label-secondary">Nonaktif</span>'
                }
            </div>
        `;

        document.getElementById('detailContent-admin-kpi-indicators').innerHTML = html;
        detailModal.show();
    }

    // Toggle Status
    async function toggleStatus(id, nama) {
        const result = await Swal.fire({
            title: 'Toggle Status?',
            html: `Status indicator "<strong>${nama}</strong>" akan diubah`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`<?= base_url('kpi/indicators/toggle-status/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        }
    }

    // Delete Indicator
    async function deleteIndicator(id, nama) {
        const result = await Swal.fire({
            title: 'Hapus Indicator?',
            html: `Indicator "<strong>${nama}</strong>" akan dihapus permanen.<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Menghapus...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`<?= base_url('kpi/indicators/delete/') ?>${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        }
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

<?= $this->endSection() ?>