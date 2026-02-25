<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$namaBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];
?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Analitik KPI</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1"><i class="ri-dashboard-3-line me-2"></i>Analitik KPI</h4>
                <p class="mb-0 text-muted">Overview dan trend performa KPI</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('kpi/analytics/distribution') ?>" class="btn btn-outline-info">
                    <i class="ri-pie-chart-line me-1"></i> Distribusi
                </a>
                <a href="<?= base_url('kpi/analytics/trends') ?>" class="btn btn-outline-primary">
                    <i class="ri-line-chart-line me-1"></i> Tren
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Trend Chart -->
<?php if (!empty($trendData)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tren Rata-rata Skor KPI</h5>
                </div>
                <div class="card-body">
                    <div id="trendChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards per Month -->
    <div class="row g-4">
        <?php foreach (array_reverse($trendData) as $m): ?>
            <div class="col-md-4 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted"><?= $namaBulan[(int)$m['bulan']] ?> <?= $m['tahun'] ?></h6>
                        <div class="d-flex justify-content-between mt-2">
                            <div>
                                <small class="text-muted">Rata-rata</small>
                                <h4 class="mb-0"><?= $m['avg_score'] ?></h4>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Total</small>
                                <h4 class="mb-0"><?= $m['total_users'] ?></h4>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-success">Max: <?= $m['max_score'] ?></small>
                            <small class="text-danger">Min: <?= $m['min_score'] ?></small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ri-dashboard-3-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Belum Ada Data Analitik</h5>
            <p class="text-muted">Data akan tersedia setelah ada perhitungan KPI bulanan.</p>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (!empty($trendData)): ?>
    <script>
        var trendOpts = {
            series: [{
                    name: 'Rata-rata',
                    data: [<?= implode(',', array_map(fn($d) => $d['avg_score'], $trendData)) ?>]
                },
                {
                    name: 'Maximum',
                    data: [<?= implode(',', array_map(fn($d) => $d['max_score'], $trendData)) ?>]
                },
                {
                    name: 'Minimum',
                    data: [<?= implode(',', array_map(fn($d) => $d['min_score'], $trendData)) ?>]
                }
            ],
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: true
                }
            },
            xaxis: {
                categories: [<?= implode(',', array_map(fn($d) => "'" . $namaBulan[(int)$d['bulan']] . " " . $d['tahun'] . "'", $trendData)) ?>]
            },
            yaxis: {
                max: 100,
                min: 0,
                title: {
                    text: 'Score'
                }
            },
            colors: ['#696cff', '#28a745', '#ff4c51'],
            stroke: {
                width: [3, 2, 2],
                curve: 'smooth',
                dashArray: [0, 5, 5]
            },
            markers: {
                size: 5
            },
            legend: {
                position: 'top'
            }
        };
        new ApexCharts(document.querySelector("#trendChart"), trendOpts).render();
    </script>
<?php endif; ?>
<?= $this->endSection() ?>