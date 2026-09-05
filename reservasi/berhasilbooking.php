<?php
require '../function.php';
?>
<!doctype html>
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
    <title>Transaksi Booking</title>
    <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta http-equiv="refresh" content="12;url=../index.php">
     <script type="text/javascript">
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
  </head>
  <body>
    <!-- Ini adalah awal navbar header -->
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
        <div class="container d-flex justify-content-center" >
             <img src="../img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
             <span class="navbar-light h1 mt-3 ms-4">Rizal Foto Studio
             </span>
        </div>
    </nav>
    <!-- Ini adalah akhir navbar header -->
    <style>
        .mb-5{
            text-align: center;
        }
    </style>
    <!-- Ini adalah awal body1 -->
    <div class="container" >
      
    
     <div class="row-md-5 p-3 ">
      <div class="col-md-5 col-lg-5 p-4 border mx-auto mt-4 mb-4" style="max-width: 400px;">
        
        <div class="mb-5 " >
         <img src="../img/logo.png" class="img-thumbnail rounded-circle " alt="Rizal Photography" width="100"  >

        </div>
        <div class="mb-4 text-center" >
        <label for="exampleInputPassword1" class="form-label fw-bold fs-2" >Terima kasih !</label>
        <label for="exampleInputPassword1" class="form-label p-3" >Data anda telah terkirim, silahkan cek email dan konfirmasi admin via whatsapp</label>
        <a href="https://wa.me/6281288045066" for="exampleInputPassword1" class="form-label btn btn-success" type="button" style="border-radius:22px;"><i class="fa-brands fa-whatsapp"></i> whatsapp</a>
            
        </div>
        <div class="mb-3 text-center" >
            <label>Halaman ini akan tutup otomatis<p id="count"></p></label>
       
       </div>
        </div>
        </div>
         </div>
    <!-- Ini adalah akhir body1  -->

    <?php 
    ?>

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
      <div class="container text-start text-md-left "> 
        <div class="row align-items-center ">

        <div class="col-md-6 col-lg-6 mx-auto" >
            <p style="margin-top:auto; margin-bottom:auto;" > Copyright ©2023 All rights reserved by :
            <a href="#" style="text-decoration: none;">
            <strong class="text-warning"> Rizal Foto Studio</strong>  </a></p>
        </div>
        <!-- <div class="col-md-6 col-lg-6 mx-auto" style="margin-top:auto; margin-bottom:auto;">
            <a href="https://www.flaticon.com/free-stickers/camera" title="camera stickers" class="text-white text-decoration-none" >Camera stickers created by frdmn - Flaticon</a>    
        <a href="https://www.flaticon.com/free-icons/pay" title="pay icons">Pay icons created by Freepik - Flaticon</a>
                <a href="https://www.flaticon.com/free-icons/waiting-list" title="waiting list icons">Waiting list icons created by Stockes Design - Flaticon</a>
        <a href="https://www.flaticon.com/free-icons/camera" title="camera icons">Camera icons created by Freepik - Flaticon</a>
        <a href="https://www.flaticon.com/free-stickers/camera" title="camera stickers">Camera stickers created by frdmn - Flaticon</a>
        <a href="https://www.flaticon.com/free-icons/right" title="right icons">Right icons created by kliwir art - Flaticon</a>
        <a href="https://www.flaticon.com/free-icons/camera" title="camera icons">Camera icons created by Freepik - Flaticon</a>

    </div> -->
        </div>
        </div>
    </footer>

    <!-- Ini adalah akhir footer  -->


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
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var end =setInterval(function () {
        seconds = parseInt(timer % 60, 10);

        seconds = seconds < 10 ? "" + seconds : seconds;

        display.textContent =  seconds;

    if (--timer < 0) {
      //  window.location = "index.php";
        clearInterval(end);
    }
     }, 1000);
    }

        window.onload = function () {
        var fiveMinutes = 10,
        display = document.querySelector('#count');
        startTimer(fiveMinutes, display);
    };
    </script>

    
    
    <!-- <script>
   window.onbeforeunload = function() { return "Your work will be lost."; };
</script> -->


  </body>
</html>