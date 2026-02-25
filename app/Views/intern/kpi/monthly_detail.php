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

$kategoriLabels = [
    'excellent'     => ['Sangat Baik', 'success'],
    'good'          => ['Baik', 'primary'],
    'average'       => ['Cukup', 'warning'],
    'below_average' => ['Kurang', 'danger'],
    'poor'          => ['Sangat Kurang', 'dark'],
];
?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/my') ?>">KPI Saya</a></li>
                <li class="breadcrumb-item active">Detail <?= $namaBulan[$bulan] ?> <?= $tahun ?></li>
            </ol>
        </nav>
        <h4 class="mb-1">Detail KPI - <?= $namaBulan[$bulan] ?> <?= $tahun ?></h4>
    </div>
</div>

<!-- Score Summary -->
<?php if ($result): ?>
    <div class="row g-4 mb-4">
        <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="text-primary"><?= number_format($result['total_score'], 2) ?></h2>
                    <p class="text-muted mb-0">Total Score</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <h2>#<?= $result['rank_bulan_ini'] ?? '-' ?></h2>
                    <p class="text-muted mb-0">Ranking</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <?php $k = $kategoriLabels[$result['kategori_performa']] ?? ['-', 'secondary']; ?>
                    <h2><span class="badge bg-<?= $k[1] ?>"><?= $k[0] ?></span></h2>
                    <p class="text-muted mb-0">Kategori</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Assessment Details -->
<?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $kategori => $items): ?>
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <span class="badge bg-<?= $kategoriColors[$kategori] ?? 'secondary' ?> me-2"><?= ucfirst($kategori) ?></span>
                    Subtotal: <?= number_format(array_sum(array_column($items, 'nilai_weighted')), 2) ?>
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Indikator</th>
                            <th class="text-center">Bobot</th>
                            <th class="text-center">Nilai Raw</th>
                            <th class="text-center">Nilai Weighted</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $a): ?>
                            <tr>
                                <td>
                                    <?= esc($a['nama_indicator']) ?>
                                    <?php if (!$a['is_auto_calculate']): ?>
                                        <small class="badge bg-label-warning">Manual</small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $a['bobot'] ?>%</td>
                                <td class="text-center">
                                    <?php $c = $a['nilai_raw'] >= 80 ? 'success' : ($a['nilai_raw'] >= 60 ? 'warning' : 'danger'); ?>
                                    <span class="badge bg-label-<?= $c ?>"><?= number_format($a['nilai_raw'], 2) ?></span>
                                </td>
                                <td class="text-center"><strong><?= number_format($a['nilai_weighted'], 2) ?></strong></td>
                                <td>
                                    <div class="progress" style="height: 8px; width: 120px;">
                                        <div class="progress-bar bg-<?= $c ?>" style="width: <?= min(100, $a['nilai_raw']) ?>%;"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ri-bar-chart-box-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Belum Ada Data</h5>
            <p class="text-muted">Data KPI untuk bulan ini belum tersedia.</p>
        </div>
    </div>
<?php endif; ?>

<div class="row mt-3">
    <div class="col-12">
        <a href="<?= base_url('kpi/my') ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

<?= $this->endSection() ?>