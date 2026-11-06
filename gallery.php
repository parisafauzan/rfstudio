<?php
require_once __DIR__ . '/gallery-customer-lib.php';

$flash = rfg_customer_take_flash();
$error = '';
$email = isset($_GET['email']) ? strtolower(trim((string) $_GET['email'])) : '';
$sessionId = isset($_GET['session']) ? (int) $_GET['session'] : 0;
$activeTab = isset($_GET['tab']) && $_GET['tab'] === 'edited' ? 'edited' : 'original';
$galleries = array();
$gallery = null;
$displayPhotos = array();
$photoTotal = 0;
$originalTotal = 0;
$editedTotal = 0;
$photoPageHasMore = false;
$editRequest = null;
$selectedMap = array();
$schemaReady = false;
$driveReady = false;
$maxSelection = 30;
$galleryUnavailable = false;
$galleryUnavailableMessage = 'Galeri foto sudah tidak tersedia karena masa penyimpanannya berakhir atau folder telah dihapus oleh admin.';

try {
    $driveReady = rfg_config_ready() && rfg_is_connected();
    $schemaReady = rfg_gallery_edit_schema_ready();
    $cfg = isset($GLOBALS['RFG_CONFIG']) && is_array($GLOBALS['RFG_CONFIG']) ? $GLOBALS['RFG_CONFIG'] : array();
    if (!empty($cfg['max_customer_edit_selection'])) $maxSelection = max(1, min(100, (int) $cfg['max_customer_edit_selection']));
    if ($email !== '') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format email belum benar.';
        } else {
            $galleries = rfg_customer_galleries($email);
            if ($sessionId > 0) {
                $gallery = rfg_customer_gallery_for_email($sessionId, $email);
                if (!$gallery) {
                    $error = 'Galeri booking tidak ditemukan untuk email tersebut.';
                    $sessionId = 0;
                } else {
                    if ($gallery['status'] !== 'active') {
                        $galleryUnavailable = true;
                    } else {
                        if (!$driveReady) throw new RuntimeException('Galeri Google Drive sedang tidak terhubung. Silakan hubungi admin studio.');
                        try {
                            rfg_folder_path($gallery['drive_folder_id']);
                        } catch (Throwable $folderError) {
                            $folderMessage = strtolower($folderError->getMessage());
                            $isMissing = strpos($folderMessage, 'not found') !== false || strpos($folderMessage, 'tidak ditemukan') !== false || strpos($folderMessage, 'trash') !== false || strpos($folderMessage, 'di luar folder galeri') !== false;
                            if (!$isMissing) throw $folderError;
                            try {
                                rfg_gallery_deactivate_sessions(array($sessionId), 'Galeri foto telah dihapus oleh admin karena masa penyimpanannya berakhir.');
                            } catch (Throwable $syncError) {
                                if (function_exists('rfg_log')) rfg_log('CUSTOMER_GALLERY_DEACTIVATE_FAILED session=' . $sessionId . ' message=' . $syncError->getMessage());
                            }
                            $gallery['status'] = 'inactive';
                            $galleryUnavailable = true;
                        }
                    }
                    if (!$galleryUnavailable) {
                        rfg_customer_grant_access($sessionId, $email);
                        $editRequest = rfg_gallery_edit_request_for_session($sessionId);
                        $selectedMap = rfg_gallery_edit_selected_drive_map($sessionId);
                        // Sinkronkan tab yang dibuka agar upload manual dari Drive
                        // langsung terlihat; thumbnail tetap hanya dimuat 10 pertama.
                        $page = rfg_customer_photo_page($gallery, $activeTab, true, 0, 10);
                        $displayPhotos = $page['files'];
                        $photoTotal = (int) $page['total'];
                        $photoPageHasMore = !empty($page['has_more']);
                        $originalTotal = $activeTab === 'original' ? $photoTotal : rfg_gallery_photo_count($sessionId, 'original');
                        $editedTotal = $activeTab === 'edited' ? $photoTotal : rfg_gallery_photo_count($sessionId, 'edited');
                    }
                }
            }
        }
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}

$requestLocked = $editRequest && in_array($editRequest['status'], array('editing', 'completed'), true);
?><!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gallery Photo | RF Studio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body{background:#f7f7f8;color:#20242a}.gallery-wrap{max-width:1160px}.gallery-panel{background:#fff;border:1px solid #dfe3e8;box-shadow:0 3px 12px rgba(20,24,30,.05)}
    .lookup-panel{max-width:780px;margin:auto}.booking-card{transition:.18s ease;background:#fff}.booking-card:hover{border-color:#8b949e!important;transform:translateY(-1px)}
    .gallery-photo{position:relative;border:1px solid #dde2e7;border-radius:10px;background:#fff;overflow:hidden;transition:.18s ease}.gallery-photo:hover{box-shadow:0 7px 20px rgba(20,24,30,.11);transform:translateY(-2px)}
    .gallery-photo.is-selected{border:2px solid #171a1f}.photo-frame{position:relative;display:block;width:100%;height:230px;border:0;padding:0;background:#edf0f3;overflow:hidden}.photo-frame img{position:relative;z-index:2;width:100%;height:100%;object-fit:cover}.photo-skeleton,.preview-modal-skeleton{position:absolute;inset:0;background:linear-gradient(90deg,#e7ebef 25%,#f7f8f9 45%,#e7ebef 65%);background-size:220% 100%;animation:gallerySkeleton 1.25s ease-in-out infinite}.photo-frame:not(.is-loading) .photo-skeleton{display:none}@keyframes gallerySkeleton{to{background-position:-220% 0}}
    .photo-check{position:absolute;z-index:3;top:10px;left:10px;background:rgba(255,255,255,.96);padding:7px 10px;border-radius:8px;font-size:13px;box-shadow:0 2px 7px rgba(0,0,0,.15)}
    .photo-info{padding:12px}.photo-name{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.photo-actions{display:flex;gap:7px;margin-top:10px}.photo-actions .btn{flex:1;font-size:12px}
    .status-strip{border-left:4px solid #20242a}.gallery-tabs .nav-link{color:#505862;border-radius:0}.gallery-tabs .nav-link.active{background:#20242a;color:#fff}
    .sticky-selection{position:sticky;bottom:12px;z-index:20;background:#20242a;color:#fff;border-radius:10px;padding:12px 15px;box-shadow:0 9px 28px rgba(0,0,0,.23)}
    .customer-photo-toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding:12px;border:1px solid #e0e4e8;border-radius:10px;background:#fafbfc}.customer-photo-toolbar>span{margin-right:auto}.customer-load-controls{display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap;margin:5px 0 20px}.preview-modal-stage{position:relative;min-height:260px;display:grid;place-items:center}.preview-modal-skeleton{z-index:1;border-radius:8px}.preview-modal-stage:not(.is-loading) .preview-modal-skeleton{display:none}.preview-modal-img{position:relative;z-index:2;max-height:78vh;max-width:100%;width:auto;height:auto;object-fit:contain}.empty-gallery{padding:55px 20px;text-align:center;color:#6c757d}
    @media(max-width:575px){.photo-frame{height:170px}.gallery-photo-grid{--bs-gutter-x:.75rem}.sticky-selection{bottom:6px}.gallery-panel{padding:1rem!important}}
  </style>
</head>
<body>
<main class="container gallery-wrap py-4 py-md-5">
  <nav class="mb-4 small"><a class="text-dark" href="index">Home</a><span class="mx-2">/</span><span class="text-muted">Gallery Photo</span></nav>
  <div class="d-inline-flex align-items-center gap-2 bg-dark text-white px-4 py-3 mb-4"><i class="fa-regular fa-images"></i><strong>Gallery Photo</strong></div>

  <?php if ($flash): ?><div class="alert alert-<?php echo rfg_customer_h($flash['type']); ?>"><?php echo rfg_customer_h($flash['message']); ?></div><?php endif; ?>
  <?php if ($error !== ''): ?><div class="alert alert-danger"><?php echo rfg_customer_h($error); ?></div><?php endif; ?>
  <?php if (!$schemaReady): ?><div class="alert alert-warning">Fitur pemilihan foto belum aktif. Admin perlu menjalankan <code>migration-gallery-customer.sql</code>.</div><?php endif; ?>

  <?php if ($email === ''): ?>
    <section class="gallery-panel lookup-panel p-4 p-md-5 text-center">
      <i class="fa-regular fa-images fs-1 mb-3"></i>
      <h1 class="h2 fw-bold">Lihat Hasil Foto Anda</h1>
      <p class="text-muted mb-4">Masukkan email yang digunakan saat booking. Tidak perlu membuat akun.</p>
      <form method="get" action="gallery" class="text-start mx-auto" style="max-width:520px">
        <label class="form-label fw-bold" for="gallery-email">Email booking</label>
        <input class="form-control form-control-lg" type="email" id="gallery-email" name="email" autocomplete="email" required>
        <p class="small text-muted mt-2 mb-4">Galeri baru tampil setelah admin membuat folder booking dan mengunggah foto.</p>
        <div class="text-center"><button class="btn btn-dark px-5" type="submit">Cari Galeri</button></div>
      </form>
    </section>
  <?php elseif (!$gallery): ?>
    <section class="gallery-panel p-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4"><div><h1 class="h3 fw-bold mb-1">Pilih Booking</h1><p class="text-muted mb-0"><?php echo rfg_customer_h($email); ?></p></div><a class="btn btn-outline-secondary btn-sm" href="gallery">Ganti Email</a></div>
      <?php if (empty($galleries)): ?>
        <div class="alert alert-light border mb-0">Belum ada galeri foto yang tersedia untuk email tersebut. Galeri akan muncul setelah admin menyiapkan folder booking.</div>
      <?php else: foreach ($galleries as $item): $booking = $item['booking']; $request = $item['edit_request']; ?>
        <article class="booking-card border rounded p-3 mb-3">
          <div class="row align-items-center"><div class="col-md-8">
            <h2 class="h5 fw-bold mb-2"><?php echo rfg_customer_h(trim($booking['package'] . ' ' . $booking['tipe_package'])); ?></h2>
            <div class="small text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo rfg_customer_h($booking['tanggal']); ?> &nbsp; <i class="fa-regular fa-clock me-1"></i><?php echo rfg_customer_h($booking['jam']); ?></div>
            <div class="small text-muted mt-1"><i class="fa-solid fa-location-dot me-1"></i><?php echo rfg_customer_h(rfg_customer_studio_label($booking['studio'])); ?></div>
            <?php if ($item['status'] !== 'active'): ?><span class="badge bg-secondary mt-2">Masa penyimpanan berakhir</span><?php elseif ($request): ?><span class="badge bg-secondary mt-2"><?php echo rfg_customer_h(rfg_customer_edit_status_label($request['status'])); ?></span><?php else: ?><span class="badge bg-info text-dark mt-2">Galeri tersedia</span><?php endif; ?>
          </div><div class="col-md-4 text-md-end mt-3 mt-md-0"><?php if ($item['status'] === 'active'): ?><a class="btn btn-dark btn-sm" href="gallery?email=<?php echo urlencode($email); ?>&amp;session=<?php echo (int) $item['id']; ?>">Buka Galeri</a><?php else: ?><button class="btn btn-outline-secondary btn-sm" type="button" disabled>Galeri tidak tersedia</button><?php endif; ?></div></div>
        </article>
      <?php endforeach; endif; ?>
    </section>
  <?php else: $booking = $gallery['booking']; ?>
    <section class="gallery-panel p-3 p-md-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3"><div><h1 class="h3 fw-bold mb-1"><?php echo rfg_customer_h(trim($booking['package'] . ' ' . $booking['tipe_package'])); ?></h1><p class="text-muted mb-0"><?php echo rfg_customer_h($booking['tanggal'] . ' | ' . $booking['jam']); ?> · <?php echo rfg_customer_h(rfg_customer_studio_label($booking['studio'])); ?></p></div><a class="btn btn-outline-secondary btn-sm" href="gallery?email=<?php echo urlencode($email); ?>">Pilih Booking Lain</a></div>
      <?php if ($galleryUnavailable): ?>
        <div class="empty-gallery border rounded bg-light"><i class="fa-regular fa-folder-closed fs-1 mb-3"></i><h2 class="h5 fw-bold">Galeri sudah tidak tersedia</h2><p class="mb-0"><?php echo rfg_customer_h($galleryUnavailableMessage); ?></p></div>
      <?php else: ?>
      <?php if ($editRequest): ?>
        <div class="status-strip bg-light border p-3 mb-3"><div class="d-flex flex-wrap justify-content-between gap-2"><strong>Status permintaan edit: <?php echo rfg_customer_h(rfg_customer_edit_status_label($editRequest['status'])); ?></strong><span class="small text-muted"><?php echo rfg_customer_h($editRequest['submitted_at']); ?></span></div><?php if ($editRequest['admin_note'] !== ''): ?><div class="small mt-2"><strong>Catatan admin:</strong> <?php echo rfg_customer_h($editRequest['admin_note']); ?></div><?php endif; ?></div>
      <?php endif; ?>
      <ul class="nav gallery-tabs mb-4">
        <li class="nav-item"><a class="nav-link <?php echo $activeTab === 'original' ? 'active' : ''; ?>" href="gallery?email=<?php echo urlencode($email); ?>&amp;session=<?php echo $sessionId; ?>&amp;tab=original">Foto Original <span class="badge bg-light text-dark ms-1"><?php echo $originalTotal; ?></span></a></li>
        <li class="nav-item"><a class="nav-link <?php echo $activeTab === 'edited' ? 'active' : ''; ?>" href="gallery?email=<?php echo urlencode($email); ?>&amp;session=<?php echo $sessionId; ?>&amp;tab=edited">Hasil Edit <span class="badge bg-light text-dark ms-1"><?php echo $editedTotal; ?></span></a></li>
      </ul>

      <?php if (empty($displayPhotos)): ?>
        <div class="empty-gallery"><i class="fa-regular fa-image fs-1 mb-3"></i><h2 class="h5 fw-bold"><?php echo $activeTab === 'edited' ? 'Hasil edit belum tersedia' : 'Foto original belum tersedia'; ?></h2><p class="mb-0">Foto belum diunggah atau sudah dihapus oleh admin karena masa penyimpanan berakhir.</p></div>
      <?php else: ?>
        <?php if ($activeTab === 'original' && $requestLocked): ?><div class="alert alert-info">Pilihan edit dikunci karena <?php echo $editRequest['status'] === 'completed' ? 'proses edit sudah selesai' : 'admin sudah mulai mengedit'; ?>. Foto tetap dapat dipilih untuk download ZIP.</div><?php elseif ($activeTab === 'original'): ?><p class="text-muted small">Pilih foto yang ingin diteruskan ke admin untuk diedit. Checkbox yang sama juga dapat digunakan untuk download ZIP.</p><?php else: ?><p class="text-muted small">Foto hasil edit dapat dipilih lalu diunduh sekaligus dalam satu ZIP.</p><?php endif; ?>
        <div class="customer-photo-toolbar mb-3"><button class="btn btn-outline-dark btn-sm" type="button" id="customer-select-all">Pilih semua</button><span class="small text-muted"><strong id="zip-choice-count">0</strong> dipilih · <span id="customer-photo-count"><?php echo count($displayPhotos); ?> dari <?php echo $photoTotal; ?> foto ditampilkan</span></span><form method="post" action="gallery-download-zip" id="customer-zip-form"><input type="hidden" name="csrf_token" value="<?php echo rfg_customer_h(rfg_customer_csrf_token()); ?>"><input type="hidden" name="gallery_session_id" value="<?php echo $sessionId; ?>"><button class="btn btn-dark btn-sm" type="submit" disabled>Download ZIP pilihan</button></form></div>
        <?php if ($activeTab === 'original'): ?><form method="post" action="gallery-submit" id="edit-selection-form">
          <input type="hidden" name="csrf_token" value="<?php echo rfg_customer_h(rfg_customer_csrf_token()); ?>">
          <input type="hidden" name="gallery_session_id" value="<?php echo $sessionId; ?>">
          <?php $visibleInitialIds = array(); foreach ($displayPhotos as $visiblePhoto) $visibleInitialIds[(int) $visiblePhoto['photo_id']] = true; foreach ($selectedMap as $selectedItem): $preservedId = isset($selectedItem['gallery_photo_id']) ? (int) $selectedItem['gallery_photo_id'] : 0; if ($preservedId > 0 && !isset($visibleInitialIds[$preservedId])): ?><input class="preserved-photo-choice" type="hidden" name="photo_ids[]" value="<?php echo $preservedId; ?>"><?php endif; endforeach; ?>
        <?php endif; ?>
          <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 gallery-photo-grid" id="customer-photo-grid" data-session="<?php echo $sessionId; ?>" data-category="<?php echo rfg_customer_h($activeTab); ?>" data-loaded="<?php echo count($displayPhotos); ?>" data-total="<?php echo $photoTotal; ?>">
            <?php foreach ($displayPhotos as $photo) echo rfg_customer_photo_card_html($photo, $sessionId, $activeTab, $selectedMap, $requestLocked); ?>
          </div>
          <?php if ($photoPageHasMore): ?><div class="customer-load-controls" id="customer-load-controls"><button class="btn btn-outline-dark btn-sm" type="button" data-customer-load="more">Tampilkan 10 lagi</button><button class="btn btn-dark btn-sm" type="button" data-customer-load="all">Tampilkan semua</button><span class="small text-muted" data-customer-load-status></span></div><?php endif; ?>
          <?php if ($activeTab === 'original' && !$requestLocked && $schemaReady): ?><div class="sticky-selection"><div class="row align-items-end g-2"><div class="col-md"><label class="form-label small mb-1" for="customer-note">Catatan untuk editor (opsional)</label><input class="form-control form-control-sm" id="customer-note" name="customer_note" maxlength="500" value="<?php echo rfg_customer_h($editRequest ? $editRequest['customer_note'] : ''); ?>" placeholder="Contoh: tone natural, hilangkan objek di belakang"></div><div class="col-md-auto d-flex align-items-center gap-3"><span class="small"><strong id="choice-count"><?php echo count($selectedMap); ?></strong>/<?php echo $maxSelection; ?> foto</span><button class="btn btn-light btn-sm px-4" type="submit">Kirim Pilihan ke Admin</button></div></div></div><?php endif; ?>
        <?php if ($activeTab === 'original'): ?></form><?php endif; ?>
      <?php endif; ?>
      <?php endif; ?>
    </section>
  <?php endif; ?>
</main>

<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content bg-dark"><div class="modal-header border-0 text-white"><h2 class="modal-title h6" id="previewTitle">Preview foto asli</h2><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body text-center p-2 preview-modal-stage" id="previewStage"><span class="preview-modal-skeleton" aria-hidden="true"></span><img class="preview-modal-img" id="previewImage" alt="Preview foto asli"></div></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
(function(){
  var modalEl=document.getElementById('previewModal'),image=document.getElementById('previewImage'),title=document.getElementById('previewTitle'),previewStage=document.getElementById('previewStage'),modal=modalEl?new bootstrap.Modal(modalEl):null,max=<?php echo (int) $maxSelection; ?>;
  function choices(){return Array.prototype.slice.call(document.querySelectorAll('.photo-choice'))}
  function updateSelection(){var all=choices(),checked=all.filter(function(c){return c.checked}),editable=checked.filter(function(c){return c.hasAttribute('name')}),preserved=document.querySelectorAll('.preserved-photo-choice').length,totalEdit=editable.length+preserved;all.forEach(function(c){var card=c.closest('.gallery-photo');if(card)card.classList.toggle('is-selected',c.checked);var label=c.parentNode.querySelector('span');if(label)label.textContent=c.checked?'Dipilih':'Pilih';if(c.hasAttribute('name'))c.disabled=!c.checked&&totalEdit>=max});var editCount=document.getElementById('choice-count'),zipCount=document.getElementById('zip-choice-count'),zipButton=document.querySelector('#customer-zip-form button');if(editCount)editCount.textContent=totalEdit;if(zipCount)zipCount.textContent=checked.length;if(zipButton)zipButton.disabled=checked.length<1}
  function loadPhotos(all){var grid=document.getElementById('customer-photo-grid'),controls=document.getElementById('customer-load-controls');if(!grid)return Promise.resolve();var loaded=Number(grid.getAttribute('data-loaded')||0),total=Number(grid.getAttribute('data-total')||0);if(loaded>=total)return Promise.resolve();var status=controls?controls.querySelector('[data-customer-load-status]'):null;Array.prototype.slice.call(controls?controls.querySelectorAll('button'):[]).forEach(function(b){b.disabled=true});if(status)status.textContent='Memuat foto...';var url='gallery-photos?session='+encodeURIComponent(grid.getAttribute('data-session')||'')+'&category='+encodeURIComponent(grid.getAttribute('data-category')||'original')+'&offset='+loaded+(all?'&all=1':'');return fetch(url,{credentials:'same-origin',cache:'no-store',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}}).then(function(r){return r.json().then(function(p){if(!r.ok||!p.success)throw new Error(p.message||'Foto gagal dimuat.');return p})}).then(function(p){grid.insertAdjacentHTML('beforeend',p.html||'');grid.setAttribute('data-loaded',String(p.loaded||loaded));grid.setAttribute('data-total',String(p.total||total));var loadedIds=choices().map(function(c){return String(c.value)});Array.prototype.slice.call(document.querySelectorAll('.preserved-photo-choice')).forEach(function(h){if(loadedIds.indexOf(String(h.value))!==-1)h.remove()});var count=document.getElementById('customer-photo-count');if(count)count.textContent=(p.loaded||loaded)+' dari '+(p.total||total)+' foto ditampilkan';if(!p.has_more&&controls)controls.remove();else Array.prototype.slice.call(controls?controls.querySelectorAll('button'):[]).forEach(function(b){b.disabled=false});updateSelection()}).catch(function(e){if(status)status.textContent=e.message||'Foto gagal dimuat.';Array.prototype.slice.call(controls?controls.querySelectorAll('button'):[]).forEach(function(b){b.disabled=false});throw e})}
  document.addEventListener('click',function(e){var preview=e.target.closest('.js-preview');if(preview&&modal){e.preventDefault();if(previewStage)previewStage.classList.add('is-loading');image.removeAttribute('src');title.textContent=(preview.getAttribute('data-title')||'Preview foto')+' · resolusi asli';modal.show();image.src=preview.getAttribute('data-src')||'';return}var load=e.target.closest('[data-customer-load]');if(load){e.preventDefault();loadPhotos(load.getAttribute('data-customer-load')==='all').catch(function(){});return}var selectAll=e.target.closest('#customer-select-all');if(selectAll){e.preventDefault();loadPhotos(true).catch(function(){}).then(function(){choices().forEach(function(c){if(!c.disabled)c.checked=true});updateSelection()})}});
  document.addEventListener('change',function(e){if(e.target.classList&&e.target.classList.contains('photo-choice'))updateSelection()});
  if(image){image.addEventListener('load',function(){if(previewStage)previewStage.classList.remove('is-loading')});image.addEventListener('error',function(){if(previewStage)previewStage.classList.remove('is-loading');title.textContent='Foto asli gagal dimuat'})}
  if(modalEl)modalEl.addEventListener('hidden.bs.modal',function(){image.removeAttribute('src');if(previewStage)previewStage.classList.remove('is-loading')});
  var zipForm=document.getElementById('customer-zip-form');if(zipForm)zipForm.addEventListener('submit',function(e){Array.prototype.slice.call(zipForm.querySelectorAll('input[name="photo_ids[]"]')).forEach(function(n){n.remove()});var selected=choices().filter(function(c){return c.checked});if(!selected.length){e.preventDefault();return}selected.forEach(function(c){var input=document.createElement('input');input.type='hidden';input.name='photo_ids[]';input.value=c.value;zipForm.appendChild(input)})});
  var editForm=document.getElementById('edit-selection-form');if(editForm)editForm.addEventListener('submit',function(e){var n=editForm.querySelectorAll('input[name="photo_ids[]"]:checked, input.preserved-photo-choice').length;if(n>max){e.preventDefault();alert('Maksimal '+max+' foto dapat dikirim untuk diedit. Kurangi pilihan terlebih dahulu.')}});
  updateSelection();
})();
</script>
</body>
</html>
