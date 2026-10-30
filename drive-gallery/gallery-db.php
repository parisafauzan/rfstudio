<?php
/**
 * Indeks/cache metadata galeri MySQL.
 * Sumber kebenaran struktur folder dan keberadaan file tetap Google Drive.
 * Kompatibel PHP 7.4; kredensial DB disimpan di config.php privat.
 */

function rfg_gallery_db_config()
{
    $cfg = rfg_get_config();
    if (isset($cfg['database']) && is_array($cfg['database'])) {
        $db = $cfg['database'];
    } else {
        $db = array(
            'host' => isset($cfg['db_host']) ? $cfg['db_host'] : '',
            'name' => isset($cfg['db_name']) ? $cfg['db_name'] : '',
            'user' => isset($cfg['db_user']) ? $cfg['db_user'] : '',
            'password' => isset($cfg['db_pass']) ? $cfg['db_pass'] : '',
            'port' => isset($cfg['db_port']) ? $cfg['db_port'] : 3306,
        );
    }
    $db['host'] = isset($db['host']) ? trim((string) $db['host']) : '';
    $db['name'] = isset($db['name']) ? trim((string) $db['name']) : '';
    $db['user'] = isset($db['user']) ? trim((string) $db['user']) : '';
    $db['password'] = isset($db['password']) ? (string) $db['password'] : '';
    $db['port'] = isset($db['port']) ? (int) $db['port'] : 3306;
    return $db;
}

function rfg_gallery_db_configured()
{
    try {
        $db = rfg_gallery_db_config();
        return $db['host'] !== '' && $db['name'] !== '' && $db['user'] !== '';
    } catch (Throwable $e) {
        return false;
    }
}

function rfg_gallery_db_connect()
{
    static $connection = null;
    if ($connection instanceof mysqli) return $connection;
    if (!rfg_gallery_db_configured()) {
        throw new RuntimeException('Konfigurasi MySQL cache belum diisi pada config.php privat.');
    }
    if (!class_exists('mysqli')) {
        throw new RuntimeException('Ekstensi mysqli PHP belum aktif pada hosting.');
    }
    $cfg = rfg_gallery_db_config();
    mysqli_report(MYSQLI_REPORT_OFF);
    $connection = @new mysqli($cfg['host'], $cfg['user'], $cfg['password'], $cfg['name'], $cfg['port'] > 0 ? $cfg['port'] : 3306);
    if ($connection->connect_errno) {
        $message = 'Koneksi MySQL cache gagal (kode ' . (int) $connection->connect_errno . '). Periksa kredensial database privat.';
        $connection = null;
        throw new RuntimeException($message);
    }
    if (!$connection->set_charset('utf8mb4')) {
        throw new RuntimeException('MySQL tidak dapat menggunakan charset utf8mb4.');
    }
    return $connection;
}

function rfg_gallery_db_status()
{
    if (!rfg_gallery_db_configured()) {
        return array('configured' => false, 'ready' => false, 'message' => 'Kredensial MySQL cache belum dikonfigurasi; galeri tetap dapat bekerja langsung dari Drive, tetapi cache belum menghemat request API.');
    }
    try {
        $db = rfg_gallery_db_connect();
        $tables = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name IN ('gallery_sessions', 'gallery_photos')");
        $foundTables = array();
        if ($tables) while ($table = $tables->fetch_assoc()) $foundTables[$table['table_name']] = true;
        $hasSessions = isset($foundTables['gallery_sessions']);
        $hasPhotos = isset($foundTables['gallery_photos']);
        if (!$hasSessions || !$hasPhotos) {
            return array('configured' => true, 'ready' => false, 'message' => 'Koneksi MySQL aktif, tetapi tabel cache belum lengkap. Jalankan migration-gallery-cache.sql sekali melalui phpMyAdmin.');
        }
        $checkSessions = $db->query('SELECT id, booking_uniqid, nama_pelanggan, studio, tanggal_booking, drive_folder_id, status, originals_synced_at, edited_synced_at FROM gallery_sessions LIMIT 0');
        $checkPhotos = $db->query('SELECT id, gallery_session_id, drive_file_id, drive_parent_folder_id, filename, category, mime_type, size_bytes, created_at, drive_modified_at, thumbnail_link, status, last_seen_at, updated_at FROM gallery_photos LIMIT 0');
        if (!$checkSessions || !$checkPhotos) {
            return array('configured' => true, 'ready' => false, 'message' => 'Tabel cache ditemukan tetapi kolomnya belum sesuai versi modul ini. Periksa migration-gallery-cache.sql; jangan hapus tabel lama tanpa backup.');
        }
        return array('configured' => true, 'ready' => true, 'message' => 'Cache MySQL aktif. Metadata foto dibaca dari cache selama 5 menit; folder tetap dibaca langsung dari Google Drive.');
    } catch (Throwable $e) {
        if (function_exists('rfg_log')) rfg_log('GALLERY_DB_STATUS_ERROR ' . $e->getMessage());
        return array('configured' => true, 'ready' => false, 'message' => $e->getMessage());
    }
}

function rfg_gallery_db_normalize_date($value)
{
    $value = trim((string) $value);
    if ($value === '') throw new RuntimeException('Tanggal booking kosong untuk cache galeri.');
    $formats = array('!Y-m-d', '!Y-m-d H:i:s', '!d-m-Y', '!d/m/Y', '!d-m-Y H:i:s', '!d/m/Y H:i:s', '!m/d/Y');
    foreach ($formats as $format) {
        $date = DateTime::createFromFormat($format, $value);
        $errors = DateTime::getLastErrors();
        if ($date instanceof DateTime && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
            return $date->format('Y-m-d');
        }
    }
    $timestamp = strtotime($value);
    if ($timestamp !== false) return date('Y-m-d', $timestamp);
    throw new RuntimeException('Format tanggal booking tidak dikenali untuk cache galeri.');
}

function rfg_gallery_session_upsert($bookingUniqId, $customerName, $studio, $bookingDate, $customerFolderId)
{
    $bookingUniqId = trim((string) $bookingUniqId);
    $customerName = trim((string) $customerName);
    $studio = trim((string) $studio);
    $customerFolderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $customerFolderId);
    if ($bookingUniqId === '' || $customerFolderId === '') throw new RuntimeException('ID booking dan ID folder Drive diperlukan untuk gallery_sessions.');
    $dateSql = rfg_gallery_db_normalize_date($bookingDate);
    $db = rfg_gallery_db_connect();

    $select = $db->prepare('SELECT id FROM gallery_sessions WHERE booking_uniqid = ? LIMIT 1');
    if (!$select) throw new RuntimeException('Query gallery_sessions gagal disiapkan.');
    $select->bind_param('s', $bookingUniqId);
    if (!$select->execute()) throw new RuntimeException('Pencarian sesi galeri gagal.');
    $existingId = null;
    $select->bind_result($existingId);
    $hasExisting = $select->fetch();
    $select->close();

    if ($hasExisting && $existingId !== null) {
        $id = (int) $existingId;
        $stmt = $db->prepare("UPDATE gallery_sessions SET nama_pelanggan=?, studio=?, tanggal_booking=?, drive_folder_id=?, status='active', updated_at=NOW() WHERE id=?");
        if (!$stmt) throw new RuntimeException('Query update gallery_sessions gagal disiapkan.');
        $stmt->bind_param('ssssi', $customerName, $studio, $dateSql, $customerFolderId, $id);
    } else {
        $stmt = $db->prepare("INSERT INTO gallery_sessions (booking_uniqid, nama_pelanggan, studio, tanggal_booking, drive_folder_id, status) VALUES (?, ?, ?, ?, ?, 'active')");
        if (!$stmt) throw new RuntimeException('Query insert gallery_sessions gagal disiapkan.');
        $stmt->bind_param('sssss', $bookingUniqId, $customerName, $studio, $dateSql, $customerFolderId);
    }
    $ok = $stmt->execute();
    $error = $stmt->error;
    $stmt->close();
    if (!$ok) throw new RuntimeException('Gagal menyimpan gallery_sessions: ' . $error);
    return true;
}

function rfg_gallery_session_find_by_folder($customerFolderId)
{
    $customerFolderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $customerFolderId);
    if ($customerFolderId === '') return null;
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare('SELECT id, booking_uniqid, nama_pelanggan, studio, tanggal_booking, drive_folder_id, status, originals_synced_at, edited_synced_at FROM gallery_sessions WHERE drive_folder_id = ? AND status = \'active\' ORDER BY id DESC LIMIT 1');
    if (!$stmt) throw new RuntimeException('Query pencarian sesi galeri gagal disiapkan.');
    $stmt->bind_param('s', $customerFolderId);
    if (!$stmt->execute()) throw new RuntimeException('Pencarian sesi galeri gagal.');
    $id = $uid = $name = $studio = $bookingDate = $folderId = $status = $originalsSynced = $editedSynced = null;
    $stmt->bind_result($id, $uid, $name, $studio, $bookingDate, $folderId, $status, $originalsSynced, $editedSynced);
    $hasRow = $stmt->fetch();
    $row = $hasRow ? array(
        'id' => $id,
        'booking_uniqid' => $uid,
        'nama_pelanggan' => $name,
        'studio' => $studio,
        'tanggal_booking' => $bookingDate,
        'drive_folder_id' => $folderId,
        'status' => $status,
        'originals_synced_at' => $originalsSynced,
        'edited_synced_at' => $editedSynced,
    ) : null;
    $stmt->close();
    return $row;
}

function rfg_gallery_session_cache_is_fresh($sessionId, $category, $ttlSeconds)
{
    $column = $category === 'original' ? 'originals_synced_at' : 'edited_synced_at';
    $sessionId = (int) $sessionId;
    $ttlSeconds = max(1, min(86400, (int) $ttlSeconds));
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare('SELECT ' . $column . ' AS synced_at, CASE WHEN ' . $column . ' >= DATE_SUB(NOW(), INTERVAL ' . $ttlSeconds . ' SECOND) THEN 1 ELSE 0 END AS is_fresh FROM gallery_sessions WHERE id = ? LIMIT 1');
    if (!$stmt) throw new RuntimeException('Query waktu sinkronisasi gagal disiapkan.');
    $stmt->bind_param('i', $sessionId);
    if (!$stmt->execute()) throw new RuntimeException('Pembacaan waktu sinkronisasi gagal.');
    $syncedAt = $isFresh = null;
    $stmt->bind_result($syncedAt, $isFresh);
    $hasRow = $stmt->fetch();
    $stmt->close();
    return $hasRow && !empty($syncedAt) && (int) $isFresh === 1;
}

function rfg_gallery_photo_upsert_one($sessionId, $folderId, $category, $file)
{
    $mime = isset($file['mimeType']) ? (string) $file['mimeType'] : '';
    if (!in_array($mime, array('image/jpeg', 'image/png', 'image/webp'), true)) return false;
    $fileId = isset($file['id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $file['id']) : '';
    if ($fileId === '') return false;
    $parentId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    $filename = isset($file['name']) ? (string) $file['name'] : 'foto';
    if (function_exists('mb_substr')) $filename = mb_substr($filename, 0, 250, 'UTF-8'); else $filename = substr($filename, 0, 250);
    $size = isset($file['size']) ? max(0, (int) $file['size']) : 0;
    $created = !empty($file['createdTime']) && strtotime($file['createdTime']) !== false ? date('Y-m-d H:i:s', strtotime($file['createdTime'])) : null;
    $modified = !empty($file['modifiedTime']) && strtotime($file['modifiedTime']) !== false ? date('Y-m-d H:i:s', strtotime($file['modifiedTime'])) : null;
    $thumbnail = isset($file['thumbnailLink']) && $file['thumbnailLink'] !== '' ? (string) $file['thumbnailLink'] : null;
    $db = rfg_gallery_db_connect();
    $sql = "INSERT INTO gallery_photos (gallery_session_id, drive_file_id, drive_parent_folder_id, filename, category, mime_type, size_bytes, created_at, drive_modified_at, thumbnail_link, status, last_seen_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW()) ON DUPLICATE KEY UPDATE gallery_session_id=VALUES(gallery_session_id), drive_parent_folder_id=VALUES(drive_parent_folder_id), filename=VALUES(filename), category=VALUES(category), mime_type=VALUES(mime_type), size_bytes=VALUES(size_bytes), created_at=VALUES(created_at), drive_modified_at=VALUES(drive_modified_at), thumbnail_link=VALUES(thumbnail_link), status='active', last_seen_at=NOW(), updated_at=NOW()";
    $stmt = $db->prepare($sql);
    if (!$stmt) throw new RuntimeException('Query cache metadata foto gagal disiapkan.');
    $sessionId = (int) $sessionId;
    $stmt->bind_param('isssssisss', $sessionId, $fileId, $parentId, $filename, $category, $mime, $size, $created, $modified, $thumbnail);
    $ok = $stmt->execute();
    $error = $stmt->error;
    $stmt->close();
    if (!$ok) throw new RuntimeException('Gagal menyimpan metadata foto: ' . $error);
    return true;
}

function rfg_gallery_cache_sync_photos($sessionId, $folderId, $category, $files)
{
    if (!in_array($category, array('original', 'edited'), true)) throw new RuntimeException('Kategori cache foto tidak valid.');
    $sessionId = (int) $sessionId;
    $folderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    if ($sessionId < 1 || $folderId === '') throw new RuntimeException('Sesi atau folder foto tidak valid untuk sinkronisasi.');
    $db = rfg_gallery_db_connect();
    if (!$db->begin_transaction()) throw new RuntimeException('Tidak dapat memulai transaksi sinkronisasi cache foto.');
    try {
        $stmt = $db->prepare("UPDATE gallery_photos SET status='missing', updated_at=NOW() WHERE gallery_session_id=? AND drive_parent_folder_id=?");
        if (!$stmt) throw new RuntimeException('Query penandaan foto lama gagal disiapkan.');
        $stmt->bind_param('is', $sessionId, $folderId);
        if (!$stmt->execute()) throw new RuntimeException('Penandaan foto yang sudah tidak ada gagal.');
        $stmt->close();

        foreach ($files as $file) rfg_gallery_photo_upsert_one($sessionId, $folderId, $category, $file);

        $column = $category === 'original' ? 'originals_synced_at' : 'edited_synced_at';
        $stmt = $db->prepare('UPDATE gallery_sessions SET ' . $column . '=NOW(), updated_at=NOW() WHERE id=?');
        if (!$stmt) throw new RuntimeException('Query timestamp cache gagal disiapkan.');
        $stmt->bind_param('i', $sessionId);
        if (!$stmt->execute()) throw new RuntimeException('Penyimpanan timestamp cache gagal.');
        $stmt->close();
        $db->commit();
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    }
}

function rfg_gallery_cached_photo_list($sessionId, $folderId, $category, $nameFilter, $offset, $limit)
{
    $sessionId = (int) $sessionId;
    $folderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    $category = $category === 'original' ? 'original' : 'edited';
    $nameFilter = trim((string) $nameFilter);
    $offset = max(0, (int) $offset);
    $limit = max(1, min(100, (int) $limit));
    $db = rfg_gallery_db_connect();
    $where = "gallery_session_id=? AND drive_parent_folder_id=? AND category=? AND status='active'";
    if ($nameFilter !== '') $where .= ' AND filename LIKE ?';
    $countSql = 'SELECT COUNT(*) AS total FROM gallery_photos WHERE ' . $where;
    $stmt = $db->prepare($countSql);
    if (!$stmt) throw new RuntimeException('Query hitung cache foto gagal disiapkan.');
    if ($nameFilter !== '') {
        $like = '%' . $nameFilter . '%';
        $stmt->bind_param('isss', $sessionId, $folderId, $category, $like);
    } else {
        $stmt->bind_param('iss', $sessionId, $folderId, $category);
    }
    if (!$stmt->execute()) throw new RuntimeException('Hitung cache foto gagal.');
    $totalValue = 0;
    $stmt->bind_result($totalValue);
    $stmt->fetch();
    $total = (int) $totalValue;
    $stmt->close();

    $sql = 'SELECT drive_file_id AS id, filename AS name, mime_type AS mimeType, size_bytes AS size, created_at AS createdTime, drive_modified_at AS modifiedTime, thumbnail_link AS thumbnailLink, drive_parent_folder_id FROM gallery_photos WHERE ' . $where . ' ORDER BY filename LIMIT ? OFFSET ?';
    $stmt = $db->prepare($sql);
    if (!$stmt) throw new RuntimeException('Query daftar cache foto gagal disiapkan.');
    if ($nameFilter !== '') {
        $like = '%' . $nameFilter . '%';
        $stmt->bind_param('isssii', $sessionId, $folderId, $category, $like, $limit, $offset);
    } else {
        $stmt->bind_param('issii', $sessionId, $folderId, $category, $limit, $offset);
    }
    if (!$stmt->execute()) throw new RuntimeException('Pembacaan cache foto gagal.');
    $id = $name = $mimeType = $size = $createdTime = $modifiedTime = $thumbnailLink = $parentId = null;
    $stmt->bind_result($id, $name, $mimeType, $size, $createdTime, $modifiedTime, $thumbnailLink, $parentId);
    $files = array();
    while ($stmt->fetch()) {
        $files[] = array(
            'id' => $id,
            'name' => $name,
            'mimeType' => $mimeType,
            'size' => $size,
            'createdTime' => $createdTime,
            'modifiedTime' => $modifiedTime,
            'thumbnailLink' => $thumbnailLink,
            'drive_parent_folder_id' => $parentId,
        );
    }
    $stmt->close();
    $next = ($offset + count($files) < $total) ? 'cache:' . ($offset + $limit) : '';
    return array('files' => $files, 'nextPageToken' => $next, 'total' => $total);
}

function rfg_gallery_photo_cached_meta($fileId, $folderId, $maxAgeSeconds)
{
    if (!rfg_gallery_db_configured()) return null;
    $fileId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $fileId);
    $folderId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $folderId);
    if ($fileId === '' || $folderId === '') return null;
    try {
        $db = rfg_gallery_db_connect();
        $maxAgeSeconds = max(1, min(86400, (int) $maxAgeSeconds));
        $stmt = $db->prepare("SELECT drive_file_id AS id, filename AS name, mime_type AS mimeType, size_bytes AS size, created_at AS createdTime, drive_modified_at AS modifiedTime, thumbnail_link AS thumbnailLink, last_seen_at, CASE WHEN last_seen_at >= DATE_SUB(NOW(), INTERVAL " . $maxAgeSeconds . " SECOND) THEN 1 ELSE 0 END AS is_fresh FROM gallery_photos WHERE drive_file_id=? AND drive_parent_folder_id=? AND status='active' LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param('ss', $fileId, $folderId);
        if (!$stmt->execute()) { $stmt->close(); return null; }
        $id = $name = $mimeType = $size = $createdTime = $modifiedTime = $thumbnailLink = $lastSeenAt = $isFresh = null;
        $stmt->bind_result($id, $name, $mimeType, $size, $createdTime, $modifiedTime, $thumbnailLink, $lastSeenAt, $isFresh);
        $hasRow = $stmt->fetch();
        $row = $hasRow ? array(
            'id' => $id,
            'name' => $name,
            'mimeType' => $mimeType,
            'size' => $size,
            'createdTime' => $createdTime,
            'modifiedTime' => $modifiedTime,
            'thumbnailLink' => $thumbnailLink,
            'last_seen_at' => $lastSeenAt,
            'is_fresh' => $isFresh,
        ) : null;
        $stmt->close();
        if (!$row || empty($row['last_seen_at']) || (int) $row['is_fresh'] !== 1) return null;
        unset($row['is_fresh']);
        return $row;
    } catch (Throwable $e) {
        return null;
    }
}

function rfg_gallery_cache_record_uploaded_file($folderId, $file)
{
    if (!rfg_gallery_db_configured()) return false;
    try {
        $path = rfg_folder_path($folderId);
        if (count($path) < 6) return false;
        $folderName = strtolower((string) $path[count($path) - 1]['name']);
        if ($folderName !== 'originals' && $folderName !== 'edited') return false;
        $customerFolderId = $path[count($path) - 2]['id'];
        $session = rfg_gallery_session_find_by_folder($customerFolderId);
        if (!$session) return false;
        $category = $folderName === 'originals' ? 'original' : 'edited';
        // Upload baru hanya disisipkan ke cache bila katalog sebelumnya sudah lengkap dan masih segar.
        // Timestamp sinkronisasi penuh tidak digeser oleh upload incremental agar perubahan manual di Drive
        // tetap ditemukan paling lambat pada akhir TTL semula.
        $wasFresh = rfg_gallery_session_cache_is_fresh((int) $session['id'], $category, 300);
        if (!$wasFresh) return false;
        rfg_gallery_photo_upsert_one((int) $session['id'], $folderId, $category, $file);
        return true;
    } catch (Throwable $e) {
        if (function_exists('rfg_log')) rfg_log('GALLERY_CACHE_UPLOAD_RECORD_FAILED ' . $e->getMessage());
        return false;
    }
}
