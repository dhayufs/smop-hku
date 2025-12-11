<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // --- START: SET TIMEZONE WIB ---
        date_default_timezone_set('Asia/Jakarta');
        // --- END: SET TIMEZONE WIB ---
        // Load semua Model dan Library yang diperlukan
        $this->load->model('User_model');
        $this->load->model('Template_model');
        $this->load->model('Grup_model'); 
        $this->load->model('Report_model');
        $this->load->model('Notifikasi_model'); // <--- TAMBAH MODEL NOTIFIKASI
        $this->load->library('form_validation'); 
        $this->load->helper('date'); 

        // Validasi akses Admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('system_role') !== 'admin') {
            redirect('auth');
        }
    }
    
    // --- FUNGSI BARU: MEMUAT DATA NOTIFIKASI UNTUK HEADER (DIPANGGIL DI SEMUA VIEW) ---
    private function _get_header_data($data)
    {
        $admin_id = $this->session->userdata('user_id');
        // Ambil jumlah notifikasi belum dibaca (untuk badge)
        $data['unread_count'] = $this->Notifikasi_model->get_unread_count($admin_id);
        
        // Ambil 5 notifikasi terbaru (untuk dropdown)
        $recent_notifs = $this->Notifikasi_model->get_recent_notifications($admin_id, 5); 
        
        // Format waktu sebelum dikirim ke view
        foreach ($recent_notifs as $notif) {
            $notif->time_ago = $this->Notifikasi_model->time_elapsed_string($notif->created_at);
        }
        $data['recent_notifications'] = $recent_notifs;
        
        return $data;
    }
    // --- END: FUNGSI MEMUAT DATA NOTIFIKASI ---
    
    // --- FUNGSI BARU: Halaman Riwayat Item (Menggantikan Modal/AJAX) ---
    public function item_history($grup_item_id)
    {
        // Pastikan Item ditemukan dan ambil detailnya
        $this->load->model('Grup_model');
        $this->load->model('User_model');
        
        $item = $this->Grup_model->get_grup_item_detail($grup_item_id);
        if (!$item) {
            $this->session->set_flashdata('error', 'Item tugas tidak ditemukan.');
            redirect('admin/monitoring');
        }

        $grup_id = $item->grup_id;
        $grup = $this->Grup_model->get_grup_by_id($grup_id);
        
        if (!$grup) {
            $this->session->set_flashdata('error', 'Grup terkait tidak ditemukan.');
            redirect('admin/monitoring');
        }

        $data['title'] = 'Riwayat Aksi: ' . $item->deskripsi;
        $data['item'] = $item;
        $data['grup'] = $grup;
        // Mengambil riwayat dari tabel_riwayat_item
        $data['riwayat'] = $this->Grup_model->get_item_history_with_user($grup_item_id); 
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/grup/history_detail', $data); // View baru
        $this->load->view('backend/layouts/footer');
    }

    // --- 1. HALAMAN UTAMA (ROOT /ADMIN) - KALIMAT PEMBUKA (REVISI BARU) ---
    public function index()
    {
        $data['title'] = 'Selamat Datang di SMOP Haramainku';
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        // Memuat view dashboard.php yang sekarang berisi kalimat pembuka
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/dashboard', $data); 
        $this->load->view('backend/layouts/footer');
    }
    
    // --- 2. DASHBOARD MONITORING (MENU BARU) - ALUR C.3 ---
    public function monitoring()
    {
        $admin_id = $this->session->userdata('user_id');
        $data['title'] = 'Dashboard Monitoring Grup';
        $data['active_groups'] = [];
        $data['archived_groups'] = [];
        $hari_ini = new DateTime(date('Y-m-d'));

        // Ambil Grup yang Relevan (Langkah 1 & 2 Alur C.3)
        $listGrup = $this->Grup_model->get_relevant_grup();

        // Loop dan Hitung Progres & Pisahkan Aktif/Non-Aktif (Sesuai Revisi)
        foreach ($listGrup as $grup) {
            $progress = $this->Grup_model->get_grup_progress($grup->id);
            
            $tgl_berangkat = new DateTime($grup->tanggal_keberangkatan);
            $tgl_pulang = new DateTime($grup->tanggal_pulang);
            $status_otomatis = '';
            $is_arsip = FALSE;
            
            if ($hari_ini < $tgl_berangkat) { 
                $status_otomatis = "Manasik / Persiapan";
            } elseif ($hari_ini >= $tgl_berangkat && $hari_ini <= $tgl_pulang) { 
                $status_otomatis = "Sedang Berjalan";
            } else {
                $status_otomatis = "Selesai";
                $tgl_batas_gray = new DateTime($grup->tanggal_pulang);
                $tgl_batas_gray->add(new DateInterval('P10D'));
                if ($hari_ini > $tgl_batas_gray) {
                    $is_arsip = TRUE;
                }
            }
            
            $grup_data = [
                'grup_id' => $grup->id,
                'nama_grup' => $grup->nama_grup,
                'total_tim' => $this->db->where('grup_id', $grup->id)->count_all_results('tabel_grup_tim'),
                'progress_persen' => $progress['persentase'],
                'total_item' => $progress['total'],
                'item_selesai' => $progress['selesai'],
                'item_gagal_buruk' => $progress['gagal_buruk'],
                'status_label' => $status_otomatis,
                'tanggal_keberangkatan' => $grup->tanggal_keberangkatan,
            ];

            // PISAHKAN AKTIF DENGAN NON-AKTIF/ARSIP
            if ($is_arsip) {
                $data['archived_groups'][] = $grup_data;
            } else {
                $data['active_groups'][] = $grup_data;
            }
        }

        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/monitoring/index', $data); // View Monitoring Baru
        $this->load->view('backend/layouts/footer');
    }
    
    // --- 3. Master User & Akun (Area 1) ---
    public function users()
    {
        $data['title'] = 'Manajemen User & Akun';
        $data['users'] = $this->User_model->get_all_users();
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/user/list', $data); 
        $this->load->view('backend/layouts/footer');
    }
    
    public function user_form($user_id)
    {
        $data['user_data'] = $this->User_model->get_user_by_id($user_id);
        if (!$data['user_data']) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/users');
        }
        $data['title'] = 'Edit Akun: ' . $data['user_data']->nama_lengkap;
        $data['mode'] = 'edit';
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/user/form', $data); // View form edit baru
        $this->load->view('backend/layouts/footer');
    }
    
    public function user_action($user_id = NULL)
    {
        // Jika ID ada, ini adalah UPDATE
        if ($user_id) {
            $user_data = $this->User_model->get_user_by_id($user_id);
            if (!$user_data) {
                $this->session->set_flashdata('error', 'User tidak ditemukan.');
                redirect('admin/users');
            }
            
            // Aturan validasi untuk UPDATE (Username tidak perlu is_unique jika tidak diubah)
            $username_rule = ($user_data->username === $this->input->post('username')) ? 'required' : 'required|is_unique[tabel_users.username]';
            
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
            $this->form_validation->set_rules('username', 'Username', $username_rule);
            $this->form_validation->set_rules('system_role', 'Role Sistem', 'required|in_list[admin,user]');
            // Password hanya divalidasi jika diisi (untuk reset)
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                // Redirect ke form edit jika gagal validasi
                $this->user_form($user_id);
                return;
            }
            
            $data_update = array(
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'username'     => $this->input->post('username'),
                'system_role'  => $this->input->post('system_role'),
            );
            if ($this->input->post('password')) {
                $data_update['password'] = $this->input->post('password');
            }

            if ($this->User_model->update_user($user_id, $data_update)) {
                $this->session->set_flashdata('success', 'Akun pengguna berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui pengguna.');
            }
        }
        
        // Redirect ke daftar user
        redirect('admin/users');
    }
    
    public function delete_user($user_id)
    {
        if ($this->User_model->delete_user($user_id)) {
            $this->session->set_flashdata('success', 'Akun pengguna berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna (mungkin masih terikat Foreign Key).');
        }
        redirect('admin/users');
    }

    public function add_user()
    {
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[tabel_users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        // PERBAIKAN: Mengganti 'tim_lapangan' menjadi 'user'
        $this->form_validation->set_rules('system_role', 'Role Sistem', 'required|in_list[admin,user]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->users();
        }
        else
        {
            $data = array(
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'username'     => $this->input->post('username'),
                'password'     => $this->input->post('password'),
                'system_role'  => $this->input->post('system_role'),
            );
            if ($this->User_model->create_user($data)) {
                $this->session->set_flashdata('success', 'Akun pengguna berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengguna.');
            }
            redirect('admin/users');
        }
    }
    
    // --- 4. Master Peran Tugas (Area 1) ---
    public function roles()
    {
        $data['title'] = 'Master Peran Tugas Tim';
        $data['peran'] = $this->User_model->get_all_peran();
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/master/role_list', $data); 
        $this->load->view('backend/layouts/footer');
    }
    
    public function role_action($action = 'add', $id = NULL)
    {
        $is_unique_rule = 'required|is_unique[tabel_peran_tugas.nama_peran]';
        
        if ($action === 'edit' && $id) {
            $current_role = $this->User_model->get_peran_by_id($id);
            if ($current_role && $current_role->nama_peran === $this->input->post('nama_peran')) {
                 $is_unique_rule = 'required';
            }
        }

        $this->form_validation->set_rules('nama_peran', 'Nama Peran', $is_unique_rule);
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'max_length[500]');
        
        if ($this->form_validation->run() == FALSE)
        {
            if ($action === 'edit' && $id) {
                $data['title'] = 'Edit Peran Tugas';
                $data['peran_data'] = $this->User_model->get_peran_by_id($id);
                if (!$data['peran_data']) {
                    $this->session->set_flashdata('error', 'Peran tidak ditemukan.');
                    redirect('admin/roles');
                }
                
                $data = $this->_get_header_data($data); // <--- TAMBAH
                $this->load->view('backend/layouts/header', $data);
                $this->load->view('backend/master/role_form', $data); 
                $this->load->view('backend/layouts/footer');
            } else {
                $this->session->set_flashdata('error', validation_errors());
                $this->roles();
            }
        }
        else
        {
            $data = array(
                'nama_peran' => $this->input->post('nama_peran'),
                'deskripsi'  => $this->input->post('deskripsi')
            );
            if ($action === 'add') {
                if ($this->User_model->create_peran($data)) {
                    $this->session->set_flashdata('success', 'Peran tugas berhasil ditambahkan.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan peran tugas.');
                }
            } elseif ($action === 'edit' && $id) {
                if ($this->User_model->update_peran($id, $data)) {
                    $this->session->set_flashdata('success', 'Peran tugas berhasil diperbarui.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal memperbarui peran tugas.');
                }
            }
            redirect('admin/roles');
        }
    }

    public function delete_role($id)
    {
        if ($this->User_model->delete_peran($id)) {
            $this->session->set_flashdata('success', 'Peran tugas berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus peran tugas.');
        }
        redirect('admin/roles');
    }

    // --- 5. CRUD Template Itinerary Induk & Item (Area 2) ---
    public function templates()
    {
        $data['title'] = 'Manajemen Template Itinerary';
        $data['templates'] = $this->Template_model->get_all_templates();
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/template/list', $data); 
        $this->load->view('backend/layouts/footer');
    }

    public function template_form($id = NULL)
    {
        $data['template_data'] = NULL;
        $data['mode'] = 'add';
        $data['title'] = 'Buat Template Baru';
        
        if ($id) {
            $data['template_data'] = $this->Template_model->get_template_by_id($id);
            if (!$data['template_data']) {
                $this->session->set_flashdata('error', 'Template tidak ditemukan.');
                redirect('admin/templates');
            }
            $data['mode'] = 'edit';
            $data['title'] = 'Edit Template: ' . $data['template_data']->nama_template;
        }

        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/template/form', $data); 
        $this->load->view('backend/layouts/footer');
    }

    public function template_action($id = NULL)
    {
        $is_unique = ($id) ? 'required' : 'required|is_unique[tabel_template.nama_template]';
        $this->form_validation->set_rules('nama_template', 'Nama Template', $is_unique);
        $this->form_validation->set_rules('lama_manasik', 'Lama Manasik (Hari)', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('lama_perjalanan', 'Lama Perjalanan (Hari)', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->template_form($id);
        }
        else
        {
            $data = array(
                'nama_template'     => $this->input->post('nama_template'),
                'lama_manasik'      => $this->input->post('lama_manasik'),
                'lama_perjalanan'   => $this->input->post('lama_perjalanan')
            );
            if ($id == NULL) { 
                if ($this->Template_model->create_template($data)) {
                    $this->session->set_flashdata('success', 'Template baru berhasil dibuat.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal membuat template baru.');
                }
            } else { 
                 if ($this->Template_model->update_template($id, $data)) {
                    $this->session->set_flashdata('success', 'Template berhasil diperbarui.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal memperbarui template.');
                }
            }
            redirect('admin/templates');
        }
    }
    
    public function delete_template($id)
    {
        if ($this->Template_model->delete_template($id)) {
            $this->session->set_flashdata('success', 'Template berhasil dihapus. Semua item terkait juga dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus template.');
        }
        redirect('admin/templates');
    }
    
    public function template_detail($template_id)
    {
        $template = $this->Template_model->get_template_by_id($template_id);
        if (!$template) {
            $this->session->set_flashdata('error', 'Template tidak ditemukan.');
            redirect('admin/templates');
            return;
        }

        $data['title'] = 'Detail Itinerary: ' . $template->nama_template;
        $data['template'] = $template;
        $data['items'] = $this->Template_model->get_items_by_template($template_id);
        $data['peran'] = $this->User_model->get_all_peran();
        $data['users'] = $this->User_model->get_all_users();

        $data['total_hari_manasik'] = $template->lama_manasik;
        $data['total_hari_perjalanan'] = $template->lama_perjalanan;

        $grouped_items = [];
        foreach ($data['items'] as $item) {
            $key = $item->tipe_blok . '_' . $item->hari_ke;
            if (!isset($grouped_items[$key])) {
                $grouped_items[$key] = [
                    'tipe_blok' => $item->tipe_blok,
                    'hari_ke' => $item->hari_ke,
                    'list' => []
                ];
            }
            $grouped_items[$key]['list'][] = $item;
        }
        $data['grouped_items'] = $grouped_items;

        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/template/detail', $data); 
        $this->load->view('backend/layouts/footer');
    }
    
    // FUNGSI BARU (Point 2): Aksi Edit Item Template
    public function edit_template_item_action($template_id, $item_id)
    {
        $item = $this->Template_model->get_item_by_id($item_id);
        if (!$item || $item->template_id != $template_id) {
            $this->session->set_flashdata('error', 'Item tidak valid.');
            redirect('admin/template_detail/' . $template_id);
            return;
        }

        $this->form_validation->set_rules('tipe_item', 'Jenis Item', 'required|in_list[info,checklist]');
        $this->form_validation->set_rules('deskripsi_item', 'Deskripsi Item', 'required');
        
        // MODIFIKASI: PJ Peran tidak lagi divalidasi karena field dihapus
        $pj_peran_id = $item->pj_peran_id; // Ambil nilai PJ Peran yang sudah ada
        $pj_user_id_default = NULL; 

        if ($this->input->post('tipe_item') == 'checklist') {
            // MODIFIKASI: PJ Peran tetap diambil dari hidden input di view
            $pj_peran_id = $this->input->post('pj_peran_id') ? $this->input->post('pj_peran_id') : $item->pj_peran_id;
            
            // Validasi dan ambil User Default (Pelaksana)
            $pj_user_id_default = $this->input->post('pj_user_id_default') ? $this->input->post('pj_user_id_default') : NULL;
        } else {
            // Jika diubah menjadi Info, set PJ Peran dan User ke NULL
            $pj_peran_id = NULL;
            $pj_user_id_default = NULL;
        }

        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('error_form_edit_' . $item_id, validation_errors());
            redirect('admin/template_detail/' . $template_id);
            return;
        }
        
        $data = array(
            'tipe_item'             => $this->input->post('tipe_item'),
            'deskripsi_item'        => $this->input->post('deskripsi_item'),
            'pj_peran_id'           => $pj_peran_id,
            'pj_user_id_default'    => $pj_user_id_default // Menyimpan Pelaksana
        );

        if ($this->Template_model->update_template_item($item_id, $data)) {
            $this->session->set_flashdata('success', 'Item itinerary berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui item itinerary.');
        }
        
        redirect('admin/template_detail/' . $template_id);
    }
    
    // FUNGSI BARU (Point 3): Aksi Reorder Item
    public function reorder_item($template_id, $item_id, $direction)
    {
        if ($this->Template_model->reorder_template_item($item_id, $direction)) {
            $this->session->set_flashdata('success', 'Posisi item berhasil dipindahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memindahkan posisi item (mungkin sudah di posisi teratas/terbawah).');
        }
        redirect('admin/template_detail/' . $template_id);
    }


    public function add_template_item($template_id)
    {
        $template = $this->Template_model->get_template_by_id($template_id);
        if (!$template) {
            $this->session->set_flashdata('error', 'Template tidak ditemukan.');
            redirect('admin/templates');
        }

        $this->form_validation->set_rules('tipe_blok', 'Blok Perjalanan', 'required|in_list[manasik,perjalanan]');
        $this->form_validation->set_rules('hari_ke', 'Hari Ke', 'required|integer|greater_than_equal_to[1]');
        $this->form_validation->set_rules('tipe_item', 'Jenis Item', 'required|in_list[info,checklist]');
        $this->form_validation->set_rules('deskripsi_item', 'Deskripsi Item', 'required');
        
        $pj_peran_id = NULL;
        $pj_user_id_default = NULL;

        if ($this->input->post('tipe_item') == 'checklist') {
            // MODIFIKASI: PJ Peran akan di set NULL dan ambil Pelaksana
            $pj_user_id_default = $this->input->post('pj_user_id_default') ? $this->input->post('pj_user_id_default') : NULL;
            $pj_peran_id = NULL; // Set NULL karena field sudah dihapus dari view.
        }

        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('error_form', validation_errors());
            redirect('admin/template_detail/' . $template_id);
        }
        else
        {
            $tipe_blok = $this->input->post('tipe_blok');
            $hari_ke = $this->input->post('hari_ke');
            $tipe_item = $this->input->post('tipe_item');

            $max_hari = ($tipe_blok == 'manasik') ? $template->lama_manasik : $template->lama_perjalanan;
            if ($hari_ke > $max_hari) {
                $this->session->set_flashdata('error', 'Hari ke-' . $hari_ke . ' melebihi durasi blok ' . $tipe_blok . ' (' . $max_hari . ' hari).');
                redirect('admin/template_detail/' . $template_id);
                return;
            }
            
            $urutan = $this->Template_model->get_last_urutan($template_id, $tipe_blok, $hari_ke);

            $data = array(
                'template_id'    => $template_id,
                'tipe_blok'      => $tipe_blok,
                'hari_ke'        => $hari_ke,
                'urutan'         => $urutan,
                'tipe_item'      => $tipe_item,
                'deskripsi_item' => $this->input->post('deskripsi_item'),
                'pj_peran_id'    => $pj_peran_id, // NULL jika checklist
                'pj_user_id_default' => $pj_user_id_default // Menyimpan Pelaksana
            );

            if ($this->Template_model->create_template_item($data)) {
                $this->session->set_flashdata('success', 'Item itinerary berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan item itinerary.');
            }
            
            redirect('admin/template_detail/' . $template_id);
        }
    }

    public function delete_template_item($template_id, $item_id)
    {
        $item = $this->Template_model->get_item_by_id($item_id);
        if (!$item || $item->template_id != $template_id) {
            $this->session->set_flashdata('error', 'Item tidak valid.');
            redirect('admin/template_detail/' . $template_id);
            return;
        }

        if ($this->Template_model->delete_template_item($item_id)) {
            $this->session->set_flashdata('success', 'Item itinerary berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus item itinerary.');
        }
        redirect('admin/template_detail/' . $template_id);
    }
    
    // --- 6. CRUD Grup Aktif (Area 3) ---
    public function grup_form($grup_id = NULL)
    {
        // INISIALISASI WAJIB: Atur nilai default untuk mode 'add'
        $data['grup_data'] = NULL;
        $data['penugasan'] = [];
        $data['mode'] = 'add'; 
        $data['penugasan_saat_ini'] = []; 

        if ($grup_id) {
            $grup_data = $this->Grup_model->get_grup_by_id($grup_id);
            if (!$grup_data) {
                $this->session->set_flashdata('error', 'Grup tidak ditemukan.');
                redirect('admin');
                return;
            }
            // Jika mode Edit/Geser Jadwal
            $data['grup_data'] = $grup_data;
            $data['mode'] = 'edit'; 
            $data['title'] = 'Edit Grup: ' . $grup_data->nama_grup; // MODIFIKASI JUDUL
            
            // Ambil penugasan saat ini dan konversi ke Map [peran_id] => user_id
            $penugasan = $this->Grup_model->get_grup_tim_penugasan($grup_id);
            foreach ($penugasan as $p) {
                $data['penugasan_saat_ini'][$p->peran_tugas_id] = $p->user_id;
            }
            
            $data['templates'] = $this->Template_model->get_all_templates();
            $data['peran'] = $this->User_model->get_all_peran();
            $data['users'] = $this->User_model->get_all_users();

            // View edit menggunakan form khusus (edit_form.php)
            $data = $this->_get_header_data($data); // <--- TAMBAH
            $this->load->view('backend/layouts/header', $data);
            $this->load->view('backend/grup/edit_form', $data); 
            $this->load->view('backend/layouts/footer');
            return;
        }

        // Mode Buat Baru
        $data['title'] = 'Buat Grup Perjalanan Baru';
        $data['templates'] = $this->Template_model->get_all_templates();
        $data['peran'] = $this->User_model->get_all_peran();
        $data['users'] = $this->User_model->get_all_users();
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/grup/form', $data); 
        $this->load->view('backend/layouts/footer');
    }

    // Logika Inti: Alur Logika C.1 (Buat Grup)
    public function add_grup_action()
    {
        $template_id = $this->input->post('template_asal_id');
        $template = $this->Template_model->get_template_by_id($template_id);
        
        $this->form_validation->set_rules('nama_grup', 'Nama Grup', 'required');
        $this->form_validation->set_rules('template_asal_id', 'Template Asal', 'required|integer');
        // REVISI: Tambahkan validasi untuk tanggal mulai manasik
        $this->form_validation->set_rules('tanggal_mulai_manasik', 'Tanggal Mulai Manasik', 'required|date');
        $this->form_validation->set_rules('tanggal_keberangkatan', 'Tanggal Keberangkatan', 'required|date');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_form', validation_errors());
            $this->grup_form();
            return;
        }

        if (!$template) {
            $this->session->set_flashdata('error', 'Template tidak ditemukan.');
            redirect('admin/grup_form');
            return;
        }
        
        $tgl_berangkat = $this->input->post('tanggal_keberangkatan');
        $tgl_mulai_manasik = $this->input->post('tanggal_mulai_manasik'); // <-- AMBIL INPUT TANGGAL BARU

        // Hitung Tanggal Pulang (tidak berubah, hanya tergantung lama_perjalanan dari Keberangkatan)
        $tgl_pulang_obj = new DateTime($tgl_berangkat);
        $tgl_pulang_obj->add(new DateInterval('P' . ($template->lama_perjalanan - 1) . 'D'));
        $tgl_pulang = $tgl_pulang_obj->format('Y-m-d');

        $grup_data = [
            'nama_grup' => $this->input->post('nama_grup'),
            'template_asal_id' => $template_id,
            'tanggal_mulai_manasik' => $tgl_mulai_manasik, // <-- SIMPAN TANGGAL BARU
            'tanggal_keberangkatan' => $tgl_berangkat,
            'tanggal_pulang' => $tgl_pulang,
            'status_grup' => 'Aktif'
        ];

        $penugasan_tim = [];
        $peran_tugas = $this->User_model->get_all_peran();

        foreach ($peran_tugas as $peran) {
            $user_id = $this->input->post('user_peran_' . $peran->id);
            if ($user_id && $user_id != '') {
                $penugasan_tim[$peran->id] = $user_id;
            }
        }
        
        $template_items = $this->Template_model->get_all_template_items_by_template_id($template_id); // Gunakan fungsi yang mengembalikan semua item

        $new_grup_id = $this->Grup_model->create_live_group($grup_data, $penugasan_tim, $template_items);

        if ($new_grup_id) {
            $this->session->set_flashdata('success', 'Grup ' . $grup_data['nama_grup'] . ' berhasil dibuat dan item checklist live telah dicetak.');
            redirect('admin/grup_detail/' . $new_grup_id);
        } else {
            $this->session->set_flashdata('error', 'Gagal membuat Grup. Cek log database.');
            redirect('admin/grup_form');
        }
    }
    
    // Logika Inti: Alur Logika C.4 (Edit Grup Lengkap + Geser Massal Jika Tanggal Berubah)
    public function edit_grup_action($grup_id)
    {
        $grup = $this->Grup_model->get_grup_by_id($grup_id);
        if (!$grup) {
            $this->session->set_flashdata('error', 'Grup tidak ditemukan.');
            redirect('admin');
            return;
        }

        // Tambahkan validasi untuk semua field yang bisa diedit
        $this->form_validation->set_rules('nama_grup', 'Nama Grup', 'required');
        $this->form_validation->set_rules('template_asal_id', 'Template Asal', 'required|integer');
        $this->form_validation->set_rules('tanggal_mulai_manasik', 'Tanggal Mulai Manasik', 'required|date');
        $this->form_validation->set_rules('tanggal_keberangkatan', 'Tanggal Keberangkatan Baru', 'required|date');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_form', validation_errors());
            // Gunakan grup_form untuk kembali ke halaman edit
            $this->grup_form($grup_id); 
            return;
        }

        $tanggal_keberangkatan_lama = $grup->tanggal_keberangkatan;
        $template_id_lama = $grup->template_asal_id;
        
        $tanggal_keberangkatan_baru = $this->input->post('tanggal_keberangkatan');
        $tanggal_mulai_manasik_baru = $this->input->post('tanggal_mulai_manasik');
        $template_id_baru = $this->input->post('template_asal_id');

        // 1. Ambil Data Acuan Template BARU
        $template = $this->Template_model->get_template_by_id($template_id_baru);
        if (!$template) {
            $this->session->set_flashdata('error', 'Template asal grup tidak ditemukan.');
            redirect('admin/grup_detail/' . $grup_id);
            return;
        }
        
        // Hitung Tanggal Pulang BARU
        $tgl_pulang_obj = new DateTime($tanggal_keberangkatan_baru);
        $tgl_pulang_obj->add(new DateInterval('P' . ($template->lama_perjalanan - 1) . 'D'));
        $tanggal_pulang_baru = $tgl_pulang_obj->format('Y-m-d');

        // Data Update untuk tabel_grup
        $grup_data_update = [
            'nama_grup' => $this->input->post('nama_grup'),
            'template_asal_id' => $template_id_baru,
            'tanggal_mulai_manasik' => $tanggal_mulai_manasik_baru,
            'tanggal_keberangkatan' => $tanggal_keberangkatan_baru,
            'tanggal_pulang' => $tanggal_pulang_baru
        ];

        // 2. Kumpulkan Data Penugasan Tim
        $penugasan_tim_baru = [];
        $peran_tugas = $this->User_model->get_all_peran();
        foreach ($peran_tugas as $peran) {
            $user_id = $this->input->post('user_peran_' . $peran->id);
            if ($user_id && $user_id != '') {
                $penugasan_tim_baru[$peran->id] = $user_id;
            }
        }
        
        // 3. Eksekusi Update Info Grup dan Tim
        $update_berhasil = $this->Grup_model->update_grup_and_tim($grup_id, $grup_data_update, $penugasan_tim_baru);
        
        if (!$update_berhasil) {
            $this->session->set_flashdata('error', 'Gagal memperbarui info grup dan tim. Terjadi kesalahan transaksi.');
            redirect('admin/grup_detail/' . $grup_id);
            return;
        }
        
        $perlu_geser_massal = ($tanggal_keberangkatan_baru != $tanggal_keberangkatan_lama || $template_id_baru != $template_id_lama);

        if ($perlu_geser_massal) {
             // Jika tanggal keberangkatan atau template berubah, lakukan pembaruan massal item checklist
             $listTemplateItemMaster = $this->Template_model->get_all_template_items_by_template_id($template_id_baru);
             $mapTemplateItems = [];
             foreach ($listTemplateItemMaster as $itemMaster) {
                 $mapTemplateItems[$itemMaster->id] = $itemMaster;
             }
             
             if ($this->Grup_model->handle_penundaan_grup(
                $grup_id, 
                $tanggal_keberangkatan_baru, 
                $template, 
                $mapTemplateItems
            )) {
                $this->session->set_flashdata('success', 'Grup ' . $grup->nama_grup . ' berhasil diperbarui dan jadwal live checklist telah digeser.');
            } else {
                $this->session->set_flashdata('warning', 'Info grup dan tim berhasil diperbarui, NAMUN gagal menggeser jadwal live checklist. Hubungi teknisi.');
            }
        } else {
             $this->session->set_flashdata('success', 'Grup ' . $grup->nama_grup . ' berhasil diperbarui.');
        }

        redirect('admin/grup_detail/' . $grup_id);
    }
    
    public function delete_grup($grup_id)
    {
        $grup = $this->Grup_model->get_grup_by_id($grup_id);
        if (!$grup) {
            $this->session->set_flashdata('error', 'Grup tidak ditemukan.');
            redirect('admin');
            return;
        }
        
        if ($this->Grup_model->delete_grup($grup_id)) {
            $this->session->set_flashdata('success', 'Grup ' . $grup->nama_grup . ' berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus grup.');
        }
        redirect('admin/monitoring'); // Redirect ke halaman monitoring yang baru
    }
    
    // --- 7. Detail Grup (Tampilan Monitoring) ---
    public function grup_detail($grup_id)
    {
        $grup = $this->Grup_model->get_grup_by_id($grup_id);
        if (!$grup) {
            $this->session->set_flashdata('error', 'Grup tidak ditemukan.');
            redirect('admin/monitoring'); // Redirect ke halaman monitoring yang baru
            return;
        }

        $data['title'] = 'Monitoring Grup: ' . $grup->nama_grup;
        $data['grup'] = $grup;
        $data['penugasan'] = $this->Grup_model->get_grup_tim_penugasan($grup_id);
        
        // --- MODIFIKASI: Ambil data durasi template dan kelompokkan ulang item ---
        $template = $this->Template_model->get_template_by_id($grup->template_asal_id);
        if ($template) {
            $data['lama_manasik'] = $template->lama_manasik;
            $data['lama_perjalanan'] = $template->lama_perjalanan;
        } else {
            $data['lama_manasik'] = 0;
            $data['lama_perjalanan'] = 0;
        }
        
        $live_items = $this->Grup_model->get_live_checklist($grup_id);
        $grouped_items_by_day_block = [];
        
        foreach ($live_items as $item) {
            // Grouping berdasarkan tipe_blok dan hari_ke
            $key = $item->tipe_blok . '_' . $item->hari_ke;
            if (!isset($grouped_items_by_day_block[$key])) {
                $grouped_items_by_day_block[$key] = [
                    'tipe_blok' => $item->tipe_blok,
                    'hari_ke' => $item->hari_ke,
                    'tanggal_item' => $item->tanggal_item, // Simpan tanggal
                    'list' => []
                ];
            }
            $grouped_items_by_day_block[$key]['list'][] = $item;
        }
        $data['grouped_items'] = $grouped_items_by_day_block;
        
        // Tambahkan variabel untuk color mapping (sama seperti di template/detail.php)
        $data['sneat_day_colors'] = [
            'bg-label-info',    
            'bg-label-success', 
            'bg-label-warning', 
            'bg-label-primary', 
            'bg-label-secondary', 
            'bg-label-danger'   
        ];
        // --- END MODIFIKASI ---
        
        $data['peran'] = $this->User_model->get_all_peran(); 
        $data['users'] = $this->User_model->get_all_users();
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/grup/detail', $data); 
        $this->load->view('backend/layouts/footer');
    }
    
    // --- FUNGSI BARU: Aksi Edit Item Live Checklist (di Grup) ---
    public function edit_grup_item_action($grup_id, $item_id)
    {
        // Pastikan Grup dan Item ditemukan dan ambil detailnya
        $grup = $this->Grup_model->get_grup_by_id($grup_id);
        // Asumsi Grup_model memiliki fungsi untuk mengambil detail item grup
        $item = $this->Grup_model->get_grup_item_detail($item_id); 
        
        if (!$grup || !$item || $item->grup_id != $grup_id) {
            $this->session->set_flashdata('error', 'Grup atau Item tidak valid.');
            redirect('admin/grup_detail/' . $grup_id);
            return;
        }

        $this->form_validation->set_rules('tipe_item', 'Jenis Item', 'required|in_list[info,checklist]');
        $this->form_validation->set_rules('deskripsi_item', 'Deskripsi Item', 'required');
        
        // MODIFIKASI: PJ Peran tidak lagi divalidasi dan diambil dari nilai lama/hidden field
        $pj_peran_id = $item->peran_tugas_id; 
        $pj_user_id = NULL; 

        if ($this->input->post('tipe_item') == 'checklist') {
            $pj_peran_id = $this->input->post('pj_peran_id') ? $this->input->post('pj_peran_id') : $item->peran_tugas_id;
            
            // Mengambil User PJ yang baru (Pelaksana)
            $pj_user_id = $this->input->post('pj_user_id') ? $this->input->post('pj_user_id') : NULL;
        } else {
            // Jika diubah menjadi Info, set PJ Peran dan User ke NULL
            $pj_peran_id = NULL;
            $pj_user_id = NULL;
        }

        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('error_form_edit_' . $item_id, validation_errors());
            redirect('admin/grup_detail/' . $grup_id);
            return;
        }
        
        // Data yang di-update di item live checklist (tabel_grup_item)
        $data = array(
            'tipe_item'             => $this->input->post('tipe_item'),
            'deskripsi'             => $this->input->post('deskripsi_item'), 
            'peran_tugas_id'        => $pj_peran_id, 
            'pj_user_id'            => $pj_user_id 
        );

        // Asumsikan ada fungsi update_grup_item di Grup_model
        if ($this->Grup_model->update_grup_item($item_id, $data)) {
            $this->session->set_flashdata('success', 'Item live checklist berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui item live checklist.');
        }
        
        redirect('admin/grup_detail/' . $grup_id);
    }
    
    // FUNGSI BARU: Tambah Item Live Checklist dari Admin
    public function add_grup_item($grup_id)
    {
        $grup = $this->Grup_model->get_grup_by_id($grup_id);
        if (!$grup) {
            $this->session->set_flashdata('error', 'Grup tidak ditemukan.');
            redirect('admin/monitoring');
            return;
        }

        $this->form_validation->set_rules('tipe_blok', 'Blok Perjalanan', 'required|in_list[manasik,perjalanan]');
        $this->form_validation->set_rules('hari_ke', 'Hari Ke', 'required|integer|greater_than_equal_to[1]');
        $this->form_validation->set_rules('tanggal_item', 'Tanggal Item', 'required|date');
        $this->form_validation->set_rules('tipe_item', 'Jenis Item', 'required|in_list[info,checklist]');
        $this->form_validation->set_rules('deskripsi_item', 'Deskripsi Item', 'required');
        
        $pj_peran_id = NULL;
        $pj_user_id = NULL;

        if ($this->input->post('tipe_item') == 'checklist') {
            // MODIFIKASI: PJ Peran di set NULL dan ambil Pelaksana
            $pj_user_id = $this->input->post('pj_user_id_default') ? $this->input->post('pj_user_id_default') : NULL;
            $pj_peran_id = NULL; 
        }

        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('error_form', validation_errors());
            redirect('admin/grup_detail/' . $grup_id);
            return;
        }
        else
        {
            $tanggal_item = $this->input->post('tanggal_item');
            // Gunakan fungsi baru dari Grup_model untuk mendapatkan urutan terakhir
            $urutan = $this->Grup_model->get_last_urutan_grup($grup_id, $tanggal_item); 

            $data = array(
                'grup_id'           => $grup_id,
                'tipe_blok'         => $this->input->post('tipe_blok'),
                'hari_ke'           => $this->input->post('hari_ke'),
                'tanggal_item'      => $tanggal_item,
                'urutan'            => $urutan,
                'tipe_item'         => $this->input->post('tipe_item'),
                'deskripsi'         => $this->input->post('deskripsi_item'),
                'peran_tugas_id'    => $pj_peran_id, // NULL
                'pj_user_id'        => $pj_user_id, // Pelaksana
                'status'            => 'Pending' // Set default status
            );

            // Gunakan fungsi baru dari Grup_model untuk membuat item live
            if ($this->Grup_model->create_grup_item_live($data)) {
                $this->session->set_flashdata('success', 'Item live checklist berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan item live checklist.');
            }
            
            redirect('admin/grup_detail/' . $grup_id);
        }
    }
    
    // --- FUNGSI BARU: Aksi Reorder Item Live Checklist ---
    public function reorder_grup_item($grup_id, $item_id, $direction)
    {
        // Asumsikan ada fungsi reorder_grup_item di Grup_model
        if ($this->Grup_model->reorder_grup_item($item_id, $direction)) {
            $this->session->set_flashdata('success', 'Posisi item live checklist berhasil dipindahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memindahkan posisi item live checklist (mungkin sudah di posisi teratas/terbawah).');
        }
        redirect('admin/grup_detail/' . $grup_id);
    }
    
    // --- FUNGSI BARU: Hapus Item Live Checklist ---
    public function delete_grup_item($grup_id, $item_id)
    {
        // Pastikan Grup dan Item ditemukan dan ambil detailnya
        $grup = $this->Grup_model->get_grup_by_id($grup_id);
        $item = $this->Grup_model->get_grup_item_detail($item_id); // Asumsikan ada fungsi ini
        
        if (!$grup || !$item || $item->grup_id != $grup_id) {
            $this->session->set_flashdata('error', 'Grup atau Item tidak valid.');
            redirect('admin/grup_detail/' . $grup_id);
            return;
        }
        
        // Asumsikan ada fungsi delete_grup_item di Grup_model
        if ($this->Grup_model->delete_grup_item($item_id)) {
            $this->session->set_flashdata('success', 'Item live checklist berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus item live checklist.');
        }
        redirect('admin/grup_detail/' . $grup_id);
    }

    // --- 8. Notifikasi & Laporan (Area 4) ---
    public function notifications()
    {
        $data['title'] = 'Notifikasi Tugas Buruk/Gagal';
        $admin_id = $this->session->userdata('user_id');
        
        // Tandai semua notifikasi sebagai sudah dibaca
        $this->Notifikasi_model->mark_all_as_read($admin_id);

        // Ambil semua notifikasi untuk ditampilkan di halaman
        $data['notifications'] = $this->Notifikasi_model->get_all_notifications_for_admin($admin_id);

        $data = $this->_get_header_data($data); // <--- TAMBAH (Walau count pasti 0)
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/laporan/notifications', $data);
        $this->load->view('backend/layouts/footer');
    }
    
    // --- FUNGSI BARU UNTUK AJAX: Tandai Notifikasi Individu Sudah Dibaca ---
    public function mark_notification_read()
    {
        // Pastikan ini adalah request AJAX dan user adalah admin
        if ($this->input->is_ajax_request() && $this->session->userdata('system_role') === 'admin') {
            $notif_id = $this->input->post('notif_id');
            
            if ($notif_id && is_numeric($notif_id)) {
                $success = $this->Notifikasi_model->mark_as_read_individual($notif_id);
                
                $response = array(
                    'success' => $success,
                    'unread_count' => $this->Notifikasi_model->get_unread_count($this->session->userdata('user_id'))
                );
                $this->output
                     ->set_content_type('application/json')
                     ->set_output(json_encode($response));
                return;
            }
        }
        
        $this->output
             ->set_status_header(400)
             ->set_output(json_encode(array('success' => FALSE, 'message' => 'Invalid request or not logged in.')));
    }


    public function laporan_kinerja()
    {
        $data['title'] = 'Laporan Kinerja & Evaluasi';

        $this->load->model('Report_model');
        $data['reports'] = $this->Report_model->get_laporan_kinerja();
        
        $data = $this->_get_header_data($data); // <--- TAMBAH
        $this->load->view('backend/layouts/header', $data);
        $this->load->view('backend/laporan/kinerja', $data);
        $this->load->view('backend/layouts/footer');
    }
}