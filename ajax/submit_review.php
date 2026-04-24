<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Review.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
    exit;
}

$productId = intval($_POST['product_id'] ?? 0);
$name      = trim($_POST['name'] ?? 'Anonymous');
$rating    = intval($_POST['rating'] ?? 0);
$comment   = trim($_POST['comment'] ?? '');

if ($productId <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please fill all required fields and select rating'
    ]);
    exit;
}

$result = Review::create([
    'product_id' => $productId,
    'name'       => $name,
    'rating'     => $rating,
    'comment'    => $comment
]);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Review submitted successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to submit review'
    ]);
}
