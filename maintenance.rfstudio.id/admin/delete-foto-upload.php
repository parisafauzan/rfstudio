<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfstudio";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['uniqid_client'])) {
    $id = $_GET['id'];
    $uniqid_client = $_GET['uniqid_client'];

    // Ambil nama file dari database
    $query = "SELECT nama_file FROM data_upload_foto_studio_admin WHERE id = ? AND uniqid_client = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $id, $uniqid_client);
    $stmt->execute();
    $stmt->bind_result($nama_file);
    $stmt->fetch();
    $stmt->close();

    if ($nama_file) {
        $filePath = "admin-upload/" . $nama_file;

        // Hapus file dari folder
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus data dari database
        $deleteQuery = "DELETE FROM data_upload_foto_studio_admin WHERE id = ? AND uniqid_client = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("ss", $id, $uniqid_client);
        if ($stmt->execute()) {
            echo "File dan data berhasil dihapus.";
        } else {
            echo "Gagal menghapus data.";
        }
        $stmt->close();
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "Parameter tidak lengkap.";
}

$conn->close();
?>
