<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// === FUNGSI HELPER BARU UNTUK FORMAT TANGGAL INDONESIA (Hari, DD Bulan YYYY) ===
if (!function_exists('format_indo_date')) {
    function format_indo_date($date_string) {
        // Daftar nama hari dan bulan dalam Bahasa Indonesia
        $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        
        // Konversi string tanggal ke timestamp
        $date = strtotime($date_string);
        // Ambil index hari (0=Minggu, 6=Sabtu)
        $w = date('w', $date);
        // Ambil tanggal
        $tgl = date('d', $date);
        // Ambil index bulan (1-12)
        $bln = date('n', $date);
        // Ambil tahun
        $thn = date('Y', $date);
        
        // Gabungkan dan kembalikan format: Hari, DD Bulan YYYY
        return $hari[$w] . ', ' . $tgl . ' ' . $bulan[$bln] . ' ' . $thn;
    }
}
// ==================================================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | SMOP</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f0f2f5;
        }
        
        /* === Gaya Card Utama === */
        .order-card {
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            cursor: default;
        }
        .order-card:hover {
             box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.3);
        }
        
        /* Flexbox untuk konten card */
        .card-block {
            padding: 1.25rem;
        }
        .m-b-20 {
            margin-bottom: 20px;
        }
        .f-left {
            float: left;
        }
        .f-right {
            float: right;
        }
        
        /* === Definisi Gradient Color === */
        .bg-c-blue {
            background: linear-gradient(45deg,#4099ff,#73b4ff); /* Biru (Pending) */
        }
        
        .bg-c-green {
            background: linear-gradient(45deg,#2ed8b6,#59e0c5); /* Hijau (Sukses) */
        }
        
        .bg-c-yellow {
            background: linear-gradient(45deg,#FFB64D,#ffcb80); /* Kuning (Cukup) */
        }
        
        .bg-c-pink {
            background: linear-gradient(45deg,#FF5370,#ff869a); /* Merah Muda (Buruk/Gagal) */
        }
        
        /* === Gaya untuk Daftar Tugas (Header Grup) === */
        .card-gradient-primary {
            background: linear-gradient(45deg,#4099ff,#73b4ff); 
            color: white;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <a class="navbar-brand" href="<?php echo site_url('mytasks'); ?>">SMOP</a>
        <span class="navbar-text">
            Halo, <?php echo $this->session->userdata('nama_lengkap'); ?> | 
            <a href="<?php echo site_url('auth/logout'); ?>" class="text-white font-weight-bold">
                <i class="fa fa-sign-out-alt"></i> Logout
            </a>
        </span>
    </nav>
    <div class="container py-4">