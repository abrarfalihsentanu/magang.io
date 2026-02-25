<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Dashboard Project</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-dashboard-line me-2"></i>Dashboard Project
                </h4>
                <p class="mb-0 text-muted">Overview dan analisis weekly project intern</p>
            </div>
            <div>
                <select class="form-select" id="yearFilter">
                    <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                        <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
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
                        <span class="text-heading">Total Project</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $stats['total'] ?></h3>
                        </div>
                        <small class="mb-0">Tahun <?= $selected_year ?></small>
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
                        <small class="mb-0">Menunggu assessment</small>
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
                        <span class="text-heading">Assessed</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-success"><?= $stats['assessed'] ?></h3>
                        </div>
                        <small class="mb-0">Sudah dinilai</small>
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
        <div class="card bg-label-primary">
            <div class="card-body text-center">
                <h2 class="mb-1"><?= $stats['avg_mentor_rating'] ?></h2>
                <p class="mb-0">Rata-rata Rating</p>
                <small class="text-muted">/ 5.0</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Project by Type -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Project per Tipe</h5>
            </div>
            <div class="card-body">
                <canvas id="typeChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Distribusi Rating</h5>
            </div>
            <div class="card-body">
                <canvas id="ratingChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Performers -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">Top 5 Performers</h5>
                <span class="badge bg-label-primary">Tahun: <?= $selected_year ?></span>
            </div>
            <div class="card-body">
                <?php if (empty($top_performers)): ?>
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Belum ada data untuk tahun ini</p>
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
                                    <th class="text-center">Total Project</th>
                                    <th class="text-center">Avg Rating</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php
                                $rank = 1;
                                foreach ($top_performers as $performer):
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
                                        <td><?= $performer['nik'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        <?= strtoupper(substr($performer['nama_lengkap'], 0, 2)) ?>
                                                    </span>
                                                </div>
                                                <strong><?= esc($performer['nama_lengkap']) ?></strong>
                                            </div>
                                        </td>
                                        <td><?= esc($performer['nama_divisi']) ?? '-' ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-label-primary">
                                                <?= $performer['total_projects'] ?> project
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success" style="font-size: 14px;">
                                                <i class="ri-star-fill"></i> <?= number_format($performer['avg_rating'], 2) ?>
                                            </span>
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
                    <a href="<?= base_url('project') ?>" class="btn btn-primary">
                        <i class="ri-list-check me-1"></i> Lihat Semua Project
                    </a>
                    <a href="<?= base_url('project/assessment') ?>" class="btn btn-info">
                        <i class="ri-checkbox-circle-line me-1"></i> Pending Assessment
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
    // Year Filter
    document.getElementById('yearFilter').addEventListener('change', function() {
        window.location.href = '<?= base_url('project/dashboard') ?>?year=' + this.value;
    });

    // Type Chart
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    const typeData = <?= json_encode($stats['by_type']) ?>;
    const typeLabels = typeData.map(item => item.tipe_project.toUpperCase());
    const typeCounts = typeData.map(item => item.total);

    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: typeLabels,
            datasets: [{
                data: typeCounts,
                backgroundColor: [
                    '#696cff', // primary - assigned
                    '#00cfe8' // info - inisiatif
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

    // Rating Distribution Chart
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    const ratingData = <?= json_encode($rating_distribution) ?>;
    const ratingLabels = ratingData.map(item => item.rating_category || 'Unknown');
    const ratingCounts = ratingData.map(item => item.total);

    new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: ratingLabels,
            datasets: [{
                label: 'Jumlah',
                data: ratingCounts,
                backgroundColor: [
                    '#28c76f', // Excellent
                    '#696cff', // Good
                    '#00cfe8', // Average
                    '#ff9f43', // Below Average
                    '#ea5455' // Poor
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
        const year = document.getElementById('yearFilter').value;
        window.location.href = `<?= base_url('project/export') ?>?tahun=${year}`;
    }
</script>

<?= $this->endSection() ?>