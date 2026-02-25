<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Dashboard Aktivitas</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-dashboard-line me-2"></i>Dashboard Aktivitas
                </h4>
                <p class="mb-0 text-muted">Overview dan analisis aktivitas harian intern</p>
            </div>
            <div>
                <input type="month" class="form-control" id="monthFilter" value="<?= $selected_month ?>">
            </div>
        </div>
    </div>
</div>

<!-- Main Statistics -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total Aktivitas</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $stats['total'] ?></h3>
                        </div>
                        <small class="mb-0">Periode ini</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-file-list-3-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Pending</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-info"><?= $stats['pending'] ?></h3>
                        </div>
                        <small class="mb-0">Menunggu approval</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-time-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Approved</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-success"><?= $stats['approved'] ?></h3>
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

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Rejected</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-danger"><?= $stats['rejected'] ?></h3>
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
</div>

<!-- Secondary Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-label-primary">
            <div class="card-body text-center">
                <h2 class="mb-1"><?= $avg_per_week ?></h2>
                <p class="mb-0">Rata-rata Aktivitas/Intern/Minggu</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-label-success">
            <div class="card-body text-center">
                <h2 class="mb-1"><?= $approval_rate ?>%</h2>
                <p class="mb-0">Approval Rate</p>
                <small class="text-muted">
                    <?= $stats['approved'] ?> dari <?= $stats['approved'] + $stats['rejected'] ?> disetujui
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-label-warning">
            <div class="card-body text-center">
                <h2 class="mb-1"><?= $avg_response_time ?></h2>
                <p class="mb-0">Rata-rata Response Time (jam)</p>
                <small class="text-muted">Submit → Approval</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Activities by Category -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Aktivitas per Kategori</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Approval Trend -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Trend Approval</h5>
            </div>
            <div class="card-body">
                <canvas id="approvalChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Interns -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">Top 5 Intern Paling Aktif</h5>
                <span class="badge bg-label-primary">Periode: <?= date('F Y', strtotime($selected_month)) ?></span>
            </div>
            <div class="card-body">
                <?php if (empty($top_interns)): ?>
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Belum ada data untuk periode ini</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-paginated">
                            <thead>
                                <tr>
                                    <th width="50">Rank</th>
                                    <th>NIK</th>
                                    <th>Nama Intern</th>
                                    <th>Divisi</th>
                                    <th class="text-center">Total Aktivitas</th>
                                    <th class="text-center">Avg/Minggu</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php
                                $rank = 1;
                                foreach ($top_interns as $intern):
                                    $avgPerWeek = round($intern['total_activities'] / 4, 1);
                                ?>
                                    <tr>
                                        <td>
                                            <?php if ($rank === 1): ?>
                                                <span class="badge bg-warning">🥇 #<?= $rank ?></span>
                                            <?php elseif ($rank === 2): ?>
                                                <span class="badge bg-label-secondary">🥈 #<?= $rank ?></span>
                                            <?php elseif ($rank === 3): ?>
                                                <span class="badge bg-label-warning">🥉 #<?= $rank ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-label-secondary">#<?= $rank ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $intern['nik'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        <?= strtoupper(substr($intern['nama_lengkap'], 0, 2)) ?>
                                                    </span>
                                                </div>
                                                <strong><?= esc($intern['nama_lengkap']) ?></strong>
                                            </div>
                                        </td>
                                        <td><?= esc($intern['nama_divisi']) ?? '-' ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-label-primary">
                                                <?= $intern['total_activities'] ?> aktivitas
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted"><?= $avgPerWeek ?> aktivitas</small>
                                        </td>
                                    </tr>
                                <?php
                                    $rank++;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="<?= base_url('activity') ?>" class="btn btn-primary">
                        <i class="ri-list-check me-1"></i> Lihat Semua Aktivitas
                    </a>
                    <a href="<?= base_url('activity/approval') ?>" class="btn btn-info">
                        <i class="ri-checkbox-circle-line me-1"></i> Approval Page
                    </a>
                    <button type="button" class="btn btn-success" onclick="exportReport()">
                        <i class="ri-file-excel-line me-1"></i> Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Month Filter
    document.getElementById('monthFilter').addEventListener('change', function() {
        window.location.href = '<?= base_url('activity/dashboard') ?>?month=' + this.value;
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = <?= json_encode($stats['by_category']) ?>;
    const categoryLabels = categoryData.map(item => item.kategori.toUpperCase());
    const categoryCounts = categoryData.map(item => item.total);

    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryCounts,
                backgroundColor: [
                    '#00cfe8', // info - learning
                    '#696cff', // primary - task
                    '#ff9f43', // warning - meeting
                    '#28c76f', // success - training
                    '#82868b' // secondary - other
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Approval Trend Chart
    const approvalCtx = document.getElementById('approvalChart').getContext('2d');
    new Chart(approvalCtx, {
        type: 'bar',
        data: {
            labels: ['Approved', 'Rejected', 'Pending'],
            datasets: [{
                label: 'Jumlah',
                data: [<?= $stats['approved'] ?>, <?= $stats['rejected'] ?>, <?= $stats['pending'] ?>],
                backgroundColor: [
                    '#28c76f',
                    '#ea5455',
                    '#00cfe8'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    function exportReport() {
        const month = document.getElementById('monthFilter').value;
        window.location.href = `<?= base_url('activity/export') ?>?start_date=${month}-01&end_date=${month}-31`;
    }
</script>

<?= $this->endSection() ?>