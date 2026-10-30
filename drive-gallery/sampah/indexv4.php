<?php
require_once __DIR__ . '/bootstrap.php';

$flash = isset($_SESSION['rfgallery_flash']) ? $_SESSION['rfgallery_flash'] : null;
unset($_SESSION['rfgallery_flash']);
if (isset($_GET['from']) && $_GET['from'] === 'kota') {
    $_SESSION['rfgallery_return_url'] = '../Admin-Kartini/datakonfirmasidp.php';
} elseif (isset($_GET['from']) && $_GET['from'] === 'kabupaten') {
    $_SESSION['rfgallery_return_url'] = '../Admin/datakonfirmasidp.php';
}
$returnUrl = isset($_SESSION['rfgallery_return_url']) ? $_SESSION['rfgallery_return_url'] : (!empty($_SESSION['adminkartini']) ? '../Admin-Kartini/datakonfirmasidp.php' : '../Admin/datakonfirmasidp.php');
$configReady = rfg_config_ready();
$connected = $configReady && rfg_is_connected();
$dbStatus = rfg_gallery_db_status();
$usage = $connected ? rfg_usage_summary() : array('minute_units' => 0, 'minute_calls' => 0, 'today_units' => 0, 'today_egress_bytes' => 0, 'today_upload_bytes' => 0, 'today_uploads' => 0, 'monitoring_since' => null);
$shareStates = $connected ? rfg_share_states_read() : array();
$folders = array();
$photos = array();
$nextPageToken = '';
$pageToken = isset($_GET['pageToken']) ? (string) $_GET['pageToken'] : '';
$nameFilter = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
if (strlen($nameFilter) > 120) $nameFilter = substr($nameFilter, 0, 120);
$currentFolderId = isset($_GET['folderId']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_GET['folderId']) : '';
$rootFolderId = '';
$folderPath = array();
$currentFolderName = '';
$listError = '';
$cacheNotice = '';
$isRootFolder = true;
$isPhotoFolder = false;
$cacheReadyForCurrentFolder = false;
$cacheUsed = false;
$cacheRefreshedNow = false;
$cacheCategory = '';
$cacheSession = null;
$cacheTtlSeconds = 300;
if ($connected) {
    try {
        $rootFolderId = rfg_folder_id();
        if ($currentFolderId === '') $currentFolderId = $rootFolderId;
        $folderPath = rfg_folder_path($currentFolderId);
        $currentFolderName = $folderPath[count($folderPath) - 1]['name'];
        $isRootFolder = ($currentFolderId === $rootFolderId);
        $lowerCurrentName = strtolower((string) $currentFolderName);
        $isPhotoFolder = count($folderPath) >= 6 && in_array($lowerCurrentName, array('originals', 'edited'), true);
        if ($isPhotoFolder && $dbStatus['ready']) {
            try {
                $customerFolderId = $folderPath[count($folderPath) - 2]['id'];
                $cacheSession = rfg_gallery_session_find_by_folder($customerFolderId);
                if ($cacheSession) {
                    $cacheReadyForCurrentFolder = true;
                    $cacheCategory = $lowerCurrentName === 'originals' ? 'original' : 'edited';
                    $forceCacheRefresh = isset($_GET['refresh']) && (string) $_GET['refresh'] === '1';
                    $cacheFresh = !$forceCacheRefresh && rfg_gallery_session_cache_is_fresh((int) $cacheSession['id'], $cacheCategory, $cacheTtlSeconds);
                    if (!$cacheFresh) {
                        $drivePhotos = rfg_list_folder_images_all($currentFolderId);
                        rfg_gallery_cache_sync_photos((int) $cacheSession['id'], $currentFolderId, $cacheCategory, $drivePhotos);
                        $cacheRefreshedNow = true;
                    }
                    $cacheOffset = 0;
                    if (strpos($pageToken, 'cache:') === 0) $cacheOffset = max(0, (int) substr($pageToken, 6));
                    $result = rfg_gallery_cached_photo_list((int) $cacheSession['id'], $currentFolderId, $cacheCategory, $nameFilter, $cacheOffset, 50);
                    $photos = isset($result['files']) && is_array($result['files']) ? $result['files'] : array();
                    $nextPageToken = isset($result['nextPageToken']) ? $result['nextPageToken'] : '';
                    $cacheUsed = true;
                } else {
                    $cacheNotice = 'Folder foto ini belum terhubung ke gallery_sessions. Gunakan form booking lama untuk mendaftarkan booking yang sesuai, lalu buka ulang folder ini.';
                }
            } catch (Throwable $cacheError) {
                $cacheNotice = 'Cache MySQL tidak dapat dipakai untuk folder ini; daftar di bawah sementara dibaca langsung dari Google Drive. Detail: ' . $cacheError->getMessage();
                rfg_log('GALLERY_CACHE_READ_FAILED folder=' . $currentFolderId . ' message=' . $cacheError->getMessage());
            }
        }
        if (!$cacheUsed) {
            $drivePageToken = strpos($pageToken, 'cache:') === 0 ? '' : $pageToken;
            $result = rfg_list_folder_items($currentFolderId, $drivePageToken, $nameFilter, false);
            $rawItems = isset($result['files']) && is_array($result['files']) ? $result['files'] : array();
            foreach ($rawItems as $item) {
                $mime = isset($item['mimeType']) ? $item['mimeType'] : '';
                if ($mime === 'application/vnd.google-apps.folder') {
                    $folders[] = $item;
                } elseif (in_array($mime, array('image/jpeg', 'image/png', 'image/webp'), true)) {
                    $photos[] = $item;
                }
            }
            $nextPageToken = isset($result['nextPageToken']) ? $result['nextPageToken'] : '';
        }
        usort($folders, function ($a, $b) { return strnatcasecmp(isset($a['name']) ? $a['name'] : '', isset($b['name']) ? $b['name'] : ''); });
    } catch (Throwable $e) {
        $listError = $e->getMessage();
    }
    $usage = rfg_usage_summary();
}
function rfg_gallery_url($folderId, $q = '', $pageToken = '')
{
    $params = array();
    if ($folderId !== '') $params['folderId'] = $folderId;
    if ($q !== '') $params['q'] = $q;
    if ($pageToken !== '') $params['pageToken'] = $pageToken;
    return 'index' . (!empty($params) ? '?' . http_build_query($params) : '');
}
?><!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Galeri Google Drive | RF Studio</title>
<link rel="stylesheet" href="gallery.css">
</head>
<body>
<header class="topbar">
  <div class="brand"><div class="brand-mark">RF</div><div><strong>Rizal Foto Studio</strong><span>Google Drive Gallery</span></div></div>
  <div class="top-actions"><a class="button button-light" href="<?php echo rfg_h($returnUrl); ?>">← Kembali ke Admin</a></div>
</header>
<main class="container">
  <section class="intro">
    <div><p class="eyebrow">MEDIA MANAGEMENT</p><h1>Galeri Foto</h1><p class="lead">Jelajahi struktur folder langsung dari Google Drive. MySQL hanya menyimpan relasi booking-folder dan cache metadata foto untuk mengurangi request API; tidak digunakan untuk menyalin hierarki folder.</p></div>
    <div class="status-pill <?php echo $connected ? 'is-connected' : 'is-disconnected'; ?>"><span class="status-dot"></span><?php echo $connected ? 'Google Drive terhubung' : 'Google Drive belum terhubung'; ?></div>
  </section>
  <?php if ($flash): ?><div class="alert alert-<?php echo rfg_h(isset($flash['type']) ? $flash['type'] : 'info'); ?>"><?php echo rfg_h(isset($flash['text']) ? $flash['text'] : ''); ?></div><?php endif; ?>
  <?php if (isset($_GET['connected'])): ?><div class="alert alert-success">Google Drive berhasil dihubungkan.</div><?php endif; ?>

  <?php if (!$configReady): ?>
    <section class="panel setup-panel"><div class="setup-icon">⚙</div><div><h2>Konfigurasi awal diperlukan</h2><p>Modul belum menemukan konfigurasi OAuth privat atau ekstensi cURL tidak aktif.</p><ol><li>Aktifkan Google Drive API dan buat OAuth Client ID tipe Web application.</li><li>Tambahkan redirect URI sesuai domain.</li><li>Isi config.php di direktori privat di luar public_html.</li><li>Pastikan ekstensi curl aktif.</li></ol><p class="small-note">Jangan pernah mengirim Client Secret atau token Google ke chat.</p></div></section>
  <?php elseif (!$connected): ?>
    <section class="panel connect-panel"><div class="connect-symbol">☁</div><div class="connect-copy"><h2>Hubungkan akun Google One</h2><p>Gunakan akun Google pemilik penyimpanan galeri. Aplikasi akan membuat folder galeri utama dan mengelola folder turunan yang dibuat melalui modul.</p><p class="small-note">Scope saat ini: <code>drive.file</code>. Folder dan foto untuk galeri sebaiknya dibuat melalui aplikasi supaya tetap dapat diakses dengan scope ini.</p></div><a class="button button-primary" href="oauth">Hubungkan Google Drive</a></section>
  <?php else: ?>
    <?php
      $minuteProjectPct = min(100, ($usage['minute_units'] / 1000000) * 100);
      $minuteUserPct = min(100, ($usage['minute_units'] / 325000) * 100);
      $dailyEgressPct = min(100, ($usage['today_egress_bytes'] / 1000000000000) * 100);
      $dailyUnitsPct = min(100, ($usage['today_units'] / 400000000) * 100);
      $fmtBytes = function ($bytes) {
          $bytes = (float) $bytes;
          if ($bytes >= 1099511627776) return number_format($bytes / 1099511627776, 2, ',', '.') . ' TB';
          if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2, ',', '.') . ' GB';
          if ($bytes >= 1048576) return number_format($bytes / 1048576, 2, ',', '.') . ' MB';
          if ($bytes >= 1024) return number_format($bytes / 1024, 1, ',', '.') . ' KB';
          return number_format($bytes, 0, ',', '.') . ' byte';
      };
    ?>
    <section class="panel usage-panel">
      <div class="section-heading"><div><h2>Usage Google Drive API</h2><p>Estimasi aktivitas yang tercatat oleh modul ini.</p></div><a class="button button-light" href="https://console.cloud.google.com/apis/api/drive.googleapis.com/quotas" target="_blank" rel="noopener">Lihat kuota Google Cloud ↗</a></div>
      <div class="usage-grid">
        <article class="usage-card"><span>Kuota API · menit berjalan</span><strong><?php echo number_format($usage['minute_units'], 0, ',', '.'); ?> unit</strong><div class="usage-meter"><span style="width:<?php echo number_format($minuteProjectPct, 3, '.', ''); ?>%"></span></div><small><?php echo number_format($minuteProjectPct, 3, ',', '.'); ?>% dari 1.000.000 unit/proyek/menit</small></article>
        <article class="usage-card"><span>Batas pengguna · menit berjalan</span><strong><?php echo number_format($usage['minute_units'], 0, ',', '.'); ?> unit</strong><div class="usage-meter"><span style="width:<?php echo number_format($minuteUserPct, 3, '.', ''); ?>%"></span></div><small><?php echo number_format($minuteUserPct, 3, ',', '.'); ?>% dari 325.000 unit/pengguna/menit</small></article>
        <article class="usage-card"><span>Data keluar via galeri · hari ini</span><strong><?php echo rfg_h($fmtBytes($usage['today_egress_bytes'])); ?></strong><div class="usage-meter"><span style="width:<?php echo number_format($dailyEgressPct, 4, '.', ''); ?>%"></span></div><small><?php echo number_format($dailyEgressPct, 4, ',', '.'); ?>% dari ambang 1 TB/24 jam</small></article>
        <article class="usage-card"><span>Unit API tercatat · hari ini</span><strong><?php echo number_format($usage['today_units'], 0, ',', '.'); ?> unit</strong><div class="usage-meter"><span style="width:<?php echo number_format($dailyUnitsPct, 4, '.', ''); ?>%"></span></div><small><?php echo number_format($dailyUnitsPct, 4, ',', '.'); ?>% dari ambang harian 400 juta unit</small></article>
        <article class="usage-card"><span>Foto diupload lewat modul · hari ini</span><strong><?php echo number_format($usage['today_uploads'], 0, ',', '.'); ?> foto</strong><small>Total data upload berhasil: <?php echo rfg_h($fmtBytes($usage['today_upload_bytes'])); ?></small></article>
        <article class="usage-card"><span>Operasi API yang tercatat · hari ini</span><strong><?php echo number_format($usage['today_calls'], 0, ',', '.'); ?> operasi</strong><small>Menit berjalan: <?php echo number_format($usage['minute_calls'], 0, ',', '.'); ?> operasi</small></article>
      </div>
      <div class="usage-notice"><strong>Angka ini estimasi, bukan penghitung resmi Google.</strong> Statistik hanya menghitung request yang melewati modul ini. <a href="https://developers.google.com/workspace/drive/api/guides/limits" target="_blank" rel="noopener">Dokumentasi kuota Google ↗</a></div>
    </section>

    <section class="gallery-section">
      <div class="section-heading"><div><h2>File Manager Google Drive</h2><p>Folder dibaca langsung dari Google Drive. Isi foto di Originals/Edited memakai cache metadata MySQL selama 5 menit dan dapat disegarkan kapan saja.</p></div><span class="count-pill"><?php echo count($folders); ?> folder · <?php echo count($photos); ?> foto</span></div>
      <div class="alert <?php echo !empty($dbStatus['ready']) ? 'alert-success' : 'alert-info'; ?>"><strong><?php echo !empty($dbStatus['ready']) ? 'Cache MySQL aktif.' : 'Cache MySQL belum aktif.'; ?></strong> <?php echo rfg_h($dbStatus['message']); ?></div>
      <?php if ($cacheNotice !== ''): ?><div class="alert alert-info"><?php echo rfg_h($cacheNotice); ?></div><?php endif; ?>
      <?php if ($cacheUsed): ?><div class="cache-toolbar"><span class="small-note"><?php echo $cacheRefreshedNow ? 'Metadata foto baru saja disinkronkan dari Google Drive.' : 'Metadata foto disajikan dari cache MySQL.'; ?> <?php echo $cacheTtlSeconds / 60; ?> menit masa cache.</span><a class="button button-light" href="<?php echo rfg_h('index?' . http_build_query(array_filter(array('folderId' => $currentFolderId, 'q' => $nameFilter, 'refresh' => '1'), function ($v) { return $v !== ''; }))); ?>">Refresh dari Google Drive</a></div><?php endif; ?>
      <?php if ($listError !== ''): ?><div class="alert alert-danger">Tidak dapat membaca Google Drive: <?php echo rfg_h($listError); ?></div><?php endif; ?>
      <?php if (!empty($folderPath)): ?>
        <nav class="breadcrumbs" aria-label="Breadcrumb">
          <?php foreach ($folderPath as $i => $crumb): ?>
            <?php if ($i > 0): ?><span class="crumb-separator">›</span><?php endif; ?>
            <?php if ($i < count($folderPath) - 1): ?><a href="<?php echo rfg_h(rfg_gallery_url($crumb['id'])); ?>"><?php echo rfg_h($crumb['name']); ?></a><?php else: ?><strong><?php echo rfg_h($crumb['name']); ?></strong><?php endif; ?>
          <?php endforeach; ?>
        </nav>
      <?php endif; ?>
      <form class="folder-search" method="get" action="index">
        <input type="hidden" name="folderId" value="<?php echo rfg_h($currentFolderId); ?>">
        <label for="gallery-search">Cari nama folder atau foto dalam folder ini</label>
        <div class="search-row"><input type="search" id="gallery-search" name="q" value="<?php echo rfg_h($nameFilter); ?>" placeholder="Contoh: Oktober, Paris, IMG_1023.jpg"><button class="button button-primary" type="submit">Cari</button><?php if ($nameFilter !== ''): ?><a class="button button-light" href="<?php echo rfg_h(rfg_gallery_url($currentFolderId)); ?>">Reset</a><?php endif; ?></div>
      </form>

      <?php if ($isRootFolder): ?>
        <details class="panel create-old-folder">
          <summary>Buat folder galeri untuk booking lama</summary>
          <p class="small-note">Gunakan ini untuk booking yang dikonfirmasi sebelum integrasi otomatis diaktifkan. Folder dibuat di Google Drive; tidak ada data hierarki yang ditulis ke MySQL.</p>
          <form action="create-booking-folder" method="post" class="manual-folder-form">
            <?php echo rfg_csrf_field(); ?>
            <label>Nama pelanggan<input name="customer_name" type="text" maxlength="100" required placeholder="Nama pelanggan"></label>
            <label>ID booking / uniqid<input name="booking_uniqid" type="text" maxlength="20" required placeholder="Contoh: a1b2c3"></label>
            <label>Studio<select name="studio" required><option value="Bekasi-Kota">Bekasi-Kota</option><option value="Bekasi-Kabupaten">Bekasi-Kabupaten</option></select></label>
            <label>Tanggal sesi<input name="booking_date" type="date" required></label>
            <button class="button button-primary" type="submit">Buat folder pelanggan</button>
          </form>
        </details>
      <?php endif; ?>

      <?php if (!empty($folders)): ?>
        <div class="subsection-heading"><h3>Folder</h3><span><?php echo count($folders); ?> folder pada halaman ini</span></div>
        <div class="folder-grid">
          <?php foreach ($folders as $folder): ?>
            <a class="folder-card" href="<?php echo rfg_h(rfg_gallery_url($folder['id'])); ?>"><span class="folder-icon">📁</span><span class="folder-name"><?php echo rfg_h($folder['name']); ?></span><span class="folder-hint">Buka folder <span aria-hidden="true">→</span></span></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <section class="upload-panel panel upload-panel-nested">
        <div class="section-heading"><div><h3>Upload ke folder saat ini</h3><p>Tujuan: <strong><?php echo rfg_h($currentFolderName); ?></strong>. Untuk booking baru, buka subfolder <strong>Originals</strong> atau <strong>Edited</strong> sebelum upload.</p></div><span class="tag">Google Drive</span></div>
        <form action="upload" method="post" enctype="multipart/form-data" class="upload-form" id="upload-form">
          <?php echo rfg_csrf_field(); ?><input type="hidden" name="folder_id" value="<?php echo rfg_h($currentFolderId); ?>">
          <label class="dropzone" for="photos"><span class="drop-icon">＋</span><strong>Pilih foto dari komputer</strong><span id="selected-files">Belum ada foto dipilih</span><input id="photos" type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple required></label>
          <div class="upload-actions"><span class="small-note">Batas awal: 10 foto, maksimal 20 MB per foto dan 100 MB per batch.</span><button class="button button-primary" type="submit" id="upload-button">Upload ke folder ini</button></div>
        </form>
      </section>

      <?php if (!empty($photos)): ?>
        <div class="subsection-heading"><h3>Foto</h3><span><?php echo count($photos); ?> foto pada halaman ini</span></div>
        <form action="download-selected" method="post" id="selection-form">
          <?php echo rfg_csrf_field(); ?><input type="hidden" name="folder_id" value="<?php echo rfg_h($currentFolderId); ?>">
          <div class="selection-toolbar"><label class="select-all"><input type="checkbox" id="select-all"> Pilih semua foto di halaman ini</label><span id="selected-count">0 foto dipilih</span><button class="button button-dark" type="submit" id="download-selected-button" disabled>Download pilihan (.ZIP)</button></div>
        </form>
        <div class="photo-grid">
          <?php foreach ($photos as $file): ?>
            <?php $shareState = isset($shareStates[$file['id']]) && !empty($shareStates[$file['id']]['enabled']); $shareUrl = $shareState && !empty($shareStates[$file['id']]['url']) ? $shareStates[$file['id']]['url'] : rfg_share_link($file['id']); $parentQuery = 'folderId=' . rawurlencode($currentFolderId); ?>
            <article class="photo-card">
              <label class="photo-select" aria-label="Pilih foto <?php echo rfg_h($file['name']); ?>"><input type="checkbox" form="selection-form" class="photo-checkbox" name="selected_ids[]" value="<?php echo rfg_h($file['id']); ?>"><span>Pilih</span></label>
              <a class="photo-preview" href="download?id=<?php echo rawurlencode($file['id']); ?>&amp;folderId=<?php echo rawurlencode($currentFolderId); ?>" title="Download foto ini"><img loading="lazy" src="thumbnail?id=<?php echo rawurlencode($file['id']); ?>&amp;folderId=<?php echo rawurlencode($currentFolderId); ?>" alt="Preview <?php echo rfg_h($file['name']); ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='grid';"><span class="preview-fallback" style="display:none">Preview tidak tersedia</span></a>
              <div class="photo-details"><strong title="<?php echo rfg_h($file['name']); ?>"><?php echo rfg_h($file['name']); ?></strong><div class="photo-meta"><span><?php echo isset($file['size']) ? number_format(((int) $file['size']) / 1048576, 2, ',', '.') . ' MB' : 'Ukuran tidak diketahui'; ?></span><span><?php echo !empty($file['createdTime']) ? rfg_h(date('d M Y', strtotime($file['createdTime']))) : ''; ?></span></div><a class="download-link" href="download?id=<?php echo rawurlencode($file['id']); ?>&amp;folderId=<?php echo rawurlencode($currentFolderId); ?>">Download foto ↗</a>
                <div class="share-controls">
                  <?php if ($shareState): ?>
                    <span class="share-status is-shared">Link aktif — siapa pun yang punya link dapat melihat foto</span>
                    <button type="button" class="button button-primary copy-share-link" data-link="<?php echo rfg_h($shareUrl); ?>">Salin link untuk WhatsApp</button>
                    <a class="share-open-link" href="<?php echo rfg_h($shareUrl); ?>" target="_blank" rel="noopener">Buka link ↗</a>
                    <form action="share" method="post" onsubmit="return confirm('Matikan akses publik untuk foto ini?');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="file_id" value="<?php echo rfg_h($file['id']); ?>"><input type="hidden" name="folder_id" value="<?php echo rfg_h($currentFolderId); ?>"><input type="hidden" name="share_action" value="disable"><button type="submit" class="button button-light">Matikan link publik</button></form>
                  <?php else: ?>
                    <span class="share-status is-private">Privat — belum dibagikan lewat link</span>
                    <form action="share" method="post" onsubmit="return confirm('Siapa pun yang menerima tautan dapat melihat foto ini, termasuk jika tautan diteruskan. Lanjutkan?');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="file_id" value="<?php echo rfg_h($file['id']); ?>"><input type="hidden" name="folder_id" value="<?php echo rfg_h($currentFolderId); ?>"><input type="hidden" name="share_action" value="enable"><button type="submit" class="button button-light">Aktifkan link WhatsApp</button></form>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php elseif (empty($folders) && $listError === ''): ?>
        <div class="empty-state"><div class="empty-symbol">▧</div><h3>Folder ini belum berisi foto atau subfolder</h3><p>Upload ke folder saat ini atau kembali ke folder induk melalui breadcrumb.</p></div>
      <?php elseif (empty($photos) && $listError === ''): ?>
        <div class="empty-state compact-empty"><h3>Belum ada foto langsung di folder ini</h3><p>Folder ini berisi subfolder. Buka salah satunya untuk melihat foto.</p></div>
      <?php endif; ?>
      <?php if ($pageToken !== ''): ?><a class="button button-light pager" href="<?php echo rfg_h(rfg_gallery_url($currentFolderId, $nameFilter)); ?>">← Halaman pertama</a><?php endif; ?>
      <?php if ($nextPageToken !== ''): ?><a class="button button-light pager" href="<?php echo rfg_h(rfg_gallery_url($currentFolderId, $nameFilter, $nextPageToken)); ?>">Item berikutnya →</a><?php endif; ?>
    </section>
    <section class="bottom-tools"><div><strong>Folder galeri</strong><p>Hierarki folder tetap bersumber dari Google Drive. MySQL hanya menyimpan relasi booking-folder dan cache metadata foto.</p></div><form action="disconnect" method="post" onsubmit="return confirm('Hapus token Google Drive yang tersimpan di server?')"><?php echo rfg_csrf_field(); ?><button class="button button-light" type="submit">Putuskan koneksi</button></form></section>
  <?php endif; ?>
  <footer>RF Studio · Galeri Google Drive · Akses admin</footer>
</main>
<script>
(function () {
  var fileInput = document.getElementById('photos');
  var selectedFiles = document.getElementById('selected-files');
  var uploadForm = document.getElementById('upload-form');
  var uploadButton = document.getElementById('upload-button');
  if (fileInput && selectedFiles) fileInput.addEventListener('change', function () {
    var files = Array.prototype.slice.call(fileInput.files || []);
    var tooMany = files.length > 10;
    var totalBytes = files.reduce(function (sum, file) { return sum + file.size; }, 0);
    var tooLarge = totalBytes > 100 * 1024 * 1024;
    selectedFiles.textContent = files.length ? files.length + ' foto dipilih · ' + (totalBytes / (1024 * 1024)).toFixed(1) + ' MB' : 'Belum ada foto dipilih';
    if (tooMany) selectedFiles.textContent += ' — maksimal 10 foto';
    if (tooLarge) selectedFiles.textContent += ' — total batch maksimal 100 MB';
    if (uploadButton) uploadButton.disabled = tooMany || tooLarge || files.length === 0;
  });
  if (uploadForm) uploadForm.addEventListener('submit', function () { if (uploadButton) { uploadButton.disabled = true; uploadButton.textContent = 'Sedang upload...'; } });
  var selectAll = document.getElementById('select-all');
  var checkboxes = Array.prototype.slice.call(document.querySelectorAll('.photo-checkbox'));
  var countLabel = document.getElementById('selected-count');
  var downloadButton = document.getElementById('download-selected-button');
  function updateSelection() {
    var count = checkboxes.filter(function (box) { return box.checked; }).length;
    if (countLabel) countLabel.textContent = count + ' foto dipilih';
    if (downloadButton) { downloadButton.disabled = count < 1 || count > 10; downloadButton.textContent = count > 10 ? 'Maksimal 10 foto per ZIP' : 'Download pilihan (.ZIP)'; }
    if (selectAll) selectAll.checked = checkboxes.length > 0 && count === checkboxes.length;
  }
  if (selectAll) selectAll.addEventListener('change', function () { checkboxes.forEach(function (box) { box.checked = selectAll.checked; }); updateSelection(); });
  checkboxes.forEach(function (box) { box.addEventListener('change', updateSelection); });
  var selectionForm = document.getElementById('selection-form');
  if (selectionForm) selectionForm.addEventListener('submit', function (event) { var count = checkboxes.filter(function (box) { return box.checked; }).length; if (count < 1 || count > 10) { event.preventDefault(); alert('Pilih 1 sampai 10 foto untuk diunduh sebagai ZIP.'); } });
  var copyButtons = Array.prototype.slice.call(document.querySelectorAll('.copy-share-link'));
  copyButtons.forEach(function (button) { button.addEventListener('click', function () { var link = button.getAttribute('data-link') || ''; if (!link) return; function copied() { var oldText = button.textContent; button.textContent = 'Link tersalin ✓'; setTimeout(function () { button.textContent = oldText; }, 1800); } if (navigator.clipboard && navigator.clipboard.writeText) navigator.clipboard.writeText(link).then(copied).catch(function () { window.prompt('Salin link berikut:', link); }); else window.prompt('Salin link berikut:', link); }); });
  updateSelection();
})();
</script>
</body>
</html>
