<?php
require_once __DIR__ . '/gallery-customer-lib.php';

$tmp = '';
$mode = isset($_GET['mode']) ? (string) $_GET['mode'] : 'thumb';
$photo = null;
try {
    $sessionId = isset($_GET['session']) ? (int) $_GET['session'] : 0;
    $photoId = isset($_GET['photo']) ? (int) $_GET['photo'] : 0;
    if (rfg_customer_access_email($sessionId) === '') throw new RuntimeException('Akses galeri berakhir.');
    $photo = rfg_customer_photo_row($sessionId, $photoId);
    if (!$photo) throw new RuntimeException('Foto tidak ditemukan.');
    rfg_require_ready();
    if (!rfg_is_connected()) throw new RuntimeException('Google Drive belum terhubung.');

    if ($mode === 'thumb') {
        $thumb = rfg_thumbnail_content($photo['drive_file_id'], $photo['drive_parent_folder_id']);
        header('Content-Type: ' . $thumb['content_type']);
        header('Cache-Control: private, max-age=900');
        header('X-Content-Type-Options: nosniff');
        echo $thumb['body'];
        exit;
    }

    $tmp = tempnam(RFG_PRIVATE_DIR, 'rfg-customer-');
    if ($tmp === false) { $tmp = ''; throw new RuntimeException('File sementara tidak dapat dibuat.'); }
    $meta = $mode === 'download'
        ? rfg_download_to_file($photo['drive_file_id'], $tmp, $photo['drive_parent_folder_id'])
        : rfg_download_original_to_file($photo['drive_file_id'], $tmp, $photo['drive_parent_folder_id']);
    $filename = basename(isset($meta['name']) ? $meta['name'] : $photo['filename']);
    $ascii = preg_replace('/[^A-Za-z0-9._-]/', '_', $filename);
    header('Content-Type: ' . $meta['mimeType']);
    header('Content-Length: ' . filesize($tmp));
    header('Content-Disposition: ' . ($mode === 'download' ? 'attachment' : 'inline') . '; filename="' . $ascii . '"; filename*=UTF-8\'\'' . rawurlencode($filename));
    header('Cache-Control: private, max-age=300');
    header('X-Content-Type-Options: nosniff');
    header('Vary: Cookie');
    readfile($tmp);
} catch (Throwable $e) {
    if (is_array($photo) && !empty($photo['drive_file_id'])) {
        $missingMessage = strtolower($e->getMessage());
        if (strpos($missingMessage, 'not found') !== false || strpos($missingMessage, 'tidak ditemukan') !== false || strpos($missingMessage, 'trash') !== false) {
            try { rfg_gallery_mark_photo_missing_by_drive_id($photo['drive_file_id']); } catch (Throwable $ignored) {}
        }
    }
    http_response_code(404);
    header('Cache-Control: no-store');
    if ($mode === 'thumb') {
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="420" height="300"><rect width="100%" height="100%" fill="#eef1f4"/><text x="50%" y="48%" text-anchor="middle" fill="#69727d" font-family="Arial" font-size="16">Foto tidak tersedia</text><text x="50%" y="58%" text-anchor="middle" fill="#8a939e" font-family="Arial" font-size="12">Masa penyimpanan mungkin telah berakhir</text></svg>';
    } else {
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Foto tidak dapat dibuka. Foto mungkin sudah dihapus karena masa penyimpanan berakhir.';
    }
} finally {
    if ($tmp !== '' && is_file($tmp)) @unlink($tmp);
}
