<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if (isset($_SESSION['cart'][$productId])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$productId]);
        }
        
        $subtotal = getCartTotal();
        $shipping = $subtotal >= 499 ? 0 : 50;
        
        $response['success'] = true;
        $response['cartCount'] = getCartCount();
        $response['subtotal'] = number_format($subtotal);
        $response['total'] = number_format($subtotal + $shipping);
    }
}

echo json_encode($response);
?>
