<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Laporan /</span> Laporan Kinerja
    </h4>

    <div class="card">
        
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column">
                <h5 class="mb-0">
                    <i class="bx bx-bar-chart-alt-2 me-2"></i> Laporan Kinerja & Evaluasi
                </h5>
                <small class="text-muted">Data berdasarkan grup yang sudah Selesai/Diarsipkan.</small>
            </div>
        </div>
        
        <div class="card-body">
            
            <?php if (isset($reports) && !empty($reports)): ?>
                
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-start">NAMA GRUP</th>
                                <th class="text-center">TGL PULANG</th>
                                <th class="text-center">TOTAL ITEM</th>
                                <th class="text-center text-success">SUKSES</th>
                                <th class="text-center text-info">CUKUP</th>
                                <th class="text-center text-warning">BURUK</th>
                                <th class="text-center text-danger">GAGAL</th>
                                <th class="text-center">PERSEN SUKSES</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php foreach ($reports as $r): ?>
                            <?php 
                                $persen_sukses = ($r['total_item'] > 0) ? round(($r['sukses'] / $r['total_item']) * 100) : 0; 
                                $persen_class = ($persen_sukses < 90) ? 'text-danger' : 'text-success';
                            ?>
                            <tr>
                                <td class="fw-bold"><?php echo $r['nama_grup']; ?></td>
                                <td class="text-center"><?php echo date('d M Y', strtotime($r['tanggal_pulang'])); ?></td>
                                <td class="text-center"><?php echo $r['total_item']; ?></td>
                                <td class="text-center fw-bold text-success"><?php echo $r['sukses']; ?></td>
                                <td class="text-center fw-bold text-info"><?php echo $r['cukup']; ?></td>
                                <td class="text-center fw-bold text-warning"><?php echo $r['buruk']; ?></td>
                                <td class="text-center fw-bold text-danger"><?php echo $r['gagal']; ?></td>
                                <td class="text-center fw-bold <?php echo $persen_class; ?>">
                                    <?php echo $persen_sukses; ?>%
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo site_url('admin/grup_detail/' . $r['grup_id']); ?>" class="btn btn-sm btn-info" title="Lihat Detail Checklist">
                                        <i class="bx bx-show me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    Belum ada data grup yang berstatus Selesai atau Diarsipkan untuk ditampilkan.
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>