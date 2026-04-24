<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

// Fetch Best Selling Products
$featuredProducts = [];
try {
    $featuredProducts = Product::getAll(true);
    if (count($featuredProducts) > 4) {
        $featuredProducts = array_slice($featuredProducts, 0, 4);
    }
} catch (Exception $e) {
    error_log("Track Order Products Error: " . $e->getMessage());
}

$pageTitle = "Track Your Order";
?>

<div class="track-order-page" style="background: #FDFBF0; min-height: 100vh; padding: 120px 0 60px;">
    <div class="container">
        <div class="track-card" style="max-width: 600px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center;">
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #333; margin-bottom: 10px;">Track Order</h1>
            <p style="color: #666; margin-bottom: 30px;">Locate the progress of your order</p>
            
            <form id="trackForm" style="margin-bottom: 30px;">
                <div style="margin-bottom: 20px;">
                    <input type="text" id="orderId" placeholder="Enter Order ID (e.g. #ORD12345)" required 
                           style="width: 100%; padding: 15px 20px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; background: #f9f9f9;">
                </div>
                <button type="submit" style="background: #4A7C59; color: #fff; padding: 12px 40px; border-radius: 5px; font-size: 1rem; font-weight: 600; cursor: pointer; border: none; transition: 0.3s;">
                    Track
                </button>
            </form>
            
            <div id="trackingResult" style="display: none; text-align: left; margin-top: 30px; padding: 20px; border-top: 1px solid #eee;">
                <!-- Result will show here -->
            </div>
            
            <p style="font-size: 0.9rem; color: #888; margin-top: 20px;">
                NOTE: You can find this number on the order confirmation email you would have received.
            </p>
        </div>

        <!-- Featured Products Section -->
        <div class="featured-products" style="margin-top: 80px;">
            <h2 style="font-family: 'Playfair Display', serif; text-align: center; margin-bottom: 40px; font-size: 2rem; color: #4A7C59;">Best Sellers For You</h2>
            <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <?php if (!empty($featuredProducts)): ?>
                    <?php foreach ($featuredProducts as $product): 
                        $discount = $product['mrp'] > 0 ? round((($product['mrp'] - $product['price']) / $product['mrp']) * 100) : 0;
                    ?>
                    <div class="product-card" style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; position: relative; border: 1px solid #eee;">
                        <?php if ($discount > 0): ?>
                        <span style="position: absolute; top: 10px; left: 10px; background: #C9A227; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; z-index: 10;"><?php echo $discount; ?>% OFF</span>
                        <?php endif; ?>
                        <div class="product-image" style="height: 200px; overflow: hidden; background: #f9f9f9;">
                            <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="product-info" style="padding: 15px;">
                            <h3 style="font-size: 1rem; margin-bottom: 10px; height: 40px; overflow: hidden;"><a href="<?php echo productUrl($product); ?>" style="color: #333;"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                            <div class="product-price" style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                                <span class="current-price" style="font-weight: 700; color: #4A7C59; font-size: 1.1rem;">₹<?php echo number_format((float)$product['price'], 2); ?></span>
                                <?php if ((float)$product['mrp'] > (float)$product['price']): ?>
                                <span class="original-price" style="text-decoration: line-through; color: #999; font-size: 0.85rem;">₹<?php echo number_format((float)$product['mrp'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo productUrl($product); ?>" style="display: block; text-align: center; background: #4A7C59; color: #fff; padding: 8px; border-radius: 5px; font-size: 0.9rem; font-weight: 600;">View Product</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; grid-column: 1/-1;">Check out our <a href="products.php" style="color: #4A7C59; font-weight: 600;">latest collection</a> of Ayurvedic premium products.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('trackForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const orderId = document.getElementById('orderId').value;
    const resultDiv = document.getElementById('trackingResult');
    
    // Simulate tracking
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = `
        <div style="background: #f0f7f2; padding: 15px; border-radius: 8px; border-left: 5px solid #4A7C59;">
            <h4 style="color: #4A7C59; margin-bottom: 5px;">Order Status: Processing</h4>
            <p style="font-size: 0.9rem; color: #666;">Your order <strong>${orderId}</strong> is being prepared and will be shipped soon.</p>
        </div>
        <div style="margin-top: 20px;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div style="width: 12px; height: 12px; background: #4A7C59; border-radius: 50%;"></div>
                <div style="flex: 1;">
                    <p style="font-weight: 600; margin: 0;">Order Placed</p>
                    <p style="font-size: 0.8rem; color: #999;">Confirmed</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px; opacity: 0.5;">
                <div style="width: 12px; height: 12px; background: #ccc; border-radius: 50%;"></div>
                <div style="flex: 1;">
                    <p style="font-weight: 600; margin: 0;">Shipped</p>
                    <p style="font-size: 0.8rem; color: #999;">Pending</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 15px; opacity: 0.5;">
                <div style="width: 12px; height: 12px; background: #ccc; border-radius: 50%;"></div>
                <div style="flex: 1;">
                    <p style="font-weight: 600; margin: 0;">Out for Delivery</p>
                    <p style="font-size: 0.8rem; color: #999;">Pending</p>
                </div>
            </div>
        </div>
    `;
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
