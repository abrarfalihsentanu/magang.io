<?php
$kpi = (float)($archive['final_kpi_score'] ?? 0);
$pct = (float)($archive['persentase_kehadiran'] ?? 0);

$kpiMeta = match (true) {
    $kpi >= 90 => ['Sangat Baik', 'success', 'ri-star-fill'],
    $kpi >= 75 => ['Baik',        'primary', 'ri-thumb-up-line'],
    $kpi >= 60 => ['Cukup',       'warning', 'ri-bar-chart-line'],
    $kpi >= 40 => ['Kurang',      'danger',  'ri-thumb-down-line'],
    default    => ['Sangat Kurang', 'dark',   'ri-close-circle-line'],
};

$attMeta = match (true) {
    $pct >= 90 => ['success', 'ri-checkbox-circle-line'],
    $pct >= 75 => ['warning', 'ri-error-warning-line'],
    default    => ['danger',  'ri-close-circle-line'],
};
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('archive') ?>">Arsip Pemagang</a></li>
                    <li class="breadcrumb-item active"><?= esc($archive['nama_lengkap']) ?></li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-semibold">
                        <i class="ri-archive-line text-primary me-2"></i>Detail Arsip Pemagang
                    </h4>
                    <p class="text-muted mb-0">
                        Diarsipkan pada <?= date('d M Y H:i', strtotime($archive['archived_at'])) ?>
                        oleh <strong><?= esc($archive['archived_by_name'] ?? '-') ?></strong>
                    </p>
                </div>
                <a href="<?= base_url('archive') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-arrow-left-line me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- ── Left: Profile Card ─────────────────────────────────── -->
        <div class="col-xl-4">

            <div class="card mb-4">
                <div class="card-body text-center pt-4">
                    <!-- Avatar -->
                    <div class="mb-3">
                        <span class="avatar avatar-xl">
                            <span class="avatar-initial rounded-circle bg-label-primary fs-1">
                                <?= mb_strtoupper(mb_substr($archive['nama_lengkap'], 0, 1)) ?>
                            </span>
                        </span>
                    </div>
                    <h5 class="mb-1 fw-bold"><?= esc($archive['nama_lengkap']) ?></h5>
                    <p class="text-muted small mb-2"><?= esc($archive['nik']) ?></p>
                    <span class="badge bg-secondary">
                        <i class="ri-archive-line me-1"></i>Telah Diarsipkan
                    </span>
                </div>
                <div class="card-body border-top pt-3 pb-2">
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">Divisi</dt>
                        <dd class="col-7 mb-2"><?= esc($archive['divisi']) ?></dd>

                        <dt class="col-5 text-muted">Universitas</dt>
                        <dd class="col-7 mb-2"><?= esc($summary['universitas'] ?? '-') ?></dd>

                        <dt class="col-5 text-muted">Jurusan</dt>
                        <dd class="col-7 mb-2"><?= esc($summary['jurusan'] ?? '-') ?></dd>

                        <dt class="col-5 text-muted">Durasi</dt>
                        <dd class="col-7 mb-2"><?= esc($summary['durasi_bulan'] ?? '-') ?> bulan</dd>

                        <dt class="col-5 text-muted">Periode</dt>
                        <dd class="col-7 mb-2">
                            <?= date('d M Y', strtotime($archive['periode_mulai'])) ?> —
                            <?= date('d M Y', strtotime($archive['periode_selesai'])) ?>
                        </dd>
                    </dl>
                </div>
                <?php if (!empty($archive['keterangan'])): ?>
                    <div class="card-body border-top pt-3">
                        <p class="small text-muted mb-1 fw-semibold">Keterangan</p>
                        <p class="small mb-0"><?= esc($archive['keterangan']) ?></p>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- ── Right: Stats + Summary ─────────────────────────────── -->
        <div class="col-xl-8">

            <!-- Performance Summary Cards -->
            <div class="row g-3 mb-4">

                <!-- KPI Score -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-<?= $kpiMeta[1] ?>">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <span class="avatar avatar-md">
                                    <span class="avatar-initial rounded bg-label-<?= $kpiMeta[1] ?>">
                                        <i class="<?= $kpiMeta[2] ?> ri-24px"></i>
                                    </span>
                                </span>
                            </div>
                            <div class="fs-2 fw-bold text-<?= $kpiMeta[1] ?>"><?= number_format($kpi, 1) ?></div>
                            <small class="text-muted d-block">KPI Score</small>
                            <span class="badge bg-label-<?= $kpiMeta[1] ?> mt-1"><?= $kpiMeta[0] ?></span>
                        </div>
                    </div>
                </div>

                <!-- Rank -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <span class="avatar avatar-md">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ri-trophy-line ri-24px"></i>
                                    </span>
                                </span>
                            </div>
                            <div class="fs-2 fw-bold">
                                <?= $archive['final_rank'] > 0 ? '#' . $archive['final_rank'] : '—' ?>
                            </div>
                            <small class="text-muted">Peringkat Akhir</small>
                        </div>
                    </div>
                </div>

                <!-- Kehadiran -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-<?= $attMeta[0] ?>">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <span class="avatar avatar-md">
                                    <span class="avatar-initial rounded bg-label-<?= $attMeta[0] ?>">
                                        <i class="<?= $attMeta[1] ?> ri-24px"></i>
                                    </span>
                                </span>
                            </div>
                            <div class="fs-2 fw-bold text-<?= $attMeta[0] ?>"><?= number_format($pct, 1) ?>%</div>
                            <small class="text-muted d-block">Kehadiran</small>
                            <small class="text-muted">
                                <?= $archive['total_hari_hadir'] ?>/<?= $archive['total_hari_kerja'] ?> hari
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Uang Saku -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <span class="avatar avatar-md">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="ri-money-dollar-circle-line ri-24px"></i>
                                    </span>
                                </span>
                            </div>
                            <div class="fw-bold" style="font-size:1.1rem">
                                Rp <?= number_format((float)$archive['total_uang_saku'], 0, ',', '.') ?>
                            </div>
                            <small class="text-muted">Total Uang Saku</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Summary -->
            <div class="card">
                <div class="card-header border-0">
                    <h5 class="card-title mb-0">
                        <i class="ri-pie-chart-line me-2 text-primary"></i>Ringkasan Aktivitas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Total Aktivitas -->
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center gap-3 p-3 rounded border">
                                <span class="avatar avatar-sm">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ri-file-list-3-line"></i>
                                    </span>
                                </span>
                                <div>
                                    <div class="fw-bold fs-5"><?= $summary['total_aktivitas'] ?? 0 ?></div>
                                    <small class="text-muted">Aktivitas Harian</small>
                                </div>
                            </div>
                        </div>
                        <!-- Total Proyek -->
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center gap-3 p-3 rounded border">
                                <span class="avatar avatar-sm">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="ri-briefcase-line"></i>
                                    </span>
                                </span>
                                <div>
                                    <div class="fw-bold fs-5"><?= $summary['total_proyek'] ?? 0 ?></div>
                                    <small class="text-muted">Proyek</small>
                                </div>
                            </div>
                        </div>
                        <!-- Total Cuti/Izin -->
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center gap-3 p-3 rounded border">
                                <span class="avatar avatar-sm">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ri-article-line"></i>
                                    </span>
                                </span>
                                <div>
                                    <div class="fw-bold fs-5"><?= $summary['total_cuti_diizin'] ?? 0 ?></div>
                                    <small class="text-muted">Cuti/Izin Disetujui</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KPI Progress Bar -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold">Skor KPI Akhir</span>
                            <span class="small text-<?= $kpiMeta[1] ?> fw-bold"><?= number_format($kpi, 1) ?>/100</span>
                        </div>
                        <div class="progress" style="height:10px">
                            <div class="progress-bar bg-<?= $kpiMeta[1] ?>"
                                style="width:<?= min($kpi, 100) ?>%"
                                role="progressbar"
                                aria-valuenow="<?= $kpi ?>"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <!-- Kehadiran Progress Bar -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold">Persentase Kehadiran</span>
                            <span class="small text-<?= $attMeta[0] ?> fw-bold"><?= number_format($pct, 1) ?>%</span>
                        </div>
                        <div class="progress" style="height:10px">
                            <div class="progress-bar bg-<?= $attMeta[0] ?>"
                                style="width:<?= min($pct, 100) ?>%"
                                role="progressbar"
                                aria-valuenow="<?= $pct ?>"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /col-xl-8 -->

    </div><!-- /row -->
</div>
<?= $this->endSection() ?>