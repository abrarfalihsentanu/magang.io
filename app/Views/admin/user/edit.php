<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

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
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-pencil-line me-2"></i>Edit User
        </h4>
        <p class="mb-0 text-muted">Perbarui informasi user</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit User</h5>
                <span class="badge bg-label-dark"><?= esc($user['nik']) ?></span>
            </div>
            <div class="card-body">
                <form id="userForm">
                    <?= csrf_field() ?>

                    <!-- Current Photo Preview -->
                    <div class="mb-4 text-center">
                        <div class="avatar avatar-xl mb-2">
                            <img src="<?= base_url('uploads/users/' . ($user['foto'] ?? 'default-avatar.png')) ?>"
                                alt="<?= esc($user['nama_lengkap']) ?>"
                                class="rounded-circle"
                                onerror="this.src='<?= base_url('assets/img/avatars/1.png') ?>'">
                        </div>
                        <small class="text-muted d-block">Foto Profil Saat Ini</small>
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
                                    <option value="<?= $role['id_role'] ?>" <?= $user['id_role'] == $role['id_role'] ? 'selected' : '' ?>>
                                        <?= esc($role['nama_role']) ?> (<?= esc($role['kode_role']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-id_role"></div>
                        <small class="text-muted">⚠️ Hati-hati mengubah role yang sudah ada</small>
                    </div>

                    <!-- Divisi -->
                    <div class="mb-4">
                        <label for="id_divisi" class="form-label">Divisi</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-building-4-line"></i></span>
                            <select class="form-select" id="id_divisi" name="id_divisi">
                                <option value="">Tidak Ada / Pilih Divisi</option>
                                <?php foreach ($divisi as $div): ?>
                                    <option value="<?= $div['id_divisi'] ?>" <?= $user['id_divisi'] == $div['id_divisi'] ? 'selected' : '' ?>>
                                        <?= esc($div['nama_divisi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-id_divisi"></div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label for="nama_lengkap" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-user-line"></i></span>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                value="<?= esc($user['nama_lengkap']) ?>" required>
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
                                value="<?= esc($user['email']) ?>" required>
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
                                value="<?= esc($user['no_hp']) ?>">
                        </div>
                        <div class="invalid-feedback" id="error-no_hp"></div>
                    </div>

                    <!-- Password (Optional) -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password Baru
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-lock-line"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                            <span class="input-group-text cursor-pointer" onclick="togglePassword()">
                                <i class="ri-eye-line" id="toggleIcon"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback" id="error-password"></div>
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="L"
                                    <?= $user['jenis_kelamin'] === 'L' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="laki">
                                    Laki-laki
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P"
                                    <?= $user['jenis_kelamin'] === 'P' ? 'checked' : '' ?>>
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
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                value="<?= esc($user['tanggal_lahir']) ?>">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="alamat" class="form-label">Alamat</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-map-pin-line"></i></span>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= esc($user['alamat']) ?></textarea>
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
                        <div class="col-md-6 mb-4">
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
                    <div class="mb-4">
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

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-toggle-line"></i></span>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                                <option value="archived" <?= $user['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>
                        <small class="text-muted">Status menentukan apakah user dapat login</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Update
                        </button>
                        <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i> Batal
                        </a>
                        <a href="<?= base_url('user/detail/' . $user['id_user']) ?>" class="btn btn-outline-info ms-auto">
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
                    <i class="ri-information-line me-1"></i> Informasi User
                </h6>
                <div class="mb-3">
                    <small class="text-muted d-block">ID User</small>
                    <strong>#<?= $user['id_user'] ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">NIK</small>
                    <span class="badge bg-label-dark"><?= $user['nik'] ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Dibuat</small>
                    <strong><?= date('d M Y H:i', strtotime($user['created_at'])) ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Terakhir Update</small>
                    <strong><?= date('d M Y H:i', strtotime($user['updated_at'])) ?></strong>
                </div>
                <div>
                    <small class="text-muted d-block">Last Login</small>
                    <strong><?= $user['last_login'] ? date('d M Y H:i', strtotime($user['last_login'])) : 'Belum pernah' ?></strong>
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
                        <small>NIK tidak dapat diubah setelah dibuat</small>
                    </li>
                    <li class="mb-2">
                        <small>Hati-hati mengubah role user</small>
                    </li>
                    <li class="mb-2">
                        <small>Email harus tetap unik</small>
                    </li>
                    <li>
                        <small>Password hanya diubah jika diisi</small>
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

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Confirmation
        const result = await Swal.fire({
            title: 'Update User?',
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

            const response = await fetch('<?= base_url('user/update/' . $user['id_user']) ?>', {
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
                submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Update';
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