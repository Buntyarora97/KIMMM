<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/shiprocket_helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===========================
   ERROR DEBUG (REMOVE IN LIVE)
=========================== */
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

/* ===========================
   AUTH CHECK
=========================== */
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

/* ===========================
   ORDER ID VALIDATION
=========================== */
if (!isset($_GET['id'])) {
    die('Invalid Order ID');
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
   DUPLICATE SHIPMENT CHECK
=========================== */
if (!empty($order['awb_code']) || !empty($order['shiprocket_order_id'])) {
    die('Shipment already created.<br>
         AWB: ' . ($order['awb_code'] ?? 'N/A') . '<br>
         Shiprocket ID: ' . ($order['shiprocket_order_id'] ?? 'N/A'));
}

/* ===========================
   FETCH ORDER ITEMS
=========================== */
$stmtItems = db()->prepare("
    SELECT oi.*, p.name as product_name, p.sku
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmtItems->execute([$orderId]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

if (empty($items)) {
    $items[] = [
        'product_name' => $order['product_name'] ?? 'Product',
        'sku' => $order['sku'] ?? 'SKU' . $orderId,
        'quantity' => $order['quantity'] ?? 1,
        'price' => $order['total_amount'],
        'hsn_code' => 1234
    ];
}

/* ===========================
   SHIPPING DETAILS
=========================== */
function getValue($order, $keys) {
    foreach ($keys as $key) {
        if (!empty($order[$key])) {
            return trim($order[$key]);
        }
    }
    return '';
}

$shippingAddress = getValue($order, ['shipping_address','address','customer_address']);
$shippingCity    = getValue($order, ['city','shipping_city','customer_city']);
$shippingState   = getValue($order, ['state','shipping_state','customer_state']);
$shippingPincode = getValue($order, ['pincode','shipping_pincode','zipcode','postal_code']);

if (
    empty($shippingAddress) ||
    empty($shippingCity) ||
    empty($shippingState) ||
    empty($shippingPincode) ||
    empty($order['customer_phone']) ||
    empty($order['customer_name'])
) {
    die("❌ Incomplete shipping details. Please edit order.");
}

/* ===========================
   PREPARE ITEMS
=========================== */
$orderItems = [];
foreach ($items as $item) {
    $orderItems[] = [
        'product_name' => $item['product_name'],
        'sku' => $item['sku'],
        'quantity' => (int)$item['quantity'],
        'price' => (float)$item['price'],
       'hsn' => 1234
    ];
}

/* ===========================
   PREPARE DATA
=========================== */
$orderData = [
    'order_number'     => $order['order_number'] ?? ('ORD' . $orderId),
    'customer_name'    => $order['customer_name'],
    'customer_email'   => $order['customer_email'] ?? 'support@livvra.in',
    'customer_phone'   => $order['customer_phone'],
    'shipping_address' => $shippingAddress,
    'city'             => $shippingCity,
    'state'            => $shippingState,
    'pincode'          => $shippingPincode,
    'total_amount'     => (float)$order['total_amount'],
    'payment_method'   => $order['payment_method'] ?? 'prepaid',
    'items'            => $orderItems,
    'length'           => 10,
    'breadth'          => 10,
    'height'           => 10,
    'weight'           => 0.5,
];

/* ===========================
   CREATE SHIPMENT
=========================== */
$result = Shiprocket::createOrder($orderData);

/* ===========================
   HANDLE FAILURE
=========================== */
if (!$result['success']) {
    echo "<h3>❌ Shipment Failed</h3>";
    echo "<p>" . htmlspecialchars($result['message']) . "</p>";
    echo "<br><a href='orders.php'>Back to Orders</a>";
    exit;
}

/* ===========================
   SAVE TO DATABASE
=========================== */
$stmtUpdate = db()->prepare("
    UPDATE orders 
    SET shiprocket_order_id = ?, 
        shipment_id = ?, 
        awb_code = ?, 
        courier_name = ?, 
        shipment_status = 'shipped'
    WHERE id = ?
");

$stmtUpdate->execute([
    $result['shiprocket_order_id'],
    $result['shipment_id'] ?? null,
    $result['awb_code'] ?? null,
    $result['courier_name'] ?? null,
    $orderId
]);

/* ===========================
   SUCCESS MESSAGE
=========================== */
echo "<h3>✅ Shipment Created Successfully</h3>";
echo "<p><strong>Shiprocket Order ID:</strong> " . $result['shiprocket_order_id'] . "</p>";

if (!empty($result['awb_code'])) {
    echo "<p><strong>AWB:</strong> " . $result['awb_code'] . "</p>";
}

if (!empty($result['label_url'])) {
    echo "<p><a href='" . $result['label_url'] . "' target='_blank'>Download Label</a></p>";
}

echo "<br><a href='orders.php'>Back to Orders</a>";
exit;