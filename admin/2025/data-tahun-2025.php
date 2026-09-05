<?php
// koneksi database
require '../../function.php';
session_start();
 if(!isset($_SESSION['admin'])){
    header("location:../adminlogin.php");
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
  <link href="../../img/logo.png" rel="icon">
  <link href="../../img/logo.png" rel="apple-touch-icon">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- As a heading -->
<title>Data Pembukuan - Bekasi Kabupaten</title>
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
      
<div class="col pt-3">
<!-------------------------------Tabell----------------------------------------------------->

<div class="d-flex justify-content-between pb-4">
  <div class="kembali">
        <a class="btn btn-success ms-1 text-white" href="../datahistory.php" style="font-size: 13px;"><i class="fa-solid fa-chevron-left"></i><span class="d-none d-sm-inline mx-1 ms-2"> Kembali</span></a>
        <br>
    </div>
  <div class="m-1 text-center mb-3">
  <h2 class="text-center">Data <b>Pembukuan </b></h2>
  <h5 class="text-center">- Seluruh data -</h5>
</div>
  <div class="logout">
    <a href="logoutadmin.php" class="btn btn-outline-dark ms-3 align-items-center  text-decoration-none " onclick="return confirm('Anda yakin ingin keluar?')">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span class="d-none d-sm-inline mx-1 ms-3">Logout</span>
    </a><br>
  </div>
</div>
    <div class="table-responsive">
        <div class="d-flex mx-auto " style="width: 500px;">
            <div class="flex-fill text-end">
                <a href="../datapembukuan.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">All data</a>
            </div>
            <div class="flex-fill text-end">
                <a href="../2022/data-tahun-2022.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">2022</a>
            </div>
            <div class="flex-fill text-start">
                <a href="../2023/data-tahun-2023.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">2023</a>
            </div>
            <div class="flex-fill text-start">
                <a href="../2024/data-tahun-2024.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">2024</a>
            </div>
            <div class="flex-fill text-start">
                <a href="../2025/data-tahun-2025.php" type="button" class="btn btn-outline-dark active" style="width:100%;border-radius:0px">2025</a>
            </div>
            <div class="flex-fill text-start">
                <a href="../2026/data-tahun-2026.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">2026</a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
    <div class="d-flex mx-auto" style="max-width: fit-content;">
      <div class="flex-fill text-end">
          <a href="data-tahun-2025.php" type="button" class="btn btn-outline-dark active" style="width:100%;border-radius:0px;">All 2025</a>
      </div>
      <div class="flex-fill text-end">
          <a href="januari.php" type="button" class="btn btn-outline-dark " style="width:100%;border-radius:0px">Januari</a>
      </div>
      <div class="flex-fill text-end">
          <a href="februari.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Februari</a>
      </div>
      <div class="flex-fill text-end">
          <a href="maret.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Maret</a>
      </div>
      <div class="flex-fill text-end">
          <a href="april.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">April</a>
      </div>
      <div class="flex-fill text-end">
          <a href="mei.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Mei</a>
      </div>
      <div class="flex-fill text-end">
          <a href="juni.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Juni</a>
      </div>
      <div class="flex-fill text-end">
          <a href="juli.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Juli</a>
      </div>
      <div class="flex-fill text-end">
          <a href="agustus.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Agustus</a>
      </div>
      <div class="flex-fill text-end">
          <a href="september.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">September</a>
      </div>
      <div class="flex-fill text-end">
          <a href="oktober.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Oktober</a>
      </div>
      <div class="flex-fill text-end">
          <a href="november.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">November</a>
      </div>
      <div class="flex-fill text-end">
          <a href="desember.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Desember</a>
      </div>
    </div>
  </div>

<div class="row mb-3 mt-3 mx-auto" style="max-width: 400px;">
  <div class="col text-end">
    <button class="btn btn-outline-dark" onClick="printDiv('tabelPrint')"><i class="fa-sharp fa-solid fa-print"></i> Print</button>
  </div>
  <!-- <div class="col text-center">
    <button class="btn btn-outline-dark" ><i class="fa-solid fa-trash"></i> Delete all data</button>
  </div> -->
  <div class="col text-start">
    <form method="post" action="all-data-excel.php">
    <button type="submit" class="btn btn-outline-dark" name="export" ><i class="fa-solid fa-file-csv"></i> Excel</button>
    </form>
  </div>
</div>
<div class="container-md align-middle table-responsive " >
	<div>
		<div class="table-wrapper">
			<div class="table-title ">
				 
			</div>
      <!-- <div class="mb-3" style="margin-left:60px; ">
      <label class="text-center">Cari data</label>
      <input type="text" name="keyword" size="40" autofocus autocomplete="off" id="keyword">
      </div> -->
    <?php //foreach ($data as $row ) :
          $saldo = "SELECT SUM(t.harga) as total FROM (SELECT harga,package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,package FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t";
          $total = mysqli_query($koneksi,$saldo);
          while($row = mysqli_fetch_assoc($total)){
          $output = $row['total'];
          }
         $datatrigger = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga UNION SELECT * FROM data_history ORDER BY id DESC");
         $trigger = mysqli_fetch_array($datatrigger);
					// $batas = 20;
					// $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
					// $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
					// //
					// $previous = $halaman - 1;
					// $next = $halaman + 1;

					// $data = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga UNION SELECT * FROM data_history  ORDER BY id DESC");
					// $jumlah_data = mysqli_num_rows($data);
					// $total_halaman = ceil($jumlah_data / $batas);

					// $data_konfirmasi = mysqli_query($koneksi,"SELECT * FROM data_history_cobahampirfinishjuga UNION SELECT * FROM data_history ORDER BY id DESC LIMIT $halaman_awal, $batas");
					// $nomor = $halaman_awal+1;?>
      <div id="tabel1" style="font-size:12px;width:100%">
        <table id="" class="table tabel table-bordered table-hover mx-auto"  >
          <thead class="align-middle head">
            <tr>
                <th class="align-middle text-center" style="width: 5%;">No.</th>
                <th class="align-middle text-center" style="width: 15%;">Tanggal Booking</th>
                <th class="align-middle text-center" style="width: 15%;">Nama</th>
                <th class="align-middle text-center" style="width: 15%;">Package</th>
                <th class="align-middle text-center" style="width: 15%;">Saldo Masuk</th>
                <th class="align-middle text-center" style="width: 10%;">Potongan (amal)</th>
                <th class="align-middle text-center" style="width: 20%;">Potongan (modal)</th>
                <th class="align-middle text-center" style="width: 25%;">Pendapatan bersih</th>
                <th class="align-middle text-center" style="width: 20%;">Pilihan</th>
                          
            </tr>
          </thead>
          <tbody>
            <?php 
            $data = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION SELECT * FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') ORDER BY id DESC");
            $nomor = 1;
            while($d = mysqli_fetch_array($data)){
            ?>
            <tr >
              <td><?= $nomor++;?></td>
              <td><?php $date = $d["tanggal"]; $newdate = date("d-m-Y", strtotime($date)); echo $newdate;?></td>
              <td><?= $d["nama"]?></td>
              <td><?= $d["package"]." "."(".$d["tipe_package"].")"?></td>
              <td>Rp. <?= number_format($d["harga"],0," ",".");?></td>
                <td>Rp. <?php $amal = $d["harga"]*(0.025); echo number_format($amal,0," ","."); ?></td>
                <td>Rp. <?php 
                  
                  $goldpwd = $d["harga"]*(0.6);
                  $selfp = 0;
                  $other = $d["harga"]*0.2;
                  $cetak = $d["harga"]*0.34;

                  if ($d["package"] == 'gold' || $d["package"] == 'silver')
                  {
                    echo number_format($goldpwd,0," ",".");
                  }else if($d["package"] == 'self photo' || $d["package"] == 'studio')
                  {
                    echo number_format($selfp,0," ",".");
                  }else if($d["package"] == 'cetak foto')
                    {
                      echo number_format($cetak,0," ",".");
                    }
                  else 
                  {
                  echo number_format($other,0," ",".");
                  }  ?></td>
                <td>Rp. <?php

                    if ($d["package"] == 'gold' || $d["package"] == 'silver')
                    {
                      echo  number_format($d["harga"]-(($d["harga"]*0.6)+($d["harga"]*0.025)),0," ",".");
                    }else if($d["package"] == 'self photo' || $d["package"] == 'studio')
                    {
                      echo  number_format($d["harga"]-($selfp+($d["harga"]*0.025)),0," ",".");
                    }else if($d["package"] == 'cetak foto')
                    {
                      echo number_format($d["harga"]-(($d["harga"]*0.34)+($d["harga"]*0.025)),0," ",".");
                    }
                    else 
                    {
                      echo  number_format($d["harga"]-(($d["harga"]*0.2)+($d["harga"]*0.025)),0," ",".");
                    } ;  ?></td>
              <td><a type="button" href="detail-data-pelanggan.php?uniqid=<?=$d["uniqid"]?>" class="btn btn-outline-dark" style="font-size:12px">Detail</a></td>
            </tr>
            <?php }?>
          </tbody>
          <?php  
                // $pwd = $d["harga"]*(0.6);
                // $selfp = 0;
                // $other = $d["harga"]*0.2;
                // $amal = $d["harga"]*(0.025);
                //////////////////////////////
                    $saldo1 = "SELECT SUM(t.harga) as total FROM (SELECT harga,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,tipe_package FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE tipe_package = 'prawedding'";
                    //"SELECT package sum(harga) total FROM(SELECT package,harga FROM data_history_cobahampirfinishjuga union all select package,harga FROM data_history) group by totalan";
                    $total1 = mysqli_query($koneksi,$saldo1);
                    while($row1 = mysqli_fetch_assoc($total1)){
                    $output1 = $row1['total']-(($row1['total']*0.6)+($row1['total']*0.025));
                    $outputamal1 = ($row1['total']*0.025) ;
                    $outputmodal1 = ($row1['total']*0.6);
                    }
                    $saldo2 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,package FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE package IN ('self photo','studio')";
                    $total2 = mysqli_query($koneksi,$saldo2);
                    while($row2 = mysqli_fetch_assoc($total2)){
                    $output2 = $row2['total']-($row2['total']*0.025);
                    $outputamal2 = ($row2['total']*0.025) ;
                    $outputmodal2 = 0;
                    }
                    $saldo3 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,package,tipe_package FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE tipe_package IN ('best deal','special package','diamond package','baby best deal','baby special package')";
                    $total3 = mysqli_query($koneksi,$saldo3);
                    while($row3 = mysqli_fetch_assoc($total3)){
                    $output3 = $row3['total']-(($row3['total']*0.2)+($row3['total']*0.025));
                    $outputamal3 = ($row3['total']*0.025) ;
                    $outputmodal3 = ($row3['total']*0.2) ;
                    }
                    $saldo4 = "SELECT SUM(t.harga) as total FROM (SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT * FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE package = 'cetak foto'";
                    $total4 = mysqli_query($koneksi,$saldo4);
                    while($row4 = mysqli_fetch_assoc($total4)){
                    $output4 = $row4['total']-(($row4['total']*0.34)+($row4['total']*0.025));
                    $outputamal4 = ($row4['total']*0.025) ;
                    $outputmodal4 = ($row4['total']*0.34) ;
                    }

                    $hasilbersih = $output1+$output2+$output3+$output4;
                    $outputamal = $outputamal1+$outputamal2+$outputamal3+$outputamal4;
                    $outputmodal = $outputmodal1+$outputmodal2+$outputmodal3+$outputmodal4;
                
                //////////////////////////////
                ?>
        </table>
        <div class="container-sm ">
          <table style="margin-left:auto;max-width:350px">
              <tbody >
              <tr>
                  <td class="text-end">
                  <label class="fw-bold ">Total Pengeluaran Modal  : </label>          
                  </td>
                  <td class="text-end">
                  Rp <?php echo number_format($outputmodal,0," ",".");?>
                  </td>
              </tr>
              <tr>
                  <td class="text-end">
                  <label class="fw-bold ">Total Pendapatan Bersih : </label>
                  </td>
                  <td class="text-end">
                  Rp <?php echo number_format($hasilbersih,0," ",".");?>
                  </td>
              </tr>
              <tr>
                  <td class="text-end">
                  <label class="fw-bold ">Total Pengeluaran Amal : </label>
                  </td>
                  <td class="text-end">
                  Rp <?php echo number_format($outputamal,0," ",".");?>
                  </td>
              </tr>
              <tr>
                  <td class="text-end">
                  <label class="fw-bold ">Total Dana Masuk : </label>          
                  </td>
                  <td class="text-end">
                  Rp <?php echo number_format($output,0," ",".");?>
                  </td>
              </tr>
                  
              </tbody>
          </table>
        </div>
      </div>
      <div id="tabelPrint" hidden style="font-size:13px;width:100%">
          <table id="" class="table table-bordered table-hover mx-auto"  >
          <thead class="align-middle head">
              <tr>
                  <th class="align-middle text-center" style="width: 5%;">No.</th>
                  <th class="align-middle text-center" style="width: 15%;">Tanggal Booking</th>
                  <th class="align-middle text-center" style="width: 15%;">Nama</th>
                  <th class="align-middle text-center" style="width: 15%;">Package</th>
                  <th class="align-middle text-center" style="width: 15%;">Saldo Masuk</th>
                  <th class="align-middle text-center" style="width: 10%;">Potongan (amal)</th>
                  <th class="align-middle text-center" style="width: 20%;">Potongan (modal)</th>
                  <th class="align-middle text-center" style="width: 25%;">Pendapatan bersih</th>
                  
                          
              </tr>
          </thead>
          <tbody>
              <?php 
              $data = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')  UNION ALL SELECT * FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')  ORDER BY id DESC");
              $nomor = 1;
              while($d = mysqli_fetch_array($data)){
              ?>
              <tr >
              <td><?= $nomor++;?></td>
              <td><?php $date = $d["tanggal"]; $newdate = date("d-m-Y", strtotime($date)); echo $newdate;?></td>
              <td><?= $d["nama"]?></td>
              <td><?= $d["package"]." "."(".$d["tipe_package"].")"?></td>
              <td>Rp. <?= number_format($d["harga"],0," ",".");?></td>
              <td>Rp. <?php $amal = $d["harga"]*(0.025); echo number_format($amal,0," ","."); ?></td>
              <td>Rp. <?php 
                  
                  $goldpwd = $d["harga"]*(0.6);
                  $selfp = 0;
                  $other = $d["harga"]*0.2;
                  $cetak = $d["harga"]*0.34;

                  if ($d["package"] == 'gold' || $d["package"] == 'silver')
                  {
                  echo number_format($goldpwd,0," ",".");
                  }else if($d["package"] == 'self photo' || $d["package"] == 'studio')
                  {
                  echo number_format($selfp,0," ",".");
                  }else if($d["package"] == 'cetak foto')
                  {
                      echo number_format($cetak,0," ",".");
                  }
                  else 
                  {
                  echo number_format($other,0," ",".");
                  }  ?></td>
              <td>Rp. <?php

                  if ($d["package"] == 'gold' || $d["package"] == 'silver')
                  {
                      echo  number_format($d["harga"]-(($d["harga"]*0.6)+($d["harga"]*0.025)),0," ",".");
                  }else if($d["package"] == 'self photo' || $d["package"] == 'studio')
                  {
                      echo  number_format($d["harga"]-($selfp+($d["harga"]*0.025)),0," ",".");
                  }else if($d["package"] == 'cetak foto')
                  {
                      echo number_format($d["harga"]-(($d["harga"]*0.34)+($d["harga"]*0.025)),0," ",".");
                  }
                  else 
                  {
                      echo  number_format($d["harga"]-(($d["harga"]*0.2)+($d["harga"]*0.025)),0," ",".");
                  } ;  ?></td>
              
              </tr>
              <?php }?>
          </tbody>
          <?php  
                  // $pwd = $d["harga"]*(0.6);
                  // $selfp = 0;
                  // $other = $d["harga"]*0.2;
                  // $amal = $d["harga"]*(0.025);
                  //////////////////////////////
                      $saldo1 = "SELECT SUM(t.harga) as total FROM (SELECT harga,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')  UNION ALL SELECT harga,tipe_package FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE tipe_package = 'prawedding'";
                      //"SELECT package sum(harga) total FROM(SELECT package,harga FROM data_history_cobahampirfinishjuga union all select package,harga FROM data_history) group by totalan";
                      $total1 = mysqli_query($koneksi,$saldo1);
                      while($row1 = mysqli_fetch_assoc($total1)){
                      $output1 = $row1['total']-(($row1['total']*0.6)+($row1['total']*0.025));
                      $outputamal1 = ($row1['total']*0.025) ;
                      $outputmodal1 = ($row1['total']*0.6);
                      }
                      $saldo2 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')  UNION ALL SELECT harga,package FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE package IN ('self photo','studio')";
                      $total2 = mysqli_query($koneksi,$saldo2);
                      while($row2 = mysqli_fetch_assoc($total2)){
                      $output2 = $row2['total']-($row2['total']*0.025);
                      $outputamal2 = ($row2['total']*0.025) ;
                      $outputmodal2 = 0;
                      }
                      $saldo3 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')  UNION ALL SELECT harga,package,tipe_package FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE tipe_package IN ('best deal','special package','diamond package','baby best deal','baby special package')";
                      $total3 = mysqli_query($koneksi,$saldo3);
                      while($row3 = mysqli_fetch_assoc($total3)){
                      $output3 = $row3['total']-(($row3['total']*0.2)+($row3['total']*0.025));
                      $outputamal3 = ($row3['total']*0.025) ;
                      $outputmodal3 = ($row3['total']*0.2) ;
                      }
                      $saldo4 = "SELECT SUM(t.harga) as total FROM (SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')  UNION ALL SELECT * FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE package = 'cetak foto'";
                      $total4 = mysqli_query($koneksi,$saldo4);
                      while($row4 = mysqli_fetch_assoc($total4)){
                      $output4 = $row4['total']-(($row4['total']*0.34)+($row4['total']*0.025));
                      $outputamal4 = ($row4['total']*0.025) ;
                      $outputmodal4 = ($row4['total']*0.34) ;
                      }

                      $hasilbersih = $output1+$output2+$output3+$output4;
                      $outputamal = $outputamal1+$outputamal2+$outputamal3+$outputamal4;
                      $outputmodal = $outputmodal1+$outputmodal2+$outputmodal3+$outputmodal4;
                  
                  //////////////////////////////
                  ?>
          </table>
        <div class="container-sm ">
          <table style="margin-left:auto;max-width:350px">
              <tbody >
              <tr>
                  <td class="text-end">
                  <label class="fw-bold ">Total Pengeluaran Modal  : </label>          
                  </td>
                  <td class="text-end">
                  Rp <?php echo number_format($outputmodal,0," ",".");?>
                  </td>
              </tr>
              <tr>
                  <td class="text-end">
                  <label class="fw-bold ">Total Pendapatan Bersih : </label>
                  </td>
                  <td class="text-end">
                  Rp <?php echo number_format($hasilbersih,0," ",".");?>
                  </td>
              </tr>
              <tr>
                  <td class="text-end">
                  <label class="fw-bold ">Total Pengeluaran Amal : </label>
                  </td>
                  <td class="text-end">
                  Rp <?php echo number_format($outputamal,0," ",".");?>
                  </td>
              </tr>
              <tr>
                  <td class="text-end">
                  <label class="fw-bold ">Total Dana Masuk : </label>          
                  </td>
                  <td class="text-end">
                  Rp <?php echo number_format($output,0," ",".");?>
                  </td>
              </tr>
                  
              </tbody>
          </table>
        </div>
      </div>
		</div>
  </div> 
  <div class="d-flex flex-column align-items-end " style="margin-right: 20px;margin-bottom:20px;" >
			
		</div>       
</div>
<!-- Modal = ketika di klik masuk mode pop up -->
    <?php
    $ambildata = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION SELECT * FROM data_history WHERE tanggal LIKE '%2025%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') ORDER BY id DESC");
    $dataambil = mysqli_fetch_array($ambildata);
    ?>
    <!-- <div class="m-2">
        <table>
            <thead>
                <tr>
                    <th>tanggal</th>
                    <th>nama</th>
                    <th>jam</th>
                    <th>package</th>
                    <th>jumlah yang foto</th>
                    <th>notelp</th>
                    <th>email</th>
                    <th>cetakfoto</th>
                    <th>tambahanak</th>
                    <th>tambahdewasa</th>
                    <th>tambahorang</th>
                    <th>tambahwaktu</th>
                    <th>tambahcetak</th>
                    <th>harga</th>
                    <th>waktutransaksi</th>
                    <th>catatan</th>
                </tr>
            </thead>
            <tbody>
                <tr class="tr">
                <td class="td"><?= $dataambil["tanggal"]?></td>
                <td class="td"><?= $dataambil["nama"]?></td>
                <td class="td"><?= $dataambil["jam"]?></td>
                <td class="td"><?= $dataambil["package"]?></td>
                <td class="td"><?php echo $dataambil["jmlhorgdws"]; echo" ";echo $dataambil["jmlhorgank"];  ?></td>
                <td class="td"><?= $dataambil["no_telp"]?></td>
                <td class="td"><?= $dataambil["email"]?></td>
                <td class="td"><?= $dataambil["cetak_foto"]?></td>
                <td class="td"><?php 
              $dewasa = $dataambil["anak"];
              $others2 = $dataambil["anak"];
                if ($dataambil["anak"] > 0)
                { 
                  echo $dewasa." orang";
                }
                else if( $dataambil["anak"] == 0)
                {
                  echo "--";
                }
                
              ?></td>
              <td class="td"><?php 
              $dewasa = $dataambil["dewasa"];
              $others2 = $dataambil["dewasa"];
                if ($dataambil["dewasa"] > 0)
                { 
                  echo $dewasa." orang";
                }
                else if( $dataambil["dewasa"] == 0)
                {
                  echo "--";
                }
                
              ?></td>
              <td class="td"><?php
              $orang_self = $dataambil["tambah_orang"];
              $others = $dataambil["tambah_orang"];
                if ($dataambil["package"] == 'self photo' && $dataambil["tambah_orang"] > 0)
                { 
                  echo $orang_self." orang";
                }
                else if($dataambil["package"] == 'self photo' && $dataambil["tambah_orang"] == 0)
                {
                  echo "--";
                }
                else if($dataambil["tambah_orang"] == '0')
                {
                  echo "--";
                }
                else 
                {
                  echo "--";
                }
                ?></td>
              <td class="td"><?php 
              $waktu_self = $dataambil["tambah_waktu"];
              $others2 = $dataambil["tambah_waktu"];
              if ($dataambil["package"] == 'self photo' && $dataambil["tambah_waktu"] > 0)
                { 
                  echo $waktu_self." menit";
                }
                else if($dataambil["package"] == 'self photo' && $dataambil["tambah_waktu"] == 0)
                {
                  echo "--";
                }
                else if($dataambil["tambah_waktu"] == '0')
                {
                  echo "--";
                }
                else 
                {
                  echo $others2." jam";
                }
              ?></td>
            <td class="td" ><?php echo $dataambil["tambah_cetak"];  ?></td>
            <td class="td" ><?php echo $dataambil["harga"];  ?></td>
            <td class="td" ><?php echo $dataambil["time_stamp"];  ?></td>
            <td class="td" ><?php echo $dataambil["catatan"];  ?></td>
            </tr>
            </tbody>
            <tbody>

            </tbody>
        </table>
    </div> -->
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