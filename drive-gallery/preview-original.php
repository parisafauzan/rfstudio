<?php
require_once __DIR__ . '/bootstrap.php';

$tmp = '';
try {
    rfg_require_ready();
    if (!rfg_is_connected()) throw new RuntimeException('Google Drive belum terhubung.');

    $id = isset($_GET['id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_GET['id']) : '';
    $folderId = isset($_GET['folderId']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_GET['folderId']) : '';
    if ($id === '' || $folderId === '') {
        http_response_code(400);
        exit('ID foto atau folder tidak valid.');
    }

    // The downloader validates the image MIME type and direct parent before retrieving original bytes.
    $tmp = tempnam(RFG_PRIVATE_DIR, 'rfg-preview-');
    if ($tmp === false) { $tmp = ''; throw new RuntimeException('Tidak dapat membuat file sementara preview.'); }

    $meta = rfg_download_original_to_file($id, $tmp, $folderId);
    $filename = basename(isset($meta['name']) ? $meta['name'] : 'foto');
    $asciiFilename = preg_replace('/[^A-Za-z0-9._-]/', '_', $filename);

    header('Content-Type: ' . $meta['mimeType']);
    header('Content-Length: ' . filesize($tmp));
    header('Content-Disposition: inline; filename="' . $asciiFilename . '"; filename*=UTF-8\'\'' . rawurlencode($filename));
    header('Cache-Control: private, max-age=300');
    header('X-Content-Type-Options: nosniff');
    header('Vary: Cookie');
    readfile($tmp);
} catch (Throwable $e) {
    if (!headers_sent()) {
        $message = $e->getMessage();
        $status = (strpos($message, 'terlalu besar') !== false) ? 413 : 500;
        http_response_code($status);
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: no-store');
    }
    echo 'Preview gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    if (function_exists('rfg_log')) rfg_log('ORIGINAL_PREVIEW_FAILED message=' . $e->getMessage());
} finally {
    if ($tmp !== '' && is_file($tmp)) @unlink($tmp);
}
