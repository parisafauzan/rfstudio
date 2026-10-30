<?php
// koneksi database
require '../function.php';
require '../session-kartini.php';
// ambil data dari database

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
<title>Data History - Bekasi Kota</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet" />
</head>
<!--  -->
<style>
.nav-link:hover {
  background-color: black;
}
table{
  width: 100%;
}
.tengah{
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
             <span class="navbar-light h1 ms-2 align-middle fw-bold" style=" margin-top: 15px; ">Rizal Foto Studio</span>
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
                    <span class="fs-5 d-none d-sm-inline mt-4 fs-3">admin</span>
                </a>
               <ul class="nav nav-pills flex-column mb-sm-auto mb-0  align-items-center align-items-sm-start" id="menu">
                    <li >
                        <a href="datakonfirmasidp.php" class="nav-link px-0 mt-2 align-middle text-white" style="font-size: 16px;" >
                        <i class="fa-solid fa-list-check m-2 "></i> <span class=" me-2 d-none d-sm-inline">Nunggu Konfirmasi</span> </a>
                    </li>
                    <li>
                        <a href="databooking.php" class="nav-link mt-2 px-0 align-middle text-white" style=" font-size: 16px;">
                            <i class="fa-solid fa-list-ol m-2"></i> <span class=" me-2 d-none d-sm-inline">Jadwal Photoshoot</span></a>
                    </li>
                   
                    <li>
                        <a href="#" class="nav-link mt-2 px-0 align-middle text-white"style="background-color:black ;font-size: 16px;">
                            <i class="fa-solid fa-book m-2"></i> <span class=" me-2 d-none d-sm-inline">Data Pelanggan</span> </a>
                    </li>
                    <li>
                        <a href="setting.php" class="nav-link mt-2 px-0 align-middle text-white" style="font-size: 16px;">
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
<div class="container-md" style="font-size: 13px;">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title ">
				<div class="row m-1 ">
					<h2>Data<b>Pelanggan - Kartini</b></h2> </b><a href="datapembukuan.php" type="button" class="text-dark mt-3 mb-3 " style="max-width:200px;margin-left:100px;font-size: 20px; margin-left: auto;">Lihat Pembukuan</a></h2>
                    
				</div>
			</div>
			<table class="table table-hover table-bordered" id="tabel">
				<thead class="align-middle head">
					<tr >
              <th class="tengah" style="width: 10%;">Photoshoot</th>
              <th class="tengah" style="width: 10%;">Nama</th>
              <th class="tengah" style="width: 10%;">Package</th>
              <th class="tengah" style="width: 10%;">Pilihan</th>
              <th class="tengah" style="width: 10%;">Waktu Transaksi</th> 
                        
					</tr>
				</thead>
				<tbody >
                    <?php //foreach ($data as $row ) :
                   
					// $batas = 10;
					// $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
					// $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
					// //
					// $previous = $halaman - 1;
					// $next = $halaman + 1;

					// $data = mysqli_query($koneksi, "SELECT * FROM data_history WHERE (studio = 'Bekasi-Kabupaten' OR studio = '') ORDER BY id DESC");
					// $jumlah_data = mysqli_num_rows($data);
					// $total_halaman = ceil($jumlah_data / $batas);


					$data_konfirmasi = mysqli_query($koneksi,"SELECT * FROM data_history WHERE studio ='Bekasi-Kota' UNION SELECT * FROM data_history_cobahampirfinishjuga WHERE studio ='Bekasi-Kota' ORDER BY id DESC ");
					// $nomor = $halaman_awal+1;

					while($d = mysqli_fetch_array($data_konfirmasi)){
						?>
					<tr style="font-size: 15px;">
            <td class="tengah"><?php  
            if($d["package"]=="cetak foto"){
              echo"--";
              }
            else{
              $tanggal1 = date("d-m-Y", strtotime($d["tanggal"]));
              echo $tanggal1; echo " "; 
              
              } 
               ?></td>
            <td class="tengah"><?php echo $d["nama"];  ?></td>
            <td class="tengah"><?php 
            if($d["package"]=='cetak foto'||$d["package"] =='cetak foto '){
              echo $d["package"];
            }else{
            echo $d["package"].' <b>'.$d["tipe_package"].'</b>'; 
            }?></td>
            <td class="">
              <a href="detail-data-pelanggan.php?uniqid=<?= $d["uniqid"];  ?>" type="button" style="font-size: 13px;" class="btn btn-success  ms-2 mt-1 mb-2" >
              Detail</a>
              <a href="editlunas.php?uniqid=<?= $d["uniqid"];  ?>" style="font-size: 13px;" class="btn btn-primary ms-2 "  >
              Link Foto Preview</a>
              <a href="edithistory.php?uniqid=<?= $d["uniqid"];  ?>" style="font-size: 13px;" class="btn btn-warning ms-2 mt-2" >
              Link Foto Final</a>
              <a href="deletehistory.php?uniqid=<?= $d["uniqid"];  ?>" style="font-size: 13px;" class="btn btn-danger ms-2 mt-2" onclick="return confirm('Anda yakin ingin menghapus data ini ?')">
              Hapus</a>
            </td>
            <td class="tengah"><?php echo $d["time_stamp"];  ?></td>
          </tr>
            <?php }//endforeach;?>
				</tbody>
			</table>
			<!-- <div class="clearfix d-flex justify-content-end">
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
			</div> -->
		</div>
	</div>        
</div>
<!-- Modal = ketika di klik masuk mode pop up -->
    <div class="modal fade" id="imgModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header">
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
     
        </div>
<!------------------------------------------------------------------------------------>

    </div>
</div>
    <!-- Akhir Sidebar -->
<!-- Modal = ketika di klik masuk mode pop up -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
            <div class="modal-header " >
                <h5 class="modal-title" id="exampleModalLabel">Data Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" style="width:100%; ">
            <table class="table table-hover" style="font-size: 15px;">
                <tbody>
                <tr> 
                    <td class="align-middle">
                        <label >Tanggal</label>
                    </td>
                    <td>
                        <input id="tanggal" class="form-control mt-2 ms-3" style="width: 250px ; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Nama</label>
                    </td>
                    <td>
                        <input id="nama" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Jam</label>
                    </td>
                    <td>
                        <input id="jam" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Package</label>
                    </td>
                    <td>
                      <input id="package" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr><tr> 
                    <td class="align-middle">
                        <label>Jumlah yang foto</label>
                    </td>
                    <td>
                        <input id="jumlahfoto" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>No. Telp</label>
                    </td>
                    <td>
                        <input id="notelp" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Email</label>
                    </td>
                    <td>
                        <input id="email" class="form-control mt-2 ms-3" style="width: 250px;background-color:white;" type="text" readonly >
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Cetak Foto</label>
                    </td>
                    <td>
                        <input id="cetakfoto" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Tambah Anak</label>
                    </td>
                    <td>
                        <input id="tambahanak" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Tambah Dewasa</label>
                    </td>
                    <td>
                        <input id="tambahdewasa" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Tambah Orang</label>
                    </td>
                    <td>
                        <input id="tambahorang" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Tambah Waktu</label>
                    </td>
                    <td>
                        <input id="tambahwaktu" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Tambah Cetak</label>
                    </td>
                    <td>
                        <input id="tambahcetak" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Harga</label>
                    </td>
                    <td>
                        <input id="harga" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Waktu Transaksi</label>
                    </td>
                    <td>
                        <input id="waktutransaksi" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Catatan</label>
                    </td>
                    <td>
                        <input id="catatan" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                
                </tbody>
            </table>
            </div>
            </div>
        </div>
    </div>
    <!------------------------------------------- -->

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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src=" https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src=" https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
  
    <!-- Modal Foto -->
    <script type="text/javascript">
    $(document).ready(function () {
          $('#tabel').DataTable();
      }); 
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
 <script >
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
  </body>
  </html>
</body>
</html>