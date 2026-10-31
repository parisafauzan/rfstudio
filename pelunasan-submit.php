<?php
require 'function.php';
require 'pelunasan-lib.php';

function rfp_submit_redirect($code, $email, $uniqid, $source){
    $parameters = array('payment_error' => $code);
    if ($email !== '') {
        $parameters['email'] = $email;
    }
    if ($uniqid !== '' && in_array($source, array('konfirmasi', 'booking'), true)) {
        $parameters['booking_uniqid'] = $uniqid;
        $parameters['legacy_source'] = $source;
    }
    header('Location: pelunasan?' . http_build_query($parameters));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rfp_submit_redirect('method', '', '', '');
}
if (rfp_post_was_discarded()) {
    rfp_submit_redirect('upload_too_large', '', '', '');
}

$email = strtolower(trim(isset($_POST['email']) ? $_POST['email'] : ''));
$uniqid = trim(isset($_POST['booking_uniqid']) ? $_POST['booking_uniqid'] : '');
$source = trim(isset($_POST['legacy_source']) ? $_POST['legacy_source'] : '');

if (!isset($_POST['action']) || $_POST['action'] !== 'submit_payment') {
    rfp_submit_redirect('invalid_form', $email, $uniqid, $source);
}
if (!rfp_database_available()) {
    rfp_submit_redirect('database', $email, $uniqid, $source);
}
if (!rfp_schema_ready()) {
    rfp_submit_redirect('schema', $email, $uniqid, $source);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    rfp_submit_redirect('email', '', $uniqid, $source);
}
if (!rfp_same_origin_post()) {
    rfp_submit_redirect('origin', $email, $uniqid, $source);
}

$booking = rfp_get_booking($uniqid, $source, $email);
if (!$booking) {
    rfp_submit_redirect('booking', $email, $uniqid, $source);
}

$result = rfp_create_submission(
    $booking,
    isset($_POST['addons']) ? $_POST['addons'] : array(),
    isset($_FILES['proof']) ? $_FILES['proof'] : array()
);

if (!$result['ok']) {
    $code = isset($result['code']) ? $result['code'] : 'submit_failed';
    error_log('RF payment submit failed: ' . $code . ' / booking ' . substr(hash('sha256', $uniqid), 0, 12));
    rfp_submit_redirect($code, $email, $uniqid, $source);
}

header('Location: pelunasan?invoice=' . rawurlencode($result['token']) . '&submitted=1');
exit;
