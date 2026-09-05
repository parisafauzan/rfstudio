<?php
// koneksi database
require '../../function.php';
require '../../session-kartini.php';

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
<title>Data Bulan Januari</title>
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
.align-middle{
  text-align: center;
}
footer {
  position: absolute;
  bottom: 0;
  width: 100%;
}

</style>
<body>

    <!-- Ini adalah awal navbar header -->
    <link rel="stylesheet" href="../../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
         <div class="container d-flex justify-content-center" >
          <a href="../../index.php" class="text-dark" style="text-decoration:none;">
             <img src="../../img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
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
                <a href="../datapembukuan.php" class="nav-link mt-4 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        < Back</span> </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0  align-items-center align-items-sm-start" id="menu">
                    <li>
                        <a href="#" class="nav-link mt-4 ms-2 align-middle text-white" style="background-color:black ; font-size: 16px;" >
                        Jan<span class="me-2 d-none d-sm-inline">uari</span> </a>
                    </li>
                    <li>
                        <a href="februari.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Feb<span class="me-2 d-none d-sm-inline">ruari</span> </a>
                    </li>
                    <li>
                        <a href="maret.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Mar<span class="me-2 d-none d-sm-inline">et</span> </a>
                    </li>
                    <li>
                        <a href="april.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Apr<span class="me-2 d-none d-sm-inline">il</span> </a>
                    </li>
                    <li>
                        <a href="mei.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Mei<span class="me-2 d-none d-sm-inline"></span> </a>
                    </li>
                    <li>
                        <a href="juni.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Jun<span class="me-2 d-none d-sm-inline">i</span> </a>
                    </li>
                    <li>
                        <a href="juli.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Jul<span class="me-2 d-none d-sm-inline">i</span> </a>
                    </li>
                    <li>
                        <a href="agustus.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Agu<span class="me-2 d-none d-sm-inline">stus</span> </a>
                    </li>
                    <li>
                        <a href="september.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Sep<span class="me-2 d-none d-sm-inline">tember</span> </a>
                    </li>
                    <li>
                        <a href="oktober.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Okt<span class="me-2 d-none d-sm-inline">ober</span> </a>
                    </li>
                    <li>
                        <a href="november.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Nov<span class="me-2 d-none d-sm-inline">ember</span> </a>
                    </li>
                    <li>
                        <a href="desember.php" class="nav-link mt-2 ms-2 align-middle text-white" style=" font-size: 16px;" >
                        Des<span class="me-2 d-none d-sm-inline">ember</span> </a>
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
      
<div class="col pt-3">
<!-------------------------------Tabell----------------------------------------------------->
<div class="row mb-3 mt-3">
  <div class="col text-end">
    <button class="btn btn-outline-dark" onClick="printDiv('printableArea')"><i class="fa-sharp fa-solid fa-print"></i> Print</button>
  </div>
  <div class="col ">
    <form method="post" action="jan-excel.php">
    <button type="submit" class="btn btn-outline-dark" name="export" ><i class="fa-solid fa-file-csv"></i> Excel</button>
    </form>
    </div>
</div>
<div class="container-md align-middle">
	<div class="table-responsive " id="printableArea">
        <h2 class="text-center">Data <b>Pembukuan </b></h2>
        <h5 class="text-center">- Seluruh data : Bulan Januari -</h5>
        <!-- <h3 class="text-center mt-5" id="h3">-- Belum ada data --</h3> -->
		<div class="table-wrapper" id="table1">
			<div class="table-title ">
				<div class="row m-1 text-center mb-3">
                    
				</div>
			</div>
			<table class="table table-bordered table-hover mx-auto"  style="font-size:14px; max-width: 1000px;">
				<thead class="align-middle head">
					<tr >
              <th class="align-middle" style="width: 5%;">No.</th>
              <th class="align-middle" style="width: 25%;">Waktu Transaksi</th>
              <th class="align-middle" style="width: 15%;">Nama</th>
              <th class="align-middle" style="width: 10%;">No. Telp</th>
              <th class="align-middle" style="width: 10%;"></th>
              <th class="align-middle" style="width: 15%;">Saldo Masuk</th>
              <th class="align-middle" style="width: 10%;">Potongan (amal)</th>
              <th class="align-middle" style="width: 20%;">Potongan (modal)</th>
              <th class="align-middle" style="width: 25%;">Pendapatan bersih</th>
                        
					</tr>
				</thead>
				<tbody class="align-middle">
          <?php //foreach ($data as $row ) :
          $saldo = "SELECT SUM(harga) AS sum FROM data_history WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (1)";
          $total = mysqli_query($koneksi,$saldo);
          if ($saldo>0){
          while($row = mysqli_fetch_assoc($total)){
          $output = $row['sum'];
          }}else{
            $output = 0;
          }
            $batas = 50;
            $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
            $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
            //
            $previous = $halaman - 1;
            $next = $halaman + 1;

            $data = mysqli_query($koneksi, "SELECT * FROM data_history WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (1) ORDER BY tanggal DESC");
            $jumlah_data = mysqli_num_rows($data);
            $total_halaman = ceil($jumlah_data / $batas);

            $datatable ="SELECT * FROM data_history WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (1) ORDER BY tanggal DESC LIMIT $halaman_awal, $batas";
            $data_konfirmasi = mysqli_query($koneksi,$datatable);
            $nomor = $halaman_awal+1;
            $i=1;
            // if($datatable>0){
                
            // }else{
            //     echo '<script language="javascript">';
            //     echo '$("#table1").hide();';
            //    // echo '$("#h3").show();'; 
            //     echo '</script>';
            // }
            while($d = mysqli_fetch_array($data_konfirmasi)){
            
			?>
			<tr>
                        <!--============================================================-->
            <!--============================================================-->
            <td hidden class="text-start"><?php echo $d["tanggal"];  ?></td>
            <td hidden class="text-start"><?php echo $d["nama"];  ?></td>
             <td hidden class="text-start"><?php 

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
            <td hidden class="text-start"><?php echo $d["package"];  ?></td>
            <td hidden class="text-start"><?php echo $d["jmlhorgdws"]; echo" ";echo $d["jmlhorgank"];  ?></td>
            <td hidden class="text-start"><?php echo $d["no_telp"];  ?></td>
            <td hidden class="text-start"><?php echo $d["email"];  ?></td>
            <td hidden class="text-start"><?php echo $d["cetak_foto"];  ?></td>
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
                else 
                {
                  echo $others2." jam";
                }
              ?></td>
            <td hidden class="text-start"><?php echo $d["tambah_cetak"];  ?></td>
            <td hidden class="text-start"><?php echo $d["harga"];  ?></td>
            <td hidden class="text-start"><?php echo $d["time_stamp"];  ?></td>
            <td hidden class="text-start"><?php echo $d["catatan"];  ?></td>
            <!--============================================================-->
            <!--============================================================-->
            <td class="text-center"><?php echo $nomor++;  ?></td>
            <td ><?php echo $d["tanggal"];  ?></td>
            <td class="text-start"><?php echo $d["nama"];  ?></td>
            <td class="text-start"><?php echo $d["no_telp"];  ?></td>
            <td class="text-start">
              <button type="button" style="font-size: 13px;" class="btn btn-success viewdata ms-2 mt-1 mb-2" id="viewdata" >
              Detail</button></td>
            <td class="text-start"><?php echo $d["harga"]; ?></td>
            <td class="text-start"><?php echo $d["harga"]*(0.025);  ?></td>
            <td class="text-start"><?php 
              
              $goldpwd = $d["harga"]*(0.6);
              $selfp = 0;
              $other = $d["harga"]*0.2;

              if ($d["package"] == 'gold' || $d["package"] == 'silver')
              {
                echo $goldpwd;
              }else if($d["package"] == 'self photo' || $d["package"] == 'studio')
              {
                echo $selfp;
              }else 
              {
               echo $other;
              }  ?></td>
              <td class="text-start fw-bold"><?php

                if ($d["package"] == 'gold' || $d["package"] == 'silver')
                {
                  echo  $d["harga"]-(($d["harga"]*0.6)+($d["harga"]*0.025));
                }else if($d["package"] == 'self photo' || $d["package"] == 'studio')
                {
                  echo  $d["harga"]-($selfp+($d["harga"]*0.025));
                }else 
                {
                   echo  $d["harga"]-(($d["harga"]*0.2)+($d["harga"]*0.025));
                } ;  ?></td>
            </tr>
            <?php $i++;?>
            <?php  }//endforeach;
              // $pwd = $d["harga"]*(0.6);
              // $selfp = 0;
              // $other = $d["harga"]*0.2;
              // $amal = $d["harga"]*(0.025);
              //////////////////////////////
                  $saldo1 = "SELECT SUM(harga) AS sum FROM data_history WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (1) AND tipe_package = 'prawedding'";
                  //"SELECT package sum(harga) total FROM(SELECT package,harga FROM data_history_cobahampirfinishjuga union all select package,harga FROM data_history) group by totalan";
                  $total1 = mysqli_query($koneksi,$saldo1);
                  while($row1 = mysqli_fetch_assoc($total1)){
                  $output1 = $row1['sum']-(($row1['sum']*0.6)+($row1['sum']*0.025));
                  $outputamal1 = ($row1['sum']*0.025) ;
                  $outputmodal1 = $row1['sum'] - ($row1['sum']*0.6);
                  }
                  $saldo2 = "SELECT SUM(harga) AS sum FROM data_history WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (1) AND package IN ('self photo','studio')";
                  $total2 = mysqli_query($koneksi,$saldo2);
                  while($row2 = mysqli_fetch_assoc($total2)){
                  $output2 = $row2['sum']-($row2['sum']*0.025);
                  $outputamal2 = ($row2['sum']*0.025) ;
                  $outputmodal2 = 0;
                  }
                  $saldo3 = "SELECT SUM(harga) AS sum FROM data_history WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (1) AND tipe_package IN ('best deal','special package','diamond package','baby best deal','baby special package')";
                  $total3 = mysqli_query($koneksi,$saldo3);
                  while($row3 = mysqli_fetch_assoc($total3)){
                  $output3 = $row3['sum']-(($row3['sum']*0.2)+($row3['sum']*0.025));
                  $outputamal3 = ($row3['sum']*0.025) ;
                  $outputmodal3 = $row3['sum'] - ($row3['sum']*0.2) ;;
                  }

                  $hasilbersih = $output1+$output2+$output3;
                  $outputamal = $outputamal1+$outputamal2+$outputamal3;
                  $outputmodal = $outputmodal1+$outputmodal2+$outputmodal3;
              
              //////////////////////////////

              ?>
                  
				</tbody>
			</table>
       
			
		<div class="container-sm ">
      <table style="margin-left:auto;max-width:350px">
        <tbody >
          <tr>
            <td class="text-end">
              <label class="fw-bold ">Total Pengeluaran Modal  : </label>          
            </td>
            <td class="text-end">
              Rp <?= $outputmodal;?>
            </td>
          </tr>
          <tr>
            <td class="text-end">
              <label class="fw-bold ">Total Pendapatan Bersih : </label>
            </td>
            <td class="text-end">
              Rp <?= $hasilbersih;?>
            </td>
          </tr>
          <tr>
            <td class="text-end">
              <label class="fw-bold ">Total Pengeluaran Amal : </label>
            </td>
            <td class="text-end">
              Rp <?= $outputamal;?>
            </td>
          </tr>
          <tr>
            <td class="text-end">
              <label class="fw-bold ">Total Dana Masuk : </label>          
            </td>
            <td class="text-end">
              Rp <?= $output;?>
            </td>
          </tr>
            
        </tbody>
      </table>
    </div> 
		</div>
	</div> 
  <div class="d-flex flex-column align-items-end " id="total1" style="margin-right: 100px;">
          
				<ul class="pagination ms-4">
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
        <div class="m-2">
         
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
                    <td class="">
                        <label >Tanggal</label>
                    </td>
                    <td>
                        <input id="tanggal" class="form-control mt-2 ms-3" style="width: 250px ; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Nama</label>
                    </td>
                    <td>
                        <input id="nama" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Jam</label>
                    </td>
                    <td>
                        <input id="jam" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Package</label>
                    </td>
                    <td>
                      <input id="package" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr><tr> 
                    <td class="">
                        <label>Jumlah yang foto</label>
                    </td>
                    <td>
                        <input id="jumlahfoto" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>No. Telp</label>
                    </td>
                    <td>
                        <input id="notelp" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Email</label>
                    </td>
                    <td>
                        <input id="email" class="form-control mt-2 ms-3" style="width: 250px;background-color:white;" type="text" readonly >
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Cetak Foto</label>
                    </td>
                    <td>
                        <input id="cetakfoto" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Tambah Anak</label>
                    </td>
                    <td>
                        <input id="tambahanak" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Tambah Dewasa</label>
                    </td>
                    <td>
                        <input id="tambahdewasa" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Tambah Orang</label>
                    </td>
                    <td>
                        <input id="tambahorang" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Tambah Waktu</label>
                    </td>
                    <td>
                        <input id="tambahwaktu" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Tambah Cetak</label>
                    </td>
                    <td>
                        <input id="tambahcetak" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Harga</label>
                    </td>
                    <td>
                        <input id="harga" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
                        <label>Waktu Transaksi</label>
                    </td>
                    <td>
                        <input id="waktutransaksi" class="form-control mt-2 ms-3" style="width: 250px; background-color:white;" type="text" readonly>
                    </td>
                </tr>
                <tr> 
                    <td class="">
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
    <!-- Edit Modal HTML -->
    <div id="viewModal" class="modal fade">
      <div class="modal-dialog">
      <div class="modal-content">
        <form name="frmedit" action="" method="post">
        <div class="modal-header">      
          <h4 class="modal-title">Edit Employee</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">     
          <div class="form-group">
          <label>Nama</label>
          <p name="name" class="form-control"><?php echo $edit_fullname; ?></p>
          </div>
          <div class="form-group">
          <label>Email</label>
          <p name="email" class="form-control"><?php echo $edit_fullname; ?></p>
          </div>
          <div class="form-group">
          <label>No. Telepon</label>
          <p name="" class="form-control"><?php echo $edit_fullname; ?></p>
          </div>
          <div class="form-group">
          <label>Phone</label>
          <p name="editname" class="form-control"><?php echo $edit_fullname; ?></p>
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
    </div>

    <!-- Akhir Sidebar -->





    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

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