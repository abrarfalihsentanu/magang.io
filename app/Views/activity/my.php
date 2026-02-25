<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Aktivitas Harian</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-file-list-3-line me-2"></i>Aktivitas Harian Saya
                </h4>
                <p class="mb-0 text-muted">Kelola dan pantau aktivitas harian Anda</p>
            </div>
            <div>
                <a href="<?= base_url('activity/create') ?>" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i> Tambah Aktivitas
                </a>
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
                        <small class="mb-0">Aktivitas</small>
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
                        <span class="text-heading">Approved</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2 text-success"><?= $statistics['approved'] ?></h4>
                        </div>
                        <small class="mb-0">Disetujui</small>
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
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Rejected</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2 text-danger"><?= $statistics['rejected'] ?></h4>
                        </div>
                        <small class="mb-0">Ditolak</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri-close-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card bg-label-primary">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Periode</small>
                <strong><?= date('M Y', strtotime($selected_month)) ?></strong>
            </div>
        </div>
    </div>
</div>

<!-- Filter & View Toggle -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label class="form-label">Filter Bulan</label>
                        <input type="month" class="form-control" id="monthFilter" value="<?= $selected_month ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tampilan</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary active" id="listViewBtn">
                                <i class="ri-list-check me-1"></i> List
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="calendarViewBtn">
                                <i class="ri-calendar-view me-1"></i> Kalender
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- List View -->
<div id="listView">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 me-2">Daftar Aktivitas</h5>
            <span class="badge bg-label-primary"><?= count($activities) ?> Records</span>
        </div>
        <div class="card-body">
            <?php if (empty($activities)): ?>
                <div class="text-center py-5">
                    <i class="ri-file-list-line" style="font-size: 64px; opacity: 0.3;"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada aktivitas untuk bulan ini</p>
                    <a href="<?= base_url('activity/create') ?>" class="btn btn-sm btn-primary mt-3">
                        <i class="ri-add-line me-1"></i> Tambah Aktivitas Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-paginated">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php foreach ($activities as $act): ?>
                                <?php
                                $statusClass = [
                                    'draft' => 'warning',
                                    'submitted' => 'info',
                                    'approved' => 'success',
                                    'rejected' => 'danger'
                                ];
                                $statusIcon = [
                                    'draft' => 'ri-draft-line',
                                    'submitted' => 'ri-send-plane-line',
                                    'approved' => 'ri-check-line',
                                    'rejected' => 'ri-close-line'
                                ];
                                $kategoriClass = [
                                    'learning' => 'info',
                                    'task' => 'primary',
                                    'meeting' => 'warning',
                                    'training' => 'success',
                                    'other' => 'secondary'
                                ];
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= date('d M Y', strtotime($act['tanggal'])) ?></strong><br>
                                        <small class="text-muted"><?= date('l', strtotime($act['tanggal'])) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= esc($act['judul_aktivitas']) ?></strong><br>
                                        <small class="text-muted">
                                            <?= substr(esc($act['deskripsi']), 0, 60) ?>...
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-<?= $kategoriClass[$act['kategori']] ?>">
                                            <?= ucfirst($act['kategori']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="ri-time-line me-1"></i>
                                            <?= $act['jam_mulai'] ?> - <?= $act['jam_selesai'] ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-<?= $statusClass[$act['status_approval']] ?>">
                                            <i class="<?= $statusIcon[$act['status_approval']] ?> me-1"></i>
                                            <?= ucfirst($act['status_approval']) ?>
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
                                                    <a class="dropdown-item" href="<?= base_url('activity/detail/' . $act['id_activity']) ?>">
                                                        <i class="ri-eye-line me-2"></i> Detail
                                                    </a>
                                                </li>
                                                <?php if ($act['status_approval'] === 'draft'): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= base_url('activity/edit/' . $act['id_activity']) ?>">
                                                            <i class="ri-pencil-line me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                            onclick="deleteActivity(<?= $act['id_activity'] ?>, '<?= esc($act['judul_aktivitas']) ?>')">
                                                            <i class="ri-delete-bin-line me-2"></i> Hapus
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($act['attachment']): ?>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= base_url('activity/attachment/view/' . $act['id_activity']) ?>" target="_blank">
                                                            <i class="ri-attachment-line me-2"></i> Lihat Lampiran
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($act['catatan_mentor']): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="showFeedback('<?= addslashes($act['catatan_mentor']) ?>')">
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
</div>

<!-- Calendar View -->
<div id="calendarView" style="display: none;">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title m-0 me-2">Kalender Aktivitas</h5>
        </div>
        <div class="card-body">
            <div id="activityCalendar"></div>
        </div>
    </div>
</div>

<!-- Legend -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Draft</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Submitted</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Approved</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Rejected</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    let calendar;

    // View Toggle
    document.getElementById('listViewBtn').addEventListener('click', function() {
        document.getElementById('listView').style.display = 'block';
        document.getElementById('calendarView').style.display = 'none';
        this.classList.add('active');
        document.getElementById('calendarViewBtn').classList.remove('active');
    });

    document.getElementById('calendarViewBtn').addEventListener('click', function() {
        document.getElementById('listView').style.display = 'none';
        document.getElementById('calendarView').style.display = 'block';
        this.classList.add('active');
        document.getElementById('listViewBtn').classList.remove('active');

        if (!calendar) {
            initCalendar();
        }
    });

    // Month Filter
    document.getElementById('monthFilter').addEventListener('change', function() {
        window.location.href = '<?= base_url('activity/my') ?>?month=' + this.value;
    });

    // Initialize Calendar
    function initCalendar() {
        const calendarEl = document.getElementById('activityCalendar');
        const events = [];

        <?php foreach ($calendar_data as $act): ?>
            events.push({
                title: '<?= esc($act['judul_aktivitas']) ?>',
                start: '<?= $act['tanggal'] ?>',
                backgroundColor: getStatusColor('<?= $act['status_approval'] ?>'),
                borderColor: getStatusColor('<?= $act['status_approval'] ?>'),
                extendedProps: {
                    kategori: '<?= $act['kategori'] ?>',
                    jamMulai: '<?= $act['jam_mulai'] ?>',
                    jamSelesai: '<?= $act['jam_selesai'] ?>',
                    status: '<?= $act['status_approval'] ?>'
                }
            });
        <?php endforeach; ?>

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: '<?= $selected_month ?>-01',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: events,
            eventClick: function(info) {
                Swal.fire({
                    title: info.event.title,
                    html: `
                        <div class="text-start">
                            <p><strong>Tanggal:</strong> ${info.event.startStr}</p>
                            <p><strong>Kategori:</strong> ${info.event.extendedProps.kategori}</p>
                            <p><strong>Waktu:</strong> ${info.event.extendedProps.jamMulai} - ${info.event.extendedProps.jamSelesai}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${getStatusBadge(info.event.extendedProps.status)}">${info.event.extendedProps.status}</span></p>
                        </div>
                    `,
                    icon: 'info'
                });
            }
        });

        calendar.render();
    }

    function getStatusColor(status) {
        const colors = {
            'draft': '#ff9f43',
            'submitted': '#00cfe8',
            'approved': '#28c76f',
            'rejected': '#ea5455'
        };
        return colors[status] || '#82868b';
    }

    function getStatusBadge(status) {
        const badges = {
            'draft': 'warning',
            'submitted': 'info',
            'approved': 'success',
            'rejected': 'danger'
        };
        return badges[status] || 'secondary';
    }

    // Delete Activity
    function deleteActivity(id, judul) {
        Swal.fire({
            title: 'Hapus Aktivitas?',
            html: `Aktivitas "<strong>${judul}</strong>" akan dihapus permanen.`,
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

                fetch(`<?= base_url('activity/delete/') ?>${id}`, {
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
                    });
            }
        });
    }

    // Show Feedback
    function showFeedback(catatan) {
        Swal.fire({
            title: 'Feedback dari Mentor',
            html: `<div class="alert alert-info text-start">${catatan}</div>`,
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