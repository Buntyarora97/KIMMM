<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }

$db = db();
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: promo-codes.php'); exit; }

// Get promo code details
$stmt = $db->prepare("SELECT * FROM promo_codes WHERE id = ?");
$stmt->execute([$id]);
$promo = $stmt->fetch();
if (!$promo) { header('Location: promo-codes.php'); exit; }

// Get all usage history with order and product details
$usageList = [];
$productBreakdown = [];
try {
    $usageStmt = $db->prepare("
        SELECT u.*, 
               u.order_number,
               u.customer_name,
               u.customer_phone,
               u.order_total,
               u.discount_given,
               u.commission_earned,
               u.used_at
        FROM promo_code_usage u
        WHERE u.promo_code_id = ?
        ORDER BY u.used_at DESC
    ");
    $usageStmt->execute([$id]);
    $usageList = $usageStmt->fetchAll() ?: [];
} catch (Exception $e) { $usageList = []; }

// Get products ordered via this promo code
try {
    $productStmt = $db->prepare("
        SELECT 
            oi.product_name,
            SUM(oi.quantity) as total_qty,
            SUM(oi.total) as total_revenue,
            COUNT(DISTINCT u.order_id) as order_count
        FROM promo_code_usage u
        JOIN order_items oi ON oi.order_id = u.order_id
        WHERE u.promo_code_id = ?
        GROUP BY oi.product_name
        ORDER BY total_qty DESC
    ");
    $productStmt->execute([$id]);
    $productBreakdown = $productStmt->fetchAll() ?: [];
} catch (Exception $e) { $productBreakdown = []; }

// Summary stats
$totalOrders = count($usageList);
$totalRevenue = array_sum(array_column($usageList, 'order_total'));
$totalDiscount = array_sum(array_column($usageList, 'discount_given'));
$totalCommission = array_sum(array_column($usageList, 'commission_earned'));

$pageTitle = 'Report: ' . $promo['code'];
require_once __DIR__ . '/views/layouts/header.php';
?>

<style>
.report-page { padding: 24px; max-width: 1200px; margin: 0 auto; }
.back-link { display: inline-flex; align-items: center; gap: 6px; color: #6b7280; text-decoration: none; font-size: 14px; margin-bottom: 20px; }
.back-link:hover { color: #C9A227; }
.influencer-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 16px; padding: 28px 32px; margin-bottom: 28px;
    display: flex; justify-content: space-between; align-items: center;
    color: #fff;
}
.influencer-header .left h2 { font-size: 24px; font-weight: 800; margin: 0 0 6px 0; }
.influencer-header .left .sub { font-size: 14px; color: #9ca3af; }
.influencer-header .code-display {
    background: rgba(201,162,39,0.15); border: 2px solid #C9A227;
    border-radius: 12px; padding: 16px 28px; text-align: center;
}
.influencer-header .code-display .code { font-size: 32px; font-weight: 900; color: #C9A227; font-family: monospace; letter-spacing: 3px; }
.influencer-header .code-display .label { font-size: 11px; color: #9ca3af; text-transform: uppercase; margin-top: 4px; }

.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
.stat-box { background: #fff; border-radius: 12px; padding: 20px 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); border: 1px solid #e5e7eb; }
.stat-box .stat-label { font-size: 12px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.stat-box .stat-val { font-size: 28px; font-weight: 800; margin-top: 6px; }
.stat-box .stat-sub { font-size: 12px; color: #9ca3af; margin-top: 4px; }
.stat-box.orders .stat-val { color: #2563eb; }
.stat-box.revenue .stat-val { color: #059669; }
.stat-box.discount .stat-val { color: #dc2626; }
.stat-box.commission .stat-val { color: #C9A227; }

.section-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 24px; }
.section-card .section-header { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; }
.section-card .section-header h3 { font-size: 15px; font-weight: 700; color: #1a1a2e; margin: 0; }
.section-card table { width: 100%; border-collapse: collapse; }
.section-card th { padding: 11px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
.section-card td { padding: 12px 16px; font-size: 14px; color: #374151; border-bottom: 1px solid #f3f4f6; }
.section-card tr:last-child td { border-bottom: none; }
.section-card tr:hover td { background: #fefce8; }

.order-link { color: #2563eb; text-decoration: none; font-weight: 700; font-family: monospace; }
.order-link:hover { text-decoration: underline; }
.empty-state { text-align: center; padding: 50px 20px; color: #9ca3af; font-size: 14px; }

.info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
.info-box { background: #fff; border-radius: 10px; padding: 16px 20px; border: 1px solid #e5e7eb; }
.info-box .info-label { font-size: 11px; color: #9ca3af; font-weight: 600; text-transform: uppercase; }
.info-box .info-val { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-top: 4px; }
.badge-active { background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
.badge-inactive { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
.print-btn { background: #1a1a2e; color: #fff; border: none; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.print-btn:hover { background: #C9A227; }
</style>

<div class="report-page">
    <a href="promo-codes.php" class="back-link">← Wapas Promo Codes</a>

    <!-- Influencer Header -->
    <div class="influencer-header">
        <div class="left">
            <h2><?= htmlspecialchars($promo['influencer_name'] ?: 'Unknown Influencer') ?></h2>
            <div class="sub">
                <?php if ($promo['influencer_email']): ?>
                    📧 <?= htmlspecialchars($promo['influencer_email']) ?>
                <?php endif; ?>
                <?php if ($promo['notes']): ?>
                    &nbsp;|&nbsp; 📝 <?= htmlspecialchars($promo['notes']) ?>
                <?php endif; ?>
            </div>
            <div style="margin-top: 10px;">
                <span class="<?= $promo['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                    <?= $promo['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
                <?php if ($promo['expiry_date']): ?>
                    <span style="font-size:13px; color:#9ca3af; margin-left:10px;">
                        Expiry: <?= date('d M Y', strtotime($promo['expiry_date'])) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:20px;">
            <a href="javascript:window.print()" class="print-btn">🖨 Print Report</a>
            <div class="code-display">
                <div class="code"><?= htmlspecialchars($promo['code']) ?></div>
                <div class="label">Promo Code</div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-box orders">
            <div class="stat-label">Kul Orders</div>
            <div class="stat-val"><?= $totalOrders ?></div>
            <div class="stat-sub">Is code se nikle</div>
        </div>
        <div class="stat-box revenue">
            <div class="stat-label">Kul Revenue</div>
            <div class="stat-val">₹<?= number_format($totalRevenue, 0) ?></div>
            <div class="stat-sub">Sabhi orders ka total</div>
        </div>
        <div class="stat-box discount">
            <div class="stat-label">Diya Gaya Discount</div>
            <div class="stat-val">₹<?= number_format($totalDiscount, 0) ?></div>
            <div class="stat-sub">Customers ko benefit</div>
        </div>
        <div class="stat-box commission">
            <div class="stat-label">Influencer Commission</div>
            <div class="stat-val">₹<?= number_format($totalCommission, 0) ?></div>
            <div class="stat-sub">
                <?= $promo['commission_type'] === 'percentage' ? $promo['commission_value'] . '% per order' : '₹' . $promo['commission_value'] . ' per order' ?>
            </div>
        </div>
    </div>

    <!-- Promo Code Info -->
    <div class="info-grid">
        <div class="info-box">
            <div class="info-label">Discount</div>
            <div class="info-val">
                <?= $promo['discount_type'] === 'percentage' ? $promo['discount_value'] . '%' : '₹' . number_format($promo['discount_value'], 0) ?>
                <?php if ($promo['max_discount'] > 0): ?>
                    <span style="font-size:12px;color:#9ca3af;">(Max ₹<?= $promo['max_discount'] ?>)</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="info-box">
            <div class="info-label">Min Order Amount</div>
            <div class="info-val">₹<?= number_format($promo['min_order_amount'], 0) ?></div>
        </div>
        <div class="info-box">
            <div class="info-label">Usage</div>
            <div class="info-val"><?= $promo['used_count'] ?> / <?= $promo['usage_limit'] > 0 ? $promo['usage_limit'] : '∞' ?></div>
        </div>
    </div>

    <!-- Product Breakdown -->
    <?php if (!empty($productBreakdown)): ?>
    <div class="section-card">
        <div class="section-header">
            <h3>📦 Product-wise Breakdown</h3>
            <span style="font-size:13px;color:#9ca3af;">Konse products kharide gaye</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Orders</th>
                    <th>Qty Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productBreakdown as $prod): ?>
                <tr>
                    <td style="font-weight:600;"><?= htmlspecialchars($prod['product_name']) ?></td>
                    <td style="color:#2563eb;font-weight:700;"><?= $prod['order_count'] ?></td>
                    <td style="font-weight:700;"><?= $prod['total_qty'] ?></td>
                    <td style="color:#059669;font-weight:700;">₹<?= number_format($prod['total_revenue'], 0) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Order History -->
    <div class="section-card">
        <div class="section-header">
            <h3>📋 Order History</h3>
            <span style="font-size:13px;color:#9ca3af;"><?= $totalOrders ?> orders</span>
        </div>
        <?php if (empty($usageList)): ?>
            <div class="empty-state">Abhi tak is code se koi order nahi aaya.</div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order No.</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Order Total</th>
                    <th>Discount Diya</th>
                    <th>Commission</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $sno = 1; foreach ($usageList as $u): ?>
                <tr>
                    <td style="color:#9ca3af;"><?= $sno++ ?></td>
                    <td>
                        <a href="order-view.php?id=<?= $u['order_id'] ?>" class="order-link">
                            <?= htmlspecialchars($u['order_number']) ?>
                        </a>
                    </td>
                    <td style="font-weight:600;"><?= htmlspecialchars($u['customer_name']) ?></td>
                    <td><?= htmlspecialchars($u['customer_phone']) ?></td>
                    <td style="font-weight:700;color:#059669;">₹<?= number_format($u['order_total'], 0) ?></td>
                    <td style="color:#dc2626;font-weight:700;">-₹<?= number_format($u['discount_given'], 0) ?></td>
                    <td style="color:#C9A227;font-weight:700;">₹<?= number_format($u['commission_earned'], 0) ?></td>
                    <td style="font-size:13px;color:#6b7280;"><?= date('d M Y, h:i A', strtotime($u['used_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
