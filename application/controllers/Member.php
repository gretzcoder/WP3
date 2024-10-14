<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller
{
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->_login();
    }
    private function _login()
    {
        $email = htmlspecialchars($this->input->post('email', true));
        $password = $this->input->post('password', true);

        $user = $this->ModelUser->cekData(['email' => $email])->row_array();

        //jika usernya ada
        if ($user) {
            //jika user sudah aktif
            if ($user['is_active'] == 1) {
                //cek password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];

                    $this->session->set_userdata($data);
                    redirect('home');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Password salah!!</div>');
                    redirect('home');
                }
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">User belum diaktifasi!!</div>');
                redirect('home');
            }
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar!!</div>');
            redirect('home');
        }
    }

    function daftar() {
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        //membuat rule untuk inputan nama agar tidak boleh kosong dengan membuat pesan error dengan 
        //bahasa sendiri yaitu 'Nama Belum diisi'
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required', [
            'required' => 'Nama Belum diis!!'
        ]);
        //membuat rule untuk inputan email agar tidak boleh kosong, tidak ada spasi, format email harus valid
        //dan email belum pernah dipakai sama user lain dengan membuat pesan error dengan bahasa sendiri 
        //yaitu jika format email tidak benar maka pesannya 'Email Tidak Benar!!'. jika email belum diisi,
        //maka pesannya adalah 'Email Belum diisi', dan jika email yang diinput sudah dipakai user lain,
        //maka pesannya 'Email Sudah dipakai'
        $this->form_validation->set_rules('email', 'Alamat Email', 'required|trim|valid_email|is_unique[user.email]', [
            'valid_email' => 'Email Tidak Benar!!',
            'required' => 'Email Belum diisi!!',
            'is_unique' => 'Email Sudah Terdaftar!'
        ]);
        //membuat rule untuk inputan password agar tidak boleh kosong, tidak ada spasi, tidak boleh kurang dari
        //dari 3 digit, dan password harus sama dengan repeat password dengan membuat pesan error dengan  
        //bahasa sendiri yaitu jika password dan repeat password tidak diinput sama, maka pesannya
        //'Password Tidak Sama'. jika password diisi kurang dari 3 digit, maka pesannya adalah 
        //'Password Terlalu Pendek'.
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'Password Tidak Sama!!',
            'min_length' => 'Password Terlalu Pendek'
        ]);
        $this->form_validation->set_rules('password2', 'Repeat Password', 'required|trim|matches[password1]');
        //jika jida disubmit kemudian validasi form diatas tidak berjalan, maka akan tetap berada di
        //tampilan registrasi. tapi jika disubmit kemudian validasi form diatas berjalan, maka data yang 
        //diinput akan disimpan ke dalam tabel user
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Daftar Gagal, ada isian yang salah !</div>');
            redirect('home');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'tanggal_input' => time()
            ];

            $this->ModelUser->simpanData($data); //menggunakan model

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Selamat!! akun member anda sudah dibuat. Silahkan Login</div>');
            redirect('home');
        }
    }

    function profile() {
        $data['judul'] = 'Profil Saya'; 
        $user = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array(); 
        foreach ($user as $a) { 
        $data = [ 
        'image' => $user['image'],
        'user' => $user['nama'], 
        'email' => $user['email'], 
        'tanggal_input' => $user['tanggal_input'], 
        ]; 
        } 
        $this->load->view('templates/templates-user/header', $data); 
        $this->load->view('member/index', $data); 
        $this->load->view('templates/templates-user/modal'); 
        $this->load->view('templates/templates-user/footer', $data); 
    }

    function ubahProfil() {
        $data['judul'] = "Ubah Profile";
        $user = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        
        foreach ($user as $a) { 
            $data = [ 
                'image' => $user['image'], 
                'user' => $user['nama'], 
                'email' => $user['email'], 
                'tanggal_input' => $user['tanggal_input'], 
            ]; 
        }

        $this->form_validation->set_rules('halo', 'Nama Lengkap', 'required|trim', ['required' => 'Nama tidak Boleh Kosong']);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/templates-user/header', $data); 
            $this->load->view('member/ubah-anggota', $data); 
            $this->load->view('templates/templates-user/modal'); 
            $this->load->view('templates/templates-user/footer', $data); 
        } else{
            $nama = $this->input->post('halo', true);
            $email = $this->input->post('email', true);

            //jika ada gambar yang akan diupload
            $upload_image = $_FILES['image']['name']; 
            if (!$upload_image) { 
            $config['upload_path'] = './assets/img/profile/'; 
            $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
            $config['max_size'] = '3000'; 
            $config['max_width'] = '1024'; 
            $config['max_height'] = '1000'; 
            $config['file_name'] = 'pro' . time(); 
            $this->load->library('upload', $config); 

            if (!$this->upload->do_upload('image')) { 
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">'.$error['error'].'</div>'); 
                
                $this->load->view('templates/templates-user/header', $data); 
                $this->load->view('member/ubah-anggota', $data); 
                $this->load->view('templates/templates-user/modal'); 
                $this->load->view('templates/templates-user/footer', $data);
            } else { 
                $gambar_lama = $data['user']['image']; 
                if ($gambar_lama != 'default.jpg') { 
                unlink(FCPATH . 'assets/img/profile/' . $gambar_lama); 
                } 
                $gambar_baru = $this->upload->data('file_name'); 
                $this->db->set('image', $gambar_baru);   
            } 
        } else {
            $this->db->set('nama', $nama); 
            $this->db->where('email', $email); 
            $this->db->update('user');
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Profil Berhasil diubah </div>'); 
            redirect('member/profile'); 
        }
    }
}

    function logout() 
        { 
            $this->session->unset_userdata('email'); 
            $this->session->unset_userdata('role_id'); 
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Anda telah logout!!</div>'); 
            redirect('home'); 
        }
    }