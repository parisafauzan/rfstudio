<!-- Buat file ZIP -->
<?php
// Mulai output buffering untuk menghindari masalah dengan header
ob_start();
    // Koneksi ke database
    require "function.php";

    // Cek koneksi
    if ($db->connect_error) {
        die('Koneksi ke database gagal.');
    }

    // Ambil data gambar dari database
    $query = "SELECT nama_file FROM data_upload_foto_studio_admin";
    $result = $db->query($query);

    $images = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $images[] = $row['nama_file'];
        }
    } else {
        die('Tidak ada gambar ditemukan.');
    }

    $zip = new ZipArchive();
    $zipFilename = 'images.zip';
    // Cek jika file ZIP dapat dibuat
if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Tidak bisa membuat file ZIP.");
}

// Menambahkan gambar ke file ZIP
foreach ($images as $image) {
    $imagePath = 'admin-upload/' . $image; // Lokasi gambar di server
    if (file_exists($imagePath)) {
        $zip->addFile($imagePath, $image); // Menambahkan gambar ke ZIP
    }
}

// Menutup file ZIP
$zip->close();

// Mengirim file ZIP untuk diunduh
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
header('Content-Length: ' . filesize($zipFilename));

// Membaca file ZIP dan mengirimkan ke browser
readfile($zipFilename);

// Menghapus file ZIP setelah dikirim
unlink($zipFilename);

// Selesai output buffering
ob_end_flush();
?>