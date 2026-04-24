<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Error adding product to cart'];

try {
    require_once '../includes/config.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        if (!$productId) {
            $response['message'] = 'Invalid product ID';
            echo json_encode($response);
            exit;
        }
        
        $product = Product::getById($productId);
        
        if ($product) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $price = (float)($product['price'] ?? 0);
            $discounted_price = $price * 0.90; // Apply 10% discount to match display
            
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = [
                    'id' => $productId,
                    'name' => $product['name'] ?? 'Product',
                    'price' => $discounted_price,
                    'image' => $product['image'] ?? '',
                    'category_name' => $product['category_name'] ?? '',
                    'quantity' => $quantity
                ];
            }
            
            $response['success'] = true;
            $response['cart_count'] = getCartCount();
            $response['cartCount'] = $response['cart_count']; // Add alias for consistency with update_cart.php
            $response['message'] = 'Product added to cart!';
        } else {
            $response['message'] = 'Product not found';
        }
    }
} catch (Exception $e) {
    error_log('Add to cart error: ' . $e->getMessage());
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
