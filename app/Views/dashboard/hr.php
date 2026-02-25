<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1"><i class="ri-dashboard-3-line me-2"></i>Dashboard HR</h4>
        <p class="mb-0 text-muted">Selamat datang, <?= esc($user['name'] ?? 'HR') ?>!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1"><?= $stats['total_interns'] ?? 0 ?></h3>
                        <small>Pemagang Aktif</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-primary"><i class="ri-user-star-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-success"><?= $stats['attendance_today'] ?? 0 ?></h3>
                        <small>Hadir Hari Ini</small>
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
                        <h3 class="mb-1 text-warning"><?= $stats['pending_approvals'] ?? 0 ?></h3>
                        <small>Pending Approval</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-warning"><i class="ri-time-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-info">Rp <?= number_format($stats['total_allowance'] ?? 0, 0, ',', '.') ?></h3>
                        <small>Total Uang Saku</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-info"><i class="ri-money-dollar-circle-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Info -->
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-flashlight-line me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('user') ?>" class="btn btn-outline-primary"><i class="ri-user-settings-line me-1"></i> Kelola User</a>
                    <a href="<?= base_url('intern') ?>" class="btn btn-outline-primary"><i class="ri-user-star-line me-1"></i> Data Pemagang</a>
                    <a href="<?= base_url('attendance/all') ?>" class="btn btn-outline-primary"><i class="ri-calendar-check-line me-1"></i> Data Absensi</a>
                    <a href="<?= base_url('attendance/correction/approval') ?>" class="btn btn-outline-warning"><i class="ri-checkbox-circle-line me-1"></i> Approval Koreksi</a>
                    <a href="<?= base_url('leave/approval') ?>" class="btn btn-outline-warning"><i class="ri-calendar-event-line me-1"></i> Approval Cuti/Izin</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-information-line me-2"></i>Informasi</h5>
            </div>
            <div class="card-body">
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
                        <tr>
                            <td class="text-muted"><i class="ri-shield-user-line me-1"></i> Role</td>
                            <td><span class="badge bg-label-info"><?= esc($user['role'] ?? 'HR') ?></span></td>
                        </tr>
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

<!-- Charts Row: Attendance 7 days + Interns per Division -->
<div class="row mb-4">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-line-chart-line me-2"></i>Absensi - 7 Hari Terakhir</h6>
            </div>
            <div class="card-body">
                <div id="chart-attendance" style="min-height:300px"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-pie-chart-line me-2"></i>Pemagang per Divisi</h6>
            </div>
            <div class="card-body">
                <div id="chart-interns-division" style="min-height:300px"></div>
            </div>
        </div>
    </div>
</div>

<!-- HR Specific: Attendance 3 Months + Pending Corrections + Daily per Division -->
<div class="row mb-4">
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-bar-chart-grouped-line me-2"></i>Rekap Absensi 3 Bulan</h6>
            </div>
            <div class="card-body">
                <div id="chart-attendance-3months" style="min-height:260px"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-error-warning-line me-2"></i>Koreksi Pending</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="mb-3">
                    <span class="avatar avatar-lg">
                        <span class="avatar-initial rounded bg-label-warning" id="pending-corrections-badge">
                            <span style="font-size:1.5rem" id="pending-corrections-count">0</span>
                        </span>
                    </span>
                </div>
                <p class="text-muted mb-3">Koreksi menunggu approval</p>
                <a href="<?= base_url('attendance/correction/approval') ?>" class="btn btn-sm btn-warning">
                    <i class="ri-arrow-right-line me-1"></i> Lihat & Proses
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-building-line me-2"></i>Kehadiran Harian per Divisi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Divisi</th>
                                <th class="text-end">Hadir</th>
                            </tr>
                        </thead>
                        <tbody id="table-daily-attendance-division">
                            <tr>
                                <td colspan="2" class="text-center text-muted">Memuat...</td>
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
        // Attendance Trend Chart
        csrfFetch('<?= base_url("dashboard/data/attendance-trend") ?>')
            .then(function(data) {
                new ApexCharts(document.querySelector('#chart-attendance'), {
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Hadir',
                        data: data.series || []
                    }],
                    xaxis: {
                        categories: data.labels || []
                    },
                    colors: ['#696cff'],
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            opacityFrom: 0.5,
                            opacityTo: 0.1
                        }
                    },
                    dataLabels: {
                        enabled: false
                    }
                }).render();
            }).catch(function() {
                document.querySelector('#chart-attendance').innerHTML = '<p class="text-muted text-center">Gagal memuat data</p>';
            });

        // Interns by Division Chart
        csrfFetch('<?= base_url("dashboard/data/interns-by-division") ?>')
            .then(function(data) {
                new ApexCharts(document.querySelector('#chart-interns-division'), {
                    chart: {
                        type: 'donut',
                        height: 300
                    },
                    series: data.series || [],
                    labels: data.labels || [],
                    legend: {
                        position: 'bottom'
                    }
                }).render();
            }).catch(function() {
                document.querySelector('#chart-interns-division').innerHTML = '<p class="text-muted text-center">Gagal memuat data</p>';
            });

        // Attendance 3 Months Bar Chart
        csrfFetch('<?= base_url("dashboard/data/attendance-3months") ?>')
            .then(function(data) {
                new ApexCharts(document.querySelector('#chart-attendance-3months'), {
                    chart: {
                        type: 'bar',
                        height: 260,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Hadir',
                        data: data.present || []
                    }],
                    xaxis: {
                        categories: data.labels || []
                    },
                    colors: ['#71dd37'],
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
                document.querySelector('#chart-attendance-3months').innerHTML = '<p class="text-muted text-center">Gagal memuat data</p>';
            });

        // Pending Corrections
        csrfFetch('<?= base_url("dashboard/data/pending-corrections") ?>')
            .then(function(data) {
                document.getElementById('pending-corrections-count').textContent = data.pending || 0;
            }).catch(function() {});

        // Daily Attendance per Division
        csrfFetch('<?= base_url("dashboard/data/daily-attendance-division") ?>')
            .then(function(data) {
                var el = document.querySelector('#table-daily-attendance-division');
                if (!data.labels || data.labels.length === 0) {
                    el.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tidak ada data hari ini</td></tr>';
                    return;
                }
                el.innerHTML = '';
                data.labels.forEach(function(label, idx) {
                    el.innerHTML += '<tr><td>' + escapeHtml(label) + '</td><td class="text-end"><span class="badge bg-label-success">' + (data.series[idx] || 0) + '</span></td></tr>';
                });
            }).catch(function() {});
    });
</script>
<?= $this->endSection() ?>