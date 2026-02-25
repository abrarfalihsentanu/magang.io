<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('kpi/indicators') ?>">KPI Indicators</a></li>
                <li class="breadcrumb-item active">Tambah Indicator</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-add-line me-2"></i>Tambah KPI Indicator
        </h4>
        <p class="mb-0 text-muted">Buat indikator penilaian kinerja baru</p>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <!-- Main Form - 8 columns -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah Indicator</h5>
            </div>
            <div class="card-body">
                <form id="indicatorForm">
                    <?= csrf_field() ?>

                    <!-- Nama Indicator -->
                    <div class="mb-4">
                        <label for="nama_indicator" class="form-label">
                            Nama Indicator <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-text"></i></span>
                            <input type="text" class="form-control" id="nama_indicator" name="nama_indicator"
                                placeholder="Contoh: Persentase Kehadiran" maxlength="100" required>
                        </div>
                        <div class="invalid-feedback" id="error-nama_indicator"></div>
                        <small class="text-muted">
                            <span id="namaCount">0</span>/100 karakter (min. 5 karakter)
                        </small>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="kategori" class="form-label">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-folder-line"></i></span>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="kehadiran">Kehadiran</option>
                                <option value="aktivitas">Aktivitas</option>
                                <option value="project">Project</option>
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error-kategori"></div>
                    </div>

                    <!-- Bobot -->
                    <div class="mb-4">
                        <label for="bobot" class="form-label">
                            Bobot (%) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-percent-line"></i></span>
                            <input type="number" class="form-control" id="bobot" name="bobot"
                                min="0" max="100" step="0.01" placeholder="0.00" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="invalid-feedback" id="error-bobot"></div>
                        <small class="text-muted">
                            Sisa bobot tersedia: <strong id="sisaBobot"><?= $validation['remaining'] ?>%</strong>
                        </small>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">
                            Deskripsi <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-align-left"></i></span>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                placeholder="Jelaskan indikator ini secara detail..." required></textarea>
                        </div>
                        <div class="invalid-feedback" id="error-deskripsi"></div>
                        <small class="text-muted">
                            <span id="deskripsiCount">0</span> karakter (min. 10 karakter)
                        </small>
                    </div>

                    <!-- Tipe Perhitungan -->
                    <div class="mb-4">
                        <label class="form-label">
                            Tipe Perhitungan <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_auto_calculate"
                                    id="autoCalc" value="1" checked>
                                <label class="form-check-label" for="autoCalc">
                                    <i class="ri-flashlight-line text-info"></i> Auto Calculate
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_auto_calculate"
                                    id="manualCalc" value="0">
                                <label class="form-check-label" for="manualCalc">
                                    <i class="ri-edit-line text-warning"></i> Manual Assessment
                                </label>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">
                            Auto: Dihitung sistem otomatis | Manual: Perlu penilaian mentor
                        </small>
                    </div>

                    <!-- Formula (Optional) -->
                    <div class="mb-4" id="formulaField">
                        <label for="formula" class="form-label">
                            Formula Perhitungan
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ri-code-line"></i></span>
                            <textarea class="form-control" id="formula" name="formula" rows="2"
                                placeholder="Contoh: (COUNT(status=hadir) / total_days) × 100"></textarea>
                        </div>
                        <small class="text-muted">
                            Opsional: Formula atau cara perhitungan indikator
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-save-line me-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('kpi/indicators') ?>" class="btn btn-outline-secondary">
                            <i class="ri-close-line me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info - 4 columns -->
    <div class="col-12 col-lg-4">
        <!-- Bobot Status -->
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="ri-percent-line me-1"></i> Status Bobot
                </h6>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Total Bobot Saat Ini</small>
                    <h4 class="mb-0"><?= $validation['total'] ?>%</h4>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Sisa Tersedia</small>
                    <h4 class="mb-0 text-success"><?= $validation['remaining'] ?>%</h4>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar" role="progressbar"
                        style="width: <?= $validation['total'] ?>%"
                        aria-valuenow="<?= $validation['total'] ?>"
                        aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    Total harus = 100%
                </small>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card bg-primary-subtle mb-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-information-line me-1"></i> Informasi
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2">
                        <small>Nama minimal 5 karakter</small>
                    </li>
                    <li class="mb-2">
                        <small>Total bobot semua indicator aktif harus = 100%</small>
                    </li>
                    <li class="mb-2">
                        <small>Auto Calculate: Dihitung sistem otomatis</small>
                    </li>
                    <li class="mb-2">
                        <small>Manual: Perlu penilaian subjektif mentor</small>
                    </li>
                    <li>
                        <small>Formula opsional untuk dokumentasi</small>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Kategori Guide -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-guide-line me-1"></i> Panduan Kategori
                </h6>
                <div class="mb-3">
                    <span class="badge bg-label-primary mb-2">Kehadiran</span>
                    <small class="d-block text-muted">
                        Indikator terkait kehadiran, ketepatan waktu, dll
                    </small>
                </div>
                <div class="mb-3">
                    <span class="badge bg-label-success mb-2">Aktivitas</span>
                    <small class="d-block text-muted">
                        Indikator terkait daily activity, logbook, dll
                    </small>
                </div>
                <div>
                    <span class="badge bg-label-warning mb-2">Project</span>
                    <small class="d-block text-muted">
                        Indikator terkait weekly project, rating, dll
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('indicatorForm');
    const submitBtn = document.getElementById('submitBtn');
    const sisaBobotDisplay = document.getElementById('sisaBobot');
    const bobotInput = document.getElementById('bobot');
    const sisaBobotAwal = <?= $validation['remaining'] ?>;

    // Character counters
    document.getElementById('nama_indicator').addEventListener('input', function() {
        document.getElementById('namaCount').textContent = this.value.length;
    });

    document.getElementById('deskripsi').addEventListener('input', function() {
        document.getElementById('deskripsiCount').textContent = this.value.length;
    });

    // Update sisa bobot saat input
    bobotInput.addEventListener('input', function() {
        const bobotValue = parseFloat(this.value) || 0;
        const sisaBobot = sisaBobotAwal - bobotValue;

        sisaBobotDisplay.textContent = sisaBobot.toFixed(2) + '%';

        if (sisaBobot < 0) {
            sisaBobotDisplay.classList.remove('text-success');
            sisaBobotDisplay.classList.add('text-danger');
        } else {
            sisaBobotDisplay.classList.remove('text-danger');
            sisaBobotDisplay.classList.add('text-success');
        }
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Validation
        const bobotValue = parseFloat(bobotInput.value) || 0;
        const sisaBobot = sisaBobotAwal - bobotValue;

        if (sisaBobot < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Bobot Melebihi Batas',
                text: `Bobot melebihi sisa tersedia (${sisaBobotAwal}%). Silakan kurangi bobot.`,
                confirmButtonColor: '#696cff'
            });
            return;
        }

        // Confirmation
        const result = await Swal.fire({
            title: 'Simpan Indicator?',
            html: `
                <p>Indicator akan ditambahkan dengan bobot <strong>${bobotValue}%</strong></p>
                <small class="text-muted">Sisa bobot setelah ini: <strong>${sisaBobot.toFixed(2)}%</strong></small>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        try {
            const formData = new FormData(form);

            const response = await fetch('<?= base_url('kpi/indicators/store') ?>', {
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
                    window.location.href = '<?= base_url('kpi/indicators') ?>';
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