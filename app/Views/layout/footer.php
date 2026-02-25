<?php
$currentYear = date('Y');
$appVersion = '1.0.0'; // Bisa diambil dari config
?>

<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
            <!-- Copyright -->
            <div class="text-body mb-2 mb-md-0">
                <span class="text-muted">© <?= $currentYear ?></span>
                <a href="<?= base_url('/') ?>" class="footer-link fw-medium text-primary ms-1">PT Bank Muamalat Indonesia Tbk</a>
            </div>

            <!-- Links & Version -->
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-label-primary rounded-pill">v<?= $appVersion ?></span>

                <div class="d-none d-lg-inline-block">
                    <a href="<?= base_url('profile') ?>" class="footer-link me-3" data-bs-toggle="tooltip" title="Profil Saya">
                        <i class="icon-base ri-user-3-line"></i>
                    </a>

                    <?php if (session()->get('role_code') === 'admin'): ?>
                        <a href="<?= base_url('settings') ?>" class="footer-link me-3" data-bs-toggle="tooltip" title="Pengaturan">
                            <i class="icon-base ri-settings-3-line"></i>
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('dashboard') ?>" class="footer-link me-3" data-bs-toggle="tooltip" title="Dashboard">
                        <i class="icon-base ri-dashboard-3-line"></i>
                    </a>

                    <a href="javascript:void(0);" class="footer-link" data-bs-toggle="modal" data-bs-target="#aboutModal" title="Tentang Aplikasi">
                        <i class="icon-base ri-information-line"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Modal About -->
<div class="modal fade" id="aboutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tentang Aplikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="icon-base ri-building-4-line icon-lg"></i>
                        </span>
                    </div>
                    <h4 class="mb-1">Sistem Manajemen Pemagang</h4>
                    <p class="text-muted">PT Bank Muamalat Indonesia Tbk</p>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Versi:</span>
                        <span class="fw-medium"><?= $appVersion ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Framework:</span>
                        <span class="fw-medium">CodeIgniter 4</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Template:</span>
                        <span class="fw-medium">Materio Bootstrap</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tahun:</span>
                        <span class="fw-medium"><?= $currentYear ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Developer:</span>
                        <span class="fw-medium">Abrar Falih Sentanu</span>
                    </div>
                </div>

                <div class="alert alert-primary mb-0">
                    <div class="d-flex gap-2">
                        <i class="icon-base ri-information-line"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Fitur Utama</h6>
                            <p class="mb-0 small">
                                Manajemen absensi, aktivitas harian, project mingguan, KPI performance,
                                pembayaran uang saku, dan pelaporan lengkap untuk program magang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Footer Enhancements */
    .footer {
        box-shadow: 0 -2px 6px rgba(67, 89, 113, 0.1);
        margin-top: auto;
    }

    .footer-link {
        color: var(--bs-body-color);
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }

    .footer-link:hover {
        color: var(--bs-primary);
        transform: translateY(-2px);
    }

    .footer-link i {
        font-size: 1.125rem;
        vertical-align: middle;
    }
</style>