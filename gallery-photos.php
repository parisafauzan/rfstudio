<?php
require_once __DIR__ . '/gallery-customer-lib.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    $sessionId = isset($_GET['session']) ? (int) $_GET['session'] : 0;
    $category = isset($_GET['category']) && $_GET['category'] === 'edited' ? 'edited' : 'original';
    $offset = isset($_GET['offset']) ? max(0, (int) $_GET['offset']) : 0;
    $all = isset($_GET['all']) && (string) $_GET['all'] === '1';
    $email = rfg_customer_access_email($sessionId);
    if ($email === '') throw new RuntimeException('Akses galeri berakhir. Cari kembali galeri menggunakan email booking.');
    $gallery = rfg_customer_gallery_for_email($sessionId, $email);
    if (!$gallery || $gallery['status'] !== 'active') throw new RuntimeException('Galeri tidak tersedia.');
    $page = rfg_customer_photo_page($gallery, $category, false, $offset, $all ? 1000 : 10);
    $request = rfg_gallery_edit_request_for_session($sessionId);
    $locked = $request && in_array($request['status'], array('editing', 'completed'), true);
    $selected = rfg_gallery_edit_selected_drive_map($sessionId);
    $html = '';
    foreach ($page['files'] as $photo) $html .= rfg_customer_photo_card_html($photo, $sessionId, $category, $selected, $locked);
    echo json_encode(array('success' => true, 'html' => $html, 'total' => $page['total'], 'loaded' => $page['loaded'], 'has_more' => $page['has_more']), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(422);
    echo json_encode(array('success' => false, 'message' => $e->getMessage()), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
