<?php
//cek tombol submit
require '../function.php';


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
    <title>Cetak Foto</title>
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
      <h5 class="product-title text-center mt-3 mx-auto">Cetak Foto</h5></div>

 <?php

    if(isset($_POST["submit"])){
  //cek apakah data berhasil disimpan atau tidak
  //var_dump($_POST);
    if(tambahcetakfoto($_POST)>0){
         echo '<script language="javascript">';
        echo 'function alert() {';
        echo 'document.getElementById("myForm").reset()};';
        echo 'window.location = "berhasilbooking.php";';
        echo '</script>';
  //echo '<meta http-equiv="refresh" content="3;url=../index.php">';

    }else{

          echo '<script language="javascript">';
          echo 'window.location = "cetak-foto.php";';
          echo '</script>';
    }exit;
   };

?>

<div style="font-size: 14px;">
<form id="myForm" method="post" action="" enctype="multipart/form-data">
  <div class="jarak  ">
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
    <input type="email"  class="form-control"  id="email" name="email"  pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.com" autocomplete="off" placeholder="masukanemail.anda@gmail.com" required>
    </div>

    

    <div class="form-check " hidden>
      <input type="text" name="nambahorang" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="0" min="0" max="8" hidden>
      <input type="text" name="nambahwaktu" style="background-color: white;"  autocomplete="off" placeholder="0" class="form-control  text-center" value="0" min="0" max="8" hidden>
    </div>

        
    <div class="form-check " hidden>
      <input class="form-control bsd" type="text" id="cetak" name="cetak" value="cetak foto">
      <!-- <input class="form-control sp" type="text" id="sp" name="cetak"> -->
    </div>

    <div class="ms-5 " hidden>
       <div class="row">
      <div class=" bs-package p-2" >
         <label for="upload-img" class="ms-3 mb-3 fw-bold">Jumlah Orang<span class="fw-light text-danger">*</span></label>
        <div class="col p-2">
          <div class="form-check mt-2" >
            <input class="form-control bsd" type="text" value="cetak foto"  name="jumlah"   >
            

          </div>
          </div>
          <div class="col  p-2">
          <div class="form-check ">
            <!-- <input class="form-control sp" type="text" value="special package" name="jumlah" id="dlpnorg" disabled > -->
            
          </div>
      </div>
      </div>
      </div>
       </div>
     

    <div class="jarak ms-5 " hidden>
      
       <p for="tanggal" class="mb-3 fw-bold">Pilih Tanggal Pengambilan<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></p>
      <!-- <label for="tanggal" class="mb-3 text-black-50">*disarankan untuk cek jadwal terlebih dahulu</label> -->
       <div class="elem-group inlined">
        <input type="text" class="mb-4" name="tanggal" placeholder="-- Pilih Tanggal --" id="tanggal" onfocus="(this.type='date')" value="0"  >

         <!-- <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">cek jadwal</button> -->
        <span id="disembunyikantgl"></span>
      </div>
    </div>

    <div class="jarak ms-5 " hidden>
      <label for="jam" class="mb-3 fw-bold">Pilih Jam Pengambilan<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label><br>
      <!-- <label for="jam" class="mb-3 text-black-50" style="font-size: 14px;">datang 15-30 menit sebelum photoshoot</label>
      <p for="jam" class="mb-3 text-black-50" style="font-size: 14px;">jam photoshoot mengikuti jam booking</p>
      <p for="jam" class="mb-3 text-black-50" style="font-size: 14px;">keterlambatan diluar tanggung jawab kami</p> -->
      <div class="row">
        <div class="col">

       
      <div class="elem-group inlined"> 
        <input type="text" name="jam" id="jam" aria-label=".form-select-lg example" value="0" style="max-width: 300px;" >
        <!-- <select class="form-select form-select-md mb-3" name="jam" id="jam" aria-label=".form-select-lg example" style="max-width: 300px;" required>
          <option selected class="text-black-50">-- Pilih Jam Pengambilan--</option>
          <option value="08:00-09:00">08:00-09:00</option>
          <option value="09:30-10:30">09:30-10:30</option>
          <option value="11:00-12:00">11:00-12:00</option>
          <option value="12:30-13:30">12:30-13:30</option>
          <option value="14:00-15:00">14:00-15:00</option>
          <option value="15:30-16:30">15:30-16:30</option>
          <option value="17:00-18:00">17:00-18:00</option>
          <option value="18:30-19:30">18:30-19:30</option>
          <option value="20:00-21:00">20:00-21:00</option>
          
        </select> -->
      </div>
      
       </div>
      </div>
    </div>
    
    <div class="jarak ms-5" hidden>
      <label class="mb-2 fw-bold">Jumlah Orang yang foto ke Studio<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
      <p  class="mb-3 text-black-50" style="font-size: 14px;">jika salah satu kosong, isikan 0</p>
      <div class="row" style="max-width:200px;">
        <div class="col input">
          <p for="jam" style="padding-top:10px;font-size: 14px;">Dewasa</p>
        </div>
        <div class="col input">
          <input type="tel" style="max-width: 50px;" value="0"  class="form-control"  id="jumlahorangdewasa" name="jumlahorangdewasa" placeholder="" minlength="1" maxlength="2"  autocomplete="off" >
        </div>
      </div>

      <div class="row" style="max-width:200px;">
        <div class="col input">
          <p for="jam" style="padding-top:10px;font-size: 14px;">Anak-anak</p>
        </div>
        <div class="col input">
          <input type="tel" style="max-width: 50px;" value="0" class="form-control"  id="jumlahoranganak" name="jumlahoranganak" placeholder="" minlength="1" maxlength="2"  autocomplete="off" >
        </div>
      </div>
    </div>
    <input type="text" name="nambahdewasa" hidden style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-gede-number" value="0" min="0" max="30" readonly>
    <input type="text" name="nambahanak" hidden style="border:none;padding-left:10px;margin-left:10px;background-color: white; width: 30px; height: 30px;"  autocomplete="off" placeholder="0" class="input-anak-number"  value="0" min="0" max="30" readonly >
     <div class="row row-sm-5 row-md-5 row-lg-5 border ms-3 me-3 " id="nambah" style="justify-content: center; margin-left:20px; margin-right:20px;">
      <!-- <label class="form-check-label jarak  " for="flexCheckDefault" >
           *Penambahan <span class="text-black-50"> (abaikan jika tidak ada penambahan)</span>
           <p>*Diatas 8 orang pakai studio 3 (Naik tangga)</p>
          </label>
      <span class="text-black-50"> (abaikan jika tidak ada penambahan)</span>
      <div class="tambah-anak ">
        <div class="d-flex flex-row justify-content-between me-2 ms-2 ">
         
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
       -->
      <div class="ms-4 me-4 mt-4">
         <label for="jam" class="ms-4 mb-2 fw-bold">Daftar Cetak Foto </label>
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
        <textarea rows="3" name="catatan" style="margin-left:5px;margin-right:15px; width:250px; resize: none;"></textarea>
      </div>


    </div>

    <div class="jarak">
      <label  class="fw-bold ms-3 mt-3">Pembayaran<span class="fw-light text-danger">*</span></label>
    <div class="elem-group inlined ms-3 mt-3">
     <input type="text" class="form-control" id="buktilunas" name="buktilunas" value="0" hidden>
     <input type="text" class="form-control" id="ketbayar" name="ketbayar" value="Terimakasih sudah mengisi data, kami akan menginfokan jika foto sudah selesai dicetak dan dikemas" hidden>
    
    <label  class="form-check-label ">Transfer ke Bank <i>BCA</i> <p> 5780785057 A.n. Rizal Satria Agung</p></label><br>
    
  </div>
    </div>
     
    
    <div class="jarak ms-4" >
       <label for="upload-img" class="mb-3 mt-3 fw-bold">Upload Bukti Transfer<span class="text-danger">* </span><label class="text-black-50"> wajib diisi </label></label>
         <p class="text-black-50" >
            *ukuran max 5 MB </p>
            <p class="text-black-50 ">
            *format gambar .png/.jpg/.jpeg
    </p>
    
      <input type="file" class="mb-2" id="buktitransfer" name="buktitransfer" value="" onchange="preview()" required>
      <!-- <input type="file" id="file" name="file[]"  multiple required>
        <p id="detail_file"></p> -->

      <div class="elem-group inlined  mt-3">
        <!-- <label for="file">
          <a class="btn btn-info" rel="nofollow"><span class='glyphicon glyphicon-paperclip'></span> Sisipkan File</a><span id="span_file">No file selected</span>
        </label> -->
        <!-- <a type="button" id="btn_reset" class="btn mt-2 btn-outline-dark">Reset <span class='glyphicon glyphicon-remove'></span></a> -->
        
        
          <?php echo bookingPublicationConsentField(); ?>
         <div class="empty-text w-50" style="width: 20%;">
            <img id="thumb" src="" width="250px"/>
        </div>
      </div>
    </div>
     <!----------------------------------------------------------------->
    <div class="row row-sm-5 row-md-5 row-lg-5   mx-auto  mb-3 " hidden style="justify-content: center;">
      <div class="col-md-5 ms-4">
        <div class="row">
          <div class="col  p-2">
            <label for="nomer" class="form-label fw-bold">Pilih Package<span class="text-danger">*</span><label class="text-black-50"> wajib diisi </label></label>
            <div class="form-check ">
              <input class="form-control" type="text" value="cetak foto" name="package" id="best-deal" onclick="myFunction1()" >
                <label class="form-check-label" for="flexRadioDefault1">
                  
                </label>
            </div>
        <input class="form-control sp" type="text" value="0" name="harga" id="mySelect1a" >
        <!-- <div class="row">
        <div class="col border mt-3 p-2">
          <div class="form-check mt-2">
            <input class="form-control sp" type="text" value="" name="harga" id="mySelect1a" onclick="org1()" required>
            <label class="form-check-label" for="flexRadioDefault2">
            SPECIAL PACKAGE<span class="fw-light text-danger">*</span> <label class="text-decoration-line-through text-black-50"> 999k</label> 800k
            </label>
            <input class="form-check-input" type="text" name="cetakfoto" hidden>
              <p class="text-black-50" style="font-size:small;margin-top:10px;">2 Background Photo</p>
              <p class="text-black-50" style="font-size:small;">1 jam Photo Session</p>
              <p class="text-black-50" style="font-size:small;">1 cetak Canvas + Frame ukuran 17R / kalau sudah di pasang frame ukurannya 40cm x 50 cm</p>
              <p class="text-black-50" style="font-size:small;">5 pcs cetak ukuran 5R (tanpa frame)</p>
              <p class="text-black-50" style="font-size:small;">Free 15-20 Photo, edit tone warna</p>
              <p class="text-black-50" style="font-size:small;">Foto unlimited / sepuasnya</p>
              <p class="text-black-50" style="font-size:small;">All Softcopy on Google drive<b><br> (berlaku 2 Minggu)</b></p>
              <p class="text-black-50 fw-bold" style="font-size:small;">Max 8 Orang</p>
          </div>
        </div>
      </div> -->
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <button type="submit" name="submit" class="btn btn-lg btn-outline-dark mt-2 mb-5" onclick="alert()" >Booking</button>
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

					$data = mysqli_query($koneksi, "SELECT nama,package,tanggal,jam,tipe_package FROM data_booking_cobahampirfinishjuga UNION SELECT nama,package,tanggal,jam,tipe_package FROM data_konfirmasi_cobahampirfinishjuga ORDER BY tanggal ASC");
					$jumlah_data = mysqli_num_rows($data);
					$total_halaman = ceil($jumlah_data / $batas);

					$data_konfirmasi = mysqli_query($koneksi,"SELECT nama,package,tanggal,jam,tipe_package FROM data_booking_cobahampirfinishjuga UNION SELECT nama,package,tanggal,jam,tipe_package FROM data_konfirmasi_cobahampirfinishjuga ORDER BY tanggal ASC LIMIT $halaman_awal, $batas");
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
   <script>
$('document').ready(function(){
   $('#btn_upload').click(function(){
     var the_file = $('#file').val();
     if(the_file ==""){
     alert('Please select the file');
     return false;
     }
   });
   $('#file').change(function(){
     FileDetails();
   });
   $('#btn_reset').click(function(){
     $('#file').val("");
     $('#span_file').css("display","inline");
     $('#detail_file').css("display","none");
   });
});
function FileDetails() {
   var fi = document.getElementById('file');
   if (fi.files.length > 0) {
     document.getElementById('detail_file').innerHTML =
     'Total Files: <b>' + fi.files.length + '</b></br >';
     for (var i = 0; i <= fi.files.length - 1; i++) {
        var no_file = i+1;
        var fname = fi.files.item(i).name;
        var fsize = fi.files.item(i).size;
        document.getElementById('detail_file').innerHTML =
        document.getElementById('detail_file').innerHTML + no_file + ". " +
        fname +' (<b>' + bytesToSize(fsize) + '</b>)<br>';
     }
     document.getElementById('detail_file').style.display="block"; 
     document.getElementById('span_file').style.display="none"; 
   }else { 
     alert('Please select a file.') 
   }
}
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
</script>
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
      ////////////////CETAK 30x40//////////////////////
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