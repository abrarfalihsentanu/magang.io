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
                    <a href="<?= base_url('activity/my') ?>">Aktivitas Harian</a>
                </li>
                <li class="breadcrumb-item active">Tambah Aktivitas</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-add-circle-line me-2"></i>Tambah Aktivitas Harian
        </h4>
        <p class="mb-0 text-muted">Catat aktivitas harian Anda</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <!-- Main Form - 8 columns -->
    <div class="col-12 col-lg-8">
        <form id="activityForm" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Tambah Aktivitas</h5>
                </div>
                <div class="card-body">

                    <!-- Tanggal -->
                    <div class="mb-4">
                        <label for="tanggal" class="form-label">
                            Tanggal <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="<?= date('Y-m-d') ?>"
                                max="<?= date('Y-m-d') ?>"
                                min="<?= date('Y-m-d', strtotime('-3 days')) ?>" required>
                        </div>
                        <div class="invalid-feedback" id="error-tanggal"></div>
                        <small class="text-muted">Maksimal 3 hari ke belakang</small>
                    </div>

                    <!-- Jam Mulai & Selesai -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="jam_mulai" class="form-label">
                                Jam Mulai <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri-time-line"></i></span>
                                <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                            </div>
                            <div class="invalid-feedback" id="error-jam_mulai"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="jam_selesai" class="form-label">
                                Jam Selesai <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri-time-line"></i></span>
                                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                            </div>
                            <div class="invalid-feedback" id="error-jam_selesai"></div>
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="kategori" class="form-label">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-folder-line"></i></span>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="learning">Learning - Pembelajaran/Training</option>
                                <option value="task">Task - Tugas/Pekerjaan</option>
                                <option value="meeting">Meeting - Rapat/Diskusi</option>
                                <option value="training">Training - Pelatihan</option>
                                <option value="other">Other - Lainnya</option>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-kategori"></div>
                    </div>

                    <!-- Judul Aktivitas -->
                    <div class="mb-4">
                        <label for="judul_aktivitas" class="form-label">
                            Judul Aktivitas <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                            <input type="text" class="form-control" id="judul_aktivitas" name="judul_aktivitas"
                                placeholder="Contoh: Membuat dokumentasi API" maxlength="200" required>
                        </div>
                        <div class="invalid-feedback" id="error-judul_aktivitas"></div>
                        <small class="text-muted"><span id="judulCount">0</span>/200 karakter (min. 5 karakter)</small>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">
                            Deskripsi Detail <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-align-left"></i></span>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"
                                placeholder="Jelaskan secara detail apa yang Anda kerjakan, hasil yang dicapai, dan kendala yang dihadapi..." required></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-deskripsi"></div>
                        <small class="text-muted"><span id="deskripsiCount">0</span> karakter (min. 50 karakter)</small>
                    </div>

                    <!-- Attachment -->
                    <div class="mb-4">
                        <label for="attachment" class="form-label">Lampiran</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-attachment-2"></i></span>
                            <input type="file" class="form-control" id="attachment" name="attachment"
                                accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        <div class="invalid-feedback" id="error-attachment"></div>
                        <small class="text-muted">Format: JPG, PNG, PDF (Maks. 5MB)</small>

                        <!-- Preview -->
                        <div id="filePreview" class="mt-2" style="display: none;">
                            <div class="alert alert-secondary d-flex align-items-center py-2">
                                <i class="ri-file-line me-2"></i>
                                <div class="flex-grow-1">
                                    <strong id="fileName"></strong>
                                    <small class="d-block text-muted" id="fileSize"></small>
                                </div>
                                <button type="button" class="btn-close" onclick="removeFile()"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="ri-send-plane-line me-1"></i> Submit untuk Approval
                        </button>
                        <button type="submit" name="action" value="draft" class="btn btn-outline-warning" id="btnDraft">
                            <i class="ri-draft-line me-1"></i> Simpan Draft
                        </button>
                        <a href="<?= base_url('activity/my') ?>" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar Helper Cards - 4 columns -->
    <div class="col-12 col-lg-4">
        <!-- Info Card -->
        <div class="card bg-primary-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-information-line me-1"></i> Informasi
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Input aktivitas maksimal <strong>3 hari</strong> ke belakang</small>
                    </li>
                    <li class="mb-2">
                        <small>Deskripsi minimal <strong>50 karakter</strong></small>
                    </li>
                    <li class="mb-2">
                        <small>Setelah submit, aktivitas <strong>tidak dapat</strong> diedit</small>
                    </li>
                    <li>
                        <small>Draft dapat diedit kapan saja</small>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Kategori Card -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-folder-line me-1"></i> Panduan Kategori
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <span class="badge bg-label-info">Learning</span>
                        <small class="d-block text-muted">Pembelajaran mandiri, riset</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-label-primary">Task</span>
                        <small class="d-block text-muted">Tugas dari mentor/project</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-label-warning">Meeting</span>
                        <small class="d-block text-muted">Rapat, diskusi, presentasi</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-label-success">Training</span>
                        <small class="d-block text-muted">Pelatihan formal/workshop</small>
                    </li>
                    <li>
                        <span class="badge bg-label-secondary">Other</span>
                        <small class="d-block text-muted">Aktivitas lainnya</small>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Warning Card -->
        <div class="card mt-3 bg-warning-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-alert-line me-1"></i> Perhatian
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Pastikan deskripsi jelas dan detail</small>
                    </li>
                    <li class="mb-2">
                        <small>Lampiran bersifat opsional</small>
                    </li>
                    <li>
                        <small>Aktivitas akan direview oleh mentor</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 & Form Handler -->
<script>
    // Character Counter
    document.getElementById('judul_aktivitas').addEventListener('input', function() {
        document.getElementById('judulCount').textContent = this.value.length;
    });

    document.getElementById('deskripsi').addEventListener('input', function() {
        const count = this.value.length;
        const counter = document.getElementById('deskripsiCount');
        counter.textContent = count;

        if (count < 50) {
            counter.classList.add('text-danger');
            counter.classList.remove('text-success');
        } else {
            counter.classList.add('text-success');
            counter.classList.remove('text-danger');
        }
    });

    // File Preview
    document.getElementById('attachment').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validate size
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 5MB',
                    confirmButtonColor: '#696cff'
                });
                this.value = '';
                return;
            }

            // Show preview
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            document.getElementById('filePreview').style.display = 'block';
        }
    });

    function removeFile() {
        document.getElementById('attachment').value = '';
        document.getElementById('filePreview').style.display = 'none';
    }

    // Form submission
    document.getElementById('activityForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Get action
        const action = e.submitter.value;

        // Validate
        if (!validateForm()) {
            return;
        }

        // Confirmation
        const message = action === 'draft'
            ? 'Aktivitas akan disimpan sebagai draft. Anda dapat mengeditnya nanti.'
            : 'Aktivitas akan disubmit untuk approval. Anda tidak dapat mengedit setelah submit.';

        const result = await Swal.fire({
            title: action === 'draft' ? 'Simpan Draft?' : 'Submit Aktivitas?',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: action === 'draft' ? '#ff9f43' : '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: action === 'draft' ? 'Ya, Simpan Draft' : 'Ya, Submit!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });

        if (!result.isConfirmed) return;

        // Show loading
        const submitBtn = document.getElementById('btnSubmit');
        const draftBtn = document.getElementById('btnDraft');
        submitBtn.disabled = true;
        draftBtn.disabled = true;

        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const formData = new FormData(this);
            formData.append('status_approval', action === 'draft' ? 'draft' : 'submitted');

            const response = await fetch('<?= base_url('activity/store') ?>', {
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
                    window.location.href = '<?= base_url('activity/my') ?>';
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
                    text: data.message || 'Terjadi kesalahan',
                    confirmButtonColor: '#696cff'
                });

                submitBtn.disabled = false;
                draftBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem',
                confirmButtonColor: '#696cff'
            });

            submitBtn.disabled = false;
            draftBtn.disabled = false;
        }
    });

    function validateForm() {
        let isValid = true;

        // Validate tanggal
        const tanggal = document.getElementById('tanggal');
        if (!tanggal.value) {
            showError('tanggal', 'Tanggal wajib diisi');
            isValid = false;
        }

        // Validate jam
        const jamMulai = document.getElementById('jam_mulai').value;
        const jamSelesai = document.getElementById('jam_selesai').value;

        if (!jamMulai) {
            showError('jam_mulai', 'Jam mulai wajib diisi');
            isValid = false;
        }

        if (!jamSelesai) {
            showError('jam_selesai', 'Jam selesai wajib diisi');
            isValid = false;
        }

        if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
            showError('jam_selesai', 'Jam selesai harus lebih besar dari jam mulai');
            isValid = false;
        }

        // Validate kategori
        const kategori = document.getElementById('kategori');
        if (!kategori.value) {
            showError('kategori', 'Kategori wajib dipilih');
            isValid = false;
        }

        // Validate judul
        const judul = document.getElementById('judul_aktivitas');
        if (!judul.value) {
            showError('judul_aktivitas', 'Judul aktivitas wajib diisi');
            isValid = false;
        } else if (judul.value.length < 5) {
            showError('judul_aktivitas', 'Judul minimal 5 karakter');
            isValid = false;
        }

        // Validate deskripsi
        const deskripsi = document.getElementById('deskripsi');
        if (!deskripsi.value) {
            showError('deskripsi', 'Deskripsi wajib diisi');
            isValid = false;
        } else if (deskripsi.value.length < 50) {
            showError('deskripsi', 'Deskripsi minimal 50 karakter');
            isValid = false;
        }

        return isValid;
    }

    function showError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const errorDiv = document.getElementById(`error-${fieldId}`);
        if (input) input.classList.add('is-invalid');
        if (errorDiv) errorDiv.textContent = message;
    }

    // Remove invalid class on input
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>

<?= $this->endSection() ?>
