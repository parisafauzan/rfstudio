<?php
require_once __DIR__ . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !rfg_check_csrf()) {
    http_response_code(403);
    exit('Permintaan tidak valid.');
}
try {
    $token = rfg_read_token();
    if (is_array($token) && !empty($token['access_token'])) {
        // Upaya revoke bersifat best effort; token lokal tetap dihapus.
        @rfg_http('POST', 'https://oauth2.googleapis.com/revoke', array('Content-Type: application/x-www-form-urlencoded'), http_build_query(array('token' => $token['access_token'])), array('timeout' => 15));
    }
    if (is_file(rfg_token_path())) {
        @unlink(rfg_token_path());
    }
    $_SESSION['rfgallery_flash'] = array('type' => 'success', 'text' => 'Token lokal dihapus. Untuk mencabut akses aplikasi sepenuhnya, periksa juga pengaturan keamanan akun Google.');
} catch (Exception $e) {
    $_SESSION['rfgallery_flash'] = array('type' => 'warning', 'text' => 'Token lokal tidak dapat diproses sepenuhnya: ' . $e->getMessage());
}
rfg_redirect('index.php');
