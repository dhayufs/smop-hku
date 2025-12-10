<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="plus-circle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $title; ?>
            </h5>
            <a href="<?php echo site_url('admin'); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline-block"></i> Kembali ke Dashboard
            </a>
        </div>
        
        <div class="card-body p-6 grid grid-cols-12 gap-x-6"> 
            
            <div class="col-span-12"> <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
                    <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i> 
                        Terjadi kesalahan. Pastikan semua field terisi dan Template Item sudah dibuat.
                        <?php echo $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?>
                    </div>
                <?php endif; ?>
                
                <?php 
                    $action_url = site_url('admin/add_grup_action');
                    echo form_open($action_url, 'id="grupForm"'); 
                ?>
                
                    <div class="card bg-gray-50 dark:bg-gray-800 shadow-none border mb-5">
                        <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4">
                            <h6 class="font-bold text-primary-500"><i data-feather="package" class="w-4 h-4 mr-2 inline-block"></i> 1. Detail Paket & Tanggal</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="grid grid-cols-12 gap-x-4">
                                
                                <div class="col-span-12">
                                    <div class="form-group mb-3">
                                        <label class="text-sm font-medium">Nama Grup</label>
                                        <input type="text" name="nama_grup" class="form-control" value="<?php echo set_value('nama_grup'); ?>" required>
                                        <?php echo form_error('nama_grup', '<small class="text-danger block">', '</small>'); ?>
                                    </div>
                                </div>
                                
                                <div class="col-span-12">
                                    <div class="form-group mb-3">
                                        <label class="text-sm font-medium">Tanggal Mulai Manasik</label>
                                        <input type="date" name="tanggal_mulai_manasik" class="form-control" value="<?php echo set_value('tanggal_mulai_manasik'); ?>" required>
                                        <?php echo form_error('tanggal_mulai_manasik', '<small class="text-danger block">', '</small>'); ?>
                                    </div>
                                </div>
                                
                                <div class="col-span-12">
                                    <div class="form-group mb-3">
                                        <label class="text-sm font-medium">Tanggal Keberangkatan (H)</label>
                                        <input type="date" name="tanggal_keberangkatan" class="form-control" value="<?php echo set_value('tanggal_keberangkatan'); ?>" required>
                                        <?php echo form_error('tanggal_keberangkatan', '<small class="text-danger block">', '</small>'); ?>
                                    </div>
                                </div>
                                
                                <div class="col-span-12">
                                    <div class="form-group mb-4">
                                        <label class="text-sm font-medium">Template Itinerary Asal</label>
                                        <select name="template_asal_id" class="form-control" required>
                                            <option value="">-- Pilih Template --</option>
                                            <?php if (isset($templates)): ?>
                                                <?php foreach ($templates as $t): ?>
                                                    <option value="<?php echo $t->id; ?>" <?php echo set_select('template_asal_id', $t->id); ?>>
                                                        <?php echo $t->nama_template; ?> (Total: <?php echo $t->lama_manasik + $t->lama_perjalanan; ?> Hari)
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php echo form_error('template_asal_id', '<small class="text-danger block">', '</small>'); ?>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-gray-50 dark:bg-gray-800 shadow-none border mb-5">
                        <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4">
                            <h6 class="font-bold text-primary-500"><i data-feather="users" class="w-4 h-4 mr-2 inline-block"></i> 2. Penugasan Tim Lapangan (User ke Peran)</h6>
                            <small class="text-muted">Setiap peran yang ditugaskan di sini akan otomatis menerima checklist di aplikasi mereka.</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="grid grid-cols-12 gap-x-4">
                                <?php if (isset($peran) && !empty($peran)): ?>
                                    <?php foreach ($peran as $p): ?>
                                        <div class="col-span-12">
                                            <div class="form-group mb-3">
                                                <label class="text-sm font-medium"><?php echo $p->nama_peran; ?></label>
                                                <select name="user_peran_<?php echo $p->id; ?>" class="form-control">
                                                    <option value="">-- Pilih User (Opsional) --</option>
                                                    <?php if (isset($users)): ?>
                                                        <?php foreach ($users as $u): ?>
                                                            <?php if ($u->system_role == 'user'): ?>
                                                                <option value="<?php echo $u->id; ?>" <?php echo set_select('user_peran_' . $p->id, $u->id); ?>>
                                                                    <?php echo $u->nama_lengkap; ?> (<?php echo $u->username; ?>)
                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <small class="text-muted block"><?php echo $p->deskripsi; ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-span-12">
                                        <div class="alert alert-warning">Mohon buat Master Peran Tugas terlebih dahulu.</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-plus me-2"></i> Buat Grup Perjalanan
                        </button>
                    </div>

                <?php echo form_close(); ?>
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