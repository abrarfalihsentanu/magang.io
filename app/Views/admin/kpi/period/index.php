<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">KPI Periode</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1"><i class="ri-calendar-check-line me-2"></i>Hasil KPI Periode</h4>
                <p class="mb-0 text-muted">Akumulasi performa selama masa magang</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btnCalculatePeriod">
                    <i class="ri-calculator-line me-1"></i> Hitung Periode
                </button>
                <a href="<?= base_url('kpi/period/best-interns') ?>" class="btn btn-outline-warning">
                    <i class="ri-medal-line me-1"></i> Pemagang Terbaik
                </a>
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
                        <span class="text-heading">Total Dinilai</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0"><?= $totalResults ?></h3>
                        </div>
                        <small>Intern dengan hasil periode</small>
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
                        <span class="text-heading">Bulan Finalized</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0"><?= count($finalizedMonths) ?></h3>
                        </div>
                        <small>Bulan yang sudah final</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-check-double-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Best Intern</span>
                        <div class="my-1">
                            <h4 class="mb-0 text-white"><?= $bestIntern ? esc($bestIntern['nama_lengkap']) : 'Belum ditentukan' ?></h4>
                        </div>
                        <small><?= $bestIntern ? 'Score: ' . number_format($bestIntern['avg_total_score'], 2) : '-' ?></small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-white text-warning">
                            <i class="ri-trophy-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Period Results Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ranking Periode</h5>
            </div>
            <?php if (!empty($periodResults)): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">Rank</th>
                                <th>Intern</th>
                                <th>Divisi</th>
                                <th class="text-center">Avg Score</th>
                                <th class="text-center">Periode</th>
                                <th class="text-center">Best Intern</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($periodResults as $r): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if ($r['final_rank'] == 1): ?>🥇
                                        <?php elseif ($r['final_rank'] == 2): ?>🥈
                                        <?php elseif ($r['final_rank'] == 3): ?>🥉
                                    <?php else: ?><span class="text-muted"><?= $r['final_rank'] ?></span>
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
                                                <small class="text-muted d-block"><?= esc($r['nik']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($r['nama_divisi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php $c = $r['avg_total_score'] >= 75 ? 'success' : ($r['avg_total_score'] >= 60 ? 'warning' : 'danger'); ?>
                                        <span class="badge bg-<?= $c ?> fs-6"><?= number_format($r['avg_total_score'], 2) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">
                                            <?= isset($r['tanggal_mulai']) ? date('d/m/Y', strtotime($r['tanggal_mulai'])) : '-' ?> -
                                            <?= isset($r['tanggal_selesai']) ? date('d/m/Y', strtotime($r['tanggal_selesai'])) : '-' ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($r['is_best_intern']): ?>
                                            <span class="badge bg-warning">🏆 Best Intern</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="card-body text-center py-5">
                    <i class="ri-calendar-check-line ri-4x text-muted mb-3 d-block"></i>
                    <h5>Belum Ada Data</h5>
                    <p class="text-muted">Klik "Hitung Periode" untuk menghitung hasil akumulasi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('btnCalculatePeriod')?.addEventListener('click', function() {
        const btn = this;

        Swal.fire({
            title: 'Hitung Periode?',
            text: 'Sistem akan menghitung rata-rata skor dari semua bulan yang sudah di-finalize.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hitung',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                // Disable button during calculation
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menghitung...';

                try {
                    const data = await csrfFetch('<?= base_url('kpi/period/calculate') ?>', {
                        method: 'POST',
                        body: new URLSearchParams({})
                    });
                    return data;
                } catch (error) {
                    console.error('Calculate error:', error);
                    return {
                        success: false,
                        message: error.message || 'Gagal terhubung ke server. Silakan coba lagi.'
                    };
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-calculator-line me-1"></i> Hitung Periode';
                }
            }
        }).then(result => {
            if (result.isConfirmed) {
                const data = result.value;
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Berhasil!' : 'Gagal',
                    text: data.message,
                }).then(() => {
                    if (data.success) location.reload();
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>