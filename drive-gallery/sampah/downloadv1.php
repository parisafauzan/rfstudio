<?php
require_once __DIR__ . '/bootstrap.php';

try {
    rfg_require_ready();
    if (!rfg_is_connected()) {
        throw new RuntimeException('Google Drive belum terhubung.');
    }
    $id = isset($_GET['id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_GET['id']) : '';
    if ($id === '') {
        http_response_code(400);
        exit('ID foto tidak valid.');
    }
    $meta = rfg_file_metadata($id);
    $maxBytes = 20 * 1024 * 1024;
    if (isset($meta['size']) && (int) $meta['size'] > $maxBytes) {
        http_response_code(413);
        exit('Foto ini melebihi batas download awal 20 MB. Hubungi pengelola galeri.');
    }
    $tmp = tempnam(RFG_PRIVATE_DIR, 'rfg-download-');
    if ($tmp === false) {
        throw new RuntimeException('Tidak dapat membuat file sementara.');
    }
    try {
        rfg_download_to_file($id, $tmp);
        $filename = basename($meta['name']);
        $asciiFilename = preg_replace('/[^A-Za-z0-9._-]/', '_', $filename);
        header('Content-Type: ' . $meta['mimeType']);
        header('Content-Length: ' . filesize($tmp));
        header('Content-Disposition: attachment; filename="' . $asciiFilename . '"; filename*=UTF-8\'\'' . rawurlencode($filename));
        header('X-Content-Type-Options: nosniff');
        readfile($tmp);
    } finally {
        if (is_file($tmp)) {
            @unlink($tmp);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo 'Download gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
