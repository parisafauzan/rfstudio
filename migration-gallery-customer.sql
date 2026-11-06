-- RF Studio - fitur galeri customer dan permintaan edit foto
-- Jalankan satu kali pada database yang sama dengan gallery_sessions/gallery_photos.

CREATE TABLE IF NOT EXISTS gallery_edit_requests (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  gallery_session_id BIGINT UNSIGNED NOT NULL,
  customer_email VARCHAR(190) NOT NULL,
  status ENUM('submitted','editing','completed','rejected') NOT NULL DEFAULT 'submitted',
  customer_note VARCHAR(500) NOT NULL DEFAULT '',
  admin_note VARCHAR(500) NOT NULL DEFAULT '',
  submitted_at DATETIME NULL,
  reviewed_at DATETIME NULL,
  completed_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_gallery_edit_request_session (gallery_session_id),
  KEY idx_gallery_edit_request_status (status, submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS gallery_edit_request_items (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  request_id BIGINT UNSIGNED NOT NULL,
  gallery_photo_id BIGINT UNSIGNED NOT NULL,
  drive_file_id VARCHAR(190) NOT NULL,
  filename VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_gallery_request_file (request_id, drive_file_id),
  KEY idx_gallery_request_items_request (request_id),
  KEY idx_gallery_request_items_photo (gallery_photo_id),
  CONSTRAINT fk_gallery_request_items_request FOREIGN KEY (request_id) REFERENCES gallery_edit_requests (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
