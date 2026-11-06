-- RF Studio: cache metadata Google Drive untuk galeri admin dan customer.
-- Jalankan SEKALI pada database subdomain testing yang belum memiliki tabel
-- gallery_sessions dan gallery_photos. Tidak menyentuh tabel booking lama.

CREATE TABLE IF NOT EXISTS gallery_sessions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    booking_uniqid VARCHAR(64) NOT NULL,
    nama_pelanggan VARCHAR(150) NOT NULL,
    studio VARCHAR(100) NOT NULL DEFAULT '',
    tanggal_booking DATE NOT NULL,
    drive_folder_id VARCHAR(190) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    originals_synced_at DATETIME DEFAULT NULL,
    edited_synced_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_gallery_session_booking (booking_uniqid),
    UNIQUE KEY uq_gallery_session_folder (drive_folder_id),
    KEY idx_gallery_session_status_date (status, tanggal_booking)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS gallery_photos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    gallery_session_id BIGINT UNSIGNED NOT NULL,
    drive_file_id VARCHAR(190) NOT NULL,
    drive_parent_folder_id VARCHAR(190) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    category ENUM('original', 'edited') NOT NULL,
    mime_type VARCHAR(100) NOT NULL DEFAULT '',
    size_bytes BIGINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT NULL,
    drive_modified_at DATETIME DEFAULT NULL,
    thumbnail_link TEXT,
    status ENUM('active', 'missing') NOT NULL DEFAULT 'active',
    last_seen_at DATETIME DEFAULT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_gallery_photo_drive_file (drive_file_id),
    KEY idx_gallery_photo_session_folder_category (gallery_session_id, drive_parent_folder_id, category, status),
    KEY idx_gallery_photo_session_category (gallery_session_id, category, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
