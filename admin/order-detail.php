<?php
require_once '../includes/config.php';
require_once '../includes/models/Admin.php';
require_once '../includes/models/Order.php';

$id = $_GET['id'] ?? 0;
$order = Order::getById($id);

if (!$order) { header('Location: orders.php'); exit; }

$items = Order::getItems($id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Detail #<?php echo $order['order_number']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-width: 260px; --primary: #4A7C59; --gold: #C9A227; }
        body { font-family: 'Poppins', sans-serif; margin: 0; display: flex; background: #f4f7f6; }
        .sidebar { width: var(--sidebar-width); background: #2c3e50; color: white; height: 100vh; position: fixed; }
        .main-content { margin-left: var(--sidebar-width); flex: 1; padding: 30px; }
        .nav-item { padding: 15px 25px; display: flex; align-items: center; gap: 15px; color: #bdc3c7; text-decoration: none; transition: 0.3s; }
        .nav-item:hover, .nav-item.active { background: #34495e; color: white; border-left: 4px solid var(--gold); }
        .admin-section { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        .btn { padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer; background: var(--primary); color: white; font-weight: 600; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div style="padding: 30px; text-align: center;">
            <img src="../assets/images/logo.png" style="width: 80px;">
            <h2 style="margin-top: 10px; font-size: 1.2rem;">LIVVRA Admin</h2>
        </div>
        <a href="dashboard.php" class="nav-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="products.php" class="nav-item"><i class="fas fa-box"></i> Products</a>
        <a href="orders.php" class="nav-item active"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="settings.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
    </div>

    <div class="main-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h1>Order <?php echo $order['order_number']; ?></h1>
            <a href="orders.php" class="btn" style="background:#95a5a6;">Back to List</a>
        </div>
        
        <div class="detail-grid">
            <div class="admin-section">
                <h3>Customer & Shipping</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                <p><strong>Address:</strong><br><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?> - <?php echo htmlspecialchars($order['pincode']); ?></p>
            </div>
            
            <div class="admin-section">
                <h3>Order Status & Tracking</h3>
                <p><strong>Current Status:</strong> <span class="status-badge"><?php echo strtoupper($order['status'] ?: $order['order_status']); ?></span></p>
                <p><strong>Payment:</strong> <?php echo strtoupper($order['payment_method']); ?> (<?php echo strtoupper($order['payment_status']); ?>)</p>
                <p><strong>Tracking:</strong> <?php echo $order['tracking_id'] ?: 'Not Assigned'; ?> (<?php echo $order['courier_name'] ?: 'N/A'; ?>)</p>
                
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                
                <form id="updateOrderForm">
                    <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                    <div style="margin-bottom:10px;">
                        <label>Update Status</label>
                        <select name="status" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ddd;">
                            <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>Pending</option>
                            <option value="confirmed" <?php if($order['status']=='confirmed') echo 'selected'; ?>>Confirmed</option>
                            <option value="shipped" <?php if($order['status']=='shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="delivered" <?php if($order['status']=='delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="cancelled" <?php if($order['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div style="margin-bottom:10px;">
                        <label>Courier Name</label>
                        <input type="text" name="courier_name" value="<?php echo htmlspecialchars($order['courier_name']); ?>" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ddd;">
                    </div>
                    <div style="margin-bottom:10px;">
                        <label>Tracking ID</label>
                        <input type="text" name="tracking_id" value="<?php echo htmlspecialchars($order['tracking_id']); ?>" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ddd;">
                    </div>
                    <button type="button" class="btn" onclick="updateOrder()">Update Order</button>
                </form>
                <?php
$canSendToShiprocket = false;

if ($order['order_status'] === 'confirmed') {
    if ($order['payment_method'] === 'cod') {
        $canSendToShiprocket = true;
    } elseif ($order['payment_status'] === 'paid') {
        $canSendToShiprocket = true;
    }
}
?>

<?php if ($canSendToShiprocket): ?>
<hr style="margin:20px 0;">
<form method="POST" action="orders.php">
    <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
    <input type="hidden" name="send_to_shiprocket" value="1">
    <button class="btn" style="background:#3498db;">
        🚚 Send to Shiprocket
    </button>
</form>
<?php else: ?>
<p style="color:#999; margin-top:10px;">
    <?php if ($order['order_status'] !== 'confirmed'): ?>
        Order must be confirmed before shipping.
    <?php elseif ($order['payment_method'] !== 'cod'): ?>
        Online payment pending – cannot ship yet.
    <?php endif; ?>
</p>
<?php endif; ?>

            </div>
        </div>

        <div class="admin-section">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹<?php echo number_format($item['total'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Subtotal:</strong></td>
                        <td>₹<?php echo number_format($order['subtotal'], 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Shipping:</strong></td>
                        <td>₹<?php echo number_format($order['shipping_fee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Total Amount:</strong></td>
                        <td><strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <script>
    function updateOrder() {
        const form = document.getElementById('updateOrderForm');
        const formData = new FormData(form);
        formData.append('action', 'update_order');
        
        fetch('ajax/admin_actions.php', {
            method: 'POST',
            body: formData
        }).then(res => res.json()).then(data => {
            if(data.success) {
                alert('Order updated successfully!');
                location.reload();
            }
        });
    }
    </script>
</body>
</html>
