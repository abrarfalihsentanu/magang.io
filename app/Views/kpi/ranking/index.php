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
                <li class="breadcrumb-item active">Leaderboard KPI</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1"><i class="ri-trophy-line me-2"></i>Leaderboard KPI</h4>
                <p class="mb-0 text-muted">Ranking performa pemagang</p>
            </div>
        </div>
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
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="ri-search-line me-1"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Top 3 Podium -->
<?php if (count($ranking) >= 3): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center align-items-end text-center">
                        <!-- 2nd Place -->
                        <div class="col-md-3">
                            <div class="mb-2">
                                <div class="avatar avatar-lg mx-auto mb-2">
                                    <?php if (!empty($ranking[1]['foto'])): ?>
                                        <img src="<?= base_url('uploads/users/' . $ranking[1]['foto']) ?>" alt="" class="rounded-circle" style="border: 3px solid #c0c0c0;">
                                    <?php else: ?>
                                        <span class="avatar-initial rounded-circle bg-label-secondary fs-4" style="border: 3px solid #c0c0c0;">
                                            <?= strtoupper(substr($ranking[1]['nama_lengkap'], 0, 1)) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h6 class="mb-0">🥈 <?= esc($ranking[1]['nama_lengkap']) ?></h6>
                                <small class="text-muted"><?= esc($ranking[1]['nama_divisi'] ?? '') ?></small>
                                <div class="mt-1">
                                    <span class="badge bg-secondary fs-6"><?= number_format($ranking[1]['total_score'], 2) ?></span>
                                </div>
                            </div>
                            <div class="bg-secondary bg-opacity-25 rounded-top pt-4 pb-2" style="height: 80px;"></div>
                        </div>
                        <!-- 1st Place -->
                        <div class="col-md-3">
                            <div class="mb-2">
                                <div class="avatar avatar-xl mx-auto mb-2">
                                    <?php if (!empty($ranking[0]['foto'])): ?>
                                        <img src="<?= base_url('uploads/users/' . $ranking[0]['foto']) ?>" alt="" class="rounded-circle" style="border: 3px solid #ffd700;">
                                    <?php else: ?>
                                        <span class="avatar-initial rounded-circle bg-label-warning fs-3" style="border: 3px solid #ffd700;">
                                            <?= strtoupper(substr($ranking[0]['nama_lengkap'], 0, 1)) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h5 class="mb-0">🥇 <?= esc($ranking[0]['nama_lengkap']) ?></h5>
                                <small class="text-muted"><?= esc($ranking[0]['nama_divisi'] ?? '') ?></small>
                                <div class="mt-1">
                                    <span class="badge bg-warning fs-5"><?= number_format($ranking[0]['total_score'], 2) ?></span>
                                </div>
                            </div>
                            <div class="bg-warning bg-opacity-25 rounded-top pt-4 pb-2" style="height: 120px;"></div>
                        </div>
                        <!-- 3rd Place -->
                        <div class="col-md-3">
                            <div class="mb-2">
                                <div class="avatar avatar-lg mx-auto mb-2">
                                    <?php if (!empty($ranking[2]['foto'])): ?>
                                        <img src="<?= base_url('uploads/users/' . $ranking[2]['foto']) ?>" alt="" class="rounded-circle" style="border: 3px solid #cd7f32;">
                                    <?php else: ?>
                                        <span class="avatar-initial rounded-circle bg-label-danger fs-4" style="border: 3px solid #cd7f32;">
                                            <?= strtoupper(substr($ranking[2]['nama_lengkap'], 0, 1)) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h6 class="mb-0">🥉 <?= esc($ranking[2]['nama_lengkap']) ?></h6>
                                <small class="text-muted"><?= esc($ranking[2]['nama_divisi'] ?? '') ?></small>
                                <div class="mt-1">
                                    <span class="badge bg-danger fs-6"><?= number_format($ranking[2]['total_score'], 2) ?></span>
                                </div>
                            </div>
                            <div class="bg-danger bg-opacity-25 rounded-top pt-4 pb-2" style="height: 50px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Full Ranking Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ranking Lengkap - <?= $namaBulan[$bulan] ?> <?= $tahun ?></h5>
            </div>
            <?php if (!empty($ranking)): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">Rank</th>
                                <th>Pemagang</th>
                                <th>Divisi</th>
                                <th class="text-center">Skor</th>
                                <th class="text-center">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ranking as $r): ?>
                                <tr class="<?= ($r['id_user'] == session()->get('id_user')) ? 'table-primary' : '' ?>">
                                    <td class="text-center">
                                        <?php if ($r['rank_bulan_ini'] == 1): ?>🥇
                                        <?php elseif ($r['rank_bulan_ini'] == 2): ?>🥈
                                        <?php elseif ($r['rank_bulan_ini'] == 3): ?>🥉
                                    <?php else: ?><span class="text-muted"><?= $r['rank_bulan_ini'] ?></span>
                                    <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <?php if (!empty($r['foto'])): ?>
                                                    <img src="<?= base_url('uploads/users/' . $r['foto']) ?>" alt="" class="rounded-circle">
                                                <?php else: ?>
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        <?= strtoupper(substr($r['nama_lengkap'], 0, 1)) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <strong><?= esc($r['nama_lengkap']) ?></strong>
                                                <?php if ($r['id_user'] == session()->get('id_user')): ?>
                                                    <span class="badge bg-primary ms-1">Anda</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($r['nama_divisi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php
                                        $score = (float)$r['total_score'];
                                        $color = $score >= 90 ? 'success' : ($score >= 75 ? 'primary' : ($score >= 60 ? 'warning' : 'danger'));
                                        ?>
                                        <span class="badge bg-<?= $color ?>"><?= number_format($score, 2) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php $k = $kategoriLabels[$r['kategori_performa']] ?? ['-', 'secondary']; ?>
                                        <span class="badge bg-label-<?= $k[1] ?>"><?= $k[0] ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="card-body text-center py-5">
                    <i class="ri-trophy-line ri-4x text-muted mb-3 d-block"></i>
                    <h5>Belum Ada Ranking</h5>
                    <p class="text-muted">Data ranking untuk periode ini belum tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>