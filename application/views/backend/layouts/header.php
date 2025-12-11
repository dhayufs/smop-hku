<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user_role = $this->session->userdata('system_role'); 
?>
<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="<?php echo base_url('assets/sneat'); ?>/" data-template="vertical-menu-template-free">

<head>
    <title><?php echo isset($title) ? $title : 'SMOP Haramainku'; ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/sneat/img/favicon/favicon.ico'); ?>" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/fonts/boxicons.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/css/core.css'); ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/css/theme-default.css'); ?>" class="template-customizer-theme-css" />
    
    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.css'); ?>" />

    <link rel="stylesheet" href="<?php echo base_url('assets/sneat/css/demo.css'); ?>" />

    <script src="<?php echo base_url('assets/sneat/vendor/js/helpers.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/js/config.js'); ?>"></script>

    <style>
        /* Perbaikan kecil untuk notifikasi agar terlihat bagus di Sneat */
        .notification-icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            font-size: 1.2rem;
        }
        /* Style untuk logo baru di sidebar */
        .app-brand-link .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 5px 0;
        }
        .app-brand-link .logo-img {
            max-height: 45px; /* Sesuaikan tinggi logo */
            width: auto;
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="<?php echo site_url('admin'); ?>" class="app-brand-link">
                        <div class="logo-container">
                            <img src="https://haramaintour.com/wp-content/uploads/2025/06/logo-haramainku-haramaintour-kartika-utama.jpg" alt="Logo Haramainku" class="logo-img">
                        </div>
                    </a>
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-item">
                        <a href="<?php echo site_url('admin'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Monitoring & Operasional</span></li>
                    
                    <li class="menu-item">
                        <a href="<?php echo site_url('admin/monitoring'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-desktop"></i>
                            <div data-i18n="Monitoring Grup">Monitoring Grup</div>
                        </a>
                    </li>
                    <?php if (isset($user_role) && $user_role == 'admin'): ?>
                    <li class="menu-item">
                        <a href="<?php echo site_url('admin/grup_form'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-plus-circle"></i>
                            <div data-i18n="Buat Grup Baru">Buat Grup Baru</div>
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Perencanaan & Master Data</span></li>

                    <li class="menu-item">
                        <a href="<?php echo site_url('admin/templates'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-copy"></i>
                            <div data-i18n="Template Itinerary">Template Itinerary</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?php echo site_url('admin/roles'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-group"></i>
                            <div data-i18n="Master Peran Tugas">Master Peran Tugas</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Administrasi & Laporan</span></li>

                    <li class="menu-item">
                        <a href="<?php echo site_url('admin/users'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="Manajemen User">Manajemen User</div>
                        </a>
                    </li>
                    
                    <li class="menu-item">
                        <a href="<?php echo site_url('admin/laporan_kinerja'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-bar-chart-alt"></i>
                            <div data-i18n="Laporan Kinerja">Laporan Kinerja</div>
                        </a>
                    </li>
                    
                    <li class="menu-item">
                        <a href="<?php echo site_url('admin/notifications'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-bell"></i>
                            <div data-i18n="Notifikasi Buruk">Notifikasi Buruk</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?php echo site_url('auth/logout'); ?>" class="menu-link text-danger">
                            <i class="menu-icon tf-icons bx bx-power-off"></i>
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
                            <div class="nav-item d-flex align-items-center me-3">
                                <h5 class="fw-bold mb-0">Sistem Monitoring Oprasional</h5>
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            
                            <li class="nav-item dropdown dropdown-notifications navbar-dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-bell bx-sm"></i>
                                    <?php $unread_count_display = isset($unread_count) ? $unread_count : 0; ?>
                                    <?php if ($unread_count_display > 0): ?>
                                        <span class="badge rounded-pill badge-notifications bg-danger" id="notification-count">
                                            <?php echo $unread_count_display > 9 ? '9+' : $unread_count_display; ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="mb-0 me-auto">Notifikasi</h5>
                                            <a href="<?php echo site_url('admin/notifications'); ?>" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Semua">
                                                <i class="bx bx-menu-alt-right"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            <?php if (isset($recent_notifications) && !empty($recent_notifications)): ?>
                                                <?php foreach ($recent_notifications as $notif): ?>
                                                    <?php $bg_class = $notif->is_read == 0 ? 'bg-label-secondary' : ''; ?>
                                                    <li class="list-group-item list-group-item-action dropdown-notifications-item notification-card <?php echo $bg_class; ?>"
                                                        data-notif-id="<?php echo $notif->id; ?>" 
                                                        data-is-read="<?php echo $notif->is_read; ?>">
                                                        
                                                        <a href="<?php echo site_url('admin/item_history/' . $notif->grup_item_id); ?>" class="d-flex notification-link">
                                                            <div class="flex-shrink-0 me-3">
                                                                <div class="avatar avatar-online">
                                                                    <div class="avatar-initial rounded-circle bg-label-danger notification-icon-box">
                                                                        <i class="bx bx-error-alt bx-sm"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">Item <?php echo $notif->status; ?></h6>
                                                                <p class="mb-0 text-body">
                                                                    <?php echo $notif->nama_grup; ?>: <?php echo $notif->deskripsi; ?>
                                                                </p>
                                                                <small class="text-muted"><?php echo $notif->time_ago; ?></small>
                                                            </div>
                                                            <div class="flex-shrink-0 dropdown-notifications-actions">
                                                                <a href="javascript:void(0);" class="text-muted dismiss-notif-btn" data-notif-id="<?php echo $notif->id; ?>">
                                                                    <i class="bx bx-x"></i>
                                                                </a>
                                                            </div>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <li class="list-group-item text-center">
                                                    <p class="text-muted mb-0 py-2">Tidak ada notifikasi baru.</p>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
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
                        
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        
        // Fungsi inti untuk menandai notifikasi sebagai sudah dibaca (dismiss)
        const markAsReadAndProcess = (notifId, cardElement, shouldNavigate, originalHref) => {
            // Cek apakah sudah dibaca atau tidak (status '0' adalah belum dibaca)
            if (cardElement.dataset.isRead === '0') {
                
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
                    if (data.success) {
                        // 1. Hapus background notifikasi belum dibaca & set status di DOM
                        cardElement.classList.remove('bg-label-secondary');
                        cardElement.dataset.isRead = '1';
                        
                        // 2. Perbarui counter di header
                        const countElement = document.getElementById('notification-count');
                        if (countElement && data.unread_count !== undefined) {
                            const newCount = data.unread_count;
                            if (newCount > 0) {
                                countElement.textContent = newCount > 9 ? '9+' : newCount;
                            } else {
                                const badge = document.querySelector('.badge-notifications');
                                if(badge) badge.remove(); // Hapus badge jika 0
                            }
                        }
                    } else {
                        console.error('Failed to mark notification as read on server.');
                    }
                    
                    // 3. Lanjutkan aksi: Navigasi atau Hilangkan dari UI
                    if (shouldNavigate) {
                        window.location.href = originalHref;
                    } else {
                        // Jika dari tombol dismiss/silang, hapus elemen card dari tampilan
                        cardElement.remove();
                        
                        // Tambahkan pesan "Tidak ada notifikasi baru." jika list kosong
                        const list = document.querySelector('.dropdown-notifications-list .list-group-flush');
                        if (list && list.children.length === 0) {
                             list.innerHTML = `<li class="list-group-item text-center">
                                                <p class="text-muted mb-0 py-2">Tidak ada notifikasi baru.</p>
                                               </li>`;
                        }
                    }
                    
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                    // Jika ada error saat AJAX tapi ini adalah klik navigasi, tetap lanjutkan navigasi
                    if (shouldNavigate) {
                        window.location.href = originalHref;
                    }
                });
            } else if (shouldNavigate && originalHref) {
                 // Jika sudah dibaca (isRead = 1) dan ini adalah klik link, langsung navigasi
                 window.location.href = originalHref;
            }
        };


        // --- LOGIKA TOMBOL DISMISS (Ikon X) ---
        // Hanya untuk dismiss/menghilangkan dari dropdown. Tidak ada navigasi.
        document.querySelectorAll('.dismiss-notif-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); 
                e.stopPropagation(); // PENTING: Menghentikan event agar tidak memicu 'notification-link'
                
                const card = this.closest('.notification-card');
                const notifId = this.dataset.notifId;
                
                // Panggil fungsi markAsReadAndProcess, set shouldNavigate=false
                markAsReadAndProcess(notifId, card, false, null);
            });
            
            // Juga tangani klik pada ikon itu sendiri (jika ikon bukan tombol)
            button.querySelector('i').addEventListener('click', function(e) {
                e.preventDefault(); 
                e.stopPropagation(); // Mencegah bubbling ke elemen induk
                
                const card = this.closest('.notification-card');
                const notifId = this.closest('.dismiss-notif-btn').dataset.notifId;
                
                // Panggil fungsi markAsReadAndProcess, set shouldNavigate=false
                markAsReadAndProcess(notifId, card, false, null);
            });
        });

        // --- LOGIKA KLIK KARTU NOTIFIKASI (Untuk Navigasi) ---
        // Jika notif belum dibaca, markAsRead dan kemudian navigasi. Jika sudah dibaca, langsung navigasi.
        document.querySelectorAll('.notification-link').forEach(link => {
            link.addEventListener('click', function(e) {
                // Jangan proses jika target klik adalah tombol dismiss (karena sudah ditangani di atas dengan stopPropagation)
                if (e.target.closest('.dismiss-notif-btn')) {
                    return; 
                }
                
                const card = this.closest('.notification-card');
                const notifId = card.dataset.notifId;
                const originalHref = this.href;

                // Cegah navigasi default (untuk diproses di fungsi markAsReadAndProcess)
                e.preventDefault(); 
                
                // Panggil fungsi markAsReadAndProcess, set shouldNavigate=true
                markAsReadAndProcess(notifId, card, true, originalHref);

            });
        });

    });
</script>