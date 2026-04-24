<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/config.php';
$id = $_GET['id'] ?? null;
if (!$id || !isset($_SESSION['admin_id'])) { header('Location: orders.php'); exit; }
$order = Order::getById($id);
$items = Order::getItems($id);
$pageTitle = 'Order Details';
require_once __DIR__ . '/views/layouts/header.php';
?>
<div class="admin-header"><h1>Order #<?php echo $order['order_number']; ?></h1></div>
<div class="admin-card">
    <h3>Customer Info</h3>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['shipping_address'] . ', ' . $order['city'] . ', ' . $order['state'] . ' - ' . $order['pincode']); ?></p>
</div>
<div class="admin-card">
    <h3>Order Items</h3>
    <table class="admin-table">
        <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr><td><?php echo $item['product_name']; ?></td><td><?php echo $item['quantity']; ?></td><td>₹<?php echo $item['price']; ?></td><td>₹<?php echo $item['total']; ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="admin-card">
    <h3>Actions</h3>
    <form action="ajax/admin_actions.php" method="POST" onsubmit="event.preventDefault(); updateOrder(this);">
        <input type="hidden" name="action" value="update_order">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
            <label>Update Status</label>
            <select name="status" class="form-control">
                <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?php echo $order['order_status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="shipped" <?php echo $order['order_status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                <option value="delivered" <?php echo $order['order_status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Update Order</button>
    </form>
</div>
<script>
function updateOrder(form) {
    const formData = new FormData(form);
    fetch('ajax/admin_actions.php', { method: 'POST', body: formData })
    .then(r => r.json()).then(d => { if(d.success) alert('Order updated!'); else alert('Failed!'); });
}
</script>
<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
