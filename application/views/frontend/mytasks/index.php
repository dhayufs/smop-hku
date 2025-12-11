<h4 class="fw-bold py-3 mb-4">List Tugas</h4>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success d-flex align-items-center" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bx bx-error me-2"></i>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Grup yang Ditugaskan kepada Saya</h5>
    </div>
    <div class="card-body">
        <?php if (empty($assigned_groups)): ?>
            <div class="text-center p-5">
                <h5 class="card-title text-muted">🎉 Belum ada grup yang ditugaskan kepada Anda.</h5>
                <p>Silakan hubungi Admin untuk penugasan.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php $user_id = $this->session->userdata('user_id'); ?>
                <?php foreach ($assigned_groups as $grup): ?>
                    <?php 
                        $pic_map = []; // Map: Peran Nama -> User Nama
                        $my_roles = [];
                        
                        foreach ($grup->penugasan as $p) {
                            $pic_map[$p->nama_peran] = [
                                'nama_lengkap' => $p->nama_lengkap,
                                'is_my_role' => ($p->user_id == $user_id)
                            ];
                            if ($p->user_id == $user_id) {
                                $my_roles[] = $p->nama_peran;
                            }
                        }
                        
                        // Menggunakan class warna standar Bootstrap/Sneat
                        $header_bg_class = ($grup->status_grup == 'Aktif') ? 'bg-primary' : 'bg-warning';
                        $border_color_class = ($grup->status_grup == 'Aktif') ? 'border-primary' : 'border-warning';
                        $status_text_color = ($grup->status_grup == 'Aktif') ? 'text-primary' : 'text-warning';
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header <?php echo $header_bg_class; ?> text-white p-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white"><i class="bx bx-trip me-2"></i> <?php echo $grup->nama_grup; ?></h5>
                                
                                <a href="<?php echo site_url('mytasks/grup_detail/' . $grup->grup_id); ?>" class="btn btn-sm btn-light py-1 px-2 text-primary" title="Detail Tugas Harian">
                                    <i class="bx bx-show-alt"></i> Detail
                                </a>
                            </div>
                            
                            <div class="card-body p-0">
                                
                                <ul class="list-group list-group-flush border-bottom <?php echo $border_color_class; ?>">
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <small class="text-muted">Tgl. Program:</small> 
                                        <small class="fw-semibold"><?php echo date('d M Y', strtotime($grup->tanggal_keberangkatan)); ?> s/d <?php echo date('d M Y', strtotime($grup->tanggal_pulang)); ?></small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <small class="text-muted">Status Grup:</small> 
                                        <small class="fw-bold <?php echo $status_text_color; ?>"><?php echo $grup->status_grup; ?></small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-label-secondary">
                                        <small class="fw-bold">Peran Anda:</small> 
                                        <small class="fw-bold text-success"><?php echo !empty($my_roles) ? implode(', ', $my_roles) : 'Tidak Terdaftar'; ?></small>
                                    </li>
                                </ul>
                                
                                <div class="p-3">
                                    <h6 class="border-bottom pb-2 mb-2 text-muted">Penanggung Jawab (PIC)</h6>
                                    <div class="list-group list-group-flush mt-2">
                                        <?php 
                                            // Menggunakan array nama peran yang diambil dari Controller untuk menentukan urutan
                                            $all_peran_names = $all_peran_names ?? [];
                                            
                                            // Loop SEMUA nama peran yang ada di database untuk menjaga urutan
                                            foreach ($all_peran_names as $peran_name):
                                                $detail = $pic_map[$peran_name] ?? null;
                                        ?>
                                                <div class="d-flex justify-content-between py-1">
                                                    <small class="text-muted" style="width: 40%;"><?php echo $peran_name; ?>:</small>
                                                    <?php if ($detail): ?>
                                                        <?php $pic_class = $detail['is_my_role'] ? 'text-primary fw-bold' : 'text-body'; ?>
                                                        <?php $pic_icon = $detail['is_my_role'] ? ' <i class="bx bx-star text-warning ms-1"></i>' : ''; ?>
                                                        <small class="<?php echo $pic_class; ?>"><?php echo $detail['nama_lengkap'] . $pic_icon; ?></small>
                                                    <?php else: ?>
                                                         <small class="text-danger fst-italic">N/A</small>
                                                    <?php endif; ?>
                                                </div>
                                        <?php 
                                            endforeach; 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>