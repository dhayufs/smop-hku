<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// === FUNGSI HELPER BARU UNTUK FORMAT WAKTU KSA/WIB ===
// Didefinisikan di sini untuk memastikan ketersediaan, mengandalkan helper format_indo_date yang seharusnya sudah dimuat
if (!function_exists('format_log_time')) {
    function format_log_time($timestamp) {
        // Asumsi: Waktu di DB adalah WIB (Asia/Jakarta)
        $dt_wib = new DateTime($timestamp, new DateTimeZone('Asia/Jakarta'));
        
        // Konversi ke KSA (Saudi Arabia Standard Time)
        $dt_ksa = clone $dt_wib;
        $dt_ksa->setTimezone(new DateTimeZone('Asia/Riyadh'));
        
        // Output format yang diminta
        $tanggal_indo = function_exists('format_indo_date') ? format_indo_date($dt_wib->format('Y-m-d')) : $dt_wib->format('d M Y');
        $wib_time = $dt_wib->format('H:i');
        $ksa_time = $dt_ksa->format('H:i');
        
        return [
            'tanggal' => $tanggal_indo,
            'wib' => $wib_time,
            'ksa' => $ksa_time
        ];
    }
}
// ========================================================
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="clock" class="w-4 h-4 mr-2 inline-block"></i> Riwayat Aksi: <?php echo $item->deskripsi; ?>
            </h5>
            <a href="<?php echo site_url('admin/grup_detail/' . $grup->id); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600 btn-sm">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline-block"></i> Kembali ke Monitoring Grup
            </a>
        </div>
        
        <div class="card-body p-6"> 
            <div class="row mb-4">
                <div class="col-md-12">
                    <p class="text-sm mb-1"><strong>Grup:</strong> <?php echo $grup->nama_grup; ?></p>
                    <p class="text-sm mb-1"><strong>Tanggal Item:</strong> <?php echo function_exists('format_indo_date') ? format_indo_date($item->tanggal_item) : $item->tanggal_item; ?></p>
                    <p class="text-sm mb-3"><strong>PIC Saat Ini:</strong> <span class="text-warning-500 font-weight-bold"><?php echo $item->pj_nama ? $item->pj_nama : 'Belum Ditugaskan'; ?></span></p>
                    <hr>
                </div>
            </div>
            
            <div class="card shadow-lg">
                <div class="card-header bg-primary-500 text-white p-3">
                    <h6 class="mb-0">Log Perubahan Tugas</h6>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($riwayat)): ?>
                        <ul class="list-group list-group-flush">
                            <?php 
                                $status_colors = [
                                    'Pending' => 'text-secondary-500', 
                                    'Sukses' => 'text-success-500', 
                                    'Cukup' => 'text-info-500', 
                                    'Buruk' => 'text-warning-500', 
                                    'Gagal' => 'text-danger-500'
                                ];
                            ?>
                            <?php foreach ($riwayat as $log): ?>
                                <?php 
                                    // LOGIC FOR PARSING: Replicated from mytasks/update_task_form.php
                                    preg_match_all("/'([^']+)'/", $log->perubahan, $matches);
                                    $status_log = $matches[1];
                                    $perubahan_log = $log->perubahan;
                                    
                                    // Mencari Foto Bukti Path (Perhatikan path di DB: ./assets/...)
                                    preg_match("/Foto Bukti: (.+\.(?:jpg|jpeg|png|gif))/", $log->perubahan, $foto_match);
                                    $log_foto_path = $foto_match[1] ?? null;

                                    // Mencari Catatan
                                    preg_match("/Catatan: (.*?)(\sFoto Bukti:|$)/", $log->perubahan, $catatan_match);
                                    $log_catatan = $catatan_match[1] ?? null;

                                    $log_time = format_log_time($log->timestamp);
                                    
                                    // 1. Highlight Status dengan warna dan bold
                                    foreach ($status_log as $status) {
                                        $color_class = str_replace('text-', 'font-bold text-', $status_colors[$status] ?? 'text-muted');
                                        $highlighted_status = "<span class='{$color_class}'>'{$status}'</span>";
                                        $perubahan_log = str_replace("'$status'", $highlighted_status, $perubahan_log);
                                    }
                                    
                                    $status_baru_badge = end($status_log);
                                    $badge_color_class = str_replace('text-', 'bg-', $status_colors[$status_baru_badge]) ?? 'bg-secondary-500';
                                    $badge_class = str_replace('-500', '', $badge_color_class); // e.g., bg-success
                                    
                                    // 2. Hapus teks "User [Nama] " dan path Foto Bukti dari deskripsi perubahan
                                    $perubahan_text_clean = str_replace('User ' . $log->user_pengubah_nama . ' ', '', $perubahan_log);
                                    
                                    // Hapus juga Catatan dan Foto Bukti dari text clean
                                    $perubahan_text_clean = preg_replace("/Catatan: (.*?)(\sFoto Bukti:|$)/", '', $perubahan_text_clean);
                                    if ($log_foto_path) {
                                         $perubahan_text_clean = preg_replace("/Foto Bukti: (.*?)(\s|$)/", '', $perubahan_text_clean);
                                    }
                                    
                                    // --- NORMALISASI PATH GAMBAR UNTUK LINK ---
                                    $normalized_path = '';
                                    if ($log_foto_path) {
                                        $file_name_only = basename($log_foto_path); 
                                        $normalized_path = 'assets/uploads/bukti/' . $file_name_only; 
                                    }

                                ?>
                                <li class="list-group-item text-sm p-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="d-inline-block font-medium">
                                            <?php echo $log->user_pengubah_nama; ?> 
                                            <small class="text-muted">(<?php echo $log_time['tanggal']; ?>)</small>
                                            <span class="badge <?php echo $badge_class; ?> text-white ml-1">
                                                <?php echo $status_baru_badge; ?>
                                            </span>
                                        </span>
                                        
                                        <small class="font-weight-bold text-gray-500 dark:text-gray-400">
                                            <?php echo $log_time['wib']; ?> WIB | <?php echo $log_time['ksa']; ?> KSA
                                        </small>
                                    </div>
                                    
                                    <div class="text-gray-700 dark:text-gray-300"> 
                                        
                                        <?php if ($log_catatan && trim($log_catatan) != "NULL"): ?>
                                            <p class="mb-1">
                                                <span class="font-bold text-dark dark:text-white">Catatan:</span> 
                                                <span><?php echo $log_catatan; ?></span>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <p class="mb-0 text-xs text-gray-600 dark:text-gray-400">
                                            <?php echo nl2br($perubahan_text_clean); ?>
                                        </p>
                                        
                                        <?php if ($normalized_path): ?>
                                            <div class="flex justify-end mt-2">
                                                <a href="<?php echo base_url($normalized_path); ?>" target="_blank" class="btn btn-sm bg-primary-500 text-white py-1 px-2 hover:bg-primary-600">
                                                    <i data-feather="image" class="w-3 h-3 mr-1"></i> Bukti
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div> 
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="p-3 text-center text-muted">Belum ada riwayat perubahan untuk tugas ini.</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Memastikan feather icons dimuat
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
