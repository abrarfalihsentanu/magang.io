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
                    <a href="<?= base_url('role') ?>">Data Role</a>
                </li>
                <li class="breadcrumb-item active">Edit Role</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-pencil-line me-2"></i>Edit Role
        </h4>
        <p class="mb-0 text-muted">Perbarui informasi role</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Role</h5>
                <span class="badge bg-label-primary"><?= esc($role['kode_role']) ?></span>
            </div>
            <div class="card-body">
                <form id="roleForm">
                    <?= csrf_field() ?>

                    <!-- Nama Role -->
                    <div class="mb-4">
                        <label for="nama_role" class="form-label">
                            Nama Role <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-shield-user-line"></i></span>
                            <input type="text" class="form-control" id="nama_role" name="nama_role"
                                value="<?= esc($role['nama_role']) ?>" required>
                        </div>
                        <div class="invalid-feedback" id="error-nama_role"></div>
                        <small class="text-muted">Nama role yang akan ditampilkan (min. 3 karakter)</small>
                    </div>

                    <!-- Kode Role -->
                    <div class="mb-4">
                        <label for="kode_role" class="form-label">
                            Kode Role <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-code-line"></i></span>
                            <input type="text" class="form-control" id="kode_role" name="kode_role"
                                value="<?= esc($role['kode_role']) ?>" required>
                        </div>
                        <div class="invalid-feedback" id="error-kode_role"></div>
                        <small class="text-muted">Kode unik untuk sistem (huruf kecil, angka, underscore, dash)</small>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                                placeholder="Deskripsi role dan tanggung jawabnya..."><?= esc($role['deskripsi']) ?></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-deskripsi"></div>
                        <small class="text-muted">Jelaskan fungsi dan tanggung jawab role ini</small>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" <?= $role['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Aktif (Role dapat digunakan)
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Update
                        </button>
                        <a href="<?= base_url('role') ?>" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i> Batal
                        </a>
                        <a href="<?= base_url('role/detail/' . $role['id_role']) ?>" class="btn btn-outline-info ms-auto">
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
                    <i class="ri-information-line me-1"></i> Informasi Role
                </h6>
                <div class="mb-3">
                    <small class="text-muted d-block">ID Role</small>
                    <strong>#<?= $role['id_role'] ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Dibuat</small>
                    <strong><?= date('d M Y H:i', strtotime($role['created_at'])) ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Terakhir Update</small>
                    <strong><?= date('d M Y H:i', strtotime($role['updated_at'])) ?></strong>
                </div>
                <div>
                    <small class="text-muted d-block">Status Saat Ini</small>
                    <?php if ($role['is_active']): ?>
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
                        <small>Hati-hati mengubah kode role yang sudah digunakan</small>
                    </li>
                    <li class="mb-2">
                        <small>Pastikan tidak ada konflik dengan sistem</small>
                    </li>
                    <li>
                        <small>Menonaktifkan role akan membatasi akses user</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 & Form Handler -->
<script>
    const form = document.getElementById('roleForm');
    const submitBtn = document.getElementById('submitBtn');

    // Auto format kode_role
    document.getElementById('kode_role').addEventListener('input', function(e) {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9_-]/g, '');
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Confirmation
        const result = await Swal.fire({
            title: 'Update Role?',
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

            const response = await fetch('<?= base_url('role/update/' . $role['id_role']) ?>', {
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