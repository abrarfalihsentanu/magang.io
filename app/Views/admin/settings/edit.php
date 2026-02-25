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
                    <a href="<?= base_url('settings') ?>">Pengaturan Sistem</a>
                </li>
                <li class="breadcrumb-item active">Edit Setting</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-pencil-line me-2"></i>Edit Setting
        </h4>
        <p class="mb-0 text-muted">Perbarui konfigurasi setting sistem</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Setting</h5>
                <span class="badge bg-label-<?= $setting['is_editable'] ? 'success' : 'secondary' ?>">
                    <?= $setting['is_editable'] ? 'Editable' : 'Locked' ?>
                </span>
            </div>
            <div class="card-body">
                <?php if (!$setting['is_editable']): ?>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="ri-alert-line me-2"></i>
                        <div>
                            Setting ini dikunci dan tidak dapat diubah atau dihapus.
                        </div>
                    </div>
                <?php endif; ?>

                <form id="settingForm">
                    <?= csrf_field() ?>

                    <!-- Setting Key -->
                    <div class="mb-4">
                        <label for="setting_key" class="form-label">
                            Setting Key <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-key-line"></i></span>
                            <input type="text" class="form-control" id="setting_key" name="setting_key"
                                value="<?= esc($setting['setting_key']) ?>" required>
                        </div>
                        <div class="invalid-feedback" id="error-setting_key"></div>
                        <small class="text-muted">Key unik untuk setting (gunakan underscore untuk spasi)</small>
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label for="category" class="form-label">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-folder-2-line"></i></span>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="general" <?= $setting['category'] === 'general' ? 'selected' : '' ?>>General</option>
                                <option value="attendance" <?= $setting['category'] === 'attendance' ? 'selected' : '' ?>>Attendance</option>
                                <option value="allowance" <?= $setting['category'] === 'allowance' ? 'selected' : '' ?>>Allowance</option>
                                <option value="kpi" <?= $setting['category'] === 'kpi' ? 'selected' : '' ?>>KPI</option>
                                <option value="notification" <?= $setting['category'] === 'notification' ? 'selected' : '' ?>>Notification</option>
                                <option value="system" <?= $setting['category'] === 'system' ? 'selected' : '' ?>>System</option>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-category"></div>
                        <small class="text-muted">Kelompokkan setting berdasarkan kategori</small>
                    </div>

                    <!-- Setting Type -->
                    <div class="mb-4">
                        <label for="setting_type" class="form-label">
                            Tipe Data <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-code-s-slash-line"></i></span>
                            <select class="form-select" id="setting_type" name="setting_type" required>
                                <option value="">Pilih Tipe Data</option>
                                <option value="string" <?= $setting['setting_type'] === 'string' ? 'selected' : '' ?>>String (Text)</option>
                                <option value="number" <?= $setting['setting_type'] === 'number' ? 'selected' : '' ?>>Number (Angka)</option>
                                <option value="boolean" <?= $setting['setting_type'] === 'boolean' ? 'selected' : '' ?>>Boolean (True/False)</option>
                                <option value="json" <?= $setting['setting_type'] === 'json' ? 'selected' : '' ?>>JSON (Object/Array)</option>
                                <option value="date" <?= $setting['setting_type'] === 'date' ? 'selected' : '' ?>>Date (Tanggal)</option>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-setting_type"></div>
                        <small class="text-muted">Tentukan tipe data yang sesuai</small>
                    </div>

                    <!-- Setting Value -->
                    <div class="mb-4">
                        <label for="setting_value" class="form-label">
                            Nilai Setting <span class="text-danger">*</span>
                        </label>
                        
                        <!-- String/Number/Date Input -->
                        <div class="input-group input-group-merge" id="input-text">
                            <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                            <input type="text" class="form-control" id="setting_value" name="setting_value"
                                value="<?= esc($setting['setting_value']) ?>" required>
                        </div>

                        <!-- Boolean Select -->
                        <div class="input-group input-group-merge" id="input-boolean" style="display: none;">
                            <span class="input-group-text"><i class="ri-toggle-line"></i></span>
                            <select class="form-select" id="setting_value_boolean">
                                <option value="true" <?= $setting['setting_value'] === 'true' ? 'selected' : '' ?>>True</option>
                                <option value="false" <?= $setting['setting_value'] === 'false' ? 'selected' : '' ?>>False</option>
                            </select>
                        </div>

                        <!-- JSON Textarea -->
                        <div id="input-json" style="display: none;">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri-braces-line"></i></span>
                                <textarea class="form-control" id="setting_value_json" rows="6"><?= esc($setting['setting_value']) ?></textarea>
                            </div>
                        </div>

                        <div class="invalid-feedback" id="error-setting_value"></div>
                        <small class="text-muted" id="value-hint">Masukkan nilai untuk setting ini</small>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Jelaskan fungsi dan penggunaan setting ini..."><?= esc($setting['description']) ?></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-description"></div>
                        <small class="text-muted">Deskripsi membantu memahami penggunaan setting</small>
                    </div>

                    <!-- Is Editable -->
                    <div class="mb-4">
                        <label class="form-label">Status Editable</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_editable" name="is_editable" 
                                value="1" <?= $setting['is_editable'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_editable">
                                Editable (Setting dapat diubah atau dihapus)
                            </label>
                        </div>
                        <small class="text-muted text-danger">⚠️ Jika dinonaktifkan, setting tidak bisa diubah lagi</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Update
                        </button>
                        <a href="<?= base_url('settings') ?>" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i> Batal
                        </a>
                        <a href="<?= base_url('settings/detail/' . $setting['id_setting']) ?>" class="btn btn-outline-info ms-auto">
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
                    <i class="ri-information-line me-1"></i> Informasi Setting
                </h6>
                <div class="mb-3">
                    <small class="text-muted d-block">ID Setting</small>
                    <strong>#<?= $setting['id_setting'] ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Kategori Saat Ini</small>
                    <span class="badge bg-label-primary text-capitalize"><?= esc($setting['category']) ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Tipe Data Saat Ini</small>
                    <span class="badge bg-label-info text-capitalize"><?= esc($setting['setting_type']) ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Terakhir Update</small>
                    <strong><?= date('d M Y H:i', strtotime($setting['updated_at'])) ?></strong>
                </div>
                <?php if ($setting['updated_by']): ?>
                    <div>
                        <small class="text-muted d-block">Diupdate Oleh</small>
                        <strong>User #<?= $setting['updated_by'] ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-3 bg-info-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-lightbulb-line me-1"></i> Tips
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Gunakan key yang deskriptif dan mudah diingat</small>
                    </li>
                    <li class="mb-2">
                        <small>Pastikan tipe data sesuai dengan nilai</small>
                    </li>
                    <li class="mb-2">
                        <small>Validasi JSON sebelum menyimpan</small>
                    </li>
                    <li>
                        <small>Setting yang dikunci tidak bisa diubah lagi</small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3 bg-warning-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-alert-line me-1"></i> Peringatan
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Perubahan setting dapat mempengaruhi sistem</small>
                    </li>
                    <li class="mb-2">
                        <small>Pastikan nilai sesuai dengan tipe data</small>
                    </li>
                    <li>
                        <small>Hati-hati saat menonaktifkan editable</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 & Form Handler -->
<script>
    const form = document.getElementById('settingForm');
    const submitBtn = document.getElementById('submitBtn');
    const settingTypeSelect = document.getElementById('setting_type');
    const settingKeyInput = document.getElementById('setting_key');
    
    // Input elements
    const inputText = document.getElementById('input-text');
    const inputBoolean = document.getElementById('input-boolean');
    const inputJson = document.getElementById('input-json');
    const settingValueInput = document.getElementById('setting_value');
    const settingValueBoolean = document.getElementById('setting_value_boolean');
    const settingValueJson = document.getElementById('setting_value_json');
    const valueHint = document.getElementById('value-hint');

    // Auto format setting_key
    settingKeyInput.addEventListener('input', function(e) {
        this.value = this.value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
    });

    // Initialize input type display based on current type
    function updateInputDisplay() {
        const type = settingTypeSelect.value;
        
        // Hide all inputs
        inputText.style.display = 'none';
        inputBoolean.style.display = 'none';
        inputJson.style.display = 'none';
        
        // Show appropriate input and update hint
        switch(type) {
            case 'string':
                inputText.style.display = 'flex';
                settingValueInput.type = 'text';
                valueHint.textContent = 'Masukkan teks untuk setting ini';
                break;
            case 'number':
                inputText.style.display = 'flex';
                settingValueInput.type = 'number';
                valueHint.textContent = 'Masukkan angka untuk setting ini';
                break;
            case 'boolean':
                inputBoolean.style.display = 'flex';
                valueHint.textContent = 'Pilih true atau false';
                break;
            case 'json':
                inputJson.style.display = 'block';
                valueHint.textContent = 'Masukkan JSON yang valid';
                break;
            case 'date':
                inputText.style.display = 'flex';
                settingValueInput.type = 'date';
                valueHint.textContent = 'Pilih tanggal';
                break;
        }
    }

    // Initialize on page load
    updateInputDisplay();

    // Change input based on type
    settingTypeSelect.addEventListener('change', updateInputDisplay);

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Confirmation
        const result = await Swal.fire({
            title: 'Update Setting?',
            text: 'Pastikan data yang diubah sudah benar',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        // Get value based on type
        const type = settingTypeSelect.value;
        let value;
        
        switch(type) {
            case 'boolean':
                value = settingValueBoolean.value;
                break;
            case 'json':
                value = settingValueJson.value;
                // Validate JSON
                try {
                    JSON.parse(value);
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'JSON Tidak Valid',
                        text: 'Format JSON yang dimasukkan tidak valid',
                        confirmButtonColor: '#696cff'
                    });
                    return;
                }
                break;
            default:
                value = settingValueInput.value;
        }

        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengupdate...';

        try {
            const formData = new FormData(form);
            // Override value with the correct one
            formData.set('setting_value', value);

            const response = await fetch('<?= base_url('settings/update/' . $setting['id_setting']) ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

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