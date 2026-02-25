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
                <li class="breadcrumb-item active">Tambah Setting</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-add-line me-2"></i>Tambah Setting Baru
        </h4>
        <p class="mb-0 text-muted">Isi form di bawah untuk menambahkan setting sistem baru</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah Setting</h5>
            </div>
            <div class="card-body">
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
                                placeholder="Contoh: app_name atau max_upload_size" required>
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
                                <option value="general">General</option>
                                <option value="attendance">Attendance</option>
                                <option value="allowance">Allowance</option>
                                <option value="kpi">KPI</option>
                                <option value="notification">Notification</option>
                                <option value="system">System</option>
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
                                <option value="string">String (Text)</option>
                                <option value="number">Number (Angka)</option>
                                <option value="boolean">Boolean (True/False)</option>
                                <option value="json">JSON (Object/Array)</option>
                                <option value="date">Date (Tanggal)</option>
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

                        <!-- String/Number/Date Input (Default) -->
                        <div class="input-group input-group-merge" id="input-text">
                            <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                            <input type="text" class="form-control" id="setting_value" name="setting_value"
                                placeholder="Masukkan nilai setting" required>
                        </div>

                        <!-- Boolean Select (Hidden by default) -->
                        <div class="input-group input-group-merge" id="input-boolean" style="display: none;">
                            <span class="input-group-text"><i class="ri-toggle-line"></i></span>
                            <select class="form-select" id="setting_value_boolean">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>

                        <!-- JSON Textarea (Hidden by default) -->
                        <div id="input-json" style="display: none;">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri-braces-line"></i></span>
                                <textarea class="form-control" id="setting_value_json" rows="6"
                                    placeholder='{"key": "value"}'></textarea>
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
                                placeholder="Jelaskan fungsi dan penggunaan setting ini..."></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-description"></div>
                        <small class="text-muted">Deskripsi membantu memahami penggunaan setting</small>
                    </div>

                    <!-- Is Editable -->
                    <div class="mb-4">
                        <label class="form-label">Status Editable</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_editable" name="is_editable" value="1" checked>
                            <label class="form-check-label" for="is_editable">
                                Editable (Setting dapat diubah atau dihapus)
                            </label>
                        </div>
                        <small class="text-muted">Setting yang tidak editable akan terkunci selamanya</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('settings') ?>" class="btn btn-outline-secondary">
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
                        <small>Setting key harus unik dan deskriptif</small>
                    </li>
                    <li class="mb-2">
                        <small>Gunakan underscore (_) untuk pemisah kata</small>
                    </li>
                    <li class="mb-2">
                        <small>Pilih tipe data yang sesuai dengan nilai</small>
                    </li>
                    <li class="mb-2">
                        <small>Setting locked tidak bisa diubah/dihapus</small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-lightbulb-line me-1"></i> Contoh Setting
                </h6>
                <div class="mb-3">
                    <span class="badge bg-label-primary mb-1">general</span>
                    <small class="d-block text-muted">app_name, app_version</small>
                </div>
                <div class="mb-3">
                    <span class="badge bg-label-success mb-1">attendance</span>
                    <small class="d-block text-muted">max_late_minutes, office_latitude</small>
                </div>
                <div class="mb-3">
                    <span class="badge bg-label-info mb-1">allowance</span>
                    <small class="d-block text-muted">rate_per_day, payment_schedule</small>
                </div>
                <div>
                    <span class="badge bg-label-warning mb-1">kpi</span>
                    <small class="d-block text-muted">attendance_weight, project_weight</small>
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
                        <small>Setting yang tidak editable tidak bisa diubah</small>
                    </li>
                    <li>
                        <small>Pastikan nilai JSON valid sebelum menyimpan</small>
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

    // Change input based on type
    settingTypeSelect.addEventListener('change', function() {
        const type = this.value;

        // Hide all inputs
        inputText.style.display = 'none';
        inputBoolean.style.display = 'none';
        inputJson.style.display = 'none';

        // Show appropriate input and update hint
        switch (type) {
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
                valueHint.textContent = 'Masukkan JSON yang valid (contoh: {"key": "value"})';
                break;
            case 'date':
                inputText.style.display = 'flex';
                settingValueInput.type = 'date';
                valueHint.textContent = 'Pilih tanggal';
                break;
        }
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Get value based on type
        const type = settingTypeSelect.value;
        let value;

        switch (type) {
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
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        try {
            const formData = new FormData(form);
            // Override value with the correct one
            formData.set('setting_value', value);

            const response = await fetch('<?= base_url('settings/store') ?>', {
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