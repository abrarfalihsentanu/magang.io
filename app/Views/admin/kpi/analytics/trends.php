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

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/analytics') ?>">Analitik KPI</a></li>
                <li class="breadcrumb-item active">Tren</li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="ri-line-chart-line me-2"></i>Tren KPI</h4>
    </div>
</div>

<?php if (!empty($trends)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tren Score KPI</h5>
                </div>
                <div class="card-body">
                    <div id="trendChart" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Data Detail</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-paginated">
                        <thead class="table-light">
                            <tr>
                                <th>Periode</th>
                                <th class="text-center">Total Intern</th>
                                <th class="text-center">Rata-rata</th>
                                <th class="text-center">Tertinggi</th>
                                <th class="text-center">Terendah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trends as $t): ?>
                                <tr>
                                    <td><?= $namaBulan[(int)$t['bulan']] ?> <?= $t['tahun'] ?></td>
                                    <td class="text-center"><?= $t['total'] ?></td>
                                    <td class="text-center"><span class="badge bg-primary"><?= $t['avg_score'] ?></span></td>
                                    <td class="text-center"><span class="text-success"><?= $t['max_score'] ?></span></td>
                                    <td class="text-center"><span class="text-danger"><?= $t['min_score'] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <h5>Belum Ada Data Tren</h5>
        </div>
    </div>
<?php endif; ?>

<div class="row mt-3">
    <div class="col-12">
        <a href="<?= base_url('kpi/analytics') ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (!empty($trends)): ?>
    <script>
        new ApexCharts(document.querySelector("#trendChart"), {
            series: [{
                    name: 'Rata-rata',
                    data: [<?= implode(',', array_column($trends, 'avg_score')) ?>]
                },
                {
                    name: 'Maximum',
                    data: [<?= implode(',', array_column($trends, 'max_score')) ?>]
                },
                {
                    name: 'Minimum',
                    data: [<?= implode(',', array_column($trends, 'min_score')) ?>]
                }
            ],
            chart: {
                type: 'area',
                height: 400,
                toolbar: {
                    show: true
                }
            },
            xaxis: {
                categories: [<?= implode(',', array_map(fn($t) => "'" . $namaBulan[(int)$t['bulan']] . " " . $t['tahun'] . "'", $trends)) ?>]
            },
            yaxis: {
                max: 100,
                min: 0
            },
            colors: ['#696cff', '#28a745', '#ff4c51'],
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.3,
                    opacityTo: 0.05
                }
            },
            markers: {
                size: 5
            },
            legend: {
                position: 'top'
            }
        }).render();
    </script>
<?php endif; ?>
<?= $this->endSection() ?>