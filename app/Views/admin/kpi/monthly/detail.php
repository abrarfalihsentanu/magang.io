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
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/monthly?bulan=' . $bulan . '&tahun=' . $tahun) ?>">KPI Bulanan</a></li>
                <li class="breadcrumb-item active">Detail <?= esc($user['nama_lengkap']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- User Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-lg">
                        <?php if (!empty($user['foto'])): ?>
                            <img src="<?= base_url('uploads/users/' . $user['foto']) ?>" alt="" class="rounded-circle">
                        <?php else: ?>
                            <span class="avatar-initial rounded-circle bg-label-primary fs-4">
                                <?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="mb-1"><?= esc($user['nama_lengkap']) ?></h4>
                        <div class="d-flex gap-3 text-muted">
                            <span><i class="ri-id-card-line me-1"></i><?= esc($user['nik']) ?></span>
                            <span><i class="ri-building-line me-1"></i><?= esc($user['nama_divisi'] ?? '-') ?></span>
                            <span><i class="ri-calendar-line me-1"></i><?= $namaBulan[$bulan] ?> <?= $tahun ?></span>
                        </div>
                    </div>
                    <div class="ms-auto text-end">
                        <?php if ($monthlyResult): ?>
                            <h2 class="mb-0 text-<?= ($monthlyResult['total_score'] >= 75) ? 'success' : (($monthlyResult['total_score'] >= 60) ? 'warning' : 'danger') ?>">
                                <?= number_format($monthlyResult['total_score'], 2) ?>
                            </h2>
                            <span>Rank #<?= $monthlyResult['rank_bulan_ini'] ?? '-' ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Score by Category -->
<?php foreach ($grouped as $kategori => $items): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <span class="badge bg-<?= $kategoriColors[$kategori] ?? 'secondary' ?> me-2"><?= ucfirst($kategori) ?></span>
                        <?php $subtotal = array_sum(array_column($items, 'nilai_weighted')); ?>
                        <small class="text-muted">Subtotal: <?= number_format($subtotal, 2) ?></small>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Indikator</th>
                                <th class="text-center" style="width: 100px">Bobot</th>
                                <th class="text-center" style="width: 120px">Nilai Raw</th>
                                <th class="text-center" style="width: 120px">Nilai Weighted</th>
                                <th style="width: 200px">Progress</th>
                                <th class="text-center" style="width: 80px">Tipe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $a): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($a['nama_indicator']) ?></strong>
                                    </td>
                                    <td class="text-center"><?= $a['bobot'] ?>%</td>
                                    <td class="text-center">
                                        <?php $rawColor = $a['nilai_raw'] >= 80 ? 'success' : ($a['nilai_raw'] >= 60 ? 'warning' : 'danger'); ?>
                                        <span class="badge bg-label-<?= $rawColor ?>"><?= number_format($a['nilai_raw'], 2) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <strong><?= number_format($a['nilai_weighted'], 2) ?></strong>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 8px">
                                            <div class="progress-bar bg-<?= $rawColor ?>" style="width: <?= min(100, $a['nilai_raw']) ?>%"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($a['is_auto_calculate']): ?>
                                            <span class="badge bg-label-info">Auto</span>
                                        <?php else: ?>
                                            <span class="badge bg-label-warning">Manual</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Radar Chart -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Radar KPI</h5>
            </div>
            <div class="card-body">
                <div id="radarChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Summary</h5>
            </div>
            <div class="card-body">
                <?php if ($monthlyResult): ?>
                    <div class="mb-3">
                        <label class="text-muted">Total Score</label>
                        <h3><?= number_format($monthlyResult['total_score'], 2) ?></h3>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Ranking</label>
                        <h3>#<?= $monthlyResult['rank_bulan_ini'] ?? '-' ?></h3>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Kategori</label>
                        <?php
                        $kLabel = match ($monthlyResult['kategori_performa']) {
                            'excellent'     => ['Sangat Baik', 'success'],
                            'good'          => ['Baik', 'primary'],
                            'average'       => ['Cukup', 'warning'],
                            'below_average' => ['Kurang', 'danger'],
                            'poor'          => ['Sangat Kurang', 'dark'],
                            default         => ['-', 'secondary'],
                        };
                        ?>
                        <h4><span class="badge bg-<?= $kLabel[1] ?>"><?= $kLabel[0] ?></span></h4>
                    </div>
                    <div>
                        <label class="text-muted">Status</label>
                        <h4>
                            <?php if ($monthlyResult['is_finalized']): ?>
                                <span class="badge bg-success">Finalized</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Draft</span>
                            <?php endif; ?>
                        </h4>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Belum ada data</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <a href="<?= base_url('kpi/monthly?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali ke Ranking
        </a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    <?php if (!empty($assessments)): ?>
        var radarOpts = {
            series: [{
                name: 'Nilai Raw',
                data: [<?= implode(',', array_map(fn($a) => $a['nilai_raw'], $assessments)) ?>]
            }],
            chart: {
                type: 'radar',
                height: 350
            },
            xaxis: {
                categories: [<?= implode(',', array_map(fn($a) => "'" . esc($a['nama_indicator']) . "'", $assessments)) ?>]
            },
            yaxis: {
                max: 100,
                min: 0
            },
            colors: ['#696cff'],
            markers: {
                size: 4
            },
            fill: {
                opacity: 0.2
            }
        };
        new ApexCharts(document.querySelector("#radarChart"), radarOpts).render();
    <?php endif; ?>
</script>
<?= $this->endSection() ?>