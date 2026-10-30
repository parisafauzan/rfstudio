<?php
// Detect AJAX from headers too: when post_max_size is exceeded PHP clears $_POST and $_FILES,
// so the hidden field ajax=1 is no longer available in the request body.
$isAjaxRequest = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && stripos((string) $_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
if ($isAjaxRequest && ob_get_level() === 0) ob_start();

function rfg_upload_json_response($payload, $status = 200)
{
    while (ob_get_level() > 0) { @ob_end_clean(); }
    http_response_code((int) $status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
function rfg_upload_ini_bytes($value)
{
    $value = trim((string) $value);
    if ($value === '' || $value === '-1') return 0;
    if (!preg_match('/^([0-9]+(?:\.[0-9]+)?)\s*([KMG])?B?$/i', $value, $m)) return 0;
    $bytes = (float) $m[1];
    $unit = isset($m[2]) ? strtoupper($m[2]) : '';
    if ($unit === 'K') $bytes *= 1024;
    elseif ($unit === 'M') $bytes *= 1024 * 1024;
    elseif ($unit === 'G') $bytes *= 1024 * 1024 * 1024;
    return (int) $bytes;
}

require_once __DIR__ . '/bootstrap.php';
$isAjaxRequest = $isAjaxRequest || (isset($_POST['ajax']) && (string) $_POST['ajax'] === '1');

$targetFolderId = '';

// Log the first point reached. If this is absent, the request was redirected/blocked before this script ran.
$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : 'UNKNOWN';
$contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int) $_SERVER['CONTENT_LENGTH'] : 0;
rfg_log('UPLOAD_ENDPOINT_REACHED method=' . $method . ' content_length=' . $contentLength);

if ($method !== 'POST') {
    rfg_log('UPLOAD_NON_POST_REDIRECT method=' . $method);
    if ($isAjaxRequest) rfg_upload_json_response(array('success' => false, 'type' => 'danger', 'message' => 'Metode request upload tidak valid.'), 405);
    rfg_redirect('index');
}
$contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int) $_SERVER['CONTENT_LENGTH'] : 0;
$postMaxBytes = rfg_upload_ini_bytes(ini_get('post_max_size'));
if ($isAjaxRequest && $contentLength > 0 && empty($_POST) && empty($_FILES) && $postMaxBytes > 0 && $contentLength > $postMaxBytes) {
    $message = 'Ukuran request upload (' . round($contentLength / 1048576, 2) . ' MB) melebihi post_max_size PHP (' . ini_get('post_max_size') . '). Naikkan post_max_size agar lebih besar dari batas total upload 100 MB, lalu restart Apache.';
    rfg_log('UPLOAD_POST_MAX_SIZE_EXCEEDED content_length=' . $contentLength . ' post_max_size=' . ini_get('post_max_size'));
    rfg_upload_json_response(array('success' => false, 'type' => 'danger', 'message' => $message), 413);
}
if (!rfg_check_csrf()) {
    rfg_log('UPLOAD_CSRF_FAILED');
    if ($isAjaxRequest) rfg_upload_json_response(array('success' => false, 'type' => 'danger', 'message' => 'Token keamanan tidak valid atau sesi sudah berubah. Muat ulang galeri lalu coba lagi.'), 403);
    http_response_code(403);
    exit('Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.');
}
try {
    rfg_require_ready();
    if (!rfg_is_connected()) {
        throw new RuntimeException('Google Drive belum terhubung.');
    }
    if (empty($_FILES['photos']) || !isset($_FILES['photos']['name']) || !is_array($_FILES['photos']['name'])) {
        $contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int) $_SERVER['CONTENT_LENGTH'] : 0;
        $postMax = (string) ini_get('post_max_size');
        $uploadMax = (string) ini_get('upload_max_filesize');
        $message = 'PHP tidak menerima data foto. Periksa post_max_size=' . $postMax . ' dan upload_max_filesize=' . $uploadMax . '.';
        if ($contentLength > 0) {
            $message .= ' Ukuran request yang diterima browser sekitar ' . round($contentLength / 1048576, 2) . ' MB.';
        }
        rfg_log('UPLOAD_REJECTED_NO_FILES content_length=' . $contentLength . ' post_max_size=' . $postMax . ' upload_max_filesize=' . $uploadMax);
        throw new RuntimeException($message);
    }
    $cfg = rfg_get_config();
    $targetFolderId = isset($_POST['folder_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_POST['folder_id']) : '';
    if ($targetFolderId === '') $targetFolderId = rfg_folder_id();
    rfg_assert_folder_in_gallery($targetFolderId);
    $_SESSION['rfgallery_allowed_folders'][$targetFolderId] = time();
    $maxFiles = isset($cfg['max_upload_files']) ? (int) $cfg['max_upload_files'] : 10;
    $maxBytes = isset($cfg['max_upload_file_bytes']) ? (int) $cfg['max_upload_file_bytes'] : 20971520;
    $maxTotalBytes = isset($cfg['max_upload_total_bytes']) ? (int) $cfg['max_upload_total_bytes'] : 104857600;
    $count = count($_FILES['photos']['name']);
    if ($count > $maxFiles) {
        throw new RuntimeException('Maksimal ' . $maxFiles . ' foto per sekali upload.');
    }
    $batchBytes = array_sum(array_map('intval', $_FILES['photos']['size']));
    if ($batchBytes > $maxTotalBytes) {
        throw new RuntimeException('Total ukuran upload maksimal ' . round($maxTotalBytes / 1048576) . ' MB per batch.');
    }
    $allowed = array(
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    );
    if (!class_exists('finfo')) {
        throw new RuntimeException('Ekstensi fileinfo PHP dibutuhkan untuk memvalidasi tipe gambar.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $success = 0;
    $errors = array();
    for ($i = 0; $i < $count; $i++) {
        $originalName = (string) $_FILES['photos']['name'][$i];
        $tmpName = (string) $_FILES['photos']['tmp_name'][$i];
        $uploadError = (int) $_FILES['photos']['error'][$i];
        if ($uploadError !== UPLOAD_ERR_OK) {
            $uploadErrorMessages = array(
                UPLOAD_ERR_INI_SIZE => 'ukuran melebihi upload_max_filesize (' . ini_get('upload_max_filesize') . ')',
                UPLOAD_ERR_FORM_SIZE => 'ukuran melebihi batas form',
                UPLOAD_ERR_PARTIAL => 'file hanya terunggah sebagian',
                UPLOAD_ERR_NO_FILE => 'tidak ada file yang diterima PHP',
                UPLOAD_ERR_NO_TMP_DIR => 'folder temporary upload PHP tidak tersedia',
                UPLOAD_ERR_CANT_WRITE => 'PHP gagal menulis file temporary',
                UPLOAD_ERR_EXTENSION => 'upload dihentikan oleh ekstensi PHP',
            );
            $detail = isset($uploadErrorMessages[$uploadError]) ? $uploadErrorMessages[$uploadError] : 'error upload PHP tidak dikenal';
            $errors[] = $originalName . ': ' . $detail . ' (kode ' . $uploadError . ').';
            rfg_log('UPLOAD_PHP_FILE_ERROR name=' . $originalName . ' code=' . $uploadError . ' detail=' . $detail);
            continue;
        }
        if (!is_uploaded_file($tmpName) || !is_file($tmpName)) {
            $errors[] = $originalName . ': file upload tidak valid.';
            continue;
        }
        $size = (int) filesize($tmpName);
        if ($size < 1 || $size > $maxBytes) {
            $errors[] = $originalName . ': ukuran harus di antara 1 byte dan ' . round($maxBytes / 1048576) . ' MB.';
            continue;
        }
        $mime = $finfo->file($tmpName);
        if (!isset($allowed[$mime])) {
            $errors[] = $originalName . ': format tidak didukung. Gunakan JPG, PNG, atau WebP.';
            continue;
        }
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = preg_replace('/[^A-Za-z0-9._ -]/', '_', $baseName);
        $baseName = trim($baseName, " .\t\n\r\0\x0B");
        if ($baseName === '') {
            $baseName = 'foto';
        }
        $name = date('Ymd_His') . '_' . substr(bin2hex(random_bytes(3)), 0, 6) . '_' . $baseName . '.' . $allowed[$mime];
        try {
            $uploadedFile = rfg_upload_file($tmpName, $name, $mime, $targetFolderId);
            if (function_exists('rfg_gallery_cache_record_uploaded_file')) {
                rfg_gallery_cache_record_uploaded_file($targetFolderId, $uploadedFile);
            }
            $success++;
        } catch (Throwable $e) {
            rfg_log('UPLOAD_FILE_EXCEPTION name=' . $originalName . ' class=' . get_class($e) . ' message=' . $e->getMessage());
            $errors[] = $originalName . ': ' . $e->getMessage();
        }
    }
    $_SESSION['rfgallery_flash'] = array(
        'type' => empty($errors) ? 'success' : ($success > 0 ? 'warning' : 'danger'),
        'text' => 'Upload berhasil: ' . $success . ' file.' . (empty($errors) ? '' : ' Perlu diperiksa: ' . implode(' | ', $errors)),
    );
} catch (Throwable $e) {
    rfg_log('UPLOAD_REQUEST_EXCEPTION class=' . get_class($e) . ' message=' . $e->getMessage());
    $_SESSION['rfgallery_flash'] = array('type' => 'danger', 'text' => $e->getMessage());
}
$uploadFlash = isset($_SESSION['rfgallery_flash']) ? $_SESSION['rfgallery_flash'] : array('type' => 'danger', 'text' => 'Upload gagal.');
if ($isAjaxRequest) {
    unset($_SESSION['rfgallery_flash']);
    $isSuccess = isset($uploadFlash['type']) && $uploadFlash['type'] !== 'danger';
    rfg_upload_json_response(array(
        'success' => $isSuccess,
        'type' => isset($uploadFlash['type']) ? $uploadFlash['type'] : 'danger',
        'message' => isset($uploadFlash['text']) ? $uploadFlash['text'] : 'Upload gagal.',
        'folder_id' => $targetFolderId
    ), $isSuccess ? 200 : 422);
}
rfg_redirect('index.php' . (!empty($targetFolderId) ? '?folderId=' . rawurlencode($targetFolderId) : ''));
