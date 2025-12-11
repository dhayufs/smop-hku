<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-12">
    <div class="card shadow mb-4">
        <div class="card-header border-bottom py-3 px-4">
            <h5 class="mb-0 fw-bold">
                <i class="menu-icon tf-icons bx bx-user-circle me-2"></i> Selamat Datang, <?php echo $this->session->userdata('nama_lengkap'); ?>
            </h5>
        </div>
        
        <div class="card-body p-4">
            <h4 class="fw-bold text-primary mb-3">Sistem Monitoring Operasional Perjalanan Haji & Umroh (SMOP)</h4>
            
            <p class="mb-4">
                Tujuan utama sistem ini adalah untuk memastikan kelancaran dan kualitas eksekusi layanan di lapangan secara real-time. SMOP adalah jembatan digital yang menghubungkan Admin di kantor dengan Tim Lapangan di Saudi/Bandara.
            </p>

            <div class="alert alert-success" role="alert">
                <p class="mb-0">Silakan klik menu Dashboard Monitoring untuk melihat status grup yang sedang aktif dan laporan dari Tim Lapangan.</p>
            </div>
            
            </div>
    </div>
</div>