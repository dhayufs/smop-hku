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
                    Ini adalah daftar riwayat notifikasi yang dipicu Tim Lapangan saat status tugas dilaporkan Buruk atau Gagal.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-sm font-semibold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700">
                                <th class="py-2 px-3 text-left">WAKTU LAPOR (WIB | KSA)</th>
                                <th class="py-2 px-3 text-left">GRUP & ITEM</th>
                                <th class="py-2 px-3 text-left">PESAN NOTIFIKASI</th>
                                <th class="py-2 px-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $notif): ?>
                            <?php
                                $dt_wib = new DateTime($notif->created_at, new DateTimeZone('Asia/Jakarta'));
                                $dt_ksa = clone $dt_wib;
                                $dt_ksa->setTimezone(new DateTimeZone('Asia/Riyadh'));
                                
                                $wib_time_full = $dt_wib->format('d M Y H:i') . ' WIB';
                                $ksa_time_only = $dt_ksa->format('H:i') . ' KSA';
                                $waktu_full = $wib_time_full . ' | ' . $ksa_time_only;
                            ?>
                            <tr class="text-sm border-t border-theme-border dark:border-themedark-border">
                                <td class="py-3 px-3 text-left"><?php echo $waktu_full; ?></td>
                                <td class="py-3 px-3 text-left">
                                    <p class="font-medium"><?php echo isset($notif->nama_grup) ? $notif->nama_grup : 'N/A'; ?></p>
                                    <small class="text-muted block">Item: <?php echo $notif->deskripsi; ?></small>
                                </td>
                                <td class="py-3 px-3 font-medium text-danger-600 whitespace-normal" style="word-break: break-all;"><?php echo $notif->pesan; ?></td>
                                <td class="py-3 px-3 text-center">
                                    <a href="<?php echo site_url('admin/item_history/' . $notif->grup_item_id); ?>" class="btn btn-sm bg-danger-500 text-white hover:bg-danger-600" title="Tindak Lanjut Detail Item">
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