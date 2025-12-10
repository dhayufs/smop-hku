<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_model extends CI_Model {

    // --- Template Induk (tabel_template) ---

    public function get_all_templates()
    {
        $this->db->order_by('id', 'DESC');
        return $this->db->get('tabel_template')->result();
    }

    public function get_template_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('tabel_template')->row();
    }

    public function create_template($data)
    {
        return $this->db->insert('tabel_template', $data);
    }

    public function update_template($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tabel_template', $data);
    }

    public function delete_template($id)
    {
        // Memulai transaksi untuk mengatasi Foreign Key Constraint Error (Error 1451)
        $this->db->trans_start();
        
        // 1. Hapus record di tabel_grup yang mereferensi template ini (tabel_grup_ibfk_1).
        //    Ini dilakukan untuk menghindari error: Cannot delete or update a parent row.
        $this->db->where('template_asal_id', $id);
        $this->db->delete('tabel_grup');
        
        // 2. Hapus template utama (tabel_template).
        //    Asumsi: item di tabel_template_item akan terhapus otomatis via ON DELETE CASCADE.
        $this->db->where('id', $id);
        $this->db->delete('tabel_template');
        
        // Menyelesaikan transaksi
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // --- Item Template (tabel_template_item) ---

    public function get_items_by_template($template_id)
    {
        $this->db->select('ti.*, tp.nama_peran, u.nama_lengkap AS pj_user_nama');
        $this->db->from('tabel_template_item ti');
        // Join dengan tabel_peran_tugas
        $this->db->join('tabel_peran_tugas tp', 'tp.id = ti.pj_peran_id', 'left'); 
        // Join dengan tabel_users (BARU)
        $this->db->join('tabel_users u', 'u.id = ti.pj_user_id_default', 'left');
        $this->db->where('ti.template_id', $template_id);
        // Urutkan berdasarkan blok, hari, dan urutan item
        $this->db->order_by('ti.tipe_blok', 'ASC'); 
        $this->db->order_by('ti.hari_ke', 'ASC'); 
        $this->db->order_by('ti.urutan', 'ASC');
        return $this->db->get()->result();
    }
    
    // Fungsi baru untuk Alur C.4
    public function get_all_template_items_by_template_id($template_id)
    {
        $this->db->where('template_id', $template_id);
        return $this->db->get('tabel_template_item')->result();
    }    
    
    // Fungsi untuk mendapatkan urutan item terakhir dalam hari tertentu
    public function get_last_urutan($template_id, $tipe_blok, $hari_ke)
    {
        $this->db->select_max('urutan');
        $this->db->where('template_id', $template_id);
        $this->db->where('tipe_blok', $tipe_blok);
        $this->db->where('hari_ke', $hari_ke);
        $query = $this->db->get('tabel_template_item')->row();
        
        return $query->urutan ? $query->urutan + 1 : 1;
    }
    
    // FUNGSI BARU (Point 2): Update Item Template
    public function update_template_item($item_id, $data)
    {
        $this->db->where('id', $item_id);
        return $this->db->update('tabel_template_item', $data);
    }
    
    // FUNGSI BARU (Point 3): Reorder Item Template (Naik/Turun)
    public function reorder_template_item($item_id, $direction)
    {
        $item = $this->get_item_by_id($item_id);

        if (!$item) {
            return FALSE;
        }

        $current_urutan = $item->urutan;
        $target_urutan = ($direction == 'up') ? $current_urutan - 1 : $current_urutan + 1;
        $tipe_blok = $item->tipe_blok;
        $hari_ke = $item->hari_ke;
        $template_id = $item->template_id;

        // Cari item yang ada di posisi target
        $target_item = $this->db->where('template_id', $template_id)
                                ->where('tipe_blok', $tipe_blok)
                                ->where('hari_ke', $hari_ke)
                                ->where('urutan', $target_urutan)
                                ->get('tabel_template_item')
                                ->row();
        
        if ($target_item) {
            $this->db->trans_start();

            // 1. Pindahkan posisi item target ke posisi item saat ini
            $this->db->where('id', $target_item->id)
                     ->update('tabel_template_item', ['urutan' => $current_urutan]);

            // 2. Pindahkan posisi item saat ini ke posisi target
            $this->db->where('id', $item_id)
                     ->update('tabel_template_item', ['urutan' => $target_urutan]);

            $this->db->trans_complete();

            return $this->db->trans_status();
        }

        return FALSE; // Tidak ada item target, tidak ada perubahan
    }

    public function create_template_item($data)
    {
        return $this->db->insert('tabel_template_item', $data);
    }

    public function delete_template_item($item_id)
    {
        $this->db->where('id', $item_id);
        return $this->db->delete('tabel_template_item');
    }
    
    // Fungsi dasar untuk mendapatkan satu item
    public function get_item_by_id($item_id)
    {
        $this->db->where('id', $item_id);
        return $this->db->get('tabel_template_item')->row();
    }
}