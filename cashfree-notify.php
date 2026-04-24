<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';

/* ============================================
   READ RAW INPUT
============================================ */

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

/* ============================================
   HANDLE EMPTY OR TEST WEBHOOK
============================================ */

if (empty($data) || (isset($data['type']) && $data['type'] === 'WEBHOOK_TEST')) {
    http_response_code(200);
    echo "Webhook OK";
    exit;
}

/* ============================================
   VALIDATE STRUCTURE
============================================ */

if (
    !isset($data['type']) ||
    !isset($data['data']['order']['order_id'])
) {
    http_response_code(200);
    echo "Invalid payload";
    exit;
}

$type = $data['type'];
$cf_order_id = $data['data']['order']['order_id'];

/* ============================================
   EXTRACT MAIN ORDER NUMBER
   (REMOVES TIMESTAMP IF ADDED)
============================================ */

$parts = explode('_', $cf_order_id);
$order_number = $parts[0];

/* ============================================
   FETCH ORDER FROM DATABASE
============================================ */

$stmt = db()->prepare("SELECT id, payment_status FROM orders WHERE order_number = ?");
$stmt->execute([$order_number]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    http_response_code(200);
    echo "Order not found";
    exit;
}

/* ============================================
   PREVENT DUPLICATE SUCCESS UPDATE
============================================ */

if ($order['payment_status'] === 'paid') {
    http_response_code(200);
    echo "Already processed";
    exit;
}

/* ============================================
   GET PAYMENT STATUS SAFELY
============================================ */

$payment_status = $data['data']['payment']['payment_status'] ?? '';

/* ============================================
   UPDATE BASED ON WEBHOOK TYPE
============================================ */

if ($type === 'PAYMENT_SUCCESS_WEBHOOK' && $payment_status === 'SUCCESS') {

    Order::updatePaymentStatus($order['id'], 'paid');
    echo "Payment confirmed";

} elseif ($type === 'PAYMENT_FAILED_WEBHOOK' && $payment_status === 'FAILED') {

    Order::updatePaymentStatus($order['id'], 'failed');
    echo "Payment failed";

} elseif ($payment_status === 'USER_DROPPED' || $payment_status === 'CANCELLED') {

    Order::updatePaymentStatus($order['id'], 'failed');
    echo "Payment cancelled";

} else {

    echo "Webhook received";
}

http_response_code(200);
exit;