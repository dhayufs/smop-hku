<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="edit" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $title; ?>
            </h5>
            <a href="<?php echo site_url('admin/users'); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline-block"></i> Kembali ke Daftar
            </a>
        </div>
        
        <div class="card-body p-6 grid grid-cols-12 gap-x-6 justify-center">
            
            <div class="col-span-12 lg:col-span-8">
                
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4"><i data-feather="check-circle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $this->session->flashdata('success'); ?></div>
                <?php endif; ?>
                
                <?php 
                    // Action URL untuk UPDATE user
                    echo form_open('admin/user_action/' . $user_data->id, 'id="editUserForm"'); 
                ?>
                
                    <div class="form-group mb-3">
                        <label class="text-sm font-medium">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" 
                            value="<?php echo set_value('nama_lengkap', $user_data->nama_lengkap); ?>" required>
                        <?php echo form_error('nama_lengkap', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="text-sm font-medium">Username</label>
                        <input type="text" name="username" class="form-control" 
                            value="<?php echo set_value('username', $user_data->username); ?>" required>
                        <?php echo form_error('username', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="text-sm font-medium">Role Sistem</label>
                        <select name="system_role" class="form-control" required>
                            <option value="admin" <?php echo set_select('system_role', 'admin', ($user_data->system_role == 'admin')); ?>>Admin (Pusat Kendali)</option>
                            <option value="user" <?php echo set_select('system_role', 'user', ($user_data->system_role == 'user')); ?>>User (Tim Lapangan)</option>
                        </select>
                        <?php echo form_error('system_role', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    
                    <div class="alert alert-warning bg-yellow-100 text-yellow-800 p-3 rounded my-4">
                        <i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i>
                        Kosongkan field di bawah jika tidak ingin mengganti Password.
                    </div>
                    
                    <div class="form-group mb-4">
                        <label class="text-sm font-medium">Password Baru (Untuk Reset)</label>
                        <input type="password" name="password" class="form-control">
                        <?php echo form_error('password', '<small class="text-danger block">', '</small>'); ?>
                    </div>

                    <div class="flex justify-between mt-4">
                        <button type="submit" class="btn bg-info-500 text-white hover:bg-info-600">
                            <i data-feather="save" class="w-4 h-4 mr-2 inline-block"></i> Update Akun
                        </button>
                        
                        <a href="<?php echo site_url('admin/delete_user/' . $user_data->id); ?>" 
                            class="btn bg-danger-500 text-white hover:bg-danger-600" 
                            onclick="return confirm('Yakin ingin menghapus akun <?php echo $user_data->nama_lengkap; ?>? Tindakan ini tidak bisa dibatalkan.');">
                            <i data-feather="trash-2" class="w-4 h-4 mr-2 inline-block"></i> Hapus Akun
                        </a>
                    </div>

                <?php echo form_close(); ?>
            </div>
            
        </div>
    </div>
</div>

<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>