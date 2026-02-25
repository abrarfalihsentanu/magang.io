<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1"><i class="ri-dashboard-3-line me-2"></i>Dashboard Admin</h4>
        <p class="mb-0 text-muted">Selamat datang, <?= esc($user['name'] ?? 'Admin') ?>!</p>
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
                            <td><span class="badge bg-label-danger"><?= esc($user['role'] ?? 'Admin') ?></span></td>
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

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-line-chart-line me-2"></i>Absensi - 7 Hari Terakhir</h6>
            </div>
            <div class="card-body">
                <div id="chart-attendance" style="min-height:320px"></div>
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

<!-- Tables Row -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-group-line me-2"></i>Distribusi Role</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="table-role-distribution">
                            <tr>
                                <td colspan="2" class="text-center text-muted">Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-login-box-line me-2"></i>Login Activity (7 hari)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th class="text-end">Logins</th>
                            </tr>
                        </thead>
                        <tbody id="table-login-activity">
                            <tr>
                                <td colspan="2" class="text-center text-muted">Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-line-chart-line me-2"></i>Pertumbuhan Pemagang (6 bln)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th class="text-end">Baru</th>
                            </tr>
                        </thead>
                        <tbody id="table-intern-growth">
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
                        height: 320,
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

        // Role Distribution Table
        csrfFetch('<?= base_url("dashboard/data/role-distribution") ?>')
            .then(function(data) {
                var el = document.querySelector('#table-role-distribution');
                if (!data.labels || data.labels.length === 0) {
                    el.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tidak ada data</td></tr>';
                    return;
                }
                el.innerHTML = '';
                data.labels.forEach(function(label, idx) {
                    el.innerHTML += '<tr><td>' + escapeHtml(label) + '</td><td class="text-end"><span class="badge bg-label-primary">' + (data.series[idx] || 0) + '</span></td></tr>';
                });
            }).catch(function() {});

        // Login Activity Table
        csrfFetch('<?= base_url("dashboard/data/login-activity") ?>')
            .then(function(data) {
                var el = document.querySelector('#table-login-activity');
                if (!data.labels || data.labels.length === 0) {
                    el.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tidak ada data</td></tr>';
                    return;
                }
                el.innerHTML = '';
                data.labels.forEach(function(label, idx) {
                    el.innerHTML += '<tr><td>' + escapeHtml(label) + '</td><td class="text-end">' + (data.series[idx] || 0) + '</td></tr>';
                });
            }).catch(function() {});

        // Intern Growth Table
        csrfFetch('<?= base_url("dashboard/data/intern-growth") ?>')
            .then(function(data) {
                var el = document.querySelector('#table-intern-growth');
                if (!data.labels || data.labels.length === 0) {
                    el.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tidak ada data</td></tr>';
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