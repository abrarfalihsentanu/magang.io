<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Cuti/Izin/Sakit</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">
                    <i class="ri-calendar-event-line me-2"></i>Cuti/Izin/Sakit
                </h4>
                <p class="mb-0 text-muted">Ajukan permohonan cuti, izin, atau sakit</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
                    <i class="ri-add-line me-1"></i> Ajukan Permohonan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Info Alert -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info d-flex align-items-start">
            <i class="ri-information-line me-2 mt-1"></i>
            <div>
                <strong>Ketentuan Pengajuan:</strong>
                <ul class="mb-0 mt-2">
                    <li><strong>Izin:</strong> Maksimal 3 hari berturut-turut</li>
                    <li><strong>Sakit:</strong> Wajib lampirkan surat keterangan dokter</li>
                    <li><strong>Cuti:</strong> Harus diajukan minimal H-3</li>
                    <li>Tanggal pengajuan tidak boleh mundur (harus >= hari ini)</li>
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
                        <h3 class="mb-1 text-warning"><?= $stats['pending'] ?></h3>
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
                        <h3 class="mb-1 text-success"><?= $stats['approved'] ?></h3>
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
                        <h3 class="mb-1 text-danger"><?= $stats['rejected'] ?></h3>
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
                        <h3 class="mb-1"><?= $stats['total_days'] ?></h3>
                        <small>Total Hari</small>
                    </div>
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-calendar-2-line ri-24px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave History -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Riwayat Pengajuan</h5>
        <span class="badge bg-label-primary"><?= count($leaves) ?> Records</span>
    </div>
    <div class="card-body">
        <?php if (empty($leaves)): ?>
            <div class="text-center py-5">
                <i class="ri-calendar-event-line" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 mb-0">Belum ada pengajuan cuti/izin/sakit</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-paginated">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Periode</th>
                            <th>Jumlah Hari</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Disetujui Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaves as $leave): ?>
                            <?php
                            $statusClass = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                            $jenisClass = [
                                'cuti' => 'primary',
                                'izin' => 'info',
                                'sakit' => 'secondary'
                            ];
                            $jenisIcon = [
                                'cuti' => 'ri-calendar-event-line',
                                'izin' => 'ri-information-line',
                                'sakit' => 'ri-nurse-line'
                            ];
                            ?>
                            <tr>
                                <td>
                                    <span class="badge bg-label-<?= $jenisClass[$leave['jenis_cuti']] ?>">
                                        <i class="<?= $jenisIcon[$leave['jenis_cuti']] ?> me-1"></i>
                                        <?= ucfirst($leave['jenis_cuti']) ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?= date('d M Y', strtotime($leave['tanggal_mulai'])) ?></strong>
                                    <i class="ri-arrow-right-s-line mx-1"></i>
                                    <strong><?= date('d M Y', strtotime($leave['tanggal_selesai'])) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">
                                        <?= $leave['jumlah_hari'] ?> Hari
                                    </span>
                                </td>
                                <td>
                                    <small><?= substr($leave['alasan'], 0, 40) ?><?= strlen($leave['alasan']) > 40 ? '...' : '' ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $statusClass[$leave['status_approval']] ?>">
                                        <?= ucfirst($leave['status_approval']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($leave['approved_by']): ?>
                                        <small><?= $leave['approver_name'] ?></small><br>
                                        <small class="text-muted"><?= date('d M Y', strtotime($leave['approved_at'])) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-view-detail"
                                        data-id="<?= $leave['id_leave'] ?>"
                                        data-jenis="<?= $leave['jenis_cuti'] ?>"
                                        data-mulai="<?= date('d M Y', strtotime($leave['tanggal_mulai'])) ?>"
                                        data-selesai="<?= date('d M Y', strtotime($leave['tanggal_selesai'])) ?>"
                                        data-jumlah="<?= $leave['jumlah_hari'] ?>"
                                        data-alasan="<?= htmlspecialchars($leave['alasan']) ?>"
                                        data-dokumen="<?= $leave['dokumen_pendukung'] ?>"
                                        data-status="<?= $leave['status_approval'] ?>"
                                        data-approver="<?= $leave['approver_name'] ?? '' ?>"
                                        data-catatan="<?= $leave['catatan_approval'] ?? '' ?>">
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

<!-- Modal: Add Leave -->
<div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLeaveModalLabel">Ajukan Cuti/Izin/Sakit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="leaveForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Jenis Permohonan <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_cuti" id="jenisCuti" required>
                                <option value="">Pilih Jenis</option>
                                <option value="cuti">Cuti</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                            </select>
                            <small class="text-muted" id="jenisHelper"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_mulai" id="tanggalMulai" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_selesai" id="tanggalSelesai" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Hari</label>
                        <input type="text" class="form-control" id="jumlahHari" readonly
                            style="background-color: #f5f5f5;">
                        <small class="text-muted">Otomatis terhitung dari tanggal mulai dan selesai</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alasan" id="alasan" rows="4"
                            placeholder="Jelaskan alasan permohonan" required></textarea>
                    </div>

                    <div class="mb-3" id="dokumenSection">
                        <label class="form-label">
                            Dokumen Pendukung
                            <span class="text-danger" id="dokumenRequired" style="display: none;">*</span>
                            <span class="text-muted" id="dokumenOptional">(Opsional)</span>
                        </label>
                        <input type="file" class="form-control" name="dokumen_pendukung" id="dokumenPendukung"
                            accept="image/*,application/pdf">
                        <small class="text-muted">Format: JPG, PNG, PDF. Max: 2MB</small>

                        <div class="mt-2" id="previewContainer" style="display: none;">
                            <img id="previewImage" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="ri-send-plane-line me-1"></i> Ajukan Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: View Detail -->
<div class="modal fade" id="detailModal-leave-my" tabindex="-1" aria-labelledby="detailModal-leave-myLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModal-leave-myLabel">Detail Permohonan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent-leave-my"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const MAX_IZIN_DAYS = 3; // From settings

    // Set min date to today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggalMulai').min = today;
        document.getElementById('tanggalSelesai').min = today;
    });

    // Jenis Cuti Change
    document.getElementById('jenisCuti').addEventListener('change', function() {
        const value = this.value;
        const dokumenRequired = document.getElementById('dokumenRequired');
        const dokumenOptional = document.getElementById('dokumenOptional');
        const dokumenInput = document.getElementById('dokumenPendukung');
        const jenisHelper = document.getElementById('jenisHelper');

        if (value === 'sakit') {
            dokumenRequired.style.display = 'inline';
            dokumenOptional.style.display = 'none';
            dokumenInput.required = true;
            jenisHelper.textContent = 'Wajib lampirkan surat keterangan dokter';
            jenisHelper.className = 'text-danger';
        } else if (value === 'izin') {
            dokumenRequired.style.display = 'none';
            dokumenOptional.style.display = 'inline';
            dokumenInput.required = false;
            jenisHelper.textContent = `Maksimal ${MAX_IZIN_DAYS} hari berturut-turut`;
            jenisHelper.className = 'text-warning';
        } else if (value === 'cuti') {
            dokumenRequired.style.display = 'none';
            dokumenOptional.style.display = 'inline';
            dokumenInput.required = false;
            jenisHelper.textContent = 'Harus diajukan minimal H-3';
            jenisHelper.className = 'text-info';
        } else {
            dokumenRequired.style.display = 'none';
            dokumenOptional.style.display = 'inline';
            dokumenInput.required = false;
            jenisHelper.textContent = '';
        }
    });

    // Calculate Days
    function calculateDays() {
        const startDate = document.getElementById('tanggalMulai').value;
        const endDate = document.getElementById('tanggalSelesai').value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            if (end < start) {
                document.getElementById('jumlahHari').value = 'Tanggal tidak valid';
                return 0;
            }

            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include start date

            document.getElementById('jumlahHari').value = diffDays + ' hari';
            return diffDays;
        }

        document.getElementById('jumlahHari').value = '-';
        return 0;
    }

    document.getElementById('tanggalMulai').addEventListener('change', function() {
        document.getElementById('tanggalSelesai').min = this.value;
        calculateDays();
    });

    document.getElementById('tanggalSelesai').addEventListener('change', calculateDays);

    // Preview Dokumen
    document.getElementById('dokumenPendukung').addEventListener('change', function(e) {
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

            // Preview if image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('previewContainer').style.display = 'none';
            }
        }
    });

    // Submit Form
    document.getElementById('leaveForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const jenis = document.getElementById('jenisCuti').value;
        const days = calculateDays();

        // Validate Izin max days
        if (jenis === 'izin' && days > MAX_IZIN_DAYS) {
            Swal.fire({
                icon: 'error',
                title: 'Izin Terlalu Lama',
                text: `Maksimal izin adalah ${MAX_IZIN_DAYS} hari`
            });
            return;
        }

        // Validate Cuti H-3
        if (jenis === 'cuti') {
            const startDate = new Date(document.getElementById('tanggalMulai').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const diffDays = Math.ceil((startDate - today) / (1000 * 60 * 60 * 24));

            if (diffDays < 3) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cuti Terlalu Dekat',
                    text: 'Cuti harus diajukan minimal H-3'
                });
                return;
            }
        }

        const formData = new FormData(this);

        Swal.fire({
            title: 'Mengajukan Permohonan...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch('<?= base_url('leave/submit') ?>', {
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

            const jenisClass = {
                'cuti': 'primary',
                'izin': 'info',
                'sakit': 'secondary'
            };

            let html = `
                <div class="mb-3">
                    <strong>Jenis:</strong><br>
                    <span class="badge bg-${jenisClass[data.jenis]}">${data.jenis.toUpperCase()}</span>
                </div>
                <div class="mb-3">
                    <strong>Periode:</strong><br>
                    ${data.mulai} <i class="ri-arrow-right-line"></i> ${data.selesai}
                </div>
                <div class="mb-3">
                    <strong>Jumlah Hari:</strong><br>
                    <span class="badge bg-label-info">${data.jumlah} Hari</span>
                </div>
                <div class="mb-3">
                    <strong>Alasan:</strong><br>
                    ${escapeHtml(data.alasan)}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong><br>
                    <span class="badge bg-${statusClass[data.status]}">${data.status.toUpperCase()}</span>
                </div>
            `;

            if (data.dokumen) {
                html += `
                    <div class="mb-3">
                        <strong>Dokumen Pendukung:</strong><br>
                        <a href="<?= base_url('writable/uploads/leaves/') ?>${data.dokumen}" 
                           target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="ri-file-line me-1"></i> Lihat Dokumen
                        </a>
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
                        <strong>Catatan:</strong><br>
                        <div class="alert alert-secondary mb-0">${escapeHtml(data.catatan)}</div>
                    </div>
                `;
            }

            document.getElementById('detailContent-leave-my').innerHTML = html;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-leave-my'));
            modal.show();
        });
    });
</script>

<?= $this->endSection() ?>