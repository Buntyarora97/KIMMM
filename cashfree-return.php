<?php
session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';

/* =========================
   VALIDATE REQUEST
========================= */

$orderId = $_GET['order_id'] ?? 0;
$cf_order_id = $_GET['cf_order_id'] ?? '';

if (!$orderId || !$cf_order_id) {
    die("Invalid request");
}

$order = Order::getById($orderId);

if (!$order) {
    die("Order not found");
}

/* =========================
   CASHFREE VERIFY API
========================= */

$client_id     = CASHFREE_CLIENT_ID;
$client_secret = CASHFREE_CLIENT_SECRET;
$mode          = CASHFREE_ENV;

$verify_url = ($mode === 'PROD')
    ? "https://api.cashfree.com/pg/orders/{$cf_order_id}/payments"
    : "https://sandbox.cashfree.com/pg/orders/{$cf_order_id}/payments";

/* ===== Correct Headers ===== */

$headers = [
    "Content-Type: application/json",
    "x-api-version: 2022-09-01",
    "x-client-id: {$client_id}",
    "x-client-secret: {$client_secret}"
];

/* ===== CURL GET Request ===== */

$ch = curl_init($verify_url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST  => "GET",
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_TIMEOUT        => 30
]);

$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    die("Cashfree Verification Failed (HTTP {$http_code})");
}

$result = json_decode($response, true);

$payment_success = false;
$payment_details = [];

/* =========================
   CHECK PAYMENT STATUS
========================= */

if (is_array($result)) {
    foreach ($result as $payment) {
        if (
            isset($payment['payment_status']) &&
            $payment['payment_status'] === "SUCCESS"
        ) {
            $payment_success = true;
            $payment_details = $payment;
            break;
        }
    }
}

/* =========================
   UPDATE ORDER STATUS & REDIRECT
========================= */

if ($payment_success) {

    Order::updateStatus($orderId, 'paid');

    unset($_SESSION['cart']);
    unset($_SESSION['pending_order_id']);
    
    // SAVE ORDER DETAILS IN SESSION FOR THANK YOU PAGE
    $_SESSION['order_success'] = [
        'order_id' => $orderId,
        'cf_order_id' => $cf_order_id,
        'payment_details' => $payment_details,
        'order_data' => $order,
        'success_time' => date('Y-m-d H:i:s')
    ];
    
    // REDIRECT TO THANK YOU PAGE
    header("Location: thank-you.php");
    exit();

} else {

    Order::updateStatus($orderId, 'failed');
    
    // SAVE FAILURE DETAILS
    $_SESSION['order_failed'] = [
        'order_id' => $orderId,
        'cf_order_id' => $cf_order_id
    ];
    
    // REDIRECT TO PAYMENT FAILED PAGE
    header("Location: payment-failed.php");
    exit();
}
?>