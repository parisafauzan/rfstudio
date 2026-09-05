<?php
  require "../function.php";
  
  $tanggallibur = $_POST["tanggallibur"];
  $catatanlibur = $_POST["catatanlibur"];
  $random = random_bytes(3); 
  $uniqid = (bin2hex($random));

  mysqli_query($koneksi,"INSERT INTO jadwal_libur_kab VALUES ('','$uniqid','$tanggallibur','$catatanlibur')");
  echo '<script language="javascript">';
    echo 'function pageRedirect() {
        window.location.replace("setting");
    }pageRedirect(); ';     
    echo '</script>';
?>