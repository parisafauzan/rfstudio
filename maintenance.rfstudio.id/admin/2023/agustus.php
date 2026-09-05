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
  <h5 class="text-center">- Data Agustus -</h5>
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
                <a href="../2023/data-tahun-2023.php" type="button" class="btn btn-outline-dark active" style="width:100%;border-radius:0px">2023</a>
            </div>
            <div class="flex-fill text-start">
                <a href="../2024/data-tahun-2024.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">2024</a>
            </div>
            <div class="flex-fill text-start">
                <a href="../2025/data-tahun-2025.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">2025</a>
            </div>
            <div class="flex-fill text-start">
                <a href="../2026/data-tahun-2026.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">2026</a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
    <div class="d-flex mx-auto" style="max-width: fit-content;">
      <div class="flex-fill text-end">
          <a href="data-tahun-2023.php" type="button" class="btn btn-outline-dark " style="width:100%;border-radius:0px;">All 2023</a>
      </div>
      <div class="flex-fill text-end">
          <a href="januari.php" type="button" class="btn btn-outline-dark" style="width:100%;border-radius:0px">Januari</a>
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
          <a href="agustus.php" type="button" class="btn btn-outline-dark active" style="width:100%;border-radius:0px">Agustus</a>
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
    <form method="post" action="agu-excel.php">
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
          $saldo = "SELECT SUM(t.harga) as total FROM (SELECT harga,package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,package FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t";
          $total = mysqli_query($koneksi,$saldo);
          while($row = mysqli_fetch_assoc($total)){
          $output = $row['total'];
          }
         $datatrigger = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga UNION SELECT * FROM data_history ORDER BY id DESC");
         $trigger = mysqli_fetch_array($datatrigger);?>
      <div id="tabel1" style="font-size:13px;width:100%">
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
            $data = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION SELECT * FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') ORDER BY id DESC");
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
                    $saldo1 = "SELECT SUM(t.harga) as total FROM (SELECT harga,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,tipe_package FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE tipe_package = 'prawedding'";
                    //"SELECT package sum(harga) total FROM(SELECT package,harga FROM data_history_cobahampirfinishjuga union all select package,harga FROM data_history) group by totalan";
                    $total1 = mysqli_query($koneksi,$saldo1);
                    while($row1 = mysqli_fetch_assoc($total1)){
                    $output1 = $row1['total']-(($row1['total']*0.6)+($row1['total']*0.025));
                    $outputamal1 = ($row1['total']*0.025) ;
                    $outputmodal1 = ($row1['total']*0.6);
                    }
                    $saldo2 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,package FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE package IN ('self photo','studio')";
                    $total2 = mysqli_query($koneksi,$saldo2);
                    while($row2 = mysqli_fetch_assoc($total2)){
                    $output2 = $row2['total']-($row2['total']*0.025);
                    $outputamal2 = ($row2['total']*0.025) ;
                    $outputmodal2 = 0;
                    }
                    $saldo3 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,package,tipe_package FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE tipe_package IN ('best deal','special package','diamond package','baby best deal','baby special package')";
                    $total3 = mysqli_query($koneksi,$saldo3);
                    while($row3 = mysqli_fetch_assoc($total3)){
                    $output3 = $row3['total']-(($row3['total']*0.2)+($row3['total']*0.025));
                    $outputamal3 = ($row3['total']*0.025) ;
                    $outputmodal3 = ($row3['total']*0.2) ;
                    }
                    $saldo4 = "SELECT SUM(t.harga) as total FROM (SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT * FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE package = 'cetak foto'";
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
            $data = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION SELECT * FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') ORDER BY id DESC");
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
                    $saldo1 = "SELECT SUM(t.harga) as total FROM (SELECT harga,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,tipe_package FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE tipe_package = 'prawedding'";
                    //"SELECT package sum(harga) total FROM(SELECT package,harga FROM data_history_cobahampirfinishjuga union all select package,harga FROM data_history) group by totalan";
                    $total1 = mysqli_query($koneksi,$saldo1);
                    while($row1 = mysqli_fetch_assoc($total1)){
                    $output1 = $row1['total']-(($row1['total']*0.6)+($row1['total']*0.025));
                    $outputamal1 = ($row1['total']*0.025) ;
                    $outputmodal1 = ($row1['total']*0.6);
                    }
                    $saldo2 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,package FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE package IN ('self photo','studio')";
                    $total2 = mysqli_query($koneksi,$saldo2);
                    while($row2 = mysqli_fetch_assoc($total2)){
                    $output2 = $row2['total']-($row2['total']*0.025);
                    $outputamal2 = ($row2['total']*0.025) ;
                    $outputmodal2 = 0;
                    }
                    $saldo3 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT harga,package,tipe_package FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE tipe_package IN ('best deal','special package','diamond package','baby best deal','baby special package')";
                    $total3 = mysqli_query($koneksi,$saldo3);
                    while($row3 = mysqli_fetch_assoc($total3)){
                    $output3 = $row3['total']-(($row3['total']*0.2)+($row3['total']*0.025));
                    $outputamal3 = ($row3['total']*0.025) ;
                    $outputmodal3 = ($row3['total']*0.2) ;
                    }
                    $saldo4 = "SELECT SUM(t.harga) as total FROM (SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '') UNION ALL SELECT * FROM data_history WHERE tanggal LIKE '%2023-08%' AND (studio = 'Bekasi-Kabupaten' OR studio = '')) t WHERE package = 'cetak foto'";
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