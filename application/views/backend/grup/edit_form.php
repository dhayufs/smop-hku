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
                            
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading mb-0">PERINGATAN!</h4>
                                <p class="mb-0">Mengubah dan menyimpan tanggal di bawah ini akan menggeser semua tanggal Manasik dan Perjalanan pada item checklist grup ini secara otomatis (Alur C.4).</p>
                            </div>
                            
                            <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <i class="bx bx-error-alt me-1"></i> 
                                    <?php echo $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                                // Action URL untuk ALUR C.4: edit_grup_action
                                echo form_open('admin/edit_grup_action/' . $grup_data->id, 'id="editGrupForm"'); 
                            ?>
                            
                                <div class="card shadow-none border border-primary mb-3">
                                    <div class="card-header bg-label-primary">
                                        <h6 class="mb-0 text-primary">
                                            <i class="bx bx-info-circle me-2"></i> Data Grup Saat Ini
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Grup</label>
                                            <input type="text" class="form-control" value="<?php echo $grup_data->nama_grup; ?>" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Keberangkatan LAMA</label>
                                            <input type="date" class="form-control" value="<?php echo $grup_data->tanggal_keberangkatan; ?>" disabled>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label">Tim Penanggung Jawab</label>
                                            <ul class="list-unstyled mt-2 ms-3">
                                                <?php if (!empty($penugasan)): ?>
                                                    <?php foreach ($penugasan as $p): ?>
                                                        <li class="mb-1"><i class="bx bx-check text-success me-2"></i><?php echo $p->nama_peran; ?> ditugaskan ke <strong><?php echo $p->nama_lengkap; ?></strong></li>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <li><i class="bx bx-x text-danger me-2"></i> Tidak ada tim yang ditugaskan.</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card shadow-none border border-warning mb-5">
                                    <div class="card-header bg-label-warning">
                                        <h6 class="mb-0 text-warning">
                                            <i class="bx bx-calendar me-2"></i> Tanggal Keberangkatan BARU
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="tanggal_keberangkatan">Tanggal Keberangkatan BARU</label>
                                            <input type="date" name="tanggal_keberangkatan" id="tanggal_keberangkatan" class="form-control" 
                                                value="<?php echo set_value('tanggal_keberangkatan', $grup_data->tanggal_keberangkatan); ?>" required>
                                            <?php echo form_error('tanggal_keberangkatan', '<small class="text-danger d-block mt-1">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="bx bx-revision me-2"></i> Geser Semua Jadwal
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