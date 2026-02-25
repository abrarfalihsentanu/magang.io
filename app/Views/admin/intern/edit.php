<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('intern') ?>">Data Pemagang</a></li>
                <li class="breadcrumb-item active">Edit Pemagang</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-pencil-line me-2"></i>Edit Data Pemagang
        </h4>
        <p class="mb-0 text-muted">Perbarui informasi pemagang</p>
    </div>
</div>

<!-- Form -->
<form id="internForm" enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <div class="row">
        <!-- Left Column -->
        <div class="col-12 col-lg-8">
            <!-- Data Pribadi -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Pribadi</h5>
                    <span class="badge bg-label-primary"><?= esc($intern['nik']) ?></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?= esc($intern['nama_lengkap']) ?>" required>
                            <div class="invalid-feedback" id="error-nama_lengkap"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= esc($intern['email']) ?>" required>
                            <div class="invalid-feedback" id="error-email"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" 
                                   value="<?= esc($intern['no_hp']) ?>" required>
                            <div class="invalid-feedback" id="error-no_hp"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="L" <?= $intern['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $intern['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <div class="invalid-feedback" id="error-jenis_kelamin"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                   value="<?= $intern['tanggal_lahir'] ?>" required>
                            <div class="invalid-feedback" id="error-tanggal_lahir"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_divisi" class="form-label">Divisi <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_divisi" name="id_divisi" required>
                                <option value="">Pilih Divisi...</option>
                                <?php foreach ($divisi as $d): ?>
                                    <option value="<?= $d['id_divisi'] ?>" <?= $intern['id_divisi'] == $d['id_divisi'] ? 'selected' : '' ?>>
                                        <?= esc($d['nama_divisi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="error-id_divisi"></div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= esc($intern['alamat']) ?></textarea>
                            <div class="invalid-feedback" id="error-alamat"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Akademik -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Data Akademik</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="universitas" class="form-label">Universitas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="universitas" name="universitas" 
                                   value="<?= esc($intern['universitas']) ?>" required>
                            <div class="invalid-feedback" id="error-universitas"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jurusan" class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" 
                                   value="<?= esc($intern['jurusan']) ?>" required>
                            <div class="invalid-feedback" id="error-jurusan"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Magang -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Data Magang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="periode_mulai" class="form-label">Periode Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="periode_mulai" name="periode_mulai" 
                                   value="<?= $intern['periode_mulai'] ?>" required>
                            <div class="invalid-feedback" id="error-periode_mulai"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="periode_selesai" class="form-label">Periode Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="periode_selesai" name="periode_selesai" 
                                   value="<?= $intern['periode_selesai'] ?>" required>
                            <div class="invalid-feedback" id="error-periode_selesai"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="durasi_bulan" class="form-label">Durasi (Bulan) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="durasi_bulan" name="durasi_bulan" 
                                   value="<?= $intern['durasi_bulan'] ?>" min="1" required>
                            <div class="invalid-feedback" id="error-durasi_bulan"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_mentor" class="form-label">Mentor Pembimbing</label>
                            <select class="form-select" id="id_mentor" name="id_mentor">
                                <option value="">Pilih Mentor...</option>
                                <?php foreach ($mentors as $mentor): ?>
                                    <option value="<?= $mentor['id_user'] ?>" <?= $intern['id_mentor'] == $mentor['id_user'] ? 'selected' : '' ?>>
                                        <?= esc($mentor['nama_lengkap']) ?> (<?= esc($mentor['nama_divisi']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="error-id_mentor"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status_magang" class="form-label">Status Magang <span class="text-danger">*</span></label>
                            <select class="form-select" id="status_magang" name="status_magang" required>
                                <option value="active" <?= $intern['status_magang'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="completed" <?= $intern['status_magang'] == 'completed' ? 'selected' : '' ?>>Selesai</option>
                                <option value="terminated" <?= $intern['status_magang'] == 'terminated' ? 'selected' : '' ?>>Diberhentikan</option>
                            </select>
                            <div class="invalid-feedback" id="error-status_magang"></div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="dokumen_surat_magang" class="form-label">
                                Surat Magang (PDF) 
                                <?php if ($intern['dokumen_surat_magang']): ?>
                                    <a href="<?= base_url('intern/download-document/' . $intern['id_intern']) ?>" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-file-pdf-line"></i> Lihat Dokumen Saat Ini
                                    </a>
                                <?php endif; ?>
                            </label>
                            <input type="file" class="form-control" id="dokumen_surat_magang" name="dokumen_surat_magang" accept=".pdf">
                            <div class="invalid-feedback" id="error-dokumen_surat_magang"></div>
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah dokumen. Maksimal 2MB, format PDF</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"><?= esc($intern['catatan']) ?></textarea>
                            <div class="invalid-feedback" id="error-catatan"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Update
                        </button>
                        <a href="<?= base_url('intern') ?>" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i> Batal
                        </a>
                        <a href="<?= base_url('intern/detail/' . $intern['id_intern']) ?>" class="btn btn-outline-info ms-auto">
                            <i class="ri-eye-line me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-12 col-lg-4">
            <!-- Info Card -->
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="ri-information-line me-1"></i> Informasi</h6>
                    <div class="mb-3">
                        <small class="text-muted d-block">ID Intern</small>
                        <strong>#<?= $intern['id_intern'] ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Dibuat</small>
                        <strong><?= date('d M Y H:i', strtotime($intern['created_at'])) ?></strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Terakhir Update</small>
                        <strong><?= date('d M Y H:i', strtotime($intern['updated_at'])) ?></strong>
                    </div>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="card bg-warning-subtle">
                <div class="card-body">
                    <h6 class="card-title"><i class="ri-alert-line me-1"></i> Peringatan</h6>
                    <ul class="ps-3 mb-0">
                        <li class="mb-2"><small>Hati-hati mengubah periode magang</small></li>
                        <li class="mb-2"><small>Perubahan dokumen akan mengganti file lama</small></li>
                        <li><small>Pastikan semua data sudah benar sebelum menyimpan</small></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    const form = document.getElementById('internForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Confirmation
        const result = await Swal.fire({
            title: 'Update Data?',
            text: 'Pastikan data yang diubah sudah benar',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengupdate...';

        try {
            const formData = new FormData(form);

            const response = await fetch('<?= base_url('intern/update/' . $intern['id_intern']) ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        const input = document.getElementById(key);
                        const errorDiv = document.getElementById(`error-${key}`);
                        if (input && errorDiv) {
                            input.classList.add('is-invalid');
                            errorDiv.textContent = data.errors[key];
                        }
                    });
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message
                });

                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Update';
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem'
            });

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Update';
        }
    });

    // Remove invalid on input
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Auto calculate duration
    document.getElementById('periode_mulai').addEventListener('change', calculateDuration);
    document.getElementById('periode_selesai').addEventListener('change', calculateDuration);

    function calculateDuration() {
        const mulai = document.getElementById('periode_mulai').value;
        const selesai = document.getElementById('periode_selesai').value;
        
        if (mulai && selesai) {
            const start = new Date(mulai);
            const end = new Date(selesai);
            const months = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());
            document.getElementById('durasi_bulan').value = months > 0 ? months : 1;
        }
    }
</script>

<?= $this->endSection() ?>