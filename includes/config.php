<?php

// Secure session settings for shared hosting
if (session_status() == PHP_SESSION_NONE && !headers_sent()) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
    session_start();
}

require_once __DIR__ . '/database.php';

// Include all model classes
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Admin.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/ContactInquiry.php';
require_once __DIR__ . '/models/Review.php';
require_once __DIR__ . '/models/ProductImage.php';
require_once __DIR__ . '/models/Setting.php';
require_once __DIR__ . '/models/Hero.php';
require_once __DIR__ . '/models/Story.php';
// Constants
define('SITE_NAME', 'Dr Tridosha Herbotech Pvt Ltd');
define('SITE_DOMAIN', 'livvra.in');
define('SITE_EMAIL', 'livvraindia@gmail.com');
define('SITE_PHONE', '8958489684');
define('SITE_ADDRESS', 'Bathinda 151001');
define('SITE_TAGLINE', 'Live Better Live Strong');
define('CURRENCY', '₹');
define('FREE_SHIPPING_ABOVE', 499);
define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_RyB01GcCP6NRfg'); 
define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: 'LIsG16IoyK3eupUbW0pHZ5hd'); 


/* ======================
   SITE CONFIG
====================== */
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Dr Tridosha Herbotech Pvt Ltd');
}

if (!defined('SITE_DOMAIN')) {
    define('SITE_DOMAIN', 'livvra.in');
}

if (!defined('CURRENCY')) {
    define('CURRENCY', '₹');
}

define('SHIPROCKET_BASE_URL', 'https://apiv2.shiprocket.in/v1/external');

define('SHIPROCKET_API_EMAIL', 'buntychhabra02@gmail.com');
define('SHIPROCKET_API_PASSWORD', '8TvDeTgZ9XD5n4yg&h61#lhH1n7^Ullj');

define('SHIPROCKET_PICKUP_LOCATION', 'Warehouse Office');

/* ======================
   PAYU CONFIG
====================== */

define('PAYU_MERCHANT_KEY', '5bQO2C');
define('PAYU_SALT', 'LBBQDAPYS1GiWkSY8m6cDci2aQGuCpR7');

define('PAYU_BASE_URL', 'https://secure.payu.in/_payment');

define('PAYU_SUCCESS_URL', 'https://livvra.in/payu-success.php');
define('PAYU_FAILURE_URL', 'https://livvra.in/payu-failure.php');




define('IM_API_KEY', '5f0c6789bc1eadb18a264cfd94d43429');
define('IM_AUTH_TOKEN', 'dd8bd447e7c211e4d0199bec11e519a1');
define('SITE_URL', 'https://livvra.in');




define('CASHFREE_CLIENT_ID', '120335009d56ea0beb8b856f1960533021');
define('CASHFREE_CLIENT_SECRET', 'cfsk_ma_prod_96881ba91034a8ce7b61fe7bfd1aae27_54e9ea92');
define('CASHFREE_ENV', 'PROD');



function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += (($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        }
    }
    return $total;
}

function getCartMrpTotal() {
    $total = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += (($item['mrp'] ?? $item['price']) * ($item['quantity'] ?? 0));
        }
    }
    return $total;
}

function getGlobalOfferPercent() {
    try {
        $stmt = db()->prepare("SELECT value FROM settings WHERE key = 'global_offer_percent'");
        $stmt->execute();
        return (float)($stmt->fetchColumn() ?: 10);
    } catch (Exception $e) {
        return 10;
    }
}

function getShippingFee($subtotal) {
    if ($subtotal >= FREE_SHIPPING_ABOVE) {
        return 0;
    }
    return 50; // Flat fee or load from settings
}

// Security Helper
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Clean product URL using slug
function productUrl($product) {
    $slug = $product['slug'] ?? '';
    if ($slug) {
        return '/' . ltrim($slug, '/');
    }
    return '/product-detail.php?id=' . ($product['id'] ?? 0);
}

function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += ($item['quantity'] ?? 0);
        }
    }
    return $count;
}

define('UPI_ID', Setting::get('UPI_ID', '9953835017@ybl'));
define('UPI_HOLDER_NAME', Setting::get('UPI_HOLDER_NAME', 'Livvra'));
function checkDelivery($pincode) {
    // In production, this could query a database or API
    $available_pincodes = ['151001', '110001', '400001']; 
    return in_array($pincode, $available_pincodes);
}