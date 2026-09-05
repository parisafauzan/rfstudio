<?php
require 'function.php';
$ambildata = mysqli_query($koneksi,"SELECT * FROM data_history_cobahampirfinishjuga");

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
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">

    
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
         <div class="container d-flex justify-content-center" >
          <a href="index.php" class="text-dark" style="text-decoration:none;">
             <img src="img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
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

  <div class="container-md-5 border" >
    <div class="d-inline-flex ps-1 pe-1 text-white bg-dark " style="width: 200px; height: 60px; " > 
        <h5 class="product-title text-center mt-3 mx-auto">Booking Jadwal</h5></div>


    <!--<div class="alert alert-warning alert-dismissible fade show m-3" role="alert" style="font-size:14px;">-->
    <!--    Mohon Maaf Kami tutup sementara di tanggal<strong> 2 Februari 2023</strong>-->
    <!--   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
    <!-- </div>-->
    <div class="row mx-auto">
        <?php 
            if(mysqli_num_rows($ambildata)>0){
                foreach($ambildata as $row){
                    ?>
                    <div class="card" style="width: 200px;">
                        <div class="card-body">
                            <label><?=$row["nama"];?></label>
                        </div>
                    </div>
                    <?php
                }
            }
        ?>
    </div>

  </div>
</div>

    <!-- Ini bagian akhir body Form -->

    <!-- Modal = ketika di klik masuk mode pop up -->
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