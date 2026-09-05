<?php
session_start();
if (!isset($_SESSION['email_family'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "data_pribadi_rizal");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$folder_id = $_GET['folder_id'] ?? '';
if (!$folder_id) {
    echo "Folder tidak ditemukan.";
    exit();
}

// Ambil nama folder
$stmt = $conn->prepare("SELECT nama_folder FROM data_folder WHERE id = ?");
$stmt->bind_param("i", $folder_id);
$stmt->execute();
$stmt->bind_result($nama_folder);
$stmt->fetch();
$stmt->close();

if (!$nama_folder) {
    echo "Folder tidak ditemukan.";
    exit();
}

// Ambil isi folder
$result = $conn->query("SELECT * FROM data_upload WHERE uniqid = '$folder_id' ORDER BY waktu_upload DESC");

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Isi Folder: <?php echo htmlspecialchars($nama_folder); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
  <style>
    .tile { background: #fff; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.05); cursor: pointer; }
    .tile:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .icon { font-size: 40px; color: #5f6368; }
    .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 20px; }
  </style>
</head>
<body>

<div class="container py-4">
  <h4>Isi Folder: <?php echo htmlspecialchars($nama_folder); ?></h4>
  <a href="rizal-storage.php" class="btn btn-secondary mb-3">Kembali ke Beranda</a>

  <div class="grid-container">
    <?php while ($file = $result->fetch_assoc()): ?>
      <div class="tile">
        <div class="icon"><i class="fas fa-file"></i></div>
        <div class="mt-2"><?php echo htmlspecialchars($file['nama_file']); ?></div>
        <small class="text-muted"><?php echo $file['waktu_upload']; ?></small>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
