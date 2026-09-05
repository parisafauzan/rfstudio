<?php
$conn = new mysqli("localhost", "root", "", "data_pribadi_rizal");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "Akses tidak valid. Request Method: " . $_SERVER['REQUEST_METHOD'];
  exit;
}

$folder_name = trim($_POST['folder_name']);
if ($folder_name === '') {
  echo "Nama folder tidak boleh kosong.";
  exit;
}

$safe_name = $conn->real_escape_string($folder_name);
$query = "INSERT INTO data_folder (tipe, nama_folder) VALUES ('folder', '$safe_name')";

if ($conn->query($query)) {
  header("Location: home.php");
  exit();
} else {
  echo "Gagal menyimpan folder: " . $conn->error;
}
?>
