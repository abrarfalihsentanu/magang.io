<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/my') ?>">KPI Saya</a></li>
                <li class="breadcrumb-item active">Breakdown per Indikator</li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="ri-pie-chart-line me-2"></i>Breakdown KPI per Indikator</h4>
        <p class="mb-0 text-muted">Rata-rata skor Anda per indikator selama periode magang</p>
    </div>
</div>

<?php
$kategoriColors = [
    'kehadiran' => 'primary',
    'aktivitas' => 'info',
    'project'   => 'success',
];
?>

<?php if (!empty($breakdownData)): ?>
    <div class="row g-4">
        <?php foreach ($breakdownData as $item): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-1"><?= esc($item['indicator']['nama_indicator']) ?></h6>
                                <span class="badge bg-label-<?= $kategoriColors[$item['indicator']['kategori']] ?? 'secondary' ?>">
                                    <?= ucfirst($item['indicator']['kategori']) ?>
                                </span>
                                <small class="text-muted ms-1">Bobot: <?= $item['indicator']['bobot'] ?>%</small>
                            </div>
                            <?php if ($item['indicator']['is_auto_calculate']): ?>
                                <span class="badge bg-label-info">Auto</span>
                            <?php else: ?>
                                <span class="badge bg-label-warning">Manual</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-center mb-3">
                            <?php $c = $item['avg_raw'] >= 80 ? 'success' : ($item['avg_raw'] >= 60 ? 'warning' : 'danger'); ?>
                            <h2 class="text-<?= $c ?> mb-0"><?= $item['avg_raw'] ?></h2>
                            <small class="text-muted">Rata-rata Nilai Raw</small>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-<?= $c ?>" style="width: <?= min(100, $item['avg_raw']) ?>%;"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Avg Weighted: <?= $item['avg_weighted'] ?></span>
                            <span><?= $item['total_months'] ?> bulan</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ri-pie-chart-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Belum Ada Data</h5>
            <p class="text-muted">Data breakdown belum tersedia.</p>
        </div>
    </div>
<?php endif; ?>

<div class="row mt-4">
    <div class="col-12">
        <a href="<?= base_url('kpi/my') ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

<?= $this->endSection() ?>