<?php
require_once __DIR__ . '/bootstrap.php';
try {
    rfg_require_ready();
    $id = isset($_GET['id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_GET['id']) : '';
    if ($id === '') {
        http_response_code(400);
        exit;
    }
    $thumb = rfg_thumbnail_content($id);
    header('Content-Type: ' . $thumb['content_type']);
    header('Cache-Control: private, max-age=1800');
    header('X-Content-Type-Options: nosniff');
    echo $thumb['body'];
} catch (Exception $e) {
    http_response_code(404);
    header('Content-Type: image/svg+xml; charset=utf-8');
    header('Cache-Control: no-store');
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="320" height="220"><rect width="100%" height="100%" fill="#f1f5f9"/><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" fill="#64748b" font-family="Arial" font-size="14">Preview tidak tersedia</text></svg>';
}
