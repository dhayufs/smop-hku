<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    
    <?php if (!empty($notifikasi_admin)): ?>
        <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-bell me-2"></i> 
                <strong><?php echo count($notifikasi_admin); ?> Notifikasi Baru!</strong> Ada tugas yang dilaporkan Buruk/Gagal oleh tim lapangan.
            </div>
            <a href="<?php echo site_url('admin/notifications'); ?>" class="btn btn-warning btn-sm">
                Lihat Detail <i class="bx bx-right-arrow-alt ms-1"></i>
            </a>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-line-chart me-2"></i> Grup Aktif (Monitoring Real-time)
            </h5>
            <a href="<?php echo site_url('admin/grup_form'); ?>" class="btn btn-primary">
                <i class="bx bx-plus me-2"></i> Buat Grup Baru
            </a>
        </div>
        
        <div class="card-body">
            <div class="row g-4">
                
                <?php if (empty($active_groups)): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted mb-0">Tidak ada Grup Perjalanan yang sedang Aktif atau Persiapan.</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($active_groups as $grup): ?>
                    <?php 
                        // --- Logika Style untuk Grup Aktif ---
                        $progress_color = ($grup['progress_persen'] == 100) ? 'bg-success' : 
                                          ($grup['progress_persen'] < 50 ? 'bg-danger' : 'bg-warning');
                        $status_badge = ($grup['status_label'] == 'Sedang Berjalan') ? 'bg-success' : 'bg-info';
                    ?>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow border-3 <?php echo ($grup['status_label'] == 'Sedang Berjalan') ? 'border-success' : 'border-info'; ?>">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-1">
                                    <?php echo $grup['nama_grup']; ?> 
                                </h5><br>
                                <h6 class="card-subtitle mb-3">
                                    <span class="badge rounded-pill <?php echo $status_badge; ?>"><?php echo $grup['status_label']; ?></span>
                                </h6>
                                <p class="card-text small mb-3">
                                    <i class="bx bx-calendar me-1"></i> Berangkat: <?php echo date('d M Y', strtotime($grup['tanggal_keberangkatan'])); ?>
                                </p>

                                <div class="mt-4">
                                    <p class="small fw-semibold mb-1">Progres Tugas: <?php echo $grup['progress_persen']; ?>%</p>
                                    <div class="progress mb-1" style="height: 6px;">
                                        <div class="progress-bar <?php echo $progress_color; ?>" role="progressbar" style="width: <?php echo $grup['progress_persen']; ?>%;" aria-valuenow="<?php echo $grup['progress_persen']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted d-block">Gagal/Buruk: <span class="fw-bold text-danger"><?php echo $grup['item_gagal_buruk']; ?></span></small>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <a href="<?php echo site_url('admin/grup_detail/' . $grup['grup_id']); ?>" class="btn btn-sm btn-info">
                                        <i class="bx bx-show me-1"></i> Detail Monitoring
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0">
                <i class="bx bx-archive me-2"></i> Grup Selesai / Arsip (Lihat Laporan)
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <?php if (empty($archived_groups)): ?>
                    <div class="col-12 text-center py-3">
                        <p class="text-muted mb-0">Tidak ada Grup Selesai yang tersimpan dalam arsip 10 hari terakhir.</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($archived_groups as $grup): ?>
                    <?php 
                        // --- Logika Style ABU-ABU untuk Grup Arsip ---
                        $status_badge = 'bg-secondary';
                    ?>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border border-secondary">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-1"><?php echo $grup['nama_grup']; ?></h5>
                                <h6 class="card-subtitle mb-3 small">
                                    <span class="badge rounded-pill <?php echo $status_badge; ?>"><?php echo $grup['status_label']; ?></span>
                                </h6>
                                <p class="card-text small mb-3 text-muted">
                                    <i class="bx bx-calendar me-1"></i> Keberangkatan: <?php echo date('d M Y', strtotime($grup['tanggal_keberangkatan'])); ?>
                                </p>

                                <div class="d-flex justify-content-end mt-3">
                                    <a href="<?php echo site_url('admin/grup_detail/' . $grup['grup_id']); ?>" class="btn btn-sm btn-secondary">
                                        <i class="bx bx-file me-1"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>