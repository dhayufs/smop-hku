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
        /* [Perbaikan Icon Box] menggunakan ti-alert-triangle */
        .notification-icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            /* Tambahkan styling khusus untuk teks icon 🚨 */
            font-size: 1.2rem; /* Menyesuaikan ukuran agar tidak terlalu kecil */
        }
        /* Perbaikan scrollbar */
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
                    
                    <li class="dropdown pc-h-item" id="notification-dropdown">
                        <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <i data-feather="bell"></i>
                            <?php $unread_count_display = isset($unread_count) ? $unread_count : 0; ?>
                            <?php if ($unread_count_display > 0): ?>
                                <span class="badge bg-danger-500 text-white rounded-full z-10 absolute right-0 top-0" id="notification-count">
                                    <?php echo $unread_count_display > 9 ? '9+' : $unread_count_display; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown p-2" style="width: 300px;">
                            <div class="dropdown-header flex items-center justify-between py-4 px-5">
                                <h5 class="m-0">Notifikasi</h5>
                                <a href="<?php echo site_url('admin/notifications'); ?>" class="btn btn-link btn-sm">Lihat Semua</a>
                            </div>
                            <div class="dropdown-body header-notification-scroll relative py-4 px-5"
                                style="max-height: 300px; overflow-y: auto;">
                                
                                <?php if (isset($recent_notifications) && !empty($recent_notifications)): ?>
                                    <?php foreach ($recent_notifications as $notif): ?>
                                        <?php $bg_class = $notif->is_read == 0 ? 'bg-theme-bg-2/10' : ''; ?>
                                        <div class="card mb-2 hover:bg-theme-bg-2/10 notification-card <?php echo $bg_class; ?>" 
                                            data-notif-id="<?php echo $notif->id; ?>" 
                                            data-is-read="<?php echo $notif->is_read; ?>">
                                            
                                            <a href="<?php echo site_url('admin/item_history/' . $notif->grup_item_id); ?>" class="card-body p-3 block notification-link">
                                                <div class="flex gap-2 items-start">
                                                    <div class="shrink-0 pt-1 notification-icon-box">
                                                        🚨
                                                    </div>
                                                    <div class="grow">
                                                        <span class="float-end text-sm text-muted"><?php echo $notif->time_ago; ?></span>
                                                        <h6 class="text-body mb-1 font-semibold line-clamp-1">Item <?php echo $notif->status; ?></h6>
                                                        <p class="mb-0 text-xs text-muted">
                                                            <?php echo $notif->nama_grup; ?>: <?php echo $notif->deskripsi; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center text-muted py-2">Tidak ada notifikasi baru.</div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </li>
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

<script>
    // PASTIKAN SCRIPT INI DIMASUKKAN KE FILE JAVASCRIPT UTAMA ANDA (misal: assets/backend/js/script.js)
    // Jika Anda meletakkannya langsung di header.php, pastikan kode JavaScript di bawahnya juga berjalan setelah elemen dimuat.

    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('.notification-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const card = this.closest('.notification-card');
                const notifId = card.dataset.notifId;
                const isRead = card.dataset.isRead;

                // Hanya proses jika belum dibaca (isRead === '0')
                if (isRead === '0') {
                    // Mencegah navigasi segera
                    e.preventDefault(); 
                    
                    // Panggil endpoint AJAX untuk menandai sudah dibaca
                    fetch('<?php echo site_url('admin/mark_notification_read'); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: 'notif_id=' + notifId
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Tidak peduli sukses/gagal, asalkan status bukan error fatal, navigasi
                        if (data.success || data.unread_count !== undefined) {
                            // 1. Hapus background notifikasi belum dibaca
                            card.classList.remove('bg-theme-bg-2/10');
                            card.dataset.isRead = '1';
                            
                            // 2. Perbarui counter di header
                            const countElement = document.getElementById('notification-count');
                            if (countElement) {
                                const newCount = data.unread_count;
                                if (newCount > 0) {
                                    countElement.textContent = newCount > 9 ? '9+' : newCount;
                                } else {
                                    countElement.remove(); // Hapus badge jika 0
                                }
                            }
                        }
                        
                        // 3. Lanjutkan navigasi
                        window.location.href = this.href;
                    })
                    .catch(error => {
                        console.error('Error marking notification as read:', error);
                        // Jika ada error, tetap navigasi
                        window.location.href = this.href;
                    });
                }
            });
        });
    });
</script>