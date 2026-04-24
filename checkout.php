<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';
require_once __DIR__ . '/includes/models/Product.php';

// DEBUG: Log all POST data for troubleshooting
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("=== CHECKOUT DEBUG ===");
    error_log("POST Data: " . print_r($_POST, true));
    error_log("Session User ID: " . ($_SESSION['user_id'] ?? 'Guest'));
}

$cartItems = $_SESSION['cart'] ?? [];
if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

// Calculate totals with discount tracking
$subtotal = 0;
$totalOriginal = 0;
$totalSavings = 0;

foreach ($cartItems as $item) {
    $itemTotal = $item['price'] * $item['quantity'];
    $itemOriginal = ($item['original_price'] ?? $item['price']) * $item['quantity'];
    
    $subtotal += $itemTotal;
    $totalOriginal += $itemOriginal;
    $totalSavings += ($itemOriginal - $itemTotal);
}

$shipping = getShippingFee($subtotal);
$total = $subtotal + $shipping;

$success = false;
$orderNumber = '';
$error = '';

// ✅ FIX: Initialize variables to prevent undefined index errors
$formData = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'address_2' => '',
    'landmark' => '',
    'city' => '',
    'state' => '',
    'pincode' => '',
    'payment_method' => 'cashfree'
];

// ✅ FIX: Properly capture POST data with sanitization
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name'] = trim($_POST['name'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $formData['phone'] = trim($_POST['phone'] ?? '');
    $formData['address'] = trim($_POST['address'] ?? '');
    $formData['address_2'] = trim($_POST['address_2'] ?? '');
    $formData['landmark'] = trim($_POST['landmark'] ?? '');
    $formData['city'] = trim($_POST['city'] ?? '');
    $formData['state'] = trim($_POST['state'] ?? '');
    $formData['pincode'] = trim($_POST['pincode'] ?? '');
    $formData['payment_method'] = $_POST['payment_method'] ?? 'cashfree';
    
    // ✅ VALIDATION: Check all required fields
    $required_fields = ['name', 'phone', 'address', 'city', 'state', 'pincode'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (empty($formData[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        $error = 'Please fill in all required fields: ' . implode(', ', $missing_fields);
    } else {
        try {
            // Apply Promo Code
            $promo_discount = 0;
            $promoCode = '';
            $applied_promo = null;
            
            if (!empty($_POST['promo_code'])) {
                $pcode = strtoupper(trim($_POST['promo_code']));
                $stmt = db()->prepare("SELECT * FROM promo_codes WHERE code = ? AND is_active = 1 AND (expiry_date IS NULL OR expiry_date >= CURRENT_DATE) AND (usage_limit = 0 OR used_count < usage_limit)");
                $stmt->execute([$pcode]);
                $promo = $stmt->fetch();
                
                if ($promo) {
                    if ($subtotal >= $promo['min_order_amount']) {
                        $promoCode = $pcode;
                        $applied_promo = $promo;
                        if ($promo['discount_type'] === 'percentage') {
                            $promo_discount = ($subtotal * $promo['discount_value']) / 100;
                            if (($promo['max_discount'] ?? 0) > 0) $promo_discount = min($promo_discount, $promo['max_discount']);
                        } else {
                            $promo_discount = (float)$promo['discount_value'];
                        }
                        $total -= $promo_discount;
                        db()->prepare("UPDATE promo_codes SET used_count = used_count + 1 WHERE id = ?")->execute([$promo['id']]);
                    }
                }
            }

            // Reward Coins Logic
            $reward_discount = 0;
            if (isset($_SESSION['user_id']) && !empty($_POST['use_reward_coins'])) {
                $uid = $_SESSION['user_id'];
                $stmt = db()->prepare("SELECT balance FROM reward_coins WHERE user_id = ?");
                $stmt->execute([$uid]);
                $balance = (int)$stmt->fetchColumn();
                
                if ($balance > 0) {
                    $redeem_rate = (float)(db()->query("SELECT value FROM settings WHERE key = 'reward_redeem_rate'")->fetchColumn() ?: 1);
                    $reward_discount = $balance * $redeem_rate;
                    $reward_discount = min($reward_discount, $total);
                    $total -= $reward_discount;
                    
                    db()->prepare("INSERT INTO coin_transactions (user_id, amount, transaction_type, description) VALUES (?, ?, 'redeemed', 'Used at checkout')")
                        ->execute([$uid, $balance]);
                    db()->prepare("UPDATE reward_coins SET balance = 0 WHERE user_id = ?")->execute([$uid]);
                }
            }

            // ✅ CRITICAL FIX: Build complete address string for Shiprocket
            $fullAddress = $formData['address'];
            if (!empty($formData['address_2'])) {
                $fullAddress .= ', ' . $formData['address_2'];
            }
            if (!empty($formData['landmark'])) {
                $fullAddress .= ' (Near: ' . $formData['landmark'] . ')';
            }

            // ✅ DEBUG: Log the actual address being used
            error_log("Shipping Address being saved: " . $fullAddress);
            error_log("City: " . $formData['city'] . ", State: " . $formData['state'] . ", Pin: " . $formData['pincode']);

            $orderData = [
                'user_id' => $_SESSION['user_id'] ?? null,
                'customer_name' => $formData['name'],
                'customer_email' => $formData['email'],
                'customer_phone' => $formData['phone'],
                'shipping_address' => $fullAddress,
                'address_line_1' => $formData['address'],
                'address_line_2' => $formData['address_2'],
                'landmark' => $formData['landmark'],
                'pincode' => $formData['pincode'],
                'city' => $formData['city'],
                'state' => $formData['state'],
                'country' => 'India',
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'discount_amount' => $promo_discount + $reward_discount,
                'total_amount' => $total,
                'payment_method' => $formData['payment_method'],
                'items' => array_values($cartItems),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // ✅ DEBUG: Log complete order data
            error_log("Complete Order Data: " . print_r($orderData, true));
            
            $orderId = Order::create($orderData);
            
            if ($orderId) {
                $order = Order::getById($orderId);
                $orderNumber = $order['order_number'];
                
                // Save promo code usage for influencer tracking
                if ($applied_promo && $promo_discount > 0) {
                    $commission = 0;
                    if ($applied_promo['commission_type'] === 'percentage') {
                        $commission = ($total * $applied_promo['commission_value']) / 100;
                    } else {
                        $commission = (float)$applied_promo['commission_value'];
                    }
                    try {
                        db()->prepare("INSERT INTO promo_code_usage 
                            (promo_code_id, order_id, order_number, customer_name, customer_phone, order_total, discount_given, commission_earned)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
                        ->execute([
                            $applied_promo['id'],
                            $orderId,
                            $orderNumber,
                            $formData['name'],
                            $formData['phone'],
                            $total,
                            $promo_discount,
                            round($commission, 2)
                        ]);
                    } catch (Exception $e) {
                        error_log("Promo usage save error: " . $e->getMessage());
                    }
                }
                
                // ✅ DEBUG: Verify what was actually saved
                error_log("Order Created Successfully - Order ID: " . $orderId . ", Order No: " . $orderNumber);
                error_log("Saved Address in DB: " . ($order['shipping_address'] ?? 'NOT FOUND'));
                
                // ✅ CRITICAL: Clear any cached address data in session
                unset($_SESSION['temp_shipping_address']);
                unset($_SESSION['last_used_address']);
                if (isset($_SESSION['pending_order_id'])) {
                    unset($_SESSION['pending_order_id']);
                }
                
                if ($formData['payment_method'] === 'cod') {
                    unset($_SESSION['cart']);
                    $success = true;
                } else if ($formData['payment_method'] === 'cashfree') {
                    $_SESSION['pending_order_id'] = $orderId;
                    header('Location: cashfree-init.php?order_id=' . $orderId);
                    exit;
                } else if ($formData['payment_method'] === 'instamojo') {
                    $_SESSION['pending_order_id'] = $orderId;
                    header('Location: instamojo-init.php?order_id=' . $orderId);
                    exit;
                } else if ($formData['payment_method'] === 'payu') {
                    $_SESSION['pending_order_id'] = $orderId;
                    header('Location: payu-init.php?order_id=' . $orderId);
                    exit;
                } else if ($formData['payment_method'] === 'upi_manual') {
                    $_SESSION['pending_order_id'] = $orderId;
                    header('Location: upi-payment.php?order_id=' . $orderId);
                    exit;
                }
            } else {
                $error = 'Failed to create order. Please try again.';
                error_log("ERROR: Order::create returned false");
            }
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
            error_log("EXCEPTION in checkout: " . $e->getMessage());
        }
    }
}

$pageTitle = 'Checkout';
require_once 'includes/header.php';

// Fetch best products for bottom section
$bestProducts = Product::getFeatured(8); 
?>

<style>
/* ===== CSS VARIABLES ===== */
:root {
    --primary: #0f3d2e;
    --primary-light: #1a5f4a;
    --accent: #C9A227;
    --accent-light: #d4b43a;
    --danger: #e53935;
    --success: #10b981;
    --bg: #f8faf9;
    --card-bg: #ffffff;
    --text: #1a1a1a;
    --text-muted: #6b7280;
    --border: #e5e7eb;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    --radius: 16px;
    --radius-sm: 8px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== BASE STYLES ===== */
.checkout-wrapper {
    background: linear-gradient(135deg, #f8faf9 0%, #f0f4f3 100%);
    padding: 40px 0 80px;
    min-height: 100vh;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 20px;
}

/* ===== PROGRESS STEPS ===== */
.checkout-progress {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 40px;
    gap: 20px;
}

.progress-step {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
}

.progress-step.active {
    color: var(--primary);
}

.progress-step.completed {
    color: var(--success);
}

.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    transition: var(--transition);
}

.progress-step.active .step-number {
    background: var(--primary);
    color: white;
    box-shadow: 0 0 0 4px rgba(15, 61, 46, 0.2);
}

.progress-step.completed .step-number {
    background: var(--success);
    color: white;
}

.step-line {
    width: 60px;
    height: 2px;
    background: var(--border);
    border-radius: 1px;
}

.step-line.completed {
    background: var(--success);
}

/* ===== CHECKOUT GRID ===== */
.checkout-container {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 40px;
    align-items: start;
}

/* ===== CHECKOUT CARDS ===== */
.checkout-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 30px;
    margin-bottom: 24px;
    border: 1px solid var(--border);
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.checkout-section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}

/* ===== FORM STYLES ===== */
.form-group-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
}

.form-label .required {
    color: var(--danger);
    margin-left: 4px;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 0.9375rem;
    transition: var(--transition);
    background: #fafafa;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    background: white;
    box-shadow: 0 0 0 3px rgba(15, 61, 46, 0.1);
}

.form-control::placeholder {
    color: #9ca3af;
}

/* Inline validation styles */
.form-control.error {
    border-color: var(--danger);
    background: #fef2f2;
}

.form-control.success {
    border-color: var(--success);
    background: #f0fdf4;
}

.validation-message {
    font-size: 0.75rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.validation-message.error {
    color: var(--danger);
}

.validation-message.success {
    color: var(--success);
}

/* ===== PAYMENT METHODS ===== */
.payment-methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-top: 20px;
}

.payment-method-item {
    position: relative;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    background: white;
}

.payment-method-item:hover {
    border-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.payment-method-item.active {
    border-color: var(--primary);
    background: linear-gradient(135deg, rgba(15, 61, 46, 0.05) 0%, rgba(15, 61, 46, 0.02) 100%);
    box-shadow: 0 0 0 3px rgba(15, 61, 46, 0.1);
}

.payment-method-item input {
    position: absolute;
    opacity: 0;
}

.method-icon {
    font-size: 2rem;
    margin-bottom: 12px;
    display: block;
}

.method-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text);
    display: block;
    margin-bottom: 4px;
}

.method-desc {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.check-icon {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    opacity: 0;
    transform: scale(0);
    transition: var(--transition);
}

.payment-method-item.active .check-icon {
    opacity: 1;
    transform: scale(1);
}

/* ===== ORDER SUMMARY SIDEBAR ===== */
.order-summary-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    position: sticky;
    top: 20px;
    overflow: hidden;
    border: 1px solid var(--border);
    animation: slideUp 0.6s ease-out 0.2s backwards;
}

.summary-header {
    padding: 24px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
}

.summary-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
}

.item-count {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-top: 4px;
    display: block;
}

.summary-items {
    max-height: 300px;
    overflow-y: auto;
    padding: 20px 24px;
}

.summary-item {
    display: flex;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateX(-10px); }
    to { opacity: 1; transform: translateX(0); }
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item-img {
    width: 70px;
    height: 70px;
    border-radius: var(--radius-sm);
    object-fit: cover;
    background: #f9fafb;
    padding: 5px;
    border: 1px solid var(--border);
}

.summary-item-info {
    flex: 1;
    min-width: 0;
}

.summary-item-name {
    font-weight: 600;
    font-size: 0.9375rem;
    color: var(--text);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.summary-item-meta {
    font-size: 0.8125rem;
    color: var(--text-muted);
    margin-bottom: 6px;
}

.item-price-breakdown {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.item-final-price {
    font-weight: 700;
    color: var(--primary);
    font-size: 0.9375rem;
}

.item-original-price {
    font-size: 0.8125rem;
    color: var(--text-muted);
    text-decoration: line-through;
}

.item-savings {
    font-size: 0.75rem;
    color: var(--success);
    font-weight: 600;
    background: rgba(16, 185, 129, 0.1);
    padding: 2px 8px;
    border-radius: 20px;
}

/* ===== PROMO CODE SECTION ===== */
.promo-section {
    padding: 20px 24px;
    border-top: 1px solid var(--border);
    background: #fafafa;
}

.promo-toggle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background: none;
    border: none;
    color: var(--accent);
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    padding: 0;
}

.promo-toggle svg {
    width: 20px;
    height: 20px;
    transition: var(--transition);
}

.promo-toggle.active svg {
    transform: rotate(180deg);
}

.promo-input-wrapper {
    display: none;
    gap: 10px;
    margin-top: 12px;
    animation: slideDown 0.3s ease;
}

.promo-input-wrapper.active {
    display: flex;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.promo-input {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 0.9375rem;
    text-transform: uppercase;
    font-weight: 600;
    transition: var(--transition);
}

.promo-input:focus {
    outline: none;
    border-color: var(--accent);
}

.promo-apply-btn {
    padding: 12px 20px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: var(--radius-sm);
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
}

.promo-apply-btn:hover {
    background: var(--accent-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(201, 162, 39, 0.3);
}

.promo-success {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    padding: 10px;
    background: rgba(16, 185, 129, 0.1);
    border-radius: var(--radius-sm);
    color: var(--success);
    font-size: 0.875rem;
    font-weight: 600;
    animation: fadeIn 0.3s ease;
}

/* ===== PRICE BREAKDOWN ===== */
.price-breakdown {
    padding: 20px 24px;
    border-top: 1px solid var(--border);
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 0.9375rem;
}

.price-row .label {
    color: var(--text-muted);
    font-weight: 500;
}

.price-row .value {
    font-weight: 600;
    color: var(--text);
}

.price-row.savings .value {
    color: var(--success);
}

.price-row.discount .value {
    color: var(--danger);
}

.price-divider {
    height: 1px;
    background: var(--border);
    margin: 16px 0;
}

.price-row.total {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
    margin-top: 16px;
    padding-top: 16px;
    border-top: 2px dashed var(--border);
}

.price-row.total .value {
    color: var(--primary);
    font-size: 1.375rem;
}

.savings-highlight {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
    border-radius: var(--radius-sm);
    padding: 12px;
    margin-top: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.875rem;
    color: var(--success);
    font-weight: 600;
    border-left: 3px solid var(--success);
}

/* ===== PLACE ORDER BUTTON ===== */
.place-order-section {
    padding: 24px;
    background: #fafafa;
    border-top: 1px solid var(--border);
}

.place-order-btn {
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1.125rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(201, 162, 39, 0.3);
    position: relative;
    overflow: hidden;
}

.place-order-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: 0.5s;
}

.place-order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(201, 162, 39, 0.4);
}

.place-order-btn:hover::before {
    left: 100%;
}

.place-order-btn svg {
    width: 20px;
    height: 20px;
    transition: var(--transition);
}

.place-order-btn:hover svg {
    transform: translateX(4px);
}

/* ✅ CRITICAL FIX: Disabled state */
.place-order-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none !important;
}

.security-badges {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 16px;
    flex-wrap: wrap;
}

.badge-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}

.badge-item svg {
    width: 16px;
    height: 16px;
    color: var(--success);
}

/* ===== SUCCESS MESSAGE ===== */
.success-container {
    text-align: center;
    padding: 80px 20px;
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    max-width: 600px;
    margin: 0 auto;
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

.success-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    animation: pulse 2s infinite;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.success-icon svg {
    width: 60px;
    height: 60px;
    color: white;
}

.success-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 16px;
}

.success-message {
    font-size: 1.125rem;
    color: var(--text-muted);
    margin-bottom: 30px;
    line-height: 1.6;
}

.order-number {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.25rem;
    margin: 20px 0;
    box-shadow: var(--shadow);
}

.continue-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 40px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 700;
    transition: var(--transition);
    box-shadow: 0 4px 15px rgba(15, 61, 46, 0.3);
}

.continue-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(15, 61, 46, 0.4);
}

/* ===== ERROR MESSAGE ===== */
.error-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: var(--danger);
    padding: 16px 20px;
    border-radius: var(--radius-sm);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    animation: shake 0.5s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

.error-alert svg {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
}

/* ===== TRUST BADGES SECTION ===== */
.trust-section {
    margin-top: 30px;
    padding: 24px;
    background: white;
    border-radius: var(--radius);
    text-align: center;
    border: 1px solid var(--border);
}

.trust-title {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-bottom: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.trust-icons {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
}

.trust-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}

.trust-icon svg {
    width: 32px;
    height: 32px;
    color: var(--primary);
}

/* ===== STICKY MOBILE SUMMARY ===== */
.sticky-mobile-summary {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 16px 20px;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
    z-index: 1000;
    border-top: 3px solid var(--primary);
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.sticky-mobile-summary.visible {
    transform: translateY(0);
}

.sticky-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1280px;
    margin: 0 auto;
}

.sticky-price {
    display: flex;
    flex-direction: column;
}

.sticky-total {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--primary);
}

.sticky-text {
    font-size: 0.875rem;
    color: var(--text-muted);
}

.sticky-btn {
    padding: 14px 32px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 50px;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    font-size: 1rem;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }
    
    .order-summary-card {
        position: static;
    }
}

@media (max-width: 768px) {
    .checkout-progress {
        gap: 10px;
    }
    
    .step-line {
        width: 30px;
    }
    
    .progress-step span:not(.step-number) {
        display: none;
    }
    
    .form-group-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .checkout-card {
        padding: 20px;
    }
    
    .payment-methods-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .sticky-mobile-summary {
        display: block;
    }
    
    .place-order-section {
        display: none;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 12px;
    }
    
    .payment-methods-grid {
        grid-template-columns: 1fr;
    }
    
    .summary-item-img {
        width: 60px;
        height: 60px;
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
    }
    
    .success-title {
        font-size: 1.5rem;
    }
}

/* ===== SCROLLBAR STYLING ===== */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 3px;
}

/* ===== LOADING STATE ===== */
.btn-loading {
    position: relative;
    color: transparent !important;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spinner 0.8s linear infinite;
}

@keyframes spinner {
    to { transform: rotate(360deg); }
}

/* ✅ NEW: Address Preview Box */
.address-preview {
    background: #f0f9ff;
    border: 2px solid #bae6fd;
    border-radius: var(--radius-sm);
    padding: 16px;
    margin-top: 20px;
    font-size: 0.875rem;
    line-height: 1.6;
}

.address-preview strong {
    color: var(--primary);
    display: block;
    margin-bottom: 8px;
    font-size: 0.9375rem;
}
</style>

<div class="checkout-wrapper">
    <div class="container">
        <?php if ($success): ?>
            <div class="success-container" data-aos="zoom-in">
                <div class="success-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <h2 class="success-title">Order Placed Successfully!</h2>
                <p class="success-message">
                    Thank you for your purchase. Your order has been confirmed and will be processed shortly.
                </p>
                <div class="order-number">#<?php echo htmlspecialchars($orderNumber); ?></div>
                <a href="index.php" class="continue-btn">
                    Continue Shopping
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        <?php else: ?>
            
            <!-- Progress Steps -->
            <div class="checkout-progress" data-aos="fade-down">
                <div class="progress-step completed">
                    <span class="step-number">✓</span>
                    <span>Cart</span>
                </div>
                <div class="step-line completed"></div>
                <div class="progress-step active">
                    <span class="step-number">2</span>
                    <span>Checkout</span>
                </div>
                <div class="step-line"></div>
                <div class="progress-step">
                    <span class="step-number">3</span>
                    <span>Confirmation</span>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="error-alert" data-aos="shake">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- ✅ CRITICAL FIX: Form tag properly structured -->
            <form method="POST" action="" id="checkout-form" class="checkout-container" novalidate>
                <div class="checkout-main">
                    <!-- Contact Details -->
                    <div class="checkout-card" data-aos="fade-up">
                        <h3 class="checkout-section-title">
                            <span class="section-icon">📞</span>
                            Contact Details
                        </h3>
                        <div class="form-group-grid">
                            <div class="form-group">
                                <label class="form-label">Phone Number <span class="required">*</span></label>
                                <input type="tel" name="phone" class="form-control" placeholder="Enter 10-digit mobile number" 
                                       value="<?php echo htmlspecialchars($formData['phone']); ?>" 
                                       required pattern="[0-9]{10}" maxlength="10" inputmode="numeric" id="phone">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="your@email.com" 
                                       value="<?php echo htmlspecialchars($formData['email']); ?>" id="email">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" 
                                   value="<?php echo htmlspecialchars($formData['name']); ?>" required id="name">
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="checkout-card" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="checkout-section-title">
                            <span class="section-icon">📍</span>
                            Shipping Address
                        </h3>
                        
                        <div class="form-group">
                            <label class="form-label">House No., Building, Street <span class="required">*</span></label>
                            <input type="text" name="address" class="form-control" placeholder="House No., Building Name, Street Address" 
                                   value="<?php echo htmlspecialchars($formData['address']); ?>" required id="address">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Apartment, Suite, Floor (Optional)</label>
                            <input type="text" name="address_2" class="form-control" placeholder="Apartment, Suite, Floor, etc." 
                                   value="<?php echo htmlspecialchars($formData['address_2']); ?>" id="address_2">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Landmark (Optional)</label>
                            <input type="text" name="landmark" class="form-control" placeholder="Nearby landmark for easy delivery" 
                                   value="<?php echo htmlspecialchars($formData['landmark']); ?>" id="landmark">
                        </div>

                        <div class="form-group-grid">
                            <div class="form-group">
                                <label class="form-label">PIN Code <span class="required">*</span></label>
                                <input type="text" name="pincode" class="form-control" placeholder="6-digit PIN" 
                                       value="<?php echo htmlspecialchars($formData['pincode']); ?>" 
                                       required pattern="[0-9]{6}" maxlength="6" inputmode="numeric" id="pincode">
                            </div>
                            <div class="form-group">
                                <label class="form-label">City <span class="required">*</span></label>
                                <input type="text" name="city" class="form-control" placeholder="City name" 
                                       value="<?php echo htmlspecialchars($formData['city']); ?>" required id="city">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">State <span class="required">*</span></label>
                            <input type="text" name="state" class="form-control" placeholder="State name" 
                                   value="<?php echo htmlspecialchars($formData['state']); ?>" required id="state">
                        </div>

                        <div class="address-preview" id="addressPreview" style="display: none;">
                            <strong>📦 Delivery Address Preview:</strong>
                            <div id="previewContent"></div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-card" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="checkout-section-title">
                            <span class="section-icon">💳</span>
                            Payment Method
                        </h3>
                        <div class="payment-methods-grid">
                            <label class="payment-method-item active">
                                <input type="radio" name="payment_method" value="cashfree" checked onchange="updatePaymentUI(this)">
                                <span class="method-icon">💳</span>
                                <span class="method-name">Cashfree</span>
                                <span class="method-desc">Cards, UPI, NetBanking</span>
                                <span class="check-icon">✓</span>
                            </label>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="trust-section" data-aos="fade-up" data-aos-delay="300">
                        <div class="trust-title">Secure Checkout Guaranteed</div>
                        <div class="trust-icons">
                            <div class="trust-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span>SSL Secure</span>
                            </div>
                            <div class="trust-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                                <span>100% Safe</span>
                            </div>
                            <div class="trust-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                </svg>
                                <span>Fast Delivery</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="checkout-sidebar" data-aos="fade-left">
                    <div class="order-summary-card">
                        <div class="summary-header">
                            <h3>Order Summary</h3>
                            <span class="item-count"><?php echo count($cartItems); ?> items in cart</span>
                        </div>
                        
                        <div class="summary-items">
                            <?php foreach ($cartItems as $item): 
                                $itemTotal = $item['price'] * $item['quantity'];
                                $itemOriginal = ($item['original_price'] ?? $item['price']) * $item['quantity'];
                                $itemSavings = $itemOriginal - $itemTotal;
                                $hasDiscount = $itemSavings > 0;
                            ?>
                                <div class="summary-item">
                                    <img src="uploads/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         class="summary-item-img" 
                                         onerror="this.src='assets/images/placeholder.jpg'">
                                    <div class="summary-item-info">
                                        <div class="summary-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                        <div class="summary-item-meta">Qty: <?php echo $item['quantity']; ?></div>
                                        <div class="item-price-breakdown">
                                            <span class="item-final-price">₹<?php echo number_format($itemTotal, 2); ?></span>
                                            <?php if ($hasDiscount): ?>
                                                <span class="item-original-price">₹<?php echo number_format($itemOriginal, 2); ?></span>
                                                <span class="item-savings">Save ₹<?php echo number_format($itemSavings, 2); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Promo Code -->
                        <div class="promo-section">
                            <button type="button" class="promo-toggle" onclick="togglePromo()">
                                <span>Have a promo code?</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <div class="promo-input-wrapper" id="promoWrapper">
                                <input type="text" name="promo_code" class="promo-input" placeholder="Enter code" maxlength="20">
                                <button type="button" class="promo-apply-btn" onclick="applyPromo()">Apply</button>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="price-breakdown" data-subtotal="<?php echo $subtotal; ?>">
                            <div class="price-row">
                                <span class="label">Subtotal</span>
                                <span class="value">₹<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            
                            <?php if ($totalSavings > 0): ?>
                            <div class="price-row savings">
                                <span class="label">Product Savings</span>
                                <span class="value">-₹<?php echo number_format($totalSavings, 2); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="price-row">
                                <span class="label">Shipping</span>
                                <span class="value <?php echo $shipping == 0 ? 'free-shipping' : ''; ?>" style="<?php echo $shipping == 0 ? 'color: var(--success);' : ''; ?>">
                                    <?php echo $shipping == 0 ? 'FREE' : '₹' . number_format($shipping, 2); ?>
                                </span>
                            </div>
                            
                            <!-- Promo Discount Row (hidden until applied) -->
                            <div class="price-row discount" id="promo-discount-row" style="display:none;">
                                <span class="label">🏷 Promo Discount</span>
                                <span class="value" id="promo-discount-display" style="color:#059669;font-weight:700;"></span>
                            </div>
                            
                            <div class="price-divider"></div>
                            
                            <div class="price-row total">
                                <span class="label">Total Payable</span>
                                <span class="value" id="order-total-display">₹<?php echo number_format($total, 2); ?></span>
                            </div>
                            
                            <?php if ($totalSavings > 0): ?>
                            <div class="savings-highlight">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                </svg>
                                <span>You saved ₹<?php echo number_format($totalSavings, 2); ?> on this order!</span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- ✅ CRITICAL FIX: Place Order Button -->
                        <div class="place-order-section">
                            <button type="submit" class="place-order-btn" id="placeOrderBtn">
                                <span>Place Order</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
                            
                            <div class="security-badges">
                                <div class="badge-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <span>Secure Payment</span>
                                </div>
                                <div class="badge-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                    <span>Data Protected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- ✅ CRITICAL FIX: Sticky Mobile Summary - onclick fixed -->
            <div class="sticky-mobile-summary" id="stickySummary">
                <div class="sticky-content">
                    <div class="sticky-price">
                        <span class="sticky-total">₹<?php echo number_format($total, 2); ?></span>
                        <span class="sticky-text">Total Payable</span>
                    </div>
                    <!-- ✅ FIXED: Direct form submit instead of function call -->
                    <button type="button" class="sticky-btn" id="mobilePlaceOrderBtn">
                        Place Order
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// ✅ CRITICAL FIX: Wrap everything in DOMContentLoaded and use unique namespace
document.addEventListener('DOMContentLoaded', function() {
    
    // ✅ CRITICAL FIX: Check if main.js has validateForm, if yes, override it for checkout page
    if (typeof window.validateForm !== 'undefined') {
        console.log('main.js validateForm detected, overriding for checkout page');
    }
    
    // ✅ UNIQUE NAMESPACE for checkout functions to avoid conflicts
    window.CheckoutApp = {
        
        // Form validation function
        validateCheckoutForm: function() {
            const requiredFields = ['name', 'phone', 'address', 'city', 'state', 'pincode'];
            let isValid = true;
            let firstError = null;
            
            // Remove all previous error states
            document.querySelectorAll('.form-control').forEach(field => {
                field.classList.remove('error');
            });
            
            requiredFields.forEach(field => {
                const element = document.getElementById(field);
                if (!element) return;
                
                const value = element.value.trim();
                if (!value) {
                    element.classList.add('error');
                    isValid = false;
                    if (!firstError) firstError = element;
                }
            });
            
            // Phone validation
            const phone = document.getElementById('phone');
            if (phone && phone.value) {
                if (!/^[0-9]{10}$/.test(phone.value)) {
                    phone.classList.add('error');
                    isValid = false;
                    if (!firstError) firstError = phone;
                }
            }
            
            // PIN validation
            const pincode = document.getElementById('pincode');
            if (pincode && pincode.value) {
                if (!/^[0-9]{6}$/.test(pincode.value)) {
                    pincode.classList.add('error');
                    isValid = false;
                    if (!firstError) firstError = pincode;
                }
            }
            
            if (!isValid && firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                alert('Please fill in all required fields correctly.');
                return false;
            }
            
            return true;
        },
        
        // Submit form function
        submitCheckoutForm: function() {
            const form = document.getElementById('checkout-form');
            const btn = document.getElementById('placeOrderBtn');
            const mobileBtn = document.getElementById('mobilePlaceOrderBtn');
            
            if (!form) {
                console.error('Checkout form not found!');
                return false;
            }
            
            // Validate form first
            if (!this.validateCheckoutForm()) {
                return false;
            }
            
            // Show loading state on both buttons
            if (btn) {
                btn.classList.add('btn-loading');
                btn.disabled = true;
            }
            if (mobileBtn) {
                mobileBtn.textContent = 'Processing...';
                mobileBtn.disabled = true;
            }
            
            // Submit form
            form.submit();
        },
        
        // Live Address Preview Update
        updateAddressPreview: function() {
            const getVal = (id) => {
                const el = document.getElementById(id);
                return el ? el.value : '';
            };
            
            const name = getVal('name');
            const phone = getVal('phone');
            const address = getVal('address');
            const address2 = getVal('address_2');
            const landmark = getVal('landmark');
            const city = getVal('city');
            const state = getVal('state');
            const pincode = getVal('pincode');
            
            const previewBox = document.getElementById('addressPreview');
            const previewContent = document.getElementById('previewContent');
            
            if (!previewBox || !previewContent) return;
            
            if (address || city || pincode) {
                let html = '';
                if (name) html += `<strong>${this.escapeHtml(name)}</strong><br>`;
                if (phone) html += `📞 ${this.escapeHtml(phone)}<br><br>`;
                if (address) html += `${this.escapeHtml(address)}<br>`;
                if (address2) html += `${this.escapeHtml(address2)}<br>`;
                if (landmark) html += `<em>Near: ${this.escapeHtml(landmark)}</em><br>`;
                if (city || state || pincode) {
                    html += `<br>${this.escapeHtml(city)}${city && state ? ', ' : ''}${this.escapeHtml(state)}${(city || state) && pincode ? ' - ' : ''}${this.escapeHtml(pincode)}`;
                }
                
                previewContent.innerHTML = html;
                previewBox.style.display = 'block';
            } else {
                previewBox.style.display = 'none';
            }
        },
        
        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },
        
        // Payment Method UI Update
        updatePaymentUI: function(radio) {
            document.querySelectorAll('.payment-method-item').forEach(item => {
                item.classList.remove('active');
            });
            if (radio && radio.closest) {
                radio.closest('.payment-method-item').classList.add('active');
            }
        },
        
        // Promo Code Toggle
        togglePromo: function() {
            const wrapper = document.getElementById('promoWrapper');
            const toggle = document.querySelector('.promo-toggle');
            if (wrapper && toggle) {
                wrapper.classList.toggle('active');
                toggle.classList.toggle('active');
            }
        },
        
        // Apply Promo Code via AJAX
        applyPromo: function() {
            const input = document.querySelector('.promo-input');
            const btn = document.querySelector('.promo-apply-btn');
            const promoSection = document.querySelector('.promo-section');
            
            if (!input || !btn) return;
            
            const code = input.value.trim().toUpperCase();
            if (!code) {
                input.style.borderColor = '#ef4444';
                input.placeholder = 'Code daalo pehle!';
                setTimeout(() => { input.style.borderColor = ''; input.placeholder = 'Enter code'; }, 2000);
                return;
            }

            // Remove previous messages
            const existing = promoSection ? promoSection.querySelector('.promo-msg') : null;
            if (existing) existing.remove();

            btn.textContent = 'Checking...';
            btn.disabled = true;
            input.disabled = true;

            // Get current cart subtotal from page
            const subtotalEl = document.querySelector('[data-subtotal]');
            const cartTotal = subtotalEl ? parseFloat(subtotalEl.getAttribute('data-subtotal')) : 0;

            const formData = new FormData();
            formData.append('code', code);
            formData.append('cart_total', cartTotal);

            fetch('ajax/apply_promo.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                const msgDiv = document.createElement('div');
                msgDiv.className = 'promo-msg';
                
                if (data.success) {
                    input.value = code;
                    btn.textContent = '✓ Applied';
                    btn.style.background = '#059669';
                    btn.style.color = '#fff';
                    input.style.borderColor = '#059669';
                    input.disabled = true;
                    btn.disabled = true;
                    
                    msgDiv.style.cssText = 'margin-top:8px;padding:8px 12px;background:#d1fae5;color:#065f46;border-radius:6px;font-size:13px;font-weight:600;';
                    msgDiv.innerHTML = data.message;

                    // Show promo discount row and update amount
                    const discountRow = document.getElementById('promo-discount-row');
                    const discountEl = document.getElementById('promo-discount-display');
                    if (discountRow && discountEl) {
                        discountEl.textContent = '-₹' + parseFloat(data.discount_amount).toLocaleString('en-IN', {minimumFractionDigits:2});
                        discountRow.style.display = '';
                    }
                    // Update total display (main + sticky mobile)
                    const totalEl = document.getElementById('order-total-display');
                    const newTotalFormatted = '₹' + parseFloat(data.new_total).toLocaleString('en-IN', {minimumFractionDigits:2});
                    if (totalEl) {
                        totalEl.textContent = newTotalFormatted;
                    }
                    // Update sticky mobile total if present
                    const stickyTotal = document.querySelector('.sticky-total');
                    if (stickyTotal) {
                        stickyTotal.textContent = newTotalFormatted;
                    }
                } else {
                    btn.textContent = 'Apply';
                    btn.disabled = false;
                    input.disabled = false;
                    input.style.borderColor = '#ef4444';
                    
                    msgDiv.style.cssText = 'margin-top:8px;padding:8px 12px;background:#fee2e2;color:#991b1b;border-radius:6px;font-size:13px;font-weight:600;';
                    msgDiv.textContent = data.message;
                    
                    setTimeout(() => { input.style.borderColor = ''; }, 3000);
                }
                
                if (promoSection) promoSection.appendChild(msgDiv);
            })
            .catch(() => {
                btn.textContent = 'Apply';
                btn.disabled = false;
                input.disabled = false;
                const msgDiv = document.createElement('div');
                msgDiv.className = 'promo-msg';
                msgDiv.style.cssText = 'margin-top:8px;padding:8px 12px;background:#fee2e2;color:#991b1b;border-radius:6px;font-size:13px;';
                msgDiv.textContent = 'Network error. Dobara try karo.';
                if (promoSection) promoSection.appendChild(msgDiv);
            });
        },
        
        // Form Validation on blur
        validateField: function(field) {
            const value = field.value.trim();
            const pattern = field.getAttribute('pattern');
            const required = field.hasAttribute('required');
            
            let isValid = true;
            
            if (required && !value) {
                isValid = false;
            } else if (pattern && value) {
                const regex = new RegExp(pattern);
                isValid = regex.test(value);
            }
            
            const existingMessage = field.parentElement.querySelector('.validation-message');
            if (existingMessage) existingMessage.remove();
            
            if (isValid) {
                field.classList.remove('error');
                field.classList.add('success');
            } else {
                field.classList.remove('success');
                field.classList.add('error');
            }
            
            return isValid;
        },
        
        // Sticky Mobile Summary
        initStickySummary: function() {
            const stickySummary = document.getElementById('stickySummary');
            if (!stickySummary) return;
            
            window.addEventListener('scroll', () => {
                const scrollY = window.scrollY;
                const formContainer = document.querySelector('.checkout-container');
                if (!formContainer) return;
                
                const formHeight = formContainer.offsetHeight;
                
                if (scrollY > 300 && scrollY < formHeight - 800) {
                    stickySummary.classList.add('visible');
                } else {
                    stickySummary.classList.remove('visible');
                }
            });
        },
        
        // Input Masks
        initInputMasks: function() {
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/\D/g, '').slice(0, 10);
                });
            }
            
            const pincodeInput = document.getElementById('pincode');
            if (pincodeInput) {
                pincodeInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/\D/g, '').slice(0, 6);
                });
                
                // Auto-fill city/state from PIN
                pincodeInput.addEventListener('blur', function() {
                    const pin = this.value;
                    if (pin.length === 6) {
                        fetch(`https://api.postalpincode.in/pincode/${pin}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data[0].Status === 'Success' && data[0].PostOffice.length > 0) {
                                    const postOffice = data[0].PostOffice[0];
                                    const cityInput = document.getElementById('city');
                                    const stateInput = document.getElementById('state');
                                    
                                    if (cityInput && (!cityInput.value || cityInput.dataset.autoFilled === 'true')) {
                                        cityInput.value = postOffice.District;
                                        cityInput.dataset.autoFilled = 'true';
                                        cityInput.style.background = '#f0fdf4';
                                        setTimeout(() => cityInput.style.background = '', 1000);
                                    }
                                    
                                    if (stateInput && (!stateInput.value || stateInput.dataset.autoFilled === 'true')) {
                                        stateInput.value = postOffice.State;
                                        stateInput.dataset.autoFilled = 'true';
                                        stateInput.style.background = '#f0fdf4';
                                        setTimeout(() => stateInput.style.background = '', 1000);
                                    }
                                    
                                    window.CheckoutApp.updateAddressPreview();
                                }
                            })
                            .catch(err => console.log('PIN lookup failed:', err));
                    }
                });
            }
        },
        
        // Initialize all event listeners
        init: function() {
            // Form submit handler
            const form = document.getElementById('checkout-form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    if (!this.validateCheckoutForm()) {
                        e.preventDefault();
                        return false;
                    }
                    
                    const btn = document.getElementById('placeOrderBtn');
                    if (btn) {
                        btn.classList.add('btn-loading');
                        btn.disabled = true;
                    }
                });
            }
            
            // Mobile button click handler - ✅ CRITICAL FIX
            const mobileBtn = document.getElementById('mobilePlaceOrderBtn');
            if (mobileBtn) {
                mobileBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.submitCheckoutForm();
                });
            }
            
            // Address preview listeners
            ['name', 'phone', 'address', 'address_2', 'landmark', 'city', 'state', 'pincode'].forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('input', () => this.updateAddressPreview());
                    element.addEventListener('blur', () => this.updateAddressPreview());
                }
            });
            
            // Form validation on blur
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        window.CheckoutApp.validateField(this);
                    }
                });
            });
            
            this.initStickySummary();
            this.initInputMasks();
            this.updateAddressPreview();
        }
    };
    
    // Initialize the checkout app
    window.CheckoutApp.init();
    
    // ✅ GLOBAL FUNCTIONS for inline onclick handlers
    window.validateForm = function() {
        return window.CheckoutApp.validateCheckoutForm();
    };
    
    window.submitCheckoutForm = function() {
        return window.CheckoutApp.submitCheckoutForm();
    };
    
    window.updatePaymentUI = function(radio) {
        return window.CheckoutApp.updatePaymentUI(radio);
    };
    
    window.togglePromo = function() {
        return window.CheckoutApp.togglePromo();
    };
    
    window.applyPromo = function() {
        return window.CheckoutApp.applyPromo();
    };
    
});
</script>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 600,
        once: true,
        offset: 50
    });
</script>

<?php require_once 'includes/footer.php'; ?>