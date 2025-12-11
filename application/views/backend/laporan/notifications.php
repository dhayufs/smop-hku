<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Laporan /</span> Notifikasi Tugas Buruk/Gagal
    </h4>

    <div class="card">
        
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-bell me-2"></i> Notifikasi Tugas Buruk/Gagal
            </h5>
            <a href="<?php echo site_url('admin'); ?>" class="btn btn-sm btn-secondary">
                <i class="bx bx-left-arrow-alt me-1"></i> Kembali ke Dashboard
            </a>
        </div>
        
        <div class="card-body">
            
            <?php if (isset($notifications) && !empty($notifications)): ?>
                <div class="alert alert-info" role="alert">
                    Ini adalah daftar riwayat notifikasi yang dipicu **Tim Lapangan** saat status tugas dilaporkan **Buruk** atau **Gagal**.
                </div>
                
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-start">WAKTU LAPOR (WIB | KSA)</th>
                                <th class="text-start">GRUP & ITEM</th>
                                <th class="text-start">PESAN NOTIFIKASI</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php foreach ($notifications as $notif): ?>
                            <?php
                                // Logic konversi waktu tetap dipertahankan
                                $dt_wib = new DateTime($notif->created_at, new DateTimeZone('Asia/Jakarta'));
                                $dt_ksa = clone $dt_wib;
                                $dt_ksa->setTimezone(new DateTimeZone('Asia/Riyadh'));
                                
                                $wib_time_full = $dt_wib->format('d M Y H:i') . ' WIB';
                                $ksa_time_only = $dt_ksa->format('H:i') . ' KSA';
                                $waktu_full = $wib_time_full . ' | ' . $ksa_time_only;
                            ?>
                            <tr>
                                <td class="text-start small"><?php echo $waktu_full; ?></td>
                                <td class="text-start">
                                    <p class="fw-medium mb-0"><?php echo isset($notif->nama_grup) ? $notif->nama_grup : 'N/A'; ?></p>
                                    <small class="text-muted d-block">Item: <?php echo $notif->deskripsi; ?></small>
                                </td>
                                <td class="fw-medium text-danger text-wrap" style="word-break: break-word; max-width: 300px;"><?php echo $notif->pesan; ?></td>
                                <td class="text-center">
                                    <a href="<?php echo site_url('admin/item_history/' . $notif->grup_item_id); ?>" class="btn btn-sm btn-danger" title="Tindak Lanjut Detail Item">
                                        <i class="bx bx-error-alt me-1"></i> Tindak Lanjut
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-success text-center" role="alert">
                    <h5 class="alert-heading mb-1"><i class="bx bx-check-circle me-1"></i> Semua Berjalan Lancar!</h5>
                    Tidak ada notifikasi tugas Buruk atau Gagal yang tercatat saat ini.
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>