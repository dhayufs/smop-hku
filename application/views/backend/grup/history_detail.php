<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// === FUNGSI HELPER BARU UNTUK FORMAT WAKTU KSA/WIB ===
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

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Grup / Monitoring /</span> Riwayat Aksi
    </h4>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mb-4">
                
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    <i class="bx bx-history me-2"></i> Riwayat Aksi: <?php echo $item->deskripsi; ?>
                    <a href="<?php echo site_url('admin/grup_detail/' . $grup->id); ?>" class="btn btn-sm btn-secondary">
                        <i class="bx bx-left-arrow-alt me-1"></i> Kembali ke Monitoring Grup
                    </a>
                </h5>
                
                <div class="card-body"> 
                    <div class="row mb-4">
                        <div class="col-12">
                            <p class="small mb-1"><strong>Grup:</strong> <?php echo $grup->nama_grup; ?></p>
                            <p class="small mb-1"><strong>Tanggal Item:</strong> <?php echo function_exists('format_indo_date') ? format_indo_date($item->tanggal_item) : $item->tanggal_item; ?></p>
                            <p class="small mb-3"><strong>PIC Saat Ini:</strong> <span class="text-warning fw-bold"><?php echo $item->pj_nama ? $item->pj_nama : 'Belum Ditugaskan'; ?></span></p>
                            <hr class="my-3">
                        </div>
                    </div>
                    
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white p-3">
                            <h6 class="mb-0">Log Perubahan Tugas</h6>
                        </div>
                        <div class="list-group list-group-flush">
                            <?php if (!empty($riwayat)): ?>
                                <?php 
                                    $status_colors = [
                                        'Pending' => 'secondary', 
                                        'Sukses' => 'success', 
                                        'Cukup' => 'info', 
                                        'Buruk' => 'warning', 
                                        'Gagal' => 'danger'
                                    ];
                                ?>
                                <?php foreach ($riwayat as $log): ?>
                                    <?php 
                                        // LOGIC FOR PARSING: Replicated from mytasks/update_task_form.php
                                        preg_match_all("/'([^']+)'/", $log->perubahan, $matches);
                                        $status_log = $matches[1];
                                        $perubahan_log = $log->perubahan;
                                        
                                        // Mencari Foto Bukti Path
                                        preg_match("/Foto Bukti: (.+\.(?:jpg|jpeg|png|gif))/", $log->perubahan, $foto_match);
                                        $log_foto_path = $foto_match[1] ?? null;

                                        // Mencari Catatan
                                        preg_match("/Catatan: (.*?)(\sFoto Bukti:|$)/", $log->perubahan, $catatan_match);
                                        $log_catatan = $catatan_match[1] ?? null;

                                        $log_time = format_log_time($log->timestamp);
                                        
                                        // 1. Highlight Status
                                        foreach ($status_log as $status) {
                                            $color_class = $status_colors[$status] ?? 'muted';
                                            $highlighted_status = "<span class='fw-bold text-{$color_class}'>'{$status}'</span>";
                                            $perubahan_log = str_replace("'$status'", $highlighted_status, $perubahan_log);
                                        }
                                        
                                        $status_baru_badge = end($status_log);
                                        $badge_color_class = $status_colors[$status_baru_badge] ?? 'secondary';
                                        
                                        // START: Perbaikan Log Cleaning (Menghilangkan Path - Point 1)
                                        $perubahan_text_clean = $perubahan_log; // Start with highlighted text

                                        // 2a. Hapus bagian "Foto Bukti: [path]" secara agresif dari teks log
                                        $perubahan_text_clean = preg_replace("/\sFoto Bukti: (.+\.(?:jpg|jpeg|png|gif))/", '', $perubahan_text_clean);
                                        
                                        // 2b. Hapus bagian "Catatan: [teks]"
                                        $perubahan_text_clean = preg_replace("/Catatan: (.*?)(\s|$)/", '', $perubahan_text_clean);

                                        // 2c. Hapus bagian 'User [Nama]' dan trim
                                        $perubahan_text_clean = str_replace('User ' . $log->user_pengubah_nama . ' ', '', $perubahan_text_clean); 
                                        $perubahan_text_clean = trim($perubahan_text_clean);
                                        // END: Perbaikan Log Cleaning
                                        
                                        // --- NORMALISASI PATH GAMBAR UNTUK LINK ---
                                        $normalized_path = '';
                                        if ($log_foto_path) {
                                            $file_name_only = basename($log_foto_path); 
                                            $normalized_path = 'assets/uploads/bukti/' . $file_name_only; 
                                        }

                                    ?>
                                    <li class="list-group-item small p-3">
                                        
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            
                                            <div class="d-flex flex-column align-items-start me-4"> 
                                                
                                                <small class="fw-bold text-muted mb-1">
                                                    <?php echo $log_time['wib']; ?> WIB | <?php echo $log_time['ksa']; ?> KSA
                                                </small>
                                                
                                                <span class="d-inline-block fw-medium">
                                                    <?php echo $log->user_pengubah_nama; ?> 
                                                    <small class="text-muted">(<?php echo $log_time['tanggal']; ?>)</small>
                                                    <span class="badge rounded-pill bg-<?php echo $badge_color_class; ?> ms-1">
                                                        <?php echo $status_baru_badge; ?>
                                                    </span>
                                                </span>
                                            </div>

                                            <?php if ($normalized_path): ?>
                                                <div class="flex-shrink-0">
                                                    <a href="<?php echo base_url($normalized_path); ?>" target="_blank" 
                                                        class="btn btn-primary btn-sm shadow-sm">
                                                        <i class="bx bx-image-add me-1"></i> Bukti
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="text-secondary"> 
                                            
                                            <?php if ($log_catatan && trim($log_catatan) != "NULL"): ?>
                                                <p class="mb-1 small">
                                                    <span class="fw-bold text-dark">Catatan:</span> 
                                                    <span><?php echo $log_catatan; ?></span>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <?php if (empty($log_catatan) && !empty($perubahan_text_clean)): ?>
                                                <p class="mb-0 text-muted small text-wrap text-break">
                                                    <?php echo nl2br($perubahan_text_clean); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div> 
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="p-3 text-center text-muted">Belum ada riwayat perubahan untuk tugas ini.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>