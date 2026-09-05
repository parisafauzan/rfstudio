<?php
//cek tombol submit
require '../function.php';


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    
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
          <a href="index.php" class="text-dark" style="text-decoration:none;">
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
      </style>

    <!-- Ini bagian atas body Form -->  
<div class="jumbotron ">
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-5 ms-3" style="justify-content: center;">
          <li class="breadcrumb-item"><a href="../index.php " class="text-dark" style="text-decoration: none;">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Isi Formulir</li> 
        </ol>
      </nav>
<div class="container-md-5 border" >
  <div class="d-inline-flex ps-1 pe-1 text-white bg-dark " style="width: 200px; height: 60px; " > 
      <h5 class="product-title text-center mt-3 mx-auto">Booking Jadwal</h5></div>

 <?php

    if(isset($_POST["submit"])){
  //cek apakah data berhasil disimpan atau tidak
 var_dump($_POST);
//     if(tambah($_POST)>0){
//    echo '<script language="javascript">';
//         echo 'function alert() {';
//         echo 'document.getElementById("myForm").reset()};';
//         echo 'window.location = "berhasilbooking.php"';
//         echo '</script>';
//   //echo '<meta http-equiv="refresh" content="3;url=../index.php">';

//     }else{

//       echo '<script language="javascript">';
//           echo 'window.location = "bookingbs.php"';
//           echo '</script>';
//     }exit;
   
  };

?>

<form id="myForm" method="post" action="" enctype="multipart/form-data">
  <div class="jarak  ">
    <label for="nama" class="form-label fw-bold">Nama<span class="fw-light text-danger">*</span></label>
    <input type="text"  class="form-control"  id="nama" name="nama" placeholder="Masukkan Nama Anda" autocomplete="off" required>
    <div class="invalid-feedback">Please fill out this field.</div>   
  </div>
    <div class="jarak  ">
    <label for="nomer" class="form-label fw-bold">Nomor Telepon<span class="fw-light text-danger">*</span></label>
    <input type="tel"  class="form-control"  id="notelp" maxlength="13" minlength="10" name="notelp" autocomplete="off" placeholder="081234567890" required>
    </div>
    <div class="jarak ">
    <label for="email" class="form-label fw-bold">Email<span class="fw-light text-danger">*</span></label>
    <input type="email"  class="form-control"  id="email" name="email"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" autocomplete="off" placeholder="masukanemail.anda@gmail.com" required>
    </div>

    <!----------------------------------------------------------------->
    <div class="row row-sm-5 row-md-5 row-lg-5   mx-auto mt-3 mb-3 " style="justify-content: center;">
      <div class="col-md-5 ms-4">
        <div class="row">
          <div class="col mt-3 p-2">
            <label for="nomer" class="form-label fw-bold">Pilih Package<span class="fw-light text-danger">*</span></label>
            <div class="form-check mt-3">
              <input class="form-check-input" type="radio" value="family" name="package" id="best-deal" onclick="myFunction1()" required>
                <label class="form-check-label" for="flexRadioDefault1">
                  Family Package
                </label>
            </div>
            <div class="form-check mt-2">
            <input class="form-check-input" type="radio" value="graduation" name="package" onclick="myFunction1()"  required>
                <label class="form-check-label" for="flexRadioDefault2">
                  Graduation Package 
                </label>
            </div>
            <div class="form-check mt-2">
            <input class="form-check-input" type="radio" value="maternity" name="package" onclick="myFunction1()"  required>
                <label class="form-check-label" for="flexRadioDefault2">
                  Maternitiy Package 
                </label>
            </div>
            <div class="form-check mt-2">
            <input class="form-check-input" type="radio" value="group" name="package" onclick="myFunction1()" required>
                <label class="form-check-label" for="flexRadioDefault2">
                  Group Package 
                </label>
              </div>
            </div>
        <div class="row">
        <div class="col border mt-3 p-2">
          <div class="form-check mt-2">
            <input class="form-check-input sp" type="radio" value="800000" name="harga" id="mySelect1a" onclick="org1()" required>
            <label class="form-check-label" for="flexRadioDefault2">
            SPECIAL PACKAGE<span class="fw-light text-danger">*</span> <label class="text-decoration-line-through text-black-50"> 999k</label> 800k
            </label>
            <!-- <input class="form-check-input" type="text" name="cetakfoto" hidden> -->
              <p class="text-black-50" style="font-size:small;margin-top:10px;">2 Background Photo</p>
              <p class="text-black-50" style="font-size:small;">1 s/d 2 jam Photo Session</p>
              <p class="text-black-50" style="font-size:small;">1 cetak Canvas + Frame ukuran 17R / kalau sudah di pasang frame ukurannya 40cm x 50 cm</p>
              <p class="text-black-50" style="font-size:small;">5 pcs cetak ukuran 5R (tanpa frame)</p>
              <p class="text-black-50" style="font-size:small;">Free 15-20 Photo edit tone warna</p>
              <p class="text-black-50" style="font-size:small;">Foto unlimited / sepuasnya</p>
              <p class="text-black-50" style="font-size:small;">All softcopy on drive</p>
              <p class="text-black-50 fw-bold" style="font-size:small;">Max 8 Orang</p>
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
            <input class="form-check-input bsd" type="radio" value="500000" name="harga" id="mySelect1" onclick="org()" required>
            <label class="form-check-label" for="flexRadioDefault2">
            BEST DEAL PACKAGE<span class="fw-light text-danger">*</span> <label class="text-decoration-line-through text-black-50"> 699k</label> 500k
            </label>
            <!-- <input class="form-check-input" type="text" name="cetakfoto" hidden> -->
              <p class="text-black-50" style="font-size:small;margin-top:10px;">2 Background Photo</p>
              <p class="text-black-50" style="font-size:small;">1 s/d 2 jam Photo Session</p>              
              <p class="text-black-50" style="font-size:small;">Free 10-15 Photo edit tone warna</p>
              <p class="text-black-50" style="font-size:small;">Foto unlimited / sepuasnya</p>
              <p class="text-black-50" style="font-size:small;">All softcopy on drive</p>
              <p class="text-black-50 fw-bold" style="font-size:small;">Max 6 Orang</p>
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

        
    <div class="form-check ">
      <input class="form-control bsd" type="text" id="cetak" name="cetak" hidden>
      <!-- <input class="form-control sp" type="text" id="sp" name="cetak"> -->
    </div>

    <div class="ms-5 " hidden>
       <div class="row">
      <div class=" bs-package p-2" >
         <label for="upload-img" class="ms-3 mb-3 fw-bold">Jumlah Orang<span class="fw-light text-danger">*</span></label>
        <div class="col p-2">
          <div class="form-check mt-2" >
            <input class="form-control bsd" type="text" value="best deal"  name="jumlah" id="enmorg" disabled >
            

          </div>
          </div>
          <div class="col  p-2">
          <div class="form-check ">
            <input class="form-control sp" type="text" value="special package" name="jumlah" id="dlpnorg" disabled >
            
          </div>
      </div>
      </div>
      </div>
       </div>
     

    <div class="jarak ms-5 ">
      
       <p for="tanggal" class="mb-3 fw-bold">Pilih Tanggal Booking<span class="fw-light text-danger">*</span></p>
      <label for="tanggal" class="mb-3 text-black-50">*disarankan untuk cek jadwal terlebih dahulu</label>
       <div class="elem-group inlined">
        <input type="date" class="mb-4" name="tanggal" id="tanggal" value=""  required> 
         <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">cek jadwal</button>
        <span id="disembunyikantgl"></span>
      </div>
    </div>

    <div class="jarak ms-5 ">
      <label for="jam" class="mb-3 fw-bold">Pilih Jam<span class="fw-light text-danger">*</span></label>
      <label for="jam" class="mb-3 text-black-50" style="font-size: 14px;">*datang 1-2 jam sebelum photoshoot</label>
      <div class="row">
        <div class="col">

       
      <div class="elem-group inlined"> 
        <select class="form-select form-select-md mb-3" name="jam" id="jam" aria-label=".form-select-lg example" style="max-width: 300px;" required>
          <option selected></option>
          <option value="08:00 - 10:00">08:00-10:00</option>
          <option value="10:00 - 12:00">10:00-12:00</option>
          <option value="12:00 - 14:00">12:00-14:00</option>
          <option value="14:00 - 16:00">14:00-16:00</option>
          <option value="19:00 - 21:00">19:00-21:00</option>
        </select>
      </div>
      
       </div>
      </div>
    </div>


     <div class="row row-sm-5 row-md-5 row-lg-5 border ms-4 me-4 " id="nambah" style="justify-content: center; margin-left:20px; margin-right:20px;">
      <label class="form-check-label jarak  " for="flexCheckDefault" >
           *Penambahan <span class="text-black-50"> (abaikan jika tidak ada penambahan)</span>
           <p>*Diatas 8 orang pakai studio 3 (Naik tangga)</p>
          </label>
      <span class="text-black-50"> (abaikan jika tidak ada penambahan)</span>
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
                  <input type="text" name="nambahanak" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-anak-number"  value="0" min="0" max="8" readonly >
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
                  <input type="text" name="nambahdewasa" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-gede-number" value="0" min="0" max="8" readonly>
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
      
      <div class="ms-5 me-4 mt-4">
         <label for="jam" class="mb-2 fw-bold">*Tambah Cetak Foto <span class="text-black-50"> (abaikan jika tidak ada penambahan)</span></label>
         <input type="hidden" class="mb-4" name="cetakfoto[]" id="tanggal" value="0"> 
        <div class="elem-group inlined"> 
        <!------------------------------------------------------------>
        <!------------------------------------------------------------>
        <div class="tambah-5r m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R 10k
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
                  <input type="text" name="nambah5r" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"  value="0" min="0" max="8" readonly >
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
        <!------------------------------------------------------------>
        <!------------------------------------------------------------>
        <div class="tambah-5r+frame m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R + Frame 10k
            </label>
          <div class="d-flex flex-row">  
            <div id="jumlah-5r+frame" >
              <div class="row">
                <div class="col-sm-3 m-3">
                  <div class="input-group" style="width: 100px;" >
                  <span class="input-group-btn" style="width: 30px; height: 30px;">
                    <button type="button" class="btn btn-default btn-5r+frame-minus" data-type="minus" data-field="quant[1]" >
                      <span class="fa-solid fa-circle-minus "></span>
                    </button>
                  </span>
                  <input type="text" name="nambah5r+frame" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r+frame-number"  value="0" min="0" max="8" readonly >
                  <span class="input-group-btn" style="width: 30px; height: 30px;">
                   <button type="button" class="btn btn-default btn-5r+frame-plus" data-type="plus" data-field="quant[1]" >
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
        <div class="tambah-5r m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R 10k
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
                  <input type="text" name="nambah5r" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"  value="0" min="0" max="8" readonly >
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
        <!------------------------------------------------------------>
        <!------------------------------------------------------------>
        <div class="tambah-5r m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R 10k
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
                  <input type="text" name="nambah5r" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"  value="0" min="0" max="8" readonly >
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
        <!------------------------------------------------------------>
        <!------------------------------------------------------------>
        <div class="tambah-5r m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R 10k
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
                  <input type="text" name="nambah5r" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"  value="0" min="0" max="8" readonly >
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
        <!------------------------------------------------------------>
        <!------------------------------------------------------------>
        <div class="tambah-5r m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R 10k
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
                  <input type="text" name="nambah5r" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"  value="0" min="0" max="8" readonly >
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
        <!------------------------------------------------------------>
        <!------------------------------------------------------------>
        <div class="tambah-5r m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R 10k
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
                  <input type="text" name="nambah5r" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"  value="0" min="0" max="8" readonly >
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
        <!------------------------------------------------------------>
        <!------------------------------------------------------------>
        <div class="tambah-5r m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R 10k
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
                  <input type="text" name="nambah5r" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"  value="0" min="0" max="8" readonly >
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
        <!------------------------------------------------------------>
        <!------------------------------------------------------------>
        <div class="tambah-5r m-1 border">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
          <!-- nambah -->
            <label class="text-center mt-4"  for="flexCheckDefault">
              5R 10k
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
                  <input type="text" name="nambah5r" style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-5r-number"  value="0" min="0" max="8" readonly >
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

        <input class="form-check-input limarf mb-2" type="checkbox"  name="cetakfoto[]"  id="limarf"> 5R + frame 30k<br>
        <input class="form-check-input sepur mb-2" type="checkbox"  name="cetakfoto[]" id="sepur"> 10RS 20k<br>
        <input class="form-check-input sepurf mb-2" type="checkbox"  name="cetakfoto[]" id="sepurf"> 10RS + frame 70k<br>
        <input class="form-check-input txefc mb-2" type="checkbox"  name="cetakfoto[]" id="txefc"> 30x40 + frame + laminasi canvas 150k<br>
        <input class="form-check-input exefc mb-2" type="checkbox"  name="cetakfoto[]"  id="exefc"> 40x60 + frame + laminasi canvas 350k<br>
        <input class="form-check-input exsfc mb-2" type="checkbox"  name="cetakfoto[]" id="exsfc"> 60x90 + frame + laminasi canvas 600k<br>
        <input class="form-check-input exserfc mb-2" type="checkbox"  name="cetakfoto[]" id="exserfc"> 60x100 + frame + laminasi canvas 650k<br>
        <input class="form-check-input txserfc mb-2" type="checkbox"  name="cetakfoto[]" id="txserfc"> 70x100 + frame + laminasi canvas 700k<br>
      </div>
        
      <div class="form-check">
        <input type="text" name="cetakharga1"  autocomplete="off"  class="form-control  text-center" id="textbox1" hidden >
        <input type="text" name="cetakharga2"  autocomplete="off"  class="form-control  text-center" id="textbox2" hidden >
        <input type="text" name="cetakharga3"  autocomplete="off"  class="form-control  text-center" id="textbox3" hidden>
        <input type="text" name="cetakharga4"  autocomplete="off"  class="form-control  text-center" id="textbox4" hidden>
        <input type="text" name="cetakharga5"  autocomplete="off"  class="form-control  text-center" id="textbox5" hidden>
        <input type="text" name="cetakharga6"  autocomplete="off"  class="form-control  text-center" id="textbox6" hidden>
        <input type="text" name="cetakharga7"  autocomplete="off"  class="form-control  text-center" id="textbox7" hidden>
        <input type="text" name="cetakharga8"  autocomplete="off"  class="form-control  text-center" id="textbox8" hidden>
        <input type="text" name="cetakharga9"  autocomplete="off"  class="form-control  text-center" id="textbox9" hidden>
      </div>
      </div>

      <input type="text" name="box"  autocomplete="off"  class="form-control  text-center" id="box">
      
      <div class="row mb-4">
        <label class="mb-2 fw-bold">*Catatan<span class="text-black-50"> (abaikan jika tidak ada catatan)</span></label>
        <textarea rows="3" name="catatan" style="margin-left:20px; width:250px; resize: none;"></textarea>
      </div>


    </div>

    <div class="jarak">
      <label  class="fw-bold ms-3 mt-3">Pembayaran<span class="fw-light text-danger">*</span></label>
    <div class="elem-group inlined ms-3 mt-3">
     <input type="radio" class="form-check-input" id="buktilunas" name="buktilunas" value="belum lunas" required>
     <input type="text" class="form-control" id="ketbayar" name="ketbayar" value="Terimakasih sudah melakukan pembayaran dp sebesar Rp. 200.000, untuk memastikan silahkan hubungi admin via Whatsapp" hidden>
     
      <label  class="form-check-label ms-3">DP Rp200.000</label>
    </div>
    </div>
     <label  class="form-check-label  jarak">Transfer ke Bank <i>BCA</i> <p> 5780785057 A.n. Rizal Satria Agung</p></label>
    
    <div class="jarak" >
       <label for="upload-img" class="mb-3 mt-3 fw-bold">Upload Bukti Transfer<span class="fw-light text-danger">*</span></label>
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

    <div class="text-center">
      <button type="submit" name="submit" class="btn btn-lg btn-outline-dark mt-2 mb-5" onclick="alert()" >Booking</button>
      </div>

      <p class="mt-4 ms-3 mb-4">
            <span class="text-danger">*</span> wajib diisi </p>
      <div >
        <input type="text" name="tambah1" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="data anda berhasil terkirim, menunggu konfirmasi dari admin" hidden>
        <input type="text" name="gambar1" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="correct.png" min="0" max="8" hidden>
        <input type="text" name="gambar2" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
        <input type="text" name="gambar3" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
        <input type="text" name="gambar4" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
        <input type="text" name="gambar5" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="circle.png" min="0" max="8" hidden>
      </div>
</form>
   
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
						<th style="width: 10%;">Nama</th>
						<th style="width: 15%;">Package</th>
						<th style="width: 20%;">Tanggal</th>
						<th style="width: 20%;">Jam</th>
                        
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

					$data = mysqli_query($koneksi, "SELECT nama,package,tanggal,jam,jumlah_orang FROM data_booking_cobahampirfinishjuga UNION SELECT nama,package,tanggal,jam,jumlah_orang FROM data_konfirmasi_cobahampirfinishjuga ORDER BY tanggal,jam DESC");
					$jumlah_data = mysqli_num_rows($data);
					$total_halaman = ceil($jumlah_data / $batas);

					$data_konfirmasi = mysqli_query($koneksi,"SELECT nama,package,tanggal,jam,jumlah_orang FROM data_booking_cobahampirfinishjuga UNION SELECT nama,package,tanggal,jam,jumlah_orang FROM data_konfirmasi_cobahampirfinishjuga ORDER BY tanggal,jam DESC LIMIT $halaman_awal, $batas");
					$nomor = $halaman_awal+1;
					while($d = mysqli_fetch_array($data_konfirmasi)){
						?>
					<tr>
						<td><?php echo $d["nama"];  ?></td>
                        <td><?php echo $d["package"];echo " "; echo $d["jumlah_orang"];  ?></td>
                        <td><?php echo $d["tanggal"];  ?></td>
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
              }else if($d["package"]== "cetak foto"){
                echo "--";
              }else{
                echo $d["jam"];
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
</div>
          </div>
    <!-- Ini bagian akhir body Form -->

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
                <a href="adminlogin.php" class="fadmin " style="text-decoration: none; color:#fff;">
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

    var dateTomorrow = year + "-" + month + "-" + date;
    var bookingElem = document.querySelector("#tanggal");
    

    bookingElem.setAttribute("min", dateTomorrow);

    bookingElem.onchange = function () {
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

<script type="text/javascript">
function myFunction1() {
  document.getElementById("mySelect1").disabled = false;
  document.getElementById("mySelect1a").disabled = false;
  document.getElementById("mySelect2").disabled = true;
  document.getElementById("mySelect2a").disabled = true;

}
function myFunction2() {
  document.getElementById("mySelect1").disabled = true;
  document.getElementById("mySelect1a").disabled = true;
  document.getElementById("mySelect2").disabled = false;
  document.getElementById("mySelect2a").disabled = false;
  document.getElementById("mySelect3").disabled = true;
  document.getElementById("mySelect3a").disabled = true;
  document.getElementById("mySelect3b").disabled = true;
}
function myFunction3() {
  document.getElementById("mySelect1").disabled = true;
  document.getElementById("mySelect1a").disabled = true;
  document.getElementById("mySelect2").disabled = true;
  document.getElementById("mySelect2a").disabled = true;
  document.getElementById("mySelect3").disabled = false;
  document.getElementById("mySelect3a").disabled = false;
  document.getElementById("mySelect3b").disabled = false;
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
          if(value < 10){
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
  function myFunctiongede(p1, p2) {
   return p1 * p2;
}
  function myFunctionngede(p1, p2) {
   return p1 - p2;
}

      $(document).ready(function(){
        $('.btn-plus').click(function (e){
          e.preventDefault();
          
          var qty = $('.input-gede-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
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
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r-number').val(value);
            $('#box').val('cetak5R');
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
            $('#box').val(' ');
          }
        });
      });
      ////////////////CETAK 5R+Frame//////////////////////
      $(document).ready(function(){
        $('.btn-5r+frame-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r+frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r+frame-number').val(value);
            $('#box').val('cetak5R+Frame');
          }
        });

        $('.btn-5r+frame-minus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r+frame-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value > 0){
            value--;
            $('.input-5r+frame-number').val(value);
            $('#box').val(' ');
          }
        });
      });
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r-number').val(value);
            $('#box').val('cetak5r');
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
            $('#box').val(' ');
          }
        });
      });
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r-number').val(value);
            $('#box').val('cetak5r');
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
            $('#box').val(' ');
          }
        });
      });
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r-number').val(value);
            $('#box').val('cetak5r');
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
            $('#box').val(' ');
          }
        });
      });
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r-number').val(value);
            $('#box').val('cetak5r');
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
            $('#box').val(' ');
          }
        });
      });
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r-number').val(value);
            $('#box').val('cetak5r');
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
            $('#box').val(' ');
          }
        });
      });
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r-number').val(value);
            $('#box').val('cetak5r');
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
            $('#box').val(' ');
          }
        });
      });
      ////////////////CETAK 5R//////////////////////
      $(document).ready(function(){
        $('.btn-5r-plus').click(function (e){
          e.preventDefault();
          var qty = $('.input-5r-number').val();
          var value = parseInt(qty,10);
          value = isNaN(value)? 0 : value;
          if(value < 10){
            value++;
            $('.input-5r-number').val(value);
            $('#box').val('cetak5r');
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
            $('#box').val(' ');
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