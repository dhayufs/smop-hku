<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// MENGGUNAKAN FIELD SYSTEM_ROLE KITA, BUKAN 'role'
$user_role = $this->session->userdata('system_role'); 
?>
<!DOCTYPE html>
<html lang="id" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="dark">

<head>
    <title><?php echo isset($title) ? $title : 'SMOP Haramainku'; ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    
    <link rel="icon" href="<?php echo base_url('assets/backend/images/favicon.svg'); ?>" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/phosphor/duotone/style.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/tabler-icons.min.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/feather.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/fontawesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/material.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/css/style.css'); ?>" id="main-style-link" />
    
    <style>
        .pc-sidebar .simplebar-track.simplebar-vertical {
            background: transparent !important; 
            width: 8px !important;
            z-index: 1000;
        }
        .pc-sidebar .simplebar-scrollbar:before {
            background: rgba(255, 255, 255, 0.15) !important; 
            border-radius: 4px !important;
        }
        .pc-sidebar:hover .simplebar-track:hover .simplebar-scrollbar:before {
            background: rgba(255, 255, 255, 0.4) !important;
        }
    </style>
</head>

<body>
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]"></div>
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header flex items-center py-4 px-6 h-header-height">
                <a href="<?php echo site_url('admin'); ?>" class="b-brand flex items-center gap-3">
                    <img src="<?php echo base_url('assets/backend/images/logo-white.svg'); ?>" class="img-fluid logo logo-lg" alt="logo" />
                    <img src="<?php echo base_url('assets/backend/images/favicon.svg'); ?>" class="img-fluid logo logo-sm" alt="logo" />
                </a>
            </div>
            <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5"> 
                <ul class="pc-navbar">
                    
                    <li class="pc-item">
                        <a href="<?php echo site_url('admin'); ?>" class="pc-link">
                            <span class="pc-micon"><i data-feather="home"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="pc-item pc-caption">
                        <label>Monitoring & Operasional</label>
                    </li>
                
                    <li class="pc-item">
                        <a href="<?php echo site_url('admin/monitoring'); ?>" class="pc-link">
                            <span class="pc-micon"><i data-feather="monitor"></i></span>
                            <span class="pc-mtext">Monitoring Grup</span>
                        </a>
                    </li>                    
                    
                    <li class="pc-item pc-caption">
                        <label>Perencanaan & Master Data</label>
                    </li>
                    
                    <li class="pc-item">
                        <a href="<?php echo site_url('admin/templates'); ?>" class="pc-link">
                            <span class="pc-micon"><i data-feather="copy"></i></span>
                            <span class="pc-mtext">Template Itinerary</span>
                        </a>
                    </li>
                    
                    <li class="pc-item">
                        <a href="<?php echo site_url('admin/roles'); ?>" class="pc-link">
                            <span class="pc-micon"><i data-feather="users"></i></span>
                            <span class="pc-mtext">Master Peran Tugas</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Administrasi & Laporan</label>
                    </li>
                    
                    <li class="pc-item">
                        <a href="<?php echo site_url('admin/users'); ?>" class="pc-link"><span class="pc-micon"><i data-feather="user"></i></span><span class="pc-mtext">Manajemen User</span></a>
                    </li>
                    
                    <li class="pc-item">
                        <a href="<?php echo site_url('admin/laporan_kinerja'); ?>" class="pc-link"><span class="pc-micon"><i data-feather="bar-chart-2"></i></span><span class="pc-mtext">Laporan Kinerja</span></a>
                    </li>
                    
                    <li class="pc-item">
                        <a href="<?php echo site_url('admin/notifications'); ?>" class="pc-link"><span class="pc-micon"><i data-feather="bell"></i></span><span class="pc-mtext">Notifikasi Buruk</span></a>
                    </li>
                    
                    <li class="pc-item">
                        <a href="<?php echo site_url('auth/logout'); ?>" class="pc-link text-danger">
                            <span class="pc-micon"><i data-feather="log-out"></i></span>
                            <span class="pc-mtext">Logout</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <header class="pc-header">
        <div class="header-wrapper flex max-sm:px-[15px] px-[25px] grow">
            
            <div class="me-auto pc-mob-drp lg:hidden">
              <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                <li class="pc-h-item pc-sidebar-popup">
                  <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="mobile-collapse">
                    <i data-feather="menu"></i>
                  </a>
                </li>
              </ul>
            </div>
            <div class="ms-auto">
                <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                    
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-pc-toggle="dropdown" href="#" role="button" data-pc-auto-close="outside" aria-expanded="false">
                            <i data-feather="user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown p-2 overflow-hidden">
                            <div class="dropdown-header flex items-center justify-between py-4 px-5 bg-primary-500">
                                <div class="flex mb-1 items-center">
                                    <div class="grow ms-3">
                                        <h6 class="mb-1 text-white"><?php echo $this->session->userdata('nama_lengkap'); ?></h6>
                                        <span class="text-white"><?php echo ucfirst($this->session->userdata('system_role')); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-body py-4 px-5">
                                <div class="grid my-3">
                                    <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-primary flex items-center justify-center">
                                        <i data-feather="log-out" class="w-[22px] h-[22px] me-2"></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium"><?php echo isset($title) ? $title : 'Dashboard'; ?></h5>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-x-6">