<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Laporan Keuangan</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1"><i class="ri-money-dollar-circle-line me-2"></i>Laporan Keuangan</h4>
                <p class="mb-0 text-muted">Rekap pembayaran uang saku pemagang</p>
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
            <div class="col-md-4">
                <label class="form-label">Periode Pembayaran</label>
                <select name="period" class="form-select">
                    <?php if (empty($periods)): ?>
                        <option value="">Tidak ada periode</option>
                    <?php else: ?>
                        <?php foreach ($periods as $period): ?>
                            <option value="<?= $period['id_period'] ?>" <?= $filters['period'] == $period['id_period'] ? 'selected' : '' ?>>
                                <?= esc($period['nama_periode']) ?> (<?= date('d/m/Y', strtotime($period['tanggal_mulai'])) ?> - <?= date('d/m/Y', strtotime($period['tanggal_selesai'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
            <div class="col-md-3">
                <label class="form-label">Status Pembayaran</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $filters['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="calculated" <?= $filters['status'] == 'calculated' ? 'selected' : '' ?>>Calculated</option>
                    <option value="approved" <?= $filters['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="paid" <?= $filters['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
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
                        <h3 class="mb-0"><?= number_format($summary['total_intern']) ?></h3>
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
                        <span class="text-muted d-block mb-1">Total Uang Saku</span>
                        <h3 class="mb-0">Rp <?= number_format($summary['total_uang_saku'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-money-dollar-circle-line ri-24px"></i>
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
                        <span class="text-muted d-block mb-1">Sudah Dibayar</span>
                        <h3 class="mb-0 text-success">Rp <?= number_format($summary['total_dibayar'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-check-double-line ri-24px"></i>
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
                        <span class="text-muted d-block mb-1">Belum Dibayar</span>
                        <h3 class="mb-0 text-warning">Rp <?= number_format($summary['total_pending'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Status Pembayaran</h5>
            </div>
            <div class="card-body">
                <div id="chartStatus" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Ringkasan Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="ri-hourglass-line"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Pending</small>
                                <strong><?= $summary['by_status']['pending'] ?> Pemagang</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri-calculator-line"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Calculated</small>
                                <strong><?= $summary['by_status']['calculated'] ?> Pemagang</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri-checkbox-circle-line"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Approved</small>
                                <strong><?= $summary['by_status']['approved'] ?> Pemagang</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-check-double-line"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Paid</small>
                                <strong><?= $summary['by_status']['paid'] ?> Pemagang</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <small class="text-muted">Persentase Dibayar</small>
                        <div class="progress mt-2" style="height: 10px;">
                            <?php
                            $percentPaid = $summary['total_intern'] > 0 ? ($summary['by_status']['paid'] / $summary['total_intern']) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-success" style="width: <?= $percentPaid ?>%"></div>
                        </div>
                        <small class="fw-semibold"><?= number_format($percentPaid, 1) ?>%</small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Rata-rata Uang Saku</small>
                        <h5 class="mb-0 mt-2">
                            Rp <?= $summary['total_intern'] > 0 ? number_format($summary['total_uang_saku'] / $summary['total_intern'], 0, ',', '.') : 0 ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Detail Uang Saku</h5>
    </div>
    <?php if (!empty($allowances)): ?>
        <div class="table-responsive">
            <table class="table table-hover table-paginated" id="allowanceTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Pemagang</th>
                        <th>Divisi</th>
                        <th class="text-center">Hari Kerja</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">Alpha</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal Transfer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allowances as $idx => $al): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong><?= esc($al['nama_lengkap']) ?></strong>
                                    <small class="text-muted"><?= esc($al['nik']) ?></small>
                                </div>
                            </td>
                            <td><?= esc($al['nama_divisi'] ?? '-') ?></td>
                            <td class="text-center"><?= $al['total_hari_kerja'] ?? 0 ?></td>
                            <td class="text-center">
                                <span class="badge bg-label-success"><?= $al['total_hadir'] ?? 0 ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-danger"><?= $al['total_alpha'] ?? 0 ?></span>
                            </td>
                            <td class="text-end">
                                <strong>Rp <?= number_format($al['total_uang_saku'] ?? 0, 0, ',', '.') ?></strong>
                            </td>
                            <td class="text-center">
                                <?php
                                $statusBadge = [
                                    'pending' => ['label' => 'Pending', 'color' => 'secondary'],
                                    'calculated' => ['label' => 'Calculated', 'color' => 'info'],
                                    'approved' => ['label' => 'Approved', 'color' => 'warning'],
                                    'paid' => ['label' => 'Paid', 'color' => 'success']
                                ];
                                $st = $statusBadge[$al['status_pembayaran']] ?? ['label' => $al['status_pembayaran'], 'color' => 'secondary'];
                                ?>
                                <span class="badge bg-<?= $st['color'] ?>"><?= $st['label'] ?></span>
                            </td>
                            <td class="text-center">
                                <?= !empty($al['tanggal_transfer']) ? date('d/m/Y', strtotime($al['tanggal_transfer'])) : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="6" class="text-end">Total:</th>
                        <th class="text-end">
                            <strong>Rp <?= number_format($summary['total_uang_saku'], 0, ',', '.') ?></strong>
                        </th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <div class="card-body text-center py-5">
            <i class="ri-money-dollar-circle-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Tidak Ada Data</h5>
            <p class="text-muted">Tidak ada data uang saku untuk periode yang dipilih.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Status Chart
    const chartStatusOptions = {
        series: [
            <?= $summary['by_status']['pending'] ?>,
            <?= $summary['by_status']['calculated'] ?>,
            <?= $summary['by_status']['approved'] ?>,
            <?= $summary['by_status']['paid'] ?>
        ],
        chart: {
            type: 'donut',
            height: 280,
            toolbar: {
                show: false
            }
        },
        labels: ['Pending', 'Calculated', 'Approved', 'Paid'],
        colors: ['#6c757d', '#00cfe8', '#ff9f43', '#28c76f'],
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
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' Pemagang'
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

    const chartStatus = new ApexCharts(document.querySelector("#chartStatus"), chartStatusOptions);
    chartStatus.render();

    // Filter Form Submit
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const params = new URLSearchParams(formData).toString();
        window.location.href = '<?= base_url('report/allowance') ?>?' + params;
    });

    // Export Button
    document.getElementById('btnExport').addEventListener('click', function() {
        const filters = {
            period: document.querySelector('[name="period"]').value,
            divisi: document.querySelector('[name="divisi"]').value,
            status: document.querySelector('[name="status"]').value
        };

        if (!filters.period) {
            Swal.fire({
                icon: 'warning',
                title: 'Periode Tidak Dipilih',
                text: 'Silakan pilih periode terlebih dahulu'
            });
            return;
        }

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('report/export') ?>';

        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = 'allowance';
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