<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1"><i class="ri-dashboard-3-line me-2"></i>Dashboard Finance</h4>
        <p class="mb-0 text-muted">Selamat datang, <?= esc($user['name'] ?? 'Finance') ?>!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-warning"><?= $stats['pending_payments'] ?? 0 ?></h3>
                        <small>Pembayaran Pending</small>
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
                        <h3 class="mb-1 text-success"><?= $stats['completed_payments'] ?? 0 ?></h3>
                        <small>Pembayaran Selesai</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-success"><i class="ri-checkbox-circle-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-info">Rp <?= number_format($stats['total_this_month'] ?? 0, 0, ',', '.') ?></h3>
                        <small>Total Bulan Ini</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-info"><i class="ri-money-dollar-circle-line ri-24px"></i></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1 text-primary"><?= esc($stats['next_payment_date'] ?? '-') ?></h6>
                        <small>Pembayaran Berikutnya</small>
                    </div>
                    <span class="avatar avatar-md"><span class="avatar-initial rounded bg-label-primary"><i class="ri-calendar-schedule-line ri-24px"></i></span></span>
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
                    <a href="<?= base_url('allowance') ?>" class="btn btn-outline-primary"><i class="ri-money-dollar-circle-line me-1"></i> Kelola Uang Saku</a>
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
                            <td><span class="badge bg-label-success"><?= esc($user['role'] ?? 'Finance') ?></span></td>
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

<!-- Finance Chart + Status -->
<div class="row mb-4">
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-donut-chart-line me-2"></i>Realisasi Pembayaran</h6>
            </div>
            <div class="card-body">
                <div id="chart-finance-summary" style="min-height:280px"></div>
                <div class="text-center mt-2">
                    <h5>Total: Rp <span id="finance-total"><?= number_format($stats['total_this_month'] ?? 0, 0, ',', '.') ?></span></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0"><i class="ri-file-list-3-line me-2"></i>Pembayaran Terbaru</h6>
                <span class="badge bg-label-warning" id="badge-pending-payments"><?= $stats['pending_payments'] ?? 0 ?> pending</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Intern</th>
                                <th class="text-end">Jumlah</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-finance-payments">
                            <tr>
                                <td colspan="4" class="text-center text-muted">Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribution per Division -->
<div class="row mb-4">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-building-line me-2"></i>Distribusi Pembayaran per Divisi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Divisi</th>
                                <th class="text-end">Total Pembayaran</th>
                                <th class="text-end">Jumlah (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="table-finance-by-division">
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
        // Finance Summary Donut Chart
        csrfFetch('<?= base_url("dashboard/data/finance-summary") ?>')
            .then(function(data) {
                var series = [data.pending || 0, data.completed || 0];
                new ApexCharts(document.querySelector('#chart-finance-summary'), {
                    chart: {
                        type: 'donut',
                        height: 280
                    },
                    series: series,
                    labels: ['Pending', 'Selesai'],
                    colors: ['#ffab00', '#71dd37'],
                    legend: {
                        position: 'bottom'
                    }
                }).render();
                document.getElementById('finance-total').textContent = new Intl.NumberFormat('id-ID').format(data.total || 0);
            }).catch(function() {
                document.querySelector('#chart-finance-summary').innerHTML = '<p class="text-muted text-center">Gagal memuat data</p>';
            });

        // Recent Payments Table
        csrfFetch('<?= base_url("dashboard/data/finance-payments") ?>')
            .then(function(data) {
                var tbody = document.querySelector('#table-finance-payments');
                var payments = data.payments || [];
                if (payments.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Tidak ada data pembayaran</td></tr>';
                    return;
                }
                tbody.innerHTML = '';
                payments.forEach(function(p) {
                    var amount = new Intl.NumberFormat('id-ID').format(p.amount || 0);
                    var status = escapeHtml(p.status || '');
                    var statusBadge = status === 'paid' ? 'bg-label-success' : 'bg-label-warning';
                    var internName = escapeHtml(String(p.id_intern || ''));
                    var actions = '';
                    if (status !== 'paid') {
                        actions = '<button class="btn btn-sm btn-primary btn-pay" data-id="' + p.id + '"><i class="ri-money-dollar-circle-line me-1"></i>Bayar</button> ';
                    } else {
                        actions = '<span class="badge bg-success">Terbayar</span> ';
                    }
                    actions += '<a class="btn btn-sm btn-outline-secondary" href="<?= base_url("allowance/slip") ?>/' + p.id + '" target="_blank"><i class="ri-file-text-line me-1"></i>Slip</a>';
                    tbody.innerHTML += '<tr><td>' + internName + '</td><td class="text-end">Rp ' + amount + '</td><td><span class="badge ' + statusBadge + '">' + status + '</span></td><td class="text-end">' + actions + '</td></tr>';
                });

                // Attach pay handlers
                document.querySelectorAll('.btn-pay').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var payBtn = this;
                        var id = payBtn.dataset.id;
                        payBtn.disabled = true;
                        csrfFetch('<?= base_url("allowance/process-payment") ?>/' + id, {
                                method: 'POST'
                            })
                            .then(function() {
                                payBtn.textContent = 'Terbayar';
                                payBtn.classList.remove('btn-primary');
                                payBtn.classList.add('btn-success');
                            }).catch(function() {
                                alert('Gagal memproses pembayaran');
                                payBtn.disabled = false;
                            });
                    });
                });
            }).catch(function() {});

        // Finance by Division Table
        csrfFetch('<?= base_url("dashboard/data/finance-by-division") ?>')
            .then(function(data) {
                var el = document.querySelector('#table-finance-by-division');
                var rows = data.rows || [];
                if (rows.length === 0) {
                    el.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>';
                    return;
                }
                el.innerHTML = '';
                rows.forEach(function(r) {
                    var amt = new Intl.NumberFormat('id-ID').format(r.total_amount || 0);
                    el.innerHTML += '<tr><td>' + escapeHtml(r.nama_divisi || 'Unknown') + '</td><td class="text-end">' + (r.total_payments || 0) + '</td><td class="text-end">Rp ' + amt + '</td></tr>';
                });
            }).catch(function() {});
    });
</script>
<?= $this->endSection() ?>