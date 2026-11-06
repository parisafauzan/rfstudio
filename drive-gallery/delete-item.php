<?php
require_once __DIR__ . '/bootstrap.php';

$parentFolderId = isset($_POST['parent_folder_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_POST['parent_folder_id']) : '';
$redirect = 'index' . ($parentFolderId !== '' ? '?folderId=' . rawurlencode($parentFolderId) : '');
$driveTrashed = false;

if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') rfg_redirect($redirect);

try {
    if (!rfg_check_csrf()) throw new RuntimeException('Token keamanan berakhir. Muat ulang galeri lalu coba kembali.');
    rfg_require_ready();
    if (!rfg_is_connected()) throw new RuntimeException('Google Drive belum terhubung.');
    if ($parentFolderId === '') throw new RuntimeException('Folder induk tidak valid.');

    $itemId = isset($_POST['item_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_POST['item_id']) : '';
    $itemType = isset($_POST['item_type']) ? (string) $_POST['item_type'] : '';
    if ($itemId === '' || !in_array($itemType, array('photo', 'folder'), true)) throw new RuntimeException('Item yang akan dihapus tidak valid.');

    if ($itemType === 'photo') {
        $meta = rfg_file_metadata($itemId, $parentFolderId);
        rfg_trash_drive_item($itemId, $parentFolderId);
        $driveTrashed = true;
        rfg_gallery_mark_photo_missing_by_drive_id($itemId);
        $_SESSION['rfgallery_flash'] = array('type' => 'success', 'text' => 'Foto "' . $meta['name'] . '" dipindahkan ke Trash Google Drive dan cache database sudah diperbarui.');
        rfg_redirect($redirect);
    }

    $meta = rfg_drive_item_metadata($itemId, $parentFolderId);
    if (!isset($meta['mimeType']) || $meta['mimeType'] !== 'application/vnd.google-apps.folder') throw new RuntimeException('Item yang dipilih bukan folder Google Drive.');
    $folderName = isset($meta['name']) ? (string) $meta['name'] : 'Folder';
    $lowerName = strtolower(trim($folderName));

    // Resolve affected booking sessions before the Drive hierarchy disappears.
    $affectedSessionIds = array();
    foreach (rfg_gallery_active_sessions() as $session) {
        if ($session['drive_folder_id'] === $itemId) {
            $affectedSessionIds[(int) $session['id']] = (int) $session['id'];
            continue;
        }
        try {
            $path = rfg_folder_path($session['drive_folder_id']);
            foreach ($path as $pathItem) {
                if (isset($pathItem['id']) && $pathItem['id'] === $itemId) {
                    $affectedSessionIds[(int) $session['id']] = (int) $session['id'];
                    break;
                }
            }
        } catch (Throwable $ignored) {
            // Sesi yang sebelumnya sudah rusak akan direkonsiliasi ketika customer membukanya.
        }
    }

    // Originals/Edited are children of a customer folder; deleting one category
    // must not deactivate the whole booking when the sibling folder remains.
    $categorySession = null;
    $category = '';
    if (in_array($lowerName, array('originals', 'edited'), true)) {
        $categorySession = rfg_gallery_session_find_by_folder($parentFolderId);
        $category = $lowerName === 'edited' ? 'edited' : 'original';
    }

    rfg_trash_drive_item($itemId, $parentFolderId);
    $driveTrashed = true;
    if ($categorySession) rfg_gallery_mark_category_missing((int) $categorySession['id'], $category);
    if (!empty($affectedSessionIds)) {
        rfg_gallery_deactivate_sessions(array_values($affectedSessionIds), 'Galeri foto telah dihapus oleh admin karena masa penyimpanannya berakhir.');
    }
    $_SESSION['rfgallery_flash'] = array('type' => 'success', 'text' => 'Folder "' . $folderName . '" dipindahkan ke Trash Google Drive. ' . count($affectedSessionIds) . ' sesi customer dinonaktifkan.');
} catch (Throwable $e) {
    $prefix = $driveTrashed ? 'Item sudah masuk Trash Google Drive, tetapi pembaruan database belum selesai: ' : 'Penghapusan gagal: ';
    $_SESSION['rfgallery_flash'] = array('type' => 'danger', 'text' => $prefix . $e->getMessage());
}
rfg_redirect($redirect);
