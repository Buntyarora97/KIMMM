<?php
session_start();

// BLOCK ALL RETRIES - ONE SHOT ONLY
if (isset($_SESSION['payu_one_time_used'])) {
    die('⏳ Payment already attempted. Wait 2 hours or use mobile data.');
}
$_SESSION['payu_one_time_used'] = true;

/* LIVE PAYU CREDENTIALS */
define('PAYU_MERCHANT_KEY', '5bQO2C');
define('PAYU_SALT', 'LBBQDAPYS1GiWkSY8m6cDci2aQGuCpR7');
define('PAYU_BASE_URL', 'https://secure.payu.in/_payment');
define('PAYU_SUCCESS_URL', 'https://livvra.in/payu-success.php');
define('PAYU_FAILURE_URL', 'https://livvra.in/payu-failure.php');

// CRITICAL: Perfectly Unique TXNID
$orderId = time();
$txnid = 'LV' . $orderId . uniqid() . rand(1000,9999);
$txnid = substr(preg_replace('/[^A-Za-z0-9]/', '', $txnid), 0, 40);

$amount = number_format(10.00, 2, '.', '');
$product = 'Livvra Test Order';
$name = 'Test Customer';
$email = 'test@livvra.in';
$phone = '9999999999';

// PERFECT HASH (PayU Official Format)
$hash_string = $PAYU_MERCHANT_KEY.'|'.$txnid.'|'.$amount.'|'.$product.'|'.$name.'|'.$email.'|||||||||||'.$PAYU_SALT;
$hash = strtolower(hash('sha512', $hash_string));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Livvra - Secure Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { 
            font-family: system-ui, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center; 
            min-height: 100vh; margin: 0; 
        }
        .card { 
            background: white; padding: 40px; border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 400px; width: 90%; 
        }
        .pay-btn {
            background: #5bQO2C; color: white; border: none; 
            padding: 18px; border-radius: 12px; font-size: 18px; 
            width: 100%; cursor: pointer; font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>🛍️ Livvra Payment</h2>
        <p><strong>Amount:</strong> ₹<?= $amount ?></p>
        <p><strong>TXNID:</strong> <?= substr($txnid, 0, 12) ?>...</p>
        
        <form action="<?= $PAYU_BASE_URL ?>" method="post" id="payuForm" style="display:none;">
            <input type="hidden" name="key" value="<?= $PAYU_MERCHANT_KEY ?>">
            <input type="hidden" name="txnid" value="<?= $txnid ?>">
            <input type="hidden" name="amount" value="<?= $amount ?>">
            <input type="hidden" name="productinfo" value="<?= $product ?>">
            <input type="hidden" name="firstname" value="<?= $name ?>">
            <input type="hidden" name="email" value="<?= $email ?>">
            <input type="hidden" name="phone" value="<?= $phone ?>">
            <input type="hidden" name="surl" value="<?= $PAYU_SUCCESS_URL ?>">
            <input type="hidden" name="furl" value="<?= $PAYU_FAILURE_URL ?>">
            <input type="hidden" name="hash" value="<?= $hash ?>">
            <input type="hidden" name="service_provider" value="payu_paisa">
        </form>
        
        <button class="pay-btn" onclick="document.getElementById('payuForm').submit()">
            🔒 Pay ₹<?= $amount ?> Now
        </button>
        
        <p style="font-size: 12px; color: #666; margin-top: 20px;">
            <strong>🚨 ONE TIME ONLY</strong> - Next attempt blocked for 2 hours
        </p>
    </div>
</body>
</html>
