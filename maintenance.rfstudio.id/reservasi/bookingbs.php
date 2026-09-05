<?php
//cek tombol submit
require '../function.php';
// ini_set('display_errors', 1); 
error_reporting(0);
$uniqid = $_GET["uniqid"];
$ambiljadwal = mysqli_query($koneksi,"SELECT * FROM data_jamtanggal WHERE uniqid = '$uniqid'");
$jadwal = mysqli_fetch_array($ambiljadwal);
$hapusjamsementara = $jadwal['id'];
  if(isset($_POST["submit"])){
   // var_dump($_POST);
  if(tambah($_POST)>0){
        echo '<script language="javascript">';
    echo 'function alert() {';
    echo 'document.getElementById("myForm").reset()};';
    echo 'window.location = "berhasilbooking.php";';
    echo '</script>';
    mysqli_query($koneksi,"DELETE FROM data_jamtanggal WHERE `data_jamtanggal`.`id` = $hapusjamsementara");
    //echo '<meta http-equiv="refresh" content="3;url=../index.php">';
    }else{

        echo '<script language="javascript">';
        echo 'window.location = "bookingbs.php";';
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
    <title>Booking</title>
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
      .jumbotron{
        
        margin-left: auto;
        justify-content: center;
      }
      .jarak{
        margin: 20px;
      }
      /* Modal Overlay (background hitam transparan) */
      .modal-overlay {
        display: none; /* awalnya disembunyikan */
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
        z-index: 9999;
      }

      /* Kotak Modal */
      .modal-popup {
        background: #fff;
        padding: 25px 30px;
        border-radius: 12px;
        text-align: center;
        width: 90%;
        max-width: 350px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        animation: popup 0.4s ease;
        font-family: 'Poppins', sans-serif;
        color: #333;
        line-height: 1.6;
      }

      /* Animasi muncul */
      @keyframes popup {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
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
      <div class=" mx-auto mb-2 mt-4" style="max-width: 400px;">
        <div class="flex-fill mb-4" >
          <a href="../kategori/familypackage.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px;font-size:18px;">
          <div class="d-flex">
            <img class="shadow-lg me-4" src="../img/foto17.png" width="60" style="border-radius: 10px;">
            <label class="my-auto ms-3"> Family Package</label>
          </div>
          </a>
        </div>
        <div class="flex-fill mb-4" >
          <a href="../kategori/maternitypackage.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px;font-size:18px;">
          <div class="d-flex">
            <img class="shadow-lg me-4" src="../img/foto29.png" width="60" style="border-radius: 10px;">
            <label class="my-auto ms-3"> Maternity Package</label>
          </div>
          </a>
        </div>
        <div class="flex-fill mb-4" >
          <a href="../kategori/grouppackage.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px;font-size:15px;">
          <div class="d-flex">
            <img class="shadow-lg me-4" src="../img/fotoGroup.jpg" width="60" style="border-radius: 10px;">
            <label class="my-auto ms-3"> Group & Personal Package</label>
          </div>
          </a>
        </div>
        <div class="flex-fill mb-4" >
          <a href="../kategori/graduationpackage.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px;font-size:18px;">
          <div class="d-flex">
            <img class="shadow-lg me-4" src="../img/foto21.png" width="60" style="border-radius: 10px;">
            <label class="my-auto ms-3"> Graduation Package</label>
          </div>
          </a>
        </div>
        <div class="flex-fill mb-4" >
          <a href="../kategori/baby-smash-cake.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px;font-size:15px;">
          <div class="d-flex">
            <img class="shadow-lg me-4" src="../img/foto73.png" width="60" style="border-radius: 10px;">
            <label class="my-auto ms-3"> Baby Birthday / Smash Cake</label>
          </div>
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php }else{?>
<div class="container-md-5 border" >
  <div class="d-inline-flex ps-1 pe-1 text-white bg-dark " style="width: 200px; height: 60px; " > 
      <h5 class="product-title text-center mt-3 mx-auto">Booking Jadwal</h5></div>

 <?php
  
?>
    <div class="alert alert-warning alert-dismissible fade show m-3 text-center" role="alert" style="font-size:14px;">
      Mohon untuk datang lebih awal<strong> dari jam yang dipilih </strong> <br>
      <b>Jika Terlambat</b> diluar tanggung jawab kami.<br>
      Sesi Foto mengikuti jam bookingan.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <div style="font-size: 14px;">
    <form id="myForm" method="post" action="" enctype="multipart/form-data">
      <div class="jarak  ">
        <input type="text"  class="form-control" hidden id="uniqid" name="uniqid"  autocomplete="off" value="<?php echo $jadwal["uniqid"];?>" readonly> 
        <label for="nama" class="form-label fw-bold">Nama<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
        <input type="text"  class="form-control"  id="nama" name="nama" placeholder="Masukkan Nama Anda" autocomplete="off" required>
        <div class="invalid-feedback">Please fill out this field.</div>   
      </div>
        <div class="jarak  ">
        <label for="nomer" class="form-label fw-bold">Nomor Telepon<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
        <input type="tel"  class="form-control"  id="notelp" maxlength="13" minlength="10" name="notelp" autocomplete="off" placeholder="081234567890" required>
        </div>
        <div class="jarak ">
        <label for="email" class="form-label fw-bold">Email<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
        <input type="email"  class="form-control"  id="email" name="email"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" autocomplete="off" placeholder="masukanemail.anda@gmail.com" required>
        </div>

        <!----------------------------------------------------------------->
        <div class="row row-sm-5 row-md-5 row-lg-5   mx-auto mt-3 mb-3 " style="justify-content: center;">
          <div class="col-md-5 ms-4">
            <div class="row">
              <div class="col mt-3 p-2">
                <label for="nomer" class="form-label fw-bold">Pilih Package<span class="text-danger">*</span><label class="text-black-50"> wajib diisi </label></label>
                <div class="form-check mt-3">
                  <input class="form-check-input" type="radio" value="family" name="package" id="family-package" onclick="myFunction1()" required>
                    <label class="form-check-label" for="flexRadioDefault1">
                      Family Package
                    </label>
                </div>
                <div class="form-check mt-2">
                <input class="form-check-input" type="radio" value="graduation" name="package" id="graduation-package" onclick="myFunction2()" required>
                    <label class="form-check-label" for="flexRadioDefault2">
                      Graduation Package 
                    </label>
                </div>
                <div class="form-check mt-2">
                <input class="form-check-input" type="radio" value="maternity" name="package" id="maternity-package" onclick="myFunction3()" required>
                    <label class="form-check-label" for="flexRadioDefault2">
                      Maternitiy Package 
                    </label>
                </div>
                <div class="form-check mt-2">
                <input class="form-check-input" type="radio" value="group" name="package" id="personalgroup-package" onclick="myFunction4()" required>
                    <label class="form-check-label" for="flexRadioDefault2">
                      Personal & Group Package 
                    </label>
                  </div>
                </div>
            <div class="row">
            <div class="col border mt-3 p-2">
              <div class="form-check mt-2">
                <input class="form-check-input sp package-btn" type="radio" value="800000" name="harga" id="mySelect2" onclick="org1()"  data-dp="200" required>
                <label class="form-check-label" for="flexRadioDefault2">
                <b>SPECIAL PACKAGE</b><span class="fw-light text-danger">*</span> <label class="text-decoration-line-through text-black-50"> 999k</label> 800k
                </label>
                <!-- <input class="form-check-input" type="text" name="cetakfoto" hidden> -->
                  <p class="" style="font-size:small;margin-top:10px;">2 Background Photo</p>
                  <p class="" style="font-size:small;">1 jam Photo Session</p>
<p class="" style="font-size:small;">Sudah termasuk Photographer</p>
                  <p class="" style="font-size:small;">1 cetak Canvas + Frame ukuran 17R / kalau sudah di pasang frame ukurannya 40cm x 50 cm</p>
                  <p class="" style="font-size:small;">5 pcs cetak ukuran 5R (tanpa frame)</p>
                  <p class="" style="font-size:small;">Free 10-20 Photo, edit tone warna</p>
                  <p class="" style="font-size:small;">Foto unlimited / sepuasnya</p>
                  <p class="" style="font-size:small;">All Softcopy on Google drive<b><br> (berlaku 2 Minggu)</b></p>
                  <p class=" fw-bold" style="font-size:small;"><b>Max 8 Orang</b></p>
              </div>
            </div>
          </div>
        </div>
        </div>

        <div class="col-md-5 ms-4 me-4">
          <!-- harga paket -->
          <!-- bs = best and special package -->
          <div class="row">

            <div class=" bs-package p-2" >
              <div class="col border p-2">
                <div class="form-check mt-2" >
                  <input class="form-check-input bsd package-btn" type="radio" value="500000" name="harga" id="mySelect1" onclick="org()"  data-dp="200" required>
                  <label class="form-check-label" for="flexRadioDefault2">
                  <b>BEST DEAL PACKAGE</b><span class="fw-light text-danger">*</span> <label class="text-decoration-line-through text-black-50"> 699k</label> 500k
                  </label>
                  <!-- <input class="form-check-input" type="text" name="cetakfoto" hidden> -->
                    <p class="" style="font-size:small;margin-top:10px;">2 Background Photo</p>
                    <p class="" style="font-size:small;">1 jam Photo Session</p>
                    <p class="" style="font-size:small;">Sudah termasuk Photographer</p>              
                    <p class="" style="font-size:small;">Free 10-15 Photo, edit tone warna</p>
                    <p class="" style="font-size:small;">Foto unlimited / sepuasnya</p>
                    <p class="" style="font-size:small;">All Softcopy on Google drive<b><br> (berlaku 2 Minggu)</b></p>
                    <p class=" fw-bold" style="font-size:small;"><b>Max 6 Orang</b></p>
                </div>
                </div>
                
            </div>
            <div class=" bs-package p-2" >
              <div class="col border p-2">
                <div class="form-check mt-2">
                  <input class="form-check-input package-btn"  type="radio" value="1850000" name="harga" id="mySelect3" onclick="org2()"  data-dp="500" required disabled>
                  <label class="form-check-label" for="flexRadioDefault2">
                  <b>DIAMOND PACKAGE</b><span class="fw-light text-danger">*</span> <label class="text-decoration-line-through text-black-50"> 1999k</label> 1850k
                  </label>
                  <!-- <input class="form-check-input" type="text" name="cetakfoto" hidden> -->
                  <div id="diamond-detail" style="color:grey">
                    <p class="" style="font-size:small;margin-top:10px;">Fotografer @rizalstudio.id + studio</p>
                    <p class="" style="font-size:small;">1 jam Photo Session</p>
                    <p class="" style="font-size:small;">Sudah termasuk Photographer</p>         
                    <p class="" style="font-size:small;">1 Fotografer</p>
                    <p class="" style="font-size:small;">2 Background Photo (Bebas Pilih)</p>     
                    <p class="" style="font-size:small;">Foto unlimited / sepuasnya</p>
                    <p class="" style="font-size:small;">1 Cetak Canvas ukuran 17R (40x50)</p>  
                    <p class="" style="font-size:small;">5 Cetak ukuran 5R (Tanpa Frame)</p> 
                    <p class="" style="font-size:small;">Edit 10-20 Foto</p>      
                    <p class="" style="font-size:small;">All Softcopy on Google drive<b><br> (berlaku 2 Minggu)</b></p>
                    
                    <p class="" style="font-size:small;">1 Jas dan Gaun by @gaunkuu_ Bebas Pilih</p>  
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        </div>

        <div class="form-check " >
          <input type="text" name="nambahorang" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="0" min="0" max="8" hidden>
          <input type="text" name="nambahwaktu" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="0" min="0" max="8" hidden>
        </div>

            
        <div class="form-check " hidden>
          <input class="form-control bsd" type="text" id="cetak" name="cetak">
          <!-- <input class="form-control sp" type="text" id="sp" name="cetak"> -->
        </div>

        <div class="form-check " hidden>
          <input class="form-control bsd" type="text" id="jumlah" name="jumlah">
          <!-- <input class="form-control sp" type="text" id="sp" name="cetak"> -->
        </div>

        <div class="jarak ms-3 ">
          
          <p for="tanggal" class="mb-3 fw-bold">Tanggal Booking<span class="text-danger">* </span><label class="text-black-50"></label></p>
          <!-- <label for="tanggal" class="mb-3 text-black-50">*disarankan untuk cek jadwal terlebih dahulu</label> -->
          <input type="text"  class="form-control"  id="tanggal" name="tanggal"  autocomplete="off" value="<?php $date = $jadwal["tanggal"]; $newdate = date('d-m-Y',strtotime($date)); echo $newdate;?>" readonly> 
          <!-- <div class="elem-group inlined">
            <input type="text" class="mb-4" name="tanggal" placeholder="-- Pilih Tanggal --" id="tanggal" onfocus="(this.type='date')" value=""  required>

            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">cek jadwal</button>
            <span id="disembunyikantgl"></span>
          </div> -->
        </div>

        <div class="jarak ms-3 ">
          <label for="jam" class="mb-3 fw-bold">Jam<span class="text-danger">* </span><label class="text-black-50"></label></label><br>
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
        <div class="jarak ms-3 ">
          
          <p for="tanggal" class="mb-3 fw-bold">Lokasi Studio<span class="text-danger">* </span><label class="text-black-50"></label></p>
          <!-- <label for="tanggal" class="mb-3 text-black-50">*disarankan untuk cek jadwal terlebih dahulu</label> -->
          <input type="text"  class="form-control"  id="studio" name="studio"  autocomplete="off" value="<?php echo $jadwal["studio"];?>" readonly> 
          <!-- <div class="elem-group inlined">
            <input type="text" class="mb-4" name="tanggal" placeholder="-- Pilih Tanggal --" id="tanggal" onfocus="(this.type='date')" value=""  required>

            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">cek jadwal</button>
            <span id="disembunyikantgl"></span>
          </div> -->
        </div>
        
        <div class="jarak ms-3" >
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


        <div class="row row-sm-5 row-md-5 row-lg-5 border ms-4 me-4 " id="nambah" style="justify-content: center; margin-left:20px; margin-right:20px;">
          <label class="form-check-label jarak  " for="flexCheckDefault" >
              *Penambahan <span class="text-black-50"> (abaikan jika tidak ada penambahan)</span>
              <p>*Diatas 8 orang pakai studio 3 (Naik tangga)</p>
              </label>
          <span class="text-black-50"> (abaikan jika tidak ada penambahan)</span>
          <div class="tambah-makeup ">
              <div class="d-flex flex-row">
                <!-- nambahorang makeup -->
                  <label class="text-start mt-4 flex-fill ps-2"  for="flexCheckDefault">
                    Makeup (400k/orang)
                  </label>
                <div class="d-flex flex-row">  
                  <div class="container" id="jumlah-makeup" style="height:50px;" >
                    <div class="row">
                      <div class="col-sm-3 mt-3 mb-3 me-3">
                        <div class="input-group" style="width: 100px;" >
                        <span class="input-group-btn" style="width: 30px; height: 30px;">
                          <button type="button" class="btn btn-default btn-makeup-minus" data-type="minus" data-field="quant[1]" >
                            <span class="fa-solid fa-circle-minus "></span>
                          </button>
                        </span>
                        <input type="text" name="nambahmakeup" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-makeup-number"  value="0" min="0" max="30" readonly >
                        <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-makeup-plus" data-type="plus" data-field="quant[1]" >
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
            <!-- nambahorang hairdo -->
            <div class="tambah-hairdo ">
              <div class="d-flex flex-row ">
                <!-- nambahorang hairdo -->
                  <label class="text-start mt-4 flex-fill ps-2"  for="flexCheckDefault">
                    Hairdo (250k/orang)
                  </label>
                <div class="d-flex flex-row">  
                  <div class="container" id="jumlah-hairdo" style="height:50px;" >
                    <div class="row">
                      <div class="col-sm-3 mt-3 mb-3 me-3">
                        <div class="input-group" style="width: 100px;" >
                        <span class="input-group-btn" style="width: 30px; height: 30px;">
                          <button type="button" class="btn btn-default btn-hairdo-minus" data-type="minus" data-field="quant[1]" >
                            <span class="fa-solid fa-circle-minus "></span>
                          </button>
                        </span>
                        <input type="text" name="nambahhairdo" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-hairdo-number"  value="0" min="0" max="30" readonly >
                        <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-hairdo-plus" data-type="plus" data-field="quant[1]" >
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


          <div class="tambah-anak ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambahorang anak-anak -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  Anak-anak (35k/orang)
                </label>
              <div class="d-flex flex-row">  
                <div class="container" id="jumlah-anak" style="height:50px;" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-anak-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="nambahanak" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-anak-number"  value="0" min="0" max="30" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-anak-plus" data-type="plus" data-field="quant[1]" >
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

            <div class="tambah-dewasa ">
                <div class="d-flex flex-row justify-content-between m-2">
                  <label class="text-center mt-4" for="flexCheckDefault">
                  Dewasa (50k/orang)
                  </label>
                
                <!-- nambahorang Dewasa -->

              <div class="d-flex flex-row">
                <div class="container jumlah-orang" style="height:50px;">
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;">
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btngede-minus"  data-type="minus" data-field="quant[1]">
                      <span class="fa-solid fa-circle-minus "></span>
                      </button>
                      </span>
                      <input type="text" name="nambahdewasa" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-gede-number" value="0" min="0" max="30" readonly>
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btngede-plus" data-type="plus" data-field="quant[1]">
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
          <div class="ms-2 mt-4 mb-2">
            <p class="fw-bold">Tambah Waktu<span class="text-black-50"> (Perpanjangan Waktu)</span></p>
            <div class="ms-3 mt-2">
              <?php
              // <div class="form-check mt-2">
              //     <input class="form-check-input" type="radio" value="250000" name="hargaovr"  onclick="myF()" >
              //         <label class="form-check-label" for="flexRadioDefault2">
              //           250k / jam
              //         </label>
              // </div>
              ?>
              <div class="form-check mt-3">
                  <input class="form-check-input" type="radio" value="150000" name="hargaovr"  onclick="myF1()">
                      <label class="form-check-label" for="flexRadioDefault2">
                        150k / 30 menit
                      </label>
              </div>
              <div class="form-check mt-3">
                  <input class="form-check-input" type="radio" value="0" name="hargaovr" checked onclick="myF2()">
                      <label class="form-check-label" for="flexRadioDefault2">
                        Tanpa Tambahan Waktu
                      </label>
              </div>
              <input class="form-control"  type="text" value="0" name="waktuovr" id="waktuovr" hidden>
            </div>
          </div>
          <div class="ms-3 me-4 mt-4">
            <label for="jam" class="mb-2 fw-bold">*Tambah Cetak Foto <span class="text-black-50"> (abaikan jika tidak ada penambahan)</span></label>
            <!-- <input type="hidden" class="mb-4" name="cetakfoto[]" id="tanggal" value="0">  -->
            <div class="elem-group inlined"> 
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-5r m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  5R 15k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-5r" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-5r-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga1" value="0" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-5r-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-5rframe m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  5R + Frame 30k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-5rframe" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-5rframe-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga2" value="0"  style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5rframe-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-5rframe-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-10RS m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  10RS 35k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-10RS " >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-10RS-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga3" value="0" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-10RS-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-10RS-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-10RS-frame m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  10RS + Frame 70k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-10RS-frame" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-10RS-frame-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga4" value="0" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-10RS-frame-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-10RS-frame-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-30x40-frame m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  17R (30x40) + Frame + Laminasi canvas 150k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-30x40-frame" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-30x40-frame-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga5" value="0" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-30x40-frame-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-30x40-frame-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-40x60-frame m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  40x60 + Frame + Laminasi canvas 350k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-40x60-frame" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-40x60-frame-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga6" value="0" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-40x60-frame-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-40x60-frame-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-60x90-frame m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  60x90 + Frame + Laminasi canvas 600k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-60x90-frame" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-60x90-frame-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga7" value="0" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-60x90-frame-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-60x90-frame-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-60x100-frame m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  60x100 + Frame + Laminasi canvas 650k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-60x100-frame" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-60x100-frame-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga8" value="0" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-60x100-frame-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-60x100-frame-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
            <div class="tambah-70x100-frame m-1 ">
            <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
              <!-- nambah -->
                <label class="text-center mt-4"  for="flexCheckDefault">
                  70x100 + Frame + Laminasi canvas 700k
                </label>
              <div class="d-flex flex-row">  
                <div id="jumlah-70x100-frame" >
                  <div class="row">
                    <div class="col-sm-3 m-3">
                      <div class="input-group" style="width: 100px;" >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                        <button type="button" class="btn btn-default btn-70x100-frame-minus" data-type="minus" data-field="quant[1]" >
                          <span class="fa-solid fa-circle-minus "></span>
                        </button>
                      </span>
                      <input type="text" name="cetakharga9" value="0" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-70x100-frame-number"   min="0" max="8" readonly >
                      <span class="input-group-btn" style="width: 30px; height: 30px;">
                      <button type="button" class="btn btn-default btn-70x100-frame-plus" data-type="plus" data-field="quant[1]" >
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
            <!------------------------------------------------------------>
            <!------------------------------------------------------------>
          </div>
            
          <div class="form-check">
            <input type="text" name="jumlahcetak1"  autocomplete="off"  class="form-control  text-center" id="textbox1" hidden>
            <input type="text" name="jumlahcetak2"  autocomplete="off"  class="form-control  text-center" id="textbox2" hidden>
            <input type="text" name="jumlahcetak3"  autocomplete="off"  class="form-control  text-center" id="textbox3" hidden>
            <input type="text" name="jumlahcetak4"  autocomplete="off"  class="form-control  text-center" id="textbox4" hidden>
            <input type="text" name="jumlahcetak5"  autocomplete="off"  class="form-control  text-center" id="textbox5" hidden>
            <input type="text" name="jumlahcetak6"  autocomplete="off"  class="form-control  text-center" id="textbox6" hidden>
            <input type="text" name="jumlahcetak7"  autocomplete="off"  class="form-control  text-center" id="textbox7" hidden>
            <input type="text" name="jumlahcetak8"  autocomplete="off"  class="form-control  text-center" id="textbox8" hidden>
            <input type="text" name="jumlahcetak9"  autocomplete="off"  class="form-control  text-center" id="textbox9" hidden>
          </div>
          </div>

          <input type="text" name="cetakfoto1"  autocomplete="off"  class="form-control  text-center" id="box1" hidden>
          <input type="text" name="cetakfoto2"  autocomplete="off"  class="form-control  text-center" id="box2" hidden>
          <input type="text" name="cetakfoto3"  autocomplete="off"  class="form-control  text-center" id="box3" hidden>
          <input type="text" name="cetakfoto4"  autocomplete="off"  class="form-control  text-center" id="box4" hidden>
          <input type="text" name="cetakfoto5"  autocomplete="off"  class="form-control  text-center" id="box5" hidden>
          <input type="text" name="cetakfoto6"  autocomplete="off"  class="form-control  text-center" id="box6" hidden>
          <input type="text" name="cetakfoto7"  autocomplete="off"  class="form-control  text-center" id="box7" hidden>
          <input type="text" name="cetakfoto8"  autocomplete="off"  class="form-control  text-center" id="box8" hidden>
          <input type="text" name="cetakfoto9"  autocomplete="off"  class="form-control  text-center" id="box9" hidden>
          
          <div class="row mb-4">
            <label class="mb-2 fw-bold">*Catatan<span class="text-black-50"> (abaikan jika tidak ada catatan)</span></label>
            <textarea rows="3" name="catatan" style="margin-left:20px; width:250px; resize: none;"></textarea>
          </div>


        </div>

        <div class="jarak">
          <label  class="fw-bold ms-3 mt-3">Pembayaran</label>
        <div class="elem-group inlined ms-3 mt-3">
        <input type="text" class="form-control" id="buktilunas" name="buktilunas" value="0" hidden>
        <input type="text" class="form-control" id="ketbayar" name="ketbayar" value="Terimakasih sudah mengisi data, untuk DP pembayaran kami cek dulu yah, mohon konfirmasi juga admin via Whatsapp" hidden>
        
        <label  class="form-check-label ">Transfer ke Bank <i>BCA</i> <p> 5780785057 A.n. Rizal Satria Agung</p></label><br>
        <p id="down-payment" class="ms-3" >DP Rp 200.000 </p>
      </div>
        </div>
        
        
        <div class="jarak" >
          <label for="upload-img" class="mb-3 mt-3 ms-3 fw-bold">Upload Bukti Transfer<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
                <p class="text-black-50 ms-3" >
                *ukuran max 5 MB </p>
                <p class="text-black-50 ms-3">
                *format gambar .png/.jpg/.jpeg
                </p>
          <div class="elem-group inlined ms-3 ">
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

          
          <div >
            <input type="text" name="tambah1" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="data anda berhasil terkirim, menunggu konfirmasi dari admin" hidden>
            <input type="text" name="gambar1" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="correct.png" min="0" max="8" hidden>
            <input type="text" name="gambar2" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
            <input type="text" name="gambar3" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
            <input type="text" name="gambar4" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
            <input type="text" name="gambar5" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
          </div>
    </form>
  </div>
  <?php }?>
   
<!-- Modal -->
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
              <td><?php  $tanggal1 = date("d-m-Y", strtotime($d["tanggal"]));
                      echo $tanggal1;  ?></td>
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
              <td><?php echo $d["package"];echo " "; echo $d["tipe_package"];  ?></td>

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
</div>
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
    <!-- Modal Pop-up -->
      <div class="modal-overlay" id="modalOverlay">
        <div class="modal-popup">
          <p>Hai kak! 👋<br> mau ngingetin nih khusus <b>Maternity Diamond Package</b> DP-nya <br>
          <b>500rb</b> ya kak <br>
        nanti kalau masih bingung bisa hubungi mimin, nomernya ada di bawah halaman ya kak😊</p>
          <button id="closeModalBtn" class="btn btn-popup btn-outline-dark">Tutup</button>
        </div>
      </div>


    <!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const openModal = document.getElementById('mySelect3');
        const overlay = document.getElementById('modalOverlay');
        const closeBtn = document.getElementById('closeModalBtn');
        const downpayment = document.getElementById('down-payment');
        const buttons = document.querySelectorAll('.package-btn');

        // Modal langsung tampil saat halaman dibuka (sudah diatur display:flex)
        // Fungsi ubah DP
        function ubahCatatan(dp) {
          downpayment.innerHTML = `DP Rp ${dp}.000`;
        }
        // buka modal saat tombol ditekan
        openModal.addEventListener('click', () => {
          overlay.style.display = 'flex';
          
        });
        // Tutup modal saat tombol ditekan
        closeBtn.addEventListener('click', () => {
          overlay.style.display = 'none';
        });

        // klik di luar modal untuk menutup
        overlay.addEventListener('click', (e) => {
          if (e.target === overlay) overlay.style.display = 'none';
        });
        // Tombol ketentuan (1–4)
        buttons.forEach(btn => {
          btn.addEventListener('click', () => {
            const dp = btn.getAttribute('data-dp');
            ubahCatatan(dp); // ubah catatan otomatis sesuai data-dp
          });
        });
      });
    </script>
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
    <!-- Confirm -->
    <script>
      function alert(){
      var result = confirm("Anda yakin sudah mengisi data dengan benar?");
      if (result == false){
        event.preventDefault();
      }
    }
    </script>
   <!-- Cek Jadwal Realtime 
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script type="text/javascript">
  function tanggal(nama){
    var xhttp;
        if (nama != "") {
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("disembunyikantgl").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "cekjadwal.php?nama=" + nama + '' , true);
            xhttp.send();
        }
        }
   </script> -->
   <!--DatePicker Script  -->
    <script type="text/javascript"> 
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

    var dateTomorrow = date + "-" + month + "-" + year ;
    var bookingElem = document.querySelector("#tanggal");

    

    bookingElem.setAttribute("min", dateTomorrow);


    bookingElem.onchange = function () {
    }
   
 </script>
<script type="text/javascript">
    $(document).ready(function(){
      $('.btn-makeup-plus').click(function (e){
        e.preventDefault();
        
        var qty = $('.input-makeup-number').val();
        var value = parseInt(qty,10);
        value = isNaN(value)? 0 : value;
        if(value < 30){
          value++;
          $('.input-makeup-number').val(value);
          
        // document.getElementById("calculation").innerHTML = myFunctionanak(value, 35000);
        
        }
      });

      $('.btn-makeup-minus').click(function (e){
        e.preventDefault();

        var qty = $('.input-makeup-number').val();
        var value = parseInt(qty,10);
        value = isNaN(value)? 0 : value;
        if(value > 0){
          value--;
          $('.input-makeup-number').val(value);
        //  document.getElementById("calculation").innerHTML = myFunctionnanak((value*35000), 0);
        
        }
      });
    });

    $(document).ready(function(){
      $('.btn-hairdo-plus').click(function (e){
        e.preventDefault();
        
        var qty = $('.input-hairdo-number').val();
        var value = parseInt(qty,10);
        value = isNaN(value)? 0 : value;
        if(value < 30){
          value++;
          $('.input-hairdo-number').val(value);
          
        // document.getElementById("calculation").innerHTML = myFunctionanak(value, 35000);
        
        }
      });

      $('.btn-hairdo-minus').click(function (e){
        e.preventDefault();

        var qty = $('.input-hairdo-number').val();
        var value = parseInt(qty,10);
        value = isNaN(value)? 0 : value;
        if(value > 0){
          value--;
          $('.input-hairdo-number').val(value);
        //  document.getElementById("calculation").innerHTML = myFunctionnanak((value*35000), 0);
        
        }
      });
    });
  </script>
 
 <script>
 function org() {
  $('#cetak').val('tidak ada');
  $('#jumlah').val('best deal');
 }
 function org1() {
  $('#cetak').val('bawaan specialpackage');
  $('#jumlah').val('special package');
 }
 function org2() {
  $('#cetak').val('bawaan diamondpackage');
  $('#jumlah').val('diamond package');
 }
</script>
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

 <script>
    function myF(){
      $("#waktuovr").val("1");
      $("#cetakhargaovr").val("250000");

    }
    function myF1(){
      $("#waktuovr").val("30");
      $("#cetakhargaovr").val("150000");
    }
    function myF2(){
      $("#waktuovr").val("0");
      $("#cetakhargaovr").val("0");
    }
    
  </script>
<script type="text/javascript">
      
    function myFunction1() {
      $("#mySelect3").attr('disabled',true);
      $("#mySelect3").prop('checked',false);
      $("#diamond-detail").css("color", "grey");
    //   $(".tambah-makeup").css("color", "grey");
    //   $(".tambah-hairdo").css("color", "grey");
    //   $(".input-makeup-number").val('0');
    //   //$(".input-makeup-number").attr('disabled',true);
    //   $(".btn-makeup-minus").attr('disabled',true);
    //   $(".btn-makeup-plus").attr('disabled',true);
    //   $(".input-hairdo-number").val('0');
    //   //$(".input-hairdo-number").attr('disabled',true);
    //   $(".btn-hairdo-minus").attr('disabled',true);
    //   $(".btn-hairdo-plus").attr('disabled',true);
    }
    function myFunction2() {
      $("#mySelect3").attr('disabled',true);
      $("#mySelect3").prop('checked',false);
      $("#diamond-detail").css("color", "grey");
    //   $(".tambah-makeup").css("color", "grey");
    //   $(".tambah-hairdo").css("color", "grey");
    //   $(".input-makeup-number").val('0');
    //   //$(".input-makeup-number").attr('disabled',true);
    //   $(".btn-makeup-minus").attr('disabled',true);
    //   $(".btn-makeup-plus").attr('disabled',true);
    //   $(".input-hairdo-number").val('0');
    //   //$(".input-hairdo-number").attr('disabled',true);
    //   $(".btn-hairdo-minus").attr('disabled',true);
    //   $(".btn-hairdo-plus").attr('disabled',true);
    }
    function myFunction3() {
      $("#mySelect3").prop('disabled',false);
      $("#diamond-detail").css("color", "black");
    //   $(".tambah-makeup").css("color", "black");
    //   $(".tambah-hairdo").css("color", "black");
    //   $(".input-makeup-number").attr('disabled',false);
    //   $(".btn-makeup-minus").attr('disabled',false);
    //   $(".btn-makeup-plus").attr('disabled',false);
    //   $(".input-hairdo-number").attr('disabled',false);
    //   $(".btn-hairdo-minus").attr('disabled',false);
    //   $(".btn-hairdo-plus").attr('disabled',false);
    }
    function myFunction4() {
      $("#mySelect3").attr('disabled',true);
      $("#mySelect3").prop('checked',false);
      $("#diamond-detail").css("color", "grey");
    //   $(".tambah-makeup").css("color", "grey");
    //   $(".tambah-hairdo").css("color", "grey");
    //   $(".input-makeup-number").val('0');
    //   //$(".input-makeup-number").attr('disabled',true);
    //   $(".btn-makeup-minus").attr('disabled',true);
    //   $(".btn-makeup-plus").attr('disabled',true);
    //   $(".input-hairdo-number").val('0');
    //   //$(".input-hairdo-number").attr('disabled',true);
    //   $(".btn-hairdo-minus").attr('disabled',true);
    //   $(".btn-hairdo-plus").attr('disabled',true);
}
function myFunction5() {
  $("#mySelect3").attr('disabled',false);
  $("#diamond-detail").css("color", "black");
  
}

</script>


   <!--Auto increament quantity orang foto-->


 <!--Auto increament quantity tambah orang anak-anak-->
<script type="text/javascript">
  function myFunctionanak(p1, p2) {
   return p1 * p2;
}
  function myFunctionnanak(p1, p2) {
   return p1 - p2;
}

    
      $(document).ready(function(){
        $('.btn-anak-plus').click(function (e){
          e.preventDefault();
          
          var qty = $('.input-anak-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 30){
            value++;
            $('.input-anak-number').val(value);
            
           // document.getElementById("calculation").innerHTML = myFunctionanak(value, 35000);
           
          }
        });

        $('.btn-anak-minus').click(function (e){
          e.preventDefault();

          var qty = $('.input-anak-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-anak-number').val(value);
           //  document.getElementById("calculation").innerHTML = myFunctionnanak((value*35000), 0);
           
          }
        });
      });
    </script>

 <!--Auto increament quantity tambah orang dewasa-->
  <script type="text/javascript">
//   function myFunctiongede(p1, p2) {
//    return p1 * p2;
// }
//   function myFunctionngede(p1, p2) {
//    return p1 - p2;
// }

    $(document).ready(function(){
      $('.btngede-plus').click(function (e){
        e.preventDefault();
        
        var qty = $('.input-gede-number').val();
        var value = parseInt(qty,10);
        value = isNaN(value)? 0 : value;
        if(value < 30){
          value++;
          $('.input-gede-number').val(value);
          
          // document.getElementById("calculation").innerHTML = myFunctiongede(value, 50000);
          
        }
      });

      $('.btngede-minus').click(function (e){
        e.preventDefault();

        var qty = $('.input-gede-number').val();
        var value = parseInt(qty,10);
        value = isNaN(value)? 0 : value;
        if(value > 0){
          value--;
          $('.input-gede-number').val(value);
          // document.getElementById("calculation").innerHTML = myFunctionngede((value*50000), 0);
          
        }
      });
    });
  </script>
    <script type="text/javascript">
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-5r-number').val(value);
            $('#box1').val('(5R)=');
            $('#textbox1').val(value);
            
          }
        });
        
        $('.btn-5r-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-5r-number').val(value);
            $('#textbox1').val(value);
            if(value == 0){
            $('#box1').val('');
            $('#textbox1').val('');
          }
          }
        });
      });
      </script>
      <script type="text/javascript">
      ////////////////CETAK 5Rframe//////////////////////
      $(document).ready(function(){
        $('.btn-5rframe-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5rframe-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-5rframe-number').val(value);
            $('#box2').val(' (5R+frame)=');
            $('#textbox2').val(value);
          }
        });

        $('.btn-5rframe-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5rframe-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-5rframe-number').val(value);
            $('#textbox2').val(value);
            if(value == 0){
            $('#box2').val('');
            $('#textbox2').val('');
          }
          }
        });
      });
      </script>
      <script type="text/javascript">
      ////////////////CETAK 10RS//////////////////////
      $(document).ready(function(){
        $('.btn-10RS-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-10RS-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-10RS-number').val(value);
            $('#box3').val(' (10RS)=');
            $('#textbox3').val(value);
          }
        });

        $('.btn-10RS-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-10RS-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-10RS-number').val(value);
            $('#textbox3').val(value);
            if(value == 0){
            $('#box3').val('');
            $('#textbox3').val('');
          }
          }
        });
      });
      </script>
      <script type="text/javascript">
      ////////////////CETAK 10RS-frame//////////////////////
      $(document).ready(function(){
        $('.btn-10RS-frame-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-10RS-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-10RS-frame-number').val(value);
            $('#box4').val(' (10RS+frame)=');
            $('#textbox4').val(value);
          }
        });

        $('.btn-10RS-frame-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-10RS-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-10RS-frame-number').val(value);
            $('#textbox4').val(value);
            if(value == 0){
            $('#box4').val('');
            $('#textbox4').val('');
          }
          }
        });
      });
      </script>
      <script type="text/javascript">
      ////////////////CETAK 17R 30x40//////////////////////
      $(document).ready(function(){
        $('.btn-30x40-frame-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-30x40-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-30x40-frame-number').val(value);
            $('#box5').val(' (17R/30x40+Frame)=');
            $('#textbox5').val(value);
          }
        });

        $('.btn-30x40-frame-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-30x40-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-30x40-frame-number').val(value);
            $('#textbox5').val(value);
            if(value == 0){
            $('#box5').val('');
            $('#textbox5').val('');
          }
          }
        });
      });
      </script>
      <script type="text/javascript">
      ////////////////CETAK 40x60//////////////////////
      $(document).ready(function(){
        $('.btn-40x60-frame-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-40x60-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-40x60-frame-number').val(value);
            $('#box6').val(' (40x60+Frame)=');
            $('#textbox6').val(value);
          }
        });

        $('.btn-40x60-frame-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-40x60-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-40x60-frame-number').val(value);
            $('#textbox6').val(value);
            if(value == 0){
            $('#box6').val('');
            $('#textbox6').val('');
          }
          }
        });
      });
      </script>
      <script type="text/javascript">
      ////////////////CETAK 60x90//////////////////////
      $(document).ready(function(){
        $('.btn-60x90-frame-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-60x90-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-60x90-frame-number').val(value);
            $('#box7').val(' (60x90+Frame)=');
            $('#textbox7').val(value);
          }
        });

        $('.btn-60x90-frame-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-60x90-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-60x90-frame-number').val(value);
            $('#textbox7').val(value);
            if(value == 0){
            $('#box7').val('');
            $('#textbox7').val('');
          }
          }
        });
      });
      </script>
      <script type="text/javascript">
      ////////////////CETAK 60x100//////////////////////
      $(document).ready(function(){
        $('.btn-60x100-frame-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-60x100-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-60x100-frame-number').val(value);
            $('#box8').val(' (60x100+Frame)=');
            $('#textbox8').val(value);
          }
        });

        $('.btn-60x100-frame-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-60x100-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-60x100-frame-number').val(value);
            $('#textbox8').val(value);
           if(value == 0){
            $('#box8').val('');
            $('#textbox8').val('');
          }
          }
        });
      });
      </script>
      <script type="text/javascript">
      ////////////////CETAK 70x100//////////////////////
      $(document).ready(function(){
        $('.btn-70x100-frame-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-70x100-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 1000){
            value++;
            $('.input-70x100-frame-number').val(value);
            $('#box9').val(' (70x100+Frame)=');
            $('#textbox9').val(value);
          }
        });

        $('.btn-70x100-frame-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-70x100-frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-70x100-frame-number').val(value);
            $('#textbox9').val(value);
           if(value == 0){
            $('#box9').val('');
            $('#textbox9').val('');
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

  </body>
  </html>