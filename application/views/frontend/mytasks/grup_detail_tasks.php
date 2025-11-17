<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user_id = $this->session->userdata('user_id');
// Ambil tanggal target dari controller
$tanggal_target = $tanggal_target ?? NULL; 
?>

<h4 class="mb-4 font-bold text-gray-800 dark:text-gray-100">Detail Tugas Grup: <?php echo $grup->nama_grup; ?></h4>
<p class="mb-4">
    <a href="<?php echo site_url('mytasks'); ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Grup</a>
</p>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4"><i class="fa fa-check-circle mr-2"></i> <?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i class="fa fa-times-circle mr-2"></i> <?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="card bg-white shadow-lg mb-4 p-4 border-l-4 border-primary">
    <div class="row">
        <div class="col-md-6">
            <strong><i class="fa fa-tag mr-2"></i> Nama Program:</strong> <?php echo $grup->nama_grup; ?><br>
            <strong><i class="fa fa-calendar-alt mr-2"></i> Tanggal Program:</strong> <?php echo format_indo_date($grup->tanggal_keberangkatan) . ' s/d ' . format_indo_date($grup->tanggal_pulang); ?>
        </div>
        <div class="col-md-6">
            <?php foreach ($penugasan as $p): ?>
                <?php
                    $is_pic_class = ($p->user_id == $user_id) ? 'text-warning' : 'text-muted';
                    $is_pic_icon = ($p->user_id == $user_id) ? '<i class="fa fa-star text-warning"></i>' : '';
                ?>
                <p class="mb-0 text-sm">
                    <strong><?php echo $p->nama_peran; ?>:</strong> 
                    <span class="<?php echo $is_pic_class; ?>"><?php echo $p->nama_lengkap; ?> <?php echo $is_pic_icon; ?></span>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="accordion" id="accordionGrupDetail">
    <?php if (empty($grouped_items)): ?>
        <div class="card text-center py-5 shadow-lg">
            <div class="card-body">
                <h5 class="card-title text-muted">Belum ada item tugas yang dicetak untuk grup ini.</h5>
            </div>
        </div>
    <?php else: ?>

        <?php $i = 0; foreach ($grouped_items as $tgl => $items_per_date): $i++; ?>
            <?php
                // Tentukan apakah sesi ini harus dibuka (show)
                $is_show = ($tanggal_target === $tgl);
                // Jika tidak ada target, buka sesi pertama saja
                if ($tanggal_target === NULL && $i === 1) {
                    $is_show = TRUE;
                }
            ?>
            <div class="card mb-4 shadow-lg border-0">
                <div class="card-header bg-light p-3 border-b border-gray-200 dark:border-gray-700" id="heading<?php echo $grup->id . $i; ?>">
                    <h2 class="mb-0">
                        <button class="btn btn-block text-left py-1 px-2 d-flex justify-content-between align-items-center font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse<?php echo $grup->id . $i; ?>" aria-expanded="<?php echo $is_show ? 'true' : 'false'; ?>">
                            Tugas Tanggal: <?php echo format_indo_date($tgl); ?>
                            <i class="fa fa-chevron-down"></i>
                        </button>
                    </h2>
                </div>

                <div id="collapse<?php echo $grup->id . $i; ?>" class="collapse <?php echo $is_show ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $grup->id . $i; ?>" data-parent="#accordionGrupDetail">
                    <div class="card-body p-0">
                        
                        <?php foreach ($items_per_date as $blok => $items): ?>
                        
                        <h6 class="mt-3 mb-0 text-sm font-weight-bold bg-gray-100 p-2 text-primary">Sesi <?php echo ucfirst($blok); ?></h6>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-striped w-full mb-0">
                                <thead class="bg-white text-black border-b border-gray-400">
                                    <tr>
                                        <th class="py-2 px-3 text-center" style="width: 5%">Kategori</th>
                                        <th class="py-2 px-3">Tugas</th>
                                        <th class="py-2 px-3 text-center" style="width: 15%">PIC</th>
                                        <th class="py-2 px-3 text-center" style="width: 10%">Status</th>
                                        <th class="py-2 px-3 text-center" style="width: 15%">Aksi / Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <?php
                                            $status_class = ['Pending' => 'secondary', 'Sukses' => 'success', 'Cukup' => 'info', 'Buruk' => 'warning', 'Gagal' => 'danger'];
                                            $status_label = ['Pending' => 'Pending', 'Sukses' => 'Sukses', 'Cukup' => 'Cukup', 'Buruk' => 'Buruk', 'Gagal' => 'Gagal'];
                                            $is_my_task = ($item->pj_user_id == $user_id);
                                            $is_checklist = ($item->tipe_item == 'checklist');
                                            
                                            // Tentukan PIC Label
                                            $pic_label = $item->pj_nama ? $item->pj_nama : 'N/A';
                                            $pic_star = ($item->pj_user_id == $user_id) ? ' <i class="fa fa-star text-warning"></i>' : '';
                                            
                                            // Mengganti **teks** menjadi <strong>teks</strong> pada deskripsi
                                            $deskripsi_bold = str_replace(['**', '__'], ['<strong>', '</strong>'], $item->deskripsi);

                                            // Tentukan Icon Kategori
                                            $kategori_icon = ($item->tipe_item == 'checklist') ? '☑️' : '💡';
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $kategori_icon; ?></td>
                                            <td>
                                                <?php echo $deskripsi_bold; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($is_checklist): ?>
                                                    <?php echo $pic_label . $pic_star; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($is_checklist): ?>
                                                    <span class="badge badge-<?php echo $status_class[$item->status]; ?>">
                                                        <?php echo $status_label[$item->status]; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($is_checklist): ?>
                                                    <?php if ($is_my_task): ?>
                                                        <a href="<?php echo site_url('mytasks/update_task_form/' . $item->id); ?>" class="btn btn-sm btn-primary py-1 px-2"><i class="fa fa-edit"></i> Aksi</a>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-secondary py-1 px-2 disabled" title="Hanya PIC yang terdaftar yang bisa edit">
                                                            <i class="fa fa-lock"></i> Hanya PIC
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($item->foto_bukti): ?>
                                                        <button type="button" class="btn btn-sm btn-success py-1 px-2 mt-1 mt-md-0"
                                                                data-toggle="modal" 
                                                                data-target="#buktiModal"
                                                                data-foto-url="<?php echo base_url($item->foto_bukti); ?>">
                                                            <i class="fa fa-camera"></i> Bukti
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="modal fade" id="buktiModal" tabindex="-1" role="dialog" aria-labelledby="buktiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header card-gradient-primary text-white border-0">
        <h5 class="modal-title" id="buktiModalLabel">Foto Bukti Tugas</h5>
        <button type="button" class="close text-white opacity-80" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img id="modalFotoBukti" src="" class="img-fluid rounded shadow-sm" alt="Foto Bukti" style="max-height: 80vh;">
      </div>
    </div>
  </div>
</div>

<script>
    // Hanya untuk memastikan skrip bootstrap collapse bekerja
</script>