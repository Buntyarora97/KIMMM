<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

$orderId = $_GET['order_id'] ?? 0;
$reason = $_GET['reason'] ?? '';
?>

<section class="section" style="padding: 120px 0 80px; text-align: center; background: var(--luxury-cream);">
    <div class="container">
        <div style="background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;">
            <div style="color: #e53e3e; font-size: 64px; margin-bottom: 20px;">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2 style="font-family: var(--font-display); color: #2d3748; margin-bottom: 10px;">Payment Failed</h2>
            <p style="color: #718096; margin-bottom: 30px;">
                We couldn't process your payment. Please try again or use a different payment method.
                <?php if ($reason === 'hash_mismatch'): ?>
                    <br><small style="color: #e53e3e;">(Security Verification Failed)</small>
                <?php endif; ?>
            </p>
            
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="payment.php?order_id=<?= $orderId ?>" class="btn" style="background: var(--primary-gold); color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    Retry Payment
                </a>
                <a href="index.php" class="btn" style="background: #edf2f7; color: #4a5568; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    Go Home
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
