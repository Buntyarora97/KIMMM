<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/../../../includes/config.php';

try {
    $pendingOrders = Order::getTotalCount('pending');
} catch (Exception $e) {
    $pendingOrders = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin'; ?> - LIVVRA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">LIVVRA Admin</div>
            <nav class="sidebar-menu">
                <a href="dashboard.php" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
                <a href="categories.php" class="menu-item"><i class="fas fa-tags"></i> Categories</a>
                <a href="products.php" class="menu-item"><i class="fas fa-box"></i> Products</a>
                <a href="orders.php" class="menu-item"><i class="fas fa-shopping-bag"></i> Orders (<?php echo $pendingOrders; ?>)</a>
                <a href="inquiries.php" class="menu-item"><i class="fas fa-envelope"></i> Enquiries</a>
                <a href="hero.php" class="menu-item"><i class="fas fa-image"></i> Hero Section</a>
                <a href="stories.php" class="menu-item"><i class="fas fa-history"></i> Stories</a>
                <a href="reviews.php" class="menu-item"><i class="fas fa-star"></i> Reviews</a>
               <a href="reels.php" class="menu-item">
    <i class="fas fa-video"></i> Video Reels
</a>
                <a href="promo-codes.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'promo-codes.php' || basename($_SERVER['PHP_SELF']) == 'promo-code-report.php' ? 'active' : ''; ?>"><i class="fas fa-tag"></i> Promo Codes</a>
                <a href="razorpay-payments.php" class="menu-item"><i class="fas fa-credit-card"></i> Razorpay</a>
                <a href="video-popups.php" class="menu-item"><i class="fas fa-video"></i> Video Popups</a>
                <a href="shiprocket-orders.php" class="menu-item"><i class="fas fa-shipping-fast"></i> Shiprocket</a>
                <a href="settings.php" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>
        <main class="main-content">
