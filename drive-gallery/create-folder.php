<?php
require_once __DIR__ . '/bootstrap.php';

$isAjax = (isset($_POST['ajax']) && (string) $_POST['ajax'] === '1')
    || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && stripos((string) $_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

function rfg_create_folder_response($payload, $status = 200)
{
    http_response_code((int) $status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$parentId = '';
try {
    if (strtoupper(isset($_SERVER['REQUEST_METHOD']) ? (string) $_SERVER['REQUEST_METHOD'] : 'GET') !== 'POST') {
        throw new RuntimeException('Metode request tidak valid.');
    }
    if (!rfg_check_csrf()) {
        if ($isAjax) rfg_create_folder_response(array('success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang galeri lalu coba lagi.'), 403);
        throw new RuntimeException('Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.');
    }
    rfg_require_ready();
    if (!rfg_is_connected()) throw new RuntimeException('Google Drive belum terhubung.');

    $parentId = isset($_POST['folder_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_POST['folder_id']) : '';
    if ($parentId === '') $parentId = rfg_folder_id();
    rfg_assert_folder_in_gallery($parentId);

    $name = isset($_POST['folder_name']) ? trim((string) $_POST['folder_name']) : '';
    $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name);
    if ($name === '') throw new RuntimeException('Nama folder tidak boleh kosong.');
    if (function_exists('mb_strlen') ? mb_strlen($name, 'UTF-8') > 100 : strlen($name) > 100) {
        throw new RuntimeException('Nama folder maksimal 100 karakter.');
    }
    if (preg_match('/[\\\\]/', $name)) throw new RuntimeException('Nama folder tidak boleh mengandung karakter backslash.');

    $escapedName = rfg_drive_query_escape($name);
    $query = "'" . rfg_drive_query_escape($parentId) . "' in parents and name = '" . $escapedName . "' and mimeType = 'application/vnd.google-apps.folder' and trashed = false";
    $existing = rfg_api_request('GET', 'files', array(
        'q' => $query,
        'spaces' => 'drive',
        'pageSize' => 10,
        'fields' => 'files(id,name,mimeType,parents)',
    ));
    if (!empty($existing['files'][0]['id'])) throw new RuntimeException('Folder dengan nama tersebut sudah ada di lokasi ini.');

    $created = rfg_api_request('POST', 'files', array('fields' => 'id,name,mimeType,parents'), array(
        'name' => $name,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => array($parentId),
    ));
    if (empty($created['id'])) throw new RuntimeException('Google Drive tidak mengembalikan ID folder yang baru dibuat.');

    if (!isset($_SESSION['rfgallery_allowed_folders']) || !is_array($_SESSION['rfgallery_allowed_folders'])) $_SESSION['rfgallery_allowed_folders'] = array();
    $_SESSION['rfgallery_allowed_folders'][$created['id']] = time();
    unset($_SESSION['rfgallery_folder_metadata_cache'][$created['id']]);
    rfg_log('MANUAL_FOLDER_CREATED name=' . $name . ' folder_id=' . $created['id'] . ' parent_id=' . $parentId);

    $message = 'Folder "' . $name . '" berhasil dibuat.';
    if ($isAjax) rfg_create_folder_response(array('success' => true, 'message' => $message, 'folder_id' => $created['id'], 'parent_id' => $parentId, 'name' => $name));
    $_SESSION['rfgallery_flash'] = array('type' => 'success', 'text' => $message);
} catch (Throwable $e) {
    rfg_log('MANUAL_FOLDER_CREATE_FAILED parent_id=' . $parentId . ' message=' . $e->getMessage());
    if ($isAjax) rfg_create_folder_response(array('success' => false, 'message' => $e->getMessage()), 422);
    $_SESSION['rfgallery_flash'] = array('type' => 'danger', 'text' => $e->getMessage());
}
$redirect = 'index' . ($parentId !== '' ? '?folderId=' . rawurlencode($parentId) : '');
header('Location: ' . $redirect);
exit;
