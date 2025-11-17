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
                <i data-feather="users" class="w-4 h-4 mr-2 inline-block"></i> Daftar Master Peran Tugas
            </h5>
            <small class="text-muted">Contoh: Muthowwif, Tim LA Bandara, Tour Leader, dll.</small>
        </div>
        
        <div class="card-body p-6 grid grid-cols-12 gap-x-6">
            
            <div class="col-span-12 lg:col-span-6 mb-6 lg:mb-0">
                <div class="card shadow-none border border-dashed border-gray-400 dark:border-gray-600 p-4">
                    <h6 class="font-semibold mb-3 text-primary-500"><i data-feather="plus" class="w-4 h-4 mr-1 inline-block"></i> Tambah Peran Tugas Baru</h6>
                    <?php echo form_open('admin/role_action/add'); ?>
                        <div class="form-group mb-3">
                            <label class="text-sm font-medium">Nama Peran</label>
                            <input type="text" name="nama_peran" class="form-control w-full" style="width: 100% !important;" value="<?php echo set_value('nama_peran'); ?>" required>
                            <?php echo form_error('nama_peran', '<small class="text-danger block">', '</small>'); ?>
                        </div>
                        <div class="form-group mb-4">
                            <label class="text-sm font-medium">Deskripsi Tugas</label>
                            <textarea name="deskripsi" class="form-control w-full" style="width: 100% !important;" rows="3"><?php echo set_value('deskripsi'); ?></textarea>
                        </div>
                        <button type="submit" class="btn bg-success-500 text-white hover:bg-success-600 w-full">
                            <i data-feather="save" class="w-4 h-4 mr-2 inline-block"></i> Simpan Peran
                        </button>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-6">
                <h6 class="font-semibold mb-3">Daftar Peran Aktif</h6>
                <div class="table-responsive">
                    <table class="table table-hover w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                <th class="py-2 px-3 text-left">ID</th>
                                <th class="py-2 px-3 text-left">NAMA PERAN</th>
                                <th class="py-2 px-3 text-left">DESKRIPSI</th>
                                <th class="py-2 px-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($peran) && !empty($peran)): ?>
                                <?php foreach ($peran as $p): ?>
                                    <tr class="text-sm border-t border-theme-border dark:border-themedark-border">
                                        <td class="py-3 px-3"><?php echo $p->id; ?></td>
                                        <td class="py-3 px-3 font-medium"><?php echo $p->nama_peran; ?></td>
                                        <td class="py-3 px-3 text-gray-600 dark:text-gray-400">
                                            <?php echo substr($p->deskripsi, 0, 50) . (strlen($p->deskripsi) > 50 ? '...' : ''); ?>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <a href="<?php echo site_url('admin/role_action/edit/' . $p->id); ?>" class="btn btn-sm bg-info-500 text-white hover:bg-info-600" title="Edit">
                                                <i data-feather="edit" class="w-4 h-4"></i>
                                            </a>
                                            <a href="<?php echo site_url('admin/delete_role/' . $p->id); ?>" class="btn btn-sm bg-danger-500 text-white hover:bg-danger-600" title="Hapus" onclick="return confirm('Menghapus peran ini mungkin memutus tautan ke Template. Lanjutkan?');">
                                                <i data-feather="trash-2" class="w-4 h-4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="text-sm">
                                    <td colspan="4" class="py-5 text-center text-muted">Belum ada Peran Tugas yang dibuat.</td>
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
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>