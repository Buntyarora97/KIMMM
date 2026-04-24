<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }

$db = db();
$success = '';
$error = '';
$db_error = '';

// ============================================================
// AUTO-MIGRATION: Missing tables/columns khud add ho jayenge
// ============================================================
function runMigration($db) {
    $msgs = [];
    try {
        // Check if promo_codes table exists
        try { $db->query("SELECT id FROM promo_codes LIMIT 1"); }
        catch (Exception $e) {
            // Detect MySQL vs PostgreSQL
            $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'pgsql') {
                $db->exec("CREATE TABLE IF NOT EXISTS promo_codes (
                    id SERIAL PRIMARY KEY, code VARCHAR(50) NOT NULL UNIQUE,
                    influencer_name VARCHAR(100) DEFAULT NULL, influencer_email VARCHAR(255) DEFAULT NULL,
                    discount_type VARCHAR(20) DEFAULT 'percentage', discount_value DECIMAL(10,2) DEFAULT 0,
                    max_discount DECIMAL(10,2) DEFAULT 0, min_order_amount DECIMAL(10,2) DEFAULT 0,
                    usage_limit INTEGER DEFAULT 0, used_count INTEGER DEFAULT 0,
                    commission_type VARCHAR(20) DEFAULT 'percentage', commission_value DECIMAL(10,2) DEFAULT 0,
                    expiry_date DATE DEFAULT NULL, is_active INTEGER DEFAULT 1,
                    notes TEXT DEFAULT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
            } else {
                $db->exec("CREATE TABLE IF NOT EXISTS `promo_codes` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY, `code` VARCHAR(50) NOT NULL UNIQUE,
                    `influencer_name` VARCHAR(100) DEFAULT NULL, `influencer_email` VARCHAR(255) DEFAULT NULL,
                    `discount_type` VARCHAR(20) DEFAULT 'percentage', `discount_value` DECIMAL(10,2) DEFAULT 0,
                    `max_discount` DECIMAL(10,2) DEFAULT 0, `min_order_amount` DECIMAL(10,2) DEFAULT 0,
                    `usage_limit` INT DEFAULT 0, `used_count` INT DEFAULT 0,
                    `commission_type` VARCHAR(20) DEFAULT 'percentage', `commission_value` DECIMAL(10,2) DEFAULT 0,
                    `expiry_date` DATE DEFAULT NULL, `is_active` INT DEFAULT 1,
                    `notes` TEXT DEFAULT NULL, `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            }
            $msgs[] = "promo_codes table created";
        }

        // Check and add missing columns in promo_codes
        $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
        $cols_result = ($driver === 'pgsql')
            ? $db->query("SELECT column_name FROM information_schema.columns WHERE table_name='promo_codes'")->fetchAll(PDO::FETCH_COLUMN)
            : $db->query("SHOW COLUMNS FROM `promo_codes`")->fetchAll(PDO::FETCH_COLUMN);
        
        $existing_cols = array_map('strtolower', $cols_result);
        
        $needed = [
            'influencer_name' => "VARCHAR(100) DEFAULT NULL",
            'influencer_email' => "VARCHAR(255) DEFAULT NULL",
            'commission_type' => "VARCHAR(20) DEFAULT 'percentage'",
            'commission_value' => "DECIMAL(10,2) DEFAULT 0",
            'max_discount' => "DECIMAL(10,2) DEFAULT 0",
            'notes' => "TEXT DEFAULT NULL",
        ];
        
        foreach ($needed as $col => $def) {
            if (!in_array($col, $existing_cols)) {
                try {
                    $db->exec("ALTER TABLE " . ($driver === 'pgsql' ? "promo_codes" : "`promo_codes`") . " ADD COLUMN " . ($driver === 'pgsql' ? "" : "`") . $col . ($driver === 'pgsql' ? "" : "`") . " $def");
                    $msgs[] = "Added column: $col";
                } catch (Exception $e) {
                    // Ignore if already exists
                }
            }
        }

        // Check if promo_code_usage table exists
        try { $db->query("SELECT id FROM promo_code_usage LIMIT 1"); }
        catch (Exception $e) {
            if ($driver === 'pgsql') {
                $db->exec("CREATE TABLE IF NOT EXISTS promo_code_usage (
                    id SERIAL PRIMARY KEY, promo_code_id INTEGER NOT NULL, order_id INTEGER NOT NULL,
                    order_number VARCHAR(50) NOT NULL, customer_name VARCHAR(255) DEFAULT NULL,
                    customer_phone VARCHAR(20) DEFAULT NULL, order_total DECIMAL(10,2) DEFAULT 0,
                    discount_given DECIMAL(10,2) DEFAULT 0, commission_earned DECIMAL(10,2) DEFAULT 0,
                    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
            } else {
                $db->exec("CREATE TABLE IF NOT EXISTS `promo_code_usage` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY, `promo_code_id` INT NOT NULL,
                    `order_id` INT NOT NULL, `order_number` VARCHAR(50) NOT NULL,
                    `customer_name` VARCHAR(255) DEFAULT NULL, `customer_phone` VARCHAR(20) DEFAULT NULL,
                    `order_total` DECIMAL(10,2) DEFAULT 0, `discount_given` DECIMAL(10,2) DEFAULT 0,
                    `commission_earned` DECIMAL(10,2) DEFAULT 0, `used_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX `idx_promo_code_id` (`promo_code_id`), INDEX `idx_order_id` (`order_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            }
            $msgs[] = "promo_code_usage table created";
        }
    } catch (Exception $e) {
        return "Migration error: " . $e->getMessage();
    }
    return count($msgs) ? implode(', ', $msgs) : null;
}

$migration_msg = runMigration($db);

// ============================================================
// Handle POST actions
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_promo'])) {
        $code = strtoupper(trim($_POST['code']));
        if (empty($code)) {
            $error = "Promo code khali nahi ho sakta.";
        } else {
            try {
                $check = $db->prepare("SELECT id FROM promo_codes WHERE code = ?");
                $check->execute([$code]);
                if ($check->fetch()) {
                    $error = "Yeh promo code pehle se exist karta hai.";
                } else {
                    $stmt = $db->prepare("INSERT INTO promo_codes 
                        (code, influencer_name, influencer_email, discount_type, discount_value, max_discount,
                         min_order_amount, usage_limit, commission_type, commission_value, expiry_date, notes, is_active)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                    $expiry = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null;
                    if ($stmt->execute([
                        $code,
                        trim($_POST['influencer_name'] ?? ''),
                        trim($_POST['influencer_email'] ?? ''),
                        $_POST['discount_type'],
                        (float)$_POST['discount_value'],
                        (float)($_POST['max_discount'] ?? 0),
                        (float)($_POST['min_order_amount'] ?? 0),
                        (int)($_POST['usage_limit'] ?? 0),
                        $_POST['commission_type'] ?? 'percentage',
                        (float)($_POST['commission_value'] ?? 0),
                        $expiry,
                        trim($_POST['notes'] ?? '')
                    ])) {
                        $success = "Promo code <strong>$code</strong> ban gaya!";
                    } else {
                        $error = "Promo code banane mein dikkat aayi.";
                    }
                }
            } catch (Exception $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }

    if (isset($_POST['toggle_status'])) {
        $id = (int)$_POST['toggle_id'];
        $newStatus = (int)$_POST['new_status'];
        try {
            $db->prepare("UPDATE promo_codes SET is_active = ? WHERE id = ?")->execute([$newStatus, $id]);
            $success = "Status update ho gaya.";
        } catch (Exception $e) {}
    }

    if (isset($_POST['delete_promo'])) {
        $id = (int)$_POST['delete_id'];
        try {
            $db->prepare("DELETE FROM promo_code_usage WHERE promo_code_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM promo_codes WHERE id = ?")->execute([$id]);
            $success = "Promo code delete ho gaya.";
        } catch (Exception $e) {}
    }
}

// ============================================================
// Fetch all promo codes with usage stats
// ============================================================
$promos = [];
try {
    $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
    $groupBy = ($driver === 'pgsql')
        ? "GROUP BY p.id, p.code, p.influencer_name, p.influencer_email, p.discount_type, p.discount_value, p.max_discount, p.min_order_amount, p.usage_limit, p.used_count, p.commission_type, p.commission_value, p.expiry_date, p.is_active, p.notes, p.created_at"
        : "GROUP BY p.id";
    
    $promos = $db->query("
        SELECT p.*, 
               COUNT(u.id) as total_orders,
               COALESCE(SUM(u.order_total), 0) as total_revenue,
               COALESCE(SUM(u.discount_given), 0) as total_discount,
               COALESCE(SUM(u.commission_earned), 0) as total_commission
        FROM promo_codes p
        LEFT JOIN promo_code_usage u ON p.id = u.promo_code_id
        $groupBy
        ORDER BY p.created_at DESC
    ")->fetchAll() ?: [];
} catch (Exception $e) {
    $db_error = $e->getMessage();
    // Fallback: try without join
    try {
        $promos = $db->query("SELECT *, 0 as total_orders, 0 as total_revenue, 0 as total_discount, 0 as total_commission FROM promo_codes ORDER BY created_at DESC")->fetchAll() ?: [];
    } catch (Exception $e2) {
        $db_error = $e2->getMessage();
    }
}

$pageTitle = 'Influencer Promo Codes';
require_once __DIR__ . '/views/layouts/header.php';
?>

<style>
.promo-page { padding: 24px; max-width: 1400px; margin: 0 auto; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-header h2 { font-size: 22px; font-weight: 700; color: #1a1a2e; margin: 0; }
.alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
.alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.alert-info { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

.add-form-card { background: #fff; border-radius: 12px; padding: 24px; margin-bottom: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; }
.add-form-card h3 { font-size: 16px; font-weight: 700; margin: 0 0 20px 0; color: #1a1a2e; border-bottom: 2px solid #C9A227; padding-bottom: 10px; display: inline-block; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group label { font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
.form-group input, .form-group select, .form-group textarea { padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 7px; font-size: 14px; color: #1a1a2e; background: #fafafa; }
.form-group input:focus, .form-group select:focus { outline: none; border-color: #C9A227; background: #fff; }
.btn-add { background: linear-gradient(135deg, #C9A227, #e6b830); color: #fff; border: none; padding: 10px 28px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; margin-top: 16px; }
.btn-add:hover { opacity: 0.9; }

.stats-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
.stat-card { background: #fff; border-radius: 10px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; }
.stat-card .label { font-size: 12px; color: #9ca3af; font-weight: 600; text-transform: uppercase; }
.stat-card .value { font-size: 26px; font-weight: 800; color: #1a1a2e; margin-top: 4px; }
.stat-card .value.green { color: #059669; }
.stat-card .value.blue { color: #2563eb; }
.stat-card .value.gold { color: #C9A227; }

.table-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; overflow: hidden; }
.table-card table { width: 100%; border-collapse: collapse; }
.table-card thead tr { background: #f8f9fa; }
.table-card th { padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
.table-card td { padding: 14px 16px; font-size: 14px; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.table-card tr:last-child td { border-bottom: none; }
.table-card tr:hover td { background: #fefce8; }

.code-badge { background: #1a1a2e; color: #C9A227; padding: 4px 10px; border-radius: 6px; font-family: monospace; font-size: 13px; font-weight: 700; letter-spacing: 1px; }
.discount-badge { padding: 3px 9px; border-radius: 5px; font-size: 12px; font-weight: 700; }
.discount-badge.pct { background: #dbeafe; color: #1d4ed8; }
.discount-badge.flat { background: #fef3c7; color: #92400e; }
.status-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-badge.active { background: #d1fae5; color: #065f46; }
.status-badge.inactive { background: #fee2e2; color: #991b1b; }
.action-btns { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
.btn-sm { padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.btn-report { background: #dbeafe; color: #1d4ed8; }
.btn-report:hover { background: #bfdbfe; }
.btn-toggle-on { background: #fee2e2; color: #991b1b; }
.btn-toggle-off { background: #d1fae5; color: #065f46; }
.btn-delete { background: #fee2e2; color: #dc2626; }
.btn-delete:hover { background: #fca5a5; color: #fff; }
.influencer-info .name { font-weight: 700; color: #1a1a2e; }
.influencer-info .email { font-size: 12px; color: #9ca3af; }
.revenue-cell { font-weight: 700; color: #059669; }
.commission-cell { font-weight: 700; color: #C9A227; }
.empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
.empty-state .icon { font-size: 48px; margin-bottom: 12px; }
</style>

<div class="promo-page">
    <div class="page-header">
        <h2>🎯 Influencer Promo Codes</h2>
    </div>

    <?php if ($migration_msg): ?>
        <div class="alert alert-info">✅ Auto-setup: <?= htmlspecialchars($migration_msg) ?></div>
    <?php endif; ?>

    <?php if ($db_error): ?>
        <div class="alert alert-warning">
            ⚠️ Database issue: <code><?= htmlspecialchars($db_error) ?></code><br>
            <strong>Fix:</strong> Hostinger phpMyAdmin mein <code>promo_codes_mysql.sql</code> run karo.
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add New Promo Code Form -->
    <div class="add-form-card">
        <h3>+ Naya Promo Code Banao</h3>
        <form method="POST">
            <input type="hidden" name="add_promo" value="1">
            <div class="form-grid">
                <div class="form-group">
                    <label>Influencer Ka Naam *</label>
                    <input type="text" name="influencer_name" placeholder="jaise: Bunty" required>
                </div>
                <div class="form-group">
                    <label>Influencer Email</label>
                    <input type="email" name="influencer_email" placeholder="bunty@gmail.com">
                </div>
                <div class="form-group">
                    <label>Promo Code *</label>
                    <input type="text" name="code" placeholder="BUNTY10" required oninput="this.value=this.value.toUpperCase()" style="text-transform:uppercase;font-weight:700;letter-spacing:1px;">
                </div>
                <div class="form-group">
                    <label>Discount Type *</label>
                    <select name="discount_type">
                        <option value="percentage">Percentage (%)</option>
                        <option value="flat">Flat Amount (₹)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Discount Value *</label>
                    <input type="number" step="0.01" min="0" name="discount_value" placeholder="10" required>
                </div>
                <div class="form-group">
                    <label>Max Discount (₹) <small style="font-weight:400">(0=no limit)</small></label>
                    <input type="number" step="0.01" min="0" name="max_discount" placeholder="0" value="0">
                </div>
                <div class="form-group">
                    <label>Min Order Amount (₹)</label>
                    <input type="number" step="0.01" min="0" name="min_order_amount" placeholder="0" value="0">
                </div>
                <div class="form-group">
                    <label>Usage Limit <small style="font-weight:400">(0=unlimited)</small></label>
                    <input type="number" min="0" name="usage_limit" placeholder="0" value="0">
                </div>
                <div class="form-group">
                    <label>Commission Type</label>
                    <select name="commission_type">
                        <option value="percentage">Percentage (%) per order</option>
                        <option value="flat">Flat Amount (₹) per order</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Commission Value</label>
                    <input type="number" step="0.01" min="0" name="commission_value" placeholder="5" value="0">
                </div>
                <div class="form-group">
                    <label>Expiry Date <small style="font-weight:400">(optional)</small></label>
                    <input type="date" name="expiry_date">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" placeholder="Koi bhi note...">
                </div>
            </div>
            <button type="submit" class="btn-add">✓ Promo Code Banao</button>
        </form>
    </div>

    <!-- Summary Stats -->
    <?php
    $totalCodes = count($promos);
    $activeCodes = array_filter($promos, fn($p) => $p['is_active'] == 1);
    $totalOrders = array_sum(array_column($promos, 'total_orders'));
    $totalCommission = array_sum(array_column($promos, 'total_commission'));
    ?>
    <div class="stats-cards">
        <div class="stat-card"><div class="label">Kul Promo Codes</div><div class="value"><?= $totalCodes ?></div></div>
        <div class="stat-card"><div class="label">Active Codes</div><div class="value green"><?= count($activeCodes) ?></div></div>
        <div class="stat-card"><div class="label">Promo Orders</div><div class="value blue"><?= $totalOrders ?></div></div>
        <div class="stat-card"><div class="label">Kul Commission</div><div class="value gold">₹<?= number_format($totalCommission, 0) ?></div></div>
    </div>

    <!-- Promo Codes Table -->
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Influencer</th>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Min Order</th>
                    <th>Commission</th>
                    <th>Orders</th>
                    <th>Revenue</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($promos)): ?>
                <tr><td colspan="10">
                    <div class="empty-state">
                        <div class="icon">🎯</div>
                        <div>Abhi koi promo code nahi hai. Upar form se banao!</div>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($promos as $p): ?>
                <tr>
                    <td>
                        <div class="influencer-info">
                            <div class="name"><?= htmlspecialchars($p['influencer_name'] ?? '—') ?></div>
                            <div class="email"><?= htmlspecialchars($p['influencer_email'] ?? '') ?></div>
                        </div>
                    </td>
                    <td><span class="code-badge"><?= htmlspecialchars($p['code']) ?></span></td>
                    <td>
                        <span class="discount-badge <?= $p['discount_type'] === 'percentage' ? 'pct' : 'flat' ?>">
                            <?= $p['discount_type'] === 'percentage' ? $p['discount_value'] . '%' : '₹' . number_format($p['discount_value'], 0) ?>
                        </span>
                        <?php if (($p['max_discount'] ?? 0) > 0): ?>
                            <div style="font-size:11px;color:#9ca3af;">Max: ₹<?= $p['max_discount'] ?></div>
                        <?php endif; ?>
                    </td>
                    <td>₹<?= number_format($p['min_order_amount'] ?? 0, 0) ?></td>
                    <td class="commission-cell">
                        <?php
                        $cv = $p['commission_value'] ?? 0;
                        $ct = $p['commission_type'] ?? 'percentage';
                        echo $ct === 'percentage' ? $cv . '%' : '₹' . number_format($cv, 0);
                        ?>
                        <?php if ($cv > 0 && ($p['total_commission'] ?? 0) > 0): ?>
                            <div style="font-size:11px;color:#6b7280;">Total: ₹<?= number_format($p['total_commission'], 0) ?></div>
                        <?php endif; ?>
                    </td>
                    <td style="font-weight:700;color:#2563eb;"><?= $p['total_orders'] ?? 0 ?></td>
                    <td class="revenue-cell">₹<?= number_format($p['total_revenue'] ?? 0, 0) ?></td>
                    <td style="font-size:13px;"><?= $p['expiry_date'] ? date('d M Y', strtotime($p['expiry_date'])) : '<span style="color:#9ca3af">No Expiry</span>' ?></td>
                    <td>
                        <span class="status-badge <?= ($p['is_active'] ?? 0) ? 'active' : 'inactive' ?>">
                            <?= ($p['is_active'] ?? 0) ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="promo-code-report.php?id=<?= $p['id'] ?>" class="btn-sm btn-report">📊 Report</a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="toggle_status" value="1">
                                <input type="hidden" name="toggle_id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="new_status" value="<?= ($p['is_active'] ?? 0) ? 0 : 1 ?>">
                                <button type="submit" class="btn-sm <?= ($p['is_active'] ?? 0) ? 'btn-toggle-on' : 'btn-toggle-off' ?>">
                                    <?= ($p['is_active'] ?? 0) ? 'Deactivate' : 'Activate' ?>
                                </button>
                            </form>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this promo code?')">
                                <input type="hidden" name="delete_promo" value="1">
                                <input type="hidden" name="delete_id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-sm btn-delete">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
