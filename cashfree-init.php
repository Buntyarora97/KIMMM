<?php
session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';

/* =========================
   ORDER CHECK
========================= */

$orderId = $_GET['order_id'] ?? ($_SESSION['pending_order_id'] ?? 0);

if (!$orderId) {
    header('Location: cart.php');
    exit;
}

$order = Order::getById($orderId);

if (!$order) {
    header('Location: cart.php');
    exit;
}

/* =========================
   CASHFREE CONFIG
========================= */

$client_id     = trim(CASHFREE_CLIENT_ID);
$client_secret = trim(CASHFREE_CLIENT_SECRET);
$mode          = CASHFREE_ENV;

$api_url = ($mode === 'PROD')
    ? "https://api.cashfree.com/pg/orders"
    : "https://sandbox.cashfree.com/pg/orders";

/* =========================
   ORDER DATA
========================= */

$order_amount = number_format((float)$order['total_amount'], 2, '.', '');
$cf_order_id  = $order['order_number'] . "_" . time();

$order_data = [
    "order_id"       => $cf_order_id,
    "order_amount"   => $order_amount,
    "order_currency" => "INR",
    "customer_details" => [
        "customer_id"    => "CUST_" . $orderId,
        "customer_name"  => $order['customer_name'],
        "customer_email" => $order['customer_email'] ?: "customer@example.com",
        "customer_phone" => $order['customer_phone']
    ],
    "order_meta" => [
        "return_url" => "https://livvra.in/cashfree-return.php?order_id={$orderId}&cf_order_id={$cf_order_id}",
        "notify_url" => "https://livvra.in/cashfree-notify.php"
    ]
];

/* =========================
   CURL REQUEST
========================= */

$headers = [
    "Content-Type: application/json",
    "x-api-version: 2022-09-01",
    "x-client-id: {$client_id}",
    "x-client-secret: {$client_secret}"
];

$ch = curl_init($api_url);

curl_setopt_array($ch, [
    CURLOPT_POST            => true,
    CURLOPT_POSTFIELDS      => json_encode($order_data),
    CURLOPT_HTTPHEADER      => $headers,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_TIMEOUT         => 30,
]);

$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error     = curl_error($ch);

curl_close($ch);

/* =========================
   DEBUG HANDLING
========================= */

$result = json_decode($response, true);

if ($http_code !== 200 || empty($result['payment_session_id'])) {

    echo "<pre>";
    echo "HTTP CODE: " . $http_code . "\n\n";
    echo "CURL ERROR: " . $error . "\n\n";
    echo "RESPONSE: " . $response;
    exit;
}

$payment_session_id = $result['payment_session_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Redirecting to Payment</title>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
</head>

<body style="font-family:Arial;text-align:center;padding-top:120px;background:#f7f7f7;">

<h2>Redirecting to Secure Payment...</h2>

<button id="payBtn" style="
    padding:15px 30px;
    background:#C9A227;
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
">
Proceed to Payment
</button>

<script>

const cashfree = Cashfree({
    mode: "<?php echo ($mode === 'PROD') ? 'production' : 'sandbox'; ?>"
});

function startPayment() {
    cashfree.checkout({
        paymentSessionId: "<?php echo $payment_session_id; ?>",
        redirectTarget: "_self"
    });
}

setTimeout(startPayment, 600);
document.getElementById("payBtn").onclick = startPayment;

</script>

</body>
</html>