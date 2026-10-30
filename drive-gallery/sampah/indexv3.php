<?php
require_once __DIR__ . '/bootstrap.php';

$flash = isset($_SESSION['rfgallery_flash']) ? $_SESSION['rfgallery_flash'] : null;
unset($_SESSION['rfgallery_flash']);
if (isset($_GET['from']) && $_GET['from'] === 'kota') {
    $_SESSION['rfgallery_return_url'] = '../adminkartini/datakonfirmasidp.php';
} elseif (isset($_GET['from']) && $_GET['from'] === 'kabupaten') {
    $_SESSION['rfgallery_return_url'] = '../admin/datakonfirmasidp.php';
}
$returnUrl = isset($_SESSION['rfgallery_return_url']) ? $_SESSION['rfgallery_return_url'] : '../admin/datakonfirmasidp.php';
$configReady = rfg_config_ready();
$connected = $configReady && rfg_is_connected();
$usage = $connected ? rfg_usage_summary() : array('minute_units' => 0, 'minute_calls' => 0, 'today_units' => 0, 'today_calls' => 0, 'today_egress_bytes' => 0, 'today_upload_bytes' => 0, 'today_uploads' => 0, 'monitoring_since' => null);
$shareStates = $connected ? rfg_share_states_read() : array();
$files = array();
$nextPageToken = '';
$pageToken = isset($_GET['pageToken']) ? (string) $_GET['pageToken'] : '';
$listError = '';
if ($connected) {
    try {
        $result = rfg_list_files($pageToken);
        $rawFiles = isset($result['files']) && is_array($result['files']) ? $result['files'] : array();
        foreach ($rawFiles as $file) {
            if (isset($file['mimeType']) && in_array($file['mimeType'], array('image/jpeg', 'image/png', 'image/webp'), true)) {
                $files[] = $file;
            }
        }
        $nextPageToken = isset($result['nextPageToken']) ? $result['nextPageToken'] : '';
    } catch (Exception $e) {
        $listError = $e->getMessage();
    }
}

// Muat ulang statistik setelah request daftar foto halaman ini tercatat.
if ($connected) {
    $usage = rfg_usage_summary();
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
    <div><p class="eyebrow">MEDIA MANAGEMENT</p><h1>Galeri Foto</h1><p class="lead">Kelola foto Google Drive, tautan berbagi, dan estimasi penggunaan API. Tautan foto bersifat privat secara default.</p></div>
    <div class="status-pill <?php echo $connected ? 'is-connected' : 'is-disconnected'; ?>"><span class="status-dot"></span><?php echo $connected ? 'Google Drive terhubung' : 'Google Drive belum terhubung'; ?></div>
  </section>

  <?php if ($flash): ?>
    <div class="alert alert-<?php echo rfg_h(isset($flash['type']) ? $flash['type'] : 'info'); ?>"><?php echo rfg_h(isset($flash['text']) ? $flash['text'] : ''); ?></div>
  <?php endif; ?>
  <?php if (isset($_GET['connected'])): ?><div class="alert alert-success">Google Drive berhasil dihubungkan.</div><?php endif; ?>

  <?php if (!$configReady): ?>
    <section class="panel setup-panel">
      <div class="setup-icon">⚙</div><div>
        <h2>Konfigurasi awal diperlukan</h2>
        <p>Modul sudah terpasang, tetapi belum menemukan konfigurasi OAuth privat atau ekstensi cURL tidak aktif.</p>
        <ol>
          <li>Buat project dan OAuth Client ID tipe <strong>Web application</strong> di Google Cloud Console.</li>
          <li>Aktifkan Google Drive API dan tambahkan Redirect URI yang ditampilkan di README.</li>
          <li>Salin template konfigurasi ke direktori privat di luar <code>public_html</code>.</li>
          <li>Pastikan ekstensi <code>curl</code> aktif untuk versi PHP yang dipakai situs.</li>
        </ol>
        <p class="small-note">Panduan rinci ada di file <strong>README_ID.md</strong> dalam paket integrasi. Jangan pernah mengirim Client Secret, token, atau password Google ke chat.</p>
      </div>
    </section>
  <?php elseif (!$connected): ?>
    <section class="panel connect-panel">
      <div class="connect-symbol">☁</div><div class="connect-copy"><h2>Hubungkan akun Google One</h2><p>Login menggunakan akun Google yang akan menjadi pemilik folder galeri RF Studio. Foto yang diunggah melalui aplikasi akan masuk ke folder <strong>RF Studio Gallery</strong>.</p><p class="small-note">Izin yang diminta: <code>drive.file</code> — untuk file/folder yang dibuat atau dipakai oleh aplikasi ini, bukan seluruh isi Drive.</p></div>
      <a class="button button-primary" href="oauth">Hubungkan Google Drive</a>
    </section>
  <?php else: ?>
    <section class="panel upload-panel">
      <div class="section-heading"><div><h2>Upload foto</h2><p>Pilih JPG, PNG, atau WebP. Batas awal: 10 foto per batch, maksimal 20 MB per foto dan 100 MB total per batch.</p></div><span class="tag">Penyimpanan: Google Drive</span></div>
      <form action="upload" method="post" enctype="multipart/form-data" class="upload-form" id="upload-form">
        <?php echo rfg_csrf_field(); ?>
        <label class="dropzone" for="photos"><span class="drop-icon">＋</span><strong>Pilih foto dari komputer</strong><span id="selected-files">Belum ada foto dipilih</span><input id="photos" type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple required></label>
        <div class="upload-actions"><span class="small-note">Foto dikirim langsung dari browser ke PHP, lalu diteruskan ke Google Drive. Tidak disimpan permanen di folder website.</span><button class="button button-primary" type="submit" id="upload-button">Upload foto</button></div>
      </form>
    </section>

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
      <div class="section-heading"><div><h2>Usage Google Drive API</h2><p>Estimasi aktivitas yang tercatat oleh modul RF Studio ini.</p></div><a class="button button-light" href="https://console.cloud.google.com/apis/api/drive.googleapis.com/quotas" target="_blank" rel="noopener">Lihat kuota Google Cloud ↗</a></div>
      <div class="usage-grid">
        <article class="usage-card"><span>Kuota API · menit berjalan</span><strong><?php echo number_format($usage['minute_units'], 0, ',', '.'); ?> unit</strong><div class="usage-meter"><span style="width:<?php echo number_format($minuteProjectPct, 3, '.', ''); ?>%"></span></div><small><?php echo number_format($minuteProjectPct, 3, ',', '.'); ?>% dari 1.000.000 unit/proyek/menit</small></article>
        <article class="usage-card"><span>Batas pengguna · menit berjalan</span><strong><?php echo number_format($usage['minute_units'], 0, ',', '.'); ?> unit</strong><div class="usage-meter"><span style="width:<?php echo number_format($minuteUserPct, 3, '.', ''); ?>%"></span></div><small><?php echo number_format($minuteUserPct, 3, ',', '.'); ?>% dari 325.000 unit/pengguna/menit</small></article>
        <article class="usage-card"><span>Data keluar via galeri · hari ini</span><strong><?php echo rfg_h($fmtBytes($usage['today_egress_bytes'])); ?></strong><div class="usage-meter"><span style="width:<?php echo number_format($dailyEgressPct, 4, '.', ''); ?>%"></span></div><small><?php echo number_format($dailyEgressPct, 4, ',', '.'); ?>% dari ambang 1 TB/24 jam</small></article>
        <article class="usage-card"><span>Unit API tercatat · hari ini</span><strong><?php echo number_format($usage['today_units'], 0, ',', '.'); ?> unit</strong><div class="usage-meter"><span style="width:<?php echo number_format($dailyUnitsPct, 4, '.', ''); ?>%"></span></div><small><?php echo number_format($dailyUnitsPct, 4, ',', '.'); ?>% dari ambang harian 400 juta unit</small></article>
        <article class="usage-card"><span>Foto diupload lewat modul · hari ini</span><strong><?php echo number_format($usage['today_uploads'], 0, ',', '.'); ?> foto</strong><small>Total data upload berhasil: <?php echo rfg_h($fmtBytes($usage['today_upload_bytes'])); ?></small></article>
        <article class="usage-card"><span>Operasi API yang tercatat · hari ini</span><strong><?php echo number_format($usage['today_calls'], 0, ',', '.'); ?> operasi</strong><small>Menit berjalan: <?php echo number_format($usage['minute_calls'], 0, ',', '.'); ?> operasi</small></article>
      </div>
      <div class="usage-notice"><strong>Penting — angka ini estimasi, bukan penghitung kuota resmi Google.</strong> Data mulai dihitung setelah patch dipasang dan hanya mencakup request yang melewati modul ini. Aktivitas dari Cloud Console, aplikasi lain, serta orang yang membuka link Google Drive langsung dari WhatsApp tidak dapat dihitung di sini. Persentase 1 TB dan 400 juta unit dibandingkan dengan batas yang dipublikasikan Google; periksa halaman kuota Google Cloud untuk data proyek yang tersedia di sana. <a href="https://developers.google.com/workspace/drive/api/guides/limits" target="_blank" rel="noopener">Dokumentasi batas Google ↗</a></div>
    </section>

    <section class="gallery-section">
      <div class="section-heading"><div><h2>Foto di Google Drive</h2><p>Halaman ini menampilkan hingga 50 foto per halaman.</p></div><span class="count-pill"><?php echo count($files); ?> foto ditampilkan</span></div>
      <?php if ($listError !== ''): ?><div class="alert alert-danger">Tidak dapat membaca Google Drive: <?php echo rfg_h($listError); ?></div><?php endif; ?>
      <?php if (empty($files)): ?>
        <div class="empty-state"><div class="empty-symbol">▧</div><h3>Belum ada foto di galeri</h3><p>Upload beberapa foto menggunakan form di atas. Folder Drive akan dibuat otomatis saat pertama kali diperlukan.</p></div>
      <?php else: ?>
        <form action="download-selected" method="post" id="selection-form">
          <?php echo rfg_csrf_field(); ?>
          <div class="selection-toolbar"><label class="select-all"><input type="checkbox" id="select-all"> Pilih semua di halaman ini</label><span id="selected-count">0 foto dipilih</span><button class="button button-dark" type="submit" id="download-selected-button" disabled>Download pilihan (.ZIP)</button></div>
        </form>
        <div class="photo-grid">
          <?php foreach ($files as $file): ?>
            <?php $shareState = isset($shareStates[$file['id']]) && !empty($shareStates[$file['id']]['enabled']); $shareUrl = $shareState && !empty($shareStates[$file['id']]['url']) ? $shareStates[$file['id']]['url'] : rfg_share_link($file['id']); ?>
            <article class="photo-card">
              <label class="photo-select" aria-label="Pilih foto <?php echo rfg_h($file['name']); ?>"><input type="checkbox" form="selection-form" class="photo-checkbox" name="selected_ids[]" value="<?php echo rfg_h($file['id']); ?>"><span>Pilih</span></label>
              <a class="photo-preview" href="download?id=<?php echo rawurlencode($file['id']); ?>" title="Download foto ini"><img loading="lazy" src="thumbnail?id=<?php echo rawurlencode($file['id']); ?>" alt="Preview <?php echo rfg_h($file['name']); ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='grid';"><span class="preview-fallback" style="display:none">Preview tidak tersedia</span></a>
              <div class="photo-details"><strong title="<?php echo rfg_h($file['name']); ?>"><?php echo rfg_h($file['name']); ?></strong><div class="photo-meta"><span><?php echo isset($file['size']) ? number_format(((int) $file['size']) / 1048576, 2, ',', '.') . ' MB' : 'Ukuran tidak diketahui'; ?></span><span><?php echo !empty($file['createdTime']) ? rfg_h(date('d M Y', strtotime($file['createdTime']))) : ''; ?></span></div><a class="download-link" href="download?id=<?php echo rawurlencode($file['id']); ?>">Download foto ↗</a>
                <div class="share-controls">
                  <?php if ($shareState): ?>
                    <span class="share-status is-shared">Link aktif — siapa pun yang punya link dapat melihat foto</span>
                    <button type="button" class="button button-primary copy-share-link" data-link="<?php echo rfg_h($shareUrl); ?>">Salin link untuk WhatsApp</button>
                    <a class="share-open-link" href="<?php echo rfg_h($shareUrl); ?>" target="_blank" rel="noopener">Buka link ↗</a>
                    <form action="share" method="post" onsubmit="return confirm('Matikan akses bagi siapa pun yang memiliki link ini? Link dari WhatsApp tidak akan dapat digunakan lagi setelah izin Google Drive berhasil dicabut.');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="file_id" value="<?php echo rfg_h($file['id']); ?>"><input type="hidden" name="share_action" value="disable"><button type="submit" class="button button-light">Matikan link publik</button></form>
                  <?php else: ?>
                    <span class="share-status is-private">Privat — belum dibagikan lewat link</span>
                    <form action="share" method="post" onsubmit="return confirm('Mengaktifkan link akan membuat foto dapat dilihat siapa pun yang memiliki tautan, termasuk jika link diteruskan ke orang lain. Lanjutkan?');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="file_id" value="<?php echo rfg_h($file['id']); ?>"><input type="hidden" name="share_action" value="enable"><button type="submit" class="button button-light">Aktifkan link WhatsApp</button></form>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
        <?php if ($pageToken !== ''): ?><a class="button button-light pager" href="index">← Halaman pertama</a><?php endif; ?>
        <?php if ($nextPageToken !== ''): ?><a class="button button-light pager" href="index?pageToken=<?php echo rawurlencode($nextPageToken); ?>">Foto berikutnya →</a><?php endif; ?>
      <?php endif; ?>
    </section>
    <section class="bottom-tools"><div><strong>Folder galeri</strong><p>Folder akan dibuat otomatis di My Drive dan ID-nya disimpan di direktori privat website.</p></div><form action="disconnect" method="post" onsubmit="return confirm('Hapus token Google Drive yang tersimpan di server?')"><?php echo rfg_csrf_field(); ?><button class="button button-light" type="submit">Putuskan koneksi</button></form></section>
  <?php endif; ?>
  <footer>RF Studio · Modul galeri uji coba · Akses admin saja</footer>
</main>
<script>
(function () {
  var fileInput = document.getElementById('photos');
  var selectedFiles = document.getElementById('selected-files');
  var uploadForm = document.getElementById('upload-form');
  var uploadButton = document.getElementById('upload-button');
  if (fileInput && selectedFiles) {
    fileInput.addEventListener('change', function () {
      var files = Array.prototype.slice.call(fileInput.files || []);
      var tooMany = files.length > 10;
      var totalBytes = files.reduce(function (sum, file) { return sum + file.size; }, 0);
      var tooLarge = totalBytes > 100 * 1024 * 1024;
      selectedFiles.textContent = files.length ? files.length + ' foto dipilih · ' + (totalBytes / (1024 * 1024)).toFixed(1) + ' MB' : 'Belum ada foto dipilih';
      if (tooMany) selectedFiles.textContent += ' — maksimal 10 foto';
      if (tooLarge) selectedFiles.textContent += ' — total batch maksimal 100 MB';
      if (uploadButton) uploadButton.disabled = tooMany || tooLarge || files.length === 0;
    });
  }
  if (uploadForm) {
    uploadForm.addEventListener('submit', function () {
      if (uploadButton) { uploadButton.disabled = true; uploadButton.textContent = 'Sedang upload...'; }
    });
  }
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
  if (selectAll) {
    selectAll.addEventListener('change', function () {
      checkboxes.forEach(function (box) { box.checked = selectAll.checked; });
      updateSelection();
    });
  }
  checkboxes.forEach(function (box) { box.addEventListener('change', updateSelection); });
  var selectionForm = document.getElementById('selection-form');
  if (selectionForm) {
    selectionForm.addEventListener('submit', function (event) {
      var count = checkboxes.filter(function (box) { return box.checked; }).length;
      if (count < 1 || count > 10) { event.preventDefault(); alert('Pilih 1 sampai 10 foto untuk diunduh sebagai ZIP.'); }
    });
  }
  var copyButtons = Array.prototype.slice.call(document.querySelectorAll('.copy-share-link'));
  copyButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      var link = button.getAttribute('data-link') || '';
      if (!link) return;
      function copied() { var oldText = button.textContent; button.textContent = 'Link tersalin ✓'; setTimeout(function () { button.textContent = oldText; }, 1800); }
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(link).then(copied).catch(function () { window.prompt('Salin link berikut:', link); });
      } else {
        window.prompt('Salin link berikut:', link);
      }
    });
  });
  updateSelection();
})();
</script>
</body>
</html>
