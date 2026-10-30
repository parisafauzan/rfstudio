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

// Lazy tree endpoint: reads only direct child folders when an admin expands a tree node.
// It does not recursively enumerate the Drive and therefore keeps API use proportional to interaction.
if (isset($_GET['ajax']) && (string) $_GET['ajax'] === 'tree_children') {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: private, max-age=30');
    try {
        if (!$connected) throw new RuntimeException('Google Drive belum terhubung.');
        $treeParentId = isset($_GET['folderId']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $_GET['folderId']) : '';
        if ($treeParentId === '') $treeParentId = rfg_folder_id();
        rfg_assert_folder_in_gallery($treeParentId);
        $treeQuery = "'" . rfg_drive_query_escape($treeParentId) . "' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false";
        $treeResult = rfg_api_request('GET', 'files', array(
            'q' => $treeQuery,
            'spaces' => 'drive',
            'pageSize' => 1000,
            'orderBy' => 'name',
            'fields' => 'nextPageToken,files(id,name,mimeType,parents)',
        ));
        $treeFolders = isset($treeResult['files']) && is_array($treeResult['files']) ? $treeResult['files'] : array();
        echo json_encode(array(
            'success' => true,
            'parentId' => $treeParentId,
            'folders' => $treeFolders,
            'nextPageToken' => isset($treeResult['nextPageToken']) ? $treeResult['nextPageToken'] : ''
        ));
    } catch (Throwable $treeError) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'error' => $treeError->getMessage()));
    }
    exit;
}

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
$treeBootstrap = array(
    'rootId' => $rootFolderId,
    'currentFolderId' => $currentFolderId,
    'currentFolderName' => $currentFolderName,
    'path' => $folderPath,
    'folders' => array_map(function ($folder) {
        return array('id' => isset($folder['id']) ? $folder['id'] : '', 'name' => isset($folder['name']) ? $folder['name'] : '');
    }, $folders),
    'foldersPageComplete' => ($nextPageToken === ''),
);
function rfg_gallery_url($folderId, $q = '', $pageToken = '')
{
    $params = array();
    if ($folderId !== '') $params['folderId'] = $folderId;
    if ($q !== '') $params['q'] = $q;
    if ($pageToken !== '') $params['pageToken'] = $pageToken;
    return 'index.php' . (!empty($params) ? '?' . http_build_query($params) : '');
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
    <section class="panel connect-panel"><div class="connect-symbol">☁</div><div class="connect-copy"><h2>Hubungkan akun Google One</h2><p>Gunakan akun Google pemilik penyimpanan galeri. Aplikasi akan membuat folder galeri utama dan mengelola folder turunan yang dibuat melalui modul.</p><p class="small-note">Scope saat ini: <code>drive.file</code>. Folder dan foto untuk galeri sebaiknya dibuat melalui aplikasi supaya tetap dapat diakses dengan scope ini.</p></div><a class="button button-primary" href="oauth.php">Hubungkan Google Drive</a></section>
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
    <div class="drive-layout">
      <aside class="folder-tree-panel panel" aria-label="Pohon folder Google Drive">
        <div class="tree-panel-heading"><div><span class="tree-panel-kicker">EXPLORER</span><h2>Folder galeri</h2></div><button type="button" id="tree-refresh" class="icon-button" title="Muat ulang pohon folder" aria-label="Muat ulang pohon folder">↻</button></div>
        <div id="drive-tree" class="drive-tree"><div class="tree-loading"><span class="mini-spinner"></span> Menyiapkan folder...</div></div>
        <p class="tree-footnote">Folder dimuat bertahap saat dibuka. Galeri tidak memuat seluruh Google Drive sekaligus.</p>
      </aside>
      <script type="application/json" id="tree-bootstrap-data"><?php echo json_encode($treeBootstrap, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
      <div class="drive-main" id="drive-main">
    <section class="gallery-section">
      <div class="section-heading"><div><h2>File Manager Google Drive</h2><p>Folder dibaca langsung dari Google Drive. Isi foto di Originals/Edited memakai cache metadata MySQL selama 5 menit dan dapat disegarkan kapan saja.</p></div><span class="count-pill"><?php echo count($folders); ?> folder · <?php echo count($photos); ?> foto</span></div>
      <div class="alert <?php echo !empty($dbStatus['ready']) ? 'alert-success' : 'alert-info'; ?>"><strong><?php echo !empty($dbStatus['ready']) ? 'Cache MySQL aktif.' : 'Cache MySQL belum aktif.'; ?></strong> <?php echo rfg_h($dbStatus['message']); ?></div>
      <?php if ($cacheNotice !== ''): ?><div class="alert alert-info"><?php echo rfg_h($cacheNotice); ?></div><?php endif; ?>
      <?php if ($cacheUsed): ?><div class="cache-toolbar"><span class="small-note"><strong><?php echo $cacheRefreshedNow ? 'Cache disinkronkan.' : 'Cache MySQL aktif.'; ?></strong> <?php echo $cacheRefreshedNow ? 'Metadata diperbarui dari Drive.' : 'Metadata foto dibaca dari cache.'; ?> Masa cache <?php echo $cacheTtlSeconds / 60; ?> menit.</span></div><?php endif; ?>
      <?php if ($listError !== ''): ?><div class="alert alert-danger">Tidak dapat membaca Google Drive: <?php echo rfg_h($listError); ?></div><?php endif; ?>
      <?php if (!empty($folderPath)): ?>
        <nav class="breadcrumbs" aria-label="Breadcrumb">
          <?php foreach ($folderPath as $i => $crumb): ?>
            <?php if ($i > 0): ?><span class="crumb-separator">›</span><?php endif; ?>
            <?php if ($i < count($folderPath) - 1): ?><a href="<?php echo rfg_h(rfg_gallery_url($crumb['id'])); ?>"><?php echo rfg_h($crumb['name']); ?></a><?php else: ?><strong><?php echo rfg_h($crumb['name']); ?></strong><?php endif; ?>
          <?php endforeach; ?>
        </nav>
      <?php endif; ?>
      <details class="panel create-old-folder" id="create-old-booking-folder" open>
          <summary><span class="create-booking-icon" aria-hidden="true">▦</span><span><strong>Buat folder galeri untuk booking lama</strong><small>Buat struktur pelanggan dan daftarkan relasinya ke gallery_sessions.</small></span><span class="create-folder-chevron" aria-hidden="true">⌄</span></summary>
          <p class="small-note">Gunakan untuk booking lama yang belum memiliki folder galeri. Form ini boleh dibuka dari folder mana pun, tetapi struktur pelanggan selalu dibuat di root galeri: Studio → Tahun → Bulan → Pelanggan → Originals/Edited. Relasi booking disimpan ke <code>gallery_sessions</code>; <code>gallery_photos</code> akan terisi saat foto diunggah atau disinkronkan.</p>
          <form action="create-booking-folder.php" method="post" class="manual-folder-form" id="booking-folder-form">
            <?php echo rfg_csrf_field(); ?>
            <label>Nama pelanggan<input name="customer_name" type="text" maxlength="100" required placeholder="Contoh: Yuni Fahira"></label>
            <label>ID booking / uniqid<input name="booking_uniqid" type="text" maxlength="64" required placeholder="Contoh: e22017"></label>
            <label>Studio<select name="studio" required><option value="Bekasi-Kota">Bekasi-Kota</option><option value="Bekasi-Kabupaten">Bekasi-Kabupaten</option></select></label>
            <label>Tanggal reservasi<input name="booking_date" type="date" required></label>
            <div class="manual-folder-submit-row"><button class="button button-primary" type="submit" id="booking-folder-submit">Buat folder booking</button><span id="booking-folder-status" class="small-note" role="status" aria-live="polite">Folder akan dibuat di Google Drive dan mapping booking disimpan di gallery_sessions.</span></div>
          </form>
      </details>
      <details class="create-folder-dropdown" id="create-folder-dropdown">
        <summary><span class="create-folder-summary-icon" aria-hidden="true">＋</span><span><strong>Buat folder</strong><small>Tambah subfolder di: <span id="create-folder-parent-name"><?php echo rfg_h($currentFolderName); ?></span></small></span><span class="create-folder-chevron" aria-hidden="true">⌄</span></summary>
        <form action="create-folder" method="post" id="create-folder-form" class="create-folder-form">
          <?php echo rfg_csrf_field(); ?><input type="hidden" name="folder_id" id="create-folder-parent-id" value="<?php echo rfg_h($currentFolderId); ?>">
          <label for="create-folder-name">Nama folder baru</label><div class="create-folder-input-row"><input id="create-folder-name" name="folder_name" type="text" maxlength="100" required placeholder="Contoh: Retouch tambahan"><button type="submit" class="button button-primary" id="create-folder-submit">Buat folder</button></div>
          <p class="small-note" id="create-folder-status" role="status" aria-live="polite">Folder akan dibuat langsung di Google Drive.</p>
        </form>
      </details>
      <div class="file-toolbar" role="region" aria-label="Filter dan tampilan file">
        <label class="quick-search" for="gallery-search"><span aria-hidden="true">⌕</span><input type="search" id="gallery-search" value="<?php echo rfg_h($nameFilter); ?>" placeholder="Cari folder atau foto..."><button type="button" id="clear-gallery-search" title="Hapus pencarian" aria-label="Hapus pencarian">×</button></label>
        <label class="toolbar-select-label"><span>Filter</span><select id="filter-kind"><option value="all">Semua item</option><option value="folders">Folder saja</option><option value="photos">Foto saja</option></select></label>
        <label class="toolbar-select-label"><span>Urutkan</span><select id="sort-items"><option value="name-asc">Nama (A–Z)</option><option value="name-desc">Nama (Z–A)</option><option value="newest">Terbaru</option><option value="oldest">Terlama</option><option value="largest">Ukuran terbesar</option><option value="smallest">Ukuran terkecil</option></select></label>
        <div class="view-switch" role="group" aria-label="Mode tampilan"><button type="button" class="view-mode is-active" data-view-mode="grid" title="Tampilan kartu" aria-label="Tampilan kartu">▦</button><button type="button" class="view-mode" data-view-mode="list" title="Tampilan daftar" aria-label="Tampilan daftar">☷</button></div>
        <button type="button" class="button button-light refresh-folder-button" id="refresh-folder" title="Ambil data folder terbaru dari Google Drive">↻ Refresh</button>
      </div>
      <div class="browser-loading-line" id="browser-loading-line" hidden><span class="mini-spinner"></span> Membuka folder...</div>

      <?php if (!empty($folders)): ?>
        <section class="content-group" id="folder-group" data-kind-group="folders">
          <div class="subsection-heading"><h3>Folder</h3><span><?php echo count($folders); ?> folder pada halaman ini</span></div>
          <div class="folder-grid" id="folder-grid">
            <?php foreach ($folders as $folder): ?>
              <a class="folder-card" data-kind="folders" data-name="<?php echo rfg_h($folder['name']); ?>" data-size="0" data-created="" href="<?php echo rfg_h(rfg_gallery_url($folder['id'])); ?>"><span class="folder-icon" aria-hidden="true"><svg class="folder-icon-svg" viewBox="0 0 24 24"><path d="M3 6.75A1.75 1.75 0 0 1 4.75 5h4.1c.55 0 1.07.26 1.4.7l1 1.3h8A1.75 1.75 0 0 1 21 8.75v9.5A1.75 1.75 0 0 1 19.25 20H4.75A1.75 1.75 0 0 1 3 18.25z"/></svg></span><span class="folder-name"><?php echo rfg_h($folder['name']); ?></span><span class="folder-hint">Buka folder <span aria-hidden="true">→</span></span></a>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

      <?php if (!empty($photos)): ?>
        <section class="content-group" id="photo-group" data-kind-group="photos">
        <div class="subsection-heading"><h3>Foto</h3><span><?php echo count($photos); ?> foto pada halaman ini</span></div>
        <form action="download-selected.php" method="post" id="selection-form">
          <?php echo rfg_csrf_field(); ?><input type="hidden" name="folder_id" value="<?php echo rfg_h($currentFolderId); ?>">
          <div class="selection-toolbar"><label class="select-all"><input type="checkbox" id="select-all"> Pilih semua foto di halaman ini</label><span id="selected-count">0 foto dipilih</span><button class="button button-dark" type="submit" id="download-selected-button" disabled>Download pilihan (.ZIP)</button></div>
        </form>
        <div class="photo-grid">
          <?php foreach ($photos as $file): ?>
            <?php $shareState = isset($shareStates[$file['id']]) && !empty($shareStates[$file['id']]['enabled']); $shareUrl = $shareState && !empty($shareStates[$file['id']]['url']) ? $shareStates[$file['id']]['url'] : rfg_share_link($file['id']); $parentQuery = 'folderId=' . rawurlencode($currentFolderId); ?>
            <article class="photo-card" data-kind="photos" data-name="<?php echo rfg_h($file['name']); ?>" data-size="<?php echo isset($file['size']) ? (int) $file['size'] : 0; ?>" data-created="<?php echo rfg_h(isset($file['createdTime']) ? $file['createdTime'] : ''); ?>">
              <label class="photo-select" aria-label="Pilih foto <?php echo rfg_h($file['name']); ?>"><input type="checkbox" form="selection-form" class="photo-checkbox" name="selected_ids[]" value="<?php echo rfg_h($file['id']); ?>"><span>Pilih</span></label>
              <div class="photo-card-media">
                <button type="button" class="photo-preview" data-photo-preview data-preview-src="thumbnail.php?id=<?php echo rawurlencode($file['id']); ?>&amp;folderId=<?php echo rawurlencode($currentFolderId); ?>" data-preview-title="<?php echo rfg_h($file['name']); ?>" aria-label="Preview <?php echo rfg_h($file['name']); ?>" title="Lihat preview foto">
                  <img loading="lazy" src="thumbnail.php?id=<?php echo rawurlencode($file['id']); ?>&amp;folderId=<?php echo rawurlencode($currentFolderId); ?>" alt="Preview <?php echo rfg_h($file['name']); ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='grid';">
                  <span class="preview-fallback" style="display:none">Preview tidak tersedia</span>
                </button>
                <a class="photo-download-button" href="download.php?id=<?php echo rawurlencode($file['id']); ?>&amp;folderId=<?php echo rawurlencode($currentFolderId); ?>" title="Download foto" aria-label="Download <?php echo rfg_h($file['name']); ?>">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v11m0 0 4-4m-4 4-4-4M4 17v3h16v-3"/></svg>
                </a>
              </div>
              <div class="photo-details"><strong title="<?php echo rfg_h($file['name']); ?>"><?php echo rfg_h($file['name']); ?></strong><div class="photo-meta"><span><?php echo isset($file['size']) ? number_format(((int) $file['size']) / 1048576, 2, ',', '.') . ' MB' : 'Ukuran tidak diketahui'; ?></span><span><?php echo !empty($file['createdTime']) ? rfg_h(date('d M Y', strtotime($file['createdTime']))) : ''; ?></span></div>
                <div class="share-controls">
                  <?php if ($shareState): ?>
                    <span class="share-status is-shared">Link aktif — siapa pun yang punya link dapat melihat foto</span>
                    <button type="button" class="button button-primary copy-share-link" data-link="<?php echo rfg_h($shareUrl); ?>">Salin link untuk WhatsApp</button>
                    <a class="share-open-link" href="<?php echo rfg_h($shareUrl); ?>" target="_blank" rel="noopener">Buka link ↗</a>
                    <form action="share.php" method="post" onsubmit="return confirm('Matikan akses publik untuk foto ini?');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="file_id" value="<?php echo rfg_h($file['id']); ?>"><input type="hidden" name="folder_id" value="<?php echo rfg_h($currentFolderId); ?>"><input type="hidden" name="share_action" value="disable"><button type="submit" class="button button-light">Matikan link publik</button></form>
                  <?php else: ?>
                    <span class="share-status is-private">Privat — belum dibagikan lewat link</span>
                    <form action="share.php" method="post" onsubmit="return confirm('Siapa pun yang menerima tautan dapat melihat foto ini, termasuk jika tautan diteruskan. Lanjutkan?');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="file_id" value="<?php echo rfg_h($file['id']); ?>"><input type="hidden" name="folder_id" value="<?php echo rfg_h($currentFolderId); ?>"><input type="hidden" name="share_action" value="enable"><button type="submit" class="button button-light">Aktifkan link WhatsApp</button></form>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
        </section>
      <?php elseif (empty($folders) && $listError === ''): ?>
        <div class="empty-state"><div class="empty-symbol">▧</div><h3>Folder ini belum berisi foto atau subfolder</h3><p>Upload ke folder saat ini atau kembali ke folder induk melalui breadcrumb.</p></div>
      <?php elseif (empty($photos) && $listError === ''): ?>
        <div class="empty-state compact-empty"><h3>Belum ada foto langsung di folder ini</h3><p>Folder ini berisi subfolder. Buka salah satunya untuk melihat foto.</p></div>
      <?php endif; ?>
      <div id="client-no-results" class="empty-state compact-empty" hidden><h3>Tidak ada item yang cocok</h3><p>Ubah kata pencarian atau filter yang dipilih.</p></div>
      <?php if ($pageToken !== ''): ?><a class="button button-light pager" href="<?php echo rfg_h(rfg_gallery_url($currentFolderId, $nameFilter)); ?>">← Halaman pertama</a><?php endif; ?>
      <?php if ($nextPageToken !== ''): ?><a class="button button-light pager" href="<?php echo rfg_h(rfg_gallery_url($currentFolderId, $nameFilter, $nextPageToken)); ?>">Item berikutnya →</a><?php endif; ?>
    </section>
      </div><!-- /.drive-main -->
      <aside class="right-rail" aria-label="Usage dan upload">
        <section class="usage-compact panel">
          <div class="usage-compact-heading"><div><span class="tree-panel-kicker">MONITORING</span><h2>Usage API</h2></div><a href="https://console.cloud.google.com/apis/api/drive.googleapis.com/quotas" target="_blank" rel="noopener" title="Lihat kuota Google Cloud">↗</a></div>
          <div class="usage-metric-row"><div class="usage-ring" style="--ring-pct:<?php echo number_format($minuteProjectPct, 3, '.', ''); ?>%"><span><?php echo number_format($minuteProjectPct, 1, ',', '.'); ?>%</span></div><div class="usage-metric-copy"><strong>Kuota proyek/menit</strong><span><?php echo number_format($usage['minute_units'], 0, ',', '.'); ?> / 1.000.000 unit</span></div></div>
          <div class="usage-metric-row"><div class="usage-ring" style="--ring-pct:<?php echo number_format($minuteUserPct, 3, '.', ''); ?>%"><span><?php echo number_format($minuteUserPct, 1, ',', '.'); ?>%</span></div><div class="usage-metric-copy"><strong>Batas pengguna/menit</strong><span><?php echo number_format($usage['minute_units'], 0, ',', '.'); ?> / 325.000 unit</span></div></div>
          <div class="usage-metric-row"><div class="usage-ring" style="--ring-pct:<?php echo number_format($dailyEgressPct, 4, '.', ''); ?>%"><span><?php echo number_format($dailyEgressPct, 1, ',', '.'); ?>%</span></div><div class="usage-metric-copy"><strong>Data keluar hari ini</strong><span><?php echo rfg_h($fmtBytes($usage['today_egress_bytes'])); ?> / 1 TB</span></div></div>
          <div class="usage-metric-row"><div class="usage-ring" style="--ring-pct:<?php echo number_format($dailyUnitsPct, 4, '.', ''); ?>%"><span><?php echo number_format($dailyUnitsPct, 1, ',', '.'); ?>%</span></div><div class="usage-metric-copy"><strong>Unit API hari ini</strong><span><?php echo number_format($usage['today_units'], 0, ',', '.'); ?> unit</span></div></div>
          <div class="usage-mini-stats"><div><span>Upload hari ini</span><strong><?php echo number_format($usage['today_uploads'], 0, ',', '.'); ?> foto</strong><small><?php echo rfg_h($fmtBytes($usage['today_upload_bytes'])); ?></small></div><div><span>Request tercatat</span><strong><?php echo number_format($usage['today_calls'], 0, ',', '.'); ?></strong><small><?php echo number_format($usage['minute_calls'], 0, ',', '.'); ?> pada menit ini</small></div></div>
          <p class="usage-disclaimer">Estimasi lokal modul ini, bukan penghitung resmi Google Cloud.</p>
        </section>
        <details class="upload-dropdown panel" id="upload-dropdown">
          <summary><span class="upload-summary-icon">↑</span><span class="upload-summary-copy"><strong>Upload foto</strong><small>Antrean foto · satu per satu</small></span><span class="upload-summary-chevron">⌄</span></summary>
          <div class="upload-dropdown-content">
            <p class="small-note">Pilih hingga 200 foto atau seret file ke area di bawah. Setiap foto dikirim satu per satu; foto yang sudah sukses langsung tersimpan meskipun foto berikutnya gagal.</p>
            <form action="upload" method="post" enctype="multipart/form-data" class="upload-form" id="upload-form" novalidate>
              <?php echo rfg_csrf_field(); ?><input type="hidden" name="folder_id" id="upload-folder-id" value="<?php echo rfg_h($currentFolderId); ?>">
              <label class="dropzone" id="upload-dropzone" for="photos"><span class="drop-icon">＋</span><strong>Drop foto di sini</strong><span id="selected-files">Klik untuk pilih foto atau drag &amp; drop</span><input id="photos" class="visually-hidden-input" type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple></label>
              <div id="upload-previews" class="upload-previews" hidden></div>
              <div id="upload-progress-wrapper" class="upload-progress-wrapper" hidden><div class="upload-progress-head"><strong id="upload-progress-title">Menyiapkan upload...</strong><span id="upload-progress-percent">0%</span></div><div class="upload-progress-track"><span id="upload-progress-fill"></span></div><p id="upload-status" class="small-note" role="status" aria-live="polite">Jangan tutup halaman selama upload berlangsung.</p></div>
              <div class="upload-actions">
                <span class="small-note">Maks. 200 foto dalam antrean · 20 MB/foto. Setiap foto dikirim satu per satu.</span>
                <div class="upload-action-buttons"><button class="button button-light" type="button" id="upload-pause-button" disabled>Jeda antrean</button><button class="button button-light" type="button" id="upload-clear-button" disabled>Bersihkan daftar</button></div>
                <button class="button button-primary" type="submit" id="upload-button" disabled>Upload foto</button>
              </div>
            </form>
          </div>
        </details>
      </aside>
    </div><!-- /.drive-layout -->
    <section class="bottom-tools"><div><strong>Folder galeri</strong><p>Hierarki folder tetap bersumber dari Google Drive. MySQL hanya menyimpan relasi booking-folder dan cache metadata foto.</p></div><form action="disconnect.php" method="post" onsubmit="return confirm('Hapus token Google Drive yang tersimpan di server?')"><?php echo rfg_csrf_field(); ?><button class="button button-light" type="submit">Putuskan koneksi</button></form></section>
  <?php endif; ?>
  <footer>RF Studio · Galeri Google Drive · Akses admin</footer>
</main>
<dialog class="photo-preview-dialog" id="photo-preview-dialog" aria-label="Preview foto">
  <div class="photo-preview-dialog-inner">
    <div class="photo-preview-dialog-toolbar"><strong id="photo-preview-dialog-title">Preview foto</strong><button type="button" id="photo-preview-dialog-close" class="photo-preview-dialog-close" aria-label="Tutup preview">×</button></div>
    <div class="photo-preview-dialog-stage"><img id="photo-preview-dialog-image" alt="Preview foto"></div>
  </div>
</dialog>
<script>
(function () {
  'use strict';
  var activeSeed = null;
  var treeNodes = Object.create(null);
  var treeChildren = Object.create(null);
  var loadedTreeParents = Object.create(null);
  var expandedTreeIds = Object.create(null);
  var pageCache = Object.create(null);
  var PAGE_CACHE_TTL = 45000;
  var PAGE_CACHE_MAX = 10;
  var cachedPageOrder = [];
  var currentView = 'grid';
  var navigationBusy = false;
  var navigationSequence = 0;
  var activeNavigationController = null;
  var driveTree = document.getElementById('drive-tree');
  var initialTreeEl = document.getElementById('tree-bootstrap-data');

  function safeJson(text) { try { return JSON.parse(text || '{}'); } catch (e) { return {}; } }
  function parseTreeSeed(doc) {
    var element = doc && doc.getElementById('tree-bootstrap-data');
    return element ? safeJson(element.textContent) : null;
  }
  function addTreeNode(node) {
    if (!node || !node.id) return;
    var id = String(node.id);
    var parentId = node.parentId ? String(node.parentId) : '';
    if (treeNodes[id]) {
      treeNodes[id].name = node.name || treeNodes[id].name;
      var oldParentId = treeNodes[id].parentId || '';
      if (parentId && oldParentId && oldParentId !== parentId && treeChildren[oldParentId]) {
        treeChildren[oldParentId] = treeChildren[oldParentId].filter(function (childId) { return childId !== id; });
      }
      if (parentId) treeNodes[id].parentId = parentId;
    } else {
      treeNodes[id] = { id: id, name: node.name || '(Tanpa nama)', parentId: parentId };
    }
    if (parentId) {
      if (!treeChildren[parentId]) treeChildren[parentId] = [];
      if (treeChildren[parentId].indexOf(id) === -1) treeChildren[parentId].push(id);
    }
    if (!treeChildren[id]) treeChildren[id] = [];
  }
  function mergeTreeSeed(seed) {
    if (!seed || !seed.rootId) return;
    activeSeed = seed;
    var path = Array.isArray(seed.path) ? seed.path : [];
    for (var i = 0; i < path.length; i++) {
      addTreeNode({ id: path[i].id, name: path[i].name, parentId: i ? path[i - 1].id : '' });
      expandedTreeIds[String(path[i].id)] = true;
      if (i < path.length - 1) {
        // The current path node is known even if siblings have not been loaded yet.
        var nextId = String(path[i + 1].id);
        if (!treeChildren[String(path[i].id)]) treeChildren[String(path[i].id)] = [];
        if (treeChildren[String(path[i].id)].indexOf(nextId) === -1) treeChildren[String(path[i].id)].push(nextId);
      }
    }
    var currentId = String(seed.currentFolderId || seed.rootId);
    var children = Array.isArray(seed.folders) ? seed.folders : [];
    children.forEach(function (folder) {
      addTreeNode({ id: folder.id, name: folder.name, parentId: currentId });
    });
    loadedTreeParents[currentId] = seed.foldersPageComplete !== false;
    if (!treeChildren[String(seed.rootId)]) treeChildren[String(seed.rootId)] = [];
    renderTree();
    updateUploadTarget(seed.currentFolderId, seed.currentFolderName);
  }
  function escapeHtml(value) {
    return String(value == null ? '' : value).replace(/[&<>"']/g, function (ch) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[ch];
    });
  }
  function treeFolderIconMarkup(isOpen) {
    var openClass = isOpen ? ' is-open' : '';
    return '<svg class="tree-folder-icon' + openClass + '" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6.75A1.75 1.75 0 0 1 4.75 5h4.1c.55 0 1.07.26 1.4.7l1 1.3h8A1.75 1.75 0 0 1 21 8.75v9.5A1.75 1.75 0 0 1 19.25 20H4.75A1.75 1.75 0 0 1 3 18.25z"/></svg>';
  }
  function renderTreeNode(id, depth) {
    var node = treeNodes[id];
    if (!node) return '';
    var childIds = (treeChildren[id] || []).filter(function (childId) { return !!treeNodes[childId]; });
    var loaded = !!loadedTreeParents[id];
    var expanded = !!expandedTreeIds[id];
    var current = activeSeed && String(activeSeed.currentFolderId) === String(id);
    var hasKnownChildren = childIds.length > 0;
    var hasToggle = !loaded || hasKnownChildren;
    var caret = !hasToggle ? '<span class="tree-toggle-placeholder"></span>' : '<button type="button" class="tree-toggle" data-tree-toggle="' + escapeHtml(id) + '" aria-label="' + (expanded ? 'Tutup' : 'Buka') + ' ' + escapeHtml(node.name) + '">' + (expanded ? '⌄' : '›') + '</button>';
    var html = '<div class="tree-node" data-tree-node="' + escapeHtml(id) + '"><div class="tree-row' + (current ? ' is-current' : '') + '">' + caret + '<a class="tree-link' + (current ? ' is-current' : '') + '" data-folder-id="' + escapeHtml(id) + '" href="index.php?folderId=' + encodeURIComponent(id) + '"><span class="tree-folder-glyph">' + treeFolderIconMarkup(expanded) + '</span><span class="tree-name">' + escapeHtml(node.name) + '</span></a></div>';
    if (expanded && hasKnownChildren) {
      html += '<div class="tree-children">' + childIds.map(function (childId) { return renderTreeNode(childId, depth + 1); }).join('') + '</div>';
    }
    return html + '</div>';
  }
  function renderTree() {
    if (!driveTree || !activeSeed || !activeSeed.rootId) return;
    var rootId = String(activeSeed.rootId);
    if (!treeNodes[rootId]) addTreeNode({ id: rootId, name: (activeSeed.path && activeSeed.path[0] && activeSeed.path[0].name) || 'RF Studio Gallery', parentId: '' });
    driveTree.innerHTML = renderTreeNode(rootId, 0);
  }
  function treeCacheKey(parentId) { return 'rfg-tree:' + location.pathname + ':' + parentId; }
  function fetchTreeChildren(parentId, force) {
    var id = String(parentId);
    var cached = null;
    if (!force) {
      try { cached = safeJson(sessionStorage.getItem(treeCacheKey(id))); } catch (e) {}
      if (cached && cached.savedAt && Date.now() - cached.savedAt < 60000 && Array.isArray(cached.folders)) {
        treeChildren[id] = [];
        cached.folders.forEach(function (folder) { addTreeNode({ id: folder.id, name: folder.name, parentId: id }); });
        loadedTreeParents[id] = true;
        renderTree();
        return Promise.resolve(cached.folders);
      }
    }
    var endpoint = new URL('index.php', location.href);
    endpoint.searchParams.set('ajax', 'tree_children');
    endpoint.searchParams.set('folderId', id);
    return fetch(endpoint.toString(), { credentials: 'same-origin', cache: force ? 'no-store' : 'default', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (response) { return response.json(); })
      .then(function (data) {
        if (!data || !data.success) throw new Error((data && data.error) || 'Folder tidak dapat dibaca.');
        var children = Array.isArray(data.folders) ? data.folders : [];
        // Replace this parent's cached child list with current Drive truth, so manual rename/move/delete is reflected.
        treeChildren[id] = [];
        children.forEach(function (folder) { addTreeNode({ id: folder.id, name: folder.name, parentId: id }); });
        loadedTreeParents[id] = !data.nextPageToken;
        try { sessionStorage.setItem(treeCacheKey(id), JSON.stringify({ savedAt: Date.now(), folders: children })); } catch (e) {}
        renderTree();
        return children;
      });
  }
  function updateUploadTarget(folderId, folderName) {
    var folderInput = document.getElementById('upload-folder-id');
    var target = document.getElementById('upload-target-name');
    var createFolderParentId = document.getElementById('create-folder-parent-id');
    var createFolderParentName = document.getElementById('create-folder-parent-name');
    if (folderInput && folderId) folderInput.value = folderId;
    if (target && folderName) target.textContent = folderName;
    if (createFolderParentId && folderId) createFolderParentId.value = folderId;
    if (createFolderParentName && folderName) createFolderParentName.textContent = folderName;
  }
  function pageKey(url) {
    var u = new URL(url, location.href);
    u.searchParams.delete('refresh');
    u.searchParams.delete('ajax');
    return u.pathname + (u.search ? u.search : '');
  }
  function getCachedPage(key) {
    var found = pageCache[key];
    if (!found || Date.now() - found.savedAt > PAGE_CACHE_TTL) return null;
    return found;
  }
  function saveCachedPage(key, page) {
    page.savedAt = Date.now();
    pageCache[key] = page;
    cachedPageOrder = cachedPageOrder.filter(function (item) { return item !== key; });
    cachedPageOrder.push(key);
    while (cachedPageOrder.length > PAGE_CACHE_MAX) delete pageCache[cachedPageOrder.shift()];
  }
  function capturePage(doc) {
    var main = doc.querySelector('#drive-main');
    if (!main) return null;
    return { mainHtml: main.innerHTML, treeSeed: parseTreeSeed(doc), currentUrl: null };
  }
  function applyPage(page) {
    if (!page || !page.mainHtml) return false;
    var main = document.getElementById('drive-main');
    if (!main) return false;
    main.innerHTML = page.mainHtml;
    if (page.treeSeed) mergeTreeSeed(page.treeSeed);
    initializeMainControls();
    if (page.treeSeed) updateUploadTarget(page.treeSeed.currentFolderId, page.treeSeed.currentFolderName);
    return true;
  }
  function setLoading(value) {
    var loadingElement = document.getElementById('browser-loading-line');
    if (loadingElement) loadingElement.hidden = !value;
    if (driveTree) driveTree.classList.toggle('is-loading', !!value);
  }
  function navigate(href, options) {
    options = options || {};
    var target = new URL(href, location.href);
    if (target.origin !== location.origin) { location.href = href; return Promise.resolve(); }
    var forceRefresh = !!options.forceRefresh;
    var skipCache = !!options.skipCache || forceRefresh;
    if (forceRefresh) target.searchParams.set('refresh', '1');
    var canonical = new URL(target.toString());
    canonical.searchParams.delete('refresh');
    canonical.searchParams.delete('ajax');
    var key = pageKey(canonical.toString());
    var scrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
    if (!skipCache) {
      var cached = getCachedPage(key);
      if (cached) {
        navigationSequence++;
        if (activeNavigationController) activeNavigationController.abort();
        activeNavigationController = null;
        navigationBusy = false;
        setLoading(false);
        applyPage(cached);
        if (options.push !== false) history.pushState({ gallery: true }, '', canonical.toString());
        window.scrollTo(0, scrollY);
        return Promise.resolve();
      }
    }
    if (activeNavigationController) activeNavigationController.abort();
    var requestSequence = ++navigationSequence;
    activeNavigationController = (typeof AbortController !== 'undefined') ? new AbortController() : null;
    navigationBusy = true;
    setLoading(true);
    return fetch(target.toString(), { credentials: 'same-origin', cache: forceRefresh ? 'no-store' : 'default', signal: activeNavigationController ? activeNavigationController.signal : undefined, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (response) {
        if (!response.ok) throw new Error('Server mengembalikan status ' + response.status + '.');
        return response.text();
      })
      .then(function (html) {
        if (requestSequence !== navigationSequence) return;
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var page = capturePage(doc);
        if (!page) throw new Error('Isi galeri tidak ditemukan pada respons server.');
        if (!applyPage(page)) throw new Error('Area galeri tidak dapat diperbarui.');
        saveCachedPage(key, page);
        if (options.push !== false) history.pushState({ gallery: true }, '', canonical.toString());
        else history.replaceState({ gallery: true }, '', canonical.toString());
        window.scrollTo(0, scrollY);
      })
      .catch(function (error) {
        if (error && error.name === 'AbortError') return;
        if (requestSequence !== navigationSequence) return;
        console.warn('Navigasi AJAX galeri gagal, menggunakan navigasi biasa:', error);
        location.href = canonical.toString();
      })
      .finally(function () {
        if (requestSequence === navigationSequence) {
          activeNavigationController = null;
          navigationBusy = false;
          setLoading(false);
        }
      });
  }
  function sortGrid(grid, mode) {
    if (!grid) return;
    var cards = Array.prototype.slice.call(grid.querySelectorAll(':scope > [data-name]'));
    var collator = new Intl.Collator('id', { numeric: true, sensitivity: 'base' });
    cards.sort(function (a, b) {
      var nameA = a.getAttribute('data-name') || '';
      var nameB = b.getAttribute('data-name') || '';
      var sizeA = Number(a.getAttribute('data-size') || 0);
      var sizeB = Number(b.getAttribute('data-size') || 0);
      var dateA = Date.parse(a.getAttribute('data-created') || '') || 0;
      var dateB = Date.parse(b.getAttribute('data-created') || '') || 0;
      if (mode === 'name-desc') return collator.compare(nameB, nameA);
      if (mode === 'newest') return dateB - dateA || collator.compare(nameA, nameB);
      if (mode === 'oldest') return dateA - dateB || collator.compare(nameA, nameB);
      if (mode === 'largest') return sizeB - sizeA || collator.compare(nameA, nameB);
      if (mode === 'smallest') return sizeA - sizeB || collator.compare(nameA, nameB);
      return collator.compare(nameA, nameB);
    });
    cards.forEach(function (card) { grid.appendChild(card); });
  }
  function applyFileControls() {
    var main = document.getElementById('drive-main');
    if (!main) return;
    var searchInput = document.getElementById('gallery-search');
    var needle = searchInput ? searchInput.value.trim().toLocaleLowerCase('id') : '';
    var kindSelect = document.getElementById('filter-kind');
    var kind = kindSelect ? kindSelect.value : 'all';
    var sortSelect = document.getElementById('sort-items');
    var sort = sortSelect ? sortSelect.value : 'name-asc';
    var visibleCount = 0;
    Array.prototype.slice.call(main.querySelectorAll('[data-name][data-kind]')).forEach(function (card) {
      var name = (card.getAttribute('data-name') || '').toLocaleLowerCase('id');
      var ownKind = card.getAttribute('data-kind');
      var matches = (!needle || name.indexOf(needle) !== -1) && (kind === 'all' || kind === ownKind);
      card.hidden = !matches;
      if (matches) visibleCount++;
    });
    var folderGroup = document.getElementById('folder-group');
    var photoGroup = document.getElementById('photo-group');
    if (folderGroup) folderGroup.hidden = kind === 'photos';
    if (photoGroup) photoGroup.hidden = kind === 'folders';
    sortGrid(document.getElementById('folder-grid'), sort);
    sortGrid(document.querySelector('.photo-grid'), sort);
    var noResults = document.getElementById('client-no-results');
    var hasServerEmpty = !!main.querySelector('.empty-state:not(#client-no-results):not([hidden])');
    if (noResults) noResults.hidden = visibleCount > 0 || hasServerEmpty;
    var gallerySection = main.querySelector('.gallery-section');
    if (gallerySection) gallerySection.setAttribute('data-view', currentView);
  }
  function initializeMainControls() {
    var main = document.getElementById('drive-main');
    if (!main) return;
    var viewSaved = null;
    try { viewSaved = sessionStorage.getItem('rfg-gallery-view'); } catch (e) {}
    if (viewSaved === 'list' || viewSaved === 'grid') currentView = viewSaved;
    Array.prototype.slice.call(main.querySelectorAll('.view-mode')).forEach(function (button) {
      button.classList.toggle('is-active', button.getAttribute('data-view-mode') === currentView);
    });
    applyFileControls();
    var selectAll = main.querySelector('#select-all');
    var checkboxes = Array.prototype.slice.call(main.querySelectorAll('.photo-checkbox'));
    var countLabel = main.querySelector('#selected-count');
    var downloadButton = main.querySelector('#download-selected-button');
    function updateSelection() {
      var count = checkboxes.filter(function (box) { return box.checked; }).length;
      if (countLabel) countLabel.textContent = count + ' foto dipilih';
      if (downloadButton) { downloadButton.disabled = count < 1 || count > 10; downloadButton.textContent = count > 10 ? 'Maksimal 10 foto per ZIP' : 'Download pilihan (.ZIP)'; }
      if (selectAll) selectAll.checked = checkboxes.length > 0 && count === checkboxes.length;
    }
    if (selectAll) selectAll.addEventListener('change', function () { checkboxes.forEach(function (box) { box.checked = selectAll.checked; }); updateSelection(); });
    checkboxes.forEach(function (box) { box.addEventListener('change', updateSelection); });
    var selectionForm = main.querySelector('#selection-form');
    if (selectionForm) selectionForm.addEventListener('submit', function (event) {
      var count = checkboxes.filter(function (box) { return box.checked; }).length;
      if (count < 1 || count > 10) { event.preventDefault(); alert('Pilih 1 sampai 10 foto untuk diunduh sebagai ZIP.'); }
    });
  }

  // Preview uses the same authenticated, cached thumbnail endpoint as the card; it never triggers download.
  var photoPreviewDialog = document.getElementById('photo-preview-dialog');
  var photoPreviewImage = document.getElementById('photo-preview-dialog-image');
  var photoPreviewTitle = document.getElementById('photo-preview-dialog-title');
  var photoPreviewClose = document.getElementById('photo-preview-dialog-close');
  function closePhotoPreview() {
    if (!photoPreviewDialog) return;
    if (typeof photoPreviewDialog.close === 'function' && photoPreviewDialog.open) photoPreviewDialog.close();
    if (photoPreviewImage) photoPreviewImage.removeAttribute('src');
  }
  document.addEventListener('click', function (event) {
    var previewButton = event.target.closest('[data-photo-preview]');
    if (!previewButton) return;
    event.preventDefault();
    if (!photoPreviewDialog || !photoPreviewImage) return;
    photoPreviewImage.src = previewButton.getAttribute('data-preview-src') || '';
    if (photoPreviewTitle) photoPreviewTitle.textContent = previewButton.getAttribute('data-preview-title') || 'Preview foto';
    if (typeof photoPreviewDialog.showModal === 'function') photoPreviewDialog.showModal();
    else window.open(photoPreviewImage.src, '_blank', 'noopener');
  });
  if (photoPreviewClose) photoPreviewClose.addEventListener('click', closePhotoPreview);
  if (photoPreviewDialog) {
    photoPreviewDialog.addEventListener('click', function (event) { if (event.target === photoPreviewDialog) closePhotoPreview(); });
    photoPreviewDialog.addEventListener('close', function () { if (photoPreviewImage) photoPreviewImage.removeAttribute('src'); });
  }

  // Delegate navigation and toolbar actions so replaced file lists continue to work without a full reload.
  document.addEventListener('click', function (event) {
    var toggle = event.target.closest('[data-tree-toggle]');
    if (toggle) {
      event.preventDefault();
      var id = toggle.getAttribute('data-tree-toggle');
      if (!id) return;
      if (expandedTreeIds[id] && loadedTreeParents[id]) {
        expandedTreeIds[id] = false;
        renderTree();
      } else {
        expandedTreeIds[id] = true;
        renderTree();
        if (!loadedTreeParents[id]) fetchTreeChildren(id, false).catch(function (error) { alert(error.message || 'Folder tidak dapat dimuat.'); });
      }
      return;
    }
    var navLink = event.target.closest('.folder-card, .breadcrumbs a, .tree-link, .pager');
    if (navLink && navLink.getAttribute('href') && !event.ctrlKey && !event.metaKey && !event.shiftKey && event.button === 0) {
      var href = navLink.getAttribute('href');
      if (href.indexOf('download') !== 0 && href.indexOf('share') !== 0 && !navLink.hasAttribute('target')) {
        event.preventDefault();
        if (navLink.classList.contains('tree-link')) {
          var treeId = navLink.getAttribute('data-folder-id');
          if (treeId) expandedTreeIds[treeId] = true;
        }
        navigate(href, { push: true });
      }
    }
    var viewButton = event.target.closest('[data-view-mode]');
    if (viewButton) {
      currentView = viewButton.getAttribute('data-view-mode') === 'list' ? 'list' : 'grid';
      try { sessionStorage.setItem('rfg-gallery-view', currentView); } catch (e) {}
      var main = document.getElementById('drive-main');
      if (main) {
        main.querySelectorAll('.view-mode').forEach(function (button) { button.classList.toggle('is-active', button === viewButton); });
        applyFileControls();
      }
    }
    if (event.target.closest('#refresh-folder')) {
      event.preventDefault();
      var refreshUrl = new URL(location.href);
      refreshUrl.searchParams.delete('refresh');
      navigate(refreshUrl.toString(), { push: false, forceRefresh: true });
    }
    if (event.target.closest('#tree-refresh')) {
      event.preventDefault();
      try { Object.keys(sessionStorage).forEach(function (key) { if (key.indexOf('rfg-tree:' + location.pathname + ':') === 0) sessionStorage.removeItem(key); }); } catch (e) {}
      if (activeSeed && activeSeed.rootId) {
        var seedToRestore = activeSeed;
        treeNodes = Object.create(null);
        treeChildren = Object.create(null);
        loadedTreeParents = Object.create(null);
        expandedTreeIds = Object.create(null);
        mergeTreeSeed(seedToRestore);
        fetchTreeChildren(String(seedToRestore.rootId), true).catch(function (error) { alert(error.message || 'Pohon folder gagal dimuat.'); });
      }
    }
    var copyButton = event.target.closest('.copy-share-link');
    if (copyButton) {
      var link = copyButton.getAttribute('data-link') || '';
      if (!link) return;
      function copied() { var oldText = copyButton.textContent; copyButton.textContent = 'Link tersalin ✓'; setTimeout(function () { copyButton.textContent = oldText; }, 1800); }
      if (navigator.clipboard && navigator.clipboard.writeText) navigator.clipboard.writeText(link).then(copied).catch(function () { window.prompt('Salin link berikut:', link); });
      else window.prompt('Salin link berikut:', link);
    }
  });
  document.addEventListener('input', function (event) {
    if (event.target && event.target.id === 'gallery-search') applyFileControls();
  });
  document.addEventListener('change', function (event) {
    if (!event.target) return;
    if (event.target.id === 'filter-kind' || event.target.id === 'sort-items') applyFileControls();
  });
  document.addEventListener('click', function (event) {
    if (event.target && event.target.id === 'clear-gallery-search') {
      var search = document.getElementById('gallery-search');
      if (search) { search.value = ''; applyFileControls(); search.focus(); }
    }
  });
  window.addEventListener('popstate', function () { navigate(location.href, { push: false }); });

  // Upload queue: one HTTP request per image. A completed image remains saved even if a later image fails.
  var fileInput = document.getElementById('photos');
  var dropzone = document.getElementById('upload-dropzone');
  var selectedFiles = document.getElementById('selected-files');
  var uploadForm = document.getElementById('upload-form');
  var uploadDropdown = document.getElementById('upload-dropdown');
  var uploadButton = document.getElementById('upload-button');
  var pauseButton = document.getElementById('upload-pause-button');
  var clearButton = document.getElementById('upload-clear-button');
  var previewBox = document.getElementById('upload-previews');
  var progressBox = document.getElementById('upload-progress-wrapper');
  var progressFill = document.getElementById('upload-progress-fill');
  var progressTitle = document.getElementById('upload-progress-title');
  var progressPercent = document.getElementById('upload-progress-percent');
  var uploadStatus = document.getElementById('upload-status');
  var chosenFiles = []; // {file, status, message, driveId, progress, objectUrl}
  var queueCardNodes = [];
  var uploadQueueRunning = false;
  var pauseQueueAfterCurrent = false;
  var MAX_QUEUE_FILES = 200;
  var MAX_FILE_BYTES = 20 * 1024 * 1024;
  var activeQueuePosition = 0;
  var queueInitialCount = 0;
  var runCompletedCount = 0;

  function formatBytes(bytes) {
    if (bytes >= 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return bytes + ' byte';
  }
  function queueCounts() {
    var counts = { queued: 0, uploading: 0, success: 0, failed: 0, unknown: 0 };
    chosenFiles.forEach(function (entry) { if (counts[entry.status] !== undefined) counts[entry.status]++; });
    return counts;
  }
  function queueSummary() {
    var c = queueCounts();
    return 'Tersimpan ' + c.success + ' · gagal ' + c.failed + ' · status tidak diketahui ' + c.unknown + ' · menunggu ' + c.queued;
  }
  function updateQueueControls() {
    var c = queueCounts();
    var totalBytes = chosenFiles.reduce(function (sum, entry) { return sum + entry.file.size; }, 0);
    if (selectedFiles) {
      if (!chosenFiles.length) selectedFiles.textContent = 'Klik untuk pilih foto atau drag & drop';
      else if (c.success || c.failed || c.unknown || c.uploading) selectedFiles.textContent = chosenFiles.length + ' foto · tersimpan ' + c.success + ' · gagal ' + c.failed + ' · menunggu ' + c.queued + ' · ' + formatBytes(totalBytes);
      else selectedFiles.textContent = chosenFiles.length + ' foto dipilih · ' + formatBytes(totalBytes) + ' — maks. ' + MAX_QUEUE_FILES + ' foto';
    }
    var hasQueued = c.queued > 0;
    if (uploadButton) {
      uploadButton.disabled = uploadQueueRunning || !hasQueued;
      if (uploadQueueRunning) uploadButton.textContent = 'Mengupload antrean...';
      else if (hasQueued && (c.success || c.failed || c.unknown)) uploadButton.textContent = 'Lanjutkan antrean';
      else if (hasQueued) uploadButton.textContent = 'Upload foto';
      else uploadButton.textContent = chosenFiles.length ? 'Antrean selesai' : 'Upload foto';
    }
    if (pauseButton) {
      pauseButton.disabled = !uploadQueueRunning;
      pauseButton.textContent = pauseQueueAfterCurrent ? 'Jeda diminta...' : 'Jeda antrean';
    }
    if (clearButton) clearButton.disabled = uploadQueueRunning || chosenFiles.length === 0;
    if (fileInput) fileInput.disabled = uploadQueueRunning;
    if (dropzone) dropzone.setAttribute('aria-disabled', uploadQueueRunning ? 'true' : 'false');
  }
  function updateQueueCard(index) {
    var entry = chosenFiles[index];
    var node = queueCardNodes[index];
    if (!entry || !node) return;
    node.card.setAttribute('data-state', entry.status);
    var labels = {
      queued: 'Menunggu', uploading: 'Mengupload ' + Math.round(entry.progress || 0) + '%',
      success: 'Tersimpan di Google Drive', failed: 'Gagal: ' + (entry.message || 'Periksa pesan server.'),
      unknown: 'Status tidak diketahui: ' + (entry.message || 'Periksa Google Drive sebelum upload ulang.')
    };
    node.status.textContent = labels[entry.status] || 'Menunggu';
    if (node.remove) node.remove.disabled = uploadQueueRunning;
    if (node.miniFill) node.miniFill.style.width = (entry.status === 'success' ? 100 : (entry.status === 'uploading' ? entry.progress : 0)) + '%';
  }
  function updateOverallProgress(currentFraction) {
    var total = queueInitialCount || chosenFiles.length || 1;
    var percent = Math.max(0, Math.min(100, Math.round(((runCompletedCount + (currentFraction || 0)) / total) * 100)));
    if (progressFill) progressFill.style.width = percent + '%';
    if (progressPercent) progressPercent.textContent = percent + '%';
    if (uploadStatus) uploadStatus.textContent = queueSummary();
  }
  function renderUploadPreviews() {
    if (!fileInput || !previewBox || !uploadButton) return;
    queueCardNodes = [];
    previewBox.innerHTML = '';
    previewBox.hidden = chosenFiles.length === 0;
    if (uploadDropdown) uploadDropdown.classList.toggle('has-files', chosenFiles.length > 0);
    chosenFiles.forEach(function (entry, index) {
      var card = document.createElement('div');
      card.className = 'upload-preview-card';
      card.setAttribute('data-state', entry.status);
      var image = document.createElement('img');
      image.alt = '';
      image.loading = 'lazy';
      entry.objectUrl = entry.objectUrl || URL.createObjectURL(entry.file);
      image.src = entry.objectUrl;
      var info = document.createElement('div'); info.className = 'upload-preview-info';
      var name = document.createElement('strong'); name.textContent = entry.file.name;
      var size = document.createElement('small'); size.textContent = formatBytes(entry.file.size);
      var status = document.createElement('small'); status.className = 'upload-file-status';
      info.appendChild(name); info.appendChild(size); info.appendChild(status);
      var miniTrack = document.createElement('span'); miniTrack.className = 'upload-file-progress';
      var miniFill = document.createElement('span'); miniTrack.appendChild(miniFill);
      var remove = document.createElement('button'); remove.type = 'button'; remove.className = 'remove-upload-file'; remove.setAttribute('data-remove-upload-index', String(index)); remove.setAttribute('aria-label', 'Hapus ' + entry.file.name); remove.textContent = '×';
      card.appendChild(image); card.appendChild(info); card.appendChild(remove); card.appendChild(miniTrack); previewBox.appendChild(card);
      queueCardNodes[index] = { card: card, status: status, miniFill: miniFill, remove: remove };
      updateQueueCard(index);
    });
    updateQueueControls();
  }
  function takeFiles(list, append) {
    var incoming = Array.prototype.slice.call(list || []);
    if (!append) {
      chosenFiles.forEach(function (entry) { if (entry.objectUrl) URL.revokeObjectURL(entry.objectUrl); });
      chosenFiles = [];
    }
    var rejectedType = 0, rejectedSize = 0, rejectedLimit = 0;
    incoming.forEach(function (file) {
      var validType = /^image\/(jpeg|png|webp)$/i.test(file.type) || /\.(jpe?g|png|webp)$/i.test(file.name);
      if (!validType) { rejectedType++; return; }
      if (file.size < 1 || file.size > MAX_FILE_BYTES) { rejectedSize++; return; }
      if (chosenFiles.length >= MAX_QUEUE_FILES) { rejectedLimit++; return; }
      var targetInput = document.getElementById('upload-folder-id');
      var targetFolderId = targetInput ? targetInput.value : '';
      var duplicate = chosenFiles.some(function (old) { return old.file.name === file.name && old.file.size === file.size && old.file.lastModified === file.lastModified && old.targetFolderId === targetFolderId; });
      if (!duplicate) chosenFiles.push({ file: file, status: 'queued', message: '', driveId: '', progress: 0, objectUrl: null, targetFolderId: targetFolderId });
    });
    renderUploadPreviews();
    if (uploadStatus && (rejectedType || rejectedSize || rejectedLimit)) {
      var notes = [];
      if (rejectedType) notes.push(rejectedType + ' file bukan JPG/PNG/WebP diabaikan');
      if (rejectedSize) notes.push(rejectedSize + ' file melebihi 20 MB atau kosong diabaikan');
      if (rejectedLimit) notes.push(rejectedLimit + ' file melewati batas antrean 200');
      uploadStatus.textContent = notes.join('. ') + '.';
      if (progressBox) progressBox.hidden = false;
    }
  }
  if (fileInput) fileInput.addEventListener('change', function () { takeFiles(fileInput.files, true); fileInput.value = ''; });
  if (dropzone) {
    ['dragenter', 'dragover'].forEach(function (type) { dropzone.addEventListener(type, function (event) { event.preventDefault(); event.stopPropagation(); dropzone.classList.add('is-dragover'); }); });
    ['dragleave', 'dragend'].forEach(function (type) { dropzone.addEventListener(type, function (event) { event.preventDefault(); dropzone.classList.remove('is-dragover'); }); });
    dropzone.addEventListener('drop', function (event) { event.preventDefault(); event.stopPropagation(); dropzone.classList.remove('is-dragover'); takeFiles(event.dataTransfer && event.dataTransfer.files, true); });
  }
  if (previewBox) previewBox.addEventListener('click', function (event) {
    var remove = event.target.closest('[data-remove-upload-index]');
    if (!remove || uploadQueueRunning) return;
    var index = Number(remove.getAttribute('data-remove-upload-index'));
    if (!chosenFiles[index]) return;
    if (chosenFiles[index].objectUrl) URL.revokeObjectURL(chosenFiles[index].objectUrl);
    chosenFiles.splice(index, 1);
    renderUploadPreviews();
  });
  if (clearButton) clearButton.addEventListener('click', function () {
    if (uploadQueueRunning) return;
    chosenFiles.forEach(function (entry) { if (entry.objectUrl) URL.revokeObjectURL(entry.objectUrl); });
    chosenFiles = [];
    if (fileInput) fileInput.value = '';
    if (progressBox) progressBox.hidden = true;
    if (uploadStatus) uploadStatus.textContent = '';
    renderUploadPreviews();
  });
  if (pauseButton) pauseButton.addEventListener('click', function () {
    if (!uploadQueueRunning) return;
    pauseQueueAfterCurrent = true;
    pauseButton.textContent = 'Jeda setelah file ini...';
    if (uploadStatus) uploadStatus.textContent = 'Permintaan foto yang sedang berjalan akan diselesaikan. Foto berikutnya ditahan.';
  });

  function uploadOneQueueItem(index, done) {
    var entry = chosenFiles[index];
    if (!entry) { done('failed'); return; }
    var csrf = uploadForm.querySelector('[name="csrf_token"]');
    var folder = uploadForm.querySelector('[name="folder_id"]');
    var formData = new FormData();
    if (csrf) formData.append('csrf_token', csrf.value);
    formData.append('folder_id', entry.targetFolderId || (folder ? folder.value : ''));
    formData.append('ajax', '1');
    formData.append('photos[]', entry.file, entry.file.name);
    entry.status = 'uploading'; entry.progress = 0;
    updateQueueCard(index);
    if (progressTitle) progressTitle.textContent = 'Foto ' + (activeQueuePosition + 1) + ' dari ' + queueInitialCount + ': ' + entry.file.name;
    updateOverallProgress(0);
    var xhr = new XMLHttpRequest();
    var settled = false;
    xhr.open('POST', uploadForm.action, true);
    xhr.withCredentials = true;
    xhr.timeout = 300000;
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Accept', 'application/json');
    function finish(state, message, payload) {
      if (settled) return;
      settled = true;
      entry.status = state;
      entry.message = message || '';
      entry.progress = state === 'success' ? 100 : entry.progress;
      if (payload && payload.uploaded_files && payload.uploaded_files[0]) entry.driveId = payload.uploaded_files[0].id || '';
      updateQueueCard(index);
      done(state);
    }
    xhr.upload.addEventListener('progress', function (event) {
      if (!event.lengthComputable) return;
      entry.progress = Math.min(85, Math.round((event.loaded / event.total) * 85));
      updateQueueCard(index);
      updateOverallProgress(entry.progress / 100);
      if (progressTitle) progressTitle.textContent = 'Mengirim foto ' + (activeQueuePosition + 1) + '/' + queueInitialCount + ' ke server...';
    });
    xhr.upload.addEventListener('load', function () {
      entry.progress = 90;
      updateQueueCard(index);
      updateOverallProgress(0.9);
      if (progressTitle) progressTitle.textContent = 'Menyimpan foto ' + (activeQueuePosition + 1) + '/' + queueInitialCount + ' ke Google Drive...';
    });
    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4 || settled) return;
      var result = safeJson(xhr.responseText);
      var hasJson = result && typeof result.success === 'boolean' && typeof result.message === 'string';
      if (xhr.status >= 200 && xhr.status < 300 && hasJson && result.success === true) {
        finish('success', 'Tersimpan di Google Drive.', result);
      } else if (hasJson && result.success === false) {
        finish('failed', result.message, result);
      } else {
        finish('unknown', 'Respons server tidak dapat dipastikan (HTTP ' + xhr.status + '). Periksa Drive sebelum mengunggah ulang.', result);
      }
    };
    xhr.onerror = function () { finish('unknown', 'Koneksi terputus. Periksa Drive sebelum mengunggah ulang untuk menghindari duplikat.'); };
    xhr.ontimeout = function () { finish('unknown', 'Request melewati 5 menit. Status file tidak diketahui; periksa Drive sebelum mencoba ulang.'); };
    xhr.onabort = function () { finish('unknown', 'Request dibatalkan; periksa Drive sebelum mencoba ulang.'); };
    try { xhr.send(formData); } catch (sendError) { finish('unknown', 'Browser gagal memulai request upload: ' + (sendError && sendError.message ? sendError.message : 'kesalahan tidak diketahui') + '. Periksa Drive sebelum mencoba ulang.'); }
  }
  function finishQueue(paused) {
    uploadQueueRunning = false;
    pauseQueueAfterCurrent = false;
    if (dropzone) dropzone.classList.remove('is-uploading');
    updateQueueControls();
    var c = queueCounts();
    if (paused && c.queued > 0) {
      if (progressTitle) progressTitle.textContent = 'Antrean dijeda';
      if (uploadStatus) uploadStatus.textContent = queueSummary() + '. Tekan Lanjutkan antrean untuk meneruskan.';
      if (progressBox) { progressBox.classList.remove('is-error', 'is-success'); }
      return;
    }
    if (c.queued === 0) {
      if (progressFill) progressFill.style.width = '100%';
      if (progressPercent) progressPercent.textContent = '100%';
      if (progressTitle) progressTitle.textContent = 'Antrean selesai';
      if (progressBox) { progressBox.classList.toggle('is-error', c.failed > 0 || c.unknown > 0); progressBox.classList.toggle('is-success', c.failed === 0 && c.unknown === 0); }
      if (uploadStatus) uploadStatus.textContent = 'Selesai. ' + queueSummary() + '. Foto yang sukses sudah tersimpan sendiri-sendiri di Google Drive.';
      if (c.success > 0) {
        var currentFolderInput = document.getElementById('upload-folder-id');
        var currentFolderId = currentFolderInput ? currentFolderInput.value : '';
        var succeededInCurrentFolder = chosenFiles.some(function (entry) { return entry.status === 'success' && entry.targetFolderId === currentFolderId; });
        if (succeededInCurrentFolder) {
          var currentUrl = new URL(location.href);
          currentUrl.searchParams.set('refresh', '1');
          navigate(currentUrl.toString(), { push: false, skipCache: true });
        }
      }
    }
  }
  function runUploadQueue() {
    if (uploadQueueRunning) return;
    var pendingIndices = [];
    chosenFiles.forEach(function (entry, index) { if (entry.status === 'queued') pendingIndices.push(index); });
    if (!pendingIndices.length) return;
    uploadQueueRunning = true;
    pauseQueueAfterCurrent = false;
    queueInitialCount = pendingIndices.length;
    activeQueuePosition = 0;
    runCompletedCount = 0;
    if (uploadDropdown) uploadDropdown.open = true;
    if (dropzone) dropzone.classList.add('is-uploading');
    if (progressBox) { progressBox.hidden = false; progressBox.classList.remove('is-error', 'is-success'); }
    updateQueueControls();
    function next() {
      if (pauseQueueAfterCurrent) { finishQueue(true); return; }
      if (activeQueuePosition >= pendingIndices.length) { finishQueue(false); return; }
      var itemIndex = pendingIndices[activeQueuePosition];
      uploadOneQueueItem(itemIndex, function () {
        runCompletedCount++;
        activeQueuePosition++;
        updateQueueControls();
        window.setTimeout(next, 180); // small gap between Drive uploads; only one file is in flight
      });
    }
    next();
  }
  if (uploadForm) uploadForm.addEventListener('submit', function (event) {
    event.preventDefault();
    if (!chosenFiles.length || uploadQueueRunning) return;
    runUploadQueue();
  });

  // AJAX create-folder form: create one direct child in the currently open Drive folder.
  document.addEventListener('submit', function (event) {
    var form = event.target && event.target.closest ? event.target.closest('#create-folder-form') : null;
    if (!form) return;
    event.preventDefault();
    var input = form.querySelector('#create-folder-name');
    var submit = form.querySelector('#create-folder-submit');
    var status = document.getElementById('create-folder-status');
    var parentInput = form.querySelector('[name=folder_id]');
    if (!input || !input.value.trim()) { if (input) input.focus(); return; }
    var fd = new FormData(form);
    fd.append('ajax', '1');
    if (submit) { submit.disabled = true; submit.textContent = 'Membuat...'; }
    if (status) { status.textContent = 'Membuat folder di Google Drive...'; status.classList.remove('is-error', 'is-success'); }
    fetch(form.action, { method: 'POST', body: fd, credentials: 'same-origin', cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
      .then(function (response) { return response.text().then(function (body) { var payload = safeJson(body); if (!response.ok || !payload || payload.success !== true) { var message = (payload && payload.message) || ('Pembuatan folder gagal (HTTP ' + response.status + ').'); if (response.redirected && response.url) message += ' Request dialihkan ke ' + response.url + '; periksa routing URL lokal.'; throw new Error(message); } return payload; }); })
      .then(function (payload) {
        if (status) { status.textContent = payload.message || 'Folder berhasil dibuat.'; status.classList.add('is-success'); }
        input.value = '';
        var parentId = parentInput ? parentInput.value : (activeSeed ? activeSeed.currentFolderId : '');
        if (parentId) fetchTreeChildren(String(parentId), true).catch(function (error) { console.warn('Pohon folder belum dapat diperbarui:', error); });
        var currentUrl = new URL(location.href); currentUrl.searchParams.delete('refresh');
        navigate(currentUrl.toString(), { push: false, forceRefresh: true });
      })
      .catch(function (error) {
        if (status) { status.textContent = error.message || 'Folder gagal dibuat.'; status.classList.add('is-error'); }
      })
      .finally(function () { if (submit) { submit.disabled = false; submit.textContent = 'Buat folder'; } });
  });

  // Register an old booking by creating its Drive hierarchy and gallery_sessions mapping.
  document.addEventListener('submit', function (event) {
    var form = event.target && event.target.closest ? event.target.closest('#booking-folder-form') : null;
    if (!form) return;
    event.preventDefault();
    var submit = form.querySelector('#booking-folder-submit');
    var status = document.getElementById('booking-folder-status');
    var fd = new FormData(form);
    fd.append('ajax', '1');
    if (submit) { submit.disabled = true; submit.textContent = 'Menyiapkan folder...'; }
    if (status) { status.textContent = 'Membuat struktur folder di Google Drive dan menyimpan mapping ke MySQL...'; status.classList.remove('is-error', 'is-success'); }
    fetch(form.action, { method: 'POST', body: fd, credentials: 'same-origin', cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
      .then(function (response) { return response.text().then(function (body) { var payload = safeJson(body); if (!response.ok || !payload || payload.success !== true) { throw new Error((payload && payload.message) || ('Pembuatan folder booking gagal (HTTP ' + response.status + ').')); } return payload; }); })
      .then(function (payload) {
        if (status) { status.textContent = payload.message || 'Folder booking berhasil dibuat.'; status.classList.add(payload.db_cache_ok ? 'is-success' : 'is-error'); }
        if (payload.db_cache_ok && payload.folder_id) {
          var target = new URL('index.php', location.href);
          target.searchParams.set('folderId', payload.folder_id);
          navigate(target.toString(), { push: true, skipCache: true });
        } else if (activeSeed && activeSeed.rootId) {
          fetchTreeChildren(String(activeSeed.rootId), true).catch(function (error) { console.warn('Pohon folder belum dapat diperbarui:', error); });
        }
      })
      .catch(function (error) { if (status) { status.textContent = error.message || 'Pembuatan folder booking gagal.'; status.classList.add('is-error'); } })
      .finally(function () { if (submit) { submit.disabled = false; submit.textContent = 'Buat folder booking'; } });
  });

  // Current page bootstrap and in-memory page cache.
  var initialSeed = initialTreeEl ? safeJson(initialTreeEl.textContent) : null;
  if (initialSeed) {
    mergeTreeSeed(initialSeed);
    saveCachedPage(pageKey(location.href), { mainHtml: (document.getElementById('drive-main') || {}).innerHTML || '', treeSeed: initialSeed });
  }
  initializeMainControls();
})();
</script>
</body>
</html>
