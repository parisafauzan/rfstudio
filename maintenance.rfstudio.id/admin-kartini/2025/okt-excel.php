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
            $saldo = "SELECT SUM(t.harga) as total FROM (SELECT harga,package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota' UNION ALL SELECT harga,package FROM data_history WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota') t";
            $total = mysqli_query($koneksi,$saldo);
            while($row = mysqli_fetch_assoc($total)){
            $output = $row['total'];
            }
            $data = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota' UNION SELECT * FROM data_history WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota' ORDER BY id DESC");
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
                    $saldo1 = "SELECT SUM(t.harga) as total FROM (SELECT harga,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota' UNION ALL SELECT harga,tipe_package FROM data_history WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota') t WHERE tipe_package = 'prawedding'";
                    //"SELECT package sum(harga) total FROM(SELECT package,harga FROM data_history_cobahampirfinishjuga union all select package,harga FROM data_history) group by totalan";
                    $total1 = mysqli_query($koneksi,$saldo1);
                    while($row1 = mysqli_fetch_assoc($total1)){
                    $output1 = $row1['total']-(($row1['total']*0.6)+($row1['total']*0.025));
                    $outputamal1 = ($row1['total']*0.025) ;
                    $outputmodal1 = ($row1['total']*0.6);
                    }
                    $saldo2 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota' UNION ALL SELECT harga,package FROM data_history WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota') t WHERE package IN ('self photo','studio')";
                    $total2 = mysqli_query($koneksi,$saldo2);
                    while($row2 = mysqli_fetch_assoc($total2)){
                    $output2 = $row2['total']-($row2['total']*0.025);
                    $outputamal2 = ($row2['total']*0.025) ;
                    $outputmodal2 = 0;
                    }
                    $saldo3 = "SELECT SUM(t.harga) as total FROM (SELECT harga,package,tipe_package FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota' UNION ALL SELECT harga,package,tipe_package FROM data_history WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota') t WHERE tipe_package IN ('best deal','special package','diamond package','baby best deal','baby special package')";
                    $total3 = mysqli_query($koneksi,$saldo3);
                    while($row3 = mysqli_fetch_assoc($total3)){
                    $output3 = $row3['total']-(($row3['total']*0.2)+($row3['total']*0.025));
                    $outputamal3 = ($row3['total']*0.025) ;
                    $outputmodal3 = ($row3['total']*0.2) ;
                    }
                    $saldo4 = "SELECT SUM(t.harga) as total FROM (SELECT * FROM data_history_cobahampirfinishjuga WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota' UNION ALL SELECT * FROM data_history WHERE tanggal LIKE '%2025-10%' AND studio = 'Bekasi-Kota') t WHERE package = 'cetak foto'";
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
    </body>
</html>