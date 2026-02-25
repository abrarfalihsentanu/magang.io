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

$kategoriLabels = [
    'excellent'     => ['Sangat Baik', 'success'],
    'good'          => ['Baik', 'primary'],
    'average'       => ['Cukup', 'warning'],
    'below_average' => ['Kurang', 'danger'],
    'poor'          => ['Sangat Kurang', 'dark'],
];

$kategoriColors = [
    'kehadiran' => 'primary',
    'aktivitas' => 'info',
    'project'   => 'success',
];
?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">KPI Saya</li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="ri-line-chart-line me-2"></i>KPI Saya</h4>
        <p class="mb-0 text-muted">Dashboard performa KPI Anda - <?= $namaBulan[$bulan] ?> <?= $tahun ?></p>
    </div>
</div>

<!-- Score Overview Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="ri-line-chart-line ri-3x mb-2"></i>
                <h2 class="mb-1"><?= $currentResult ? number_format($currentResult['total_score'], 2) : '-' ?></h2>
                <p class="mb-0">Skor Bulan Ini</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="ri-trophy-line ri-3x mb-2 text-warning"></i>
                <h2 class="mb-1"><?= $currentResult ? '#' . ($currentResult['rank_bulan_ini'] ?? '-') : '-' ?></h2>
                <p class="mb-0 text-muted">Ranking dari <?= $totalRanked ?> intern</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <?php
                $kLabel = $currentResult ? ($kategoriLabels[$currentResult['kategori_performa']] ?? ['-', 'secondary']) : ['-', 'secondary'];
                ?>
                <i class="ri-medal-line ri-3x mb-2 text-<?= $kLabel[1] ?>"></i>
                <h2 class="mb-1"><span class="badge bg-<?= $kLabel[1] ?>"><?= $kLabel[0] ?></span></h2>
                <p class="mb-0 text-muted">Kategori Performa</p>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Score Chart -->
<?php if (!empty($history)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tren Skor Bulanan</h5>
                    <a href="<?= base_url('kpi/my/history') ?>" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div id="trenChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Current Month Breakdown -->
<?php if (!empty($grouped)): ?>
    <div class="row mb-4">
        <div class="col-md-8">
            <!-- Detail per Category -->
            <?php foreach ($grouped as $kategori => $items): ?>
                <div class="card mb-3">
                    <div class="card-header py-2">
                        <h6 class="mb-0">
                            <span class="badge bg-<?= $kategoriColors[$kategori] ?? 'secondary' ?>"><?= ucfirst($kategori) ?></span>
                            <span class="text-muted ms-2">Subtotal: <?= number_format(array_sum(array_column($items, 'nilai_weighted')), 2) ?></span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($items as $a): ?>
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <div>
                                    <span class="fw-medium"><?= esc($a['nama_indicator']) ?></span>
                                    <small class="text-muted d-block">Bobot: <?= $a['bobot'] ?>%</small>
                                </div>
                                <div class="text-end">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 100px;">
                                            <div class="progress" style="height: 6px;">
                                                <?php $barColor = $a['nilai_raw'] >= 80 ? 'success' : ($a['nilai_raw'] >= 60 ? 'warning' : 'danger'); ?>
                                                <div class="progress-bar bg-<?= $barColor ?>" style="width: <?= min(100, $a['nilai_raw']) ?>%;"></div>
                                            </div>
                                        </div>
                                        <span class="badge bg-label-<?= $barColor ?>" style="min-width: 50px;"><?= number_format($a['nilai_raw'], 1) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md-4">
            <!-- Radar Chart -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Radar KPI</h6>
                </div>
                <div class="card-body">
                    <div id="radarChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Period Result -->
            <?php if ($periodResult): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Hasil Periode</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-2">
                            <h3 class="text-primary"><?= number_format($periodResult['avg_total_score'], 2) ?></h3>
                            <small class="text-muted">Rata-rata Score</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Final Rank</span>
                            <strong>#<?= $periodResult['final_rank'] ?></strong>
                        </div>
                        <?php if ($periodResult['is_best_intern']): ?>
                            <div class="text-center mt-2">
                                <span class="badge bg-warning fs-6">🏆 Best Intern!</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-bar-chart-box-line ri-4x text-muted mb-3 d-block"></i>
                    <h5>Belum Ada Data KPI</h5>
                    <p class="text-muted">KPI Anda belum dihitung untuk bulan ini. Data akan tersedia setelah admin melakukan perhitungan.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Quick Links -->
<div class="row">
    <div class="col-12">
        <div class="d-flex gap-2">
            <a href="<?= base_url('kpi/my/breakdown') ?>" class="btn btn-outline-info">
                <i class="ri-pie-chart-line me-1"></i> Breakdown per Indikator
            </a>
            <a href="<?= base_url('kpi/ranking') ?>" class="btn btn-outline-warning">
                <i class="ri-trophy-line me-1"></i> Lihat Leaderboard
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    <?php if (!empty($history)): ?>
        // Trend Chart
        var trendOpts = {
            series: [{
                name: 'Total Score',
                data: [<?= implode(',', array_map(fn($h) => $h['total_score'], $history)) ?>]
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            xaxis: {
                categories: [<?= implode(',', array_map(fn($h) => "'" . $namaBulan[(int)$h['bulan']] . " " . $h['tahun'] . "'", $history)) ?>]
            },
            yaxis: {
                max: 100,
                min: 0
            },
            colors: ['#696cff'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: true
            },
            markers: {
                size: 5
            }
        };
        new ApexCharts(document.querySelector("#trenChart"), trendOpts).render();
    <?php endif; ?>

    <?php if (!empty($assessments)): ?>
        // Radar Chart
        var radarOpts = {
            series: [{
                name: 'Nilai',
                data: [<?= implode(',', array_map(fn($a) => $a['nilai_raw'], $assessments)) ?>]
            }],
            chart: {
                type: 'radar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            xaxis: {
                categories: [<?= implode(',', array_map(fn($a) => "'" . mb_substr($a['nama_indicator'], 0, 15) . "'", $assessments)) ?>]
            },
            yaxis: {
                max: 100,
                min: 0,
                show: false
            },
            colors: ['#696cff'],
            fill: {
                opacity: 0.2
            },
            markers: {
                size: 3
            }
        };
        new ApexCharts(document.querySelector("#radarChart"), radarOpts).render();
    <?php endif; ?>
</script>
<?= $this->endSection() ?>