<?php
 require 'function.php'; 

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
    <title>Rizal Foto Studio - Studio Foto Bekasi, Sewa Studio Bekasi, Berbagai Macam Background Studio, dan Cetak Foto</title>
    
  <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
  <meta name="google-site-verification" content="J-jUrTbG5z5HS-Iyc1qkAFW4bax-GDzYQZW01t1NwFc" />
  <style>
    @keyframes appear {
    from {
      opacity: 0;
      scale: 0.5;
    }
      to {
        opacity: 1;
        scale: 1;
      }
    }
  .scroll {
    animation: appear linear;
    animation-timeline: view();
    animation-range: entry 0% cover 40%;
  }
  .jumbotron{
    position: relative;
    background-image: url("img/foto49-1200p.png");
    min-height: 100vh;
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
    background-attachment: fixed;
  }
  .text{
    padding: 50px;
    width:800px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    color: black;
    font-size: 48px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50px;
    box-shadow: 0px 0px 16px 0px rgba(0, 0, 0, 0.5);
  }
  .navbar{
    top: 8px;
    left: 16px;
    position: absolute;
  }
  /* Efek Fade-in */
  .text {
      opacity: 0;
      animation: fadeInAnimation 3s forwards;
    }

    /* Definisi animasi fadeIn */
    @keyframes fadeInAnimation {
      0% {
          opacity: 0;
      }
      100% {
          opacity: 1;
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
    
    <!-- Ini adalah akhir navbar header -->
    
    <!-- Ini adalah awal body1 -->
     <!-- img/foto (49-50-51) -->
    <section class="jumbotron" >
      <nav class="navbar navbar-light" style="padding: 15px; ">
          <div class="container d-flex justify-content-center" >
            <a href="index.php" class="text-dark" style="text-decoration:none;">
              <img src="img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
              <span class="navbar-light h1 ms-4 align-middle fw-bold" style=" margin-top: 15px; font-size:25px; ">Rizal Foto Studio</span>
            </a>
          </div>
      </nav>
      <div class="text">
        <div class="col-md-5" style="width: fit-content;">
          <h1 class="display fw-bold" style="font-size: 4.5vw;" >Welcome to RF Studio</h1>
          <!--<p class="span ms-2" style="font-size: 16px;" > Sebuah foto dapat menggambarkan kita kenangan terhadap momen tersebut, segera abadikan momen-mu di Rizal Foto <h class="fw-bold">Studio</h> </p>-->
          <p class="span ms-2" style="font-size: 16px;" > Setiap kebersamaan pasti akan berakhir, tapi kebahagiannya akan tetap abadi dalam kenangan indah di RF <h class="fw-bold">Studio</h> </p>
          
        </div> 
      </div>    
    </section>
    <!-- Ini adalah akhir body1  -->

    

  
    <!-- Modal = ketika di klik masuk mode pop up -->
    <div class="modal fade " id="imgModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">RF Studio Collections</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body text-center">
            <img class="modal-img " style="max-width: 100%;" src="img/foto21.png" alt="Modal image" >
          </div>
        </div>
      </div>
    </div>
    
  
    <!-- Ini adalah akhir body3  -->


    <!-- Ini adalah awal body4  -->
    <div class="container-lg" id="pricelist">
      <div class="position-relative"> 
        <h1 class="display-4 fw-bold text-center">Price List</h1>
            <p class="span text-center">Berikut daftar harga dari studio foto kami</p>
          </div>
      <div class="scrollAnimation">
        <div class="scroll row row-cols-md-3 justify-content-center m-4"> 
          <div class="col-sm-5 p-3">
            <div class="inner">
                <img class="d-block w-100" src="img/foto17.png" alt="foto 1" >
            </div>
            <h5 class="span m-4">Family Package</p>
          
          <a href="kategori/familypackage.php" >
            <button type="button" class="btn btn-dark m-1" >View Price List </button>
          </a>        
          </div>

          <div class="col-sm-5 p-3">
            <div class="inner">
                <img class="d-block w-100" src="img/foto25.png" alt="foto 1" >
            </div>
            <h5 class="span m-4">Graduation Package</p>
            <a href="kategori/graduationpackage.php">
            <button type="button" class="btn btn-dark m-1" >View Price List </button>
          </a>
          </div>
        </div>

        <div class="scroll row row-cols-md-3 justify-content-center m-4"> 
          <div class="col-sm-5 p-3">
            <div class="inner">
              <img class="d-block w-100" src="img/foto56.png" alt="foto 1" >
            </div>
            <h5 class="span m-4">Personal & Group Package</p>
            <a href="kategori/grouppackage.php">
            <button type="button" class="btn btn-dark m-1" >View Price List </button>
          </a>
          </div>
          <div class="col-sm-5 p-3">
            <div class="inner">
              <img class="d-block w-100" src="img/fotoGoldPrawedding.png" alt="foto 1">
            </div>
            <h5 class="span m-4">Gold Prawedding Package</p>
            <a href="kategori/gpraweddingpackage.php">
            <button type="button" class="btn btn-dark m-1" >View Price List </button>
          </a>
          </div>
        </div>

        <div class="scroll row row-cols-md-3 justify-content-center m-4"> 
          <div class="col-sm-5 p-3">
            <div class="inner">
                <img class="d-block w-100" src="img/foto27.png" alt="foto 1">
            </div>
            <h5 class="span m-4">Silver Prawedding Package</p>
              <a href="kategori/spraweddingpackage.php">
            <button type="button" class="btn btn-dark m-1" >View Price List </button>
          </a>
          </div>
          <div class="col-sm-5 p-3">
            <div class="inner">
                <img class="d-block w-100" src="img/fotoMaternity.png" alt="foto 1">
            </div>
            <h5 class="span m-4">Maternity Package</p>
              <a href="kategori/maternitypackage.php">
            <button type="button" class="btn btn-dark m-1" >View Price List </button>
          </a>
          </div>
        </div>

        <div class="scroll row row-cols-md-3 justify-content-center m-4"> 
          <div class="col-sm-5 p-3">
            <div class="inner">
                <img class="d-block w-100" src="img/foto55.png" alt="foto 1">
            </div>
            <h5 class="span m-4">Self Photo Studio Package</p>
            <a href="kategori/selfstudiopackage.php">
            <button type="button" class="btn btn-dark m-1" >View Price List </button>
          </a>
          </div>
          <div class="col-sm-5 p-3">
            <div class="inner">
                <img class="d-block w-100" src="img/foto55.png" alt="pas foto">
            </div>
            <h5 class="span m-4">Pas Foto / CV / Comcard</p>
            <a href="kategori/pasfotopackage.php">
            <button type="button" class="btn btn-dark m-1" >View Price List </button>
          </a>
          </div>
          <div class="col-sm-5 p-3 container" style="position:relative;">
              <img src="assets/new.png" style="width: 10%;position:absolute; top:10px;right:16px;">
            <div class="inner">              
              <img class="d-block w-100" src="img/foto74.png" alt="foto 1">
            </div>
            <h5 class="span m-4">Baby Birthday / Smash Cake</p>
            <a href="kategori/baby-smash-cake.php">
              <button type="button" class="btn btn-dark m-1" >View Price List </button>
            </a>
          </div>
          
        </div>

        <div class="scroll row row-cols-md-3 justify-content-center m-4"> 
          <div class="col-sm-5 p-3">
            <div class="inner">
                <img class="d-block w-100" src="img/foto66.png" alt="foto 1">
            </div>
            <h5 class="span m-4">Cetak Foto</p>
            <a href="kategori/cetak-foto.php">
              <button type="button" class="btn btn-dark m-1" >View Price List </button>
            </a>
          </div>
          <div class="col-sm-5 p-3">
            <div class="inner">
              <img class="d-block w-100" src="img/foto43.jpg" alt="foto 1">
            </div>
            <h5 class="span m-4">Rental Studio Only</p>
            <a href="kategori/rentstudio.php">
              <button type="button" class="btn btn-dark m-1" >View Price List </button>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- Ini adalah akhir body4  -->
    <!-- Ini adalah awal body2  -->
    
    <div class="container">
      <div class="position-relative"> 
        <h1 class="display-4 fw-bold text-center" >Our Collections</h1>
        <p class="span text-center">Beberapa koleksi dari studio foto kami</p>
      </div>
    </div>
   
    <!-- Ini adalah akhir body2  -->
   <!-- Ini adalah awal body3 koleksi foto -->
    <section class="gallery" >
    <div class="container-lg" id="gallery">

        <div class="row row-cols-md-1 justify-content-center m-4 "> 
          <div class="col-sm-4 p-3 ">
          <div class="inner">
            <img class="gallery-item w-100" src="img/fotoFamily2.png" alt="foto 1" >
          </div>
          </div>
          <div class="col-sm-4 p-3">
            <div class="inner">
              <img class="gallery-item w-100" src="img/fotoSilverPrawedding.png" alt="foto 1" >
            </div>
            
          </div>
        </div>

        <div class="row row-cols-md-1 justify-content-center m-4"> 
          <div class="col-sm-4 p-3">
            <div class="inner">
              <img class="gallery-item w-100" src="img/foto24.png" alt="foto 1" >
            </div>
            
          </div>
          <div class="col-sm-4 p-3">
            
            <div class="inner">
              <img class="gallery-item w-100" src="img/foto33.jpg" alt="foto 1" >
            </div>
          </div>
          
        </div>

        <div class="row row-cols-md-1 justify-content-center m-4"> 
          <div class="col-sm-4 p-3">
            <div class="inner">
              <img class="gallery-item w-100" src="img/foto38.png" alt="foto 1" >
            </div>
            
          </div>
          </div>

        
        
      </div>
    </section>
    <!-- Modal = ketika di klik masuk mode pop up -->
    <div class="modal fade" id="btnModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Konfirmasi User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body m-2">
            <form action="" method="POST">

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="text" class="form-control" name="email" id="exampleInputEmail1" placeholder="Masukkan email">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Nomor Telepon</label>
                <input type="password" class="form-control" name="passwordinput" id="exampleInputPassword1" minlength="10" maxlength="13" placeholder="Masukkan nomor telepon sebagai password ">
            </div>
            
           
            <button type="submit" name="loglunas" class="btn btn-primary">Submit</button>

            </form>
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
          
          <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
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

            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 fw-bold text-warning">On Going</h5>
              <p >
                <a href="jadwal.php" class="fjadwal text-white" style="text-decoration:none; " ><i class="fas fa-calendar-days me-2"></i> Jadwal</a>
                </p>
               
               <p>
                <a href="trackingprogress.php" class="fpelunasan text-white" style="text-decoration:none;" ><i class="fa-solid fa-rotate me-2"></i> Tracking Progress</a>
              </p>
                
              </div>

            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
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


  </body>
</html>