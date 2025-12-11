<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data /</span> Daftar Template Itinerary
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
        
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-medium">
                <i class="bx bx-copy me-2"></i> Daftar Template Itinerary
            </h5>
            <a href="<?php echo site_url('admin/template_form'); ?>" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Buat Template Baru
            </a>
        </div>
        
        <div class="card-body p-6">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="text-nowrap">
                            <th class="text-start">NAMA TEMPLATE</th>
                            <th class="text-center">MANASIK (HARI)</th>
                            <th class="text-center">PERJALANAN (HARI)</th>
                            <th class="text-center">TOTAL ITEM</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php if (isset($templates) && !empty($templates)): ?>
                            <?php foreach ($templates as $t): ?>
                                <?php 
                                    // Amankan akses ke total_item
                                    $total_item_count = isset($t->total_item) ? $t->total_item : 0;
                                ?>
                                <tr>
                                    <td class="text-start fw-medium">
                                        <a href="<?php echo site_url('admin/template_detail/' . $t->id); ?>" class="text-primary">
                                            <?php echo $t->nama_template; ?>
                                        </a>
                                    </td>
                                    <td class="text-center"><span class="badge bg-label-info"><?php echo $t->lama_manasik; ?></span></td>
                                    <td class="text-center"><span class="badge bg-label-warning"><?php echo $t->lama_perjalanan; ?></span></td>
                                    <td class="text-center fw-bold"><?php echo $total_item_count; ?></td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?php echo site_url('admin/template_detail/' . $t->id); ?>" class="btn btn-sm btn-icon btn-primary" title="Lihat Detail Item">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="<?php echo site_url('admin/template_form/' . $t->id); ?>" class="btn btn-sm btn-icon btn-info" title="Edit Template">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <a href="<?php echo site_url('admin/delete_template/' . $t->id); ?>" class="btn btn-sm btn-icon btn-danger" title="Hapus Template" onclick="return confirm('Anda yakin ingin menghapus template ini? Semua item akan terhapus.');">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">Belum ada Template Itinerary yang dibuat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>