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
                <li class="breadcrumb-item active">Tambah Role</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-add-line me-2"></i>Tambah Role Baru
        </h4>
        <p class="mb-0 text-muted">Isi form di bawah untuk menambahkan role baru</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah Role</h5>
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
                                placeholder="Contoh: Administrator" required>
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
                                placeholder="Contoh: admin" required>
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
                                placeholder="Deskripsi role dan tanggung jawabnya..."></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-deskripsi"></div>
                        <small class="text-muted">Jelaskan fungsi dan tanggung jawab role ini</small>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Aktif (Role dapat digunakan)
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('role') ?>" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Helper Card -->
    <div class="col-12 col-lg-4">
        <div class="card bg-primary-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-information-line me-1"></i> Informasi
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Nama role harus jelas dan deskriptif</small>
                    </li>
                    <li class="mb-2">
                        <small>Kode role digunakan di sistem (lowercase)</small>
                    </li>
                    <li class="mb-2">
                        <small>Kode role tidak bisa diubah setelah ada user</small>
                    </li>
                    <li class="mb-2">
                        <small>Role nonaktif tidak bisa dipilih saat tambah user</small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-shield-check-line me-1"></i> Contoh Role
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <span class="badge bg-label-primary">admin</span>
                        <small class="d-block text-muted">Administrator Sistem</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-label-success">hr</span>
                        <small class="d-block text-muted">Human Resource Staff</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-label-info">mentor</span>
                        <small class="d-block text-muted">Mentor/Pembimbing</small>
                    </li>
                    <li>
                        <span class="badge bg-label-warning">intern</span>
                        <small class="d-block text-muted">Pemagang</small>
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

        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        try {
            const formData = new FormData(form);

            const response = await fetch('<?= base_url('role/store') ?>', {
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
                submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Simpan';
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem',
                confirmButtonColor: '#696cff'
            });

            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Simpan';
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