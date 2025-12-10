<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-error-alt me-2 flex-shrink-0"></i> <?php echo $this->session->flashdata('error'); ?>
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
                    <p class="small mb-1"><strong>Template Asal:</strong> ID <?php echo $grup->template_asal_id; ?></p>
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
        <div class="col-12" id="live-checklist-container">
            <div class="card shadow-md">
                <div class="card-header border-bottom p-4 bg-light">
                    <h5 class="mb-0 fw-medium d-flex align-items-center"><i class="bx bx-calendar-check me-2"></i> Live Checklist Monitoring & Edit</h5>
                    <small class="text-muted small d-block mt-1">Status eksekusi real-time dan opsi edit langsung item checklist.</small>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($grouped_items)): ?>
                        <div class="alert alert-warning text-center" role="alert">
                            <i class="bx bx-alert-triangle me-2"></i> Checklist live belum dicetak. Pastikan template memiliki item.
                        </div>
                    <?php else: ?>
                        <?php 
                        // Palet Warna Header Card Harian (Sneat Labels)
                        $day_header_colors = [
                            'bg-label-info',
                            'bg-label-success',
                            'bg-label-warning',
                            'bg-label-primary',
                            'bg-label-secondary',
                            'bg-label-danger'
                        ];
                        $colors_count = count($day_header_colors);
                        $day_index = 0;
                        
                        $status_class = [
                            'Pending' => 'secondary',
                            'Sukses' => 'success',
                            'Cukup' => 'info',
                            'Buruk' => 'warning',
                            'Gagal' => 'danger'
                        ];
                        ?>

                        <?php foreach ($grouped_items as $tgl => $items_per_day): ?>
                            
                            <?php 
                            $current_header_class = $day_header_colors[($day_index) % $colors_count]; 
                            $day_index++;
                            ?>

                            <div class="card border border-dark mb-4 shadow-lg">
                                <div class="card-header p-3 <?= $current_header_class; ?>">
                                    <h6 class="mb-0 small fw-bold d-flex align-items-center">
                                        <i class="bx bx-calendar me-2"></i> <?php echo date('d M Y', strtotime($tgl)); ?>
                                        <small class="opacity-80 ms-2 fw-medium">(Hari ke-<?php echo $day_index; ?>)</small>
                                    </h6>
                                </div>
                                <div class="p-3">
                                    <?php foreach ($items_per_day as $item): ?>
                                        <?php
                                            $error_edit = $this->session->flashdata('error_form_edit_' . $item->id);
                                            $current_status_class = $status_class[$item->status] ?? 'secondary';
                                            
                                            // Terapkan border khusus untuk item
                                            $item_border_class = 'border';
                                            if ($error_edit) {
                                                $item_border_class = 'border border-danger border-3';
                                            }
                                        ?>
                                        
                                        <div class="js-item-card card shadow-sm mb-3 p-3 transition-all duration-300 <?= $item_border_class; ?> <?= $error_edit ? 'bg-light' : 'bg-white'; ?>">
                                            
                                            <?php if ($error_edit): ?>
                                                <div class="alert alert-danger p-2 mb-3 small d-flex align-items-center">
                                                    <i class="bx bx-x-octagon me-2 flex-shrink-0"></i> <?= $error_edit; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?= form_open('admin/edit_grup_item_action/' . $grup->id . '/' . $item->id, 'id="editForm_' . $item->id . '" class="row g-2 align-items-start"'); ?>

                                                <div class="col-12 d-flex flex-wrap align-items-center gap-2 mb-2 border-bottom pb-2">
                                                    <span class="badge bg-<?php echo $current_status_class; ?> me-2 flex-shrink-0 px-2 py-1 small fw-semibold"><?php echo $item->status; ?></span>
                                                    <?php if ($item->tipe_item == 'checklist'): ?>
                                                        <span class="text-xs text-info me-2 flex-shrink-0 fw-medium">[PJ Aktif: <?php echo $item->pj_nama ? $item->pj_nama : 'Belum Ditugaskan'; ?>]</span>
                                                    <?php else: ?>
                                                        <span class="text-xs text-muted me-2 flex-shrink-0 fst-italic">(! Info Item)</span>
                                                    <?php endif; ?>
                                                    <span class="text-xs text-primary fw-medium">Urutan: <?= $item->urutan; ?></span>
                                                </div>
                                                
                                                <div class="col-12 col-md-4 form-group mb-0">
                                                    <label for="deskripsi_item_<?= $item->id; ?>" class="form-label small fw-medium mb-1">Deskripsi Tugas</label>
                                                    <input type="text" id="deskripsi_item_<?= $item->id; ?>" name="deskripsi_item" class="form-control form-control-sm" value="<?= $item->deskripsi; ?>" required>
                                                </div>

                                                <div class="col-6 col-md-2 form-group mb-0">
                                                    <label for="tipe_item_<?= $item->id; ?>" class="form-label small fw-medium mb-1">Tipe Item</label>
                                                    <select id="tipe_item_<?= $item->id; ?>" name="tipe_item" class="form-select form-select-sm js-tipe-item-edit">
                                                        <option value="">-- Pilih Item --</option>
                                                        <option value="info" <?= ($item->tipe_item=='info')?'selected':''; ?>>📝 Info</option>
                                                        <option value="checklist" <?= ($item->tipe_item=='checklist')?'selected':''; ?>>✅ Checklist</option>
                                                    </select>
                                                </div>

                                                <div class="col-6 col-md-3 form-group mb-0 js-pj-peran-field">
                                                    <label for="pj_peran_id_<?= $item->id; ?>" class="form-label small fw-medium mb-1">Ganti PJ Peran</label>
                                                    <select id="pj_peran_id_<?= $item->id; ?>" name="pj_peran_id" class="form-select form-select-sm">
                                                        <option value="">-- Pilih Peran --</option>
                                                        <?php foreach($peran as $p): ?>
                                                        <option value="<?= $p->id; ?>" <?= ($item->peran_tugas_id==$p->id)?'selected':''; ?>>
                                                            <?= $p->nama_peran; ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-12 col-md-3 form-group mb-0 js-pj-user-field">
                                                    <label for="pj_user_id_<?= $item->id; ?>" class="form-label small fw-medium mb-1">Ganti PJ User</label>
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
                                                
                                                <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2 pt-2 border-top">
                                                    
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
                                                <?= form_close(); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const liveChecklistContainer = document.getElementById('live-checklist-container');
        
        if (liveChecklistContainer) {
            
            // Fungsi terpusat untuk toggle field edit
            function toggleEditPjFields(selectElement) {
                const itemCard = selectElement.closest('.js-item-card');
                if (!itemCard) return;

                const pjPeranField = itemCard.querySelector('.js-pj-peran-field');
                const pjUserField = itemCard.querySelector('.js-pj-user-field');
                const pjPeranSelect = pjPeranField.querySelector('select');
                const pjUserSelect = pjUserField.querySelector('select'); 

                if (selectElement.value === 'checklist') {
                    // Tampilkan field
                    pjPeranField.style.display = 'block';
                    pjUserField.style.display = 'block';
                } else {
                    // Sembunyikan field
                    pjPeranField.style.display = 'none';
                    pjUserField.style.display = 'none';
                    // Hapus required attribute (jika sebelumnya ada, penting untuk mencegah validasi browser)
                    pjPeranSelect.removeAttribute('required');
                    pjUserSelect.removeAttribute('required'); 
                }
            }

            // 1. Event listener terpusat untuk 'change'
            liveChecklistContainer.addEventListener('change', function(event) {
                // Hanya bereaksi jika targetnya adalah select '.js-tipe-item-edit'
                if (event.target.classList.contains('js-tipe-item-edit')) {
                    toggleEditPjFields(event.target);
                }
            });

            // 2. Jalankan fungsi toggle untuk semua item saat halaman pertama kali dimuat
            const allEditSelects = liveChecklistContainer.querySelectorAll('.js-tipe-item-edit');
            allEditSelects.forEach(function(select) {
                toggleEditPjFields(select);
            });
        }
    });
</script>