<?php
require 'function.php';
// $jadwalkabupaten = mysqli_query($koneksi,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE studio ='Bekasi-Kabupaten' ORDER BY tanggal,jam");
// $ambil = mysqli_fetch_array($jadwalkabupaten);
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
  </head>
  <style>
    /* di bawah ini khusus untuk data yang ditampikan paling pertama maka akan di beri warna */
    /* .card.bg-primary {
      background-color: #4CAF50;
      color: white;
    } */

    .main-content{
      width: 100%;
      max-width: 900px;
      
    }
    .data-jadwal{
      width:100%;
      max-width: 850px;
      
    }
    .teks-tanggal{
      border-radius: 20px;
      background-color: rgb(255, 152, 152);
      color:#000;
      padding: 8px;
    }
    .teks-waktu{
      border-radius: 20px;
      background-color: rgb(255, 207, 152);
      color:#000;
      padding: 8px;
    }
    .teks-jadwal{
      font-size: 14px;
    }
    .bekasi-kab{
      justify-content: center;
      margin-top: 20px;
      margin-bottom: 20px;
    }
  </style>
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
    <!-- Ini adalah awal body1 -->
    <!-------------------------------Tabell----------------------------------------------------->
    <div class="container main-content">
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
      <div class="main-data mx-auto" >  
      <?php 
          // Ambil seluruh data dari query
          $jadwalkabupaten = mysqli_query($koneksi, "SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE studio ='Bekasi-Kabupaten' ORDER BY tanggal, jam UNION SELECT * FROM data_booking_cobahampirfinishjuga WHERE studio ='Bekasi-Kabupaten' ORDER BY tanggal, jam ASC");
          
          // Menyimpan hasil query ke dalam array
          $data = mysqli_fetch_all($jadwalkabupaten, MYSQLI_ASSOC); // Mengambil data dalam bentuk array asosiatif

          // Menggunakan foreach untuk menampilkan data
          foreach ($data as $row) {
          ?>
          <div id="bekasi-kab" class="row bekasi-kab">
            <div class="col-md-4 data-jadwal">
              <div class="card mt-3">
                <div class="card-header">
                    <strong><?php echo $row['nama']; ?></strong>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <p class="teks-jadwal"><strong>Tanggal:</strong> <span class="teks-tanggal"><?php $tanggal1 = date("d-m-Y", strtotime($row["tanggal"]));
                        echo $tanggal1; ?></span></p>
                      <p class="teks-jadwal"><strong>Jam:</strong> <span class="teks-waktu"><?php echo $row['jam']; ?></span></p>
                    </div>
                    <div class="col">
                      <p class="teks-jadwal"><strong>Studio:</strong> <?php echo $row['studio']; ?></p>
                      <p class="teks-jadwal"><strong>Package:</strong> <?php if($row["package"]== "cetak foto"){
                          echo $row["package"];
                        }else{
                          echo $row["package"];echo " "; echo '<b>'.$row["tipe_package"].'</b>';
                        } ?>
                      </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <p class="teks-jadwal"><strong>Jumlah Orang:</strong> <?php echo $row["jmlhorgdws"]; echo "</br>"; echo $row["jmlhorgank"]; ?></p>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <small><p class="teks-jadwal"><strong>Catatan:</strong> <?php echo $row['catatan']; ?></p></small>
                </div>
            </div>
            </div>
          </div> 
          <?php 
            }
          ?>       

      </div>
    </div>
    <!------------------------------------------------------------------------------------>

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
              <p class="text-start">Copyright &copy;<span id="copyright-year"></span>
		            All rights reserved by :
            <a href="#" style="text-decoration: none;">
                <strong class="text-warning"> Rizal Foto Studio</strong>
            </a>
            </p>
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src=" https://cdn.datatables.net/1.13.22/js/jquery.dataTables.min.js"></script>
    <script src=" https://cdn.datatables.net/1.13.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- Modal Foto -->
    <!-- <script type="text/javascript"> 
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
        })
    </script> -->
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