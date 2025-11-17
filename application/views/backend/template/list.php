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
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="copy" class="w-4 h-4 mr-2 inline-block"></i> Daftar Template Itinerary
            </h5>
            
            <a href="<?php echo site_url('admin/template_form'); ?>" class="btn bg-primary-500 text-white hover:bg-primary-600">
                <i data-feather="plus" class="w-4 h-4 mr-2 inline-block"></i> Buat Template Baru
            </a>
        </div>
        
        <div class="card-body p-6">
            <div class="table-responsive">
                <table class="table table-hover w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                            <th class="py-2 px-3 text-left">NAMA TEMPLATE</th>
                            <th class="py-2 px-3 text-center">MANASIK (Hari)</th>
                            <th class="py-2 px-3 text-center">PERJALANAN (Hari)</th>
                            <th class="py-2 px-3 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($templates) && !empty($templates)): ?>
                            <?php foreach ($templates as $template): ?>
                                <tr class="text-sm border-t border-theme-border dark:border-themedark-border">
                                    <td class="py-3 px-3 text-left font-medium text-gray-800 dark:text-gray-200">
                                        <?php echo $template->nama_template; ?>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="badge bg-secondary-500 text-white"><?php echo $template->lama_manasik; ?> Hari</span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="badge bg-info-500 text-white"><?php echo $template->lama_perjalanan; ?> Hari</span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="<?php echo site_url('admin/template_detail/' . $template->id); ?>" class="btn btn-sm bg-success-500 text-white hover:bg-success-600" title="Kelola Item Template">
                                                <i data-feather="list" class="w-4 h-4"></i> Kelola Item
                                            </a>
                                            <a href="<?php echo site_url('admin/template_form/' . $template->id); ?>" class="btn btn-sm bg-info-500 text-white hover:bg-info-600" title="Edit Template">
                                                <i data-feather="edit" class="w-4 h-4"></i> Edit Tamplate
                                            </a>
                                            <a href="<?php echo site_url('admin/delete_template/' . $template->id); ?>" class="btn btn-sm bg-danger-500 text-white hover:bg-danger-600" title="Hapus Template" onclick="return confirm('Anda yakin ingin menghapus template ini? Semua item akan terhapus.');">
                                                <i data-feather="trash-2" class="w-4 h-4"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="text-sm">
                                <td colspan="4" class="py-5 text-center text-muted">Belum ada Template Itinerary yang dibuat.</td>
                            </tr>
                        <?php endif; ?>
                        
                    </tbody>
                </table>
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