<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('attendance/all') ?>">Absensi</a></li>
                <li class="breadcrumb-item active">Approval Koreksi</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-checkbox-line me-2"></i>Approval Koreksi Absensi
        </h4>
        <p class="mb-0 text-muted">Review dan setujui permintaan koreksi absensi</p>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 text-warning"><?= count($corrections) ?></h3>
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
                <small>Koreksi yang menunggu persetujuan akan ditampilkan di bawah ini.</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-label-info">
            <div class="card-body">
                <h6 class="card-title mb-2">
                    <i class="ri-shield-check-line me-1"></i> Permission
                </h6>
                <small>
                    <?php if (session()->get('kode_role') === 'mentor'): ?>
                        Anda hanya dapat approve mentee Anda
                    <?php else: ?>
                        Anda dapat approve semua koreksi
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Corrections List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Pending Koreksi</h5>
    </div>
    <div class="card-body">
        <?php if (empty($corrections)): ?>
            <div class="text-center py-5">
                <i class="ri-checkbox-circle-line" style="font-size: 64px; opacity: 0.3; color: #28c76f;"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada koreksi yang menunggu approval</p>
                <small class="text-muted">Semua permintaan sudah diproses</small>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-paginated">
                    <thead>
                        <tr>
                            <th>Pemagang</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Perubahan</th>
                            <th>Alasan</th>
                            <th>Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($corrections as $corr): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?= strtoupper(substr($corr['nama_lengkap'], 0, 2)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <strong class="d-block"><?= $corr['nama_lengkap'] ?></strong>
                                            <small class="text-muted"><?= $corr['nik'] ?></small>
                                        </div>
                                    </div>
                                </td>
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
                                            <i class="ri-arrow-right-s-line"></i>
                                            <strong class="text-success"><?= $corr['jam_masuk_baru'] ?></strong>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($corr['jenis_koreksi'] === 'keluar' || $corr['jenis_koreksi'] === 'both'): ?>
                                        <div>
                                            <small class="text-muted">Keluar:</small><br>
                                            <del class="text-danger"><?= $corr['old_jam_keluar'] ?? '-' ?></del>
                                            <i class="ri-arrow-right-s-line"></i>
                                            <strong class="text-success"><?= $corr['jam_keluar_baru'] ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="d-block" style="max-width: 200px;">
                                        <?= substr($corr['alasan'], 0, 60) ?><?= strlen($corr['alasan']) > 60 ? '...' : '' ?>
                                    </small>
                                    <?php if ($corr['bukti_foto']): ?>
                                        <button type="button" class="btn btn-sm btn-link p-0 btn-view-bukti"
                                            data-bukti="<?= $corr['bukti_foto'] ?>"
                                            data-name="<?= $corr['nama_lengkap'] ?>">
                                            <i class="ri-image-line"></i> Lihat Bukti
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d M Y H:i', strtotime($corr['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-success btn-approve"
                                            data-id="<?= $corr['id_correction'] ?>"
                                            data-name="<?= $corr['nama_lengkap'] ?>"
                                            data-tanggal="<?= date('d M Y', strtotime($corr['tanggal_koreksi'])) ?>">
                                            <i class="ri-check-line me-1"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-reject"
                                            data-id="<?= $corr['id_correction'] ?>"
                                            data-name="<?= $corr['nama_lengkap'] ?>"
                                            data-tanggal="<?= date('d M Y', strtotime($corr['tanggal_koreksi'])) ?>">
                                            <i class="ri-close-line me-1"></i> Reject
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-1 w-100 btn-view-detail"
                                        data-id="<?= $corr['id_correction'] ?>"
                                        data-name="<?= $corr['nama_lengkap'] ?>"
                                        data-nik="<?= $corr['nik'] ?>"
                                        data-tanggal="<?= date('d M Y', strtotime($corr['tanggal_koreksi'])) ?>"
                                        data-jenis="<?= $corr['jenis_koreksi'] ?>"
                                        data-old-masuk="<?= $corr['old_jam_masuk'] ?? '-' ?>"
                                        data-old-keluar="<?= $corr['old_jam_keluar'] ?? '-' ?>"
                                        data-new-masuk="<?= $corr['jam_masuk_baru'] ?? '-' ?>"
                                        data-new-keluar="<?= $corr['jam_keluar_baru'] ?? '-' ?>"
                                        data-alasan="<?= htmlspecialchars($corr['alasan']) ?>"
                                        data-bukti="<?= $corr['bukti_foto'] ?>">
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

<!-- Modal: View Bukti -->
<div class="modal fade" id="buktiModal" tabindex="-1" aria-labelledby="buktiModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buktiModalTitle">Bukti Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="buktiImage" src="" alt="Bukti" class="img-fluid rounded" style="max-height: 600px;">
            </div>
        </div>
    </div>
</div>

<!-- Modal: View Detail -->
<div class="modal fade" id="detailModal-attendance-correction-approval" tabindex="-1" aria-labelledby="detailModal-attendance-correction-approvalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buktiModalLabel">Detail Koreksi Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent-attendance-correction-approval"></div>
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
                <h5 class="modal-title text-white">Approve Koreksi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="ri-information-line me-2"></i>
                        <strong>Konfirmasi Approval</strong><br>
                        Anda akan menyetujui koreksi untuk: <strong id="approveName"></strong><br>
                        Tanggal: <strong id="approveTanggal"></strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="3"
                            placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>

                    <input type="hidden" name="id_correction" id="approveId">
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
                <h5 class="modal-title text-white">Reject Koreksi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="ri-alert-line me-2"></i>
                        <strong>Konfirmasi Reject</strong><br>
                        Anda akan menolak koreksi untuk: <strong id="rejectName"></strong><br>
                        Tanggal: <strong id="rejectTanggal"></strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="catatan" rows="3"
                            placeholder="Jelaskan alasan penolakan" required></textarea>
                        <small class="text-muted">Alasan wajib diisi agar pemagang mengetahui kenapa ditolak</small>
                    </div>

                    <input type="hidden" name="id_correction" id="rejectId">
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
    // View Bukti
    document.querySelectorAll('.btn-view-bukti').forEach(btn => {
        btn.addEventListener('click', function() {
            const bukti = this.dataset.bukti;
            const name = this.dataset.name;

            document.getElementById('buktiImage').src = '<?= base_url('writable/uploads/corrections/') ?>' + bukti;
            document.getElementById('buktiModalTitle').textContent = 'Bukti Foto - ' + name;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('buktiModal'));
            modal.show();
        });
    });

    // View Detail
    document.querySelectorAll('.btn-view-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;

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
                        <strong>Tanggal:</strong><br>
                        ${data.tanggal}
                    </div>
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
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-label-danger">${data.oldMasuk}</span>
                            <i class="ri-arrow-right-line"></i>
                            <span class="badge bg-label-success">${data.newMasuk}</span>
                        </div>
                    </div>
                `;
            }

            if (data.jenis === 'keluar' || data.jenis === 'both') {
                html += `
                    <div class="mb-3">
                        <strong>Jam Keluar:</strong><br>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-label-danger">${data.oldKeluar}</span>
                            <i class="ri-arrow-right-line"></i>
                            <span class="badge bg-label-success">${data.newKeluar}</span>
                        </div>
                    </div>
                `;
            }

            html += `
                <div class="mb-3">
                    <strong>Alasan:</strong><br>
                    <div class="alert alert-secondary mb-0 mt-2">${escapeHtml(data.alasan)}</div>
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

            document.getElementById('detailContent-attendance-correction-approval').innerHTML = html;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-attendance-correction-approval'));
            modal.show();
        });
    });

    // Approve Button
    document.querySelectorAll('.btn-approve').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const tanggal = this.dataset.tanggal;

            document.getElementById('approveId').value = id;
            document.getElementById('approveName').textContent = name;
            document.getElementById('approveTanggal').textContent = tanggal;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('approveModal'));
            modal.show();
        });
    });

    // Reject Button
    document.querySelectorAll('.btn-reject').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const tanggal = this.dataset.tanggal;

            document.getElementById('rejectId').value = id;
            document.getElementById('rejectName').textContent = name;
            document.getElementById('rejectTanggal').textContent = tanggal;

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('rejectModal'));
            modal.show();
        });
    });

    // Approve Form Submit
    document.getElementById('approveForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const id = formData.get('id_correction');

        Swal.fire({
            title: 'Memproses...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`<?= base_url('attendance/correction/approve/') ?>${id}`, {
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
        const id = formData.get('id_correction');

        Swal.fire({
            title: 'Memproses...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`<?= base_url('attendance/correction/reject/') ?>${id}`, {
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