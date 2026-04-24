<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Setting.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Settings';
$currentPage = 'settings';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['settings'] as $key => $value) {
        Setting::set($key, $value);
    }
    $success = 'Settings updated successfully!';
}

$settings = Setting::getAll();

require_once __DIR__ . '/views/layouts/header.php';
?>
<div class="admin-header">
    <h1>General Settings</h1>
</div>
<?php if ($success): ?><div class="badge badge-success" style="margin-bottom: 20px; display: block;"><?php echo $success; ?></div><?php endif; ?>
<div class="admin-card">
    <form method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label>Site Name</label>
                <input type="text" name="settings[site_name]" class="form-control" value="<?php echo htmlspecialchars($settings['site_name'] ?? 'LIVVRA'); ?>">
            </div>
            <div class="form-group">
                <label>Contact Email</label>
                <input type="email" name="settings[contact_email]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Contact Phone</label>
                <input type="text" name="settings[contact_phone]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Razorpay Key ID</label>
                <input type="text" name="settings[razorpay_key_id]" class="form-control" value="<?php echo htmlspecialchars($settings['razorpay_key_id'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Razorpay Key Secret</label>
                <input type="password" name="settings[razorpay_key_secret]" class="form-control" value="<?php echo htmlspecialchars($settings['razorpay_key_secret'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Shiprocket Email</label>
                <input type="text" name="settings[shiprocket_email]" class="form-control" value="<?php echo htmlspecialchars($settings['shiprocket_email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Shiprocket Password</label>
                <input type="password" name="settings[shiprocket_password]" class="form-control" value="<?php echo htmlspecialchars($settings['shiprocket_password'] ?? ''); ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Save Settings</button>
    </form>
</div>
<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
