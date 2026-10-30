<?php
require_once __DIR__ . '/bootstrap.php';

$state = isset($_GET['state']) ? (string) $_GET['state'] : '';
$expectedState = isset($_SESSION['rfgallery_oauth_state']) ? (string) $_SESSION['rfgallery_oauth_state'] : '';
unset($_SESSION['rfgallery_oauth_state']);
if ($state === '' || $expectedState === '' || !hash_equals($expectedState, $state)) {
    http_response_code(400);
    exit('Permintaan OAuth tidak valid. Silakan kembali ke galeri dan ulangi proses koneksi.');
}
if (isset($_GET['error'])) {
    $error = htmlspecialchars((string) $_GET['error'], ENT_QUOTES, 'UTF-8');
    exit('Google membatalkan otorisasi: ' . $error . '. <a href="index.php">Kembali</a>');
}
if (empty($_GET['code'])) {
    http_response_code(400);
    exit('Kode OAuth tidak ditemukan. <a href="index.php">Kembali</a>');
}

try {
    rfg_require_ready();
    $cfg = rfg_get_config();
    $token = rfg_oauth_post(array(
        'code' => (string) $_GET['code'],
        'client_id' => $cfg['client_id'],
        'client_secret' => $cfg['client_secret'],
        'redirect_uri' => $cfg['redirect_uri'],
        'grant_type' => 'authorization_code',
    ));
    $oldToken = rfg_read_token();
    if (empty($token['refresh_token']) && is_array($oldToken) && !empty($oldToken['refresh_token'])) {
        $token['refresh_token'] = $oldToken['refresh_token'];
    }
    if (empty($token['refresh_token'])) {
        throw new RuntimeException('Google tidak mengembalikan refresh token. Hapus akses aplikasi RF Studio dari pengaturan keamanan akun Google, kemudian hubungkan kembali. Pastikan akses offline diminta.');
    }
    $token['expires_at'] = time() + (isset($token['expires_in']) ? (int) $token['expires_in'] : 3600);
    rfg_save_token($token);
    rfg_folder_id(); // Buat/pastikan folder khusus galeri di Google Drive.
    rfg_redirect('index.php?connected=1');
} catch (Exception $e) {
    http_response_code(500);
    echo '<!doctype html><html lang="id"><meta charset="utf-8"><title>Koneksi gagal</title><body style="font-family:Arial,sans-serif;padding:32px"><h2>Koneksi Google Drive gagal</h2><p>' . rfg_h($e->getMessage()) . '</p><p><a href="index.php">Kembali ke galeri</a></p></body></html>';
}
