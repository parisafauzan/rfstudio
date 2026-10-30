<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rfg_redirect('index');
}
if (!rfg_check_csrf()) {
    http_response_code(403);
    exit('Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.');
}
try {
    rfg_require_ready();
    if (!rfg_is_connected()) throw new RuntimeException('Google Drive belum terhubung.');
    $fileId = isset($_POST['file_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_POST['file_id']) : '';
    $action = isset($_POST['share_action']) ? (string) $_POST['share_action'] : '';
    if ($fileId === '') throw new RuntimeException('ID foto tidak valid.');
    if ($action === 'enable') {
        $result = rfg_set_link_sharing($fileId, true);
        $_SESSION['rfgallery_flash'] = array(
            'type' => 'warning',
            'text' => 'Link aktif. Siapa pun yang memiliki tautan dapat melihat foto ini: ' . $result['url'],
        );
    } elseif ($action === 'disable') {
        rfg_set_link_sharing($fileId, false);
        $_SESSION['rfgallery_flash'] = array('type' => 'success', 'text' => 'Akses dengan tautan publik dimatikan untuk foto tersebut.');
    } else {
        throw new RuntimeException('Aksi berbagi tidak dikenal.');
    }
} catch (Throwable $e) {
    rfg_log('SHARE_ACTION_FAILED class=' . get_class($e) . ' message=' . $e->getMessage());
    $_SESSION['rfgallery_flash'] = array('type' => 'danger', 'text' => $e->getMessage());
}
rfg_redirect('index');
