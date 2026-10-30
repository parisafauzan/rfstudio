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
<title>Data Booking</title>
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
.nav-link:hover {
  background-color: black;
}
table{
  width: 100%;
}
th{
  text-align: center;
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
             <span class="navbar-light h1 ms-4 align-middle fw-bold" style="margin-top: 15px;">Rizal Foto Studio</span>
          </a>
        </div>
    </nav>
    <!-- Ini adalah akhir navbar header -->

     <!-- Awal Sidebar -->
<div class="container-fluid">
    <div class="row flex-nowrap">
       <div class="col-auto px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-1 pt-2 text-white min-vh-100">
                <a class="d-flex mx-auto pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline mt-4 fs-3">admin </span>
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
                        <a href="datahistory.php" class="nav-link mt-2 px-0 align-middle text-white" style="font-size: 16px;">
                            <i class="fa-solid fa-book m-2"></i> <span class=" me-2 d-none d-sm-inline">Data Pelanggan</span> </a>
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
<div class="container-md">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title ">
				<div class="row m-1 ">
					<h2>Nunggu<b>Pelunasan</b></h2>
          <label>*Apabila belum ada gambar pada <b>bukti lunas</b>, pelunasan belum dilakukan oleh client</label>
          <label>*Untuk package selfstudio langsung <b>lunas</b></label>
				</div>
			</div>
			<table class="table table-hover">
				<thead class="align-middle head">
					<tr >
              <th class="align-middle">Nama</th>
              <th class="align-middle">No. Telp</th>
              <th class="align-middle">Email</th>
              <th class="align-middle">Package</th>
              <th class="align-middle">Tanggal</th>
              <th class="align-middle">Jam</th>
              <th class="align-middle">Harga</th>
              <th class="align-middle">Sisa Pembayaran</th>
              <th class="align-middle">Bukti Transfer</th>
              <th class="align-middle">Bukti Lunas</th>
              <th class="align-middle">Waktu Transaksi</th>
              <th class="align-middle">Pilihan</th>
                        
					</tr>
				</thead>
				<tbody>
                    <?php //foreach ($data as $row ) :
                   
					$batas = 10;
					$halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
					$halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
					//
					$previous = $halaman - 1;
					$next = $halaman + 1;

					$data = mysqli_query($koneksi, "SELECT * FROM data_pelunasan_cobahampirfinishjuga  ORDER BY bukti_lunas ASC");
					$jumlah_data = mysqli_num_rows($data);
					$total_halaman = ceil($jumlah_data / $batas);


					$data_konfirmasi = mysqli_query($koneksi,"SELECT * FROM data_pelunasan_cobahampirfinishjuga  ORDER BY bukti_lunas ASC LIMIT $halaman_awal, $batas");
					$nomor = $halaman_awal+1;
					while($d = mysqli_fetch_array($data_konfirmasi)){
						?>
					<tr  style="font-size: 15px;">
     
            <td><?php echo $d["nama"];  ?></td>
            <td><?php echo $d["no_telp"];  ?></td>
            <td><?php echo $d["email"];  ?></td>
            <td><?php echo $d["package"]; echo " "; echo $d["tipe_package"]; ?></td><?php  ?>
            <td><?php echo $d["tanggal"];  ?></td>
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
            <td><?php echo $d["harga"];  ?></td>
            <td><?php echo $d["hargasetelahdp"];  ?></td>
            <td><img class="gallery-item" aria-label="bukti" src= "../assets/img/data_konfirmasi/<?= $d["bukti_transfer"];?>" width="80"></td>
            <td><img class="gallery-item" aria-label="buktiL" src= "../assets/img/data_pelunasan/<?= $d["bukti_lunas"];?>" width="80"></td>
            <td><?php echo $d["time_stamp"];  ?></td>
            <td>
              <?php $date = $d["tanggal"];
               $newDate = date("Y-m-d", strtotime($date)); ?> 
              <input type="text" name="waktuinput"  style=" border:#fff;" value="<?= $newDate?>" autocomplete="off" placeholder="0" class="form-control text-center bg-white" min="0" max="100" hidden>
              <!--  -->
              <a href="lunas.php?uniqid=<?= $d["uniqid"];  ?>" style="font-size: 13px;" class="btn btn-primary ms-2 text-white" name="tombol_konF" onclick="return confirm('Anda yakin client sudah melakukan pelunasan ?')">
              Konfirmasi</a>
              <a href="editlunas.php?uniqid=<?= $d["uniqid"];  ?>" style="font-size: 13px;" class="btn btn-warning ms-2 mt-2 " name="tombol_konF">
              Link Foto Preview</a>
              <a href="deletepelunasan.php?uniqid=<?= $d["uniqid"];  ?>" style="font-size: 13px;" class="btn btn-danger ms-2 mt-2 " name="tombol_konF">
              hapus</a>
            </td>
            </tr>
            <?php }//endforeach;?>
				</tbody>
			</table>
			<div class="clearfix d-flex justify-content-end">
				<ul class="pagination">
                    <li class="page-item"><a class="page-link"<?php if($halaman > 1){ echo "href='?halaman=$previous'"; } ?>>Previous</a></li>
					<?php 
						for($x=1;$x<=$total_halaman;$x++){
					?> 
					<li class="page-item"><a class="page-link" href="?halaman=<?php echo $x ?>"><?php echo $x; ?></a></li>
					<?php
					}
					?>		
					<li class="page-item"><a class="page-link" <?php if($halaman < $total_halaman) { echo "href='?halaman=$next'"; } ?>>Next</a></li>
				</ul>
			</div>
		</div>
	</div>        
</div>
<!-- Modal = ketika di klik masuk mode pop up -->
    <div class="modal fade" id="imgModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Bukti Transfer </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <img class="modal-img w-100" alt="Modal image" >
          </div>
        </div>
      </div>
    </div>


        </div>
<!------------------------------------------------------------------------------------>
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
$(document).ready(function(){
	// Activate tooltip
	$('[data-toggle="tooltip"]').tooltip();
	
	// Select/Deselect checkboxes
	var checkbox = $('table tbody input[type="checkbox"]');
	$("#selectAll").click(function(){
		if(this.checked){
			checkbox.each(function(){
				this.checked = true;                        
			});
		} else{
			checkbox.each(function(){
				this.checked = false;                        
			});
		} 
	});
	checkbox.click(function(){
		if(!this.checked){
			$("#selectAll").prop("checked", false);
		}
	});
});
</script>   
  </body>
  </html>
</body>
</html>