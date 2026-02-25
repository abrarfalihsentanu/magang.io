<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">💳 <?= esc($title) ?></h4>
            <p class="text-muted mb-0">Proses pembayaran uang saku yang sudah dikalkulasi</p>
        </div>
    </div>

    <!-- Payment Queue -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Antrian Pembayaran</h5>
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($allowances)):
                    $processableCount = 0;
                    foreach ($allowances as $a) {
                        if ($a['nomor_rekening']) $processableCount++;
                    }
                ?>
                    <span class="badge bg-label-warning"><?= count($allowances) ?> menunggu</span>
                    <?php if ($processableCount > 0): ?>
                        <button type="button" class="btn btn-primary btn-sm d-none" id="btnBulkProcess">
                            <i class="bx bx-send me-1"></i> Proses Terpilih (<span id="selectedCount">0</span>)
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="btnProcessAll">
                            <i class="bx bx-check-double me-1"></i> Proses Semua (<?= $processableCount ?>)
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($allowances)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bx bx-check-circle" style="font-size: 64px; color: #28c76f;"></i>
                    </div>
                    <h5 class="text-success">Semua Pembayaran Selesai</h5>
                    <p class="text-muted">Tidak ada pembayaran yang perlu diproses saat ini</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-paginated">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Periode</th>
                                <th>NIK</th>
                                <th>Nama Pemagang</th>
                                <th>Divisi</th>
                                <th class="text-center">Hadir</th>
                                <th class="text-end">Total</th>
                                <th>Rekening</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalPembayaran = 0;
                            foreach ($allowances as $allowance):
                                $totalPembayaran += $allowance['total_uang_saku'];
                            ?>
                                <tr>
                                    <td>
                                        <?php if ($allowance['nomor_rekening']): ?>
                                            <div class="form-check">
                                                <input class="form-check-input row-checkbox" type="checkbox"
                                                    value="<?= $allowance['id_allowance'] ?>"
                                                    data-nama="<?= esc($allowance['nama_lengkap']) ?>"
                                                    data-nominal="<?= $allowance['total_uang_saku'] ?>">
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= esc($allowance['nama_periode']) ?></small>
                                    </td>
                                    <td><?= esc($allowance['nik']) ?></td>
                                    <td>
                                        <strong><?= esc($allowance['nama_lengkap']) ?></strong>
                                    </td>
                                    <td><?= esc($allowance['nama_divisi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-label-success"><?= $allowance['total_hadir'] ?> hari</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp <?= number_format($allowance['total_uang_saku'], 0, ',', '.') ?></strong><br>
                                        <small class="text-muted"><?= $allowance['total_hadir'] ?> × Rp <?= number_format($allowance['rate_per_hari'], 0, ',', '.') ?></small>
                                    </td>
                                    <td>
                                        <?php if ($allowance['nomor_rekening']): ?>
                                            <div class="d-flex align-items-start">
                                                <i class="bx bx-wallet me-2 text-primary"></i>
                                                <div>
                                                    <strong><?= esc($allowance['nama_bank']) ?></strong><br>
                                                    <small class="text-muted"><?= esc($allowance['nomor_rekening']) ?></small><br>
                                                    <small class="text-muted">a/n <?= esc($allowance['atas_nama']) ?></small>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-label-danger">
                                                <i class="bx bx-error"></i> Rekening belum ada
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($allowance['nomor_rekening']): ?>
                                            <button type="button"
                                                class="btn btn-sm btn-primary btn-process-payment"
                                                data-allowance-id="<?= $allowance['id_allowance'] ?>"
                                                data-nama="<?= esc($allowance['nama_lengkap']) ?>"
                                                data-nominal="<?= $allowance['total_uang_saku'] ?>"
                                                data-bank="<?= esc($allowance['nama_bank']) ?>"
                                                data-rekening="<?= esc($allowance['nomor_rekening']) ?>"
                                                data-atas-nama="<?= esc($allowance['atas_nama']) ?>">
                                                <i class="bx bx-send"></i> Proses
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-label-secondary" disabled>
                                                Tidak bisa diproses
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="6" class="text-end">TOTAL PEMBAYARAN:</th>
                                <th class="text-end">
                                    <h5 class="mb-0">Rp <?= number_format($totalPembayaran, 0, ',', '.') ?></h5>
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Process Payment (Single) -->
<div class="modal fade" id="modalProcessPayment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formProcessPayment" enctype="multipart/form-data">
                <input type="hidden" name="id_allowance" id="paymentAllowanceId">
                <div class="modal-body">
                    <!-- Payment Info -->
                    <div class="alert alert-info mb-3">
                        <h6 class="mb-2">Detail Pembayaran:</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td style="width: 120px;">Nama</td>
                                <td>: <strong id="paymentNama"></strong></td>
                            </tr>
                            <tr>
                                <td>Nominal</td>
                                <td>: <strong id="paymentNominal" class="text-primary"></strong></td>
                            </tr>
                            <tr>
                                <td>Bank</td>
                                <td>: <span id="paymentBank"></span></td>
                            </tr>
                            <tr>
                                <td>Rekening</td>
                                <td>: <span id="paymentRekening"></span></td>
                            </tr>
                            <tr>
                                <td>Atas Nama</td>
                                <td>: <span id="paymentAtasNama"></span></td>
                            </tr>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Transfer <span class="text-danger">*</span></label>
                        <input type="date"
                            class="form-control"
                            name="tanggal_transfer"
                            id="tanggalTransfer"
                            required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Bukti Transfer</label>
                        <input type="file"
                            class="form-control"
                            name="bukti_transfer"
                            accept="image/*">
                        <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control"
                            name="catatan"
                            rows="3"
                            placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check"></i> Konfirmasi Pembayaran
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
        const modal = new bootstrap.Modal(document.getElementById('modalProcessPayment'));
        const form = document.getElementById('formProcessPayment');
        const selectAllCheckbox = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const btnBulkProcess = document.getElementById('btnBulkProcess');
        const btnProcessAll = document.getElementById('btnProcessAll');
        const selectedCountEl = document.getElementById('selectedCount');

        // Set today as default transfer date
        const tanggalTransfer = document.getElementById('tanggalTransfer');
        if (tanggalTransfer) {
            tanggalTransfer.value = new Date().toISOString().split('T')[0];
        }

        // ========================================
        // CHECKBOX SELECTION
        // ========================================
        function updateSelectionUI() {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            const count = checked.length;

            if (selectedCountEl) selectedCountEl.textContent = count;

            if (btnBulkProcess) {
                if (count > 0) {
                    btnBulkProcess.classList.remove('d-none');
                } else {
                    btnBulkProcess.classList.add('d-none');
                }
            }

            // Update select all state
            if (selectAllCheckbox && rowCheckboxes.length > 0) {
                const allChecked = checked.length === rowCheckboxes.length;
                const someChecked = checked.length > 0 && !allChecked;
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked;
            }

            // Highlight selected rows
            rowCheckboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (cb.checked) {
                    row.classList.add('table-primary');
                } else {
                    row.classList.remove('table-primary');
                }
            });
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectionUI();
            });
        }

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectionUI);
        });

        // ========================================
        // SINGLE PAYMENT (existing)
        // ========================================
        document.querySelectorAll('.btn-process-payment').forEach(button => {
            button.addEventListener('click', function() {
                const allowanceId = this.getAttribute('data-allowance-id');
                const nama = this.getAttribute('data-nama');
                const nominal = parseFloat(this.getAttribute('data-nominal'));
                const bank = this.getAttribute('data-bank');
                const rekening = this.getAttribute('data-rekening');
                const atasNama = this.getAttribute('data-atas-nama');

                document.getElementById('paymentAllowanceId').value = allowanceId;
                document.getElementById('paymentNama').textContent = nama;
                document.getElementById('paymentNominal').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
                document.getElementById('paymentBank').textContent = bank;
                document.getElementById('paymentRekening').textContent = rekening;
                document.getElementById('paymentAtasNama').textContent = atasNama;

                form.reset();
                tanggalTransfer.value = new Date().toISOString().split('T')[0];
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                modal.show();
            });
        });

        // Single Form Submit
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bx bx-loader bx-spin"></i> Memproses...';

                this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                const allowanceId = document.getElementById('paymentAllowanceId').value;
                const formData = new FormData(this);

                try {
                    const response = await csrfFetch(`/allowance/process-payment/${allowanceId}`, {
                        method: 'POST',
                        body: formData
                    });

                    if (response.success) {
                        modal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => location.reload());
                    } else {
                        if (response.errors) {
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
                            text: response.message
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memproses pembayaran'
                    });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // ========================================
        // BULK PAYMENT
        // ========================================
        async function executeBulkPayment(ids) {
            const tanggal = new Date().toISOString().split('T')[0];

            const confirmation = await Swal.fire({
                title: 'Proses Pembayaran Massal?',
                html: `<div class="text-start">
                    <p>Anda akan memproses <strong>${ids.length}</strong> pembayaran sekaligus.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Transfer</label>
                        <input type="date" class="form-control" id="swalTanggalTransfer" value="${tanggal}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan (opsional)</label>
                        <textarea class="form-control" id="swalCatatan" rows="2" placeholder="Catatan untuk semua pembayaran"></textarea>
                    </div>
                    <div class="alert alert-warning py-2 mb-0">
                        <i class="bx bx-info-circle me-1"></i>
                        <small>Semua pembayaran akan diproses dengan tanggal transfer yang sama. Bukti transfer tidak dapat diupload pada mode massal.</small>
                    </div>
                </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#8592a3',
                confirmButtonText: '<i class="bx bx-check"></i> Ya, Proses Semua!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'swal-wide'
                },
                preConfirm: () => {
                    const tgl = document.getElementById('swalTanggalTransfer').value;
                    if (!tgl) {
                        Swal.showValidationMessage('Tanggal transfer wajib diisi');
                        return false;
                    }
                    return {
                        tanggal_transfer: tgl,
                        catatan: document.getElementById('swalCatatan').value
                    };
                }
            });

            if (!confirmation.isConfirmed) return;

            const {
                tanggal_transfer,
                catatan
            } = confirmation.value;

            // Show progress
            Swal.fire({
                title: 'Memproses Pembayaran...',
                html: `<div class="mb-3"><div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" id="bulkProgressBar" style="width: 0%"></div></div></div>
                       <p class="mb-0"><span id="bulkProgressText">0</span> / ${ids.length} diproses</p>`,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let success = 0;
            let failed = 0;
            const errors = [];

            for (let i = 0; i < ids.length; i++) {
                try {
                    const formData = new FormData();
                    formData.append('tanggal_transfer', tanggal_transfer);
                    if (catatan) formData.append('catatan', catatan);

                    const response = await csrfFetch(`/allowance/process-payment/${ids[i]}`, {
                        method: 'POST',
                        body: formData
                    });

                    if (response.success) {
                        success++;
                    } else {
                        failed++;
                        errors.push(response.message || `ID ${ids[i]} gagal`);
                    }
                } catch (error) {
                    failed++;
                    errors.push(`ID ${ids[i]}: ${error.message}`);
                }

                // Update progress
                const progress = Math.round(((i + 1) / ids.length) * 100);
                const progressBar = document.getElementById('bulkProgressBar');
                const progressText = document.getElementById('bulkProgressText');
                if (progressBar) progressBar.style.width = progress + '%';
                if (progressText) progressText.textContent = i + 1;
            }

            // Show result
            let resultHtml = `<p><strong>${success}</strong> pembayaran berhasil diproses.</p>`;
            if (failed > 0) {
                resultHtml += `<p class="text-danger"><strong>${failed}</strong> pembayaran gagal.</p>`;
                resultHtml += `<details><summary>Detail error</summary><ul class="text-start small">`;
                errors.forEach(e => resultHtml += `<li>${e}</li>`);
                resultHtml += `</ul></details>`;
            }

            await Swal.fire({
                icon: failed === 0 ? 'success' : 'warning',
                title: failed === 0 ? 'Semua Berhasil!' : 'Selesai dengan Catatan',
                html: resultHtml,
                confirmButtonText: 'OK'
            });

            location.reload();
        }

        // Bulk Process Selected
        if (btnBulkProcess) {
            btnBulkProcess.addEventListener('click', function() {
                const checked = document.querySelectorAll('.row-checkbox:checked');
                const ids = Array.from(checked).map(cb => cb.value);
                if (ids.length === 0) return;
                executeBulkPayment(ids);
            });
        }

        // Process All
        if (btnProcessAll) {
            btnProcessAll.addEventListener('click', function() {
                const ids = Array.from(rowCheckboxes).map(cb => cb.value);
                if (ids.length === 0) return;
                executeBulkPayment(ids);
            });
        }
    });
</script>
<?= $this->endSection() ?>