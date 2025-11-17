<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    // Fungsi untuk memverifikasi user dan password
    public function verify_login($username, $password)
    {
        // 1. Ambil data user berdasarkan username
        $this->db->where('username', $username);
        $query = $this->db->get('tabel_users');

        if ($query->num_rows() == 1)
        {
            $user = $query->row();
            // 2. Verifikasi Password dengan MD5
            // Kunci Logika: Cek apakah MD5 dari input password sama dengan password_hash di DB
            if ($user->password_hash === md5($password))
            {
                // Login Sukses
                return $user;
            }
        }
        // Gagal
        return FALSE;
    }
}