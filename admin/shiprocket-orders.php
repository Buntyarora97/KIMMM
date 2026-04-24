<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Shiprocket Orders';
require_once 'views/layouts/header.php';

/* =========================
   FETCH SHIPPED ORDERS
========================= */

$stmt = db()->prepare("SELECT * FROM orders WHERE shipment_status = 'shipped' ORDER BY created_at DESC");
$stmt->execute();
$shipOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Shiprocket::createOrder($orderData);
?>

<div class="admin-header">
    <h1>Shiprocket Management</h1>
</div>

<div class="admin-card">

    <a href="shiprocket-orders.php" class="btn btn-primary" style="margin-bottom:15px;">
        Refresh
    </a>

    <div class="alert alert-info">
        Only orders marked as <strong>SHIPPED</strong> will appear here.
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Shiprocket ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>AWB Code</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
        <?php if (!empty($shipOrders)): ?>
            <?php foreach ($shipOrders as $order): ?>
            <tr>
                <td>
                    <strong>#<?php echo htmlspecialchars($order['order_number']); ?></strong>
                </td>

                <td>
                    <?php echo htmlspecialchars($order['shiprocket_order_id'] ?? '-'); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($order['customer_name']); ?><br>
                    <small><?php echo htmlspecialchars($order['customer_phone']); ?></small>
                </td>

                <td>
                    <span class="badge badge-success">
                        <?php echo strtoupper($order['shipment_status']); ?>
                    </span>
                </td>

                <td>
                    <?php echo htmlspecialchars($order['awb_code'] ?? '-'); ?>
                </td>

                <td>
                    <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">
                    No Shiprocket Orders Found
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

<?php require_once 'views/layouts/footer.php'; ?>