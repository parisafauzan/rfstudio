<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rfg_redirect('index');
}
if (!rfg_check_csrf()) {
    http_response_code(403);
    exit('Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.');
}
$tmpFiles = array();
$zipPath = '';
try {
    rfg_require_ready();
    if (!rfg_is_connected()) {
        throw new RuntimeException('Google Drive belum terhubung.');
    }
    if (!class_exists('ZipArchive')) {
        throw new RuntimeException('Ekstensi PHP ZipArchive belum aktif. Aktifkan ekstensi zip di cPanel atau download satu foto per satu.');
    }
    $cfg = rfg_get_config();
    $maxFiles = isset($cfg['max_selected_files']) ? (int) $cfg['max_selected_files'] : 10;
    $maxBytes = isset($cfg['max_selected_bytes']) ? (int) $cfg['max_selected_bytes'] : 104857600;
    $folderId = isset($_POST['folder_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_POST['folder_id']) : '';
    if ($folderId === '') $folderId = rfg_folder_id();
    rfg_assert_folder_in_gallery($folderId);
    $ids = isset($_POST['selected_ids']) && is_array($_POST['selected_ids']) ? $_POST['selected_ids'] : array();
    $ids = array_values(array_unique(array_filter(array_map(function ($value) {
        return preg_replace('/[^A-Za-z0-9_-]/', '', (string) $value);
    }, $ids))));
    if (count($ids) < 1) {
        throw new RuntimeException('Pilih setidaknya satu foto.');
    }
    if (count($ids) > $maxFiles) {
        throw new RuntimeException('Maksimal ' . $maxFiles . ' foto per ZIP.');
    }
    $metas = array();
    $totalBytes = 0;
    foreach ($ids as $id) {
        $meta = rfg_file_metadata($id, $folderId);
        $size = isset($meta['size']) ? (int) $meta['size'] : 0;
        if ($size <= 0 || $size > 20 * 1024 * 1024) {
            throw new RuntimeException('Salah satu foto melebihi batas 20 MB atau ukurannya tidak diketahui. Download satu per satu.');
        }
        $totalBytes += $size;
        if ($totalBytes > $maxBytes) {
            throw new RuntimeException('Total pilihan melebihi batas ZIP ' . round($maxBytes / 1048576) . ' MB. Kurangi jumlah foto.');
        }
        $metas[] = $meta;
    }
    $zipPath = tempnam(RFG_PRIVATE_DIR, 'rfg-zip-');
    if ($zipPath === false) {
        throw new RuntimeException('Tidak dapat membuat ZIP sementara.');
    }
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new RuntimeException('ZIP sementara tidak dapat dibuat.');
    }
    $usedNames = array();
    foreach ($metas as $meta) {
        $id = $meta['id'];
        $tmp = tempnam(RFG_PRIVATE_DIR, 'rfg-photo-');
        if ($tmp === false) {
            throw new RuntimeException('Tidak dapat membuat file sementara untuk foto.');
        }
        $tmpFiles[] = $tmp;
        rfg_download_to_file($id, $tmp, $folderId);
        $name = basename($meta['name']);
        if (isset($usedNames[$name])) {
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $stem = pathinfo($name, PATHINFO_FILENAME);
            $name = $stem . '_' . substr($id, -5) . ($extension ? '.' . $extension : '');
        }
        $usedNames[$name] = true;
        if (!$zip->addFile($tmp, $name)) {
            throw new RuntimeException('Foto gagal ditambahkan ke ZIP.');
        }
    }
    if (!$zip->close()) {
        throw new RuntimeException('ZIP tidak berhasil diselesaikan.');
    }
    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($zipPath));
    header('Content-Disposition: attachment; filename="rfstudio-foto-terpilih-' . date('Ymd-His') . '.zip"');
    header('X-Content-Type-Options: nosniff');
    readfile($zipPath);
} catch (Exception $e) {
    http_response_code(400);
    echo 'Download pilihan gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . ' <a href="index">Kembali ke galeri</a>';
} finally {
    foreach ($tmpFiles as $file) {
        if (is_file($file)) {
            @unlink($file);
        }
    }
    if ($zipPath !== '' && is_file($zipPath)) {
        @unlink($zipPath);
    }
}
