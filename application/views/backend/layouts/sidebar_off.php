<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Variabel session user tidak terlalu penting di sini, tapi kita sisakan jika dibutuhkan
// $user_system_role = $this->session->userdata('system_role'); 
// $user_full_name = $this->session->userdata('nama_lengkap'); 
?>

<nav class="navbar navbar-vertical navbar-expand-xl navbar-light bg-white">
    <div class="container-fluid">
        <a class="navbar-brand py-3" href="<?php echo site_url('admin'); ?>">
            <img src="<?php echo base_url('assets/backend/images/logo-dark.svg'); ?>" alt="logo" />
        </a>
        
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse show" id="navbarVerticalCollapse">
            <div class="nav-scroller os-host os-theme-dark os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition os-padding">
                <ul class="navbar-nav flex-column" id="sideNavbar">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('admin'); ?>">
                            <span class="nav-icon me-2"><i data-feather="home"></i></span>
                            Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <div class="navbar-vertical-label">Monitoring & Operasional</div>
                        <hr class="border-2 opacity-100 mt-2 mb-1">
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('admin/monitoring'); ?>">
                            <span class="nav-icon me-2"><i data-feather="monitor"></i></span>
                            Monitoring Grup
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('admin/grup_form'); ?>">
                            <span class="nav-icon me-2"><i data-feather="plus-circle"></i></span>
                            Buat Grup Baru
                        </a>
                    </li>

                    <li class="nav-item">
                        <div class="navbar-vertical-label">Perencanaan & Master Data</div>
                        <hr class="border-2 opacity-100 mt-2 mb-1">
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('admin/templates'); ?>">
                            <span class="nav-icon me-2"><i data-feather="copy"></i></span>
                            Template Itinerary
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('admin/roles'); ?>">
                            <span class="nav-icon me-2"><i data-feather="users"></i></span>
                            Master Peran Tugas
                        </a>
                    </li>

                    <li class="nav-item">
                        <div class="navbar-vertical-label">Administrasi & Laporan</div>
                        <hr class="border-2 opacity-100 mt-2 mb-1">
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('admin/users'); ?>">
                            <span class="nav-icon me-2"><i data-feather="user"></i></span>
                            Manajemen User
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('admin/laporan_kinerja'); ?>">
                            <span class="nav-icon me-2"><i data-feather="bar-chart-2"></i></span>
                            Laporan Kinerja
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('admin/notifications'); ?>">
                            <span class="nav-icon me-2"><i data-feather="bell"></i></span>
                            Notifikasi Buruk
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo site_url('auth/logout'); ?>" class="nav-link text-danger mt-4">
                            <span class="nav-icon me-2"><i data-feather="log-out"></i></span>
                            Logout
                        </a>
                    </li>
                </ul>
                </div>
        </div>
    </div>
</nav>