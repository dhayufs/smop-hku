<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Logika Inti: Update Status Checklist oleh Tim Lapangan (Alur C.2)
     */
    public function update_checklist_item($current_user_id, $grup_item_id, $new_status, $new_foto_path = NULL, $catatan = NULL)
    {
        $item = $this->db->select('gi.*, g.nama_grup, u.nama_lengkap')
                         ->from('tabel_grup_item gi')
                         ->join('tabel_grup g', 'g.id = gi.grup_id', 'left') 
                         ->join('tabel_users u', 'u.id = gi.pj_user_id', 'left')
                         ->where('gi.id', $grup_item_id)
                         ->get()
                         ->row();

        if (!$item) {
            return "Item checklist tidak ditemukan.";
        }
        
        // Cek Otorisasi (Hanya PIC yang Boleh Edit)
        if ($item->tipe_item === 'checklist' && $item->pj_user_id != $current_user_id) {
            return "Anda tidak memiliki hak akses untuk mengubah item ini (Bukan PIC).";
        }
        
        $user_pengubah = $this->db->get_where('tabel_users', ['id' => $current_user_id])->row();

        $old_status = $item->status;
        $foto_url_final = $new_foto_path ? $new_foto_path : $item->foto_bukti;

        $this->db->trans_start();

        // 1. Update Database (Tugas Utama: tabel_grup_item)
        $this->db->where('id', $grup_item_id);
        $this->db->update('tabel_grup_item', [
            'status' => $new_status,
            'foto_bukti' => $foto_url_final,
            'catatan' => $catatan // Simpan Catatan saat ini
        ]);

        // 2. Catat ke Riwayat (tabel_riwayat_item)
        $perubahan = "Status diubah dari '" . $old_status . "' ke '" . $new_status . "'.";
        
        // Tambahkan detail Catatan dan Foto ke Log Perubahan
        if (!empty($catatan)) {
            $perubahan .= " Catatan: " . $catatan;
        }
        if (!empty($new_foto_path)) {
            $perubahan .= " Foto Bukti: " . $new_foto_path;
        }


        $this->db->insert('tabel_riwayat_item', [
            'grup_item_id' => $grup_item_id,
            'user_id_pengubah' => $current_user_id,
            'timestamp' => date('Y-m-d H:i:s'),
            'perubahan' => $perubahan // Menyimpan detail perubahan (status, catatan, foto path)
        ]);

        // 3. Pemicu Notifikasi (tabel_notifikasi)
        if ($new_status === 'Buruk' || $new_status === 'Gagal') {
            $listAdmin = $this->db->get_where('tabel_users', ['system_role' => 'admin'])->result();
            $pesan_notif = "Item '" . $item->deskripsi . "' (Grup: " . $item->nama_grup . ") ditandai '" . $new_status . "' oleh " . $user_pengubah->nama_lengkap . ".";

            foreach ($listAdmin as $admin) {
                $this->db->insert('tabel_notifikasi', [
                    'user_id_tujuan' => $admin->id,
                    'grup_item_id' => $grup_item_id,
                    'pesan' => $pesan_notif,
                    'is_read' => FALSE
                ]);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "Terjadi kesalahan sistem saat menyimpan perubahan.";
        }

        return TRUE;
    }
    
    // --- FUNGSI BARU: Mengambil Laporan Kinerja (Area 4) ---
    public function get_laporan_kinerja()
    {
        // PENTING: Ini adalah implementasi awal. Query aslinya akan kompleks.
        // Di sini kita ambil semua grup yang berstatus 'Selesai' atau 'Arsip' untuk ditampilkan.
        $this->db->select('g.id AS grup_id, g.nama_grup, g.tanggal_pulang, g.status_grup');
        $this->db->from('tabel_grup g');
        $this->db->where_in('g.status_grup', ['Selesai', 'Arsip']);
        $this->db->order_by('g.tanggal_pulang', 'DESC');
        
        $grup_list = $this->db->get()->result();
        $reports = [];
        
        // Loop untuk menghitung statistik (STATISTIK INI SEMENTARA DI-HARDCODE)
        foreach ($grup_list as $grup) {
            $reports[] = [
                'grup_id' => $grup->grup_id,
                'nama_grup' => $grup->nama_grup,
                'tanggal_pulang' => $grup->tanggal_pulang,
                'status_grup' => $grup->status_grup,
                // --- DATA SIMULASI DI LEVEL MODEL UNTUK MENGISI VIEW ---
                'total_item' => 100, // Harus dihitung dari tabel_grup_item
                'sukses' => rand(80, 95),
                'cukup' => rand(3, 10),
                'buruk' => rand(0, 3),
                'gagal' => rand(0, 2)
            ];
        }
        
        return $reports;
    }
}