-- RF Studio: modul pelunasan tanpa login, berbasis email.
-- Jalankan SEKALI pada database target setelah membuat backup database.
-- Tidak mengubah tabel booking lama.

CREATE TABLE IF NOT EXISTS booking_payment_submissions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    booking_uniqid VARCHAR(64) NOT NULL,
    -- Nilai konfirmasi dipertahankan untuk kompatibilitas data lama. Pengajuan baru
    -- hanya boleh dibuat dari booking aktif setelah DP dikonfirmasi admin.
    legacy_source ENUM('konfirmasi', 'booking', 'pelunasan') NOT NULL,
    customer_name VARCHAR(150) NOT NULL,
    customer_email VARCHAR(191) NOT NULL,
    customer_phone VARCHAR(40) NOT NULL DEFAULT '',
    studio VARCHAR(100) NOT NULL DEFAULT '',
    package_name VARCHAR(150) NOT NULL DEFAULT '',
    package_type VARCHAR(150) NOT NULL DEFAULT '',
    booking_date VARCHAR(30) NOT NULL DEFAULT '',
    booking_time VARCHAR(150) NOT NULL DEFAULT '',
    package_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    deposit_paid DECIMAL(12,2) NOT NULL DEFAULT 0,
    base_due DECIMAL(12,2) NOT NULL DEFAULT 0,
    addons_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    total_due DECIMAL(12,2) NOT NULL DEFAULT 0,
    proof_filename VARCHAR(255) NOT NULL,
    proof_mime VARCHAR(100) NOT NULL DEFAULT '',
    proof_size INT UNSIGNED NOT NULL DEFAULT 0,
    invoice_number VARCHAR(64) DEFAULT NULL,
    public_token CHAR(64) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    admin_note VARCHAR(500) NOT NULL DEFAULT '',
    reviewed_by VARCHAR(150) NOT NULL DEFAULT '',
    reviewed_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_payment_invoice_number (invoice_number),
    UNIQUE KEY uq_payment_public_token (public_token),
    KEY idx_payment_booking (booking_uniqid, legacy_source),
    KEY idx_payment_email (customer_email),
    KEY idx_payment_status_created (status, created_at),
    KEY idx_payment_studio_status (studio, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jalankan juga bila tabel di atas SUDAH pernah dibuat dengan versi migration lama.
-- Nilai `konfirmasi` dipertahankan agar invoice lama tetap dapat dibaca.
ALTER TABLE booking_payment_submissions
    MODIFY legacy_source ENUM('konfirmasi', 'booking', 'pelunasan') NOT NULL;

CREATE TABLE IF NOT EXISTS booking_payment_addons (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    submission_id BIGINT UNSIGNED NOT NULL,
    addon_code VARCHAR(100) NOT NULL,
    addon_name VARCHAR(255) NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    line_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_payment_addon_submission (submission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
