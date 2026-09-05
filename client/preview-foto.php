<?php
// koneksi database
require 'function.php';
$nama_file = $_GET["nama_file"];
$ambildata = mysqli_query($koneksi,"SELECT * FROM data_upload_foto_studio_admin WHERE nama_file ='$nama_file'");
$data = mysqli_fetch_array($ambildata);
// session_start();
//  if(!isset($_SESSION['adminkartini'])){
//     header("location:adminlogin.php");
//     exit;
// }

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
<title>Detail - Bekasi Kota</title>
<!-- Fontawesome -->
<script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
<!--  -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!--  -->
 <!-- <link rel="stylesheet" href=" https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
 <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"> -->
 
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet" />
<!--  -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<!--  -->
<style>

    table{
    width: 100%;
    }
    .align-middle{
    text-align: center;
    }
    footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    }
    .jarak{
    margin:5px;
    width: 80%;
    font-size: 12px;
    }
    .form-control{
    font-size: 13px;
    }
    .card{
        width: 100%;
        max-width: 1000px;
        height: 100%;
        max-height: fit-content;
        justify-content: center;
    }
    .main-img{
        width: 100%;
        max-width: 900px;
        height: 100%;
        max-height: fit-content;

    }
    .container-md{
        width: 100%;
    }
    .row{
        justify-content: center;
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
          <a href="../index.php" class="text-dark" style="text-decoration:none;">
             <img src="../img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
             <span class="navbar-light h1 ms-2 align-middle fw-bold" style=" margin-top: 15px; ">Rizal Foto Studio</span>
          </a>
        </div>
    </nav>
    <!-- Ini adalah akhir navbar header -->

      <!-- Awal Sidebar -->
<div class="container-fluid">
      <div class="row flex-nowrap">
      
<div class="pt-3">
<!-------------------------------Tabell----------------------------------------------------->

<div class="d-flex justify-content-center pb-4">
  
  <div class="m-1 text-center mb-3">
    <h2 class="text-center">Preview Foto <b>Kamu</b></h2>
    <h5 class="text-center"><?= $data["nama"];?></h5>
  </div>

</div>
 
<div class="container-md " >
    <div class="row mx-auto">
        <div class="card shadow m-2">
            <img class="mt-2 main-img shadow mx-auto" src= "admin-upload/<?= $data["nama_file"];?>">
            <label class="mt-2 mx-auto fw-bold"><?= $data["nama_file"];?></label>
        </div>
    </div>
    <div class="jarak mx-auto mb-3">
    </div>      
</div>

 
    </div>
    <!------------------------------------------- -->


        </div>
<!------------------------------------------------------------------------------------>
    </div>

   
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
 <script src=" https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
 <script src=" https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <!-- <script type="text/javascript">
    $(document).ready(function(){
     $('#keyword').on('keyup', function(){
      $('#tabel1').load('pencarian.php?keyword=' + $('#keyword').val());
     });
    });
    </script> -->

    
  
  </body>
  </html>
</body>
</html>