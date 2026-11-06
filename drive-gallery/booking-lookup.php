<?php
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'GET') throw new RuntimeException('Metode request tidak valid.');
    $uniqid = isset($_GET['booking_uniqid']) ? trim((string) $_GET['booking_uniqid']) : '';
    $name = isset($_GET['customer_name']) ? trim((string) $_GET['customer_name']) : '';
    $studio = isset($_GET['studio']) ? trim((string) $_GET['studio']) : '';
    if (!in_array($studio, array('Bekasi-Kota', 'Bekasi-Kabupaten'), true)) throw new RuntimeException('Pilih studio yang valid.');
    $booking = rfg_gallery_booking_lookup($uniqid, $name, $studio);
    echo json_encode(array('success' => true, 'booking' => $booking), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(422);
    echo json_encode(array('success' => false, 'message' => $e->getMessage()), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
