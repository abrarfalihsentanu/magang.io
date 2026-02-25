<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('intern') ?>">Data Pemagang</a></li>
                <li class="breadcrumb-item active">Detail Pemagang</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    <i class="ri-user-star-line me-2"></i>Detail Pemagang
                </h4>
                <p class="mb-0 text-muted">Informasi lengkap data pemagang</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('intern/edit/' . $intern['id_intern']) ?>" class="btn btn-primary">
                    <i class="ri-pencil-line me-1"></i> Edit
                </a>
                <a href="<?= base_url('intern') ?>" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-12 col-lg-8">
        <!-- Profile Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start gap-4">
                    <div class="avatar avatar-xl">
                        <?php if (!empty($intern['foto']) && $intern['foto'] !== 'default-avatar.png'): ?>
                            <img src="<?= base_url('uploads/users/' . $intern['foto']) ?>" 
                                 alt="<?= esc($intern['nama_lengkap']) ?>" 
                                 class="rounded-circle"
                                 onerror="this.onerror=null; this.src='<?= base_url('assets/img/avatars/1.png') ?>'">
                        <?php else: ?>
                            <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 32px;">
                                <?= strtoupper(substr($intern['nama_lengkap'], 0, 2)) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-1"><?= esc($intern['nama_lengkap']) ?></h4>
                        <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                            <span class="badge bg-label-primary"><?= esc($intern['nik']) ?></span>
                            <?php
                            $statusColors = [
                                'active' => 'success',
                                'completed' => 'info',
                                'terminated' => 'danger'
                            ];
                            $statusLabels = [
                                'active' => 'Aktif Magang',
                                'completed' => 'Selesai',
                                'terminated' => 'Diberhentikan'
                            ];
                            ?>
                            <span class="badge bg-label-<?= $statusColors[$intern['status_magang']] ?>">
                                <?= $statusLabels[$intern['status_magang']] ?>
                            </span>
                        </div>
                        <div class="d-flex gap-4 flex-wrap">
                            <div>
                                <small class="text-muted d-block">Email</small>
                                <span><?= esc($intern['email']) ?></span>
                            </div>
                            <div>
                                <small class="text-muted d-block">No HP</small>
                                <span><?= esc($intern['no_hp']) ?></span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Divisi</small>
                                <span class="badge bg-label-info"><?= esc($intern['nama_divisi'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Pribadi -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Data Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Jenis Kelamin</small>
                        <strong><?= $intern['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                        <strong><?= date('d M Y', strtotime($intern['tanggal_lahir'])) ?></strong>
                        <small class="text-muted">(<?= floor((time() - strtotime($intern['tanggal_lahir'])) / 31556926) ?> tahun)</small>
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted d-block mb-1">Alamat</small>
                        <p class="mb-0"><?= esc($intern['alamat']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Akademik -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Data Akademik</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Universitas</small>
                        <strong><?= esc($intern['universitas']) ?></strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Jurusan</small>
                        <strong><?= esc($intern['jurusan']) ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Magang -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Magang</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block mb-1">Periode Mulai</small>
                        <strong><?= date('d M Y', strtotime($intern['periode_mulai'])) ?></strong>
                    </div>
                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block mb-1">Periode Selesai</small>
                        <strong><?= date('d M Y', strtotime($intern['periode_selesai'])) ?></strong>
                    </div>
                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block mb-1">Durasi</small>
                        <strong><?= $intern['durasi_bulan'] ?> Bulan</strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Mentor Pembimbing</small>
                        <strong><?= esc($intern['nama_mentor'] ?? 'Belum ditentukan') ?></strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Surat Magang</small>
                        <?php if ($intern['dokumen_surat_magang']): ?>
                            <a href="<?= base_url('intern/download-document/' . $intern['id_intern']) ?>" 
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="ri-file-pdf-line me-1"></i> Lihat Dokumen
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Tidak ada dokumen</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($intern['catatan']): ?>
                        <div class="col-12 mb-3">
                            <small class="text-muted d-block mb-1">Catatan</small>
                            <p class="mb-0"><?= esc($intern['catatan']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ringkasan Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="avatar avatar-lg mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-calendar-check-line ri-26px"></i>
                            </span>
                        </div>
                        <h5 class="mb-0"><?= $attendance_summary['total'] ?></h5>
                        <small class="text-muted">Total Hari</small>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="avatar avatar-lg mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-checkbox-circle-line ri-26px"></i>
                            </span>
                        </div>
                        <h5 class="mb-0"><?= $attendance_summary['hadir'] ?></h5>
                        <small class="text-muted">Hadir</small>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="avatar avatar-lg mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-information-line ri-26px"></i>
                            </span>
                        </div>
                        <h5 class="mb-0"><?= $attendance_summary['izin'] + $attendance_summary['sakit'] ?></h5>
                        <small class="text-muted">Izin/Sakit</small>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="avatar avatar-lg mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ri-close-circle-line ri-26px"></i>
                            </span>
                        </div>
                        <h5 class="mb-0"><?= $attendance_summary['alpha'] ?></h5>
                        <small class="text-muted">Alpha</small>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Persentase Kehadiran</span>
                        <strong><?= $attendance_summary['persentase'] ?>%</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar <?= $attendance_summary['persentase'] >= 90 ? 'bg-success' : ($attendance_summary['persentase'] >= 75 ? 'bg-warning' : 'bg-danger') ?>" 
                             style="width: <?= $attendance_summary['persentase'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ringkasan Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="avatar avatar-lg mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-file-list-3-line ri-26px"></i>
                            </span>
                        </div>
                        <h5 class="mb-0"><?= $activity_summary['total'] ?></h5>
                        <small class="text-muted">Total Aktivitas</small>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="avatar avatar-lg mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-check-line ri-26px"></i>
                            </span>
                        </div>
                        <h5 class="mb-0"><?= $activity_summary['approved'] ?></h5>
                        <small class="text-muted">Disetujui</small>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="avatar avatar-lg mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-time-line ri-26px"></i>
                            </span>
                        </div>
                        <h5 class="mb-0"><?= $activity_summary['pending'] ?></h5>
                        <small class="text-muted">Pending</small>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="avatar avatar-lg mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ri-close-line ri-26px"></i>
                            </span>
                        </div>
                        <h5 class="mb-0"><?= $activity_summary['rejected'] ?></h5>
                        <small class="text-muted">Ditolak</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-12 col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('intern/edit/' . $intern['id_intern']) ?>" class="btn btn-outline-primary">
                        <i class="ri-pencil-line me-1"></i> Edit Data
                    </a>
                    <a href="<?= base_url('attendance/all?user=' . $intern['id_user']) ?>" class="btn btn-outline-success">
                        <i class="ri-calendar-check-line me-1"></i> Lihat Absensi
                    </a>
                    <a href="<?= base_url('activity?user=' . $intern['id_user']) ?>" class="btn btn-outline-info">
                        <i class="ri-file-list-3-line me-1"></i> Lihat Aktivitas
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteIntern()">
                        <i class="ri-delete-bin-6-line me-1"></i> Hapus Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Statistik</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted d-block">Masa Magang</small>
                        <strong><?= $intern['durasi_bulan'] ?> Bulan</strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-calendar-line"></i>
                        </span>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted d-block">Sisa Hari</small>
                        <strong>
                            <?php 
                            $today = new DateTime();
                            $end = new DateTime($intern['periode_selesai']);
                            $diff = $today->diff($end);
                            echo $diff->days > 0 ? $diff->days . ' Hari' : 'Selesai';
                            ?>
                        </strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line"></i>
                        </span>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Persentase Hadir</small>
                        <strong><?= $attendance_summary['persentase'] ?>%</strong>
                    </div>
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-<?= $attendance_summary['persentase'] >= 90 ? 'success' : 'warning' ?>">
                            <i class="ri-user-star-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Riwayat</h6>
            </div>
            <div class="card-body">
                <ul class="timeline mb-0">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Data Dibuat</h6>
                                <small class="text-muted"><?= date('d M Y, H:i', strtotime($intern['created_at'])) ?></small>
                            </div>
                            <p class="mb-0">Data pemagang berhasil ditambahkan</p>
                        </div>
                    </li>
                    <?php if ($intern['updated_at'] !== $intern['created_at']): ?>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-info"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Terakhir Diupdate</h6>
                                    <small class="text-muted"><?= date('d M Y, H:i', strtotime($intern['updated_at'])) ?></small>
                                </div>
                                <p class="mb-0">Informasi diperbarui</p>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-warning"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Status Saat Ini</h6>
                                <small class="text-muted"><?= date('d M Y') ?></small>
                            </div>
                            <p class="mb-0">
                                Status: <span class="badge bg-label-<?= $statusColors[$intern['status_magang']] ?>">
                                    <?= $statusLabels[$intern['status_magang']] ?>
                                </span>
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteIntern() {
        Swal.fire({
            title: 'Hapus Pemagang?',
            html: `Data pemagang <strong><?= esc($intern['nama_lengkap']) ?></strong> akan dihapus permanen.<br>Semua data terkait akan terhapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`<?= base_url('intern/delete/' . $intern['id_intern']) ?>`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= base_url('intern') ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message
                        });
                    }
                });
            }
        });
    }
</script>

<?= $this->endSection() ?>