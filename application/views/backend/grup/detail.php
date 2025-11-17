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
                        <a href="<?php echo site_url('admin/grup_form/' . $grup->id); ?>" class="btn bg-warning-500 text-white hover:bg-warning-600 btn-sm">
                            <i data-feather="repeat" class="w-4 h-4 mr-1 inline-block"></i> Geser Jadwal
                        </a><br>
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
        
        <div class="col-span-12 lg:col-span-9">
            <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
                <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4 bg-gray-100 dark:bg-gray-800">
                    <h5 class="mb-0 font-medium"><i data-feather="activity" class="w-4 h-4 mr-2 inline-block"></i> Live Checklist Monitoring</h5>
                    <small class="text-muted">Status eksekusi real-time dari Tim Lapangan.</small>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($grouped_items)): ?>
                        <div class="alert alert-warning text-center bg-yellow-100 text-yellow-800 p-4 rounded">
                            Checklist live belum dicetak. Pastikan template memiliki item.
                        </div>
                    <?php else: ?>
                        <?php foreach ($grouped_items as $tgl => $items_per_day): ?>
                            <div class="card border-primary mb-4 shadow-sm">
                                <div class="card-header bg-primary-500 text-white p-3">
                                    🗓️ <?php echo date('d M Y', strtotime($tgl)); ?>
                                </div>
                                <ul class="list-group list-group-flush">
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
                                        ?>
                                        <li class="list-group-item text-sm px-3 py-2 border-b">
                                            <div class="flex flex-wrap items-center justify-between">
                                                <div class="flex items-center flex-grow min-w-0 mb-1 sm:mb-0">
                                                    <span class="badge bg-<?php echo $status_class[$item->status]; ?> text-white mr-2 flex-shrink-0"><?php echo $item->status; ?></span>
                                                    
                                                    <?php if ($item->tipe_item == 'checklist'): ?>
                                                        <a class="text-xs text-info-500 mr-2 flex-shrink-0">[PJ: <?php echo $item->pj_nama ? $item->pj_nama : 'Belum Ditugaskan'; ?>]</a>
                                                    <?php else: ?>
                                                        <a class="text-xs text-muted mr-2 flex-shrink-0">(! Info)</a>
                                                    <?php endif; ?>
                                                    
                                                    <span class="<?php echo $status_text_color; ?> truncate w-full sm:w-auto"><?php echo $item->deskripsi; ?></p>
                                                </div>
                                                
                                                <div class="flex-shrink-0 ml-auto flex items-center space-x-2">
                                                    <?php if ($item->foto_bukti): ?>
                                                        <a href="<?php echo base_url($item->foto_bukti); ?>" target="_blank" class="btn btn-sm bg-primary-500 text-white hover:bg-primary-600">
                                                            <i data-feather="image" class="w-1 h-1 mr-1"></i>📷 Bukti Foto
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($item->tipe_item == 'checklist'): ?>
                                                        <a href="<?php echo site_url('admin/item_history/' . $item->id); ?>" class="btn btn-sm bg-secondary-500 text-white hover:bg-secondary-600" 
                                                            <i data-feather="clock" class="w-1 h-1 mr-1"></i>🕗 Riwayat
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
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
</script>