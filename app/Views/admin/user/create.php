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
                    <a href="<?= base_url('user') ?>">Data User</a>
                </li>
                <li class="breadcrumb-item active">Tambah User</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-add-line me-2"></i>Tambah User Baru
        </h4>
        <p class="mb-0 text-muted">Isi form di bawah untuk menambahkan user baru</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah User</h5>
            </div>
            <div class="card-body">
                <form id="userForm">
                    <?= csrf_field() ?>

                    <!-- NIK Preview -->
                    <div class="alert alert-info mb-4" id="nikPreview" style="display: none;">
                        <div class="d-flex align-items-center">
                            <i class="ri-information-line me-2"></i>
                            <div>
                                <strong>NIK yang akan digenerate:</strong>
                                <span id="nikValue" class="ms-2 badge bg-primary"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label for="id_role" class="form-label">
                            Role <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-shield-user-line"></i></span>
                            <select class="form-select" id="id_role" name="id_role" required>
                                <option value="">Pilih Role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id_role'] ?>">
                                        <?= esc($role['nama_role']) ?> (<?= esc($role['kode_role']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-id_role"></div>
                        <small class="text-muted">NIK akan digenerate otomatis berdasarkan role</small>
                    </div>

                    <!-- Divisi -->
                    <div class="mb-4">
                        <label for="id_divisi" class="form-label">Divisi</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-building-4-line"></i></span>
                            <select class="form-select" id="id_divisi" name="id_divisi">
                                <option value="">Tidak Ada / Pilih Divisi</option>
                                <?php foreach ($divisi as $div): ?>
                                    <option value="<?= $div['id_divisi'] ?>">
                                        <?= esc($div['nama_divisi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-id_divisi"></div>
                        <small class="text-muted">Opsional, untuk staff/intern yang memiliki divisi</small>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label for="nama_lengkap" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-user-line"></i></span>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                placeholder="Contoh: John Doe" required>
                        </div>
                        <div class="invalid-feedback" id="error-nama_lengkap"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-mail-line"></i></span>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="user@muamalatbank.com" required>
                        </div>
                        <div class="invalid-feedback" id="error-email"></div>
                        <small class="text-muted">Email harus unik dan valid</small>
                    </div>

                    <!-- No HP -->
                    <div class="mb-4">
                        <label for="no_hp" class="form-label">No. HP/WhatsApp</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-phone-line"></i></span>
                            <input type="text" class="form-control" id="no_hp" name="no_hp"
                                placeholder="081234567890">
                        </div>
                        <div class="invalid-feedback" id="error-no_hp"></div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-lock-line"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Minimal 6 karakter" required>
                            <span class="input-group-text cursor-pointer" onclick="togglePassword()">
                                <i class="ri-eye-line" id="toggleIcon"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback" id="error-password"></div>
                        <small class="text-muted">Password minimal 6 karakter</small>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="L">
                                <label class="form-check-label" for="laki">
                                    Laki-laki
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P">
                                <label class="form-check-label" for="perempuan">
                                    Perempuan
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="mb-4">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="alamat" class="form-label">Alamat</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-map-pin-line"></i></span>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                placeholder="Alamat lengkap"></textarea>
                        </div>
                    </div>

                    <!-- Informasi Rekening Bank -->
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="ri-bank-card-line me-1"></i> Informasi Rekening Bank</h6>
                    <small class="text-muted d-block mb-3">Digunakan untuk pembayaran uang saku pemagang</small>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="nama_bank" class="form-label">Nama Bank</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri-bank-line"></i></span>
                                <select class="form-select" id="nama_bank" name="nama_bank">
                                    <option value="">Pilih Bank</option>
                                    <option value="Bank Muamalat">Bank Muamalat</option>
                                    <option value="Bank BCA">Bank BCA</option>
                                    <option value="Bank BNI">Bank BNI</option>
                                    <option value="Bank BRI">Bank BRI</option>
                                    <option value="Bank Mandiri">Bank Mandiri</option>
                                    <option value="Bank BSI">Bank BSI</option>
                                    <option value="Bank CIMB Niaga">Bank CIMB Niaga</option>
                                    <option value="Bank Danamon">Bank Danamon</option>
                                    <option value="Bank Permata">Bank Permata</option>
                                    <option value="Bank OCBC NISP">Bank OCBC NISP</option>
                                </select>
                            </div>
                            <div class="invalid-feedback" id="error-nama_bank"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="nomor_rekening" class="form-label">Nomor Rekening</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri-bank-card-line"></i></span>
                                <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening"
                                    placeholder="Contoh: 1234567890">
                            </div>
                            <div class="invalid-feedback" id="error-nomor_rekening"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="atas_nama" class="form-label">Atas Nama Rekening</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-user-line"></i></span>
                            <input type="text" class="form-control" id="atas_nama" name="atas_nama"
                                placeholder="Nama sesuai buku rekening">
                        </div>
                        <div class="invalid-feedback" id="error-atas_nama"></div>
                        <small class="text-muted">Kosongkan jika sama dengan nama lengkap</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary">
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
                        <small>NIK akan digenerate otomatis sesuai role</small>
                    </li>
                    <li class="mb-2">
                        <small>Email harus unik untuk setiap user</small>
                    </li>
                    <li class="mb-2">
                        <small>Password minimal 6 karakter</small>
                    </li>
                    <li class="mb-2">
                        <small>Field dengan tanda (*) wajib diisi</small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-shield-check-line me-1"></i> Format NIK
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <span class="badge bg-label-primary">ADM</span>
                        <small class="d-block text-muted">Administrator</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-label-success">HR</span>
                        <small class="d-block text-muted">Human Resource</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-label-info">FIN</span>
                        <small class="d-block text-muted">Finance</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-label-warning">MEN</span>
                        <small class="d-block text-muted">Mentor</small>
                    </li>
                    <li>
                        <span class="badge bg-label-secondary">INT</span>
                        <small class="d-block text-muted">Intern</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 & Form Handler -->
<script>
    const form = document.getElementById('userForm');
    const submitBtn = document.getElementById('submitBtn');
    const roleSelect = document.getElementById('id_role');
    const nikPreview = document.getElementById('nikPreview');
    const nikValue = document.getElementById('nikValue');

    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('ri-eye-line');
            toggleIcon.classList.add('ri-eye-off-line');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('ri-eye-off-line');
            toggleIcon.classList.add('ri-eye-line');
        }
    }

    // Preview NIK saat role dipilih
    roleSelect.addEventListener('change', async function() {
        if (this.value) {
            try {
                const formData = new FormData();
                formData.append('id_role', this.value);

                const response = await fetch('<?= base_url('user/get-next-nik') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    nikValue.textContent = data.nik;
                    nikPreview.style.display = 'block';
                }
            } catch (error) {
                console.error('Error fetching NIK:', error);
            }
        } else {
            nikPreview.style.display = 'none';
        }
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

            const response = await fetch('<?= base_url('user/store') ?>', {
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
                    html: `${data.message}<br><small class="text-muted">NIK: <strong>${data.nik}</strong></small>`,
                    showConfirmButton: false,
                    timer: 2000
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