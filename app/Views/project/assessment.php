<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Assessment Project</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-checkbox-circle-line me-2"></i>Assessment Project Mentee
                </h4>
                <p class="mb-0 text-muted">Review dan nilai weekly project mentee Anda</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Card -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Pending Assessment</span>
                        <div class="d-flex align-items-center my-1">
                            <h3 class="mb-0 me-2 text-info"><?= $pending_count ?></h3>
                        </div>
                        <small class="mb-0">Menunggu review</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-time-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Projects List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 me-2">Daftar Pending Project</h5>
        <span class="badge bg-label-primary"><?= count($projects) ?> Records</span>
    </div>
    <div class="card-body">

        <?php if (empty($projects)): ?>
            <div class="text-center py-5">
                <i class="ri-checkbox-circle-line" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada project yang perlu di-assess</p>
            </div>
        <?php else: ?>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-paginated">
                    <thead>
                        <tr>
                            <th>Intern</th>
                            <th>Week</th>
                            <th>Judul Project</th>
                            <th>Tipe</th>
                            <th>Progress</th>
                            <th>Self Rating</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php foreach ($projects as $proj): ?>
                            <?php
                            $tipeClass = [
                                'inisiatif' => 'primary',
                                'assigned' => 'secondary'
                            ];
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?= strtoupper(substr($proj['nama_lengkap'], 0, 2)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <strong><?= esc($proj['nama_lengkap']) ?></strong>
                                            <small class="d-block text-muted"><?= $proj['nik'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>Week <?= $proj['week_number'] ?></strong><br>
                                    <small class="text-muted"><?= $proj['tahun'] ?></small>
                                </td>
                                <td>
                                    <strong><?= esc($proj['judul_project']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= substr(esc($proj['deskripsi']), 0, 50) ?>...
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-label-<?= $tipeClass[$proj['tipe_project']] ?>">
                                        <?= ucfirst($proj['tipe_project']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress w-100 me-2" style="height: 8px; min-width: 60px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?= $proj['progress'] ?>%">
                                            </div>
                                        </div>
                                        <small><?= $proj['progress'] ?>%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($proj['self_rating']): ?>
                                        <span class="badge bg-label-warning">
                                            <i class="ri-star-fill"></i> <?= $proj['self_rating'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-icon btn-label-info"
                                            onclick="viewDetail(<?= $proj['id_project'] ?>)"
                                            title="Lihat Detail">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon btn-label-success"
                                            onclick="assessProject(<?= $proj['id_project'] ?>, '<?= esc($proj['judul_project']) ?>')"
                                            title="Assess">
                                            <i class="ri-check-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal-project-assessment" tabindex="-1" aria-labelledby="detailModal-project-assessmentLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModal-project-assessmentLabel">Detail Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent-project-assessment">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="btnAssessFromModal">
                    <i class="ri-check-line me-1"></i> Assess Project
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Assessment Modal -->
<div class="modal fade" id="assessmentModal" tabindex="-1" aria-labelledby="assessmentModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assessmentModalLabel">Assessment Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="assessmentForm">
                <div class="modal-body">
                    <input type="hidden" id="assess_project_id">

                    <div class="alert alert-secondary mb-3">
                        <strong id="assess_project_title"></strong>
                    </div>

                    <!-- Mentor Rating -->
                    <div class="mb-3">
                        <label for="mentor_rating" class="form-label">
                            Mentor Rating <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <input type="range" class="form-range flex-grow-1" id="mentor_rating" name="mentor_rating"
                                min="1.0" max="5.0" value="3.0" step="0.1" required>
                            <span class="badge bg-success" id="mentorRatingBadge" style="min-width: 80px;">
                                <i class="ri-star-fill"></i> 3.0
                            </span>
                        </div>
                        <small class="text-muted">Beri penilaian untuk project ini (1.0 - 5.0)</small>
                    </div>

                    <!-- Rating Guide -->
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <small class="d-block mb-1"><strong>Panduan Rating:</strong></small>
                            <small class="d-block">1.0-1.9: Poor | 2.0-2.9: Below Avg | 3.0-3.9: Average</small>
                            <small class="d-block">4.0-4.5: Good | 4.6-5.0: Excellent</small>
                        </div>
                    </div>

                    <!-- Feedback -->
                    <div class="mb-3">
                        <label for="feedback_mentor" class="form-label">
                            Feedback <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="feedback_mentor" name="feedback_mentor" rows="4"
                            placeholder="Berikan feedback kepada intern tentang project-nya..." required></textarea>
                        <small class="text-muted">
                            <span id="feedbackCount">0</span> karakter (min. 30 karakter)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSubmitAssessment">
                        <i class="ri-check-line me-1"></i> Submit Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let detailModal;
    let assessmentModal;
    let currentProjectId = null;

    document.addEventListener('DOMContentLoaded', function() {
        detailModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal-project-assessment'));
        assessmentModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('assessmentModal'));

        // Rating slider
        document.getElementById('mentor_rating').addEventListener('input', function() {
            document.getElementById('mentorRatingBadge').innerHTML =
                `<i class="ri-star-fill"></i> ${parseFloat(this.value).toFixed(1)}`;
        });

        // Feedback counter
        document.getElementById('feedback_mentor').addEventListener('input', function() {
            document.getElementById('feedbackCount').textContent = this.value.length;
        });
    });

    // View Detail
    function viewDetail(id) {
        currentProjectId = id;

        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`<?= base_url('project/detail/') ?>${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    showDetailModal(data.project);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire('Error', 'Terjadi kesalahan', 'error');
            });
    }

    function showDetailModal(project) {
        const html = `
        <div class="row">
            <div class="col-12 mb-3">
                <div class="alert alert-secondary">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Intern</small>
                            <strong>${project.nama_lengkap}</strong>
                            <small class="d-block">${project.nik}</small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Week</small>
                            <strong>Week ${project.week_number} - ${project.tahun}</strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <small class="text-muted d-block">Tipe Project</small>
                <span class="badge bg-label-primary">${project.tipe_project}</span>
            </div>
            <div class="col-md-6 mb-3">
                <small class="text-muted d-block">Progress</small>
                <div class="d-flex align-items-center">
                    <div class="progress w-100 me-2" style="height: 8px;">
                        <div class="progress-bar" style="width: ${project.progress}%"></div>
                    </div>
                    <span>${project.progress}%</span>
                </div>
            </div>
            
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Judul Project</small>
                <h6>${project.judul_project}</h6>
            </div>
            
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Deskripsi</small>
                <p style="white-space: pre-wrap;">${project.deskripsi}</p>
            </div>
            
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Deliverables</small>
                <p style="white-space: pre-wrap;">${project.deliverables || '-'}</p>
            </div>
            
            <div class="col-12 mb-3">
                <small class="text-muted d-block mb-1">Self Rating</small>
                <span class="badge bg-warning">
                    <i class="ri-star-fill"></i> ${project.self_rating || '-'}
                </span>
            </div>
            
            ${project.attachment ? `
            <div class="col-12">
                <small class="text-muted d-block mb-2">Attachment</small>
                <div class="alert alert-info">
                    <i class="ri-attachment-2 me-2"></i>
                    <strong>${project.attachment}</strong>
                    <a href="<?= base_url('project/attachment/view/') ?>${project.id_project}" 
                       target="_blank" class="btn btn-sm btn-primary ms-2">
                        <i class="ri-eye-line"></i> Lihat
                    </a>
                </div>
            </div>
            ` : ''}
        </div>
    `;

        document.getElementById('detailContent-project-assessment').innerHTML = html;
        detailModal.show();
    }

    // Assess from modal
    document.getElementById('btnAssessFromModal').addEventListener('click', function() {
        if (currentProjectId) {
            const title = document.querySelector('#detailContent-project-assessment h6').textContent;
            const modalEl = document.getElementById('detailModal-project-assessment');
            modalEl.addEventListener('hidden.bs.modal', function handler() {
                modalEl.removeEventListener('hidden.bs.modal', handler);
                assessProject(currentProjectId, title);
            });
            detailModal.hide();
        }
    });

    // Assess Project
    function assessProject(id, judul) {
        document.getElementById('assess_project_id').value = id;
        document.getElementById('assess_project_title').textContent = judul;

        // Reset form
        document.getElementById('assessmentForm').reset();
        document.getElementById('mentor_rating').value = 3.0;
        document.getElementById('mentorRatingBadge').innerHTML = '<i class="ri-star-fill"></i> 3.0';
        document.getElementById('feedbackCount').textContent = '0';

        assessmentModal.show();
    }

    // Submit Assessment
    document.getElementById('assessmentForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const projectId = document.getElementById('assess_project_id').value;
        const mentorRating = document.getElementById('mentor_rating').value;
        const feedback = document.getElementById('feedback_mentor').value;

        if (feedback.length < 30) {
            Swal.fire({
                icon: 'warning',
                title: 'Feedback Terlalu Pendek',
                text: 'Feedback minimal 30 karakter',
                confirmButtonColor: '#696cff'
            });
            return;
        }

        const result = await Swal.fire({
            title: 'Submit Assessment?',
            html: `
                <p>Rating: <strong>${parseFloat(mentorRating).toFixed(1)}</strong></p>
                <p>Pastikan feedback sudah sesuai</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28c76f',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Submit!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const formData = new FormData();
            formData.append('mentor_rating', mentorRating);
            formData.append('feedback_mentor', feedback);

            const response = await fetch(`<?= base_url('project/assess/') ?>${projectId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                assessmentModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem'
            });
        }
    });

    // Flash messages
    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success') ?>',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    <?php endif; ?>
</script>

<?= $this->endSection() ?>