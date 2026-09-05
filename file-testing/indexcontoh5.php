<?php
include 'function.php';

// Hitung jumlah pesan belum dibaca
$query = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM data_kode_foto_dari_client WHERE uniqid_client = 0");
$data  = mysqli_fetch_assoc($query);
$jumlah_notif = $data['jumlah'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Notifikasi Demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="p-4">

  <h3>Contoh Notifikasi 🔔</h3>

  <a href="pesan.php" class="position-relative">
    <i class="bi bi-bell" style="font-size: 32px;"></i>
    <?php if($jumlah_notif > 0) { ?>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?= $jumlah_notif ?>
      </span>
    <?php } ?>
  </a>

</body>
</html>
