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
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    body {
        font-family: Arial, sans-serif;
        background: #ffffff;
        margin: 0;
        padding: 0;
    }
    .main {
        max-width: 500px;
        margin: auto;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }
    .header {
        background: #212529;
        color: #ffffff;
        padding: 12px;
        text-align: center;
        font-size: 20px;
        border-radius: 6px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 14px;
    }
    td {
        padding: 6px 3px;
    }
    .label {
        color: #444;
        width: 50%;
    }
    .value {
        text-align: right;
        font-weight: bold;
        color: #000;
    }
    .note-box {
        margin-top: 15px;
        font-size: 13px;
        background: #f7f7f7;
        padding: 12px;
        border-radius: 6px;
        line-height: 1.5;
        border: 1px solid #ddd;
    }
    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 13px;
        color: #555;
    }
    a { color: #212529; text-decoration: underline; }
</style>
</head>

<body>

<div class="main">

    <div class="header">
        Rizal Foto Studio
    </div>

    <table>
        <tr><td class="label">Nama</td><td class="value">'.$nama.'</td></tr>
        <tr><td class="label">No. Telp</td><td class="value">'.$notelp.'</td></tr>
        <tr><td class="label">Email</td><td class="value">'.$email.'</td></tr>
        <tr><td class="label">Tanggal</td><td class="value">'.$date.'</td></tr>
        <tr><td class="label">Jam</td><td class="value">'.$jam.'</td></tr>
        <tr><td class="label">Jumlah Orang</td><td class="value">'.$jumlahorangdewasa.' & '.$jumlahoranganak.'</td></tr>
        <tr><td class="label">Kode Track Progress</td><td class="value">'.$uniqid.'</td></tr>
        <tr><td class="label">Lokasi Studio</td><td class="value">'.$studio.'</td></tr>
        <tr><td class="label">Package</td><td class="value">'.$package.' '.$jumlah.'</td></tr>
        <tr><td class="label">Cetak Foto</td><td class="value">'.$cetakfoto.'</td></tr>
        <tr><td class="label">Penambahan Waktu</td><td class="value">'.$penambahanwaktu.'</td></tr>
        <tr><td class="label">Penambahan Makeup</td><td class="value">'.$penambahanmakeup.'</td></tr>
        <tr><td class="label">Penambahan Hairdo</td><td class="value">'.$penambahanhairdo.'</td></tr>
        <tr><td class="label">Penambahan Cetak</td><td class="value">'.$nambahcetak.'</td></tr>
        <tr><td class="label">Catatan</td><td class="value">'.$catatanemail.'</td></tr>
        <tr><td class="label">Status</td><td class="value">'.$ketbayar.'</td></tr>
    </table>

    <div class="note-box">
        <strong>*Note :</strong><br>
        - Mohon datang lebih awal dari jam bookingan.<br>
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

