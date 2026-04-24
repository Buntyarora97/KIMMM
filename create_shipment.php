<?php

require_once 'includes/config.php';
require_once 'includes/Shiprocket.php';

$orderId = 147; // 👈 apna order id daalo (screenshot me 147)

try {
    $response = Shiprocket::createOrder($orderId);
    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}