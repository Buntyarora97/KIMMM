<?php
session_start();

// SECURITY CHECK - AGAR SESSION NA HO TOH HOME PE BHEJO
if (!isset($_SESSION['order_success'])) {
    header("Location: index.php");
    exit();
}

// SESSION SE DATA NIKALO
$orderData = $_SESSION['order_success'];
$orderId = $orderData['order_id'];
$cf_order_id = $orderData['cf_order_id'];
$payment = $orderData['payment_details'] ?? [];
$order = $orderData['order_data'] ?? [];
$successTime = $orderData['success_time'];

// FORMAT DATA
$orderDate = date('F j, Y', strtotime($successTime));
$orderTime = date('g:i A', strtotime($successTime));
$paymentMethod = $payment['payment_method'] ?? 'Online Payment';
$paymentGroup = $payment['payment_group'] ?? 'UPI/CARD/NETBANKING';
$transactionId = $payment['cf_payment_id'] ?? $cf_order_id;
$paymentAmount = $payment['payment_amount'] ?? $order['total_amount'] ?? 0;

// ORDER ITEMS (AGAR AVAILABLE HO TOH)
$orderItems = $order['items'] ?? [];
$subtotal = $order['subtotal'] ?? 0;
$shippingCost = $order['shipping_cost'] ?? 0;
$discount = $order['discount'] ?? 0;
$totalAmount = $order['total_amount'] ?? $paymentAmount;

// CUSTOMER DETAILS
$customerName = $order['customer_name'] ?? 'Valued Customer';
$customerEmail = $order['customer_email'] ?? '';
$customerPhone = $order['customer_phone'] ?? '';
$shippingAddress = $order['shipping_address'] ?? '';

// SESSION CLEAR (OPTIONAL - COMMENT KAR DO AGAR REFRESH CHAHIYE)
// unset($_SESSION['order_success']);

// FACEBOOK PIXEL TRACKING - SERVER SIDE
$access_token = "EAA8TQJDUNtwBQxNFN0lRIHFBoLDmus5P7TXSQdKhELokczwsLdSUYGL3Uv44iAMTH0pPoZBS6WpSYmUp8KYbwBTpFxzIFVUFzWCgFoR8CV04eXZCQ1R1AEZCfv4GB7PQpVcmbS7rI7StcMSPNa0vm4RkWSjFZCeSz7ZCrBkZAG9HMmPU0D3P15GIEFhXiuwHDPlgZDZD";
$pixel_id = "YOUR_PIXEL_ID";

$data = [
  "data" => [
    [
      "event_name" => "Purchase",
      "event_time" => time(),
      "event_id" => $orderId,
      "action_source" => "website",
      "user_data" => [
        "client_ip_address" => $_SERVER['REMOTE_ADDR'],
        "client_user_agent" => $_SERVER['HTTP_USER_AGENT']
      ],
      "custom_data" => [
        "currency" => "INR",
        "value" => $totalAmount,
        "order_id" => $orderId
      ]
    ]
  ]
];

$url = "https://graph.facebook.com/v18.0/$pixel_id/events?access_token=$access_token";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
curl_close($ch);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | Thank You</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            overflow: hidden;
            animation: slideUp 0.6s ease;
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
        
        /* SUCCESS HEADER */
        .success-header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }
        
        .check-circle {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 45px;
            color: #11998e;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        @keyframes popIn {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .success-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .success-header p {
            font-size: 16px;
            opacity: 0.95;
        }
        
        /* ORDER CONTENT */
        .order-content {
            padding: 40px;
        }
        
        /* INFO GRID */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #C9A227;
            transition: transform 0.2s;
        }
        
        .info-box:hover {
            transform: translateX(5px);
        }
        
        .info-box label {
            display: block;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            font-weight: 600;
        }
        
        .info-box strong {
            color: #333;
            font-size: 18px;
            display: block;
        }
        
        .info-box .small {
            font-size: 13px;
            color: #888;
            margin-top: 4px;
        }
        
        /* PAYMENT SUCCESS BOX */
        .payment-success-box {
            background: #e8f5e9;
            border: 2px solid #4caf50;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .payment-success-box h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
        }
        
        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #a5d6a7;
        }
        
        .payment-row:last-child {
            border-bottom: none;
        }
        
        .payment-row span:first-child {
            color: #555;
        }
        
        .payment-row span:last-child {
            font-weight: 600;
            color: #333;
        }
        
        .payment-row.success span:last-child {
            color: #4caf50;
            font-weight: 700;
        }
        
        /* ORDER ITEMS TABLE */
        .items-section {
            margin-bottom: 30px;
        }
        
        .items-section h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #C9A227;
            padding-bottom: 10px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .items-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }
        
        .items-table tr:hover {
            background: #fafafa;
        }
        
        .product-name {
            font-weight: 600;
        }
        
        .product-variant {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
        }
        
        /* TOTAL BOX */
        .total-box {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #555;
        }
        
        .total-row.discount span:last-child {
            color: #4caf50;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #C9A227;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 22px;
            font-weight: 700;
            color: #333;
        }
        
        /* SHIPPING BOX */
        .shipping-box {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .shipping-box h4 {
            color: #e65100;
            margin-bottom: 12px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .shipping-box p {
            color: #555;
            line-height: 1.6;
            font-size: 15px;
        }
        
        .shipping-box .customer-name {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        
        /* EMAIL NOTICE */
        .email-notice {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .email-notice strong {
            color: #333;
        }
        
        /* ACTION BUTTONS */
        .actions {
            padding: 30px 40px;
            background: #f8f9fa;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #C9A227;
            color: white;
        }
        
        .btn-primary:hover {
            background: #b08d1f;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(201, 162, 39, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #333;
            border: 2px solid #ddd;
        }
        
        .btn-secondary:hover {
            border-color: #C9A227;
            color: #C9A227;
        }
        
        /* PRINT STYLES */
        @media print {
            body { background: white; }
            .actions { display: none; }
            .container { box-shadow: none; margin: 0; }
        }
        
        /* MOBILE RESPONSIVE */
        @media (max-width: 600px) {
            .info-grid { grid-template-columns: 1fr; }
            .container { margin: 10px; border-radius: 15px; }
            .order-content { padding: 25px; }
            .actions { padding: 20px; flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .success-header h1 { font-size: 24px; }
            .items-table { font-size: 14px; }
            .items-table th, .items-table td { padding: 10px 8px; }
        }
        
        /* CONFETTI ANIMATION */
        .confetti {
            position: fixed;
            width: 12px;
            height: 12px;
            background: #f0f;
            top: -10px;
            z-index: 1000;
            animation: fall linear forwards;
        }
        
        @keyframes fall {
            to { 
                transform: translateY(100vh) rotate(720deg); 
                opacity: 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- SUCCESS HEADER -->
    <div class="success-header">
        <div class="check-circle">✓</div>
        <h1>Thank You, <?php echo htmlspecialchars($customerName); ?>!</h1>
        <p>Your payment was successful and your order has been confirmed.</p>
    </div>
    
    <!-- ORDER CONTENT -->
    <div class="order-content">
        
        <!-- ORDER INFO GRID -->
        <div class="info-grid">
            <div class="info-box">
                <label>Order Number</label>
                <strong>#<?php echo htmlspecialchars($orderId); ?></strong>
            </div>
            <div class="info-box">
                <label>Order Date</label>
                <strong><?php echo $orderDate; ?></strong>
                <div class="small">at <?php echo $orderTime; ?></div>
            </div>
            <div class="info-box">
                <label>Payment Method</label>
                <strong><?php echo htmlspecialchars($paymentMethod); ?></strong>
                <div class="small"><?php echo htmlspecialchars($paymentGroup); ?></div>
            </div>
            <div class="info-box">
                <label>Transaction ID</label>
                <strong style="font-size: 14px;"><?php echo htmlspecialchars($transactionId); ?></strong>
            </div>
        </div>
        
        <!-- PAYMENT SUCCESS DETAILS -->
        <div class="payment-success-box">
            <h3>✓ Payment Confirmed</h3>
            <div class="payment-row success">
                <span>Payment Status</span>
                <span>SUCCESS</span>
            </div>
            <div class="payment-row">
                <span>Amount Paid</span>
                <span>₹<?php echo number_format($paymentAmount, 2); ?></span>
            </div>
            <div class="payment-row">
                <span>Transaction Reference</span>
                <span><?php echo htmlspecialchars($cf_order_id); ?></span>
            </div>
            <div class="payment-row">
                <span>Payment Time</span>
                <span><?php echo $orderTime; ?></span>
            </div>
        </div>
        
        <!-- ORDER ITEMS (AGAR ITEMS HO TOH DIKHE) -->
        <?php if (!empty($orderItems)): ?>
        <div class="items-section">
            <h3>Order Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): 
                        $itemTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                    ?>
                    <tr>
                        <td>
                            <div class="product-name"><?php echo htmlspecialchars($item['name'] ?? 'Product'); ?></div>
                            <?php if (!empty($item['variant'])): ?>
                            <div class="product-variant"><?php echo htmlspecialchars($item['variant']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $item['quantity'] ?? 1; ?></td>
                        <td>₹<?php echo number_format($item['price'] ?? 0, 2); ?></td>
                        <td>₹<?php echo number_format($itemTotal, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- ORDER TOTAL BREAKDOWN -->
        <div class="total-box">
            <div class="total-row">
                <span>Subtotal</span>
                <span>₹<?php echo number_format($subtotal, 2); ?></span>
            </div>
            <?php if ($discount > 0): ?>
            <div class="total-row discount">
                <span>Discount</span>
                <span>-₹<?php echo number_format($discount, 2); ?></span>
            </div>
            <?php endif; ?>
            <div class="total-row">
                <span>Shipping</span>
                <span><?php echo $shippingCost > 0 ? '₹'.number_format($shippingCost, 2) : 'FREE'; ?></span>
            </div>
            <div class="total-row grand-total">
                <span>Total Amount Paid</span>
                <span>₹<?php echo number_format($totalAmount, 2); ?></span>
            </div>
        </div>
        
        <!-- SHIPPING ADDRESS -->
        <?php if (!empty($shippingAddress)): ?>
        <div class="shipping-box">
            <h4>📦 Shipping Address</h4>
            <p class="customer-name"><?php echo htmlspecialchars($customerName); ?></p>
            <p><?php echo nl2br(htmlspecialchars($shippingAddress)); ?></p>
            <?php if (!empty($customerPhone)): ?>
            <p style="margin-top: 8px;"><strong>Phone:</strong> <?php echo htmlspecialchars($customerPhone); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- EMAIL CONFIRMATION -->
        <?php if (!empty($customerEmail)): ?>
        <div class="email-notice">
            📧 A confirmation email has been sent to <strong><?php echo htmlspecialchars($customerEmail); ?></strong>
        </div>
        <?php endif; ?>
        
    </div>
    
    <!-- ACTION BUTTONS -->
    <div class="actions">
        <a href="index.php" class="btn btn-primary">
            🛍️ Continue Shopping
        </a>
        <a href="orders.php" class="btn btn-secondary">
            📋 View My Orders
        </a>
        <button onclick="window.print()" class="btn btn-secondary">
            🖨️ Print Receipt
        </button>
    </div>
</div>

<!-- CONFETTI EFFECT -->
<script>
    function createConfetti() {
        const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff', '#C9A227', '#11998e'];
        
        for (let i = 0; i < 60; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 5000);
            }, i * 50);
        }
    }
    
    window.onload = createConfetti;
</script>

</body>
</html>