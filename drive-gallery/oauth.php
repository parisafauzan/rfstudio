<?php
require_once __DIR__ . '/bootstrap.php';

try {
    rfg_require_ready();
    $cfg = rfg_get_config();
    $state = bin2hex(random_bytes(24));
    $_SESSION['rfgallery_oauth_state'] = $state;
    $params = array(
        'client_id' => $cfg['client_id'],
        'redirect_uri' => $cfg['redirect_uri'],
        'response_type' => 'code',
        // Scope penuh diperlukan supaya foto yang diunggah manual melalui UI
        // Google Drive di dalam folder galeri ikut terlihat oleh aplikasi.
        'scope' => 'https://www.googleapis.com/auth/drive',
        'access_type' => 'offline',
        'include_granted_scopes' => 'true',
        'prompt' => 'consent',
        'state' => $state,
    );
    header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo '<!doctype html><meta charset="utf-8"><p>Gagal memulai koneksi Google Drive: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p><p><a href="index.php">Kembali</a></p>';
}
