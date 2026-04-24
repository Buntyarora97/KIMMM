<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

adminRequired();

$db = db();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_reward_settings'])) {
    $earn_rate = (float)$_POST['reward_earn_rate'];
    $redeem_rate = (float)$_POST['reward_redeem_rate'];
    
    // Update or insert settings
    $stmt = $db->prepare("UPDATE settings SET value = ? WHERE key = 'reward_earn_rate'");
    $stmt->execute([$earn_rate]);
    $stmt = $db->prepare("UPDATE settings SET value = ? WHERE key = 'reward_redeem_rate'");
    $stmt->execute([$redeem_rate]);
    
    $success = "Reward settings updated!";
}

$reward_earn_rate = $db->query("SELECT value FROM settings WHERE key = 'reward_earn_rate'")->fetchColumn() ?: 1;
$reward_redeem_rate = $db->query("SELECT value FROM settings WHERE key = 'reward_redeem_rate'")->fetchColumn() ?: 1;

require_once __DIR__ . '/views/layouts/header.php';
?>
<div style="padding: 20px;">
    <h2>Reward Coins Configuration</h2>
    <div style="background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <form method="POST">
            <input type="hidden" name="save_reward_settings" value="1">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label>Earn Rate (Coins per ₹100 spent)</label>
                    <input type="number" name="reward_earn_rate" value="<?php echo $reward_earn_rate; ?>" step="0.1" required style="width: 100%; padding: 10px; border: 1px solid #ddd;">
                </div>
                <div>
                    <label>Redeem Value (₹ per 1 Coin)</label>
                    <input type="number" name="reward_redeem_rate" value="<?php echo $reward_redeem_rate; ?>" step="0.1" required style="width: 100%; padding: 10px; border: 1px solid #ddd;">
                </div>
            </div>
            <button type="submit" style="background: #4A7C59; color: #fff; border: none; padding: 12px 25px; cursor: pointer; border-radius: 5px;">Save Reward Settings</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>