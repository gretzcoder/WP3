<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    function index()
    {
        $data = [
            'judul' => "Katalog Buku",
            'bukus' => $this->ModelBuku->getBuku()->result()
        ];

        //cek udah login belom
        if ($this->session->userdata('email')) {
            $user = $this->ModelUser->CekData(['email' => $this->session->userdata('email')])->row_array();

            $data['user'] = $user['nama'];

            $this->load->view('templates/templates-user/header', $data);
            $this->load->view('buku/daftarbuku', $data);
            $this->load->view('templates/templates-user/footer', $data);
        } else {
            $data['user'] = 'Pengunjung';

            $this->load->view('templates/templates-user/header', $data);
            $this->load->view('buku/daftarbuku', $data);
            $this->load->view('templates/templates-user/modal', $data);
            $this->load->view('templates/templates-user/footer', $data);
        }
    }

    function detailBuku($id)
    {
        $buku = $this->ModelBuku->joinKategoriBuku(['buku.id' => $id])->result()[0];
        $data = [
            'judul' => "Detail Buku",
            'user' => "Pengunjung",
            'buku' => $buku
        ];
        // var_dump($buku);die();
        $this->load->view('templates/templates-user/header', $data);
        $this->load->view('buku/detail-buku', $data);
        $this->load->view('templates/templates-user/modal', $data);
        $this->load->view('templates/templates-user/footer');
    }
}
