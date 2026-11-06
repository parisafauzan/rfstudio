<?php
require_once __DIR__ . '/gallery-customer-lib.php';

if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gallery');
    exit;
}

$sessionId = isset($_POST['gallery_session_id']) ? (int) $_POST['gallery_session_id'] : 0;
$email = rfg_customer_access_email($sessionId);
$redirect = 'gallery' . ($email !== '' ? '?email=' . urlencode($email) . '&session=' . $sessionId . '&tab=original' : '');
try {
    if (!rfg_customer_csrf_valid(isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '')) throw new RuntimeException('Sesi formulir berakhir. Muat ulang galeri lalu coba kembali.');
    if ($email === '') throw new RuntimeException('Akses galeri berakhir. Cari kembali galeri menggunakan email booking.');
    $gallery = rfg_customer_gallery_for_email($sessionId, $email);
    if (!$gallery) throw new RuntimeException('Galeri booking tidak ditemukan untuk email tersebut.');
    $cfg = isset($GLOBALS['RFG_CONFIG']) && is_array($GLOBALS['RFG_CONFIG']) ? $GLOBALS['RFG_CONFIG'] : array();
    $max = !empty($cfg['max_customer_edit_selection']) ? (int) $cfg['max_customer_edit_selection'] : 30;
    rfg_gallery_submit_edit_request($sessionId, $email, isset($_POST['photo_ids']) ? $_POST['photo_ids'] : array(), isset($_POST['customer_note']) ? $_POST['customer_note'] : '', $max);
    rfg_customer_flash('success', 'Pilihan foto berhasil dikirim ke admin untuk diedit.');
} catch (Throwable $e) {
    rfg_customer_flash('danger', $e->getMessage());
}
header('Location: ' . $redirect);
exit;
