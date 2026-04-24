<?php
require_once 'includes/config.php';

$categorySlug = $_GET['category'] ?? null;
$searchQuery  = $_GET['search'] ?? null;

$allCategories = Category::getAll(true);

// Get filter parameters
$priceMin     = $_GET['price_min'] ?? null;
$priceMax     = $_GET['price_max'] ?? null;
$ratingFilter = $_GET['rating'] ?? null;
$sortBy       = $_GET['sort'] ?? 'newest';
$inStockOnly  = isset($_GET['in_stock']) ? true : false;
$discountOnly = isset($_GET['discount']) ? true : false;

// Price ranges for filter
$priceRanges = [
    // ['min' => 0, 'max' => 500, 'label' => 'Under ₹500'],
    ['min' => 500, 'max' => 1000, 'label' => '₹500 - ₹1,000'],
    ['min' => 1000, 'max' => 2000, 'label' => '₹1,000 - ₹2,000'],
    ['min' => 2000, 'max' => 5000, 'label' => '₹2,000 - ₹5,000'],
    ['min' => 5000, 'max' => null, 'label' => 'Above ₹5,000']
];

if ($categorySlug) {
    $filteredProducts = Product::getByCategory($categorySlug);
    $currentCategory  = Category::getBySlug($categorySlug);
    $pageTitle        = $currentCategory ? $currentCategory['name'] : 'Products';
    $metaDescription  = 'Shop ' . ($currentCategory['name'] ?? 'ayurvedic') . ' products online in India. Explore our premium range of natural health supplements and herbal wellness products.';
    $metaKeywords     = 'buy ayurvedic products online, herbal store india, ayurvedic supplements shop, natural products online, wellness shop';
    $canonicalUrl     = 'https://livvra.in/buy-ayurvedic-products-online';
} elseif ($searchQuery) {
    $filteredProducts = Product::search($searchQuery);
    $pageTitle        = 'Search Results for "' . htmlspecialchars($searchQuery) . '"';
    $metaDescription  = 'Search results for ' . htmlspecialchars($searchQuery) . ' on Livvra. Find the best ayurvedic products online in India.';
    $metaKeywords     = 'ayurvedic products india, herbal wellness products, natural health supplements';
    $canonicalUrl     = 'https://livvra.in/buy-ayurvedic-products-online';
} else {
    $filteredProducts = Product::getAll(true);
    $pageTitle        = 'Buy Ayurvedic Products Online | Best Herbal Store India';
    $metaDescription  = 'Explore our complete range of premium ayurvedic products online. From pure shilajit to natural skincare and organic protein. Buy the best herbal supplements!';
    $metaKeywords     = 'buy ayurvedic products online, herbal store india, ayurvedic supplements shop, natural products online, wellness shop';
    $canonicalUrl     = 'https://livvra.in/buy-ayurvedic-products-online';
}

// Apply additional filters
if ($priceMin || $priceMax || $ratingFilter || $inStockOnly || $discountOnly) {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($priceMin, $priceMax, $ratingFilter, $inStockOnly, $discountOnly) {
        if ($priceMin && $product['final_price'] < $priceMin) return false;
        if ($priceMax && $product['final_price'] > $priceMax) return false;
        if ($ratingFilter && ($product['rating'] ?? 0) < $ratingFilter) return false;
        if ($inStockOnly && $product['stock_qty'] <= 0) return false;
        if ($discountOnly && $product['discount_percent'] <= 0) return false;
        return true;
    });
}

// Sorting
switch($sortBy) {
    case 'price_low':
        usort($filteredProducts, fn($a, $b) => $a['final_price'] <=> $b['final_price']);
        break;
    case 'price_high':
        usort($filteredProducts, fn($a, $b) => $b['final_price'] <=> $a['final_price']);
        break;
    case 'rating':
        usort($filteredProducts, fn($a, $b) => ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0));
        break;
    case 'discount':
        usort($filteredProducts, fn($a, $b) => $b['discount_percent'] <=> $a['discount_percent']);
        break;
}

require_once 'includes/header.php';
?>

<section class="products-section">
    <div class="container">
        
        <!-- Header with Search -->
        <div class="products-header" data-aos="fade-down">
            <div class="header-left">
                <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
                <p>Premium Ayurvedic Wellness Products</p>
                <div class="header-line"></div>
            </div>
            
            <!-- Search Bar -->
            <div class="header-search">
                <form action="products.php" method="GET" class="search-form">
                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($searchQuery ?? ''); ?>" class="search-input">
                        <?php if($categorySlug): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($categorySlug); ?>">
                        <?php endif; ?>
                        <button type="submit" class="search-btn">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="products-layout">
            
            <!-- LEFT SIDEBAR - ALL FILTERS -->
            <aside class="filters-sidebar">
                <div class="sidebar-inner">
                    
                    <!-- Mobile Filter Toggle -->
                    <button class="mobile-filter-btn" onclick="toggleMobileFilters()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        Filters
                    </button>

                    <!-- Active Filters -->
                    <?php if($categorySlug || $priceMin || $ratingFilter || $inStockOnly || $discountOnly || $searchQuery): ?>
                    <div class="filter-box active-filters">
                        <div class="filter-title">
                            <span>Active Filters</span>
                            <a href="products.php" class="clear-all">Clear All</a>
                        </div>
                        <div class="active-tags">
                            <?php if($searchQuery): ?>
                                <span class="tag">Search: <?php echo htmlspecialchars($searchQuery); ?> <a href="?<?php echo http_build_query(array_diff_key($_GET, ['search' => 1])); ?>">×</a></span>
                            <?php endif; ?>
                            <?php if($categorySlug && $currentCategory): ?>
                                <span class="tag"><?php echo htmlspecialchars($currentCategory['name']); ?> <a href="?<?php echo http_build_query(array_diff_key($_GET, ['category' => 1])); ?>">×</a></span>
                            <?php endif; ?>
                            <?php if($priceMin): ?>
                                <span class="tag">₹<?php echo $priceMin; ?>-<?php echo $priceMax ?: '+'; ?> <a href="?<?php echo http_build_query(array_diff_key($_GET, ['price_min' => 1, 'price_max' => 1])); ?>">×</a></span>
                            <?php endif; ?>
                            <?php if($ratingFilter): ?>
                                <span class="tag"><?php echo $ratingFilter; ?>+ ★ <a href="?<?php echo http_build_query(array_diff_key($_GET, ['rating' => 1])); ?>">×</a></span>
                            <?php endif; ?>
                            <?php if($inStockOnly): ?>
                                <span class="tag">In Stock <a href="?<?php echo http_build_query(array_diff_key($_GET, ['in_stock' => 1])); ?>">×</a></span>
                            <?php endif; ?>
                            <?php if($discountOnly): ?>
                                <span class="tag">On Sale <a href="?<?php echo http_build_query(array_diff_key($_GET, ['discount' => 1])); ?>">×</a></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Categories Filter -->
                    <div class="filter-box">
                        <div class="filter-title" onclick="toggleFilter(this)">
                            <span>Categories</span>
                            <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="filter-options expanded">
                            <a href="products.php?<?php echo http_build_query(array_diff_key($_GET, ['category' => 1])); ?>" class="filter-option <?php echo !$categorySlug ? 'active' : ''; ?>">
                                <span class="radio <?php echo !$categorySlug ? 'checked' : ''; ?>"></span>
                                <span>All Categories</span>
                            </a>
                            <?php foreach($allCategories as $cat): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['category' => $cat['slug']])); ?>" class="filter-option <?php echo $categorySlug === $cat['slug'] ? 'active' : ''; ?>">
                                <span class="radio <?php echo $categorySlug === $cat['slug'] ? 'checked' : ''; ?>"></span>
                                <span><?php echo htmlspecialchars($cat['name']); ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="filter-box">
                        <div class="filter-title" onclick="toggleFilter(this)">
                            <span>Price Range</span>
                            <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="filter-options expanded">
                            <?php foreach($priceRanges as $range): 
                                $isActive = $priceMin == $range['min'] && $priceMax == $range['max'];
                                $queryParams = $isActive ? array_diff_key($_GET, ['price_min' => 1, 'price_max' => 1]) : array_merge($_GET, ['price_min' => $range['min'], 'price_max' => $range['max']]);
                            ?>
                            <a href="?<?php echo http_build_query($queryParams); ?>" class="filter-option <?php echo $isActive ? 'active' : ''; ?>">
                                <span class="radio <?php echo $isActive ? 'checked' : ''; ?>"></span>
                                <span><?php echo $range['label']; ?></span>
                            </a>
                            <?php endforeach; ?>
                            
                            <!-- Custom Price -->
                            <form class="custom-price" method="GET">
                                <?php foreach(array_diff_key($_GET, ['price_min' => 1, 'price_max' => 1]) as $key => $val): ?>
                                    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($val); ?>">
                                <?php endforeach; ?>
                                <div class="price-inputs">
                                    <input type="number" name="price_min" placeholder="Min" value="<?php echo $priceMin; ?>">
                                    <span>-</span>
                                    <input type="number" name="price_max" placeholder="Max" value="<?php echo $priceMax; ?>">
                                    <button type="submit">Go</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="filter-box">
                        <div class="filter-title" onclick="toggleFilter(this)">
                            <span>Customer Rating</span>
                            <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="filter-options">
                            <?php for($i = 4; $i >= 1; $i--): 
                                $isActive = $ratingFilter == $i;
                                $queryParams = $isActive ? array_diff_key($_GET, ['rating' => 1]) : array_merge($_GET, ['rating' => $i]);
                            ?>
                            <a href="?<?php echo http_build_query($queryParams); ?>" class="filter-option <?php echo $isActive ? 'active' : ''; ?>">
                                <span class="check <?php echo $isActive ? 'checked' : ''; ?>"></span>
                                <div class="stars">
                                    <?php for($j = 1; $j <= 5; $j++): ?>
                                        <svg class="<?php echo $j <= $i ? 'filled' : ''; ?>" viewBox="0 0 24 24">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                                <span>& Up</span>
                            </a>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Availability Filter -->
                    <div class="filter-box">
                        <div class="filter-title" onclick="toggleFilter(this)">
                            <span>Availability</span>
                            <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="filter-options">
                            <a href="?<?php echo http_build_query($inStockOnly ? array_diff_key($_GET, ['in_stock' => 1]) : array_merge($_GET, ['in_stock' => 1])); ?>" class="filter-option <?php echo $inStockOnly ? 'active' : ''; ?>">
                                <span class="toggle <?php echo $inStockOnly ? 'active' : ''; ?>"><span></span></span>
                                <span>In Stock Only</span>
                            </a>
                            <a href="?<?php echo http_build_query($discountOnly ? array_diff_key($_GET, ['discount' => 1]) : array_merge($_GET, ['discount' => 1])); ?>" class="filter-option <?php echo $discountOnly ? 'active' : ''; ?>">
                                <span class="toggle <?php echo $discountOnly ? 'active' : ''; ?>"><span></span></span>
                                <span>On Sale</span>
                            </a>
                        </div>
                    </div>

                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="products-main">
                
                <!-- Toolbar -->
                <div class="toolbar">
                    <span class="results-count"><?php echo count($filteredProducts); ?> products found</span>
                    
                    <div class="sort-box">
                        <label>Sort by:</label>
                        <select onchange="window.location.href=this.value">
                            <?php 
                            $sortOptions = [
                                'newest' => 'Newest First',
                                'price_low' => 'Price: Low to High',
                                'price_high' => 'Price: High to Low',
                                'rating' => 'Highest Rated',
                                'discount' => 'Biggest Discount'
                            ];
                            foreach($sortOptions as $value => $label): 
                                $url = '?'.http_build_query(array_merge($_GET, ['sort' => $value]));
                                $selected = $sortBy === $value ? 'selected' : '';
                            ?>
                                <option value="<?php echo $url; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Products Grid - 4 per row -->
                <div class="products-grid four-columns">
                    <?php foreach ($filteredProducts as $index => $product): ?>
                        <?php
                        $finalPrice      = $product['final_price'];
                        $discountPercent = $product['discount_percent'];
                        $fakeStock       = rand(3, 15);
                        $fakeViews       = rand(5, 30);
                        $animationDelay  = $index * 0.1;
                        ?>

                        <div class="product-card" data-aos="fade-up" data-aos-delay="<?php echo $animationDelay; ?>" style="--delay: <?php echo $animationDelay; ?>s">
                            
                            <div class="card-badges">
                                <?php if ($index < 2): ?>
                                    <!--<span class="badge bestseller">-->
                                    <!--    <span class="badge-icon">🔥</span>-->
                                    <!--    BEST SELLER-->
                                    <!--</span>-->
                                <?php endif; ?>

                                <?php if (!empty($product['offer_label'])): ?>
                                    <span class="badge discount">
                                        <?php echo htmlspecialchars($product['offer_label']); ?>
                                    </span>
                                <?php elseif ($discountPercent > 0): ?>
                                    <span class="badge discount">
                                        <span class="discount-value"><?php echo $discountPercent; ?>%</span>
                                        <span class="discount-text">OFF</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="wishlist-btn" onclick="toggleWishlist(<?php echo $product['id']; ?>)">
                                <svg class="heart-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </div>

                            <a href="<?php echo productUrl($product); ?>" class="product-image">
                                <div class="image-shine"></div>
                                <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     loading="lazy">
                                <div class="quick-view">
                                    <span>Quick View</span>
                                </div>
                            </a>

                            <div class="product-info">
                                <div class="product-meta">
                                    <span class="category-tag">Ayurvedic</span>
                                </div>

                                <h3 class="product-title">
                                    <a href="<?php echo productUrl($product); ?>">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h3>

                                <?php if (!empty($product['reviews_count']) && $product['reviews_count'] > 0): ?>
                                <div class="product-rating">
                                    <div class="stars">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <svg class="star <?php echo $i <= round($product['rating']) ? 'filled' : ''; ?>" viewBox="0 0 24 24">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-text"><?php echo number_format($product['rating'],1); ?> (<?php echo $product['reviews_count']; ?>)</span>
                                </div>
                                <?php endif; ?>

                                <div class="price-row">
                                    <span class="price">₹<?php echo number_format($finalPrice, 0); ?></span>
                                    <?php if ($discountPercent > 0): ?>
                                        <span class="old-price">₹<?php echo number_format($product['price'], 0); ?></span>
                                        <span class="save-tag">Save ₹<?php echo number_format($product['price'] - $finalPrice, 0); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="trust-indicators">
                                    <div class="trust-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        <span>Secure Payment</span>
                                    </div>
                                    <div class="trust-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg>
                                        <span>Free Shipping</span>
                                    </div>
                                </div>

                                <?php if ($product['stock_qty'] > 0): ?>
                                    <div class="urgency-bar">
                                        <div class="urgency-content">
                                            <span class="pulse-dot"></span>
                                            <span class="urgency-text">Only <?php echo $fakeStock; ?> left</span>
                                            <span class="divider">•</span>
                                            <span class="views-count"><?php echo $fakeViews; ?> viewing now</span>
                                        </div>
                                        <div class="stock-progress">
                                            <div class="progress-fill" style="width: <?php echo ($fakeStock/20)*100; ?>%"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="product-actions">
                                    <?php if ($product['stock_qty'] > 0): ?>
                                        <form method="POST" action="cart.php" class="action-form">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-cart">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="9" cy="21" r="1"></circle>
                                                    <circle cx="20" cy="21" r="1"></circle>
                                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                                </svg>
                                                <span>Add to Cart</span>
                                            </button>
                                        </form>

                                        <form method="POST" action="cart.php" class="action-form">
                                            <input type="hidden" name="action" value="buy_now">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-buy">
                                                <span>Buy Now</span>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    <polyline points="12 5 19 12 12 19"></polyline>
                                                </svg>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-outofstock" disabled>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                            <span>Out of Stock</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (empty($filteredProducts)): ?>
                    <div class="empty-state" data-aos="fade-up">
                        <div class="empty-icon">🔍</div>
                        <h3>No products found</h3>
                        <p>Try adjusting your search or category filter</p>
                        <a href="products.php" class="btn btn-primary">View All Products</a>
                    </div>
                <?php endif; ?>

                <!-- RECOMMENDED PRODUCTS SECTION - YEH AB DIKHEGA -->
                <section class="recommended-section" data-aos="fade-up">
                    <div class="section-header">
                        <h2>✨ Recommended For You</h2>
                        <a href="products.php" class="view-all">View All →</a>
                    </div>
                    <div class="products-grid recommended-grid">
                        <?php 
                        // Get 4 random products as recommended
                        $recommendedProducts = array_slice($filteredProducts, 0, 4);
                        if(count($recommendedProducts) < 4) {
                            $allProducts = Product::getAll(true);
                            shuffle($allProducts);
                            $recommendedProducts = array_slice($allProducts, 0, 4);
                        }
                        foreach($recommendedProducts as $recProduct): 
                        ?>
                        <div class="product-card compact">
                            <a href="<?php echo productUrl($recProduct); ?>" class="product-image">
                                <img src="uploads/products/<?php echo htmlspecialchars($recProduct['image']); ?>" alt="<?php echo htmlspecialchars($recProduct['name']); ?>">
                            </a>
                            <div class="product-info">
                                <h4 class="product-title"><a href="<?php echo productUrl($recProduct); ?>"><?php echo htmlspecialchars($recProduct['name']); ?></a></h4>
                                <div class="price">₹<?php echo number_format($recProduct['final_price'], 0); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- TRENDING PRODUCTS SECTION -->
                <section class="trending-section" data-aos="fade-up">
                    <div class="section-header">
                        <h2>🔥 Trending Now</h2>
                        <a href="products.php" class="view-all">View All →</a>
                    </div>
                    <div class="products-grid recommended-grid">
                        <?php 
                        // Get trending products (highest discount or most viewed)
                        $trendingProducts = Product::getAll(true);
                        usort($trendingProducts, fn($a, $b) => $b['discount_percent'] <=> $a['discount_percent']);
                        $trendingProducts = array_slice($trendingProducts, 0, 4);
                        $rank = 0;
                        foreach($trendingProducts as $trendProduct): 
                        $rank++;
                        ?>
                        <div class="product-card compact">
                            <div class="trend-rank">#<?php echo $rank; ?></div>
                            <a href="product-detail.php?id=<?php echo $trendProduct['id']; ?>" class="product-image">
                                <img src="uploads/products/<?php echo htmlspecialchars($trendProduct['image']); ?>" alt="<?php echo htmlspecialchars($trendProduct['name']); ?>">
                            </a>
                            <div class="product-info">
                                <h4 class="product-title"><a href="product-detail.php?id=<?php echo $trendProduct['id']; ?>"><?php echo htmlspecialchars($trendProduct['name']); ?></a></h4>
                                <div class="price">₹<?php echo number_format($trendProduct['final_price'], 0); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

            </main>
        </div>
    </div>
</section>

<!-- Sticky Mobile Cart -->
<div class="sticky-cart" id="stickyCart">
    <div class="cart-content">
        <div class="cart-info">
            <span class="cart-icon">🛒</span>
            <span class="cart-count">0 items</span>
        </div>
        <a href="cart.php" class="cart-btn">View Cart →</a>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <div class="toast-content">
        <span class="toast-icon">✓</span>
        <span class="toast-message">Added to cart!</span>
    </div>
</div>

<script>
// Filter Accordion Toggle
function toggleFilter(header) {
    const content = header.nextElementSibling;
    const arrow = header.querySelector('.arrow');
    content.classList.toggle('expanded');
    arrow.style.transform = content.classList.contains('expanded') ? 'rotate(180deg)' : 'rotate(0deg)';
}

// Mobile Filters Toggle
function toggleMobileFilters() {
    document.querySelector('.filters-sidebar').classList.toggle('mobile-open');
    document.body.classList.toggle('filter-open');
}

// Wishlist Toggle
function toggleWishlist(productId) {
    const btn = event.currentTarget;
    btn.classList.toggle('active');
    
    // Animation effect
    btn.style.transform = 'scale(1.3)';
    setTimeout(() => {
        btn.style.transform = 'scale(1)';
    }, 200);
}

// Sticky Cart Visibility
let lastScroll = 0;
const stickyCart = document.getElementById('stickyCart');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 300) {
        stickyCart.classList.add('visible');
    } else {
        stickyCart.classList.remove('visible');
    }
    
    lastScroll = currentScroll;
});

// Add to Cart Animation
document.querySelectorAll('.btn-cart').forEach(btn => {
    btn.addEventListener('click', function(e) {
        // Show toast
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
        
        // Button animation
        this.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>Added!</span>
        `;
        this.style.background = '#10b981';
        
        setTimeout(() => {
            this.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span>Add to Cart</span>
            `;
            this.style.background = '';
        }, 2000);
    });
});

// Smooth scroll for filter chips
document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', function(e) {
        if (this.getAttribute('href') === 'products.php') return;
        
        document.querySelector('.products-section').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
});

// Close mobile filters on outside click
document.addEventListener('click', function(e) {
    if (window.innerWidth <= 992) {
        const sidebar = document.querySelector('.filters-sidebar');
        const toggle = document.querySelector('.mobile-filter-btn');
        if (sidebar && toggle && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('mobile-open');
            document.body.classList.remove('filter-open');
        }
    }
});
</script>

<style>
/* ===== CSS VARIABLES ===== */
:root {
    --primary: #0f3d2e;
    --primary-light: #1a5f4a;
    --secondary: #ff9800;
    --danger: #e53935;
    --success: #10b981;
    --bg: #f8faf9;
    --card-bg: #ffffff;
    --text: #1a1a1a;
    --text-muted: #6b7280;
    --border: #e5e7eb;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    --radius: 16px;
    --radius-sm: 8px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== BASE STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.products-section {
    padding: 40px 0;
    background: linear-gradient(135deg, #f8faf9 0%, #f0f4f3 100%);
    min-height: 100vh;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* ===== HEADER WITH SEARCH ===== */
.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.header-left h1 {
    font-size: clamp(1.5rem, 4vw, 2.5rem);
    color: var(--primary);
    font-weight: 800;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-left p {
    font-size: 1rem;
    color: var(--text-muted);
    font-weight: 500;
}

.header-line {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--secondary), var(--danger));
    margin-top: 15px;
    border-radius: 2px;
}

/* Search Box */
.header-search {
    flex: 1;
    max-width: 500px;
}

.search-box {
    display: flex;
    align-items: center;
    background: white;
    border: 2px solid var(--border);
    border-radius: 50px;
    padding: 5px;
    transition: var(--transition);
}

.search-box:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(15, 61, 46, 0.1);
}

.search-icon {
    width: 20px;
    height: 20px;
    color: var(--text-muted);
    margin-left: 15px;
}

.search-input {
    flex: 1;
    border: none;
    background: none;
    padding: 10px 15px;
    font-size: 1rem;
    outline: none;
}

.search-btn {
    background: var(--primary);
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.search-btn:hover {
    background: var(--primary-light);
}

/* ===== MAIN LAYOUT ===== */
.products-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
    align-items: start;
}

/* ===== SIDEBAR FILTERS ===== */
.filters-sidebar {
    position: sticky;
    top: 20px;
    height: calc(100vh - 40px);
    overflow-y: auto;
    padding-right: 5px;
}

.sidebar-inner {
    background: white;
    border-radius: var(--radius);
    padding: 20px;
    box-shadow: var(--shadow);
}

/* Mobile Filter Button */
.mobile-filter-btn {
    display: none;
    width: 100%;
    padding: 12px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius-sm);
    font-weight: 600;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 15px;
}

.mobile-filter-btn svg {
    width: 18px;
    height: 18px;
}

/* Filter Box */
.filter-box {
    border-bottom: 1px solid var(--border);
    padding-bottom: 20px;
    margin-bottom: 20px;
}

.filter-box:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.filter-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    user-select: none;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 15px;
}

.filter-title .arrow {
    width: 18px;
    height: 18px;
    color: var(--text-muted);
    transition: transform 0.3s ease;
}

.filter-options {
    display: none;
}

.filter-options.expanded {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Active Filters */
.active-filters .filter-title {
    cursor: default;
}

.clear-all {
    font-size: 0.8rem;
    color: var(--danger);
    text-decoration: none;
    font-weight: 600;
}

.clear-all:hover {
    text-decoration: underline;
}

.active-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag {
    background: var(--primary);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.tag a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    opacity: 0.8;
}

.tag a:hover {
    opacity: 1;
}

/* Filter Options */
.filter-option {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: var(--text);
    font-size: 0.9rem;
    padding: 8px 5px;
    border-radius: var(--radius-sm);
    transition: var(--transition);
    margin-bottom: 5px;
}

.filter-option:hover {
    background: var(--bg);
}

.filter-option.active {
    color: var(--primary);
    font-weight: 600;
}

.radio, .check {
    width: 18px;
    height: 18px;
    border: 2px solid var(--border);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: var(--transition);
}

.radio {
    border-radius: 50%;
}

.radio.checked {
    border-color: var(--primary);
    border-width: 5px;
}

.check.checked {
    background: var(--primary);
    border-color: var(--primary);
}

.check.checked::after {
    content: '✓';
    color: white;
    font-size: 12px;
    font-weight: bold;
}

/* Stars in Filter */
.filter-option .stars {
    display: flex;
    gap: 2px;
}

.filter-option .stars svg {
    width: 16px;
    height: 16px;
    fill: #e5e7eb;
}

.filter-option .stars svg.filled {
    fill: #fbbf24;
}

/* Toggle Switch */
.toggle {
    width: 40px;
    height: 22px;
    background: var(--border);
    border-radius: 11px;
    position: relative;
    transition: var(--transition);
    flex-shrink: 0;
}

.toggle.active {
    background: var(--primary);
}

.toggle span {
    width: 18px;
    height: 18px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: var(--transition);
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.toggle.active span {
    left: 20px;
}

/* Custom Price */
.custom-price {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px dashed var(--border);
}

.price-inputs {
    display: flex;
    align-items: center;
    gap: 8px;
}

.price-inputs input {
    width: 70px;
    padding: 8px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 0.9rem;
    text-align: center;
}

.price-inputs button {
    background: var(--primary);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
    cursor: pointer;
    font-weight: 600;
}

/* ===== MAIN PRODUCTS AREA ===== */
.products-main {
    min-width: 0;
}

/* Toolbar */
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 15px 20px;
    border-radius: var(--radius);
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
    flex-wrap: wrap;
    gap: 15px;
}

.results-count {
    font-weight: 600;
    color: var(--text);
}

.sort-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sort-box label {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.sort-box select {
    padding: 8px 15px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: white;
    cursor: pointer;
    font-size: 0.9rem;
    outline: none;
}

/* ===== PRODUCTS GRID - 4 COLUMNS ===== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}

.products-grid.four-columns {
    grid-template-columns: repeat(4, 1fr);
}

/* Product Card - Original Style but Compact */
.product-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition-slow);
    position: relative;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--border);
    transform-style: preserve-3d;
    animation: fadeInUp 0.6s ease-out backwards;
    animation-delay: var(--delay, 0s);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-light);
}

/* Card shine effect */
.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.4),
        transparent
    );
    transition: 0.5s;
    z-index: 10;
    pointer-events: none;
}

.product-card:hover::before {
    left: 100%;
}

/* Badges */
.card-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    z-index: 5;
    pointer-events: none;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    box-shadow: var(--shadow);
}

.badge.bestseller {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
}

.badge.discount {
    background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
    color: white;
    flex-direction: column;
    padding: 6px;
    min-width: 45px;
    text-align: center;
}

.discount-value {
    font-size: 0.9rem;
    font-weight: 800;
    line-height: 1;
}

.discount-text {
    font-size: 0.6rem;
    opacity: 0.9;
}

.badge-icon {
    font-size: 0.8rem;
}

/* Wishlist */
.wishlist-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: var(--shadow);
    z-index: 10;
    transition: var(--transition);
    border: none;
}

.wishlist-btn:hover {
    transform: scale(1.1);
    background: #fee2e2;
}

.wishlist-btn.active {
    background: #fee2e2;
}

.wishlist-btn.active .heart-icon {
    fill: #e53935;
    stroke: #e53935;
}

.heart-icon {
    width: 18px;
    height: 18px;
    color: #9ca3af;
    transition: var(--transition);
}

/* Product Image */
.product-image {
    height: 200px;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    position: relative;
    overflow: hidden;
    text-decoration: none;
}

.image-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.6),
        transparent
    );
    transition: 0.6s;
    z-index: 2;
}

.product-card:hover .image-shine {
    left: 100%;
}

.product-image img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    transition: var(--transition-slow);
    z-index: 1;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.quick-view {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(15, 61, 46, 0.9);
    color: white;
    padding: 10px;
    text-align: center;
    font-weight: 600;
    font-size: 0.85rem;
    transform: translateY(100%);
    transition: var(--transition);
    z-index: 3;
    backdrop-filter: blur(4px);
}

.product-card:hover .quick-view {
    transform: translateY(0);
}

/* Product Info */
.product-info {
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}

.product-meta {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.category-tag {
    font-size: 0.7rem;
    color: var(--primary);
    background: rgba(15, 61, 46, 0.1);
    padding: 3px 8px;
    border-radius: 20px;
    font-weight: 600;
}

.product-title {
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1.3;
    color: var(--text);
    height: 38px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-title a {
    color: inherit;
    text-decoration: none;
    transition: var(--transition);
}

.product-title a:hover {
    color: var(--primary);
}

/* Rating */
.product-rating {
    display: flex;
    align-items: center;
    gap: 6px;
}

.stars {
    display: flex;
    gap: 2px;
}

.star {
    width: 14px;
    height: 14px;
    fill: #e5e7eb;
    stroke: none;
    transition: var(--transition);
}

.star.filled {
    fill: #fbbf24;
    animation: starPop 0.3s ease;
}

@keyframes starPop {
    50% { transform: scale(1.3); }
}

.rating-text {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* Price */
.price-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin: 4px 0;
}

.price {
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--primary);
    letter-spacing: -0.02em;
}

.old-price {
    font-size: 0.85rem;
    text-decoration: line-through;
    color: var(--text-muted);
}

.save-tag {
    font-size: 0.7rem;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 3px 6px;
    border-radius: 4px;
    font-weight: 700;
}

/* Trust Indicators */
.trust-indicators {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-top: 1px dashed var(--border);
    border-bottom: 1px dashed var(--border);
    margin: 5px 0;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    color: var(--text-muted);
    font-weight: 500;
}

.trust-item svg {
    width: 12px;
    height: 12px;
    color: var(--success);
}

/* Urgency Bar */
.urgency-bar {
    background: linear-gradient(135deg, #fef3f2 0%, #fee2e2 100%);
    border-radius: var(--radius-sm);
    padding: 8px 10px;
    border-left: 3px solid var(--danger);
    margin: 5px 0;
}

.urgency-content {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--danger);
    margin-bottom: 4px;
}

.pulse-dot {
    width: 6px;
    height: 6px;
    background: var(--danger);
    border-radius: 50%;
    animation: pulseDot 1.5s infinite;
    box-shadow: 0 0 0 0 rgba(229, 57, 53, 0.7);
}

@keyframes pulseDot {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(229, 57, 53, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(229, 57, 53, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(229, 57, 53, 0); }
}

.divider {
    opacity: 0.5;
}

.views-count {
    color: var(--text-muted);
    font-weight: 500;
}

.stock-progress {
    height: 3px;
    background: rgba(229, 57, 53, 0.2);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--danger), #ff6b6b);
    border-radius: 2px;
    transition: width 1s ease;
    animation: progressShine 2s infinite;
}

@keyframes progressShine {
    0% { filter: brightness(1); }
    50% { filter: brightness(1.2); }
    100% { filter: brightness(1); }
}

/* Action Buttons */
.product-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: auto;
    padding-top: 12px;
}

.action-form {
    margin: 0;
}

.btn {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.btn svg {
    width: 16px;
    height: 16px;
    transition: var(--transition);
}

.btn-cart {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    color: var(--text);
    border: 2px solid var(--border);
}

.btn-cart:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-cart:hover svg {
    transform: rotate(-10deg);
}

.btn-buy {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    box-shadow: 0 4px 6px -1px rgba(15, 61, 46, 0.3);
}

.btn-buy:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(15, 61, 46, 0.4);
}

.btn-buy:hover svg {
    transform: translateX(4px);
}

.btn-outofstock {
    grid-column: 1 / -1;
    background: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    border: 2px dashed #d1d5db;
}

/* ===== RECOMMENDED & TRENDING SECTIONS ===== */
.recommended-section,
.trending-section {
    margin-top: 50px;
    padding-top: 30px;
    border-top: 2px solid var(--border);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.section-header h2 {
    font-size: 1.4rem;
    color: var(--primary);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.view-all {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: var(--transition);
}

.view-all:hover {
    gap: 10px;
    color: var(--primary-light);
}

/* Compact Cards for Recommended */
.recommended-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.product-card.compact {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    transition: var(--transition);
}

.product-card.compact:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-light);
}

.product-card.compact .product-image {
    height: 160px;
    padding: 12px;
}

.product-card.compact .product-info {
    padding: 12px;
}

.product-card.compact .product-title {
    font-size: 0.9rem;
    height: 36px;
    margin-bottom: 8px;
}

.product-card.compact .price {
    font-size: 1.1rem;
}

/* Trend Rank Badge */
.trend-rank {
    position: absolute;
    top: 10px;
    left: 10px;
    background: var(--secondary);
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.9rem;
    z-index: 5;
    box-shadow: var(--shadow);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin: 40px 0;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: var(--text);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 24px;
}

.btn-primary {
    background: var(--primary);
    color: white;
    padding: 14px 32px;
    border-radius: 50px;
    text-decoration: none;
    display: inline-block;
    font-weight: 700;
    transition: var(--transition);
}

.btn-primary:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

/* ===== STICKY CART ===== */
.sticky-cart {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    padding: 12px 20px;
    z-index: 1000;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    border-top: 3px solid var(--primary);
}

.sticky-cart.visible {
    transform: translateY(0);
}

.cart-content {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cart-icon {
    font-size: 1.5rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.cart-count {
    font-weight: 700;
    color: var(--text);
}

.cart-btn {
    background: var(--primary);
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    transition: var(--transition);
    box-shadow: var(--shadow);
}

.cart-btn:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

/* ===== TOAST NOTIFICATION ===== */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 24px;
    border-radius: var(--radius-sm);
    box-shadow: var(--shadow-xl);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 9999;
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    border-left: 4px solid var(--success);
}

.toast.show {
    transform: translateX(0);
    opacity: 1;
}

.toast-icon {
    width: 24px;
    height: 24px;
    background: var(--success);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.toast-message {
    font-weight: 600;
    color: var(--text);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1200px) {
    .products-layout {
        grid-template-columns: 240px 1fr;
        gap: 20px;
    }
    
    .products-grid,
    .products-grid.four-columns,
    .recommended-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .products-layout {
        grid-template-columns: 1fr;
    }
    
    .filters-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 320px;
        height: 100vh;
        z-index: 999;
        background: white;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        padding: 20px;
        box-shadow: var(--shadow-xl);
    }
    
    .filters-sidebar.mobile-open {
        transform: translateX(0);
    }
    
    body.filter-open::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 998;
    }
    
    .mobile-filter-btn {
        display: flex;
    }
    
    .products-grid,
    .products-grid.four-columns,
    .recommended-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .product-image {
        height: 180px;
    }
    
    .product-actions {
        grid-template-columns: 1fr;
    }
    
    .btn span {
        display: inline;
    }
}

@media (max-width: 576px) {
    .container {
        padding: 0 12px;
    }
    
    .products-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-search {
        max-width: 100%;
    }
    
    .products-grid,
    .products-grid.four-columns,
    .recommended-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .product-image {
        height: 150px;
    }
    
    .product-title {
        font-size: 0.85rem;
        height: 34px;
    }
    
    .price {
        font-size: 1rem;
    }
    
    .trust-indicators {
        display: none;
    }
    
    .urgency-bar {
        display: none;
    }
    
    .btn {
        padding: 8px;
        font-size: 0.75rem;
    }
    
    .section-header h2 {
        font-size: 1.1rem;
    }
    
    .recommended-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .product-card.compact .product-image {
        height: 120px;
    }
}

@media (max-width: 375px) {
    .products-grid,
    .products-grid.four-columns {
        grid-template-columns: 1fr;
    }
    
    .recommended-grid {
        grid-template-columns: 1fr;
    }
}

/* ===== SCROLLBAR STYLING ===== */
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

/* ===== SELECTION STYLING ===== */
::selection {
    background: rgba(15, 61, 46, 0.2);
    color: var(--primary);
}
</style>

<!-- AOS Animation Library (Add in header.php) -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 600,
        once: true,
        offset: 50
    });
</script>

<?php require_once 'includes/footer.php'; ?>
