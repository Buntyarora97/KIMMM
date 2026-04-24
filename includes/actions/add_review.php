<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $name = trim($_POST['name']);
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    
    $conn = db();
    
    try {
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, name, rating, comment) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$product_id, $name, $rating, $comment]);
        
        if ($result) {
            // ✅ PERFECT REDIRECT - Same page पर जायेगा
            header("Location: ../../product-detail.php?id=" . $product_id . "&review=success");
            exit;
        }
    } catch (Exception $e) {
        // Error भी same page पर
        header("Location: ../../product-detail.php?id=" . $product_id . "&review=error");
        exit;
    }
}

// अगर direct access या invalid request
header("Location: ../../product-detail.php");
exit;
?>
