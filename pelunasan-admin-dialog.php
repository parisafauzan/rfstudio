<?php
// Partial untuk kolom Pelunasan pada Jadwal Photoshoot.
// Variabel yang tersedia: $payment, $d, dan opsional $adminPaymentFormAction.
$adminPaymentFormAction = isset($adminPaymentFormAction) ? $adminPaymentFormAction : 'databooking';
?>
<?php if (!$payment) { ?>
  <span class="badge bg-secondary">Belum diajukan</span><br>
  <small class="text-muted">Sisa: <?php echo rfp_format_rupiah(rfp_booking_is_due($d)); ?></small>
<?php } else {
  $paymentAddons = rfp_submission_addons($payment['id']);
  $paymentFinancials = rfp_submission_financials($payment);
  $initialBreakdown = rfp_booking_initial_breakdown($d);
  $modalId = 'payment-review-' . (int)$payment['id'];
?>
  <span class="badge <?php echo rfp_status_badge_class($payment['status']); ?>"><?php echo rfp_h(rfp_status_label($payment['status'])); ?></span><br>
  <strong><?php echo rfp_format_rupiah($paymentFinancials['total_due']); ?></strong>
  <?php if (!empty($paymentAddons)) { ?><div class="small text-muted mt-1"><?php foreach ($paymentAddons as $addon) { echo rfp_h($addon['addon_name']) . ' x' . (int)$addon['quantity'] . '<br>'; } ?></div><?php } ?>
  <a href="../assets/img/data_pelunasan/<?php echo rawurlencode(basename($payment['proof_filename'])); ?>" target="_blank" rel="noopener"><img class="gallery-item mt-2" aria-label="Bukti pelunasan" src="../assets/img/data_pelunasan/<?php echo rawurlencode(basename($payment['proof_filename'])); ?>" width="80" height="80"></a><br>
  <a class="btn btn-outline-dark btn-sm mt-2" href="../pelunasan-invoice?token=<?php echo urlencode($payment['public_token']); ?>">Invoice</a>
  <?php if ($payment['status'] === 'pending') { ?><button class="btn btn-success btn-sm mt-2" type="button" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">Verifikasi</button><?php } ?>

  <?php if ($payment['status'] === 'pending') { ?>
  <div class="modal fade text-start" id="<?php echo $modalId; ?>" tabindex="-1" aria-labelledby="<?php echo $modalId; ?>-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <div><h5 class="modal-title fw-bold" id="<?php echo $modalId; ?>-title">Verifikasi Pelunasan</h5><div class="small text-muted"><?php echo rfp_h($payment['invoice_number']); ?></div></div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-md-7">
              <div class="row small mb-3"><div class="col-6"><strong>Customer</strong><br><?php echo rfp_h($payment['customer_name']); ?><br><?php echo rfp_h($payment['customer_email']); ?></div><div class="col-6"><strong>Booking</strong><br><?php echo rfp_h(trim($payment['package_name'] . ' ' . $payment['package_type'])); ?><br><?php echo rfp_h($payment['booking_date'] . ' | ' . $payment['booking_time']); ?></div></div>
              <div class="border rounded p-3 bg-light small">
                <div class="d-flex justify-content-between"><span>Harga paket awal</span><strong><?php echo rfp_format_rupiah($initialBreakdown['package_base']); ?></strong></div>
                <?php foreach ($initialBreakdown['items'] as $initialItem) { ?><div class="d-flex justify-content-between mt-2"><span><?php echo rfp_h($initialItem['name']); ?> x<?php echo (int)$initialItem['quantity']; ?><br><span class="badge bg-secondary">Saat booking</span></span><strong><?php echo rfp_format_rupiah($initialItem['line_total']); ?></strong></div><?php } ?>
                <div class="d-flex justify-content-between mt-2"><span>DP telah dibayar</span><strong>- <?php echo rfp_format_rupiah($paymentFinancials['deposit_paid']); ?></strong></div>
                <div class="d-flex justify-content-between mt-2"><span>Sisa paket</span><strong><?php echo rfp_format_rupiah($paymentFinancials['base_due']); ?></strong></div>
                <?php foreach ($paymentAddons as $addon) { ?><div class="d-flex justify-content-between mt-2"><span><?php echo rfp_h($addon['addon_name']); ?> x<?php echo (int)$addon['quantity']; ?><br><span class="badge bg-info text-dark">Saat pelunasan</span></span><strong><?php echo rfp_format_rupiah($addon['line_total']); ?></strong></div><?php } ?>
                <hr><div class="d-flex justify-content-between fs-6"><strong>Total pelunasan</strong><strong><?php echo rfp_format_rupiah($paymentFinancials['total_due']); ?></strong></div>
              </div>
            </div>
            <div class="col-md-5 text-center"><a href="../assets/img/data_pelunasan/<?php echo rawurlencode(basename($payment['proof_filename'])); ?>" target="_blank" rel="noopener"><img class="img-fluid border rounded" style="max-height:320px;object-fit:contain" src="../assets/img/data_pelunasan/<?php echo rawurlencode(basename($payment['proof_filename'])); ?>" alt="Bukti pelunasan"></a><p class="small text-muted mt-2">Klik gambar untuk melihat ukuran penuh.</p></div>
          </div>
        </div>
        <div class="modal-footer d-block">
          <form method="post" action="<?php echo rfp_h($adminPaymentFormAction); ?>">
            <input type="hidden" name="rfp_payment_review" value="1">
            <input type="hidden" name="csrf_token" value="<?php echo rfp_h(rfp_csrf_token()); ?>">
            <input type="hidden" name="submission_id" value="<?php echo (int)$payment['id']; ?>">
            <label class="form-label small fw-bold" for="admin-note-<?php echo (int)$payment['id']; ?>">Catatan admin <span class="fw-normal text-muted">(opsional; alasan standar digunakan jika kosong)</span></label>
            <textarea class="form-control form-control-sm" id="admin-note-<?php echo (int)$payment['id']; ?>" name="admin_note" maxlength="500" rows="2" placeholder="Contoh: nominal atau bukti belum sesuai"></textarea>
            <div class="d-flex justify-content-end gap-2 mt-3"><button class="btn btn-outline-secondary btn-sm" type="button" data-bs-dismiss="modal">Batal</button><button class="btn btn-outline-danger btn-sm" type="submit" name="review_action" value="reject" onclick="return confirm('Tolak pengajuan pelunasan ini?')">Tolak</button><button class="btn btn-success btn-sm" type="submit" name="review_action" value="approve" onclick="return confirm('Setujui pembayaran ini dan tandai invoice LUNAS?')">Setujui &amp; Tandai Lunas</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
<?php } ?>
