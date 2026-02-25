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
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/indicators') ?>">KPI</a></li>
                <li class="breadcrumb-item active">Perhitungan KPI</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-calculator-line me-2"></i>Perhitungan KPI
                </h4>
                <p class="mb-0 text-muted">Hitung KPI otomatis berdasarkan data kehadiran, aktivitas, dan project</p>
            </div>
        </div>
    </div>
</div>

<!-- Month/Year Selector -->
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

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total Intern</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $totalInterns ?></h3>
                        </div>
                        <small class="mb-0">Intern aktif</small>
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

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Indikator Aktif</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= count($indicators) ?></h3>
                        </div>
                        <small class="mb-0">8 auto, <?= count(array_filter($indicators, fn($i) => !$i['is_auto_calculate'])) ?> manual</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-bar-chart-box-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Rata-rata Skor</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $stats['avg_score'] ?></h3>
                        </div>
                        <small class="mb-0"><?= $isCalculated ? 'Sudah dihitung' : 'Belum dihitung' ?></small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-<?= $isCalculated ? 'success' : 'warning' ?>">
                            <i class="ri-line-chart-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Penilaian Manual</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 <?= $manualPendingCount > 0 ? 'text-warning' : 'text-success' ?>"><?= $manualPendingCount > 0 ? $manualPendingCount . ' pending' : 'Lengkap' ?></h3>
                        </div>
                        <small class="mb-0">Penilaian mentor</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-<?= $manualPendingCount > 0 ? 'warning' : 'success' ?>">
                            <i class="ri-edit-2-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <?php if (!$isFinalized): ?>
                        <button type="button" class="btn btn-primary" id="btnCalculate" <?= $isFinalized ? 'disabled' : '' ?>>
                            <i class="ri-calculator-line me-1"></i>
                            <?= $isCalculated ? 'Hitung Ulang KPI' : 'Hitung KPI' ?> - <?= $namaBulan[$bulan] ?> <?= $tahun ?>
                        </button>
                    <?php endif; ?>

                    <?php if ($manualPendingCount > 0): ?>
                        <a href="<?= base_url('kpi/assessment?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-outline-warning">
                            <i class="ri-edit-2-line me-1"></i> Lengkapi Penilaian Manual (<?= $manualPendingCount ?>)
                        </a>
                    <?php endif; ?>

                    <?php if ($isFinalized): ?>
                        <span class="badge bg-success fs-6 py-2 px-3">
                            <i class="ri-check-double-line me-1"></i> Sudah Finalized
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Results Table (if calculated) -->
<?php if ($isCalculated && !empty($assessmentOverview)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Hasil Perhitungan - <?= $namaBulan[$bulan] ?> <?= $tahun ?></h5>
                    <a href="<?= base_url('kpi/monthly?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="ri-eye-line me-1"></i> Lihat Detail
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Intern</th>
                                <th>NIK</th>
                                <th>Divisi</th>
                                <th class="text-center">Jumlah Indikator</th>
                                <th class="text-center">Total Skor</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($assessmentOverview as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    <?= strtoupper(substr($row['nama_lengkap'], 0, 1)) ?>
                                                </span>
                                            </div>
                                            <strong><?= esc($row['nama_lengkap']) ?></strong>
                                        </div>
                                    </td>
                                    <td><code><?= esc($row['nik']) ?></code></td>
                                    <td><?= esc($row['nama_divisi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-label-info"><?= $row['indicator_count'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $score = (float)$row['total_score'];
                                        $badgeClass = $score >= 90 ? 'success' : ($score >= 75 ? 'primary' : ($score >= 60 ? 'warning' : 'danger'));
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>"><?= number_format($score, 2) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!$isFinalized): ?>
                                            <button class="btn btn-sm btn-outline-info btn-recalculate"
                                                data-user-id="<?= $row['id_user'] ?>"
                                                data-name="<?= esc($row['nama_lengkap']) ?>">
                                                <i class="ri-refresh-line"></i>
                                            </button>
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
<?php elseif (!$isCalculated): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-calculator-line ri-4x text-muted mb-3 d-block"></i>
                    <h5>Belum Ada Perhitungan</h5>
                    <p class="text-muted">Klik tombol "Hitung KPI" untuk memulai perhitungan otomatis.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Calculate KPI
    document.getElementById('btnCalculate')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Hitung KPI?',
            html: `Perhitungan akan dilakukan untuk <strong><?= $totalInterns ?> intern</strong> pada periode <strong><?= $namaBulan[$bulan] ?> <?= $tahun ?></strong>.<br><br>
               <?= $manualPendingCount > 0 ? '<span class="text-warning"><i class="ri-alert-line"></i> ' . $manualPendingCount . ' intern belum dinilai manual oleh mentor. Skor manual akan 0.</span>' : '' ?>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hitung',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return csrfFetch('<?= base_url('kpi/calculation/calculate') ?>', {
                        method: 'POST',
                        body: new URLSearchParams({
                            bulan: <?= $bulan ?>,
                            tahun: <?= $tahun ?>
                        })
                    })
                    .catch(error => {
                        Swal.showValidationMessage('Gagal: ' + error.message);
                        return {
                            success: false,
                            message: error.message
                        };
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then(result => {
            if (result.isConfirmed && result.value) {
                const data = result.value;
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Berhasil!' : 'Gagal',
                    text: data.message,
                }).then(() => {
                    if (data.success) location.reload();
                });
            }
        }).catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan: ' + error.message
            });
        });
    });

    // Recalculate single user
    document.querySelectorAll('.btn-recalculate').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Hitung ulang?',
                text: `Hitung ulang KPI untuk ${name}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return csrfFetch(`<?= base_url('kpi/calculation/recalculate') ?>/${userId}`, {
                            method: 'POST',
                            body: new URLSearchParams({
                                bulan: <?= $bulan ?>,
                                tahun: <?= $tahun ?>
                            })
                        })
                        .catch(error => {
                            Swal.showValidationMessage('Gagal: ' + error.message);
                            return {
                                success: false,
                                message: error.message
                            };
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(result => {
                if (result.isConfirmed && result.value) {
                    const data = result.value;
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        title: data.success ? 'Berhasil!' : 'Gagal',
                        text: data.message,
                    }).then(() => {
                        if (data.success) location.reload();
                    });
                }
            }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan: ' + error.message
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>