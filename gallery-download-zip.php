<?php
require_once __DIR__ . '/gallery-customer-lib.php';

$tmpFiles = array();
$zipPath = '';
try {
    if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') throw new RuntimeException('Metode request tidak valid.');
    if (!rfg_customer_csrf_valid(isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '')) throw new RuntimeException('Sesi formulir berakhir. Muat ulang galeri.');
    if (!class_exists('ZipArchive')) throw new RuntimeException('Ekstensi ZIP belum aktif pada hosting.');
    $sessionId = isset($_POST['gallery_session_id']) ? (int) $_POST['gallery_session_id'] : 0;
    if (rfg_customer_access_email($sessionId) === '') throw new RuntimeException('Akses galeri berakhir. Cari kembali galeri menggunakan email.');
    $ids = isset($_POST['photo_ids']) && is_array($_POST['photo_ids']) ? array_values(array_unique(array_map('intval', $_POST['photo_ids']))) : array();
    $ids = array_values(array_filter($ids, function ($id) { return $id > 0; }));
    $cfg = isset($GLOBALS['RFG_CONFIG']) && is_array($GLOBALS['RFG_CONFIG']) ? $GLOBALS['RFG_CONFIG'] : array();
    $maxFiles = isset($cfg['max_customer_zip_files']) ? max(1, (int) $cfg['max_customer_zip_files']) : 200;
    $maxBytes = isset($cfg['max_customer_zip_bytes']) ? max(1048576, (int) $cfg['max_customer_zip_bytes']) : 524288000;
    if (count($ids) < 1) throw new RuntimeException('Pilih setidaknya satu foto untuk diunduh.');
    if (count($ids) > $maxFiles) throw new RuntimeException('Maksimal ' . $maxFiles . ' foto per ZIP.');
    $photos = array(); $totalBytes = 0;
    foreach ($ids as $id) {
        $photo = rfg_customer_photo_row($sessionId, $id);
        if (!$photo) throw new RuntimeException('Salah satu foto tidak lagi tersedia. Muat ulang galeri.');
        $totalBytes += (int) $photo['size_bytes'];
        if ($totalBytes > $maxBytes) throw new RuntimeException('Total ukuran pilihan terlalu besar. Kurangi jumlah foto lalu coba lagi.');
        $photos[] = $photo;
    }
    $zipPath = tempnam(RFG_PRIVATE_DIR, 'rfg-customer-zip-');
    if ($zipPath === false) { $zipPath = ''; throw new RuntimeException('File ZIP sementara tidak dapat dibuat.'); }
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) throw new RuntimeException('ZIP sementara tidak dapat dibuka.');
    $names = array();
    foreach ($photos as $photo) {
        $tmp = tempnam(RFG_PRIVATE_DIR, 'rfg-customer-photo-');
        if ($tmp === false) throw new RuntimeException('File foto sementara tidak dapat dibuat.');
        $tmpFiles[] = $tmp;
        rfg_download_to_file($photo['drive_file_id'], $tmp, $photo['drive_parent_folder_id']);
        $name = basename($photo['filename']);
        if (isset($names[$name])) $name = pathinfo($name, PATHINFO_FILENAME) . '_' . $photo['id'] . (($ext = pathinfo($name, PATHINFO_EXTENSION)) ? '.' . $ext : '');
        $names[$name] = true;
        if (!$zip->addFile($tmp, $name)) throw new RuntimeException('Foto gagal dimasukkan ke ZIP.');
    }
    if (!$zip->close()) throw new RuntimeException('ZIP tidak berhasil diselesaikan.');
    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($zipPath));
    header('Content-Disposition: attachment; filename="rfstudio-gallery-' . date('Ymd-His') . '.zip"');
    header('X-Content-Type-Options: nosniff');
    readfile($zipPath);
} catch (Throwable $e) {
    http_response_code(400);
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Download ZIP gagal: ' . rfg_customer_h($e->getMessage()) . '</p><p><a href="gallery">Kembali ke galeri</a></p>';
} finally {
    foreach ($tmpFiles as $file) if (is_file($file)) @unlink($file);
    if ($zipPath !== '' && is_file($zipPath)) @unlink($zipPath);
}
