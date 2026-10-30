<?php
/**
 * RF Studio Google Drive Gallery
 * Kompatibel dengan PHP 7.4; file ini sengaja tidak membutuhkan framework.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mendukung kedua panel admin lama (nama folder bersifat case-sensitive di Linux).
// Untuk AJAX, jangan redirect ke halaman login HTML karena pemanggil mengharapkan JSON.
$rfgBootstrapAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && stripos((string) $_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
if (empty($_SESSION['admin']) && empty($_SESSION['adminkartini'])) {
    $requestScript = isset($_SERVER['SCRIPT_NAME']) ? basename((string) $_SERVER['SCRIPT_NAME']) : '';
    if ($rfgBootstrapAjax && in_array($requestScript, array('upload.php', 'upload', 'create-folder.php', 'create-folder'), true)) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode(array('success' => false, 'type' => 'danger', 'message' => 'Sesi admin sudah berakhir. Login kembali lalu ulangi operasi.'));
        exit;
    }
    $fromKota = isset($_GET['from']) && $_GET['from'] === 'kota';
    $loginPath = $fromKota ? '../Admin-Kartini/adminlogin.php' : '../Admin/adminlogin.php';
    header('Location: ' . $loginPath);
    exit;
}

if (empty($_SESSION['rfgallery_csrf'])) {
    $_SESSION['rfgallery_csrf'] = bin2hex(random_bytes(32));
}

// Direktori privat bisa ditentukan lewat environment variable RFG_PRIVATE_DIR.
// Fallback XAMPP: C:/xampp/rfstudio-drive-private.
// Fallback cPanel: cari direktori home berdasarkan segmen /public_html/.
if (!defined('RFG_PRIVATE_DIR')) {
    $privateDir = getenv('RFG_PRIVATE_DIR');
    if (!$privateDir) {
        $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', (string) $_SERVER['DOCUMENT_ROOT']) : '';
        $publicHtmlPos = stripos($docRoot, '/public_html');
        if ($publicHtmlPos !== false) {
            $accountHome = rtrim(substr($docRoot, 0, $publicHtmlPos), '/');
            $privateDir = $accountHome . '/rfstudio-drive-private-staging';
        } elseif (DIRECTORY_SEPARATOR === '\\' || stripos(PHP_OS, 'WIN') === 0) {
            $privateDir = 'C:/xampp/rfstudio-drive-private';
        } else {
            $privateDir = dirname(__DIR__, 2) . '/rfstudio-drive-private-staging';
        }
    }
    define('RFG_PRIVATE_DIR', $privateDir);
}

if (!is_dir(RFG_PRIVATE_DIR)) {
    @mkdir(RFG_PRIVATE_DIR, 0700, true);
}

$configFile = RFG_PRIVATE_DIR . '/config.php';
$GLOBALS['RFG_CONFIG'] = is_file($configFile) ? include $configFile : null;

require_once __DIR__ . '/drive-api.php';
require_once __DIR__ . '/gallery-db.php';

function rfg_config_ready()
{
    $cfg = isset($GLOBALS['RFG_CONFIG']) ? $GLOBALS['RFG_CONFIG'] : null;
    if (!is_array($cfg)) {
        return false;
    }
    $required = array('client_id', 'client_secret', 'redirect_uri', 'folder_name');
    foreach ($required as $key) {
        if (empty($cfg[$key]) || strpos((string) $cfg[$key], 'PASTE_') === 0) {
            return false;
        }
    }
    if (stripos((string) $cfg['redirect_uri'], 'YOUR-DOMAIN') !== false || stripos((string) $cfg['redirect_uri'], 'example.com') !== false) {
        return false;
    }
    return extension_loaded('curl') && function_exists('random_bytes');
}

function rfg_csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['rfgallery_csrf'], ENT_QUOTES, 'UTF-8') . '">';
}

function rfg_check_csrf()
{
    $sent = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
    return isset($_SESSION['rfgallery_csrf']) && $sent !== '' && hash_equals($_SESSION['rfgallery_csrf'], $sent);
}

function rfg_h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rfg_redirect($url)
{
    header('Location: ' . $url);
    exit;
}
