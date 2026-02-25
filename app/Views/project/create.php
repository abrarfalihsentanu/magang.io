<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('project/my') ?>">Weekly Project</a></li>
                <li class="breadcrumb-item active">Submit Project</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-add-circle-line me-2"></i>Submit Weekly Project
        </h4>
        <p class="mb-0 text-muted">Submit project mingguan Anda</p>
    </div>
</div>

<!-- Week Info Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary" role="alert">
            <div class="d-flex align-items-center">
                <i class="ri-calendar-check-line me-2 fs-4"></i>
                <div>
                    <strong><?= $week_info['week_label'] ?></strong><br>
                    <small>Periode: <?= $week_info['periode_label'] ?> | Deadline: Jumat, <?= date('d M Y', strtotime($week_info['periode_mulai'] . ' +4 days')) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <!-- Main Form - 8 columns -->
    <div class="col-12 col-lg-8">
        <form id="projectForm" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Submit Project</h5>
                </div>
                <div class="card-body">

                    <!-- Week & Periode (Read Only) -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Week</label>
                            <input type="text" class="form-control" value="<?= $week_info['week_label'] ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Periode</label>
                            <input type="text" class="form-control" value="<?= $week_info['periode_label'] ?>" readonly>
                        </div>
                    </div>

                    <!-- Judul Project -->
                    <div class="mb-4">
                        <label for="judul_project" class="form-label">
                            Judul Project <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                            <input type="text" class="form-control" id="judul_project" name="judul_project"
                                placeholder="Contoh: Implementasi Fitur Dashboard Analytics" maxlength="200" required>
                        </div>
                        <div class="invalid-feedback" id="error-judul_project"></div>
                        <small class="text-muted"><span id="judulCount">0</span>/200 karakter (min. 10 karakter)</small>
                    </div>

                    <!-- Tipe Project -->
                    <div class="mb-4">
                        <label for="tipe_project" class="form-label">
                            Tipe Project <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-folder-line"></i></span>
                            <select class="form-select" id="tipe_project" name="tipe_project" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="assigned">Assigned - Ditugaskan oleh Mentor</option>
                                <option value="inisiatif">Inisiatif - Proyek Sendiri</option>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-tipe_project"></div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">
                            Deskripsi Project <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-align-left"></i></span>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"
                                placeholder="Jelaskan secara detail tentang project yang Anda kerjakan..." required></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-deskripsi"></div>
                        <small class="text-muted"><span id="deskripsiCount">0</span> karakter (min. 100 karakter)</small>
                    </div>

                    <!-- Progress -->
                    <div class="mb-4">
                        <label for="progress" class="form-label">
                            Progress Project <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="range" class="form-range flex-grow-1" id="progress" name="progress"
                                min="0" max="100" value="0" step="5">
                            <span class="badge bg-primary" id="progressBadge" style="min-width: 60px;">0%</span>
                        </div>
                        <div class="invalid-feedback" id="error-progress"></div>
                        <small class="text-muted">Geser slider untuk menentukan progress (0-100%)</small>
                    </div>

                    <!-- Deliverables -->
                    <div class="mb-4">
                        <label for="deliverables" class="form-label">
                            Deliverables / Output <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-gift-line"></i></span>
                            <textarea class="form-control" id="deliverables" name="deliverables" rows="3"
                                placeholder="Apa hasil/output dari project ini? (dashboard, API, dokumentasi, dll)" required></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-deliverables"></div>
                        <small class="text-muted"><span id="delivCount">0</span> karakter (min. 20 karakter)</small>
                    </div>

                    <!-- Attachment -->
                    <div class="mb-4">
                        <label for="attachment" class="form-label">
                            Attachment <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-attachment-2"></i></span>
                            <input type="file" class="form-control" id="attachment" name="attachment"
                                accept=".jpg,.jpeg,.png,.pdf,.zip" required>
                        </div>
                        <div class="invalid-feedback" id="error-attachment"></div>
                        <small class="text-muted">Format: JPG, PNG, PDF, ZIP (Maks. 10MB) - <strong class="text-danger">WAJIB</strong></small>

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

                    <!-- Self Rating -->
                    <div class="mb-4">
                        <label for="self_rating" class="form-label">
                            Self Rating <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="range" class="form-range flex-grow-1" id="self_rating" name="self_rating"
                                min="1.0" max="5.0" value="3.0" step="0.1">
                            <span class="badge bg-warning" id="ratingBadge" style="min-width: 80px;">
                                <i class="ri-star-fill"></i> 3.0
                            </span>
                        </div>
                        <div class="invalid-feedback" id="error-self_rating"></div>
                        <small class="text-muted">Beri penilaian untuk project Anda (1.0 - 5.0)</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="submitted" class="btn btn-primary" id="btnSubmit">
                            <i class="ri-send-plane-line me-1"></i> Submit untuk Assessment
                        </button>
                        <button type="submit" name="action" value="draft" class="btn btn-outline-warning" id="btnDraft">
                            <i class="ri-draft-line me-1"></i> Simpan Draft
                        </button>
                        <a href="<?= base_url('project/my') ?>" class="btn btn-outline-secondary">
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
        <div class="card bg-primary-subtle mb-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-information-line me-1"></i> Informasi
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Submit maksimal hari <strong>Jumat</strong></small>
                    </li>
                    <li class="mb-2">
                        <small>Deskripsi minimal <strong>100 karakter</strong></small>
                    </li>
                    <li class="mb-2">
                        <small>Attachment <strong>wajib diupload</strong></small>
                    </li>
                    <li>
                        <small>1 project per minggu</small>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Rating Guide -->
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-star-line me-1"></i> Panduan Rating
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <span class="badge bg-danger">1.0-1.9</span>
                        <small class="d-block text-muted">Poor - Tidak memenuhi standar</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-warning">2.0-2.9</span>
                        <small class="d-block text-muted">Below Average - Perlu perbaikan</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-info">3.0-3.9</span>
                        <small class="d-block text-muted">Average - Memenuhi ekspektasi</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-primary">4.0-4.5</span>
                        <small class="d-block text-muted">Good - Melebihi ekspektasi</small>
                    </li>
                    <li>
                        <span class="badge bg-success">4.6-5.0</span>
                        <small class="d-block text-muted">Excellent - Outstanding</small>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card bg-warning-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-lightbulb-line me-1"></i> Tips
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Jelaskan project secara detail</small>
                    </li>
                    <li class="mb-2">
                        <small>Cantumkan challenge yang dihadapi</small>
                    </li>
                    <li class="mb-2">
                        <small>Upload screenshot atau dokumentasi</small>
                    </li>
                    <li>
                        <small>Berikan rating yang objektif</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Character Counter
    document.getElementById('judul_project').addEventListener('input', function() {
        document.getElementById('judulCount').textContent = this.value.length;
    });

    document.getElementById('deskripsi').addEventListener('input', function() {
        const count = this.value.length;
        const counter = document.getElementById('deskripsiCount');
        counter.textContent = count;

        if (count < 100) {
            counter.classList.add('text-danger');
            counter.classList.remove('text-success');
        } else {
            counter.classList.add('text-success');
            counter.classList.remove('text-danger');
        }
    });

    document.getElementById('deliverables').addEventListener('input', function() {
        document.getElementById('delivCount').textContent = this.value.length;
    });

    // Progress Slider
    document.getElementById('progress').addEventListener('input', function() {
        document.getElementById('progressBadge').textContent = this.value + '%';
    });

    // Rating Slider
    document.getElementById('self_rating').addEventListener('input', function() {
        document.getElementById('ratingBadge').innerHTML = `<i class="ri-star-fill"></i> ${parseFloat(this.value).toFixed(1)}`;
    });

    // File Preview
    document.getElementById('attachment').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 10MB',
                    confirmButtonColor: '#696cff'
                });
                this.value = '';
                return;
            }

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
    document.getElementById('projectForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        const action = e.submitter.value;

        if (!validateForm()) {
            return;
        }

        const message = action === 'draft' ?
            'Project akan disimpan sebagai draft.' :
            'Project akan disubmit untuk assessment. Pastikan semua data sudah benar.';

        const result = await Swal.fire({
            title: action === 'draft' ? 'Simpan Draft?' : 'Submit Project?',
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
            formData.append('status_submission', action === 'draft' ? 'draft' : 'submitted');

            const response = await fetch('<?= base_url('project/store') ?>', {
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
                    window.location.href = '<?= base_url('project/my') ?>';
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

        const judul = document.getElementById('judul_project');
        if (!judul.value || judul.value.length < 10) {
            showError('judul_project', 'Judul minimal 10 karakter');
            isValid = false;
        }

        const tipe = document.getElementById('tipe_project');
        if (!tipe.value) {
            showError('tipe_project', 'Tipe project wajib dipilih');
            isValid = false;
        }

        const deskripsi = document.getElementById('deskripsi');
        if (!deskripsi.value || deskripsi.value.length < 100) {
            showError('deskripsi', 'Deskripsi minimal 100 karakter');
            isValid = false;
        }

        const deliverables = document.getElementById('deliverables');
        if (!deliverables.value || deliverables.value.length < 20) {
            showError('deliverables', 'Deliverables minimal 20 karakter');
            isValid = false;
        }

        const attachment = document.getElementById('attachment');
        if (!attachment.files.length) {
            showError('attachment', 'Attachment wajib diupload');
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

    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>

<?= $this->endSection() ?>