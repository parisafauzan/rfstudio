<?php
 
session_start();
 if(!isset($_SESSION['adminkartini'])){
    header("location:adminlogin.php");
    exit;
}

?>