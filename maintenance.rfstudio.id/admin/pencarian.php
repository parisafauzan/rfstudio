<?php
$keyword = $_GET["keyword"];
require "../function.php";
$query = "SELECT * FROM data_history_cobahampirfinishjuga 
    WHERE 
    nama LIKE '%$keyword%' OR
    no_telp LIKE '%$keyword%' OR
    email LIKE '%$keyword%' OR
    harga LIKE '%$keyword%' OR
    time_stamp LIKE '%$keyword%'";
    $sql = query($query);
    

?>
    <table class="table table-bordered table-hover mx-auto"  style=" max-width: 1000px;">
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
        // $batas = 20;
        // $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
        // $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
        // //
        // $previous = $halaman - 1;
        // $next = $halaman + 1;

        // $data = mysqli_query($koneksi, "SELECT * FROM data_history_cobahampirfinishjuga  ORDER BY id DESC");
        // $jumlah_data = mysqli_num_rows($data);
        // $total_halaman = ceil($jumlah_data / $batas);

        // $data_konfirmasi = mysqli_query($koneksi,"SELECT * FROM data_history_cobahampirfinishjuga  ORDER BY id DESC LIMIT $halaman_awal, $batas");
        // $nomor = $halaman_awal+1;
        // $i=1;
            while($d = $sql){


                ?>
            <tr>
    <td class="text-start"><?php echo $i;  ?></td>
    <td ><?php echo $d["time_stamp"];  ?></td>
    <td class="text-start"><?php echo $d["nama"];  ?></td>
    <td class="text-start"><?php echo $d["no_telp"];  ?></td>
    <td class="text-start"><?php echo $d["email"];  ?></td>
    <td class="text-start"><?php echo $d["harga"]; ?></td>
    <td class="text-start"><?php echo $d["harga"]*(0.025);  ?></td>
    <td class="text-start"><?php 
        
        $goldpwd = $d["harga"]*(0.6);
        $selfp = $d["harga"]*0;
        $other = $d["harga"]*0.2;

        if ($d["package"] == 'goldprawedd' && $d["package"] == 'silverprawedd')
        {
        echo $goldpwd;
        }else if($d["package"] == 'self photo')
        {
        echo $selfp;
        }else 
        {
        echo $other;
        }  ?></td>
        <td class="text-start fw-bold"><?php

        if ($d["package"] == 'goldprawedd' && $d["package"] == 'silverprawedd')
        {
            echo  $d["harga"]-(($d["harga"]*0.6)+($d["harga"]*0.025));
        }else if($d["package"] == 'self photo')
        {
            echo  $d["harga"]-(($d["harga"]*0)+($d["harga"]*0.025));
        }else 
        {
            echo  $d["harga"]-(($d["harga"]*0.2)+($d["harga"]*0.025));
        } ;  ?></td>
    </tr>
    <?php $i++;?>
    <?php }//endforeach;?>
        </tbody>
    </table>