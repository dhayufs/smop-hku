<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800">
            <h5 class="mb-0 font-medium">
                <i data-feather="edit" class="w-4 h-4 mr-2 inline-block"></i> Edit Peran Tugas: <?php echo $peran_data->nama_peran; ?>
            </h5>
        </div>
        
        <div class="card-body p-6 grid grid-cols-12 gap-x-6 justify-center">
            
            <div class="col-span-12 lg:col-span-6">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                
                <?php echo form_open('admin/role_action/edit/' . $peran_data->id); ?>
                    <div class="form-group mb-3">
                        <label class="text-sm font-medium">Nama Peran</label>
                        <input type="text" name="nama_peran" class="form-control" value="<?php echo set_value('nama_peran', $peran_data->nama_peran); ?>" required>
                        <?php echo form_error('nama_peran', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-sm font-medium">Deskripsi Tugas</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?php echo set_value('deskripsi', $peran_data->deskripsi); ?></textarea>
                    </div>
                    <div class="flex justify-between">
                        <button type="submit" class="btn bg-info-500 text-white hover:bg-info-600">
                            <i data-feather="save" class="w-4 h-4 mr-2 inline-block"></i> Update Peran
                        </button>
                        <a href="<?php echo site_url('admin/roles'); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600">
                            Batal
                        </a>
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