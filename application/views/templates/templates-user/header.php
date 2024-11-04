<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $judul ?></title>
    <link href="<?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/user/css/bootstrap.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url(); ?>">PUSTAKA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active" href="<?= base_url(); ?>">Beranda <span class="sr-only">(current)</span></a>
                    <?php if (!empty($this->session->userdata('email'))) { ?>
                        <?php $id_user = $this->session->userdata('id'); ?>
                        <?php if ($this->db->query("select*from booking bo, booking_detail d, buku bu where d.id_booking=bo.id_booking and d.id_buku=bu.id and bo.id_user='$id_user'")->num_rows() > 0) { ?>
                            <a class="nav-item nav-link" href="<?= base_url(); ?>booking/info">History Booking</b></a>
                        <?php } else { ?>
                            <a class="nav-item nav-link" href="<?= base_url(); ?>booking">Booking <b> <?= $this->ModelBooking->getDataWhere('temp', ['email_user' => $this->session->userdata('email')])->num_rows(); ?> Buku </b></a>
                        <?php } ?>
                        <a class="nav-item nav-link" href="<?= base_url(); ?>member/profile">Profile Saya</a>
                        <a class="nav-item nav-link" href="<?= base_url(); ?>autentifikasi/logout">Logout</a>
                        <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Selamat Datang <?= $user ?></a>
                    <?php } else { ?>
                        <a class="nav-item nav-link" data-toggle="modal" data-target="#loginModal" href="#">Login</a>
                        <a class="nav-item nav-link" href="#" data-toggle="modal" data-target="#daftarModal">Daftar</a>
                        <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Selamat Datang Pengunjung</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-2">