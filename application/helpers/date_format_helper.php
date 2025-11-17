<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helper untuk format tanggal Indonesia
 * (Fungsi ini dipanggil di View index.php dan detail.php)
 */

if ( ! function_exists('format_indo_date'))
{
    function format_indo_date($datetime, $with_time = false)
    {
        // LOGIKA CONTOH (ANDA HARUS MENGISI LOGIKA ASLI ANDA DI SINI)
        if ($datetime == '0000-00-00 00:00:00' || empty($datetime)) {
            return 'N/A';
        }

        $date_only = date('Y-m-d', strtotime($datetime));
        $time_only = date('H:i', strtotime($datetime));
        
        $bulan = array (
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
            'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );
        $pecahkan = explode('-', $date_only);
        $tgl_indo = $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
        
        if ($with_time) {
            return $tgl_indo . ' Pukul ' . $time_only . ' WIB';
        }
        return $tgl_indo;
    }
}

// Tambahkan fungsi helper lain jika diperlukan oleh Views atau Controller