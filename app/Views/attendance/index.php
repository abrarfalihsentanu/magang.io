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
                <li class="breadcrumb-item active">Riwayat Absensi</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">
                    <i class="ri-calendar-check-line me-2"></i>Riwayat Absensi Saya
                </h4>
                <p class="mb-0 text-muted">Lihat riwayat dan statistik kehadiran Anda</p>
            </div>
            <div>
                <a href="<?= base_url('attendance/checkin') ?>" class="btn btn-primary">
                    <i class="ri-map-pin-user-line me-1"></i> Check In/Out
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="content-left">
                        <h3 class="mb-1"><?= $summary['total'] ?></h3>
                        <small>Total Hari</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-calendar-2-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="content-left">
                        <h3 class="mb-1 text-success"><?= $summary['hadir'] ?></h3>
                        <small>Hadir</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-checkbox-circle-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="content-left">
                        <h3 class="mb-1 text-info"><?= $summary['persentase'] ?>%</h3>
                        <small>Persentase</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-percent-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="content-left">
                        <h3 class="mb-1 text-warning"><?= $summary['izin'] ?></h3>
                        <small>Izin</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-information-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="content-left">
                        <h3 class="mb-1 text-secondary"><?= $summary['sakit'] ?></h3>
                        <small>Sakit</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="ri-nurse-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="content-left">
                        <h3 class="mb-1 text-danger"><?= $summary['alpha'] ?></h3>
                        <small>Alpha</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri-close-circle-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & View Toggle -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label class="form-label mb-1">Filter Bulan</label>
                        <input type="month" class="form-control" id="monthFilter" value="<?= $selected_month ?>">
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <label class="form-label mb-1">Tampilan</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary active" id="listViewBtn">
                                <i class="ri-list-check me-1"></i> List
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="calendarViewBtn">
                                <i class="ri-calendar-view me-1"></i> Kalender
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <label class="form-label mb-1">&nbsp;</label>
                        <a href="<?= base_url('attendance/correction') ?>" class="btn btn-outline-secondary w-100">
                            <i class="ri-edit-line me-1"></i> Ajukan Koreksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- List View -->
<div id="listView" class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kehadiran</h5>
                <span class="badge bg-label-primary"><?= count($attendances) ?> Records</span>
            </div>
            <div class="card-body">
                <?php if (empty($attendances)): ?>
                    <div class="text-center py-5">
                        <i class="ri-inbox-line" style="font-size: 64px; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada data absensi untuk bulan ini</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-paginated">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Total Jam</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attendances as $att): ?>
                                    <?php
                                    $statusClass = [
                                        'hadir' => 'success',
                                        'terlambat' => 'warning',
                                        'izin' => 'info',
                                        'sakit' => 'secondary',
                                        'alpha' => 'danger',
                                        'cuti' => 'primary'
                                    ];
                                    $statusIcon = [
                                        'hadir' => 'ri-checkbox-circle-line',
                                        'terlambat' => 'ri-time-line',
                                        'izin' => 'ri-information-line',
                                        'sakit' => 'ri-nurse-line',
                                        'alpha' => 'ri-close-circle-line',
                                        'cuti' => 'ri-calendar-event-line'
                                    ];

                                    // Calculate total hours
                                    $totalJam = '-';
                                    if ($att['jam_masuk'] && $att['jam_keluar']) {
                                        $masuk = new DateTime($att['jam_masuk']);
                                        $keluar = new DateTime($att['jam_keluar']);
                                        $diff = $masuk->diff($keluar);
                                        $totalJam = $diff->format('%h jam %i menit');
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?= date('d M Y', strtotime($att['tanggal'])) ?></strong><br>
                                            <small class="text-muted"><?= date('l', strtotime($att['tanggal'])) ?></small>
                                        </td>
                                        <td>
                                            <?php if ($att['jam_masuk']): ?>
                                                <i class="ri-login-box-line text-success me-1"></i>
                                                <?= $att['jam_masuk'] ?>
                                                <?php if ($att['distance_masuk']): ?>
                                                    <br><small class="text-muted"><?= $att['distance_masuk'] ?>m</small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($att['jam_keluar']): ?>
                                                <i class="ri-logout-box-line text-danger me-1"></i>
                                                <?= $att['jam_keluar'] ?>
                                                <?php if ($att['distance_keluar']): ?>
                                                    <br><small class="text-muted"><?= $att['distance_keluar'] ?>m</small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($totalJam !== '-'): ?>
                                                <span class="badge bg-label-info"><?= $totalJam ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-<?= $statusClass[$att['status']] ?>">
                                                <i class="<?= $statusIcon[$att['status']] ?> me-1"></i>
                                                <?= ucfirst($att['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($att['latitude_masuk'] && $att['longitude_masuk']): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-view-map"
                                                    data-lat="<?= $att['latitude_masuk'] ?>"
                                                    data-lng="<?= $att['longitude_masuk'] ?>"
                                                    data-date="<?= date('d M Y', strtotime($att['tanggal'])) ?>">
                                                    <i class="ri-map-pin-line me-1"></i> Lihat
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-line"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <?php if ($att['foto_masuk']): ?>
                                                        <li>
                                                            <a class="dropdown-item btn-view-photo" href="javascript:void(0);"
                                                                data-photo="<?= base_url('writable/uploads/attendance/' . $att['foto_masuk']) ?>"
                                                                data-title="Foto Check-in - <?= date('d M Y', strtotime($att['tanggal'])) ?>">
                                                                <i class="ri-image-line me-2"></i> Lihat Foto Check-in
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($att['foto_keluar']): ?>
                                                        <li>
                                                            <a class="dropdown-item btn-view-photo" href="javascript:void(0);"
                                                                data-photo="<?= base_url('writable/uploads/attendance/' . $att['foto_keluar']) ?>"
                                                                data-title="Foto Check-out - <?= date('d M Y', strtotime($att['tanggal'])) ?>">
                                                                <i class="ri-image-line me-2"></i> Lihat Foto Check-out
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($att['tanggal'] === $today && !$att['jam_keluar']): ?>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('attendance/checkin') ?>">
                                                                <i class="ri-logout-box-line me-2"></i> Check-out Sekarang
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
</div>

<!-- Calendar View (Hidden by default) -->
<div id="calendarView" class="row" style="display: none;">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Kalender Kehadiran</h5>
            </div>
            <div class="card-body">
                <div id="attendanceCalendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Legend for Calendar -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Hadir</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Terlambat</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Izin</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Sakit</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Alpha</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">&nbsp;&nbsp;&nbsp;</span>
                        <small>Cuti</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: View Map -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Lokasi Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalMap" style="height: 400px; width: 100%;"></div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Latitude:</small><br>
                            <strong id="modalLat">-</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Longitude:</small><br>
                            <strong id="modalLng">-</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: View Photo -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalTitle">Foto Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="photoModalImage" src="" alt="Foto Absensi" class="img-fluid rounded" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
    let calendar;
    let modalMap;

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
        const month = this.value;
        window.location.href = '<?= base_url('attendance') ?>?month=' + month;
    });

    // Initialize Calendar
    function initCalendar() {
        const calendarEl = document.getElementById('attendanceCalendar');

        // Prepare events from PHP data
        const events = [];
        <?php foreach ($attendances as $att): ?>
            events.push({
                title: '<?= ucfirst($att['status']) ?>',
                start: '<?= $att['tanggal'] ?>',
                backgroundColor: getStatusColor('<?= $att['status'] ?>'),
                borderColor: getStatusColor('<?= $att['status'] ?>'),
                extendedProps: {
                    jamMasuk: '<?= $att['jam_masuk'] ?? '-' ?>',
                    jamKeluar: '<?= $att['jam_keluar'] ?? '-' ?>',
                    status: '<?= $att['status'] ?>'
                }
            });
        <?php endforeach; ?>

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: '<?= $selected_month ?>-01',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: events,
            eventClick: function(info) {
                Swal.fire({
                    title: info.event.title,
                    html: `
                        <div class="text-start">
                            <p><strong>Tanggal:</strong> ${info.event.startStr}</p>
                            <p><strong>Jam Masuk:</strong> ${info.event.extendedProps.jamMasuk}</p>
                            <p><strong>Jam Keluar:</strong> ${info.event.extendedProps.jamKeluar}</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        });

        calendar.render();
    }

    function getStatusColor(status) {
        const colors = {
            'hadir': '#28c76f',
            'terlambat': '#ff9f43',
            'izin': '#00cfe8',
            'sakit': '#82868b',
            'alpha': '#ea5455',
            'cuti': '#7367f0'
        };
        return colors[status] || '#82868b';
    }

    // View Map
    document.querySelectorAll('.btn-view-map').forEach(btn => {
        btn.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            const date = this.dataset.date;

            document.getElementById('modalLat').textContent = lat.toFixed(6);
            document.getElementById('modalLng').textContent = lng.toFixed(6);

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('mapModal'));
            modal.show();

            setTimeout(() => {
                if (!modalMap) {
                    modalMap = L.map('modalMap').setView([lat, lng], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(modalMap);
                } else {
                    modalMap.setView([lat, lng], 16);
                }

                L.marker([lat, lng]).addTo(modalMap)
                    .bindPopup(`<b>Lokasi Absensi</b><br>${date}`)
                    .openPopup();

                modalMap.invalidateSize();
            }, 300);
        });
    });

    // View Photo
    document.querySelectorAll('.btn-view-photo').forEach(btn => {
        btn.addEventListener('click', function() {
            const photo = this.dataset.photo;
            const title = this.dataset.title;

            document.getElementById('photoModalImage').src = photo;
            document.getElementById('photoModalTitle').textContent = title;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('photoModal'));
            modal.show();
        });
    });
</script>

<?= $this->endSection() ?>