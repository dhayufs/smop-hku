<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="col-span-12">
    
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4"><i data-feather="check-circle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800">
            <h5 class="mb-0 font-medium">
                <i data-feather="users" class="w-4 h-4 mr-2 inline-block"></i> Manajemen User & Akun
            </h5>
            <small class="text-muted">Kelola akun Admin dan User.</small>
        </div>
        
        <div class="card-body p-6 grid grid-cols-12 gap-x-6">
            
            <div class="col-span-12 lg:col-span-6 border-r border-gray-200 dark:border-gray-700 pr-6">
                <h6 class="font-semibold mb-3">Tambah Akun Baru</h6>
                <?php echo form_open('admin/add_user'); ?>
                    
                    <div class="form-group mb-3">
                        <label class="text-sm font-medium">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control w-full" value="<?php echo set_value('nama_lengkap'); ?>" required>
                        <?php echo form_error('nama_lengkap', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="text-sm font-medium">Username</label>
                        <input type="text" name="username" class="form-control w-full" value="<?php echo set_value('username'); ?>" required>
                        <?php echo form_error('username', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="text-sm font-medium">Password</label>
                        <input type="password" name="password" class="form-control w-full" required>
                        <?php echo form_error('password', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label class="text-sm font-medium">Role Sistem</label>
                        <select name="system_role" class="form-control w-full" required>
                            <option value="admin" <?php echo set_select('system_role', 'admin'); ?>>Admin (Pusat Kendali)</option>
                            <option value="user" <?php echo set_select('system_role', 'user'); ?>>User (Tim Lapangan)</option>
                        </select>
                        <?php echo form_error('system_role', '<small class="text-danger block">', '</small>'); ?>
                    </div>
                    
                    <button type="submit" class="btn bg-success-500 text-white hover:bg-success-600 w-full">
                        <i data-feather="user-plus" class="w-4 h-4 mr-2 inline-block"></i> Simpan User
                    </button>
                <?php echo form_close(); ?>
            </div>

            <div class="col-span-12 lg:col-span-6">
                <br>
                    <br>
                    <br>
                <br>
                <h6 class="font-semibold mb-3 pt-1">Daftar Akun Aktif</h6>
                <div class="table-responsive">
                    <table class="table table-hover w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                <th class="py-2 px-3 text-left">NAMA LENGKAP</th>
                                <th class="py-2 px-3 text-left">USERNAME</th>
                                <th class="py-2 px-3 text-center">ROLE SISTEM</th>
                                <th class="py-2 px-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($users) && !empty($users)): ?>
                                <?php foreach ($users as $u): ?>
                                    <?php 
                                        $role_badge = ($u->system_role == 'admin') ? 'bg-danger-500' : 'bg-primary-500';
                                    ?>
                                    <tr class="text-sm border-t border-theme-border dark:border-themedark-border">
                                        <td class="py-3 px-3 font-medium text-gray-800 dark:text-gray-200"><?php echo $u->nama_lengkap; ?></td>
                                        <td class="py-3 px-3"><?php echo $u->username; ?></td>
                                        <td class="py-3 px-3 text-center">
                                            <span class="badge <?php echo $role_badge; ?> text-white"><?php echo strtoupper($u->system_role); ?></span>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="<?php echo site_url('admin/user_form/' . $u->id); ?>" 
                                                   class="btn btn-sm bg-info-500 text-white hover:bg-info-600" title="Edit User">
                                                    <i data-feather="edit" class="w-4 h-4"></i>
                                                </a>
                                                <a href="<?php echo site_url('admin/delete_user/' . $u->id); ?>" 
                                                   class="btn btn-sm bg-danger-500 text-white hover:bg-danger-600" 
                                                   title="Hapus Akun" 
                                                   onclick="return confirm('Yakin ingin menghapus akun <?php echo $u->nama_lengkap; ?>?');">
                                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="text-sm">
                                    <td colspan="4" class="py-5 text-center text-muted">Belum ada akun pengguna yang terdaftar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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