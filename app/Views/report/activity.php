<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Laporan Aktivitas</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1"><i class="ri-file-list-3-line me-2"></i>Laporan Aktivitas</h4>
                <p class="mb-0 text-muted">Rekap aktivitas harian pemagang per periode</p>
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
            <div class="col-md-2">
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
            <div class="col-md-2">
                <label class="form-label">Divisi</label>
                <select name="divisi" class="form-select">
                    <option value="">Semua</option>
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
                    <option value="learning" <?= $filters['kategori'] == 'learning' ? 'selected' : '' ?>>Learning</option>
                    <option value="task" <?= $filters['kategori'] == 'task' ? 'selected' : '' ?>>Task</option>
                    <option value="meeting" <?= $filters['kategori'] == 'meeting' ? 'selected' : '' ?>>Meeting</option>
                    <option value="training" <?= $filters['kategori'] == 'training' ? 'selected' : '' ?>>Training</option>
                    <option value="other" <?= $filters['kategori'] == 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status_approval" class="form-select">
                    <option value="">Semua</option>
                    <option value="approved" <?= $filters['status_approval'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="submitted" <?= $filters['status_approval'] == 'submitted' ? 'selected' : '' ?>>Pending</option>
                    <option value="rejected" <?= $filters['status_approval'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    <option value="draft" <?= $filters['status_approval'] == 'draft' ? 'selected' : '' ?>>Draft</option>
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
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-file-list-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['total']) ?></h5>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-check-double-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['approved']) ?></h5>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['pending']) ?></h5>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri-close-circle-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['rejected']) ?></h5>
                        <small class="text-muted">Rejected</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="ri-draft-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= number_format($summary['draft']) ?></h5>
                        <small class="text-muted">Draft</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3 flex-shrink-0">
                        <span class="avatar-initial rounded bg-white text-primary">
                            <i class="ri-timer-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-0 text-white"><?= $summary['total_jam'] ?></h5>
                        <small>Total Jam</small>
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
                <h5 class="mb-0">Aktivitas per Kategori</h5>
            </div>
            <div class="card-body">
                <div id="chartKategori" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Status Approval</h5>
            </div>
            <div class="card-body">
                <div id="chartStatus" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Detail Aktivitas</h5>
    </div>
    <?php if (!empty($activities)): ?>
        <div class="table-responsive">
            <table class="table table-hover table-paginated" id="activityTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Pemagang</th>
                        <th>Judul Aktivitas</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Durasi</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $idx => $act): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><?= date('d/m/Y', strtotime($act['tanggal'])) ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong><?= esc($act['nama_lengkap']) ?></strong>
                                    <small class="text-muted"><?= esc($act['nama_divisi'] ?? '-') ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;" title="<?= esc($act['judul_aktivitas']) ?>">
                                    <?= esc($act['judul_aktivitas']) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                $kategoriLabel = [
                                    'learning' => ['label' => 'Learning', 'color' => 'info'],
                                    'task' => ['label' => 'Task', 'color' => 'primary'],
                                    'meeting' => ['label' => 'Meeting', 'color' => 'warning'],
                                    'training' => ['label' => 'Training', 'color' => 'success'],
                                    'other' => ['label' => 'Other', 'color' => 'secondary']
                                ];
                                $kat = $kategoriLabel[$act['kategori']] ?? ['label' => $act['kategori'], 'color' => 'secondary'];
                                ?>
                                <span class="badge bg-label-<?= $kat['color'] ?>"><?= $kat['label'] ?></span>
                            </td>
                            <td class="text-center">
                                <?php
                                if (!empty($act['jam_mulai']) && !empty($act['jam_selesai'])) {
                                    $start = strtotime($act['jam_mulai']);
                                    $end = strtotime($act['jam_selesai']);
                                    $hours = ($end - $start) / 3600;
                                    echo number_format($hours, 1) . ' jam';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $statusBadge = [
                                    'approved' => ['label' => 'Approved', 'color' => 'success'],
                                    'submitted' => ['label' => 'Pending', 'color' => 'warning'],
                                    'rejected' => ['label' => 'Rejected', 'color' => 'danger'],
                                    'draft' => ['label' => 'Draft', 'color' => 'secondary']
                                ];
                                $st = $statusBadge[$act['status_approval']] ?? ['label' => $act['status_approval'], 'color' => 'secondary'];
                                ?>
                                <span class="badge bg-<?= $st['color'] ?>"><?= $st['label'] ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card-body text-center py-5">
            <i class="ri-file-list-3-line ri-4x text-muted mb-3 d-block"></i>
            <h5>Tidak Ada Data</h5>
            <p class="text-muted">Tidak ada data aktivitas untuk periode yang dipilih.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Kategori Chart
    const chartKategoriOptions = {
        series: [{
            name: 'Aktivitas',
            data: [
                <?= $summary['by_kategori']['learning'] ?>,
                <?= $summary['by_kategori']['task'] ?>,
                <?= $summary['by_kategori']['meeting'] ?>,
                <?= $summary['by_kategori']['training'] ?>,
                <?= $summary['by_kategori']['other'] ?>
            ]
        }],
        chart: {
            type: 'bar',
            height: 280,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 8,
                horizontal: true,
                distributed: true
            }
        },
        colors: ['#00cfe8', '#7367f0', '#ff9f43', '#28c76f', '#6c757d'],
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val + ' aktivitas'
            }
        },
        xaxis: {
            categories: ['Learning', 'Task', 'Meeting', 'Training', 'Other']
        },
        legend: {
            show: false
        }
    };

    const chartKategori = new ApexCharts(document.querySelector("#chartKategori"), chartKategoriOptions);
    chartKategori.render();

    // Status Chart
    const chartStatusOptions = {
        series: [<?= $summary['approved'] ?>, <?= $summary['pending'] ?>, <?= $summary['rejected'] ?>, <?= $summary['draft'] ?>],
        chart: {
            type: 'pie',
            height: 280,
            toolbar: {
                show: false
            }
        },
        labels: ['Approved', 'Pending', 'Rejected', 'Draft'],
        colors: ['#28c76f', '#ff9f43', '#ea5455', '#6c757d'],
        legend: {
            position: 'bottom'
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
        window.location.href = '<?= base_url('report/activity') ?>?' + params;
    });

    // Export Button
    document.getElementById('btnExport').addEventListener('click', function() {
        const filters = {
            bulan: document.querySelector('[name="bulan"]').value,
            tahun: document.querySelector('[name="tahun"]').value,
            divisi: document.querySelector('[name="divisi"]').value,
            kategori: document.querySelector('[name="kategori"]').value,
            status_approval: document.querySelector('[name="status_approval"]').value
        };

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('report/export') ?>';

        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = 'activity';
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