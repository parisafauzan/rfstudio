<?php
/** Markup kartu foto admin yang dipakai halaman awal dan endpoint AJAX. */

function rfg_admin_icon($name)
{
    $paths = array(
        'download' => '<path d="M12 3v11m0 0 4-4m-4 4-4-4M4 17v3h16v-3"/>',
        'link' => '<path d="M10 13a5 5 0 0 0 7.5.5l2-2a5 5 0 0 0-7-7l-1.1 1.1M14 11a5 5 0 0 0-7.5-.5l-2 2a5 5 0 0 0 7 7l1.1-1.1"/>',
        'copy' => '<rect x="8" y="8" width="11" height="11" rx="2"/><path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3"/>',
        'unlink' => '<path d="m3 3 18 18M10 13a5 5 0 0 0 2.2.8M14 11a5 5 0 0 0-2.2-.8M7.5 10.5l-3 2a5 5 0 0 0 6.5 7.4l1.6-1.5M11.4 5.6l1.1-1.1a5 5 0 0 1 7 7l-2 2"/>',
        'trash' => '<path d="M4 7h16M9 7V4h6v3m3 0-1 14H7L6 7m4 4v6m4-6v6"/>',
    );
    return '<svg viewBox="0 0 24 24" aria-hidden="true">' . (isset($paths[$name]) ? $paths[$name] : '') . '</svg>';
}

function rfg_admin_photo_card_html($file, $folderId, $shareStates, $selectedDriveMap)
{
    $id = isset($file['id']) ? (string) $file['id'] : '';
    $name = isset($file['name']) ? (string) $file['name'] : 'Foto';
    $size = isset($file['size']) ? (int) $file['size'] : 0;
    $created = isset($file['createdTime']) ? (string) $file['createdTime'] : '';
    $chosen = isset($selectedDriveMap[$id]);
    $shareState = isset($shareStates[$id]) && !empty($shareStates[$id]['enabled']);
    $shareUrl = $shareState && !empty($shareStates[$id]['url']) ? $shareStates[$id]['url'] : rfg_share_link($id);
    ob_start();
    ?>
    <article class="photo-card <?php echo $chosen ? 'is-customer-selected' : ''; ?>" data-kind="photos" data-name="<?php echo rfg_h($name); ?>" data-size="<?php echo $size; ?>" data-created="<?php echo rfg_h($created); ?>">
      <?php if ($chosen): ?><span class="customer-selected-badge">Pilihan customer</span><?php endif; ?>
      <label class="photo-select" aria-label="Pilih foto <?php echo rfg_h($name); ?>"><input type="checkbox" form="selection-form" class="photo-checkbox" name="selected_ids[]" value="<?php echo rfg_h($id); ?>"><span>Pilih</span></label>
      <div class="photo-card-media is-loading">
        <button type="button" class="photo-preview" data-photo-preview data-preview-src="preview-original.php?id=<?php echo rawurlencode($id); ?>&amp;folderId=<?php echo rawurlencode($folderId); ?>" data-preview-title="<?php echo rfg_h($name); ?>" aria-label="Preview <?php echo rfg_h($name); ?>" title="Lihat foto resolusi penuh">
          <img loading="lazy" src="thumbnail.php?id=<?php echo rawurlencode($id); ?>&amp;folderId=<?php echo rawurlencode($folderId); ?>" alt="Preview <?php echo rfg_h($name); ?>" onload="this.closest('.photo-card-media').classList.remove('is-loading')" onerror="this.closest('.photo-card-media').classList.remove('is-loading');this.style.display='none';this.nextElementSibling.style.display='grid';">
          <span class="preview-fallback" style="display:none">Preview tidak tersedia</span>
        </button>
        <a class="photo-download-button" href="download.php?id=<?php echo rawurlencode($id); ?>&amp;folderId=<?php echo rawurlencode($folderId); ?>" title="Unduh foto asli" aria-label="Unduh <?php echo rfg_h($name); ?>"><?php echo rfg_admin_icon('download'); ?></a>
      </div>
      <div class="photo-details">
        <strong title="<?php echo rfg_h($name); ?>"><?php echo rfg_h($name); ?></strong>
        <div class="photo-meta"><span><?php echo $size > 0 ? number_format($size / 1048576, 2, ',', '.') . ' MB' : 'Ukuran tidak diketahui'; ?></span><span><?php echo $created !== '' ? rfg_h(date('d M Y', strtotime($created))) : ''; ?></span></div>
        <div class="photo-compact-status <?php echo $shareState ? 'is-shared' : 'is-private'; ?>"><?php echo $shareState ? 'Link WhatsApp aktif' : 'Privat'; ?></div>
        <div class="photo-icon-actions">
          <?php if ($shareState): ?>
            <button type="button" class="photo-icon-button copy-share-link" data-link="<?php echo rfg_h($shareUrl); ?>" title="Salin link WhatsApp" aria-label="Salin link WhatsApp"><?php echo rfg_admin_icon('copy'); ?></button>
            <form action="share" method="post" onsubmit="return confirm('Matikan akses publik untuk foto ini?');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="file_id" value="<?php echo rfg_h($id); ?>"><input type="hidden" name="folder_id" value="<?php echo rfg_h($folderId); ?>"><input type="hidden" name="share_action" value="disable"><button type="submit" class="photo-icon-button" title="Matikan link WhatsApp" aria-label="Matikan link WhatsApp"><?php echo rfg_admin_icon('unlink'); ?></button></form>
          <?php else: ?>
            <form action="share" method="post" onsubmit="return confirm('Siapa pun yang menerima tautan dapat melihat foto ini. Aktifkan link WhatsApp?');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="file_id" value="<?php echo rfg_h($id); ?>"><input type="hidden" name="folder_id" value="<?php echo rfg_h($folderId); ?>"><input type="hidden" name="share_action" value="enable"><button type="submit" class="photo-icon-button" title="Aktifkan link WhatsApp" aria-label="Aktifkan link WhatsApp"><?php echo rfg_admin_icon('link'); ?></button></form>
          <?php endif; ?>
          <form action="delete-item" method="post" onsubmit="return confirm('Pindahkan foto ini ke Trash Google Drive? Foto juga akan dihapus dari pilihan edit customer.');"><?php echo rfg_csrf_field(); ?><input type="hidden" name="item_type" value="photo"><input type="hidden" name="item_id" value="<?php echo rfg_h($id); ?>"><input type="hidden" name="parent_folder_id" value="<?php echo rfg_h($folderId); ?>"><button type="submit" class="photo-icon-button is-danger" title="Hapus foto" aria-label="Hapus foto"><?php echo rfg_admin_icon('trash'); ?></button></form>
        </div>
      </div>
    </article>
    <?php
    return trim(ob_get_clean());
}
