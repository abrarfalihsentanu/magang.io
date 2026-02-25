<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('divisi') ?>">Data Divisi</a>
                </li>
                <li class="breadcrumb-item active">Edit Divisi</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-pencil-line me-2"></i>Edit Divisi
        </h4>
        <p class="mb-0 text-muted">Perbarui informasi divisi</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Divisi</h5>
                <span class="badge bg-label-primary"><?= esc($divisi['kode_divisi']) ?></span>
            </div>
            <div class="card-body">
                <form id="divisiForm">
                    <?= csrf_field() ?>

                    <!-- Nama Divisi -->
                    <div class="mb-4">
                        <label for="nama_divisi" class="form-label">
                            Nama Divisi <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-building-4-line"></i></span>
                            <input type="text" class="form-control" id="nama_divisi" name="nama_divisi"
                                value="<?= esc($divisi['nama_divisi']) ?>" required>
                        </div>
                        <div class="invalid-feedback" id="error-nama_divisi"></div>
                        <small class="text-muted">Nama divisi yang akan ditampilkan (min. 3 karakter)</small>
                    </div>

                    <!-- Kode Divisi -->
                    <div class="mb-4">
                        <label for="kode_divisi" class="form-label">
                            Kode Divisi <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-code-line"></i></span>
                            <input type="text" class="form-control" id="kode_divisi" name="kode_divisi"
                                value="<?= esc($divisi['kode_divisi']) ?>" required>
                        </div>
                        <div class="invalid-feedback" id="error-kode_divisi"></div>
                        <small class="text-muted">Kode unik untuk sistem (huruf kapital dan angka)</small>
                    </div>

                    <!-- Kepala Divisi -->
                    <div class="mb-4">
                        <label for="kepala_divisi" class="form-label">
                            Kepala Divisi
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-user-star-line"></i></span>
                            <input type="text" class="form-control" id="kepala_divisi" name="kepala_divisi"
                                value="<?= esc($divisi['kepala_divisi']) ?>" placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="invalid-feedback" id="error-kepala_divisi"></div>
                        <small class="text-muted">Nama kepala/pimpinan divisi (opsional)</small>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                                placeholder="Deskripsi divisi dan tanggung jawabnya..."><?= esc($divisi['deskripsi']) ?></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-deskripsi"></div>
                        <small class="text-muted">Jelaskan fungsi dan tanggung jawab divisi ini</small>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" <?= $divisi['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Aktif (Divisi dapat digunakan)
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Update
                        </button>
                        <a href="<?= base_url('divisi') ?>" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i> Batal
                        </a>
                        <a href="<?= base_url('divisi/detail/' . $divisi['id_divisi']) ?>" class="btn btn-outline-info ms-auto">
                            <i class="ri-eye-line me-1"></i> Lihat Detail
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="ri-information-line me-1"></i> Informasi Divisi
                </h6>
                <div class="mb-3">
                    <small class="text-muted d-block">ID Divisi</small>
                    <strong>#<?= $divisi['id_divisi'] ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Dibuat</small>
                    <strong><?= date('d M Y H:i', strtotime($divisi['created_at'])) ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Terakhir Update</small>
                    <strong><?= date('d M Y H:i', strtotime($divisi['updated_at'])) ?></strong>
                </div>
                <div>
                    <small class="text-muted d-block">Status Saat Ini</small>
                    <?php if ($divisi['is_active']): ?>
                        <span class="badge bg-label-success">Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-label-secondary">Nonaktif</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-3 bg-warning-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-alert-line me-1"></i> Peringatan
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Hati-hati mengubah kode divisi yang sudah digunakan</small>
                    </li>
                    <li class="mb-2">
                        <small>Pastikan tidak ada konflik dengan sistem</small>
                    </li>
                    <li>
                        <small>Menonaktifkan divisi akan membatasi akses user</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 & Form Handler -->
<script>
    const form = document.getElementById('divisiForm');
    const submitBtn = document.getElementById('submitBtn');

    // Auto format kode_divisi
    document.getElementById('kode_divisi').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Confirmation
        const result = await Swal.fire({
            title: 'Update Divisi?',
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

            const response = await fetch('<?= base_url('divisi/update/' . $divisi['id_divisi']) ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Log response untuk debugging
            console.log('Response status:', response.status);
            console.log('Response URL:', response.url);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);

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
                // Show validation errors
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
                    text: data.message,
                    confirmButtonColor: '#696cff'
                });

                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Update';
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem: ' + error.message,
                confirmButtonColor: '#696cff'
            });

            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Update';
        }
    });

    // Remove invalid class on input
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>

<?= $this->endSection() ?>