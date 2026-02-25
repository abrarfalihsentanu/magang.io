<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('attendance') ?>">Absensi</a></li>
                <li class="breadcrumb-item active">Koreksi Absensi</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">
                    <i class="ri-edit-line me-2"></i>Koreksi Absensi
                </h4>
                <p class="mb-0 text-muted">Ajukan permintaan koreksi data absensi Anda</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCorrectionModal">
                    <i class="ri-add-line me-1"></i> Ajukan Koreksi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Info Alert -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info d-flex align-items-center">
            <i class="ri-information-line me-2"></i>
            <div>
                <strong>Ketentuan Koreksi Absensi:</strong>
                <ul class="mb-0 mt-2">
                    <li>Maksimal 7 hari ke belakang dari hari ini</li>
                    <li>Tidak bisa mengkoreksi absensi hari ini (gunakan fitur Check-in/Check-out)</li>
                    <li>Bukti foto/screenshot wajib dilampirkan</li>
                    <li>Alasan minimal 20 karakter</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1"><?= count(array_filter($corrections, fn($c) => $c['status_approval'] === 'pending')) ?></h3>
                        <small>Pending</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-success"><?= count(array_filter($corrections, fn($c) => $c['status_approval'] === 'approved')) ?></h3>
                        <small>Disetujui</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-check-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-danger"><?= count(array_filter($corrections, fn($c) => $c['status_approval'] === 'rejected')) ?></h3>
                        <small>Ditolak</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri-close-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1"><?= count($corrections) ?></h3>
                        <small>Total</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-file-list-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Corrections List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Riwayat Pengajuan Koreksi</h5>
    </div>
    <div class="card-body">
        <?php if (empty($corrections)): ?>
            <div class="text-center py-5">
                <i class="ri-file-list-line" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 mb-0">Belum ada pengajuan koreksi</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-paginated">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Koreksi</th>
                            <th>Jam Lama → Baru</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Disetujui Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($corrections as $corr): ?>
                            <?php
                            $statusClass = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                            $statusIcon = [
                                'pending' => 'ri-time-line',
                                'approved' => 'ri-check-line',
                                'rejected' => 'ri-close-line'
                            ];
                            ?>
                            <tr>
                                <td>
                                    <strong><?= date('d M Y', strtotime($corr['tanggal_koreksi'])) ?></strong><br>
                                    <small class="text-muted"><?= date('l', strtotime($corr['tanggal_koreksi'])) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">
                                        <?= ucfirst($corr['jenis_koreksi']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($corr['jenis_koreksi'] === 'masuk' || $corr['jenis_koreksi'] === 'both'): ?>
                                        <div class="mb-1">
                                            <small class="text-muted">Masuk:</small><br>
                                            <del class="text-danger"><?= $corr['old_jam_masuk'] ?? '-' ?></del>
                                            <i class="ri-arrow-right-line mx-1"></i>
                                            <strong class="text-success"><?= $corr['jam_masuk_baru'] ?? '-' ?></strong>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($corr['jenis_koreksi'] === 'keluar' || $corr['jenis_koreksi'] === 'both'): ?>
                                        <div>
                                            <small class="text-muted">Keluar:</small><br>
                                            <del class="text-danger"><?= $corr['old_jam_keluar'] ?? '-' ?></del>
                                            <i class="ri-arrow-right-line mx-1"></i>
                                            <strong class="text-success"><?= $corr['jam_keluar_baru'] ?? '-' ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= substr($corr['alasan'], 0, 50) ?><?= strlen($corr['alasan']) > 50 ? '...' : '' ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $statusClass[$corr['status_approval']] ?>">
                                        <i class="<?= $statusIcon[$corr['status_approval']] ?> me-1"></i>
                                        <?= ucfirst($corr['status_approval']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($corr['approver_name']): ?>
                                        <small><?= $corr['approver_name'] ?></small><br>
                                        <small class="text-muted"><?= date('d M Y', strtotime($corr['approved_at'])) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-view-detail"
                                        data-id="<?= $corr['id_correction'] ?>"
                                        data-tanggal="<?= date('d M Y', strtotime($corr['tanggal_koreksi'])) ?>"
                                        data-jenis="<?= $corr['jenis_koreksi'] ?>"
                                        data-old-masuk="<?= $corr['old_jam_masuk'] ?? '-' ?>"
                                        data-old-keluar="<?= $corr['old_jam_keluar'] ?? '-' ?>"
                                        data-new-masuk="<?= $corr['jam_masuk_baru'] ?? '-' ?>"
                                        data-new-keluar="<?= $corr['jam_keluar_baru'] ?? '-' ?>"
                                        data-alasan="<?= htmlspecialchars($corr['alasan']) ?>"
                                        data-bukti="<?= $corr['bukti_foto'] ?>"
                                        data-status="<?= $corr['status_approval'] ?>"
                                        data-approver="<?= $corr['approver_name'] ?? '' ?>"
                                        data-catatan="<?= $corr['catatan_approval'] ?? '' ?>">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: Add Correction -->
<div class="modal fade" id="addCorrectionModal" tabindex="-1" aria-labelledby="addCorrectionModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCorrectionModalLabel">Ajukan Koreksi Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="correctionForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal yang Dikoreksi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_koreksi" id="tanggalKoreksi" required>
                            <small class="text-muted">Maksimal 7 hari ke belakang</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Koreksi <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_koreksi" id="jenisKoreksi" required>
                                <option value="">Pilih Jenis</option>
                                <option value="masuk">Jam Masuk</option>
                                <option value="keluar">Jam Keluar</option>
                                <option value="both">Masuk & Keluar</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" id="jamMasukRow" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk yang Seharusnya <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="jam_masuk_baru" id="jamMasukBaru">
                        </div>
                    </div>

                    <div class="row" id="jamKeluarRow" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Keluar yang Seharusnya <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="jam_keluar_baru" id="jamKeluarBaru">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alasan" id="alasan" rows="4"
                            placeholder="Jelaskan alasan koreksi (minimal 20 karakter)" required></textarea>
                        <small class="text-muted" id="alasanCounter">0/20 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Foto/Screenshot <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="bukti_foto" id="buktiFoto"
                            accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, JPEG. Max: 2MB</small>

                        <div class="mt-2" id="previewContainer" style="display: none;">
                            <img id="previewImage" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="ri-save-line me-1"></i> Ajukan Koreksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: View Detail -->
<div class="modal fade" id="detailModal-attendance-correction" tabindex="-1" aria-labelledby="detailModal-attendance-correctionLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModal-attendance-correctionLabel">Detail Koreksi Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent-attendance-correction"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Set max date (7 days ago) and min date (today)
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const sevenDaysAgo = new Date();
        sevenDaysAgo.setDate(today.getDate() - 7);
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);

        const tanggalInput = document.getElementById('tanggalKoreksi');
        tanggalInput.max = yesterday.toISOString().split('T')[0];
        tanggalInput.min = sevenDaysAgo.toISOString().split('T')[0];
    });

    // Jenis Koreksi Change
    document.getElementById('jenisKoreksi').addEventListener('change', function() {
        const value = this.value;
        const jamMasukRow = document.getElementById('jamMasukRow');
        const jamKeluarRow = document.getElementById('jamKeluarRow');
        const jamMasukInput = document.getElementById('jamMasukBaru');
        const jamKeluarInput = document.getElementById('jamKeluarBaru');

        if (value === 'masuk') {
            jamMasukRow.style.display = 'block';
            jamKeluarRow.style.display = 'none';
            jamMasukInput.required = true;
            jamKeluarInput.required = false;
        } else if (value === 'keluar') {
            jamMasukRow.style.display = 'none';
            jamKeluarRow.style.display = 'block';
            jamMasukInput.required = false;
            jamKeluarInput.required = true;
        } else if (value === 'both') {
            jamMasukRow.style.display = 'block';
            jamKeluarRow.style.display = 'block';
            jamMasukInput.required = true;
            jamKeluarInput.required = true;
        } else {
            jamMasukRow.style.display = 'none';
            jamKeluarRow.style.display = 'none';
            jamMasukInput.required = false;
            jamKeluarInput.required = false;
        }
    });

    // Alasan Character Counter
    document.getElementById('alasan').addEventListener('input', function() {
        const length = this.value.length;
        document.getElementById('alasanCounter').textContent = `${length}/20 karakter`;

        if (length < 20) {
            document.getElementById('alasanCounter').className = 'text-danger';
        } else {
            document.getElementById('alasanCounter').className = 'text-success';
        }
    });

    // Preview Bukti Foto
    document.getElementById('buktiFoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Maksimal ukuran file 2MB'
                });
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Submit Form
    document.getElementById('correctionForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const alasan = document.getElementById('alasan').value;
        if (alasan.length < 20) {
            Swal.fire({
                icon: 'error',
                title: 'Alasan Terlalu Pendek',
                text: 'Alasan minimal 20 karakter'
            });
            return;
        }

        const formData = new FormData(this);

        Swal.fire({
            title: 'Mengajukan Koreksi...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch('<?= base_url('attendance/correction/submit') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem'
            });
        }
    });

    // View Detail
    document.querySelectorAll('.btn-view-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;

            const statusClass = {
                'pending': 'warning',
                'approved': 'success',
                'rejected': 'danger'
            };

            let html = `
                <div class="mb-3">
                    <strong>Tanggal:</strong><br>
                    ${data.tanggal}
                </div>
                <div class="mb-3">
                    <strong>Jenis Koreksi:</strong><br>
                    <span class="badge bg-label-info">${data.jenis.toUpperCase()}</span>
                </div>
            `;

            if (data.jenis === 'masuk' || data.jenis === 'both') {
                html += `
                    <div class="mb-3">
                        <strong>Jam Masuk:</strong><br>
                        <del class="text-danger">${data.oldMasuk}</del> 
                        <i class="ri-arrow-right-line mx-1"></i> 
                        <strong class="text-success">${data.newMasuk}</strong>
                    </div>
                `;
            }

            if (data.jenis === 'keluar' || data.jenis === 'both') {
                html += `
                    <div class="mb-3">
                        <strong>Jam Keluar:</strong><br>
                        <del class="text-danger">${data.oldKeluar}</del> 
                        <i class="ri-arrow-right-line mx-1"></i> 
                        <strong class="text-success">${data.newKeluar}</strong>
                    </div>
                `;
            }

            html += `
                <div class="mb-3">
                    <strong>Alasan:</strong><br>
                    ${escapeHtml(data.alasan)}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong><br>
                    <span class="badge bg-${statusClass[data.status]}">${data.status.toUpperCase()}</span>
                </div>
            `;

            if (data.bukti) {
                html += `
                    <div class="mb-3">
                        <strong>Bukti Foto:</strong><br>
                        <img src="<?= base_url('writable/uploads/corrections/') ?>${data.bukti}" 
                             class="img-fluid rounded mt-2" style="max-height: 300px;">
                    </div>
                `;
            }

            if (data.approver) {
                html += `
                    <div class="mb-3">
                        <strong>Disetujui Oleh:</strong><br>
                        ${data.approver}
                    </div>
                `;
            }

            if (data.catatan) {
                html += `
                    <div class="mb-3">
                        <strong>Catatan Approval:</strong><br>
                        <div class="alert alert-secondary mb-0">${escapeHtml(data.catatan)}</div>
                    </div>
                `;
            }

            document.getElementById('detailContent-attendance-correction').innerHTML = html;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-attendance-correction'));
            modal.show();
        });
    });
</script>

<?= $this->endSection() ?>