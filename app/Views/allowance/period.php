<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">📅 <?= esc($title) ?></h4>
            <p class="text-muted mb-0">Kelola periode pembayaran uang saku pemagang</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreatePeriod">
            <i class="bx bx-plus"></i> Buat Periode Baru
        </button>
    </div>

    <!-- Periods Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Periode</h5>
        </div>
        <div class="card-body">
            <?php if (empty($periods)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bx bx-calendar-x" style="font-size: 64px; color: #ddd;"></i>
                    </div>
                    <h5 class="text-muted">Belum ada periode</h5>
                    <p class="text-muted mb-3">Buat periode baru untuk memulai kalkulasi uang saku</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreatePeriod">
                        <i class="bx bx-plus"></i> Buat Periode Pertama
                    </button>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Tanggal</th>
                                <th class="text-center">Pemagang</th>
                                <th class="text-end">Total Nominal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($periods as $period): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($period['nama_periode']) ?></strong>
                                    </td>
                                    <td>
                                        <?= date('d M Y', strtotime($period['tanggal_mulai'])) ?><br>
                                        <small class="text-muted">s/d <?= date('d M Y', strtotime($period['tanggal_selesai'])) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($period['total_pemagang']): ?>
                                            <span class="badge bg-label-primary"><?= $period['total_pemagang'] ?> orang</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($period['total_nominal']): ?>
                                            <strong>Rp <?= number_format($period['total_nominal'], 0, ',', '.') ?></strong>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
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
                                    </td>
                                    <td class="text-center">
                                        <?php if ($period['status'] === 'draft'): ?>
                                            <button type="button"
                                                class="btn btn-sm btn-primary btn-calculate"
                                                data-period-id="<?= $period['id_period'] ?>"
                                                data-period-name="<?= esc($period['nama_periode']) ?>">
                                                <i class="bx bx-calculator"></i> Hitung
                                            </button>
                                        <?php else: ?>
                                            <a href="<?= base_url('allowance?period=' . $period['id_period']) ?>"
                                                class="btn btn-sm btn-label-primary">
                                                <i class="bx bx-show"></i> Lihat
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Detail Row -->
                                <?php if ($period['status'] !== 'draft'): ?>
                                    <tr class="table-active">
                                        <td colspan="6" class="small text-muted">
                                            <?php if ($period['calculated_by_name']): ?>
                                                Dihitung oleh: <strong><?= esc($period['calculated_by_name']) ?></strong>
                                                pada <?= date('d/m/Y H:i', strtotime($period['calculated_at'])) ?>
                                            <?php endif; ?>

                                            <?php if ($period['approved_by_name']): ?>
                                                | Disetujui oleh: <strong><?= esc($period['approved_by_name']) ?></strong>
                                                pada <?= date('d/m/Y H:i', strtotime($period['approved_at'])) ?>
                                            <?php endif; ?>

                                            <?php if ($period['paid_by_name']): ?>
                                                | Dibayar oleh: <strong><?= esc($period['paid_by_name']) ?></strong>
                                                pada <?= date('d/m/Y H:i', strtotime($period['paid_at'])) ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Create Period -->
<div class="modal fade" id="modalCreatePeriod" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Periode Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCreatePeriod">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Periode <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control"
                            name="nama_periode"
                            placeholder="Contoh: Uang Saku Januari 2026"
                            required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control"
                                name="tanggal_mulai"
                                required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control"
                                name="tanggal_selesai"
                                required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bx bx-info-circle"></i>
                        <small>Periode biasanya dimulai tanggal 15 bulan sebelumnya dan berakhir tanggal 14 bulan berikutnya.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create Period Form
        const formCreatePeriod = document.getElementById('formCreatePeriod');
        if (formCreatePeriod) {
            formCreatePeriod.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bx bx-loader bx-spin"></i> Menyimpan...';

                // Clear previous errors
                this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                const formData = new FormData(this);

                try {
                    const response = await csrfFetch('/allowance/period/create', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.success) {
                        // Close modal first
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalCreatePeriod'));
                        if (modal) modal.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        if (response.errors) {
                            // Show validation errors
                            for (const [field, message] of Object.entries(response.errors)) {
                                const input = this.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.nextElementSibling;
                                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                                        feedback.textContent = message;
                                    }
                                }
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyimpan data',
                        confirmButtonText: 'OK'
                    });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // Calculate Buttons
        const calculateButtons = document.querySelectorAll('.btn-calculate');
        calculateButtons.forEach(button => {
            button.addEventListener('click', async function() {
                const periodId = this.getAttribute('data-period-id');
                const periodName = this.getAttribute('data-period-name');

                const result = await Swal.fire({
                    icon: 'question',
                    title: 'Konfirmasi Kalkulasi',
                    html: `Anda akan menghitung uang saku untuk periode:<br><strong>${periodName}</strong><br><br>
                       Sistem akan menghitung kehadiran semua pemagang aktif.<br>
                       Proses ini tidak dapat dibatalkan. Lanjutkan?`,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hitung',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#696cff'
                });

                if (!result.isConfirmed) return;

                // Show loading
                Swal.fire({
                    title: 'Menghitung...',
                    html: 'Mohon tunggu, sistem sedang menghitung uang saku',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const formData = new FormData();
                    formData.append('id_period', periodId);

                    const response = await csrfFetch('/allowance/calculate', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghitung uang saku',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>