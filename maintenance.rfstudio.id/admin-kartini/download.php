<?php
include "../function.php";
 $dir="../assets/img/data_cetakonly/";
    if(isset($_GET["id"])){
        $uniqid=$_GET['id'];
    $d = mysqli_query( $koneksi,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE id = $uniqid");
    $data = mysqli_fetch_array($d);

    $file_path = $dir.$data["bukti_transfer"];

    $ctype="application/octet-stream";
    if(!empty($file_path) && file_exists($file_path)){ /*check keberadaan file*/
        header("Pragma:public");
        header("Expired:0");
        header("Cache-Control:must-revalidate");
        header("Content-Control:public");
        header("Content-Description: File Transfer");
        header("Content-Type: $ctype");
        header("Content-Disposition:attachment; filename=\"".basename($file_path)."\"");
        header("Content-Transfer-Encoding:binary");
        header("Content-Length:".filesize($file_path));
        flush();
        readfile($file_path);
    exit();
    }else{
        echo "File tidak ditemukan.";
        
    }
    }
?>