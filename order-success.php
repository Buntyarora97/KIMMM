<?php
require_once 'includes/config.php';

$orderNumber = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : '';

$pageTitle = 'Order Confirmation';
require_once 'includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner">
    <div class="page-banner-content">
        <h1>Order Confirmed</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Order Confirmation</span>
        </div>
    </div>
</section>

<section style="padding: 80px 0;">
    <div class="container">
        <div class="order-success reveal" style="max-width: 600px; margin: 0 auto; text-align: center; background: #fff; padding: 50px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
                <i class="fas fa-check" style="font-size: 50px; color: #fff;"></i>
            </div>
            
            <h2 style="font-size: 32px; color: #1a1a2e; margin-bottom: 15px;">Thank You!</h2>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">Your order has been placed successfully.</p>
            
            <?php if ($orderNumber): ?>
            <div style="background: #f8f9fa; padding: 25px; border-radius: 12px; margin-bottom: 30px;">
                <p style="color: #888; margin-bottom: 5px;">Order Number</p>
                <h3 style="font-size: 28px; color: #C9A227;"><?php echo $orderNumber; ?></h3>
            </div>
            <?php endif; ?>
            
            <p style="color: #666; margin-bottom: 30px;">
                We have sent an order confirmation to your email. You will receive another notification when your order is shipped.
            </p>
            
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="products.php" style="display: inline-flex; align-items: center; gap: 8px; padding: 15px 30px; background: #C9A227; color: #fff; text-decoration: none; border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
                <a href="index.php" style="display: inline-flex; align-items: center; gap: 8px; padding: 15px 30px; background: #f8f9fa; color: #666; text-decoration: none; border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
            
            <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #eee;">
                <p style="color: #888; font-size: 14px;">
                    Need help? Contact us at <a href="mailto:<?php echo SITE_EMAIL; ?>" style="color: #C9A227;"><?php echo SITE_EMAIL; ?></a>
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
