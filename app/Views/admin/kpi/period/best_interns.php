<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/period') ?>">KPI Periode</a></li>
                <li class="breadcrumb-item active">Pemagang Terbaik</li>
            </ol>
        </nav>
        <h4 class="mb-1"><i class="ri-medal-line me-2"></i>Pemagang Terbaik</h4>
    </div>
</div>

<!-- Best Intern Highlight -->
<?php if ($bestIntern): ?>
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <div class="card border-warning">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <span class="display-1">🏆</span>
                    </div>
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <?php if (!empty($bestIntern['foto'])): ?>
                            <img src="<?= base_url('uploads/users/' . $bestIntern['foto']) ?>" alt="" class="rounded-circle" style="border: 4px solid #ffd700; width: 80px; height: 80px;">
                        <?php else: ?>
                            <span class="avatar-initial rounded-circle bg-warning text-white fs-1" style="width: 80px; height: 80px; line-height: 80px;">
                                <?= strtoupper(substr($bestIntern['nama_lengkap'], 0, 1)) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h3 class="mb-1">🎉 <?= esc($bestIntern['nama_lengkap']) ?></h3>
                    <p class="text-muted mb-2"><?= esc($bestIntern['nama_divisi'] ?? '') ?></p>
                    <h2 class="text-warning mb-1"><?= number_format($bestIntern['avg_total_score'], 2) ?></h2>
                    <p class="text-muted">Average KPI Score</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Top 3 Podium -->
<?php if (!empty($top3)): ?>
    <div class="row g-4 mb-4">
        <?php
        $podiumColors = ['warning', 'secondary', 'danger'];
        $medals = ['🥇', '🥈', '🥉'];
        ?>
        <?php foreach ($top3 as $i => $r): ?>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <span class="display-4"><?= $medals[$i] ?></span>
                        <h5 class="mt-2"><?= esc($r['nama_lengkap']) ?></h5>
                        <p class="text-muted"><?= esc($r['nama_divisi'] ?? '-') ?></p>
                        <h3 class="text-<?= $podiumColors[$i] ?>"><?= number_format($r['avg_total_score'], 2) ?></h3>
                        <small class="text-muted">Rank #<?= $r['final_rank'] ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Full Results Table -->
<?php if (!empty($periodResults)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ranking Lengkap</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Rank</th>
                                <th>Intern</th>
                                <th>Divisi</th>
                                <th class="text-center">Avg Score</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($periodResults as $r): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if ($r['final_rank'] <= 3): ?>
                                            <strong><?= $medals[$r['final_rank'] - 1] ?? $r['final_rank'] ?></strong>
                                        <?php else: ?>
                                            <?= $r['final_rank'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    <?= strtoupper(substr($r['nama_lengkap'], 0, 1)) ?>
                                                </span>
                                            </div>
                                            <strong><?= esc($r['nama_lengkap']) ?></strong>
                                        </div>
                                    </td>
                                    <td><?= esc($r['nama_divisi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php $c = $r['avg_total_score'] >= 75 ? 'success' : ($r['avg_total_score'] >= 60 ? 'warning' : 'danger'); ?>
                                        <span class="badge bg-<?= $c ?> fs-6"><?= number_format($r['avg_total_score'], 2) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($r['is_best_intern']): ?>
                                            <span class="badge bg-warning">🏆 Best Intern</span>
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
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ri-medal-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Belum Ada Data</h5>
            <p class="text-muted">Hitung periode KPI terlebih dahulu dari menu KPI Periode.</p>
        </div>
    </div>
<?php endif; ?>

<div class="row mt-3">
    <div class="col-12">
        <a href="<?= base_url('kpi/period') ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

<?= $this->endSection() ?>