<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$orderId = $_GET['order_id'] ?? ($_SESSION['pending_order_id'] ?? 0);
if (!$orderId) {
    header('Location: checkout.php');
    exit;
}

$order = Order::getById($orderId);
if (!$order) {
    header('Location: checkout.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utr = trim($_POST['utr_reference'] ?? '');
    if (!empty($utr)) {
        // Update order with UTR and set status to pending_verification
        Order::updatePaymentStatus($orderId, 'pending_verification', [
            'transaction_id' => $utr
        ]);
        Order::updateStatus($orderId, 'processing');
        
        unset($_SESSION['cart']);
        header('Location: order-success.php?order_id=' . $orderId);
        exit;
    }
}

$pageTitle = 'UPI Payment';
require_once 'includes/header.php';
?>

<div class="container" style="padding: 60px 20px; max-width: 600px; margin: 0 auto;">
    <div style="background: #fff; padding: 40px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center;">
        <h2 style="font-family: 'Playfair Display', serif; color: #3A6147; margin-bottom: 20px;">UPI Payment</h2>
        <p style="margin-bottom: 25px; color: #666;">Scan QR or pay to UPI ID below</p>
        
        <div style="background: #f9f9f9; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
            <div style="font-size: 1.2rem; font-weight: 700; color: #333; margin-bottom: 5px;"><?php echo defined('UPI_HOLDER_NAME') ? e(UPI_HOLDER_NAME) : 'Livvra'; ?></div>
            <div style="font-size: 1.1rem; color: #C9A227; font-weight: 600;"><?php echo defined('UPI_ID') ? e(UPI_ID) : '9953835017@ybl'; ?></div>
        </div>

        <div style="font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 30px;">
            Amount to Pay: ₹<?php echo number_format($order['total_amount'], 2); ?>
        </div>

        <form method="POST">
            <div style="margin-bottom: 20px; text-align: left;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Enter Transaction ID / UTR Number</label>
                <input type="text" name="utr_reference" placeholder="Enter 12 digit UTR number" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            <button type="submit" style="width: 100%; background: #4A7C59; color: #fff; padding: 15px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer;">Submit & Confirm Order</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>