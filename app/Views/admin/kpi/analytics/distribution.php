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
                <li class="breadcrumb-item active">Distribusi</li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="ri-pie-chart-line me-2"></i>Distribusi Performa - <?= $namaBulan[$bulan] ?> <?= $tahun ?></h4>
    </div>
</div>

<!-- Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $bulan ? 'selected' : '' ?>><?= $namaBulan[$m] ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select">
                            <?php for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++): ?>
                                <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary"><i class="ri-search-line me-1"></i> Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribusi Kategori</h5>
            </div>
            <div class="card-body">
                <div id="pieChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribusi Skor</h5>
            </div>
            <div class="card-body">
                <div id="barChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <a href="<?= base_url('kpi/analytics') ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Pie Chart
    new ApexCharts(document.querySelector("#pieChart"), {
        series: [<?= $distribution['excellent'] ?>, <?= $distribution['good'] ?>, <?= $distribution['average'] ?>, <?= $distribution['below_average'] ?>, <?= $distribution['poor'] ?>],
        chart: {
            type: 'pie',
            height: 300
        },
        labels: ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'],
        colors: ['#28a745', '#696cff', '#ffab00', '#ff4c51', '#333'],
        legend: {
            position: 'bottom'
        }
    }).render();

    // Bar Chart
    new ApexCharts(document.querySelector("#barChart"), {
        series: [{
            name: 'Jumlah',
            data: [<?= implode(',', array_values($scoreRanges)) ?>]
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        xaxis: {
            categories: [<?= implode(',', array_map(fn($k) => "'$k'", array_keys($scoreRanges))) ?>]
        },
        colors: ['#696cff'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '50%'
            }
        }
    }).render();
</script>
<?= $this->endSection() ?>