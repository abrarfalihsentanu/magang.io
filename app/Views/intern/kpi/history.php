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
?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/my') ?>">KPI Saya</a></li>
                <li class="breadcrumb-item active">Riwayat</li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="ri-history-line me-2"></i>Riwayat KPI</h4>
    </div>
</div>

<?php if (!empty($history)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead class="table-light">
                            <tr>
                                <th>Periode</th>
                                <th class="text-center">Total Score</th>
                                <th class="text-center">Ranking</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $h): ?>
                                <tr>
                                    <td>
                                        <strong><?= $namaBulan[(int)$h['bulan']] ?> <?= $h['tahun'] ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <?php $c = $h['total_score'] >= 75 ? 'success' : ($h['total_score'] >= 60 ? 'warning' : 'danger'); ?>
                                        <span class="badge bg-<?= $c ?> fs-6"><?= number_format($h['total_score'], 2) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($h['rank_bulan_ini'] <= 3): ?>
                                            <span class="badge bg-warning">#<?= $h['rank_bulan_ini'] ?></span>
                                        <?php else: ?>
                                            #<?= $h['rank_bulan_ini'] ?? '-' ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php $k = $kategoriLabels[$h['kategori_performa']] ?? ['-', 'secondary']; ?>
                                        <span class="badge bg-label-<?= $k[1] ?>"><?= $k[0] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?= $h['is_finalized'] ? '<span class="badge bg-success">Final</span>' : '<span class="badge bg-warning">Draft</span>' ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url("kpi/my/monthly/{$h['bulan']}/{$h['tahun']}") ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i> Detail
                                        </a>
                                    </td>
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
            <i class="ri-history-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Belum Ada Riwayat</h5>
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