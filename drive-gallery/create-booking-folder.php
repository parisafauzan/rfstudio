<?php
require_once __DIR__ . '/bootstrap.php';

$isAjaxRequest = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && stripos((string) $_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

function rfg_booking_folder_response($payload, $status)
{
    while (ob_get_level() > 0) { @ob_end_clean(); }
    http_response_code((int) $status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjaxRequest) rfg_booking_folder_response(array('success' => false, 'message' => 'Metode request tidak valid.'), 405);
    rfg_redirect('index');
}
if (!rfg_check_csrf()) {
    if ($isAjaxRequest) rfg_booking_folder_response(array('success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.'), 403);
    http_response_code(403);
    exit('Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.');
}
try {
    rfg_require_ready();
    if (!rfg_is_connected()) throw new RuntimeException('Google Drive belum terhubung.');
    $name = isset($_POST['customer_name']) ? trim((string) $_POST['customer_name']) : '';
    $uniqid = isset($_POST['booking_uniqid']) ? trim((string) $_POST['booking_uniqid']) : '';
    $studio = isset($_POST['studio']) ? (string) $_POST['studio'] : '';
    $date = isset($_POST['booking_date']) ? (string) $_POST['booking_date'] : '';
    if ($name === '' || $uniqid === '' || $date === '') throw new RuntimeException('Nama pelanggan, ID booking, dan tanggal wajib diisi.');
    if (!in_array($studio, array('Bekasi-Kota', 'Bekasi-Kabupaten'), true)) throw new RuntimeException('Pilih studio yang valid.');
    if (strlen($uniqid) > 64 || !preg_match('/^[A-Za-z0-9_-]+$/', $uniqid)) throw new RuntimeException('ID booking hanya boleh berisi huruf, angka, tanda hubung, atau underscore (maksimal 64 karakter).');
    if (function_exists('mb_strlen') ? mb_strlen($name, 'UTF-8') > 100 : strlen($name) > 100) throw new RuntimeException('Nama pelanggan maksimal 100 karakter.');

    $result = rfg_ensure_booking_folder($studio, $date, $name, $uniqid);
    $message = 'Folder galeri berhasil disiapkan: ' . $result['path'] . '.';
    if (!empty($result['db_cache_ok'])) {
        $message .= ' Mapping booking tersimpan di gallery_sessions. gallery_photos akan terisi setelah foto diunggah atau cache disinkronkan.';
    } else {
        $message .= ' Folder Drive sudah dibuat, tetapi mapping MySQL belum tersimpan: ' . (isset($result['db_cache_error']) ? $result['db_cache_error'] : 'Periksa konfigurasi database.');
    }
    rfg_log('MANUAL_BOOKING_FOLDER_READY booking=' . $uniqid . ' studio=' . $studio . ' db_cache_ok=' . (!empty($result['db_cache_ok']) ? '1' : '0'));
    $_SESSION['rfgallery_flash'] = array('type' => !empty($result['db_cache_ok']) ? 'success' : 'warning', 'text' => $message);

    if ($isAjaxRequest) {
        rfg_booking_folder_response(array(
            'success' => true,
            'db_cache_ok' => !empty($result['db_cache_ok']),
            'message' => $message,
            'folder_id' => $result['customerFolderId'],
            'folder_path' => $result['path']
        ), 200);
    }
    rfg_redirect('index?folderId=' . rawurlencode($result['customerFolderId']));
} catch (Throwable $e) {
    rfg_log('MANUAL_BOOKING_FOLDER_FAILED message=' . $e->getMessage());
    if ($isAjaxRequest) rfg_booking_folder_response(array('success' => false, 'message' => $e->getMessage()), 422);
    $_SESSION['rfgallery_flash'] = array('type' => 'danger', 'text' => 'Folder galeri tidak berhasil dibuat: ' . $e->getMessage());
    rfg_redirect('index');
}
