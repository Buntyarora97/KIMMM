<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

adminRequired();

$db = db();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pincode'])) {
    $pincode = trim($_POST['pincode']);
    $days = (int)$_POST['delivery_days'];
    
    $stmt = $db->prepare("INSERT INTO pincodes (pincode, delivery_days) VALUES (?, ?) ON CONFLICT (pincode) DO UPDATE SET delivery_days = EXCLUDED.delivery_days");
    if ($stmt->execute([$pincode, $days])) {
        $success = "Pincode updated!";
    } else {
        $error = "Failed to update pincode.";
    }
}

$pincodes = $db->query("SELECT * FROM pincodes ORDER BY pincode ASC")->fetchAll();

require_once __DIR__ . '/views/layouts/header.php';
?>
<div style="padding: 20px;">
    <h2>Manage Serviceable Pincodes</h2>
    <div style="background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <form method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <input type="hidden" name="add_pincode" value="1">
            <input type="text" name="pincode" placeholder="Pincode (e.g. 110001)" required style="padding: 10px; border: 1px solid #ddd;">
            <input type="number" name="delivery_days" placeholder="Delivery Days" required style="padding: 10px; border: 1px solid #ddd;">
            <button type="submit" style="background: #C9A227; color: #fff; border: none; padding: 10px; cursor: pointer; grid-column: span 2;">Update Pincode</button>
        </form>
    </div>
    <table style="width: 100%; border-collapse: collapse; background: #fff;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 10px; text-align: left;">Pincode</th>
                <th style="padding: 10px; text-align: left;">Est. Delivery Days</th>
                <th style="padding: 10px; text-align: left;">Serviceable</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pincodes as $p): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><?php echo htmlspecialchars($p['pincode']); ?></td>
                <td style="padding: 10px;"><?php echo $p['delivery_days']; ?> Days</td>
                <td style="padding: 10px;"><?php echo $p['is_serviceable'] ? 'Yes' : 'No'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>