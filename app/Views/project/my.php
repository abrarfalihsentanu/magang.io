<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Weekly Project</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-file-list-3-line me-2"></i>Weekly Project Saya
                </h4>
                <p class="mb-0 text-muted">Submit dan pantau project mingguan Anda</p>
            </div>
            <div>
                <?php if ($can_submit && !$has_submitted): ?>
                    <a href="<?= base_url('project/create') ?>" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Submit Project Minggu Ini
                    </a>
                <?php elseif ($has_submitted): ?>
                    <button class="btn btn-success" disabled>
                        <i class="ri-check-line me-1"></i> Sudah Submit Minggu Ini
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>
                        <i class="ri-time-line me-1"></i> Deadline Sudah Lewat (Max Jumat)
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Week Info Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="ri-information-line me-2 fs-4"></i>
            <div>
                <strong><?= $week_info['week_label'] ?></strong><br>
                <small>Periode: <?= $week_info['periode_label'] ?> | Deadline: Jumat, <?= date('d M Y', strtotime($week_info['periode_mulai'] . ' +4 days')) ?></small>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2"><?= $statistics['total'] ?></h4>
                        </div>
                        <small class="mb-0">Project</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-file-list-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Draft</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2 text-warning"><?= $statistics['draft'] ?></h4>
                        </div>
                        <small class="mb-0">Belum submit</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-draft-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Submitted</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2 text-info"><?= $statistics['submitted'] ?></h4>
                        </div>
                        <small class="mb-0">Menunggu</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-send-plane-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Assessed</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2 text-success"><?= $statistics['assessed'] ?></h4>
                        </div>
                        <small class="mb-0">Dinilai</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-check-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card bg-label-primary">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Self Rating</small>
                <h3 class="mb-0"><?= $statistics['avg_self_rating'] ?></h3>
                <small>/ 5.0</small>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card bg-label-success">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Mentor Rating</small>
                <h3 class="mb-0"><?= $statistics['avg_mentor_rating'] ?></h3>
                <small>/ 5.0</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label class="form-label">Filter Tahun</label>
                        <select class="form-select" id="yearFilter">
                            <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                                <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Projects List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 me-2">Daftar Project</h5>
        <span class="badge bg-label-primary"><?= count($projects) ?> Records</span>
    </div>
    <div class="card-body">
        <?php if (empty($projects)): ?>
            <div class="text-center py-5">
                <i class="ri-folder-open-line" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 mb-0">Belum ada project untuk tahun ini</p>
                <?php if ($can_submit && !$has_submitted): ?>
                    <a href="<?= base_url('project/create') ?>" class="btn btn-sm btn-primary mt-3">
                        <i class="ri-add-line me-1"></i> Submit Project Pertama
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-paginated">
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th>Periode</th>
                            <th>Judul Project</th>
                            <th>Tipe</th>
                            <th>Progress</th>
                            <th>Self Rating</th>
                            <th>Mentor Rating</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php foreach ($projects as $proj): ?>
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
                            $tipeClass = [
                                'inisiatif' => 'primary',
                                'assigned' => 'secondary'
                            ];
                            ?>
                            <tr>
                                <td>
                                    <strong>Week <?= $proj['week_number'] ?></strong><br>
                                    <small class="text-muted"><?= $proj['tahun'] ?></small>
                                </td>
                                <td>
                                    <small>
                                        <?= date('d M', strtotime($proj['periode_mulai'])) ?> -
                                        <?= date('d M', strtotime($proj['periode_selesai'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?= esc($proj['judul_project']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= substr(esc($proj['deskripsi']), 0, 50) ?>...
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $tipeClass[$proj['tipe_project']] ?>">
                                        <?= ucfirst($proj['tipe_project']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress w-100 me-2" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?= $proj['progress'] ?>%"
                                                aria-valuenow="<?= $proj['progress'] ?>"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-nowrap"><?= $proj['progress'] ?>%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($proj['self_rating']): ?>
                                        <span class="badge bg-label-primary">
                                            <i class="ri-star-fill"></i> <?= $proj['self_rating'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($proj['mentor_rating']): ?>
                                        <span class="badge bg-label-success">
                                            <i class="ri-star-fill"></i> <?= $proj['mentor_rating'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $statusClass[$proj['status_submission']] ?>">
                                        <i class="<?= $statusIcon[$proj['status_submission']] ?> me-1"></i>
                                        <?= ucfirst($proj['status_submission']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('project/detail/' . $proj['id_project']) ?>">
                                                    <i class="ri-eye-line me-2"></i> Detail
                                                </a>
                                            </li>
                                            <?php if ($proj['status_submission'] === 'draft'): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?= base_url('project/edit/' . $proj['id_project']) ?>">
                                                        <i class="ri-pencil-line me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                        onclick="deleteProject(<?= $proj['id_project'] ?>, '<?= esc($proj['judul_project']) ?>')">
                                                        <i class="ri-delete-bin-line me-2"></i> Hapus
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($proj['attachment']): ?>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="<?= base_url('project/attachment/view/' . $proj['id_project']) ?>" target="_blank">
                                                        <i class="ri-attachment-line me-2"></i> Lihat Attachment
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($proj['feedback_mentor']): ?>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="showFeedback('<?= addslashes($proj['feedback_mentor']) ?>')">
                                                        <i class="ri-message-line me-2"></i> Lihat Feedback
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
        <?php endif; ?>
    </div>
</div>

<script>
    // Year Filter
    document.getElementById('yearFilter').addEventListener('change', function() {
        window.location.href = '<?= base_url('project/my') ?>?year=' + this.value;
    });

    // Delete Project
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
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    });
            }
        });
    }

    // Show Feedback
    function showFeedback(feedback) {
        Swal.fire({
            title: 'Feedback dari Mentor',
            html: `<div class="alert alert-info text-start">${feedback}</div>`,
            icon: 'info'
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