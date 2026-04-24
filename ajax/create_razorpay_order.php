<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Order.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$orderId = $_POST['order_id'] ?? null;
if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Order ID missing']);
    exit;
}

$order = Order::getById($orderId);
if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

// In a real app, use Razorpay PHP SDK here
// Since we don't have it installed, we simulate the response
// for the frontend to show the gateway
echo json_encode([
    'success' => true,
    'razorpay_order_id' => 'order_' . uniqid(),
    'amount' => $order['total_amount'] * 100,
    'currency' => 'INR'
]);
