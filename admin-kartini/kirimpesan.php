<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

 //
include('../assets/phpmailer/Exception.php');
include('../assets/phpmailer/PHPMailer.php');
include('../assets/phpmailer/SMTP.php');

require '../function.php';

$id = $_GET["id"];

$dataemail = query("SELECT * FROM data_booking_cobahampirfinishjuga WHERE id = '$id'")[0];
$nama = $dataemail["nama"]; 
$email = $dataemail["email"];
$package = $dataemail["package"];
$katpakcage = $dataemail["tipe_package"];
$harga = $dataemail["harga"];
$tanggal = $dataemail["tanggal"];
$jam = $dataemail["jam"];


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$template = "../templates/konfirmasi.php";

    $mail->do_debug = 0;
    //Server settings
    $mail->SMTPDebug = false;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'ssl://smtp.gmail.com:465';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'rizalfotostudio2022@gmail.com';                     //SMTP username
    $mail->Password   = 'dpbgrqoftjhvbcev';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RF Studio');
    $mail->addAddress($email, $nama);     //Add a recipient


    // //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    // if(file_exists($template))
    //     $pesan = file_get_contents($template);
    // else
    //     die("gagal dapat templet");
    
    //Content
    // $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Photoshoot';
    $mail->Body    =  '<!DOCTYPE html>
<html lang="en">
    <head>
 <!-- Favicons -->
  <link href="../img/logo.png" rel="icon">
  <link href="../img/logo.png" rel="apple-touch-icon">
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- As a heading -->
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
    
    <!-- <link rel="stylesheet" href="../style.css"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com/css2">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    
    <style>
    body{
        font-family: "Josefin Sans", sans-serif;
    }
    .tronn{
       
        margin-top: auto;
        margin-bottom: auto;
    }
    .border{
        margin: auto;
    }
    .head{
        align-items: center;
        margin-top: 20px;
        margin-bottom: 20px;
        
    }
    .txthead{
        font-size: 20px;
        font-weight: bold;
    }
    .judul{
        font-size: 25px;
        font-weight:500;
        margin-bottom: 10px;
        text-align: center;
        font-family:"Josefin sans",sans-serif;
        
    }
    .isisatu{
        font-size: 16px;
        font-weight:800;
        margin-bottom: 10px;
        text-align: center;
    }
    .isidua{
        font-size: 16px;
        margin-bottom: 10px;
        text-align: center;
    }
    .row,.row-md{
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 20px;
    }
  </style>
    
  </head>
  
  
  <body>
   
    <div class="jumbotron tronn">
        <div class="container-fluid border" style="max-width:500px ;">
        <div class="row head">
            <div class="col">
            <p class="txthead text-center">Rizal Foto Studio</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="judul">Photoshoot</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Hai, '.$nama.'</p>
            </div>
        </div>
        <div class="row-md">
            <div class="col-md isisatu">
                <p>Jadwal Photoshoot</p>
            </div>
            <div class="col-md isidua">
                <p>Jangan lupa dengan tanggal bookingnya ya </p>
                <h5>'.$tanggal.' '.$jam.'</h5>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Package : '.$package.' '.$katpakcage.'</p>
            </div>
        </div>
        <div class="row">
            <p>*note :</p>
            <p>- Yang belum lunas pelunasan setelah selesai foto <p>
            <p>- Link google drive dikirim setelah pelunasan<p>
            <p>- Reschedule paling lambat h-3</p>
            <p>- Link Google drive berlaku hanya 2 minggu</p>
        </div>
        </div>
    </div>
  </body>
</html>';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $send = $mail->send();
if($send && kirimpsn($id)>0){
        
        echo '<script language="javascript">';
        echo 'alert("Pesan Terkirim");';
        echo 'function myFunction() {';
        echo 'document.getElementById("myForm").reset();};';
        echo 'window.location = "databooking.php"';
        echo '</script>';
}else{
        echo '<script language="javascript">';
        echo 'alert("Pesan Terkirim");';
        echo 'function myFunction() {';
        echo 'document.getElementById("myForm").reset();};';
        echo 'window.location = "databooking.php"';
        echo '</script>';
}
?>