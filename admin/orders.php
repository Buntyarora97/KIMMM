<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Order.php';

// ✅ FIX: Session start missing tha
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$currentPage = 'orders';

// ✅ FIX: Error handling
try {
    $orders = Order::getAll();
} catch (Exception $e) {
    $orders = [];
    $errorMsg = "Failed to load orders: " . $e->getMessage();
}

require_once __DIR__ . '/views/layouts/header.php';
?>

<div class="admin-header">
    <h1>Manage Orders</h1>
</div>

<!-- ✅ FIX: Success/Error Messages Display -->
<?php if (!empty($_GET['success'])): ?>
    <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; margin: 10px 20px; border-radius: 4px; border: 1px solid #c3e6cb;">
        ✅ <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['error'])): ?>
    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; margin: 10px 20px; border-radius: 4px; border: 1px solid #f5c6cb;">
        ❌ <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
    </div>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; margin: 10px 20px; border-radius: 4px;">
        <?php echo $errorMsg; ?>
    </div>
<?php endif; ?>

<div class="admin-card" style="padding:0; overflow:hidden;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Order Status</th>
                <th>Shipment</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px;">
                        No orders found
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $o): 

                    $orderStatus = strtoupper($o['order_status'] ?? '');
                    $paymentStatus = strtolower($o['payment_status'] ?? '');
                    $shipmentStatus = strtolower($o['shipment_status'] ?? 'pending');
                    
                    // Shiprocket ID check
                    $hasShiprocket = !empty($o['shiprocket_order_id']);
                    $hasAWB = !empty($o['awb_code']);
                ?>

                <tr>

                    <!-- Order Number -->
                    <td>
                        <strong>#<?php echo e($o['order_number']); ?></strong>
                        <?php if ($hasShiprocket): ?>
                            <br>
                            <small style="color: #666;">
                                SR: <?php echo e($o['shiprocket_order_id']); ?>
                            </small>
                        <?php endif; ?>
                    </td>

                    <!-- Customer -->
                    <td>
                        <?php echo e($o['customer_name']); ?><br>
                        <small><?php echo e($o['customer_phone']); ?></small>
                    </td>

                    <!-- Amount -->
                    <td>
                        <?php echo CURRENCY . number_format($o['total_amount'], 2); ?>
                    </td>

                    <!-- Payment -->
                    <td>
                        <div>
                            <small><?php echo strtoupper($o['payment_method']); ?></small><br>

                            <?php if ($paymentStatus === 'paid'): ?>
                                <span class="badge badge-success">PAID</span>
                            <?php elseif ($paymentStatus === 'failed'): ?>
                                <span class="badge badge-danger">FAILED</span>
                            <?php else: ?>
                                <span class="badge badge-warning">
                                    <?php echo strtoupper($paymentStatus); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>

                    <!-- Order Status -->
                    <td>
                        <span class="badge badge-info">
                            <?php echo $orderStatus; ?>
                        </span>
                    </td>

                    <!-- Shipment -->
                    <td>
                        <?php if ($shipmentStatus === 'shipped'): ?>
                            <span class="badge badge-success">
                                SHIPPED
                                <?php if ($hasAWB): ?>
                                    <br><small>AWB: <?php echo e($o['awb_code']); ?></small>
                                <?php endif; ?>
                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary">PENDING</span>
                        <?php endif; ?>
                    </td>

                    <!-- Date -->
                    <td>
                        <?php echo date('d M Y, h:i A', strtotime($o['created_at'])); ?>
                    </td>

                    <!-- Action -->
                    <td>
                        <?php if (($orderStatus === 'PAID' || $orderStatus === 'PROCESSING') && $shipmentStatus !== 'shipped'): ?>

                            <!-- ✅ FIX: Better button with icon -->
                            <a href="ship-order.php?id=<?php echo $o['id']; ?>"
                               class="btn btn-sm btn-primary"
                               onclick="return confirm('Send order #<?php echo e($o['order_number']); ?> to Shiprocket?')"
                               style="background: #1e88e5; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; display: inline-flex; align-items: center; gap: 5px;">
                                <span>🚀</span>
                                <span>Send to Shiprocket</span>
                            </a>

                        <?php elseif ($shipmentStatus === 'shipped' && $hasShiprocket): ?>

                            <!-- ✅ NEW: View in Shiprocket Button -->
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                <span class="badge badge-success">Completed</span>
                                
                                <a href="https://app.shiprocket.in/seller/orders/details/<?php echo e($o['shiprocket_order_id']); ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-outline"
                                   style="font-size: 11px; color: #1e88e5; text-decoration: none;">
                                    View in Shiprocket →
                                </a>
                                
                                <?php if ($hasAWB): ?>
                                    <a href="https://app.shiprocket.in/tracking/<?php echo e($o['awb_code']); ?>"
                                       target="_blank"
                                       class="btn btn-sm btn-outline"
                                       style="font-size: 11px; color: #28a745; text-decoration: none;">
                                        Track AWB
                                    </a>
                                <?php endif; ?>
                            </div>

                        <?php else: ?>

                            -

                        <?php endif; ?>
                    </td>

                </tr>

                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>