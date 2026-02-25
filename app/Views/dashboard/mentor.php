<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1"><i class="ri-dashboard-3-line me-2"></i>Dashboard Mentor</h4>
        <p class="mb-0 text-muted">Selamat datang, <?= esc($user['name'] ?? 'Mentor') ?>!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1"><?= $stats['total_mentees'] ?? 0 ?></h3>
                        <small>Total Mentee</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-primary"><i class="ri-team-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-warning"><?= $stats['pending_activities'] ?? 0 ?></h3>
                        <small>Aktivitas Pending</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-warning"><i class="ri-file-list-3-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-info"><?= $stats['pending_projects'] ?? 0 ?></h3>
                        <small>Proyek Pending</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-info"><i class="ri-folder-chart-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-success"><?= $stats['avg_kpi'] ?? 0 ?></h3>
                        <small>Rata-rata KPI</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-success"><i class="ri-bar-chart-box-line ri-24px"></i></span></span>
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
                    <a href="<?= base_url('activity/approval') ?>" class="btn btn-outline-warning"><i class="ri-file-list-3-line me-1"></i> Approval Aktivitas Harian</a>
                    <a href="<?= base_url('project/approval') ?>" class="btn btn-outline-warning"><i class="ri-folder-chart-line me-1"></i> Approval Proyek Mingguan</a>
                    <a href="<?= base_url('attendance/correction/approval') ?>" class="btn btn-outline-warning"><i class="ri-checkbox-circle-line me-1"></i> Approval Koreksi Absensi</a>
                    <a href="<?= base_url('leave/approval') ?>" class="btn btn-outline-warning"><i class="ri-calendar-event-line me-1"></i> Approval Cuti/Izin</a>
                    <a href="<?= base_url('attendance/all') ?>" class="btn btn-outline-primary"><i class="ri-calendar-check-line me-1"></i> Data Absensi</a>
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
                            <td><span class="badge bg-label-warning"><?= esc($user['role'] ?? 'Mentor') ?></span></td>
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

<!-- Mentee Monitoring Table + Activity Feed -->
<div class="row mb-4">
    <div class="col-md-7 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-user-search-line me-2"></i>Monitoring Mentee</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th style="min-width:150px">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody id="table-mentees">
                            <tr>
                                <td colspan="3" class="text-center text-muted">Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-chat-history-line me-2"></i>Feed Aktivitas Mentee</h6>
            </div>
            <div class="card-body" style="max-height:400px; overflow-y:auto" id="mentor-activity-feed">
                <p class="text-muted text-center">Memuat...</p>
            </div>
        </div>
    </div>
</div>

<!-- Mentee Status Chart -->
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-pie-chart-line me-2"></i>Distribusi Status Mentee</h6>
            </div>
            <div class="card-body">
                <div id="chart-mentor-mentees" style="min-height:300px"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mentee Status Pie Chart
        csrfFetch('<?= base_url("dashboard/data/mentor-mentees") ?>')
            .then(function(data) {
                new ApexCharts(document.querySelector('#chart-mentor-mentees'), {
                    chart: {
                        type: 'pie',
                        height: 300
                    },
                    series: data.series || [],
                    labels: data.labels || [],
                    legend: {
                        position: 'bottom'
                    }
                }).render();
            }).catch(function() {
                document.querySelector('#chart-mentor-mentees').innerHTML = '<p class="text-muted text-center">Gagal memuat data</p>';
            });

        // Mentees Detail Table with Progress Bar
        csrfFetch('<?= base_url("dashboard/data/mentor-mentees-detail") ?>')
            .then(function(data) {
                var el = document.querySelector('#table-mentees');
                var mentees = data.mentees || [];
                if (mentees.length === 0) {
                    el.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada mentee</td></tr>';
                    return;
                }
                el.innerHTML = '';
                mentees.forEach(function(m) {
                    var name = escapeHtml(m.nama_lengkap || '');
                    var status = escapeHtml(m.status_magang || 'aktif');
                    var pct = m.attendance_pct || 0;
                    var barColor = pct >= 80 ? 'bg-success' : (pct >= 50 ? 'bg-warning' : 'bg-danger');
                    var badgeColor = status === 'aktif' ? 'bg-label-success' : 'bg-label-secondary';
                    el.innerHTML += '<tr>' +
                        '<td>' + name + '</td>' +
                        '<td><span class="badge ' + badgeColor + '">' + status + '</span></td>' +
                        '<td><div class="progress" style="height:18px"><div class="progress-bar ' + barColor + '" role="progressbar" style="width:' + pct + '%">' + pct + '%</div></div></td>' +
                        '</tr>';
                });
            }).catch(function() {});

        // Activity Feed
        csrfFetch('<?= base_url("dashboard/data/mentor-activity-feed") ?>')
            .then(function(data) {
                var el = document.querySelector('#mentor-activity-feed');
                var feed = data.feed || [];
                if (feed.length === 0) {
                    el.innerHTML = '<p class="text-muted text-center">Belum ada aktivitas</p>';
                    return;
                }
                el.innerHTML = '';
                feed.forEach(function(item) {
                    el.innerHTML += '<div class="d-flex align-items-start mb-3 pb-2 border-bottom">' +
                        '<div class="avatar avatar-sm me-2"><span class="avatar-initial rounded bg-label-primary">' + escapeHtml((item.nama_lengkap || 'U').charAt(0)) + '</span></div>' +
                        '<div><strong>' + escapeHtml(item.nama_lengkap || '') + '</strong> <small class="text-muted ms-1">' + escapeHtml(item.tanggal || '') + '</small>' +
                        '<div class="text-muted small">' + escapeHtml(item.judul || '') + '</div></div></div>';
                });
            }).catch(function() {});
    });
</script>
<?= $this->endSection() ?>