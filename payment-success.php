<?php
require_once 'includes/config.php';
require_once 'includes/models/Order.php';

$orderId = $_SESSION['pending_order_id'] ?? 0;

if($orderId){
    db()->prepare("UPDATE orders SET payment_status='paid' WHERE id=?")
       ->execute([$orderId]);

    unset($_SESSION['cart']);
    unset($_SESSION['pending_order_id']);
}

header("Location: thank-you.php");
exit;