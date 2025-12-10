<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="<?php echo base_url('assets/sneat/'); ?>" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login | SMOP Haramainku</title>

    <meta name="description" content="Sistem Monitoring Operasional Perjalanan Haramainku" />

    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/sneat/img/favicon/favicon.ico'); ?>" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/fonts/boxicons.css'); ?>" />

    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/css/core.css'); ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/css/theme-default.css'); ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/css/demo.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/css/custom.css'); ?>" />

    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.css'); ?>" />

    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/css/pages/page-auth.css'); ?>" />
    <script src="<?php echo base_url('assets/sneat/vendor/js/helpers.js'); ?>"></script>

    <script src="<?php echo base_url('assets/sneat/js/config.js'); ?>"></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center">
                            <a href="<?php echo site_url('auth'); ?>" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="<?php echo base_url('assets/sneat/img/logo.png'); ?>" alt="Logo" style="width: 35px; height: 35px;">
                                </span>
                                <span class="app-brand-text demo text-body fw-bolder">SMOP HaramainKU</span>
                            </a>
                        </div>
                        <h4 class="mb-2">Selamat Datang! 👋</h4>
                        <p class="mb-4">Silahkan login ke sistem Anda.</p>
                        
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <?php echo form_open('auth/login_process', 'id="formAuthentication" class="mb-3"'); ?>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username Anda" value="<?php echo set_value('username'); ?>" autofocus required />
                                <?php echo form_error('username', '<p class="text-danger mt-1">', '</p>'); ?>
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                    </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <?php echo form_error('password', '<p class="text-danger mt-1">', '</p>'); ?>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                                    <label class="form-check-label" for="remember-me"> Ingat Saya </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        <?php echo form_close(); ?>
                        
                        <p class="text-center">
                            <small class="text-center">Powered by Dhayu Fandy Stiawan</small>
                        </p>
                        
                    </div>
                </div>
                </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/sneat/vendor/libs/jquery/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/vendor/libs/popper/popper.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/vendor/js/bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.js'); ?>"></script>

    <script src="<?php echo base_url('assets/sneat/vendor/js/menu.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/js/main.js'); ?>"></script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>