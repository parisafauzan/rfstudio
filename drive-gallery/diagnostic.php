<?php
/**
 * Diagnostik lokal sementara untuk RF Studio Google Drive Gallery.
 * HAPUS file ini setelah diagnosis selesai.
 */
require_once __DIR__ . '/bootstrap.php';

if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
}

$privateDir = defined('RFG_PRIVATE_DIR') ? RFG_PRIVATE_DIR : '';
$cfg = isset($GLOBALS['RFG_CONFIG']) && is_array($GLOBALS['RFG_CONFIG']) ? $GLOBALS['RFG_CONFIG'] : array();
$expectedFiles = array(
    'config.php' => $privateDir !== '' && is_file($privateDir . '/config.php'),
    'drive-token.json (format yang dipakai paket resmi)' => $privateDir !== '' && is_file($privateDir . '/drive-token.json'),
    'drive-folder-id.txt (format yang dipakai paket resmi)' => $privateDir !== '' && is_file($privateDir . '/drive-folder-id.txt'),
    'drive-token.php (nama alternatif; jangan dibaca/dibagikan)' => $privateDir !== '' && is_file($privateDir . '/drive-token.php'),
    'drive-folder-id.php (nama alternatif)' => $privateDir !== '' && is_file($privateDir . '/drive-folder-id.php'),
    'drive-gallery-upload.log' => $privateDir !== '' && is_file($privateDir . '/drive-gallery-upload.log'),
);

$logWritable = false;
$logMessage = 'Belum dicoba';
if (function_exists('rfg_log')) {
    rfg_log('DIAGNOSTIC_PAGE_OPEN');
    $logPath = $privateDir . '/drive-gallery-upload.log';
    $logWritable = is_file($logPath) && is_writable($logPath);
    $logMessage = $logWritable ? 'rfg_log berhasil membuat/menulis log' : 'rfg_log belum menghasilkan log; periksa path/permission';
}

$writeTest = false;
$writeTestMessage = 'Tidak dapat diuji';
if ($privateDir !== '' && is_dir($privateDir) && is_writable($privateDir)) {
    $testPath = $privateDir . '/.rfg-write-test-' . bin2hex(random_bytes(4)) . '.tmp';
    $writeTest = @file_put_contents($testPath, 'ok', LOCK_EX) !== false;
    if ($writeTest) {
        @unlink($testPath);
        $writeTestMessage = 'Berhasil membuat dan menghapus file uji';
    } else {
        $writeTestMessage = 'Folder terlihat writable tetapi gagal menulis file uji';
    }
}

$configChecks = array();
foreach (array('client_id', 'client_secret', 'redirect_uri', 'folder_name') as $key) {
    $value = isset($cfg[$key]) ? trim((string) $cfg[$key]) : '';
    $isSet = ($value !== '' && strpos($value, 'PASTE_') !== 0 && stripos($value, 'YOUR-DOMAIN') === false && stripos($value, 'example.com') === false);
    $configChecks[$key] = $isSet;
}

$ini = array(
    'file_uploads' => ini_get('file_uploads'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'max_input_time' => ini_get('max_input_time'),
    'max_execution_time' => ini_get('max_execution_time'),
    'upload_tmp_dir' => ini_get('upload_tmp_dir') !== '' ? ini_get('upload_tmp_dir') : '(default system temp)',
);

function rfg_diag_yesno($yes) {
    return $yes ? '<span style="color:#087443;font-weight:700">YA</span>' : '<span style="color:#b42318;font-weight:700">TIDAK</span>';
}
function rfg_diag_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?><!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Diagnostik Galeri RF Studio</title>
<style>body{font-family:Arial,sans-serif;background:#f3f6fb;color:#14213d;margin:0;padding:24px}.wrap{max-width:900px;margin:auto}.card{background:#fff;border:1px solid #dbe3ef;border-radius:12px;padding:20px;margin:16px 0;overflow-wrap:anywhere}h1{margin-top:0}table{width:100%;border-collapse:collapse}td,th{text-align:left;padding:9px;border-bottom:1px solid #e7edf5;vertical-align:top}code{background:#f1f5f9;padding:2px 5px;border-radius:4px}.btn{display:inline-block;background:#2159d6;color:#fff;padding:11px 15px;border:0;border-radius:8px;cursor:pointer;text-decoration:none;font-weight:700}.note{color:#64748b;font-size:14px}.warn{background:#fff7ed;border-color:#fed7aa}</style></head><body><div class="wrap">
<h1>Diagnostik Galeri Foto RF Studio</h1>
<p>Halaman sementara ini tidak menampilkan Client Secret maupun isi token. Hapus <code>diagnostic.php</code> setelah selesai.</p>
<div class="card"><h2>Lingkungan PHP</h2><table>
<tr><th>Item</th><th>Nilai</th></tr>
<tr><td>Versi PHP</td><td><?php echo rfg_diag_escape(PHP_VERSION); ?></td></tr>
<tr><td>File yang sedang dibuka</td><td><?php echo rfg_diag_escape(__FILE__); ?></td></tr>
<tr><td>Private directory</td><td><?php echo rfg_diag_escape($privateDir); ?></td></tr>
<tr><td>Private directory tersedia</td><td><?php echo rfg_diag_yesno($privateDir !== '' && is_dir($privateDir)); ?></td></tr>
<tr><td>Private directory writable</td><td><?php echo rfg_diag_yesno($privateDir !== '' && is_writable($privateDir)); ?></td></tr>
<tr><td>File upload aktif</td><td><?php echo rfg_diag_yesno(strtolower((string) $ini['file_uploads']) === '1' || strtolower((string) $ini['file_uploads']) === 'on'); ?></td></tr>
<tr><td>cURL aktif</td><td><?php echo rfg_diag_yesno(extension_loaded('curl')); ?></td></tr>
<tr><td>fileinfo aktif</td><td><?php echo rfg_diag_yesno(class_exists('finfo')); ?></td></tr>
<tr><td>ZIP aktif</td><td><?php echo rfg_diag_yesno(class_exists('ZipArchive')); ?></td></tr>
<tr><td>Google Drive terbaca terhubung</td><td><?php echo rfg_diag_yesno(function_exists('rfg_is_connected') && rfg_is_connected()); ?></td></tr>
<tr><td>Uji tulis privat</td><td><?php echo rfg_diag_escape($writeTestMessage); ?></td></tr>
<tr><td>Uji log</td><td><?php echo rfg_diag_escape($logMessage); ?></td></tr>
</table></div>
<div class="card"><h2>Konfigurasi (hanya status, tanpa isi rahasia)</h2><table><tr><th>Kunci</th><th>Terisi dan tampak valid</th></tr>
<?php foreach ($configChecks as $key => $valid): ?><tr><td><?php echo rfg_diag_escape($key); ?></td><td><?php echo rfg_diag_yesno($valid); ?></td></tr><?php endforeach; ?>
</table></div>
<div class="card"><h2>File di folder privat</h2><p class="note">Paket yang saya periksa memakai drive-token.json dan drive-folder-id.txt. Jika yang tersedia hanya nama berakhiran .php, jangan mengganti nama atau mengirim isi file—kemungkinan kode yang terpasang berbeda versi.</p><table><tr><th>File</th><th>Ada</th></tr>
<?php foreach ($expectedFiles as $name => $exists): ?><tr><td><?php echo rfg_diag_escape($name); ?></td><td><?php echo rfg_diag_yesno($exists); ?></td></tr><?php endforeach; ?>
</table></div>
<div class="card"><h2>Limit PHP untuk upload</h2><table><tr><th>Pengaturan</th><th>Nilai</th></tr>
<?php foreach ($ini as $key => $value): ?><tr><td><?php echo rfg_diag_escape($key); ?></td><td><?php echo rfg_diag_escape($value); ?></td></tr><?php endforeach; ?>
<tr><td>CONTENT_LENGTH pada halaman diagnostik</td><td><?php echo isset($_SERVER['CONTENT_LENGTH']) ? rfg_diag_escape($_SERVER['CONTENT_LENGTH']) : 'tidak tersedia'; ?></td></tr>
</table></div>
<div class="card warn"><h2>Uji apakah upload.php benar-benar dijalankan</h2><p>Tombol ini mengirim POST kecil tanpa foto ke upload.php. Karena sengaja tidak mengirim foto, pesan yang diharapkan adalah bahwa PHP tidak menerima data foto. Ini tidak mengunggah atau menghapus file Google Drive.</p>
<form action="upload" method="post"><?php echo rfg_csrf_field(); ?><button class="btn" type="submit">Uji jalur upload.php (tanpa foto)</button></form>
</div>
<div class="card"><a class="btn" href="index">Kembali ke galeri</a></div>
</div></body></html>
