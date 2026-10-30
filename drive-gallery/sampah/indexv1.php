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
    <div><p class="eyebrow">MEDIA MANAGEMENT</p><h1>Galeri Foto</h1><p class="lead">Kelola foto yang tersimpan pada folder privat Google Drive. Modul ini terpisah dari sistem booking lama.</p></div>
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
      <a class="button button-primary" href="oauth.php">Hubungkan Google Drive</a>
    </section>
  <?php else: ?>
    <section class="panel upload-panel">
      <div class="section-heading"><div><h2>Upload foto</h2><p>Pilih JPG, PNG, atau WebP. Batas awal: 10 foto per batch, maksimal 20 MB per foto dan 100 MB total per batch.</p></div><span class="tag">Penyimpanan: Google Drive</span></div>
      <form action="upload.php" method="post" enctype="multipart/form-data" class="upload-form" id="upload-form">
        <?php echo rfg_csrf_field(); ?>
        <label class="dropzone" for="photos"><span class="drop-icon">＋</span><strong>Pilih foto dari komputer</strong><span id="selected-files">Belum ada foto dipilih</span><input id="photos" type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple required></label>
        <div class="upload-actions"><span class="small-note">Foto dikirim langsung dari browser ke PHP, lalu diteruskan ke Google Drive. Tidak disimpan permanen di folder website.</span><button class="button button-primary" type="submit" id="upload-button">Upload foto</button></div>
      </form>
    </section>

    <section class="gallery-section">
      <div class="section-heading"><div><h2>Foto di Google Drive</h2><p>Halaman ini menampilkan hingga 50 foto per halaman.</p></div><span class="count-pill"><?php echo count($files); ?> foto ditampilkan</span></div>
      <?php if ($listError !== ''): ?><div class="alert alert-danger">Tidak dapat membaca Google Drive: <?php echo rfg_h($listError); ?></div><?php endif; ?>
      <?php if (empty($files)): ?>
        <div class="empty-state"><div class="empty-symbol">▧</div><h3>Belum ada foto di galeri</h3><p>Upload beberapa foto menggunakan form di atas. Folder Drive akan dibuat otomatis saat pertama kali diperlukan.</p></div>
      <?php else: ?>
        <form action="download-selected.php" method="post" id="selection-form">
          <?php echo rfg_csrf_field(); ?>
          <div class="selection-toolbar"><label class="select-all"><input type="checkbox" id="select-all"> Pilih semua di halaman ini</label><span id="selected-count">0 foto dipilih</span><button class="button button-dark" type="submit" id="download-selected-button" disabled>Download pilihan (.ZIP)</button></div>
          <div class="photo-grid">
            <?php foreach ($files as $file): ?>
              <article class="photo-card">
                <label class="photo-select" aria-label="Pilih foto <?php echo rfg_h($file['name']); ?>"><input type="checkbox" class="photo-checkbox" name="selected_ids[]" value="<?php echo rfg_h($file['id']); ?>"><span>Pilih</span></label>
                <a class="photo-preview" href="download.php?id=<?php echo rawurlencode($file['id']); ?>" title="Download foto ini"><img loading="lazy" src="thumbnail.php?id=<?php echo rawurlencode($file['id']); ?>" alt="Preview <?php echo rfg_h($file['name']); ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='grid';"><span class="preview-fallback" style="display:none">Preview tidak tersedia</span></a>
                <div class="photo-details"><strong title="<?php echo rfg_h($file['name']); ?>"><?php echo rfg_h($file['name']); ?></strong><div class="photo-meta"><span><?php echo isset($file['size']) ? number_format(((int) $file['size']) / 1048576, 2, ',', '.') . ' MB' : 'Ukuran tidak diketahui'; ?></span><span><?php echo !empty($file['createdTime']) ? rfg_h(date('d M Y', strtotime($file['createdTime']))) : ''; ?></span></div><a class="download-link" href="download.php?id=<?php echo rawurlencode($file['id']); ?>">Download foto ↗</a></div>
              </article>
            <?php endforeach; ?>
          </div>
        </form>
        <?php if ($pageToken !== ''): ?><a class="button button-light pager" href="index.php">← Halaman pertama</a><?php endif; ?>
        <?php if ($nextPageToken !== ''): ?><a class="button button-light pager" href="index.php?pageToken=<?php echo rawurlencode($nextPageToken); ?>">Foto berikutnya →</a><?php endif; ?>
      <?php endif; ?>
    </section>
    <section class="bottom-tools"><div><strong>Folder galeri</strong><p>Folder akan dibuat otomatis di My Drive dan ID-nya disimpan di direktori privat website.</p></div><form action="disconnect.php" method="post" onsubmit="return confirm('Hapus token Google Drive yang tersimpan di server?')"><?php echo rfg_csrf_field(); ?><button class="button button-light" type="submit">Putuskan koneksi</button></form></section>
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
  updateSelection();
})();
</script>
</body>
</html>
