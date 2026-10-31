<?php
require 'function.php';
require 'pelunasan-lib.php';

$databaseAvailable = rfp_database_available();
$schemaReady = rfp_schema_ready();
$step = 'lookup';
$lookupEmail = '';
$bookings = array();
$selectedBooking = null;
$submission = null;
$error = '';
$warning = '';

if ($databaseAvailable && isset($_GET['email'])) {
    $lookupEmail = strtolower(trim($_GET['email']));
    if (!filter_var($lookupEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Masukkan alamat email yang valid.';
    } elseif (isset($_GET['booking_uniqid'], $_GET['legacy_source'])) {
        $selectedBooking = rfp_get_booking($_GET['booking_uniqid'], $_GET['legacy_source'], $lookupEmail);
        if (!$selectedBooking) {
            $error = 'Data booking tidak ditemukan atau email tidak sesuai.';
        } elseif (empty($selectedBooking['payment_ready'])) {
            $error = 'DP booking ini belum dikonfirmasi admin. Pelunasan baru dapat diajukan setelah booking masuk ke Jadwal Photoshoot.';
            $bookings = rfp_find_bookings_by_email($lookupEmail);
            $step = 'results';
        } elseif (!$schemaReady) {
            $error = 'Daftar booking ditemukan, tetapi tabel modul pembayaran belum disiapkan.';
        } else {
            $step = 'payment';
        }
    } else {
        $lookupDatabaseError = null;
        $lookupDatabaseWarning = null;
        $bookings = rfp_find_bookings_by_email($lookupEmail, $lookupDatabaseError, $lookupDatabaseWarning);
        if ($lookupDatabaseError !== null) {
            $error = 'Pencarian tidak dapat membaca tabel booking aktif. Periksa koneksi dan hak akses database.';
        } elseif ($lookupDatabaseWarning !== null) {
            $warning = $lookupDatabaseWarning;
        }
        $step = 'results';
    }
}
if ($databaseAvailable && $schemaReady && isset($_GET['invoice'])) {
    $submission = rfp_get_submission_by_token(trim($_GET['invoice']));
    if ($submission) { $step = 'invoice'; } else { $error = 'Invoice tidak ditemukan.'; }
}
$paymentErrors = array(
    'method' => 'Metode pengiriman form tidak valid. Silakan buka kembali booking dan coba ulang.',
    'upload_too_large' => 'Data upload ditolak oleh konfigurasi PHP. Gunakan foto maksimal 5 MB.',
    'invalid_form' => 'Data pengajuan tidak diterima lengkap. Silakan isi ulang form pelunasan.',
    'database' => 'Koneksi database tidak tersedia. Pengajuan belum disimpan.',
    'schema' => 'Tabel modul pembayaran belum disiapkan atau migration belum lengkap.',
    'email' => 'Email pada pengajuan tidak valid.',
    'booking' => 'Booking tidak ditemukan lagi atau email tidak sesuai.',
    'origin' => 'Pengajuan hanya dapat dikirim dari halaman pelunasan RF Studio.',
    'proof_required' => 'Bukti transfer wajib dipilih.',
    'proof_too_large' => 'Ukuran bukti transfer maksimal 5 MB.',
    'proof_invalid_type' => 'Format bukti transfer harus JPG, PNG, atau WEBP.',
    'proof_upload_failed' => 'Upload bukti transfer tidak selesai. Silakan pilih foto lagi.',
    'proof_store_failed' => 'Bukti transfer gagal disimpan. Periksa izin folder assets/img/data_pelunasan.',
    'existing_submission' => 'Booking ini sudah memiliki pengajuan pembayaran yang masih aktif.',
    'booking_not_ready' => 'Booking ini tidak dapat diajukan untuk pelunasan.',
    'invalid_total' => 'Total pelunasan tidak valid. Silakan hubungi admin.',
    'db_not_ready' => 'Database pelunasan belum siap. Jalankan migration terlebih dahulu.',
    'insert_failed' => 'Pengajuan gagal masuk database. Pastikan ALTER legacy_source sudah dijalankan.',
    'addon_failed' => 'Data tambahan pembayaran gagal disimpan.',
    'submit_failed' => 'Pengajuan pembayaran gagal diproses. Silakan coba kembali.'
);
if (isset($_GET['payment_error']) && isset($paymentErrors[$_GET['payment_error']])) {
    $error = $paymentErrors[$_GET['payment_error']];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pelunasan - Rizal Foto Studio</title>
  <link href="img/logo.png" rel="icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
  <style>body{font-family:'Josefin Sans',sans-serif;background:#fafafa}.payment-wrap{max-width:800px;margin:0 auto 70px}.payment-title{background:#212529;color:#fff;display:inline-block;padding:13px 26px}.booking-card{border-left:4px solid #212529}.booking-card.is-locked{border-left-color:#ffc107;background:#fffdf5}.addon-row{border-bottom:1px solid #ececec;padding:12px 0}.amount-box{background:#f8f9fa;border:1px solid #dee2e6}.muted-note{font-size:.9rem;color:#6c757d}</style>
</head>
<body>
  <nav class="navbar navbar-light border-bottom bg-white py-3"><div class="container d-flex justify-content-center"><a href="index.php" class="text-dark text-decoration-none"><img src="img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Foto Studio" width="70"><span class="navbar-light h2 ms-3 align-middle fw-bold">Rizal Foto Studio</span></a></div></nav>
  <main class="container payment-wrap">
    <nav aria-label="breadcrumb"><ol class="breadcrumb mt-5"><li class="breadcrumb-item"><a href="index.php" class="text-dark">Home</a></li><li class="breadcrumb-item active">Pelunasan</li></ol></nav>
    <div class="payment-title mb-4"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Pelunasan Booking</div>
    <?php if (!$databaseAvailable) { ?><div class="alert alert-danger">Koneksi database belum tersedia. Periksa apakah layanan MySQL aktif dan konfigurasi koneksi pada <code>function.php</code> sudah sesuai.</div><?php } elseif (!$schemaReady) { ?><div class="alert alert-warning">Modul belum dapat digunakan karena tabel pembayaran belum disiapkan. Admin perlu menjalankan <code>migration-pelunasan-email.sql</code> terlebih dahulu.</div><?php } ?>
    <?php if (isset($_GET['submitted']) && $submission) { ?><div class="alert alert-success" role="alert">Bukti transfer telah diterima. Invoice dibuat dengan status Menunggu Verifikasi Admin.</div><?php } ?>
    <?php if ($error !== '') { ?><div class="alert alert-danger" role="alert"><?php echo rfp_h($error); ?></div><?php } ?>
    <?php if ($warning !== '') { ?><div class="alert alert-warning" role="alert"><?php echo rfp_h($warning); ?></div><?php } ?>

    <?php if ($databaseAvailable && $step === 'lookup') { ?>
      <section class="bg-white border shadow-sm p-4 p-md-5"><h3 class="fw-bold text-center">Temukan Booking Anda</h3><p class="text-center text-muted">Masukkan email yang digunakan saat booking. Tidak perlu membuat akun.</p>
        <form method="get" action="pelunasan" class="mx-auto" style="max-width:520px"><label for="email" class="form-label fw-bold">Email booking</label><input type="email" class="form-control form-control-lg" id="email" name="email" value="<?php echo rfp_h($lookupEmail); ?>" autocomplete="email" required><p class="muted-note mt-2 mb-4">Booking yang masih menunggu verifikasi DP tetap ditampilkan, tetapi baru dapat dilunasi setelah dikonfirmasi admin.</p><div class="text-center"><button class="btn btn-dark px-5" type="submit">Cari Booking</button></div></form>
      </section>
    <?php } ?>

    <?php if ($databaseAvailable && $step === 'results') { ?>
      <section class="bg-white border shadow-sm p-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h3 class="fw-bold mb-1">Pilih Booking</h3><p class="text-muted mb-0">Email: <?php echo rfp_h($lookupEmail); ?></p></div><a href="pelunasan" class="btn btn-outline-secondary btn-sm">Ganti Email</a></div>
      <?php if (empty($bookings)) { ?>
        <div class="alert alert-light border mb-0"><?php echo $error !== '' ? 'Hasil tidak dapat ditampilkan karena pembacaan database gagal. Silakan coba kembali setelah masalah database diperiksa.' : 'Booking dengan email tersebut belum ditemukan pada booking baru maupun booking aktif. Pastikan email sama seperti saat booking atau hubungi admin.'; ?></div>
      <?php } else { foreach ($bookings as $booking) { ?>
        <div class="booking-card <?php echo empty($booking['payment_ready']) ? 'is-locked' : ''; ?> border rounded p-3 mb-3"><div class="row align-items-center"><div class="col-md-8"><h5 class="fw-bold mb-1"><?php echo rfp_h(trim($booking['package'] . ' ' . $booking['tipe_package'])); ?></h5><div class="small text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo rfp_h($booking['tanggal']); ?> &nbsp; <i class="fa-regular fa-clock me-1"></i><?php echo rfp_h($booking['jam']); ?></div><div class="small text-muted mt-1"><i class="fa-solid fa-location-dot me-1"></i><?php echo rfp_h(rfp_studio_label($booking['studio'])); ?></div>
          <?php if (empty($booking['payment_ready'])) { ?><span class="badge bg-warning text-dark mt-2"><i class="fa-solid fa-lock me-1"></i>DP belum dikonfirmasi admin</span><?php } elseif ($booking['payment_state']) { ?><span class="badge <?php echo rfp_status_badge_class($booking['payment_state']['status']); ?> mt-2"><?php echo rfp_h(rfp_status_label($booking['payment_state']['status'])); ?></span><?php } else { ?><span class="badge bg-info text-dark mt-2">Booking aktif - dapat dilunasi</span><?php } ?>
        </div><div class="col-md-4 text-md-end mt-3 mt-md-0"><div class="fw-bold mb-2"><?php echo rfp_format_rupiah($booking['remaining_due']); ?></div>
          <?php if (!$schemaReady) { ?><button class="btn btn-outline-secondary btn-sm" disabled>Modul pembayaran belum siap</button><?php } elseif (empty($booking['payment_ready'])) { ?><button class="btn btn-outline-secondary btn-sm" type="button" disabled><i class="fa-solid fa-lock me-1"></i>Belum dapat dilunasi</button><?php } elseif ($booking['payment_state'] && $booking['payment_state']['status'] !== 'rejected') { ?><a class="btn btn-outline-dark btn-sm" href="pelunasan?invoice=<?php echo urlencode($booking['payment_state']['public_token']); ?>">Lihat Invoice</a><?php } else { ?><a class="btn btn-dark btn-sm" href="pelunasan?email=<?php echo urlencode($lookupEmail); ?>&amp;booking_uniqid=<?php echo urlencode($booking['uniqid']); ?>&amp;legacy_source=<?php echo urlencode($booking['legacy_source']); ?>">Pilih Booking</a><?php } ?>
        </div></div></div>
      <?php } } ?></section>
    <?php } ?>

    <?php if ($databaseAvailable && $schemaReady && $step === 'payment' && $selectedBooking) { $catalog = rfp_addon_catalog($selectedBooking); ?>
      <section class="bg-white border shadow-sm p-4"><div class="d-flex justify-content-between align-items-start mb-3"><div><h3 class="fw-bold mb-1">Ajukan Pelunasan</h3><p class="text-muted mb-0">Periksa kembali detail booking sebelum mengirim.</p></div><a class="btn btn-outline-secondary btn-sm" href="pelunasan?email=<?php echo urlencode($lookupEmail); ?>">Kembali</a></div>
        <?php $initialBreakdown = rfp_booking_initial_breakdown($selectedBooking); $packageTotal = $initialBreakdown['package_total']; $depositPaid = max(0, $packageTotal - $selectedBooking['remaining_due']); ?>
        <div class="amount-box rounded p-3 mb-4">
          <div class="row small mb-3"><div class="col-sm-6 mb-2"><strong>Paket:</strong><br><?php echo rfp_h(trim($selectedBooking['package'] . ' ' . $selectedBooking['tipe_package'])); ?></div><div class="col-sm-6 mb-2"><strong>Jadwal:</strong><br><?php echo rfp_h($selectedBooking['tanggal'] . ' | ' . $selectedBooking['jam']); ?></div><div class="col-sm-6"><strong>Studio:</strong><br><?php echo rfp_h(rfp_studio_label($selectedBooking['studio'])); ?></div><div class="col-sm-6"><strong>Total booking:</strong><br><?php echo rfp_format_rupiah($packageTotal); ?></div></div>
          <div class="bg-white border rounded p-3">
            <div class="d-flex justify-content-between small"><span>Harga paket awal</span><strong><?php echo rfp_format_rupiah($initialBreakdown['package_base']); ?></strong></div>
            <?php foreach ($initialBreakdown['items'] as $initialItem) { ?><div class="d-flex justify-content-between align-items-start small mt-2"><span><?php echo rfp_h($initialItem['name']); ?> x<?php echo (int)$initialItem['quantity']; ?><br><span class="badge bg-secondary">Diajukan saat booking</span></span><strong><?php echo rfp_format_rupiah($initialItem['line_total']); ?></strong></div><?php } ?>
            <?php if (empty($initialBreakdown['items']) && $initialBreakdown['raw_additions'] !== '' && $initialBreakdown['raw_additions'] !== '-') { ?><div class="small text-muted mt-2"><span class="badge bg-secondary me-1">Diajukan saat booking</span><?php echo rfp_h($initialBreakdown['raw_additions']); ?></div><?php } ?>
            <hr class="my-2"><div class="d-flex justify-content-between small"><span>Total harga booking</span><strong><?php echo rfp_format_rupiah($packageTotal); ?></strong></div><div class="d-flex justify-content-between small mt-2"><span>DP telah dibayar</span><strong>- <?php echo rfp_format_rupiah($depositPaid); ?></strong></div><div class="d-flex justify-content-between mt-2"><span>Sisa paket</span><strong><?php echo rfp_format_rupiah($selectedBooking['remaining_due']); ?></strong></div>
          </div>
        </div>
        <?php if ($selectedBooking['payment_state'] && $selectedBooking['payment_state']['status'] !== 'rejected') { ?><div class="alert alert-warning mb-0">Booking ini sudah memiliki pengajuan pembayaran <?php echo strtolower(rfp_status_label($selectedBooking['payment_state']['status'])); ?>.</div><?php } else { ?>
          <?php if ($selectedBooking['payment_state'] && $selectedBooking['payment_state']['status'] === 'rejected') { ?><div class="alert alert-danger"><strong>Pengajuan sebelumnya ditolak.</strong><br><?php echo rfp_h($selectedBooking['payment_state']['admin_note']); ?><br><span class="small">Silakan perbaiki tambahan atau bukti transfer, lalu kirim ulang formulir di bawah ini.</span></div><?php } ?>
          <form method="post" action="pelunasan-submit" enctype="multipart/form-data" id="payment-form"><input type="hidden" name="action" value="submit_payment"><input type="hidden" name="email" value="<?php echo rfp_h($lookupEmail); ?>"><input type="hidden" name="booking_uniqid" value="<?php echo rfp_h($selectedBooking['uniqid']); ?>"><input type="hidden" name="legacy_source" value="<?php echo rfp_h($selectedBooking['legacy_source']); ?>"><h5 class="fw-bold">Tambahan saat pelunasan <small class="text-muted fw-normal">(opsional)</small></h5><p class="muted-note">Bagian ini hanya untuk tambahan baru yang diajukan saat pelunasan. Tambahan saat booking sudah tercantum pada rincian di atas.</p>
          <?php foreach ($catalog as $code => $addon) { ?><div class="addon-row row align-items-center"><div class="col-8"><strong><?php echo rfp_h($addon['name']); ?></strong><br><span class="text-muted small"><?php echo rfp_format_rupiah($addon['price']); ?> / item</span></div><div class="col-4"><input class="form-control text-center addon-quantity" type="number" min="0" max="25" value="0" name="addons[<?php echo rfp_h($code); ?>]" data-price="<?php echo (int)$addon['price']; ?>" aria-label="Jumlah <?php echo rfp_h($addon['name']); ?>"></div></div><?php } ?>
          <div class="amount-box rounded p-3 mt-4"><div class="d-flex justify-content-between"><span>Sisa paket</span><strong><?php echo rfp_format_rupiah($selectedBooking['remaining_due']); ?></strong></div><div class="d-flex justify-content-between mt-2"><span>Tambahan saat pelunasan</span><strong id="addon-total">Rp 0</strong></div><hr><div class="d-flex justify-content-between fs-5"><span>Total pelunasan</span><strong id="grand-total" data-base="<?php echo (int)$selectedBooking['remaining_due']; ?>"><?php echo rfp_format_rupiah($selectedBooking['remaining_due']); ?></strong></div></div>
          <div class="mt-4"><label for="proof" class="form-label fw-bold">Bukti transfer</label><input class="form-control" type="file" id="proof" name="proof" accept="image/jpeg,image/png,image/webp" required><p class="muted-note mt-2">Format JPG, PNG, atau WEBP. Maksimal 5 MB. Pengajuan akan diperiksa admin sebelum dinyatakan lunas.</p></div><div class="text-center mt-4"><button class="btn btn-dark px-5" type="submit">Kirim Pengajuan &amp; Buat Invoice</button></div></form>
        <?php } ?>
      </section>
    <?php } ?>

    <?php if ($databaseAvailable && $schemaReady && $step === 'invoice' && $submission) { ?><section class="bg-white border shadow-sm p-4 p-md-5 text-center"><span class="badge <?php echo rfp_status_badge_class($submission['status']); ?> mb-3"><?php echo rfp_h(rfp_status_label($submission['status'])); ?></span><h3 class="fw-bold">Invoice <?php echo rfp_h($submission['invoice_number']); ?></h3><p class="text-muted">Total pengajuan: <strong><?php echo rfp_format_rupiah($submission['total_due']); ?></strong></p><?php if ($submission['status'] === 'pending') { ?><p>Bukti transfer Anda sudah diterima. Admin akan memverifikasinya terlebih dahulu.</p><?php } ?><?php if ($submission['status'] === 'rejected') { ?><p>Pengajuan ditolak. <?php echo rfp_h($submission['admin_note']); ?></p><a class="btn btn-dark" href="pelunasan?email=<?php echo urlencode($submission['customer_email']); ?>&amp;booking_uniqid=<?php echo urlencode($submission['booking_uniqid']); ?>&amp;legacy_source=booking"><i class="fa-solid fa-rotate-right me-2"></i>Perbaiki &amp; Ajukan Ulang</a><?php } else { ?><a class="btn btn-dark" href="pelunasan-invoice?token=<?php echo urlencode($submission['public_token']); ?>"><i class="fa-solid fa-download me-2"></i>Download PDF Invoice</a><?php } ?></section><?php } ?>
  </main>
  <footer class="bg-dark text-white py-4"><div class="container text-center small">Copyright &copy; <span id="copyright-year"></span> Rizal Foto Studio</div></footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script>document.getElementById('copyright-year').textContent=new Date().getFullYear();(function(){var inputs=document.querySelectorAll('.addon-quantity'),baseElement=document.getElementById('grand-total');if(baseElement){var base=parseInt(baseElement.getAttribute('data-base'),10)||0;function rupiah(value){return 'Rp '+value.toLocaleString('id-ID')}function updateTotal(){var addons=0;for(var i=0;i<inputs.length;i++){var quantity=Math.max(0,parseInt(inputs[i].value,10)||0);addons+=quantity*(parseInt(inputs[i].getAttribute('data-price'),10)||0)}document.getElementById('addon-total').textContent=rupiah(addons);baseElement.textContent=rupiah(base+addons)}for(var i=0;i<inputs.length;i++)inputs[i].addEventListener('input',updateTotal)}var paymentForm=document.getElementById('payment-form'),proof=document.getElementById('proof');if(paymentForm&&proof){paymentForm.addEventListener('submit',function(event){if(proof.files&&proof.files[0]&&proof.files[0].size>5*1024*1024){event.preventDefault();alert('Ukuran bukti transfer maksimal 5 MB. Silakan pilih foto yang lebih kecil.');}});}}());</script>
</body>
</html>
