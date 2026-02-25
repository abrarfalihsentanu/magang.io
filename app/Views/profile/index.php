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
                <li class="breadcrumb-item active">Profil Saya</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-user-line me-2"></i>Profil Saya
        </h4>
        <p class="mb-0 text-muted">Kelola informasi profil dan keamanan akun Anda</p>
    </div>
</div>

<!-- Profile Content -->
<div class="row g-4">
    <!-- Left Column - Profile Card -->
    <div class="col-12 col-md-5 col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center pt-4">
                <!-- Profile Photo -->
                <div class="mb-3">
                    <div id="avatarPreview" class="d-flex justify-content-center mb-3">
                        <?php
                        $photoUrl = $user['foto']
                            ? base_url('profile/photo/' . $user['foto'])
                            : base_url('assets/img/avatars/1.png');
                        ?>
                        <img src="<?= $photoUrl ?>"
                            alt="<?= esc($user['nama_lengkap']) ?>"
                            class="rounded-circle border"
                            style="width: 120px; height: 120px; object-fit: cover;"
                            onerror="this.src='<?= base_url('assets/img/avatars/1.png') ?>'">
                    </div>
                    <small class="text-muted d-block">Format: JPG, PNG (Max 2MB)</small>
                </div>

                <!-- User Info -->
                <h5 class="mb-2"><?= esc($user['nama_lengkap']) ?></h5>
                <div class="mb-3">
                    <span class="badge bg-label-primary mb-1"><?= esc($user['nama_role'] ?? 'N/A') ?></span>
                    <?php if (!empty($user['nama_divisi'])): ?>
                        <span class="badge bg-label-info mb-1 d-inline-block"><?= esc($user['nama_divisi']) ?></span>
                    <?php endif; ?>
                </div>

                <hr class="my-3">

                <!-- Quick Info -->
                <div class="text-start px-2">
                    <div class="d-flex align-items-start mb-2">
                        <i class="ri-mail-line me-2 text-muted mt-1" style="flex-shrink: 0;"></i>
                        <small class="text-break"><?= esc($user['email']) ?></small>
                    </div>
                    <?php if (!empty($user['no_hp'])): ?>
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri-phone-line me-2 text-muted" style="flex-shrink: 0;"></i>
                            <small><?= esc($user['no_hp']) ?></small>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex align-items-center mb-2">
                        <i class="ri-shield-user-line me-2 text-muted" style="flex-shrink: 0;"></i>
                        <small>NIK: <?= esc($user['nik']) ?></small>
                    </div>
                    <?php if (!empty($user['last_login'])): ?>
                        <div class="d-flex align-items-start">
                            <i class="ri-time-line me-2 text-muted mt-1" style="flex-shrink: 0;"></i>
                            <small>Login: <?= date('d M Y H:i', strtotime($user['last_login'])) ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3"><i class="ri-shield-check-line me-1 text-success"></i>Status Akun</h6>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Status:</span>
                    <?php
                    $statusClass = $user['status'] === 'active' ? 'success' : 'secondary';
                    $statusText = ucfirst($user['status']);
                    ?>
                    <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Member sejak:</span>
                    <small class="fw-medium"><?= date('d M Y', strtotime($user['created_at'])) ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Forms -->
    <div class="col-12 col-md-7 col-lg-8">
        <!-- Nav Tabs -->
        <ul class="nav nav-pills mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                    data-bs-target="#profile-content" type="button" role="tab">
                    <i class="ri-user-settings-line me-1"></i>
                    <span class="d-none d-sm-inline">Edit Profil</span>
                    <span class="d-inline d-sm-none">Profil</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                    data-bs-target="#password-content" type="button" role="tab">
                    <i class="ri-lock-password-line me-1"></i>
                    <span class="d-none d-sm-inline">Ubah Password</span>
                    <span class="d-inline d-sm-none">Password</span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile-content" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Profil</h5>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <!-- Foto Profile -->
                            <div class="mb-3">
                                <label for="foto" class="form-label">Ubah Foto Profil (Opsional)</label>
                                <input type="file" class="form-control" id="foto" name="foto"
                                    accept="image/jpeg,image/jpg,image/png">
                                <div class="invalid-feedback" id="error-foto"></div>
                                <small class="text-muted">Format: JPG, JPEG, PNG (Maks. 2MB). Kosongkan jika tidak ingin mengubah foto.</small>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-user-line"></i></span>
                                    <input type="text" class="form-control" id="nama_lengkap"
                                        name="nama_lengkap" value="<?= esc($user['nama_lengkap']) ?>" required>
                                </div>
                                <div class="invalid-feedback" id="error-nama_lengkap"></div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                    <input type="email" class="form-control" id="email"
                                        name="email" value="<?= esc($user['email']) ?>" required>
                                </div>
                                <div class="invalid-feedback" id="error-email"></div>
                            </div>

                            <!-- No HP -->
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                    <input type="text" class="form-control" id="no_hp"
                                        name="no_hp" value="<?= esc($user['no_hp'] ?? '') ?>"
                                        placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="invalid-feedback" id="error-no_hp"></div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-user-3-line"></i></span>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" <?= ($user['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="P" <?= ($user['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback" id="error-jenis_kelamin"></div>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                    <input type="date" class="form-control" id="tanggal_lahir"
                                        name="tanggal_lahir" value="<?= esc($user['tanggal_lahir'] ?? '') ?>">
                                </div>
                                <div class="invalid-feedback" id="error-tanggal_lahir"></div>
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-map-pin-line"></i></span>
                                    <textarea class="form-control" id="alamat" name="alamat"
                                        rows="3" placeholder="Alamat lengkap"><?= esc($user['alamat'] ?? '') ?></textarea>
                                </div>
                                <div class="invalid-feedback" id="error-alamat"></div>
                            </div>

                            <!-- Informasi Rekening Bank -->
                            <hr class="my-3">
                            <h6 class="mb-3"><i class="ri-bank-card-line me-1"></i> Informasi Rekening Bank</h6>
                            <small class="text-muted d-block mb-3">Digunakan untuk pembayaran uang saku</small>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_bank" class="form-label">Nama Bank</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri-bank-line"></i></span>
                                        <select class="form-select" id="nama_bank" name="nama_bank">
                                            <option value="">Pilih Bank</option>
                                            <?php
                                            $banks = ['Bank Muamalat', 'Bank BCA', 'Bank BNI', 'Bank BRI', 'Bank Mandiri', 'Bank BSI', 'Bank CIMB Niaga', 'Bank Danamon', 'Bank Permata', 'Bank OCBC NISP'];
                                            foreach ($banks as $bank):
                                            ?>
                                                <option value="<?= $bank ?>" <?= ($user['nama_bank'] ?? '') === $bank ? 'selected' : '' ?>><?= $bank ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="invalid-feedback" id="error-nama_bank"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nomor_rekening" class="form-label">Nomor Rekening</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ri-bank-card-line"></i></span>
                                        <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening"
                                            value="<?= esc($user['nomor_rekening'] ?? '') ?>"
                                            placeholder="Contoh: 1234567890">
                                    </div>
                                    <div class="invalid-feedback" id="error-nomor_rekening"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="atas_nama" class="form-label">Atas Nama Rekening</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-user-line"></i></span>
                                    <input type="text" class="form-control" id="atas_nama" name="atas_nama"
                                        value="<?= esc($user['atas_nama'] ?? '') ?>"
                                        placeholder="Nama sesuai buku rekening">
                                </div>
                                <div class="invalid-feedback" id="error-atas_nama"></div>
                                <small class="text-muted">Kosongkan jika sama dengan nama lengkap</small>
                            </div>

                            <!-- Readonly Fields -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIK</label>
                                    <input type="text" class="form-control" value="<?= esc($user['nik']) ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" value="<?= esc($user['nama_role'] ?? 'N/A') ?>" readonly>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-label-secondary" onclick="window.location.href='<?= base_url('dashboard') ?>'">
                                    <i class="ri-close-line me-1"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary" id="btnSubmitProfile">
                                    <i class="ri-save-line me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password Tab -->
            <div class="tab-pane fade" id="password-content" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ubah Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning d-flex align-items-start mb-3" role="alert">
                            <i class="ri-information-line me-2 mt-1"></i>
                            <div>
                                <strong>Perhatian!</strong> Pastikan password baru minimal 6 karakter dan mudah diingat.
                            </div>
                        </div>

                        <form id="passwordForm">
                            <?= csrf_field() ?>

                            <!-- Current Password -->
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    Password Lama <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-lock-line"></i></span>
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" required placeholder="Masukkan password lama">
                                    <span class="input-group-text cursor-pointer toggle-password" data-target="current_password">
                                        <i class="ri-eye-off-line"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="error-current_password"></div>
                            </div>

                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="new_password" class="form-label">
                                    Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                                    <input type="password" class="form-control" id="new_password"
                                        name="new_password" required placeholder="Minimal 6 karakter">
                                    <span class="input-group-text cursor-pointer toggle-password" data-target="new_password">
                                        <i class="ri-eye-off-line"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="error-new_password"></div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">
                                    Konfirmasi Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required placeholder="Ketik ulang password baru">
                                    <span class="input-group-text cursor-pointer toggle-password" data-target="confirm_password">
                                        <i class="ri-eye-off-line"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="error-confirm_password"></div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="reset" class="btn btn-label-secondary">
                                    <i class="ri-refresh-line me-1"></i>Reset Form
                                </button>
                                <button type="submit" class="btn btn-primary" id="btnSubmitPassword">
                                    <i class="ri-shield-check-line me-1"></i>Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            } else {
                input.type = 'password';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            }
        });
    });

    // Preview foto before upload
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.querySelector('#avatarPreview img');
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Clear all error messages
    function clearErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    // Display errors
    function displayErrors(errors) {
        clearErrors();
        for (let field in errors) {
            const input = document.querySelector(`[name="${field}"]`);
            const errorDiv = document.getElementById(`error-${field}`);
            if (input && errorDiv) {
                input.classList.add('is-invalid');
                errorDiv.textContent = errors[field];
            }
        }
    }

    // ========================================
    // SUBMIT PROFILE FORM
    // ========================================
    document.getElementById('profileForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        clearErrors();

        const submitBtn = document.getElementById('btnSubmitProfile');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        try {
            const formData = new FormData(this);

            const result = await csrfFetch('<?= base_url('profile/update') ?>', {
                method: 'POST',
                body: formData
            });

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Update avatar in navbar if foto was uploaded
                    if (result.foto_url) {
                        const navbarAvatar = document.querySelector('.navbar .avatar img');
                        if (navbarAvatar) {
                            navbarAvatar.src = result.foto_url;
                        }
                    }

                    // Reload to show updated data
                    window.location.reload();
                });
            } else {
                if (result.errors) {
                    displayErrors(result.errors);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: result.message
                    });
                }
            }
        } catch (error) {
            console.error('Profile Update Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat memperbarui profil. Periksa console untuk detail.',
                footer: '<small class="text-muted">' + error.message + '</small>'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // ========================================
    // SUBMIT PASSWORD FORM
    // ========================================
    document.getElementById('passwordForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        clearErrors();

        const submitBtn = document.getElementById('btnSubmitPassword');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengubah...';

        try {
            const formData = new FormData(this);

            const result = await csrfFetch('<?= base_url('profile/change-password') ?>', {
                method: 'POST',
                body: formData
            });

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                this.reset();
            } else {
                if (result.errors) {
                    displayErrors(result.errors);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: result.message
                    });
                }
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan pada server'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
</script>
<?= $this->endSection() ?>