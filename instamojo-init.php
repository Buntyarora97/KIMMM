<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/models/Order.php';

$orderId = $_GET['order_id'] ?? 0;
$order = Order::getById($orderId);

if(!$order){
    die("Invalid Order");
}

$payload = [
    'purpose' => 'Livvra Order '.$order['order_number'],
    'amount' => $order['total_amount'],
    'buyer_name' => $order['customer_name'],
    'email' => $order['customer_email'],
    'phone' => $order['customer_phone'],
    'redirect_url' => SITE_URL.'/payment-success.php',
    'webhook' => SITE_URL.'/instamojo-webhook.php',
    'allow_repeated_payments' => false
];

$ch = curl_init();

// Using Test URL if needed, but the user image shows standard keys. 
// Standard production endpoint is https://www.instamojo.com/api/1.1/payment-requests/
// If HTTP 403 occurs with correct keys, it often means the account isn't fully enabled for API access 
// or the endpoint needs to be adjusted.

curl_setopt($ch, CURLOPT_URL, "https://www.instamojo.com/api/1.1/payment-requests/");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-Api-Key: ".IM_API_KEY,
    "X-Auth-Token: ".IM_AUTH_TOKEN
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Debug logging
file_put_contents('instamojo_debug.log', "HTTP Code: $httpCode\nResponse: $response\nCurl Error: $curlError\nPayload: ".json_encode($payload)."\n", FILE_APPEND);

$data = json_decode($response, true);

if ($httpCode !== 201 || !isset($data['payment_request']['longurl'])) {
    // If we still get 403, it's likely an account permission issue on Instamojo dashboard.
    // Ensure "API Access" is enabled in your Instamojo profile.
    die("Instamojo Error (HTTP $httpCode): " . ($response ?: $curlError));
}

header("Location: ".$data['payment_request']['longurl']);
exit;