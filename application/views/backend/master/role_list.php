<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data /</span> Master Peran Tugas
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

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                
                <div class="card-header border-bottom">
                    <h5 class="mb-0">
                        <i class="bx bx-group me-2"></i> Daftar Master Peran Tugas
                    </h5>
                    <small class="text-muted">Contoh: Muthowwif, Tim LA Bandara, Tour Leader, dll.</small>
                </div>
                <br>
                <div class="card-body">
                    <div class="row g-4">
                        
                        <div class="col-lg-6">
                            <div class="card shadow-none border border-dashed border-primary p-4 h-100">
                                <h6 class="fw-semibold mb-3 text-primary"><i class="bx bx-plus-circle me-1"></i> Tambah Peran Tugas Baru</h6>
                                <?php echo form_open('admin/role_action/add'); ?>
                                    <div class="mb-3">
                                        <label class="form-label small" for="nama_peran_add">Nama Peran</label>
                                        <input type="text" name="nama_peran" id="nama_peran_add" class="form-control" value="<?php echo set_value('nama_peran'); ?>" required>
                                        <?php echo form_error('nama_peran', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small" for="deskripsi_add">Deskripsi Tugas</label>
                                        <textarea name="deskripsi" id="deskripsi_add" class="form-control" rows="3"><?php echo set_value('deskripsi'); ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bx bx-save me-2"></i> Simpan Peran
                                    </button>
                                <?php echo form_close(); ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h6 class="fw-semibold mb-3">Daftar Peran Aktif</h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-start">ID</th>
                                            <th class="text-start">NAMA PERAN</th>
                                            <th class="text-start">DESKRIPSI</th>
                                            <th class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php if (isset($peran) && !empty($peran)): ?>
                                            <?php foreach ($peran as $p): ?>
                                                <tr>
                                                    <td><?php echo $p->id; ?></td>
                                                    <td class="fw-medium"><?php echo $p->nama_peran; ?></td>
                                                    <td class="text-muted small">
                                                        <?php echo substr($p->deskripsi, 0, 50) . (strlen($p->deskripsi) > 50 ? '...' : ''); ?>
                                                    </td>
                                                    <td class="text-center text-nowrap">
                                                        <a href="<?php echo site_url('admin/role_action/edit/' . $p->id); ?>" class="btn btn-sm btn-icon btn-info" title="Edit">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                        <a href="<?php echo site_url('admin/delete_role/' . $p->id); ?>" class="btn btn-sm btn-icon btn-danger" title="Hapus" onclick="return confirm('Menghapus peran ini mungkin memutus tautan ke Template. Lanjutkan?');">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-5">Belum ada Peran Tugas yang dibuat.</td>
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
    </div>
</div>