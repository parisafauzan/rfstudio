<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/gallery-render.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'GET') throw new RuntimeException('Metode request tidak valid.');
    rfg_require_ready();
    if (!rfg_is_connected()) throw new RuntimeException('Google Drive belum terhubung.');
    $folderId = isset($_GET['folderId']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_GET['folderId']) : '';
    $offset = isset($_GET['offset']) ? max(0, (int) $_GET['offset']) : 0;
    $nameFilter = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
    $all = isset($_GET['all']) && (string) $_GET['all'] === '1';
    if ($folderId === '') throw new RuntimeException('Folder foto tidak valid.');
    $path = rfg_folder_path($folderId);
    $folderName = strtolower((string) $path[count($path) - 1]['name']);
    if (!in_array($folderName, array('originals', 'edited'), true) || count($path) < 2) throw new RuntimeException('Folder ini bukan folder foto booking.');
    $session = rfg_gallery_session_find_by_folder($path[count($path) - 2]['id']);
    if (!$session) throw new RuntimeException('Folder belum terhubung ke gallery_sessions.');
    $category = $folderName === 'originals' ? 'original' : 'edited';
    $limit = $all ? 1000 : 10;
    $page = rfg_gallery_cached_photo_list((int) $session['id'], $folderId, $category, $nameFilter, $offset, $limit);
    $selected = rfg_gallery_edit_selected_drive_map((int) $session['id']);
    $shareStates = rfg_share_states_read();
    $html = '';
    foreach ($page['files'] as $file) $html .= rfg_admin_photo_card_html($file, $folderId, $shareStates, $selected);
    $nextOffset = $offset + count($page['files']);
    echo json_encode(array(
        'success' => true,
        'html' => $html,
        'total' => (int) $page['total'],
        'loaded' => $nextOffset,
        'has_more' => $nextOffset < (int) $page['total'],
    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(422);
    echo json_encode(array('success' => false, 'message' => $e->getMessage()), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
