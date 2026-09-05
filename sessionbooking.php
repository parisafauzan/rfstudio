<?php
 
session_start();
 if(!isset($_SESSION['buktitransfer'])){
    header("location:index.php");
    exit;
}

?>