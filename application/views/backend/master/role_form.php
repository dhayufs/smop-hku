<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data / Peran Tugas /</span> Edit
    </h4>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                
                <div class="card-header border-bottom">
                    <h5 class="mb-0">
                        <i class="bx bx-edit-alt me-2"></i> Edit Peran Tugas: <?php echo $peran_data->nama_peran; ?>
                    </h5>
                </div>
                
                <div class="card-body">
                    
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bx bx-error-alt me-1"></i> <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo form_open('admin/role_action/edit/' . $peran_data->id); ?>
                        <div class="mb-3">
                            <label class="form-label" for="nama_peran">Nama Peran</label>
                            <input type="text" name="nama_peran" id="nama_peran" class="form-control" value="<?php echo set_value('nama_peran', $peran_data->nama_peran); ?>" required>
                            <?php echo form_error('nama_peran', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="deskripsi">Deskripsi Tugas</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"><?php echo set_value('deskripsi', $peran_data->deskripsi); ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-info">
                                <i class="bx bx-save me-2"></i> Update Peran
                            </button>
                            <a href="<?php echo site_url('admin/roles'); ?>" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    <?php echo form_close(); ?>
                </div>
                
            </div>
        </div>
    </div>
</div>