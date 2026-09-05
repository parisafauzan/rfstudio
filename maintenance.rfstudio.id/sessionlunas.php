<?php
session_start();
if(!isset($_SESSION['uniqid'])){
        header("location:../verifikasilunas.php");
        exit;
    }
?>