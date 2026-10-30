<?php
/*
 * Modul pelunasan berbasis email.
 * Seluruh harga tambahan ditentukan di server; nilai dari browser hanya jumlah item.
 * Kompatibel dengan PHP 7.4.
 */

function rfp_h($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function rfp_start_session(){
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function rfp_csrf_token(){
    rfp_start_session();
    if (empty($_SESSION['rfp_csrf'])) {
        try {
            $_SESSION['rfp_csrf'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            $_SESSION['rfp_csrf'] = hash('sha256', uniqid('rfp', true));
        }
    }
    return $_SESSION['rfp_csrf'];
}

function rfp_csrf_is_valid($token){
    rfp_start_session();
    return is_string($token) && !empty($_SESSION['rfp_csrf']) && hash_equals($_SESSION['rfp_csrf'], $token);
}

function rfp_flash($type, $message){
    rfp_start_session();
    $_SESSION['rfp_flash'] = array('type' => $type, 'message' => $message);
}

function rfp_take_flash(){
    rfp_start_session();
    if (empty($_SESSION['rfp_flash'])) {
        return null;
    }
    $flash = $_SESSION['rfp_flash'];
    unset($_SESSION['rfp_flash']);
    return $flash;
}

function rfp_format_rupiah($amount){
    return 'Rp ' . number_format((float)$amount, 0, ',', '.');
}

function rfp_money($amount){
    if (is_string($amount)) {
        $amount = str_replace(array('Rp', 'rp', '.', ','), '', trim($amount));
    }
    return max(0, (float)$amount);
}

function rfp_schema_ready(){
    global $koneksi;
    $result = mysqli_query($koneksi, "SHOW TABLES LIKE 'booking_payment_submissions'");
    return $result && mysqli_num_rows($result) === 1;
}

function rfp_bind_params($statement, $types, $params){
    if ($types === '' || empty($params)) {
        return true;
    }
    $bound = array($types);
    foreach ($params as $index => $value) {
        $bound[] = &$params[$index];
    }
    return call_user_func_array(array($statement, 'bind_param'), $bound);
}

function rfp_stmt_rows($sql, $types, $params){
    global $koneksi;
    $statement = mysqli_prepare($koneksi, $sql);
    if (!$statement) {
        return array();
    }
    rfp_bind_params($statement, $types, $params);
    if (!mysqli_stmt_execute($statement)) {
        mysqli_stmt_close($statement);
        return array();
    }
    $rows = array();
    // mysqli_stmt_get_result hanya tersedia pada PHP dengan mysqlnd. Beberapa
    // shared hosting PHP 7.4 memakai libmysqlclient, jadi sediakan fallback.
    if (function_exists('mysqli_stmt_get_result')) {
        $result = mysqli_stmt_get_result($statement);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }
    } else {
        $metadata = mysqli_stmt_result_metadata($statement);
        if ($metadata) {
            $row = array();
            $references = array();
            foreach (mysqli_fetch_fields($metadata) as $field) {
                $row[$field->name] = null;
                $references[] = &$row[$field->name];
            }
            call_user_func_array(array($statement, 'bind_result'), $references);
            while (mysqli_stmt_fetch($statement)) {
                $copy = array();
                foreach ($row as $key => $value) {
                    $copy[$key] = $value;
                }
                $rows[] = $copy;
            }
            mysqli_free_result($metadata);
        }
    }
    mysqli_stmt_close($statement);
    return $rows;
}

function rfp_stmt_one($sql, $types, $params){
    $rows = rfp_stmt_rows($sql, $types, $params);
    return empty($rows) ? null : $rows[0];
}

function rfp_payment_state($uniqid, $legacySource){
    global $koneksi;
    $uniqid = mysqli_real_escape_string($koneksi, (string)$uniqid);
    $legacySource = mysqli_real_escape_string($koneksi, (string)$legacySource);
    $result = mysqli_query($koneksi, "SELECT id, invoice_number, public_token, status, total_due, created_at FROM booking_payment_submissions WHERE booking_uniqid = '$uniqid' AND legacy_source = '$legacySource' ORDER BY id DESC LIMIT 1");
    return $result ? mysqli_fetch_assoc($result) : null;
}

function rfp_status_label($status){
    $labels = array(
        'pending' => 'Menunggu verifikasi',
        'approved' => 'Lunas',
        'rejected' => 'Ditolak - silakan unggah ulang'
    );
    return isset($labels[$status]) ? $labels[$status] : 'Belum ada pengajuan';
}

function rfp_status_badge_class($status){
    $classes = array(
        'pending' => 'bg-warning text-dark',
        'approved' => 'bg-success',
        'rejected' => 'bg-danger'
    );
    return isset($classes[$status]) ? $classes[$status] : 'bg-secondary';
}

function rfp_studio_label($studio){
    if ($studio === 'Bekasi-Kota') {
        return 'Bekasi Timur Kota';
    }
    if ($studio === 'Bekasi-Kabupaten' || $studio === '') {
        return 'Bekasi Timur Kabupaten';
    }
    return $studio;
}

function rfp_booking_is_due($booking){
    $package = strtolower(trim(isset($booking['package']) ? $booking['package'] : ''));
    $harga = rfp_money(isset($booking['harga']) ? $booking['harga'] : 0);
    $sisa = rfp_money(isset($booking['hargasetelahdp']) ? $booking['hargasetelahdp'] : 0);

    // Pas Foto dan Self Photo memang tidak memakai DP. Pada data lama sisa pembayaran
    // keduanya tersimpan 0, sehingga nilai yang benar untuk pelunasan adalah harga total.
    if ((strpos($package, 'pas foto') !== false || strpos($package, 'self photo') !== false) && $harga > 0) {
        return $harga;
    }

    return $sisa > 0 ? $sisa : $harga;
}

function rfp_booking_date_is_reached($date){
    $timestamp = strtotime((string)$date);
    return $timestamp !== false && $timestamp <= strtotime(date('Y-m-d'));
}

function rfp_find_bookings_by_email($email, &$lookupError = null){
    global $koneksi;

    $email = strtolower(trim((string)$email));
    $lookupError = null;

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array();
    }

    // Urutan prioritas: booking aktif > menunggu konfirmasi > history.
    // Jika satu uniqid muncul di beberapa tabel, jangan biarkan history
    // menimpa data booking yang masih dapat diproses.
    $tables = array(
        'booking' => array('name' => 'data_booking_cobahampirfinishjuga', 'priority' => 1),
        'konfirmasi' => array('name' => 'data_konfirmasi_cobahampirfinishjuga', 'priority' => 2),
        'history' => array('name' => 'data_history', 'priority' => 3)
    );

    $unique = array();
    $successfulQueries = 0;
    $queryErrors = array();

    foreach ($tables as $source => $config) {
        $table = $config['name'];

        // Nama tabel berasal dari daftar statis di atas, bukan input pengguna.
        $sql = "SELECT id, uniqid, nama, no_telp, email, package, harga,
                       hargasetelahdp, tanggal, jam, tipe_package, studio
                FROM `$table`
                WHERE LOWER(TRIM(email)) = ?";

        $statement = mysqli_prepare($koneksi, $sql);
        if (!$statement) {
            $queryErrors[] = $table . ': ' . mysqli_error($koneksi);
            continue;
        }

        mysqli_stmt_bind_param($statement, 's', $email);
        if (!mysqli_stmt_execute($statement)) {
            $queryErrors[] = $table . ': ' . mysqli_stmt_error($statement);
            mysqli_stmt_close($statement);
            continue;
        }

        $successfulQueries++;
        $result = mysqli_stmt_get_result($statement);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $uniqid = isset($row['uniqid']) ? trim((string)$row['uniqid']) : '';
                $key = $uniqid !== '' ? $uniqid : $source . ':' . (string)$row['id'];

                if (isset($unique[$key]) &&
                    $unique[$key]['_source_priority'] <= $config['priority']) {
                    continue;
                }

                $row['legacy_source'] = $source;
                $row['remaining_due'] = rfp_booking_is_due($row);
                $row['date_reached'] = rfp_booking_date_is_reached($row['tanggal']);
                $row['payment_ready'] = ($source !== 'history');
                $row['payment_state'] = rfp_payment_state($row['uniqid'], $source);
                $row['_source_priority'] = $config['priority'];
                $unique[$key] = $row;
            }
            mysqli_free_result($result);
        } else {
            // Fallback untuk lingkungan PHP/MySQLi tanpa mysqlnd.
            $metadata = mysqli_stmt_result_metadata($statement);
            if ($metadata) {
                $row = array();
                $references = array();
                foreach (mysqli_fetch_fields($metadata) as $field) {
                    $row[$field->name] = null;
                    $references[] = &$row[$field->name];
                }
                call_user_func_array(array($statement, 'bind_result'), $references);
                while (mysqli_stmt_fetch($statement)) {
                    $copy = array();
                    foreach ($row as $fieldName => $fieldValue) {
                        $copy[$fieldName] = $fieldValue;
                    }
                    $uniqid = isset($copy['uniqid']) ? trim((string)$copy['uniqid']) : '';
                    $key = $uniqid !== '' ? $uniqid : $source . ':' . (string)$copy['id'];

                    if (isset($unique[$key]) &&
                        $unique[$key]['_source_priority'] <= $config['priority']) {
                        continue;
                    }

                    $copy['legacy_source'] = $source;
                    $copy['remaining_due'] = rfp_booking_is_due($copy);
                    $copy['date_reached'] = rfp_booking_date_is_reached($copy['tanggal']);
                    $copy['payment_ready'] = ($source !== 'history');
                    $copy['payment_state'] = rfp_payment_state($copy['uniqid'], $source);
                    $copy['_source_priority'] = $config['priority'];
                    $unique[$key] = $copy;
                }
                mysqli_free_result($metadata);
            }
        }

        mysqli_stmt_close($statement);
    }

    if ($successfulQueries === 0) {
        $lookupError = 'Tidak ada tabel booking yang berhasil dibaca. ' . implode(' | ', $queryErrors);
        error_log('RF payment lookup failed: ' . $lookupError);
        return array();
    }

    // Metadata prioritas hanya dipakai internal, tidak perlu diteruskan ke UI.
    foreach ($unique as &$booking) {
        unset($booking['_source_priority']);
    }
    unset($booking);

    $bookings = array_values($unique);
    usort($bookings, function ($a, $b) {
        $dateA = isset($a['tanggal']) ? (string)$a['tanggal'] : '';
        $dateB = isset($b['tanggal']) ? (string)$b['tanggal'] : '';
        return strcmp($dateB, $dateA);
    });

    return $bookings;
}

function rfp_get_booking($uniqid, $legacySource, $email){
    global $koneksi;
    $tables = array(
        'konfirmasi' => 'data_konfirmasi_cobahampirfinishjuga',
        'booking' => 'data_booking_cobahampirfinishjuga',
        'history' => 'data_history'
    );
    if (!isset($tables[$legacySource])) {
        return null;
    }
    $uniqidSql = mysqli_real_escape_string($koneksi, (string)$uniqid);
    $emailSql = mysqli_real_escape_string($koneksi, strtolower(trim($email)));
    $sql = 'SELECT id, uniqid, nama, no_telp, email, package, harga, hargasetelahdp, tanggal, jam, tipe_package, studio FROM ' . $tables[$legacySource] . " WHERE uniqid = '$uniqidSql' AND LOWER(TRIM(email)) = '$emailSql' LIMIT 1";
    $result = mysqli_query($koneksi, $sql);
    $booking = $result ? mysqli_fetch_assoc($result) : null;
    if (!$booking) {
        return null;
    }
    $booking['legacy_source'] = $legacySource;
    $booking['remaining_due'] = rfp_booking_is_due($booking);
    $booking['date_reached'] = rfp_booking_date_is_reached($booking['tanggal']);
    $booking['payment_ready'] = $legacySource !== 'history';
    $booking['payment_state'] = rfp_payment_state($booking['uniqid'], $legacySource);
    return $booking;
}

function rfp_addon_catalog($booking){
    $package = strtolower(trim(isset($booking['package']) ? $booking['package'] : ''));
    if (strpos($package, 'pas foto') !== false) {
        return array(
            'pasfoto_4x6' => array('name' => 'Template cetak Pas Foto 4x6', 'price' => 15000),
            'pasfoto_3x4' => array('name' => 'Template cetak Pas Foto 3x4', 'price' => 15000),
            'pasfoto_2x3' => array('name' => 'Template cetak Pas Foto 2x3', 'price' => 15000),
            'pasfoto_mix' => array('name' => 'Template cetak Pas Foto Mix', 'price' => 15000),
            'pasfoto_visa' => array('name' => 'Template cetak Pas Foto Visa', 'price' => 15000)
        );
    }

    // Harga ini mengikuti opsi cetak yang telah digunakan form booking paket reguler.
    return array(
        'print_5r' => array('name' => 'Cetak foto 5R', 'price' => 15000),
        'print_5r_frame' => array('name' => 'Cetak foto 5R + frame', 'price' => 30000),
        'print_10r' => array('name' => 'Cetak foto 10R', 'price' => 35000),
        'print_10r_frame' => array('name' => 'Cetak foto 10R + frame', 'price' => 70000),
        'print_30x40_frame' => array('name' => 'Cetak foto 30x40 + frame', 'price' => 150000),
        'print_40x60_frame' => array('name' => 'Cetak foto 40x60 + frame', 'price' => 350000),
        'print_60x90_frame' => array('name' => 'Cetak foto 60x90 + frame', 'price' => 600000),
        'print_60x100_frame' => array('name' => 'Cetak foto 60x100 + frame', 'price' => 650000),
        'print_70x100_frame' => array('name' => 'Cetak foto 70x100 + frame', 'price' => 700000)
    );
}

function rfp_selected_addons($booking, $input){
    $catalog = rfp_addon_catalog($booking);
    $selected = array();
    $total = 0;
    foreach ($catalog as $code => $addon) {
        $quantity = isset($input[$code]) ? (int)$input[$code] : 0;
        $quantity = max(0, min(25, $quantity));
        if ($quantity < 1) {
            continue;
        }
        $lineTotal = $addon['price'] * $quantity;
        $selected[] = array(
            'code' => $code,
            'name' => $addon['name'],
            'unit_price' => (float)$addon['price'],
            'quantity' => $quantity,
            'line_total' => (float)$lineTotal
        );
        $total += $lineTotal;
    }
    return array('items' => $selected, 'total' => (float)$total);
}

function rfp_upload_proof($file){
    if (empty($file) || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return array('ok' => false, 'error' => 'Bukti transfer wajib diunggah.');
    }
    if ($file['size'] < 1 || $file['size'] > 5 * 1024 * 1024) {
        return array('ok' => false, 'error' => 'Ukuran bukti transfer maksimal 5 MB.');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : '';
    if ($finfo) {
        finfo_close($finfo);
    }
    $extensions = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp');
    if (!isset($extensions[$mime])) {
        return array('ok' => false, 'error' => 'Format bukti transfer harus JPG, PNG, atau WEBP.');
    }
    try {
        $random = bin2hex(random_bytes(16));
    } catch (Exception $e) {
        $random = sha1(uniqid('payment', true));
    }
    $directory = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'payment-proofs';
    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
        return array('ok' => false, 'error' => 'Folder penyimpanan bukti transfer tidak dapat dibuat.');
    }
    $filename = 'payment-' . date('Ymd-His') . '-' . $random . '.' . $extensions[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename)) {
        return array('ok' => false, 'error' => 'Bukti transfer gagal disimpan. Silakan coba lagi.');
    }
    return array('ok' => true, 'filename' => $filename, 'mime' => $mime, 'size' => (int)$file['size']);
}

function rfp_new_public_token(){
    try {
        return bin2hex(random_bytes(32));
    } catch (Exception $e) {
        return hash('sha256', uniqid('invoice', true));
    }
}

function rfp_create_submission($booking, $addonInput, $file){
    global $koneksi;
    $existing = rfp_payment_state($booking['uniqid'], $booking['legacy_source']);
    if ($existing && ($existing['status'] === 'pending' || $existing['status'] === 'approved')) {
        return array('ok' => false, 'error' => 'Booking ini sudah memiliki pengajuan pembayaran ' . strtolower(rfp_status_label($existing['status'])) . '.');
    }
    if (empty($booking['payment_ready'])) {
        return array('ok' => false, 'error' => 'Booking ini sudah berada pada data history dan tidak dapat diajukan untuk pelunasan lagi.');
    }

    $addons = rfp_selected_addons($booking, is_array($addonInput) ? $addonInput : array());
    $baseDue = rfp_booking_is_due($booking);
    $totalDue = $baseDue + $addons['total'];
    if ($totalDue <= 0) {
        return array('ok' => false, 'error' => 'Total pelunasan tidak valid. Silakan hubungi admin.');
    }
    $proof = rfp_upload_proof($file);
    if (!$proof['ok']) {
        return $proof;
    }
    $token = rfp_new_public_token();
    $sql = 'INSERT INTO booking_payment_submissions (booking_uniqid, legacy_source, customer_name, customer_email, customer_phone, studio, package_name, package_type, booking_date, booking_time, base_due, addons_total, total_due, proof_filename, proof_mime, proof_size, public_token, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'pending\')';
    $statement = mysqli_prepare($koneksi, $sql);
    if (!$statement) {
        @unlink(__DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'payment-proofs' . DIRECTORY_SEPARATOR . $proof['filename']);
        return array('ok' => false, 'error' => 'Database belum siap. Jalankan migration pelunasan terlebih dahulu.');
    }

    $params = array(
        $booking['uniqid'], $booking['legacy_source'], $booking['nama'], $booking['email'], $booking['no_telp'],
        $booking['studio'], $booking['package'], $booking['tipe_package'], $booking['tanggal'], $booking['jam'],
        $baseDue, $addons['total'], $totalDue, $proof['filename'], $proof['mime'], $proof['size'], $token
    );

    mysqli_begin_transaction($koneksi);
    rfp_bind_params($statement, str_repeat('s', 10) . 'ddd' . 'ss' . 'is', $params);
    $created = mysqli_stmt_execute($statement);
    $submissionId = $created ? mysqli_insert_id($koneksi) : 0;
    mysqli_stmt_close($statement);
    if (!$created || !$submissionId) {
        mysqli_rollback($koneksi);
        @unlink(__DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'payment-proofs' . DIRECTORY_SEPARATOR . $proof['filename']);
        return array('ok' => false, 'error' => 'Pengajuan pembayaran gagal disimpan.');
    }

    $invoiceNumber = 'RFS-' . date('Ymd') . '-' . str_pad((string)$submissionId, 5, '0', STR_PAD_LEFT);
    $invoiceStmt = mysqli_prepare($koneksi, 'UPDATE booking_payment_submissions SET invoice_number = ? WHERE id = ?');
    $invoiceUpdated = false;
    if ($invoiceStmt) {
        $invoiceParams = array($invoiceNumber, $submissionId);
        rfp_bind_params($invoiceStmt, 'si', $invoiceParams);
        $invoiceUpdated = mysqli_stmt_execute($invoiceStmt);
        mysqli_stmt_close($invoiceStmt);
    }

    $addonsSaved = $invoiceUpdated;
    foreach ($addons['items'] as $item) {
        $itemStmt = mysqli_prepare($koneksi, 'INSERT INTO booking_payment_addons (submission_id, addon_code, addon_name, unit_price, quantity, line_total) VALUES (?, ?, ?, ?, ?, ?)');
        if (!$itemStmt) {
            $addonsSaved = false;
            break;
        }
        $itemParams = array($submissionId, $item['code'], $item['name'], $item['unit_price'], $item['quantity'], $item['line_total']);
        rfp_bind_params($itemStmt, 'issdid', $itemParams);
        if (!mysqli_stmt_execute($itemStmt)) {
            $addonsSaved = false;
        }
        mysqli_stmt_close($itemStmt);
        if (!$addonsSaved) {
            break;
        }
    }

    if (!$addonsSaved) {
        mysqli_rollback($koneksi);
        @unlink(__DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'payment-proofs' . DIRECTORY_SEPARATOR . $proof['filename']);
        return array('ok' => false, 'error' => 'Data tambahan pembayaran gagal disimpan.');
    }
    mysqli_commit($koneksi);
    return array('ok' => true, 'id' => $submissionId, 'token' => $token, 'invoice_number' => $invoiceNumber, 'total_due' => $totalDue);
}

function rfp_get_submission_by_token($token){
    return rfp_stmt_one('SELECT * FROM booking_payment_submissions WHERE public_token = ? LIMIT 1', 's', array($token));
}

function rfp_get_submission_by_id($id){
    return rfp_stmt_one('SELECT * FROM booking_payment_submissions WHERE id = ? LIMIT 1', 'i', array((int)$id));
}

function rfp_submission_addons($submissionId){
    return rfp_stmt_rows('SELECT * FROM booking_payment_addons WHERE submission_id = ? ORDER BY id ASC', 'i', array((int)$submissionId));
}

function rfp_admin_update_submission($id, $status, $note, $adminName, $studio){
    global $koneksi;
    if (!in_array($status, array('approved', 'rejected'), true)) {
        return false;
    }
    $statement = mysqli_prepare($koneksi, "UPDATE booking_payment_submissions SET status = ?, admin_note = ?, reviewed_by = ?, reviewed_at = NOW() WHERE id = ? AND status = 'pending' AND (studio = ? OR (studio = '' AND ? = 'Bekasi-Kabupaten'))");
    if (!$statement) {
        return false;
    }
    $params = array($status, trim($note), trim($adminName), (int)$id, $studio, $studio);
    rfp_bind_params($statement, 'sssiss', $params);
    $result = mysqli_stmt_execute($statement) && mysqli_stmt_affected_rows($statement) === 1;
    mysqli_stmt_close($statement);
    return $result;
}

function rfp_admin_submissions($studio){
    $sql = 'SELECT * FROM booking_payment_submissions WHERE studio = ? OR (studio = \'\' AND ? = \'Bekasi-Kabupaten\') ORDER BY FIELD(status, \'pending\', \'rejected\', \'approved\'), created_at DESC';
    return rfp_stmt_rows($sql, 'ss', array($studio, $studio));
}

function rfp_pdf_escape($text){
    $text = html_entity_decode((string)$text, ENT_QUOTES, 'UTF-8');
    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            $text = $converted;
        }
    }
    $text = preg_replace('/[^\x20-\x7E\xA0-\xFF]/', '', $text);
    return str_replace(array('\\', '(', ')'), array('\\\\', '\\(', '\\)'), $text);
}

function rfp_invoice_pdf($submission, $addons){
    $status = $submission['status'] === 'approved' ? 'LUNAS' : 'MENUNGGU VERIFIKASI';
    $lines = array(
        array('RF STUDIO', 18, true),
        array('INVOICE PELUNASAN - ' . $status, 13, true),
        array('Nomor Invoice: ' . $submission['invoice_number'], 10, false),
        array('Dibuat: ' . date('d-m-Y H:i', strtotime($submission['created_at'])), 10, false),
        array('', 10, false),
        array('Pelanggan: ' . $submission['customer_name'], 10, false),
        array('Email: ' . $submission['customer_email'], 10, false),
        array('Studio: ' . rfp_studio_label($submission['studio']), 10, false),
        array('Booking: ' . $submission['booking_date'] . ' | ' . $submission['booking_time'], 10, false),
        array('Paket: ' . trim($submission['package_name'] . ' ' . $submission['package_type']), 10, false),
        array('', 10, false),
        array('RINCIAN TAGIHAN', 11, true),
        array('Sisa paket / jasa: ' . rfp_format_rupiah($submission['base_due']), 10, false)
    );
    foreach ($addons as $addon) {
        $lines[] = array($addon['addon_name'] . ' x' . $addon['quantity'] . ': ' . rfp_format_rupiah($addon['line_total']), 10, false);
    }
    $lines[] = array('', 10, false);
    $lines[] = array('TOTAL PELUNASAN: ' . rfp_format_rupiah($submission['total_due']), 12, true);
    $lines[] = array('Status: ' . rfp_status_label($submission['status']), 10, false);
    if ($submission['status'] !== 'approved') {
        $lines[] = array('Bukti transfer telah diterima dan sedang diperiksa admin.', 9, false);
        $lines[] = array('Dokumen ini belum merupakan tanda lunas.', 9, false);
    } else {
        $lines[] = array('Pembayaran telah diverifikasi oleh admin RF Studio.', 9, false);
    }

    $content = '';
    $y = 795;
    foreach ($lines as $line) {
        $font = $line[2] ? 'F2' : 'F1';
        $text = rfp_pdf_escape($line[0]);
        $content .= 'BT /' . $font . ' ' . $line[1] . ' Tf 48 ' . $y . ' Td (' . $text . ") Tj ET\n";
        $y -= $line[0] === '' ? 9 : 18;
    }
    $objects = array();
    $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
    $objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
    $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>';
    $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
    $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>';
    $objects[] = '<< /Length ' . strlen($content) . " >>\nstream\n" . $content . 'endstream';

    $pdf = "%PDF-1.4\n";
    $offsets = array(0);
    foreach ($objects as $index => $object) {
        $offsets[] = strlen($pdf);
        $pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
    }
    $xref = strlen($pdf);
    $pdf .= 'xref' . "\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
    for ($index = 1; $index <= count($objects); $index++) {
        $pdf .= sprintf('%010d 00000 n ', $offsets[$index]) . "\n";
    }
    $pdf .= 'trailer' . "\n<< /Size " . (count($objects) + 1) . ' /Root 1 0 R >>' . "\nstartxref\n" . $xref . "\n%%EOF";
    return $pdf;
}
