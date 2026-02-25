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
                <li class="breadcrumb-item active">Penilaian KPI</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-edit-2-line me-2"></i>Penilaian KPI Manual
                </h4>
                <p class="mb-0 text-muted">Berikan penilaian indikator manual untuk setiap pemagang</p>
            </div>
        </div>
    </div>
</div>

<!-- Month Selector -->
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

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total Pemagang</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0"><?= $totalMentees ?></h3>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-user-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Sudah Dinilai</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 text-success"><?= $assessedCount ?></h3>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-check-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Belum Dinilai</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 text-warning"><?= $totalMentees - $assessedCount ?></h3>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mentees List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Pemagang - <?= $namaBulan[$bulan] ?> <?= $tahun ?></h5>
            </div>
            <?php if (!empty($mentees)): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Pemagang</th>
                                <th>NIK</th>
                                <th>Divisi</th>
                                <th class="text-center">Status Penilaian</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($mentees as $mentee): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <?php if (!empty($mentee['foto'])): ?>
                                                    <img src="<?= base_url('uploads/users/' . $mentee['foto']) ?>" alt="" class="rounded-circle">
                                                <?php else: ?>
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        <?= strtoupper(substr($mentee['nama_lengkap'], 0, 1)) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <strong><?= esc($mentee['nama_lengkap']) ?></strong>
                                        </div>
                                    </td>
                                    <td><code><?= esc($mentee['nik']) ?></code></td>
                                    <td><?= esc($mentee['nama_divisi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php if ($mentee['is_assessed']): ?>
                                            <span class="badge bg-success">
                                                <i class="ri-check-line me-1"></i>Sudah Dinilai (<?= $mentee['manual_count'] ?>/<?= $mentee['total_manual'] ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">
                                                <i class="ri-time-line me-1"></i>Belum Dinilai (<?= $mentee['manual_count'] ?>/<?= $mentee['total_manual'] ?>)
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="<?= base_url("kpi/assessment/form/{$mentee['id_user']}?bulan={$bulan}&tahun={$tahun}") ?>"
                                                class="btn btn-sm btn-<?= $mentee['is_assessed'] ? 'outline-primary' : 'primary' ?>">
                                                <i class="ri-edit-2-line me-1"></i><?= $mentee['is_assessed'] ? 'Edit' : 'Nilai' ?>
                                            </a>
                                            <a href="<?= base_url("kpi/assessment/history/{$mentee['id_user']}") ?>"
                                                class="btn btn-sm btn-outline-secondary" title="Riwayat">
                                                <i class="ri-history-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="card-body text-center py-5">
                    <i class="ri-user-line ri-4x text-muted mb-3 d-block"></i>
                    <h5>Tidak Ada Pemagang</h5>
                    <p class="text-muted">Tidak ada pemagang yang perlu dinilai.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>