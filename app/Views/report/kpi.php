<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Laporan KPI</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1"><i class="ri-line-chart-line me-2"></i>Laporan KPI</h4>
                <p class="mb-0 text-muted">Rekap performa pemagang per periode</p>
            </div>
            <button type="button" class="btn btn-success" id="btnExport">
                <i class="ri-download-2-line me-1"></i> Export CSV
            </button>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php
                    $bulanNames = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                    for ($i = 1; $i <= 12; $i++):
                        $m = str_pad($i, 2, '0', STR_PAD_LEFT);
                    ?>
                        <option value="<?= $m ?>" <?= $filters['bulan'] == $m ? 'selected' : '' ?>><?= $bulanNames[$m] ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                        <option value="<?= $y ?>" <?= $filters['tahun'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Divisi</label>
                <select name="divisi" class="form-select">
                    <option value="">Semua Divisi</option>
                    <?php foreach ($divisions as $div): ?>
                        <option value="<?= $div['id_divisi'] ?>" <?= $filters['divisi'] == $div['id_divisi'] ? 'selected' : '' ?>>
                            <?= esc($div['nama_divisi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua</option>
                    <option value="excellent" <?= $filters['kategori'] == 'excellent' ? 'selected' : '' ?>>Excellent</option>
                    <option value="good" <?= $filters['kategori'] == 'good' ? 'selected' : '' ?>>Good</option>
                    <option value="average" <?= $filters['kategori'] == 'average' ? 'selected' : '' ?>>Average</option>
                    <option value="below_average" <?= $filters['kategori'] == 'below_average' ? 'selected' : '' ?>>Below Average</option>
                    <option value="poor" <?= $filters['kategori'] == 'poor' ? 'selected' : '' ?>>Poor</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ri-filter-line me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted d-block mb-1">Total Pemagang</span>
                        <h3 class="mb-0"><?= number_format($summary['total']) ?></h3>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-user-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted d-block mb-1">Rata-rata Score</span>
                        <h3 class="mb-0"><?= number_format($summary['avg_score'], 2) ?></h3>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-bar-chart-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted d-block mb-1">Score Tertinggi</span>
                        <h3 class="mb-0 text-success"><?= number_format($summary['max_score'], 2) ?></h3>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-arrow-up-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted d-block mb-1">Score Terendah</span>
                        <h3 class="mb-0 text-danger"><?= number_format($summary['min_score'], 2) ?></h3>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri-arrow-down-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Distribusi Kategori Performa</h5>
            </div>
            <div class="card-body">
                <div id="chartKategori" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Top 5 Pemagang</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($kpiResults)): ?>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <?php foreach (array_slice($kpiResults, 0, 5) as $idx => $kpi): ?>
                                    <tr>
                                        <td style="width: 40px;">
                                            <?php if ($idx == 0): ?>
                                                <span class="badge bg-warning rounded-pill fs-6">🥇</span>
                                            <?php elseif ($idx == 1): ?>
                                                <span class="badge bg-secondary rounded-pill fs-6">🥈</span>
                                            <?php elseif ($idx == 2): ?>
                                                <span class="badge bg-danger rounded-pill fs-6">🥉</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark rounded-pill"><?= $idx + 1 ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <?php if (!empty($kpi['foto'])): ?>
                                                        <img src="<?= base_url('uploads/users/' . $kpi['foto']) ?>" alt="" class="rounded-circle">
                                                    <?php else: ?>
                                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                                            <?= strtoupper(substr($kpi['nama_lengkap'], 0, 1)) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <strong><?= esc($kpi['nama_lengkap']) ?></strong>
                                                    <small class="d-block text-muted"><?= esc($kpi['nama_divisi'] ?? '-') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-<?= $kpi['total_score'] >= 75 ? 'success' : ($kpi['total_score'] >= 60 ? 'warning' : 'danger') ?> fs-6">
                                                <?= number_format($kpi['total_score'], 2) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Tidak ada data</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- KPI Periode (Overall) -->
<?php if (!empty($periodResults)): ?>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ri-trophy-line me-2"></i>Ranking KPI Periode (Akumulasi)</h5>
            <span class="badge bg-primary"><?= count($periodResults) ?> Pemagang</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-paginated">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">Rank</th>
                        <th>Pemagang</th>
                        <th>Divisi</th>
                        <th class="text-center">Avg Score</th>
                        <th class="text-center">Best Intern</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($periodResults, 0, 10) as $r): ?>
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
    </div>
<?php endif; ?>

<!-- Monthly KPI Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Detail KPI Bulanan</h5>
    </div>
    <?php if (!empty($kpiResults)): ?>
        <div class="table-responsive">
            <table class="table table-hover table-paginated" id="kpiTable">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">Rank</th>
                        <th>Pemagang</th>
                        <th>Divisi</th>
                        <th class="text-center">Total Score</th>
                        <th class="text-center">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kpiResults as $kpi): ?>
                        <tr>
                            <td class="text-center">
                                <?php if ($kpi['rank_bulan_ini'] == 1): ?>🥇
                                <?php elseif ($kpi['rank_bulan_ini'] == 2): ?>🥈
                                <?php elseif ($kpi['rank_bulan_ini'] == 3): ?>🥉
                            <?php else: ?><span class="text-muted"><?= $kpi['rank_bulan_ini'] ?></span>
                            <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <?php if (!empty($kpi['foto'])): ?>
                                            <img src="<?= base_url('uploads/users/' . $kpi['foto']) ?>" alt="" class="rounded-circle">
                                        <?php else: ?>
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?= strtoupper(substr($kpi['nama_lengkap'], 0, 1)) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong><?= esc($kpi['nama_lengkap']) ?></strong>
                                        <small class="text-muted d-block"><?= esc($kpi['nik']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($kpi['nama_divisi'] ?? '-') ?></td>
                            <td class="text-center">
                                <?php $c = $kpi['total_score'] >= 75 ? 'success' : ($kpi['total_score'] >= 60 ? 'warning' : 'danger'); ?>
                                <span class="badge bg-<?= $c ?> fs-6"><?= number_format($kpi['total_score'], 2) ?></span>
                            </td>
                            <td class="text-center">
                                <?php
                                $kategoriLabel = [
                                    'excellent' => ['label' => 'Excellent', 'color' => 'success'],
                                    'good' => ['label' => 'Good', 'color' => 'info'],
                                    'average' => ['label' => 'Average', 'color' => 'warning'],
                                    'below_average' => ['label' => 'Below Average', 'color' => 'secondary'],
                                    'poor' => ['label' => 'Poor', 'color' => 'danger']
                                ];
                                $kat = $kategoriLabel[$kpi['kategori_performa'] ?? 'average'] ?? ['label' => '-', 'color' => 'secondary'];
                                ?>
                                <span class="badge bg-label-<?= $kat['color'] ?>"><?= $kat['label'] ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card-body text-center py-5">
            <i class="ri-line-chart-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Tidak Ada Data</h5>
            <p class="text-muted">Tidak ada data KPI untuk periode yang dipilih.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Kategori Distribution Chart
    const chartKategoriOptions = {
        series: [
            <?= $summary['by_kategori']['excellent'] ?>,
            <?= $summary['by_kategori']['good'] ?>,
            <?= $summary['by_kategori']['average'] ?>,
            <?= $summary['by_kategori']['below_average'] ?>,
            <?= $summary['by_kategori']['poor'] ?>
        ],
        chart: {
            type: 'donut',
            height: 300,
            toolbar: {
                show: false
            }
        },
        labels: ['Excellent', 'Good', 'Average', 'Below Average', 'Poor'],
        colors: ['#28c76f', '#00cfe8', '#ff9f43', '#6c757d', '#ea5455'],
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function(w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 280
                }
            }
        }]
    };

    const chartKategori = new ApexCharts(document.querySelector("#chartKategori"), chartKategoriOptions);
    chartKategori.render();

    // Filter Form Submit
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const params = new URLSearchParams(formData).toString();
        window.location.href = '<?= base_url('report/kpi') ?>?' + params;
    });

    // Export Button
    document.getElementById('btnExport').addEventListener('click', function() {
        const filters = {
            bulan: document.querySelector('[name="bulan"]').value,
            tahun: document.querySelector('[name="tahun"]').value,
            divisi: document.querySelector('[name="divisi"]').value,
            kategori: document.querySelector('[name="kategori"]').value
        };

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('report/export') ?>';

        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = 'kpi';
        form.appendChild(typeInput);

        Object.keys(filters).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `filters[${key}]`;
            input.value = filters[key];
            form.appendChild(input);
        });

        // Add CSRF token
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = csrfMeta.content;
            form.appendChild(csrfInput);
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
</script>
<?= $this->endSection() ?>