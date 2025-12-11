<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grup_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // --- 1. CRUD Grup Induk (tabel_grup) ---

    public function get_all_grup()
    {
        $this->db->select('g.*, t.nama_template');
        $this->db->from('tabel_grup g');
        $this->db->join('tabel_template t', 't.id = g.template_asal_id', 'left');
        $this->db->order_by('g.tanggal_keberangkatan', 'DESC');
        return $this->db->get()->result();
    }
    
    /**
     * Mengambil semua riwayat perubahan status dari tabel RIWAYAT LAMA.
     */
    public function get_item_history($grup_item_id)
    {
        // Mengambil log dari tabel LAMA: tabel_riwayat_item
        $this->db->select('ri.timestamp, ri.perubahan, u.nama_lengkap AS user_pengubah_nama');
        $this->db->from('tabel_riwayat_item ri');
        $this->db->join('tabel_users u', 'u.id = ri.user_id_pengubah', 'left');
        $this->db->where('ri.grup_item_id', $grup_item_id);
        $this->db->order_by('ri.timestamp', 'DESC');
        return $this->db->get()->result();
    }
    
    // FUNGSI BARU (Point 5): Mengambil riwayat dari tabel_riwayat_item (untuk halaman update)
    public function get_item_history_with_user($grup_item_id)
    {
        $this->db->select('ri.*, u.nama_lengkap AS user_pengubah_nama');
        $this->db->from('tabel_riwayat_item ri');
        $this->db->join('tabel_users u', 'u.id = ri.user_id_pengubah', 'left');
        $this->db->where('ri.grup_item_id', $grup_item_id);
        $this->db->order_by('ri.timestamp', 'DESC');
        return $this->db->get()->result();
    }

    public function get_grup_by_id($id)
    {
        $this->db->select('g.*, t.nama_template');
        $this->db->from('tabel_grup g');
        $this->db->join('tabel_template t', 't.id = g.template_asal_id', 'left');
        $this->db->where('g.id', $id);
        return $this->db->get()->row();
    }
    
    // FUNGSI BARU (Point 4): Mengambil daftar grup yang ditugaskan ke user
    public function get_user_assigned_groups($user_id)
    {
        // Ambil grup berdasarkan penugasan di tabel_grup_tim
        $this->db->select('g.id AS grup_id, g.nama_grup, g.tanggal_keberangkatan, g.tanggal_pulang, g.status_grup');
        $this->db->from('tabel_grup g');
        $this->db->join('tabel_grup_tim gt', 'gt.grup_id = g.id');
        $this->db->where('gt.user_id', $user_id);
        // Tambahkan kondisi untuk hanya grup yang aktif/berjalan (optional, tapi disarankan)
        $this->db->where_in('g.status_grup', ['Aktif', 'Selesai']);
        $this->db->group_by('g.id');
        $this->db->order_by('g.tanggal_keberangkatan', 'DESC');
        return $this->db->get()->result();
    }
    
    // FUNGSI BARU (Point 3): Cek apakah user bagian dari grup
    public function is_user_assigned_to_group($user_id, $grup_id)
    {
         $this->db->where('grup_id', $grup_id);
         $this->db->where('user_id', $user_id);
         return $this->db->count_all_results('tabel_grup_tim') > 0;
    }
    
    // FUNGSI BARU (Missing from error): Mengambil daftar nama peran tugas (untuk index mytasks)
    public function get_all_role_names()
    {
        $this->db->select('nama_peran');
        $this->db->from('tabel_peran_tugas');
        $this->db->order_by('nama_peran', 'ASC'); 
        return $this->db->get()->result(); 
    }
    
    // --- 2. Logika Inti: Buat Grup dari Template (Alur C.1) ---
    
    public function create_live_group($grup_data, $penugasan_tim, $template_items)
    {
        $this->db->trans_start();

        $this->db->insert('tabel_grup', $grup_data);
        $new_grup_id = $this->db->insert_id();

        $tim_inserts = [];
        foreach ($penugasan_tim as $peran_id => $user_id) {
            $tim_inserts[] = [
                'grup_id' => $new_grup_id,
                'peran_tugas_id' => $peran_id,
                'user_id' => $user_id
            ];
        }
        if (!empty($tim_inserts)) {
            $this->db->insert_batch('tabel_grup_tim', $tim_inserts);
        }

        $item_inserts = [];
        $tanggal_keberangkatan = new DateTime($grup_data['tanggal_keberangkatan']);
        $mapPenugasan = $penugasan_tim;

        foreach ($template_items as $itemTemplate) 
        {
            $tanggal_item_final = $this->_calculate_item_date($tanggal_keberangkatan, $itemTemplate);
            
            $pj_user_id_final = NULL;
            $peran_id_final = NULL; 
            
            if ($itemTemplate->tipe_item === 'checklist') {
                // Gunakan user default dari template item (Pelaksana)
                $pj_user_id_final = $itemTemplate->pj_user_id_default; 
                // Simpan PJ Peran dari Template jika ada
                $peran_id_final = $itemTemplate->pj_peran_id; 
            }

            $item_inserts[] = [
                'grup_id' => $new_grup_id,
                'template_item_asal_id' => $itemTemplate->id, 
                'tanggal_item' => $tanggal_item_final,
                'urutan' => $itemTemplate->urutan,
                'tipe_blok' => $itemTemplate->tipe_blok, 
                'hari_ke' => $itemTemplate->hari_ke,     
                'tipe_item' => $itemTemplate->tipe_item,
                'deskripsi' => $itemTemplate->deskripsi_item,
                'peran_tugas_id' => $peran_id_final, 
                'pj_user_id' => $pj_user_id_final,
                'status' => 'Pending', 
                'foto_bukti' => NULL,
                'catatan' => NULL
            ];
        }

        if (!empty($item_inserts)) {
            $this->db->insert_batch('tabel_grup_item', $item_inserts);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }

        return $new_grup_id;
    }
    
    // --- 3. Update & Delete ---
    
    public function update_grup($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tabel_grup', $data);
    }
    
    /**
     * Memperbarui detail grup dan penugasan tim dalam satu transaksi
     * @param int $grup_id ID Grup yang akan diperbarui
     * @param array $grup_data_update Data untuk tabel_grup
     * @param array $penugasan_tim_baru Map [peran_tugas_id] => user_id
     * @return bool Status transaksi
     */
    public function update_grup_and_tim($grup_id, $grup_data_update, $penugasan_tim_baru)
    {
        $this->db->trans_start();

        // 1. Update tabel_grup
        $this->db->where('id', $grup_id);
        $this->db->update('tabel_grup', $grup_data_update);

        // 2. Update tabel_grup_tim (Hapus yang lama, masukkan yang baru)
        $this->db->where('grup_id', $grup_id);
        $this->db->delete('tabel_grup_tim');

        $tim_inserts = [];
        foreach ($penugasan_tim_baru as $peran_id => $user_id) {
            $tim_inserts[] = [
                'grup_id' => $grup_id,
                'peran_tugas_id' => $peran_id,
                'user_id' => $user_id
            ];
        }
        if (!empty($tim_inserts)) {
            $this->db->insert_batch('tabel_grup_tim', $tim_inserts);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }


    public function delete_grup($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tabel_grup');
    }

    // --- 4. Helper dan Pivot Penugasan Tim ---

    public function get_grup_tim_penugasan($grup_id)
    {
        $this->db->select('gt.user_id, gt.peran_tugas_id, u.nama_lengkap, pt.nama_peran');
        $this->db->from('tabel_grup_tim gt');
        $this->db->join('tabel_users u', 'u.id = gt.user_id', 'left');
        $this->db->join('tabel_peran_tugas pt', 'pt.id = gt.peran_tugas_id', 'left');
        $this->db->where('gt.grup_id', $grup_id);
        return $this->db->get()->result();
    }
    
    // FUNGSI PENDUKUNG BARU (Revisi): Mengambil detail item untuk edit/validasi
    public function get_grup_item_detail($grup_item_id)
    {
        // Mengambil detail item checklist live (tabel_grup_item)
        $this->db->select('gi.*, pt.nama_peran, u.nama_lengkap AS pj_nama');
        $this->db->from('tabel_grup_item gi');
        $this->db->join('tabel_peran_tugas pt', 'pt.id = gi.peran_tugas_id', 'left'); // Tambah join peran
        $this->db->join('tabel_users u', 'u.id = gi.pj_user_id', 'left');
        $this->db->where('gi.id', $grup_item_id);
        return $this->db->get()->row();
    }
    
    // --- FUNGSI BARU UNTUK EDIT CHECKLIST LIVE DARI ADMIN ---
    
    public function update_grup_item($item_id, $data)
    {
        // Memperbarui data item checklist live (deskripsi, tipe, pj_peran_id, pj_user_id)
        $this->db->where('id', $item_id);
        return $this->db->update('tabel_grup_item', $data);
    }
    
    public function delete_grup_item($item_id)
    {
        // Menghapus item live checklist. Asumsikan trigger DB mengurus riwayat terkait
        $this->db->where('id', $item_id);
        return $this->db->delete('tabel_grup_item');
    }

    public function reorder_grup_item($item_id, $direction)
    {
        // Panggil fungsi detail untuk mendapatkan data yang diperlukan
        $item = $this->get_grup_item_detail($item_id);
        if (!$item) {
            return FALSE;
        }

        $current_urutan = $item->urutan;
        $grup_id = $item->grup_id;
        $tanggal_item = $item->tanggal_item;

        $this->db->trans_start(); // Mulai transaksi

        if ($direction === 'up') {
            // Cari item di atasnya (urutan lebih kecil, tanggal sama)
            $this->db->where('grup_id', $grup_id);
            $this->db->where('tanggal_item', $tanggal_item);
            $this->db->where('urutan <', $current_urutan);
            $this->db->order_by('urutan', 'DESC');
            $item_tujuan = $this->db->get('tabel_grup_item', 1)->row();
            
            if ($item_tujuan) {
                // Tukar posisi: Item Tujuan (di atas) turun, Item Saat Ini naik
                $new_urutan = $item_tujuan->urutan;
                $this->db->where('id', $item_tujuan->id)->update('tabel_grup_item', ['urutan' => $current_urutan]);
                $this->db->where('id', $item_id)->update('tabel_grup_item', ['urutan' => $new_urutan]);
            }

        } elseif ($direction === 'down') {
            // Cari item di bawahnya (urutan lebih besar, tanggal sama)
            $this->db->where('grup_id', $grup_id);
            $this->db->where('tanggal_item', $tanggal_item);
            $this->db->where('urutan >', $current_urutan);
            $this->db->order_by('urutan', 'ASC');
            $item_tujuan = $this->db->get('tabel_grup_item', 1)->row();

            if ($item_tujuan) {
                // Tukar posisi: Item Tujuan (di bawah) naik, Item Saat Ini turun
                $new_urutan = $item_tujuan->urutan;
                $this->db->where('id', $item_tujuan->id)->update('tabel_grup_item', ['urutan' => $current_urutan]);
                $this->db->where('id', $item_id)->update('tabel_grup_item', ['urutan' => $new_urutan]);
            }
        }
        
        $this->db->trans_complete(); // Selesaikan transaksi

        return $this->db->trans_status(); // Return status transaksi
    }
    
    // --- FUNGSI BARU UNTUK TAMBAH ITEM LIVE DARI ADMIN ---

    /**
     * Mengambil urutan item terakhir dalam tanggal item tertentu di sebuah grup
     */
    public function get_last_urutan_grup($grup_id, $tanggal_item)
    {
        $this->db->select_max('urutan');
        $this->db->where('grup_id', $grup_id);
        $this->db->where('tanggal_item', $tanggal_item);
        $query = $this->db->get('tabel_grup_item')->row();
        
        return $query->urutan ? $query->urutan + 1 : 1;
    }

    /**
     * Membuat item live checklist baru
     */
    public function create_grup_item_live($data)
    {
        // Pastikan field status diset jika tidak ada (default Pending)
        if (!isset($data['status'])) {
            $data['status'] = 'Pending';
        }
        
        // --- FIX for Error 1048/1452 ---
        // Mengirim NULL adalah nilai logis yang benar untuk item manual.
        // Asumsi: Developer TELAH MENGUBAH kolom 'template_item_asal_id' di tabel 'tabel_grup_item' menjadi NULLABLE.
        if (!isset($data['template_item_asal_id']) || $data['template_item_asal_id'] === 0) {
             $data['template_item_asal_id'] = NULL;
        }
        
        return $this->db->insert('tabel_grup_item', $data);
    }
    
    // --- END FUNGSI BARU UNTUK EDIT CHECKLIST LIVE DARI ADMIN ---
    
    // FUNGSI YANG DIKEMBANGKAN (Point 3 & 4): Mengambil Checklist Live (dikelompokkan)
    public function get_live_checklist_grouped($grup_id)
    {
        $this->db->select('gi.*, u.nama_lengkap AS pj_nama, pt.nama_peran');
        $this->db->from('tabel_grup_item gi');
        $this->db->join('tabel_users u', 'u.id = gi.pj_user_id', 'left');
        $this->db->join('tabel_peran_tugas pt', 'pt.id = gi.peran_tugas_id', 'left'); 
        $this->db->where('gi.grup_id', $grup_id);
        $this->db->order_by('gi.tanggal_item', 'ASC'); 
        $this->db->order_by('gi.tipe_blok', 'ASC'); 
        $this->db->order_by('gi.urutan', 'ASC');
        $live_items = $this->db->get()->result();
        
        $grouped = [];
        foreach ($live_items as $item) {
            $grouped[$item->tanggal_item][$item->tipe_blok][] = $item;
        }
        return $grouped;
    }
    
    // FUNGSI ASLI (digunakan oleh Admin Controller)
    public function get_live_checklist($grup_id)
    {
        $this->db->select('gi.*, u.nama_lengkap AS pj_nama, pt.nama_peran');
        $this->db->from('tabel_grup_item gi');
        $this->db->join('tabel_users u', 'u.id = gi.pj_user_id', 'left');
        $this->db->join('tabel_peran_tugas pt', 'pt.id = gi.peran_tugas_id', 'left');
        $this->db->where('gi.grup_id', $grup_id);
        $this->db->order_by('gi.tanggal_item', 'ASC'); 
        $this->db->order_by('gi.urutan', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
     * Fungsi private untuk menghitung tanggal item
     */
    private function _calculate_item_date(DateTime $tanggal_keberangkatan, $itemTemplate)
    {
        $date = clone $tanggal_keberangkatan;

        if ($itemTemplate->tipe_blok === 'manasik') {
            $date->sub(new DateInterval('P' . $itemTemplate->hari_ke . 'D'));
        } elseif ($itemTemplate->tipe_blok === 'perjalanan') {
            $offset = $itemTemplate->hari_ke - 1;
            $date->add(new DateInterval('P' . $offset . 'D'));
        }
        
        return $date->format('Y-m-d');
    }
    
    // --- 5. Fungsi Khusus Dashboard Monitoring (Alur C.3) ---

    public function get_relevant_grup()
    {
        $tanggal_batas_arsip = date('Y-m-d', strtotime('-10 days'));

        $this->db->select('g.*, t.nama_template');
        $this->db->from('tabel_grup g');
        $this->db->join('tabel_template t', 't.id = g.template_asal_id', 'left');
        
        $this->db->where("g.tanggal_pulang >=", $tanggal_batas_arsip);

        $this->db->order_by('g.tanggal_keberangkatan', 'ASC');
        
        return $this->db->get()->result();
    }
    
    public function get_grup_progress($grup_id)
    {
        // Total Item Checklist
        $total_item = $this->db->where('grup_id', $grup_id)
                               ->where('tipe_item', 'checklist')
                               ->count_all_results('tabel_grup_item');

        // Item Selesai (Sukses, Cukup, Buruk, Gagal)
        $item_selesai = $this->db->where('grup_id', $grup_id)
                                 ->where('tipe_item', 'checklist')
                                 ->where('status !=', 'Pending')
                                 ->count_all_results('tabel_grup_item');
        
        // Item Buruk/Gagal (untuk notifikasi admin)
        $item_gagal_buruk = $this->db->where('grup_id', $grup_id)
                                    ->where('tipe_item', 'checklist')
                                    ->where_in('status', ['Buruk', 'Gagal'])
                                    ->count_all_results('tabel_grup_item');

        $persentase = 0;
        if ($total_item > 0) {
            $persentase = round(($item_selesai / $total_item) * 100);
        }

        return [
            'total' => $total_item, 
            'selesai' => $item_selesai, 
            'gagal_buruk' => $item_gagal_buruk, 
            'persentase' => $persentase
        ];
    }
    
    public function get_unread_notifications($user_id_tujuan)
    {
        $this->db->select('*');
        $this->db->from('tabel_notifikasi');
        $this->db->where('user_id_tujuan', $user_id_tujuan);
        $this->db->where('is_read', FALSE);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    // --- 6. Logika Inti: Update Massal Tanggal (Alur C.4) ---
    
    public function handle_penundaan_grup($grup_id, $tanggal_keberangkatan_baru, $template, $listTemplateItemMaster)
    {
        $this->db->trans_start();

        $tgl_baru_obj = new DateTime($tanggal_keberangkatan_baru);

        $listItemGrup = $this->db->get_where('tabel_grup_item', ['grup_id' => $grup_id])->result();
        
        $updates = [];

        // Loop & Hitung Ulang Tanggal
        foreach ($listItemGrup as $itemGrup) {
            $blueprint_id = $itemGrup->template_item_asal_id;
            
            $blueprint = $listTemplateItemMaster[$blueprint_id] ?? NULL;
            
            if (!$blueprint) {
                continue; 
            }

            $tanggal_item_baru = NULL;
            $temp_tgl = clone $tgl_baru_obj;

            // Logika perhitungan tanggal item berdasarkan hari_ke blueprint
            if ($blueprint->tipe_blok === 'manasik') {
                $temp_tgl->sub(new DateInterval('P' . $blueprint->hari_ke . 'D'));
            } elseif ($blueprint->tipe_blok === 'perjalanan') {
                $offset = $blueprint->hari_ke - 1;
                $temp_tgl->add(new DateInterval('P' . $offset . 'D'));
            }
            $tanggal_item_baru = $temp_tgl->format('Y-m-d');

            $updates[] = [
                'id' => $itemGrup->id,
                'tanggal_item' => $tanggal_item_baru
            ];
        }

        // Eksekusi Update Massal
        if (!empty($updates)) {
            $this->db->update_batch('tabel_grup_item', $updates, 'id');
        }

        // Update Tanggal Induk di Grup
        $tanggal_pulang_baru_obj = clone $tgl_baru_obj;
        $tanggal_pulang_baru_obj->add(new DateInterval('P' . ($template->lama_perjalanan - 1) . 'D'));
        $tanggal_pulang_baru = $tanggal_pulang_baru_obj->format('Y-m-d');
        
        $this->db->where('id', $grup_id);
        $this->db->update('tabel_grup', [
            'tanggal_keberangkatan' => $tanggal_keberangkatan_baru,
            'tanggal_pulang' => $tanggal_pulang_baru
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}