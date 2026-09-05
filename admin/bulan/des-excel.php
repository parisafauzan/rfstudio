<?php
require "../../function.php";
// if (isset($_POST["export"])){
    
    header('Content-Type: application/vnd-ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename= data.xls');
    // $output = fopen("php://output","w");
    // fputcsv($output,array('nama','no_telp','email','package','harga','tanggal','jam','waktutransaksi','uniqid'));
    // $query = "SELECT nama,no_telp,email,package,harga,tanggal,jam,time_stamp,uniqid FROM data_history_cobahampirfinishjuga ORDER BY id DESC";
    // $result = mysqli_query($koneksi,$query);
    // while ($row = mysqli_fetch_assoc($result)){
    //     fputcsv($output,$row);

    // }
    // fclose($output);
// }
?>
<!DOCTYPE html>
<html>
    <head>
 <!-- Favicons -->
  <link href="../img/logo.png" rel="icon">
  <link href="../img/logo.png" rel="apple-touch-icon">
        <title>Export Data Ke Excel</title>
    </head>
    <body>
        <table class="table table-bordered table-hover mx-auto" style=" max-width: 1000px;">
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
          $saldo = "SELECT SUM(t.harga) as total FROM (SELECT harga,tanggal,tipe_package FROM data_history_cobahampirfinishjuga UNION ALL SELECT harga,tanggal,tipe_package FROM data_history) t WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (12)";
          $total = mysqli_query($koneksi,$saldo);
          while($row = mysqli_fetch_assoc($total)){
          $output = $row['total'];
          }
         
					$batas = 50;
					$halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
					$halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
					//
					$previous = $halaman - 1;
					$next = $halaman + 1;

					$data = mysqli_query($koneksi, "SELECT * FROM data_history WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (12) ORDER BY tanggal DESC");
					$jumlah_data = mysqli_num_rows($data);
					$total_halaman = ceil($jumlah_data / $batas);

					$data_konfirmasi = mysqli_query($koneksi,"SELECT * FROM data_history WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (12) ORDER BY tanggal DESC LIMIT $halaman_awal, $batas");
					$nomor = $halaman_awal+1;
          $i=1;
          
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
                  $saldo1 = "SELECT SUM(t.harga) as total FROM (SELECT harga,tanggal,tipe_package FROM data_history_cobahampirfinishjuga UNION ALL SELECT harga,tanggal,tipe_package FROM data_history) t WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (12) AND tipe_package = 'prawedding'";
                  //"SELECT package sum(harga) total FROM(SELECT package,harga FROM data_history_cobahampirfinishjuga union all select package,harga FROM data_history) group by totalan";
                  $total1 = mysqli_query($koneksi,$saldo1);
                  while($row1 = mysqli_fetch_assoc($total1)){
                  $output1 = $row1['total']-(($row1['total']*0.6)+($row1['total']*0.025));
                  $outputamal1 = ($row1['total']*0.025) ;
                  $outputmodal1 = ($row1['total']*0.6);
                  }
                  $saldo2 = "SELECT SUM(t.harga) as total FROM (SELECT harga,tanggal,tipe_package ,package FROM data_history_cobahampirfinishjuga UNION ALL SELECT harga,tanggal,tipe_package,package FROM data_history) t WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (12) AND package IN ('self photo','studio')";
                  $total2 = mysqli_query($koneksi,$saldo2);
                  while($row2 = mysqli_fetch_assoc($total2)){
                  $output2 = $row2['total']-($row2['total']*0.025);
                  $outputamal2 = ($row2['total']*0.025) ;
                  $outputmodal2 = 0;
                  }
                  $saldo3 = "SELECT SUM(t.harga) as total FROM (SELECT harga,tanggal,tipe_package ,package FROM data_history_cobahampirfinishjuga UNION ALL SELECT harga,tanggal,tipe_package,package FROM data_history) t  WHERE YEAR(tanggal) = YEAR(NOW()) AND MONTH(tanggal) IN (12) AND tipe_package IN ('best deal','special package','diamond package','baby best deal','baby special package')";
                  $total3 = mysqli_query($koneksi,$saldo3);
                  while($row3 = mysqli_fetch_assoc($total3)){
                  $output3 = $row3['total']-(($row3['total']*0.2)+($row3['total']*0.025));
                  $outputamal3 = ($row3['total']*0.025) ;
                  $outputmodal3 = ($row3['total']*0.2) ;
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
    </body>
</html>