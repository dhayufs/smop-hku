<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="plus-circle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $title; ?>
            </h5>
            <a href="<?php echo site_url('admin/templates'); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline-block"></i> Kembali ke Daftar
            </a>
        </div>
        
        <div class="card-body p-6 grid grid-cols-12 gap-x-6 justify-center">
            
            <div class="col-span-12 lg:col-span-8">
                
                <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
                    <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?></div>
                <?php endif; ?>
                
                <?php 
                    // Tentukan URL aksi (tambah atau edit)
                    $action_url = site_url('admin/template_action' . ($mode == 'edit' ? '/' . $template_data->id : ''));
                    echo form_open($action_url, 'id="templateForm"'); 
                ?>
                
                    <div class="form-group mb-3">
                        <label class="text-sm font-medium">Nama Template</label>
                        <input type="text" name="nama_template" class="form-control" 
                            value="<?php echo set_value('nama_template', $template_data ? $template_data->nama_template : ''); ?>" required>
                        <?php echo form_error('nama_template', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    
                    <div class="grid grid-cols-12 gap-x-6">
                        <div class="col-span-12 md:col-span-6">
                            <div class="form-group mb-3">
                                <label class="text-sm font-medium">Lama Manasik (Hari)</label>
                                <input type="number" name="lama_manasik" class="form-control" 
                                    value="<?php echo set_value('lama_manasik', $template_data ? $template_data->lama_manasik : 0); ?>" min="0" required>
                                <small class="text-muted block">Jumlah hari persiapan sebelum keberangkatan.</small>
                                <?php echo form_error('lama_manasik', '<small class="text-danger block">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <div class="form-group mb-4">
                                <label class="text-sm font-medium">Lama Perjalanan (Hari)</label>
                                <input type="number" name="lama_perjalanan" class="form-control" 
                                    value="<?php echo set_value('lama_perjalanan', $template_data ? $template_data->lama_perjalanan : ''); ?>" min="1" required>
                                <small class="text-muted block">Durasi program di lapangan (misal: 9 hari umroh).</small>
                                <?php echo form_error('lama_perjalanan', '<small class="text-danger block">', '</small>'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center mt-4">
                        <button type="submit" class="btn btn-lg bg-success-500 text-white hover:bg-success-600 shadow-2xl">
                            <i data-feather="save" class="w-5 h-5 mr-2 inline-block"></i> Simpan Template
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>
            
        </div>
    </div>
</div>

<script>
    // Pastikan feather icons di-replace setelah konten dimuat
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>