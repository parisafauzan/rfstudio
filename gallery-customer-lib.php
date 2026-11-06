<?php
/**
 * Helper galeri customer. Tidak memerlukan login admin dan tidak pernah
 * mengirim token Google Drive ke browser.
 */
if (session_status() === PHP_SESSION_NONE) session_start();

if (!defined('RFG_PRIVATE_DIR')) {
    $privateDir = getenv('RFG_PRIVATE_DIR');
    if (!$privateDir) {
        $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', (string) $_SERVER['DOCUMENT_ROOT']) : '';
        $publicHtmlPos = stripos($docRoot, '/public_html');
        if ($publicHtmlPos !== false) {
            $accountHome = rtrim(substr($docRoot, 0, $publicHtmlPos), '/');
            $productionDir = $accountHome . '/rfstudio-drive-private';
            $legacyDir = $accountHome . '/rfstudio-drive-private-staging';
            $privateDir = is_dir($productionDir) ? $productionDir : $legacyDir;
        } elseif (DIRECTORY_SEPARATOR === '\\' || stripos(PHP_OS, 'WIN') === 0) {
            $privateDir = 'C:/xampp/rfstudio-drive-private';
        } else {
            $privateDir = dirname(__DIR__) . '/rfstudio-drive-private';
        }
    }
    define('RFG_PRIVATE_DIR', $privateDir);
}

$configFile = RFG_PRIVATE_DIR . '/config.php';
$GLOBALS['RFG_CONFIG'] = is_file($configFile) ? include $configFile : null;

require_once __DIR__ . '/drive-gallery/drive-api.php';
require_once __DIR__ . '/drive-gallery/gallery-db.php';

function rfg_config_ready()
{
    $cfg = isset($GLOBALS['RFG_CONFIG']) ? $GLOBALS['RFG_CONFIG'] : null;
    if (!is_array($cfg)) return false;
    foreach (array('client_id', 'client_secret', 'redirect_uri', 'folder_name') as $key) {
        if (empty($cfg[$key]) || strpos((string) $cfg[$key], 'PASTE_') === 0) return false;
    }
    return extension_loaded('curl') && function_exists('random_bytes');
}

function rfg_customer_h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rfg_customer_csrf_token()
{
    if (empty($_SESSION['rfg_customer_csrf'])) $_SESSION['rfg_customer_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['rfg_customer_csrf'];
}

function rfg_customer_csrf_valid($token)
{
    return isset($_SESSION['rfg_customer_csrf']) && is_string($token) && $token !== '' && hash_equals($_SESSION['rfg_customer_csrf'], $token);
}

function rfg_customer_flash($type, $message)
{
    $_SESSION['rfg_customer_flash'] = array('type' => $type, 'message' => $message);
}

function rfg_customer_take_flash()
{
    $flash = isset($_SESSION['rfg_customer_flash']) ? $_SESSION['rfg_customer_flash'] : null;
    unset($_SESSION['rfg_customer_flash']);
    return $flash;
}

function rfg_customer_table_exists($table)
{
    $allowed = array('data_booking_cobahampirfinishjuga', 'data_history', 'data_konfirmasi_cobahampirfinishjuga');
    if (!in_array($table, $allowed, true)) return false;
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare('SELECT 1 FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=? LIMIT 1');
    if (!$stmt) return false;
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $one = null;
    $stmt->bind_result($one);
    $exists = $stmt->fetch();
    $stmt->close();
    return (bool) $exists;
}

function rfg_customer_booking_rows($email)
{
    $email = strtolower(trim((string) $email));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return array();
    $db = rfg_gallery_db_connect();
    $tables = array('data_booking_cobahampirfinishjuga', 'data_history', 'data_konfirmasi_cobahampirfinishjuga');
    $rows = array();
    foreach ($tables as $table) {
        if (!rfg_customer_table_exists($table)) continue;
        $sql = 'SELECT uniqid,nama,email,package,tipe_package,tanggal,jam,studio FROM `' . $table . '` WHERE LOWER(TRIM(email))=? ORDER BY tanggal DESC, id DESC';
        $stmt = $db->prepare($sql);
        if (!$stmt) continue;
        $stmt->bind_param('s', $email);
        if (!$stmt->execute()) { $stmt->close(); continue; }
        $uniqid = $name = $rowEmail = $package = $packageType = $date = $time = $studio = null;
        $stmt->bind_result($uniqid, $name, $rowEmail, $package, $packageType, $date, $time, $studio);
        while ($stmt->fetch()) {
            $key = trim((string) $uniqid);
            if ($key === '' || isset($rows[$key])) continue;
            $rows[$key] = array(
                'uniqid' => $key,
                'nama' => $name,
                'email' => strtolower(trim((string) $rowEmail)),
                'package' => $package,
                'tipe_package' => $packageType,
                'tanggal' => $date,
                'jam' => $time,
                'studio' => $studio,
                'legacy_table' => $table,
            );
        }
        $stmt->close();
    }
    return array_values($rows);
}

function rfg_customer_session_by_booking($bookingUniqId)
{
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare("SELECT id,booking_uniqid,nama_pelanggan,studio,tanggal_booking,drive_folder_id,status,originals_synced_at,edited_synced_at FROM gallery_sessions WHERE booking_uniqid=? ORDER BY CASE status WHEN 'active' THEN 0 WHEN 'inactive' THEN 1 ELSE 2 END, id DESC LIMIT 1");
    if (!$stmt) throw new RuntimeException('Query sesi galeri gagal disiapkan.');
    $stmt->bind_param('s', $bookingUniqId);
    if (!$stmt->execute()) { $stmt->close(); throw new RuntimeException('Sesi galeri gagal dibaca.'); }
    $id = $uid = $name = $studio = $date = $folderId = $status = $originalsSynced = $editedSynced = null;
    $stmt->bind_result($id, $uid, $name, $studio, $date, $folderId, $status, $originalsSynced, $editedSynced);
    $has = $stmt->fetch();
    $row = $has ? array(
        'id' => (int) $id, 'booking_uniqid' => $uid, 'nama_pelanggan' => $name,
        'studio' => $studio, 'tanggal_booking' => $date, 'drive_folder_id' => $folderId,
        'status' => $status, 'originals_synced_at' => $originalsSynced, 'edited_synced_at' => $editedSynced,
    ) : null;
    $stmt->close();
    return $row;
}

function rfg_customer_galleries($email)
{
    $result = array();
    foreach (rfg_customer_booking_rows($email) as $booking) {
        $session = rfg_customer_session_by_booking($booking['uniqid']);
        if (!$session) continue;
        $session['booking'] = $booking;
        $session['edit_request'] = rfg_gallery_edit_request_for_session($session['id']);
        $result[] = $session;
    }
    return $result;
}

function rfg_customer_gallery_for_email($sessionId, $email)
{
    $sessionId = (int) $sessionId;
    foreach (rfg_customer_galleries($email) as $gallery) {
        if ((int) $gallery['id'] === $sessionId) return $gallery;
    }
    return null;
}

function rfg_customer_grant_access($sessionId, $email)
{
    if (!isset($_SESSION['rfg_customer_gallery_access']) || !is_array($_SESSION['rfg_customer_gallery_access'])) $_SESSION['rfg_customer_gallery_access'] = array();
    $_SESSION['rfg_customer_gallery_access'][(int) $sessionId] = array(
        'email' => strtolower(trim((string) $email)),
        'expires' => time() + 7200,
    );
}

function rfg_customer_access_email($sessionId)
{
    $sessionId = (int) $sessionId;
    if (empty($_SESSION['rfg_customer_gallery_access'][$sessionId])) return '';
    $access = $_SESSION['rfg_customer_gallery_access'][$sessionId];
    if (empty($access['expires']) || (int) $access['expires'] < time()) {
        unset($_SESSION['rfg_customer_gallery_access'][$sessionId]);
        return '';
    }
    return isset($access['email']) ? (string) $access['email'] : '';
}

function rfg_customer_find_photo_folder($customerFolderId, $category)
{
    $name = $category === 'edited' ? 'Edited' : 'Originals';
    rfg_assert_folder_in_gallery($customerFolderId);
    $query = "'" . rfg_drive_query_escape($customerFolderId) . "' in parents and name = '" . rfg_drive_query_escape($name) . "' and mimeType = 'application/vnd.google-apps.folder' and trashed = false";
    $result = rfg_api_request('GET', 'files', array('q' => $query, 'spaces' => 'drive', 'pageSize' => 10, 'fields' => 'files(id,name,mimeType,parents)'));
    return !empty($result['files'][0]['id']) ? $result['files'][0]['id'] : '';
}

function rfg_customer_photos($gallerySession, $category, $forceRefresh)
{
    $category = $category === 'edited' ? 'edited' : 'original';
    $folderId = rfg_customer_find_photo_folder($gallerySession['drive_folder_id'], $category);
    if ($folderId === '') {
        rfg_gallery_mark_category_missing((int) $gallerySession['id'], $category);
        return array();
    }
    $fresh = !$forceRefresh && rfg_gallery_session_cache_is_fresh((int) $gallerySession['id'], $category, 300);
    if (!$fresh) {
        $drivePhotos = rfg_list_folder_images_all($folderId);
        rfg_gallery_cache_sync_photos((int) $gallerySession['id'], $folderId, $category, $drivePhotos);
    }
    $photos = array();
    $offset = 0;
    do {
        $page = rfg_gallery_cached_photo_list((int) $gallerySession['id'], $folderId, $category, '', $offset, 100);
        foreach ($page['files'] as $photo) $photos[] = $photo;
        $hasNext = !empty($page['nextPageToken']);
        $offset += 100;
    } while ($hasNext && $offset < 2000);
    return $photos;
}

function rfg_customer_photo_page($gallerySession, $category, $forceRefresh, $offset, $limit)
{
    $category = $category === 'edited' ? 'edited' : 'original';
    $offset = max(0, (int) $offset);
    $limit = max(1, min(1000, (int) $limit));
    $folderId = rfg_customer_find_photo_folder($gallerySession['drive_folder_id'], $category);
    if ($folderId === '') {
        rfg_gallery_mark_category_missing((int) $gallerySession['id'], $category);
        return array('files' => array(), 'total' => 0, 'loaded' => $offset, 'has_more' => false, 'folder_id' => '');
    }
    $fresh = !$forceRefresh && rfg_gallery_session_cache_is_fresh((int) $gallerySession['id'], $category, 300);
    if (!$fresh) {
        $drivePhotos = rfg_list_folder_images_all($folderId);
        rfg_gallery_cache_sync_photos((int) $gallerySession['id'], $folderId, $category, $drivePhotos);
    }
    $page = rfg_gallery_cached_photo_list((int) $gallerySession['id'], $folderId, $category, '', $offset, $limit);
    $loaded = $offset + count($page['files']);
    return array(
        'files' => $page['files'],
        'total' => (int) $page['total'],
        'loaded' => $loaded,
        'has_more' => $loaded < (int) $page['total'],
        'folder_id' => $folderId,
    );
}

function rfg_customer_photo_card_html($photo, $sessionId, $category, $selectedMap, $requestLocked)
{
    $numericId = isset($photo['photo_id']) ? (int) $photo['photo_id'] : 0;
    $driveId = isset($photo['id']) ? (string) $photo['id'] : '';
    $name = isset($photo['name']) ? (string) $photo['name'] : 'Foto';
    $selectedForEdit = isset($selectedMap[$driveId]);
    $canEditSelect = $category === 'original' && !$requestLocked && $numericId > 0;
    ob_start();
    ?>
    <div class="col mb-3 customer-photo-item"><article class="gallery-photo <?php echo $selectedForEdit ? 'is-selected' : ''; ?>">
      <label class="photo-check"><input class="form-check-input me-1 photo-choice" type="checkbox" value="<?php echo $numericId; ?>" <?php echo $canEditSelect ? 'name="photo_ids[]"' : ''; ?> <?php echo $selectedForEdit ? 'checked' : ''; ?>> <span><?php echo $selectedForEdit ? 'Dipilih edit' : 'Pilih'; ?></span></label>
      <button class="photo-frame js-preview is-loading" type="button" data-src="gallery-media?session=<?php echo (int) $sessionId; ?>&amp;photo=<?php echo $numericId; ?>&amp;mode=preview" data-title="<?php echo rfg_customer_h($name); ?>"><span class="photo-skeleton" aria-hidden="true"></span><img loading="lazy" src="gallery-media?session=<?php echo (int) $sessionId; ?>&amp;photo=<?php echo $numericId; ?>&amp;mode=thumb" alt="<?php echo rfg_customer_h($name); ?>" onload="this.parentNode.classList.remove('is-loading')" onerror="this.parentNode.classList.remove('is-loading')"></button>
      <div class="photo-info"><strong class="photo-name" title="<?php echo rfg_customer_h($name); ?>"><?php echo rfg_customer_h($name); ?></strong><div class="small text-muted"><?php echo number_format(((int) $photo['size']) / 1048576, 2, ',', '.'); ?> MB</div><div class="photo-actions"><button class="btn btn-outline-dark js-preview" type="button" data-src="gallery-media?session=<?php echo (int) $sessionId; ?>&amp;photo=<?php echo $numericId; ?>&amp;mode=preview" data-title="<?php echo rfg_customer_h($name); ?>">Lihat Asli</button><a class="btn btn-dark" href="gallery-media?session=<?php echo (int) $sessionId; ?>&amp;photo=<?php echo $numericId; ?>&amp;mode=download">Download</a></div></div>
    </article></div>
    <?php
    return trim(ob_get_clean());
}

function rfg_customer_photo_row($sessionId, $photoId)
{
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare("SELECT p.id,p.gallery_session_id,p.drive_file_id,p.drive_parent_folder_id,p.filename,p.category,p.mime_type,p.size_bytes FROM gallery_photos p INNER JOIN gallery_sessions s ON s.id=p.gallery_session_id WHERE p.id=? AND p.gallery_session_id=? AND p.status='active' AND s.status='active' LIMIT 1");
    if (!$stmt) throw new RuntimeException('Query foto gagal disiapkan.');
    $sessionId = (int) $sessionId;
    $photoId = (int) $photoId;
    $stmt->bind_param('ii', $photoId, $sessionId);
    if (!$stmt->execute()) { $stmt->close(); throw new RuntimeException('Foto gagal dibaca.'); }
    $id = $gallerySessionId = $driveId = $parentId = $filename = $category = $mime = $size = null;
    $stmt->bind_result($id, $gallerySessionId, $driveId, $parentId, $filename, $category, $mime, $size);
    $has = $stmt->fetch();
    $row = $has ? array('id' => (int) $id, 'gallery_session_id' => (int) $gallerySessionId, 'drive_file_id' => $driveId, 'drive_parent_folder_id' => $parentId, 'filename' => $filename, 'category' => $category, 'mime_type' => $mime, 'size_bytes' => (int) $size) : null;
    $stmt->close();
    return $row;
}

function rfg_customer_photo_id_map($sessionId)
{
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare("SELECT id,drive_file_id FROM gallery_photos WHERE gallery_session_id=? AND status='active'");
    if (!$stmt) return array();
    $sessionId = (int) $sessionId;
    $stmt->bind_param('i', $sessionId);
    if (!$stmt->execute()) { $stmt->close(); return array(); }
    $id = $driveId = null;
    $stmt->bind_result($id, $driveId);
    $map = array();
    while ($stmt->fetch()) $map[$driveId] = (int) $id;
    $stmt->close();
    return $map;
}

function rfg_customer_edit_status_label($status)
{
    $labels = array('submitted' => 'Menunggu admin', 'editing' => 'Sedang diedit', 'completed' => 'Selesai', 'rejected' => 'Perlu diperbaiki');
    return isset($labels[$status]) ? $labels[$status] : 'Belum diajukan';
}

function rfg_customer_studio_label($studio)
{
    return stripos((string) $studio, 'kota') !== false ? 'Bekasi Timur Kota' : 'Bekasi Timur Kabupaten';
}
