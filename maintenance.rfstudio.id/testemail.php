<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Jika form disubmit
if (isset($_POST['send_email'])) {
    require 'vendor/autoload.php'; // Memuat autoloader jika menggunakan Composer

    

    $mail = new PHPMailer(true);
    
    try {
        // Mengatur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Server SMTP Anda (misal Gmail)
        $mail->SMTPAuth = true;
        $mail->Username = 'rizalfotostudio2022@gmail.com'; // Ganti dengan email pengirim
        $mail->Password = 'dpbgrqoftjhvbcev'; // Ganti dengan password atau app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Pengaturan pengirim dan penerima
        $mail->setFrom('rizalfotostudio2022@gmail.com', 'Nama Pengirim');
        $mail->addAddress($_POST['recipient_email'], 'Nama Penerima'); // Email penerima dari form

        // Isi email
        $mail->isHTML(true);
        $mail->Subject = $_POST['email_subject']; // Subjek dari form
        $mail->Body    = $_POST['email_body'];    // Isi email dari form
        $mail->AltBody = strip_tags($_POST['email_body']); // Versi teks biasa

        // Mengirim email
        $mail->send();
        $status = "Email pengingat telah dikirim!";
    } catch (Exception $e) {
        $status = "Pesan gagal dikirim. Error: {$mail->ErrorInfo}";
    }
}
?>

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

<div class="card" style="
    background:#ffffff;
    border:1px solid #dcdcdc;
    border-radius:10px;
    padding:20px;
    margin-bottom:20px;
">

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