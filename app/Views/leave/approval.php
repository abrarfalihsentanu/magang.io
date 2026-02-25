<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Approval Cuti/Izin/Sakit</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-calendar-check-fill me-2"></i>Approval Cuti/Izin/Sakit
        </h4>
        <p class="mb-0 text-muted">Review dan setujui permohonan cuti, izin, atau sakit</p>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-warning"><?= count($leaves) ?></h3>
                        <small>Menunggu Approval</small>
                    </div>
                    <span class="avatar avatar-lg">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line ri-26px"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-label-warning">
            <div class="card-body">
                <h6 class="card-title mb-2">
                    <i class="ri-information-line me-1"></i> Info
                </h6>
                <small>Permohonan cuti/izin/sakit yang menunggu persetujuan ditampilkan di bawah.</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-label-info">
            <div class="card-body">
                <h6 class="card-title mb-2">
                    <i class="ri-shield-check-line me-1"></i> Auto-Create Attendance
                </h6>
                <small>Jika disetujui, sistem akan otomatis membuat record absensi sesuai periode.</small>
            </div>
        </div>
    </div>
</div>

<!-- Leave Requests List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Pending Permohonan</h5>
    </div>
    <div class="card-body">
        <?php if (empty($leaves)): ?>
            <div class="text-center py-5">
                <i class="ri-checkbox-circle-line" style="font-size: 64px; opacity: 0.3; color: #28c76f;"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada permohonan yang menunggu approval</p>
                <small class="text-muted">Semua permintaan sudah diproses</small>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-paginated">
                    <thead>
                        <tr>
                            <th>Pemagang</th>
                            <th>Jenis</th>
                            <th>Periode</th>
                            <th>Jumlah Hari</th>
                            <th>Alasan</th>
                            <th>Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaves as $leave): ?>
                            <?php
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
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?= strtoupper(substr($leave['nama_lengkap'], 0, 2)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <strong class="d-block"><?= $leave['nama_lengkap'] ?></strong>
                                            <small class="text-muted"><?= $leave['nik'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $jenisClass[$leave['jenis_cuti']] ?>">
                                        <i class="<?= $jenisIcon[$leave['jenis_cuti']] ?> me-1"></i>
                                        <?= ucfirst($leave['jenis_cuti']) ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?= date('d M', strtotime($leave['tanggal_mulai'])) ?></strong>
                                    <i class="ri-arrow-right-s-line mx-1"></i>
                                    <strong><?= date('d M Y', strtotime($leave['tanggal_selesai'])) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">
                                        <?= $leave['jumlah_hari'] ?> Hari
                                    </span>
                                </td>
                                <td>
                                    <small class="d-block" style="max-width: 200px;">
                                        <?= substr($leave['alasan'], 0, 50) ?><?= strlen($leave['alasan']) > 50 ? '...' : '' ?>
                                    </small>
                                    <?php if ($leave['dokumen_pendukung']): ?>
                                        <a href="<?= base_url('writable/uploads/leaves/' . $leave['dokumen_pendukung']) ?>"
                                            target="_blank" class="btn btn-sm btn-link p-0">
                                            <i class="ri-file-line"></i> Lihat Dokumen
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d M Y H:i', strtotime($leave['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-success btn-approve"
                                            data-id="<?= $leave['id_leave'] ?>"
                                            data-name="<?= $leave['nama_lengkap'] ?>"
                                            data-jenis="<?= $leave['jenis_cuti'] ?>"
                                            data-periode="<?= date('d M', strtotime($leave['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($leave['tanggal_selesai'])) ?>">
                                            <i class="ri-check-line me-1"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-reject"
                                            data-id="<?= $leave['id_leave'] ?>"
                                            data-name="<?= $leave['nama_lengkap'] ?>"
                                            data-jenis="<?= $leave['jenis_cuti'] ?>"
                                            data-periode="<?= date('d M', strtotime($leave['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($leave['tanggal_selesai'])) ?>">
                                            <i class="ri-close-line me-1"></i> Reject
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-1 w-100 btn-view-detail"
                                        data-id="<?= $leave['id_leave'] ?>"
                                        data-name="<?= $leave['nama_lengkap'] ?>"
                                        data-nik="<?= $leave['nik'] ?>"
                                        data-jenis="<?= $leave['jenis_cuti'] ?>"
                                        data-mulai="<?= date('d M Y', strtotime($leave['tanggal_mulai'])) ?>"
                                        data-selesai="<?= date('d M Y', strtotime($leave['tanggal_selesai'])) ?>"
                                        data-jumlah="<?= $leave['jumlah_hari'] ?>"
                                        data-alasan="<?= htmlspecialchars($leave['alasan']) ?>"
                                        data-dokumen="<?= $leave['dokumen_pendukung'] ?>">
                                        <i class="ri-eye-line"></i> Detail
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

<!-- Modal: View Detail -->
<div class="modal fade" id="detailModal-leave-approval" tabindex="-1" aria-labelledby="detailModal-leave-approvalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModal-leave-approvalLabel">Detail Permohonan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent-leave-approval"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Approve Confirmation -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">Approve Permohonan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="ri-information-line me-2"></i>
                        <strong>Konfirmasi Approval</strong><br>
                        Anda akan menyetujui permohonan <span id="approveJenis"></span> untuk: <strong id="approveName"></strong><br>
                        Periode: <strong id="approvePeriode"></strong>
                    </div>

                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <small><strong>Info:</strong> Sistem akan otomatis membuat record absensi untuk setiap hari dalam periode tersebut.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="3"
                            placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>

                    <input type="hidden" name="id_leave" id="approveId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-check-line me-1"></i> Ya, Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Reject Confirmation -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Reject Permohonan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="ri-alert-line me-2"></i>
                        <strong>Konfirmasi Reject</strong><br>
                        Anda akan menolak permohonan <span id="rejectJenis"></span> untuk: <strong id="rejectName"></strong><br>
                        Periode: <strong id="rejectPeriode"></strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="catatan" rows="3"
                            placeholder="Jelaskan alasan penolakan" required></textarea>
                        <small class="text-muted">Alasan wajib diisi agar pemagang mengetahui kenapa ditolak</small>
                    </div>

                    <input type="hidden" name="id_leave" id="rejectId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-close-line me-1"></i> Ya, Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // View Detail
    document.querySelectorAll('.btn-view-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;

            const jenisClass = {
                'cuti': 'primary',
                'izin': 'info',
                'sakit': 'secondary'
            };

            let html = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Pemagang:</strong><br>
                        <div class="d-flex align-items-center mt-1">
                            <div class="avatar avatar-sm me-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    ${data.name.substring(0, 2).toUpperCase()}
                                </span>
                            </div>
                            <div>
                                ${data.name}<br>
                                <small class="text-muted">${data.nik}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong>Jenis:</strong><br>
                        <span class="badge bg-${jenisClass[data.jenis]}">${data.jenis.toUpperCase()}</span>
                    </div>
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
                    <div class="alert alert-secondary mb-0 mt-2">${escapeHtml(data.alasan)}</div>
                </div>
            `;

            if (data.dokumen) {
                html += `
                    <div class="mb-3">
                        <strong>Dokumen Pendukung:</strong><br>
                        <a href="<?= base_url('writable/uploads/leaves/') ?>${data.dokumen}" 
                           target="_blank" class="btn btn-outline-primary mt-2">
                            <i class="ri-file-line me-1"></i> Lihat Dokumen
                        </a>
                    </div>
                `;
            }

            document.getElementById('detailContent-leave-approval').innerHTML = html;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-leave-approval'));
            modal.show();
        });
    });

    // Approve Button
    document.querySelectorAll('.btn-approve').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const jenis = this.dataset.jenis;
            const periode = this.dataset.periode;

            document.getElementById('approveId').value = id;
            document.getElementById('approveName').textContent = name;
            document.getElementById('approveJenis').textContent = jenis.toUpperCase();
            document.getElementById('approvePeriode').textContent = periode;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('approveModal'));
            modal.show();
        });
    });

    // Reject Button
    document.querySelectorAll('.btn-reject').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const jenis = this.dataset.jenis;
            const periode = this.dataset.periode;

            document.getElementById('rejectId').value = id;
            document.getElementById('rejectName').textContent = name;
            document.getElementById('rejectJenis').textContent = jenis.toUpperCase();
            document.getElementById('rejectPeriode').textContent = periode;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('rejectModal'));
            modal.show();
        });
    });

    // Approve Form Submit
    document.getElementById('approveForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const id = formData.get('id_leave');

        Swal.fire({
            title: 'Memproses...',
            text: 'Membuat record absensi untuk periode tersebut',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`<?= base_url('leave/approve/') ?>${id}`, {
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
                    title: 'Approved!',
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

    // Reject Form Submit
    document.getElementById('rejectForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const id = formData.get('id_leave');

        Swal.fire({
            title: 'Memproses...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`<?= base_url('leave/reject/') ?>${id}`, {
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
                    title: 'Rejected!',
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
</script>

<?= $this->endSection() ?>