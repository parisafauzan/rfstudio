<?php
// koneksi database
require '../function.php';
$uniqid = $_GET["uniqid"];
$ambildata = query("SELECT * FROM data_history_cobahampirfinishjuga WHERE uniqid ='$uniqid' UNION SELECT * FROM data_history WHERE uniqid ='$uniqid'")[0];
session_start();
 if(!isset($_SESSION['admin'])){
    header("location:adminlogin.php");
    exit;
}

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
<title>Detail - Bekasi Kabupaten</title>
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

</style>
<body>

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
<div class="container-fluid">
      <div class="row flex-nowrap">
      
<div class="pt-3">
<!-------------------------------Tabell----------------------------------------------------->

<div class="d-flex justify-content-between pb-4">
  <div class="kembali">
    <a href="logoutadmin.php" class="btn btn-outline-dark align-items-center  text-decoration-none " onClick="<?php echo 'history.go(-1);';?>">
      <i class="fa-solid fa-circle-left"></i>
      <span class="d-none d-sm-inline mx-1 ms-3">Kembali</span>
    </a><br>
  </div>
  <div class="m-1 text-center mb-3">
    <h2 class="text-center">Data <b>Pelanggan </b></h2>
    <h5 class="text-center">Detail data pelanggan</h5>
  </div>
  <div class="logout">
    <a href="logoutadmin.php" class="btn btn-outline-dark align-items-center  text-decoration-none " onclick="return confirm('Anda yakin ingin keluar?')">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span class="d-none d-sm-inline mx-1 ms-3">Logout</span>
    </a><br>
  </div>
</div>
 
<div class="container-md " >
    <div class="mx-auto border" style="margin-bottom:40px;max-width:800px;" >
      <div class="row mx-auto">
        <div class="card shadow m-2" style="width: 270px;max-height:430px">
          <img class="mt-2 shadow" style="width: 243px;max-height:380px; " src= "../assets/img/data_konfirmasi/<?= $ambildata["bukti_transfer"];?>">
          <label class="mt-2 mx-auto fw-bold">Bukti Transfer</label>
        </div>
        <div class="col-md mt-2 mb-1">
          <div class="jarak">
            <label class="m-1 fw-bold">Nama</label></br>
            <label class="ms-2 "><?=$ambildata["nama"]?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Nomor Telpon</label></br>
            <label class="ms-2 "><?=$ambildata["no_telp"]?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Email</label></br>
            <label class="ms-2 "><?=$ambildata["email"]?></label>

          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Package</label></br>
            <label class="ms-2 "><?=$ambildata["package"]." (".$ambildata["tipe_package"].")"?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Lokasi Studio</label></br>
            <label class="ms-2 "><?php
            if($ambildata["studio"]==''||$ambildata["studio"]=='Bekasi-Kabupaten'){
              echo 'Bekasi-Kabupaten';
            }
            ?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Anak-anak</label></br>
            <label class="ms-2 "><?=$ambildata["jmlhorgank"]?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Dewasa</label></br>
            <label class="ms-2 "><?=$ambildata["jmlhorgdws"]?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Harga</label></br>
            <label class="ms-2 ">Rp. <?= number_format($ambildata["harga"],0," ",".");?></label>
          </div>
        </div>
        <div class="col-md mt-2 mb-1">
         <div class="jarak">
            <label class="m-1 fw-bold">Tanggal Photoshoot</label></br>
            <label class="ms-2 "><?=$ambildata["tanggal"]?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Jam Photoshoot</label></br>
            <label class="ms-2 "><?=$ambildata["jam"]?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Ukuran Cetak Foto</label></br>
            <label class="ms-2 "><?= $ambildata["cetak_foto"]?></label>
          </div>
          <div class="jarak">
            <label class="m-2 ">*Tambahan Optional(Best-deal/special)</label>
            <label class="m-1 fw-bold">Tambah anak-anak</label></br>
            <label class="ms-2 "><?= $ambildata["anak"]?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Tambah Orang Dewasa</label></br>
            <label class="ms-2 "><?= $ambildata["dewasa"]?></label>
          </div>
          <div class="jarak">
            <label class="m-2 ">*Tambahan Optional(SelfPhoto)</label>
            <label class="m-1 fw-bold">Tambah Waktu</label></br>
            <label class="ms-2 "><?= $ambildata["tambah_waktu"]?></label>
          </div>
          <div class="jarak">
            <label class="m-1 fw-bold">Tambah Orang</label></br>
            <label class="ms-2 "><?= $ambildata["tambah_orang"]?></label>
          </div>
        </div>
      </div>
      <div class="jarak mx-auto mb-3">
          <label class="m-1 fw-bold">Catatan</label></br>
          <label class="ms-2 "><?php if(empty($ambildata["catatan"])){echo "- tidak ada catatan -";}else{ echo $ambildata["catatan"];}?></label>
        </div>
		</div>       
</div>
<!-- Modal = ketika di klik masuk mode pop up -->
 
    </div>
    <!------------------------------------------- -->
    <!-- Edit Modal HTML -->
    <div id="viewModal" class="modal fade">
      <div class="modal-dialog">
      <div class="modal-content">
        <form name="frmedit" action="" method="post">
        <div class="modal-header">      
          <h4 class="modal-title">Edit Employee</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">     
          <div class="form-group">
          <label>Nama</label>
          <p name="name" class="form-control bg-white"><?php echo $edit_fullname; ?></p>
          </div>
          <div class="form-group">
          <label>Email</label>
          <p name="email" class="form-control bg-white"><?php echo $edit_fullname; ?></p>
          </div>
          <div class="form-group">
          <label>No. Telepon</label>
          <p name="" class="form-control bg-white"><?php echo $edit_fullname; ?></p>
          </div>
          <div class="form-group">
          <label>Phone</label>
          <p name="editname" class="form-control bg-white"><?php echo $edit_fullname; ?></p>
          </div>     
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
          <input type="submit" name="cmdedit" class="btn btn-info" value="Save">
        </div>
        </form>
      </div>
      </div>
    </div>


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

    <script >
      $(document).ready(function () {
          $('table.tabel').DataTable();
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
              $('#harga').val(data[13]);
              $('#waktutransaksi').val(data[14]);
              $('#catatan').val(data[15]);

            });
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
 <script>

	function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

</script>   
  </body>
  </html>
</body>
</html>