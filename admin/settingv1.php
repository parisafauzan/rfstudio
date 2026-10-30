<?php
// koneksi database
require '../function.php';
require '../session.php';
     
// $data = mysqli_fetch_row($result) ;
// var_dump($data);
  

?>

<!DOCTYPE html>
<html lang="en">
<!--  -->
<head>
 <!-- Favicons -->
  <link href="../img/logo.png" rel="icon">
  <link href="../img/logo.png" rel="apple-touch-icon">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- As a heading -->
<title>Setting - Bekasi Kabupaten</title>
<!-- Fontawesome -->
<script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
<!--  -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<!--  -->


<style>
    #loader {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,1) url("../assets/loader-dot.gif") center no-repeat;
    }
    .nav-link:hover {
    background-color: black;
    }
    table{
    width: 100%;
    }
    th,td{
    text-align: start;
    }
    .hapusbtn{
        color: #e32239;
    }
    .hapusbtn :hover{
        color: #bf0f59
    }
    .scroll {
        background-color: #fff;
        overflow: auto;
        white-space: nowrap;
        padding: 10px;
        width: 50px;
    }
    /* Add animation to "page content" */
            .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
            }

            @-webkit-keyframes animatebottom {
            from { bottom:-100px; opacity:0 } 
            to { bottom:0px; opacity:1 }
            }

            @keyframes animatebottom { 
            from{ bottom:-100px; opacity:0 } 
            to{ bottom:0; opacity:1 }
            }

</style>
<body onload="myFunction()">
  <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

    <!-- Ini adalah awal navbar header -->
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
         <div class="container d-flex justify-content-center" >
          <a href="../index.php" class="text-dark" style="text-decoration:none;">
             <img src="../img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
             <span class="navbar-light h1 ms-2 align-middle fw-bold" style=" margin-top: 15px; ">Rizal Foto Studio</span>
          </a>
        </div>
    </nav>
    <!-- Ini adalah akhir navbar header -->

     <!-- Awal Sidebar -->
    <div id="loader" class="text-center" >
    </div>
    <div style="display:none;" id="myDiv" class="animate-bottom" >
        <div class="container-fluid">
            <div class="row flex-nowrap">
                <div class="col-auto  px-sm-2 px-0 bg-dark">
                    <div class="d-flex flex-column align-items-center align-items-sm-start px-1 pt-2 text-white min-vh-100">
                        <a class="d-flex mx-auto pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                            <span class="fs-5 d-none d-sm-inline mt-4 fs-3">admin</span>
                        </a>
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0  align-items-center align-items-sm-start" id="menu">
                            <li >
                                <a href="datakonfirmasidp.php" class="nav-link px-0 mt-2 align-middle text-white" style=" font-size: 16px;" >
                                <i class="fa-solid fa-list-check m-2 "></i> <span class=" me-2 d-none d-sm-inline">Nunggu Konfirmasi</span> </a>
                            </li>
                            <li>
                                <a href="databooking.php" class="nav-link mt-2 px-0 align-middle text-white" style=" font-size: 16px;">
                                    <i class="fa-solid fa-list-ol m-2"></i> <span class=" me-2 d-none d-sm-inline">Jadwal Photoshoot</span></a>
                            </li>
                        
                            <li>
                                <a href="datahistory.php" class="nav-link mt-2 px-0 align-middle text-white"style="font-size: 16px;">
                                    <i class="fa-solid fa-book m-2"></i> <span class=" me-2 d-none d-sm-inline">Data Pelanggan</span> </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link mt-2 px-0 align-middle text-white" style="background-color:black ;font-size: 16px;">
                                    <i class="fa-solid fa-gear m-2"></i></i> <span class=" me-2 d-none d-sm-inline">Setting</span> </a>
                            </li>
                        </ul>
                        <hr>
                        <div class="pb-4">
                            <a href="logoutadmin.php" class="d-flex ms-3 align-items-center text-white text-decoration-none " onclick="return confirm('Anda yakin ingin keluar?')">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span class="d-none d-sm-inline mx-1 ms-3">Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col pt-5 pb-4">
                    <!-------------------------------Tabell----------------------------------------------------->

                    <div class="">
                        <div class="row m-1 ">
                            <h2><i class="fa-solid fa-gear"></i> Setting -<b> Studio Kab</b></h2>
                        </div>
                        <div class="card ">
                            <div class="d-flex mt-3 ms-3 me-3">
                                <div class="flex-fill">
                                <a type="button" class="btn btn-outline-dark active" style="width:100%;border-radius:0px" href="#jadwal">Jadwal Libur</a>
                                </div>
                                <div class="flex-fill" >
                                <a type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px" href="#edit">Edit Foto (<i>soon</i>)</a>
                                </div>
                            </div>
                            <div class="row card-body me-1">
                                <!-- Jadwal Libur -->
                                <div class="card mb-3 ms-2 me-2 p-3">
                                    <label class="text-center">Masukan tanggal libur</label>
                                    <form class="form" action="form-setting-input" method="POST">
                                        <div class="col-md text-center border-bottom pb-2" >
                                            <!-- <input type="date" id="tanggal" name="tanggallibur" class="tanggal m-2" value=""> -->
                                            <input type="text" id="tanggal" class="m-2" name="tanggallibur" placeholder="-Pilih tanggal-" onfocus="(this.type='date')">
                                            <input type="text" name="catatanlibur" class="form-control tanggal m-2" value="" placeholder="catatan">
                                            <button type="submit" name="submit" class="btn btn-outline-dark m-2" onclick="alert()">Submit</button>
                                        </div>
                                    </form>
                                    <div class="mt-4">
                                        <div class="row">
                                            <div class="d-flex mx-auto m-2" style="max-width:950px; max-height: 60%">
                                                    <div class="flex-fill"><b>No</b></div>
                                                    <div class="flex-fill"><b>Tanggal Libur</b></div>
                                                    <div class="flex-fill"><b>Catatan</b></div>
                                                    <div class=""><b>Pilihan</b>
                                                    </div>
                                            </div>
                                            <?php 
                                                $ambiljadwallibur = mysqli_query($koneksi,"SELECT * FROM jadwal_libur_kab");
                                                $no = 1;
                                                if(mysqli_num_rows($ambiljadwallibur)>0){
                                                    foreach($ambiljadwallibur as $row){
                                                        ?>
                                                        <div class="row m-2" style="height:fit-content">
                                                            <div class="d-flex mx-auto border align-items-center" style="max-width:950px; max-height: 60%">
                                                                    <div class="flex-fill border-end me-1" style="max-width:30px;"><?=$no++;?></div>
                                                                    <div class="flex-fill border-end me-1 scroll" ><?=$row["jadwal"];?></div>
                                                                    <div class="flex-fill border-end me-1 scroll"><?=$row["catatan"];?></div>
                                                                    <div class="" style="max-width:30px;">
                                                                        <a class="btn hapusbtn" href="hapusjadwal.php?uniqid=<?=$row["uniqid"];?>" onclick="return confirm('Anda yakin ingin menghapus data ini ?')"><i class="fa-solid fa-trash hapusbtn" ></i></a>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }else{
                                                    ?>
                                                    <div class="card mx-auto text-center mt-3" style="max-width: 950px;">
                                                        <h5>Belum ada jadwal</h5>
                                                    </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Foto -->
                                <div class="col-md card mb-3 ms-2 me-2" hidden>
                                    asd
                                </div>
                            </div>
                        </div>
                    </div>   
                    <!------------------------------------------------------------------------------------>

                </div>
        
            </div>
        </div>
    </div>
    <!-- Akhir Sidebar -->


<!-- Ini adalah awal footer  -->
    <footer class="bg-dark text-white pt-5 pb-4">
      <div class="container text-start text-md-left "> 
        <div class="row align-items-center ">
        <div class="col-md-6 col-lg-6 mx-auto">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
      function alert(){
      var result = confirm("Anda yakin atur jadwal libur?");
      if (result == false){
        event.preventDefault();
      }
    }
    $(document).ready(function() {
        $.datepicker.setDefaults({
          dateFormat: 'yy-mm-dd'
        })
      });
      $(function(){
        $("#tanggal").datepicker();
      });
    </script>
    <script>
        // $(document).on({
        //     ajaxStart: function(){
        //         $("#myDiv").addClass("loading"); 
        //     },
        //     ajaxStop: function(){ 
        //         $("#myDiv").removeClass("loading"); 
        //     }    
        // });
            var myVar;

            function myFunction() {
            myVar = setTimeout(showPage, 1000);
            }

            function showPage() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("myDiv").style.display = "block";
            }
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
    <script >
      $(document).ready(function () {
          $('#table').DataTable();
      });
              $(document).on('click','.viewdata',function(){ 
              $('#viewModal').modal("show");  

              $tr = $(this).closest('tr');
              var data = $tr.children('td').map(function(){
                  return $(this).text();
              }).get();

              console.log(data);
              $('#tanggal').val(data[0]);
              $('#nama').val(data[1]);
              $('#jam').val(data[2]);
              $('#package').val(data[3]);
              $('#jumlahfoto').val(data[4]);
              $('#notelp').val(data[5]);
              $('#email').val(data[6]);
              $('#cetakfoto').val(data[7]);
              $('#tambahanak').val(data[8]);
              $('#tambahdewasa').val(data[9]);
              $('#tambahorang').val(data[10]);
              $('#tambahwaktu').val(data[11]);
              $('#tambahcetak').val(data[12]);
              $('#tambahmakeup').val(data[13]);
              $('#tambahhairdo').val(data[14]);
              $('#harga').val(data[15]);
              $('#waktutransaksi').val(data[16]);
              $('#catatan').val(data[17]);

            });
        </script>
    

   
  </body>
  </html>
</body>
</html>