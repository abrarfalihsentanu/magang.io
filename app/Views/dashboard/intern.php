<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1"><i class="ri-dashboard-3-line me-2"></i>Dashboard Pemagang</h4>
        <p class="mb-0 text-muted">Selamat datang, <?= esc($user['name'] ?? 'Pemagang') ?>!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-success"><?= $stats['attendance_this_month']['hadir'] ?? 0 ?></h3>
                        <small>Hadir Bulan Ini</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-success"><i class="ri-calendar-check-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1"><?= $stats['attendance_this_month']['total_days'] ?? 0 ?></h3>
                        <small>Total Hari Tercatat</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-primary"><i class="ri-calendar-2-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-info"><?= $stats['kpi_score'] ?? 0 ?></h3>
                        <small>Skor KPI</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-info"><i class="ri-bar-chart-box-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-warning"><?= $stats['activities_submitted'] ?? 0 ?></h3>
                        <small>Aktivitas Dikirim</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-warning"><i class="ri-file-list-3-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Uang Saku & Quick Actions -->
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-money-dollar-circle-line me-2"></i>Uang Saku</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-lg me-3">
                        <span class="avatar-initial rounded bg-label-success"><i class="ri-wallet-3-line ri-24px"></i></span>
                    </div>
                    <div>
                        <h4 class="mb-0">Rp <?= number_format($stats['allowance_this_period'] ?? 0, 0, ',', '.') ?></h4>
                        <small class="text-muted">Periode ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-flashlight-line me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('attendance/checkin') ?>" class="btn btn-primary"><i class="ri-map-pin-user-line me-1"></i> Check In / Check Out</a>
                    <a href="<?= base_url('attendance') ?>" class="btn btn-outline-primary"><i class="ri-calendar-check-line me-1"></i> Rekap Absensi</a>
                    <a href="<?= base_url('activity') ?>" class="btn btn-outline-primary"><i class="ri-file-list-3-line me-1"></i> Aktivitas Harian</a>
                    <a href="<?= base_url('project') ?>" class="btn btn-outline-primary"><i class="ri-folder-chart-line me-1"></i> Proyek Mingguan</a>
                    <a href="<?= base_url('leave/my') ?>" class="btn btn-outline-primary"><i class="ri-calendar-event-line me-1"></i> Cuti / Izin / Sakit</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="row mb-4">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi Saya</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted"><i class="ri-user-line me-1"></i> Nama</td>
                                    <td class="fw-semibold"><?= esc($user['name'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="ri-mail-line me-1"></i> Email</td>
                                    <td class="fw-semibold"><?= esc($user['email'] ?? '-') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted"><i class="ri-building-4-line me-1"></i> Divisi</td>
                                    <td class="fw-semibold"><?= esc($user['divisi'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="ri-calendar-line me-1"></i> Tanggal</td>
                                    <td class="fw-semibold"><?= date('d F Y') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance vs Target + Activity Chart -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-focus-3-line me-2"></i>Kehadiran vs Target</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div id="chart-attendance-radial" style="min-height:200px; width:100%"></div>
                <p class="mt-2 mb-0 text-center" id="attendance-vs-target-text">Memuat...</p>
            </div>
        </div>
    </div>
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-bar-chart-2-line me-2"></i>Aktivitas - 4 Minggu Terakhir</h6>
            </div>
            <div class="card-body">
                <div id="chart-intern-activities" style="min-height:280px"></div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Calendar + Allowance History -->
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-calendar-event-line me-2"></i>Kalender Kehadiran (Terakhir)</h6>
            </div>
            <div class="card-body" style="max-height:400px; overflow-y:auto" id="attendance-calendar">
                <p class="text-muted text-center">Memuat...</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-wallet-3-line me-2"></i>Riwayat Uang Saku - 3 Bulan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th class="text-end">Jumlah (Rp)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="table-allowance-history">
                            <tr>
                                <td colspan="3" class="text-center text-muted">Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attendance vs Target (Radial Chart)
        csrfFetch('<?= base_url("dashboard/data/attendance-vs-target") ?>')
            .then(function(data) {
                var pct = Math.round((data.present / (data.target || 1)) * 100);
                if (pct > 100) pct = 100;
                new ApexCharts(document.querySelector('#chart-attendance-radial'), {
                    chart: {
                        type: 'radialBar',
                        height: 200
                    },
                    series: [pct],
                    labels: ['Kehadiran'],
                    colors: [pct >= 80 ? '#71dd37' : (pct >= 50 ? '#ffab00' : '#ff3e1d')],
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: '60%'
                            },
                            dataLabels: {
                                name: {
                                    fontSize: '14px',
                                    offsetY: -5
                                },
                                value: {
                                    fontSize: '24px',
                                    fontWeight: 700,
                                    offsetY: 5
                                }
                            }
                        }
                    }
                }).render();
                document.getElementById('attendance-vs-target-text').innerHTML =
                    '<span class="fw-semibold">' + data.present + '</span> dari <span class="fw-semibold">' + data.target + '</span> hari target';
            }).catch(function() {
                document.querySelector('#chart-attendance-radial').innerHTML = '<p class="text-muted text-center">Gagal memuat</p>';
            });

        // Intern Activities Bar Chart
        csrfFetch('<?= base_url("dashboard/data/intern-activities") ?>')
            .then(function(data) {
                new ApexCharts(document.querySelector('#chart-intern-activities'), {
                    chart: {
                        type: 'bar',
                        height: 280,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Aktivitas',
                        data: data.series || []
                    }],
                    xaxis: {
                        categories: data.labels || []
                    },
                    colors: ['#696cff'],
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '50%'
                        }
                    },
                    dataLabels: {
                        enabled: true
                    }
                }).render();
            }).catch(function() {
                document.querySelector('#chart-intern-activities').innerHTML = '<p class="text-muted text-center">Gagal memuat data</p>';
            });

        // Attendance Calendar (List)
        csrfFetch('<?= base_url("dashboard/data/attendance-calendar") ?>')
            .then(function(data) {
                var el = document.getElementById('attendance-calendar');
                var events = data.events || [];
                if (events.length === 0) {
                    el.innerHTML = '<p class="text-muted text-center">Tidak ada data kehadiran</p>';
                    return;
                }
                el.innerHTML = '';
                events.slice(0, 50).forEach(function(ev) {
                    var statusColor = 'secondary';
                    var s = (ev.status || '').toLowerCase();
                    if (s === 'hadir') statusColor = 'success';
                    else if (s === 'alpha' || s === 'tidak hadir') statusColor = 'danger';
                    else if (s === 'izin' || s === 'sakit') statusColor = 'warning';
                    el.innerHTML += '<div class="d-flex align-items-center mb-2">' +
                        '<span class="badge bg-label-' + statusColor + ' me-2" style="min-width:70px">' + escapeHtml(ev.status || '-') + '</span>' +
                        '<span>' + escapeHtml(ev.tanggal || '') + '</span></div>';
                });
            }).catch(function() {});

        // Allowance History Table
        csrfFetch('<?= base_url("dashboard/data/allowance-history") ?>')
            .then(function(data) {
                var el = document.getElementById('table-allowance-history');
                var history = data.history || [];
                if (history.length === 0) {
                    el.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Belum ada riwayat uang saku</td></tr>';
                    return;
                }
                el.innerHTML = '';
                history.forEach(function(h) {
                    var amt = new Intl.NumberFormat('id-ID').format(h.amount || 0);
                    var status = escapeHtml(h.status || '');
                    var statusBadge = status === 'paid' ? 'bg-label-success' : 'bg-label-warning';
                    el.innerHTML += '<tr><td>' + escapeHtml(h.created_at || h.createdAt || '') + '</td><td class="text-end">Rp ' + amt + '</td><td><span class="badge ' + statusBadge + '">' + status + '</span></td></tr>';
                });
            }).catch(function() {});
    });
</script>
<?= $this->endSection() ?>