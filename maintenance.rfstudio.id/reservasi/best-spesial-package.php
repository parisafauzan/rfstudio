<?php
include "../function.php";

    if(tambah($_POST)>0){
        echo '<script language="javascript">';
    echo 'function alert() {';
    echo 'document.getElementById("myForm").reset()};';
    echo 'window.location = "berhasilbooking.php";';
    echo '</script>';
    //echo '<meta http-equiv="refresh" content="3;url=../index.php">';
    }else{

        echo '<script language="javascript">';
        echo 'window.location = "bookingbs.php";';
        echo '</script>';
    }exit;
    
?>