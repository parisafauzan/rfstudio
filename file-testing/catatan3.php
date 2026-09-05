<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$koneksi = mysqli_connect("localhost","root","","rizalfotostudio");

function query($query){
    global $koneksi;
    $hasil = mysqli_query($koneksi,$query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($hasil)){
        $rows [] = $row;
    } 
    return $rows;
}
// ===============================
function tambah($data){
    
    global $koneksi;
//ambil data dari tiap elemen dalam form
$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
$package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
$tanggal =htmlspecialchars ($data["tanggal"]);
$jam =htmlspecialchars ($data["jam"]);
$bukti_lunas =htmlspecialchars ($data["buktilunas"]);
$jumlah =htmlspecialchars ($data["jumlah"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);
$ketbayar =htmlspecialchars ($data["ketbayar"]);
$catatan = htmlspecialchars ($data["catatan"]);
$cetakharga1 =htmlspecialchars ($data["nambah5R"]);
$cetakharga2 =htmlspecialchars ($data["nambah5Rframe"]);
$cetakharga3 =htmlspecialchars ($data["nambah10RS"]);
$cetakharga4 =htmlspecialchars ($data["nambah10RSframe"]);
$cetakharga5 =htmlspecialchars ($data["nambah30x40frame"]);
$cetakharga6 =htmlspecialchars ($data["nambah40x60frame"]);
$cetakharga7 =htmlspecialchars ($data["nambah60x90frame"]);
$cetakharga8 =htmlspecialchars ($data["nambah60x100frame"]);
$cetakharga9 =htmlspecialchars ($data["nambah70x100frame"]);
$nambahanak =htmlspecialchars ($data["nambahanak"]);
$nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
$nambahorang =htmlspecialchars ($data["nambahorang"]);
$nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);
$namacetak1 =htmlspecialchars ($data["cetakharga1"]);
$namacetak2 =htmlspecialchars ($data["cetakharga2"]);
$namacetak3 =htmlspecialchars ($data["cetakharga3"]);
$namacetak4 =htmlspecialchars ($data["cetakharga4"]);
$namacetak5 =htmlspecialchars ($data["cetakharga5"]);
$namacetak6 =htmlspecialchars ($data["cetakharga6"]);
$namacetak7 =htmlspecialchars ($data["cetakharga7"]);
$namacetak8 =htmlspecialchars ($data["cetakharga8"]);
$namacetak9 =htmlspecialchars ($data["cetakharga9"]);

///////
date_default_timezone_set("Asia/Jakarta");
$waktuinput= date("d-m-Y H:i"); 
/////// tabel tracking transaksi
$random = random_bytes(3);
$uniqid = (bin2hex($random));

$bayardp = "Terimakasih sudah melakukan pembayaran dp sebesar Rp. 200.000";

$bayarself = "Terimakasih sudah mengisi format booking, data anda telah tersimpan. Untuk memastikan silahkan hubungi admin via Whatsapp";

//
///////
$tambah1 = htmlspecialchars ($data["tambah1"]);
$gambar1 = htmlspecialchars ($data["gambar1"]);
$gambar2 = htmlspecialchars ($data["gambar2"]);
$gambar3 = htmlspecialchars ($data["gambar3"]);
$gambar4 = htmlspecialchars ($data["gambar4"]);
$gambar5 = htmlspecialchars ($data["gambar5"]);

/////// tabel tracking transaksi

// $hargaanak = $nambahanak*35000;
// $hargadewasa = $nambahdewasa*50000;



$jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$tanggal' AND jam = '$jam'");
$jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$tanggal' AND jam = '$jam'");
if (mysqli_fetch_assoc($jamjadwal)){
      
    echo '<script language="javascript">';
    echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
    echo 'history.go(-1);';
    echo '</script>';
    return false;
}else if(mysqli_fetch_assoc($jamjadwal1)){
    echo '<script language="javascript">';
    echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
    echo 'history.go(-1);';
    echo '</script>';
    return false;
}
//upload gambar
$bukti_transfer = uploadDpTf();
if(!$bukti_transfer){
    return false;
}
$query = "INSERT INTO data_konfirmasi
                VALUES
                ('','$nama','$notelp',
                '$email','$package',
                '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 10000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 20000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000),
                ('$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 10000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 20000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000))-200000,
                '$tanggal',
                '$jam','$bukti_transfer',
                '$bukti_lunas','$jumlah','$cetakfoto',
                '$nambahanak','$nambahdewasa',
                '$nambahorang',
                 '$nambahwaktu' * 5,
                 'test', 
                 $waktuinput,'$uniqid','$catatan')";
mysqli_query($koneksi, $query);
//urutan query sql = id,nama,notelp,email,package,harga(total),hargasetelahdp(total-dp),tanggal,jam,buktitf,buktilunas,jumlah(jenispaket),cetakfoto,nambahanak,nambahdewasa,nambahorang,nambahwaktu,tambahcetak,waktutransaksi(suksess isi data)
// if($package == 'self photo'){
//      $query = "INSERT INTO data_konfirmasi
//                 VALUES
//                 ('','$nama','$notelp',
//                 '$email','$package',
//                 '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
//                 + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
//                 + ($cetakharga1 * 10000) + ($cetakharga2 * 30000)
//                 + ($cetakharga3 * 20000) + ($cetakharga4 * 70000)
//                 + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
//                 + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
//                 + ($cetakharga9 * 700000),
//                 0 ,
//                 '$tanggal',
//                 '$jam','$bukti_transfer',
//                 '$bukti_lunas','$jumlah','$cetakfoto',
//                 '$nambahanak','$nambahdewasa',
//                 '$nambahorang',
//                  '$nambahwaktu' * 5,
//                  '$namacetak1', 
//                  $waktuinput,'$uniqid','$catatan' )";
                
// mysqli_query($koneksi, $query);
// }else{
//     $query = "INSERT INTO data_konfirmasi
//                 VALUES
//                 ('','$nama','$notelp',
//                 '$email','$package',
//                 '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
//                 + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
//                 + ($cetakharga1 * 10000) + ($cetakharga2 * 30000)
//                 + ($cetakharga3 * 20000) + ($cetakharga4 * 70000)
//                 + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
//                 + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
//                 + ($cetakharga9 * 700000),
//                 ('$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
//                 + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
//                 + ($cetakharga1 * 10000) + ($cetakharga2 * 30000)
//                 + ($cetakharga3 * 20000) + ($cetakharga4 * 70000)
//                 + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
//                 + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
//                 + ($cetakharga9 * 700000))-200000,
//                 '$tanggal',
//                 '$jam','$bukti_transfer',
//                 '$bukti_lunas','$jumlah','$cetakfoto',
//                 '$nambahanak','$nambahdewasa',
//                 '$nambahorang',
//                  '$nambahwaktu' * 5,
//                  '$namacetak1', 
//                  $waktuinput,'$uniqid','$catatan')";
// mysqli_query($koneksi, $query);
// }


// $harganambah = "UPDATE data_konfirmasi_cobahampirfinishjuga SET anak = $hargaanak , dewasa = $hargadewasa WHERE harga = $harga + $hargaanak + $hargadewasa";
// mysqli_query($koneksi, $harganambah);

 // transaksi
// $input = "INSERT INTO data_tracking_coba 
//         VALUES('','$uniqid','$nama','$email','$tambah1','','','','','','','','',CURRENT_TIMESTAMP,'','','','','$gambar1','$gambar2','$gambar3','$gambar4','$gambar5','','')";
// mysqli_query($koneksi,$input);

// transaksi

include('assets/phpmailer/Exception.php');
include('assets/phpmailer/PHPMailer.php');
include('assets/phpmailer/SMTP.php');


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


    $mail->do_debug = 0;
    //Server settings
    $mail->SMTPDebug = false;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'ssl://smtp.gmail.com:465';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'rizalfotostudio2022@gmail.com';                     //SMTP username
    $mail->Password   = 'uwzokysprljpdnql';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RFStudio');
  //  //$mail->addAddress('rfstudio2022@gmail.com', 'Data Arsip');
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
    $mail->Subject = 'Berhasil Mengisi Data';
    $mail->Body    =  '<!DOCTYPE html>
    <html lang="en">
    <head>
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- As a heading -->
    <title>Login Admin</title>
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
        font-size: 30px;
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
        font-size: 14px;
        margin-bottom: 10px;
        text-align: center;
    }
    .row,.row-md{
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 10px;
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
                <p class="judul">Data yang masuk</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Nama : '.$nama.' </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>No.Telp : '.$notelp.' </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Email : '.$email.' </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Tanggal : '.$tanggal.' </p>
                <p>Jam : '.$jam.' </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Kode Track Progress : <b>'.$uniqid.'</b></p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Package : '.$package.' <b>'.$jumlah.'</b></p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Status : <b>'.$ketbayar.'</b></p>
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
   $mail->send();

return mysqli_affected_rows($koneksi);
}
// ===============================
// function tambahbsc($data){
    
//     global $koneksi;
// //ambil data dari tiap elemen dalam form
// $nama = htmlspecialchars($data["nama"]);
// $notelp = htmlspecialchars($data["notelp"]);
// $email = htmlspecialchars($data["email"]);
// $package = htmlspecialchars($data["package"]);
// $harga = htmlspecialchars($data["harga"]);
// $tanggal =htmlspecialchars ($data["tanggal"]);
// $jam =htmlspecialchars ($data["jam"]);
// $bukti_lunas =htmlspecialchars ($data["buktilunas"]);
// $jumlah =htmlspecialchars ($data["jumlah"]);
// $cetakfoto =htmlspecialchars ($data["cetakfoto"]);
// $nambahanak =htmlspecialchars ($data["nambahanak"]);
// $nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
// $nambahorang =htmlspecialchars ($data["nambahorang"]);
// $nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);
// // $hargaanak = $nambahanak*35000;
// // $hargadewasa = $nambahdewasa*50000;


// //upload gambar
// $bukti_transfer = uploadDpTf();
// if(!$bukti_transfer){
//     return false;
// }
// $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga, data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$tanggal' AND jam = '$jam'");
// if (mysqli_fetch_assoc($jamjadwal)){
//      echo '<script language="javascript">';
//     echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
//     echo 'history.go(-1);';
//     echo '</script>';
//         return false;
// }


// $query = "INSERT INTO data_booking_cobahampirfinishjuga
//                 VALUES
//                 ('','$nama','$notelp','$email','$package','$harga' + ($nambahorang * 15000) + ($nambahwaktu * 20000),'$tanggal','$jam','$bukti_transfer','$bukti_lunas','$jumlah',$nambahanak,$nambahdewasa, $nambahorang,$nambahwaktu * 5, CURRENT_TIMESTAMP )";
// mysqli_query($koneksi, $query);

// // $harganambah = "UPDATE data_konfirmasi_cobahampirfinishjuga SET anak = $hargaanak , dewasa = $hargadewasa WHERE harga = $harga + $hargaanak + $hargadewasa";
// // mysqli_query($koneksi, $harganambah);

// return mysqli_affected_rows($koneksi);
// }

// ===============================
function edit($data){

    global $koneksi;
//ambil data dari tiap elemen dalam form
$id = $data["id"];

$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
// $package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
$tanggal =htmlspecialchars ($data["tanggal"]);
$jam =htmlspecialchars ($data["jam"]);
// $bukti_lunas =htmlspecialchars ($data["buktilunas"]);
// $jumlah =htmlspecialchars ($data["jumlah"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);
$arraycetak = ($data["cetakfoto"]);
$tambahcetak = implode(",", $arraycetak);
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
// $nambahanak =htmlspecialchars ($data["nambahanak"]);
// $nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
// $nambahorang =htmlspecialchars ($data["nambahorang"]);
// $nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);

//upload gambar
// $bukti_lunas = uploadLunasTf();
// if(!$bukti_lunas){
//     return false;
// }

$hargasebelumnya = $harga;
$hargaupgrade = htmlspecialchars ($data["hargaup"]);
$hargatambahctk = $cetakharga1 + $cetakharga2
                + $cetakharga3 + $cetakharga4
                + $cetakharga5 + $cetakharga6
                + $cetakharga7 + $cetakharga8
                + $cetakharga9;
$hargareal = $hargasebelumnya + $hargaupgrade + $hargatambahctk;


    $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
    nama = '$nama' ,
    no_telp = '$notelp', 
    email = '$email', 
    tanggal = '$tanggal', 
    jam = '$jam', 
    harga = '$hargareal',
    cetak_foto = '$cetakfoto',
    tambah_cetak = '$tambahcetak'
      WHERE id = '$id'";

mysqli_query($koneksi, $query);


return mysqli_affected_rows($koneksi);


}
function editbooking($data){

    global $koneksi;
//ambil data dari tiap elemen dalam form
$id = $data["id"];

$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
// $package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
$tanggal =htmlspecialchars ($data["tanggal"]);
$jam =htmlspecialchars ($data["jam"]);
// $bukti_lunas =htmlspecialchars ($data["buktilunas"]);
// $jumlah =htmlspecialchars ($data["jumlah"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);
$arraycetak = ($data["cetakfoto"]);
$tambahcetak = implode(",", $arraycetak);
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
// $nambahanak =htmlspecialchars ($data["nambahanak"]);
// $nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
// $nambahorang =htmlspecialchars ($data["nambahorang"]);
// $nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);

//upload gambar
// $bukti_lunas = uploadLunasTf();
// if(!$bukti_lunas){
//     return false;
// }

$hargasebelumnya = $harga;
$hargaupgrade = htmlspecialchars ($data["hargaup"]);
$hargatambahctk = $cetakharga1 + $cetakharga2
                + $cetakharga3 + $cetakharga4
                + $cetakharga5 + $cetakharga6
                + $cetakharga7 + $cetakharga8
                + $cetakharga9;
$hargareal = $hargasebelumnya + $hargaupgrade + $hargatambahctk;


    $query = "UPDATE data_booking_cobahampirfinishjuga SET 
    nama = '$nama' , 
    no_telp = '$notelp', 
    email = '$email', 
    tanggal = '$tanggal', 
    jam = '$jam', 
    harga = '$hargareal',
    cetak_foto = '$cetakfoto',
    tambah_cetak = '$tambahcetak'
      WHERE id = '$id'";

mysqli_query($koneksi, $query);


return mysqli_affected_rows($koneksi);


}
// ===============================
function kirimpsn($data){
    global $koneksi;

}
// ===============================

// ===============================
function update($data){

    global $koneksi;
//ambil data dari tiap elemen dalam form
$id = $data["id"];

$nama = htmlspecialchars($data["nama"]);
// $notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
// $package = htmlspecialchars($data["package"]);
// $harga = htmlspecialchars($data["harga"]);
// $tanggal =htmlspecialchars ($data["tanggal"]);
// $jam =htmlspecialchars ($data["jam"]);
// $bukti_transfer =htmlspecialchars ($data["buktitransfer"]);
// $jumlah =htmlspecialchars ($data["jumlah"]);

//upload gambar
$bukti_lunas = uploadLunasTf();
if(!$bukti_lunas){
    return false;
}
    $tambah4 = "upload bukti pelunasan telah terkirim menunggu konfirmasi dari admin";
    $gambar4 = "correct.png";
    $judul3 = "Pelunasan";
    
    $pindahdata = mysqli_query($koneksi,"UPDATE data_pelunasan_cobahampirfinishjuga SET bukti_lunas ='$bukti_lunas' WHERE id = $id ");
    $pindahdata .= mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu4 = CURRENT_TIMESTAMP,tambah4 = '$tambah4',gambar4 ='$gambar4',judul3 = '$judul3'  WHERE id = '$id'");
   
include('assets/phpmailer/Exception.php');
include('assets/phpmailer/PHPMailer.php');
include('assets/phpmailer/SMTP.php');


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


    $mail->do_debug = 0;
    //Server settings
    $mail->SMTPDebug = false;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'ssl://smtp.gmail.com:465';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'rizalfotostudio2022@gmail.com';                     //SMTP username
    $mail->Password   = 'uwzokysprljpdnql';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RFStudio');
    //$mail->addAddress('rfstudio2022@gmail.com', 'Data Arsip');
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
    $mail->Subject = 'Upload Bukti Pelunasan';
    $mail->Body    =  '<!DOCTYPE html>
<html lang="en">
    <head>
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- As a heading -->
    <title>Login Admin</title>
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
        font-size: 30px;
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
        font-size: 14px;
        margin-bottom: 10px;
        text-align: center;
    }
    .colbtn{
        
        margin: 10px;
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
                <p class="judul">Pelunasan</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Hai, '.$nama.'</p>
            </div>
        </div>
        <div class="row-md">
            <div class="col-md isisatu">
                <p>Bukti Lunas Telah Terkirim</p>
            </div>
            <div class="col-md isidua">
                <p>Tunggu sebentar ya, pelunasan anda sedang di cek admin<br>
                <h4>Terimakasih.</h4></p>
            </div>
        </div>
        <div class="row">
            <p>*note :</p>
            <p>- Link google drive dikirim setelah pelunasan<p>
            <p>- Link Google drive berlaku hanya 2 minggu</p>
            
            </div>
        </div>
    </div>
  </body>
</html>';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   $mail->send(); 


mysqli_multi_query($koneksi, $pindahdata);


return mysqli_affected_rows($koneksi);


}
// ===============================
function konfirmasi($id){
    global $koneksi;

    $tambah2 = "data telah terkonfirmasi oleh admin, menunggu sesi photoshoot sesuai jadwal anda";
    $gambar2 = "correct.png";
    $judul1 = "Terkonfirmasi";

    $pindahdata = mysqli_query($koneksi, "INSERT INTO data_booking_cobahampirfinishjuga SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE id = '$id'");
    $pindahdata .= mysqli_query($koneksi,"DELETE FROM data_konfirmasi_cobahampirfinishjuga WHERE id = '$id'");
    $pindahdata .= mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu2 = CURRENT_TIMESTAMP ,tambah2 = '$tambah2',gambar2 ='$gambar2',judul1 = '$judul1'  WHERE id = '$id'");
    if ( mysqli_multi_query($koneksi, $pindahdata) ){

    echo 'Data Baru telah ditambahkan';

    }
    
    return mysqli_affected_rows($koneksi);
}

// ===============================
function booking($id){
    global $koneksi;
    $tambah3 = "sesi photoshoot selesai, segera melakukan pelunasan *(package self photo tidak perlu melakukan pelunasan)";
    $gambar3 = "correct.png";
    $judul2 = "Photoshoot";
    $tambah4 = "upload bukti pelunasan telah terkirim menunggu konfirmasi dari admin";
    $gambar4 = "correct.png";
    $judul3 = "Pelunasan";
    
    //
    // $tambah4 = "upload bukti pelunasan telah terkirim menunggu konfirmasi dari admin";
    // $gambar4 = "correct.png";
    // $judul3 = "Pelunasan";
    // //
    // $tambah5 = "pelunasan terkonfirmasi, transaksi anda telah selesai";
    // $gambar5 = "correct.png";
    // $judul4 = "Pelunasan Terkonfirmasi";

    $selfstudio = mysqli_query($koneksi,"SELECT * FROM data_booking_cobahampirfinishjuga WHERE package = 'self photo'");
    //////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////
    if (mysqli_fetch_array($selfstudio)){
    
        $pindahdata = mysqli_query($koneksi, "INSERT INTO data_pelunasan_cobahampirfinishjuga SELECT * FROM data_booking_cobahampirfinishjuga WHERE id = '$id'");
        $pindahdata .= mysqli_query($koneksi,"DELETE FROM data_booking_cobahampirfinishjuga WHERE id = '$id'");
        $pindahdata .= mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu3 = CURRENT_TIMESTAMP, tambah3 = '$tambah3',gambar3 ='$gambar3',judul2 = '$judul2'  WHERE id = '$id'");
        $pindahdata .= mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu4 = CURRENT_TIMESTAMP, tambah4 = '$tambah4',gambar4 ='$gambar4',judul3 = '$judul3'  WHERE id = '$id'");
        
        // $pindahdata .= mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu5 = CURRENT_TIMESTAMP, tambah5 = '$tambah5',gambar5 ='$gambar5',judul4 = '$judul4'  WHERE id = '$id'");


        if ( mysqli_multi_query($koneksi, $pindahdata) )
        {
            //echo 'Data Baru telah ditambahkan';
        }

    }else{
        $pindahdata = mysqli_query($koneksi, "INSERT INTO data_pelunasan_cobahampirfinishjuga SELECT * FROM data_booking_cobahampirfinishjuga WHERE id = '$id'");
        $pindahdata .= mysqli_query($koneksi,"DELETE FROM data_booking_cobahampirfinishjuga WHERE id = '$id'");
        $pindahdata .= mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu3 = CURRENT_TIMESTAMP, tambah3 = '$tambah3',gambar3 ='$gambar3',judul2 = '$judul2'  WHERE id = '$id'");
    
        if ( mysqli_multi_query($koneksi, $pindahdata) )
        {
        // echo 'Data Baru telah ditambahkan';
        }
    }
    //////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////
    
    
    return mysqli_affected_rows($koneksi);
}
// ===============================
function editlunas($data){
    global $koneksi;
    
    $id = $data["id"];
    $link = htmlspecialchars($data["link"]);

    $pindahdata = "UPDATE data_tracking_coba SET link = '$link' WHERE id = '$id'";
   
    if ( mysqli_query($koneksi, $pindahdata) ){

    echo 'Data Baru telah ditambahkan';

    }
    
    return mysqli_affected_rows($koneksi);
}
// ===============================
function delete($id){
    global $koneksi;
    
    $sql_img = mysqli_query($koneksi,"SELECT bukti_transfer FROM data_konfirmasi_cobahampirfinishjuga WHERE id = '$id'");
    //$rsPic = mysqli_query($koneksi,$sql_img);
    $rowPic = mysqli_fetch_assoc($sql_img);
    $bukti = $rowPic['bukti_transfer'];
    unlink (__DIR__."/assets/img/data_konfirmasi/".$bukti);
 

    $pindahdata = mysqli_query($koneksi,"DELETE FROM data_konfirmasi_cobahampirfinishjuga WHERE id = '$id'");
    $pindahdata .= mysqli_query($koneksi,"DELETE FROM data_tracking_coba WHERE id = '$id'");
    
    $result = mysqli_multi_query($koneksi, $pindahdata);

    if ( $result ){
     
    echo 'Data telah dihapus'; 

    }
    
    return mysqli_affected_rows($koneksi);
}
function deletebooking($id){
    global $koneksi;
    
    $sql_img = mysqli_query($koneksi,"SELECT bukti_transfer FROM data_booking_cobahampirfinishjuga WHERE id = '$id'");
    //$rsPic = mysqli_query($koneksi,$sql_img);
    $rowPic = mysqli_fetch_assoc($sql_img);
    $bukti = $rowPic['bukti_transfer'];
    unlink (__DIR__."/assets/img/data_konfirmasi/".$bukti);
 

    $pindahdata = mysqli_query($koneksi,"DELETE FROM data_booking_cobahampirfinishjuga WHERE id = '$id'");
    $pindahdata .= mysqli_query($koneksi,"DELETE FROM data_tracking_coba WHERE id = '$id'");
    
    $result = mysqli_multi_query($koneksi, $pindahdata);

    if ( $result ){
     
    echo 'Data telah dihapus'; 

    }
    
    return mysqli_affected_rows($koneksi);
}

function lunas($id){
    global $koneksi;
    
    $tambah5 ="bukti pelunasan anda telah terkonfirmasi oleh admin";
    $gambar5 ="correct.png";
    $judul4 = "Pelunasan Terkonfirmasi";

    $pindahdata = mysqli_query($koneksi, "INSERT INTO data_history SELECT * FROM data_pelunasan_cobahampirfinishjuga WHERE id = '$id'");
    $pindahdata .= mysqli_query($koneksi,"DELETE FROM data_pelunasan_cobahampirfinishjuga WHERE id = '$id'");
    $pindahdata .= mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu5 = CURRENT_TIMESTAMP,tambah5 = '$tambah5',gambar5 ='$gambar5',judul4 = '$judul4'  WHERE id = '$id'");
   
    if ( mysqli_multi_query($koneksi, $pindahdata) ){

    echo 'Data Baru telah ditambahkan';

    }
    


    return mysqli_affected_rows($koneksi);
}

// ===============================
function uploadDpTf(){
    $namaFile = $_FILES['buktitransfer']['name'];
    $ukuranFile = $_FILES['buktitransfer']['size'];
    $error = $_FILES['buktitransfer']['error'];
    $tmpName = $_FILES['buktitransfer']['tmp_name'];

//cek apakah ada notifikasi kalo tidak diisi?
    if($error===4){
        echo"<script>
        alert('pilih gambar terlebih dahulu');
        </script>
        ";
        return false;
    }

    //cek apakah yang diupload gambar
    $extensigambarValid = ['jpg','jpeg','png'];
    $extensigambar = explode('.',$namaFile);
    $extensigambar = strtolower(end($extensigambar));

    if(!in_array($extensigambar,$extensigambarValid)){
        echo "<script>
        alert('yang anda upload bukan gambar!');
        </script>
        ";
        return false;
    }

    if($ukuranFile > 5000000){
        echo "<script>
        alert('gambar terlalu besar!');
        </script>
        ";
        return false;
    }
    //gambar siap diupload
    //bikin namafile baru
    $namaFileBaru = 'dptf-';
    $namaFileBaru .= uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $extensigambar;
    
    if (move_uploaded_file($tmpName, __DIR__.'../assets/img/data_konfirmasi/'.$namaFileBaru)){
       // echo "upload";
    }else{
      //  echo " gagal";
    }

    return $namaFileBaru;

}
// ===============================
function uploadLunasTf(){
    $namaFile = $_FILES['buktilunas']['name'];
    $ukuranFile = $_FILES['buktilunas']['size'];
    $error = $_FILES['buktilunas']['error'];
    $tmpName = $_FILES['buktilunas']['tmp_name'];

//cek apakah ada notifikasi kalo tidak diisi?
    if($error===4){
        echo"<script>
        alert('pilih gambar terlebih dahulu');
        </script>
        ";
        return false;
    }

    //cek apakah yang diupload gambar
    $extensigambarValid = ['jpg','jpeg','png'];
    $extensigambar = explode('.',$namaFile);
    $extensigambar = strtolower(end($extensigambar));

    if(!in_array($extensigambar,$extensigambarValid)){
        echo "<script>
        alert('yang anda upload bukan gambar!');
        </script>
        ";
        return false;
    }

    if($ukuranFile > 5000000){
        echo "<script>
        alert('gambar terlalu besar!');
        </script>
        ";
        return false;
    }
    //gambar siap diupload
    //bikin namafile baru
    $namaFileBaru = 'lunastf-';
    $namaFileBaru .= uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $extensigambar;
    
    if (move_uploaded_file($tmpName, __DIR__.'../assets/img/data_pelunasan/'.$namaFileBaru)){
       // echo "upload";
    }else{
       // echo " gagal";
    }

    return $namaFileBaru;

}

?>