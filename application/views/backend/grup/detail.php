<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// --- Pengecekan Variabel PHP yang Robust ---
// Variabel lama_manasik, lama_perjalanan, dan sneat_day_colors diasumsikan sudah ada dari controller.
if (!isset($lama_manasik)) $lama_manasik = 0;
if (!isset($lama_perjalanan)) $lama_perjalanan = 0;
if (!isset($sneat_day_colors)) {
    // Sneat/Bootstrap equivalent color mapping untuk header hari (fallback)
    $sneat_day_colors = [
        'bg-label-info',    // Blue/Cyan
        'bg-label-success', // Green
        'bg-label-warning', // Yellow/Orange
        'bg-label-primary', // Primary Blue
        'bg-label-secondary', // Gray
        'bg-label-danger'   // Red
    ];
}
$colors_count = count($sneat_day_colors);

// Mapping untuk status
$status_class = [
    'Pending' => 'secondary',
    'Sukses' => 'success',
    'Cukup' => 'info',
    'Buruk' => 'warning',
    'Gagal' => 'danger'
];
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Monitoring /</span> Grup: <?= $grup->nama_grup; ?>
    </h4>
    
    <div class="row">
        <div class="col-12">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-check-circle me-2 flex-shrink-0"></i> <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-error-alt me-2 flex-shrink-0"></i> <?php echo $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row mb-4">
        
        <div class="col-12 col-lg-6"> 
            <div class="card shadow-md">
                <div class="card-header border-bottom p-4 bg-label-primary">
                    <h6 class="mb-0 fw-medium d-flex align-items-center"><i class="bx bx-info-circle me-2"></i> Ringkasan Grup</h6>
                </div>
                <div class="card-body p-4">
                    <p class="small mb-1"><strong>Nama Grup:</strong> <?php echo $grup->nama_grup; ?></p>
                    <p class="small mb-1"><strong>Template Asal:</strong> ID <?php echo $grup->template_asal_id; ?> (<?php echo $grup->nama_template; ?>)</p>
                    <p class="small mb-1"><strong>Mulai Manasik:</strong> <?php echo date('d M Y', strtotime($grup->tanggal_mulai_manasik)); ?></p>
                    <p class="small mb-1"><strong>Keberangkatan:</strong> <?php echo date('d M Y', strtotime($grup->tanggal_keberangkatan)); ?></p>
                    <p class="small mb-3"><strong>Kepulangan:</strong> <?php echo date('d M Y', strtotime($grup->tanggal_pulang)); ?></p>
                    
                    <p class="small mb-3"><strong>Status Grup:</strong> 
                        <span class="badge bg-success px-2 py-1"><?php echo $grup->status_grup; ?></span>
                    </p>
                    
                    <hr class="my-3">
                    
                    <h6 class="fw-semibold small mb-3 text-warning">Aksi Admin</h6>
                    <div class="d-grid gap-2">
                        <a href="<?php echo site_url('admin/grup_form/' . $grup->id); ?>" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center">
                            <i class="bx bx-edit me-1"></i> Edit Detail Grup
                        </a>
                        <a href="<?php echo site_url('admin/delete_grup/' . $grup->id); ?>" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" onclick="return confirm('Yakin hapus grup ini? SEMUA DATA CHECKLIST AKKAN HILANG!');">
                            <i class="bx bx-trash me-1"></i> Hapus Grup
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-4 mt-lg-0">
            <div class="card shadow-md h-100">
                <div class="card-header border-bottom p-4 bg-label-info">
                    <h6 class="mb-0 fw-medium small d-flex align-items-center"><i class="bx bx-group me-2"></i> Tim Lapangan Ditugaskan</h6>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($penugasan)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($penugasan as $p): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center small px-0 py-2">
                                    <strong class="text-primary"><?php echo $p->nama_peran; ?>:</strong>
                                    <span class="text-dark"><?php echo $p->nama_lengkap; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted small fst-italic">Tidak ada tim yang ditugaskan ke grup ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12" id="itinerary-list-container">
            <div class="card shadow-md">
                <div class="card-header border-bottom p-4 bg-light">
                    <h5 class="mb-0 fw-medium d-flex align-items-center"><i class="bx bx-calendar-check me-2"></i> Live Checklist Monitoring & Edit</h5>
                    <small class="text-muted small d-block mt-1">Status eksekusi real-time dan opsi edit/tambah langsung item checklist.</small>
                </div>
                <div class="card-body p-4">
                    
                    <?php 
                    $day_colors = $sneat_day_colors;
                    $total_item_count = count($grouped_items);
                    ?>
                    
                    <?php if ($total_item_count == 0 && $lama_manasik == 0 && $lama_perjalanan == 0): ?>
                        <div class="alert alert-warning text-center" role="alert">
                            <i class="bx bx-alert-triangle me-2"></i> Checklist live belum dicetak. Pastikan template memiliki item.
                        </div>
                    <?php endif; ?>

                    <?php foreach (['manasik' => 'Manasik', 'perjalanan' => 'Perjalanan'] as $blok_key => $blok_label): ?>
                        
                        <?php $max_hari = ($blok_key == 'manasik') ? $lama_manasik : $lama_perjalanan; ?>
                        
                        <?php if ($max_hari > 0): ?>
                            <h6 class="mt-4 text-primary fw-bold">BLOK <?= strtoupper($blok_label); ?></h6>
                        <?php endif; ?>

                        <?php for ($h = 1; $h <= $max_hari; $h++): ?>
                            <div class="card border border-dark mb-3">
                                
                                <?php 
                                $current_color = $day_colors[($h - 1) % $colors_count]; 
                                $key = $blok_key . '_' . $h;
                                $items_exist = isset($grouped_items[$key]);
                                $current_day_data = $items_exist ? $grouped_items[$key] : null;
                                
                                $tanggal_item_display = $current_day_data ? date('d M Y', strtotime($current_day_data['tanggal_item'])) : 'Tanggal N/A (Item Belum Dicetak)';
                                $tanggal_item_input = $current_day_data ? $current_day_data['tanggal_item'] : '';
                                
                                $is_add_disabled = ($tanggal_item_input == '');
                                ?>

                                <div class="card-header p-3 <?= $current_color; ?>">
                                    <h6 class="mb-0 small fw-semibold">
                                        Hari Ke-<?= $h; ?> (<?= ucfirst($blok_key); ?>) 
                                        <small class="opacity-80 ms-2 fw-medium">(<?= $tanggal_item_display; ?>)</small>
                                    </h6>
                                </div>

                                <div class="p-3">
                                    <?php if ($items_exist): ?>
                                        <?php foreach ($current_day_data['list'] as $item): ?>
                                            
                                            <?php
                                                $form_id = 'editForm_' . $item->id;
                                                $error_edit = $this->session->flashdata('error_form_edit_' . $item->id);
                                                $current_status_class = $status_class[$item->status] ?? 'secondary';
                                                $item_border_class = $error_edit ? 'border border-danger border-3 bg-light' : 'bg-white';
                                            ?>

                                            <div class="js-item-card card shadow-sm mb-3 p-3 transition-all duration-300 border <?= $item_border_class; ?>">
                                                
                                                <?php if ($error_edit): ?>
                                                    <div class="alert alert-danger p-2 mb-3 small d-flex align-items-center">
                                                        <i class="bx bx-x-octagon me-2 flex-shrink-0"></i> <?= $error_edit; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?= form_open('admin/edit_grup_item_action/' . $grup->id . '/' . $item->id, 'id="' . $form_id . '" class="row g-2 align-items-start"'); ?>
                                                
                                                    <div class="col-12 d-flex flex-wrap align-items-center gap-2 mb-2 border-bottom pb-2">
                                                        <span class="badge bg-<?php echo $current_status_class; ?> me-2 flex-shrink-0 px-2 py-1 small fw-semibold"><?php echo $item->status; ?></span>
                                                        <?php if ($item->tipe_item == 'checklist'): ?>
                                                            <span class="text-xs text-info me-2 flex-shrink-0 fw-medium">
                                                                [PJ Aktif: <?php echo $item->pj_nama ? $item->pj_nama : ($item->nama_peran ? $item->nama_peran : 'Belum Ditugaskan'); ?>]
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-xs text-muted me-2 flex-shrink-0 fst-italic">(! Info Item)</span>
                                                        <?php endif; ?>
                                                    </div>
                                                
                                                    <div class="col-12 d-flex flex-wrap align-items-start gap-3 mb-3 border-bottom pb-3">
                                                        
                                                        <div class="form-group mb-0 flex-grow-1" style="min-width: 150px;">
                                                            <label for="tipe_item_<?= $item->id; ?>" class="form-label small fw-medium mb-1">Tipe Item</label>
                                                            <select id="tipe_item_<?= $item->id; ?>" name="tipe_item" class="form-select form-select-sm js-tipe-item-edit">
                                                                <option value="">-- Pilih Item --</option>
                                                                <option value="info" <?= ($item->tipe_item=='info')?'selected':''; ?>>📝 Info</option>
                                                                <option value="checklist" <?= ($item->tipe_item=='checklist')?'selected':''; ?>>✅ Checklist</option>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="pj_peran_id" value="<?= $item->peran_tugas_id; ?>"> 
                                                        <div class="form-group mb-0 js-pj-user-field flex-grow-1" style="min-width: 200px;">
                                                            <label for="pj_user_id_<?= $item->id; ?>" class="form-label small fw-medium mb-1">Pelaksana</label>
                                                            <select id="pj_user_id_<?= $item->id; ?>" name="pj_user_id" class="form-select form-select-sm">
                                                                <option value="">-- Pilih User --</option>
                                                                <?php foreach ($users as $u): ?>
                                                                <?php if ($u->system_role === 'user'): ?>
                                                                <option value="<?= $u->id; ?>" <?= ($item->pj_user_id==$u->id)?'selected':''; ?>>
                                                                    <?= $u->nama_lengkap; ?>
                                                                </option>
                                                                <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="d-flex align-items-end gap-2">
                                                            <a href="<?= site_url('admin/reorder_grup_item/' . $grup->id . '/' . $item->id . '/up'); ?>" 
                                                                class="btn btn-sm btn-icon btn-primary" title="Pindah Naik">
                                                                <i class="bx bx-up-arrow-alt"></i>
                                                            </a>
                                                            <a href="<?= site_url('admin/reorder_grup_item/' . $grup->id . '/' . $item->id . '/down'); ?>" 
                                                                class="btn btn-sm btn-icon btn-primary" title="Pindah Turun">
                                                                <i class="bx bx-down-arrow-alt"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 form-group mb-0">
                                                        <label for="deskripsi_item_<?= $item->id; ?>" class="form-label small fw-medium mb-1">Tugas <?= $item->urutan; ?></label>
                                                        <textarea id="deskripsi_item_<?= $item->id; ?>" name="deskripsi_item" class="form-control form-control-sm" rows="3" required><?= $item->deskripsi; ?></textarea>
                                                    </div>
                                                    <div class="col-12 d-flex flex-wrap justify-content-end align-items-center gap-2 mt-2 pt-2 border-top">
                                                    
                                                    <div class="d-flex gap-2">
                                                        <?php if ($item->foto_bukti): ?>
                                                            <a href="<?php echo base_url($item->foto_bukti); ?>" target="_blank" class="btn btn-sm btn-primary d-flex align-items-center">
                                                                <i class="bx bx-image me-1"></i> Bukti Foto
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($item->tipe_item == 'checklist'): ?>
                                                            <a href="<?php echo site_url('admin/item_history/' . $item->id); ?>" class="btn btn-sm btn-secondary d-flex align-items-center" >
                                                                <i class="bx bx-history me-1"></i> Riwayat
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="d-flex gap-2 ms-auto">
                                                        <button type="submit" class="btn btn-sm btn-info" title="Simpan Perubahan">
                                                            <i class="bx bx-save me-1"></i> Simpan
                                                        </button>
                                                        <a href="<?= site_url('admin/delete_grup_item/' . $grup->id . '/' . $item->id); ?>" 
                                                            class="btn btn-sm btn-danger d-flex align-items-center" title="Hapus Item" onclick="return confirm('Hapus item live checklist ini?');">
                                                            <i class="bx bx-trash me-1"></i> Hapus
                                                        </a>
                                                    </div>
                                                </div>
                                                <?= form_close(); ?>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted small">Belum ada item untuk Hari Ke-<?= $h; ?>.</p>
                                    <?php endif; ?>
                                    
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdd_<?= $key; ?>" aria-expanded="false" aria-controls="collapseAdd_<?= $key; ?>" <?= $is_add_disabled ? 'disabled title="Tidak bisa menambah item karena tanggal item belum tersedia. Pastikan template memiliki item perjalanan."' : ''; ?>>
                                            <i class="bx bx-plus me-1"></i> Tambah Item Hari Ini
                                        </button>
                                        <div class="collapse pt-3" id="collapseAdd_<?= $key; ?>">
                                            <div class="card card-body bg-light border">
                                                <h6 class="fw-semibold small mb-3">Tambah Item Hari Ke-<?= $h; ?> (<?= $tanggal_item_display; ?>)</h6>
                                                <?= form_open('admin/add_grup_item/' . $grup->id, 'id="addForm_' . $key . '" class="row g-2"'); ?>
                                                    
                                                    <input type="hidden" name="tipe_blok" value="<?= $blok_key; ?>">
                                                    <input type="hidden" name="hari_ke" value="<?= $h; ?>">
                                                    <input type="hidden" name="tanggal_item" value="<?= $tanggal_item_input; ?>">
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
    document.addEventListener('DOMContentLoaded', function() {

        const itineraryListContainer = document.getElementById('itinerary-list-container');
        
        if (itineraryListContainer) {
            
            // Fungsi terpusat untuk toggle field PJ (Pelaksana) pada form TAMBAH
            function toggleAddPjFields(selectElement) {
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
                // Cek form Tambah Item
                if (event.target.classList.contains('js-tipe-item-add')) {
                    toggleAddPjFields(event.target);
                }
                // Cek form Edit Item
                if (event.target.classList.contains('js-tipe-item-edit')) {
                    toggleEditPjFields(event.target);
                }
            });

            // 2. Jalankan fungsi toggle untuk semua item saat halaman pertama kali dimuat (edit form)
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