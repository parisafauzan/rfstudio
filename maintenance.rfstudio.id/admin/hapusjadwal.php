<?php
  require "../function.php";
  
  $uniqid = $_GET["uniqid"];

  mysqli_query($koneksi,"DELETE FROM jadwal_libur_kab WHERE uniqid = '$uniqid'");
  echo '<script language="javascript">';
    echo 'function pageRedirect() {
        window.location.replace("setting");
    }pageRedirect(); ';     
    echo '</script>';
?>