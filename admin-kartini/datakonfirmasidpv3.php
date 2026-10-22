<?php
// koneksi database
require '../function.php';
require '../session-kartini.php';
     
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
<title>Nunggu Konfirmasi - Bekasi Kota</title>
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
th,td{
  text-align: start;
}


</style>
<body>
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
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto  px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-1 pt-2 text-white min-vh-100">
                <a class="d-flex mx-auto pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline mt-4 fs-3">admin</span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0  align-items-center align-items-sm-start" id="menu">
                    <li >
                        <a href="#" class="nav-link px-0 mt-2 align-middle text-white" style="background-color:black ; font-size: 16px;" >
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

<section class="container-md" id="containermd">
 
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title ">
				<div class="row m-1 ">
					<h2>Nunggu<b>Konfirmasi - Kartini</b></h2>
				</div>
			</div>
			<table class="table  table-hover">
				<thead class="align-middle head">
					<tr >
              <th class="align-middle">Tanggal</th>
              <th class="align-middle">Nama</th>
              <th class="align-middle">Jam</th>              
              <th class="align-middle">Package</th>  
                            <th class="align-middle">Izin Publikasi</th>
              <th class="align-middle">Tambahan</th>
              <th class="align-middle">Catatan</th>
<th class="align-middle">Bukti Transfer</th>             
              <th class="align-middle">Pilihan</th>
                        
					</tr>
				</thead>
				<tbody >
          <?php //foreach ($data as $row ) :
          

					$batas = 10;
					$halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
					$halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
					//
					$previous = $halaman - 1;
					$next = $halaman + 1;

					$data = mysqli_query($koneksi, "SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE studio = 'Bekasi-Kota' ORDER BY time_stamp ASC");

					$jumlah_data = mysqli_num_rows($data);
					$total_halaman = ceil($jumlah_data / $batas);


					$data_konfirmasi = mysqli_query($koneksi,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE studio = 'Bekasi-Kota' ORDER BY time_stamp ASC LIMIT $halaman_awal, $batas");
					$nomor = $halaman_awal+1;
					while($d = mysqli_fetch_array($data_konfirmasi)){
						?>
					<tr style="font-size: 15px;">
              <td><?php 
              if($d["package"] == 'cetak foto'){
                echo '--';
              }else{
              $tanggal1 = date("d-m-Y", strtotime($d["tanggal"]));
                        echo $tanggal1; 
               } ?></td>
              <td><?php echo $d["nama"];  ?></td>
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
                else if($d["jam"]=="20:00-21:00 21:00-22:00"){
                echo "20:00-22:00";
                }
                else{
                    echo $d["jam"];
                }
                
              }else if($d["package"]== "cetak foto"){
                echo "--";
              }else{
                echo $d["jam"];
              }
               ?></td>
              <td><?php echo $d["package"];echo " ";echo  '<b>'.$d["tipe_package"];'</b>'  ?> </td>
              <td>
                <?php $izinAdmin = bookingPublicationConsentStoredValue($d); ?>
                <span class="badge <?php echo bookingPublicationConsentBadgeClass($izinAdmin); ?>"><?php echo bookingPublicationConsentLabel($izinAdmin); ?></span>
              </td>
              <td style="min-width: 210px; font-size: 13px;"><?php echo bookingAdditionalSummary($d); ?></td>
              <td style="min-width: 180px; font-size: 13px;"><?php echo empty($d["catatan"]) ? '--' : nl2br(htmlspecialchars($d["catatan"], ENT_QUOTES, 'UTF-8')); ?></td>
              <td hidden><?php 
              if($d["package"] == 'cetak foto'){
                echo '--';
              }else{
               echo $d["jmlhorgdws"]; echo " ";echo $d["jmlhorgank"];
               }
               ?></td>
              <td hidden><?php echo $d["no_telp"];  ?></td>
              <td hidden><?php echo $d["email"];  ?></td>
              <td hidden><?php echo $d["cetak_foto"];  ?></td>
              <td hidden><?php 
              $dewasa = $d["anak"];
              $others2 = $d["anak"];
                if ($d["anak"] > 0)
                { 
                  echo $dewasa." orang";
                }
                else if( $d["anak"] == 0)
                {
                  echo "--";
                }
                
              ?></td>
              <td hidden><?php 
              $dewasa = $d["dewasa"];
              $others2 = $d["dewasa"];
                if ($d["dewasa"] > 0)
                { 
                  echo $dewasa." orang";
                }
                else if( $d["dewasa"] == 0)
                {
                  echo "--";
                }
                
              ?></td>
              <td hidden><?php
              $orang_self = $d["tambah_orang"];
              $others = $d["tambah_orang"];
                if ($d["package"] == 'self photo' && $d["tambah_orang"] > 0)
                { 
                  echo $orang_self." orang";
                }
                else if($d["package"] == 'self photo' && $d["tambah_orang"] == 0)
                {
                  echo "--";
                }
                else if($d["tambah_orang"] == '0')
                {
                  echo "--";
                }
                else 
                {
                  echo "--";
                }
                ?></td>
              <td hidden><?php 
              $waktu_self = $d["tambah_waktu"];
              $others2 = $d["tambah_waktu"];
             if ($d["package"] == 'self photo' && $d["tambah_waktu"] > 0)
                { 
                  echo $waktu_self." menit";
                }
                else if($d["package"] == 'self photo' && $d["tambah_waktu"] == 0)
                {
                  echo "--";
                }
                else if($d["tambah_waktu"] == '0')
                {
                  echo "--";
                }
                else if($d["tambah_waktu"] == '30')
                {
                 echo $others2." menit";
                }
                else 
                {
                  echo $others2." jam";
                }
              ?></td>
              <td hidden><?php if (empty($d["tambah_cetak"])){ echo '--';}else{echo $d["tambah_cetak"]; }?></td>
              <td hidden><?php echo $d["tambah_makeup"];  ?></td>
              <td hidden><?php echo $d["tambah_hairdo"];  ?></td>
              <td hidden><?php echo $d["harga"];  ?></td>
              <td hidden><?php echo $d["time_stamp"];  ?></td>
              <td hidden><?php echo $d["catatan"];  ?></td>
              <td>  
                <img class="gallery-item" aria-label="bukti" src= "../assets/img/data_konfirmasi/<?= $d["bukti_transfer"];?>" width="80" height="80"></td>
              <td>
                <a href="konfirmasi.php?uniqid=<?= $d["uniqid"];  ?>" style="font-size: 13px;" class="btn btn-primary ms-2 mt-2 text-white" name="tombol_konF" onclick="return confirm('Anda yakin biaya DP sudah masuk ke Rekening ?')">
                    Konfirmasi</a>
                <button type="button" style="font-size: 13px;" class="btn btn-success viewdata ms-2 mt-1" id="viewdata" >
                  Detail</button>
                <a href="edit.php?id=<?= $d["id"]; ?>"  style="font-size: 13px;" class="btn btn-warning ms-2 mt-1 text-dark" name="tombol_konF" >
                    Edit</a>
                <a href="delete.php?uniqid=<?= $d["uniqid"]; ?>" style="font-size: 13px;" class="btn btn-danger ms-2 mt-1 text-white" name="tombol_konF" onclick="return confirm('Anda yakin ingin menghapus data ini ?')">
                    Hapus</a>
              </td>
          </tr>
            <?php }//endforeach;?>
				</tbody>
			</table>
		</div>
    
	</div>   
  
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
                        <label>Tambah Makeup</label>
                    </td>
                    <td>
                        <input id="tambahmakeup" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="align-middle">
                        <label>Tambah Hairdo</label>
                    </td>
                    <td>
                        <input id="tambahhairdo" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
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
<!-- <h3 class='text-start note' id="note" style="margin-top: 200px; margin-left:200px;" hidden>Belum ada data yang masuk</h3> -->
<!------------------------------------------------------------------------------------>

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
              $('#jumlahfoto').val(data[7]);
              $('#notelp').val(data[8]);
              $('#email').val(data[9]);
              $('#cetakfoto').val(data[10]);
              $('#tambahanak').val(data[11]);
              $('#tambahdewasa').val(data[12]);
              $('#tambahorang').val(data[13]);
              $('#tambahwaktu').val(data[14]);
              $('#tambahcetak').val(data[15]);
              $('#tambahmakeup').val(data[16]);
              $('#tambahhairdo').val(data[17]);
              $('#harga').val(data[18]);
              $('#waktutransaksi').val(data[19]);
              $('#catatan').val(data[6]);

            });
        </script>
    

   
  </body>
  </html>
</body>
</html>
