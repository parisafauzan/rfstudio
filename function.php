<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// $koneksi = mysqli_connect("localhost","rfstu853_rfsadmin","Rfstudio2022","rfstu853_rfstudio");
$koneksi = mysqli_connect("localhost","root","","rfstudio");

// Samakan perilaku error mysqli dengan produksi (PHP <= 8.0) agar tidak fatal di PHP 8.1+
mysqli_report(MYSQLI_REPORT_OFF);

function query($query){
    global $koneksi;
    $hasil = mysqli_query($koneksi,$query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($hasil)){
        $rows [] = $row;
    } 
    return $rows;
}
function jadwal($data){
    global $koneksi;
    $ambiljam = htmlspecialchars($data['jampost']);
    $ambiltanggal = htmlspecialchars($data['tanggalpost']);
    $uniqid = htmlspecialchars($data['uniqid']);
    $ambilstudio = htmlspecialchars($data['studio']);

     mysqli_query($koneksi,"INSERT INTO data_jamtanggal VALUES('','$uniqid','$ambiltanggal','$ambiljam','$ambilstudio') ");
    return mysqli_affected_rows($koneksi);
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
//
$olddate =htmlspecialchars ($data["tanggal"]);
$date = date('Y-m-d', strtotime($olddate));
$uniqid =htmlspecialchars ($data["uniqid"]);
$studio =htmlspecialchars ($data["studio"]);

//
$jam =htmlspecialchars ($data["jam"]);
$bukti_lunas =htmlspecialchars ($data["buktilunas"]);
$jumlah =htmlspecialchars ($data["jumlah"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);
$ketbayar =htmlspecialchars ($data["ketbayar"]);
$catatan = htmlspecialchars ($data["catatan"]);
////////////////////////////////////////////////////////////////
$nambahmakeup =htmlspecialchars ($data["nambahmakeup"]);
$nambahhairdo = htmlspecialchars ($data["nambahhairdo"]);
////////////////////////////////////////////////////////////////
$katajmlhorgdws = htmlspecialchars ($data["jumlahorangdewasa"]);
$katajmlhorgank = htmlspecialchars ($data["jumlahoranganak"]);
$jumlahorangdewasa = 'dewasa='.$katajmlhorgdws;
$jumlahoranganak = 'anakanak='.$katajmlhorgank;
////////////////////////////////////////////////////////////////
// $arraycetak = ($data["cetakfoto"]);
// $tambahcetak = implode($arraycetak);
// $arrayjumlah = ($data["jumlahcetak"]);
// $jumlahcetak = implode($arrayjumlah);
///
$hargaovr = htmlspecialchars ($data["hargaovr"]);
$waktuovr = htmlspecialchars ($data["waktuovr"]);
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
///
$cetakfoto1 =htmlspecialchars ($data["cetakfoto1"]);
$cetakfoto2 =htmlspecialchars ($data["cetakfoto2"]);
$cetakfoto3 =htmlspecialchars ($data["cetakfoto3"]);
$cetakfoto4 =htmlspecialchars ($data["cetakfoto4"]);
$cetakfoto5 =htmlspecialchars ($data["cetakfoto5"]);
$cetakfoto6 =htmlspecialchars ($data["cetakfoto6"]);
$cetakfoto7 =htmlspecialchars ($data["cetakfoto7"]);
$cetakfoto8 =htmlspecialchars ($data["cetakfoto8"]);
$cetakfoto9 =htmlspecialchars ($data["cetakfoto9"]);
///
$jumlahcetak1 =htmlspecialchars ($data["jumlahcetak1"]);
$jumlahcetak2 =htmlspecialchars ($data["jumlahcetak2"]);
$jumlahcetak3 =htmlspecialchars ($data["jumlahcetak3"]);
$jumlahcetak4 =htmlspecialchars ($data["jumlahcetak4"]);
$jumlahcetak5 =htmlspecialchars ($data["jumlahcetak5"]);
$jumlahcetak6 =htmlspecialchars ($data["jumlahcetak6"]);
$jumlahcetak7 =htmlspecialchars ($data["jumlahcetak7"]);
$jumlahcetak8 =htmlspecialchars ($data["jumlahcetak8"]);
$jumlahcetak9 =htmlspecialchars ($data["jumlahcetak9"]);
///
$nambahanak =htmlspecialchars ($data["nambahanak"]);
$nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
$nambahorang =htmlspecialchars ($data["nambahorang"]);
$nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);
/////// tabel tracking transaksi
$random = random_bytes(3);
$uniqid = (bin2hex($random));

$nilai = 10;
$penambahanmakeup = $nambahmakeup == 0 ? '-' : $nambahmakeup;
$penambahanhairdo = $nambahhairdo == 0 ? '-' : $nambahhairdo;
$waktuemail = $waktuovr == 30 ? 'menit' : (($waktuovr == 1 ? 'jam':''));
$penambahanwaktu = $waktuovr == 0 ? '-': $waktuovr.' '.$waktuemail;
$catatanemail = empty($catatan) ? '-' : $catatan;
//harga DP
$dp = (
    $package == 'baby smash cake' ||
    ($package == 'maternity' && $jumlah == 'diamond package')
) ? 500000 : 200000;



$nambahcetak = empty($cetakfoto1.$jumlahcetak1.
                 $cetakfoto2.$jumlahcetak2.
                 $cetakfoto3.$jumlahcetak3.
                 $cetakfoto4.$jumlahcetak4.
                 $cetakfoto5.$jumlahcetak5.
                 $cetakfoto6.$jumlahcetak6.
                 $cetakfoto7.$jumlahcetak7.
                 $cetakfoto8.$jumlahcetak8.
                 $cetakfoto9.$jumlahcetak9)  ? '-' : $cetakfoto1.$jumlahcetak1.
                 $cetakfoto2.$jumlahcetak2.
                 $cetakfoto3.$jumlahcetak3.
                 $cetakfoto4.$jumlahcetak4.
                 $cetakfoto5.$jumlahcetak5.
                 $cetakfoto6.$jumlahcetak6.
                 $cetakfoto7.$jumlahcetak7.
                 $cetakfoto8.$jumlahcetak8.
                 $cetakfoto9.$jumlahcetak9;

$bayardp = "Terimakasih sudah melakukan pembayaran dp sebesar Rp. 200.000";

$bayarself = "Terimakasih sudah mengisi format booking, data anda telah tersimpan. Untuk memastikan silahkan hubungi admin via Whatsapp";

//
date_default_timezone_set("Asia/Jakarta");
$waktuinput= date("d-m-Y H:i:s"); 

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


if($studio == 'Bekasi-Kabupaten'||$studio == ''){
    $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kabupaten','')");
    $jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kabupaten','')");
    if (mysqli_fetch_assoc($jamjadwal)){  
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingbs.php";';
        echo '</script>';
        return false;
    }else if(mysqli_fetch_assoc($jamjadwal1)){
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingbs.php";';
        echo '</script>';
        return false;
    } 
}else{
    $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kota')");
    $jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kota')");
    if (mysqli_fetch_assoc($jamjadwal)){  
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingbs.php";';
        echo '</script>';
        return false;
    }else if(mysqli_fetch_assoc($jamjadwal1)){
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingbs.php";';
        echo '</script>';
        return false;
    } 
}

//upload gambar
$bukti_transfer = uploadDpTf();
if(!$bukti_transfer){
    return false;
}
    
//urutan query sql = id,nama,notelp,email,package,harga(total),hargasetelahdp(total-dp),tanggal,jam,buktitf,buktilunas,jumlah(jenispaket),cetakfoto,nambahanak,nambahdewasa,nambahorang,nambahwaktu,tambahcetak,waktutransaksi(suksess isi data)
    
    $query = "INSERT INTO data_konfirmasi_cobahampirfinishjuga
                VALUES
                ('','$nama','$notelp',
                '$email','$package',
                '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000) + $hargaovr + ($nambahmakeup*400000) + ($nambahhairdo*250000),
                ('$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000) + $hargaovr + ($nambahmakeup*400000)+($nambahhairdo*250000))- $dp ,
                '$date',
                '$jam','$bukti_transfer',
                '$bukti_lunas','$jumlah','$cetakfoto',
                '$nambahanak','$nambahdewasa',
                '$nambahorang',
                 '$waktuovr',
                 '$cetakfoto1$jumlahcetak1'
                 '$cetakfoto2$jumlahcetak2'
                 '$cetakfoto3$jumlahcetak3'
                 '$cetakfoto4$jumlahcetak4'
                 '$cetakfoto5$jumlahcetak5'
                 '$cetakfoto6$jumlahcetak6'
                 '$cetakfoto7$jumlahcetak7'
                 '$cetakfoto8$jumlahcetak8'
                 '$cetakfoto9$jumlahcetak9','$nambahhairdo','$nambahmakeup', '$waktuinput','$uniqid','$catatan','$jumlahorangdewasa','$jumlahoranganak','$studio')";
    
mysqli_query($koneksi, $query);

            mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000) + $hargaovr
            )");




// $harganambah = "UPDATE data_konfirmasi_cobahampirfinishjuga SET anak = $hargaanak , dewasa = $hargadewasa WHERE harga = $harga + $hargaanak + $hargadewasa";
// mysqli_query($koneksi, $harganambah);

 // transaksi
$input = "INSERT INTO data_tracking_coba 
        VALUES('','$uniqid','$nama','$email','$tambah1','','','','','','','','','$waktuinput','','','','','$gambar1','$gambar2','$gambar3','$gambar4','$gambar5','','')";
mysqli_query($koneksi,$input);

// transaksi

include('assets/phpmailer/Exception.php');
include('assets/phpmailer/PHPMailer.php');
include('assets/phpmailer/SMTP.php');


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer();


    $mail->do_debug = 0;
    //Server settings
    $mail->SMTPDebug = false;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'ssl://smtp.gmail.com:465';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication rizalfotostudio@rfstudio.id
    $mail->Username   = 'rizalfotostudio2022@gmail.com';                     //SMTP username
    $mail->Password   = 'dpbgrqoftjhvbcev';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RF Studio');
    $mail->addAddress('rfstudioarsip2022@gmail.com', 'Data Arsip');
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
   $mail->Body = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RF Studio Email</title>

<style>
    body {
        background:#f5f5f5; 
        font-family: Arial, sans-serif; 
        margin:0; 
        padding:20px;
    }
    .card {
        background:#ffffff;
        border-radius:10px;
        padding:20px;
        max-width:500px;
        margin:auto;
        box-shadow:0 0 5px rgba(0,0,0,0.1);
    }
    .header {
        background:#212529;
        color:#fff;
        padding:15px;
        text-align:center;
        font-size:20px;
        font-weight:bold;
        border-radius:8px;
    }
    .rf-box {
        background: #ffffff;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .label {
        font-weight:bold;
        padding:6px 10px;
        width:40%;
        vertical-align:top;
        color:#000;
    }
    .value {
        padding:6px 10px;
        width:60%;
        vertical-align:top;
        color:#333;
    }
    .note-box {
        background:#f1f1f1;
        padding:15px;
        border-radius:8px;
        margin-top:20px;
        font-size:13px;
        color:#444;
    }
    .footer {
        text-align:center;
        font-size:13px;
        margin-top:20px;
        color:#555;
    }
    a { color:#212529; }
</style>

</head>

<body>

<div class="card rf-box">

    <div class="header">Rizal Foto Studio</div>

    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; margin-top:20px;">

        <tr><td class="label">Nama</td>
            <td class="value">'.$nama.'</td></tr>

        <tr><td class="label">No. Telp</td>
            <td class="value">'.$notelp.'</td></tr>

        <tr><td class="label">Email</td>
            <td class="value">'.$email.'</td></tr>

        <tr><td class="label">Tanggal</td>
            <td class="value">'.$date.'</td></tr>

        <tr><td class="label">Jam</td>
            <td class="value">'.$jam.'</td></tr>

        <tr><td class="label">Jumlah Orang</td>
            <td class="value">'.$jumlahorangdewasa.' & '.$jumlahoranganak.'</td></tr>

        <tr><td class="label">Kode Track Progress</td>
            <td class="value">'.$uniqid.'</td></tr>

        <tr><td class="label">Lokasi Studio</td>
            <td class="value">'.$studio.'</td></tr>

        <tr><td class="label">Package</td>
            <td class="value">'.$package.' '.$jumlah.'</td></tr>

        <tr><td class="label">Cetak Foto</td>
            <td class="value">'.$cetakfoto.'</td></tr>

        <tr><td class="label">Penambahan Waktu</td>
            <td class="value">'.$penambahanwaktu.'</td></tr>

        <tr><td class="label">Penambahan Makeup</td>
            <td class="value">'.$penambahanmakeup.'</td></tr>

        <tr><td class="label">Penambahan Hairdo</td>
            <td class="value">'.$penambahanhairdo.'</td></tr>

        <tr><td class="label">Penambahan Cetak</td>
            <td class="value">'.$nambahcetak.'</td></tr>

        <tr><td class="label">Catatan</td>
            <td class="value">'.$catatanemail.'</td></tr>

        <tr><td class="label">Status</td>
            <td class="value">'.$ketbayar.'</td></tr>

    </table>

    <div class="note-box">
        <b>*Note :</b><br>
        - Mohon datang lebih awal dari jam bookingan.<br>
        - Sesi Foto mengikuti jam bookingan.<br>
        - <b>Terlambat</b> diluar tanggung jawab kami.<br>
        - Yang belum lunas, pelunasan dilakukan setelah selesai foto.<br>
        - Link Google Drive dikirim setelah pelunasan.<br>
        - Reschedule maksimal H-3.<br>
        - Link Google Drive berlaku selama 2 minggu.
    </div>

    <div class="footer">
        Terima kasih telah menggunakan layanan kami<br>
        <a href="https://rfstudio.id">RFStudio.id</a>
    </div>

</div>

</body>
</html>
';

    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   try { $mail->send(); } catch (\Throwable $e) { /* kirim email gagal: diabaikan agar proses tetap lanjut & tidak halaman putih */ }

return mysqli_affected_rows($koneksi);
}
function tambahpw($data){
    
    global $koneksi;
//ambil data dari tiap elemen dalam form
$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
$package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
//
$olddate =htmlspecialchars ($data["tanggal"]);
$date = date('Y-m-d', strtotime($olddate));
$uniqid =htmlspecialchars ($data["uniqid"]);
$studio =htmlspecialchars ($data["studio"]);
//
$jam =htmlspecialchars ($data["jam"]);
$jamemail =htmlspecialchars ($data["jamemail"]);
$bukti_lunas =htmlspecialchars ($data["buktilunas"]);
$jumlah =htmlspecialchars ($data["jumlah"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);
$ketbayar =htmlspecialchars ($data["ketbayar"]);
$catatan = htmlspecialchars ($data["catatan"]);
////////////////////////////////////////////////////////////////
$katajmlhorgdws = htmlspecialchars ($data["jumlahorangdewasa"]);
$katajmlhorgank = htmlspecialchars ($data["jumlahoranganak"]);
$jumlahorangdewasa = 'dewasa='.$katajmlhorgdws;
$jumlahoranganak = 'anakanak='.$katajmlhorgank;
////////////////////////////////////////////////////////////////
// $arraycetak = ($data["cetakfoto"]);
// $tambahcetak = implode($arraycetak);
// $arrayjumlah = ($data["jumlahcetak"]);
// $jumlahcetak = implode($arrayjumlah);
///
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
///
$cetakfoto1 =htmlspecialchars ($data["cetakfoto1"]);
$cetakfoto2 =htmlspecialchars ($data["cetakfoto2"]);
$cetakfoto3 =htmlspecialchars ($data["cetakfoto3"]);
$cetakfoto4 =htmlspecialchars ($data["cetakfoto4"]);
$cetakfoto5 =htmlspecialchars ($data["cetakfoto5"]);
$cetakfoto6 =htmlspecialchars ($data["cetakfoto6"]);
$cetakfoto7 =htmlspecialchars ($data["cetakfoto7"]);
$cetakfoto8 =htmlspecialchars ($data["cetakfoto8"]);
$cetakfoto9 =htmlspecialchars ($data["cetakfoto9"]);
///
$jumlahcetak1 =htmlspecialchars ($data["jumlahcetak1"]);
$jumlahcetak2 =htmlspecialchars ($data["jumlahcetak2"]);
$jumlahcetak3 =htmlspecialchars ($data["jumlahcetak3"]);
$jumlahcetak4 =htmlspecialchars ($data["jumlahcetak4"]);
$jumlahcetak5 =htmlspecialchars ($data["jumlahcetak5"]);
$jumlahcetak6 =htmlspecialchars ($data["jumlahcetak6"]);
$jumlahcetak7 =htmlspecialchars ($data["jumlahcetak7"]);
$jumlahcetak8 =htmlspecialchars ($data["jumlahcetak8"]);
$jumlahcetak9 =htmlspecialchars ($data["jumlahcetak9"]);
///
$nambahanak =htmlspecialchars ($data["nambahanak"]);
$nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
$nambahorang =htmlspecialchars ($data["nambahorang"]);
$nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);
/////// tabel tracking transaksi
$random = random_bytes(3);
$uniqid = (bin2hex($random));
$catatanemail = empty($catatan) ? '-' : $catatan;
//harga DP
$dp =  ($package == 'gold') ? 500000 : 200000;
$bayardp = "Terimakasih sudah melakukan pembayaran dp sebesar Rp. 200.000";

$bayarself = "Terimakasih sudah mengisi format booking, data anda telah tersimpan. Untuk memastikan silahkan hubungi admin via Whatsapp";

$waktuovr = htmlspecialchars ($data["waktuovr"]);
$hargaovr = htmlspecialchars ($data["hargaovr"]);

$waktuemail = $waktuovr == 30 ? 'menit' : (($waktuovr == 1 ? 'jam':''));
$penambahanwaktu = $waktuovr == 0 ? '-': $waktuovr.' '.$waktuemail;

$nambahcetak = empty($cetakfoto1.$jumlahcetak1.
                 $cetakfoto2.$jumlahcetak2.
                 $cetakfoto3.$jumlahcetak3.
                 $cetakfoto4.$jumlahcetak4.
                 $cetakfoto5.$jumlahcetak5.
                 $cetakfoto6.$jumlahcetak6.
                 $cetakfoto7.$jumlahcetak7.
                 $cetakfoto8.$jumlahcetak8.
                 $cetakfoto9.$jumlahcetak9)  ? '-' : $cetakfoto1.$jumlahcetak1.
                 $cetakfoto2.$jumlahcetak2.
                 $cetakfoto3.$jumlahcetak3.
                 $cetakfoto4.$jumlahcetak4.
                 $cetakfoto5.$jumlahcetak5.
                 $cetakfoto6.$jumlahcetak6.
                 $cetakfoto7.$jumlahcetak7.
                 $cetakfoto8.$jumlahcetak8.
                 $cetakfoto9.$jumlahcetak9;

//
date_default_timezone_set("Asia/Jakarta");
$waktuinput= date("d-m-Y H:i:s"); 

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



if($studio == 'Bekasi-Kabupaten'||$studio == ''){
    $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kabupaten','')");
    $jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kabupaten','')");
    if (mysqli_fetch_assoc($jamjadwal)){  
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingpw.php";';
        echo '</script>';
        return false;
    }else if(mysqli_fetch_assoc($jamjadwal1)){
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingpw.php";';
        echo '</script>';
        return false;
    } 
}else{
    $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kota')");
    $jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kota')");
    if (mysqli_fetch_assoc($jamjadwal)){  
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingpw.php";';
        echo '</script>';
        return false;
    }else if(mysqli_fetch_assoc($jamjadwal1)){
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingpw.php";';
        echo '</script>';
        return false;
    } 
}
//upload gambar
$bukti_transfer = uploadDpTf();
if(!$bukti_transfer){
    return false;
}
    
//urutan query sql = id,nama,notelp,email,package,harga(total),hargasetelahdp(total-dp),tanggal,jam,buktitf,buktilunas,jumlah(jenispaket),cetakfoto,nambahanak,nambahdewasa,nambahorang,nambahwaktu,tambahcetak,waktutransaksi(suksess isi data)
    
    $query = "INSERT INTO data_konfirmasi_cobahampirfinishjuga
                VALUES
                ('','$nama','$notelp',
                '$email','$package',
                '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000) + $hargaovr,
                ('$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000) + $hargaovr)- $dp ,
                '$date',
                '$jam','$bukti_transfer',
                '$bukti_lunas','$jumlah','$cetakfoto',
                '$nambahanak','$nambahdewasa',
                '$nambahorang',
                 '$nambahwaktu' * 5,
                 '$cetakfoto1$jumlahcetak1'
                 '$cetakfoto2$jumlahcetak2'
                 '$cetakfoto3$jumlahcetak3'
                 '$cetakfoto4$jumlahcetak4'
                 '$cetakfoto5$jumlahcetak5'
                 '$cetakfoto6$jumlahcetak6'
                 '$cetakfoto7$jumlahcetak7'
                 '$cetakfoto8$jumlahcetak8'
                 '$cetakfoto9$jumlahcetak9','0','0', '$waktuinput','$uniqid','$catatan','$jumlahorangdewasa','$jumlahoranganak','$studio')";
    
mysqli_query($koneksi, $query);

mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000) + $hargaovr
            )");


// $harganambah = "UPDATE data_konfirmasi_cobahampirfinishjuga SET anak = $hargaanak , dewasa = $hargadewasa WHERE harga = $harga + $hargaanak + $hargadewasa";
// mysqli_query($koneksi, $harganambah);

 // transaksi
$input = "INSERT INTO data_tracking_coba 
        VALUES('','$uniqid','$nama','$email','$tambah1','','','','','','','','','$waktuinput','','','','','$gambar1','$gambar2','$gambar3','$gambar4','$gambar5','','')";
mysqli_query($koneksi,$input);

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
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication rizalfotostudio@rfstudio.id
    $mail->Username   = 'rizalfotostudio2022@gmail.com';                     //SMTP username
    $mail->Password   = 'dpbgrqoftjhvbcev';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RF Studio');
    // $mail->addAddress('rfstudioarsip2022@gmail.com', 'Data Arsip');
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
    $mail->Body = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RF Studio Email</title>

<style>
    body {
        background:#f5f5f5; 
        font-family: Arial, sans-serif; 
        margin:0; 
        padding:20px;
    }
    .card {
        background:#ffffff;
        border-radius:10px;
        padding:20px;
        max-width:500px;
        margin:auto;
        box-shadow:0 0 5px rgba(0,0,0,0.1);
    }
    .header {
        background:#212529;
        color:#fff;
        padding:15px;
        text-align:center;
        font-size:20px;
        font-weight:bold;
        border-radius:8px;
    }
    .rf-box {
        background: #ffffff;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .label {
        font-weight:bold;
        padding:6px 10px;
        width:40%;
        vertical-align:top;
        color:#000;
    }
    .value {
        padding:6px 10px;
        width:60%;
        vertical-align:top;
        color:#333;
    }
    .note-box {
        background:#f1f1f1;
        padding:15px;
        border-radius:8px;
        margin-top:20px;
        font-size:13px;
        color:#444;
    }
    .footer {
        text-align:center;
        font-size:13px;
        margin-top:20px;
        color:#555;
    }
    a { color:#212529; }
</style>

</head>

<body>

<div class="card rf-box">

    <div class="header">Rizal Foto Studio</div>

    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; margin-top:20px;">

        <tr><td class="label">Nama</td>
            <td class="value">'.$nama.'</td></tr>

        <tr><td class="label">No. Telp</td>
            <td class="value">'.$notelp.'</td></tr>

        <tr><td class="label">Email</td>
            <td class="value">'.$email.'</td></tr>

        <tr><td class="label">Tanggal</td>
            <td class="value">'.$date.'</td></tr>

        <tr><td class="label">Jam</td>
            <td class="value">'.$jam.'</td></tr>

        <tr><td class="label">Jumlah Orang</td>
            <td class="value">'.$jumlahorangdewasa.' & '.$jumlahoranganak.'</td></tr>

        <tr><td class="label">Kode Track Progress</td>
            <td class="value">'.$uniqid.'</td></tr>

        <tr><td class="label">Lokasi Studio</td>
            <td class="value">'.$studio.'</td></tr>

        <tr><td class="label">Package</td>
            <td class="value">'.$package.' '.$jumlah.'</td></tr>

        <tr><td class="label">Cetak Foto</td>
            <td class="value">'.$cetakfoto.'</td></tr>

        <tr><td class="label">Penambahan Waktu</td>
            <td class="value">'.$penambahanwaktu.'</td></tr>

        <tr><td class="label">Penambahan Makeup</td>
            <td class="value">'.$penambahanmakeup.'</td></tr>

        <tr><td class="label">Penambahan Hairdo</td>
            <td class="value">'.$penambahanhairdo.'</td></tr>

        <tr><td class="label">Penambahan Cetak</td>
            <td class="value">'.$nambahcetak.'</td></tr>

        <tr><td class="label">Catatan</td>
            <td class="value">'.$catatanemail.'</td></tr>

        <tr><td class="label">Status</td>
            <td class="value">'.$ketbayar.'</td></tr>

    </table>

    <div class="note-box">
        <b>*Note :</b><br>
        - Mohon datang lebih awal dari jam bookingan.<br>
        - Sesi Foto mengikuti jam bookingan.<br>
        - <b>Terlambat</b> diluar tanggung jawab kami.<br>
        - Yang belum lunas, pelunasan dilakukan setelah selesai foto.<br>
        - Link Google Drive dikirim setelah pelunasan.<br>
        - Reschedule maksimal H-3.<br>
        - Link Google Drive berlaku selama 2 minggu.
    </div>

    <div class="footer">
        Terima kasih telah menggunakan layanan kami<br>
        <a href="https://rfstudio.id">RFStudio.id</a>
    </div>

</div>

</body>
</html>
';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   try { $mail->send(); } catch (\Throwable $e) { /* kirim email gagal: diabaikan agar proses tetap lanjut & tidak halaman putih */ }

return mysqli_affected_rows($koneksi);
}
function tambahbsc($data){
    
    global $koneksi;
//ambil data dari tiap elemen dalam form
$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
$package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
//
$olddate =htmlspecialchars ($data["tanggal"]);
$date = date('Y-m-d', strtotime($olddate));
$uniqid =htmlspecialchars ($data["uniqid"]);
$studio =htmlspecialchars ($data["studio"]);

//
$jam =htmlspecialchars ($data["jam"]);
$bukti_lunas =htmlspecialchars ($data["buktilunas"]);
$jumlah =htmlspecialchars ($data["jumlah"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);
$ketbayar =htmlspecialchars ($data["ketbayar"]);
$catatan = htmlspecialchars ($data["catatan"]);
//
////////////////////////////////////////////////////////////////
$katajmlhorgdws = htmlspecialchars ($data["jumlahorangdewasa"]);
$katajmlhorgank = htmlspecialchars ($data["jumlahoranganak"]);
$jumlahorangdewasa = 'dewasa='.$katajmlhorgdws;
$jumlahoranganak = 'anakanak='.$katajmlhorgank;
////////////////////////////////////////////////////////////////
// $arraycetak = ($data["cetakfoto"]);
// $tambahcetak = implode($arraycetak);
// $arrayjumlah = ($data["jumlahcetak"]);
// $jumlahcetak = implode($arrayjumlah);
///
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
///
$cetakfoto1 =htmlspecialchars ($data["cetakfoto1"]);
$cetakfoto2 =htmlspecialchars ($data["cetakfoto2"]);
$cetakfoto3 =htmlspecialchars ($data["cetakfoto3"]);
$cetakfoto4 =htmlspecialchars ($data["cetakfoto4"]);
$cetakfoto5 =htmlspecialchars ($data["cetakfoto5"]);
$cetakfoto6 =htmlspecialchars ($data["cetakfoto6"]);
$cetakfoto7 =htmlspecialchars ($data["cetakfoto7"]);
$cetakfoto8 =htmlspecialchars ($data["cetakfoto8"]);
$cetakfoto9 =htmlspecialchars ($data["cetakfoto9"]);
///
$jumlahcetak1 =htmlspecialchars ($data["jumlahcetak1"]);
$jumlahcetak2 =htmlspecialchars ($data["jumlahcetak2"]);
$jumlahcetak3 =htmlspecialchars ($data["jumlahcetak3"]);
$jumlahcetak4 =htmlspecialchars ($data["jumlahcetak4"]);
$jumlahcetak5 =htmlspecialchars ($data["jumlahcetak5"]);
$jumlahcetak6 =htmlspecialchars ($data["jumlahcetak6"]);
$jumlahcetak7 =htmlspecialchars ($data["jumlahcetak7"]);
$jumlahcetak8 =htmlspecialchars ($data["jumlahcetak8"]);
$jumlahcetak9 =htmlspecialchars ($data["jumlahcetak9"]);
///
$nambahanak =htmlspecialchars ($data["nambahanak"]);
$nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
$nambahorang =htmlspecialchars ($data["nambahorang"]);
$nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);
$catatanemail = empty($catatan) ? '-' : $catatan;
/////// tabel tracking transaksi
$random = random_bytes(3);
$uniqid = (bin2hex($random));

$bayardp = "Terimakasih sudah melakukan pembayaran dp sebesar Rp. 200.000";

$bayarself = "Terimakasih sudah mengisi format booking, data anda telah tersimpan. Untuk memastikan silahkan hubungi admin via Whatsapp";

$nambahcetak = empty($cetakfoto1.$jumlahcetak1.
                 $cetakfoto2.$jumlahcetak2.
                 $cetakfoto3.$jumlahcetak3.
                 $cetakfoto4.$jumlahcetak4.
                 $cetakfoto5.$jumlahcetak5.
                 $cetakfoto6.$jumlahcetak6.
                 $cetakfoto7.$jumlahcetak7.
                 $cetakfoto8.$jumlahcetak8.
                 $cetakfoto9.$jumlahcetak9)  ? '-' : $cetakfoto1.$jumlahcetak1.
                 $cetakfoto2.$jumlahcetak2.
                 $cetakfoto3.$jumlahcetak3.
                 $cetakfoto4.$jumlahcetak4.
                 $cetakfoto5.$jumlahcetak5.
                 $cetakfoto6.$jumlahcetak6.
                 $cetakfoto7.$jumlahcetak7.
                 $cetakfoto8.$jumlahcetak8.
                 $cetakfoto9.$jumlahcetak9;

//
date_default_timezone_set("Asia/Jakarta");
$waktuinput= date("d-m-Y H:i:s"); 

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
//SELF FOTO


if($studio == 'Bekasi-Kabupaten'||$studio == ''){
    $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$date' AND jam='$jam' AND studio IN ('Bekasi-Kabupaten','')");
    $jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$date' AND jam='$jam' AND studio IN ('Bekasi-Kabupaten','')");
    if (mysqli_fetch_assoc($jamjadwal)){  
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingbsc.php";';
        echo '</script>';
        return false;
    }else if(mysqli_fetch_assoc($jamjadwal1)){
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingbsc.php";';
        echo '</script>';
        return false;
    } 
}else{
    $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$date' AND jam='$jam' AND studio IN ('Bekasi-Kota')");
    $jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$date' AND jam='$jam' AND studio IN ('Bekasi-Kota')");
    if (mysqli_fetch_assoc($jamjadwal)){  
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingbsc.php";';
        echo '</script>';
        return false;
    }else if(mysqli_fetch_assoc($jamjadwal1)){
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingbsc.php";';
        echo '</script>';
        return false;
    } 
}
//upload gambar
$bukti_transfer = uploadDpTf();
if(!$bukti_transfer){
    return false;
}
    
//urutan query sql = id,nama,notelp,email,package,harga(total),hargasetelahdp(total-dp),tanggal,jam,buktitf,buktilunas,jumlah(jenispaket),cetakfoto,nambahanak,nambahdewasa,nambahorang,nambahwaktu,tambahcetak,waktutransaksi(suksess isi data)
     $query = "INSERT INTO data_konfirmasi_cobahampirfinishjuga
                VALUES
                ('','$nama','$notelp',
                '$email','$package',
                '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000),
                0 ,
                '$date', 
                '$jam','$bukti_transfer',
                '$bukti_lunas','$jumlah','$cetakfoto',
                '$nambahanak','$nambahdewasa',
                '$nambahorang',
                 '$nambahwaktu' * 5,
                 '$cetakfoto1$jumlahcetak1'
                 '$cetakfoto2$jumlahcetak2'
                 '$cetakfoto3$jumlahcetak3'
                 '$cetakfoto4$jumlahcetak4'
                 '$cetakfoto5$jumlahcetak5'
                 '$cetakfoto6$jumlahcetak6'
                 '$cetakfoto7$jumlahcetak7'
                 '$cetakfoto8$jumlahcetak8'
                 '$cetakfoto9$jumlahcetak9','0','0', '$waktuinput','$uniqid','$catatan','$jumlahorangdewasa','$jumlahoranganak','$studio' )";
                
mysqli_query($koneksi, $query);

mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000) 
            )");


// $harganambah = "UPDATE data_konfirmasi_cobahampirfinishjuga SET anak = $hargaanak , dewasa = $hargadewasa WHERE harga = $harga + $hargaanak + $hargadewasa";
// mysqli_query($koneksi, $harganambah);

 // transaksi
$input = "INSERT INTO data_tracking_coba 
        VALUES('','$uniqid','$nama','$email','$tambah1','','','','','','','','','$waktuinput','','','','','$gambar1','$gambar2','$gambar3','$gambar4','$gambar5','','')";
mysqli_query($koneksi,$input);

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
     $mail->Password   = 'dpbgrqoftjhvbcev';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RF Studio');
        $mail->addAddress('rfstudioarsip2022@gmail.com', 'Data Arsip');
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
    $mail->Body = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RF Studio Email</title>

<style>
    body {
        background:#f5f5f5; 
        font-family: Arial, sans-serif; 
        margin:0; 
        padding:20px;
    }
    .card {
        background:#ffffff;
        border-radius:10px;
        padding:20px;
        max-width:500px;
        margin:auto;
        box-shadow:0 0 5px rgba(0,0,0,0.1);
    }
    .header {
        background:#212529;
        color:#fff;
        padding:15px;
        text-align:center;
        font-size:20px;
        font-weight:bold;
        border-radius:8px;
    }
    .rf-box {
        background: #ffffff;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .label {
        font-weight:bold;
        padding:6px 10px;
        width:40%;
        vertical-align:top;
        color:#000;
    }
    .value {
        padding:6px 10px;
        width:60%;
        vertical-align:top;
        color:#333;
    }
    .note-box {
        background:#f1f1f1;
        padding:15px;
        border-radius:8px;
        margin-top:20px;
        font-size:13px;
        color:#444;
    }
    .footer {
        text-align:center;
        font-size:13px;
        margin-top:20px;
        color:#555;
    }
    a { color:#212529; }
</style>

</head>

<body>

<div class="card rf-box">

    <div class="header">Rizal Foto Studio</div>

    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; margin-top:20px;">

        <tr><td class="label">Nama</td>
            <td class="value">'.$nama.'</td></tr>

        <tr><td class="label">No. Telp</td>
            <td class="value">'.$notelp.'</td></tr>

        <tr><td class="label">Email</td>
            <td class="value">'.$email.'</td></tr>

        <tr><td class="label">Tanggal</td>
            <td class="value">'.$date.'</td></tr>

        <tr><td class="label">Jam</td>
            <td class="value">'.$jam.'</td></tr>

        <tr><td class="label">Jumlah Orang</td>
            <td class="value">'.$jumlahorangdewasa.' & '.$jumlahoranganak.'</td></tr>

        <tr><td class="label">Kode Track Progress</td>
            <td class="value">'.$uniqid.'</td></tr>

        <tr><td class="label">Lokasi Studio</td>
            <td class="value">'.$studio.'</td></tr>

        <tr><td class="label">Package</td>
            <td class="value">'.$package.' '.$jumlah.'</td></tr>

        <tr><td class="label">Cetak Foto</td>
            <td class="value">'.$cetakfoto.'</td></tr>

        <tr><td class="label">Penambahan Waktu</td>
            <td class="value">'.$penambahanwaktu.'</td></tr>

        <tr><td class="label">Penambahan Cetak</td>
            <td class="value">
                  '.$cetakfoto1.$jumlahcetak1.
                    $cetakfoto2.$jumlahcetak2.
                    $cetakfoto3.$jumlahcetak3.
                    $cetakfoto4.$jumlahcetak4.
                    $cetakfoto5.$jumlahcetak5.
                    $cetakfoto6.$jumlahcetak6.
                    $cetakfoto7.$jumlahcetak7.
                    $cetakfoto8.$jumlahcetak8.
                    $cetakfoto9.$jumlahcetak9.'</td></tr>

        <tr><td class="label">Catatan</td>
            <td class="value">'.$catatanemail.'</td></tr>

        <tr><td class="label">Status</td>
            <td class="value">'.$ketbayar.'</td></tr>

    </table>

    <div class="note-box">
        <b>*Note :</b><br>
        - Mohon datang lebih awal dari jam bookingan.<br>
        - Sesi Foto mengikuti jam bookingan.<br>
        - <b>Terlambat</b> diluar tanggung jawab kami.<br>
        - Yang belum lunas, pelunasan dilakukan setelah selesai foto.<br>
        - Link Google Drive dikirim setelah pelunasan.<br>
        - Reschedule maksimal H-3.<br>
        - Link Google Drive berlaku selama 2 minggu.
    </div>

    <div class="footer">
        Terima kasih telah menggunakan layanan kami<br>
        <a href="https://rfstudio.id">RFStudio.id</a>
    </div>

</div>

</body>
</html>
';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   try { $mail->send(); } catch (\Throwable $e) { /* kirim email gagal: diabaikan agar proses tetap lanjut & tidak halaman putih */ }

return mysqli_affected_rows($koneksi);
}
function tambahcetakfoto($data){
    
    global $koneksi;
//ambil data dari tiap elemen dalam form
$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
$package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
//
// $date =htmlspecialchars ($data["tanggal"]);
// $uniqid =htmlspecialchars ($data["uniqid"]);
// $studio =htmlspecialchars ($data["studio"]);

//
$jam =htmlspecialchars ($data["jam"]);
$bukti_lunas =htmlspecialchars ($data["buktilunas"]);
$jumlah =htmlspecialchars ($data["jumlah"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);
$ketbayar =htmlspecialchars ($data["ketbayar"]);
$catatan = htmlspecialchars ($data["catatan"]);
//
////////////////////////////////////////////////////////////////
$katajmlhorgdws = htmlspecialchars ($data["jumlahorangdewasa"]);
$katajmlhorgank = htmlspecialchars ($data["jumlahoranganak"]);
$jumlahorangdewasa = 'dewasa='.$katajmlhorgdws;
$jumlahoranganak = 'anakanak='.$katajmlhorgank;
////////////////////////////////////////////////////////////////
// $arraycetak = ($data["file"]);
// $tambahcetak = implode(",",$arraycetak);
// $arrayjumlah = ($data["jumlahcetak"]);
// $jumlahcetak = implode($arrayjumlah);
///
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
///
$cetakfoto1 =htmlspecialchars ($data["cetakfoto1"]);
$cetakfoto2 =htmlspecialchars ($data["cetakfoto2"]);
$cetakfoto3 =htmlspecialchars ($data["cetakfoto3"]);
$cetakfoto4 =htmlspecialchars ($data["cetakfoto4"]);
$cetakfoto5 =htmlspecialchars ($data["cetakfoto5"]);
$cetakfoto6 =htmlspecialchars ($data["cetakfoto6"]);
$cetakfoto7 =htmlspecialchars ($data["cetakfoto7"]);
$cetakfoto8 =htmlspecialchars ($data["cetakfoto8"]);
$cetakfoto9 =htmlspecialchars ($data["cetakfoto9"]);
///
$jumlahcetak1 =htmlspecialchars ($data["jumlahcetak1"]);
$jumlahcetak2 =htmlspecialchars ($data["jumlahcetak2"]);
$jumlahcetak3 =htmlspecialchars ($data["jumlahcetak3"]);
$jumlahcetak4 =htmlspecialchars ($data["jumlahcetak4"]);
$jumlahcetak5 =htmlspecialchars ($data["jumlahcetak5"]);
$jumlahcetak6 =htmlspecialchars ($data["jumlahcetak6"]);
$jumlahcetak7 =htmlspecialchars ($data["jumlahcetak7"]);
$jumlahcetak8 =htmlspecialchars ($data["jumlahcetak8"]);
$jumlahcetak9 =htmlspecialchars ($data["jumlahcetak9"]);
///
$nambahanak =htmlspecialchars ($data["nambahanak"]);
$nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
$nambahorang =htmlspecialchars ($data["nambahorang"]);
$nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);
/////// tabel tracking transaksi
$random = random_bytes(3);
$uniqid = (bin2hex($random));
$catatanemail = empty($catatan) ? '-' : $catatan;

$bayardp = "Terimakasih sudah melakukan pembayaran dp sebesar Rp. 200.000";

$bayarself = "Terimakasih sudah mengisi format booking, data anda telah tersimpan. Untuk memastikan silahkan hubungi admin via Whatsapp";

//
date_default_timezone_set("Asia/Jakarta");
$waktuinput= date("d-m-Y H:i:s"); 
$waktuinput2= date("Y-m-d"); 

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
//SELF FOTO

//upload gambar
$bukti_transfer = uploadDpTf();
if(!$bukti_transfer){
    return false;
}
    
//urutan query sql = id,nama,notelp,email,package,harga(total),hargasetelahdp(total-dp),tanggal,jam,buktitf,buktilunas,jumlah(jenispaket),cetakfoto,nambahanak,nambahdewasa,nambahorang,nambahwaktu,tambahcetak,waktutransaksi(suksess isi data)
     $query = "INSERT INTO data_konfirmasi_cobahampirfinishjuga
                VALUES
                ('','$nama','$notelp',
                '$email','$package',
                '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000),
                0,'$waktuinput2', 
                '$jam','$bukti_transfer',
                '$bukti_lunas','$jumlah','$cetakfoto',
                0,0,0,0,
                 '$cetakfoto1$jumlahcetak1'
                 '$cetakfoto2$jumlahcetak2'
                 '$cetakfoto3$jumlahcetak3'
                 '$cetakfoto4$jumlahcetak4'
                 '$cetakfoto5$jumlahcetak5'
                 '$cetakfoto6$jumlahcetak6'
                 '$cetakfoto7$jumlahcetak7'
                 '$cetakfoto8$jumlahcetak8'
                 '$cetakfoto9$jumlahcetak9','0','0', '$waktuinput','$uniqid','$catatan','$jumlahorangdewasa','$jumlahoranganak','' )";
                
mysqli_query($koneksi, $query);
mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) + ($nambahwaktu * 20000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000)
            )");



// $harganambah = "UPDATE data_konfirmasi_cobahampirfinishjuga SET anak = $hargaanak , dewasa = $hargadewasa WHERE harga = $harga + $hargaanak + $hargadewasa";
// mysqli_query($koneksi, $harganambah);

 // transaksi
$input = "INSERT INTO data_tracking_coba 
        VALUES('','$uniqid','$nama','$email','$tambah1','','','','','','','','','$waktuinput','','','','','$gambar1','$gambar2','$gambar3','$gambar4','$gambar5','','')";
mysqli_query($koneksi,$input);

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
     $mail->Password   = 'dpbgrqoftjhvbcev';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RF Studio');
    $mail->addAddress('rfstudioarsip2022@gmail.com', 'Data Arsip');
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
   $mail->Body = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RF Studio Email</title>

<style>
    body {
        background:#f5f5f5; 
        font-family: Arial, sans-serif; 
        margin:0; 
        padding:20px;
    }
    .card {
        background:#ffffff;
        border-radius:10px;
        padding:20px;
        max-width:500px;
        margin:auto;
        box-shadow:0 0 5px rgba(0,0,0,0.1);
    }
    .header {
        background:#212529;
        color:#fff;
        padding:15px;
        text-align:center;
        font-size:20px;
        font-weight:bold;
        border-radius:8px;
    }
    .rf-box {
        background: #ffffff;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .label {
        font-weight:bold;
        padding:6px 10px;
        width:40%;
        vertical-align:top;
        color:#000;
    }
    .value {
        padding:6px 10px;
        width:60%;
        vertical-align:top;
        color:#333;
    }
    .note-box {
        background:#f1f1f1;
        padding:15px;
        border-radius:8px;
        margin-top:20px;
        font-size:13px;
        color:#444;
    }
    .footer {
        text-align:center;
        font-size:13px;
        margin-top:20px;
        color:#555;
    }
    a { color:#212529; }
</style>

</head>

<body>

<div class="card rf-box">

    <div class="header">Rizal Foto Studio</div>

    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; margin-top:20px;">

        <tr><td class="label">Nama</td>
            <td class="value">'.$nama.'</td></tr>

        <tr><td class="label">No. Telp</td>
            <td class="value">'.$notelp.'</td></tr>

        <tr><td class="label">Email</td>
            <td class="value">'.$email.'</td></tr>

        <tr><td class="label">Kode Track Progress</td>
            <td class="value">'.$uniqid.'</td></tr>

        <tr><td class="label">Lokasi Studio</td>
            <td class="value">'.$studio.'</td></tr>

        <tr><td class="label">Package</td>
            <td class="value">'.$package.' '.$jumlah.'</td></tr>

        <tr><td class="label">Cetak Foto</td>
            <td class="value">
                  '.$cetakfoto1.$jumlahcetak1.
                    $cetakfoto2.$jumlahcetak2.
                    $cetakfoto3.$jumlahcetak3.
                    $cetakfoto4.$jumlahcetak4.
                    $cetakfoto5.$jumlahcetak5.
                    $cetakfoto6.$jumlahcetak6.
                    $cetakfoto7.$jumlahcetak7.
                    $cetakfoto8.$jumlahcetak8.
                    $cetakfoto9.$jumlahcetak9.'</td></tr>

        <tr><td class="label">Catatan</td>
            <td class="value">'.$catatanemail.'</td></tr>

        <tr><td class="label">Status</td>
            <td class="value">'.$ketbayar.'</td></tr>

    </table>

    <div class="note-box">
        <b>*Note :</b><br>
        - Mohon datang lebih awal dari jam bookingan.<br>
        - Sesi Foto mengikuti jam bookingan.<br>
        - <b>Terlambat</b> diluar tanggung jawab kami.<br>
        - Yang belum lunas, pelunasan dilakukan setelah selesai foto.<br>
        - Link Google Drive dikirim setelah pelunasan.<br>
        - Reschedule maksimal H-3.<br>
        - Link Google Drive berlaku selama 2 minggu.
    </div>

    <div class="footer">
        Terima kasih telah menggunakan layanan kami<br>
        <a href="https://rfstudio.id">RFStudio.id</a>
    </div>

</div>

</body>
</html>
';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   try { $mail->send(); } catch (\Throwable $e) { /* kirim email gagal: diabaikan agar proses tetap lanjut & tidak halaman putih */ }

return mysqli_affected_rows($koneksi);
}

function tambahstudio($data){
    
    global $koneksi;
//ambil data dari tiap elemen dalam form
$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
$package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
//
$olddate =htmlspecialchars ($data["tanggal"]);
$date = date('Y-m-d', strtotime($olddate));
$uniqid =htmlspecialchars ($data["uniqid"]);
$studio =htmlspecialchars ($data["studio"]);
$nambahmakeup =htmlspecialchars ($data["nambahmakeup"]);
$nambahhairdo = htmlspecialchars ($data["nambahhairdo"]);

//
$jam =htmlspecialchars ($data["jam"]);
$bukti_lunas =htmlspecialchars ($data["buktilunas"]);
$tipe_package =htmlspecialchars ($data["jumlah"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);
$ketbayar =htmlspecialchars ($data["ketbayar"]);
$catatan = htmlspecialchars ($data["catatan"]);
//
////////////////////////////////////////////////////////////////
$katajmlhorgdws = htmlspecialchars ($data["jumlahorangdewasa"]);
$katajmlhorgank = htmlspecialchars ($data["jumlahoranganak"]);
$jumlahorangdewasa = 'dewasa='.$katajmlhorgdws;
$jumlahoranganak = 'anakanak='.$katajmlhorgank;
////////////////////////////////////////////////////////////////
// $arraycetak = ($data["cetakfoto"]);
// $tambahcetak = implode($arraycetak);
// $arrayjumlah = ($data["jumlahcetak"]);
// $jumlahcetak = implode($arrayjumlah);
///
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
///
$cetakfoto1 =htmlspecialchars ($data["cetakfoto1"]);
$cetakfoto2 =htmlspecialchars ($data["cetakfoto2"]);
$cetakfoto3 =htmlspecialchars ($data["cetakfoto3"]);
$cetakfoto4 =htmlspecialchars ($data["cetakfoto4"]);
$cetakfoto5 =htmlspecialchars ($data["cetakfoto5"]);
$cetakfoto6 =htmlspecialchars ($data["cetakfoto6"]);
$cetakfoto7 =htmlspecialchars ($data["cetakfoto7"]);
$cetakfoto8 =htmlspecialchars ($data["cetakfoto8"]);
$cetakfoto9 =htmlspecialchars ($data["cetakfoto9"]);
///
$jumlahcetak1 =htmlspecialchars ($data["jumlahcetak1"]);
$jumlahcetak2 =htmlspecialchars ($data["jumlahcetak2"]);
$jumlahcetak3 =htmlspecialchars ($data["jumlahcetak3"]);
$jumlahcetak4 =htmlspecialchars ($data["jumlahcetak4"]);
$jumlahcetak5 =htmlspecialchars ($data["jumlahcetak5"]);
$jumlahcetak6 =htmlspecialchars ($data["jumlahcetak6"]);
$jumlahcetak7 =htmlspecialchars ($data["jumlahcetak7"]);
$jumlahcetak8 =htmlspecialchars ($data["jumlahcetak8"]);
$jumlahcetak9 =htmlspecialchars ($data["jumlahcetak9"]);
///
$nambahanak =htmlspecialchars ($data["nambahanak"]);
$nambahdewasa =htmlspecialchars ($data["nambahdewasa"]);
$nambahorang =htmlspecialchars ($data["nambahorang"]);
//$nambahwaktu =htmlspecialchars ($data["nambahwaktu"]);
$cetakwaktu2 =htmlspecialchars ($data["cetakwaktu2"]);
$cetakwaktu3 =htmlspecialchars ($data["cetakwaktu3"]);
$nambahwaktustdio2 = 0 ;
$nambahwaktustdio3 =htmlspecialchars ($data["nambahwaktustdio3"]);
$catatanemail = empty($catatan) ? '-' : $catatan;
/////// tabel tracking transaksi
// $random = random_bytes(3);
// $uniqid = (bin2hex($random)); 

$bayardp = "Terimakasih sudah melakukan pembayaran dp sebesar Rp. 200.000";

$bayarself = "Terimakasih sudah mengisi format booking, data anda telah tersimpan. Untuk memastikan silahkan hubungi admin via Whatsapp";

$waktuemail = ($cetakwaktu2 =='0' ||$cetakwaktu3 =='0') ? '-' :$cetakwaktu2.$cetakwaktu3.' jam' ;

//
date_default_timezone_set("Asia/Jakarta");
$waktuinput= date("d-m-Y H:i:s"); 

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

if($studio == 'Bekasi-Kabupaten'||$studio == ''){
    $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kabupaten','')");
    $jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kabupaten','')");
    if (mysqli_fetch_assoc($jamjadwal)){  
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingstudio.php";';
        echo '</script>';
        return false;
    }else if(mysqli_fetch_assoc($jamjadwal1)){
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingstudio.php";';
        echo '</script>';
        return false;
    } 
}else{
    $jamjadwal = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kota')");
    $jamjadwal1 = mysqli_query($koneksi, "SELECT tanggal,jam FROM data_booking_cobahampirfinishjuga WHERE tanggal='$date' AND (jam LIKE '%$jam%' OR jam LIKE '%$jam %') AND studio IN ('Bekasi-Kota')");
    if (mysqli_fetch_assoc($jamjadwal)){  
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingstudio.php";';
        echo '</script>';
        return false;
    }else if(mysqli_fetch_assoc($jamjadwal1)){
        echo '<script language="javascript">';
        echo 'alert("Mohon maaf, jam yang anda pilih sudah dipesan");';
        echo 'window.location = "bookingstudio.php";';
        echo '</script>';
        return false;
    } 
}

//upload gambar
$bukti_transfer = uploadDpTf();
if(!$bukti_transfer){
    return false;
}
    
//urutan query sql = id,nama,notelp,email,package,harga(total),hargasetelahdp(total-dp),tanggal,jam,buktitf,buktilunas,jumlah(jenispaket),cetakfoto,nambahanak,nambahdewasa,nambahorang,nambahwaktu,tambahcetak,waktutransaksi(suksess isi data)

    $query = "INSERT INTO data_konfirmasi_cobahampirfinishjuga
                VALUES
                ('','$nama','$notelp',
                '$email','$package',
                '$harga' + ($nambahanak * 35000) + ($nambahdewasa * 50000) 
                + ($nambahorang * 15000) 
                + ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000)+($nambahwaktustdio2 * 200000)+($nambahwaktustdio3 * 250000),
                0,
                '$date', 
                '$jam','$bukti_transfer',
                '$bukti_lunas','$tipe_package','$cetakfoto',
                '$nambahanak','$nambahdewasa',
                '$nambahorang',
                 '$cetakwaktu2$cetakwaktu3',
                 '$cetakfoto1$jumlahcetak1'
                 '$cetakfoto2$jumlahcetak2'
                 '$cetakfoto3$jumlahcetak3'
                 '$cetakfoto4$jumlahcetak4'
                 '$cetakfoto5$jumlahcetak5'
                 '$cetakfoto6$jumlahcetak6'
                 '$cetakfoto7$jumlahcetak7'
                 '$cetakfoto8$jumlahcetak8'
                 '$cetakfoto9$jumlahcetak9','$nambahhairdo','$nambahmakeup','$waktuinput','$uniqid','$catatan','$jumlahorangdewasa','$jumlahoranganak','$studio')";

mysqli_query($koneksi, $query);
// if (!mysqli_query($koneksi, $query)) {
//     echo "Error: " . mysqli_error($koneksi);
// }


// $harganambah = "UPDATE data_konfirmasi_cobahampirfinishjuga SET anak = $hargaanak , dewasa = $hargadewasa WHERE harga = $harga + $hargaanak + $hargadewasa";
// mysqli_query($koneksi, $harganambah);

 // transaksi
$input = "INSERT INTO data_tracking_coba 
        VALUES('','$uniqid','$nama','$email','$tambah1','','','','','','','','','$waktuinput','','','','','$gambar1','$gambar2','$gambar3','$gambar4','$gambar5','','')";
mysqli_query($koneksi,$input);

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
     $mail->Password   = 'dpbgrqoftjhvbcev';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RF Studio');
    $mail->addAddress('rfstudioarsip2022@gmail.com', 'Data Arsip');
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
    $mail->Body = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RF Studio Email</title>

<style>
    body {
        background:#f5f5f5; 
        font-family: Arial, sans-serif; 
        margin:0; 
        padding:20px;
    }
    .card {
        background:#ffffff;
        border-radius:10px;
        padding:20px;
        max-width:500px;
        margin:auto;
        box-shadow:0 0 5px rgba(0,0,0,0.1);
    }
    .header {
        background:#212529;
        color:#fff;
        padding:15px;
        text-align:center;
        font-size:20px;
        font-weight:bold;
        border-radius:8px;
    }
    .rf-box {
        background: #ffffff;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .label {
        font-weight:bold;
        padding:6px 10px;
        width:40%;
        vertical-align:top;
        color:#000;
    }
    .value {
        padding:6px 10px;
        width:60%;
        vertical-align:top;
        color:#333;
    }
    .note-box {
        background:#f1f1f1;
        padding:15px;
        border-radius:8px;
        margin-top:20px;
        font-size:13px;
        color:#444;
    }
    .footer {
        text-align:center;
        font-size:13px;
        margin-top:20px;
        color:#555;
    }
    a { color:#212529; }
</style>

</head>

<body>

<div class="card rf-box">

    <div class="header">Rizal Foto Studio</div>

    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; margin-top:20px;">

        <tr><td class="label">Nama</td>
            <td class="value">'.$nama.'</td></tr>

        <tr><td class="label">No. Telp</td>
            <td class="value">'.$notelp.'</td></tr>

        <tr><td class="label">Email</td>
            <td class="value">'.$email.'</td></tr>

        <tr><td class="label">Tanggal</td>
            <td class="value">'.$date.'</td></tr>

        <tr><td class="label">Jam</td>
            <td class="value">'.$jam.'</td></tr>

        <tr><td class="label">Jumlah Orang</td>
            <td class="value">'.$jumlahorangdewasa.' & '.$jumlahoranganak.'</td></tr>

        <tr><td class="label">Kode Track Progress</td>
            <td class="value">'.$uniqid.'</td></tr>

        <tr><td class="label">Lokasi Studio</td>
            <td class="value">'.$studio.'</td></tr>

        <tr><td class="label">Package</td>
            <td class="value">'.$package.' '.$jumlah.'</td></tr>

        <tr><td class="label">Penambahan Waktu</td>
            <td class="value">'.$penambahanwaktu.'</td></tr>

        <tr><td class="label">Catatan</td>
            <td class="value">'.$catatanemail.'</td></tr>

        <tr><td class="label">Status</td>
            <td class="value">'.$ketbayar.'</td></tr>

    </table>

    <div class="note-box">
        <b>*Note :</b><br>
        - Mohon datang lebih awal dari jam bookingan.<br>
        - Sesi Foto mengikuti jam bookingan.<br>
        - <b>Terlambat</b> diluar tanggung jawab kami.<br>
        - Yang belum lunas, pelunasan dilakukan setelah selesai foto.<br>
        - Link Google Drive dikirim setelah pelunasan.<br>
        - Reschedule maksimal H-3.<br>
        - Link Google Drive berlaku selama 2 minggu.
    </div>

    <div class="footer">
        Terima kasih telah menggunakan layanan kami<br>
        <a href="https://rfstudio.id">RFStudio.id</a>
    </div>

</div>

</body>
</html>
';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   try { $mail->send(); } catch (\Throwable $e) { /* kirim email gagal: diabaikan agar proses tetap lanjut & tidak halaman putih */ }

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
//     echo '.go(-1);';
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
// $id = $data["id"];
$uniqid = htmlspecialchars($data["uniqid"]);
$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
$package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
//
$waktu =htmlspecialchars ($data["tanggal"]);
//
$jam =htmlspecialchars ($data["jam"]);
// $bukti_lunas =htmlspecialchars ($data["buktilunas"]);
// $jumlah =htmlspecialchars ($data["jumlah"]);
$studio =htmlspecialchars ($data["studio"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);//upsize
////////////////////////////////////////////////////////////////
$katajmlhorgdws = htmlspecialchars ($data["jumlahorangdewasa"]);
$katajmlhorgank = htmlspecialchars ($data["jumlahoranganak"]);
$jumlahorangdewasa = $katajmlhorgdws;
$jumlahoranganak = $katajmlhorgank;
////////////////////////////////////////////////////////////////
$hargaovr = htmlspecialchars($data["hargaovr"]);
$waktuovr = htmlspecialchars($data["waktuovr"]);
$catatan = htmlspecialchars($data["catatan"]);
// $hargasebelumnyaovr = htmlspecialchars($data["harga"]);
// $hargasebelumnyadp = htmlspecialchars($data["hargasdp"]);

$nambahmakeup =htmlspecialchars ($data["nambahmakeup"]);
$nambahhairdo = htmlspecialchars ($data["nambahhairdo"]);

// $arraycetak = ($data["cetakfoto"]);
// $tambahcetak = implode(",", $arraycetak);
//
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
///
$cetakfoto1 =htmlspecialchars ($data["cetakfoto1"]);
$cetakfoto2 =htmlspecialchars ($data["cetakfoto2"]);
$cetakfoto3 =htmlspecialchars ($data["cetakfoto3"]);
$cetakfoto4 =htmlspecialchars ($data["cetakfoto4"]);
$cetakfoto5 =htmlspecialchars ($data["cetakfoto5"]);
$cetakfoto6 =htmlspecialchars ($data["cetakfoto6"]);
$cetakfoto7 =htmlspecialchars ($data["cetakfoto7"]);
$cetakfoto8 =htmlspecialchars ($data["cetakfoto8"]);
$cetakfoto9 =htmlspecialchars ($data["cetakfoto9"]);
// $cetakfoto10 =htmlspecialchars ($data["cetakfotosebelumnya"]);
///
$jumlahcetak1 =htmlspecialchars ($data["jumlahcetak1"]);
$jumlahcetak2 =htmlspecialchars ($data["jumlahcetak2"]);
$jumlahcetak3 =htmlspecialchars ($data["jumlahcetak3"]);
$jumlahcetak4 =htmlspecialchars ($data["jumlahcetak4"]);
$jumlahcetak5 =htmlspecialchars ($data["jumlahcetak5"]);
$jumlahcetak6 =htmlspecialchars ($data["jumlahcetak6"]);
$jumlahcetak7 =htmlspecialchars ($data["jumlahcetak7"]);
$jumlahcetak8 =htmlspecialchars ($data["jumlahcetak8"]);
$jumlahcetak9 =htmlspecialchars ($data["jumlahcetak9"]);
//
// $nambahanaklama =htmlspecialchars ($data["nambahanaklama"]);
// $nambahdewasalama =htmlspecialchars ($data["nambahdewasalama"]);
// $nambahoranglama =htmlspecialchars ($data["nambahoranglama"]);
// $nambahwaktulama =htmlspecialchars ($data["nambahwaktulama"]);
// $cetakwaktu2lama =htmlspecialchars ($data["cetakwaktu2lama"]);
// $cetakwaktu3lama =htmlspecialchars ($data["cetakwaktu3lama"]);
$nambahanakbaru =htmlspecialchars ($data["nambahanak"]);
$nambahdewasabaru =htmlspecialchars ($data["nambahdewasa"]);
$nambahorangbaru =htmlspecialchars ($data["nambahorang"]);
$nambahwaktubaru =htmlspecialchars ($data["nambahwaktu"]);
$cetakwaktu2baru =htmlspecialchars ($data["cetakwaktu2"]);
$cetakwaktu3baru =htmlspecialchars ($data["cetakwaktu3"]);
///
$nambahanak =  (int) $nambahanakbaru;
$nambahdewasa = (int) $nambahdewasabaru;
$nambahorang = (int) $nambahorangbaru;
$nambahwaktu = (int) $nambahwaktubaru;
$cetakwaktu2 =  (int) $cetakwaktu2baru;
$cetakwaktu3 =  (int) $cetakwaktu3baru;
///
$nambahwaktustdio2 =htmlspecialchars ($data["nambahwaktustdio2"]);
$nambahwaktustdio3 =htmlspecialchars ($data["nambahwaktustdio3"]);

//upload gambar
// $bukti_lunas = uploadLunasTf();
// if(!$bukti_lunas){
//     return false;
// }


$hargasebelumnya = $harga;
$hargaupgrade = htmlspecialchars ($data["hargaup"]);
$hargatambah = ((int) $nambahanak * 35000) + ((int) $nambahdewasa * 50000) 
                + ((int) $nambahorangbaru * 15000) + ((int) $nambahwaktubaru * 20000)
                +($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000)+((int) $nambahwaktustdio2 * 200000)+((int) $nambahwaktustdio3 * 250000)
                + ((int)$nambahmakeup * 400000) + ((int)$nambahhairdo * 250000)
                ;
$hargareal = (int)$hargasebelumnya + (int)$hargaupgrade + (int)$hargatambah + (int)$hargaovr;
    
    $ambildatacetak = mysqli_query($koneksi,"SELECT * FROM data_tambahcetak WHERE uniqid ='$uniqid'");
    $datacetak = mysqli_fetch_array($ambildatacetak);
    $ambildatastudio = mysqli_query($koneksi,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid ='$uniqid'");
    $datastudio = mysqli_fetch_array($ambildatastudio);
    if(empty($datacetak["uniqid"])){
        if($package == 'self photo'||$package == 'studio'){
            $hargasisa = $hargaupgrade + $hargatambah + $hargaovr;
            if($package == 'self photo'){
                $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
                nama = '$nama' ,
                no_telp = '$notelp', 
                email = '$email', 
                tanggal = '$waktu', 
                jam = '$jam', 
                harga = '$hargareal',
                hargasetelahdp = $hargasisa ,
                cetak_foto = '$cetakfoto',
                tambah_cetak =
                        
                        '$cetakfoto1$jumlahcetak1'
                        '$cetakfoto2$jumlahcetak2'
                        '$cetakfoto3$jumlahcetak3'
                        '$cetakfoto4$jumlahcetak4'
                        '$cetakfoto5$jumlahcetak5'
                        '$cetakfoto6$jumlahcetak6'
                        '$cetakfoto7$jumlahcetak7'
                        '$cetakfoto8$jumlahcetak8'
                        '$cetakfoto9$jumlahcetak9',
                anak = '$nambahanak',
                dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
                tambah_waktu = '$nambahwaktu'*5,
                jmlhorgdws = '$jumlahorangdewasa',
                jmlhorgank = '$jumlahoranganak',
                studio = '$studio'
                WHERE uniqid = '$uniqid'";
                mysqli_query($koneksi, $query);
                mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                    '',
                    '$nama',
                    '$uniqid',
                    '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                    ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                    ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                    ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                    ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                    $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                    $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                    + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                    + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                    + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                    + ($cetakharga9 * 700000), $hargareal
                )");
            }
            else if($package == 'studio'&&$datastudio["tipe_package"]=='studio2(bawah)'){
                $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
                nama = '$nama' ,
                no_telp = '$notelp', 
                email = '$email', 
                tanggal = '$waktu', 
                jam = '$jam', 
                harga = '$hargareal',
                hargasetelahdp = $hargasisa ,
                cetak_foto = '$cetakfoto',
                tambah_cetak =
                        
                        '$cetakfoto1$jumlahcetak1'
                        '$cetakfoto2$jumlahcetak2'
                        '$cetakfoto3$jumlahcetak3'
                        '$cetakfoto4$jumlahcetak4'
                        '$cetakfoto5$jumlahcetak5'
                        '$cetakfoto6$jumlahcetak6'
                        '$cetakfoto7$jumlahcetak7'
                        '$cetakfoto8$jumlahcetak8'
                        '$cetakfoto9$jumlahcetak9',
                anak = '$nambahanak',
                dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
                tambah_waktu = '$nambahwaktustdio2',
                jmlhorgdws = '$jumlahorangdewasa',
                jmlhorgank = '$jumlahoranganak',
                studio = '$studio'
                WHERE uniqid = '$uniqid'";
                mysqli_query($koneksi, $query);
                mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                    '',
                    '$nama',
                    '$uniqid',
                    '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                    ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                    ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                    ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                    ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                    $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                    $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                    + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                    + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                    + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                    + ($cetakharga9 * 700000), $hargareal
                )");
            }
            else if($package == 'studio'&&$datastudio["tipe_package"]=='studio3(atas)'){
                $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
                nama = '$nama' ,
                no_telp = '$notelp', 
                email = '$email', 
                tanggal = '$waktu', 
                jam = '$jam', 
                harga = '$hargareal',
                hargasetelahdp = $hargasisa ,
                cetak_foto = '$cetakfoto',
                tambah_cetak =
                        
                        '$cetakfoto1$jumlahcetak1'
                        '$cetakfoto2$jumlahcetak2'
                        '$cetakfoto3$jumlahcetak3'
                        '$cetakfoto4$jumlahcetak4'
                        '$cetakfoto5$jumlahcetak5'
                        '$cetakfoto6$jumlahcetak6'
                        '$cetakfoto7$jumlahcetak7'
                        '$cetakfoto8$jumlahcetak8'
                        '$cetakfoto9$jumlahcetak9',
                anak = '$nambahanak',
                dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
                tambah_waktu = '$nambahwaktustdio3',
                jmlhorgdws = '$jumlahorangdewasa',
                jmlhorgank = '$jumlahoranganak',
                studio = '$studio'
                WHERE uniqid = '$uniqid'";
                mysqli_query($koneksi, $query);
                mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                    '',
                    '$nama',
                    '$uniqid',
                    '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                    ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                    ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                    ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                    ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                    $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                    $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                    + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                    + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                    + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                    + ($cetakharga9 * 700000), $hargareal
                )");
            }
            
        }else if($package == 'cetak foto'){
           
            $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000),
            hargasetelahdp = 0 ,
            cetak_foto = '$cetakfoto',
            tambah_cetak =
                    
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9'
            WHERE uniqid = '$uniqid'";
            mysqli_query($koneksi, $query);
            mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000)
            )");
        }else{
            $hargasisa = $hargareal - 200000;
            $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = '$hargareal',
            hargasetelahdp = $hargasisa,
            cetak_foto = '$cetakfoto',
            tambah_cetak =
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9',
            anak = '$nambahanak',
            dewasa = '$nambahdewasa',
            catatan = '$catatan',
            tambah_orang = '$nambahorang',
            tambah_waktu = '$waktuovr',
            tambah_makeup = '$nambahmakeup',
            tambah_hairdo = '$nambahhairdo',
            jmlhorgdws = '$jumlahorangdewasa',
            jmlhorgank = '$jumlahoranganak',
            studio = '$studio'
            WHERE uniqid = '$uniqid'";
            mysqli_multi_query($koneksi, $query);
            mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), $hargareal

            )");
        }
    }else{
        if($package == 'self photo'||$package == 'studio'){
            $hargasisa = $hargaupgrade + $hargatambah + $hargaovr;
            if($package == 'self photo'){
                $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
                nama = '$nama' ,
                no_telp = '$notelp', 
                email = '$email', 
                tanggal = '$waktu', 
                jam = '$jam', 
                harga = '$hargareal',
                hargasetelahdp = $hargasisa ,
                cetak_foto = '$cetakfoto',
                tambah_cetak =
                        
                        '$cetakfoto1$jumlahcetak1'
                        '$cetakfoto2$jumlahcetak2'
                        '$cetakfoto3$jumlahcetak3'
                        '$cetakfoto4$jumlahcetak4'
                        '$cetakfoto5$jumlahcetak5'
                        '$cetakfoto6$jumlahcetak6'
                        '$cetakfoto7$jumlahcetak7'
                        '$cetakfoto8$jumlahcetak8'
                        '$cetakfoto9$jumlahcetak9',
                anak = '$nambahanak',
                dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
                tambah_waktu = '$nambahwaktu'*5,
                jmlhorgdws = '$jumlahorangdewasa',
                jmlhorgank = '$jumlahoranganak',
                studio = '$studio'
                WHERE uniqid = '$uniqid'";
                mysqli_multi_query($koneksi, $query);
                mysqli_query($koneksi, "UPDATE data_tambahcetak SET
                    nama = '$nama',
                    uk5r = ('$cetakharga1' * 15000),
                    uk5rframe = ('$cetakharga2' * 30000),
                    uk10rs = ('$cetakharga3' * 35000),
                    uk10rsframe = ('$cetakharga4' * 70000),
                    uk30x40 = ('$cetakharga5' * 150000),
                    uk40x60 = ('$cetakharga6' * 350000),
                    uk60x90 = ('$cetakharga7' * 600000),
                    uk60x100 = ('$cetakharga8' * 650000),
                    uk70x100 = ('$cetakharga9' * 700000),
                    qtty_uk5r = $cetakharga1, 
                    qtty_uk5rframe = $cetakharga2,
                    qtty_uk10rs = $cetakharga3,
                    qtty_uk10rsframe = $cetakharga4,
                    qtty_uk30x40 = $cetakharga5,
                    qtty_uk40x60 = $cetakharga6,
                    qtty_uk60x90 = $cetakharga7,
                    qtty_uk60x100 =$cetakharga8,
                    qtty_uk70x100 =$cetakharga9,
                    harga_cetak = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                    + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                    + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                    + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                    + ($cetakharga9 * 700000), 
                    total_harga = $hargareal
                    WHERE uniqid = '$uniqid'
                ");
            }
            else if($package == 'studio'&& $datastudio["tipe_package"]=='studio2(bawah)'){
                $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
                nama = '$nama' ,
                no_telp = '$notelp', 
                email = '$email', 
                tanggal = '$waktu', 
                jam = '$jam', 
                harga = '$hargareal',
                hargasetelahdp = $hargasisa ,
                cetak_foto = '$cetakfoto',
                tambah_cetak =
                        
                        '$cetakfoto1$jumlahcetak1'
                        '$cetakfoto2$jumlahcetak2'
                        '$cetakfoto3$jumlahcetak3'
                        '$cetakfoto4$jumlahcetak4'
                        '$cetakfoto5$jumlahcetak5'
                        '$cetakfoto6$jumlahcetak6'
                        '$cetakfoto7$jumlahcetak7'
                        '$cetakfoto8$jumlahcetak8'
                        '$cetakfoto9$jumlahcetak9',
                anak = '$nambahanak',
                dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
                tambah_waktu = '$nambahwaktustdio2',
                jmlhorgdws = '$jumlahorangdewasa',
                jmlhorgank = '$jumlahoranganak',
                studio = '$studio'
                WHERE uniqid = '$uniqid'";
                mysqli_multi_query($koneksi, $query);
                mysqli_query($koneksi, "UPDATE data_tambahcetak SET
                    nama = '$nama',
                    uk5r = ('$cetakharga1' * 15000),
                    uk5rframe = ('$cetakharga2' * 30000),
                    uk10rs = ('$cetakharga3' * 35000),
                    uk10rsframe = ('$cetakharga4' * 70000),
                    uk30x40 = ('$cetakharga5' * 150000),
                    uk40x60 = ('$cetakharga6' * 350000),
                    uk60x90 = ('$cetakharga7' * 600000),
                    uk60x100 = ('$cetakharga8' * 650000),
                    uk70x100 = ('$cetakharga9' * 700000),
                    qtty_uk5r = $cetakharga1, 
                    qtty_uk5rframe = $cetakharga2,
                    qtty_uk10rs = $cetakharga3,
                    qtty_uk10rsframe = $cetakharga4,
                    qtty_uk30x40 = $cetakharga5,
                    qtty_uk40x60 = $cetakharga6,
                    qtty_uk60x90 = $cetakharga7,
                    qtty_uk60x100 =$cetakharga8,
                    qtty_uk70x100 =$cetakharga9,
                    harga_cetak = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                    + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                    + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                    + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                    + ($cetakharga9 * 700000), 
                    total_harga = $hargareal
                    WHERE uniqid = '$uniqid'
                ");
            }
            else if($package == 'studio'&& $datastudio["tipe_package"]=='studio3(atas)'){
                $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
                nama = '$nama' ,
                no_telp = '$notelp', 
                email = '$email', 
                tanggal = '$waktu', 
                jam = '$jam', 
                harga = '$hargareal',
                hargasetelahdp = $hargasisa ,
                cetak_foto = '$cetakfoto',
                tambah_cetak =
                        
                        '$cetakfoto1$jumlahcetak1'
                        '$cetakfoto2$jumlahcetak2'
                        '$cetakfoto3$jumlahcetak3'
                        '$cetakfoto4$jumlahcetak4'
                        '$cetakfoto5$jumlahcetak5'
                        '$cetakfoto6$jumlahcetak6'
                        '$cetakfoto7$jumlahcetak7'
                        '$cetakfoto8$jumlahcetak8'
                        '$cetakfoto9$jumlahcetak9',
                anak = '$nambahanak',
                dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
                tambah_waktu = '$nambahwaktustdio3',
                jmlhorgdws = '$jumlahorangdewasa',
                jmlhorgank = '$jumlahoranganak',
                studio = '$studio'
                WHERE uniqid = '$uniqid'";
                mysqli_multi_query($koneksi, $query);
                mysqli_query($koneksi, "UPDATE data_tambahcetak SET
                    nama = '$nama',
                    uk5r = ('$cetakharga1' * 15000),
                    uk5rframe = ('$cetakharga2' * 30000),
                    uk10rs = ('$cetakharga3' * 35000),
                    uk10rsframe = ('$cetakharga4' * 70000),
                    uk30x40 = ('$cetakharga5' * 150000),
                    uk40x60 = ('$cetakharga6' * 350000),
                    uk60x90 = ('$cetakharga7' * 600000),
                    uk60x100 = ('$cetakharga8' * 650000),
                    uk70x100 = ('$cetakharga9' * 700000),
                    qtty_uk5r = $cetakharga1, 
                    qtty_uk5rframe = $cetakharga2,
                    qtty_uk10rs = $cetakharga3,
                    qtty_uk10rsframe = $cetakharga4,
                    qtty_uk30x40 = $cetakharga5,
                    qtty_uk40x60 = $cetakharga6,
                    qtty_uk60x90 = $cetakharga7,
                    qtty_uk60x100 =$cetakharga8,
                    qtty_uk70x100 =$cetakharga9,
                    harga_cetak = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                    + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                    + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                    + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                    + ($cetakharga9 * 700000), 
                    total_harga = $hargareal
                    WHERE uniqid = '$uniqid'
                ");
            }
        }else if($package == 'cetak foto'){
            $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000),
            hargasetelahdp = 0 ,
            cetak_foto = '$cetakfoto',
            tambah_cetak =                 
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9'
            WHERE uniqid = '$uniqid'";
            mysqli_multi_query($koneksi, $query);
            mysqli_query($koneksi, "UPDATE data_tambahcetak SET
                nama = '$nama',
                uk5r = ('$cetakharga1' * 15000),
                uk5rframe = ('$cetakharga2' * 30000),
                uk10rs = ('$cetakharga3' * 35000),
                uk10rsframe = ('$cetakharga4' * 70000),
                uk30x40 = ('$cetakharga5' * 150000),
                uk40x60 = ('$cetakharga6' * 350000),
                uk60x90 = ('$cetakharga7' * 600000),
                uk60x100 = ('$cetakharga8' * 650000),
                uk70x100 = ('$cetakharga9' * 700000),
                qtty_uk5r = $cetakharga1, 
                qtty_uk5rframe = $cetakharga2,
                qtty_uk10rs = $cetakharga3,
                qtty_uk10rsframe = $cetakharga4,
                qtty_uk30x40 = $cetakharga5,
                qtty_uk40x60 = $cetakharga6,
                qtty_uk60x90 = $cetakharga7,
                qtty_uk60x100 =$cetakharga8,
                qtty_uk70x100 =$cetakharga9,
                harga_cetak = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), 
                total_harga = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000)
                WHERE uniqid = '$uniqid'
            ");
        }else{
            $hargasisa = $hargareal - 200000;
            $query = "UPDATE data_konfirmasi_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = '$hargareal',
            hargasetelahdp = $hargasisa,
            cetak_foto = '$cetakfoto',
            tambah_cetak =
                    
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9',
            anak = '$nambahanak',
            dewasa = '$nambahdewasa',
            catatan = '$catatan',
            tambah_orang = '$nambahorang',
            tambah_waktu = '$waktuovr',
            jmlhorgdws = '$jumlahorangdewasa',
            jmlhorgank = '$jumlahoranganak',
            studio = '$studio'
            WHERE uniqid = '$uniqid'";
            mysqli_multi_query($koneksi, $query);
             mysqli_query($koneksi, "UPDATE data_tambahcetak SET
                nama = '$nama',
                uk5r = ('$cetakharga1' * 15000),
                uk5rframe = ('$cetakharga2' * 30000),
                uk10rs = ('$cetakharga3' * 35000),
                uk10rsframe = ('$cetakharga4' * 70000),
                uk30x40 = ('$cetakharga5' * 150000),
                uk40x60 = ('$cetakharga6' * 350000),
                uk60x90 = ('$cetakharga7' * 600000),
                uk60x100 = ('$cetakharga8' * 650000),
                uk70x100 = ('$cetakharga9' * 700000),
                qtty_uk5r = $cetakharga1, 
                qtty_uk5rframe = $cetakharga2,
                qtty_uk10rs = $cetakharga3,
                qtty_uk10rsframe = $cetakharga4,
                qtty_uk30x40 = $cetakharga5,
                qtty_uk40x60 = $cetakharga6,
                qtty_uk60x90 = $cetakharga7,
                qtty_uk60x100 =$cetakharga8,
                qtty_uk70x100 =$cetakharga9,
                harga_cetak = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), 
                total_harga = $hargareal
                WHERE uniqid = '$uniqid'
            ");
        }
    }
return mysqli_affected_rows($koneksi);
}
// ===============================
// ===============================
function editbooking($data){

    global $koneksi;
//ambil data dari tiap elemen dalam form
$id = $data["id"];
$uniqid = htmlspecialchars($data["uniqid"]);
$nama = htmlspecialchars($data["nama"]);
$notelp = htmlspecialchars($data["notelp"]);
$email = htmlspecialchars($data["email"]);
$package = htmlspecialchars($data["package"]);
$harga = htmlspecialchars($data["harga"]);
//
$waktu =htmlspecialchars ($data["tanggal"]);
//
$jam =htmlspecialchars ($data["jam"]);
// $bukti_lunas =htmlspecialchars ($data["buktilunas"]);
$jumlah =htmlspecialchars ($data["jumlah"]);
$studio =htmlspecialchars ($data["studio"]);
$cetakfoto =htmlspecialchars ($data["cetak"]);//upsize
////////////////////////////////////////////////////////////////
$katajmlhorgdws = htmlspecialchars ($data["jumlahorangdewasa"]);
$katajmlhorgank = htmlspecialchars ($data["jumlahoranganak"]);
$jumlahorangdewasa = $katajmlhorgdws;
$jumlahoranganak = $katajmlhorgank;
$catatan = htmlspecialchars ($data["catatan"]);
////////////////////////////////////////////////////////////////
$hargaovr = htmlspecialchars($data["hargaovr"]);
$waktuovr = htmlspecialchars($data["waktuovr"]);
//$hargasebelumnyaovr = htmlspecialchars($data["harga"]);
//$hargasebelumnyadp = htmlspecialchars($data["hargasdp"]);


// $arraycetak = ($data["cetakfoto"]);
// $tambahcetak = implode(",", $arraycetak);
//
$cetakharga1 =htmlspecialchars ($data["cetakharga1"]);
$cetakharga2 =htmlspecialchars ($data["cetakharga2"]);
$cetakharga3 =htmlspecialchars ($data["cetakharga3"]);
$cetakharga4 =htmlspecialchars ($data["cetakharga4"]);
$cetakharga5 =htmlspecialchars ($data["cetakharga5"]);
$cetakharga6 =htmlspecialchars ($data["cetakharga6"]);
$cetakharga7 =htmlspecialchars ($data["cetakharga7"]);
$cetakharga8 =htmlspecialchars ($data["cetakharga8"]);
$cetakharga9 =htmlspecialchars ($data["cetakharga9"]);
///
$cetakfoto1 =htmlspecialchars ($data["cetakfoto1"]);
$cetakfoto2 =htmlspecialchars ($data["cetakfoto2"]);
$cetakfoto3 =htmlspecialchars ($data["cetakfoto3"]);
$cetakfoto4 =htmlspecialchars ($data["cetakfoto4"]);
$cetakfoto5 =htmlspecialchars ($data["cetakfoto5"]);
$cetakfoto6 =htmlspecialchars ($data["cetakfoto6"]);
$cetakfoto7 =htmlspecialchars ($data["cetakfoto7"]);
$cetakfoto8 =htmlspecialchars ($data["cetakfoto8"]);
$cetakfoto9 =htmlspecialchars ($data["cetakfoto9"]);
$cetakfoto10 =htmlspecialchars ($data["cetakfotosebelumnya"]);
///
$jumlahcetak1 =htmlspecialchars ($data["jumlahcetak1"]);
$jumlahcetak2 =htmlspecialchars ($data["jumlahcetak2"]);
$jumlahcetak3 =htmlspecialchars ($data["jumlahcetak3"]);
$jumlahcetak4 =htmlspecialchars ($data["jumlahcetak4"]);
$jumlahcetak5 =htmlspecialchars ($data["jumlahcetak5"]);
$jumlahcetak6 =htmlspecialchars ($data["jumlahcetak6"]);
$jumlahcetak7 =htmlspecialchars ($data["jumlahcetak7"]);
$jumlahcetak8 =htmlspecialchars ($data["jumlahcetak8"]);
$jumlahcetak9 =htmlspecialchars ($data["jumlahcetak9"]);
//
$nambahanaklama =htmlspecialchars ($data["nambahanaklama"]);
$nambahdewasalama =htmlspecialchars ($data["nambahdewasalama"]);
$nambahoranglama =htmlspecialchars ($data["nambahoranglama"]);
$nambahwaktulama =htmlspecialchars ($data["nambahwaktulama"]);
$cetakwaktu2lama =htmlspecialchars ($data["cetakwaktu2lama"]);
$cetakwaktu3lama =htmlspecialchars ($data["cetakwaktu3lama"]);
$nambahanakbaru =htmlspecialchars ($data["nambahanak"]);
$nambahdewasabaru =htmlspecialchars ($data["nambahdewasa"]);
$nambahorangbaru =htmlspecialchars ($data["nambahorang"]);
$nambahwaktubaru =htmlspecialchars ($data["nambahwaktu"]);
$cetakwaktu2baru =htmlspecialchars ($data["cetakwaktu2"]);
$cetakwaktu3baru =htmlspecialchars ($data["cetakwaktu3"]);
///
$nambahanak =  (int) $nambahanakbaru;
$nambahdewasa = (int) $nambahdewasabaru;
$nambahorang = (int) $nambahorangbaru;
$nambahwaktu = (int) $nambahwaktubaru;
$cetakwaktu2 =  (int) $cetakwaktu2baru;
$cetakwaktu3 =  (int) $cetakwaktu3baru;
///
$nambahwaktustdio2 =htmlspecialchars ($data["nambahwaktustdio2"]);
$nambahwaktustdio3 =htmlspecialchars ($data["nambahwaktustdio3"]);

//upload gambar
// $bukti_lunas = uploadLunasTf();
// if(!$bukti_lunas){
//     return false;
// }

$nambahwaktufix= $nambahwaktu||$cetakwaktu2||$cetakwaktu3 ;

$hargasebelumnya = $harga;
$hargaupgrade = htmlspecialchars ($data["hargaup"]);
$hargatambah = ((int) $nambahanak * 35000) + ((int) $nambahdewasa * 50000) 
                + ((int) $nambahorangbaru * 15000) + ((int) $nambahwaktubaru * 20000)
                +($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000)+((int) $nambahwaktustdio2 * 200000)+((int) $nambahwaktustdio3 * 250000);
$hargareal = $hargasebelumnya + $hargaupgrade + $hargatambah + $hargaovr;
    
    $ambildatacetak = mysqli_query($koneksi,"SELECT * FROM data_tambahcetak WHERE uniqid ='$uniqid'");
    $datacetak = mysqli_fetch_array($ambildatacetak);
    if(empty($datacetak["uniqid"])){
        if($package == 'self photo'||$package == 'studio'){
            $hargasisa = $hargaupgrade + $hargatambah + $hargaovr;
            $query = "UPDATE data_booking_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = '$hargareal',
            hargasetelahdp = $hargasisa ,
            cetak_foto = '$cetakfoto',
            tambah_cetak =
                    
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9',
            anak = '$nambahanak',
            dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
            tambah_waktu = '$nambahwaktufix'*5,
            jmlhorgdws = '$jumlahorangdewasa',
            jmlhorgank = '$jumlahoranganak',
            studio = '$studio'
            WHERE uniqid = '$uniqid'";
            mysqli_query($koneksi, $query);
            mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), $hargareal
            )");
        }else if($package == 'cetak foto'){
           
            $query = "UPDATE data_booking_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000),
            hargasetelahdp = 0 ,
            cetak_foto = '$cetakfoto',
            tambah_cetak =
                    
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9'
            WHERE uniqid = '$uniqid'";
            mysqli_query($koneksi, $query);
            mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000)
            )");
        }else{
            $hargasisa = $hargareal - 200000;
            $query = "UPDATE data_booking_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = '$hargareal',
            hargasetelahdp = $hargasisa,
            cetak_foto = '$cetakfoto',
            tambah_cetak =
                    
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9',
            anak = '$nambahanak',
            dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
            tambah_waktu = '$waktuovr',
            jmlhorgdws = '$jumlahorangdewasa',
            jmlhorgank = '$jumlahoranganak',
            studio = '$studio'
            WHERE uniqid = '$uniqid'";
            mysqli_multi_query($koneksi, $query);
            mysqli_query($koneksi, "INSERT INTO data_tambahcetak VALUES(
                '',
                '$nama',
                '$uniqid',
                '$cetakharga1' * 15000,('$cetakharga2' * 30000),
                ('$cetakharga3' * 35000),('$cetakharga4' * 70000),
                ('$cetakharga5' * 150000),('$cetakharga6' * 350000),
                ('$cetakharga7' * 600000),('$cetakharga8' * 650000),
                ('$cetakharga9' * 700000),$cetakharga1, $cetakharga2,
                $cetakharga3,$cetakharga4,$cetakharga5,$cetakharga6,
                $cetakharga7,$cetakharga8,$cetakharga9,($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), $hargareal

            )");
        }
    }else{
        if($package == 'self photo'||$package == 'studio'){
            $hargasisa = $hargaupgrade + $hargatambah + $hargaovr;
            $query = "UPDATE data_booking_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = '$hargareal',
            hargasetelahdp = $hargasisa ,
            cetak_foto = '$cetakfoto',
            tambah_cetak =
                    
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9',
            anak = '$nambahanak',
            dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
            tambah_waktu = '$nambahwaktufix'*5,
            jmlhorgdws = '$jumlahorangdewasa',
            jmlhorgank = '$jumlahoranganak',
            studio = '$studio'
            WHERE uniqid = '$uniqid'";
            mysqli_multi_query($koneksi, $query);
            mysqli_query($koneksi, "UPDATE data_tambahcetak SET
                nama = '$nama',
                uk5r = ('$cetakharga1' * 15000),
                uk5rframe = ('$cetakharga2' * 30000),
                uk10rs = ('$cetakharga3' * 35000),
                uk10rsframe = ('$cetakharga4' * 70000),
                uk30x40 = ('$cetakharga5' * 150000),
                uk40x60 = ('$cetakharga6' * 350000),
                uk60x90 = ('$cetakharga7' * 600000),
                uk60x100 = ('$cetakharga8' * 650000),
                uk70x100 = ('$cetakharga9' * 700000),
                qtty_uk5r = $cetakharga1, 
                qtty_uk5rframe = $cetakharga2,
                qtty_uk10rs = $cetakharga3,
                qtty_uk10rsframe = $cetakharga4,
                qtty_uk30x40 = $cetakharga5,
                qtty_uk40x60 = $cetakharga6,
                qtty_uk60x90 = $cetakharga7,
                qtty_uk60x100 =$cetakharga8,
                qtty_uk70x100 =$cetakharga9,
                harga_cetak = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), 
                total_harga = $hargareal
                WHERE uniqid = '$uniqid'
            ");
        }else if($package == 'cetak foto'){
            $query = "UPDATE data_booking_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000),
            hargasetelahdp = 0 ,
            cetak_foto = '$cetakfoto',
            tambah_cetak =                 
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9'
            WHERE uniqid = '$uniqid'";
            mysqli_multi_query($koneksi, $query);
            mysqli_query($koneksi, "UPDATE data_tambahcetak SET
                nama = '$nama',
                uk5r = ('$cetakharga1' * 15000),
                uk5rframe = ('$cetakharga2' * 30000),
                uk10rs = ('$cetakharga3' * 35000),
                uk10rsframe = ('$cetakharga4' * 70000),
                uk30x40 = ('$cetakharga5' * 150000),
                uk40x60 = ('$cetakharga6' * 350000),
                uk60x90 = ('$cetakharga7' * 600000),
                uk60x100 = ('$cetakharga8' * 650000),
                uk70x100 = ('$cetakharga9' * 700000),
                qtty_uk5r = $cetakharga1, 
                qtty_uk5rframe = $cetakharga2,
                qtty_uk10rs = $cetakharga3,
                qtty_uk10rsframe = $cetakharga4,
                qtty_uk30x40 = $cetakharga5,
                qtty_uk40x60 = $cetakharga6,
                qtty_uk60x90 = $cetakharga7,
                qtty_uk60x100 =$cetakharga8,
                qtty_uk70x100 =$cetakharga9,
                harga_cetak = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), 
                total_harga = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000)
                WHERE uniqid = '$uniqid'
            ");
        }else{
            $hargasisa = $hargareal - 200000;
            $query = "UPDATE data_booking_cobahampirfinishjuga SET 
            nama = '$nama' ,
            no_telp = '$notelp', 
            email = '$email', 
            tanggal = '$waktu', 
            jam = '$jam', 
            harga = '$hargareal',
            hargasetelahdp = $hargasisa,
            cetak_foto = '$cetakfoto',
            tambah_cetak =
                    
                    '$cetakfoto1$jumlahcetak1'
                    '$cetakfoto2$jumlahcetak2'
                    '$cetakfoto3$jumlahcetak3'
                    '$cetakfoto4$jumlahcetak4'
                    '$cetakfoto5$jumlahcetak5'
                    '$cetakfoto6$jumlahcetak6'
                    '$cetakfoto7$jumlahcetak7'
                    '$cetakfoto8$jumlahcetak8'
                    '$cetakfoto9$jumlahcetak9',
            anak = '$nambahanak',
            dewasa = '$nambahdewasa',
                catatan = '$catatan',
                tambah_orang = '$nambahorang',
            tambah_waktu = '$waktuovr',
            jmlhorgdws = '$jumlahorangdewasa',
            jmlhorgank = '$jumlahoranganak',
            studio = '$studio'
            WHERE uniqid = '$uniqid'";
            mysqli_multi_query($koneksi, $query);
             mysqli_query($koneksi, "UPDATE data_tambahcetak SET
                nama = '$nama',
                uk5r = ('$cetakharga1' * 15000),
                uk5rframe = ('$cetakharga2' * 30000),
                uk10rs = ('$cetakharga3' * 35000),
                uk10rsframe = ('$cetakharga4' * 70000),
                uk30x40 = ('$cetakharga5' * 150000),
                uk40x60 = ('$cetakharga6' * 350000),
                uk60x90 = ('$cetakharga7' * 600000),
                uk60x100 = ('$cetakharga8' * 650000),
                uk70x100 = ('$cetakharga9' * 700000),
                qtty_uk5r = $cetakharga1, 
                qtty_uk5rframe = $cetakharga2,
                qtty_uk10rs = $cetakharga3,
                qtty_uk10rsframe = $cetakharga4,
                qtty_uk30x40 = $cetakharga5,
                qtty_uk40x60 = $cetakharga6,
                qtty_uk60x90 = $cetakharga7,
                qtty_uk60x100 =$cetakharga8,
                qtty_uk70x100 =$cetakharga9,
                harga_cetak = ($cetakharga1 * 15000) + ($cetakharga2 * 30000)
                + ($cetakharga3 * 35000) + ($cetakharga4 * 70000)
                + ($cetakharga5 * 150000) + ($cetakharga6 * 350000)
                + ($cetakharga7 * 600000) + ($cetakharga8 * 650000)
                + ($cetakharga9 * 700000), 
                total_harga = $hargareal
                WHERE uniqid = '$uniqid'
            ");
        }
    }
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
    
    mysqli_query($koneksi,"UPDATE data_pelunasan_cobahampirfinishjuga SET bukti_lunas ='$bukti_lunas' WHERE id = $id ");
    mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu4 = CURRENT_TIMESTAMP,tambah4 = '$tambah4',gambar4 ='$gambar4',judul3 = '$judul3'  WHERE id = '$id'");
   
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
     $mail->Password   = 'dpbgrqoftjhvbcev';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->isHTML(true);
    //$mail->addEmbeddedImage(dirname(__FILE__).'../img/logo2.png','logo');

    //Recipients
    $mail->setFrom('rizalfotostudio2022@gmail.com', 'RF Studio');
        $mail->addAddress('rfstudioarsip2022@gmail.com', 'Data Arsip');
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
   try { $mail->send(); } catch (\Throwable $e) { /* kirim email gagal: diabaikan agar proses tetap lanjut & tidak halaman putih */ } 


/* dihapus: baris ini menjalankan hasil boolean sebagai SQL (bug '111') */


return mysqli_affected_rows($koneksi);


}
// ===============================
function konfirmasi($id){
    global $koneksi;

    $sql = mysqli_query($koneksi, "SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid = '$id'");
    $data = mysqli_fetch_array($sql);
    if ($data["package"]=="cetak foto"){
        $tambah2 = "data telah terkonfirmasi oleh admin";
        $gambar2 = "correct.png";
        $judul1 = "Terkonfirmasi";
        //
        $tambah3 = "-cetak foto-";
        $gambar3 = "correct.png";
        $judul2 = "Terkonfirmasi";
        //
        $tambah4 = "-cetak foto-";
        $gambar4 = "correct.png";
        $judul3 = "Terkonfirmasi";
        //
        $tambah5 = "-cetak foto-";
        $gambar5 = "correct.png";
        $judul4 = "Terkonfirmasi";

        mysqli_query($koneksi, "INSERT INTO data_history SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid = '$id'");
        mysqli_query($koneksi,"DELETE FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid = '$id'");
        mysqli_query($koneksi, "UPDATE data_tracking_coba 
        SET 
        waktu2 = CURRENT_TIMESTAMP ,
        tambah2 = '$tambah2',
        gambar2 ='$gambar2',
        judul1 = '$judul1',
        waktu3 = CURRENT_TIMESTAMP ,
        tambah3 = '$tambah3',
        gambar3 ='$gambar3',
        judul2 = '$judul2',
        waktu4 = CURRENT_TIMESTAMP ,
        tambah4 = '$tambah4',
        gambar4 ='$gambar4',
        judul3 = '$judul3',
        waktu5 = CURRENT_TIMESTAMP ,
        tambah5 = '$tambah5',
        gambar5 ='$gambar5',
        judul4 = '$judul4'  WHERE uniqid = '$id'");
        if ( true ){

        echo 'Data Baru telah ditambahkan';

    }
    }
    else{
    $tambah2 = "data telah terkonfirmasi oleh admin, menunggu sesi photoshoot sesuai jadwal anda";
    $gambar2 = "correct.png";
    $judul1 = "Terkonfirmasi";

    mysqli_query($koneksi, "INSERT INTO data_booking_cobahampirfinishjuga SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid = '$id'");
    mysqli_query($koneksi,"DELETE FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid = '$id'");
    mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu2 = CURRENT_TIMESTAMP ,tambah2 = '$tambah2',gambar2 ='$gambar2',judul1 = '$judul1'  WHERE uniqid = '$id'");
    if ( true ){

    echo 'Data Baru telah ditambahkan';

    }
    }

   
    
    return mysqli_affected_rows($koneksi);
}

// ===============================
// function booking($id){
//     global $koneksi;
//     $tambah3 = "sesi photoshoot selesai, segera melakukan pelunasan *(package self photo tidak perlu melakukan pelunasan)";
//     $gambar3 = "correct.png";
//     $judul2 = "Photoshoot";
//     ////////////////////////
//     $tambah4 = "upload bukti pelunasan telah terkirim menunggu konfirmasi dari admin";
//     $gambar4 = "correct.png";
//     $judul3 = "Pelunasan";
    
//     //
//     // $tambah4 = "upload bukti pelunasan telah terkirim menunggu konfirmasi dari admin";
//     // $gambar4 = "correct.png";
//     // $judul3 = "Pelunasan";
//     // //
//     // $tambah5 = "pelunasan terkonfirmasi, transaksi anda telah selesai";
//     // $gambar5 = "correct.png";
//     // $judul4 = "Pelunasan Terkonfirmasi";

//     $selfstudio = mysqli_query($koneksi,"SELECT * FROM data_booking_cobahampirfinishjuga WHERE;");
//     $ambil = mysqli_fetch_array($selfstudio);
//     //////////////////////////////////////////////////////////
//     //////////////////////////////////////////////////////////
//     if ($ambil=='self photo'||$ambil=='studio'){
    
//         mysqli_query($koneksi, "INSERT INTO data_pelunasan_cobahampirfinishjuga SELECT * FROM data_booking_cobahampirfinishjuga WHERE uniqid = '$id'");
//         mysqli_query($koneksi,"DELETE FROM data_booking_cobahampirfinishjuga WHERE uniqid = '$id'");
//         mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu3 = CURRENT_TIMESTAMP, tambah3 = '$tambah3',gambar3 ='$gambar3',judul2 = '$judul2'  WHERE uniqid = '$id'");
//         mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu4 = CURRENT_TIMESTAMP, tambah4 = '$tambah4',gambar4 ='$gambar4',judul3 = '$judul3'  WHERE uniqid = '$id'");
        
//        // mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu5 = CURRENT_TIMESTAMP, tambah5 = '$tambah5',gambar5 ='$gambar5',judul4 = '$judul4'  WHERE id = '$id'");


//         if ( mysqli_multi_query($koneksi, $pindahdata) )
//         {
//             //echo 'Data Baru telah ditambahkan';
//         }

//     }else{
//         mysqli_query($koneksi, "INSERT INTO data_pelunasan_cobahampirfinishjuga SELECT * FROM data_booking_cobahampirfinishjuga WHERE uniqid = '$id'");
//         mysqli_query($koneksi,"DELETE FROM data_booking_cobahampirfinishjuga WHERE uniqid = '$id'");
//         mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu3 = CURRENT_TIMESTAMP, tambah3 = '$tambah3',gambar3 ='$gambar3',judul2 = '$judul2'  WHERE uniqid = '$id'");
    
//         if ( mysqli_multi_query($koneksi, $pindahdata) )
//         {
//         // echo 'Data Baru telah ditambahkan';
//         }
//     }
//     //////////////////////////////////////////////////////////
//     //////////////////////////////////////////////////////////
    
    
//     return mysqli_affected_rows($koneksi);
// }
// ===============================
function editlunas($data){
    global $koneksi;
    
    $id = $data["id"];
    $link = htmlspecialchars($data["link"]);

    $pindahdata = "UPDATE data_tracking_coba SET linkpreview = '$link' WHERE id = '$id'";
   
    if ( mysqli_query($koneksi, $pindahdata) ){

    echo 'Data Baru telah ditambahkan';

    }
    
    return mysqli_affected_rows($koneksi);
}
// ===============================
function overtime($data){
    global $koneksi;
    
    $id = $data["id"];
    $harga = htmlspecialchars($data["hargaovr"]);
    $waktu = htmlspecialchars($data["waktuovr"]);
    $hargasebelumnyaovr = htmlspecialchars($data["harga"]);
    $hargasebelumnyadp = htmlspecialchars($data["hargasdp"]);
    // $waktusebelumnya = htmlspecialchars($data["waktu"]);
    // $buktiovr = htmlspecialchars($data["hargaovr"]);

    $bukti = uploadOvrTf();
    if(!$bukti){
    return false;
    }

    $pindahdata = "UPDATE data_booking_cobahampirfinishjuga SET 
                hargasetelahdp = '$hargasebelumnyadp'+'$harga',
                harga = '$hargasebelumnyaovr'+'$harga',
                tambah_waktu = '$waktu',
                bukti_lunas = '$bukti',
                catatan = '(overtime)'
                WHERE id = '$id'";
   
    if ( mysqli_query($koneksi, $pindahdata) ){
    
    echo '<script type="text/javascript">';
    echo 'alert("Data telah terkirim");';
    echo 'window.location = "overtime.php";';
    echo '</script>';
    
    }
    
    return mysqli_affected_rows($koneksi);
}
// ===============================
function edithistory($data){
    global $koneksi;
    
    $id = $data["id"];
    $link = htmlspecialchars($data["link"]);

    $pindahdata = "UPDATE data_tracking_coba SET linkfinal = '$link' WHERE id = '$id'";
   
    if ( mysqli_query($koneksi, $pindahdata) ){

    echo 'Data Baru telah ditambahkan';

    }
    
    return mysqli_affected_rows($koneksi);
}
// ===============================
function delete($id){
    global $koneksi;
    
    $sql_img = mysqli_query($koneksi,"SELECT bukti_transfer FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid = '$id'");
    //$rsPic = mysqli_query($koneksi,$sql_img);
    $rowPic = mysqli_fetch_assoc($sql_img);
    $bukti = $rowPic['bukti_transfer'];
    unlink (__DIR__."/assets/img/data_konfirmasi/".$bukti);
 

    mysqli_query($koneksi,"DELETE FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid = '$id'");
    mysqli_query($koneksi,"DELETE FROM data_tracking_coba WHERE uniqid = '$id'");
    
    $result = true;

    if ( $result ){
     
    echo 'Data telah dihapus'; 

    }
    
    return mysqli_affected_rows($koneksi);
}
function deletebooking($id){
    global $koneksi;
    
    $sql_img = mysqli_query($koneksi,"SELECT bukti_transfer FROM data_booking_cobahampirfinishjuga WHERE uniqid = '$id'");
    //$rsPic = mysqli_query($koneksi,$sql_img);
    $rowPic = mysqli_fetch_assoc($sql_img);
    $bukti = $rowPic['bukti_transfer'];
    unlink (__DIR__."/assets/img/data_konfirmasi/".$bukti);
 

    mysqli_query($koneksi,"DELETE FROM data_booking_cobahampirfinishjuga WHERE uniqid = '$id'");
    mysqli_query($koneksi,"DELETE FROM data_tracking_coba WHERE uniqid = '$id'");
    
    $result = true;

    if ( $result ){
     
    echo 'Data telah dihapus'; 

    }
    
    return mysqli_affected_rows($koneksi);
}
function deletepelunasan($id){
    global $koneksi;
    
    $sql_img = mysqli_query($koneksi,"SELECT bukti_transfer FROM data_pelunasan_cobahampirfinishjuga WHERE uniqid = '$id'");
    //$rsPic = mysqli_query($koneksi,$sql_img);
    $rowPic = mysqli_fetch_assoc($sql_img);
    $bukti = $rowPic['bukti_transfer'];
    unlink (__DIR__."/assets/img/data_konfirmasi/".$bukti);
 

    mysqli_query($koneksi,"DELETE FROM data_pelunasan_cobahampirfinishjuga WHERE uniqid = '$id'");
    mysqli_query($koneksi,"DELETE FROM data_tracking_coba WHERE uniqid = '$id'");
    
    $result = true;

    if ( $result ){
     
    echo 'Data telah dihapus'; 

    }
    
    return mysqli_affected_rows($koneksi);
}
function deletehistory($id){
    global $koneksi;
    
    $sql_img = mysqli_query($koneksi,"SELECT bukti_transfer FROM data_history WHERE uniqid = '$id'");
    //$rsPic = mysqli_query($koneksi,$sql_img);
    $rowPic = mysqli_fetch_assoc($sql_img);
    $bukti = $rowPic['bukti_transfer'];
    unlink (__DIR__."/assets/img/data_konfirmasi/".$bukti);
 

    mysqli_query($koneksi,"DELETE FROM data_history WHERE uniqid = '$id'");
    mysqli_query($koneksi,"DELETE FROM data_tracking_coba WHERE uniqid = '$id'");
    
    $result = true;

    if ( $result ){
     
    echo 'Data telah dihapus'; 

    }
    
    return mysqli_affected_rows($koneksi);
}

function lunas($id){
    global $koneksi;
    $data = mysqli_query($koneksi,"SELECT tanggal FROM data_booking_cobahampirfinishjuga WHERE uniqid ='$id'");
    $d = mysqli_fetch_array($data); 
    
    $tambah3 = "sesi photoshoot selesai";
    $gambar3 = "correct.png";
    $judul2 = "Photoshoot";

    $date = $d["tanggal"];
    $newDate = date("Y-m-d", strtotime($date));
    
    $tambah5 ="bukti pelunasan anda telah terkonfirmasi oleh admin";
    $gambar5 ="correct.png";
    $judul4 = "Pelunasan Terkonfirmasi";
    //$date = "SELECT DATE_FORMAT(STR_TO_DATE(`tanggal`, '%d-%m-%Y'), '%Y-%m-%d') as tanggal FROM data_pelunasan_cobahampirfinishjuga";

    //mysqli_query($koneksi,"UPDATE data_pelunasan_cobahampirfinishjuga SET tanggal ='$newDate' WHERE uniqid = '$id'");
    mysqli_query($koneksi, "INSERT INTO data_history SELECT * FROM data_booking_cobahampirfinishjuga WHERE uniqid = '$id'");
    mysqli_query($koneksi,"DELETE FROM data_booking_cobahampirfinishjuga WHERE uniqid = '$id'");
    mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu3 = CURRENT_TIMESTAMP, tambah3 = '$tambah3',gambar3 ='$gambar3',judul2 = '$judul2'  WHERE uniqid = '$id'");
    mysqli_query($koneksi, "UPDATE data_tracking_coba SET waktu5 = CURRENT_TIMESTAMP,tambah5 = '$tambah5',gambar5 ='$gambar5',judul4 = '$judul4'  WHERE uniqid = '$id'");
   
    if ( true ){

    

    }
    


    return mysqli_affected_rows($koneksi);
}
function uploadmultiple(){
        // // ===============================
        // echo "<h2>Upload " . count($_FILES['file']['name']) . " files</h2>";
        // $dir = "uploads/";
        // if(!is_dir($dir)){
        // mkdir($dir);
        // }
        // if(!empty($_FILES['file']['name'])){
        // for($x=0; $x<count($_FILES['file']['name']); $x++){
        //     $targetFile = $dir.$_FILES['file']['name'][$x];
        //     $input_file = $_FILES['file']['tmp_name'][$x];
        //     echo "<p>" . $_FILES['file']['name'][$x] . "</p>";
        //     $move = move_uploaded_file($input_file, $targetFile);
        // }
        // if($move){
        //     // echo "<script>alert('File berhasil dikirim..');window.history.go(-1);</script>";
        // }else{
        //     // echo "<script>alert('File gagal dikirim !!!');window.history.go(-1);</script>";
        // }
        // }else{
        // echo "No files selected";
        // }
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
    $extensigambarValid = ['jpg','jpeg','png','zip','rar'];
    $extensigambar = explode('.',$namaFile);
    $extensigambar = strtolower(end($extensigambar));

    if(!in_array($extensigambar,$extensigambarValid)){
        echo "<script>
        alert('ekstensi tidak sesuai!');
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
    $random = random_bytes(3);
    $uniqid = (bin2hex($random));
    $namaFileBaru = 'cetak-';
    $namaFileBaru .= $uniqid;
    $namaFileBaru .= '.';
    $namaFileBaru .= $extensigambar;
    
    if (move_uploaded_file($tmpName,dirname(__FILE__).'/assets/img/data_cetakonly/'.$namaFileBaru)){
       // echo "upload";
    }else{
      //  echo " gagal";
    }

    return $namaFileBaru;
}



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
    $random = random_bytes(3);
    $uniqid = (bin2hex($random));
    $namaFileBaru = 'dptf-';
    $namaFileBaru .= $uniqid;
    $namaFileBaru .= '.';
    $namaFileBaru .= $extensigambar;
    
    if (move_uploaded_file($tmpName,dirname(__FILE__).'/assets/img/data_konfirmasi/'.$namaFileBaru)){
       // echo "upload";
    }else{
      //  echo " gagal";
    }

    return $namaFileBaru;

}
// ===============================
function uploadOvrTf(){
    $namaFile = $_FILES['buktiovr']['name'];
    $ukuranFile = $_FILES['buktiovr']['size'];
    $error = $_FILES['buktiovr']['error'];
    $tmpName = $_FILES['buktiovr']['tmp_name'];

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
    $random = random_bytes(3);
    $uniqid = (bin2hex($random));
    $namaFileBaru = 'ovrtime-';
    $namaFileBaru .= $uniqid;
    $namaFileBaru .= '.';
    $namaFileBaru .= $extensigambar;
    
    if (move_uploaded_file($tmpName, dirname(__FILE__).'/assets/img/data_overtime/'.$namaFileBaru)){
       // echo "upload";
    }else{
       // echo " gagal";
    }

    return $namaFileBaru;

}

?>