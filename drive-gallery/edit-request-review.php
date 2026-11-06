<?php
require_once __DIR__ . '/bootstrap.php';

$folderId = isset($_POST['folder_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_POST['folder_id']) : '';
$redirect = 'index' . ($folderId !== '' ? '?folderId=' . rawurlencode($folderId) : '');
if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') rfg_redirect($redirect);

try {
    if (!rfg_check_csrf()) throw new RuntimeException('Token keamanan berakhir. Muat ulang halaman lalu coba kembali.');
    $requestId = isset($_POST['request_id']) ? (int) $_POST['request_id'] : 0;
    $status = isset($_POST['request_status']) ? (string) $_POST['request_status'] : '';
    $note = isset($_POST['admin_note']) ? trim((string) $_POST['admin_note']) : '';
    if ($requestId < 1 || !in_array($status, array('submitted', 'editing', 'completed', 'rejected'), true)) throw new RuntimeException('Permintaan atau status edit tidak valid.');
    if ($status === 'rejected' && $note === '') $note = 'Pilihan foto perlu diperbaiki. Silakan pilih dan kirim ulang.';
    if (!rfg_gallery_admin_update_edit_request($requestId, $status, $note)) throw new RuntimeException('Status permintaan edit tidak berhasil diperbarui.');
    $labels = array('submitted' => 'dikembalikan ke antrean', 'editing' => 'ditandai sedang diedit', 'completed' => 'ditandai selesai', 'rejected' => 'dikembalikan ke customer');
    $_SESSION['rfgallery_flash'] = array('type' => 'success', 'text' => 'Permintaan edit ' . $labels[$status] . '.');
} catch (Throwable $e) {
    $_SESSION['rfgallery_flash'] = array('type' => 'danger', 'text' => $e->getMessage());
}
rfg_redirect($redirect);
