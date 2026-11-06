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

/** Cari satu booking secara aman untuk pembuatan folder galeri lama.
 *
 * Kode booking lama hanya memakai 3 random bytes, sehingga kode yang sama secara
 * teori dapat muncul lagi. Karena itu lookup tidak hanya memakai uniqid: nama dan
 * studio ikut divalidasi, lalu hasil yang benar-benar berbeda dianggap ambigu.
 */
function rfg_gallery_booking_lookup($bookingUniqId, $customerName, $studio)
{
    $bookingUniqId = trim((string) $bookingUniqId);
    $customerName = trim((string) $customerName);
    $studio = stripos((string) $studio, 'kota') !== false ? 'Bekasi-Kota' : 'Bekasi-Kabupaten';
    if ($bookingUniqId === '' || $customerName === '') throw new RuntimeException('Nama pelanggan dan kode booking wajib diisi.');
    if (!preg_match('/^[A-Za-z0-9_-]{1,64}$/', $bookingUniqId)) throw new RuntimeException('Format kode booking tidak valid.');

    $db = rfg_gallery_db_connect();
    $tables = array(
        'data_booking_cobahampirfinishjuga' => 0,
        'data_konfirmasi_cobahampirfinishjuga' => 1,
        'data_history' => 2,
    );
    $normalizedName = function ($value) {
        $value = trim((string) $value);
        return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
    };
    $wantedName = $normalizedName($customerName);
    $matches = array();
    $allSignatures = array();

    foreach ($tables as $table => $priority) {
        $exists = false;
        $tableStmt = $db->prepare('SELECT 1 FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=? LIMIT 1');
        if ($tableStmt) {
            $tableStmt->bind_param('s', $table);
            $tableStmt->execute();
            $one = null;
            $tableStmt->bind_result($one);
            $exists = (bool) $tableStmt->fetch();
            $tableStmt->close();
        }
        if (!$exists) continue;

        $stmt = $db->prepare('SELECT uniqid,nama,package,tipe_package,tanggal,jam,studio FROM `' . $table . '` WHERE uniqid=? ORDER BY id DESC');
        if (!$stmt) continue;
        $stmt->bind_param('s', $bookingUniqId);
        if (!$stmt->execute()) { $stmt->close(); continue; }
        $uid = $name = $package = $packageType = $date = $time = $rowStudio = null;
        $stmt->bind_result($uid, $name, $package, $packageType, $date, $time, $rowStudio);
        while ($stmt->fetch()) {
            $normalizedStudio = stripos((string) $rowStudio, 'kota') !== false ? 'Bekasi-Kota' : 'Bekasi-Kabupaten';
            $packageLabel = trim(trim((string) $package) . ' ' . trim((string) $packageType));
            $signature = implode('|', array($normalizedName($name), $normalizedStudio, trim((string) $date), trim((string) $time), $packageLabel));
            $allSignatures[$signature] = true;
            if ($normalizedName($name) !== $wantedName || $normalizedStudio !== $studio) continue;
            if (!isset($matches[$signature]) || $priority < $matches[$signature]['priority']) {
                $matches[$signature] = array(
                    'uniqid' => (string) $uid,
                    'nama' => trim((string) $name),
                    'package' => trim((string) $package),
                    'tipe_package' => trim((string) $packageType),
                    'package_label' => $packageLabel !== '' ? $packageLabel : 'Paket foto',
                    'tanggal' => trim((string) $date),
                    'jam' => trim((string) $time),
                    'studio' => $normalizedStudio,
                    'source_table' => $table,
                    'priority' => $priority,
                );
            }
        }
        $stmt->close();
    }

    $matches = array_values($matches);
    if (count($allSignatures) > 1) throw new RuntimeException('Kode booking ini dipakai oleh lebih dari satu booking berbeda. Periksa dan perbaiki kode duplikat pada database terlebih dahulu.');
    if (count($matches) < 1) throw new RuntimeException('Booking tidak ditemukan. Pastikan nama, kode booking, dan studio sama dengan data booking.');
    if (count($matches) > 1) throw new RuntimeException('Kode booking ini cocok dengan lebih dari satu booking berbeda. Jangan membuat folder sebelum kode duplikat pada database diperiksa.');
    unset($matches[0]['priority']);
    if ($matches[0]['tanggal'] === '' || $matches[0]['jam'] === '') throw new RuntimeException('Tanggal atau jam booking pada database belum lengkap.');
    return $matches[0];
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

/**
 * Ambil snapshot booking yang benar untuk sebuah gallery_session.
 * Identitas sesi (nama, studio, dan tanggal) ikut dicocokkan karena uniqid
 * booking lama relatif pendek dan secara teori dapat berulang.
 */
function rfg_gallery_booking_admin_detail($session)
{
    if (!is_array($session) || empty($session['booking_uniqid'])) return null;
    $db = rfg_gallery_db_connect();
    $uniqid = (string) $session['booking_uniqid'];
    $tables = array(
        'data_booking_cobahampirfinishjuga',
        'data_history',
        'data_konfirmasi_cobahampirfinishjuga',
    );
    $normalizeText = function ($value) {
        $value = trim((string) $value);
        return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
    };
    $normalizeStudio = function ($value) {
        return stripos((string) $value, 'kota') !== false ? 'Bekasi-Kota' : 'Bekasi-Kabupaten';
    };
    $normalizeDate = function ($value) {
        $timestamp = strtotime((string) $value);
        return $timestamp === false ? trim((string) $value) : date('Y-m-d', $timestamp);
    };
    $wantedName = $normalizeText(isset($session['nama_pelanggan']) ? $session['nama_pelanggan'] : '');
    $wantedStudio = $normalizeStudio(isset($session['studio']) ? $session['studio'] : '');
    $wantedDate = $normalizeDate(isset($session['tanggal_booking']) ? $session['tanggal_booking'] : '');

    foreach ($tables as $table) {
        $tableEscaped = $db->real_escape_string($table);
        $exists = $db->query("SELECT 1 FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='" . $tableEscaped . "' LIMIT 1");
        if (!$exists || !$exists->fetch_row()) continue;

        $uniqidEscaped = $db->real_escape_string($uniqid);
        $result = $db->query("SELECT * FROM `" . $table . "` WHERE uniqid='" . $uniqidEscaped . "' ORDER BY id DESC");
        if (!$result) continue;
        while ($row = $result->fetch_assoc()) {
            $rowName = $normalizeText(isset($row['nama']) ? $row['nama'] : '');
            $rowStudio = $normalizeStudio(isset($row['studio']) ? $row['studio'] : '');
            $rowDate = $normalizeDate(isset($row['tanggal']) ? $row['tanggal'] : '');
            if ($rowName !== $wantedName || $rowStudio !== $wantedStudio || $rowDate !== $wantedDate) continue;
            $row['source_table'] = $table;
            return $row;
        }
    }
    return null;
}

function rfg_gallery_booking_addition_items($booking)
{
    if (!is_array($booking)) return array();
    $items = array();
    $package = isset($booking['package']) ? trim((string) $booking['package']) : '';
    $numericItems = array(
        'anak' => 'Anak',
        'dewasa' => 'Dewasa',
        'tambah_orang' => 'Tambah orang',
    );
    foreach ($numericItems as $field => $label) {
        $value = isset($booking[$field]) ? (int) $booking[$field] : 0;
        if ($value > 0) $items[] = $label . ': ' . $value . ' orang';
    }
    $extraTime = isset($booking['tambah_waktu']) ? (int) $booking['tambah_waktu'] : 0;
    if ($extraTime > 0) $items[] = 'Tambah waktu: ' . $extraTime . ($package === 'self photo' ? ' menit' : ' jam');

    $print = isset($booking['tambah_cetak']) ? trim((string) $booking['tambah_cetak']) : '';
    if ($print !== '' && $print !== '0' && strtolower($print) !== 'tidak ada') $items[] = 'Tambah cetak: ' . $print;
    $makeup = isset($booking['tambah_makeup']) ? (int) $booking['tambah_makeup'] : 0;
    if ($makeup > 0) $items[] = 'Makeup: ' . $makeup;
    $hairdo = isset($booking['tambah_hairdo']) ? (int) $booking['tambah_hairdo'] : 0;
    if ($hairdo > 0) $items[] = 'Hairdo: ' . $hairdo;
    return array_values(array_unique($items));
}

/** Status pelunasan dan tambahan yang diajukan pada form pelunasan. */
function rfg_gallery_payment_admin_detail($booking)
{
    $empty = array('status' => '', 'label' => 'Belum ada pengajuan', 'addons' => array());
    if (!is_array($booking) || empty($booking['uniqid'])) return $empty;
    $db = rfg_gallery_db_connect();
    $tableCheck = $db->query("SELECT 1 FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='booking_payment_submissions' LIMIT 1");
    if (!$tableCheck || !$tableCheck->fetch_row()) return $empty;

    $uniqid = $db->real_escape_string((string) $booking['uniqid']);
    $email = $db->real_escape_string(trim((string) (isset($booking['email']) ? $booking['email'] : '')));
    $date = $db->real_escape_string(trim((string) (isset($booking['tanggal']) ? $booking['tanggal'] : '')));
    $where = "booking_uniqid='" . $uniqid . "' AND legacy_source='booking'";
    if ($email !== '') $where .= " AND LOWER(TRIM(customer_email))=LOWER('" . $email . "')";
    if ($date !== '') $where .= " AND TRIM(booking_date)='" . $date . "'";
    $result = $db->query('SELECT id,status FROM booking_payment_submissions WHERE ' . $where . ' ORDER BY id DESC LIMIT 1');
    if (!$result || !($payment = $result->fetch_assoc())) return $empty;

    $status = isset($payment['status']) ? (string) $payment['status'] : '';
    $labels = array(
        'pending' => 'Menunggu verifikasi',
        'approved' => 'Lunas',
        'rejected' => 'Ditolak - unggah ulang',
    );
    $detail = array(
        'status' => $status,
        'label' => isset($labels[$status]) ? $labels[$status] : 'Belum ada pengajuan',
        'addons' => array(),
    );
    $addonTable = $db->query("SELECT 1 FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='booking_payment_addons' LIMIT 1");
    if (!$addonTable || !$addonTable->fetch_row()) return $detail;
    $submissionId = (int) $payment['id'];
    $addons = $db->query('SELECT addon_name,quantity FROM booking_payment_addons WHERE submission_id=' . $submissionId . ' ORDER BY id ASC');
    if ($addons) {
        while ($addon = $addons->fetch_assoc()) {
            $name = trim((string) $addon['addon_name']);
            $quantity = max(1, (int) $addon['quantity']);
            if ($name !== '') $detail['addons'][] = $name . ($quantity > 1 ? ' × ' . $quantity : '');
        }
    }
    return $detail;
}

function rfg_gallery_whatsapp_url($phone, $customerName, $packageLabel)
{
    $digits = preg_replace('/\D+/', '', (string) $phone);
    if (strpos($digits, '0') === 0) $digits = '62' . substr($digits, 1);
    elseif (strpos($digits, '8') === 0) $digits = '62' . $digits;
    if (!preg_match('/^[0-9]{8,15}$/', $digits)) return '';
    $message = 'Halo ' . trim((string) $customerName) . ', kami dari RF Studio ingin menginformasikan terkait galeri foto booking ' . trim((string) $packageLabel) . '.';
    return 'https://wa.me/' . $digits . '?text=' . rawurlencode($message);
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

        // Foto yang tetap berstatus missing setelah daftar Drive terbaru masuk
        // benar-benar sudah dihapus di luar aplikasi. Bersihkan pilihan editnya juga.
        $missingStmt = $db->prepare("SELECT id FROM gallery_photos WHERE gallery_session_id=? AND drive_parent_folder_id=? AND status='missing'");
        if (!$missingStmt) throw new RuntimeException('Query rekonsiliasi foto terhapus gagal disiapkan.');
        $missingStmt->bind_param('is', $sessionId, $folderId);
        if (!$missingStmt->execute()) throw new RuntimeException('Rekonsiliasi foto terhapus gagal dijalankan.');
        $missingPhotoId = null;
        $missingStmt->bind_result($missingPhotoId);
        $missingPhotoIds = array();
        while ($missingStmt->fetch()) $missingPhotoIds[] = (int) $missingPhotoId;
        $missingStmt->close();
        rfg_gallery_reject_requests_for_photo_ids($db, $missingPhotoIds, 'Salah satu foto pilihan sudah tidak tersedia di Google Drive. Silakan pilih kembali foto yang masih tersedia.');

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
    $limit = max(1, min(1000, (int) $limit));
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

    $sql = 'SELECT id AS photo_id, drive_file_id AS id, filename AS name, mime_type AS mimeType, size_bytes AS size, created_at AS createdTime, drive_modified_at AS modifiedTime, thumbnail_link AS thumbnailLink, drive_parent_folder_id FROM gallery_photos WHERE ' . $where . ' ORDER BY filename LIMIT ? OFFSET ?';
    $stmt = $db->prepare($sql);
    if (!$stmt) throw new RuntimeException('Query daftar cache foto gagal disiapkan.');
    if ($nameFilter !== '') {
        $like = '%' . $nameFilter . '%';
        $stmt->bind_param('isssii', $sessionId, $folderId, $category, $like, $limit, $offset);
    } else {
        $stmt->bind_param('issii', $sessionId, $folderId, $category, $limit, $offset);
    }
    if (!$stmt->execute()) throw new RuntimeException('Pembacaan cache foto gagal.');
    $photoId = $id = $name = $mimeType = $size = $createdTime = $modifiedTime = $thumbnailLink = $parentId = null;
    $stmt->bind_result($photoId, $id, $name, $mimeType, $size, $createdTime, $modifiedTime, $thumbnailLink, $parentId);
    $files = array();
    while ($stmt->fetch()) {
        $files[] = array(
            'photo_id' => (int) $photoId,
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

function rfg_gallery_photo_count($sessionId, $category)
{
    $sessionId = (int) $sessionId;
    $category = $category === 'edited' ? 'edited' : 'original';
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare("SELECT COUNT(*) FROM gallery_photos WHERE gallery_session_id=? AND category=? AND status='active'");
    if (!$stmt) return 0;
    $stmt->bind_param('is', $sessionId, $category);
    if (!$stmt->execute()) { $stmt->close(); return 0; }
    $total = 0;
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    return (int) $total;
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

/** Fitur pilihan foto customer ------------------------------------------------ */

function rfg_gallery_edit_schema_ready()
{
    try {
        $db = rfg_gallery_db_connect();
        $tables = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name IN ('gallery_edit_requests', 'gallery_edit_request_items')");
        if (!$tables) return false;
        $found = array();
        while ($row = $tables->fetch_assoc()) $found[$row['table_name']] = true;
        return isset($found['gallery_edit_requests'], $found['gallery_edit_request_items']);
    } catch (Throwable $e) {
        return false;
    }
}

function rfg_gallery_edit_request_for_session($sessionId)
{
    if (!rfg_gallery_edit_schema_ready()) return null;
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare('SELECT id, gallery_session_id, customer_email, status, customer_note, admin_note, submitted_at, reviewed_at, completed_at, created_at, updated_at FROM gallery_edit_requests WHERE gallery_session_id=? LIMIT 1');
    if (!$stmt) throw new RuntimeException('Query permintaan edit gagal disiapkan.');
    $sessionId = (int) $sessionId;
    $stmt->bind_param('i', $sessionId);
    if (!$stmt->execute()) { $stmt->close(); throw new RuntimeException('Permintaan edit gagal dibaca.'); }
    $id = $gallerySessionId = $email = $status = $customerNote = $adminNote = $submittedAt = $reviewedAt = $completedAt = $createdAt = $updatedAt = null;
    $stmt->bind_result($id, $gallerySessionId, $email, $status, $customerNote, $adminNote, $submittedAt, $reviewedAt, $completedAt, $createdAt, $updatedAt);
    $hasRow = $stmt->fetch();
    $row = $hasRow ? array(
        'id' => (int) $id,
        'gallery_session_id' => (int) $gallerySessionId,
        'customer_email' => $email,
        'status' => $status,
        'customer_note' => $customerNote,
        'admin_note' => $adminNote,
        'submitted_at' => $submittedAt,
        'reviewed_at' => $reviewedAt,
        'completed_at' => $completedAt,
        'created_at' => $createdAt,
        'updated_at' => $updatedAt,
    ) : null;
    $stmt->close();
    return $row;
}

function rfg_gallery_edit_request_items($requestId)
{
    if (!rfg_gallery_edit_schema_ready()) return array();
    $db = rfg_gallery_db_connect();
    $stmt = $db->prepare('SELECT id, request_id, gallery_photo_id, drive_file_id, filename, created_at FROM gallery_edit_request_items WHERE request_id=? ORDER BY filename, id');
    if (!$stmt) throw new RuntimeException('Query daftar foto pilihan gagal disiapkan.');
    $requestId = (int) $requestId;
    $stmt->bind_param('i', $requestId);
    if (!$stmt->execute()) { $stmt->close(); throw new RuntimeException('Daftar foto pilihan gagal dibaca.'); }
    $id = $request = $photoId = $driveId = $filename = $createdAt = null;
    $stmt->bind_result($id, $request, $photoId, $driveId, $filename, $createdAt);
    $rows = array();
    while ($stmt->fetch()) {
        $rows[] = array('id' => (int) $id, 'request_id' => (int) $request, 'gallery_photo_id' => (int) $photoId, 'drive_file_id' => $driveId, 'filename' => $filename, 'created_at' => $createdAt);
    }
    $stmt->close();
    return $rows;
}

function rfg_gallery_edit_selected_drive_map($sessionId)
{
    $request = rfg_gallery_edit_request_for_session($sessionId);
    if (!$request) return array();
    $map = array();
    foreach (rfg_gallery_edit_request_items($request['id']) as $item) $map[$item['drive_file_id']] = $item;
    return $map;
}

function rfg_gallery_submit_edit_request($sessionId, $customerEmail, $photoIds, $customerNote, $maxPhotos)
{
    if (!rfg_gallery_edit_schema_ready()) throw new RuntimeException('Database pilihan foto belum siap. Jalankan migration-gallery-customer.sql terlebih dahulu.');
    $sessionId = (int) $sessionId;
    $customerEmail = strtolower(trim((string) $customerEmail));
    $customerNote = trim((string) $customerNote);
    if ($sessionId < 1 || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) throw new RuntimeException('Sesi galeri atau email tidak valid.');
    if (function_exists('mb_substr')) $customerNote = mb_substr($customerNote, 0, 500, 'UTF-8'); else $customerNote = substr($customerNote, 0, 500);
    $maxPhotos = max(1, min(100, (int) $maxPhotos));
    $cleanIds = array();
    foreach ((array) $photoIds as $photoId) {
        $photoId = (int) $photoId;
        if ($photoId > 0) $cleanIds[$photoId] = $photoId;
    }
    $cleanIds = array_values($cleanIds);
    if (empty($cleanIds)) throw new RuntimeException('Pilih minimal satu foto yang ingin diedit.');
    if (count($cleanIds) > $maxPhotos) throw new RuntimeException('Maksimal ' . $maxPhotos . ' foto dapat dipilih dalam satu pengajuan.');

    $existing = rfg_gallery_edit_request_for_session($sessionId);
    if ($existing && in_array($existing['status'], array('editing', 'completed'), true)) {
        throw new RuntimeException($existing['status'] === 'completed' ? 'Permintaan edit untuk booking ini sudah selesai.' : 'Admin sudah mulai mengedit foto. Pilihan tidak dapat diubah lagi.');
    }

    $db = rfg_gallery_db_connect();
    if (!$db->begin_transaction()) throw new RuntimeException('Tidak dapat memulai penyimpanan pilihan foto.');
    try {
        $valid = array();
        $photoStmt = $db->prepare("SELECT id, drive_file_id, filename FROM gallery_photos WHERE id=? AND gallery_session_id=? AND category='original' AND status='active' LIMIT 1");
        if (!$photoStmt) throw new RuntimeException('Validasi foto pilihan gagal disiapkan.');
        foreach ($cleanIds as $photoId) {
            $photoStmt->bind_param('ii', $photoId, $sessionId);
            if (!$photoStmt->execute()) throw new RuntimeException('Foto pilihan gagal divalidasi.');
            $foundId = $driveId = $filename = null;
            $photoStmt->bind_result($foundId, $driveId, $filename);
            if ($photoStmt->fetch()) $valid[] = array('id' => (int) $foundId, 'drive_file_id' => $driveId, 'filename' => $filename);
            $photoStmt->free_result();
        }
        $photoStmt->close();
        if (count($valid) !== count($cleanIds)) throw new RuntimeException('Sebagian foto pilihan tidak ditemukan. Muat ulang galeri lalu pilih kembali.');

        if ($existing) {
            $requestId = (int) $existing['id'];
            $stmt = $db->prepare("UPDATE gallery_edit_requests SET customer_email=?, status='submitted', customer_note=?, admin_note='', submitted_at=NOW(), reviewed_at=NULL, completed_at=NULL, updated_at=NOW() WHERE id=? AND gallery_session_id=?");
            if (!$stmt) throw new RuntimeException('Pembaruan permintaan edit gagal disiapkan.');
            $stmt->bind_param('ssii', $customerEmail, $customerNote, $requestId, $sessionId);
        } else {
            $stmt = $db->prepare("INSERT INTO gallery_edit_requests (gallery_session_id, customer_email, status, customer_note, submitted_at) VALUES (?, ?, 'submitted', ?, NOW())");
            if (!$stmt) throw new RuntimeException('Pembuatan permintaan edit gagal disiapkan.');
            $stmt->bind_param('iss', $sessionId, $customerEmail, $customerNote);
        }
        if (!$stmt->execute()) throw new RuntimeException('Permintaan edit gagal disimpan: ' . $stmt->error);
        if (!$existing) $requestId = (int) $stmt->insert_id;
        $stmt->close();

        $delete = $db->prepare('DELETE FROM gallery_edit_request_items WHERE request_id=?');
        if (!$delete) throw new RuntimeException('Penggantian daftar pilihan gagal disiapkan.');
        $delete->bind_param('i', $requestId);
        if (!$delete->execute()) throw new RuntimeException('Daftar pilihan lama gagal diperbarui.');
        $delete->close();

        $insert = $db->prepare('INSERT INTO gallery_edit_request_items (request_id, gallery_photo_id, drive_file_id, filename) VALUES (?, ?, ?, ?)');
        if (!$insert) throw new RuntimeException('Penyimpanan foto pilihan gagal disiapkan.');
        foreach ($valid as $photo) {
            $insert->bind_param('iiss', $requestId, $photo['id'], $photo['drive_file_id'], $photo['filename']);
            if (!$insert->execute()) throw new RuntimeException('Salah satu foto pilihan gagal disimpan.');
        }
        $insert->close();
        $db->commit();
        return $requestId;
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    }
}

function rfg_gallery_admin_update_edit_request($requestId, $status, $adminNote)
{
    if (!rfg_gallery_edit_schema_ready()) return false;
    if (!in_array($status, array('submitted', 'editing', 'completed', 'rejected'), true)) return false;
    $requestId = (int) $requestId;
    $adminNote = trim((string) $adminNote);
    if (function_exists('mb_substr')) $adminNote = mb_substr($adminNote, 0, 500, 'UTF-8'); else $adminNote = substr($adminNote, 0, 500);
    $db = rfg_gallery_db_connect();
    if ($status === 'completed') {
        $sql = "UPDATE gallery_edit_requests SET status=?, admin_note=?, reviewed_at=COALESCE(reviewed_at,NOW()), completed_at=NOW(), updated_at=NOW() WHERE id=?";
    } else {
        $sql = "UPDATE gallery_edit_requests SET status=?, admin_note=?, reviewed_at=NOW(), completed_at=NULL, updated_at=NOW() WHERE id=?";
    }
    $stmt = $db->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param('ssi', $status, $adminNote, $requestId);
    $ok = $stmt->execute() && $stmt->affected_rows >= 0;
    $stmt->close();
    return $ok;
}

/** Penghapusan/masa simpan galeri ------------------------------------------ */

function rfg_gallery_active_sessions()
{
    $db = rfg_gallery_db_connect();
    $result = $db->query("SELECT id, booking_uniqid, drive_folder_id FROM gallery_sessions WHERE status='active' ORDER BY id");
    if (!$result) throw new RuntimeException('Daftar sesi galeri aktif gagal dibaca.');
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = array('id' => (int) $row['id'], 'booking_uniqid' => $row['booking_uniqid'], 'drive_folder_id' => $row['drive_folder_id']);
    }
    $result->free();
    return $rows;
}

function rfg_gallery_inactive_status_value($db)
{
    $result = $db->query("SHOW COLUMNS FROM gallery_sessions LIKE 'status'");
    if (!$result || !($row = $result->fetch_assoc())) throw new RuntimeException('Kolom status gallery_sessions tidak ditemukan.');
    $type = strtolower((string) $row['Type']);
    $result->free();
    if (strpos($type, 'enum(') === 0) {
        foreach (array('inactive', 'archived', 'deleted') as $candidate) {
            if (strpos($type, "'" . $candidate . "'") !== false) return $candidate;
        }
        throw new RuntimeException("Kolom status gallery_sessions belum mendukung nilai inactive. Ubah ENUM agar memuat 'active' dan 'inactive'.");
    }
    return 'inactive';
}

function rfg_gallery_session_set_status($sessionId, $status)
{
    if (!in_array($status, array('active', 'inactive'), true)) return false;
    $db = rfg_gallery_db_connect();
    if ($status === 'inactive') $status = rfg_gallery_inactive_status_value($db);
    $stmt = $db->prepare('UPDATE gallery_sessions SET status=?, updated_at=NOW() WHERE id=?');
    if (!$stmt) return false;
    $sessionId = (int) $sessionId;
    $stmt->bind_param('si', $status, $sessionId);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function rfg_gallery_reject_requests_for_photo_ids($db, $photoIds, $message)
{
    if (!rfg_gallery_edit_schema_ready() || empty($photoIds)) return;
    $clean = array();
    foreach ($photoIds as $id) if ((int) $id > 0) $clean[] = (int) $id;
    if (empty($clean)) return;
    $idList = implode(',', $clean);
    $message = $db->real_escape_string((string) $message);
    $db->query("UPDATE gallery_edit_requests SET status='rejected', admin_note='" . $message . "', reviewed_at=NOW(), completed_at=NULL, updated_at=NOW() WHERE id IN (SELECT DISTINCT request_id FROM gallery_edit_request_items WHERE gallery_photo_id IN (" . $idList . '))');
    $db->query('DELETE FROM gallery_edit_request_items WHERE gallery_photo_id IN (' . $idList . ')');
}

function rfg_gallery_mark_photo_missing_by_drive_id($driveFileId)
{
    $driveFileId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $driveFileId);
    if ($driveFileId === '') return false;
    $db = rfg_gallery_db_connect();
    if (!$db->begin_transaction()) throw new RuntimeException('Tidak dapat memulai pembaruan cache foto.');
    try {
        $stmt = $db->prepare("SELECT id FROM gallery_photos WHERE drive_file_id=? AND status='active'");
        if (!$stmt) throw new RuntimeException('Query foto yang dihapus gagal disiapkan.');
        $stmt->bind_param('s', $driveFileId);
        if (!$stmt->execute()) throw new RuntimeException('Foto yang dihapus gagal dibaca.');
        $photoId = null;
        $stmt->bind_result($photoId);
        $photoIds = array();
        while ($stmt->fetch()) $photoIds[] = (int) $photoId;
        $stmt->close();
        rfg_gallery_reject_requests_for_photo_ids($db, $photoIds, 'Salah satu foto pilihan telah dihapus admin. Silakan pilih kembali foto yang masih tersedia.');
        $stmt = $db->prepare("UPDATE gallery_photos SET status='missing', updated_at=NOW() WHERE drive_file_id=?");
        if (!$stmt) throw new RuntimeException('Pembaruan cache foto gagal disiapkan.');
        $stmt->bind_param('s', $driveFileId);
        if (!$stmt->execute()) throw new RuntimeException('Cache foto yang dihapus gagal diperbarui.');
        $stmt->close();
        $db->commit();
        return true;
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    }
}

function rfg_gallery_mark_category_missing($sessionId, $category)
{
    $sessionId = (int) $sessionId;
    $category = $category === 'edited' ? 'edited' : 'original';
    $db = rfg_gallery_db_connect();
    if (!$db->begin_transaction()) throw new RuntimeException('Tidak dapat memulai pembaruan folder foto.');
    try {
        $stmt = $db->prepare("SELECT id FROM gallery_photos WHERE gallery_session_id=? AND category=? AND status='active'");
        if (!$stmt) throw new RuntimeException('Query kategori foto gagal disiapkan.');
        $stmt->bind_param('is', $sessionId, $category);
        if (!$stmt->execute()) throw new RuntimeException('Kategori foto gagal dibaca.');
        $photoId = null;
        $stmt->bind_result($photoId);
        $photoIds = array();
        while ($stmt->fetch()) $photoIds[] = (int) $photoId;
        $stmt->close();
        rfg_gallery_reject_requests_for_photo_ids($db, $photoIds, 'Folder foto yang berisi pilihan edit telah dihapus admin. Silakan tunggu galeri baru dari studio.');
        $stmt = $db->prepare("UPDATE gallery_photos SET status='missing', updated_at=NOW() WHERE gallery_session_id=? AND category=?");
        if (!$stmt) throw new RuntimeException('Pembaruan kategori foto gagal disiapkan.');
        $stmt->bind_param('is', $sessionId, $category);
        if (!$stmt->execute()) throw new RuntimeException('Kategori foto gagal dinonaktifkan.');
        $stmt->close();
        $column = $category === 'original' ? 'originals_synced_at' : 'edited_synced_at';
        $stmt = $db->prepare('UPDATE gallery_sessions SET ' . $column . '=NULL, updated_at=NOW() WHERE id=?');
        if ($stmt) { $stmt->bind_param('i', $sessionId); $stmt->execute(); $stmt->close(); }
        $db->commit();
        return true;
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    }
}

function rfg_gallery_deactivate_sessions($sessionIds, $message)
{
    $clean = array();
    foreach ((array) $sessionIds as $id) if ((int) $id > 0) $clean[(int) $id] = (int) $id;
    if (empty($clean)) return true;
    $idList = implode(',', array_values($clean));
    $db = rfg_gallery_db_connect();
    $inactiveStatus = $db->real_escape_string(rfg_gallery_inactive_status_value($db));
    if (!$db->begin_transaction()) throw new RuntimeException('Tidak dapat memulai penonaktifan sesi galeri.');
    try {
        if (rfg_gallery_edit_schema_ready()) {
            $escaped = $db->real_escape_string((string) $message);
            if (!$db->query("UPDATE gallery_edit_requests SET status='rejected', admin_note='" . $escaped . "', reviewed_at=NOW(), completed_at=NULL, updated_at=NOW() WHERE gallery_session_id IN (" . $idList . ')')) throw new RuntimeException('Permintaan edit gagal dinonaktifkan.');
        }
        if (!$db->query("UPDATE gallery_photos SET status='missing', updated_at=NOW() WHERE gallery_session_id IN (" . $idList . ')')) throw new RuntimeException('Cache foto sesi gagal dinonaktifkan.');
        if (!$db->query("UPDATE gallery_sessions SET status='" . $inactiveStatus . "', updated_at=NOW() WHERE id IN (" . $idList . ')')) throw new RuntimeException('Sesi galeri gagal dinonaktifkan.');
        $db->commit();
        return true;
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    }
}
