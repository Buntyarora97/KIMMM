<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$code = strtoupper(trim($_POST['code'] ?? ''));
$cartTotal = (float)($_POST['cart_total'] ?? 0);

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Promo code daalo']);
    exit;
}

try {
    $stmt = db()->prepare("
        SELECT * FROM promo_codes 
        WHERE code = ? 
          AND is_active = 1 
          AND (expiry_date IS NULL OR expiry_date >= CURRENT_DATE)
          AND (usage_limit = 0 OR used_count < usage_limit)
    ");
    $stmt->execute([$code]);
    $promo = $stmt->fetch();

    if (!$promo) {
        echo json_encode(['success' => false, 'message' => 'Yeh promo code invalid hai ya expire ho gaya hai']);
        exit;
    }

    if ($cartTotal < $promo['min_order_amount']) {
        echo json_encode([
            'success' => false,
            'message' => 'Is code ke liye minimum order ₹' . number_format($promo['min_order_amount'], 0) . ' hona chahiye'
        ]);
        exit;
    }

    // Calculate discount
    $discount = 0;
    if ($promo['discount_type'] === 'percentage') {
        $discount = ($cartTotal * $promo['discount_value']) / 100;
        if ($promo['max_discount'] > 0) {
            $discount = min($discount, $promo['max_discount']);
        }
    } else {
        $discount = (float)$promo['discount_value'];
    }
    $discount = min($discount, $cartTotal);

    // Build message
    if ($promo['discount_type'] === 'percentage') {
        $discountMsg = $promo['discount_value'] . '% off';
        if ($promo['max_discount'] > 0) $discountMsg .= ' (Max ₹' . number_format($promo['max_discount'], 0) . ')';
    } else {
        $discountMsg = '₹' . number_format($promo['discount_value'], 0) . ' off';
    }

    echo json_encode([
        'success' => true,
        'message' => '🎉 Promo code apply ho gaya! ' . $discountMsg,
        'discount' => round($discount, 2),
        'discount_amount' => round($discount, 2),
        'discount_formatted' => '₹' . number_format($discount, 2),
        'new_total' => round($cartTotal - $discount, 2),
        'promo_id' => $promo['id']
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error. Dobara try karo.']);
}
?>
