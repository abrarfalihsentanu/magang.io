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
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/assessment') ?>">Penilaian KPI</a></li>
                <li class="breadcrumb-item active">Riwayat - <?= esc($intern['nama_lengkap']) ?></li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="ri-history-line me-2"></i>Riwayat Penilaian - <?= esc($intern['nama_lengkap']) ?></h4>
    </div>
</div>

<?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $monthKey => $assessments): ?>
        <?php
        $parts = explode('-', $monthKey);
        $bln = (int)$parts[1];
        $thn = $parts[0];
        $totalWeighted = array_sum(array_column($assessments, 'nilai_weighted'));
        ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= $namaBulan[$bln] ?> <?= $thn ?></h5>
                        <span class="badge bg-primary fs-6">Total: <?= number_format($totalWeighted, 2) ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Indikator</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Bobot</th>
                                    <th class="text-center">Nilai Raw</th>
                                    <th class="text-center">Nilai Weighted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assessments as $a): ?>
                                    <tr>
                                        <td><?= esc($a['nama_indicator']) ?></td>
                                        <td class="text-center"><span class="badge bg-label-info"><?= ucfirst($a['kategori']) ?></span></td>
                                        <td class="text-center"><?= $a['bobot'] ?>%</td>
                                        <td class="text-center"><?= number_format($a['nilai_raw'], 2) ?></td>
                                        <td class="text-center"><strong><?= number_format($a['nilai_weighted'], 2) ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-history-line ri-4x text-muted mb-3 d-block"></i>
                    <h5>Belum Ada Riwayat</h5>
                    <p class="text-muted">Belum ada penilaian untuk pemagang ini.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <a href="<?= base_url('kpi/assessment') ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

<?= $this->endSection() ?>