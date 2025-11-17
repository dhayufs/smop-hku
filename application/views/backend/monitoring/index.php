<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    
    <?php if (!empty($notifikasi_admin)): ?>
        <div class="alert alert-warning bg-yellow-100 text-yellow-800 p-3 rounded mb-4 d-flex justify-between items-center">
            🔔 <?php echo count($notifikasi_admin); ?> Notifikasi Baru! Ada tugas yang dilaporkan Buruk/Gagal oleh tim lapangan.
            <a href="<?php echo site_url('admin/notifications'); ?>" class="btn bg-warning-500 text-white hover:bg-warning-600 btn-sm">Lihat Detail</a>
        </div>
    <?php endif; ?>

    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="activity" class="w-4 h-4 mr-2 inline-block"></i> Grup Aktif (Monitoring Real-time)
            </h5>
            <a href="<?php echo site_url('admin/grup_form'); ?>" class="btn bg-success-500 text-white hover:bg-success-600">
                <i data-feather="plus" class="w-4 h-4 mr-2 inline-block"></i> Buat Grup Baru
            </a>
        </div>
        
        <div class="card-body p-6">
            <div class="grid grid-cols-12 gap-6">
                
                <?php if (empty($active_groups)): ?>
                    <div class="col-span-12 text-center py-5">
                        <p class="text-muted">Tidak ada Grup Perjalanan yang sedang Aktif atau Persiapan.</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($active_groups as $grup): ?>
                    <?php 
                        // --- Logika Style HIJAU MENYALA untuk Grup Aktif ---
                        $progress_color = ($grup['progress_persen'] == 100) ? 'bg-success-500' : 
                                          ($grup['progress_persen'] < 50 ? 'bg-danger-500' : 'bg-warning-500');
                        $status_badge = ($grup['status_label'] == 'Sedang Berjalan') ? 'bg-success-500' : 'bg-info-500';
                    ?>
                    <div class="col-span-12 md:col-span-6 lg:col-span-4">
                        <div class="card shadow-lg border-2 border-success-500">
                            <div class="card-body p-4">
                                <h5 class="font-bold text-lg mb-1">
                                    <?php echo $grup['nama_grup']; ?> 
                                </h5>
                                <h6 class="card-subtitle mb-3 text-sm">
                                    <span class="badge <?php echo $status_badge; ?> text-white"><?php echo $grup['status_label']; ?></span>
                                </h6>
                                <p class="text-sm mb-3">
                                    <i data-feather="calendar" class="w-4 h-4 mr-1 inline-block"></i> Berangkat: <?php echo date('d M Y', strtotime($grup['tanggal_keberangkatan'])); ?>
                                </p>

                                <div class="mt-4">
                                    <p class="text-sm font-semibold mb-1">Progres Tugas: <?php echo $grup['progress_persen']; ?>%</p>
                                    <div>
                                        <div class="progress-bar <?php echo $progress_color; ?>" role="progressbar" style="width: <?php echo $grup['progress_persen']; ?>%;" aria-valuenow="<?php echo $grup['progress_persen']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted block">Gagal/Buruk: <span class="font-bold text-danger-500"><?php echo $grup['item_gagal_buruk']; ?></span></small>
                                </div>

                                <div class="d-flex justify-content-between items-center mt-3">
                                    <a href="<?php echo site_url('admin/grup_detail/' . $grup['grup_id']); ?>" class="btn btn-sm bg-info-500 text-white hover:bg-info-600">
                                        <i data-feather="eye" class="w-4 h-4 mr-1 inline-block"></i> Detail Monitoring
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6 mt-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800">
            <h5 class="mb-0 font-medium">
                <i data-feather="archive" class="w-4 h-4 mr-2 inline-block"></i> Grup Selesai / Arsip (Lihat Laporan)
            </h5>
        </div>
        <div class="card-body p-6">
            <div class="grid grid-cols-12 gap-6">
                <?php if (empty($archived_groups)): ?>
                    <div class="col-span-12 text-center py-3">
                        <p class="text-muted">Tidak ada Grup Selesai yang tersimpan dalam arsip 10 hari terakhir.</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($archived_groups as $grup): ?>
                    <?php 
                        // --- Logika Style ABU-ABU untuk Grup Arsip ---
                        $status_badge = 'bg-secondary-500';
                        $card_style = 'opacity: 0.6;';
                    ?>
                    <div class="col-span-12 md:col-span-6 lg:col-span-4">
                        <div class="card shadow-md border border-secondary-500" style="<?php echo $card_style; ?>">
                            <div class="card-body p-4">
                                <h5 class="font-bold text-lg mb-1"><?php echo $grup['nama_grup']; ?></h5>
                                <h6 class="card-subtitle mb-3 text-sm">
                                    <span class="badge <?php echo $status_badge; ?> text-white"><?php echo $grup['status_label']; ?></span>
                                </h6>
                                <p class="text-sm mb-3">
                                    <i data-feather="calendar" class="w-4 h-4 mr-1 inline-block"></i> Keberangkatan: <?php echo date('d M Y', strtotime($grup['tanggal_keberangkatan'])); ?>
                                </p>

                                <div class="d-flex justify-content-between items-center mt-3">
                                    <a href="<?php echo site_url('admin/grup_detail/' . $grup['grup_id']); ?>" class="btn btn-sm bg-secondary-500 text-white hover:bg-secondary-600">
                                        <i data-feather="file-text" class="w-4 h-4 mr-1 inline-block"></i> Lihat Laporan
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

<script>if (typeof feather !== 'undefined') { feather.replace(); }</script>