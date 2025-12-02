<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success bg-green-100 text-green-800 p-3 mb-4 flex items-center"><i data-feather="check-circle" class="w-4 h-4 mr-2 flex-shrink-0"></i> <?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger bg-red-100 text-red-800 p-3 mb-4 flex items-center"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 flex-shrink-0"></i> <?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    
    <div class="grid grid-cols-12 gap-x-6">
        
        <div class="col-span-12 lg:col-span-3">
            <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
                <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4 bg-primary-500 text-white">
                    <h6 class="mb-0 font-medium flex items-center"><i data-feather="info" class="w-4 h-4 mr-2 inline-block"></i> Ringkasan Grup</h6>
                </div>
                <div class="card-body p-4">
                    <p class="text-sm mb-1"><strong>Nama Grup:</strong> <?php echo $grup->nama_grup; ?></p>
                    <p class="text-sm mb-1"><strong>Template Asal:</strong> ID <?php echo $grup->template_asal_id; ?></p>
                    <p class="text-sm mb-1"><strong>Keberangkatan:</strong> <?php echo date('d M Y', strtotime($grup->tanggal_keberangkatan)); ?></p>
                    <p class="text-sm mb-3"><strong>Kepulangan:</strong> <?php echo date('d M Y', strtotime($grup->tanggal_pulang)); ?></p>
                    
                    <p class="text-sm mb-3"><strong>Status Grup:</strong> 
                        <span class="badge bg-success-500 text-white px-2 py-1 text-xs"><?php echo $grup->status_grup; ?></span>
                    </p>
                    
                    <hr class="my-4 border-t border-gray-200 dark:border-gray-700">
                    
                    <h6 class="font-semibold text-sm mb-3 text-warning-500">Aksi Admin</h6>
                    <div class="flex flex-col space-y-2">
                        <a href="<?php echo site_url('admin/grup_form/' . $grup->id); ?>" class="btn bg-primary-500 text-white hover:bg-primary-600 btn-sm p-2 flex items-center justify-center text-sm">
                            <i data-feather="edit" class="w-4 h-4 mr-1 inline-block"></i> Edit Detail Grup
                        </a>
                        <br>
                        <a href="<?php echo site_url('admin/delete_grup/' . $grup->id); ?>" class="btn bg-danger-500 text-white hover:bg-danger-600 btn-sm p-2 flex items-center justify-center text-sm" onclick="return confirm('Yakin hapus grup ini? SEMUA DATA CHECKLIST AKKAN HILANG!');">
                            <i data-feather="trash-2" class="w-4 h-4 mr-1 inline-block"></i> Hapus Grup
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-span-12 lg:col-span-3">
            <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6 h-full">
                <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4 bg-gray-100 dark:bg-gray-800">
                    <h6 class="mb-0 font-medium text-sm flex items-center"><i data-feather="user-check" class="w-4 h-4 mr-2 inline-block"></i> Tim Lapangan Ditugaskan</h6>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($penugasan)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($penugasan as $p): ?>
                                <li class="list-group-item d-flex justify-content-between items-center text-sm px-0 py-2 border-b border-gray-100 dark:border-gray-700">
                                    <strong class="text-primary-500"><?php echo $p->nama_peran; ?>:</strong>
                                    <span class="text-gray-700 dark:text-gray-300"><?php echo $p->nama_lengkap; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted text-sm italic">Tidak ada tim yang ditugaskan ke grup ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>        
        
        <div class="col-span-12 lg:col-span-6" id="live-checklist-container">
            <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
                <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4 bg-gray-100 dark:bg-gray-800">
                    <h5 class="mb-0 font-medium flex items-center"><i data-feather="activity" class="w-4 h-4 mr-2 inline-block"></i> Live Checklist Monitoring & Edit</h5>
                    <small class="text-muted text-sm block mt-1">Status eksekusi real-time dan opsi edit langsung item checklist.</small>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($grouped_items)): ?>
                        <div class="alert alert-warning text-center bg-yellow-100 text-yellow-800 p-4 border border-yellow-300">
                            <i data-feather="alert-circle" class="w-5 h-5 mr-2 inline-block"></i> Checklist live belum dicetak. Pastikan template memiliki item.
                        </div>
                    <?php else: ?>
                        <?php 
                        // Palet Warna Header Card Harian (Tetap berwarna untuk kontras)
                        $day_header_colors = [
                            'bg-sky-500 text-white',      // Sky Blue
                            'bg-emerald-500 text-white',  // Emerald Green
                            'bg-amber-500 text-white',    // Amber Yellow
                            'bg-indigo-500 text-white',   // Indigo
                            'bg-fuchsia-500 text-white',  // Fuchsia
                            'bg-rose-500 text-white'      // Rose Red
                        ];
                        $colors_count = count($day_header_colors);
                        $day_index = 0;
                        
                        // Garis batas BLACK untuk Card Harian dan Item Tugas
                        $black_border_class = 'border-2 border-black dark:border-gray-500'; 
                        
                        $status_class = [
                            'Pending' => 'secondary-500',
                            'Sukses' => 'success-500',
                            'Cukup' => 'info-500',
                            'Buruk' => 'warning-500',
                            'Gagal' => 'danger-500'
                        ];
                        ?>

                        <?php foreach ($grouped_items as $tgl => $items_per_day): ?>
                            
                            <?php 
                            // Tentukan warna header hari saat ini
                            $current_header_class = $day_header_colors[($day_index) % $colors_count]; 
                            $day_index++;
                            ?>

                            <div class="card <?= $black_border_class; ?> mb-4 shadow-xl">
                                <div class="card-header p-3 <?= $current_header_class; ?>">
                                    <h6 class="mb-0 text-sm font-bold flex items-center">
                                        <i data-feather="calendar" class="w-4 h-4 mr-2"></i> <?php echo date('d M Y', strtotime($tgl)); ?>
                                        <small class="opacity-80 ml-2 font-medium">(Hari ke-<?php echo $day_index; ?>)</small>
                                    </h6>
                                </div>
                                <div class="list-group list-group-flush p-3">
                                    <?php $item_index = 0; ?>
                                    <?php foreach ($items_per_day as $item): ?>
                                        <?php
                                            $error_edit = $this->session->flashdata('error_form_edit_' . $item->id);
                                            $current_status_class = $status_class[$item->status] ?? 'gray-500';
                                            
                                            // Tetapkan background putih/themedark agar border menonjol
                                            $bg_class = 'bg-white dark:bg-themedark-cardbg';
                                            
                                            // Terapkan border BLACK pada item tugas
                                            $item_border_class = $black_border_class;
                                            // Jika ada error, override border dengan border merah 4px
                                            if ($error_edit) {
                                                $item_border_class = 'border-4 border-red-500';
                                            }
                                        ?>
                                        
                                        <div class="js-item-card border p-4 mb-3 transition-all duration-300 <?= $item_border_class; ?> <?= $bg_class; ?>">
                                            
                                            <?php if ($error_edit): ?>
                                                <div class="alert alert-danger bg-red-100 text-red-800 p-2 mb-3 text-sm flex items-center">
                                                    <i data-feather="x-octagon" class="w-4 h-4 mr-2 flex-shrink-0"></i> <?= $error_edit; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?= form_open('admin/edit_grup_item_action/' . $grup->id . '/' . $item->id, 'id="editForm_' . $item->id . '" class="grid grid-cols-12 gap-x-4 gap-y-3 items-start"'); ?>

                                                <div class="col-span-12 flex flex-wrap items-center gap-2 mb-2 border-b border-gray-100 dark:border-gray-700 pb-2">
                                                    <span class="badge bg-<?php echo $current_status_class; ?> text-white mr-2 flex-shrink-0 px-2 py-1 text-xs font-semibold"><?php echo $item->status; ?></span>
                                                    <?php if ($item->tipe_item == 'checklist'): ?>
                                                        <span class="text-xs text-info-500 mr-2 flex-shrink-0 font-medium">[PJ Aktif: <?php echo $item->pj_nama ? $item->pj_nama : 'Belum Ditugaskan'; ?>]</span>
                                                    <?php else: ?>
                                                        <span class="text-xs text-muted mr-2 flex-shrink-0 italic">(! Info Item)</span>
                                                    <?php endif; ?>
                                                    <span class="text-xs text-primary-500 font-medium">Urutan: <?= $item->urutan; ?></span>
                                                </div>
                                                
                                                <div class="col-span-12 md:col-span-4 form-group mb-0">
                                                    <label for="deskripsi_item_<?= $item->id; ?>" class="text-sm font-medium block mb-1">Deskripsi Tugas</label>
                                                    <input type="text" id="deskripsi_item_<?= $item->id; ?>" name="deskripsi_item" class="form-control form-control-sm w-full border border-gray-300 dark:border-gray-600 p-2 text-sm dark:bg-gray-800" value="<?= $item->deskripsi; ?>" required>
                                                </div>

                                                <div class="col-span-6 md:col-span-2 form-group mb-0">
                                                    <label for="tipe_item_<?= $item->id; ?>" class="text-sm font-medium block mb-1">Tipe Item</label>
                                                    <select id="tipe_item_<?= $item->id; ?>" name="tipe_item" class="form-control form-control-sm js-tipe-item-edit w-full border border-gray-300 dark:border-gray-600 p-2 text-sm dark:bg-gray-800">
                                                        <option value="">-- Pilih Item --</option>
                                                        <option value="info" <?= ($item->tipe_item=='info')?'selected':''; ?>>📝 Info</option>
                                                        <option value="checklist" <?= ($item->tipe_item=='checklist')?'selected':''; ?>>✅ Checklist</option>
                                                    </select>
                                                </div>

                                                <div class="col-span-6 md:col-span-3 form-group mb-0 js-pj-peran-field">
                                                    <label for="pj_peran_id_<?= $item->id; ?>" class="text-sm font-medium block mb-1">Ganti PJ Peran</label>
                                                    <select id="pj_peran_id_<?= $item->id; ?>" name="pj_peran_id" class="form-control form-control-sm w-full border border-gray-300 dark:border-gray-600 p-2 text-sm dark:bg-gray-800">
                                                        <option value="">-- Pilih Peran --</option>
                                                        <?php foreach($peran as $p): ?>
                                                        <option value="<?= $p->id; ?>" <?= ($item->peran_tugas_id==$p->id)?'selected':''; ?>>
                                                            <?= $p->nama_peran; ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-span-6 md:col-span-3 form-group mb-0 js-pj-user-field">
                                                    <label for="pj_user_id_<?= $item->id; ?>" class="text-sm font-medium block mb-1">Ganti PJ User</label>
                                                    <select id="pj_user_id_<?= $item->id; ?>" name="pj_user_id" class="form-control form-control-sm w-full border border-gray-300 dark:border-gray-600 p-2 text-sm dark:bg-gray-800">
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
                                                <div class="col-span-12 flex flex-wrap justify-between items-center gap-2 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                                    
                                                    <div class="flex space-x-2">
                                                        <?php if ($item->foto_bukti): ?>
                                                            <a href="<?php echo base_url($item->foto_bukti); ?>" target="_blank" class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600 p-2 text-xs flex items-center">
                                                                <i data-feather="image" class="w-4 h-4 mr-1"></i> Bukti Foto
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($item->tipe_item == 'checklist'): ?>
                                                            <a href="<?php echo site_url('admin/item_history/' . $item->id); ?>" class="btn btn-sm bg-secondary-500 text-white hover:bg-secondary-600 p-2 text-xs flex items-center" >
                                                                <i data-feather="clock" class="w-4 h-4 mr-1"></i> Riwayat
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="flex space-x-2 ml-auto">
                                                        <button type="submit" class="btn btn-sm bg-info-500 text-white hover:bg-info-600 p-2 text-xs flex items-center font-medium" title="Simpan Perubahan">
                                                            <i data-feather="save" class="w-4 h-4 mr-1 inline-block"></i> Simpan
                                                        </button>
                                                        <a href="<?= site_url('admin/delete_grup_item/' . $grup->id . '/' . $item->id); ?>" 
                                                            class="btn btn-sm bg-danger-500 text-white hover:bg-danger-600 p-2 text-xs flex items-center font-medium" title="Hapus Item" onclick="return confirm('Hapus item live checklist ini?');">
                                                            <i data-feather="trash-2" class="w-4 h-4 mr-1 inline-block"></i>
                                                        </a>
                                                        <a href="<?= site_url('admin/reorder_grup_item/' . $grup->id . '/' . $item->id . '/up'); ?>" 
                                                            class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600 p-2 text-xs flex items-center" title="Pindah Naik">
                                                            <i data-feather="arrow-up" class="w-4 h-4"></i>
                                                        </a>
                                                        <a href="<?= site_url('admin/reorder_grup_item/' . $grup->id . '/' . $item->id . '/down'); ?>" 
                                                            class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600 p-2 text-xs flex items-center" title="Pindah Turun">
                                                            <i data-feather="arrow-down" class="w-4 h-4"></i>
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
    // Pastikan feather icons di-replace setelah konten dimuat
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    document.addEventListener('DOMContentLoaded', function() {

        // --- [LOGIC OPTIMAL UNTUK SEMUA FORM EDIT ITEM LIVE CHECKLIST] ---
        const liveChecklistContainer = document.getElementById('live-checklist-container');
        
        if (liveChecklistContainer) {
            
            // Fungsi terpusat untuk toggle field edit
            function toggleEditPjFields(selectElement) {
                // Temukan '.js-item-card' terdekat
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