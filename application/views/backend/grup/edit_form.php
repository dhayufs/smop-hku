<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
            <h5 class="mb-0 font-medium">
                <i data-feather="repeat" class="w-4 h-4 mr-2 inline-block"></i> <?php echo $title; ?>
            </h5>
            <a href="<?php echo site_url('admin/grup_detail/' . $grup_data->id); ?>" class="btn bg-secondary-500 text-white hover:bg-secondary-600">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline-block"></i> Kembali ke Monitoring
            </a>
        </div>
        
        <div class="card-body p-6 grid grid-cols-12 gap-x-6 justify-center">
            
            <div class="col-span-12 lg:col-span-8">
                
                <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4">
                    <strong>PERINGATAN!</strong> Mengubah dan menyimpan tanggal di bawah ini akan menggeser semua tanggal Manasik dan Perjalanan pada item checklist grup ini secara otomatis (Alur C.4).
                </div>
                
                <?php if ($this->session->flashdata('error') || $this->session->flashdata('error_form')): ?>
                    <div class="alert alert-danger bg-red-100 text-red-800 p-3 rounded mb-4"><i data-feather="alert-triangle" class="w-4 h-4 mr-2 inline-block"></i> 
                        <?php echo $this->session->flashdata('error') . $this->session->flashdata('error_form'); ?>
                    </div>
                <?php endif; ?>
                
                <?php 
                    // Action URL untuk ALUR C.4: edit_grup_action
                    echo form_open('admin/edit_grup_action/' . $grup_data->id, 'id="editGrupForm"'); 
                ?>
                
                    <div class="card bg-gray-50 dark:bg-gray-800 shadow-none border mb-5">
                        <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4">
                            <h6 class="font-bold text-primary-500"><i data-feather="info" class="w-4 h-4 mr-2 inline-block"></i> Data Grup Saat Ini</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-group mb-3">
                                <label class="text-sm font-medium">Nama Grup</label>
                                <input type="text" class="form-control" value="<?php echo $grup_data->nama_grup; ?>" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-sm font-medium">Tanggal Keberangkatan LAMA</label>
                                <input type="date" class="form-control" value="<?php echo $grup_data->tanggal_keberangkatan; ?>" disabled>
                            </div>
                            <div class="form-group mb-0">
                                <label class="text-sm font-medium">Tim Penanggung Jawab</label>
                                <ul class="list-disc pl-5 text-sm mt-1">
                                    <?php if (!empty($penugasan)): ?>
                                        <?php foreach ($penugasan as $p): ?>
                                            <li><?php echo $p->nama_peran; ?> ditugaskan ke <?php echo $p->nama_lengkap; ?></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li>Tidak ada tim yang ditugaskan.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-gray-50 dark:bg-gray-800 shadow-none border mb-5">
                        <div class="card-header border-b border-gray-200 dark:border-gray-700 p-4">
                            <h6 class="font-bold text-warning-500"><i data-feather="calendar" class="w-4 h-4 mr-2 inline-block"></i> Tanggal Keberangkatan BARU</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-group mb-4">
                                <label class="text-sm font-medium">Tanggal Keberangkatan BARU</label>
                                <input type="date" name="tanggal_keberangkatan" class="form-control" 
                                    value="<?php echo set_value('tanggal_keberangkatan', $grup_data->tanggal_keberangkatan); ?>" required>
                                <?php echo form_error('tanggal_keberangkatan', '<small class="text-danger block">', '</small>'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-center mt-4">
                        <button type="submit" class="btn btn-lg bg-warning-500 text-white hover:bg-warning-600 shadow-2xl">
                            <i data-feather="repeat" class="w-5 h-5 mr-2 inline-block"></i> Geser Semua Jadwal
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>
            
        </div>
    </div>
</div>

<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>