<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/shiprocket-orders.php';
session_start();

/* ===========================
   ADMIN LOGIN CHECK
=========================== */
if (!isset($_SESSION['admin_id'])) {
    die('Unauthorized access');
}

/* ===========================
   ORDER ID VALIDATION
=========================== */
if (!isset($_GET['id'])) {
    die('Invalid order ID');
}

$orderId = (int)$_GET['id'];

/* ===========================
   FETCH ORDER
=========================== */
$stmt = db()->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('Order not found');
}

/* ===========================
   DUPLICATE CHECK
=========================== */
if (!empty($order['awb_code'])) {
    die("Shipment already created. AWB: " . $order['awb_code']);
}

/* ===========================
   BASIC SHIPPING VALIDATION
=========================== */
if (
    empty($order['shipping_address']) ||
    empty($order['shipping_city']) ||
    empty($order['shipping_state']) ||
    empty($order['shipping_pincode'])
) {
    die('Incomplete shipping details');
}

try {

    /* ===========================
       CALL SHIPROCKET CLASS
    ============================ */
    $response = Shiprocket::createOrder($orderId);

    echo "<pre>";
    print_r($response);

    /* ===========================
       STRICT VALIDATION
    ============================ */
    if (
        !isset($response['awb_code']) ||
        empty($response['awb_code'])
    ) {
        die("Shipment failed. No AWB generated.");
    }

    /* ===========================
       UPDATE DATABASE
    ============================ */
    $stmt = db()->prepare("
        UPDATE orders 
        SET shipment_status = 'shipped',
            order_status = 'processing',
            awb_code = ?,
            shiprocket_order_id = ?,
            updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $response['awb_code'],
        $response['order_id'] ?? null,
        $orderId
    ]);

    echo "<h3>Shipment Created Successfully</h3>";
    echo "AWB: " . $response['awb_code'];

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}