<?= $this->session->flashdata('pesan'); ?>
 
 <div style="padding: 25px;">
    <div class="x_panel">
        <div class="x_content">
            <!-- Tampilkan semua produk -->
            <div class="row">
                <!-- looping products -->
                <?php foreach ($bukus as $buku) { ?>
                    <div class="col-md-2 col-md-3 mb-5 pb-2">
                        <div class="thumbnail" style="height: 370px;">
                            <img src="<?php echo base_url(); ?>assets/img/upload/<?= $buku->image; ?>" style="max-width:100%; max-height: 100%; height: 200px; width: 180px">
                        <div class="caption">
                        <h6 style="min-height:30px; margin-top:10px; text-align:center;"><?= $buku->judul_buku ?></h6>
                        <p style="min-height:30px; margin-bottom:0px;"><?= $buku->pengarang ?></p>
                        <p style="min-height:30px; margin-bottom:0px;"><?= $buku->penerbit ?></p>
                        <p style="min-height:30px; "><?= substr($buku->tahun_terbit, 0, 4) ?></p>
                        <p>
                        <?php
                        if ($buku->stok < 1) { 
                        echo "<i class='btn btn-outline-primary fas fw fa-shopping-cart'> Booking&nbsp;&nbsp 0</i>"; 
                        } else { 
                        echo "<a class='btn btn-outline-primary fas fw fa-shopping-cart' href='" . base_url('booking/tambahBooking/' . $buku->id) . "'> Booking</a>"; 
                        } 
                        ?>
                        <a class="btn btn-outline-warning fas fw fa-search" href="<?= base_url('home/detailBuku/' . $buku->id); ?>"> Detail</a></p>
                    </div>
                </div>
            </div> <?php } ?>
            <!-- end looping -->
            </div>
        </div>
    </div>
 </div>