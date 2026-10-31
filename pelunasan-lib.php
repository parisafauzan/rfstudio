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

function rfp_same_origin_post(){
    $host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
    if ($host === '') {
        return true;
    }
    $source = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
    if ($source === '') {
        return true;
    }
    $sourceHost = parse_url($source, PHP_URL_HOST);
    if (!$sourceHost) {
        return false;
    }
    $sourceHost = strtolower($sourceHost);
    $hostWithoutPort = preg_replace('/:\\d+$/', '', $host);
    return $sourceHost === $host || $sourceHost === $hostWithoutPort;
}

function rfp_ini_bytes($value){
    $value = trim((string)$value);
    if ($value === '') {
        return 0;
    }
    $unit = strtolower(substr($value, -1));
    $number = (float)$value;
    if ($unit === 'g') {
        return (int)($number * 1024 * 1024 * 1024);
    }
    if ($unit === 'm') {
        return (int)($number * 1024 * 1024);
    }
    if ($unit === 'k') {
        return (int)($number * 1024);
    }
    return (int)$number;
}

function rfp_post_was_discarded(){
    $contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int)$_SERVER['CONTENT_LENGTH'] : 0;
    $postLimit = rfp_ini_bytes(ini_get('post_max_size'));
    return $contentLength > 0 && empty($_POST) && empty($_FILES) && $postLimit > 0 && $contentLength > $postLimit;
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
    if (!is_string($amount)) {
        return max(0, (float)$amount);
    }
    $value = preg_replace('/[^0-9,\.\-]/', '', trim($amount));
    if ($value === '' || $value === '-') {
        return 0.0;
    }
    $hasDot = strpos($value, '.') !== false;
    $hasComma = strpos($value, ',') !== false;
    if ($hasDot && $hasComma) {
        // Format Indonesia: 1.250.000,00.
        if (strrpos($value, ',') > strrpos($value, '.')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            // Format internasional: 1,250,000.00.
            $value = str_replace(',', '', $value);
        }
    } elseif ($hasDot) {
        // Titik dengan kelompok tiga digit adalah pemisah ribuan. Nilai
        // DECIMAL MySQL seperti 70000.00 tetap diperlakukan sebagai desimal.
        if (preg_match('/^-?\d{1,3}(?:\.\d{3})+$/', $value)) {
            $value = str_replace('.', '', $value);
        }
    } elseif ($hasComma) {
        if (preg_match('/^-?\d{1,3}(?:,\d{3})+$/', $value)) {
            $value = str_replace(',', '', $value);
        } else {
            $value = str_replace(',', '.', $value);
        }
    }
    return max(0, (float)$value);
}

function rfp_database_available(){
    global $koneksi;
    return $koneksi instanceof mysqli;
}

function rfp_database_connection_message(){
    $message = mysqli_connect_error();
    return $message ? $message : 'Koneksi database belum tersedia.';
}

function rfp_schema_ready(){
    global $koneksi;
    static $ready = null;
    if ($ready !== null) {
        return $ready;
    }
    if (!rfp_database_available()) {
        $ready = false;
        return $ready;
    }
    $result = mysqli_query($koneksi, "SHOW TABLES LIKE 'booking_payment_submissions'");
    $ready = $result && mysqli_num_rows($result) === 1;
    return $ready;
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

function rfp_payment_state($uniqid, $booking = null){
    global $koneksi;
    if (!rfp_schema_ready()) {
        return null;
    }
    $uniqid = mysqli_real_escape_string($koneksi, (string)$uniqid);
    $conditions = array("booking_uniqid = '$uniqid'");
    // Kode booking lama hanya enam karakter sehingga secara teori dapat dipakai
    // kembali. Cocokkan juga snapshot identitas agar bukti dari booking lain
    // tidak pernah menempel hanya karena uniqid-nya sama.
    if (is_array($booking)) {
        if (!empty($booking['legacy_source'])) {
            $source = mysqli_real_escape_string($koneksi, (string)$booking['legacy_source']);
            $conditions[] = "legacy_source = '$source'";
        }
        $identityFields = array(
            'email' => 'customer_email',
            'no_telp' => 'customer_phone',
            'tanggal' => 'booking_date',
            'jam' => 'booking_time',
            'package' => 'package_name',
            'tipe_package' => 'package_type',
            'studio' => 'studio'
        );
        foreach ($identityFields as $bookingField => $paymentField) {
            if (!array_key_exists($bookingField, $booking)) {
                continue;
            }
            $value = mysqli_real_escape_string($koneksi, trim((string)$booking[$bookingField]));
            if ($bookingField === 'email' || $bookingField === 'package' || $bookingField === 'tipe_package') {
                $conditions[] = "LOWER(TRIM(`$paymentField`)) = LOWER('$value')";
            } else {
                $conditions[] = "TRIM(`$paymentField`) = '$value'";
            }
        }
    }
    $result = mysqli_query($koneksi, 'SELECT * FROM booking_payment_submissions WHERE ' . implode(' AND ', $conditions) . ' ORDER BY id DESC LIMIT 1');
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

function rfp_studio_address($studio){
    if ($studio === 'Bekasi-Kota') {
        return 'Villa Taman Kartini Bekasi, Jl. Graha Juwita III Blok A2 No.3, Margahayu, Bekasi Timur';
    }
    return 'Jl. Arjuna 4 No.1, Setiamekar, Bekasi Timur';
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

function rfp_booking_initial_breakdown($booking){
    $package = strtolower(trim(isset($booking['package']) ? $booking['package'] : ''));
    $packageTotal = rfp_money(isset($booking['harga']) ? $booking['harga'] : 0);
    $items = array();
    $knownTotal = 0.0;
    $addItem = function($name, $quantity, $unitPrice) use (&$items, &$knownTotal) {
        $quantity = max(0, (int)$quantity);
        $unitPrice = rfp_money($unitPrice);
        if ($quantity < 1 || $unitPrice <= 0) {
            return;
        }
        $lineTotal = $quantity * $unitPrice;
        $items[] = array(
            'name' => $name,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $lineTotal,
            'source' => 'booking'
        );
        $knownTotal += $lineTotal;
    };

    $tambahCetak = trim(isset($booking['tambah_cetak']) ? (string)$booking['tambah_cetak'] : '');
    if (strpos($package, 'pas foto') !== false) {
        if ($tambahCetak !== '' && $tambahCetak !== '-' && preg_match_all('/(4x6|3x4|2x3|mix|visa)\s*:\s*(\d+)\s*template/i', $tambahCetak, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $label = strcasecmp($match[1], 'mix') === 0 ? 'Mix' : (strcasecmp($match[1], 'visa') === 0 ? 'Visa' : $match[1]);
                $addItem('Template cetak Pas Foto ' . $label, (int)$match[2], 15000);
            }
        }
    } elseif ($tambahCetak !== '' && $tambahCetak !== '-') {
        $printPrices = array(
            '5r' => 15000,
            '5r+frame' => 30000,
            '10rs' => 35000,
            '10rs+frame' => 70000,
            '17r/30x40+frame' => 150000,
            '30x40+frame' => 150000,
            '40x60+frame' => 350000,
            '60x90+frame' => 600000,
            '60x100+frame' => 650000,
            '70x100+frame' => 700000
        );
        if (preg_match_all('/\(([^)]+)\)\s*=\s*(\d+)/', $tambahCetak, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $key = strtolower(str_replace(' ', '', trim($match[1])));
                if (isset($printPrices[$key])) {
                    $addItem('Cetak foto ' . trim($match[1]), (int)$match[2], $printPrices[$key]);
                }
            }
        }
    }

    $addItem('Tambah anak', isset($booking['anak']) ? $booking['anak'] : 0, 35000);
    $addItem('Tambah dewasa', isset($booking['dewasa']) ? $booking['dewasa'] : 0, 50000);
    $addItem('Tambah orang', isset($booking['tambah_orang']) ? $booking['tambah_orang'] : 0, 15000);
    if (strpos($package, 'self photo') !== false) {
        $storedTime = max(0, (int)(isset($booking['tambah_waktu']) ? $booking['tambah_waktu'] : 0));
        $timeQuantity = $storedTime > 0 && $storedTime % 5 === 0 ? (int)($storedTime / 5) : 0;
        $addItem('Tambah waktu Self Photo', $timeQuantity, 20000);
    }
    $addItem('Hairdo', isset($booking['tambah_hairdo']) ? $booking['tambah_hairdo'] : 0, 250000);
    $addItem('Makeup', isset($booking['tambah_makeup']) ? $booking['tambah_makeup'] : 0, 400000);

    if ($knownTotal > $packageTotal) {
        $knownTotal = $packageTotal;
    }
    return array(
        'package_total' => $packageTotal,
        'package_base' => max(0, $packageTotal - $knownTotal),
        'booking_addons_total' => $knownTotal,
        'items' => $items,
        'raw_additions' => $tambahCetak
    );
}

function rfp_find_booking_snapshot_for_submission($submission){
    global $koneksi;
    if (!rfp_database_available() || empty($submission['booking_uniqid'])) {
        return null;
    }
    $uniqid = mysqli_real_escape_string($koneksi, (string)$submission['booking_uniqid']);
    $email = mysqli_real_escape_string($koneksi, trim((string)$submission['customer_email']));
    $date = mysqli_real_escape_string($koneksi, trim((string)$submission['booking_date']));
    $package = mysqli_real_escape_string($koneksi, trim((string)$submission['package_name']));
    $columns = 'id, uniqid, nama, no_telp, email, package, harga, hargasetelahdp, tanggal, jam, tipe_package, studio, tambah_cetak, anak, dewasa, tambah_orang, tambah_waktu, tambah_hairdo, tambah_makeup';
    foreach (array('data_booking_cobahampirfinishjuga', 'data_konfirmasi_cobahampirfinishjuga', 'data_history') as $table) {
        $sql = "SELECT $columns FROM `$table` WHERE uniqid = '$uniqid' AND LOWER(TRIM(email)) = LOWER('$email') AND tanggal = '$date' AND LOWER(TRIM(package)) = LOWER('$package') LIMIT 1";
        $result = mysqli_query($koneksi, $sql);
        $row = $result ? mysqli_fetch_assoc($result) : null;
        if ($row) {
            return $row;
        }
    }
    return null;
}

function rfp_booking_date_is_reached($date){
    $timestamp = strtotime((string)$date);
    return $timestamp !== false && $timestamp <= strtotime(date('Y-m-d'));
}

function rfp_find_bookings_by_email($email, &$lookupError = null, &$lookupWarning = null){
    global $koneksi;
    $email = strtolower(trim($email));
    $emailSql = mysqli_real_escape_string($koneksi, $email);
    $lookupError = null;
    $lookupWarning = null;
    // Pelunasan hanya mencari dua tabel booking yang masih aktif. data_history
    // adalah data selesai dan data_jamtanggal tidak menyimpan email pelanggan.
    // Query sengaja dipisah: gagal membaca satu tabel tidak boleh membatalkan
    // hasil dari tabel lainnya.
    $tables = array(
        'konfirmasi' => 'data_konfirmasi_cobahampirfinishjuga',
        'booking' => 'data_booking_cobahampirfinishjuga'
    );
    // Jika uniqid yang sama terdapat di dua tabel, data booking aktif menang.
    $priority = array('booking' => 1, 'konfirmasi' => 2);
    $unique = array();
    $failedTables = array();
    $successfulTables = 0;
    foreach ($tables as $source => $table) {
        $sql = "SELECT id, uniqid, nama, no_telp, email, package, harga, hargasetelahdp, tanggal, jam, tipe_package, studio, tambah_cetak, anak, dewasa, tambah_orang, tambah_waktu, tambah_hairdo, tambah_makeup FROM `$table` WHERE TRIM(email) = '$emailSql'";
        $result = mysqli_query($koneksi, $sql);
        if (!$result) {
            $detail = 'Tabel ' . $table . ' tidak dapat dibaca: ' . mysqli_error($koneksi);
            $failedTables[] = $table;
            error_log('RF payment lookup failed: ' . $detail);
            continue;
        }
        $successfulTables++;
        while ($row = mysqli_fetch_assoc($result)) {
            $key = $row['uniqid'];
            if (isset($unique[$key]) && $priority[$unique[$key]['legacy_source']] <= $priority[$source]) {
                continue;
            }
            $row['legacy_source'] = $source;
            $row['remaining_due'] = rfp_booking_is_due($row);
            $row['date_reached'] = rfp_booking_date_is_reached($row['tanggal']);
            // Booking pada tabel konfirmasi tetap ditampilkan agar pelanggan tahu
            // datanya ditemukan, tetapi belum boleh dilunasi sampai DP diverifikasi.
            $row['payment_ready'] = $source === 'booking';
            $row['payment_state'] = rfp_payment_state($row['uniqid'], $row);
            $unique[$key] = $row;
        }
    }
    if ($successfulTables === 0) {
        $lookupError = 'Kedua tabel booking aktif tidak dapat dibaca.';
    } elseif (!empty($failedTables)) {
        $lookupWarning = 'Sebagian data booking sedang tidak dapat dibaca. Hasil yang tersedia tetap ditampilkan.';
    }
    return array_values($unique);
}

function rfp_get_booking($uniqid, $legacySource, $email){
    global $koneksi;
    $tables = array(
        'konfirmasi' => 'data_konfirmasi_cobahampirfinishjuga',
        'booking' => 'data_booking_cobahampirfinishjuga'
    );
    if (!isset($tables[$legacySource])) {
        return null;
    }
    $uniqidSql = mysqli_real_escape_string($koneksi, (string)$uniqid);
    $emailSql = mysqli_real_escape_string($koneksi, strtolower(trim($email)));
    $sql = 'SELECT id, uniqid, nama, no_telp, email, package, harga, hargasetelahdp, tanggal, jam, tipe_package, studio, tambah_cetak, anak, dewasa, tambah_orang, tambah_waktu, tambah_hairdo, tambah_makeup FROM `' . $tables[$legacySource] . "` WHERE uniqid = '$uniqidSql' AND TRIM(email) = '$emailSql' LIMIT 1";
    $result = mysqli_query($koneksi, $sql);
    $booking = $result ? mysqli_fetch_assoc($result) : null;
    if (!$booking) {
        return null;
    }
    $booking['legacy_source'] = $legacySource;
    $booking['remaining_due'] = rfp_booking_is_due($booking);
    $booking['date_reached'] = rfp_booking_date_is_reached($booking['tanggal']);
    $booking['payment_ready'] = $legacySource === 'booking';
    $booking['payment_state'] = rfp_payment_state($booking['uniqid'], $booking);
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
        'print_10r' => array('name' => 'Cetak foto 10RS', 'price' => 35000),
        'print_10r_frame' => array('name' => 'Cetak foto 10RS + frame', 'price' => 70000),
        'print_30x40_frame' => array('name' => 'Cetak foto 17R/30x40 + frame', 'price' => 150000),
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
    if (empty($file) || !isset($file['error'])) {
        return array('ok' => false, 'code' => 'proof_required', 'error' => 'Bukti transfer wajib diunggah.');
    }
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        return array('ok' => false, 'code' => 'proof_too_large', 'error' => 'Ukuran foto melebihi batas server. Pilih foto maksimal 5 MB.');
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return array('ok' => false, 'code' => 'proof_upload_failed', 'error' => 'Upload bukti transfer tidak selesai. Silakan pilih foto lagi dan coba ulang.');
    }
    if ($file['size'] < 1 || $file['size'] > 5 * 1024 * 1024) {
        return array('ok' => false, 'code' => 'proof_too_large', 'error' => 'Ukuran bukti transfer maksimal 5 MB.');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : '';
    if ($finfo) {
        finfo_close($finfo);
    }
    $extensions = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp');
    if (!isset($extensions[$mime])) {
        return array('ok' => false, 'code' => 'proof_invalid_type', 'error' => 'Format bukti transfer harus JPG, PNG, atau WEBP.');
    }
    try {
        $random = bin2hex(random_bytes(16));
    } catch (Exception $e) {
        $random = sha1(uniqid('payment', true));
    }
    // Samakan lokasi dengan aset bukti transfer lama, dengan folder pelunasan
    // tersendiri agar file DP dan pelunasan tidak tercampur.
    $directory = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'data_pelunasan';
    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
        return array('ok' => false, 'code' => 'proof_store_failed', 'error' => 'Folder penyimpanan bukti transfer tidak dapat dibuat.');
    }
    $filename = 'lunastf-' . date('Ymd-His') . '-' . $random . '.' . $extensions[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename)) {
        return array('ok' => false, 'code' => 'proof_store_failed', 'error' => 'Bukti transfer gagal disimpan. Silakan coba lagi.');
    }
    return array('ok' => true, 'filename' => $filename, 'mime' => $mime, 'size' => (int)$file['size']);
}

function rfp_payment_proof_path($filename){
    return __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'data_pelunasan' . DIRECTORY_SEPARATOR . basename($filename);
}

function rfp_new_public_token(){
    try {
        return bin2hex(random_bytes(32));
    } catch (Exception $e) {
        return hash('sha256', uniqid('invoice', true));
    }
}

function rfp_payment_financial_columns_ready(){
    static $ready = null;
    global $koneksi;
    if ($ready !== null) {
        return $ready;
    }
    $packageColumn = mysqli_query($koneksi, "SHOW COLUMNS FROM booking_payment_submissions LIKE 'package_total'");
    $depositColumn = mysqli_query($koneksi, "SHOW COLUMNS FROM booking_payment_submissions LIKE 'deposit_paid'");
    $ready = $packageColumn && mysqli_num_rows($packageColumn) > 0 && $depositColumn && mysqli_num_rows($depositColumn) > 0;
    return $ready;
}

function rfp_submission_financials($submission){
    global $koneksi;
    $baseDue = rfp_money(isset($submission['base_due']) ? $submission['base_due'] : 0);
    $packageTotal = rfp_money(isset($submission['package_total']) ? $submission['package_total'] : 0);
    $depositPaid = rfp_money(isset($submission['deposit_paid']) ? $submission['deposit_paid'] : 0);

    // Kompatibilitas untuk pengajuan yang dibuat sebelum kolom snapshot harga
    // ditambahkan. Harga dicari dari lifecycle booking berdasarkan uniqid.
    if ($packageTotal <= 0 && !empty($submission['booking_uniqid'])) {
        $uniqid = mysqli_real_escape_string($koneksi, (string)$submission['booking_uniqid']);
        $tables = array('data_booking_cobahampirfinishjuga', 'data_konfirmasi_cobahampirfinishjuga', 'data_history');
        foreach ($tables as $table) {
            $result = mysqli_query($koneksi, "SELECT harga FROM `$table` WHERE uniqid = '$uniqid' LIMIT 1");
            $row = $result ? mysqli_fetch_assoc($result) : null;
            if ($row) {
                $packageTotal = rfp_money($row['harga']);
                break;
            }
        }
    }
    if ($packageTotal <= 0) {
        $packageTotal = $baseDue;
    }
    if ($depositPaid <= 0 && $packageTotal > $baseDue) {
        $depositPaid = $packageTotal - $baseDue;
    }
    return array(
        'package_total' => $packageTotal,
        'deposit_paid' => max(0, $depositPaid),
        'base_due' => $baseDue,
        'addons_total' => rfp_money(isset($submission['addons_total']) ? $submission['addons_total'] : 0),
        'total_due' => rfp_money(isset($submission['total_due']) ? $submission['total_due'] : 0)
    );
}

function rfp_create_submission($booking, $addonInput, $file){
    global $koneksi;
    $existing = rfp_payment_state($booking['uniqid'], $booking);
    if ($existing && ($existing['status'] === 'pending' || $existing['status'] === 'approved')) {
        return array('ok' => false, 'code' => 'existing_submission', 'error' => 'Booking ini sudah memiliki pengajuan pembayaran ' . strtolower(rfp_status_label($existing['status'])) . '.');
    }
    if (empty($booking['payment_ready'])) {
        return array('ok' => false, 'code' => 'booking_not_ready', 'error' => 'Booking ini tidak dapat diajukan untuk pelunasan lagi.');
    }

    $addons = rfp_selected_addons($booking, is_array($addonInput) ? $addonInput : array());
    $baseDue = rfp_booking_is_due($booking);
    $packageTotal = rfp_money(isset($booking['harga']) ? $booking['harga'] : $baseDue);
    if ($packageTotal < $baseDue) {
        $packageTotal = $baseDue;
    }
    $depositPaid = max(0, $packageTotal - $baseDue);
    $totalDue = $baseDue + $addons['total'];
    if ($totalDue <= 0) {
        return array('ok' => false, 'code' => 'invalid_total', 'error' => 'Total pelunasan tidak valid. Silakan hubungi admin.');
    }
    $proof = rfp_upload_proof($file);
    if (!$proof['ok']) {
        return $proof;
    }
    $token = rfp_new_public_token();
    $hasFinancialSnapshot = rfp_payment_financial_columns_ready();
    if ($hasFinancialSnapshot) {
        $sql = 'INSERT INTO booking_payment_submissions (booking_uniqid, legacy_source, customer_name, customer_email, customer_phone, studio, package_name, package_type, booking_date, booking_time, package_total, deposit_paid, base_due, addons_total, total_due, proof_filename, proof_mime, proof_size, public_token, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'pending\')';
    } else {
        $sql = 'INSERT INTO booking_payment_submissions (booking_uniqid, legacy_source, customer_name, customer_email, customer_phone, studio, package_name, package_type, booking_date, booking_time, base_due, addons_total, total_due, proof_filename, proof_mime, proof_size, public_token, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'pending\')';
    }
    $statement = mysqli_prepare($koneksi, $sql);
    if (!$statement) {
        @unlink(rfp_payment_proof_path($proof['filename']));
        error_log('RF payment prepare failed: ' . mysqli_error($koneksi));
        return array('ok' => false, 'code' => 'db_not_ready', 'error' => 'Database belum siap. Jalankan migration pelunasan terlebih dahulu.');
    }

    $params = array(
        $booking['uniqid'], $booking['legacy_source'], $booking['nama'], $booking['email'], $booking['no_telp'],
        $booking['studio'], $booking['package'], $booking['tipe_package'], $booking['tanggal'], $booking['jam']
    );
    if ($hasFinancialSnapshot) {
        $params[] = $packageTotal;
        $params[] = $depositPaid;
    }
    $params[] = $baseDue;
    $params[] = $addons['total'];
    $params[] = $totalDue;
    $params[] = $proof['filename'];
    $params[] = $proof['mime'];
    $params[] = $proof['size'];
    $params[] = $token;

    mysqli_begin_transaction($koneksi);
    $bindTypes = str_repeat('s', 10) . ($hasFinancialSnapshot ? 'dd' : '') . 'ddd' . 'ss' . 'is';
    rfp_bind_params($statement, $bindTypes, $params);
    $created = mysqli_stmt_execute($statement);
    $statementError = $created ? '' : mysqli_stmt_error($statement);
    $submissionId = $created ? mysqli_insert_id($koneksi) : 0;
    mysqli_stmt_close($statement);
    if (!$created || !$submissionId) {
        mysqli_rollback($koneksi);
        @unlink(rfp_payment_proof_path($proof['filename']));
        error_log('RF payment insert failed: ' . $statementError);
        return array('ok' => false, 'code' => 'insert_failed', 'error' => 'Pengajuan pembayaran gagal disimpan.');
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
        @unlink(rfp_payment_proof_path($proof['filename']));
        error_log('RF payment invoice/addon transaction failed for submission ' . (int)$submissionId);
        return array('ok' => false, 'code' => 'addon_failed', 'error' => 'Data tambahan pembayaran gagal disimpan.');
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
    $statement = mysqli_prepare($koneksi, "UPDATE booking_payment_submissions SET status = ?, admin_note = ?, reviewed_by = ?, reviewed_at = NOW() WHERE id = ? AND status = 'pending' AND legacy_source = 'booking' AND (studio = ? OR (studio = '' AND ? = 'Bekasi-Kabupaten'))");
    if (!$statement) {
        return false;
    }
    $params = array($status, trim($note), trim($adminName), (int)$id, $studio, $studio);
    rfp_bind_params($statement, 'sssiss', $params);
    $result = mysqli_stmt_execute($statement) && mysqli_stmt_affected_rows($statement) === 1;
    mysqli_stmt_close($statement);
    return $result;
}

function rfp_handle_admin_payment_review($studio, $adminName, $redirectUrl){
    if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['rfp_payment_review'])) {
        return '';
    }
    if (!rfp_csrf_is_valid(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
        return 'Sesi verifikasi telah berakhir. Muat ulang halaman lalu coba kembali.';
    }
    $decision = isset($_POST['review_action']) ? $_POST['review_action'] : '';
    $status = $decision === 'approve' ? 'approved' : ($decision === 'reject' ? 'rejected' : '');
    $note = trim(isset($_POST['admin_note']) ? $_POST['admin_note'] : '');
    if ($status === '') {
        return 'Aksi verifikasi pelunasan tidak valid.';
    }
    if ($status === 'rejected' && $note === '') {
        $note = 'Bukti atau nominal pelunasan belum sesuai. Silakan perbaiki dan unggah ulang.';
    }
    $submissionId = isset($_POST['submission_id']) ? (int)$_POST['submission_id'] : 0;
    if (!rfp_admin_update_submission($submissionId, $status, $note, $adminName, $studio)) {
        return 'Pengajuan tidak dapat diperbarui. Mungkin sudah diproses oleh admin lain.';
    }
    rfp_flash('success', $status === 'approved' ? 'Pelunasan disetujui dan invoice sudah berstatus LUNAS.' : 'Pengajuan pelunasan ditolak. Customer dapat mengunggah ulang.');
    header('Location: ' . $redirectUrl);
    exit;
}

function rfp_admin_submissions($studio){
    $sql = 'SELECT * FROM booking_payment_submissions WHERE legacy_source = \'booking\' AND (studio = ? OR (studio = \'\' AND ? = \'Bekasi-Kabupaten\')) ORDER BY FIELD(status, \'pending\', \'rejected\', \'approved\'), created_at DESC';
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

function rfp_pdf_text(&$content, $x, $y, $text, $size, $bold = false, $color = array(25, 31, 39), $align = 'left'){
    $safe = rfp_pdf_escape($text);
    $estimatedWidth = strlen($safe) * $size * 0.50;
    if ($align === 'right') {
        $x -= $estimatedWidth;
    } elseif ($align === 'center') {
        $x -= $estimatedWidth / 2;
    }
    $content .= sprintf("%.3F %.3F %.3F rg\n", $color[0] / 255, $color[1] / 255, $color[2] / 255);
    $content .= 'BT /' . ($bold ? 'F2' : 'F1') . ' ' . $size . ' Tf ' . round($x, 2) . ' ' . round($y, 2) . ' Td (' . $safe . ") Tj ET\n";
}

function rfp_pdf_rect(&$content, $x, $y, $width, $height, $fill, $stroke = null){
    if ($fill !== null) {
        $content .= sprintf("%.3F %.3F %.3F rg\n", $fill[0] / 255, $fill[1] / 255, $fill[2] / 255);
    }
    if ($stroke !== null) {
        $content .= sprintf("%.3F %.3F %.3F RG\n", $stroke[0] / 255, $stroke[1] / 255, $stroke[2] / 255);
    }
    $content .= round($x, 2) . ' ' . round($y, 2) . ' ' . round($width, 2) . ' ' . round($height, 2) . ' re ';
    $content .= $fill !== null && $stroke !== null ? "B\n" : ($fill !== null ? "f\n" : "S\n");
}

function rfp_pdf_line(&$content, $x1, $y1, $x2, $y2, $color = array(220, 224, 229), $width = 1){
    $content .= sprintf("%.3F %.3F %.3F RG\n", $color[0] / 255, $color[1] / 255, $color[2] / 255);
    $content .= round($width, 2) . ' w ' . round($x1, 2) . ' ' . round($y1, 2) . ' m ' . round($x2, 2) . ' ' . round($y2, 2) . " l S\n";
}

function rfp_pdf_wrapped_text(&$content, $x, $y, $text, $size, $maxCharacters, $lineHeight, $bold = false, $color = array(25, 31, 39)){
    $lines = explode("\n", wordwrap((string)$text, $maxCharacters, "\n", false));
    foreach ($lines as $line) {
        rfp_pdf_text($content, $x, $y, $line, $size, $bold, $color);
        $y -= $lineHeight;
    }
    return $y;
}

function rfp_pdf_paeth($a, $b, $c){
    $p = $a + $b - $c;
    $pa = abs($p - $a);
    $pb = abs($p - $b);
    $pc = abs($p - $c);
    return $pa <= $pb && $pa <= $pc ? $a : ($pb <= $pc ? $b : $c);
}

function rfp_pdf_png_rgb($path){
    if (!is_file($path) || !function_exists('gzuncompress')) {
        return null;
    }
    $png = file_get_contents($path);
    if ($png === false || substr($png, 0, 8) !== "\x89PNG\r\n\x1a\n") {
        return null;
    }
    $offset = 8;
    $width = 0;
    $height = 0;
    $bitDepth = 0;
    $colorType = -1;
    $compressed = '';
    $length = strlen($png);
    while ($offset + 12 <= $length) {
        $chunkLength = unpack('N', substr($png, $offset, 4));
        $chunkLength = $chunkLength[1];
        $type = substr($png, $offset + 4, 4);
        $data = substr($png, $offset + 8, $chunkLength);
        if ($type === 'IHDR') {
            $header = unpack('Nwidth/Nheight/Cdepth/Ctype', substr($data, 0, 10));
            $width = $header['width'];
            $height = $header['height'];
            $bitDepth = $header['depth'];
            $colorType = $header['type'];
        } elseif ($type === 'IDAT') {
            $compressed .= $data;
        } elseif ($type === 'IEND') {
            break;
        }
        $offset += 12 + $chunkLength;
    }
    if ($width < 1 || $height < 1 || $bitDepth !== 8 || !in_array($colorType, array(2, 6), true)) {
        return null;
    }
    $raw = @gzuncompress($compressed);
    if ($raw === false) {
        return null;
    }
    $bytesPerPixel = $colorType === 6 ? 4 : 3;
    $rowBytes = $width * $bytesPerPixel;
    $position = 0;
    $previous = array_fill(0, $rowBytes, 0);
    $rgb = '';
    for ($row = 0; $row < $height; $row++) {
        if ($position + $rowBytes + 1 > strlen($raw)) {
            return null;
        }
        $filter = ord($raw[$position++]);
        $scanline = substr($raw, $position, $rowBytes);
        $position += $rowBytes;
        $reconstructed = array();
        for ($index = 0; $index < $rowBytes; $index++) {
            $value = ord($scanline[$index]);
            $left = $index >= $bytesPerPixel ? $reconstructed[$index - $bytesPerPixel] : 0;
            $up = $previous[$index];
            $upLeft = $index >= $bytesPerPixel ? $previous[$index - $bytesPerPixel] : 0;
            if ($filter === 1) {
                $value = ($value + $left) & 255;
            } elseif ($filter === 2) {
                $value = ($value + $up) & 255;
            } elseif ($filter === 3) {
                $value = ($value + (int)floor(($left + $up) / 2)) & 255;
            } elseif ($filter === 4) {
                $value = ($value + rfp_pdf_paeth($left, $up, $upLeft)) & 255;
            } elseif ($filter !== 0) {
                return null;
            }
            $reconstructed[$index] = $value;
        }
        for ($pixel = 0; $pixel < $width; $pixel++) {
            $base = $pixel * $bytesPerPixel;
            $alpha = $colorType === 6 ? $reconstructed[$base + 3] : 255;
            for ($channel = 0; $channel < 3; $channel++) {
                $value = $reconstructed[$base + $channel];
                $flattened = (int)round(($value * $alpha + 255 * (255 - $alpha)) / 255);
                $rgb .= chr($flattened);
            }
        }
        $previous = $reconstructed;
    }
    return array('width' => $width, 'height' => $height, 'data' => gzcompress($rgb, 9));
}

function rfp_invoice_pdf_legacy($submission, $addons){
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

function rfp_invoice_pdf($submission, $addons){
    $financials = rfp_submission_financials($submission);
    $bookingSnapshot = isset($submission['_booking_snapshot']) && is_array($submission['_booking_snapshot'])
        ? $submission['_booking_snapshot']
        : rfp_find_booking_snapshot_for_submission($submission);
    $initialBreakdown = $bookingSnapshot ? rfp_booking_initial_breakdown($bookingSnapshot) : array(
        'package_base' => $financials['package_total'],
        'items' => array()
    );
    $isApproved = $submission['status'] === 'approved';
    $status = $isApproved ? 'LUNAS' : ($submission['status'] === 'rejected' ? 'DITOLAK' : 'MENUNGGU VERIFIKASI');
    $brand = array(15, 88, 160);
    $dark = array(26, 32, 44);
    $muted = array(102, 112, 128);
    $light = array(244, 247, 250);
    $content = '';

    rfp_pdf_rect($content, 0, 0, 595, 842, array(255, 255, 255));
    rfp_pdf_text($content, 46, 744, 'RF STUDIO', 16, true, $dark);
    rfp_pdf_text($content, 46, 728, 'Photography & Creative Studio', 8, false, $muted);
    rfp_pdf_text($content, 548, 774, 'INVOICE', 27, true, $dark, 'right');
    rfp_pdf_text($content, 548, 750, 'No. ' . $submission['invoice_number'], 9, true, $brand, 'right');
    rfp_pdf_text($content, 548, 734, 'Tanggal: ' . date('d-m-Y', strtotime($submission['created_at'])), 8, false, $muted, 'right');
    rfp_pdf_text($content, 548, 718, 'Jadwal: ' . $submission['booking_date'], 8, false, $muted, 'right');
    $statusWidth = max(76, strlen($status) * 5.4 + 18);
    rfp_pdf_rect($content, 548 - $statusWidth, 686, $statusWidth, 20, $isApproved ? array(222, 247, 231) : array(255, 244, 204));
    rfp_pdf_text($content, 548 - ($statusWidth / 2), 692, $status, 8, true, $isApproved ? array(30, 126, 62) : array(143, 98, 0), 'center');
    rfp_pdf_line($content, 46, 672, 548, 672, $brand, 2);

    rfp_pdf_text($content, 46, 646, 'DARI', 8, true, $brand);
    rfp_pdf_text($content, 46, 626, 'Rizal Foto Studio', 11, true, $dark);
    rfp_pdf_text($content, 46, 610, rfp_studio_label($submission['studio']), 9, false, $dark);
    rfp_pdf_wrapped_text($content, 46, 594, rfp_studio_address($submission['studio']), 7.2, 54, 11, false, $muted);
    rfp_pdf_text($content, 320, 646, 'DITAGIHKAN KEPADA', 8, true, $brand);
    rfp_pdf_text($content, 320, 626, $submission['customer_name'], 11, true, $dark);
    rfp_pdf_text($content, 320, 610, $submission['customer_email'], 8.5, false, $dark);
    rfp_pdf_text($content, 320, 594, $submission['customer_phone'], 8.5, false, $muted);

    rfp_pdf_rect($content, 46, 530, 502, 42, $light);
    rfp_pdf_text($content, 58, 554, 'PAKET', 7, true, $muted);
    rfp_pdf_text($content, 58, 538, trim($submission['package_name'] . ' ' . $submission['package_type']), 9, true, $dark);
    rfp_pdf_text($content, 286, 554, 'JADWAL FOTO', 7, true, $muted);
    rfp_pdf_text($content, 286, 538, $submission['booking_date'] . ' | ' . $submission['booking_time'], 9, true, $dark);

    $tableTop = 500;
    rfp_pdf_rect($content, 46, $tableTop, 502, 24, $brand);
    rfp_pdf_text($content, 58, $tableTop + 8, 'DESKRIPSI', 8, true, array(255, 255, 255));
    rfp_pdf_text($content, 410, $tableTop + 8, 'QTY', 8, true, array(255, 255, 255), 'right');
    rfp_pdf_text($content, 536, $tableTop + 8, 'JUMLAH', 8, true, array(255, 255, 255), 'right');
    $rows = array(array('Harga paket awal', 1, $initialBreakdown['package_base']));
    foreach ($initialBreakdown['items'] as $initialItem) {
        $rows[] = array($initialItem['name'] . ' (saat booking)', (int)$initialItem['quantity'], rfp_money($initialItem['line_total']));
    }
    $rows[] = array('DP telah dibayar', 1, -$financials['deposit_paid']);
    foreach ($addons as $addon) {
        $rows[] = array($addon['addon_name'] . ' (saat pelunasan)', (int)$addon['quantity'], rfp_money($addon['line_total']));
    }
    if (count($rows) > 12) {
        $visibleRows = array_slice($rows, 0, 11);
        $remainingTotal = 0;
        foreach (array_slice($rows, 11) as $remainingRow) {
            $remainingTotal += $remainingRow[2];
        }
        $visibleRows[] = array('Tambahan lainnya (tersimpan di sistem)', count($rows) - 11, $remainingTotal);
        $rows = $visibleRows;
    }
    $rowY = $tableTop - 24;
    foreach ($rows as $index => $row) {
        if ($index % 2 === 1) {
            rfp_pdf_rect($content, 46, $rowY, 502, 24, $light);
        }
        rfp_pdf_text($content, 58, $rowY + 8, $row[0], 8.5, false, $dark);
        rfp_pdf_text($content, 410, $rowY + 8, (string)$row[1], 8.5, false, $dark, 'right');
        $amountLabel = ($row[2] < 0 ? '- ' : '') . rfp_format_rupiah(abs($row[2]));
        rfp_pdf_text($content, 536, $rowY + 8, $amountLabel, 8.5, false, $dark, 'right');
        rfp_pdf_line($content, 46, $rowY, 548, $rowY, array(230, 234, 239), 0.5);
        $rowY -= 24;
    }

    $summaryTop = min($rowY - 6, 374);
    rfp_pdf_text($content, 46, $summaryTop - 2, 'CATATAN', 8, true, $brand);
    $note = $isApproved
        ? 'Pembayaran telah diverifikasi oleh admin RF Studio. Invoice ini merupakan bukti pelunasan yang sah.'
        : 'Bukti transfer sudah diterima dan sedang diperiksa admin. Invoice ini belum menjadi bukti lunas sampai status berubah menjadi LUNAS.';
    rfp_pdf_wrapped_text($content, 46, $summaryTop - 22, $note, 7.2, 43, 11, false, $muted);
    if (!empty($submission['admin_note'])) {
        rfp_pdf_text($content, 46, $summaryTop - 38, 'Catatan admin: ' . $submission['admin_note'], 7.2, false, $muted);
    }
    $boxX = 330;
    rfp_pdf_rect($content, $boxX, $summaryTop - 75, 218, 76, $light);
    rfp_pdf_text($content, $boxX + 12, $summaryTop - 19, 'Sisa paket setelah DP', 8, false, $muted);
    rfp_pdf_text($content, 536, $summaryTop - 19, rfp_format_rupiah($financials['base_due']), 8.5, true, $dark, 'right');
    rfp_pdf_text($content, $boxX + 12, $summaryTop - 38, 'Tambahan', 8, false, $muted);
    rfp_pdf_text($content, 536, $summaryTop - 38, rfp_format_rupiah($financials['addons_total']), 8.5, true, $dark, 'right');
    rfp_pdf_line($content, $boxX + 12, $summaryTop - 48, 536, $summaryTop - 48, array(205, 213, 221), 0.7);
    rfp_pdf_text($content, $boxX + 12, $summaryTop - 68, 'TOTAL PELUNASAN', 9, true, $brand);
    rfp_pdf_text($content, 536, $summaryTop - 68, rfp_format_rupiah($financials['total_due']), 10.5, true, $brand, 'right');

    rfp_pdf_line($content, 46, 92, 548, 92, array(220, 224, 229), 0.7);
    rfp_pdf_text($content, 46, 72, 'Terima kasih telah mempercayakan momen Anda kepada RF Studio.', 8, true, $dark);
    rfp_pdf_text($content, 46, 56, 'Invoice dibuat otomatis oleh sistem RF Studio.', 7.5, false, $muted);
    rfp_pdf_text($content, 548, 56, 'Booking ID: ' . $submission['booking_uniqid'], 7.5, false, $muted, 'right');

    $logo = rfp_pdf_png_rgb(__DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo2.png');
    if ($logo) {
        $content .= "q 62 0 0 56 46 770 cm /Im1 Do Q\n";
    }
    $resources = '<< /Font << /F1 4 0 R /F2 5 0 R >>';
    if ($logo) {
        $resources .= ' /XObject << /Im1 7 0 R >>';
    }
    $resources .= ' >>';
    $objects = array();
    $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
    $objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
    $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources ' . $resources . ' /Contents 6 0 R >>';
    $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
    $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>';
    $objects[] = '<< /Length ' . strlen($content) . ">>\nstream\n" . $content . 'endstream';
    if ($logo) {
        $objects[] = '<< /Type /XObject /Subtype /Image /Width ' . $logo['width'] . ' /Height ' . $logo['height'] . ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /FlateDecode /Length ' . strlen($logo['data']) . ">>\nstream\n" . $logo['data'] . "\nendstream";
    }

    $pdf = "%PDF-1.4\n";
    $offsets = array(0);
    foreach ($objects as $index => $object) {
        $offsets[] = strlen($pdf);
        $pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
    }
    $xref = strlen($pdf);
    $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
    for ($index = 1; $index <= count($objects); $index++) {
        $pdf .= sprintf('%010d 00000 n ', $offsets[$index]) . "\n";
    }
    $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";
    return $pdf;
}
