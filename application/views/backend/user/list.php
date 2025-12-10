<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data /</span> Manajemen User & Akun
    </h4>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bx bx-check-circle me-2"></i> <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bx bx-error-alt me-2"></i> <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        
        <div class="card-header border-bottom">
            <h5 class="mb-0">
                <i class="bx bx-user-circle me-2"></i> Manajemen User & Akun
            </h5>
            <small class="text-muted">Kelola akun Admin dan User.</small>
        </div>
        <br>
        <div class="card-body">
            <div class="row g-4">
                
                <div class="col-lg-6 border-end-lg border-bottom border-gray-200 pb-4 pb-lg-0">
                    <h6 class="fw-semibold mb-3"><i class="bx bx-user-plus me-1"></i> Tambah Akun Baru</h6>
                    <?php echo form_open('admin/add_user'); ?>
                        
                        <div class="mb-3">
                            <label class="form-label small" for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?php echo set_value('nama_lengkap'); ?>" required>
                            <?php echo form_error('nama_lengkap', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small" for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="<?php echo set_value('username'); ?>" required>
                            <?php echo form_error('username', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small" for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <?php echo form_error('password', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small" for="system_role">Role Sistem</label>
                            <select name="system_role" id="system_role" class="form-select" required>
                                <option value="admin" <?php echo set_select('system_role', 'admin'); ?>>Admin (Pusat Kendali)</option>
                                <option value="user" <?php echo set_select('system_role', 'user'); ?>>User (Tim Lapangan)</option>
                            </select>
                            <?php echo form_error('system_role', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bx bx-save me-2"></i> Simpan User
                        </button>
                    <?php echo form_close(); ?>
                </div>

                <div class="col-lg-6">
                    <h6 class="fw-semibold mb-3"><i class="bx bx-list-ul me-1"></i> Daftar Akun Aktif</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-start">NAMA LENGKAP</th>
                                    <th class="text-start">USERNAME</th>
                                    <th class="text-center">ROLE SISTEM</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php if (isset($users) && !empty($users)): ?>
                                    <?php foreach ($users as $u): ?>
                                        <?php 
                                            $role_badge = ($u->system_role == 'admin') ? 'bg-danger' : 'bg-primary';
                                        ?>
                                        <tr>
                                            <td class="fw-medium"><?php echo $u->nama_lengkap; ?></td>
                                            <td><?php echo $u->username; ?></td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill <?php echo $role_badge; ?>"><?php echo strtoupper($u->system_role); ?></span>
                                            </td>
                                            <td class="text-center text-nowrap">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="<?php echo site_url('admin/user_form/' . $u->id); ?>" 
                                                       class="btn btn-sm btn-icon btn-info" title="Edit User">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    <a href="<?php echo site_url('admin/delete_user/' . $u->id); ?>" 
                                                       class="btn btn-sm btn-icon btn-danger" 
                                                       title="Hapus Akun" 
                                                       onclick="return confirm('Yakin ingin menghapus akun <?php echo $u->nama_lengkap; ?>?');">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">Belum ada akun pengguna yang terdaftar.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>