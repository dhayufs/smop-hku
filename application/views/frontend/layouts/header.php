<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// === FUNGSI HELPER BARU UNTUK FORMAT TANGGAL INDONESIA (Hari, DD Bulan YYYY) ===
// (Fungsi dipindahkan ke controller MyTasks.php)
// ==================================================================================
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="<?php echo base_url('assets/sneat/');?>" data-template="vertical-menu-template-free">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | SMOP</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/fonts/boxicons.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/css/core.css'); ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/css/theme-default.css'); ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/css/demo.css'); ?>" />

    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.css'); ?>" />
    
    <script src="<?php echo base_url('assets/sneat/vendor/js/helpers.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/js/config.js'); ?>"></script>

    <style>
        /* CSS lama telah dihapus untuk mengutamakan gaya dari template Sneat */
    </style>

</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="<?php echo site_url('mytasks'); ?>" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="https://haramaintour.com/wp-content/uploads/2025/06/logo-haramainku-haramaintour-kartika-utama.jpg" alt="Logo" style="max-height: 45px;">
                        </span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>
                
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Tugas Lapangan</span></li>

                <ul class="menu-inner py-1">
                    <li class="menu-item <?php echo (uri_string() == 'mytasks' || strpos(uri_string(), 'mytasks/grup_detail') !== FALSE || strpos(uri_string(), 'mytasks/update_task_form') !== FALSE) ? 'active open' : ''; ?>">
                        <a href="<?php echo site_url('mytasks'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-list-check"></i>
                            <div data-i18n="My Tasks">List Tugas</div>
                        </a>
                    </li>
                    
                    <li class="menu-item">
                        <a href="<?php echo site_url('auth/logout'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-log-out"></i>
                            <div data-i18n="Logout">Logout</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <h5 class="mb-0 fw-bold">Sistem Monitoring Oprasional</h5>
                            </div>
                        </div>
                        
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item navbar-dropdown dropdown dropdown-user">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?php echo base_url('assets/sneat/img/avatars/1.png'); ?>" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="<?php echo base_url('assets/sneat/img/avatars/1.png'); ?>" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block"><?php echo $this->session->userdata('nama_lengkap'); ?></span>
                                                    <small class="text-muted"><?php echo ucfirst($this->session->userdata('system_role')); ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo site_url('auth/logout'); ?>">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            </ul>
                    </div>
                </nav>
                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">