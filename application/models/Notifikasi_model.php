<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mendapatkan jumlah notifikasi yang belum dibaca (untuk badge di header)
    public function get_unread_count($admin_id)
    {
        $this->db->where('user_id_tujuan', $admin_id);
        $this->db->where('is_read', FALSE);
        return $this->db->count_all_results('tabel_notifikasi');
    }

    // Fungsi untuk mendapatkan notifikasi terbaru (untuk dropdown di header)
    public function get_recent_notifications($admin_id, $limit = 5)
    {
        // MENAMBAH field 'is_read'
        $this->db->select('n.id, n.grup_item_id, n.created_at, n.is_read, g.nama_grup, gi.deskripsi, gi.status');
        $this->db->from('tabel_notifikasi n');
        $this->db->join('tabel_grup_item gi', 'gi.id = n.grup_item_id');
        $this->db->join('tabel_grup g', 'g.id = gi.grup_id');
        $this->db->where('n.user_id_tujuan', $admin_id);
        $this->db->order_by('n.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    // Fungsi untuk mendapatkan semua notifikasi (untuk halaman notifikasi)
    public function get_all_notifications_for_admin($admin_id)
    {
        $this->db->select('n.*, g.nama_grup, gi.deskripsi, gi.tanggal_item, gi.status');
        $this->db->from('tabel_notifikasi n');
        $this->db->join('tabel_grup_item gi', 'gi.id = n.grup_item_id');
        $this->db->join('tabel_grup g', 'g.id = gi.grup_id');
        $this->db->where('n.user_id_tujuan', $admin_id);
        $this->db->order_by('n.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // FUNGSI BARU: Menandai notifikasi individu sudah dibaca (Dipakai AJAX)
    public function mark_as_read_individual($notif_id)
    {
        // Pastikan hanya admin yang bisa menandai notifikasi miliknya sendiri
        $admin_id = $this->session->userdata('user_id');
        
        $this->db->where('id', $notif_id)
                 ->where('user_id_tujuan', $admin_id)
                 ->where('is_read', FALSE)
                 ->limit(1)
                 ->update('tabel_notifikasi', ['is_read' => TRUE]);
        
        return $this->db->affected_rows() > 0;
    }

    // Fungsi untuk menandai semua notifikasi sudah dibaca
    public function mark_all_as_read($admin_id)
    {
        // Hanya update yang belum dibaca
        $this->db->where('user_id_tujuan', $admin_id)->where('is_read', FALSE)->update('tabel_notifikasi', ['is_read' => TRUE]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Helper untuk menghitung waktu berlalu
     */
    public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $ago = new DateTime($datetime, new DateTimeZone('Asia/Jakarta'));
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'tahun',
            'm' => 'bulan',
            'w' => 'minggu',
            'd' => 'hari',
            'h' => 'jam',
            'i' => 'menit',
            's' => 'detik',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' lalu' : 'baru saja';
    }
}