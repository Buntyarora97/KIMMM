<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/User.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = User::getById($_SESSION['user_id']);
$orders = Order::getRecentOrders(100); // Filtered by user in real app

$pageTitle = 'My Account';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <div class="account-layout" style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
            <div class="sidebar" style="background: #f9f9f9; padding: 20px; border-radius: 10px;">
                <h3>Hello, <?php echo e($user['name']); ?></h3>
                <p>Reward Coins: <strong><?php echo $user['reward_coins']; ?></strong></p>
                <hr>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="account.php" style="color: var(--primary-gold);">Order History</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <div class="content">
                <h3>Order History</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eee;">
                            <th style="text-align: left; padding: 10px;">Order #</th>
                            <th style="text-align: left; padding: 10px;">Date</th>
                            <th style="text-align: left; padding: 10px;">Total</th>
                            <th style="text-align: left; padding: 10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): 
                            if ($order['user_id'] != $user['id']) continue;
                        ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><?php echo e($order['order_number']); ?></td>
                            <td style="padding: 10px;"><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                            <td style="padding: 10px;">₹<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td style="padding: 10px;"><span style="background: #eee; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;"><?php echo ucfirst($order['order_status']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>