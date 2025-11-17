<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col-span-12">
    <div class="card bg-white dark:bg-themedark-cardbg shadow mb-6">
        <div class="card-header border-b border-theme-border dark:border-themedark-border p-4 bg-gray-50 dark:bg-gray-800">
            <h5 class="mb-0 font-medium">
                <i data-feather="bell" class="w-4 h-4 mr-2 inline-block"></i> Selamat Datang, <?php echo $this->session->userdata('nama_lengkap'); ?>
            </h5>
        </div>
        
        <div class="card-body p-6">
            <h4 class="font-bold text-primary-500 mb-4">Sistem Monitoring Operasional Perjalanan Haji & Umroh (SMOP)</h4>
            
            <p class="mb-4">
                Tujuan utama sistem ini adalah untuk memastikan kelancaran dan kualitas eksekusi layanan di lapangan secara real-time. SMOP adalah jembatan digital yang menghubungkan Admin di kantor dengan Tim Lapangan di Saudi/Bandara.
            </p>

            <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4">
                <p>Silakan klik menu Dashboard Monitoring untuk melihat status grup yang sedang aktif dan laporan dari Tim Lapangan.</p>
            </div>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<script>if (typeof feather !== 'undefined') { feather.replace(); }</script>