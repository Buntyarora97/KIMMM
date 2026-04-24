<?php
session_start();

/* =========================
   PAYU LIVE CONFIG (Your Credentials)
========================= */
define('PAYU_MERCHANT_KEY', '5bQO2C');
define('PAYU_SALT', 'LBBQDAPYS1GiWkSY8m6cDci2aQGuCpR7');
define('PAYU_BASE_URL', 'https://secure.payu.in/_payment');
define('PAYU_SUCCESS_URL', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'livvra.in') . '/payu-success.php');
define('PAYU_FAILURE_URL', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'livvra.in') . '/payu-failed.php');

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';

$orderId = $_GET['order_id'] ?? ($_SESSION['pending_order_id'] ?? 0);
$order = Order::getById($orderId);
if (!$order) {
    header('Location: cart.php');
    exit;
}

// Generate a clean transaction ID
$txnid = 'LIV' . $order['order_number'] . 'T' . time();
$amount = (float)$order['total_amount'];

/* =========================
   PERFECT HASH (PayU Exact Format)
========================= */
$hashSequence = PAYU_MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|Order #' . 
              $order['order_number'] . '|' . $order['customer_name'] . '|' . 
              $order['customer_email'] . '|||||||||||' . PAYU_SALT;

$hash = strtolower(hash('sha512', $hashSequence));
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay ₹<?= $amount ?> - Livvra</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #FDFBF0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            max-width: 420px;
            width: 90%;
            text-align: center;
            border: 1px solid #eee;
        }
        .loader {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #C9A227;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .amount { 
            font-size: 32px; 
            font-weight: bold; 
            color: #4A7C59; 
            margin: 10px 0;
        }
        .pay-button {
            background: #4A7C59;
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            margin-top: 20px;
        }
        .pay-button:hover {
            background: #3d664a;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #4A7C59; font-size: 1.5rem; margin-bottom: 20px;">Livvra Wellness</h2>
        <div class="loader" id="loader"></div>
        <p id="msg">Initializing secure payment...</p>
        
        <div style="background: #fcfaf0; padding: 20px; border-radius: 12px; margin: 20px 0; border: 1px solid #f0e6c0;">
            <div style="font-size: 0.9rem; color: #666;">Order Amount</div>
            <div class="amount">₹<?= number_format($amount, 2) ?></div>
            <div style="font-weight: 600; color: #333;">Order #<?= $order['order_number'] ?></div>
        </div>

        <form id="payuForm" action="<?= PAYU_BASE_URL ?>" method="post">
            <input type="hidden" name="key" value="<?= PAYU_MERCHANT_KEY ?>">
            <input type="hidden" name="txnid" value="<?= $txnid ?>">
            <input type="hidden" name="amount" value="<?= $amount ?>">
            <input type="hidden" name="productinfo" value="Order #<?= $order['order_number'] ?>">
            <input type="hidden" name="firstname" value="<?= $order['customer_name'] ?>">
            <input type="hidden" name="email" value="<?= $order['customer_email'] ?>">
            <input type="hidden" name="phone" value="<?= $order['customer_phone'] ?>">
            <input type="hidden" name="surl" value="<?= PAYU_SUCCESS_URL ?>">
            <input type="hidden" name="furl" value="<?= PAYU_FAILURE_URL ?>">
            <input type="hidden" name="hash" value="<?= $hash ?>">
            <input type="hidden" name="service_provider" value="payu_paisa">
            
            <button type="submit" class="pay-button" id="payBtn" style="display: none;">
                Pay Securely via PayU
            </button>
        </form>
    </div>

    <script>
    window.onload = function() {
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('msg').innerText = 'Redirecting to PayU...';
            document.getElementById('payuForm').submit();
        }, 1500);
    };
    </script>
</body>
</html>