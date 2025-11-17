<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 
        // --- START: SET TIMEZONE WIB ---
        date_default_timezone_set('Asia/Jakarta');
        // --- END: SET TIMEZONE WIB ---
        $this->load->model('Auth_model');
    }
    
    // Baris 17 Anda ada di sini:
    public function index()
    {
        // SOLUSI MUTLAK: Dapatkan instance CI di awal fungsi
        $CI =& get_instance(); 
        
        if ($CI->session->userdata('logged_in')) {
            if ($CI->session->userdata('system_role') === 'admin') {
                 redirect('admin'); 
            } else {
                 redirect('mytasks'); 
            }
        }
        
        $this->load->view('auth/login');
    }

    public function login_process()
    {
        $CI =& get_instance(); 
        
        if ($CI->session->userdata('logged_in')) {
            if ($CI->session->userdata('system_role') === 'admin') {
                 redirect('admin'); 
            } else {
                 redirect('mytasks'); 
            }
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $CI->session->set_flashdata('error', validation_errors());
            redirect('auth'); 
        }
        else
        {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);

            $user = $this->Auth_model->verify_login($username, $password);

            if ($user)
            {
                $session_data = array(
                    'user_id'       => $user->id,
                    'username'      => $user->username,
                    'nama_lengkap'  => $user->nama_lengkap,
                    'system_role'   => $user->system_role,
                    'logged_in'     => TRUE
                );
                $CI->session->set_userdata($session_data);

                if ($user->system_role === 'admin') {
                    redirect('admin'); 
                } else {
                    redirect('mytasks'); 
                }
            }
            else
            {
                $CI->session->set_flashdata('error', 'Username atau Password salah.');
                redirect('auth');
            }
        }
    }

    public function logout()
    {
        $CI =& get_instance();
        $CI->session->sess_destroy();
        redirect('auth');
    }
}