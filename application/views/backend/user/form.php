<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data / User /</span> <?php echo $title; ?>
    </h4>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-edit-alt me-2"></i> <?php echo $title; ?>
                    </h5>
                    <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-sm btn-secondary">
                        <i class="bx bx-left-arrow-alt me-1"></i> Kembali ke Daftar
                    </a>
                </div>
                
                <div class="card-body">
                    
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bx bx-error-alt me-2"></i> <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bx bx-check-circle me-2"></i> <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                        // Action URL untuk UPDATE user
                        echo form_open('admin/user_action/' . $user_data->id, 'id="editUserForm"'); 
                    ?>
                    
                        <div class="mb-3">
                            <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" 
                                value="<?php echo set_value('nama_lengkap', $user_data->nama_lengkap); ?>" required>
                            <?php echo form_error('nama_lengkap', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" 
                                value="<?php echo set_value('username', $user_data->username); ?>" required>
                            <?php echo form_error('username', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="system_role">Role Sistem</label>
                            <select name="system_role" id="system_role" class="form-select" required>
                                <option value="admin" <?php echo set_select('system_role', 'admin', ($user_data->system_role == 'admin')); ?>>Admin (Pusat Kendali)</option>
                                <option value="user" <?php echo set_select('system_role', 'user', ($user_data->system_role == 'user')); ?>>User (Tim Lapangan)</option>
                            </select>
                            <?php echo form_error('system_role', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        
                        <div class="alert alert-warning my-4" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            Kosongkan field di bawah jika tidak ingin mengganti Password.
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label" for="password">Password Baru (Untuk Reset)</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <?php echo form_error('password', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-info">
                                <i class="bx bx-save me-2"></i> Update Akun
                            </button>
                            
                            <a href="<?php echo site_url('admin/delete_user/' . $user_data->id); ?>" 
                                class="btn btn-danger" 
                                onclick="return confirm('Yakin ingin menghapus akun <?php echo $user_data->nama_lengkap; ?>? Tindakan ini tidak bisa dibatalkan.');">
                                <i class="bx bx-trash me-2"></i> Hapus Akun
                            </a>
                        </div>

                    <?php echo form_close(); ?>
                </div>
                
            </div>
        </div>
    </div>
</div>