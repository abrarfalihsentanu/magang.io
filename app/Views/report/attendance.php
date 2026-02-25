<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Laporan Absensi</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1"><i class="ri-file-chart-line me-2"></i>Laporan Absensi</h4>
                <p class="mb-0 text-muted">Rekap kehadiran pemagang per periode</p>
            </div>
            <button type="button" class="btn btn-success" id="btnExport">
                <i class="ri-download-2-line me-1"></i> Export CSV
            </button>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php
                    $bulanNames = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                    for ($i = 1; $i <= 12; $i++):
                        $m = str_pad($i, 2, '0', STR_PAD_LEFT);
                    ?>
                        <option value="<?= $m ?>" <?= $filters['bulan'] == $m ? 'selected' : '' ?>><?= $bulanNames[$m] ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                        <option value="<?= $y ?>" <?= $filters['tahun'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Divisi</label>
                <select name="divisi" class="form-select">
                    <option value="">Semua Divisi</option>
                    <?php foreach ($divisions as $div): ?>
                        <option value="<?= $div['id_divisi'] ?>" <?= $filters['divisi'] == $div['id_divisi'] ? 'selected' : '' ?>>
                            <?= esc($div['nama_divisi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="hadir" <?= $filters['status'] == 'hadir' ? 'selected' : '' ?>>Hadir</option>
                    <option value="terlambat" <?= $filters['status'] == 'terlambat' ? 'selected' : '' ?>>Terlambat</option>
                    <option value="izin" <?= $filters['status'] == 'izin' ? 'selected' : '' ?>>Izin</option>
                    <option value="sakit" <?= $filters['status'] == 'sakit' ? 'selected' : '' ?>>Sakit</option>
                    <option value="alpha" <?= $filters['status'] == 'alpha' ? 'selected' : '' ?>>Alpha</option>
                    <option value="cuti" <?= $filters['status'] == 'cuti' ? 'selected' : '' ?>>Cuti</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ri-filter-line me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-bar-chart-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['total']) ?></h5>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-check-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['hadir']) ?></h5>
                        <small class="text-muted">Hadir</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['terlambat']) ?></h5>
                        <small class="text-muted">Terlambat</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-calendar-event-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['izin'] + $summary['sakit'] + $summary['cuti']) ?></h5>
                        <small class="text-muted">Izin/Sakit</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri-close-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['alpha']) ?></h5>
                        <small class="text-muted">Alpha</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-white text-primary">
                            <i class="ri-percent-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0 text-white"><?= $summary['persentase_kehadiran'] ?>%</h5>
                        <small>Kehadiran</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Distribusi Status Kehadiran</h5>
            </div>
            <div class="card-body">
                <div id="chartStatus" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Ringkasan</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="ri-checkbox-circle-fill text-success me-2"></i>Hadir</span>
                        <span class="badge bg-success rounded-pill"><?= $summary['hadir'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="ri-time-fill text-warning me-2"></i>Terlambat</span>
                        <span class="badge bg-warning rounded-pill"><?= $summary['terlambat'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="ri-calendar-event-fill text-info me-2"></i>Izin</span>
                        <span class="badge bg-info rounded-pill"><?= $summary['izin'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="ri-hospital-fill text-secondary me-2"></i>Sakit</span>
                        <span class="badge bg-secondary rounded-pill"><?= $summary['sakit'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="ri-calendar-2-fill text-primary me-2"></i>Cuti</span>
                        <span class="badge bg-primary rounded-pill"><?= $summary['cuti'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="ri-close-circle-fill text-danger me-2"></i>Alpha</span>
                        <span class="badge bg-danger rounded-pill"><?= $summary['alpha'] ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Detail Absensi</h5>
    </div>
    <?php if (!empty($attendances)): ?>
        <div class="table-responsive">
            <table class="table table-hover table-paginated" id="attendanceTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Pemagang</th>
                        <th>Divisi</th>
                        <th class="text-center">Jam Masuk</th>
                        <th class="text-center">Jam Keluar</th>
                        <th class="text-center">Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendances as $idx => $att): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><?= date('d/m/Y', strtotime($att['tanggal'])) ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong><?= esc($att['nama_lengkap']) ?></strong>
                                    <small class="text-muted"><?= esc($att['nik']) ?></small>
                                </div>
                            </td>
                            <td><?= esc($att['nama_divisi'] ?? '-') ?></td>
                            <td class="text-center">
                                <?= !empty($att['jam_masuk']) ? date('H:i', strtotime($att['jam_masuk'])) : '-' ?>
                            </td>
                            <td class="text-center">
                                <?= !empty($att['jam_keluar']) ? date('H:i', strtotime($att['jam_keluar'])) : '-' ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $statusBadge = [
                                    'hadir' => 'success',
                                    'terlambat' => 'warning',
                                    'izin' => 'info',
                                    'sakit' => 'secondary',
                                    'cuti' => 'primary',
                                    'alpha' => 'danger'
                                ];
                                $badge = $statusBadge[$att['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= ucfirst($att['status']) ?></span>
                            </td>
                            <td>
                                <small class="text-muted"><?= esc($att['keterangan'] ?? '-') ?></small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card-body text-center py-5">
            <i class="ri-calendar-close-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Tidak Ada Data</h5>
            <p class="text-muted">Tidak ada data absensi untuk periode yang dipilih.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Status Distribution Chart
    const chartOptions = {
        series: [<?= $summary['hadir'] ?>, <?= $summary['terlambat'] ?>, <?= $summary['izin'] ?>, <?= $summary['sakit'] ?>, <?= $summary['cuti'] ?>, <?= $summary['alpha'] ?>],
        chart: {
            type: 'donut',
            height: 300,
            toolbar: {
                show: false
            }
        },
        labels: ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Cuti', 'Alpha'],
        colors: ['#28c76f', '#ff9f43', '#00cfe8', '#6c757d', '#7367f0', '#ea5455'],
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function(w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const chart = new ApexCharts(document.querySelector("#chartStatus"), chartOptions);
    chart.render();

    // Filter Form Submit
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const params = new URLSearchParams(formData).toString();
        window.location.href = '<?= base_url('report/attendance') ?>?' + params;
    });

    // Export Button
    document.getElementById('btnExport').addEventListener('click', function() {
        const filters = {
            bulan: document.querySelector('[name="bulan"]').value,
            tahun: document.querySelector('[name="tahun"]').value,
            divisi: document.querySelector('[name="divisi"]').value,
            status: document.querySelector('[name="status"]').value
        };

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('report/export') ?>';

        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = 'attendance';
        form.appendChild(typeInput);

        Object.keys(filters).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `filters[${key}]`;
            input.value = filters[key];
            form.appendChild(input);
        });

        // Add CSRF token
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = csrfMeta.content;
            form.appendChild(csrfInput);
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
</script>
<?= $this->endSection() ?>