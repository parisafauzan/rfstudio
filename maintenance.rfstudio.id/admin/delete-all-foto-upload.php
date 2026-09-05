<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfstudio";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['uniqid_client'])) {
    $uniqid_client = $_GET['uniqid_client'];

    // Ambil semua file dari database
    $query = "SELECT nama_file FROM data_upload_foto_studio_admin WHERE uniqid_client = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $uniqid_client);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $filePath = "admin-upload/" . $row['nama_file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    $stmt->close();

    // Hapus semua data dari database
    $deleteQuery = "DELETE FROM data_upload_foto_studio_admin WHERE uniqid_client = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("s", $uniqid_client);
    if ($stmt->execute()) {
        echo "Semua file dan data berhasil dihapus.";
    } else {
        echo "Gagal menghapus data.";
    }
    $stmt->close();
} else {
    echo "Parameter tidak lengkap.";
}

$conn->close();
?>
