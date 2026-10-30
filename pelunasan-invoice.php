<?php
require 'function.php';
require 'pelunasan-lib.php';

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$submission = $token !== '' ? rfp_get_submission_by_token($token) : null;
if (!$submission) { http_response_code(404); exit('Invoice tidak ditemukan.'); }
$addons = rfp_submission_addons($submission['id']);
$filename = preg_replace('/[^A-Za-z0-9-]/', '', $submission['invoice_number']);
$pdf = rfp_invoice_pdf($submission, $addons);
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="invoice-' . $filename . '.pdf"');
header('Content-Length: ' . strlen($pdf));
echo $pdf;
exit;
