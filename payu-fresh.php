<?php
// SESSION BYPASS - Direct execution
session_start();
// Clear ALL PayU session data
$_SESSION = []; // Complete session reset

/* LIVE PAYU - FRESH START */
define('PAYU_MERCHANT_KEY', '5bQO2C');
define('PAYU_SALT', 'LBBQDAPYS1GiWkSY8m6cDci2aQGuCpR7');
define('PAYU_BASE_URL', 'https://secure.payu.in/_payment');
define('PAYU_SUCCESS_URL', 'https://livvra.in/payu-success.php');
define('PAYU_FAILURE_URL', 'https://livvra.in/payu-failure.php');

// ULTRA UNIQUE TXNID - PayU Guaranteed Accept
$timestamp = microtime(true);
$txnid = 'LV' . substr(md5($timestamp . rand()), 8, 16) . '_' . substr($timestamp, -6, 6);

$amount = '10.00'; // Fixed test amount
$product = 'Livvra Order';
$name = 'TEST';
$email = 'test@livvra.in';
$phone = '9999999999';

// PERFECT PayU HASH
$hash_string = PAYU_MERCHANT_KEY.'|'.$txnid.'|'.$amount.'|'.$product.'|'.$name.'|'.$email.'|||||||||||'.PAYU_SALT;
$hash = strtolower(hash('sha512', $hash_string));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Livvra - Pay ₹10</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:system-ui,sans-serif;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;min-height:100vh;}
        .paybox{background:white;padding:30px;border-radius:16px;box-shadow:0 15px 35px rgba(0,0,0,0.2);max-width:380px;width:95%;text-align:center;}
        .amount{font-size:28px;font-weight:bold;color:#2d3748;margin:15px 0;}
        .paybtn{background:#5bQO2C;color:white;border:none;padding:16px 30px;border-radius:10px;font-size:16px;font-weight:600;cursor:pointer;width:100%;margin:20px 0;transition:all 0.3s;}
        .paybtn:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(91,162,44,0.4);}
        .txnid{font-family:monospace;font-size:12px;color:#666;padding:10px;background:#f8f9fa;border-radius:6px;margin:15px 0;}
    </style>
</head>
<body>
    <div class="paybox">
        <h2>🛍️ Livvra Payment</h2>
        
        <div class="amount">₹<?= $amount ?></div>
        <div class="txnid">TXNID: <?= $txnid ?></div>
        
        <p><strong>Status:</strong> <span style="color:green">✅ READY</span></p>
        
        <form action="<?= PAYU_BASE_URL ?>" method="post" style="display:none;" id="payForm">
            <input type="hidden" name="key" value="<?= PAYU_MERCHANT_KEY ?>">
            <input type="hidden" name="txnid" value="<?= $txnid ?>">
            <input type="hidden" name="amount" value="<?= $amount ?>">
            <input type="hidden" name="productinfo" value="<?= $product ?>">
            <input type="hidden" name="firstname" value="<?= $name ?>">
            <input type="hidden" name="email" value="<?= $email ?>">
            <input type="hidden" name="phone" value="<?= $phone ?>">
            <input type="hidden" name="surl" value="<?= PAYU_SUCCESS_URL ?>">
            <input type="hidden" name="furl" value="<?= PAYU_FAILURE_URL ?>">
            <input type="hidden" name="hash" value="<?= $hash ?>">
            <input type="hidden" name="service_provider" value="payu_paisa">
        </form>
        
        <button class="paybtn" onclick="document.getElementById('payForm').submit()">
            🚀 Proceed to PayU
        </button>
        
        <p style="font-size:12px;color:#666;">
            🔐 100% Secure | Fresh TXNID Generated
        </p>
    </div>
</body>
</html>
