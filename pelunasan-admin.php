<?php
require 'function.php';
require 'pelunasan-lib.php';

rfp_start_session();
$isKabupaten = !empty($_SESSION['admin']);
$isKota = !empty($_SESSION['adminkartini']);
if (!$isKabupaten && !$isKota) {
    header('Location: index.php');
    exit;
}
$studio = $isKota ? 'Bekasi-Kota' : 'Bekasi-Kabupaten';
$adminName = isset($_SESSION['fusername']) ? $_SESSION['fusername'] : ($isKota ? 'Admin Bekasi Kota' : 'Admin Bekasi Kabupaten');
$backUrl = $isKota ? 'admin-kartini/admin.php' : 'admin/admin.php';
$error = '';

if (rfp_schema_ready() && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!rfp_csrf_is_valid(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
        $error = 'Sesi formulir telah berakhir. Muat ulang halaman sebelum memproses data.';
    } else {
        $decision = isset($_POST['review_action']) ? $_POST['review_action'] : '';
        $status = $decision === 'approve' ? 'approved' : ($decision === 'reject' ? 'rejected' : '');
        $adminNote = trim(isset($_POST['admin_note']) ? $_POST['admin_note'] : '');
        if ($status === 'rejected' && $adminNote === '') {
            $adminNote = 'Bukti atau nominal pelunasan belum sesuai. Silakan perbaiki dan unggah ulang.';
        }
        if ($status === '') {
            $error = 'Aksi verifikasi tidak valid.';
        } elseif (rfp_admin_update_submission(isset($_POST['submission_id']) ? (int)$_POST['submission_id'] : 0, $status, $adminNote, $adminName, $studio)) {
            rfp_flash('success', $status === 'approved' ? 'Pembayaran disetujui dan invoice sekarang berstatus LUNAS.' : 'Pengajuan ditolak. Pelanggan dapat mengajukan ulang.');
            header('Location: pelunasan-admin');
            exit;
        } else {
            $error = 'Pengajuan tidak dapat diperbarui. Mungkin sudah diproses oleh admin lain.';
        }
    }
}
$flash = rfp_take_flash();
$submissions = rfp_schema_ready() ? rfp_admin_submissions($studio) : array();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pengajuan Pelunasan - RF Studio</title>
  <link href="img/logo.png" rel="icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
  <style>body{font-family:'Josefin Sans',sans-serif;background:#fafafa}.admin-wrap{max-width:1200px}.proof{max-width:180px;max-height:180px;object-fit:contain;background:#f8f9fa}.payment-card{border-left:4px solid #212529}.table td{vertical-align:middle}</style>
</head>
<body>
  <nav class="navbar navbar-light border-bottom bg-white py-3"><div class="container"><a href="<?php echo rfp_h($backUrl); ?>" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Admin</a><a href="index.php" class="text-dark text-decoration-none fw-bold"><img src="img/logo.png" class="rounded-circle me-2" width="42" alt="RF Studio">Rizal Foto Studio</a></div></nav>
  <main class="container admin-wrap my-5">
    <div class="d-flex justify-content-between align-items-start mb-4"><div><h1 class="h2 fw-bold mb-1">Pengajuan Pelunasan</h1><p class="text-muted mb-0"><?php echo rfp_h(rfp_studio_label($studio)); ?> · status pembayaran pada modul baru</p></div><a href="pelunasan-admin.php" class="btn btn-outline-secondary btn-sm">Muat Ulang</a></div>
    <?php if (!rfp_schema_ready()) { ?><div class="alert alert-warning">Database belum siap. Jalankan <code>migration-pelunasan-email.sql</code>.</div><?php } ?>
    <?php if ($flash) { ?><div class="alert alert-<?php echo rfp_h($flash['type']); ?> alert-dismissible fade show"><?php echo rfp_h($flash['message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php } ?>
    <?php if ($error !== '') { ?><div class="alert alert-danger"><?php echo rfp_h($error); ?></div><?php } ?>
    <div class="alert alert-light border small">Menyetujui pengajuan akan mengubah invoice menjadi <strong>LUNAS</strong>. Setelah sesi foto selesai, gunakan tombol <strong>Selesaikan Photoshoot</strong> pada Jadwal Photoshoot untuk memindahkan booking ke history.</div>
    <?php if (rfp_schema_ready() && empty($submissions)) { ?><div class="bg-white border shadow-sm p-4 text-center text-muted">Belum ada pengajuan pelunasan untuk lokasi ini.</div><?php } ?>
    <?php foreach ($submissions as $item) { $addons = rfp_submission_addons($item['id']); $financials = rfp_submission_financials($item); ?>
      <article class="payment-card bg-white border shadow-sm rounded p-4 mb-4">
        <div class="d-flex justify-content-between flex-wrap gap-2 mb-3"><div><h2 class="h5 fw-bold mb-1"><?php echo rfp_h($item['invoice_number']); ?></h2><span class="badge <?php echo rfp_status_badge_class($item['status']); ?>"><?php echo rfp_h(rfp_status_label($item['status'])); ?></span></div><div class="text-md-end small text-muted">Diajukan: <?php echo rfp_h($item['created_at']); ?><br>Admin: <?php echo rfp_h($item['reviewed_by'] !== '' ? $item['reviewed_by'] : '-'); ?></div></div>
        <div class="row"><div class="col-lg-8"><div class="row small"><div class="col-sm-6 mb-3"><strong>Pelanggan</strong><br><?php echo rfp_h($item['customer_name']); ?><br><?php echo rfp_h($item['customer_email']); ?><br><?php echo rfp_h($item['customer_phone']); ?></div><div class="col-sm-6 mb-3"><strong>Booking</strong><br><?php echo rfp_h(trim($item['package_name'] . ' ' . $item['package_type'])); ?><br><?php echo rfp_h($item['booking_date'] . ' | ' . $item['booking_time']); ?><br><?php echo rfp_h(rfp_studio_label($item['studio'])); ?></div></div>
          <table class="table table-sm small mb-2"><tbody><tr><td>Harga paket</td><td class="text-end"><?php echo rfp_format_rupiah($financials['package_total']); ?></td></tr><tr><td>DP telah dibayar</td><td class="text-end">- <?php echo rfp_format_rupiah($financials['deposit_paid']); ?></td></tr><tr><td>Sisa paket</td><td class="text-end"><?php echo rfp_format_rupiah($financials['base_due']); ?></td></tr><?php foreach ($addons as $addon) { ?><tr><td><?php echo rfp_h($addon['addon_name']); ?> x<?php echo (int)$addon['quantity']; ?></td><td class="text-end"><?php echo rfp_format_rupiah($addon['line_total']); ?></td></tr><?php } ?><tr class="fw-bold"><td>Total pelunasan</td><td class="text-end"><?php echo rfp_format_rupiah($financials['total_due']); ?></td></tr></tbody></table>
          <?php if ($item['admin_note'] !== '') { ?><p class="small mb-0"><strong>Catatan admin:</strong> <?php echo rfp_h($item['admin_note']); ?></p><?php } ?>
        </div><div class="col-lg-4 text-center"><a href="assets/img/data_pelunasan/<?php echo rawurlencode(basename($item['proof_filename'])); ?>" target="_blank" rel="noopener"><img class="proof border rounded img-fluid" src="assets/img/data_pelunasan/<?php echo rawurlencode(basename($item['proof_filename'])); ?>" alt="Bukti transfer"></a><p class="small text-muted mt-2 mb-0">Klik bukti untuk ukuran penuh</p></div></div>
        <div class="mt-3 d-flex flex-wrap gap-2"><a class="btn btn-outline-dark btn-sm" href="pelunasan-invoice?token=<?php echo urlencode($item['public_token']); ?>"><i class="fa-solid fa-file-pdf me-1"></i>Unduh Invoice</a><?php if ($item['status'] === 'pending') { ?><button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#review-<?php echo (int)$item['id']; ?>">Proses Verifikasi</button><?php } ?></div>
        <?php if ($item['status'] === 'pending') { ?><div class="collapse mt-3" id="review-<?php echo (int)$item['id']; ?>"><form method="post" action="pelunasan-admin" class="border rounded p-3 bg-light"><input type="hidden" name="csrf_token" value="<?php echo rfp_h(rfp_csrf_token()); ?>"><input type="hidden" name="submission_id" value="<?php echo (int)$item['id']; ?>"><label class="form-label small fw-bold" for="note-<?php echo (int)$item['id']; ?>">Catatan admin (wajib bila ditolak)</label><textarea class="form-control form-control-sm" id="note-<?php echo (int)$item['id']; ?>" name="admin_note" maxlength="500" rows="2" placeholder="Contoh: nominal belum sesuai"></textarea><div class="mt-3"><button class="btn btn-success btn-sm" type="submit" name="review_action" value="approve" onclick="return confirm('Setujui pembayaran ini? Invoice akan berubah menjadi LUNAS.')">Setujui &amp; Tandai Lunas</button><button class="btn btn-outline-danger btn-sm ms-2" type="submit" name="review_action" value="reject" onclick="return confirm('Tolak pengajuan ini?')">Tolak</button></div></form></div><?php } ?>
      </article>
    <?php } ?>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
