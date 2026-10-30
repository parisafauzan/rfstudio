<?php
/**
 * RF Studio Google Drive Gallery
 * Kompatibel dengan PHP 7.4; file ini sengaja tidak membutuhkan framework.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengikuti session yang digunakan halaman login admin lama.
if (empty($_SESSION['admin'])) {
    header('Location: ../admin/adminlogin.php');
    exit;
}

if (empty($_SESSION['rfgallery_csrf'])) {
    $_SESSION['rfgallery_csrf'] = bin2hex(random_bytes(32));
}

// Jika website berada di /home/USERNAME/public_html/drive-gallery,
// direktori privat akan berada di /home/USERNAME/rfstudio-drive-private.
// if (!defined('RFG_PRIVATE_DIR')) {
//     define('RFG_PRIVATE_DIR', dirname(__DIR__, 2) . '/rfstudio-drive-private');
// }

if (!defined('RFG_PRIVATE_DIR')) {
    define('RFG_PRIVATE_DIR', 'C:/xampp/rfstudio-drive-private');
}

// if (!defined('RFG_PRIVATE_DIR')) {
//     define(
//         'RFG_PRIVATE_DIR',
//         '/home/rfstu853/rfstudio-drive-private-staging'
//     );
// }

if (!is_dir(RFG_PRIVATE_DIR)) {
    @mkdir(RFG_PRIVATE_DIR, 0700, true);
}

$configFile = RFG_PRIVATE_DIR . '/config.php';
$GLOBALS['RFG_CONFIG'] = is_file($configFile) ? include $configFile : null;

require_once __DIR__ . '/drive-api.php';

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
