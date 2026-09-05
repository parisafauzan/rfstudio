<?php
$targetDir = "admin-upload/";
$success = true;
$response = [];

// Mengecek apakah folder upload ada, jika tidak maka buat foldernya
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Mengecek apakah ada gambar yang di-upload
if (isset($_FILES['images']) && isset($_POST['categories'])) {
    $images = $_FILES['images'];
    $categories = $_POST['categories'];

    foreach ($images['tmp_name'] as $index => $tmpName) {
        $fileName = basename($images['name'][$index]);
        $filePath = $targetDir . $fileName;
        $category = $categories[$index];

        // Pindahkan file ke folder upload
        if (move_uploaded_file($tmpName, $filePath)) {
            // Simpan informasi ke database (misalnya di MySQL)
            // Gantilah dengan kode penyimpanan ke database sesuai struktur tabel kamu.
            $db = new mysqli('localhost', 'root', '', 'rfstudio');
            if ($db->connect_error) {
                $response = ['success' => false, 'message' => 'Koneksi ke database gagal.'];
                die(json_encode($response));
            }

            $stmt = $db->prepare("INSERT INTO data_admin_studio_upload (nama_file, kategori, waktu_upload) VALUES (?, ?, NOW())");
            $stmt->bind_param('ss', $fileName, $category);
            if (!$stmt->execute()) {
                $response = ['success' => false, 'message' => 'Gagal menyimpan data ke database.'];
            }
            $stmt->close();
            $db->close();
        } else {
            $success = false;
            $response = ['success' => false, 'message' => 'Gagal meng-upload file: ' . $fileName];
            break;
        }
    }
} else {
    $success = false;
    $response = ['success' => false, 'message' => 'Tidak ada file yang di-upload.'];
}

if ($success) {
    $response = ['success' => true, 'message' => 'File berhasil di-upload dan disimpan!'];
}

echo json_encode($response);
?>
