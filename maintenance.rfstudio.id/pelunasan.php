<?php
   
    require 'function.php';
    echo '<script language="javascript">';
    echo '$(document).ready(function(){';
      echo '$("#containertr").hide()';
      echo '});';
      echo '</script>';
?>

<!DOCTYPE html>
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
    <title>Pelunasan</title>
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
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
    
    <style type="text/css">
     
      .col-md-5{ 
        margin-top: 1rem;
        margin-bottom: 5rem;
        padding: 2rem;

      }
      .note{
        color: darkgray;
        font-size:14px;
      }
      .containertr{
        display: none;
      }
      .container-md-5{ 
        margin-top: 1rem;
        margin-bottom: 5rem;
        max-width: 600px;
        margin: auto;
        padding-left: 15px;
        padding-right: 15px;
      }
      </style>
    <!-- Form Login -->
    <div class="container" id="verifikasi">
       <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mt-5 ms-3">
          <li class="breadcrumb-item"><a href="index.php " class="text-dark" style="text-decoration: none;">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Pelunasan</li>
        </ol>
      </nav>
    
     <div class="row-md-5 p-3 ">
      <div class="col-md-5 p-4 border mx-auto">
        <form action="" method="GET" >
          <h4 class="text-center fw-bold mt-3">Verifikasi Data</h4>

        <div class="mb-3">
        <label for="exampleInputuniqid1" class="form-label">Kode Track</label>
            <input type="text" id="nama" class="form-control" autocomplete="off" name="uniqid" placeholder="Masukkan kode track Anda" required>
        </div>
        <label class="note">*pastikan anda belum melakukan pelunasan</label>
        <label class="note">*cek email untuk melihat kode track</label>
        <label class="note mb-3">*konfirmasi ke admin bahwa data anda ada di data pelunasan</label>
        
        <div class="text-center">
         <button type="submit" name="bsubmit" id="login" class="btn btn-outline-dark w-50 mt-3" >Submit</button>
        </div>
        </form>
        </div>
        </div>
         </div>
   
  <!-- Ini bagian atas body Form -->  
<section class="m-3 containertr"  id="containertr" >
  <?php
    if (isset($_GET['bsubmit'])){
      
      $track = htmlspecialchars($_GET['uniqid']);

      $sql = mysqli_query($koneksi, "SELECT * FROM data_pelunasan_cobahampirfinishjuga WHERE uniqid = '$track' ");
      $ambil = mysqli_fetch_assoc($sql);

      if (mysqli_num_rows($sql)===1){
        echo '<script language="javascript">';
      echo '$(document).ready(function(){';
      echo '$("#containertr").show()';
      echo '});';
      echo '</script>';
        foreach($ambil as $get){
      //     $_SESSION["track"] = true;
       

      //  }else{
      //     echo '<script language="javascript">';
      //     echo 'alert("Silahkan cek kembali kode anda");'; 
      //     echo '</script>';
      //     // echo"maaf login gagal periksa kembali username dan passwordnya";
      //     // echo "<meta http-equiv=refresh content=2; URL='adminlogin.php'>";
      }
      }else{
        echo '<script language="javascript">';
      echo 'alert("Data tidak ditemukan")';
      echo '</script>';
      }
    }
    if(isset($_POST["updatelunas"])){
    //cek apakah data berhasil disimpan atau tidak
    // var_dump($_POST);
      if(update($_POST)>0){
        echo '<script language="javascript">';
        echo 'function alert("Bukti Lunas telah terkirim") {';
        echo 'document.getElementById("myForm").reset()};';
        echo 'window.location = "index.php"';
        echo '</script>';
      //echo '<meta http-equiv="refresh" content="3;url=../index.php">';

      }exit;
    } 
      ?>
  
  <div class="container-md-5 border">
  <div class="d-inline-flex ps-1 pe-1 text-white bg-dark " style="width: 200px; height: 60px; " > 
      <h5 class="product-title text-center mt-3 mx-auto">Pelunasan</h5></div>


<form id="myForm" method="post" action="" enctype="multipart/form-data">
  <div class="m-3">
    <input type="hidden"  class="form-control"  id="id" name="id" value="<?= $ambil["id"];  ?>">  
    <label for="nama" class="form-label fw-bold">Nama</label>
    <input type="hidden"  class="form-control"  id="nama" name="nama" value="<?= $ambil["nama"];  ?>">
    <p><?= $ambil["nama"];  ?></p>
  </div>
    <div class="m-3">
    <label for="nomer" class="form-label fw-bold">Nomor Telepon</label>
    <p><?= $ambil["no_telp"];  ?></p>
    </div>
    <div class="m-3">
    <label for="email" class="form-label fw-bold">Email</label>
    <input type="hidden"  class="form-control"  id="email" name="email" value="<?= $ambil["email"];  ?>">
    <p><?= $ambil["email"];  ?></p>
    </div>

    <!----------------------------------------------------------------->
    <div class="m-3 ">
      
        <label for="nomer" class="form-label fw-bold">Package</label>
            <p><?= $ambil["package"], $ambil["tipe_package"]; ?></p>
   
    </div>

    <div class="m-3">
        <label for="nama" class="form-label fw-bold">Tanggal</label>
       <p><?= $ambil["tanggal"];  ?></p>
    </div>

    <div class="m-3">
        <label for="jam" class="mb-3 fw-bold">Waktu</label>
        <p><?= $ambil["jam"];  ?></p>
    </div>

    <div class="m-3">
        
    <label for="upload-img" class="mb-3 mt-4 fw-bold">Bukti Transfer (DP)</label>
      <div class="elem-group inlined">
        <!-- img -->
        <img class="gallery-item" aria-label="bukti"  src= "assets/img/data_konfirmasi/<?= $ambil["bukti_transfer"];?>" width="200"> 
      </div>
    </div>
    <div class="m-2">
      <p  class="form-check-label  mt-3">Transfer ke Bank <i>BCA</i> <p> 5780785057 A.n. Rizal Satria Agung</p></p>
      <p for="nomer" class="form-label fw-bold">Sisa untuk pelunasan</p>
      <p><h5>Rp <?= $ambil["hargasetelahdp"];  ?></h5></p>
    </div>
    <div class="m-3">
      <label for="upload-img" class="mb-3 mt-4 fw-bold">Upload Bukti Transfer (Lunas)</label>
      <p class="text-black-50" >
            *ukuran max 5 MB</p>
            <p class="text-black-50 ">
            *format gambar .png/.jpg/.jpeg </p>
            <p class="text-black-50 ">
            
          
         
    <div class="elem-group inlined">
        <!-- img -->
      <input type="file" class="mb-4" id="buktilunas" name="buktilunas" value="" required onchange="preview()"> 
        <div class="empty-text w-50" style="width: 20%;">
            <img id="thumb" width="200px"/>
        </div>
    </div>
    </div>
  
 
      <button  type="submit" name="updatelunas" class="btn-lg btn-outline-dark m-4 justify-content-center">Submit</button>
    </form>
   

  </div>
  <!-- Modal = ketika di klik masuk mode pop up -->
    <div class="modal fade" id="imgModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header " >
            <h5 class="modal-title" id="exampleModalLabel">Bukti Transfer DP</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <img class="modal-img w-100" alt="Modal image" >
          </div>
        </div>
      </div>
    </div>

  </section>

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
                <a href="jadwal.php" class="fjadwal text-white" style="text-decoration:none; " ><i class="fas fa-calendar-days me-2"></i> Jadwal</a>
                </p>
               
              <p>
                <a href="trackingprogress.php" class="fpelunasan text-white" style="text-decoration:none;" ><i class="fa-solid fa-rotate me-2"></i> Tracking Progress</a>
              </p>
                
              </div>

            <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
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
                <a href="https://mail.google.com/" class="funiqid" style="text-decoration: none; color:#fff;">
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
    <script type="text/javascript">
function preview() {
   thumb.src=URL.createObjectURL(event.target.files[0]);
}
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
    var bookingElem = document.querySelector("#booking-date");
    

    bookingElem.setAttribute("min", dateTomorrow);

    bookingElem.onchange = function () {
    }
 </script>   
  </body>
  </html>