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
    
    <div class="grid grid-cols-12 gap-x-6">
        
        <div class="col-span-12 lg:col-span-3">
            <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
                <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4 bg-primary-500 text-white">
                    <h6 class="mb-0 font-medium"><i data-feather="info" class="w-4 h-4 mr-2 inline-block"></i> Ringkasan Grup</h6>
                </div>
                <div class="card-body p-4">
                    <p class="text-sm mb-1"><strong>Nama Grup:</strong> <?php echo $grup->nama_grup; ?></p>
                    <p class="text-sm mb-1"><strong>Template Asal:</strong> ID <?php echo $grup->template_asal_id; ?></p>
                    <p class="text-sm mb-1"><strong>Keberangkatan:</strong> <?php echo date('d M Y', strtotime($grup->tanggal_keberangkatan)); ?></p>
                    <p class="text-sm mb-3"><strong>Kepulangan:</strong> <?php echo date('d M Y', strtotime($grup->tanggal_pulang)); ?></p>
                    
                    <p class="text-sm mb-3"><strong>Status Grup:</strong> 
                        <span class="badge bg-success-500 text-white"><?php echo $grup->status_grup; ?></span>
                    </p>
                    
                    <hr class="my-4 border-t border-gray-200 dark:border-gray-700">
                    
                    <h6 class="font-semibold text-sm mb-3 text-warning-500">Aksi Admin</h6>
                    <div class="flex flex-col space-y-2">
                        <a href="<?php echo site_url('admin/delete_grup/' . $grup->id); ?>" class="btn bg-danger-500 text-white hover:bg-danger-600 btn-sm" onclick="return confirm('Yakin hapus grup ini? SEMUA DATA CHECKLIST AKAN HILANG!');">
                            <i data-feather="trash-2" class="w-4 h-4 mr-1 inline-block"></i> Hapus Grup
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-span-12 lg:col-span-3">
            <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
                <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4 bg-gray-100 dark:bg-gray-800">
                    <h6 class="mb-0 font-medium text-sm"><i data-feather="user-check" class="w-4 h-4 mr-2 inline-block"></i> Tim Lapangan Ditugaskan</h6>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($penugasan)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($penugasan as $p): ?>
                                <li class="list-group-item d-flex justify-content-between items-center text-sm px-0 py-2 border-b">
                                    <strong class="text-primary-500"><?php echo $p->nama_peran; ?>:</strong>
                                    <span><?php echo $p->nama_lengkap; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted text-sm">Tidak ada tim yang ditugaskan ke grup ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>        
        
        <div class="col-span-12 lg:col-span-9" id="live-checklist-container">
            <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
                <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4 bg-gray-100 dark:bg-gray-800">
                    <h5 class="mb-0 font-medium"><i data-feather="activity" class="w-4 h-4 mr-2 inline-block"></i> Live Checklist Monitoring & Edit</h5>
                    <small class="text-muted">Status eksekusi real-time dan opsi edit langsung item checklist.</small>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($grouped_items)): ?>
                        <div class="alert alert-warning text-center bg-yellow-100 text-yellow-800 p-4 rounded">
                            Checklist live belum dicetak. Pastikan template memiliki item.
                        </div>
                    <?php else: ?>
                        <?php 
                        // [MODIFIKASI] Definisikan palet warna di sini (seperti di template/detail.php)
                        $day_colors = [
                            'bg-sky-100 dark:bg-sky-800', 
                            'bg-emerald-100 dark:bg-emerald-800', 
                            'bg-amber-100 dark:bg-amber-800', 
                            'bg-indigo-100 dark:bg-indigo-800',
                            'bg-fuchsia-100 dark:bg-fuchsia-800',
                            'bg-rose-100 dark:bg-rose-800'
                        ];
                        $colors_count = count($day_colors);
                        $day_index = 0;
                        ?>

                        <?php foreach ($grouped_items as $tgl => $items_per_day): ?>
                            
                            <?php 
                            // Hitung warna berdasarkan indeks hari
                            $current_color = $day_colors[($day_index) % $colors_count]; 
                            $day_index++;
                            ?>

                            <div class="card border-primary mb-4 shadow-sm">
                                <div class="card-header p-3 <?= $current_color; ?>">
                                    <h6 class="mb-0 text-sm font-semibold text-black/80 dark:text-white/90">
                                        🗓️ <?php echo date('d M Y', strtotime($tgl)); ?>
                                        <small class="text-black/60 dark:text-white/60 ml-2">(Hari ke-<?php echo $day_index; ?>)</small>
                                    </h6>
                                </div>
                                <ul class="list-group list-group-flush p-3">
                                    <?php foreach ($items_per_day as $item): ?>
                                        <?php
                                            $status_class = [
                                                'Pending' => 'secondary-500',
                                                'Sukses' => 'success-500',
                                                'Cukup' => 'info-500',
                                                'Buruk' => 'warning-500',
                                                'Gagal' => 'danger-500'
                                            ];
                                            $status_text_color = $item->status == 'Pending' ? 'text-muted' : 'font-bold';
                                            
                                            $form_id = 'editForm_' . $item->id;
                                            $error_edit = $this->session->flashdata('error_form_edit_' . $item->id);
                                        ?>
                                        
                                        <li class="list-group-item js-item-card shadow-sm rounded-lg border border-theme-border dark:border-themedark-border bg-white dark:bg-themedark-cardbg p-4 mb-3 <?php echo $error_edit ? 'bg-red-50 border-red-500' : ''; ?>">
                                            
                                            <?php if ($error_edit): ?>
                                                <div class="alert alert-danger bg-red-100 text-red-800 p-2 rounded mb-3 text-sm">
                                                    <?= $error_edit; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?= form_open('admin/edit_grup_item_action/' . $grup->id . '/' . $item->id, 'id="' . $form_id . '" class="grid grid-cols-12 gap-x-4 gap-y-3 items-start"'); ?>

                                                <div class="col-span-12 flex flex-wrap items-center gap-2 mb-2 border-b pb-2">
                                                    <span class="badge bg-<?php echo $status_class[$item->status]; ?> text-white mr-2 flex-shrink-0"><?php echo $item->status; ?></span>
                                                    <?php if ($item->tipe_item == 'checklist'): ?>
                                                        <span class="text-xs text-info-500 mr-2 flex-shrink-0">[PJ Aktif: <?php echo $item->pj_nama ? $item->pj_nama : 'Belum Ditugaskan'; ?>]</span>
                                                    <?php else: ?>
                                                        <span class="text-xs text-muted mr-2 flex-shrink-0">(! Info)</span>
                                                    <?php endif; ?>
                                                    <span class="text-xs text-primary-500">Urutan: <?= $item->urutan; ?></span>
                                                </div>
                                                
                                                <div class="col-span-12 md:col-span-4 form-group mb-0">
                                                    <label class="text-sm font-medium block mb-1">Deskripsi Tugas</label>
                                                    <input type="text" name="deskripsi_item" class="form-control form-control-sm" value="<?= $item->deskripsi; ?>" required>
                                                </div>

                                                <div class="col-span-6 md:col-span-2 form-group mb-0">
                                                    <label class="text-sm font-medium block mb-1">Tipe Item</label>
                                                    <select name="tipe_item" class="form-control form-control-sm js-tipe-item-edit">
                                                        <option value="">-- Pilih Item --</option>
                                                        <option value="info" <?= ($item->tipe_item=='info')?'selected':''; ?>>📝 Info</option>
                                                        <option value="checklist" <?= ($item->tipe_item=='checklist')?'selected':''; ?>>✅ Checklist</option>
                                                    </select>
                                                </div>

                                                <div class="col-span-6 md:col-span-3 form-group mb-0 js-pj-peran-field">
                                                    <label class="text-sm font-medium block mb-1">Ganti PJ Peran</label>
                                                    <select name="pj_peran_id" class="form-control form-control-sm">
                                                        <option value="">-- Pilih Peran --</option>
                                                        <?php foreach($peran as $p): ?>
                                                        <option value="<?= $p->id; ?>" <?= ($item->peran_tugas_id==$p->id)?'selected':''; ?>>
                                                            <?= $p->nama_peran; ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-span-6 md:col-span-3 form-group mb-0 js-pj-user-field">
                                                    <label class="text-sm font-medium block mb-1">Ganti PJ User</label>
                                                    <select name="pj_user_id" class="form-control form-control-sm">
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
                                                <div class="col-span-12 flex flex-wrap justify-between items-center gap-2 mt-2 pt-2 border-t">
                                                    
                                                    <div class="flex space-x-2">
                                                        <?php if ($item->foto_bukti): ?>
                                                            <a href="<?php echo base_url($item->foto_bukti); ?>" target="_blank" class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600">
                                                                Bukti Foto
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($item->tipe_item == 'checklist'): ?>
                                                            <a href="<?php echo site_url('admin/item_history/' . $item->id); ?>" class="btn btn-sm bg-secondary-500 text-white hover:bg-secondary-600" >
                                                                Riwayat
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="flex space-x-2 ml-auto">
                                                        <button type="submit" class="btn btn-sm bg-info-500 text-white hover:bg-info-600" title="Simpan Perubahan">
                                                            <i data-feather="save" class="w-4 h-4 mr-1 inline-block"></i> Simpan Edit
                                                        </button>
                                                        <a href="<?= site_url('admin/delete_grup_item/' . $grup->id . '/' . $item->id); ?>" 
                                                            class="btn btn-sm bg-danger-500 text-white hover:bg-danger-600" title="Hapus Item" onclick="return confirm('Hapus item live checklist ini?');">
                                                            <i data-feather="trash-2" class="w-4 h-4 mr-1 inline-block"></i> Hapus
                                                        </a>
                                                        <a href="<?= site_url('admin/reorder_grup_item/' . $grup->id . '/' . $item->id . '/up'); ?>" 
                                                            class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600" title="Pindah Naik">
                                                            <i data-feather="arrow-up" class="w-4 h-4"></i>
                                                        </a>
                                                        <a href="<?= site_url('admin/reorder_grup_item/' . $grup->id . '/' . $item->id . '/down'); ?>" 
                                                            class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600" title="Pindah Turun">
                                                            <i data-feather="arrow-down" class="w-4 h-4"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <?= form_close(); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
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
                    pjPeranField.style.display = 'block';
                    pjUserField.style.display = 'block';
                    pjPeranSelect.setAttribute('required', 'required');
                } else {
                    pjPeranField.style.display = 'none';
                    pjUserField.style.display = 'none';
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