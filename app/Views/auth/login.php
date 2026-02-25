<!doctype html>
<html lang="en" class="layout-wide customizer-hide" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>Login - Sistem Manajemen Pemagang BMI</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon/favicon.ico') ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/iconify-icons.css') ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/node-waves/node-waves.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/vendor/css/core.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/demo.css') ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/css/pages/page-auth.css') ?>" />

    <!-- Helpers -->
    <script src="<?= base_url('assets/vendor/js/helpers.js') ?>"></script>
    <script src="<?= base_url('assets/js/config.js') ?>"></script>
</head>

<body>
    <!-- Content -->
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6 mx-4">
                <!-- Login -->
                <div class="card p-sm-7 p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="<?= base_url('/') ?>" class="app-brand-link gap-3">
                            <span class="app-brand-logo demo">
                                <span class="text-primary">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2L2 7V10H22V7L12 2Z" fill="currentColor" />
                                        <path d="M4 11V20H8V11H4Z" fill="currentColor" opacity="0.6" />
                                        <path d="M10 11V20H14V11H10Z" fill="currentColor" opacity="0.8" />
                                        <path d="M16 11V20H20V11H16Z" fill="currentColor" opacity="0.6" />
                                        <path d="M2 21H22V22H2V21Z" fill="currentColor" />
                                    </svg>
                                </span>
                            </span>
                            <span class="app-brand-text demo text-heading fw-semibold">BMI Magang</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-1">
                        <h4 class="mb-1">Selamat Datang! 👋🏻</h4>
                        <p class="mb-5">Silakan login ke akun Anda untuk melanjutkan</p>

                        <!-- Alert Messages -->
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <i class="icon-base ri-check-line me-1"></i>
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <i class="icon-base ri-close-line me-1"></i>
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <i class="icon-base ri-error-warning-line me-1"></i>
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form id="formAuthentication" class="mb-5" action="<?= base_url('login') ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="form-floating form-floating-outline mb-5 form-control-validation">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" value="<?= old('email') ?>" autofocus required />
                                <label for="email">Email</label>
                            </div>

                            <div class="mb-5">
                                <div class="form-password-toggle form-control-validation">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                            <label for="password">Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer" id="togglePassword">
                                            <i class="icon-base ri-eye-off-line icon-20px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5 pb-2 d-flex justify-content-between pt-2 align-items-center">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                                    <label class="form-check-label" for="remember-me">Remember Me</label>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" type="submit">
                                    <i class="icon-base ri-login-box-line me-1"></i> Login
                                </button>
                            </div>
                        </form>

                        <!-- Demo Credentials -->
                        <div class="divider my-4">
                            <div class="divider-text">Demo Accounts</div>
                        </div>

                        <div class="demo-credentials mb-3">
                            <small class="text-muted d-block mb-2">Default password untuk semua: <strong>password123</strong></small>
                            <div class="btn-group-vertical w-100" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="fillLogin('admin@muamalatbank.com')">
                                    <i class="icon-base ri-shield-user-line me-1"></i> Admin
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success mb-1" onclick="fillLogin('hr@muamalatbank.com')">
                                    <i class="icon-base ri-user-settings-line me-1"></i> HR Staff
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info mb-1" onclick="fillLogin('mentor.it@muamalatbank.com')">
                                    <i class="icon-base ri-user-star-line me-1"></i> Mentor
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning mb-1" onclick="fillLogin('finance@muamalatbank.com')">
                                    <i class="icon-base ri-money-dollar-circle-line me-1"></i> Finance
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fillLogin('intern001@muamalatbank.com')">
                                    <i class="icon-base ri-user-3-line me-1"></i> Intern
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Login -->
                <img src="<?= base_url('assets/img/illustrations/tree-3.png') ?>" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block" />
                <img src="<?= base_url('assets/img/illustrations/auth-basic-mask-light.png') ?>" class="authentication-image d-none d-lg-block scaleX-n1-rtl" height="172" alt="triangle-bg" />
                <img src="<?= base_url('assets/img/illustrations/tree.png') ?>" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block" />
            </div>
        </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <script src="<?= base_url('assets/vendor/libs/jquery/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/popper/popper.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/node-waves/node-waves.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/js/menu.js') ?>"></script>
    <script src="<?= base_url('assets/js/main.js') ?>"></script>

    <!-- Custom JS -->
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            } else {
                password.type = 'password';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            }
        });

        // Fill login credentials for demo
        function fillLogin(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password123';
            document.getElementById('password').focus();
        }

        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
</body>

</html>