<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Asumsi: $title, $mode, dan $template_data sudah disiapkan oleh controller.
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data / Template /</span> <?php echo $title; ?>
    </h4>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-plus-circle me-2"></i> <?php echo $title; ?>
                    </h5>
                    <a href="<?php echo site_url('admin/templates'); ?>" class="btn btn-sm btn-secondary">
                        <i class="bx bx-left-arrow-alt me-1"></i> Kembali ke Daftar
                    </a>
                </div>
                
                <div class="card-body">
                    
                    <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bx bx-error-alt me-1"></i> 
                            <?php echo $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                        // Tentukan URL aksi (tambah atau edit)
                        $action_url = site_url('admin/template_action' . ($mode == 'edit' ? '/' . $template_data->id : ''));
                        echo form_open($action_url, 'id="templateForm"'); 
                    ?>
                    
                        <div class="mb-3">
                            <label class="form-label" for="nama_template">Nama Template</label>
                            <input type="text" name="nama_template" id="nama_template" class="form-control" 
                                value="<?php echo set_value('nama_template', $template_data ? $template_data->nama_template : ''); ?>" required>
                            <?php echo form_error('nama_template', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="lama_manasik">Lama Manasik (Hari)</label>
                                    <input type="number" name="lama_manasik" id="lama_manasik" class="form-control" 
                                        value="<?php echo set_value('lama_manasik', $template_data ? $template_data->lama_manasik : 0); ?>" min="0" required>
                                    <small class="text-muted d-block">Jumlah hari persiapan sebelum keberangkatan.</small>
                                    <?php echo form_error('lama_manasik', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="lama_perjalanan">Lama Perjalanan (Hari)</label>
                                    <input type="number" name="lama_perjalanan" id="lama_perjalanan" class="form-control" 
                                        value="<?php echo set_value('lama_perjalanan', $template_data ? $template_data->lama_perjalanan : ''); ?>" min="1" required>
                                    <small class="text-muted d-block">Durasi program di lapangan (misal: 9 hari umroh).</small>
                                    <?php echo form_error('lama_perjalanan', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bx bx-save me-2"></i> Simpan Template
                            </button>
                        </div>

                    <?php echo form_close(); ?>
                </div>
                
            </div>
        </div>
    </div>
</div>