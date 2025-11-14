<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="bell" class="w-4 h-4 mr-2 inline-block"></i> Notifikasi Tugas Buruk/Gagal
            </h5>
            <a href="<?php echo site_url('admin'); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline-block"></i> Kembali ke Dashboard
            </a>
        </div>
        
        <div class="card-body p-6">
            
            <?php if (isset($notifications) && !empty($notifications)): ?>
                <div class="alert alert-info bg-blue-100 text-blue-800 p-3 rounded mb-4">
                    Ini adalah daftar riwayat notifikasi yang dipicu Tim Lapangan saat status tugas dilaporkan **Buruk** atau **Gagal**.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-sm font-semibold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700">
                                <th class="py-2 px-3 text-left">WAKTU LAPOR</th>
                                <th class="py-2 px-3 text-left">GRUP & ITEM</th>
                                <th class="py-2 px-3 text-left">PESAN NOTIFIKASI</th>
                                <th class="py-2 px-3 text-center">STATUS BACA</th>
                                <th class="py-2 px-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $notif): ?>
                            <?php
                                $waktu = date('d M Y H:i', strtotime($notif->created_at));
                                $status_baca_class = $notif->is_read ? 'bg-secondary-500' : 'bg-warning-500';
                                $status_baca_text = $notif->is_read ? 'Sudah Dibaca' : 'Baru';
                            ?>
                            <tr class="text-sm border-t border-theme-border dark:border-themedark-border">
                                <td class="py-3 px-3 text-left"><?php echo $waktu; ?></td>
                                <td class="py-3 px-3 text-left">
                                    <p class="font-medium"><?php echo isset($notif->nama_grup) ? $notif->nama_grup : 'N/A'; ?></p>
                                    <small class="text-muted block">Item: <?php echo $notif->deskripsi; ?></small>
                                </td>
                                <td class="py-3 px-3 font-medium text-danger-600"><?php echo $notif->pesan; ?></td>
                                <td class="py-3 px-3 text-center">
                                    <span class="badge <?php echo $status_baca_class; ?> text-white"><?php echo $status_baca_text; ?></span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <a href="<?php echo site_url('admin/grup_detail/' . $notif->grup_item_id); ?>" class="btn btn-sm bg-danger-500 text-white hover:bg-danger-600" title="Tindak Lanjut">
                                        <i data-feather="alert-octagon" class="w-4 h-4"></i> Tindak Lanjut
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-success text-center bg-green-100 text-green-800 p-4 rounded">
                    Tidak ada notifikasi tugas Buruk atau Gagal yang tercatat saat ini. Sistem berjalan lancar!
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<script>
    // Pastikan feather icons di-replace setelah konten dimuat
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>