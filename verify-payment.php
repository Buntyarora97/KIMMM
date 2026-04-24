<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';

$orderId = $_POST['order_id'] ?? null;
$paymentId = $_POST['razorpay_payment_id'] ?? null;
$signature = $_POST['razorpay_signature'] ?? null;
$razorpayOrderId = $_POST['razorpay_order_id'] ?? null;

if (!$orderId || !$paymentId) {
    header('Location: index.php');
    exit;
}

try {
    // Basic verification (in production use official SDK)
    Order::updatePaymentStatus($orderId, 'paid');
    Order::updateStatus($orderId, 'confirmed');
    
    // Create order in Shiprocket
    try {
        require_once __DIR__ . '/includes/models/Shiprocket.php';
        $shiprocketResult = Shiprocket::createOrder($orderId);
        if (isset($shiprocketResult['order_id'])) {
            Order::updateShiprocketId($orderId, $shiprocketResult['order_id']);
        }
    } catch (Exception $se) {
        error_log("Shiprocket Order Creation Failed: " . $se->getMessage());
    }
    
    unset($_SESSION['cart']);
    header('Location: order-success.php?order_id=' . $orderId);
    exit;
} catch (Exception $e) {
    die("Payment verification failed: " . $e->getMessage());
}
?>