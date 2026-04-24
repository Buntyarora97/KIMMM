<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Dashboard';
$currentPage = 'dashboard';

try {
    $stats = [
        'total_orders' => Order::getTotalCount(),
        'total_sales' => db()->query("SELECT SUM(total_amount) FROM orders WHERE payment_status = 'paid'")->fetchColumn() ?: 0,
        'total_products' => db()->query("SELECT COUNT(*) FROM products")->fetchColumn() ?: 0,
        'total_users' => 0
    ];
    $recentOrders = Order::getRecent(5);
} catch (Exception $e) {
    $stats = ['total_orders' => 0, 'total_sales' => 0, 'total_products' => 0, 'total_users' => 0];
    $recentOrders = [];
}

require_once __DIR__ . '/views/layouts/header.php';
?>

<div class="admin-header">
    <h1>Dashboard Overview</h1>
</div>

<div class="stats-grid">
    <div class="admin-card">
        <h4>Total Orders</h4>
        <div class="value"><?php echo $stats['total_orders']; ?></div>
    </div>
    <div class="admin-card">
        <h4>Total Revenue</h4>
        <div class="value">₹<?php echo number_format($stats['total_sales'], 2); ?></div>
    </div>
    <div class="admin-card">
        <h4>Products</h4>
        <div class="value"><?php echo $stats['total_products']; ?></div>
    </div>
</div>

<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 20px; border-bottom: 1px solid #eee;">
        <h3 style="margin: 0;">Recent Orders</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentOrders)): ?>
                <tr><td colspan="5" style="padding: 40px; text-align: center; color: #999;">No recent orders found.</td></tr>
            <?php else: foreach ($recentOrders as $ro): ?>
                <tr>
                    <td><strong><?php echo $ro['order_number']; ?></strong></td>
                    <td><?php echo htmlspecialchars($ro['customer_name']); ?></td>
                    <td>₹<?php echo number_format($ro['total_amount'], 2); ?></td>
                    <td><span class="badge <?php echo $ro['order_status'] === 'confirmed' ? 'badge-success' : 'badge-warning'; ?>"><?php echo strtoupper($ro['order_status']); ?></span></td>
                    <td><a href="order-view.php?id=<?php echo $ro['id']; ?>" style="color: var(--primary-gold);">VIEW</a></td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
