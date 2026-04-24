<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Product.php';

/*
|--------------------------------------------------------------------------
| Global Image Path (ABSOLUTE - NEVER BREAKS)
|--------------------------------------------------------------------------
*/
define('BASE_URL', '/'); 
define('PRODUCT_IMG_PATH', BASE_URL . 'uploads/products/');

// Add to cart logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    switch ($_POST['action']) {
       case 'add':
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $product = Product::getById($productId);

            if ($product) {
               $final_price = (float)$product['final_price'];
               $original_price = (float)$product['price'];
               
                if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$productId] = [
                        'id' => $productId,
                        'name' => $product['name'],
                        'price' => $final_price,
                        'original_price' => $original_price,
                        'image' => $product['image'],
                        'category_name' => $product['category_name'],
                        'quantity' => $quantity,
                        'discount_percent' => $product['discount_percent'] ?? 0
                    ];
                }
            }
            break;

        case 'buy_now':
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $product = Product::getById($productId);

            if ($product) {
                $final_price = (float)$product['final_price'];
                $original_price = (float)$product['price'];
                
                // Clear old cart
                $_SESSION['cart'] = [];

                // Add only this product
                $_SESSION['cart'][$productId] = [
                    'id' => $productId,
                    'name' => $product['name'],
                    'price' => $final_price,
                    'original_price' => $original_price,
                    'image' => $product['image'],
                    'category_name' => $product['category_name'],
                    'quantity' => $quantity,
                    'discount_percent' => $product['discount_percent'] ?? 0
                ];
            }

            header('Location: checkout.php');
            exit;
            
        case 'update':
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            if (isset($_SESSION['cart'][$productId])) {
                if ($quantity > 0) {
                    $_SESSION['cart'][$productId]['quantity'] = $quantity;
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
            }
            break;
            
        case 'remove':
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
            break;
    }
    
    header('Location: cart.php');
    exit;
}

$pageTitle = 'Shopping Cart';
$cartItems = $_SESSION['cart'] ?? [];

// Calculate totals with discount tracking
$subtotal = 0;
$totalOriginal = 0;
$totalSavings = 0;

foreach ($cartItems as $item) {
    $itemTotal = $item['price'] * $item['quantity'];
    $itemOriginal = ($item['original_price'] ?? $item['price']) * $item['quantity'];
    
    $subtotal += $itemTotal;
    $totalOriginal += $itemOriginal;
    $totalSavings += ($itemOriginal - $itemTotal);
}

$shipping = getShippingFee($subtotal);
$total = $subtotal + $shipping;

// Get featured products
$featuredProducts = Product::getFeatured();

require_once 'includes/header.php';
?>

<div class="cart-page-wrapper">
    <div class="container">
        <div class="cart-header" data-aos="fade-down">
            <h1 class="cart-title">Shopping Cart <span class="item-count">(<?php echo count($cartItems); ?> items)</span></h1>
            <a href="products.php" class="continue-shopping">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Continue Shopping
            </a>
        </div>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart-state" data-aos="fade-up">
                <div class="empty-cart-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added anything to your cart yet.</p>
                <a href="products.php" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="cart-grid">
                <div class="cart-main" data-aos="fade-right">
                    <div class="cart-card">
                        <div class="cart-items-header">
                            <span class="header-product">Product</span>
                            <span class="header-price">Price</span>
                            <span class="header-qty">Quantity</span>
                            <span class="header-total">Total</span>
                        </div>
                        
                        <?php foreach ($cartItems as $index => $item): 
                            $displayImg = PRODUCT_IMG_PATH . $item['image'];
                            $itemTotal = $item['price'] * $item['quantity'];
                            $itemOriginalTotal = ($item['original_price'] ?? $item['price']) * $item['quantity'];
                            $itemSavings = $itemOriginalTotal - $itemTotal;
                            $hasDiscount = ($item['original_price'] ?? 0) > $item['price'];
                        ?>
                            <div class="cart-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>" style="--delay: <?php echo $index * 0.1; ?>s">
                                <div class="cart-item-product">
                                    <div class="cart-item-img-wrapper">
                                        <img src="<?php echo htmlspecialchars($displayImg); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                                        <?php if ($hasDiscount): ?>
                                            <span class="item-discount-badge">-<?php echo round((($item['original_price'] - $item['price']) / $item['original_price']) * 100); ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="cart-item-details">
                                        <span class="cart-item-cat"><?php echo htmlspecialchars($item['category_name'] ?? 'Wellness'); ?></span>
                                        <a href="<?php echo productUrl($item); ?>" class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></a>
                                        <?php if ($hasDiscount): ?>
                                            <div class="item-savings">
                                                <span class="savings-amount">You save ₹<?php echo number_format($itemSavings, 2); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="cart-item-price">
                                    <?php if ($hasDiscount): ?>
                                        <div class="price-stack">
                                            <span class="current-price">₹<?php echo number_format($item['price'], 2); ?></span>
                                            <span class="original-price">₹<?php echo number_format($item['original_price'], 2); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="current-price">₹<?php echo number_format($item['price'], 2); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="cart-item-quantity">
                                    <div class="qty-box">
                                        <form action="cart.php" method="post" class="qty-form">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" name="quantity" value="<?php echo $item['quantity'] - 1; ?>" class="qty-btn minus" <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                            </button>
                                            <input type="number" value="<?php echo $item['quantity']; ?>" readonly class="qty-input">
                                            <button type="submit" name="quantity" value="<?php echo $item['quantity'] + 1; ?>" class="qty-btn plus">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="cart-item-total">
                                    <span class="total-amount">₹<?php echo number_format($itemTotal, 2); ?></span>
                                    <?php if ($hasDiscount): ?>
                                        <span class="original-total">₹<?php echo number_format($itemOriginalTotal, 2); ?></span>
                                    <?php endif; ?>
                                    <form action="cart.php" method="post" class="remove-form">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="remove-btn" title="Remove item">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="cart-footer">
                            <div class="savings-banner" <?php echo $totalSavings > 0 ? '' : 'style="display:none;"'; ?>>
                                <div class="savings-icon">🎉</div>
                                <div class="savings-text">
                                    <strong>Great Savings!</strong>
                                    <span>You saved ₹<?php echo number_format($totalSavings, 2); ?> on this order</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cart-sidebar" data-aos="fade-left">
                    <div class="cart-card summary-card sticky-summary">
                        <h3 class="summary-title">Order Summary</h3>
                        
                        <div class="summary-content">
                            <div class="summary-row">
                                <span class="label">Subtotal</span>
                                <span class="value">₹<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            
                            <?php if ($totalSavings > 0): ?>
                            <div class="summary-row savings-row">
                                <span class="label">Total Savings</span>
                                <span class="value savings-value">-₹<?php echo number_format($totalSavings, 2); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="summary-row">
                                <span class="label">Shipping</span>
                                <span class="value <?php echo $shipping == 0 ? 'free-shipping' : ''; ?>">
                                    <?php echo $shipping == 0 ? 'FREE' : '₹' . number_format($shipping, 2); ?>
                                </span>
                            </div>
                            
                            <div class="promo-code-section">
                                <button class="promo-toggle" onclick="togglePromo()">
                                    <span>Have a promo code?</span>
                                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                                <div class="promo-input-wrapper" id="promoWrapper">
                                    <input type="text" placeholder="Enter promo code" class="promo-input">
                                    <button class="promo-apply">Apply</button>
                                </div>
                            </div>
                            
                            <div class="summary-divider"></div>
                            
                            <div class="summary-total">
                                <div class="total-label">
                                    <span>Total</span>
                                    <small>Inclusive of all taxes</small>
                                </div>
                                <div class="total-value">
                                    <span class="final-total">₹<?php echo number_format($total, 2); ?></span>
                                    <?php if ($totalSavings > 0): ?>
                                        <span class="original-total">₹<?php echo number_format($totalOriginal + $shipping, 2); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <a href="checkout.php" class="checkout-btn">
                            <span>Proceed to Checkout</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>

                        <div class="trust-badges">
                            <div class="badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span>Secure Payment</span>
                            </div>
                            <div class="badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                </svg>
                                <span>Free Returns</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Featured Products Section -->
    <?php if (!empty($featuredProducts)): ?>
    <div class="container featured-section">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">You Might Also Like</h2>
            <a href="products.php" class="view-all">View All</a>
        </div>
        <div class="featured-grid">
            <?php foreach (array_slice($featuredProducts, 0, 4) as $index => $fp): 
                $fpDiscount = ($fp['price'] > $fp['final_price']) ? round((($fp['price'] - $fp['final_price']) / $fp['price']) * 100) : 0;
            ?>
                <div class="featured-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="product-img">
                        <?php if ($fpDiscount > 0): ?>
                            <span class="featured-discount">-<?php echo $fpDiscount; ?>%</span>
                        <?php endif; ?>
                        <img src="<?php echo PRODUCT_IMG_PATH . $fp['image']; ?>" alt="<?php echo htmlspecialchars($fp['name']); ?>" loading="lazy">
                        <div class="quick-add">
                            <form action="cart.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $fp['id']; ?>">
                                <button type="submit" class="quick-add-btn">+ Quick Add</button>
                            </form>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($fp['name']); ?></h3>
                        <div class="product-price">
                            <?php if ($fpDiscount > 0): ?>
                                <span class="sale-price">₹<?php echo number_format($fp['final_price'], 2); ?></span>
                                <span class="regular-price">₹<?php echo number_format($fp['price'], 2); ?></span>
                            <?php else: ?>
                                <span class="sale-price">₹<?php echo number_format($fp['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Sticky Mobile Checkout -->
<?php if (!empty($cartItems)): ?>
<div class="sticky-mobile-checkout" id="stickyCheckout">
    <div class="sticky-content">
        <div class="sticky-info">
            <span class="sticky-total">₹<?php echo number_format($total, 2); ?></span>
            <span class="sticky-items"><?php echo count($cartItems); ?> items</span>
        </div>
        <a href="checkout.php" class="sticky-btn">Checkout</a>
    </div>
</div>
<?php endif; ?>

<script>
// Promo Code Toggle
function togglePromo() {
    const wrapper = document.getElementById('promoWrapper');
    const toggle = document.querySelector('.promo-toggle');
    wrapper.classList.toggle('active');
    toggle.classList.toggle('active');
}

// Sticky Checkout on Mobile
let lastScroll = 0;
const stickyCheckout = document.getElementById('stickyCheckout');

if (stickyCheckout) {
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        const cartBottom = document.querySelector('.cart-grid').getBoundingClientRect().bottom;
        
        if (currentScroll > 300 && cartBottom > 400) {
            stickyCheckout.classList.add('visible');
        } else {
            stickyCheckout.classList.remove('visible');
        }
    });
}

// Quantity Button Animation
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.style.transform = 'scale(0.9)';
        setTimeout(() => {
            this.style.transform = '';
        }, 150);
    });
});

// Remove Item Animation
document.querySelectorAll('.remove-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const item = this.closest('.cart-item');
        item.style.transform = 'translateX(100px)';
        item.style.opacity = '0';
        
        setTimeout(() => {
            this.submit();
        }, 300);
    });
});
</script>

<style>
/* ===== CSS VARIABLES ===== */
:root {
    --primary: #0f3d2e;
    --primary-light: #1a5f4a;
    --secondary: #b57d62;
    --accent: #ff9800;
    --danger: #e53935;
    --success: #10b981;
    --bg: #f8faf9;
    --card-bg: #ffffff;
    --text: #1a1a1a;
    --text-muted: #6b7280;
    --border: #e5e7eb;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    --radius: 16px;
    --radius-sm: 8px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== BASE STYLES ===== */
.cart-page-wrapper {
    background: linear-gradient(135deg, #f8faf9 0%, #f0f4f3 100%);
    padding: 40px 0 80px;
    min-height: 100vh;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 20px;
}

/* ===== CART HEADER ===== */
.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;
}

.cart-title {
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 800;
    color: var(--primary);
    letter-spacing: -0.02em;
}

.item-count {
    font-size: 1rem;
    color: var(--text-muted);
    font-weight: 500;
}

.continue-shopping {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--secondary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    padding: 10px 20px;
    border-radius: 50px;
    border: 2px solid transparent;
}

.continue-shopping:hover {
    border-color: var(--secondary);
    background: rgba(181, 125, 98, 0.05);
}

.continue-shopping svg {
    width: 20px;
    height: 20px;
    transition: var(--transition);
}

.continue-shopping:hover svg {
    transform: translateX(-4px);
}

/* ===== EMPTY CART STATE ===== */
.empty-cart-state {
    text-align: center;
    padding: 100px 20px;
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    max-width: 500px;
    margin: 0 auto;
}

.empty-cart-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.empty-cart-icon svg {
    width: 60px;
    height: 60px;
    color: var(--text-muted);
}

.empty-cart-state h2 {
    font-size: 1.5rem;
    color: var(--text);
    margin-bottom: 12px;
}

.empty-cart-state p {
    color: var(--text-muted);
    margin-bottom: 30px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 32px;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(15, 61, 46, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(15, 61, 46, 0.4);
}

/* ===== CART GRID ===== */
.cart-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
    align-items: start;
}

/* ===== CART CARD ===== */
.cart-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    border: 1px solid var(--border);
}

/* ===== CART ITEMS HEADER ===== */
.cart-items-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    padding: 20px 30px;
    background: #f9fafb;
    border-bottom: 1px solid var(--border);
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ===== CART ITEM ===== */
.cart-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    padding: 30px;
    gap: 20px;
    border-bottom: 1px solid var(--border);
    align-items: center;
    transition: var(--transition);
    animation: slideIn 0.5s ease-out backwards;
    animation-delay: var(--delay, 0s);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item:hover {
    background: #fafafa;
}

/* ===== PRODUCT COLUMN ===== */
.cart-item-product {
    display: flex;
    gap: 20px;
    align-items: center;
}

.cart-item-img-wrapper {
    position: relative;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    border-radius: var(--radius-sm);
    overflow: hidden;
    flex-shrink: 0;
}

.cart-item-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
    transition: var(--transition);
}

.cart-item:hover .cart-item-img-wrapper img {
    transform: scale(1.05);
}

.item-discount-badge {
    position: absolute;
    top: 5px;
    left: 5px;
    background: linear-gradient(135deg, var(--danger) 0%, #c62828 100%);
    color: white;
    font-size: 0.625rem;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.cart-item-details {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.cart-item-cat {
    font-size: 0.75rem;
    text-transform: uppercase;
    color: var(--secondary);
    font-weight: 700;
    letter-spacing: 0.05em;
}

.cart-item-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    text-decoration: none;
    line-height: 1.4;
    transition: var(--transition);
}

.cart-item-name:hover {
    color: var(--primary);
}

.item-savings {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8125rem;
    color: var(--success);
    font-weight: 600;
}

/* ===== PRICE COLUMN ===== */
.cart-item-price {
    text-align: center;
}

.price-stack {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.current-price {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--primary);
}

.original-price {
    font-size: 0.875rem;
    color: var(--text-muted);
    text-decoration: line-through;
}

/* ===== QUANTITY COLUMN ===== */
.cart-item-quantity {
    display: flex;
    justify-content: center;
}

.qty-box {
    display: inline-flex;
    align-items: center;
    background: #f3f4f6;
    border-radius: 50px;
    padding: 4px;
    border: 2px solid transparent;
    transition: var(--transition);
}

.qty-box:focus-within {
    border-color: var(--primary);
    background: white;
}

.qty-form {
    display: flex;
    align-items: center;
    gap: 4px;
}

.qty-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: white;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text);
    transition: var(--transition);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.qty-btn:hover:not(:disabled) {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

.qty-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.qty-btn svg {
    width: 16px;
    height: 16px;
}

.qty-input {
    width: 50px;
    text-align: center;
    border: none;
    background: transparent;
    font-weight: 700;
    font-size: 1rem;
    color: var(--text);
    -moz-appearance: textfield;
}

.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* ===== TOTAL COLUMN ===== */
.cart-item-total {
    text-align: right;
    position: relative;
}

.total-amount {
    display: block;
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 4px;
}

.original-total {
    display: block;
    font-size: 0.875rem;
    color: var(--text-muted);
    text-decoration: line-through;
    margin-bottom: 8px;
}

.remove-form {
    display: inline-block;
}

.remove-btn {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: auto;
}

.remove-btn:hover {
    background: #fee2e2;
    color: var(--danger);
    transform: rotate(90deg);
}

.remove-btn svg {
    width: 20px;
    height: 20px;
}

/* ===== CART FOOTER ===== */
.cart-footer {
    padding: 20px 30px;
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border-top: 1px solid var(--border);
}

.savings-banner {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    background: white;
    border-radius: var(--radius-sm);
    border-left: 4px solid var(--success);
    box-shadow: var(--shadow);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    50% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
}

.savings-icon {
    font-size: 1.5rem;
    animation: bounce 1s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.savings-text {
    display: flex;
    flex-direction: column;
}

.savings-text strong {
    color: var(--success);
    font-size: 0.9375rem;
}

.savings-text span {
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* ===== SIDEBAR ===== */
.sticky-summary {
    position: sticky;
    top: 20px;
}

.summary-card {
    padding: 30px;
}

.summary-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--border);
}

.summary-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9375rem;
}

.summary-row .label {
    color: var(--text-muted);
    font-weight: 500;
}

.summary-row .value {
    font-weight: 600;
    color: var(--text);
}

.savings-row .label {
    color: var(--success);
    font-weight: 600;
}

.savings-value {
    color: var(--success) !important;
    font-weight: 700;
}

.free-shipping {
    color: var(--success);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ===== PROMO CODE ===== */
.promo-code-section {
    margin: 10px 0;
}

.promo-toggle {
    width: 100%;
    background: none;
    border: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    color: var(--secondary);
    font-weight: 600;
    cursor: pointer;
    font-size: 0.9375rem;
    transition: var(--transition);
}

.promo-toggle:hover {
    color: var(--primary);
}

.promo-toggle .chevron {
    width: 20px;
    height: 20px;
    transition: var(--transition);
}

.promo-toggle.active .chevron {
    transform: rotate(180deg);
}

.promo-input-wrapper {
    display: none;
    gap: 10px;
    margin-top: 10px;
    animation: slideDown 0.3s ease;
}

.promo-input-wrapper.active {
    display: flex;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.promo-input {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 0.9375rem;
    transition: var(--transition);
}

.promo-input:focus {
    outline: none;
    border-color: var(--primary);
}

.promo-apply {
    padding: 12px 20px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius-sm);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.promo-apply:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
}

/* ===== SUMMARY TOTAL ===== */
.summary-divider {
    height: 1px;
    background: var(--border);
    margin: 10px 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
}

.total-label {
    display: flex;
    flex-direction: column;
}

.total-label span {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text);
}

.total-label small {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}

.total-value {
    text-align: right;
}

.final-total {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary);
}

.total-value .original-total {
    font-size: 0.875rem;
    color: var(--text-muted);
    text-decoration: line-through;
    font-weight: 500;
}

/* ===== CHECKOUT BUTTON ===== */
.checkout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.0625rem;
    margin-bottom: 20px;
    transition: var(--transition);
    box-shadow: 0 4px 15px rgba(15, 61, 46, 0.3);
    position: relative;
    overflow: hidden;
}

.checkout-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: 0.5s;
}

.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(15, 61, 46, 0.4);
}

.checkout-btn:hover::before {
    left: 100%;
}

.checkout-btn svg {
    width: 20px;
    height: 20px;
    transition: var(--transition);
}

.checkout-btn:hover svg {
    transform: translateX(4px);
}

/* ===== TRUST BADGES ===== */
.trust-badges {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: #f9fafb;
    border-radius: var(--radius-sm);
    font-size: 0.8125rem;
    color: var(--text-muted);
    font-weight: 500;
}

.badge svg {
    width: 20px;
    height: 20px;
    color: var(--primary);
    flex-shrink: 0;
}

/* ===== FEATURED SECTION ===== */
.featured-section {
    margin-top: 80px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
}

.view-all {
    color: var(--secondary);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: var(--transition);
}

.view-all:hover {
    color: var(--primary);
    gap: 8px;
}

.featured-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.featured-item {
    background: var(--card-bg);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: 1px solid var(--border);
    animation: fadeInUp 0.6s ease-out backwards;
    animation-delay: var(--delay, 0s);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.featured-item:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.product-img {
    position: relative;
    height: 220px;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    overflow: hidden;
}

.product-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 20px;
    transition: var(--transition);
}

.featured-item:hover .product-img img {
    transform: scale(1.05);
}

.featured-discount {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, var(--danger) 0%, #c62828 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    z-index: 2;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.quick-add {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 15px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    transform: translateY(100%);
    transition: var(--transition);
}

.featured-item:hover .quick-add {
    transform: translateY(0);
}

.quick-add-btn {
    width: 100%;
    padding: 12px;
    background: white;
    color: var(--text);
    border: none;
    border-radius: var(--radius-sm);
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
}

.quick-add-btn:hover {
    background: var(--primary);
    color: white;
}

.product-info {
    padding: 20px;
}

.product-info h3 {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 10px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 42px;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sale-price {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--primary);
}

.regular-price {
    font-size: 0.875rem;
    color: var(--text-muted);
    text-decoration: line-through;
}

/* ===== STICKY MOBILE CHECKOUT ===== */
.sticky-mobile-checkout {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 16px 20px;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
    z-index: 1000;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    border-top: 3px solid var(--primary);
}

.sticky-mobile-checkout.visible {
    transform: translateY(0);
}

.sticky-content {
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sticky-info {
    display: flex;
    flex-direction: column;
}

.sticky-total {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--primary);
}

.sticky-items {
    font-size: 0.875rem;
    color: var(--text-muted);
}

.sticky-btn {
    padding: 14px 32px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 700;
    transition: var(--transition);
    box-shadow: 0 4px 15px rgba(15, 61, 46, 0.3);
}

.sticky-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(15, 61, 46, 0.4);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .cart-grid {
        grid-template-columns: 1fr;
    }
    
    .sticky-summary {
        position: static;
    }
    
    .featured-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .cart-header {
        flex-direction: column;
        text-align: center;
    }
    
    .cart-items-header {
        display: none;
    }
    
    .cart-item {
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 20px;
    }
    
    .cart-item-product {
        flex-direction: row;
    }
    
    .cart-item-price,
    .cart-item-quantity,
    .cart-item-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 15px;
        border-top: 1px dashed var(--border);
    }
    
    .cart-item-price::before {
        content: 'Price:';
        font-weight: 600;
        color: var(--text-muted);
    }
    
    .cart-item-quantity::before {
        content: 'Quantity:';
        font-weight: 600;
        color: var(--text-muted);
    }
    
    .cart-item-total::before {
        content: 'Total:';
        font-weight: 600;
        color: var(--text-muted);
    }
    
    .remove-form {
        margin-left: 0;
    }
    
    .cart-footer {
        padding: 15px 20px;
    }
    
    .trust-badges {
        grid-template-columns: 1fr;
    }
    
    .featured-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .product-img {
        height: 180px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 12px;
    }
    
    .cart-item-img-wrapper {
        width: 80px;
        height: 80px;
    }
    
    .featured-grid {
        grid-template-columns: 1fr;
    }
    
    .sticky-btn {
        padding: 12px 24px;
        font-size: 0.9375rem;
    }
}

/* ===== SCROLLBAR ===== */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-light);
}
</style>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 600,
        once: true,
        offset: 50
    });
</script>