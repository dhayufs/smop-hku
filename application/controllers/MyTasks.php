<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// === NEW: DEFINE format_indo_date GLOBALLY TO PREVENT ERROR ===
if (!function_exists('format_indo_date')) {
    function format_indo_date($date_string) {
        // Daftar nama hari dan bulan dalam Bahasa Indonesia
        $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        
        // Konversi string tanggal ke timestamp
        $date = strtotime($date_string);
        // Ambil index hari (0=Minggu, 6=Sabtu)
        $w = date('w', $date);
        // Ambil tanggal
        $tgl = date('d', $date);
        // Ambil index bulan (1-12)
        $bln = date('n', $date);
        // Ambil tahun
        $thn = date('Y', $date);
        
        // Gabungkan dan kembalikan format: Hari, DD Bulan YYYY
        return $hari[$w] . ', ' . $tgl . ' ' . $bulan[$bln] . ' ' . $thn;
    }
}
// ======================================================================

class MyTasks extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // --- START: SET TIMEZONE WIB ---
        date_default_timezone_set('Asia/Jakarta');
        // --- END: SET TIMEZONE WIB ---
        $this->load->model('Grup_model');
        $this->load->model('Report_model');
        $this->load->model('User_model'); // LOAD USER MODEL
        $this->load->library('form_validation');
        $this->load->helper('date'); // Load Helper Date untuk format tanggal

        // Validasi Akses Tim Lapangan (system_role !== 'user')
        if (!$this->session->userdata('logged_in') || $this->session->userdata('system_role') !== 'user') {
            redirect('auth');
        }
    }

    // --- 1. Tampilan Daftar Grup (My Tasks Index) - Point 2 & 4 ---
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Dashboard Tugas Tim Lapangan';
        
        // Ambil daftar grup yang ditugaskan ke user ini
        $data['assigned_groups'] = $this->Grup_model->get_user_assigned_groups($user_id);
        
        // Ambil data penugasan untuk ditampilkan di kartu
        foreach ($data['assigned_groups'] as $group) {
            $group->penugasan = $this->Grup_model->get_grup_tim_penugasan($group->grup_id);
        }
        
        // LANGKAH PENTING: Ambil semua peran dari database
        $all_peran = $this->User_model->get_all_peran(); 
        $data['all_peran_names'] = array_column($all_peran, 'nama_peran'); // Ambil array nama-nama peran
        
        // REVISI: Menggunakan template frontend (Bukan lagi backend)
        $this->load->view('frontend/layouts/header', $data); // DIUBAH
        $this->load->view('frontend/mytasks/index', $data); // View Utama yang diubah
        $this->load->view('frontend/layouts/footer'); // DIUBAH
    }
    
    // --- 2. Tampilan Detail Grup (Tugas Per Tanggal) - Point 3 & 4 ---
    public function grup_detail($grup_id)
    {
        $user_id = $this->session->userdata('user_id');
        
        $grup = $this->Grup_model->get_grup_by_id($grup_id);
        if (!$grup) {
            $this->session->set_flashdata('error', 'Grup tidak ditemukan.');
            redirect('mytasks');
            return;
        }

        // Pastikan user ini bagian dari grup sebelum menampilkan
        if (!$this->Grup_model->is_user_assigned_to_group($user_id, $grup_id)) {
            $this->session->set_flashdata('error', 'Anda tidak ditugaskan ke grup ini.');
            redirect('mytasks');
            return;
        }

        $data['title'] = 'Detail Tugas Grup: ' . $grup->nama_grup;
        $data['grup'] = $grup;
        $data['penugasan'] = $this->Grup_model->get_grup_tim_penugasan($grup_id);
        
        // Ambil detail checklist, dikelompokkan oleh Model
        $grouped_items = $this->Grup_model->get_live_checklist_grouped($grup_id);
        $data['grouped_items'] = $grouped_items;
        
        // Ambil tanggal terakhir diupdate dari session (Langkah 3)
        $data['tanggal_target'] = $this->session->flashdata('tanggal_target') ?? NULL;

        // REVISI: Menggunakan template frontend (Bukan lagi backend)
        $this->load->view('frontend/layouts/header', $data); // DIUBAH
        $this->load->view('frontend/mytasks/grup_detail_tasks', $data); // View Detail Baru
        $this->load->view('frontend/layouts/footer'); // DIUBAH
    }

    // --- 3. Tampilan Form Update Tugas (Bukan Modal) - Point 5 ---
    public function update_task_form($grup_item_id)
    {
        $user_id = $this->session->userdata('user_id');
        $item = $this->Grup_model->get_grup_item_detail($grup_item_id);
        
        // Item harus ditugaskan ke user ini (pj_user_id) atau user ini adalah anggota tim yang bertugas (peran)
        // Kita modifikasi logika otorisasi di sini:
        // Cek 1: Apakah user ini adalah PJ langsung?
        $is_direct_pj = ($item && $item->pj_user_id == $user_id);

        // Cek 2: Apakah user ini ditugaskan ke peran yang sama dengan item?
        $is_assigned_to_role = FALSE;
        if ($item && $item->peran_tugas_id) {
            $grup_id = $item->grup_id;
            $penugasan_user = $this->Grup_model->get_grup_tim_penugasan($grup_id);
            
            foreach ($penugasan_user as $p) {
                if ($p->user_id == $user_id && $p->peran_tugas_id == $item->peran_tugas_id) {
                    $is_assigned_to_role = TRUE;
                    break;
                }
            }
        }
        
        if (!$item || (!$is_direct_pj && !$is_assigned_to_role)) {
            $this->session->set_flashdata('error', 'Akses ditolak: Anda bukan Pelaksana tugas ini.');
            redirect('mytasks'); 
            return;
        }

        $grup = $this->Grup_model->get_grup_by_id($item->grup_id);

        $data['title'] = 'Aksi Tugas: ' . $item->deskripsi;
        $data['item'] = $item;
        $data['grup'] = $grup;
        
        // Ambil Log Riwayat (tabel_riwayat_item)
        $data['riwayat'] = $this->Grup_model->get_item_history_with_user($grup_item_id);

        // REVISI: Menggunakan template frontend (Bukan lagi backend)
        $this->load->view('frontend/layouts/header', $data); // DIUBAH
        $this->load->view('frontend/mytasks/update_task_form', $data); // View Form Update Baru
        $this->load->view('frontend/layouts/footer'); // DIUBAH
    }
    
    // --- 4. Logika Update Checklist (Alur C.2) ---
    public function update_task()
    {
        $user_id = $this->session->userdata('user_id');
        $grup_item_id = $this->input->post('grup_item_id');
        $grup_id = $this->input->post('grup_id'); // Untuk redirect
        $new_status = $this->input->post('status');
        $old_foto_path = $this->input->post('old_foto_path');
        $catatan = $this->input->post('catatan');

        $this->form_validation->set_rules('grup_item_id', 'Item Tugas', 'required|integer');
        $this->form_validation->set_rules('status', 'Status Eksekusi', 'required|in_list[Sukses,Cukup,Buruk,Gagal]');
        $this->form_validation->set_rules('catatan', 'Catatan', 'max_length[500]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('mytasks/update_task_form/' . $grup_item_id); 
            return;
        }

        // --- Proses Upload Foto ---
        $new_foto_path = NULL;
        if (!empty($_FILES['foto_bukti']['name'])) {
            
            $config['upload_path']   = './assets/uploads/bukti/'; 
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 5000; // Maks 5MB
            $config['file_name']     = 'bukti_' . $grup_item_id . '_' . time();
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config); // Inisialisasi ulang

            if ($this->upload->do_upload('foto_bukti')) {
                $upload_data = $this->upload->data();
                $new_foto_path = $config['upload_path'] . $upload_data['file_name'];
                
                // Hapus foto lama jika ada upload baru
                if ($old_foto_path && file_exists($old_foto_path)) {
                    // unlink($old_foto_path); // Nonaktifkan untuk demo/simulasi
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal upload foto: ' . $this->upload->display_errors('', ''));
                redirect('mytasks/update_task_form/' . $grup_item_id);
                return;
            }
        } else {
            // Jika user tidak upload, gunakan foto lama dari input hidden
            $new_foto_path = $old_foto_path;
        }
        
        // --- Eksekusi Logika Update (Alur C.2) ---
        $result = $this->Report_model->update_checklist_item($user_id, $grup_item_id, $new_status, $new_foto_path, $catatan);

        if ($result === TRUE) {
            // LANGKAH PENTING 1: Ambil tanggal tugas yang baru saja diupdate
            $updated_item = $this->Grup_model->get_grup_item_detail($grup_item_id);
            if ($updated_item) {
                // Simpan tanggal item ke flashdata untuk digunakan di view detail
                $this->session->set_flashdata('tanggal_target', $updated_item->tanggal_item);
            }
            
            $this->session->set_flashdata('success', 'Status tugas berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', $result); // Pesan error dari Model (Otorisasi, dll.)
        }
        
        // Redirect ke detail grup setelah update
        redirect('mytasks/grup_detail/' . $grup_id);
    }
}