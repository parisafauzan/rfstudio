-- RF Studio: snapshot harga invoice pelunasan.
-- Jalankan SEKALI pada database yang tabel booking_payment_submissions-nya
-- sudah dibuat sebelum pembaruan sinkronisasi DP/pelunasan ini.

ALTER TABLE booking_payment_submissions
    ADD COLUMN package_total DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER booking_time,
    ADD COLUMN deposit_paid DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER package_total;

-- Isi snapshot untuk pengajuan lama dari tabel lifecycle booking yang masih ada.
UPDATE booking_payment_submissions AS p
LEFT JOIN data_booking_cobahampirfinishjuga AS b ON b.uniqid = p.booking_uniqid
LEFT JOIN data_konfirmasi_cobahampirfinishjuga AS k ON k.uniqid = p.booking_uniqid
LEFT JOIN data_history AS h ON h.uniqid = p.booking_uniqid
SET
    p.package_total = COALESCE(b.harga, k.harga, h.harga, p.base_due),
    p.deposit_paid = GREATEST(
        COALESCE(b.harga, k.harga, h.harga, p.base_due) - p.base_due,
        0
    )
WHERE p.package_total = 0;
