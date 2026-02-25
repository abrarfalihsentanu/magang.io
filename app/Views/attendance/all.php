<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Semua Absensi</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">
                    <i class="ri-team-line me-2"></i>Semua Absensi Karyawan
                </h4>
                <p class="mb-0 text-muted">Monitoring kehadiran semua karyawan dan pemagang</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary" id="exportBtn">
                    <i class="ri-file-excel-line me-1"></i> Export
                </button>
                <button type="button" class="btn btn-outline-primary" id="refreshBtn">
                    <i class="ri-refresh-line me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1"><?= $stats['total'] ?></h3>
                        <small class="text-muted">Total Absensi</small>
                    </div>
                    <span class="avatar avatar-lg">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-file-list-3-line ri-26px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-success"><?= $stats['hadir'] ?></h3>
                        <small class="text-muted">Hadir</small>
                    </div>
                    <span class="avatar avatar-lg">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-checkbox-circle-line ri-26px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-warning"><?= $stats['izin'] ?></h3>
                        <small class="text-muted">Izin</small>
                    </div>
                    <span class="avatar avatar-lg">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-information-line ri-26px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-danger"><?= $stats['alpha'] ?></h3>
                        <small class="text-muted">Alpha</small>
                    </div>
                    <span class="avatar avatar-lg">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri-close-circle-line ri-26px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="<?= base_url('attendance/all') ?>">
            <div class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" class="form-control" name="search" placeholder="Nama / NIK..."
                        value="<?= $search ?? '' ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <input type="month" class="form-control" name="month" id="monthFilter"
                        value="<?= $selected_month ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Divisi</label>
                    <select class="form-select" name="divisi" id="divisiFilter">
                        <option value="">Semua Divisi</option>
                        <?php foreach ($divisi_list as $div): ?>
                            <option value="<?= $div['id_divisi'] ?>"
                                <?= $selected_divisi == $div['id_divisi'] ? 'selected' : '' ?>>
                                <?= $div['nama_divisi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="hadir" <?= $selected_status === 'hadir' ? 'selected' : '' ?>>Hadir</option>
                        <option value="terlambat" <?= $selected_status === 'terlambat' ? 'selected' : '' ?>>Terlambat</option>
                        <option value="izin" <?= $selected_status === 'izin' ? 'selected' : '' ?>>Izin</option>
                        <option value="sakit" <?= $selected_status === 'sakit' ? 'selected' : '' ?>>Sakit</option>
                        <option value="alpha" <?= $selected_status === 'alpha' ? 'selected' : '' ?>>Alpha</option>
                        <option value="cuti" <?= $selected_status === 'cuti' ? 'selected' : '' ?>>Cuti</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i> Filter
                    </button>
                    <a href="<?= base_url('attendance/all') ?>" class="btn btn-label-secondary">
                        <i class="ri-refresh-line me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Attendance Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tableView" role="tab">
                    <i class="ri-table-line me-1"></i> Tabel
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#summaryView" role="tab">
                    <i class="ri-bar-chart-box-line me-1"></i> Summary
                </button>
            </li>
        </ul>
        <div>
            <span class="badge bg-label-primary"><?= isset($total) ? $total : count($attendances) ?> Records</span>
        </div>
    </div>

    <div class="card-body">
        <div class="tab-content p-0">
            <!-- Table View -->
            <div class="tab-pane fade show active" id="tableView" role="tabpanel">
                <?php if (empty($attendances)): ?>
                    <div class="text-center py-5">
                        <i class="ri-inbox-line" style="font-size: 64px; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Tidak ada data absensi ditemukan</p>
                        <small class="text-muted">Coba ubah filter untuk melihat data lainnya</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Karyawan</th>
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
                                <?php
                                $no = ($currentPage - 1) * 10 + 1;
                                foreach ($attendances as $att):
                                    $statusClass = [
                                        'hadir' => 'success',
                                        'terlambat' => 'warning',
                                        'izin' => 'info',
                                        'sakit' => 'secondary',
                                        'alpha' => 'danger',
                                        'cuti' => 'primary'
                                    ];

                                    // Calculate total hours
                                    $totalJam = '-';
                                    if ($att['jam_masuk'] && $att['jam_keluar']) {
                                        $masuk = new DateTime($att['jam_masuk']);
                                        $keluar = new DateTime($att['jam_keluar']);
                                        $diff = $masuk->diff($keluar);
                                        $totalJam = $diff->format('%h:%I');
                                    }
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        <?= strtoupper(substr($att['nama_lengkap'], 0, 2)) ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong class="d-block"><?= $att['nama_lengkap'] ?></strong>
                                                    <small class="text-muted"><?= $att['nik'] ?></small>
                                                    <?php if ($att['nama_divisi']): ?>
                                                        <br><span class="badge bg-label-secondary mt-1"><?= $att['nama_divisi'] ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?= date('d M Y', strtotime($att['tanggal'])) ?></strong><br>
                                            <small class="text-muted"><?= date('l', strtotime($att['tanggal'])) ?></small>
                                        </td>
                                        <td>
                                            <?php if ($att['jam_masuk']): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-login-box-line text-success me-2"></i>
                                                    <div>
                                                        <strong><?= $att['jam_masuk'] ?></strong>
                                                        <?php if ($att['distance_masuk']): ?>
                                                            <br><small class="text-muted">
                                                                <i class="ri-map-pin-line"></i> <?= $att['distance_masuk'] ?>m
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($att['jam_keluar']): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-logout-box-line text-danger me-2"></i>
                                                    <div>
                                                        <strong><?= $att['jam_keluar'] ?></strong>
                                                        <?php if ($att['distance_keluar']): ?>
                                                            <br><small class="text-muted">
                                                                <i class="ri-map-pin-line"></i> <?= $att['distance_keluar'] ?>m
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($totalJam !== '-'): ?>
                                                <span class="badge bg-label-info">
                                                    <i class="ri-time-line me-1"></i><?= $totalJam ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-<?= $statusClass[$att['status']] ?>">
                                                <?= ucfirst($att['status']) ?>
                                            </span>
                                            <?php if ($att['is_manual']): ?>
                                                <br><small class="text-warning">
                                                    <i class="ri-edit-line"></i> Manual
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($att['latitude_masuk'] && $att['longitude_masuk']): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-view-map"
                                                    data-lat="<?= $att['latitude_masuk'] ?>"
                                                    data-lng="<?= $att['longitude_masuk'] ?>"
                                                    data-name="<?= $att['nama_lengkap'] ?>"
                                                    data-date="<?= date('d M Y', strtotime($att['tanggal'])) ?>">
                                                    <i class="ri-map-pin-line"></i>
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
                                                    <li>
                                                        <a class="dropdown-item btn-view-detail" href="javascript:void(0);"
                                                            data-id="<?= $att['id_attendance'] ?>"
                                                            data-name="<?= $att['nama_lengkap'] ?>"
                                                            data-date="<?= date('d M Y', strtotime($att['tanggal'])) ?>"
                                                            data-masuk="<?= $att['jam_masuk'] ?? '-' ?>"
                                                            data-keluar="<?= $att['jam_keluar'] ?? '-' ?>"
                                                            data-status="<?= $att['status'] ?>"
                                                            data-foto-masuk="<?= $att['foto_masuk'] ?>"
                                                            data-foto-keluar="<?= $att['foto_keluar'] ?>">
                                                            <i class="ri-eye-line me-2"></i> Lihat Detail
                                                        </a>
                                                    </li>
                                                    <?php if ($att['foto_masuk']): ?>
                                                        <li>
                                                            <a class="dropdown-item btn-view-photo" href="javascript:void(0);"
                                                                data-photo="<?= base_url('writable/uploads/attendance/' . $att['foto_masuk']) ?>"
                                                                data-title="Foto Check-in - <?= $att['nama_lengkap'] ?>">
                                                                <i class="ri-image-line me-2"></i> Foto Check-in
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($att['foto_keluar']): ?>
                                                        <li>
                                                            <a class="dropdown-item btn-view-photo" href="javascript:void(0);"
                                                                data-photo="<?= base_url('writable/uploads/attendance/' . $att['foto_keluar']) ?>"
                                                                data-title="Foto Check-out - <?= $att['nama_lengkap'] ?>">
                                                                <i class="ri-image-line me-2"></i> Foto Check-out
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($att['keterangan']): ?>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                                onclick="alert('<?= addslashes($att['keterangan']) ?>')">
                                                                <i class="ri-information-line me-2"></i> Keterangan
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

                    <!-- Pagination -->
                    <?php if (isset($pager)): ?>
                        <?= view('components/pagination_wrapper', [
                            'pager' => $pager,
                            'total' => $total ?? 0,
                            'perPage' => $perPage ?? 10,
                            'currentPage' => $currentPage ?? 1
                        ]) ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Summary View -->
            <div class="tab-pane fade" id="summaryView" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-label-primary mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Summary Kehadiran</h5>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Hadir:</span>
                                        <strong class="text-success"><?= $stats['hadir'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Izin:</span>
                                        <strong class="text-info"><?= $stats['izin'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Sakit:</span>
                                        <strong class="text-secondary"><?= $stats['sakit'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Alpha:</span>
                                        <strong class="text-danger"><?= $stats['alpha'] ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-label-success mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Persentase Kehadiran</h5>
                                <div class="text-center mt-4">
                                    <?php
                                    $totalRecords = $stats['total'];
                                    $persentase = $totalRecords > 0 ? round(($stats['hadir'] / $totalRecords) * 100, 2) : 0;
                                    ?>
                                    <h1 class="display-3"><?= $persentase ?>%</h1>
                                    <p class="text-muted">dari <?= $totalRecords ?> total absensi</p>
                                </div>
                            </div>
                        </div>
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

<!-- Modal: Detail -->
<div class="modal fade" id="detailModal-attendance-all" tabindex="-1" aria-labelledby="detailModal-attendance-allLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Detail Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent-attendance-all"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
    let modalMap;

    // Auto-submit filter on change
    document.getElementById('monthFilter').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('divisiFilter').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('statusFilter').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });

    // Export button
    document.getElementById('exportBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Export Data',
            text: 'Fitur export sedang dalam pengembangan',
            icon: 'info'
        });
    });

    // View Map
    document.querySelectorAll('.btn-view-map').forEach(btn => {
        btn.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            const name = this.dataset.name;
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
                    modalMap.eachLayer(layer => {
                        if (layer instanceof L.Marker) {
                            modalMap.removeLayer(layer);
                        }
                    });
                }

                L.marker([lat, lng]).addTo(modalMap)
                    .bindPopup(`<b>${name}</b><br>${date}`)
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

    // View Detail
    document.querySelectorAll('.btn-view-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;

            const statusClass = {
                'hadir': 'success',
                'terlambat': 'warning',
                'izin': 'info',
                'sakit': 'secondary',
                'alpha': 'danger',
                'cuti': 'primary'
            };

            const html = `
                <div class="mb-3">
                    <strong>Karyawan:</strong><br>
                    ${data.name}
                </div>
                <div class="mb-3">
                    <strong>Tanggal:</strong><br>
                    ${data.date}
                </div>
                <div class="mb-3">
                    <strong>Jam Masuk:</strong><br>
                    ${data.masuk}
                </div>
                <div class="mb-3">
                    <strong>Jam Keluar:</strong><br>
                    ${data.keluar}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong><br>
                    <span class="badge bg-${statusClass[data.status]}">${data.status.toUpperCase()}</span>
                </div>
                ${data.fotoMasuk ? `
                    <div class="mb-3">
                        <strong>Foto Check-in:</strong><br>
                        <img src="<?= base_url('writable/uploads/attendance/') ?>${data.fotoMasuk}" 
                             class="img-fluid rounded mt-2" style="max-height: 200px;">
                    </div>
                ` : ''}
                ${data.fotoKeluar ? `
                    <div class="mb-3">
                        <strong>Foto Check-out:</strong><br>
                        <img src="<?= base_url('writable/uploads/attendance/') ?>${data.fotoKeluar}" 
                             class="img-fluid rounded mt-2" style="max-height: 200px;">
                    </div>
                ` : ''}
            `;

            document.getElementById('detailContent-attendance-all').innerHTML = html;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-attendance-all'));
            modal.show();
        });
    });
</script>

<?= $this->endSection() ?>