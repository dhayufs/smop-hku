<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// --- Pengecekan Variabel PHP yang Robust ---
// Ini adalah penanganan error untuk mencegah Undefined Variable/Property Notices
$is_template_valid = isset($template) && is_object($template);

if (!$is_template_valid) {
    // Tetapkan nilai default aman jika data tidak valid
    $template = (object)['id' => 0, 'nama_template' => 'N/A', 'lama_manasik' => 0, 'lama_perjalanan' => 0];
}

// Catatan: Variabel $grouped_items diasumsikan sudah terisi oleh Controller Admin.php
// dengan kunci: 'tipe_blok_hari_ke'

// Sneat/Bootstrap equivalent color mapping untuk header hari
$sneat_day_colors = [
    'bg-label-info',    // Blue/Cyan
    'bg-label-success', // Green
    'bg-label-warning', // Yellow/Orange
    'bg-label-primary', // Primary Blue
    'bg-label-secondary', // Gray
    'bg-label-danger'   // Red
];
$colors_count = count($sneat_day_colors);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data / Template /</span> Detail
    </h4>

    <div class="row">
        <div class="col-12">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-check-circle me-2"></i> <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-error-alt me-2"></i> <?= $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-list-ul me-2"></i> Struktur Template: <?= $template->nama_template; ?>
            </h5>
            <div class="d-flex gap-2">
                <a href="<?= site_url('admin/template_form/' . $template->id); ?>" class="btn btn-info btn-sm">
                    <i class="bx bx-edit me-1"></i> Edit Info
                </a>
                <a href="<?= site_url('admin/delete_template/' . $template->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus template ini? Semua item di dalamnya akan ikut terhapus!');">
                    <i class="bx bx-trash me-1"></i> Hapus Template
                </a>
                <a href="<?= site_url('admin/templates'); ?>" class="btn btn-secondary btn-sm">
                    <i class="bx bx-left-arrow-alt me-1"></i> Kembali
                </a>
            </div>
        </div>
        
        <div class="card-body p-6">
            
            <div class="mb-4">
                <p class="mb-0"><strong>Durasi:</strong> Manasik <span class="badge bg-info"><?= $template->lama_manasik; ?></span> Hari | Perjalanan <span class="badge bg-warning"><?= $template->lama_perjalanan; ?></span> Hari</p>
            </div>
            
            <hr class="my-4">

            <div class="row g-4">
                
                <div class="col-12" id="itinerary-list-container"> 
                    <h6 class="fw-semibold mb-3"><i class="bx bx-layer me-1"></i> Daftar Item Itinerary</h6>
                    
                    <?php 
                    $day_colors = $sneat_day_colors;
                    ?>
                    
                    <?php foreach (['manasik' => 'Manasik', 'perjalanan' => 'Perjalanan'] as $blok_key => $blok_label): ?>
                        
                        <?php $max_hari = ($blok_key == 'manasik') ? $template->lama_manasik : $template->lama_perjalanan; ?>
                        
                        <?php if ($max_hari > 0): ?>
                            <h6 class="mt-4 text-primary fw-bold">BLOK <?= strtoupper($blok_label); ?></h6>
                        <?php endif; ?>

                        <?php for ($h = 1; $h <= $max_hari; $h++): ?>
                            <div class="card border border-dark mb-3">
                                
                                <?php 
                                $current_color = $day_colors[($h - 1) % $colors_count]; 
                                $key = $blok_key . '_' . $h;
                                $items_exist = isset($grouped_items[$key]);
                                ?>

                                <div class="card-header p-3 <?= $current_color; ?>">
                                    <h6 class="mb-0 small fw-semibold">Hari Ke-<?= $h; ?> (<?= ucfirst($blok_key); ?>)</h6>
                                </div>

                                <div class="p-3">
                                    <?php if ($items_exist): ?>
                                        <?php foreach ($grouped_items[$key]['list'] as $item): ?>
                                            
                                            <?php
                                                $form_id = 'editForm_' . $item->id;
                                                $error_edit = $this->session->flashdata('error_form_edit_' . $item->id);
                                            ?>

                                            <div class="js-item-card card shadow-sm mb-3 p-3 transition-all duration-300 border <?= $error_edit ? 'border-danger border-3 bg-light' : 'bg-white'; ?>">
                                                
                                                <?php if ($error_edit): ?>
                                                    <div class="alert alert-danger p-2 mb-3 small d-flex align-items-center">
                                                        <i class="bx bx-x-octagon me-2 flex-shrink-0"></i> <?= $error_edit; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?= form_open('admin/edit_template_item_action/' . $template->id . '/' . $item->id, 'id="' . $form_id . '" class="row g-2 align-items-start"'); ?>
                                                
                                                    <div class="col-12 d-flex flex-wrap align-items-start gap-3 mb-3 border-bottom pb-3">
                                                        
                                                        <div class="form-group mb-0 flex-grow-1" style="min-width: 150px;">
                                                            <label class="form-label small fw-medium mb-1">Tipe Item</label>
                                                            <select name="tipe_item" class="form-select form-select-sm js-tipe-item-edit">
                                                                <option value="">-- Pilih Item --</option>
                                                                <option value="info" <?= ($item->tipe_item=='info')?'selected':''; ?>>📝 Info</option>
                                                                <option value="checklist" <?= ($item->tipe_item=='checklist')?'selected':''; ?>>✅ Checklist</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-0 js-pj-user-field flex-grow-1" style="min-width: 200px;">
                                                            <label class="form-label small fw-medium mb-1">Pelaksana</label>
                                                            <select name="pj_user_id_default" class="form-select form-select-sm">
                                                                <option value="">-- Pilih User --</option>
                                                                <?php foreach ($users as $u): ?>
                                                                <?php if ($u->system_role === 'user'): ?>
                                                                <option value="<?= $u->id; ?>" <?= ($item->pj_user_id_default==$u->id)?'selected':''; ?>>
                                                                    <?= $u->nama_lengkap; ?>
                                                                </option>
                                                                <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <input type="hidden" name="pj_peran_id" value="<?= $item->pj_peran_id; ?>"> 
                                                        </div>

                                                        <div class="d-flex align-items-end gap-2">
                                                            <a href="<?= site_url('admin/reorder_item/' . $template->id . '/' . $item->id . '/up'); ?>" 
                                                                class="btn btn-sm btn-icon btn-primary" title="Pindah Naik">
                                                                <i class="bx bx-up-arrow-alt"></i>
                                                            </a>
                                                            <a href="<?= site_url('admin/reorder_item/' . $template->id . '/' . $item->id . '/down'); ?>" 
                                                                class="btn btn-sm btn-icon btn-primary" title="Pindah Turun">
                                                                <i class="bx bx-down-arrow-alt"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 form-group mb-0">
                                                        <label class="form-label small fw-medium mb-1">Tugas <?= $item->urutan; ?></label>
                                                        <textarea name="deskripsi_item" class="form-control form-control-sm" rows="3" required><?= $item->deskripsi_item; ?></textarea>
                                                    </div>
                                                    <div class="col-12 d-flex justify-content-end align-items-center gap-2 mt-2 pt-2 border-top">
                                                        <button type="submit" class="btn btn-sm btn-info" title="Simpan Perubahan">
                                                            <i class="bx bx-save me-1"></i> Simpan
                                                        </button>
                                                        <a href="<?= site_url('admin/delete_template_item/' . $template->id . '/' . $item->id); ?>" class="btn btn-sm btn-danger" title="Hapus Item" onclick="return confirm('Hapus item ini?');">
                                                            <i class="bx bx-trash me-1"></i> Hapus
                                                        </a>
                                                    </div>

                                                <?= form_close(); ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted small">Belum ada item untuk Hari Ke-<?= $h; ?>.</p>
                                    <?php endif; ?>
                                    
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdd_<?= $key; ?>" aria-expanded="false" aria-controls="collapseAdd_<?= $key; ?>">
                                            <i class="bx bx-plus me-1"></i> Tambah Item Hari Ini
                                        </button>
                                        <div class="collapse pt-3" id="collapseAdd_<?= $key; ?>">
                                            <div class="card card-body bg-light border">
                                                <h6 class="fw-semibold small mb-3">Tambah Item Hari Ke-<?= $h; ?></h6>
                                                <?= form_open('admin/add_template_item/' . $template->id, 'id="addForm_' . $key . '" class="row g-2"'); ?>
                                                    
                                                    <input type="hidden" name="tipe_blok" value="<?= $blok_key; ?>">
                                                    <input type="hidden" name="hari_ke" value="<?= $h; ?>">
                                                    <input type="hidden" name="pj_peran_id" value=""> 

                                                    <div class="col-12 form-group">
                                                        <label class="form-label small">Deskripsi Tugas</label>
                                                        <textarea name="deskripsi_item" class="form-control form-control-sm" rows="3" required></textarea>
                                                    </div>
                                                    
                                                    <div class="col-6 form-group">
                                                        <label class="form-label small">Jenis Item</label>
                                                        <select name="tipe_item" id="tipe_item_add_<?= $key; ?>" class="form-select form-select-sm js-tipe-item-add" required>
                                                            <option value="info">📝 Informasi</option>
                                                            <option value="checklist" selected>✅ Checklist</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-6 form-group js-pj-user-field-add">
                                                        <label class="form-label small">Pelaksana (Opsional)</label>
                                                        <select name="pj_user_id_default" class="form-select form-select-sm">
                                                            <option value="">-- Pilih User --</option>
                                                            <?php foreach ($users as $u): ?>
                                                                <?php if ($u->system_role === 'user'): ?>
                                                                    <option value="<?= $u->id; ?>"><?= $u->nama_lengkap; ?></option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-12 text-end">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bx bx-save me-1"></i> Simpan Item
                                                        </button>
                                                    </div>                        
                                                    
                                                <?= form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
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
    // Script JavaScript asli dipertahankan untuk fungsionalitas checklist/info toggle

    document.addEventListener('DOMContentLoaded', function() {

        // --- [LOGIC UNTUK FORM TAMBAH ITEM BARU DI MASING-MASING HARI] ---
        const itineraryListContainer = document.getElementById('itinerary-list-container');
        
        if (itineraryListContainer) {
            
            // Fungsi terpusat untuk toggle field PJ (Pelaksana) pada form TAMBAH
            function toggleAddPjFields(selectElement) {
                // Temukan '.card-body' terdekat (container form tambah)
                const addFormContainer = selectElement.closest('.card-body');
                if (!addFormContainer) return;

                const pjUserField = addFormContainer.querySelector('.js-pj-user-field-add');

                if (!pjUserField) return;

                if (selectElement.value === 'checklist') {
                    pjUserField.style.display = 'block';
                } else {
                    pjUserField.style.display = 'none';
                }
            }
            
            // Fungsi terpusat untuk toggle field PJ (Pelaksana) pada form EDIT
            function toggleEditPjFields(selectElement) {
                const itemCard = selectElement.closest('.js-item-card');
                if (!itemCard) return;

                const pjUserField = itemCard.querySelector('.js-pj-user-field');

                if (!pjUserField) return;

                if (selectElement.value === 'checklist') {
                    pjUserField.style.display = 'block';
                } else {
                    pjUserField.style.display = 'none';
                }
            }

            // 1. Event listener terpusat untuk 'change'
            itineraryListContainer.addEventListener('change', function(event) {
                // Cek form Tambah Item (hanya form baru yang punya class ini)
                if (event.target.classList.contains('js-tipe-item-add')) {
                    toggleAddPjFields(event.target);
                }
                // Cek form Edit Item (form yang sudah ada)
                if (event.target.classList.contains('js-tipe-item-edit')) {
                    toggleEditPjFields(event.target);
                }
            });

            // 2. Jalankan fungsi toggle untuk semua item saat halaman pertama kali dimuat
            const allEditSelects = itineraryListContainer.querySelectorAll('.js-tipe-item-edit');
            allEditSelects.forEach(function(select) {
                toggleEditPjFields(select);
            });
            
            // 3. Jalankan fungsi toggle untuk semua form Add (jika sudah terbuka)
            const allAddSelects = itineraryListContainer.querySelectorAll('.js-tipe-item-add');
            allAddSelects.forEach(function(select) {
                toggleAddPjFields(select);
            });
        }
    });
</script>