<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">💵 <?= esc($title) ?></h4>
            <?php if ($period): ?>
                <p class="text-muted mb-0">
                    Periode: <strong><?= esc($period['nama_periode']) ?></strong>
                    (<?= date('d M Y', strtotime($period['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($period['tanggal_selesai'])) ?>)
                </p>
            <?php endif; ?>
        </div>
        <div>
            <a href="<?= base_url('allowance/period') ?>" class="btn btn-label-secondary">
                <i class="bx bx-calendar"></i> Kelola Periode
            </a>
        </div>
    </div>

    <!-- Period Selector -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="form-label mb-2">Pilih Periode:</label>
                    <select class="form-select" id="periodSelector">
                        <?php foreach ($periods as $p): ?>
                            <option value="<?= $p['id_period'] ?>" <?= ($period && $p['id_period'] == $period['id_period']) ? 'selected' : '' ?>>
                                <?= esc($p['nama_periode']) ?> -
                                <?= date('d M Y', strtotime($p['tanggal_mulai'])) ?> s/d <?= date('d M Y', strtotime($p['tanggal_selesai'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($period): ?>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 align-items-end h-100">
                            <div class="flex-grow-1">
                                <small class="text-muted">Total Pemagang:</small>
                                <h5 class="mb-0"><?= $period['total_pemagang'] ?? 0 ?> orang</h5>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted">Total Nominal:</small>
                                <h5 class="mb-0">Rp <?= number_format($period['total_nominal'] ?? 0, 0, ',', '.') ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!$period): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-info-circle" style="font-size: 64px; color: #ddd;"></i>
                <h5 class="text-muted mt-3">Pilih periode untuk melihat data uang saku</h5>
            </div>
        </div>
    <?php else: ?>
        <!-- Allowances Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Uang Saku Pemagang</h5>
                <?php
                $statusClass = [
                    'draft' => 'bg-label-secondary',
                    'calculated' => 'bg-label-info',
                    'approved' => 'bg-label-warning',
                    'paid' => 'bg-label-success'
                ];
                $statusText = [
                    'draft' => 'Draft',
                    'calculated' => 'Terhitung',
                    'approved' => 'Disetujui',
                    'paid' => 'Dibayar'
                ];
                ?>
                <span class="badge <?= $statusClass[$period['status']] ?>">
                    <?= $statusText[$period['status']] ?>
                </span>
            </div>
            <div class="card-body">
                <?php if (empty($allowances)): ?>
                    <div class="text-center py-5">
                        <i class="bx bx-user-x" style="font-size: 64px; color: #ddd;"></i>
                        <h5 class="text-muted mt-3">Belum ada data uang saku</h5>
                        <p class="text-muted">Data akan muncul setelah periode dikalkulasi</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-paginated">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Pemagang</th>
                                    <th>Divisi</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Alpha</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Status</th>
                                    <th>Rekening</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allowances as $index => $allowance): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($allowance['nik']) ?></td>
                                        <td>
                                            <strong><?= esc($allowance['nama_lengkap']) ?></strong>
                                        </td>
                                        <td><?= esc($allowance['nama_divisi'] ?? '-') ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-label-success"><?= $allowance['total_hadir'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-label-danger"><?= $allowance['total_alpha'] ?></span>
                                        </td>
                                        <td class="text-end">
                                            <strong>Rp <?= number_format($allowance['total_uang_saku'], 0, ',', '.') ?></strong>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $paymentStatusClass = [
                                                'pending' => 'bg-label-warning',
                                                'approved' => 'bg-label-info',
                                                'paid' => 'bg-label-success'
                                            ];
                                            $paymentStatusText = [
                                                'pending' => 'Pending',
                                                'approved' => 'Disetujui',
                                                'paid' => 'Dibayar'
                                            ];
                                            ?>
                                            <span class="badge <?= $paymentStatusClass[$allowance['status_pembayaran']] ?>">
                                                <?= $paymentStatusText[$allowance['status_pembayaran']] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($allowance['nomor_rekening']): ?>
                                                <small>
                                                    <?= esc($allowance['nama_bank']) ?><br>
                                                    <?= esc($allowance['nomor_rekening']) ?><br>
                                                    a/n <?= esc($allowance['atas_nama']) ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="text-danger small">Belum ada</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="6" class="text-end">TOTAL:</th>
                                    <th class="text-end">
                                        Rp <?= number_format(array_sum(array_column($allowances, 'total_uang_saku')), 0, ',', '.') ?>
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Period Selector
        const periodSelector = document.getElementById('periodSelector');
        if (periodSelector) {
            periodSelector.addEventListener('change', function() {
                const periodId = this.value;
                if (periodId) {
                    window.location.href = '<?= base_url('allowance') ?>?period=' + periodId;
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>