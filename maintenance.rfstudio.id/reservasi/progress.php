<?php
require '../function.php';
$track = $_GET['track'];
$query = mysqli_query($koneksi,"SELECT * FROM data_tracking_coba WHERE uniqid = '$track'");
$ambil = mysqli_fetch_assoc($query);

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
    <title>Pelunasan</title>
    <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  </head>
  <style>
    .mb-2{
        font-size: 15px;
    }
    .dot{
        width: 20px;
        height: 20px;
        background-color: #919191;
        border-radius: 50%;
        border-color: #fff;
        
    }
  </style>
  <body>
    <!-- Ini adalah awal navbar header -->
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
         <div class="container d-flex justify-content-center" >
          <a href="index.php" class="text-dark" style="text-decoration:none;">
             <img src="../img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
             <span class="navbar-light h1 ms-4 align-middle fw-bold" style=" margin-top: 15px; ">Rizal Foto Studio</span>
          </a>
        </div>
    </nav>
    <!-- Ini adalah akhir navbar header -->
      
    
    <!-- Ini adalah awal body1 -->
    
    <section class="jumbotron" >
        
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mt-5 ms-3">
          <li class="breadcrumb-item"><a href="index.php " class="text-dark" style="text-decoration: none;">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tracking Progress</li>
        </ol>
      </nav>
        <div class="mb-4 text-center">
            <h2>Tracking Progress nama : <?=$_POST['bsubmit'];?></h2>
        </div>
        
    <!-----------------------------------------Tabell----------------------------------------------------->
        <div class="container border " style="max-width: 400px;">
            <div class="row border mt-4 ms-3 me-3 mb-4" style="max-width: 400px;">
                <div class="m-2 " style="max-width: 400px;">
                <div class="row">
                    <div class="row">
                        <div class="col">
                        <img class="dot" src = "../assets/<?=$ambil["gambar1"];?>" width="20"> 
                        <label class="mt-2" style="margin-left:9px;">Mengisi data</label> 
                        </div>
                    </div>
                    <div class="row">
                        <label class="text-black-50" style="margin-left:33px;"><?=$ambil["waktu1"];?></label>
                        <label class="mb-2 text-black-50" style="margin-left:33px;"><?=$ambil["tambah1"];?></label>
                    </div>
                </div>
                <?php if(($ambil["judul1"])>1) { ?>
                <div class="row">
                    <div class="row">
                        <div class="col">
                        <img class="dot" src = "../assets/<?=$ambil["gambar2"];?>" width="20"> 
                        <label class="mt-2" style="margin-left:9px;">Terkonfirmasi</label> 
                        </div>
                    </div>
                    <div class="row">
                        
                        <label class="text-black-50" style="margin-left:33px;"><?= $ambil["waktu2"];?></label>
                        <label class="mb-2 text-black-50" style="margin-left:33px;"><?=$ambil["tambah1"];?></label>
                    
                        <?php }else{echo""; }?>
                    </div>
                </div>
                <?php if(($ambil["judul2"])>1) { ?>
                <div class="row">
                    <div class="row">
                        <div class="col">
                        <img class="dot" src = "../assets/<?=$ambil["gambar3"];?>" width="20"> 
                        <label class="mt-2" style="margin-left:9px;">Photoshoot</label> 
                        </div>
                    </div>
                    <div class="row">
                        <label class="text-black-50" style="margin-left:33px;"><?=$ambil["waktu3"];?></label>
                        <label class="mb-2 text-black-50" style="margin-left:33px;"><?=$ambil["tambah3"];?></label>
                    </div>
                </div>
                <?php }else{echo""; }?>
                <?php if(($ambil["judul3"])>1) { ?>
                <div class="row">
                    <div class="row">
                        <div class="col">
                        <img class="dot" src = "../assets/<?=$ambil["gambar4"];?>" width="20"> 
                        <label class="mt-2" style="margin-left:9px;">Pelunasan</label> 
                        </div>
                    </div>
                    <div class="row">
                        <label class="text-black-50" style="margin-left:33px;"><?=$ambil["waktu4"];?></label>
                        <label class="mb-2 text-black-50" style="margin-left:33px;"><?=$ambil["tambah4"];?></label>
                    </div>
                </div>
                <?php }else{echo""; }?>
                <?php if(($ambil["judul4"])>1) { ?>
                <div class="row">
                    <div class="row">
                        <div class="col">
                        <img class="dot" src = "../assets/<?=$ambil["gambar5"];?>" width="20"> 
                        <label class="mt-2" style="margin-left:9px;">Transaksi selesai</label> 
                        </div>
                    </div>
                    <div class="row">
                        <label class="text-black-50" style="margin-left:33px;"><?=$ambil["waktu5"];?></label>
                        <label class="mb-2 text-black-50" style="margin-left:33px;"><?=$ambil["tambah5"];?></label>
                    </div>
                </div>
                <?php }else{echo""; }?>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->

    </section>
    <!-- Ini adalah akhir body1  -->
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
                <a href="jadwal.php" class="fjadwal text-white" style="text-decoration:none; " ><i class="fas fa-calendar-days me-2"></i> Jadwal</a>
                </p>
               <p>
              <a href="#" class="fpelunasan text-white" style="text-decoration:none;" ><i class="fa-solid fa-clock"></i> Pelunasan</a>
              </p>
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