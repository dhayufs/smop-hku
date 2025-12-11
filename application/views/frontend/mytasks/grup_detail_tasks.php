<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Helper function format_indo_date is assumed to be available globally now.

// Cek apakah ada flashdata error atau success
if ($this->session->flashdata('success')) {
    echo '<div class="alert alert-success alert-dismissible" role="alert">
            <i class="bx bx-check-circle me-2"></i>' . $this->session->flashdata('success') . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
if ($this->session->flashdata('error')) {
    echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <i class="bx bx-error me-2"></i>' . $this->session->flashdata('error') . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}

$user_id = $this->session->userdata('user_id');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-3 mb-0">Detail Grup: <?php echo $grup->nama_grup; ?></h4>
    <a href="<?php echo site_url('mytasks'); ?>" class="btn btn-primary">
        <i class='bx bx-arrow-back me-1'></i> Kembali ke Daftar Grup
    </a>
</div>

<div class="row">
    
    <div class="col-lg-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-label-info">
                <h5 class="card-title mb-0 text-info"><i class="bx bx-info-circle me-1"></i> Informasi Grup & Penanggung Jawab</h5>
            </div>
            <br>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h6 class="text-dark fw-bold mb-3"><i class="bx bx-package me-1"></i> <?php echo $grup->nama_grup; ?></h6>
                        <ul class="list-unstyled small">
                            <li class="mb-1"><span class="fw-semibold">Tgl. Mulai Manasik:</span> 
                                <?php echo format_indo_date($grup->tanggal_mulai_manasik); ?>
                            </li>
                            <li class="mb-1"><span class="fw-semibold">Tgl. Keberangkatan:</span> 
                                <?php echo format_indo_date($grup->tanggal_keberangkatan); ?>
                            </li>
                            <li class="mb-1"><span class="fw-semibold">Tgl. Pulang:</span> 
                                <?php echo format_indo_date($grup->tanggal_pulang); ?>
                            </li>
                            <li class="mt-2"><span class="fw-semibold">Template Asal:</span> <span class="badge bg-label-secondary"><?php echo $grup->nama_template; ?></span></li>
                        </ul>
                    </div>
                    <div class="col-md-6 border-start">
                        <h6 class="fw-bold mb-3 text-secondary">Penanggung Jawab Tim:</h6>
                        <ul class="list-group list-group-flush small">
                            <?php if (!empty($penugasan)): ?>
                                <?php foreach ($penugasan as $p): ?>
                                    <li class="list-group-item d-flex align-items-center p-1 ps-0">
                                        <i class="bx bx-user-circle me-2 text-primary"></i> 
                                        <span class="text-muted" style="width: 40%;"><?php echo $p->nama_peran; ?>:</span> 
                                        <strong class="text-dark"><?php echo $p->nama_lengkap; ?></strong>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="text-muted">- Belum ada penugasan tim.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bx bx-calendar-check me-2 text-primary"></i> Jadwal Tugas Harian</h5>
            </div>
            <div class="card-body">
                
                <?php 
                $tanggal_sekarang = date('Y-m-d');
                $target_date = $tanggal_target ?: $tanggal_sekarang;
                ?>

                <div class="accordion mt-2" id="taskAccordion">
                    <?php if (!empty($grouped_items)): ?>
                        <?php foreach ($grouped_items as $tanggal => $blocks): ?>
                            <?php 
                            $is_active = $tanggal == $target_date;
                            $header_class = $is_active ? 'bg-primary' : 'bg-label-secondary'; 
                            $title_date = format_indo_date($tanggal);
                            
                            $date_obj = new DateTime($tanggal);
                            $is_past = $date_obj < new DateTime($tanggal_sekarang);
                            $is_future = $date_obj > new DateTime($tanggal_sekarang);
                            
                            $status_info = '';
                            if ($tanggal == $tanggal_sekarang) {
                                $status_info = '<span class="badge bg-danger">HARI INI</span>';
                            } elseif ($is_past) {
                                $status_info = '<span class="badge bg-secondary">LEWAT</span>';
                            } elseif ($is_future) {
                                $status_info = '<span class="badge bg-info">AKAN DATANG</span>';
                            }
                            $text_class = $is_active ? 'text-white' : 'text-dark';
                            ?>

                            <div class="card mb-2 border">
                                <div class="card-header <?php echo $header_class; ?> py-2" id="heading_<?php echo $tanggal; ?>">
                                    <h7 class="mb-0">
                                        <button class="btn btn-link <?php echo $text_class; ?> fw-bold w-100 text-start d-flex justify-content-between" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse_<?php echo $tanggal; ?>" 
                                                aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>" 
                                                aria-controls="collapse_<?php echo $tanggal; ?>">
                                            
                                            <div class="d-flex flex-column text-start">
                                                <span class="d-block"><?php echo $title_date; ?></span>
                                                <span class="d-block mt-1"><?php echo $status_info; ?></span>
                                            </div>
                                            
                                            <i class="bx bx-chevron-down align-self-start ms-2"></i>
                                        </button>
                                    </h7>
                                </div>

                                <div id="collapse_<?php echo $tanggal; ?>" class="collapse <?php echo $is_active ? 'show' : ''; ?>" aria-labelledby="heading_<?php echo $tanggal; ?>" data-bs-parent="#taskAccordion">
                                    <div class="card-body p-3">
                                        <?php 
                                        $block_order = ['manasik' => 'Manasik', 'perjalanan' => 'Perjalanan'];
                                        foreach ($block_order as $block_key => $block_title):
                                            if (isset($blocks[$block_key])):
                                                $items = $blocks[$block_key];
                                                $hari_ke = !empty($items) ? $items[0]->hari_ke : '';
                                        ?>
                                            <h6 class="mt-3 mb-2 text-decoration-underline text-secondary">
                                                <i class="bx bx-calendar-check me-1"></i> <?php echo $block_title; ?> (Hari ke-<?php echo $hari_ke; ?>)
                                            </h6>
                                            <ul class="list-group list-group-flush mb-4 border rounded">
                                                <?php foreach ($items as $item): ?>
                                                    <?php
                                                    $status_color = 'label-secondary';
                                                    if ($item->status == 'Sukses') $status_color = 'success';
                                                    elseif ($item->status == 'Cukup') $status_color = 'warning';
                                                    elseif ($item->status == 'Buruk' || $item->status == 'Gagal') $status_color = 'danger';
                                                    
                                                    // Tentukan apakah ini tugas user saat ini
                                                    $is_my_task = ($item->pj_user_id == $user_id);
                                                    
                                                    // Logika untuk menampilkan link Aksi
                                                    $show_action_button = ($item->tipe_item == 'checklist' && $is_my_task && $item->status != 'Sukses');

                                                    // Highlight tugas pengguna
                                                    $li_class = $is_my_task ? 'list-group-item list-group-item-action bg-label-light' : 'list-group-item';
                                                    $li_class .= ($item->tipe_item != 'checklist' ? ' bg-light text-muted' : '');
                                                    ?>
                                                    <li class="<?php echo $li_class; ?> d-flex justify-content-between align-items-center py-2 px-3 border-start-0 border-end-0">
                                                        <div class="me-auto d-flex flex-column flex-grow-1"> 
                                                            
                                                            <div class="fw-bold d-flex align-items-center text-dark mb-1">
                                                                <?php if ($item->tipe_item == 'checklist'): ?>
                                                                    <i class="bx bx-check-square me-2 <?php echo $is_my_task ? 'text-primary' : 'text-secondary'; ?>"></i>
                                                                    <span class="<?php echo $is_my_task ? 'text-primary' : 'text-dark'; ?>">Tugas <?php echo $item->urutan; ?>:</span>
                                                                <?php else: ?>
                                                                    <i class="bx bx-info-circle me-2 text-info"></i>
                                                                    <span>Informasi <?php echo $item->urutan; ?>:</span>
                                                                <?php endif; ?>
                                                                <span class="ms-2"><?php echo $item->deskripsi; ?></span>
                                                            </div>
                                                            
                                                            <?php if ($item->tipe_item == 'checklist'): ?>
                                                                <div class="d-flex align-items-center ms-4 mb-1 small">
                                                                    <span class="text-muted me-2">Status:</span>
                                                                    <span class="badge rounded-pill bg-<?php echo $status_color; ?>"><?php echo $item->status; ?></span>
                                                                </div>
                                                                
                                                                <small class="text-muted ms-4 d-block">
                                                                    Pelaksana: <span class="fw-semibold <?php echo $is_my_task ? 'text-success' : ''; ?>"><?php echo $item->pj_nama ? $item->pj_nama : 'Belum Ditugaskan'; ?></span>
                                                                </small>
                                                            <?php endif; ?>
                                                            
                                                        </div>
                                                        
                                                        <?php if ($show_action_button): ?>
                                                            <a href="<?php echo site_url('mytasks/update_task_form/' . $item->id); ?>" 
                                                               class="btn btn-sm btn-primary align-self-center flex-shrink-0">
                                                                <i class="bx bx-edit-alt me-1"></i> Aksi
                                                            </a>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php 
                                            endif; 
                                        endforeach; 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info"><i class="bx bx-info-circle me-1"></i> Tidak ada tugas yang tersedia untuk grup ini.</div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
    
</div>