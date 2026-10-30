<?php
/**
 * Dipanggil setelah admin berhasil mengonfirmasi booking.
 * Kegagalan Google Drive tidak membatalkan konfirmasi booking lama.
 */
require_once __DIR__ . '/bootstrap.php';

function rfg_booking_folder_after_confirmation($booking)
{
    if (!is_array($booking)) throw new RuntimeException('Data booking tidak valid untuk pembuatan folder galeri.');
    if (empty($booking['uniqid'])) throw new RuntimeException('Booking tidak memiliki uniqid.');
    return rfg_ensure_booking_folder(
        isset($booking['studio']) ? $booking['studio'] : '',
        isset($booking['tanggal']) ? $booking['tanggal'] : '',
        isset($booking['nama']) ? $booking['nama'] : '',
        $booking['uniqid']
    );
}
