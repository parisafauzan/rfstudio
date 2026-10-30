<?php
/** Helper OAuth + REST Google Drive API berbasis cURL (tanpa Composer). */


/** Catatan diagnostik privat; tidak pernah mencatat token/Client Secret. */
function rfg_log($message)
{
    if (!defined('RFG_PRIVATE_DIR') || !is_dir(RFG_PRIVATE_DIR) || !is_writable(RFG_PRIVATE_DIR)) {
        return;
    }
    $message = str_replace(array("\r", "\n"), ' ', (string) $message);
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    @file_put_contents(RFG_PRIVATE_DIR . '/drive-gallery-upload.log', $line, FILE_APPEND | LOCK_EX);
    @chmod(RFG_PRIVATE_DIR . '/drive-gallery-upload.log', 0600);
}

function rfg_get_config()
{
    $cfg = isset($GLOBALS['RFG_CONFIG']) ? $GLOBALS['RFG_CONFIG'] : null;
    if (!is_array($cfg)) {
        throw new RuntimeException('Konfigurasi privat Google Drive belum tersedia. Ikuti README_ID.md.');
    }
    return $cfg;
}

function rfg_require_ready()
{
    if (!rfg_config_ready()) {
        throw new RuntimeException('Konfigurasi Google Drive belum lengkap atau ekstensi cURL PHP belum aktif.');
    }
}

function rfg_http($method, $url, $headers, $body = null, $options = array())
{
    if (!function_exists('curl_init')) {
        throw new RuntimeException('Ekstensi cURL belum aktif pada PHP hosting. Aktifkan dari Select PHP Version / Extensions di cPanel.');
    }
    $responseHeaders = array();
    $ch = curl_init($url);
    $curlOptions = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_TIMEOUT => isset($options['timeout']) ? (int) $options['timeout'] : 120,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_HEADERFUNCTION => function ($curl, $headerLine) use (&$responseHeaders) {
            $length = strlen($headerLine);
            $parts = explode(':', $headerLine, 2);
            if (count($parts) === 2) {
                $responseHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
            }
            return $length;
        },
    );
    if ($body !== null) {
        $curlOptions[CURLOPT_POSTFIELDS] = $body;
    }
    if (isset($options['upload_file'])) {
        $stream = fopen($options['upload_file'], 'rb');
        if (!$stream) {
            curl_close($ch);
            throw new RuntimeException('File sementara tidak dapat dibuka.');
        }
        $curlOptions[CURLOPT_UPLOAD] = true;
        $curlOptions[CURLOPT_INFILE] = $stream;
        $curlOptions[CURLOPT_INFILESIZE] = (int) filesize($options['upload_file']);
        $curlOptions[CURLOPT_CUSTOMREQUEST] = 'PUT';
    }
    curl_setopt_array($ch, $curlOptions);
    $responseBody = curl_exec($ch);
    $curlError = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (isset($stream) && is_resource($stream)) {
        fclose($stream);
    }
    curl_close($ch);
    if ($responseBody === false) {
        throw new RuntimeException('Koneksi ke Google gagal: ' . $curlError);
    }
    return array('status' => $status, 'body' => $responseBody, 'headers' => $responseHeaders);
}

function rfg_oauth_post($fields)
{
    $cfg = rfg_get_config();
    $response = rfg_http('POST', 'https://oauth2.googleapis.com/token', array(
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json',
    ), http_build_query($fields), array('timeout' => 45));
    $data = json_decode($response['body'], true);
    if ($response['status'] < 200 || $response['status'] >= 300 || !is_array($data)) {
        $message = is_array($data) && isset($data['error_description']) ? $data['error_description'] : 'Google OAuth gagal (HTTP ' . $response['status'] . ').';
        throw new RuntimeException($message);
    }
    return $data;
}

function rfg_token_path()
{
    return RFG_PRIVATE_DIR . '/drive-token.json';
}

function rfg_read_token()
{
    $path = rfg_token_path();
    if (!is_file($path)) {
        return null;
    }
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : null;
}

function rfg_save_token($token)
{
    $path = rfg_token_path();
    $json = json_encode($token, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false || file_put_contents($path, $json, LOCK_EX) === false) {
        throw new RuntimeException('Token Google tidak dapat disimpan. Periksa permission direktori privat di luar web root.');
    }
    @chmod($path, 0600);
}

function rfg_access_token()
{
    rfg_require_ready();
    $cfg = rfg_get_config();
    $token = rfg_read_token();
    if (!$token || empty($token['access_token'])) {
        throw new RuntimeException('Google Drive belum terhubung. Tekan tombol Hubungkan Google Drive.');
    }
    if (!empty($token['expires_at']) && (int) $token['expires_at'] > time() + 60) {
        return $token['access_token'];
    }
    if (empty($token['refresh_token'])) {
        throw new RuntimeException('Refresh token tidak ada. Putuskan koneksi lalu hubungkan Google Drive kembali.');
    }
    $fresh = rfg_oauth_post(array(
        'client_id' => $cfg['client_id'],
        'client_secret' => $cfg['client_secret'],
        'refresh_token' => $token['refresh_token'],
        'grant_type' => 'refresh_token',
    ));
    $token['access_token'] = $fresh['access_token'];
    $token['expires_at'] = time() + (isset($fresh['expires_in']) ? (int) $fresh['expires_in'] : 3600);
    if (!empty($fresh['scope'])) {
        $token['scope'] = $fresh['scope'];
    }
    rfg_save_token($token);
    return $token['access_token'];
}

function rfg_is_connected()
{
    $token = rfg_read_token();
    return is_array($token) && !empty($token['refresh_token']) && !empty($token['access_token']);
}

function rfg_api_request($method, $path, $query = array(), $jsonBody = null, $extraHeaders = array())
{
    $accessToken = rfg_access_token();
    $url = 'https://www.googleapis.com/drive/v3/' . ltrim($path, '/');
    if (!empty($query)) {
        $url .= '?' . http_build_query($query);
    }
    $headers = array_merge(array(
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json',
    ), $extraHeaders);
    $body = $jsonBody === null ? null : json_encode($jsonBody);
    if ($jsonBody !== null) {
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
    }
    $response = rfg_http($method, $url, $headers, $body);
    // Estimasi penggunaan kuota berdasarkan operasi yang dipanggil oleh modul ini.
    // Angka ini bukan metrik resmi/real-time Google Cloud dan tidak mencakup aplikasi lain.
    $usageUnits = rfg_estimate_method_quota_units($method, $path, $query);
    rfg_usage_record('api_' . strtoupper($method) . '_' . preg_replace('/[^A-Za-z0-9_]+/', '_', $path), $usageUnits, 0, 0, 0);
    $data = json_decode($response['body'], true);
    if ($response['status'] < 200 || $response['status'] >= 300) {
        $msg = (is_array($data) && isset($data['error']['message'])) ? $data['error']['message'] : 'Google Drive API gagal (HTTP ' . $response['status'] . ').';
        throw new RuntimeException($msg);
    }
    return is_array($data) ? $data : array();
}

function rfg_folder_id()
{
    $cfg = rfg_get_config();
    $folderFile = RFG_PRIVATE_DIR . '/drive-folder-id.txt';
    if (is_file($folderFile)) {
        $saved = trim(file_get_contents($folderFile));
        if ($saved !== '') {
            return $saved;
        }
    }

    $escapedName = str_replace("'", "\\'", $cfg['folder_name']);
    $existing = rfg_api_request('GET', 'files', array(
        'q' => "name = '" . $escapedName . "' and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
        'spaces' => 'drive',
        'pageSize' => 100,
        'fields' => 'files(id,name,mimeType)',
    ));
    if (!empty($existing['files'][0]['id'])) {
        $id = $existing['files'][0]['id'];
    } else {
        $created = rfg_api_request('POST', 'files', array('fields' => 'id,name,mimeType'), array(
            'name' => $cfg['folder_name'],
            'mimeType' => 'application/vnd.google-apps.folder',
        ));
        if (empty($created['id'])) {
            throw new RuntimeException('Google Drive tidak mengembalikan ID folder.');
        }
        $id = $created['id'];
    }
    if (file_put_contents($folderFile, $id, LOCK_EX) === false) {
        throw new RuntimeException('Folder berhasil dibuat tetapi ID folder tidak dapat disimpan pada direktori privat.');
    }
    @chmod($folderFile, 0600);
    return $id;
}

/** Escape a value for use in a Google Drive API q string. */
function rfg_drive_query_escape($value)
{
    return str_replace(array('\\', "'"), array('\\\\', "\\'"), (string) $value);
}

/** Read folder metadata without downloading file contents. */
function rfg_folder_metadata($folderId)
{
    $folderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    if ($folderId === '') {
        throw new RuntimeException('ID folder tidak valid.');
    }
    // Cache metadata folder in the current PHP session for 45 seconds. This avoids
    // re-reading each ancestor when navigating between siblings, without copying the tree to MySQL.
    // Explicit Refresh bypasses the cache so manual Drive moves/renames are picked up immediately.
    $forceMetadataRefresh = isset($_GET['refresh']) && (string) $_GET['refresh'] === '1';
    if (!$forceMetadataRefresh && isset($_SESSION['rfgallery_folder_metadata_cache'][$folderId])) {
        $cachedFolder = $_SESSION['rfgallery_folder_metadata_cache'][$folderId];
        if (isset($cachedFolder['at'], $cachedFolder['meta']) && (int) $cachedFolder['at'] >= time() - 45) {
            return $cachedFolder['meta'];
        }
        unset($_SESSION['rfgallery_folder_metadata_cache'][$folderId]);
    }
    $meta = rfg_api_request('GET', 'files/' . rawurlencode($folderId), array(
        'fields' => 'id,name,mimeType,parents,trashed',
    ));
    if (empty($meta['id']) || !empty($meta['trashed']) || !isset($meta['mimeType']) || $meta['mimeType'] !== 'application/vnd.google-apps.folder') {
        throw new RuntimeException('Folder tidak ditemukan atau bukan folder Google Drive.');
    }
    if (!isset($_SESSION['rfgallery_folder_metadata_cache']) || !is_array($_SESSION['rfgallery_folder_metadata_cache'])) {
        $_SESSION['rfgallery_folder_metadata_cache'] = array();
    }
    $_SESSION['rfgallery_folder_metadata_cache'][$folderId] = array('at' => time(), 'meta' => $meta);
    if (count($_SESSION['rfgallery_folder_metadata_cache']) > 250) {
        foreach ($_SESSION['rfgallery_folder_metadata_cache'] as $cachedId => $cachedEntry) {
            if (!isset($cachedEntry['at']) || (int) $cachedEntry['at'] < time() - 45) unset($_SESSION['rfgallery_folder_metadata_cache'][$cachedId]);
        }
        if (count($_SESSION['rfgallery_folder_metadata_cache']) > 250) {
            $_SESSION['rfgallery_folder_metadata_cache'] = array_slice($_SESSION['rfgallery_folder_metadata_cache'], -200, null, true);
        }
    }
    return $meta;
}

/**
 * Verify a folder belongs to the configured gallery root and return the
 * current path. Folder hierarchy is deliberately read from Drive, not MySQL.
 */
function rfg_folder_path($folderId)
{
    $rootId = rfg_folder_id();
    $cfg = rfg_get_config();
    $folderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    if ($folderId === '') $folderId = $rootId;

    $root = array(array('id' => $rootId, 'name' => (string) $cfg['folder_name']));
    if ($folderId === $rootId) {
        if (!isset($_SESSION['rfgallery_allowed_folders'])) $_SESSION['rfgallery_allowed_folders'] = array();
        $_SESSION['rfgallery_allowed_folders'][$rootId] = time();
        return $root;
    }

    $reverse = array();
    $currentId = $folderId;
    $foundRoot = false;
    for ($depth = 0; $depth < 12; $depth++) {
        if ($currentId === $rootId) {
            $foundRoot = true;
            break;
        }
        $meta = rfg_folder_metadata($currentId);
        $reverse[] = array('id' => $meta['id'], 'name' => isset($meta['name']) ? $meta['name'] : '(Tanpa nama)');
        $parents = isset($meta['parents']) && is_array($meta['parents']) ? $meta['parents'] : array();
        if (empty($parents)) break;
        $currentId = (string) $parents[0];
    }
    if (!$foundRoot && $currentId === $rootId) $foundRoot = true;
    if (!$foundRoot) {
        throw new RuntimeException('Folder ini berada di luar folder galeri RF Studio atau tidak dapat diakses oleh aplikasi.');
    }

    $path = array_merge($root, array_reverse($reverse));
    if (!isset($_SESSION['rfgallery_allowed_folders'])) $_SESSION['rfgallery_allowed_folders'] = array();
    foreach ($path as $item) {
        $_SESSION['rfgallery_allowed_folders'][$item['id']] = time();
    }
    // Keep the session authorization list bounded.
    foreach ($_SESSION['rfgallery_allowed_folders'] as $id => $stamp) {
        if ((int) $stamp < time() - 3600) unset($_SESSION['rfgallery_allowed_folders'][$id]);
    }
    return $path;
}

/** Accept a folder id previously navigated to, or verify it from Drive. */
function rfg_assert_folder_in_gallery($folderId)
{
    $folderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    if ($folderId === '') throw new RuntimeException('ID folder tidak valid.');
    $rootId = rfg_folder_id();
    if ($folderId === $rootId) return true;
    if (isset($_SESSION['rfgallery_allowed_folders'][$folderId]) && (int) $_SESSION['rfgallery_allowed_folders'][$folderId] >= time() - 600) {
        return true;
    }
    rfg_folder_path($folderId);
    return true;
}

/** Find a child folder by name or create it. */
function rfg_find_or_create_child_folder($name, $parentId)
{
    $name = trim((string) $name);
    if ($name === '') throw new RuntimeException('Nama folder tidak boleh kosong.');
    $parentId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $parentId);
    if ($parentId === '') throw new RuntimeException('ID folder induk tidak valid.');
    $escapedName = rfg_drive_query_escape($name);
    $query = "'" . rfg_drive_query_escape($parentId) . "' in parents and name = '" . $escapedName . "' and mimeType = 'application/vnd.google-apps.folder' and trashed = false";
    $existing = rfg_api_request('GET', 'files', array(
        'q' => $query,
        'spaces' => 'drive',
        'pageSize' => 100,
        'fields' => 'files(id,name,mimeType,parents)',
    ));
    if (!empty($existing['files'][0]['id'])) return $existing['files'][0]['id'];

    $created = rfg_api_request('POST', 'files', array('fields' => 'id,name,mimeType,parents'), array(
        'name' => $name,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => array($parentId),
    ));
    if (empty($created['id'])) throw new RuntimeException('Google Drive tidak mengembalikan ID folder yang dibuat.');
    rfg_log('FOLDER_CREATED name=' . $name . ' folder_id=' . $created['id']);
    return $created['id'];
}

/** Create folder hierarchy on Drive and register only the booking/session reference in MySQL. */
function rfg_ensure_booking_folder($studio, $bookingDate, $customerName, $bookingUniqId)
{
    rfg_require_ready();
    if (!rfg_is_connected()) throw new RuntimeException('Google Drive belum terhubung. Hubungkan dari halaman galeri terlebih dahulu.');

    $studioText = trim((string) $studio);
    if (stripos($studioText, 'kabupaten') !== false || stripos($studioText, 'kab') !== false) {
        $studioName = 'Bekasi-Kabupaten';
    } elseif (stripos($studioText, 'kota') !== false) {
        $studioName = 'Bekasi-Kota';
    } else {
        $studioName = preg_replace('/[^\\p{L}\\p{N} ._-]/u', '', $studioText);
        $studioName = trim(preg_replace('/\\s+/', ' ', (string) $studioName));
        if ($studioName === '') $studioName = 'Studio-Tidak-Diketahui';
    }

    $timestamp = strtotime((string) $bookingDate);
    if ($timestamp === false) {
        if (preg_match('/^(\\d{2})[-\\/](\\d{2})[-\\/](\\d{4})$/', trim((string) $bookingDate), $m)) {
            $timestamp = mktime(12, 0, 0, (int) $m[2], (int) $m[1], (int) $m[3]);
        }
    }
    if ($timestamp === false || $timestamp <= 0) throw new RuntimeException('Tanggal booking tidak dikenali: ' . (string) $bookingDate);

    $uid = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $bookingUniqId);
    if ($uid === '') throw new RuntimeException('ID booking (uniqid) diperlukan untuk membuat folder pelanggan.');
    $name = trim(preg_replace('/\\s+/', ' ', (string) $customerName));
    $name = preg_replace('/[^\\p{L}\\p{N} ._-]/u', '_', $name);
    $name = trim((string) $name, " ._-\t\n\r\0\x0B");
    if ($name === '') $name = 'Pelanggan';
    if (function_exists('mb_substr')) $name = mb_substr($name, 0, 70, 'UTF-8'); else $name = substr($name, 0, 70);

    $rootId = rfg_folder_id();
    $studioId = rfg_find_or_create_child_folder($studioName, $rootId);
    $yearName = date('Y', $timestamp);
    $monthName = date('m', $timestamp) . ' - ' . array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')[(int) date('n', $timestamp)];
    $yearId = rfg_find_or_create_child_folder($yearName, $studioId);
    $monthId = rfg_find_or_create_child_folder($monthName, $yearId);
    $customerFolderName = $name . ' - Booking ' . $uid;
    $customerId = rfg_find_or_create_child_folder($customerFolderName, $monthId);
    $originalsId = rfg_find_or_create_child_folder('Originals', $customerId);
    $editedId = rfg_find_or_create_child_folder('Edited', $customerId);

    // Refresh authorization path after automatic folder creation.
    rfg_folder_path($customerId);
    $result = array(
        'rootFolderId' => $rootId,
        'studioFolderId' => $studioId,
        'yearFolderId' => $yearId,
        'monthFolderId' => $monthId,
        'customerFolderId' => $customerId,
        'originalsFolderId' => $originalsId,
        'editedFolderId' => $editedId,
        'customerFolderName' => $customerFolderName,
        'studioName' => $studioName,
        'bookingDateSql' => date('Y-m-d', $timestamp),
        'path' => $studioName . ' / ' . $yearName . ' / ' . $monthName . ' / ' . $customerFolderName,
        'db_cache_ok' => false,
        'db_cache_error' => '',
    );
    rfg_log('BOOKING_FOLDER_READY booking=' . $uid . ' studio=' . $studioName . ' customer_folder_id=' . $customerId);

    // MySQL hanya menyimpan mapping booking -> folder pelanggan serta cache; tidak menyimpan pohon folder.
    if (function_exists('rfg_gallery_db_configured') && rfg_gallery_db_configured()) {
        try {
            rfg_gallery_session_upsert($uid, $name, $studioName, $result['bookingDateSql'], $customerId);
            $result['db_cache_ok'] = true;
        } catch (Throwable $dbError) {
            $result['db_cache_error'] = $dbError->getMessage();
            rfg_log('GALLERY_SESSION_UPSERT_FAILED booking=' . $uid . ' message=' . $dbError->getMessage());
        }
    } else {
        $result['db_cache_error'] = 'Konfigurasi MySQL cache belum diisi pada config.php privat.';
    }
    return $result;
}

/** Direct children of the selected folder: child folders and supported image files only. */
function rfg_list_folder_items($folderId, $pageToken = '', $nameFilter = '', $validateFolder = true)
{
    if ($validateFolder) rfg_assert_folder_in_gallery($folderId);
    $folderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    $query = "'" . rfg_drive_query_escape($folderId) . "' in parents and trashed = false";
    $nameFilter = trim((string) $nameFilter);
    if ($nameFilter !== '') {
        $query .= " and name contains '" . rfg_drive_query_escape($nameFilter) . "'";
    }
    $params = array(
        'q' => $query,
        'spaces' => 'drive',
        'pageSize' => 50,
        'orderBy' => 'name',
        'fields' => 'nextPageToken,files(id,name,mimeType,size,createdTime,modifiedTime,thumbnailLink,parents,webViewLink)',
    );
    if ($pageToken !== '') $params['pageToken'] = (string) $pageToken;
    return rfg_api_request('GET', 'files', $params);
}

/** Fetch all supported images in one folder, paging in Drive batches up to 1000 items.
 * Used only on cache miss/expiry or explicit refresh, not on every gallery page load.
 */
function rfg_list_folder_images_all($folderId)
{
    rfg_assert_folder_in_gallery($folderId);
    $folderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    if ($folderId === '') throw new RuntimeException('ID folder foto tidak valid.');
    $query = "'" . rfg_drive_query_escape($folderId) . "' in parents and trashed = false and (mimeType = 'image/jpeg' or mimeType = 'image/png' or mimeType = 'image/webp')";
    $all = array();
    $pageToken = '';
    do {
        $params = array(
            'q' => $query,
            'spaces' => 'drive',
            'pageSize' => 1000,
            'orderBy' => 'name',
            'fields' => 'nextPageToken,files(id,name,mimeType,size,createdTime,modifiedTime,thumbnailLink,parents,webViewLink)',
        );
        if ($pageToken !== '') $params['pageToken'] = $pageToken;
        $batch = rfg_api_request('GET', 'files', $params);
        foreach ((isset($batch['files']) && is_array($batch['files'])) ? $batch['files'] : array() as $file) {
            if (isset($file['mimeType']) && in_array($file['mimeType'], array('image/jpeg', 'image/png', 'image/webp'), true)) $all[] = $file;
        }
        $pageToken = isset($batch['nextPageToken']) ? (string) $batch['nextPageToken'] : '';
    } while ($pageToken !== '');
    return $all;
}

/** Backward-compatible wrapper for older internal links. */
function rfg_list_files($pageToken = '')
{
    return rfg_list_folder_items(rfg_folder_id(), $pageToken, '', false);
}

/** Check a file is an allowed image and is directly inside the selected gallery folder. */
function rfg_file_metadata($fileId, $expectedParentId = '')
{
    $fileId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $fileId);
    if ($fileId === '') throw new RuntimeException('ID foto tidak valid.');
    if ($expectedParentId !== '') rfg_assert_folder_in_gallery($expectedParentId);

    $file = rfg_api_request('GET', 'files/' . rawurlencode($fileId), array(
        'fields' => 'id,name,mimeType,size,createdTime,modifiedTime,thumbnailLink,parents,trashed,webViewLink',
    ));
    $parents = isset($file['parents']) && is_array($file['parents']) ? $file['parents'] : array();
    if (!empty($file['trashed'])) throw new RuntimeException('File sudah masuk Trash Google Drive.');
    if ($expectedParentId !== '') {
        if (!in_array($expectedParentId, $parents, true)) throw new RuntimeException('Foto tidak berada di folder yang sedang dibuka. Muat ulang galeri.');
    } else {
        $rootId = rfg_folder_id();
        $belongs = false;
        foreach ($parents as $parentId) {
            try { rfg_assert_folder_in_gallery($parentId); $belongs = true; break; } catch (Exception $ignored) {}
        }
        if (!$belongs && !in_array($rootId, $parents, true)) throw new RuntimeException('File tidak ditemukan di dalam galeri RF Studio.');
    }
    $allowed = array('image/jpeg', 'image/png', 'image/webp');
    if (empty($file['mimeType']) || !in_array($file['mimeType'], $allowed, true)) {
        throw new RuntimeException('File bukan gambar yang didukung oleh galeri.');
    }
    return $file;
}

function rfg_upload_file($filePath, $name, $mimeType, $targetFolderId = '')
{
    $folderId = $targetFolderId !== '' ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $targetFolderId) : rfg_folder_id();
    if ($folderId === '') throw new RuntimeException('Folder tujuan upload tidak valid.');
    rfg_assert_folder_in_gallery($folderId);
    $size = (int) filesize($filePath);
    if ($size < 1) throw new RuntimeException('File kosong atau tidak dapat dibaca.');

    $metadata = array('name' => $name, 'mimeType' => $mimeType, 'parents' => array($folderId));
    $metadataJson = json_encode($metadata);
    if ($metadataJson === false) throw new RuntimeException('Metadata file gagal disiapkan.');

    $initUrl = 'https://www.googleapis.com/upload/drive/v3/files?' . http_build_query(array(
        'uploadType' => 'resumable',
        'fields' => 'id,name,mimeType,size,createdTime,thumbnailLink,parents',
    ));
    $accessToken = rfg_access_token();
    rfg_log('UPLOAD_INIT_START name=' . $name . ' bytes=' . $size . ' target_folder_id=' . $folderId);
    $response = rfg_http('POST', $initUrl, array(
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json; charset=UTF-8',
        'Content-Length: ' . strlen($metadataJson),
        'X-Upload-Content-Type: ' . $mimeType,
        'X-Upload-Content-Length: ' . $size,
    ), $metadataJson, array('timeout' => 45));

    $sessionUrl = isset($response['headers']['location']) ? $response['headers']['location'] : '';
    if ($response['status'] < 200 || $response['status'] >= 300 || $sessionUrl === '') {
        $data = json_decode($response['body'], true);
        $message = is_array($data) && isset($data['error']['message']) ? $data['error']['message'] : 'Google tidak mengembalikan URL sesi upload.';
        rfg_log('UPLOAD_INIT_FAILED http=' . $response['status'] . ' message=' . substr($message, 0, 500));
        throw new RuntimeException('Google gagal memulai upload (HTTP ' . $response['status'] . '): ' . $message);
    }

    $uploadResponse = rfg_http('PUT', $sessionUrl, array(
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: ' . $mimeType,
        'Content-Length: ' . $size,
    ), null, array('upload_file' => $filePath, 'timeout' => 300));
    $data = json_decode($uploadResponse['body'], true);
    if ($uploadResponse['status'] < 200 || $uploadResponse['status'] >= 300 || !is_array($data) || empty($data['id'])) {
        $message = is_array($data) && isset($data['error']['message']) ? $data['error']['message'] : trim(substr((string) $uploadResponse['body'], 0, 500));
        if ($message === '') $message = 'Google tidak mengembalikan metadata file setelah menerima data.';
        rfg_log('UPLOAD_CONTENT_FAILED http=' . $uploadResponse['status'] . ' message=' . substr($message, 0, 500));
        throw new RuntimeException('Upload Google Drive gagal (HTTP ' . $uploadResponse['status'] . '): ' . $message);
    }
    rfg_usage_record('upload_success', 5, 0, $size, 1);
    rfg_log('UPLOAD_SUCCESS drive_file_id=' . $data['id'] . ' name=' . $name . ' bytes=' . $size . ' parent=' . $folderId);
    return $data;
}

function rfg_download_to_file($fileId, $destination, $expectedParentId = '')
{
    // Validasi folder dan tipe file dilakukan sebelum data ditarik dari Drive.
    $meta = rfg_file_metadata($fileId, $expectedParentId);
    $token = rfg_access_token();
    $response = rfg_http('GET', 'https://www.googleapis.com/drive/v3/files/' . rawurlencode($fileId) . '?alt=media', array(
        'Authorization: Bearer ' . $token,
    ), null, array('timeout' => 180));
    if ($response['status'] < 200 || $response['status'] >= 300) {
        throw new RuntimeException('Gagal mengambil file dari Google Drive (HTTP ' . $response['status'] . ').');
    }
    rfg_usage_record('download_original', 200, strlen($response['body']), 0, 0);
    if (file_put_contents($destination, $response['body'], LOCK_EX) === false) {
        throw new RuntimeException('File sementara gagal ditulis. Periksa ruang disk hosting.');
    }
    return $meta;
}

/**
 * Download the original image bytes to a private temporary file without resizing or recompressing.
 * The cURL response is streamed to disk to avoid holding a full-resolution photo in PHP memory.
 */
function rfg_download_original_to_file($fileId, $destination, $expectedParentId = '')
{
    $meta = rfg_file_metadata($fileId, $expectedParentId);
    $cfg = rfg_get_config();
    $maxBytes = isset($cfg['max_preview_file_bytes']) ? (int) $cfg['max_preview_file_bytes'] : (200 * 1024 * 1024);
    if ($maxBytes > 0 && isset($meta['size']) && (int) $meta['size'] > $maxBytes) {
        throw new RuntimeException('Foto terlalu besar untuk preview langsung (' . number_format(((int) $meta['size']) / 1048576, 1, ',', '.') . ' MB). Gunakan tombol download.');
    }

    $token = rfg_access_token();
    $url = 'https://www.googleapis.com/drive/v3/files/' . rawurlencode($fileId) . '?alt=media';
    $stream = @fopen($destination, 'wb');
    if (!$stream) throw new RuntimeException('File sementara preview tidak dapat dibuat. Periksa ruang dan izin direktori privat.');

    $ch = curl_init($url);
    if ($ch === false) {
        fclose($stream);
        throw new RuntimeException('Tidak dapat memulai koneksi preview ke Google Drive.');
    }
    $curlOptions = array(
        CURLOPT_FILE => $stream,
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_HTTPGET => true,
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $token, 'Accept: */*'),
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    );
    curl_setopt_array($ch, $curlOptions);
    $ok = curl_exec($ch);
    $curlError = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($stream);

    if ($ok === false) {
        @unlink($destination);
        throw new RuntimeException('Gagal mengambil foto asli dari Google Drive: ' . $curlError);
    }
    if ($status < 200 || $status >= 300) {
        $errorBody = @file_get_contents($destination, false, null, 0, 4096);
        @unlink($destination);
        $errorData = json_decode((string) $errorBody, true);
        $message = is_array($errorData) && isset($errorData['error']['message']) ? $errorData['error']['message'] : 'Google Drive mengembalikan HTTP ' . $status . '.';
        throw new RuntimeException('Preview foto asli gagal: ' . $message);
    }

    $bytes = @filesize($destination);
    if ($bytes === false || $bytes < 1) {
        @unlink($destination);
        throw new RuntimeException('Google Drive tidak mengirimkan konten foto.');
    }
    if (isset($meta['size']) && (int) $meta['size'] > 0 && (int) $bytes !== (int) $meta['size']) {
        @unlink($destination);
        throw new RuntimeException('Ukuran foto yang diterima tidak sesuai metadata Google Drive. Silakan coba lagi.');
    }

    rfg_usage_record('preview_original', 200, (int) $bytes, 0, 0);
    return $meta;
}

function rfg_thumbnail_content($fileId, $expectedParentId = '')
{
    $meta = null;
    $usedCache = false;
    if ($expectedParentId !== '') {
        rfg_assert_folder_in_gallery($expectedParentId);
        if (function_exists('rfg_gallery_photo_cached_meta')) {
            $cached = rfg_gallery_photo_cached_meta($fileId, $expectedParentId, 300);
            if ($cached && !empty($cached['thumbnailLink'])) {
                $meta = $cached;
                $usedCache = true;
            }
        }
    }
    if ($meta === null) $meta = rfg_file_metadata($fileId, $expectedParentId);
    if (empty($meta['thumbnailLink'])) {
        throw new RuntimeException('Google Drive belum menyediakan thumbnail untuk foto ini.');
    }
    $token = rfg_access_token();
    $response = rfg_http('GET', $meta['thumbnailLink'], array('Authorization: Bearer ' . $token), null, array('timeout' => 45));
    if (($response['status'] < 200 || $response['status'] >= 300 || $response['body'] === '') && $usedCache) {
        // URL thumbnail cache mungkin kedaluwarsa; validasi metadata terbaru sekali lalu coba lagi.
        $freshMeta = rfg_file_metadata($fileId, $expectedParentId);
        if (!empty($freshMeta['thumbnailLink']) && $freshMeta['thumbnailLink'] !== $meta['thumbnailLink']) {
            $response = rfg_http('GET', $freshMeta['thumbnailLink'], array('Authorization: Bearer ' . $token), null, array('timeout' => 45));
        }
    }
    if ($response['status'] < 200 || $response['status'] >= 300 || $response['body'] === '') {
        throw new RuntimeException('Thumbnail belum dapat diambil dari Google Drive.');
    }
    rfg_usage_record('thumbnail_download', 200, strlen($response['body']), 0, 0);
    return array('body' => $response['body'], 'content_type' => isset($response['headers']['content-type']) ? $response['headers']['content-type'] : 'image/jpeg');
}


/**
 * ESTIMASI PENGGUNAAN MODUL RF STUDIO
 * Data hanya menghitung operasi yang dilewatkan melalui modul ini.
 * Ini bukan angka kuota resmi yang dilaporkan Google Cloud.
 */
function rfg_estimate_method_quota_units($method, $path, $query = array())
{
    $method = strtoupper((string) $method);
    $path = trim((string) $path, '/');
    if ($method === 'GET' && $path === 'files') {
        return 100; // list files
    }
    if ($method === 'GET' && preg_match('#^files/[^/]+/permissions$#', $path)) {
        return 100; // permission listing estimated as list operation
    }
    if ($method === 'GET' && preg_match('#^files/[^/]+$#', $path)) {
        return 5; // file metadata (actual media download tracked separately)
    }
    return 5; // other operations: estimate, subject to Google's method-specific quota
}

function rfg_usage_record($operation, $units = 0, $egressBytes = 0, $uploadBytes = 0, $uploadCount = 0)
{
    if (!defined('RFG_PRIVATE_DIR') || !is_dir(RFG_PRIVATE_DIR) || !is_writable(RFG_PRIVATE_DIR)) {
        return;
    }
    $path = RFG_PRIVATE_DIR . '/drive-gallery-usage.json';
    $lock = @fopen(RFG_PRIVATE_DIR . '/drive-gallery-usage.lock', 'c+');
    if (!$lock) {
        return;
    }
    if (!@flock($lock, LOCK_EX)) {
        fclose($lock);
        return;
    }
    $data = array('minutes' => array(), 'days' => array(), 'monitoring_since' => date('c'));
    if (is_file($path)) {
        $old = json_decode((string) @file_get_contents($path), true);
        if (is_array($old)) {
            $data = array_merge($data, $old);
            if (!isset($data['minutes']) || !is_array($data['minutes'])) $data['minutes'] = array();
            if (!isset($data['days']) || !is_array($data['days'])) $data['days'] = array();
        }
    }
    $now = time();
    $minuteKey = (string) floor($now / 60);
    $dayKey = date('Y-m-d', $now);
    if (!isset($data['minutes'][$minuteKey])) {
        $data['minutes'][$minuteKey] = array('units' => 0, 'api_calls' => 0, 'egress_bytes' => 0);
    }
    if (!isset($data['days'][$dayKey])) {
        $data['days'][$dayKey] = array('units' => 0, 'api_calls' => 0, 'egress_bytes' => 0, 'upload_bytes' => 0, 'uploads' => 0);
    }
    $units = max(0, (int) $units);
    $egressBytes = max(0, (int) $egressBytes);
    $uploadBytes = max(0, (int) $uploadBytes);
    $uploadCount = max(0, (int) $uploadCount);
    foreach (array('minutes' => $minuteKey, 'days' => $dayKey) as $collection => $key) {
        $data[$collection][$key]['units'] += $units;
        if ($units > 0) $data[$collection][$key]['api_calls'] += 1;
        $data[$collection][$key]['egress_bytes'] += $egressBytes;
        if ($collection === 'days') {
            $data[$collection][$key]['upload_bytes'] += $uploadBytes;
            $data[$collection][$key]['uploads'] += $uploadCount;
        }
    }
    // Hindari file statistik tumbuh tanpa batas: simpan bucket menit 48 jam dan data harian 60 hari.
    foreach ($data['minutes'] as $key => $value) {
        if ((int) $key < floor($now / 60) - 2880) unset($data['minutes'][$key]);
    }
    foreach ($data['days'] as $key => $value) {
        if ($key < date('Y-m-d', $now - 60 * 86400)) unset($data['days'][$key]);
    }
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json !== false) {
        @file_put_contents($path, $json, LOCK_EX);
        @chmod($path, 0600);
    }
    @flock($lock, LOCK_UN);
    fclose($lock);
}

function rfg_usage_summary()
{
    $empty = array('minute_units' => 0, 'minute_calls' => 0, 'today_units' => 0, 'today_calls' => 0, 'today_egress_bytes' => 0, 'today_upload_bytes' => 0, 'today_uploads' => 0, 'monitoring_since' => null);
    if (!defined('RFG_PRIVATE_DIR')) return $empty;
    $path = RFG_PRIVATE_DIR . '/drive-gallery-usage.json';
    if (!is_file($path)) return $empty;
    $data = json_decode((string) @file_get_contents($path), true);
    if (!is_array($data)) return $empty;
    $minuteKey = (string) floor(time() / 60);
    $dayKey = date('Y-m-d');
    $minute = isset($data['minutes'][$minuteKey]) && is_array($data['minutes'][$minuteKey]) ? $data['minutes'][$minuteKey] : array();
    $day = isset($data['days'][$dayKey]) && is_array($data['days'][$dayKey]) ? $data['days'][$dayKey] : array();
    return array(
        'minute_units' => isset($minute['units']) ? (int) $minute['units'] : 0,
        'minute_calls' => isset($minute['api_calls']) ? (int) $minute['api_calls'] : 0,
        'today_units' => isset($day['units']) ? (int) $day['units'] : 0,
        'today_calls' => isset($day['api_calls']) ? (int) $day['api_calls'] : 0,
        'today_egress_bytes' => isset($day['egress_bytes']) ? (int) $day['egress_bytes'] : 0,
        'today_upload_bytes' => isset($day['upload_bytes']) ? (int) $day['upload_bytes'] : 0,
        'today_uploads' => isset($day['uploads']) ? (int) $day['uploads'] : 0,
        'monitoring_since' => isset($data['monitoring_since']) ? $data['monitoring_since'] : null,
    );
}

function rfg_share_state_path()
{
    return RFG_PRIVATE_DIR . '/drive-sharing-state.json';
}

function rfg_share_states_read()
{
    $path = rfg_share_state_path();
    if (!is_file($path)) return array();
    $data = json_decode((string) @file_get_contents($path), true);
    return is_array($data) ? $data : array();
}

function rfg_share_state_set($fileId, $enabled, $url = '')
{
    $states = rfg_share_states_read();
    $states[$fileId] = array('enabled' => (bool) $enabled, 'url' => $enabled ? (string) $url : '', 'updated_at' => date('c'));
    $path = rfg_share_state_path();
    if (@file_put_contents($path, json_encode($states, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
        throw new RuntimeException('Status tautan berbagi tidak bisa disimpan di direktori privat.');
    }
    @chmod($path, 0600);
}

function rfg_share_link($fileId)
{
    return 'https://drive.google.com/file/d/' . rawurlencode($fileId) . '/view?usp=sharing';
}

/** Atur izin link publik untuk file foto tertentu. File default tetap privat. */
function rfg_set_link_sharing($fileId, $enable, $expectedParentId = '')
{
    // Memastikan file ini milik folder galeri dan tipenya adalah gambar yang didukung.
    rfg_file_metadata($fileId, $expectedParentId);
    $permissions = rfg_api_request('GET', 'files/' . rawurlencode($fileId) . '/permissions', array(
        'fields' => 'permissions(id,type,role,allowFileDiscovery,permissionDetails)',
    ));
    $anyone = array();
    foreach ((isset($permissions['permissions']) && is_array($permissions['permissions'])) ? $permissions['permissions'] : array() as $permission) {
        if (isset($permission['type']) && $permission['type'] === 'anyone') {
            $inherited = false;
            if (!empty($permission['permissionDetails']) && is_array($permission['permissionDetails'])) {
                foreach ($permission['permissionDetails'] as $detail) {
                    if (!empty($detail['inherited'])) $inherited = true;
                }
            }
            $anyone[] = array('id' => isset($permission['id']) ? $permission['id'] : '', 'inherited' => $inherited);
        }
    }
    if ($enable) {
        if (empty($anyone)) {
            rfg_api_request('POST', 'files/' . rawurlencode($fileId) . '/permissions', array(
                'fields' => 'id,type,role,allowFileDiscovery',
            ), array('type' => 'anyone', 'role' => 'reader', 'allowFileDiscovery' => false));
        }
        $url = rfg_share_link($fileId);
        rfg_share_state_set($fileId, true, $url);
        rfg_log('SHARE_LINK_ENABLED file_id=' . $fileId);
        return array('enabled' => true, 'url' => $url);
    }
    foreach ($anyone as $permission) {
        if (!empty($permission['inherited'])) {
            throw new RuntimeException('Izin tautan publik berasal dari folder induk dan tidak dapat dimatikan dari pengaturan file ini. Periksa permission folder di Google Drive.');
        }
        if ($permission['id'] !== '') {
            rfg_api_request('DELETE', 'files/' . rawurlencode($fileId) . '/permissions/' . rawurlencode($permission['id']));
        }
    }
    rfg_share_state_set($fileId, false, '');
    rfg_log('SHARE_LINK_DISABLED file_id=' . $fileId);
    return array('enabled' => false, 'url' => '');
}
