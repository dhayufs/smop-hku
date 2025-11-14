<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4"><i data-feather="check-circle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
        <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?></div>
    <?php endif; ?>

    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="list" class="w-4 h-4 mr-2 inline-block"></i> Struktur Template: <?php echo $template->nama_template; ?>
            </h5>
            <a href="<?php echo site_url('admin/templates'); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline-block"></i> Kembali
            </a>
        </div>
        <div class="card-body p-6">
            
            <div class="mb-4">
                <p><strong>Durasi:</strong> Manasik <?php echo $template->lama_manasik; ?> Hari | Perjalanan <?php echo $template->lama_perjalanan; ?> Hari</p>
            </div>
            
            <hr class="my-4 border-t border-gray-200 dark:border-gray-700">

            <div class="grid grid-cols-12 gap-x-6">
                
                <div class="col-span-12 mb-6 border-b lg:border-r lg:border-b-0 border-gray-200 dark:border-gray-700 pb-4 lg:pr-6">
                    <h6 class="font-semibold mb-3"><i data-feather="plus" class="w-4 h-4 mr-1 inline-block"></i> Tambah Item Baru</h6>
                    <?php echo form_open('admin/add_template_item/' . $template->id, 'class="grid grid-cols-12 gap-x-4"'); ?>
                        
                        <div class="form-group col-span-12 md:col-span-3 mb-3">
                            <label class="text-sm font-medium">Blok Tugas</label>
                            <select name="tipe_blok" class="form-control" required>
                                <option value=""> --- Pilih Blok Tugas --- </option>                                
                                <option value="manasik">Manasik (<?php echo $template->lama_manasik; ?> Hari)</option>
                                <option value="perjalanan">Perjalanan (<?php echo $template->lama_perjalanan; ?> Hari)</option>
                            </select>
                        </div>
                        <div class="form-group col-span-12 md:col-span-2 mb-3">
                            <label class="text-sm font-medium">Hari Ke</label>
                            <input type="number" name="hari_ke" class="form-control" min="1" required>
                            <small class="text-muted block">Max M: <?php echo $template->lama_manasik; ?>, Max P: <?php echo $template->lama_perjalanan; ?></small>
                        </div>
                        <div class="form-group col-span-12 md:col-span-2 mb-3">
                            <label class="text-sm font-medium">Jenis Item</label>
                            <select name="tipe_item" id="tipe_item_add" class="form-control" required>
                                <option value=""> --- Pilih Item --- </option>
                                <option value="info">📝 Informasi</option>
                                <option value="checklist">✅ Checklist</option>
                            </select>
                        </div>
                        <div class="form-group col-span-12 md:col-span-2 mb-3" id="pj_peran_field_add">
                            <label class="text-sm font-medium">PJ Peran</label>
                            <select name="pj_peran_id" class="form-control">
                                <option value="">-- Pilih Peran --</option>
                                <?php foreach ($peran as $p): ?>
                                    <option value="<?php echo $p->id; ?>"><?php echo $p->nama_peran; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-span-12 md:col-span-3 mb-3" id="pj_user_field_add">
                            <label class="text-sm font-medium">User Default</label>
                            <select name="pj_user_id_default" class="form-control">
                                <option value="">-- Pilih User --</option>
                                <?php foreach ($users as $u): ?>
                                    <?php if ($u->system_role === 'tim_lapangan'): ?>
                                        <option value="<?php echo $u->id; ?>"><?php echo $u->nama_lengkap; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group col-span-12 md:col-span-10 mb-4">
                            <label class="text-sm font-medium">Deskripsi Item</label>
                            <textarea name="deskripsi_item" class="form-control" rows="1" required></textarea>
                        </div>
                        
                        <div class="form-group col-span-12 md:col-span-2 mb-4 self-end">
                            <button type="submit" class="btn bg-success-500 text-white hover:bg-success-600 w-full">
                                <i data-feather="plus" class="w-4 h-4 mr-2 inline-block"></i> Tambah
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>

                <div class="col-span-12">
                    <h6 class="font-semibold mb-3"><i data-feather="layers" class="w-4 h-4 mr-1 inline-block"></i> Daftar Item Itinerary</h6>
                    
                    <?php foreach (['manasik' => 'Manasik', 'perjalanan' => 'Perjalanan'] as $blok_key => $blok_label): ?>
                        
                        <?php $max_hari = ($blok_key == 'manasik') ? $template->lama_manasik : $template->lama_perjalanan; ?>
                        
                        <?php if ($max_hari > 0): ?>
                            <h6 class="mt-4 text-primary font-bold">BLOK <?php echo strtoupper($blok_label); ?></h6>
                        <?php endif; ?>

                        <?php for ($h = 1; $h <= $max_hari; $h++): ?>
                            <div class="card border-theme-border dark:border-themedark-border mb-3">
                                <div class="card-header bg-gray-100 dark:bg-gray-700 p-3">
                                    <h6 class="mb-0 text-sm">Hari Ke-<?php echo $h; ?> (<?php echo ucfirst($blok_key); ?>)</h6>
                                </div>
                                <div class="list-group list-group-flush p-3">
                                    <?php $key = $blok_key . '_' . $h; ?>
                                    <?php if (isset($grouped_items[$key])): ?>
                                        <?php foreach ($grouped_items[$key]['list'] as $item): ?>
                                            
                                            <?php 
                                                $form_id = 'editForm_' . $item->id;
                                                $error_edit = $this->session->flashdata('error_form_edit_' . $item->id);
                                            ?>
                                            <div class="p-3 border-b mb-4 pb-4 <?php echo $error_edit ? 'bg-red-50 border-red-500' : ''; ?>">
                                                
                                                <?php if ($error_edit): ?>
                                                    <div class="alert alert-danger bg-red-100 text-red-800 p-2 rounded mb-3 text-sm">
                                                        <?php echo $error_edit; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php echo form_open('admin/edit_template_item_action/' . $template->id . '/' . $item->id, 'id="' . $form_id . '" class="grid grid-cols-12 gap-x-3 items-start"'); ?>
                                                
                                                <div class="col-span-12 md:col-span-3 flex flex-col space-y-3">
                                                    
                                                    <div class="form-group mb-0">
                                                        <label class="text-sm font-medium block mb-1">Tipe Item</label>
                                                        <select name="tipe_item" class="form-control form-control-sm w-full" 
                                                                id="tipe_item_<?php echo $item->id; ?>">
                                                            <option value="info" <?php echo ($item->tipe_item == 'info') ? 'selected' : ''; ?>>Info</option>
                                                            <option value="checklist" <?php echo ($item->tipe_item == 'checklist') ? 'selected' : ''; ?>>Checklist</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group mb-0" id="pj_peran_edit_<?php echo $item->id; ?>">
                                                        <label class="text-sm font-medium block mb-1">PJ Peran</label>
                                                        <select name="pj_peran_id" class="form-control form-control-sm w-full">
                                                            <option value="">N/A</option>
                                                            <?php foreach ($peran as $p): ?>
                                                                <option value="<?php echo $p->id; ?>" <?php echo ($item->pj_peran_id == $p->id) ? 'selected' : ''; ?>>
                                                                    <?php echo $p->nama_peran; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-0" id="pj_user_edit_<?php echo $item->id; ?>">
                                                        <label class="text-sm font-medium block mb-1">Nama Orang (Default)</label>
                                                        <select name="pj_user_id_default" class="form-control form-control-sm w-full">
                                                            <option value="">-- Pilih User --</option>
                                                            <?php foreach ($users as $u): ?>
                                                                <?php if ($u->system_role === 'tim_lapangan'): ?>
                                                                    <option value="<?php echo $u->id; ?>" <?php echo ($item->pj_user_id_default == $u->id) ? 'selected' : ''; ?>>
                                                                        <?php echo $u->nama_lengkap; ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    
                                                </div>

                                                <div class="col-span-12 md:col-span-5 mt-3 md:mt-0 form-group mb-0">
                                                    <label class="text-sm font-medium block mb-1">Deskripsi Item (Urutan: <?php echo $item->urutan; ?>)</label>
                                                    <textarea name="deskripsi_item" class="form-control form-control-sm" rows="3" required><?php echo $item->deskripsi_item; ?></textarea>
                                                </div>
                                                
                                                <div class="col-span-12 md:col-span-4 mt-3 md:mt-0 flex flex-col justify-start space-y-3">
                                                    
                                                    <div class="flex justify-between items-start w-full">
                                                        
                                                        <div class="flex justify-start space-x-2">
                                                            <button type="submit" class="btn bg-info-500 text-white hover:bg-info-600" title="Simpan Perubahan">
                                                                <i data-feather="save" class="w-4 h-4 mr-1 inline-block"></i> Simpan
                                                            </button>
                                                            
                                                            <a href="<?php echo site_url('admin/delete_template_item/' . $template->id . '/' . $item->id); ?>" 
                                                                class="btn bg-danger-500 text-white hover:bg-danger-600" title="Hapus Item" onclick="return confirm('Hapus item ini?');">
                                                                <i data-feather="trash-2" class="w-4 h-4 mr-1 inline-block"></i> Hapus
                                                            </a>
                                                        </div>
                                                        
                                                        <div class="flex justify-end space-x-2">
                                                            <a href="<?php echo site_url('admin/reorder_item/' . $template->id . '/' . $item->id . '/up'); ?>" 
                                                                class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600" title="Pindah Naik">
                                                                <i data-feather="arrow-up" class="w-4 h-4"></i>
                                                            </a>
                                                            <a href="<?php echo site_url('admin/reorder_item/' . $template->id . '/' . $item->id . '/down'); ?>" 
                                                                class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600" title="Pindah Turun">
                                                                <i data-feather="arrow-down" class="w-4 h-4"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php echo form_close(); ?>
                                            </div>
                                            <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="text-muted text-sm list-group-item">Belum ada item untuk Hari Ke-<?php echo $h; ?>.</li>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                    
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
    
    // Logic JS untuk menyembunyikan/menampilkan field PJ Peran dan PJ User di Form Tambah Item (Kiri Atas)
    document.addEventListener('DOMContentLoaded', function() {
        const tipeItemAddSelect = document.getElementById('tipe_item_add');
        const pjFieldAdd = document.getElementById('pj_peran_field_add');
        const pjUserFieldAdd = document.getElementById('pj_user_field_add'); // <-- PJ User Tambahan

        function togglePjFieldAdd() {
            if (tipeItemAddSelect.value === 'checklist') {
                pjFieldAdd.style.display = 'block';
                pjUserFieldAdd.style.display = 'block'; // <-- Tampilkan User
                pjFieldAdd.querySelector('select').setAttribute('required', 'required');
            } else {
                pjFieldAdd.style.display = 'none';
                pjUserFieldAdd.style.display = 'none'; // <-- Sembunyikan User
                pjFieldAdd.querySelector('select').removeAttribute('required');
            }
        }
        tipeItemAddSelect.addEventListener('change', togglePjFieldAdd);
        togglePjFieldAdd();
        
        // Logic JS untuk menyembunyikan/menampilkan field PJ Peran dan PJ User di Form Edit (Per Baris)
        <?php if (isset($grouped_items)): ?>
            <?php foreach ($grouped_items as $group): ?>
                <?php foreach ($group['list'] as $item): ?>
                    const tipeItemEditSelect_<?php echo $item->id; ?> = document.getElementById('tipe_item_<?php echo $item->id; ?>');
                    const pjFieldEdit_<?php echo $item->id; ?> = document.getElementById('pj_peran_edit_<?php echo $item->id; ?>');
                    const pjUserFieldEdit_<?php echo $item->id; ?> = document.getElementById('pj_user_edit_<?php echo $item->id; ?>'); // <-- PJ User Tambahan

                    function togglePjFieldEdit_<?php echo $item->id; ?>() {
                        if (tipeItemEditSelect_<?php echo $item->id; ?>.value === 'checklist') {
                            pjFieldEdit_<?php echo $item->id; ?>.style.display = 'block';
                            pjUserFieldEdit_<?php echo $item->id; ?>.style.display = 'block'; // <-- Tampilkan User
                            pjFieldEdit_<?php echo $item->id; ?>.querySelector('select').setAttribute('required', 'required');
                        } else {
                            pjFieldEdit_<?php echo $item->id; ?>.style.display = 'none';
                            pjUserFieldEdit_<?php echo $item->id; ?>.style.display = 'none'; // <-- Sembunyikan User
                            pjFieldEdit_<?php echo $item->id; ?>.querySelector('select').removeAttribute('required');
                        }
                    }
                    tipeItemEditSelect_<?php echo $item->id; ?>.addEventListener('change', togglePjFieldEdit_<?php echo $item->id; ?>);
                    togglePjFieldEdit_<?php echo $item->id; ?>();
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    });
</script>