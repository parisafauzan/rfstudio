<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rfg_redirect('index.php');
}
if (!rfg_check_csrf()) {
    http_response_code(403);
    exit('Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.');
}
try {
    rfg_require_ready();
    if (!rfg_is_connected()) {
        throw new RuntimeException('Google Drive belum terhubung.');
    }
    if (empty($_FILES['photos']) || !isset($_FILES['photos']['name']) || !is_array($_FILES['photos']['name'])) {
        throw new RuntimeException('Pilih foto terlebih dahulu. Jika file terlalu besar, periksa nilai post_max_size di PHP.');
    }
    $cfg = rfg_get_config();
    $maxFiles = isset($cfg['max_upload_files']) ? (int) $cfg['max_upload_files'] : 10;
    $maxBytes = isset($cfg['max_upload_file_bytes']) ? (int) $cfg['max_upload_file_bytes'] : 20971520;
    $maxTotalBytes = isset($cfg['max_upload_total_bytes']) ? (int) $cfg['max_upload_total_bytes'] : 104857600;
    $count = count($_FILES['photos']['name']);
    if ($count > $maxFiles) {
        throw new RuntimeException('Maksimal ' . $maxFiles . ' foto per sekali upload.');
    }
    $batchBytes = array_sum(array_map('intval', $_FILES['photos']['size']));
    if ($batchBytes > $maxTotalBytes) {
        throw new RuntimeException('Total ukuran upload maksimal ' . round($maxTotalBytes / 1048576) . ' MB per batch.');
    }
    $allowed = array(
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    );
    if (!class_exists('finfo')) {
        throw new RuntimeException('Ekstensi fileinfo PHP dibutuhkan untuk memvalidasi tipe gambar.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $success = 0;
    $errors = array();
    for ($i = 0; $i < $count; $i++) {
        $originalName = (string) $_FILES['photos']['name'][$i];
        $tmpName = (string) $_FILES['photos']['tmp_name'][$i];
        $uploadError = (int) $_FILES['photos']['error'][$i];
        if ($uploadError !== UPLOAD_ERR_OK) {
            $errors[] = $originalName . ': upload PHP gagal (kode ' . $uploadError . ').';
            continue;
        }
        if (!is_uploaded_file($tmpName) || !is_file($tmpName)) {
            $errors[] = $originalName . ': file upload tidak valid.';
            continue;
        }
        $size = (int) filesize($tmpName);
        if ($size < 1 || $size > $maxBytes) {
            $errors[] = $originalName . ': ukuran harus di antara 1 byte dan ' . round($maxBytes / 1048576) . ' MB.';
            continue;
        }
        $mime = $finfo->file($tmpName);
        if (!isset($allowed[$mime])) {
            $errors[] = $originalName . ': format tidak didukung. Gunakan JPG, PNG, atau WebP.';
            continue;
        }
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = preg_replace('/[^A-Za-z0-9._ -]/', '_', $baseName);
        $baseName = trim($baseName, " .\t\n\r\0\x0B");
        if ($baseName === '') {
            $baseName = 'foto';
        }
        $name = date('Ymd_His') . '_' . substr(bin2hex(random_bytes(3)), 0, 6) . '_' . $baseName . '.' . $allowed[$mime];
        try {
            rfg_upload_file($tmpName, $name, $mime);
            $success++;
        } catch (Exception $e) {
            $errors[] = $originalName . ': ' . $e->getMessage();
        }
    }
    $_SESSION['rfgallery_flash'] = array(
        'type' => empty($errors) ? 'success' : ($success > 0 ? 'warning' : 'danger'),
        'text' => 'Upload berhasil: ' . $success . ' file.' . (empty($errors) ? '' : ' Perlu diperiksa: ' . implode(' | ', $errors)),
    );
} catch (Exception $e) {
    $_SESSION['rfgallery_flash'] = array('type' => 'danger', 'text' => $e->getMessage());
}
rfg_redirect('index.php');
