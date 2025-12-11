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

/**
 * Helper untuk format Waktu Log KSA/WIB
 * (Dipanggil di View update_task_form.php)
 */
if (!function_exists('format_log_time')) {
    function format_log_time($timestamp) {
        // Asumsi: Waktu di DB adalah WIB (Asia/Jakarta), karena server kemungkinan berada di Indonesia.
        $dt_wib = new DateTime($timestamp, new DateTimeZone('Asia/Jakarta'));
        
        // Konversi ke KSA (Saudi Arabia Standard Time)
        $dt_ksa = clone $dt_wib;
        $dt_ksa->setTimezone(new DateTimeZone('Asia/Riyadh'));
        
        // Output format yang diminta
        // Memanggil fungsi format_indo_date yang sudah didefinisikan di helper ini
        $tanggal_indo = format_indo_date($dt_wib->format('Y-m-d'));
        $wib_time = $dt_wib->format('H:i');
        $ksa_time = $dt_ksa->format('H:i');
        
        return [
            'tanggal' => $tanggal_indo,
            'wib' => $wib_time,
            'ksa' => $ksa_time
        ];
    }
}