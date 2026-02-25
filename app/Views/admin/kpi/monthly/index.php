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
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/monthly') ?>">KPI Bulanan</a></li>
                <li class="breadcrumb-item active">Hasil <?= $namaBulan[$bulan] ?> <?= $tahun ?></li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-calendar-2-line me-2"></i>Hasil KPI Bulanan
                </h4>
                <p class="mb-0 text-muted">Ranking dan performa semua intern</p>
            </div>
            <div class="d-flex gap-2">
                <?php if (!empty($ranking) && !$stats['is_finalized'] && in_array(session()->get('role_code'), ['admin', 'hr'])): ?>
                    <button type="button" class="btn btn-success" id="btnFinalize">
                        <i class="ri-check-double-line me-1"></i> Finalize
                    </button>
                <?php endif; ?>
                <a href="<?= base_url('kpi/monthly/export?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-outline-secondary">
                    <i class="ri-download-line me-1"></i> Export CSV
                </a>
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

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total Intern</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $stats['total_users'] ?></h3>
                        </div>
                        <small>Dinilai bulan ini</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-group-line ri-26px"></i>
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
                        <span class="text-heading">Rata-rata</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $stats['avg_score'] ?></h3>
                        </div>
                        <small>Skor rata-rata</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
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
                        <span class="text-heading">Skor Tertinggi</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-success"><?= $stats['max_score'] ?></h3>
                        </div>
                        <small>Maximum</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-arrow-up-line ri-26px"></i>
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
                        <span class="text-heading">Status</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2"><?= $stats['is_finalized'] ? '✅' : '📝' ?></h3>
                        </div>
                        <small><?= $stats['is_finalized'] ? 'Finalized' : 'Draft' ?></small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-<?= $stats['is_finalized'] ? 'success' : 'warning' ?>">
                            <i class="ri-<?= $stats['is_finalized'] ? 'lock-line' : 'draft-line' ?> ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Distribution -->
<?php if (!empty($ranking)): ?>
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Performa</h5>
                </div>
                <div class="card-body">
                    <div id="performanceChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Kategori</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($kategoriLabels as $key => $val): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-<?= $val[1] ?>"><?= $val[0] ?></span>
                            <strong><?= $distribution[$key] ?? 0 ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Ranking Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ranking - <?= $namaBulan[$bulan] ?> <?= $tahun ?></h5>
            </div>
            <?php if (!empty($ranking)): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px">Rank</th>
                                <th>Intern</th>
                                <th>NIK</th>
                                <th>Divisi</th>
                                <th class="text-center">Skor</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ranking as $r): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if ($r['rank_bulan_ini'] <= 3): ?>
                                            <span class="badge bg-<?= $r['rank_bulan_ini'] == 1 ? 'warning' : ($r['rank_bulan_ini'] == 2 ? 'secondary' : 'danger') ?> rounded-pill">
                                                <?php if ($r['rank_bulan_ini'] == 1): ?>🥇<?php elseif ($r['rank_bulan_ini'] == 2): ?>🥈<?php else: ?>🥉<?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted"><?= $r['rank_bulan_ini'] ?></span>
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
                                            <strong><?= esc($r['nama_lengkap']) ?></strong>
                                        </div>
                                    </td>
                                    <td><code><?= esc($r['nik']) ?></code></td>
                                    <td><?= esc($r['nama_divisi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php
                                        $score = (float)$r['total_score'];
                                        $color = $score >= 90 ? 'success' : ($score >= 75 ? 'primary' : ($score >= 60 ? 'warning' : 'danger'));
                                        ?>
                                        <span class="badge bg-<?= $color ?> fs-6"><?= number_format($score, 2) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php $k = $kategoriLabels[$r['kategori_performa']] ?? ['?', 'secondary']; ?>
                                        <span class="badge bg-label-<?= $k[1] ?>"><?= $k[0] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url("kpi/monthly/view/{$bulan}/{$tahun}?user={$r['id_user']}") ?>"
                                            class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="card-body text-center py-5">
                    <i class="ri-calendar-2-line ri-4x text-muted mb-3 d-block"></i>
                    <h5>Belum Ada Data</h5>
                    <p class="text-muted">Belum ada perhitungan KPI untuk bulan ini.</p>
                    <a href="<?= base_url('kpi/calculation?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-primary">
                        <i class="ri-calculator-line me-1"></i> Hitung KPI
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    <?php if (!empty($ranking)): ?>
        // Performance Distribution Chart
        var chartOpts = {
            series: [<?= $distribution['excellent'] ?? 0 ?>, <?= $distribution['good'] ?? 0 ?>, <?= $distribution['average'] ?? 0 ?>, <?= $distribution['below_average'] ?? 0 ?>, <?= $distribution['poor'] ?? 0 ?>],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'],
            colors: ['#28a745', '#696cff', '#ffab00', '#ff4c51', '#333'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#performanceChart"), chartOpts).render();
    <?php endif; ?>

    // Finalize
    document.getElementById('btnFinalize')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Finalize Data?',
            html: 'Setelah di-finalize, data <strong>tidak dapat diubah lagi</strong>. Pastikan semua perhitungan sudah benar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Finalize',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return csrfFetch('<?= base_url('kpi/monthly/finalize/' . $bulan) ?>', {
                    method: 'POST',
                    body: new URLSearchParams({
                        tahun: <?= $tahun ?>
                    })
                }).then(r => r.json());
            }
        }).then(result => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: result.value.success ? 'success' : 'error',
                    title: result.value.success ? 'Berhasil!' : 'Gagal',
                    text: result.value.message,
                }).then(() => {
                    if (result.value.success) location.reload();
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>