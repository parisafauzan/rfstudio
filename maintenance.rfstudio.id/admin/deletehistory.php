<?php 
require '../function.php';

$id = $_GET["uniqid"];


if(deletehistory($id)>0){
     echo '<script language="javascript">';
        echo 'alert("Data Berhasil di Hapus");';
        echo 'function myFunction() {';
        echo 'document.getElementById("myForm").reset();};';
        echo 'window.location = "datahistory.php"';
        echo '</script>';
}else{
     echo '<script language="javascript">';
        echo 'alert("Data Berhasil di Hapus");';
        echo 'function myFunction() {';
        echo 'document.getElementById("myForm").reset();};';
        echo 'window.location = "datahistory.php"';
        echo '</script>';
}
?>