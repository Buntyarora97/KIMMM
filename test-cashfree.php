<?php

require_once __DIR__ . '/includes/config.php';

$api_url = "https://api.cashfree.com/pg/orders";

$headers = [
    "Content-Type: application/json",
    "x-api-version: 2025-01-01",
    "x-client-id: " . CASHFREE_CLIENT_ID,
    "x-client-secret: " . CASHFREE_CLIENT_SECRET
];

$data = [
    "order_id" => "TEST_" . time(),
    "order_amount" => 1,
    "order_currency" => "INR",
    "customer_details" => [
        "customer_id" => "TEST001",
        "customer_phone" => "9999999999"
    ]
];

$ch = curl_init($api_url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => $headers
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "<pre>";
echo "HTTP CODE: " . $http_code . "\n\n";
print_r(json_decode($response, true));