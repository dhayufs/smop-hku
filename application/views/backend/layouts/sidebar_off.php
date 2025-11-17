<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Variabel session user tidak terlalu penting di sini, tapi kita sisakan jika dibutuhkan
// $user_system_role = $this->session->userdata('system_role'); 
// $user_full_name = $this->session->userdata('nama_lengkap'); 
?>

<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header flex items-center py-4 px-6 h-header-height">
            <a href="<?php echo site_url('admin'); ?>" class="b-brand flex items-center gap-3">
                <img src="<?php echo base_url('assets/backend/images/logo-white.svg'); ?>" class="img-fluid logo logo-lg" alt="logo" />
                <img src="<?php echo base_url('assets/backend/images/favicon.svg'); ?>" class="img-fluid logo logo-sm" alt="logo" />
            </a>
        </div>
        <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5" data-simplebar> 
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
                
                <li class="pc-item">
                    <a href="<?php echo site_url('admin/grup_form'); ?>" class="pc-link">
                        <span class="pc-micon"><i data-feather="plus-circle"></i></span>
                        <span class="pc-mtext">Buat Grup Baru</span>
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