<?php
//cek tombol submit
require '../function.php';

error_reporting(0);
$uniqid = $_GET["uniqid"];
$ambiljadwal = mysqli_query($koneksi,"SELECT * FROM data_jamtanggal WHERE uniqid = '$uniqid'");
$jadwal = mysqli_fetch_array($ambiljadwal);
$hapusjamsementara = $jadwal['id'];
  if(isset($_POST["submit"])){
    //cek apakah data berhasil disimpan atau tidak
    //var_dump($_POST);
        if(tambahstudio($_POST)>0){
          echo '<script language="javascript">';
          echo 'function alert() {';
          echo 'document.getElementById("myForm").reset()};';
          echo 'window.location = "berhasilbooking.php";';
          echo '</script>';
          mysqli_query($koneksi,"DELETE FROM data_jamtanggal WHERE `data_jamtanggal`.`id` = $hapusjamsementara");
        //echo '<meta http-equiv="refresh" content="3;url=../index.php">';
        }else{

            echo '<script language="javascript">';
            echo 'window.location = "bookingstudio.php";';
            echo '</script>';
        }exit;
      }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
 <!-- Favicons -->
  <link href="../img/logo.png" rel="icon">
  <link href="../img/logo.png" rel="apple-touch-icon">
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- As a heading -->
    <title>Booking Studio</title>
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
    <!--  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
  </head>
  <body>
  
    <!-- Ini adalah awal navbar header -->
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">

    
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
         <div class="container d-flex justify-content-center" >
          <a href="../index.php" class="text-dark" style="text-decoration:none;">
             <img src="../img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
             <span class="navbar-light h1 ms-4 align-middle fw-bold" style=" margin-top: 15px; ">Rizal Foto Studio</span>
          </a>
        </div>
    </nav>
    <!-- Ini adalah akhir navbar header -->
    <style type="text/css">
     
      .container-md-5{ 
        margin-top: 1rem;
        margin-bottom: 5rem;
        max-width: 600px;
        margin: auto;

      }
      .jarak{
        margin:20px;
      }

      
      
      </style>

    <!-- Ini bagian atas body Form -->  
<div class="jumbotron ">
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-5 ms-3" style="justify-content: center;">
          <li class="breadcrumb-item"><a href="../index.php " class="text-dark" style="text-decoration: none;">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Isi Formulir</li> 
        </ol>
      </nav>
  <?php 
  if(empty($jadwal["uniqid"])){
  ?>
  <div class="mb-4 mt-4 mx-auto text-center" >
    <div class="m-2 ">
      <label style="font-size: 20px;">Silahkan pilih jenis paket yang anda butuhkan</label><br>
      <label style="font-size: 20px;">klik link dibawah ini</label><br>
      <div class="d-flex mx-auto mb-2 mt-4" style="max-width: 700px;">
        <div class="flex-fill " >
          <!-- <a href="../kategori/familypackage.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px;font-size:14px;">Family Package</a> -->
        </div>
        <div class="flex-fill">
          <a href="../kategori/rentstudio.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px;font-size:14px;">Rental Studio Only</a>
        </div>
        <div class="flex-fill">
          <!-- <a href="../kategori/grouppackage.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px;font-size:14px;">Personal & Group Package</a> -->
        </div>
      </div>
    </div>
  </div>
  <?php }else{?>
  <div class="container-md-5 border" >
    <div class="d-inline-flex ps-1 pe-1 text-white bg-dark " style="width: 200px; height: 60px; " > 
        <h5 class="product-title text-center mt-3 mx-auto">Booking Jadwal</h5></div>


    <!--<div class="alert alert-warning alert-dismissible fade show m-3" role="alert" style="font-size:14px;">-->
    <!--    Mohon Maaf Kami tutup sementara di tanggal<strong> 2 Februari 2023</strong>-->
    <!--   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
    <!-- </div>-->
    <form id="myForm"  method="post" action="" enctype="multipart/form-data">
      <div class="formjarak">
        <div class="jarak">
          <label for="nama" class="form-label fw-bold">Nama<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
          <input type="text"  class="form-control"  id="nama" name="nama" placeholder="Masukkan Nama Anda" autocomplete="off" required>
          <div class="invalid-feedback">Please fill out this field.</div>   
        </div>
          <div class="jarak  ">
          <label for="nomer" class="form-label fw-bold">Nomor Telepon<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
          <input type="tel"  class="form-control"  id="notelp" maxlength="13" minlength="10" name="notelp" autocomplete="off" placeholder="081234567890" required>
          </div>
          <div class="jarak  ">
          <label for="email" class="form-label fw-bold">Email<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
          <input type="email"  class="form-control"  id="email" name="email" autocomplete="off"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="masukanemail.anda@gmail.com" required>
          </div>

          <input type="text"  class="form-control" id="cetak" name="cetak" value="tidak ada" autocomplete="off" hidden >
          <input type="text"  class="form-control" id="uniqid" name="uniqid" value="<?=$uniqid?>" autocomplete="off" hidden >
          <input type="text"  class="form-control" id="nambahhairdo" name="nambahhairdo" value="0" autocomplete="off" hidden >
          <input type="text"  class="form-control" id="nambahmakeup" name="nambahmakeup" value="0" autocomplete="off" hidden >
          
          <!-- <input type="text"  class="form-control"  name="cetakfoto[]"  value="0" autocomplete="off" hidden> -->
        
          <!----------------------------------------------------------------->
          <div class="row row-sm-5 row-md-5 row-lg-5  mt-5 mb-3 ms-2 " style="justify-content: center;">
            <div class="col ms-4">
          <label for="nomer" class="form-label fw-bold">Package</label>
          
            <input class="form-control bsd" type="text" id="harga" name="harga" value="250000" hidden>
            <!-- <input class="form-control sp" type="text" id="sp" name="cetak"> -->
          </div>
        </div>
          <div class="row row-md-5  jarak">
            <!-- harga paket -->
            <!-- gs = gold and silver package -->
            <!-- <div class="col-md-5 mx-auto">
            <div class="border gs-package p-2 mt-3" >
                <div class="form-check mt-2">
                  <input class="form-check-input" type="radio" value="studio" name="package" onclick="myFunction2()" required>
                  <label class="form-check-label" for="flexRadioDefault2">
                  STUDIO 2 (bawah) <label class="text-decoration-line-through text-black-50">259k</label> 200k
                  </label>
                  <div style="font-size: 14px;">
                    <p class="text-black-50 mt-2">3 Background Photo</p>
                    <p class="text-black-50">Lighting</p>
                    <p class="text-black-50">Properti Studio</p>
                    <p class="text-black-50">200k/jam</p>
                    <p class="text-black-50">Max 6 orang</p>
                    
                  </div>  
                </div>
          </div>
          </div> -->
          <div class="col-md-5 mx-auto">
          <div class="border gs-package p-2 mt-3" >
          <div class="form-check mt-2">
              <input class="form-check-input" type="radio" value="studio" name="package" onclick="myFunction2a()" required checked hidden>
              <label class="form-check-label" for="flexRadioDefault2">
              RENTAL STUDIO <label class="text-decoration-line-through text-black-50">299k</label> 250k
              </label>
              <div style="font-size: 14px;">
                <p class="text-black-50 mt-2">4 Background Photo</p>
                <p class="text-black-50">Lighting</p>
                <p class="text-black-50">Properti Studio</p>
                <p class="text-black-50">250k/jam</p>
                <p class="text-black-50">Max 10 orang</p>
              
              </div>
            </div>
            </div>
        </div>
          </div>
          <input class="form-control bsd" type="text" value="package"  name="jumlah" id="jumlah" hidden >

          

        <div class="jarak ms-5 ">
          
          <p for="tanggal" class="mb-3 fw-bold">Tanggal Booking<span class="text-danger">* </span><label class="text-black-50"></label></p>
          <!-- <label for="tanggal" class="mb-3 text-black-50">*disarankan untuk cek jadwal terlebih dahulu</label> -->
          <input type="text"  class="form-control"  id="tanggal" name="tanggal"  autocomplete="off" value="<?php $date = $jadwal["tanggal"]; $newdate = date('d-m-Y',strtotime($date)); echo $newdate;?>" readonly> 
          <!-- <div class="elem-group inlined">
            <input type="text" class="mb-4" name="tanggal" placeholder="-- Pilih Tanggal --" id="tanggal" onfocus="(this.type='date')" value=""  required>

            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">cek jadwal</button>
            <span id="disembunyikantgl"></span>
          </div> -->
        </div>

        <div class="jarak ms-5 ">
          <label for="jam" class="mb-3 fw-bold">Jam<span class="text-danger">* </span><label class="text-black-50"></label></label><br>
          <label for="jam" class="mb-3 text-black-50" style="font-size: 14px;">datang 15-30 menit sebelum photoshoot</label>
          <p for="jam" class="mb-3 text-black-50" style="font-size: 14px;">jam photoshoot mengikuti jam booking</p>
          <p for="jam" class="mb-3 text-black-50" style="font-size: 14px;">keterlambatan diluar tanggung jawab kami</p>
          <div class="row">
            <div class="col">
              <input type="text"  class="form-control"  id="jam" name="jam"  autocomplete="off" value="<?php echo $jadwal["jam"];?>" readonly> 
          <!-- <div class="elem-group inlined"> 
            <select class="form-select form-select-md mb-3" name="jam" id="jam" aria-label=".form-select-lg example" style="max-width: 300px;" required>
              <option class="text-black-50" value="">-- Pilih Jam --</option>
              <option value="08:00-09:00">08:00-09:00</option>
              <option value="09:30-10:30">09:30-10:30</option>
              <option value="11:00-12:00">11:00-12:00</option>
              <option value="12:30-13:30">12:30-13:30</option>
              <option value="14:00-15:00">14:00-15:00</option>
              <option value="15:30-16:30">15:30-16:30</option>
              <option value="17:00-18:00">17:00-18:00</option>
              <option value="18:30-19:30">18:30-19:30</option>
              <option value="20:00-21:00">20:00-21:00</option>
              
            </select>
          </div> -->
          </div>
          </div>
        </div>
        <div class="jarak ms-5 ">
          
          <p for="tanggal" class="mb-3 fw-bold">Lokasi Studio<span class="text-danger">* </span><label class="text-black-50"></label></p>
          <!-- <label for="tanggal" class="mb-3 text-black-50">*disarankan untuk cek jadwal terlebih dahulu</label> -->
          <input type="text"  class="form-control"  id="studio" name="studio"  autocomplete="off" value="<?php echo $jadwal["studio"];?>" readonly> 
          <!-- <div class="elem-group inlined">
            <input type="text" class="mb-4" name="tanggal" placeholder="-- Pilih Tanggal --" id="tanggal" onfocus="(this.type='date')" value=""  required>

            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">cek jadwal</button>
            <span id="disembunyikantgl"></span>
          </div> -->
        </div>

          <div class="jarak ms-5" >
            <label class="mb-2 fw-bold">Jumlah Orang yang foto ke Studio<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
            <p  class="mb-3 text-black-50" style="font-size: 14px;">jika salah satu kosong, isikan 0</p>
            <div class="row" style="max-width:200px;">
              <div class="col input">
                <p for="jam" style="padding-top:10px;font-size: 14px;">Dewasa</p>
              </div>
              <div class="col input">
                <input type="tel" style="max-width: 50px;"  class="form-control"  id="jumlahorangdewasa" name="jumlahorangdewasa" placeholder="" minlength="1" maxlength="2"  autocomplete="off" required>
              </div>
            </div>

            <div class="row" style="max-width:200px;">
              <div class="col input">
                <p for="jam" style="padding-top:10px;font-size: 14px;">Anak-anak</p>
              </div>
              <div class="col input">
                <input type="tel" style="max-width: 50px;"  class="form-control"  id="jumlahoranganak" name="jumlahoranganak" placeholder="" minlength="1" maxlength="2"  autocomplete="off" required>
              </div>
            </div>
          </div>

          <div class="row mb-4 ms-4">
              <label class="mb-2 fw-bold">*Catatan<span class="text-black-50"> (abaikan jika tidak ada catatan)</span></label>
              <textarea rows="3" name="catatan" style="margin-left:20px; width:250px; resize: none;"></textarea>
            </div>
            <?php echo bookingPublicationConsentField(); ?>
            

        <!-- <div class="tambah-menit " id="studio2">
          <div class="d-flex flex-row justify-content-evenly me-2 ms-2 ">
                <label class="form-check-label mt-4" for="flexCheckDefault">
                Tambah waktu studio 2(200k/jam)
                </label>
            
           
            
          <div class="d-flex flex-row"> 
            <div class="container jumlah-orang" style="height:50px;">
            <div class="row">
              <div class="col-sm-3 m-3">
                  <div class="input-group" style="width: 130px;">
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-dua-minus"  data-type="minus" data-field="quant[1]">
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      
                      <input type="text" name="nambahwaktustdio2" id="inputstudio2" style=" border:#fff;" value="0" autocomplete="off" placeholder="0" class="form-control input-dua-number text-center bg-white" min="0" max="100" readonly>
                      
                      
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-dua-plus" data-type="plus" data-field="quant[1]">
                          <span class="fa-solid fa-circle-plus"></span>
                        </button>
                      </span>
                  </div>
              </div>
            </div>
            </div>
            </div>
            </div>
          </div> -->

          <div class="tambah-menit m-4" id="studio3">
          <div class="d-flex flex-row justify-content-evenly me-2 ms-2 ">
                <label class="form-check-label mt-4" for="flexCheckDefault">
                Tambah waktu studio (250k/jam)
                </label>
            
            <!-- nambahorang Dewasa -->
            
          <div class="d-flex flex-row"> 
            <div class="container jumlah-orang" style="height:50px;">
            <div class="row">
              <div class="col-sm-3 m-3">
                  <div class="input-group" style="width: 120px;">
                      <span class="input-group-btn" style="width: 40px; height: 30px;">
                        <button type="button" class="btn btn-default btn-tiga-minus"  data-type="minus" data-field="quant[1]">
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      
                      <input type="text" name="nambahwaktustdio3" id="inputstudio3" style=" border:#fff;" value="0" autocomplete="off" placeholder="0" class="form-control input-tiga-number text-center bg-white" min="0" max="100" readonly>
                      <span class="mt-1"> jam</span>
                      <span class="input-group-btn" style="width: 20px; height: 30px;">
                        <button type="button" class="btn btn-default btn-tiga-plus" data-type="plus" data-field="quant[1]">
                          <span class="fa-solid fa-circle-plus"></span>
                        </button>
                      </span>
                      
                  </div>
              </div>
            </div>
            </div>
            </div>
            </div>
          </div>
          

          <input type="text" name="cetakwaktu2"  autocomplete="off"  class="form-control  text-center" value="" id="cetakwaktu2" hidden>
          <input type="text" name="cetakwaktu3"  autocomplete="off"  class="form-control  text-center" value="" id="cetakwaktu3" hidden>


          <!--------------------------------------------->
            <div >
              <input type="text" name="tambah1" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="data anda berhasil terkirim, menunggu konfirmasi dari admin" hidden>
              <input type="text" name="gambar1" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="correct.png" min="0" max="8" hidden>
              <input type="text" name="gambar2" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
              <input type="text" name="gambar3" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
              <input type="text" name="gambar4" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
              <input type="text" name="gambar5" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
            </div>
          <!--------------------------------------------->
            <div class="form-check" hidden>
              <input type="text" name="cetakharga1"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox1" hidden >
              <input type="text" name="cetakharga2"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox2" hidden >
              <input type="text" name="cetakharga3"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox3" hidden>
              <input type="text" name="cetakharga4"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox4" hidden>
              <input type="text" name="cetakharga5"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox5" hidden>
              <input type="text" name="cetakharga6"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox6" hidden>
              <input type="text" name="cetakharga7"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox7" hidden>
              <input type="text" name="cetakharga8"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox8" hidden>
              <input type="text" name="cetakharga9"  autocomplete="off"  class="form-control  text-center" value="0" id="textbox9" hidden>
            </div>
          <!--------------------------------------------->
          <!--------------------------------------------->
            <div class="form-check" hidden>
              <input type="text" name="cetakfoto1"  autocomplete="off"  class="form-control  text-center" value="" id="textbox1" hidden >
              <input type="text" name="cetakfoto2"  autocomplete="off"  class="form-control  text-center" value="" id="textbox2" hidden >
              <input type="text" name="cetakfoto3"  autocomplete="off"  class="form-control  text-center" value="" id="textbox3" hidden>
              <input type="text" name="cetakfoto4"  autocomplete="off"  class="form-control  text-center" value="" id="textbox4" hidden>
              <input type="text" name="cetakfoto5"  autocomplete="off"  class="form-control  text-center" value="" id="textbox5" hidden>
              <input type="text" name="cetakfoto6"  autocomplete="off"  class="form-control  text-center" value="" id="textbox6" hidden>
              <input type="text" name="cetakfoto7"  autocomplete="off"  class="form-control  text-center" value="" id="textbox7" hidden>
              <input type="text" name="cetakfoto8"  autocomplete="off"  class="form-control  text-center" value="" id="textbox8" hidden>
              <input type="text" name="cetakfoto9"  autocomplete="off"  class="form-control  text-center" value="" id="textbox9" hidden>
            </div>
          <!--------------------------------------------->
          <!--------------------------------------------->
            <div class="form-check" hidden>
              <input type="text" name="jumlahcetak1"  autocomplete="off"  class="form-control  text-center" value="" id="textbox1" hidden >
              <input type="text" name="jumlahcetak2"  autocomplete="off"  class="form-control  text-center" value="" id="textbox2" hidden >
              <input type="text" name="jumlahcetak3"  autocomplete="off"  class="form-control  text-center" value="" id="textbox3" hidden>
              <input type="text" name="jumlahcetak4"  autocomplete="off"  class="form-control  text-center" value="" id="textbox4" hidden>
              <input type="text" name="jumlahcetak5"  autocomplete="off"  class="form-control  text-center" value="" id="textbox5" hidden>
              <input type="text" name="jumlahcetak6"  autocomplete="off"  class="form-control  text-center" value="" id="textbox6" hidden>
              <input type="text" name="jumlahcetak7"  autocomplete="off"  class="form-control  text-center" value="" id="textbox7" hidden>
              <input type="text" name="jumlahcetak8"  autocomplete="off"  class="form-control  text-center" value="" id="textbox8" hidden>
              <input type="text" name="jumlahcetak9"  autocomplete="off"  class="form-control  text-center" value="" id="textbox9" hidden>
            </div>
          <!--------------------------------------------->
          <input type="hidden" name="nambahorang" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control input-anak-number text-center" value="0" min="0" max="8" hidden>
          <input type="hidden" name="nambahanak" style="background-color: white;" autocomplete="off" placeholder="0" class="form-control input-gede-number text-center" value="0" min="0" max="8" hidden>
          <input type="hidden" name="nambahdewasa" style="background-color: white;" autocomplete="off" placeholder="0" class="form-control input-gede-number text-center" value="0" min="0" max="8" hidden>

          <div class="jarak">
            <label  class="fw-bold ms-3 mt-3">Pembayaran<span class="fw-light text-danger">*</span></label>
          <div class="elem-group inlined ms-3 mt-3">
          <input type="text" class="form-control" id="buktilunas" name="buktilunas" value="0" hidden>
          <input type="text" class="form-control" id="ketbayar" name="ketbayar" value="Terimakasih sudah melakukan pembayaran, untuk memastikan silahkan hubungi admin via Whatsapp" hidden>
          
          <label  class="form-check-label ">Transfer ke Bank <i>BCA</i> <p> 5780785057 A.n. Rizal Satria Agung</p></label><br>
          <label  class="form-check-label ms-3">Rp250.000</label>
          <!-- <p class="text-black-50" >sesuai package yang dipilih </p> -->
        </div>
          </div>

          <div class="jarak ms-4" >
            <label for="upload-img" class="mb-3 mt-3 fw-bold">Upload Bukti Transfer<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
              <p class="text-black-50" >
                  *ukuran max 5 MB </p>
                  <p class="text-black-50 ">
                  *format gambar .png/.jpg/.jpeg
          </p>
        
            <div class="elem-group inlined  ">
              <input type="file" class="mb-2" id="buktitransfer" name="buktitransfer" value="" onchange="preview()" required>
              <div class="empty-text w-50" style="width: 20%;">
                  <img id="thumb" src="" width="250px"/>
              </div>
            </div>
          </div>
          
          <div class="jarak">
            <div class="container-check">
              <input type="checkbox" id="test6"/>
              <label for="test6">Dibaca dulu yaa <u><b>Term & Condition</b> (syarat & ketentuan)</u></label>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" id="submit" name="submit" class="btn btn-lg btn-outline-dark mt-2 mb-5" onclick="alert()" >Booking</button>
            </div>

            <p class="mt-4 ms-4 mb-4">
            <span class="text-danger">*</span> wajib diisi </p>
      </div>
    </form>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cek Jadwal</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <div class="table-responsive mx-auto" style="max-width:1000px ;">
        <div class="table-wrapper">
          <div class="table-title ">
            <div class="row m-1 ">
              <h2></h2>
            </div>
          </div>
          <table class="table table-hover mx-auto" style="width: 100%;">
            <thead class="align-middle">
              <tr>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 10%;">Nama</th>
                <th style="width: 20%;">Jam</th>
                <th style="width: 15%;">Package</th>  
              </tr>
            </thead>
            <tbody>
                        <?php //foreach ($data as $row ) :
              $batas = 50;
              $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
              $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
              //
              $previous = $halaman - 1;
              $next = $halaman + 1;

              $data = mysqli_query($koneksi, "SELECT nama,package,tanggal,jam,tipe_package FROM data_booking_cobahampirfinishjuga UNION SELECT nama,package,tanggal,jam,tipe_package FROM data_konfirmasi_cobahampirfinishjuga ORDER BY tanggal,jam ASC");
              $jumlah_data = mysqli_num_rows($data);
              $total_halaman = ceil($jumlah_data / $batas);

              $data_konfirmasi = mysqli_query($koneksi,"SELECT nama,package,tanggal,jam,tipe_package FROM data_booking_cobahampirfinishjuga UNION SELECT nama,package,tanggal,jam,tipe_package FROM data_konfirmasi_cobahampirfinishjuga ORDER BY tanggal,jam ASC LIMIT $halaman_awal, $batas");
              $nomor = $halaman_awal+1;
              while($d = mysqli_fetch_array($data_konfirmasi)){
                ?>
              <tr>
                  <td><?php 
                  if($d["package"]== "cetak foto"){
                    echo "--";
                  }else{
                  $tanggal1 = date("d-m-Y", strtotime($d["tanggal"]));
                          echo $tanggal1;  }?></td>
                  <td><?php echo $d["nama"];  ?></td>
                  <td><?php 
                  if ($d["tipe_package"]== "prawedding"){
                    if($d["jam"]=="08:00-09:00 09:30-10:30"){
                      echo "08:00-10:00";
                    }else if($d["jam"]=="11:00-12:00 12:30-13:30"){
                    echo "11:00-13:00";
                    }
                    else if($d["jam"]=="14:00-15:00 15:30-16:30"){
                    echo "14:00-16:00";
                    }
                    else if($d["jam"]=="17:00-18:00 18:30-19:30"){
                    echo "17:00-19:00";
                    }
                    else if($d["jam"]=="20:00-21:00 21:00-22:00"){
                    echo "20:00-22:00";
                    }
                  }else if($d["package"]== "cetak foto"){
                    echo "--";
                  }else{
                    echo $d["jam"];
                  }
                    ?></td>
                  <td><?php 
                  if($d["package"]== "cetak foto"){
                    echo $d["package"];
                  }else{
                    echo $d["package"];echo " "; echo $d["tipe_package"];
                  }
                  ?></td>

              </tr>
                        <?php }//endforeach;?>
            </tbody>
          </table>
        </div>
      </div>  
          </div>
        </div>
      </div>
    </div>
    <!-- Tutup Modal -->

  </div>
  <?php }?>
</div>

    <!-- Ini bagian akhir body Form -->
    <!-- Modal Checkbox Term & Condition -->
    
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Term & Condition (Syarat & Ketentuan)</h5>
            
          </div>
          <div class="modal-body">   
              <div class="container">
                <ul>
                  <li>Untuk booking / reservasi dp 200K  pelunasan setelah foto ( cancel dp hangus )</li>
                  <li>Link G-Drive dikirim setelah pelunasan & hanya berlaku 2 minggu</li>
                  <li>Sesi foto tanpa make up dan hairdo datang 10 - 15 menit sebelum sesi foto</li>
                  <li>Sesi foto dengan make up & hairdo datang 2 jam sebelum sesi foto</li>
                  <li>Sesi foto sesuai jam bookingan</li>
                  <li>Jika terlambat sesi foto kepotong ( jika sesi foto kurang bisa ambil overtime on the spot)</li> 
                  <li>Bisa ambil overtime jika jam berikutnya kosong</li>
                  <li>Biaya overtime = 150K / 30 menit</li>
                  <li>Reshedule paling lambat H-1  cancel hari H dp hangus</li>
                  <li>Kita ada 2 studio dengan background dan lokasi yg berbeda, pastikan anda sudah booking dengan benar</li>
                  <li>Untuk property bisa sewaktu” berubah / upgrade</li> 
                  <li>Sesi foto sepuasnya selama 1 jam Bisa 2 busana jika waktunya cukup</li>
                  <li>Editing mencangkup coloring, memperbaiki background, menghilangkan jerawat</li>
                </ul>
              </div>
          </div>
          <div class="modal-footer" style="border-top: 0 none;">
            <button type="button" class="btn btn-outline-dark m-1" data-bs-dismiss="modal" >oke lanjut >
            </button>
          </div>
        </div>
      </div>
    </div>

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
                    <div class="col-md-6 mx-auto m-2" ><a href="../admin/adminlogin.php" class="btn btn-outline-dark" style="height:180px; width:200px;" ><i class="fa-solid fa-user-gear pt-5" style="font-size:50px;"></i><br><span style="font-size:20px;">admin-Bekasi Kab.</span></a></div>
                    <div class="col-md-6 mx-auto m-2"><a href="../admin-kartini/adminlogin.php" class="btn btn-outline-dark" style="height:180px; width:200px;" ><i class="fa-solid fa-user-gear pt-5" style="font-size:50px;"></i><br><span style="font-size:20px;">admin-Bekasi Kota</span></a></div>
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
                <a href="../jadwal.php" class="fjadwal text-white" style="text-decoration:none; " ><i class="fas fa-calendar-days me-2"></i> Jadwal</a>
                </p>
               <!-- <p>
                <a href="../reservasi/overtime.php" class="fovertime text-white" style="text-decoration:none;" ><i class="fa-solid fa-clock me-2"></i> Overtime</a>
              </p> -->
               <p>
                <a href="../trackingprogress.php" class="fpelunasan text-white" style="text-decoration:none;" ><i class="fa-solid fa-rotate me-2"></i> Tracking Progress</a>
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
              <p class="text-start">Copyright &copy;<span id="copyright-year"></span>
		All rights reserved by :
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
    <script>
        // Menampilkan tahun saat ini
        document.getElementById('copyright-year').textContent = new Date().getFullYear();
    </script>

    <!-- Ini adalah akhir footer  -->

    <!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  
    <!-- Modal Foto -->
    <script type="text/javascript"> 
    document.addEventListener("click",function (e){
      if(e.target.classList.contains("gallery-item")){
          const src = e.target.getAttribute("src");
          document.querySelector(".modal-img").src = src;
          const myModal = new bootstrap.Modal(document.getElementById('imgModal'));
          myModal.show();
      }
    })</script>

    <script>
      $('#submit').prop('disabled',true);
      $("#test6").click(function(){
          if($(this).is(':checked')) {
             $('#myModal').modal('show');
             $('#submit').prop('disabled',false);
           } else {
             $('#myModal').modal('hide');
             $('#submit').prop('disabled',true);
           }
        });
    </script>
    <!--DatePicker Script  -->
    <script> 
    var currentDateTime = new Date();
    var year = currentDateTime.getFullYear();
    var month = (currentDateTime.getMonth() + 0);
    var date = (currentDateTime.getDate() + 0);

    if(date < 10) {
      date = '0' + date;
      }
    if(month < 10) {
      month = '0' + month;
      }

    var dateTomorrow = year + "-" + month + "-" + date;
    var bookingElem = document.querySelector("#tanggal");
    

    bookingElem.setAttribute("min", dateTomorrow);

    bookingElem.onchange = function () {
    }
 </script> 
 <!--  -->
 <script>
      function alert(){
      var result = confirm("Anda yakin sudah mengisi data dengan benar?");
      if (result == false){
        event.preventDefault();
      }
    }
    </script>
<script>
 function org() {
  $('#cetak').val('tidak ada ');
  document.getElementById("dlpnorg").disabled = true;
  document.getElementById("enmorg").disabled = false;

 }
 function org1() {
  $('#cetak').val('bawaan specialpackage ');
  document.getElementById("dlpnorg").disabled = false;
  document.getElementById("enmorg").disabled = true;
 
 }
</script>
<!--  -->
<script type="text/javascript">

function myFunction2() {
  $('#harga').val('200000');
  $('#cetak').val('');
  $('#jumlah').val('studio2(bawah)');
  $('#studio3').hide();
  $('#studio2').show();
 
  
}
function myFunction2a() {
  $('#harga').val('250000');
  $('#cetak').val('');
  $('#jumlah').val('studio3(atas)');
  $('#studio2').hide();
  $('#studio3').show();

}
function myFunction2b() {
  $('#harga').val('0');
  $('#cetak').val('');
  $('#jumlah').val('-');

}

</script>


   <!--Auto increament quantity-->
<script type="text/javascript">
  function myFunction(p1, p2) {
   return p1 * p2;
}
  function myFunctionn(p1, p2) {
   return p1 - p2;
}
    
      $(document).ready(function(){
        $('.btn-dua-plus').click(function (e){
          e.preventDefault();
          
          var qty = $('.input-dua-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-dua-number').val(value);
            $('#cetakwaktu2').val(value);
            
           // document.getElementById("calculation").innerHTML = myFunctionanak(value, 35000);
           
          }
        });

        $('.btn-dua-minus').click(function (e){
          e.preventDefault();

          var qty = $('.input-dua-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-dua-number').val(value);
            $('#cetakwaktu2').val(value);
            if(value == 0){
            $('#cetakwaktu2').val('');

            }
           //  document.getElementById("calculation").innerHTML = myFunctionnanak((value*35000), 0);
           
          }
        });
      });
    </script>
    <script type="text/javascript">
  function myFunction(p1, p2) {
   return p1 * p2;
}
  function myFunctionn(p1, p2) {
   return p1 - p2;
}
    
      $(document).ready(function(){
        $('.btn-tiga-plus').click(function (e){
          e.preventDefault();
          
          var qty = $('.input-tiga-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-tiga-number').val(value);
            $('#cetakwaktu3').val(value);
           // document.getElementById("calculation").innerHTML = myFunctionanak(value, 35000);
           
          }
        });

        $('.btn-tiga-minus').click(function (e){
          e.preventDefault();

          var qty = $('.input-tiga-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-tiga-number').val(value);
             $('#cetakwaktu3').val(value);
            if(value == 0){
            $('#cetakwaktu3').val('');
           //  document.getElementById("calculation").innerHTML = myFunctionnanak((value*35000), 0);
            }
          }
        });
      });
    </script>

<!-- Preview Foto -->
<script type="text/javascript">
function preview() {
   thumb.src=URL.createObjectURL(event.target.files[0]);
}
</script>


    <input type="hidden" id="refreshed" value="">
    <script type="text/javascript">

         $( document ).ready(function() {
  var iAmProcessing = false;
  $('.link').click(function(){
    if (iAmProcessing === true) {
      return;
    }
    iAmProcessing = true;
    $.post('ajax1.php?strana='+$(this).attr('strana'), function(odgovor) {
      iAmProcessing = false;
      $('#odgovor').html(odgovor);
    });
  });
});

    </script>
  </body>
  </html>
