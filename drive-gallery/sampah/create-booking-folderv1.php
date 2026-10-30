<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') rfg_redirect('index');
if (!rfg_check_csrf()) {
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
    $result = rfg_ensure_booking_folder($studio, $date, $name, $uniqid);
    $message = 'Folder galeri siap: ' . $result['path'] . '. Subfolder Originals dan Edited juga sudah tersedia.';
    if (!empty($result['db_cache_ok'])) {
        $message .= ' Booking juga tercatat di gallery_sessions untuk cache metadata foto.';
    } else {
        $message .= ' Catatan: folder ada di Google Drive, tetapi cache MySQL belum aktif/tersimpan. ' . (isset($result['db_cache_error']) ? $result['db_cache_error'] : 'Periksa konfigurasi database.');
    }
    $_SESSION['rfgallery_flash'] = array('type' => !empty($result['db_cache_ok']) ? 'success' : 'warning', 'text' => $message);
    rfg_redirect('index?folderId=' . rawurlencode($result['customerFolderId']));
} catch (Throwable $e) {
    rfg_log('MANUAL_BOOKING_FOLDER_FAILED message=' . $e->getMessage());
    $_SESSION['rfgallery_flash'] = array('type' => 'danger', 'text' => 'Folder galeri tidak berhasil dibuat: ' . $e->getMessage());
    rfg_redirect('index');
}
