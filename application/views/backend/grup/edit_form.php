<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Grup /</span> <?php echo $title; ?>
    </h4>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mb-4">
                
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    <?php echo $title; ?>
                    <a href="<?php echo site_url('admin/grup_detail/' . $grup_data->id); ?>" class="btn btn-sm btn-secondary">
                        <i class="bx bx-left-arrow-alt me-1"></i> Kembali ke Monitoring
                    </a>
                </h5>
                
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading mb-0">PERINGATAN! Perubahan Jadwal Otomatis</h4>
                                <p class="mb-0">Mengubah dan menyimpan "Tanggal Keberangkatan" atau "Template Asal" akan menggeser semua tanggal Manasik dan Perjalanan pada item checklist grup ini secara otomatis.</p>
                            </div>
                            
                            <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <i class="bx bx-error-alt me-1"></i> 
                                    <?php echo $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                                // Action URL untuk ALUR C.4: edit_grup_action (dimodifikasi untuk edit lengkap)
                                echo form_open('admin/edit_grup_action/' . $grup_data->id, 'id="editGrupForm"'); 
                            ?>
                            
                                <div class="card shadow-none border border-primary mb-3">
                                    <div class="card-header bg-label-primary">
                                        <h6 class="mb-0 text-primary">
                                            <i class="bx bx-package me-2"></i> 1. Detail Paket & Tanggal
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="nama_grup">Nama Grup</label>
                                            <input type="text" name="nama_grup" id="nama_grup" class="form-control" 
                                                value="<?php echo set_value('nama_grup', $grup_data->nama_grup); ?>" required>
                                            <?php echo form_error('nama_grup', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label" for="template_asal_id">Template Itinerary Asal</label>
                                            <select name="template_asal_id" id="template_asal_id" class="form-select" required>
                                                <option value="">-- Pilih Template --</option>
                                                <?php if (isset($templates)): ?>
                                                    <?php foreach ($templates as $t): ?>
                                                        <option value="<?php echo $t->id; ?>" 
                                                            <?php echo set_select('template_asal_id', $t->id, ($grup_data->template_asal_id == $t->id)); ?>>
                                                            <?php echo $t->nama_template; ?> (Total: <?php echo $t->lama_manasik + $t->lama_perjalanan; ?> Hari)
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <?php echo form_error('template_asal_id', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label" for="tanggal_mulai_manasik">Tanggal Mulai Manasik</label>
                                            <input type="date" name="tanggal_mulai_manasik" id="tanggal_mulai_manasik" class="form-control" 
                                                value="<?php echo set_value('tanggal_mulai_manasik', $grup_data->tanggal_mulai_manasik); ?>" required>
                                            <?php echo form_error('tanggal_mulai_manasik', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                                        </div>
                                        
                                        <div class="mb-0">
                                            <label class="form-label" for="tanggal_keberangkatan">Tanggal Keberangkatan</label>
                                            <input type="date" name="tanggal_keberangkatan" id="tanggal_keberangkatan" class="form-control" 
                                                value="<?php echo set_value('tanggal_keberangkatan', $grup_data->tanggal_keberangkatan); ?>" required>
                                            <?php echo form_error('tanggal_keberangkatan', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card shadow-none border border-info mb-5">
                                    <div class="card-header bg-label-info">
                                        <h6 class="mb-0 text-info">
                                            <i class="bx bx-group me-2"></i> 2. Penugasan Tim Lapangan (User ke Peran)
                                        </h6>
                                        <small class="text-muted d-block mt-1">Setiap peran yang ditugaskan di sini akan otomatis menerima checklist di aplikasi mereka.</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php if (isset($peran) && !empty($peran)): ?>
                                                <?php foreach ($peran as $p): ?>
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label"><?php echo $p->nama_peran; ?></label>
                                                            <select name="user_peran_<?php echo $p->id; ?>" class="form-select">
                                                                <option value="">-- Pilih User (Opsional) --</option>
                                                                <?php if (isset($users)): ?>
                                                                    <?php 
                                                                        $current_user_id = $penugasan_saat_ini[$p->id] ?? ''; 
                                                                        $selected_value = set_value('user_peran_' . $p->id, $current_user_id);
                                                                    ?>
                                                                    <?php foreach ($users as $u): ?>
                                                                        <?php if ($u->system_role == 'user'): ?>
                                                                            <option value="<?php echo $u->id; ?>" 
                                                                                <?php echo set_select('user_peran_' . $p->id, $u->id, ($selected_value == $u->id)); ?>>
                                                                                <?php echo $u->nama_lengkap; ?> (<?php echo $u->username; ?>)
                                                                            </option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                            <small class="text-muted d-block mt-1"><?php echo $p->deskripsi; ?></small>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="col-12">
                                                    <div class="alert alert-warning">Mohon buat Master Peran Tugas terlebih dahulu.</div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bx bx-save me-2"></i> Simpan Perubahan Grup
                                    </button>
                                </div>

                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>