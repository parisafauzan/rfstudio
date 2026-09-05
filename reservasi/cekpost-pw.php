<?php
include "../function.php";

$ambiljam = $_POST['jampost'];
$ambiltanggal = $_POST['tanggalpost'];
$uniqid = $_POST['uniqid'];
$ambilstudio = $_POST['studio'];

// var_dump($_POST);
mysqli_query($koneksi,"INSERT INTO data_jamtanggal VALUES('','$uniqid','$ambiltanggal','$ambiljam','$ambilstudio') ");
    echo '<script language="javascript">';
    echo 'function pageRedirect() {
        window.location.replace("bookingpw.php?uniqid='.$uniqid.'");
    }pageRedirect(); ';     
    echo '</script>';
//  if(($_POST)>0){

    
//     //echo '<meta http-equiv="refresh" content="3;url=../index.php">';
//     }else{

//         echo '<script language="javascript">';
//         echo 'window.location = history.go(-1);';
//         echo '</script>';
//     }exit;
    
?>