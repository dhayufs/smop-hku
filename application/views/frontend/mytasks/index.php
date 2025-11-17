<h4 class="mb-4 font-bold text-gray-800 dark:text-gray-100"><?php echo $title; ?></h4>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4"><i class="fa fa-check-circle mr-2"></i> <?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i class="fa fa-times-circle mr-2"></i> <?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<h5 class="mb-3 font-bold text-gray-800 dark:text-gray-100">Daftar Grup yang Ditugaskan kepada Saya</h5>

<?php if (empty($assigned_groups)): ?>
    <div class="card text-center py-5 shadow-lg">
        <div class="card-body">
            <h5 class="card-title text-muted">🎉 Belum ada grup yang ditugaskan kepada Anda.</h5>
            <p>Silakan hubungi Admin untuk penugasan.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php $user_id = $this->session->userdata('user_id'); ?>
        <?php foreach ($assigned_groups as $grup): ?>
            <?php 
                $pic_details = [];
                $my_roles = [];
                
                foreach ($grup->penugasan as $p) {
                    $pic_details[] = [
                        'nama_peran' => $p->nama_peran,
                        'nama_lengkap' => $p->nama_lengkap,
                        'is_my_role' => ($p->user_id == $user_id)
                    ];
                    if ($p->user_id == $user_id) {
                        $my_roles[] = $p->nama_peran;
                    }
                }
                
                $header_color_class = ($grup->status_grup == 'Aktif') ? 'card-gradient-primary' : 'bg-c-yellow';
            ?>
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="card shadow-lg p-0 border-0 rounded">
                    <div class="card-header <?php echo $header_color_class; ?> text-white p-3 rounded-top d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fa fa-plane mr-2"></i> <?php echo $grup->nama_grup; ?></h6>
                        
                        <a href="<?php echo site_url('mytasks/grup_detail/' . $grup->grup_id); ?>" class="btn btn-sm btn-light py-1 px-2" title="Detail Tugas Harian">
                            <i class="fa fa-eye"></i>
                        </a>
                    </div>
                    
                    <div class="card-body p-0">
                        
                        <ul class="list-group list-group-flush border-b <?php echo $header_color_class; ?> text-white rounded-0">
                            <li class="list-group-item d-flex justify-content-between text-sm py-2 bg-transparent text-white border-0">
                                <small>Tgl. Program:</small> 
                                <small class="font-weight-bold"><?php echo date('d M Y', strtotime($grup->tanggal_keberangkatan)); ?> s/d <?php echo date('d M Y', strtotime($grup->tanggal_pulang)); ?></small>
                            </li>
                            <li class="list-group-item d-flex justify-content-between text-sm py-2 bg-transparent text-white border-0">
                                <small>Status Grup:</small> 
                                <small class="font-weight-bold"><?php echo $grup->status_grup; ?></small>
                            </li>
                            <li class="list-group-item d-flex justify-content-between text-sm py-2 bg-transparent text-white font-weight-bold border-0 border-top pt-3">
                                <small>Peran Anda:</small> 
                                <small><?php echo !empty($my_roles) ? implode(', ', $my_roles) : 'Tidak Terdaftar'; ?></small>
                            </li>
                        </ul>
                        
                        <div class="p-3">
                            <div class="list-group list-group-flush">
                                <?php 
                                    // Menggunakan array nama peran yang diambil dari Controller untuk menentukan urutan
                                    $all_peran_names = $all_peran_names ?? [];
                                    $displayed_peran_names = []; // Untuk melacak peran yang sudah ditampilkan
                                    
                                    // Loop SEMUA nama peran yang ada di database untuk menjaga urutan (walaupun grup ini tidak memiliki peran tersebut)
                                    foreach ($all_peran_names as $peran_name):
                                        // Cari detail peran ini di array penugasan grup
                                        $detail = array_filter($pic_details, function($p) use ($peran_name) {
                                            return $p['nama_peran'] === $peran_name;
                                        });

                                        if (!empty($detail)):
                                            $detail = reset($detail); // Ambil elemen pertama
                                            $pic_class = $detail['is_my_role'] ? 'font-weight-bold text-warning' : 'text-gray-700';
                                            $pic_icon = $detail['is_my_role'] ? ' <i class="fa fa-star text-warning"></i>' : '';
                                ?>
                                        <div class="d-flex justify-content-between text-sm py-1">
                                            <small class="text-sm font-weight-medium" style="width: 40%;"><?php echo $detail['nama_peran']; ?>:</small>
                                            <small class="text-sm <?php echo $pic_class; ?>"><?php echo $detail['nama_lengkap'] . $pic_icon; ?></small>
                                        </div>
                                <?php 
                                            $displayed_peran_names[] = $peran_name;
                                        endif;
                                    endforeach; 
                                    
                                    // Tampilkan peran yang tidak ada penugasan jika diperlukan (saat ini diabaikan)
                                    // Anda bisa menambahkan logika di sini jika ingin menampilkan peran yang tidak ditugaskan (N/A)
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>