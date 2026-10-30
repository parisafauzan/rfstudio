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

function rfg_list_files($pageToken = '')
{
    $folderId = rfg_folder_id();
    $query = array(
        'q' => "'" . str_replace("'", "\\'", $folderId) . "' in parents and trashed = false",
        'pageSize' => 50,
        'orderBy' => 'createdTime desc',
        'fields' => 'nextPageToken,files(id,name,mimeType,size,createdTime,thumbnailLink,parents)',
    );
    if ($pageToken !== '') {
        $query['pageToken'] = $pageToken;
    }
    return rfg_api_request('GET', 'files', $query);
}

function rfg_file_metadata($fileId)
{
    $file = rfg_api_request('GET', 'files/' . rawurlencode($fileId), array(
        'fields' => 'id,name,mimeType,size,createdTime,thumbnailLink,parents,trashed',
    ));
    $folderId = rfg_folder_id();
    if (!empty($file['trashed']) || empty($file['parents']) || !in_array($folderId, $file['parents'], true)) {
        throw new RuntimeException('File tidak ditemukan di folder galeri RF Studio.');
    }
    $allowed = array('image/jpeg', 'image/png', 'image/webp');
    if (empty($file['mimeType']) || !in_array($file['mimeType'], $allowed, true)) {
        throw new RuntimeException('File bukan gambar yang didukung oleh galeri.');
    }
    return $file;
}

function rfg_upload_file($filePath, $name, $mimeType)
{
    $folderId = rfg_folder_id();
    $size = (int) filesize($filePath);
    if ($size < 1) {
        throw new RuntimeException('File kosong atau tidak dapat dibaca.');
    }

    $metadata = array(
        'name' => $name,
        'mimeType' => $mimeType,
        'parents' => array($folderId),
    );
    $metadataJson = json_encode($metadata);
    if ($metadataJson === false) {
        throw new RuntimeException('Metadata file gagal disiapkan.');
    }

    $initUrl = 'https://www.googleapis.com/upload/drive/v3/files?' . http_build_query(array(
        'uploadType' => 'resumable',
        'fields' => 'id,name,mimeType,size,createdTime,thumbnailLink,parents',
    ));
    $accessToken = rfg_access_token();
    rfg_log('UPLOAD_INIT_START name=' . $name . ' bytes=' . $size);

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
        if ($message === '') {
            $message = 'Google tidak mengembalikan metadata file setelah menerima data.';
        }
        rfg_log('UPLOAD_CONTENT_FAILED http=' . $uploadResponse['status'] . ' message=' . substr($message, 0, 500));
        throw new RuntimeException('Upload Google Drive gagal (HTTP ' . $uploadResponse['status'] . '): ' . $message);
    }

    rfg_log('UPLOAD_SUCCESS drive_file_id=' . (isset($data['id']) ? $data['id'] : 'unknown') . ' name=' . $name . ' bytes=' . $size);
    return $data;
}

function rfg_download_to_file($fileId, $destination)
{
    // Validasi folder dan tipe file dilakukan sebelum data ditarik dari Drive.
    $meta = rfg_file_metadata($fileId);
    $token = rfg_access_token();
    $response = rfg_http('GET', 'https://www.googleapis.com/drive/v3/files/' . rawurlencode($fileId) . '?alt=media', array(
        'Authorization: Bearer ' . $token,
    ), null, array('timeout' => 180));
    if ($response['status'] < 200 || $response['status'] >= 300) {
        throw new RuntimeException('Gagal mengambil file dari Google Drive (HTTP ' . $response['status'] . ').');
    }
    if (file_put_contents($destination, $response['body'], LOCK_EX) === false) {
        throw new RuntimeException('File sementara gagal ditulis. Periksa ruang disk hosting.');
    }
    return $meta;
}

function rfg_thumbnail_content($fileId)
{
    $meta = rfg_file_metadata($fileId);
    if (empty($meta['thumbnailLink'])) {
        throw new RuntimeException('Google Drive belum menyediakan thumbnail untuk foto ini.');
    }
    $token = rfg_access_token();
    $response = rfg_http('GET', $meta['thumbnailLink'], array('Authorization: Bearer ' . $token), null, array('timeout' => 45));
    if ($response['status'] < 200 || $response['status'] >= 300 || $response['body'] === '') {
        throw new RuntimeException('Thumbnail belum dapat diambil dari Google Drive.');
    }
    return array('body' => $response['body'], 'content_type' => isset($response['headers']['content-type']) ? $response['headers']['content-type'] : 'image/jpeg');
}
