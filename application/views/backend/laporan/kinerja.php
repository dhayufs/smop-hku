<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800">
            <h5 class="mb-0 font-medium">
                <i data-feather="bar-chart-2" class="w-4 h-4 mr-2 inline-block"></i> Laporan Kinerja & Evaluasi
            </h5>
            <small class="text-muted">Data berdasarkan grup yang sudah Selesai/Diarsipkan.</small>
        </div>
        
        <div class="card-body p-6">
            
            <?php if (isset($reports) && !empty($reports)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-sm font-semibold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700">
                                <th class="py-2 px-3 text-left">NAMA GRUP</th>
                                <th class="py-2 px-3 text-center">TGL PULANG</th>
                                <th class="py-2 px-3 text-center">TOTAL ITEM</th>
                                <th class="py-2 px-3 text-center text-success-500">SUKSES</th>
                                <th class="py-2 px-3 text-center text-info-500">CUKUP</th>
                                <th class="py-2 px-3 text-center text-warning-500">BURUK</th>
                                <th class="py-2 px-3 text-center text-danger-500">GAGAL</th>
                                <th class="py-2 px-3 text-center">PERSEN SUKSES</th>
                                <th class="py-2 px-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $r): ?>
                            <?php 
                                $persen_sukses = round(($r['sukses'] / $r['total_item']) * 100); 
                                $persen_class = ($persen_sukses < 90) ? 'text-danger-500' : 'text-success-500';
                            ?>
                            <tr class="text-sm border-t border-theme-border dark:border-themedark-border">
                                <td class="py-3 px-3 font-medium text-gray-800 dark:text-gray-200"><?php echo $r['nama_grup']; ?></td>
                                <td class="py-3 px-3 text-center"><?php echo date('d M Y', strtotime($r['tanggal_pulang'])); ?></td>
                                <td class="py-3 px-3 text-center"><?php echo $r['total_item']; ?></td>
                                <td class="py-3 px-3 text-center font-bold text-success-500"><?php echo $r['sukses']; ?></td>
                                <td class="py-3 px-3 text-center font-bold text-info-500"><?php echo $r['cukup']; ?></td>
                                <td class="py-3 px-3 text-center font-bold text-warning-500"><?php echo $r['buruk']; ?></td>
                                <td class="py-3 px-3 text-center font-bold text-danger-500"><?php echo $r['gagal']; ?></td>
                                <td class="py-3 px-3 text-center font-bold <?php echo $persen_class; ?>">
                                    <?php echo $persen_sukses; ?>%
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <a href="<?php echo site_url('admin/grup_detail/' . $r['grup_id']); ?>" class="btn btn-sm bg-info-500 text-white hover:bg-info-600" title="Lihat Detail Checklist">
                                        <i data-feather="eye" class="w-4 h-4"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center bg-blue-100 text-blue-800 p-4 rounded">
                    Belum ada data grup yang berstatus Selesai atau Diarsipkan untuk ditampilkan.
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<script>
    // Pastikan feather icons di-replace setelah konten dimuat
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>