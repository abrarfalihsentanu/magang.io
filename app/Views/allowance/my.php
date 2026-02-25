<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">💰 <?= esc($title) ?></h4>
            <p class="text-muted mb-0">Riwayat pembayaran uang saku Anda</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Allowances Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Uang Saku</h5>
            <?php if (!empty($allowances)): ?>
                <span class="badge bg-label-primary"><?= count($allowances) ?> periode</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (empty($allowances)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bx bx-wallet" style="font-size: 64px; color: #ddd;"></i>
                    </div>
                    <h5 class="text-muted">Belum ada data uang saku</h5>
                    <p class="text-muted">Data uang saku Anda akan muncul di sini setelah diproses oleh HR</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Tanggal</th>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Alpha</th>
                                <th class="text-center">Izin</th>
                                <th class="text-center">Sakit</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Slip</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allowances as $allowance): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($allowance['nama_periode']) ?></strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($allowance['tanggal_mulai'])) ?><br>
                                            <?= date('d/m/Y', strtotime($allowance['tanggal_selesai'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-success"><?= $allowance['total_hadir'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-danger"><?= $allowance['total_alpha'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-warning"><?= $allowance['total_izin'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-info"><?= $allowance['total_sakit'] ?></span>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp <?= number_format($allowance['total_uang_saku'], 0, ',', '.') ?></strong><br>
                                        <small class="text-muted"><?= $allowance['total_hadir'] ?> × Rp <?= number_format($allowance['rate_per_hari'], 0, ',', '.') ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $statusClass = [
                                            'pending' => 'bg-label-warning',
                                            'approved' => 'bg-label-info',
                                            'paid' => 'bg-label-success'
                                        ];
                                        $statusText = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'paid' => 'Dibayar'
                                        ];
                                        ?>
                                        <span class="badge <?= $statusClass[$allowance['status_pembayaran']] ?>">
                                            <?= $statusText[$allowance['status_pembayaran']] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($allowance['slip']): ?>
                                            <a href="<?= base_url('allowance/slip/' . $allowance['id_allowance']) ?>"
                                                class="btn btn-sm btn-label-primary"
                                                title="Download Slip">
                                                <i class="bx bx-download"></i> Download
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Belum tersedia</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Summary Card -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-label-primary">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="bx bx-calendar-event"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted small">Total Periode</p>
                                        <h5 class="mb-0"><?= count($allowances) ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-label-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="bx bx-check-circle"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted small">Sudah Dibayar</p>
                                        <h5 class="mb-0">
                                            <?= count(array_filter($allowances, fn($a) => $a['status_pembayaran'] === 'paid')) ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-label-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-info">
                                            <i class="bx bx-wallet"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted small">Total Diterima</p>
                                        <h5 class="mb-0">
                                            Rp <?= number_format(array_sum(array_column(array_filter($allowances, fn($a) => $a['status_pembayaran'] === 'paid'), 'total_uang_saku')), 0, ',', '.') ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>