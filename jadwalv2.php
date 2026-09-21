<?php
require 'function.php';

function kategoriJadwalPhotoshoot($package){
    $package = strtolower(trim((string)$package));
    $khusus = array('self photo', 'selfphoto', 'pas foto', 'pasfoto', 'cetak foto');
    return in_array($package, $khusus, true) ? 'self-pasfoto' : 'photo-studio';
}

?>
<!doctype html>
<html lang="en">
  <head>
 <!-- Favicons -->
  <link href="img/logo.png" rel="icon">
  <link href="img/logo.png" rel="apple-touch-icon">
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- As a heading -->
    <title>Rizal Foto Studio</title>
    
  <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet" />

    <style>
      .jadwal-page-shell {
        width: 100%;
        max-width: 1150px;
        margin-left: auto;
        margin-right: auto;
      }
      .jadwal-panel {
        width: 100%;
      }
      .jadwal-filter-bar {
        width: 100%;
      }
      .jadwal-table-scroll {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
      }
      .jadwal-table-scroll .table {
        min-width: 920px;
        margin-bottom: 0;
      }
      @media (max-width: 767.98px) {
        .navbar .h1 {
          font-size: 1.25rem !important;
          margin-left: .5rem !important;
        }
        .row.mx-auto[style*="max-width:500px"] {
          width: 100%;
          max-width: 100% !important;
        }
        .std-kab,
        .std-kot {
          font-size: 13px;
          padding-left: 6px;
          padding-right: 6px;
        }
        .jadwal-filter-bar {
          flex-wrap: nowrap !important;
          justify-content: flex-start !important;
          overflow-x: auto;
          padding-bottom: 5px;
          -webkit-overflow-scrolling: touch;
        }
        .jadwal-filter {
          flex: 0 0 auto;
          white-space: nowrap;
        }
        .jadwal-table-scroll {
          width: calc(100% + 24px);
          margin-left: -12px;
          margin-right: -12px;
        }
        .jadwal-table-scroll .table {
          min-width: 920px;
          font-size: 13px;
        }
      }
    </style>
  </head>
  <body>
    <!-- Ini adalah awal navbar header -->
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
         <div class="container d-flex justify-content-center" >
          <a href="index.php" class="text-dark" style="text-decoration:none;">
             <img src="img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
             <span class="navbar-light h1 ms-4 align-middle fw-bold" style=" margin-top: 15px; ">Rizal Foto Studio</span>
          </a>
        </div>
    </nav>
    <!-- Ini adalah akhir navbar header -->
    <?php
      $jadwalkabupaten = mysqli_query($koneksi, "SELECT id FROM data_konfirmasi_cobahampirfinishjuga WHERE studio = '' OR studio ='Bekasi-Kabupaten' UNION ALL SELECT id FROM data_booking_cobahampirfinishjuga WHERE studio = '' OR studio ='Bekasi-Kabupaten' ");
      $adaKabupaten = mysqli_num_rows($jadwalkabupaten) > 0;

    

    ?>
    <!-- Ini adalah awal body1 -->
    <section class="jumbotron" >
          <!-------------------------------Tabell----------------------------------------------------->
  <div class="container">
    <div class="row m-1 text-center">
            <h2>Jadwal Photoshoot</h2>
          </div>
          <div class="row mx-auto mt-4" style="max-width:500px">
            <div class="d-flex">
              <div class="flex-fill">
                <a type="button" class="btn btn-outline-dark std-kab active" style="width:100%;border-radius:0px" onclick="kabbtn()">Studio Bekasi Kabupaten</a>
              </div>
              <div class="flex-fill">
                <a type="button" class="btn btn-outline-dark std-kot" style="width:100%;border-radius:0px" onclick="kotabtn()">Studio Bekasi Kota</a>
              </div>
            </div>
          </div>
    <div class="jadwal-page-shell">
          
      <div id="bekasi-kab" class="jadwal-panel" data-jadwal-container="kab">
        <div class="jadwal-filter-bar d-flex flex-wrap justify-content-center gap-2 mt-4 mb-3" data-jadwal-filter-group="kab">
          <button type="button" class="btn btn-dark btn-sm jadwal-filter active" data-jadwal-group="kab" data-jadwal-filter="all">Semua</button>
          <button type="button" class="btn btn-outline-dark btn-sm jadwal-filter" data-jadwal-group="kab" data-jadwal-filter="photo-studio">Photo Studio</button>
          <button type="button" class="btn btn-outline-dark btn-sm jadwal-filter" data-jadwal-group="kab" data-jadwal-filter="self-pasfoto">Self Photo + Pas Foto</button>
        </div>
        <div class="jadwal-table-scroll">
        <?php
          if ($adaKabupaten){?>
        <table  class="table border table-striped table-hover mx-auto mt-5" style="max-width: 70%;" >
          <thead class="align-middle">
            <tr>
              <th style="width: 15%;">Tanggal</th>
              <th style="width: 15%;">Jam</th>
              <th style="width: 20%;">Nama</th>
              <th style="width: 10%;">Studio</th>
              <th style="width: 15%;">Package</th> 
              <th style="width: 15%;">Jumlah Orang</th>
              <th style="width: 15%;">Izin Publikasi</th> 
              <th style="width: 15%;">Catatan</th> 
            </tr>
          </thead>
          <tbody>
                      <?php //foreach ($data as $row ) :
            $batas = 100;
            $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
            $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
            //
            $previous = $halaman - 1;
            $next = $halaman + 1;

            $data = mysqli_query($koneksi, "SELECT nama,tanggal,jam,studio,jmlhorgdws,jmlhorgank,package,tipe_package,catatan,izin_publikasi FROM data_booking_cobahampirfinishjuga WHERE studio ='Bekasi-Kabupaten' OR studio='' UNION SELECT nama,tanggal,jam,studio,jmlhorgdws,jmlhorgank,package,tipe_package,catatan,izin_publikasi FROM data_konfirmasi_cobahampirfinishjuga WHERE studio ='Bekasi-Kabupaten' OR studio='' ORDER BY tanggal,jam ASC");
            $jumlah_data = mysqli_num_rows($data);
            $total_halaman = ceil($jumlah_data / $batas);

            $data_konfirmasi = mysqli_query($koneksi,"SELECT nama,tanggal,jam,studio,jmlhorgdws,jmlhorgank,package,tipe_package,catatan,izin_publikasi FROM data_booking_cobahampirfinishjuga WHERE studio ='Bekasi-Kabupaten' OR studio='' UNION SELECT nama,tanggal,jam,studio,jmlhorgdws,jmlhorgank,package,tipe_package,catatan,izin_publikasi FROM data_konfirmasi_cobahampirfinishjuga WHERE studio ='Bekasi-Kabupaten' OR studio='' ORDER BY tanggal,jam ASC LIMIT $halaman_awal, $batas");
            $nomor = $halaman_awal+1;
            while($d = mysqli_fetch_array($data_konfirmasi)){
              $kategoriJadwal = kategoriJadwalPhotoshoot($d["package"]);
              ?>
            <tr data-jadwal-category="<?php echo $kategoriJadwal; ?>">
                <td><?php 
                if($d["package"]== "cetak foto"){
                  echo "--";
                }else{
                $tanggal1 = date("d-m-Y", strtotime($d["tanggal"]));
                        echo $tanggal1;  }?></td>
                <td><?php 
                if($d["package"]== "cetak foto"){
                  echo "--";
                }else{
                  echo $d["jam"];
                }
                  ?></td>
                <td><?php echo $d["nama"];  ?></td>
                <td><?php 
                if($d['studio']==""||$d['studio']=="Bekasi-Kabupaten"){
                  echo "Bekasi-Kabupaten";
                }else{ echo $d["studio"]; } ?></td>
                <td><?php 
                if($d["package"]== "cetak foto"){
                  echo $d["package"];
                }else{
                  echo $d["package"];echo " "; echo '<b>'.$d["tipe_package"].'</b>';
                }
                ?></td>
                <td><?php
                  echo $d["jmlhorgdws"]; echo " "; echo $d["jmlhorgank"];
                ?></td>
                <td>
                  <?php $izinJadwal = (isset($d["izin_publikasi"]) && (string)$d["izin_publikasi"] === '1') ? '1' : '0'; ?>
                  <span class="badge <?php echo ($izinJadwal === '1') ? 'bg-success' : 'bg-secondary'; ?>">
                    <?php echo bookingPublicationConsentLabel($izinJadwal); ?>
                  </span>
                </td>
                <td><?php
                  echo $d["catatan"]; 
                ?></td>

            </tr>
                      <?php }//endforeach;?>
            <tr class="jadwal-filter-empty" style="display:none;">
              <td colspan="8" class="text-center text-black-50">Tidak ada jadwal pada kategori ini.</td>
            </tr>
          </tbody>
        </table>
        <?php }else{
          ?>
          <h3 class="text-center fw-bold" style="margin-top: 100px; margin-bottom:100px;">Belum ada Jadwal</h3>
          <?php
        }?>

        <!-- <div class="clearfix d-flex justify-content-end">
          <ul class="pagination">
                      <li class="page-item"><a class="page-link"<?php if($halaman > 1){ echo "href='?halaman=$previous'"; } ?>>Previous</a></li>
            <?php 
              for($x=1;$x<=$total_halaman;$x++){
            ?> 
            <li class="page-item"><a class="page-link" href="?halaman=<?php echo $x ?>"><?php echo $x; ?></a></li>
            <?php
            }
            ?>		
            <li class="page-item"><a class="page-link" <?php if($halaman < $total_halaman) { echo "href='?halaman=$next'"; } ?>>Next</a></li>
          </ul>
        </div> -->
        </div>
      </div>
      <div id="bekasi-kota" class="jadwal-panel" data-jadwal-container="kota">
        <div class="jadwal-filter-bar d-flex flex-wrap justify-content-center gap-2 mt-4 mb-3" data-jadwal-filter-group="kota">
          <button type="button" class="btn btn-dark btn-sm jadwal-filter active" data-jadwal-group="kota" data-jadwal-filter="all">Semua</button>
          <button type="button" class="btn btn-outline-dark btn-sm jadwal-filter" data-jadwal-group="kota" data-jadwal-filter="photo-studio">Photo Studio</button>
          <button type="button" class="btn btn-outline-dark btn-sm jadwal-filter" data-jadwal-group="kota" data-jadwal-filter="self-pasfoto">Self Photo + Pas Foto</button>
        </div>
        <div class="jadwal-table-scroll">
        <?php
          $ambilbekasikota =mysqli_query($koneksi, "SELECT id FROM data_konfirmasi_cobahampirfinishjuga WHERE studio ='Bekasi-Kota' UNION ALL SELECT id FROM data_booking_cobahampirfinishjuga WHERE studio ='Bekasi-Kota' ");
          $adaKota = mysqli_num_rows($ambilbekasikota) > 0;
          if ($adaKota){?>
        <table  class="table border table-striped table-hover mx-auto mt-5" style="max-width: 70%;" >
          <thead class="align-middle">
            <tr>
              <th style="width: 15%;">Tanggal</th>
              <th style="width: 15%;">Jam</th>
              <th style="width: 20%;">Nama</th>
              <th style="width: 10%;">Studio</th>
              <th style="width: 15%;">Package</th> 
              <th style="width: 15%;">Jumlah Orang</th>
              <th style="width: 15%;">Izin Publikasi</th>
              <th style="width: 15%;">Catatan</th>
            </tr>
          </thead>
          <tbody>
                      <?php //foreach ($data as $row ) :
            $batas = 150;
            $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
            $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
            //
            $previous = $halaman - 1;
            $next = $halaman + 1;

            $data = mysqli_query($koneksi, "SELECT nama,tanggal,jam,studio,jmlhorgdws,jmlhorgank,package,tipe_package,catatan,izin_publikasi FROM data_booking_cobahampirfinishjuga WHERE studio ='Bekasi-Kota' UNION SELECT nama,tanggal,jam,studio,jmlhorgdws,jmlhorgank,package,tipe_package,catatan,izin_publikasi FROM data_konfirmasi_cobahampirfinishjuga WHERE studio ='Bekasi-Kota' ORDER BY tanggal,jam ASC");
            $jumlah_data = mysqli_num_rows($data);
            $total_halaman = ceil($jumlah_data / $batas);

            $data_konfirmasi = mysqli_query($koneksi,"SELECT nama,tanggal,jam,studio,jmlhorgdws,jmlhorgank,package,tipe_package,catatan,izin_publikasi FROM data_booking_cobahampirfinishjuga WHERE studio ='Bekasi-Kota' UNION SELECT nama,tanggal,jam,studio,jmlhorgdws,jmlhorgank,package,tipe_package,catatan,izin_publikasi FROM data_konfirmasi_cobahampirfinishjuga WHERE studio ='Bekasi-Kota' ORDER BY tanggal,jam ASC LIMIT $halaman_awal, $batas");
            $nomor = $halaman_awal+1;
            while($d = mysqli_fetch_array($data_konfirmasi)){
              $kategoriJadwal = kategoriJadwalPhotoshoot($d["package"]);
              ?>
            <tr data-jadwal-category="<?php echo $kategoriJadwal; ?>">
                <td><?php 
                if($d["package"]== "cetak foto"){
                  echo "--";
                }else{
                $tanggal1 = date("d-m-Y", strtotime($d["tanggal"]));
                        echo $tanggal1;  }?></td>
                <td><?php 
                if($d["package"]== "cetak foto"){
                  echo "--";
                }else{
                  echo $d["jam"];
                }
                  ?></td>
                <td><?php echo $d["nama"];  ?></td>
                <td><?php 
                if($d['studio']==""||$d['studio']=="Bekasi-Kabupaten"){
                  echo "Bekasi-Kabupaten";
                }else{ echo $d["studio"]; } ?></td>
                <td><?php 
                if($d["package"]== "cetak foto"){
                  echo $d["package"];
                }else{
                  echo $d["package"];echo " "; echo '<b>'.$d["tipe_package"].'</b>';
                }
                ?></td>
                <td><?php
                  echo $d["jmlhorgdws"]; echo " "; echo $d["jmlhorgank"];
                ?></td>
                <td>
                  <?php $izinJadwal = (isset($d["izin_publikasi"]) && (string)$d["izin_publikasi"] === '1') ? '1' : '0'; ?>
                  <span class="badge <?php echo ($izinJadwal === '1') ? 'bg-success' : 'bg-secondary'; ?>">
                    <?php echo bookingPublicationConsentLabel($izinJadwal); ?>
                  </span>
                </td>
                <td><?php
                  echo $d["catatan"]; 
                ?></td>

            </tr>
                      <?php }//endforeach;?>
            <tr class="jadwal-filter-empty" style="display:none;">
              <td colspan="8" class="text-center text-black-50">Tidak ada jadwal pada kategori ini.</td>
            </tr>
          </tbody>
        </table>
        <?php }else{
          ?>
          <h3 class="text-center fw-bold" style="margin-top: 100px; margin-bottom:100px;">Belum ada Jadwal</h3>
          <?php
        }?>

        <!-- <div class="clearfix d-flex justify-content-end">
          <ul class="pagination">
                      <li class="page-item"><a class="page-link"<?php if($halaman > 1){ echo "href='?halaman=$previous'"; } ?>>Previous</a></li>
            <?php 
              for($x=1;$x<=$total_halaman;$x++){
            ?> 
            <li class="page-item"><a class="page-link" href="?halaman=<?php echo $x ?>"><?php echo $x; ?></a></li>
            <?php
            }
            ?>		
            <li class="page-item"><a class="page-link" <?php if($halaman < $total_halaman) { echo "href='?halaman=$next'"; } ?>>Next</a></li>
          </ul>
        </div> -->
        </div>
      </div>
    </div>        
  </div>



        </div>
<!------------------------------------------------------------------------------------>

    </section>
    <!-- Ini adalah akhir body1  -->

   

    <!-- Modal = ketika di klik masuk mode pop up -->
    <div class="modal fade" id="adminModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">RF Studio</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
                <div class="row text-center m-2">
                    <div class="col-md-6 mx-auto m-2" ><a href="admin/adminlogin.php" class="btn btn-outline-dark" style="height:180px; width:200px;" ><i class="fa-solid fa-user-gear pt-5" style="font-size:50px;"></i><br><span style="font-size:20px;">admin-Bekasi Kab.</span></a></div>
                    <div class="col-md-6 mx-auto m-2"><a href="admin-kartini/adminlogin.php" class="btn btn-outline-dark" style="height:180px; width:200px;" ><i class="fa-solid fa-user-gear pt-5" style="font-size:50px;"></i><br><span style="font-size:20px;">admin-Bekasi Kota</span></a></div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- Ini adalah awal footer  -->
    <footer class="bg-dark text-white pt-5 pb-4">
      <div class="container-md text-start text-md-left mx-auto"> 
        <div class="row text-start text-md-left"> 
          
          <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
             <h5 class="text-uppercase mb-4 fw-bold text-warning text-start">RF Studio</h5>
            <p class="span" style="font-size: 16px;" > Sebuah foto dapat menggambarkan kita kenangan terhadap momen tersebut, segera abadikan momen-mu di Rizal Foto <h class="fw-bold">Studio</h> </p>
           
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 fw-bold text-warning">Social media</h5>
              <p >
                <a href="https://www.instagram.com/rizalstudio.id" class="fjadwal text-white" style="text-decoration:none; " ><i class="fa-brands fa-instagram me-2"></i> rizalstudio.id</a>
                </p>
                <p >
                <a href="https://www.instagram.com/rizalphotography" class="fjadwal text-white" style="text-decoration:none; " ><i class="fa-brands fa-instagram me-2"></i> rizalphotography</a>
                </p>
               
              </p>
                
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 fw-bold text-warning">On Going</h5>
              <p >
                <a href="#" class="fjadwal text-white" style="text-decoration:none; " ><i class="fas fa-calendar-days me-2"></i> Jadwal</a>
                </p>
              <p>
                <a href="trackingprogress.php" class="fpelunasan text-white" style="text-decoration:none;" ><i class="fa-solid fa-rotate me-2"></i> Tracking Progress</a>
              </p>
                
              </div>

            <div class="col-md-5 col-lg-3 col-xl-3 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 fw-bold text-warning"> Contact Us</h5>
              <p>
                <!-- <a href="https://g.page/Rizalstudio?share" class="fmaps" style="text-decoration: none; color:#fff;"> -->
                <a class="" href="" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="text-decoration: none; color:#fff;">
                  <i class="fas fa-location-dot me-2"></i>RF Studio - Maps
                </a>
                <div class="collapse" id="collapseExample">
                    <a href="https://g.page/Rizalstudio?share" target="_blank" class="fmaps" style="text-decoration: none; color:#fff;"><i class="fa-solid fa-map-location-dot mb-3 ms-3 me-2"></i> 
                      Bekasi Kabupaten
                    </a><br>
                    <a href="https://goo.gl/maps/knP7cQPUXhfDEti2A" target="_blank" class="fmaps" style="text-decoration: none; color:#fff;"><i class="fa-solid fa-map-location-dot ms-3 me-2"></i> 
                      Bekasi Kota
                    </a>      
                </div>
              </p>
              <p>
                <a href="https://wa.me/6281288045066" class="fwa" style="text-decoration: none; color:#fff;">
              <i class="fa-brands fa-whatsapp mt-2 me-2"></i>0812-8804-5066
              </a>
              </p>
              <p>
                <a href="https://mail.google.com/" class="femail" style="text-decoration: none; color:#fff;">
              <i class="fas fa-envelope  mt-2 me-2"></i>rizalphotography98@gmail.com
              </a>
              </p>
              <p>
                <a type="button" class="fadmin" data-bs-toggle="modal" data-bs-target="#adminModal" style="text-decoration: none; color:#fff;">
                <i class="fas fa-lock  mt-2 me-2"></i> Masuk Admin </a>
              </p>
            </div>
            
            </div>
            <hr class="mb-4">
            <div class="row align-items-center">
              <div class="col-md-6 col-lg-6 ">
                <p class="text-start"> Copyright ©2023 All rights reserved by :
                  <a href="#" style="text-decoration: none;">
                    <strong class="text-warning"> Rizal Foto Studio</strong>
                  </a>
                </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </footer>

    <!-- Ini adalah akhir footer  -->


    <!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src=" https://cdn.datatables.net/1.13.22/js/jquery.dataTables.min.js"></script>
    <script src=" https://cdn.datatables.net/1.13.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- Modal Foto -->
    <script type="text/javascript"> 
    $("#bekasi-kab").show();
    $("#bekasi-kota").hide();
    
  $(document).ready(function(){
    $('.std-kab').click(function() {
      $(".std-kot.active").removeClass('active');
      $(".std-kab").removeClass('active');
  
      // this.addClass('active');
      $(this).toggleClass('active');
    });
  });
  $(document).ready(function(){
    $('.std-kot').click(function() {
      $(".std-kab.active").removeClass('active'); 
      $(".std-kot").removeClass('active');
          
      // this.addClass('active');
      $(this).toggleClass('active');
    });
  });
    function kabbtn(){
      $("#bekasi-kab").show();
      $("#bekasi-kota").hide();
    }
    function kotabtn(){
      $("#bekasi-kab").hide();
      $("#bekasi-kota").show();
    }
    $(document).ready(function () {
          $('table.tabel').DataTable();
      });
    document.addEventListener("click",function (e){
      if(e.target.classList.contains("gallery-item")){
          const src = e.target.getAttribute("src");
          document.querySelector(".modal-img").src = src;
          const myModal = new bootstrap.Modal(document.getElementById('imgModal'));
          myModal.show();
      }
    })</script>
    <!-- <script>
    // Get the modal
    var modal = document.getElementById('id01');

    // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
      if (event.target == modal) {
          modal.style.display = "none";
     }
}
</script> -->



    <script>
      document.addEventListener('click', function(event) {
        var button = event.target.closest('.jadwal-filter');
        if (!button) return;

        var group = button.getAttribute('data-jadwal-group');
        var filter = button.getAttribute('data-jadwal-filter');
        var container = document.querySelector('[data-jadwal-container="' + group + '"]');
        if (!container) return;

        var rows = container.querySelectorAll('tbody tr[data-jadwal-category]');
        var visibleRows = 0;
        rows.forEach(function(row) {
          var show = filter === 'all' || row.getAttribute('data-jadwal-category') === filter;
          row.style.display = show ? '' : 'none';
          if (show) visibleRows++;
        });

        var emptyRow = container.querySelector('.jadwal-filter-empty');
        if (emptyRow) emptyRow.style.display = visibleRows === 0 ? 'table-row' : 'none';

        document.querySelectorAll('.jadwal-filter[data-jadwal-group="' + group + '"]').forEach(function(item) {
          item.classList.remove('active', 'btn-dark');
          item.classList.add('btn-outline-dark');
        });
        button.classList.add('active', 'btn-dark');
        button.classList.remove('btn-outline-dark');
      });
    </script>
  </body>
</html>