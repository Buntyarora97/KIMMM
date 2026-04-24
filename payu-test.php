<?php
// TEST ENVIRONMENT - NO IP BLOCK
define('PAYU_MERCHANT_KEY', 'testing');
define('PAYU_SALT', 'XXXXXXXXXXXXXXXX');
define('PAYU_BASE_URL', 'https://test.payu.in/_payment'); // TEST URL
define('PAYU_SUCCESS_URL', 'https://livvra.in/payu-success.php');
define('PAYU_FAILURE_URL', 'https://livvra.in/payu-failure.php');

$txnid = 'TEST_' . time() . rand(1000,9999);
$amount = '10.00';

$hash_string = 'testing|'.$txnid.'|10.00|TEST Order|TEST|||test@test.com|||||||||||XXXXXXXXXXXXXXXX';
$hash = strtolower(hash('sha512', $hash_string));
?>

<!DOCTYPE html>
<html>
<body style="font-family:Arial;text-align:center;padding:50px;background:#f0f8ff;">
    <h2>🧪 TEST MODE (No IP Block)</h2>
    <p><strong>₹<?= $amount ?></strong></p>
    <form action="<?= PAYU_BASE_URL ?>" method="post" style="display:none;" id="form">
        <input name="key" value="testing">
        <input name="txnid" value="<?= $txnid ?>">
        <input name="amount" value="<?= $amount ?>">
        <input name="productinfo" value="TEST Order">
        <input name="firstname" value="TEST">
        <input name="email" value="test@test.com">
        <input name="phone" value="9999999999">
        <input name="surl" value="<?= PAYU_SUCCESS_URL ?>">
        <input name="furl" value="<?= PAYU_FAILURE_URL ?>">
        <input name="hash" value="<?= $hash ?>">
        <input name="service_provider" value="payu_paisa">
    </form>
    <button onclick="document.getElementById('form').submit()" style="padding:15px 30px;font-size:18px;background:#4CAF50;color:white;border:none;border-radius:8px;cursor:pointer;">TEST PAYMENT</button>
</body>
</html>
