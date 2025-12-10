<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// === FUNGSI HELPER BARU UNTUK FORMAT WAKTU KSA/WIB ===
if (!function_exists('format_log_time')) {
    function format_log_time($timestamp) {
        // Asumsi: Waktu di DB adalah WIB (Asia/Jakarta), karena server kemungkinan berada di Indonesia.
        $dt_wib = new DateTime($timestamp, new DateTimeZone('Asia/Jakarta'));
        
        // Konversi ke KSA (Saudi Arabia Standard Time)
        $dt_ksa = clone $dt_wib;
        $dt_ksa->setTimezone(new DateTimeZone('Asia/Riyadh'));
        
        // Output format yang diminta
        $tanggal_indo = format_indo_date($dt_wib->format('Y-m-d'));
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

<h4 class="fw-bold py-3 mb-4">Update Tugas: <?php echo $item->deskripsi; ?></h4>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger d-flex align-items-center" role="alert"><i class="bx bx-error me-2"></i> <?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12 mb-4">
        <a href="<?php echo site_url('mytasks/grup_detail/' . $grup->id); ?>" class="btn btn-sm btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali ke Detail Grup
        </a>
    </div>
</div>

<div class="row">
    
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow">
            <div class="card-header bg-primary text-white p-3">
                <h6 class="mb-0 text-white"><i class="bx bx-list-check me-1"></i> Form Aksi Tugas</h6>
            </div>
            <?php echo form_open_multipart('mytasks/update_task'); ?>
                <div class="card-body">
                    <input type="hidden" name="grup_item_id" value="<?php echo $item->id; ?>">
                    <input type="hidden" name="old_foto_path" value="<?php echo $item->foto_bukti; ?>">
                    <input type="hidden" name="grup_id" value="<?php echo $grup->id; ?>">

                    <p class="mb-1"><strong>Grup:</strong> <span class="fw-semibold text-dark"><?php echo $grup->nama_grup; ?></span></p>
                    <p class="mb-1"><strong>Tanggal Item:</strong> <span class="fw-semibold text-dark"><?php echo format_indo_date($item->tanggal_item); ?></span></p>
                    <p class="mb-3">
                        <strong>PIC:</strong> 
                        <span class="text-primary fw-bold"><?php echo $item->pj_nama; ?> 
                            <i class="bx bx-star text-warning"></i>
                        </span>
                    </p>
                    <hr>

                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Status Eksekusi Wajib Dipilih</label>
                        <select name="status" id="modal_status" class="form-select" required>
                            <option value="Sukses" <?php echo ($item->status == 'Sukses') ? 'selected' : ''; ?>>Sukses</option>
                            <option value="Cukup" <?php echo ($item->status == 'Cukup') ? 'selected' : ''; ?>>Cukup</option>
                            <option value="Buruk" <?php echo ($item->status == 'Buruk') ? 'selected' : ''; ?>>Buruk</option>
                            <option value="Gagal" <?php echo ($item->status == 'Gagal') ? 'selected' : ''; ?>>Gagal</option>
                        </select>
                        <small class="form-text text-danger">Status Buruk atau Gagal akan memicu notifikasi real-time ke Admin!</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="catatan" class="form-label">Catatan / Keterangan Tambahan (Opsional)</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Foto Bukti (Maks. 5MB)</label>
                        <input type="file" name="foto_bukti" class="form-control">
                        <small class="form-text text-muted">Upload foto baru untuk tugas ini.</small>
                    </div>
                    
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Aksi</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card h-100 shadow">
            <div class="card-header bg-secondary text-white p-3">
                <h6 class="mb-0 text-white"><i class="bx bx-history me-1"></i> Log Perubahan Tugas</h6>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($riwayat)): ?>
                    <ul class="list-group list-group-flush">
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
                                // Mencari status, catatan, dan foto_bukti di string perubahan
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
                                    $color_class = $status_colors[$status] ?? 'text-muted';
                                    $highlighted_status = "<span class='text-{$color_class} fw-bold'>'{$status}'</span>";
                                    $perubahan_log = str_replace("'$status'", $highlighted_status, $perubahan_log);
                                }
                                
                                $status_baru_badge = end($status_log);
                                $badge_class = $status_colors[$status_baru_badge] ?? 'secondary';
                                
                                // 2. Hapus teks "User [Nama] " dan path Foto Bukti dari deskripsi perubahan
                                $perubahan_text_clean = str_replace('User ' . $log->user_pengubah_nama . ' ', '', $perubahan_log);
                                if ($log_foto_path) {
                                     $perubahan_text_clean = preg_replace("/Foto Bukti: (.*?)(\s|$)/", '', $perubahan_text_clean);
                                }
                                
                                // --- NORMALISASI PATH GAMBAR UNTUK LINK ---
                                $normalized_path = '';
                                if ($log_foto_path) {
                                    // Mengisolasi nama file dan merangkai ulang path agar 100% benar dengan base_url()
                                    $file_name_only = basename($log_foto_path); 
                                    $normalized_path = 'assets/uploads/bukti/' . $file_name_only;
                                }

                            ?>
                            <li class="list-group-item">
                                <p class="mb-1 d-flex justify-content-between align-items-center">
                                    <span class="d-inline-block text-dark fw-bold">
                                        <?php echo $log->user_pengubah_nama; ?> <small class="text-muted">(<?php echo $log_time['tanggal']; ?>)</small>
                                        <span class="badge bg-<?php echo $badge_class; ?> text-white ms-1">
                                            <?php echo $status_baru_badge; ?>
                                        </span>
                                    </span>
                                    
                                    <small class="fw-bold text-muted">
                                        <?php echo $log_time['wib']; ?> WIB | <?php echo $log_time['ksa']; ?> KSA
                                    </small>
                                </p>
                                
                                <p class="mb-0 text-body">
                                    <?php if ($log_catatan): ?>
                                        <span class="fw-bold text-dark">Catatan:</span> <?php echo $log_catatan; ?><br>
                                    <?php endif; ?>
                                    
                                    <span class="text-xs d-block text-muted mt-1"><?php echo nl2br($perubahan_text_clean); ?></span>

                                    <?php if ($normalized_path): ?>
                                        <a href="<?php echo base_url($normalized_path); ?>" target="_blank" class="btn btn-sm btn-outline-success py-1 px-2 mt-2 float-end">
                                            <i class="bx bx-camera me-1"></i> Bukti
                                        </a>
                                    <?php endif; ?>
                                </p> 
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