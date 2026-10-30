<?php
require 'function.php';
require 'pelunasan-lib.php';

rfp_start_session();
$databaseAvailable = rfp_database_available();
$schemaReady = rfp_schema_ready();
$step = 'lookup';
$lookupEmail = isset($_SESSION['rfp_lookup_email']) ? $_SESSION['rfp_lookup_email'] : '';
$bookings = array();
$selectedBooking = null;
$submission = null;
$error = '';
$warning = '';

function rfp_lookup_allowed(){
    rfp_start_session();
    $now = time();
    $attempts = isset($_SESSION['rfp_lookup_attempts']) && is_array($_SESSION['rfp_lookup_attempts']) ? $_SESSION['rfp_lookup_attempts'] : array();
    $attempts = array_filter($attempts, function ($timestamp) use ($now) { return $timestamp > ($now - 600); });
    if (count($attempts) >= 8) { $_SESSION['rfp_lookup_attempts'] = $attempts; return false; }
    $attempts[] = $now;
    $_SESSION['rfp_lookup_attempts'] = $attempts;
    return true;
}

if ($schemaReady && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!rfp_csrf_is_valid(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
        $error = 'Sesi formulir telah berakhir. Silakan muat ulang halaman dan coba kembali.';
    } else {
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        if ($action === 'lookup') {
            $lookupEmail = strtolower(trim(isset($_POST['email']) ? $_POST['email'] : ''));
            if (!filter_var($lookupEmail, FILTER_VALIDATE_EMAIL)) {
                $error = 'Masukkan alamat email yang valid.';
            } elseif (!rfp_lookup_allowed()) {
                $error = 'Terlalu banyak pencarian. Silakan coba lagi dalam beberapa menit.';
            } else {
                $_SESSION['rfp_lookup_email'] = $lookupEmail;
                $lookupDatabaseError = null;
                $lookupDatabaseWarning = null;
                $bookings = rfp_find_bookings_by_email($lookupEmail, $lookupDatabaseError, $lookupDatabaseWarning);
                if ($lookupDatabaseError !== null) {
                    // Detail MySQL hanya dicatat ke error log, jangan ditampilkan ke pelanggan.
                    $error = 'Pencarian sedang bermasalah saat membaca data booking. Silakan coba lagi atau hubungi admin.';
                } elseif ($lookupDatabaseWarning !== null) {
                    $warning = $lookupDatabaseWarning;
                }
                $step = 'results';
            }
        } elseif ($action === 'choose' && !empty($_SESSION['rfp_lookup_email'])) {
            $selectedBooking = rfp_get_booking(isset($_POST['booking_uniqid']) ? $_POST['booking_uniqid'] : '', isset($_POST['legacy_source']) ? $_POST['legacy_source'] : '', $_SESSION['rfp_lookup_email']);
            if (!$selectedBooking) { $error = 'Data booking tidak ditemukan atau email tidak sesuai.'; }
            else { $step = 'payment'; }
        } elseif ($action === 'submit_payment' && !empty($_SESSION['rfp_lookup_email'])) {
            $selectedBooking = rfp_get_booking(isset($_POST['booking_uniqid']) ? $_POST['booking_uniqid'] : '', isset($_POST['legacy_source']) ? $_POST['legacy_source'] : '', $_SESSION['rfp_lookup_email']);
            if (!$selectedBooking) {
                $error = 'Data booking tidak ditemukan atau sesi Anda telah berakhir.';
            } else {
                $result = rfp_create_submission($selectedBooking, isset($_POST['addons']) ? $_POST['addons'] : array(), isset($_FILES['proof']) ? $_FILES['proof'] : array());
                if ($result['ok']) {
                    rfp_flash('success', 'Bukti transfer telah diterima. Invoice dibuat dengan status Menunggu Verifikasi Admin.');
                    header('Location: pelunasan.php?invoice=' . urlencode($result['token']));
                    exit;
                }
                $error = $result['error'];
                $step = 'payment';
            }
        }
    }
}
if ($schemaReady && isset($_GET['invoice'])) {
    $submission = rfp_get_submission_by_token(trim($_GET['invoice']));
    if ($submission) { $step = 'invoice'; } else { $error = 'Invoice tidak ditemukan.'; }
}
$flash = rfp_take_flash();
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
  <style>body{font-family:'Josefin Sans',sans-serif;background:#fafafa}.payment-wrap{max-width:800px;margin:0 auto 70px}.payment-title{background:#212529;color:#fff;display:inline-block;padding:13px 26px}.booking-card{border-left:4px solid #212529}.addon-row{border-bottom:1px solid #ececec;padding:12px 0}.amount-box{background:#f8f9fa;border:1px solid #dee2e6}.muted-note{font-size:.9rem;color:#6c757d}</style>
</head>
<body>
  <nav class="navbar navbar-light border-bottom bg-white py-3"><div class="container d-flex justify-content-center"><a href="index.php" class="text-dark text-decoration-none"><img src="img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Foto Studio" width="70"><span class="navbar-light h2 ms-3 align-middle fw-bold">Rizal Foto Studio</span></a></div></nav>
  <main class="container payment-wrap">
    <nav aria-label="breadcrumb"><ol class="breadcrumb mt-5"><li class="breadcrumb-item"><a href="index.php" class="text-dark">Home</a></li><li class="breadcrumb-item active">Pelunasan</li></ol></nav>
    <div class="payment-title mb-4"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Pelunasan Booking</div>
    <?php if (!$databaseAvailable) { ?><div class="alert alert-danger">Koneksi database belum tersedia. Periksa apakah layanan MySQL aktif dan konfigurasi koneksi pada <code>function.php</code> sudah sesuai.</div><?php } elseif (!$schemaReady) { ?><div class="alert alert-warning">Modul belum dapat digunakan karena tabel pembayaran belum disiapkan. Admin perlu menjalankan <code>migration-pelunasan-email.sql</code> terlebih dahulu.</div><?php } ?>
    <?php if ($flash) { ?><div class="alert alert-<?php echo rfp_h($flash['type']); ?> alert-dismissible fade show" role="alert"><?php echo rfp_h($flash['message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div><?php } ?>
    <?php if ($error !== '') { ?><div class="alert alert-danger" role="alert"><?php echo rfp_h($error); ?></div><?php } ?>
    <?php if ($warning !== '') { ?><div class="alert alert-warning" role="alert"><?php echo rfp_h($warning); ?></div><?php } ?>

    <?php if ($schemaReady && $step === 'lookup') { ?>
      <section class="bg-white border shadow-sm p-4 p-md-5"><h3 class="fw-bold text-center">Temukan Booking Anda</h3><p class="text-center text-muted">Masukkan email yang digunakan saat booking. Tidak perlu membuat akun.</p>
        <form method="post" action="pelunasan.php" class="mx-auto" style="max-width:520px"><input type="hidden" name="csrf_token" value="<?php echo rfp_h(rfp_csrf_token()); ?>"><input type="hidden" name="action" value="lookup"><label for="email" class="form-label fw-bold">Email booking</label><input type="email" class="form-control form-control-lg" id="email" name="email" value="<?php echo rfp_h($lookupEmail); ?>" autocomplete="email" required><p class="muted-note mt-2 mb-4">Pencarian membaca booking baru dan booking aktif. Tidak perlu menunggu konfirmasi admin.</p><div class="text-center"><button class="btn btn-dark px-5" type="submit">Cari Booking</button></div></form>
      </section>
    <?php } ?>

    <?php if ($schemaReady && $step === 'results') { ?>
      <section class="bg-white border shadow-sm p-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h3 class="fw-bold mb-1">Pilih Booking</h3><p class="text-muted mb-0">Email: <?php echo rfp_h($lookupEmail); ?></p></div><a href="pelunasan.php" class="btn btn-outline-secondary btn-sm">Ganti Email</a></div>
      <?php if (empty($bookings)) { ?>
        <div class="alert alert-light border mb-0"><?php echo $error !== '' ? 'Hasil tidak dapat ditampilkan karena pembacaan database gagal. Silakan coba kembali setelah masalah database diperiksa.' : 'Booking dengan email tersebut belum ditemukan pada booking baru maupun booking aktif. Pastikan email sama seperti saat booking atau hubungi admin.'; ?></div>
      <?php } else { foreach ($bookings as $booking) { ?>
        <div class="booking-card border rounded p-3 mb-3"><div class="row align-items-center"><div class="col-md-8"><h5 class="fw-bold mb-1"><?php echo rfp_h(trim($booking['package'] . ' ' . $booking['tipe_package'])); ?></h5><div class="small text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo rfp_h($booking['tanggal']); ?> &nbsp; <i class="fa-regular fa-clock me-1"></i><?php echo rfp_h($booking['jam']); ?></div><div class="small text-muted mt-1"><i class="fa-solid fa-location-dot me-1"></i><?php echo rfp_h(rfp_studio_label($booking['studio'])); ?></div>
          <?php if ($booking['payment_state']) { ?><span class="badge <?php echo rfp_status_badge_class($booking['payment_state']['status']); ?> mt-2"><?php echo rfp_h(rfp_status_label($booking['payment_state']['status'])); ?></span><?php } elseif ($booking['legacy_source'] === 'konfirmasi') { ?><span class="badge bg-secondary mt-2">Booking baru</span><?php } else { ?><span class="badge bg-info text-dark mt-2">Booking aktif</span><?php } ?>
        </div><div class="col-md-4 text-md-end mt-3 mt-md-0"><div class="fw-bold mb-2"><?php echo rfp_format_rupiah($booking['remaining_due']); ?></div>
          <?php if ($booking['payment_state'] && $booking['payment_state']['status'] !== 'rejected') { ?><a class="btn btn-outline-dark btn-sm" href="pelunasan.php?invoice=<?php echo urlencode($booking['payment_state']['public_token']); ?>">Lihat Invoice</a><?php } elseif (empty($booking['payment_ready'])) { ?><button class="btn btn-outline-secondary btn-sm" disabled>Sudah selesai</button><?php } else { ?><form method="post" action="pelunasan.php"><input type="hidden" name="csrf_token" value="<?php echo rfp_h(rfp_csrf_token()); ?>"><input type="hidden" name="action" value="choose"><input type="hidden" name="booking_uniqid" value="<?php echo rfp_h($booking['uniqid']); ?>"><input type="hidden" name="legacy_source" value="<?php echo rfp_h($booking['legacy_source']); ?>"><button class="btn btn-dark btn-sm" type="submit">Pilih Booking</button></form><?php } ?>
        </div></div></div>
      <?php } } ?></section>
    <?php } ?>

    <?php if ($schemaReady && $step === 'payment' && $selectedBooking) { $catalog = rfp_addon_catalog($selectedBooking); ?>
      <section class="bg-white border shadow-sm p-4"><div class="d-flex justify-content-between align-items-start mb-3"><div><h3 class="fw-bold mb-1">Ajukan Pelunasan</h3><p class="text-muted mb-0">Periksa kembali detail booking sebelum mengirim.</p></div><form method="post" action="pelunasan.php"><input type="hidden" name="csrf_token" value="<?php echo rfp_h(rfp_csrf_token()); ?>"><input type="hidden" name="action" value="lookup"><input type="hidden" name="email" value="<?php echo rfp_h($lookupEmail); ?>"><button class="btn btn-outline-secondary btn-sm" type="submit">Kembali</button></form></div>
        <div class="amount-box rounded p-3 mb-4"><div class="row small"><div class="col-sm-6 mb-2"><strong>Paket:</strong><br><?php echo rfp_h(trim($selectedBooking['package'] . ' ' . $selectedBooking['tipe_package'])); ?></div><div class="col-sm-6 mb-2"><strong>Jadwal:</strong><br><?php echo rfp_h($selectedBooking['tanggal'] . ' | ' . $selectedBooking['jam']); ?></div><div class="col-sm-6"><strong>Studio:</strong><br><?php echo rfp_h(rfp_studio_label($selectedBooking['studio'])); ?></div><div class="col-sm-6"><strong>Sisa paket:</strong><br><?php echo rfp_format_rupiah($selectedBooking['remaining_due']); ?></div></div></div>
        <?php if ($selectedBooking['payment_state'] && $selectedBooking['payment_state']['status'] !== 'rejected') { ?><div class="alert alert-warning mb-0">Booking ini sudah memiliki pengajuan pembayaran <?php echo strtolower(rfp_status_label($selectedBooking['payment_state']['status'])); ?>.</div><?php } else { ?>
          <form method="post" action="pelunasan.php" enctype="multipart/form-data" id="payment-form"><input type="hidden" name="csrf_token" value="<?php echo rfp_h(rfp_csrf_token()); ?>"><input type="hidden" name="action" value="submit_payment"><input type="hidden" name="booking_uniqid" value="<?php echo rfp_h($selectedBooking['uniqid']); ?>"><input type="hidden" name="legacy_source" value="<?php echo rfp_h($selectedBooking['legacy_source']); ?>"><h5 class="fw-bold">Tambahan cetak foto <small class="text-muted fw-normal">(opsional)</small></h5><p class="muted-note">Harga dihitung otomatis oleh sistem. Silakan isi jumlah hanya bila ada tambahan.</p>
          <?php foreach ($catalog as $code => $addon) { ?><div class="addon-row row align-items-center"><div class="col-8"><strong><?php echo rfp_h($addon['name']); ?></strong><br><span class="text-muted small"><?php echo rfp_format_rupiah($addon['price']); ?> / item</span></div><div class="col-4"><input class="form-control text-center addon-quantity" type="number" min="0" max="25" value="0" name="addons[<?php echo rfp_h($code); ?>]" data-price="<?php echo (int)$addon['price']; ?>" aria-label="Jumlah <?php echo rfp_h($addon['name']); ?>"></div></div><?php } ?>
          <div class="amount-box rounded p-3 mt-4"><div class="d-flex justify-content-between"><span>Sisa paket</span><strong><?php echo rfp_format_rupiah($selectedBooking['remaining_due']); ?></strong></div><div class="d-flex justify-content-between mt-2"><span>Tambahan</span><strong id="addon-total">Rp 0</strong></div><hr><div class="d-flex justify-content-between fs-5"><span>Total pelunasan</span><strong id="grand-total" data-base="<?php echo (int)$selectedBooking['remaining_due']; ?>"><?php echo rfp_format_rupiah($selectedBooking['remaining_due']); ?></strong></div></div>
          <div class="mt-4"><label for="proof" class="form-label fw-bold">Bukti transfer</label><input class="form-control" type="file" id="proof" name="proof" accept="image/jpeg,image/png,image/webp" required><p class="muted-note mt-2">Format JPG, PNG, atau WEBP. Maksimal 5 MB. Pengajuan akan diperiksa admin sebelum dinyatakan lunas.</p></div><div class="text-center mt-4"><button class="btn btn-dark px-5" type="submit">Kirim Pengajuan &amp; Buat Invoice</button></div></form>
        <?php } ?>
      </section>
    <?php } ?>

    <?php if ($schemaReady && $step === 'invoice' && $submission) { ?><section class="bg-white border shadow-sm p-4 p-md-5 text-center"><span class="badge <?php echo rfp_status_badge_class($submission['status']); ?> mb-3"><?php echo rfp_h(rfp_status_label($submission['status'])); ?></span><h3 class="fw-bold">Invoice <?php echo rfp_h($submission['invoice_number']); ?></h3><p class="text-muted">Total pengajuan: <strong><?php echo rfp_format_rupiah($submission['total_due']); ?></strong></p><?php if ($submission['status'] === 'pending') { ?><p>Bukti transfer Anda sudah diterima. Admin akan memverifikasinya terlebih dahulu.</p><?php } ?><?php if ($submission['status'] === 'rejected') { ?><p>Pengajuan ditolak. <?php echo rfp_h($submission['admin_note']); ?></p><a class="btn btn-dark" href="pelunasan.php">Ajukan Ulang</a><?php } else { ?><a class="btn btn-dark" href="pelunasan-invoice.php?token=<?php echo urlencode($submission['public_token']); ?>"><i class="fa-solid fa-download me-2"></i>Download PDF Invoice</a><?php } ?></section><?php } ?>
  </main>
  <footer class="bg-dark text-white py-4"><div class="container text-center small">Copyright &copy; <span id="copyright-year"></span> Rizal Foto Studio</div></footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script>document.getElementById('copyright-year').textContent=new Date().getFullYear();(function(){var inputs=document.querySelectorAll('.addon-quantity'),baseElement=document.getElementById('grand-total');if(!baseElement)return;var base=parseInt(baseElement.getAttribute('data-base'),10)||0;function rupiah(value){return 'Rp '+value.toLocaleString('id-ID')}function updateTotal(){var addons=0;for(var i=0;i<inputs.length;i++){var quantity=Math.max(0,parseInt(inputs[i].value,10)||0);addons+=quantity*(parseInt(inputs[i].getAttribute('data-price'),10)||0)}document.getElementById('addon-total').textContent=rupiah(addons);baseElement.textContent=rupiah(base+addons)}for(var i=0;i<inputs.length;i++)inputs[i].addEventListener('input',updateTotal)}());</script>
</body>
</html>
