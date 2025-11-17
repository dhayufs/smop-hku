<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    // --- 1. Fungsi CRUD Users ---
    public function get_all_users()
    {
        $this->db->select('u.*');
        $this->db->from('tabel_users u');
        $this->db->order_by('u.system_role', 'ASC');
        return $this->db->get()->result();
    }
    
    // FUNGSI BARU/HILANG: Mengambil detail user berdasarkan ID
    public function get_user_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('tabel_users')->row();
    }

    public function create_user($data)
    {
        // Menggunakan MD5 sesuai permintaan
        $data['password_hash'] = md5($data['password']);
        unset($data['password']); 

        return $this->db->insert('tabel_users', $data);
    }
    
    // FUNGSI BARU: Update user (termasuk reset password)
    public function update_user($id, $data)
    {
        // Handle password update jika disertakan
        if (isset($data['password'])) {
            $data['password_hash'] = md5($data['password']);
            unset($data['password']);
        }
        
        $this->db->where('id', $id);
        return $this->db->update('tabel_users', $data);
    }
    
    // FUNGSI BARU: Hapus user
    public function delete_user($id)
    {
        // Hapus penugasan terkait di tabel_grup_tim (FK harus CASCADE/SET NULL)
        // Jika tidak CASCADE, hapus manual dulu (tapi kita asumsikan FK sudah benar)
        $this->db->where('id', $id);
        return $this->db->delete('tabel_users');
    }
    
    // --- 2. Fungsi Master Peran Tugas (CRUD) ---
    
    public function get_all_peran()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('tabel_peran_tugas')->result();
    }

    // FUNGSI YANG HILANG/BELUM TERSEDIA: get_peran_by_id()
    public function get_peran_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('tabel_peran_tugas')->row();
    }

    public function create_peran($data)
    {
        return $this->db->insert('tabel_peran_tugas', $data);
    }

    public function update_peran($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tabel_peran_tugas', $data);
    }

    public function delete_peran($id)
    {
        // Note: Delete ini mungkin gagal jika ada Foreign Key di tabel_grup_tim atau tabel_template_item
        $this->db->where('id', $id);
        return $this->db->delete('tabel_peran_tugas');
    }
}