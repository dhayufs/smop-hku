<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4"><i data-feather="check-circle" class="w-4 h-4 mr-2 inline-block"></i> <?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
        <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i> <?= $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?></div>
    <?php endif; ?>

    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="list" class="w-4 h-4 mr-2 inline-block"></i> Struktur Template: <?= $template->nama_template; ?>
            </h5>
            <a href="<?= site_url('admin/templates'); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline-block"></i> Kembali
            </a>
        </div>
        <div class="card-body p-6">
            
            <div class="mb-4">
                <p><strong>Durasi:</strong> Manasik <?= $template->lama_manasik; ?> Hari | Perjalanan <?= $template->lama_perjalanan; ?> Hari</p>
            </div>
            
            <hr class="my-4 border-t border-gray-200 dark:border-gray-700">

            <div class="grid grid-cols-12 gap-x-6">
                
                <div class="col-span-12 mb-6 border-b lg:border-r lg:border-b-0 border-gray-200 dark:border-gray-700 pb-4 lg:pr-6">
                    <h6 class="font-semibold mb-3"><i data-feather="plus" class="w-4 h-4 mr-1 inline-block"></i> Tambah Item Baru</h6>
                    
                    <?= form_open('admin/add_template_item/' . $template->id, 'class="grid grid-cols-12 gap-x-4 gap-y-3"'); ?>
                        
                        <div class="form-group col-span-12 md:col-span-10">
                            <label class="text-sm font-medium">Tugas</label>
                            <input type="text" name="deskripsi_item" class="form-control" required>
                        </div>
                        
                        <div class="form-group col-span-12 md:col-span-3">
                            <label class="text-sm font-medium">Blok Tugas</label>
                            <select name="tipe_blok" class="form-control" required>
                                <option value="">--- Pilih Blok Tugas ---</option>
                                <option value="manasik">Manasik (<?= $template->lama_manasik; ?> Hari)</option>
                                <option value="perjalanan">Perjalanan (<?= $template->lama_perjalanan; ?> Hari)</option>
                            </select>
                        </div>
                        <div class="form-group col-span-12 md:col-span-2">
                            <label class="text-sm font-medium">Hari Ke</label>
                            <input type="number" name="hari_ke" class="form-control" min="1" required>
                            <small class="text-muted block">Max M: <?= $template->lama_manasik; ?>, Max P: <?= $template->lama_perjalanan; ?></small>
                        </div>
                        
                        <div class="form-group col-span-12 md:col-span-2">
                            <label class="text-sm font-medium">Jenis Item</label>
                            <select name="tipe_item" id="tipe_item_add" class="form-control" required>
                                <option value="">--- Pilih Item ---</option>
                                <option value="info">📝 Informasi</option>
                                <option value="checklist">✅ Checklist</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-span-12 md:col-span-2" id="pj_peran_field_add">
                            <label class="text-sm font-medium">PJ Peran</label>
                            <select name="pj_peran_id" class="form-control">
                                <option value="">-- Pilih Peran --</option>
                                <?php foreach ($peran as $p): ?>
                                    <option value="<?= $p->id; ?>"><?= $p->nama_peran; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group col-span-12 md:col-span-3" id="pj_user_field_add">
                            <label class="text-sm font-medium">User Default</label>
                            <select name="pj_user_id_default" class="form-control">
                                <option value="">-- Pilih User --</option>
                                <?php foreach ($users as $u): ?>
                                    <?php if ($u->system_role === 'user'): ?>
                                        <option value="<?= $u->id; ?>"><?= $u->nama_lengkap; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group col-span-12 md:col-span-2 self-end">
                            <button type="submit" class="btn bg-success-500 text-white hover:bg-success-600 w-full">
                                <i data-feather="plus" class="w-4 h-4 mr-2 inline-block"></i> Tambah
                            </button>
                        </div>                        
                        
                    <?= form_close(); ?>
                </div>
                    <br>
                    <br>
                    <br>
                <div class="col-span-12" id="itinerary-list-container"> 
                    <h6 class="font-semibold mb-3"><i data-feather="layers" class="w-4 h-4 mr-1 inline-block"></i> Daftar Item Itinerary</h6>
                    
                    <?php 
                    // [MODIFIKASI] Definisikan palet warna di sini
                    $day_colors = [
                        'bg-sky-100 dark:bg-sky-800', 
                        'bg-emerald-100 dark:bg-emerald-800', 
                        'bg-amber-100 dark:bg-amber-800', 
                        'bg-indigo-100 dark:bg-indigo-800',
                        'bg-fuchsia-100 dark:bg-fuchsia-800',
                        'bg-rose-100 dark:bg-rose-800'
                    ];
                    $colors_count = count($day_colors);
                    ?>
                    
                    <?php foreach (['manasik' => 'Manasik', 'perjalanan' => 'Perjalanan'] as $blok_key => $blok_label): ?>
                        
                        <?php $max_hari = ($blok_key == 'manasik') ? $template->lama_manasik : $template->lama_perjalanan; ?>
                        
                        <?php if ($max_hari > 0): ?>
                            <h6 class="mt-4 text-primary font-bold">BLOK <?= strtoupper($blok_label); ?></h6>
                        <?php endif; ?>

                        <?php for ($h = 1; $h <= $max_hari; $h++): ?>
                            <div class="card border-theme-border dark:border-themedark-border mb-3">
                                
                                <?php 
                                // [MODIFIKASI] Ambil warna berdasarkan hari ke-$h
                                // -1 karena array index mulai dari 0, tapi $h mulai dari 1
                                $current_color = $day_colors[($h - 1) % $colors_count]; 
                                ?>

                                <div class="card-header p-3 <?= $current_color; ?>">
                                    <h6 class="mb-0 text-sm font-semibold text-black/80 dark:text-white/90">Hari Ke-<?= $h; ?> (<?= ucfirst($blok_key); ?>)</h6>
                                </div>

                                <div class="p-3"> <?php $key = $blok_key . '_' . $h; ?>
                                    <?php if (isset($grouped_items[$key])): ?>
                                        <?php foreach ($grouped_items[$key]['list'] as $item): ?>
                                            
                                            <?php
                                                $form_id = 'editForm_' . $item->id;
                                                $error_edit = $this->session->flashdata('error_form_edit_' . $item->id);
                                            ?>

                                            <div class="js-item-card shadow-sm rounded-lg border border-theme-border dark:border-themedark-border bg-white dark:bg-themedark-cardbg p-4 mb-3 <?php echo $error_edit ? 'bg-red-50 border-red-500' : ''; ?>">
                                                
                                                <?php if ($error_edit): ?>
                                                    <div class="alert alert-danger bg-red-100 text-red-800 p-2 rounded mb-3 text-sm">
                                                        <?= $error_edit; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?= form_open('admin/edit_template_item_action/' . $template->id . '/' . $item->id, 'id="' . $form_id . '" class="grid grid-cols-12 gap-x-4 gap-y-3 items-start"'); ?>
                                                
                                                    <div class="col-span-12 md:col-span-5 form-group mb-0">
                                                        <label class="text-sm font-medium block mb-1">Tugas (Urutan: <?= $item->urutan; ?>)</label>
                                                        <input type="text" name="deskripsi_item" class="form-control form-control-sm" value="<?= $item->deskripsi_item; ?>" required>
                                                    </div>

                                                    <div class="col-span-6 md:col-span-2 form-group mb-0">
                                                        <label class="text-sm font-medium block mb-1">Tipe Item</label>
                                                        <select name="tipe_item" class="form-control form-control-sm js-tipe-item-edit">
                                                            <option value="">-- Pilih Item --</option>
                                                            <option value="info" <?= ($item->tipe_item=='info')?'selected':''; ?>>📝 Info</option>
                                                            <option value="checklist" <?= ($item->tipe_item=='checklist')?'selected':''; ?>>✅ Checklist</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-span-6 md:col-span-2 form-group mb-0 js-pj-peran-field">
                                                        <label class="text-sm font-medium block mb-1">PJ Peran</label>
                                                        <select name="pj_peran_id" class="form-control form-control-sm">
                                                            <option value="">-- Pilih Peran --</option>
                                                            <?php foreach($peran as $p): ?>
                                                            <option value="<?= $p->id; ?>" <?= ($item->pj_peran_id==$p->id)?'selected':''; ?>>
                                                                <?= $p->nama_peran; ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-span-12 md:col-span-3 form-group mb-0 js-pj-user-field">
                                                        <label class="text-sm font-medium block mb-1">Nama PJ</label>
                                                        <select name="pj_user_id_default" class="form-control form-control-sm">
                                                            <option value="">-- Pilih User --</option>
                                                            <?php foreach ($users as $u): ?>
                                                            <?php if ($u->system_role === 'user'): ?>
                                                            <option value="<?= $u->id; ?>" <?= ($item->pj_user_id_default==$u->id)?'selected':''; ?>>
                                                                <?= $u->nama_lengkap; ?>
                                                            </option>
                                                            <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-span-12 flex flex-wrap justify-between items-center gap-2 mt-2">
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="btn btn-sm bg-info-500 text-white hover:bg-info-600" title="Simpan Perubahan">
                                                                <i data-feather="save" class="w-4 h-4 mr-1 inline-block"></i> Simpan
                                                            </button>
                                                            <a href="<?= site_url('admin/delete_template_item/' . $template->id . '/' . $item->id); ?>" 
                                                                class="btn btn-sm bg-danger-500 text-white hover:bg-danger-600" title="Hapus Item" onclick="return confirm('Hapus item ini?');">
                                                                <i data-feather="trash-2" class="w-4 h-4 mr-1 inline-block"></i> Hapus
                                                            </a>
                                                        </div>
                                                        <div class="flex space-x-2">
                                                            <a href="<?= site_url('admin/reorder_item/' . $template->id . '/' . $item->id . '/up'); ?>" 
                                                                class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600" title="Pindah Naik">
                                                                <i data-feather="arrow-up" class="w-4 h-4"></i>
                                                            </a>
                                                            <a href="<?= site_url('admin/reorder_item/' . $template->id . '/' . $item->id . '/down'); ?>" 
                                                                class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600" title="Pindah Turun">
                                                                <i data-feather="arrow-down" class="w-4 h-4"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                <?= form_close(); ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted text-sm">Belum ada item untuk Hari Ke-<?= $h; ?>.</p>
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
    
    document.addEventListener('DOMContentLoaded', function() {

        // --- [LOGIC UNTUK FORM TAMBAH ITEM BARU] ---
        const tipeItemAddSelect = document.getElementById('tipe_item_add');
        if (tipeItemAddSelect) {
            const pjFieldAdd = document.getElementById('pj_peran_field_add');
            const pjUserFieldAdd = document.getElementById('pj_user_field_add');
            const pjPeranSelectAdd = pjFieldAdd.querySelector('select');

            function togglePjFieldAdd() {
                if (tipeItemAddSelect.value === 'checklist') {
                    pjFieldAdd.style.display = 'block';
                    pjUserFieldAdd.style.display = 'block';
                    pjPeranSelectAdd.setAttribute('required', 'required');
                } else {
                    pjFieldAdd.style.display = 'none';
                    pjUserFieldAdd.style.display = 'none';
                    pjPeranSelectAdd.removeAttribute('required');
                }
            }
            tipeItemAddSelect.addEventListener('change', togglePjFieldAdd);
            togglePjFieldAdd(); // Jalankan saat load
        }

        // --- [LOGIC OPTIMAL UNTUK SEMUA FORM EDIT ITEM] ---
        const itineraryListContainer = document.getElementById('itinerary-list-container');
        
        if (itineraryListContainer) {
            
            // Fungsi terpusat untuk toggle field edit
            function toggleEditPjFields(selectElement) {
                // Temukan '.js-item-card' terdekat
                const itemCard = selectElement.closest('.js-item-card');
                if (!itemCard) return;

                const pjPeranField = itemCard.querySelector('.js-pj-peran-field');
                const pjUserField = itemCard.querySelector('.js-pj-user-field');
                const pjPeranSelect = pjPeranField.querySelector('select');

                if (selectElement.value === 'checklist') {
                    pjPeranField.style.display = 'block';
                    pjUserField.style.display = 'block';
                    pjPeranSelect.setAttribute('required', 'required');
                } else {
                    pjPeranField.style.display = 'none';
                    pjUserField.style.display = 'none';
                    pjPeranSelect.removeAttribute('required');
                }
            }

            // 1. Event listener terpusat untuk 'change'
            itineraryListContainer.addEventListener('change', function(event) {
                // Hanya bereaksi jika targetnya adalah select '.js-tipe-item-edit'
                if (event.target.classList.contains('js-tipe-item-edit')) {
                    toggleEditPjFields(event.target);
                }
            });

            // 2. Jalankan fungsi toggle untuk semua item saat halaman pertama kali dimuat
            const allEditSelects = itineraryListContainer.querySelectorAll('.js-tipe-item-edit');
            allEditSelects.forEach(function(select) {
                toggleEditPjFields(select);
            });
        }
    });
</script>