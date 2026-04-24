<?php
    require_once __DIR__ . '/includes/config.php';
    $pageTitle       = 'Best Ayurvedic Products Online in India | Shop Now';
    $metaDescription = 'Buy the best ayurvedic products online in India. Shop our premium range of natural health supplements, pure shilajit & organic skin care. Order yours today!';
    $metaKeywords    = 'ayurvedic products india, herbal wellness products, natural health supplements, ayurvedic oils online, organic supplements';
    $canonicalUrl    = 'https://livvra.in/';
    
    try {
        $heroSlides = Hero::getAll(true);
    } catch (Exception $e) {
        error_log("Hero Error: " . $e->getMessage());
        $heroSlides = [];
    }
    
    try {
        $featuredProducts = Product::getFeatured(12);
    } catch (Exception $e) {
        error_log("Featured Products Error: " . $e->getMessage());
        $featuredProducts = [];
    }

    try {
        $newArrivals = Product::getNewArrivals(8);
    } catch (Exception $e) {
        error_log("New Arrivals Error: " . $e->getMessage());
        $newArrivals = [];
    }
    
    try {
        $allCategories = Category::getAll(true);
    } catch (Exception $e) {
        error_log("Categories Error: " . $e->getMessage());
        $allCategories = [];
    }
    
    require_once __DIR__ . '/includes/header.php';
    ?>
    
    
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        @media (min-width: 1025px) {
            .desktop-only { display: block !important; }
            .mobile-only { display: none !important; }
        }
        @media (max-width: 1024px) {
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
        }
    </style>
    <style>
        .livvra-hero-section {
            width: 100%;
            height: 120vh;
            position: relative;
            overflow: hidden;
            display: block !important;
        }
    
        .livvra-hero-section .swiper {
            width: 100%;
            height: 100%;
        }
    
        .livvra-hero-section .swiper-slide {
            width: 100%;
            height: 100%;
            position: relative;
        }
    
        .livvra-desktop-view, .livvra-mobile-view {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: absolute;
            top: 0;
            left: 0;
        }
    
        .livvra-desktop-view { display: block; }
        .livvra-mobile-view { display: none; }
    
        @media (max-width: 768px) {
            .livvra-hero-section { height: 80vh; }
            .livvra-desktop-view { display: none; }
            .livvra-mobile-view { display: block; }
        }
    
        .livvra-hero-section .swiper-button-next, 
        .livvra-hero-section .swiper-button-prev {
            color: #fff !important;
            background: rgba(0,0,0,0.3) !important;
            width: 45px !important;
            height: 45px !important;
            border-radius: 50% !important;
            z-index: 999 !important;
        }
        .livvra-hero-section .swiper-button-next:after, 
        .livvra-hero-section .swiper-button-prev:after { font-size: 18px !important; font-weight: bold; }
        
        .livvra-hero-section .swiper-pagination-bullet-active {
            background: #fff;
            opacity: 1;
            width: 30px;
            border-radius: 10px;
        }
    
        .livra-trust-strip {
            width: 100%;
            overflow: hidden;
            background: #0f3d2e;
            padding: 16px 0;
        }
        .livra-trust-marquee { width: 100%; overflow: hidden; }
        .livra-trust-track {
            display: flex;
            width: max-content;
            gap: 18px;
            animation: livraScroll 25s linear infinite;
        }
        .livra-trust-pill {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 18px; background: rgba(255,255,255,0.1);
            color: #ffffff; border-radius: 999px; font-size: 14px; white-space: nowrap;
        }
        @keyframes livraScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    
    /* Category Navigation Styling */
    .category-nav-bar { background: #fdfdfd; border-bottom: 1px solid #f0f0f0; padding: 3px 0; overflow: hidden; position: relative; width: 100%; display: none; }
    
    /* Fixed behavior for mobile (No Sticky) */
    @media (max-width: 768px) {
        .category-nav-bar.mobile-only-bar {
            display: block !important;
            position: relative !important;
            z-index: 100 !important;
            box-shadow: none !important;
            margin-bottom: 0 !important;
        }
        .category-nav-bar.desktop-only-bar {
            display: none !important;
        }
    }
    
    @media (min-width: 769px) {
        .category-nav-bar.desktop-only-bar {
            display: block !important;
        }
        .category-nav-bar.mobile-only-bar {
            display: none !important;
        }
    }

    .category-scroll { 
        display: flex !important; 
        align-items: center !important;
        overflow-x: auto !important; 
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important; 
        -ms-overflow-style: none !important;
        justify-content: flex-start !important; 
        /*padding: 0 15px !important;*/
        white-space: nowrap !important;
        width: 100% !important;
        touch-action: pan-x !important;
        margin: 0 !important;
        gap: 1px !important;
        position: relative !important;
        z-index: 100 !important;
    }
    .category-scroll::-webkit-scrollbar { display: none !important; }
    
    .category-item-mobile { 
        display: inline-flex !important; 
        flex-direction: column !important; 
        align-items: center !important; 
        gap: 8px !important; 
        text-decoration: none !important; 
        flex: 0 0 auto !important;
        position: relative !important;
        pointer-events: auto !important;
        min-width: 70px !important;
    }
    
    .category-icon-circle-mobile {
        width: 65px !important; 
        height: 65px !important; 
        border-radius: 50% !important; 
        display: flex !important;
        align-items: center !important; 
        justify-content: center !important; 
        overflow: hidden !important; 
        border: 1px solid #e8e8e8 !important;
        background: #fdfdfd !important;
        transition: all 0.3s ease;
    }
    
    .category-icon-circle-mobile:hover {
        border-color: #76a33a !important;
        transform: scale(1.05);
    }
    
    .category-icon-circle-mobile img { 
        width: 60% !important; 
        height: 60% !important; 
        object-fit: contain !important; 
    }
    
    .category-item-mobile span { 
        font-size: 11px !important; 
        font-weight: 600 !important; 
        color: #444 !important; 
        text-align: center !important;
        text-transform: capitalize !important;
    }

    .mobile-category-divider {
        height: 60px;
        width: 1px;
        background: #eee;
        align-self: center;
        flex-shrink: 0;
    }

    .nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        background: rgba(255,255,255,0.95);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #999;
        z-index: 10;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        pointer-events: none;
        border: 1px solid #eee;
    }
    .arrow-left { left: 5px; }
    .arrow-right { right: 5px; }
    
        .concern-pills-section { padding: 30px 0; background: #fff; }
        .concern-pills-header { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .concern-pill {
            display: flex; align-items: center; padding: 6px 16px; background: #fff;
            border: 1px solid #dcdcdc; border-radius: 8px; cursor: pointer; gap: 10px; transition: all 0.3s ease;
        }
        .concern-pill.active { background: #f1f8f1; border-color: #4A7C59; }
        .pill-thumb { width: 32px; height: 32px; border-radius: 50%; overflow: hidden; background: #f0f0f0; }
        .pill-thumb img { width: 100%; height: 100%; object-fit: cover; }
    
        .product-grid-home { display: grid; gap: 25px; grid-template-columns: repeat(4, 1fr); margin-top: 20px; }
        @media (max-width: 1024px) { .product-grid-home { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .product-grid-home { grid-template-columns: repeat(2, 1fr); gap: 15px; } }
    </style>
    
    <!-- Hero Section -->
    <style>
        .hero-container {
            width: 100%;
            position: relative;
            background: #f4f4f4;
        }
        /* Maintain Aspect Ratio for Screenshot Style (Approx 3:1 or 2.5:1 for desktop) */
        .hero-carousel-wrapper {
            position: relative;
            width: 100%;
            /* For desktop, let it follow image aspect ratio, but constrain height if needed */
            max-height: 500px; 
            overflow: hidden;
        }
        .hero-slide img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
        }
        @media (max-width: 768px) {
            .hero-carousel-wrapper {
                max-height: none; /* Let mobile banners define height */
            }
        }
        /* Slider dots styling */
        .glider-dot {
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            display: inline-block;
            margin: 0 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .glider-dot.active {
            background: #ffffff;
            width: 24px;
            border-radius: 4px;
        }
        /* Carousel Slide Transition */
        .hero-slide {
            display: none;
            animation: fade 0.5s ease-in-out;
        }
        .hero-slide.active {
            display: block;
        }
        @keyframes fade {
            from { opacity: 0.8; }
            to { opacity: 1; }
        }
    </style>
    
  



    
 <!-- Mobile Category Navigation (Kapiva Style) -->
    <div class="category-nav-bar mobile-only-bar">
        <div class="category-scroll">
            <?php foreach ($allCategories as $cat): 
                if (($cat['show_on_mobile_top_slider'] ?? 0) == 1):
            ?>
                <a href="products.php?category=<?php echo $cat['id']; ?>" class="category-item-mobile">
                    <div class="category-icon-circle-mobile" style="background-color: <?php 
                        $colors = ['#f1f8f1', '#eef7f1', '#fdfbf0', '#fce8e4', '#f3f4f6'];
                        echo $colors[$cat['id'] % count($colors)]; 
                    ?> !important;">
                        <?php if (!empty($cat['icon_upload'])): ?>
                            <img src="uploads/categories/<?php echo htmlspecialchars($cat['icon_upload']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                        <?php elseif (!empty($cat['image'])): ?>
                            <img src="uploads/categories/<?php echo htmlspecialchars($cat['image']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                        <?php else: ?>
                            <div class="flex items-center justify-center w-full h-full bg-[#f1f8f1]">
                                <i class="fa <?php echo htmlspecialchars($cat['icon_class'] ?? 'fa-leaf'); ?>" style="color: #76a33a; font-size: 20px;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span><?php echo htmlspecialchars($cat['name']); ?></span>
                </a>
                <div class="mobile-category-divider"></div>
            <?php 
                endif;
            endforeach; ?>
        </div>
    </div>
    
    
    
    
    
    <main class="hero-container">
        <div id="hero-carousel" class="hero-carousel-wrapper group">
            <div class="relative w-full overflow-hidden">
                <div class="hero-slide active">
                    <img src="assets/products-banners/banners/Metabolic%20Balance%20(4).png" class="desktop-only">
                    <img src="assets/products-banners/livra-2.jpg.jpeg" class="mobile-only">
                </div>
                
                  <div class="hero-slide">
                    <img src="assets/products-banners/banners/Metabolic%20Balance%20(5).png">
                    <img src="assets/images/herobanner/Mobile-banner-1.jpeg" class="mobile-only">
                </div>
                <div class="hero-slide">
                    <img src="assets/products-banners/banners/Metabolic%20Balance%20(8).png" class="desktop-only">
                    <img src="assets/products-banners/livra-3.jpg.jpeg" class="mobile-only">
                </div>
                <div class="hero-slide">
                    <img src="assets/products-banners/banners/Metabolic%20Balance%20(11).png" class="desktop-only">
                    <img src="assets/products-banners/livra-4.jpg.jpeg" class="mobile-only">
                </div>
                <!--<div class="hero-slide">-->
                <!--    <img src="assets/images/herobanner/4%20copy.jpg%20(3).jpeg" class="desktop-only">-->
                <!--    <img src="assets/images/herobanner/Mobile-banner-4.jpeg" class="mobile-only">-->
                <!--</div>-->
            </div>
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center space-x-1 z-10">
                <span class="glider-dot active" data-slide="0"></span>
                <span class="glider-dot" data-slide="1"></span>
                <span class="glider-dot" data-slide="2"></span>
                <span class="glider-dot" data-slide="3"></span>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.glider-dot');
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            currentSlide = index;
        }

        function nextSlide() {
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function startAutoplay() {
            slideInterval = setInterval(nextSlide, 4000);
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                clearInterval(slideInterval);
                showSlide(index);
                startAutoplay();
            });
        });

        startAutoplay();
    });
    </script>
    
    
    
    <!-- SELECT CONCERN Section -->
    <style>
        .concern-item {
            transition: all 0.2s ease;
            white-space: nowrap;
            border-radius: 6px;
            border: 1px solid #c2c9b4;
            padding: 6px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            cursor: pointer;
            min-width: fit-content;
            flex-shrink: 0;
        }
        .concern-item.active {
            background-color: #f1f6e9;
            border-color: #76a33a;
        }
        .concern-item:hover {
            border-color: #76a33a;
        }
        /* Horizontal scroll hide */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

   

   <!-- SELECT CONCERN: Filtered for visibility -->
<section class="bg-white py-6 border-b border-gray-100">
    <div class="max-w-[1400px] mx-auto px-4 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">

            <!-- Heading -->
            <div class="flex items-center gap-2 shrink-0">
                <i data-lucide="filter" class="w-4 h-4 text-[#76a33a]"></i>
                <h2 class="font-bold text-[#212121] uppercase text-[12px] tracking-wider">
                    SELECT CONCERN:
                </h2>
            </div>

            <!-- Categories -->
            <div class="flex items-center gap-2 lg:gap-3 overflow-x-auto no-scrollbar py-1 flex-wrap">

                <?php foreach ($allCategories as $cat): 
                    $showOnMobile  = ($cat['show_on_mobile_concern'] ?? 1) == 1;
                    $showOnDesktop = ($cat['show_on_desktop_concern'] ?? 1) == 1;
                ?>

                <a 
                    href="products.php?category=<?php echo $cat['id']; ?>"
                    data-category="<?php echo $cat['id']; ?>"
                    class="concern-item 
                        <?php echo $showOnMobile ? 'flex' : 'hidden'; ?> 
                        <?php echo $showOnDesktop ? 'lg:flex' : 'lg:hidden'; ?> 
                        items-center gap-2 px-3 py-1 rounded-full 
                        hover:bg-gray-100 transition duration-200"
                >

                    <div class="w-7 h-7 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                        <?php if (!empty($cat['icon_upload'])): ?>
                            <img src="uploads/categories/<?php echo htmlspecialchars($cat['icon_upload']); ?>" 
                                 class="w-full h-full object-cover">
                        <?php elseif (!empty($cat['image'])): ?>
                            <img src="uploads/categories/<?php echo htmlspecialchars($cat['image']); ?>" 
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-[#f1f8f1]">
                                <i data-lucide="leaf" class="w-3 h-3 text-[#76a33a]"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <span class="text-[13px] font-semibold text-[#212121]">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </span>

                </a>

                <?php endforeach; ?>

            </div>

        </div>
    </div>
</section>
    <!-- Best Sellers Section -->
    <style>
        .product-grid-home {
            display: grid;
            gap: 25px;
            grid-template-columns: repeat(4, 1fr);
            margin-top: 20px;
        }
        @media (max-width: 1024px) { .product-grid-home { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { 
            .product-grid-home { 
                grid-template-columns: repeat(2, 1fr) !important; 
                gap: 10px !important; 
                padding: 0 10px !important;
            } 
            .luxury-image-wrapper {
                height: 180px !important;
                padding: 10px !important;
            }
            .luxury-buy-now-btn {
                font-size: 12px !important;
                height: 40px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .luxury-cart-icon-btn {
                width: 50px !important;
                height: 40px !important;
            }
            .luxury-rating-badge {
                top: 8px !important;
                left: 8px !important;
                font-size: 10px !important;
                padding: 1px 6px !important;
            }
            .luxury-badge-discount {
                top: 8px !important;
                right: 8px !important;
                font-size: 10px !important;
                padding: 1px 6px !important;
            }
        }

        .product-card-luxury {
            border: 1px solid #f1f1f1;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .product-card-luxury:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .luxury-image-wrapper {
            position: relative;
            background: #f8f8f8;
            padding: 16px;
            display: flex;
            justify-content: center;
            height: 250px;
        }
        .luxury-badge-discount {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #e9f0df;
            color: #76a33a;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            z-index: 10;
        }
        .luxury-rating-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(255, 255, 255, 0.8);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
            z-index: 10;
        }
        .luxury-coin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff8e5;
            padding: 4px 10px;
            border-radius: 50px;
            margin-bottom: 12px;
        }
        .luxury-coin-icon {
            width: 16px;
            height: 16px;
            background: #fbbf24;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
        }
        .luxury-delivery-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .luxury-buy-now-btn {
            background: #76a33a;
            color: white;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            flex-grow: 1;
            transition: background 0.3s;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .luxury-buy-now-btn:hover {
            background: #658c31;
        }
        .luxury-cart-icon-btn {
            width: 64px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #374151;
            color: white;
            border-right: 1px solid #4b5563;
        }
    </style>

  <section class="bg-white py-8 px-4 lg:px-8">
    <div class="max-w-[1400px] mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl lg:text-2xl font-bold text-[#212121]">
                Livvra Bestsellers
            </h2>
            <a href="products.php" class="text-sm font-semibold text-[#76a33a] hover:underline">
                View all
            </a>
        </div>

      <div id="product-container" class="product-grid-home">

<?php 

// ✅ YAHI PE LAGANA HAI (FOREACH SE PEHLE)

$priorityIds = [6, 5, 4]; // 👈 apne actual product IDs

if (!empty($featuredProducts) && is_array($featuredProducts)) {

    usort($featuredProducts, function($a, $b) use ($priorityIds) {

        $aIndex = array_search($a['id'], $priorityIds);
        $bIndex = array_search($b['id'], $priorityIds);

        $aIndex = $aIndex !== false ? $aIndex : 999;
        $bIndex = $bIndex !== false ? $bIndex : 999;

        return $aIndex - $bIndex;
    });

}

?>





        <?php foreach (array_slice($featuredProducts, 0, 8) as $product): 

            $final_price      = (float)($product['final_price'] ?? $product['price']);
            $discount_percent = (int)($product['discount_percent'] ?? 0);
            $mrp              = (float)($product['price'] ?? 0);
            $coins = floor($final_price * 0.05);

        ?>

            <div class="category-product-item product-card-luxury" 
                 data-category-id="<?php echo $product['category_id']; ?>">

                <div class="luxury-image-wrapper">

                    <!-- Rating -->
                    <div class="luxury-rating-badge">
                        <i data-lucide="star" class="w-3 h-3 fill-yellow-400 text-yellow-400"></i>

                        <?php if (!empty($product['reviews_count']) && $product['reviews_count'] > 0): ?>
                            <span>
                                <?php echo number_format($product['rating'],1); ?>/5
                                (<?php echo $product['reviews_count']; ?>)
                            </span>
                        <?php else: ?>
                            <span>New</span>
                        <?php endif; ?>
                    </div>

                    <!-- Discount Badge -->
                    <?php if ($discount_percent > 0): ?>
                        <div class="luxury-badge-discount">
                            <?php echo $discount_percent; ?>% OFF
                        </div>
                    <?php endif; ?>

                    <!-- ✅ IMAGE CLICKABLE -->
                    <a href="<?php echo productUrl($product); ?>">
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="object-contain h-full">
                    </a>

                </div>

                <div class="p-4 flex-grow flex flex-col">

                    <!-- ✅ NAME CLICKABLE -->
                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 min-h-[40px] mb-2">
                        <a href="<?php echo productUrl($product); ?>" 
                           class="hover:text-[#76a33a] transition-colors">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </a>
                    </h3>

                    <!-- Price -->
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-xl font-bold text-[#212121]">
                            ₹<?php echo number_format($final_price, 0); ?>
                        </span>

                        <?php if ($discount_percent > 0): ?>
                            <span class="text-xs text-gray-400 line-through">
                                ₹<?php echo number_format($mrp, 0); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Coins -->
                    <div class="luxury-coin-badge">
                        <div class="luxury-coin-icon">C</div>
                        <span class="text-[11px] font-bold text-gray-700">
                            Earn <?php echo $coins; ?> Livvra coins
                        </span>
                    </div>

                    <!-- Delivery -->
                    <div class="luxury-delivery-info mt-auto">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                        <span>
                            Delivered by <?php echo date('j', strtotime('+3 days')); ?> - 
                            <?php echo date('j M', strtotime('+4 days')); ?>
                        </span>
                    </div>

                   <!-- Buttons -->
<div class="flex items-center mt-2 border-t border-gray-100">

<?php if ($product['stock_qty'] > 0): ?>

    <button class="luxury-cart-icon-btn"
            onclick="addToCart(<?php echo $product['id']; ?>)">
        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
    </button>

    <button class="luxury-buy-now-btn"
            onclick="buyNow(<?php echo $product['id']; ?>)">
        BUY NOW
    </button>

<?php else: ?>

    <button class="luxury-buy-now-btn"
            style="width:100%; background:#d1d5db; cursor:not-allowed;"
            disabled>
        OUT OF STOCK
    </button>

<?php endif; ?>

</div>

                </div>
            </div>

        <?php endforeach; ?>

        </div>

        <div class="text-center mt-12">
            <a href="products.php"
               class="text-[#b57d62] font-bold border-b-2 border-[#b57d62] pb-1 hover:text-[#a06b52] hover:border-[#a06b52] transition-all">
                View all products
            </a>
        </div>

    </div>
</section>
    
    <style>
    /* Section Spacing & Header Layout */
    .category-section {
        padding: 60px 0 !important; /* Reduced padding for tighter feel */
    }
    
    .concern-header-layout {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 30px;
        gap: 30px;
    }
    
    .concern-title-group {
        text-align: left;
    }
    
    .concern-subtitle-group {
        text-align: right;
        max-width: 400px;
    }
    
    .concern-subtitle-group .section-subtitle {
        margin: 0;
        font-size: 1.05rem;
        color: #6C757D;
    }
    
    /* Category Filter Row Refinement */
    .category-filter-row {
        margin-bottom: 35px;
    }
    
    .category-filter-scroll {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding: 10px 5px 20px;
        scrollbar-width: none;
    }
    
    .filter-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 120px; /* Slightly larger */
        padding: 20px;
        background: #fff;
        border: 1px solid rgba(201, 162, 39, 0.15); /* Softer border */
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); /* Subtle shadow */
    }
    
    .filter-pill:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(201, 162, 39, 0.08);
        border-color: #C9A227;
    }
    
    .filter-pill i {
        font-size: 1.6rem;
        color: #4A7C59;
        margin-bottom: 10px;
    }
    
    .filter-pill span {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .filter-pill.active {
        background: #4A7C59;
        border-color: #4A7C59;
        box-shadow: 0 8px 25px rgba(74, 124, 89, 0.25);
    }
    
    /* Product Card Rating UI */
    .product-rating-premium {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }
    
    .product-rating-premium .stars {
        display: flex;
        gap: 2px;
    }
    
    .product-rating-premium .stars i {
        font-size: 0.8rem;
        color: #E5E7EB;
    }
    
    .product-rating-premium .stars i.filled {
        color: #C9A227;
    }
    
    .product-rating-premium .review-count {
        font-size: 0.8rem;
        color: #9CA3AF;
    }
    
    /* Concern Section Layout Refinements */
    .category-grid-premium {
        display: grid !important;
        gap: 25px !important;
        grid-template-columns: repeat(4, 1fr) !important;
    }
    
    @media (max-width: 1024px) {
        .concern-header-layout {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .concern-subtitle-group {
            text-align: left;
        }
        .category-grid-premium {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    @media (max-width: 600px) {
        .category-grid-premium {
            grid-template-columns: 1fr !important;
        }
        .filter-pill {
            min-width: 100px;
            padding: 15px;
        }
    }
    
    
    .category-filter-scroll {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding: 10px 5px;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
    }
    
    .category-filter-scroll::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
    }
    
    .filter-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        padding: 15px;
        background: #fff;
        border: 1px solid rgba(201, 162, 39, 0.2);
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .filter-pill i {
        font-size: 1.5rem;
        color: #4A7C59;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    
    .filter-pill span {
        font-size: 0.85rem;
        font-weight: 600;
        color: #2C3E50;
        white-space: nowrap;
    }
    
    .filter-pill:hover {
        border-color: #C9A227;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(201, 162, 39, 0.1);
    }
    
    .filter-pill.active {
        background: #4A7C59;
        border-color: #4A7C59;
        box-shadow: 0 8px 20px rgba(74, 124, 89, 0.2);
    }
    
    .filter-pill.active i, 
    .filter-pill.active span {
        color: #fff;
    }
    
    .category-product-item {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .category-product-item.hidden {
        display: none;
        opacity: 0;
        transform: scale(0.9);
    }
    
    @media (max-width: 768px) {
        .filter-pill {
            min-width: 90px;
            padding: 12px;
        }
    }
    </style>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const carousel = document.getElementById('hero-carousel');
        if (carousel) {
            const slides = carousel.querySelectorAll('.hero-slide');
            const dots = carousel.querySelectorAll('.glider-dot');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentSlide = index;
            }

            function nextSlide() {
                let next = (currentSlide + 1) % slides.length;
                showSlide(next);
            }

            function startAutoplay() {
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 4000);
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                    startAutoplay();
                });
            });

            startAutoplay();
        }

        // Concern filter logic
        const items = document.querySelectorAll('.category-product-item');
        const concernItems = document.querySelectorAll('.concern-item');

        // Story carousel scroll logic
        const storyTrack = document.getElementById('stories-track');
        const prevBtn = document.querySelector('.btn-prev');
        const nextBtn = document.querySelector('.btn-next');

        if (storyTrack && prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => {
                storyTrack.scrollBy({ left: -300, behavior: 'smooth' });
            });
            nextBtn.addEventListener('click', () => {
                storyTrack.scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
    });
    </script>
    
    
    <style>
        @media (max-width: 768px) {
            .mobile-only-bar { display: block !important; }
            .desktop-only-bar { display: none !important; }
        }
    </style>
    
    <style>.trust-scroller-section {
          background: #fdfaf3;
          overflow: hidden;
          padding: 12px 0;
          border-top: 1px solid rgba(0,0,0,0.05);
          border-bottom: 1px solid rgba(0,0,0,0.05);
        }
    
        .trust-marquee {
          width: 100%;
          overflow: hidden;
        }
    
        .trust-track {
          display: flex;
          gap: 40px;
          width: max-content;
        }
    
        .trust-pill {
          display: flex;
          align-items: center;
          gap: 10px;
          white-space: nowrap;
          background: #fff;
          padding: 6px 16px;
          border-radius: 50px;
          font-size: 0.85rem;
          font-weight: 600;
          color: #4A7C59;
          border: 1px solid rgba(201,162,39,0.2);
        }
    
        .trust-pill i {
          color: #C9A227;
        }
    </style>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
      const track = document.querySelector(".trust-track");
      if (!track) return;
    
      // Duplicate content for infinite loop
      track.innerHTML += track.innerHTML;
    
      let position = 0;
      const speed = 0.5; // control speed (0.3 slow, 1 fast)
    
      function move() {
        position -= speed;
        if (Math.abs(position) >= track.scrollWidth / 2) {
          position = 0;
        }
        track.style.transform = `translateX(${position}px)`;
        requestAnimationFrame(move);
      }
    
      move();
    });
    </script>
    
    
    
      <?php
    try {
        $stories = Story::getAll();
    } catch (Exception $e) {
        error_log("Stories Error: " . $e->getMessage());
        $stories = [];
    }
    ?>
    <!-- Real People, Real Stories Section -->
    <style>
        .stories-section {
            padding: 60px 0;
            background: #fff;
            overflow: hidden;
        }
        .stories-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: #212121;
            margin-bottom: 40px;
            font-family: 'Inter', sans-serif;
        }
        .stories-carousel {
            position: relative;
            width: 100%;
        }
        .stories-track {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding-bottom: 20px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .stories-track::-webkit-scrollbar {
            display: none;
        }
        .story-card {
            flex: 0 0 280px;
            background: #fff;
        }
        .story-image {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
            background: #f5f5f5;
        }
        .story-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .story-rating {
            display: flex;
            gap: 2px;
            margin-bottom: 10px;
        }
        .story-rating i {
            color: #76a33a;
            font-size: 14px;
        }
        .story-name {
            font-weight: 800;
            font-size: 15px;
            color: #212121;
            margin-bottom: 5px;
            font-family: 'Inter', sans-serif;
        }
        .story-text {
            font-size: 13px;
            color: #444;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .carousel-btn {
            position: absolute;
            top: 40%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            z-index: 10;
            border: 1px solid #eee;
        }
        .btn-prev { left: -20px; }
        .btn-next { right: -20px; }
        
    <style>
        .stories-track {
            display: flex !important;
            gap: 20px !important;
            overflow-x: auto !important;
            scroll-behavior: smooth !important;
            padding: 10px 5px 30px !important;
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
            white-space: nowrap !important;
        }
        .stories-track::-webkit-scrollbar {
            display: none !important;
        }
        .story-card {
            flex: 0 0 280px !important;
            background: #fff !important;
            white-space: normal !important;
            display: block !important;
        }
        @media (max-width: 768px) { 
            .story-card {
                flex: 0 0 240px !important;
            }
        }
    </style>
    <section class="stories-section py-8">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-8">
            <div class="stories-header mb-8">
                <h2 class="text-2xl font-bold text-[#212121]">Real people, Real stories</h2>
            </div>
            
            <div class="stories-carousel">
                <div class="carousel-btn btn-prev"><i data-lucide="chevron-left"></i></div>
                <div class="carousel-btn btn-next"><i data-lucide="chevron-right"></i></div>
                
                <div class="stories-track" id="stories-track">
                    <?php if (empty($stories)): ?>
                        <!-- Static fallback for preview -->
                        <?php 
                        $demo_stories = [
                            ['name' => 'Bholanath', 'text' => 'Used it as whey protein supplements post...', 'img' => 'https://livvra.in/assets/images/reviews/1.png'],
                            ['name' => 'Dinesh', 'text' => 'I consumed many whey proteins but I got very...', 'img' => 'https://livvra.in/assets/images/reviews/2.png'],
                            ['name' => 'Sonu Bhardwaj', 'text' => 'After using kapiva shilajit gold.. I feel the...', 'img' => 'https://livvra.in/assets/images/reviews/3.png'],
                            ['name' => 'Masarat Jahan', 'text' => 'I ordered for my brother and he is more energe...', 'img' => 'https://livvra.in/assets/images/reviews/4.png'],
                            ['name' => 'Shivam', 'text' => 'Never switching back to other proteins again....', 'img' => 'https://livvra.in/assets/images/reviews/5.png'],
                            ['name' => 'Prabhjot Gill', 'text' => 'Happy to use the product able to do the...', 'img' => 'https://livvra.in/assets/images/reviews/6.png']
                        ];
                        foreach($demo_stories as $s): ?>
                            <div class="story-card">
                                <div class="story-image"><img src="<?php echo $s['img']; ?>"></div>
                                <div class="story-rating">
                                    <i data-lucide="star" class="fill-current" ></i>
                                    <i data-lucide="star" class="fill-current"></i>
                                    <i data-lucide="star" class="fill-current"></i>
                                    <i data-lucide="star" class="fill-current"></i>
                                    <i data-lucide="star" class="fill-current"></i>
                                </div>
                                <div class="story-name"><?php echo $s['name']; ?></div>
                                <div class="story-text"><?php echo $s['text']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                       <?php foreach($stories as $s): ?>
    <div class="story-card">
        <div class="story-image">
            <img src="uploads/stories/<?php echo htmlspecialchars($s['image_path']); ?>">
        </div>
        <div class="story-rating">
            <?php for($i=0; $i<$s['rating']; $i++): ?>
                <i data-lucide="star" class="fill-current"></i>
            <?php endfor; ?>
        </div>
        <div class="story-name"><?php echo htmlspecialchars($s['name']); ?></div>
        <div class="story-text"><?php echo htmlspecialchars($s['story_text']); ?></div>
    </div>
<?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('stories-track');
            const prev = document.querySelector('.btn-prev');
            const next = document.querySelector('.btn-next');
            
            if (track && prev && next) {
                prev.addEventListener('click', () => {
                    track.scrollBy({ left: -300, behavior: 'smooth' });
                });
                next.addEventListener('click', () => {
                    track.scrollBy({ left: 300, behavior: 'smooth' });
                });
            }
        });
    </script>
    
    
    
    
    
    
    
    
    
    <section class="livvra-reels-section">

<?php
$reels = [];

try {
    $stmt = db()->prepare("
        SELECT r.*, 
               p.id as product_id,
               p.name,
               p.price,
               p.offer_price
        FROM reels r
        LEFT JOIN products p ON r.product_id = p.id
        WHERE r.is_active = 1
        ORDER BY r.id DESC
    ");
    $stmt->execute();
    $reels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    echo "<div style='color:red'>".$e->getMessage()."</div>";
}
?>

<h2 class="reel-heading-neon">
    WATCH LIVVRA TAKE OVER
</h2>

<!-- Scroll Arrows -->
<button class="scroll-arrow scroll-left" onclick="scrollReels('left')">❮</button>
<button class="scroll-arrow scroll-right" onclick="scrollReels('right')">❯</button>

<div class="reel-scroll" id="reelScroll">

<?php foreach($reels as $reel):

$price = (float)($reel['price'] ?? 0);
$offer = (float)($reel['offer_price'] ?? 0);
$final = $offer > 0 ? $offer : $price;
$discount = ($offer > 0 && $price > 0)
    ? round((($price - $offer)/$price)*100)
    : 0;

/* Auto Increase Views */
db()->prepare("UPDATE reels SET views = views + 1 WHERE id = ?")
   ->execute([$reel['id']]);

$views = number_format(($reel['views'] ?? 0) + 1);

$link = "https://livvra.in/product-detail.php?id=".$reel['product_id'];

?>

<div class="reel-card">

    <div class="reel-video">

        <!-- Voice enabled: muted hata diya, volume icon add kiya -->
        <video autoplay loop playsinline controlsList="nodownload" onvolumechange="handleVolume(this)">
            <source src="uploads/reels/<?php echo htmlspecialchars($reel['video']); ?>" type="video/mp4">
        </video>

        <!-- Volume Toggle Button -->
        <div class="volume-btn" onclick="toggleMute(this)">
            <span class="vol-icon">🔇</span>
        </div>

        <div class="views-badge">
            <?php echo $views; ?> Views
        </div>

        <div class="reel-icons">
            <span class="heart">❤</span>
            <span class="share-btn" onclick="openShare('<?php echo $link; ?>')">➤</span>
        </div>

    </div>

    <div class="reel-product">

        <div class="reel-product-name">
            <?php echo htmlspecialchars($reel['name'] ?? 'Livvra Product'); ?>
        </div>

        <div class="reel-price">
            ₹<?php echo number_format($final,0); ?>

            <?php if($discount > 0): ?>
                <span class="mrp">₹<?php echo number_format($price,0); ?></span>
                <span class="off"><?php echo $discount; ?>% OFF</span>
            <?php endif; ?>
        </div>

        <?php if(!empty($reel['product_id'])): ?>
        <a href="product-detail.php?id=<?php echo $reel['product_id']; ?>" 
           class="buy-btn">
           Buy Now
        </a>
        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

</div>

</section>

<!-- SHARE POPUP -->
<div id="sharePopup" class="share-popup">
    <div class="share-box">
        <h3>Share This Product</h3>
        <a id="whatsappShare" target="_blank">WhatsApp</a>
        <a id="facebookShare" target="_blank">Facebook</a>
        <a id="twitterShare" target="_blank">Twitter</a>
        <button onclick="copyLink()">Copy Link</button>
        <span class="close-share" onclick="closeShare()">×</span>
    </div>
</div>

<style>

/* SECTION */
.livvra-reels-section {
    padding: 80px 0;
    background: #f5f5f5;
    position: relative;
}

/* HEADING */
.reel-heading-neon{
    text-align:center;
    font-size:60px;
    font-weight:900;
    text-transform:uppercase;
    margin-bottom:60px;
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    background: linear-gradient(90deg,#ff2d00,#ff9100,#ff00c8,#00ffe0,#ff2d00);
    background-size:400%;
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    animation: gradientMove 6s linear infinite;
}

@keyframes gradientMove{
    0%{ background-position:0% 50%; }
    100%{ background-position:100% 50%; }
}

/* SCROLL ARROWS */
.scroll-arrow{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(118, 163, 58, 0.9);
    color: #fff;
    border: none;
    font-size: 24px;
    cursor: pointer;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.scroll-arrow:hover{
    background: #d92400;
    transform: translateY(-50%) scale(1.1);
}

.scroll-left{
    left: 20px;
}

.scroll-right{
    right: 20px;
}

/* SCROLL */
.reel-scroll{
    display:flex;
    gap:15px;
    overflow-x:auto;
    padding:0 80px;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.reel-scroll::-webkit-scrollbar{ display:none; }

/* CARD - CHOTA SIZE 7-8 reels dikhenge */
.reel-card{
    min-width:160px;
    max-width:160px;
    background:#fff;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,0.1);
    transition:0.3s;
    flex-shrink: 0;
}
.reel-card:hover{
    transform:translateY(-6px);
    box-shadow:0 12px 35px rgba(0,0,0,0.15);
}

/* VIDEO */
.reel-video{ 
    position:relative; 
    background: #000;
}
.reel-video video{
    width:100%;
    height:280px;
    object-fit:cover;
    display: block;
}

/* VOLUME BUTTON */
.volume-btn{
    position: absolute;
    top: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    background: rgba(0,0,0,0.6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: 0.2s;
}

.volume-btn:hover{
    background: rgba(118, 163, 58, 0.9);
    transform: scale(1.1);
}

.vol-icon{
    font-size: 14px;
}

.volume-btn.active{
    background: #76a33a;
}

/* VIEWS */
.views-badge{
    position:absolute;
    bottom:10px;
    left:10px;
    background:rgba(0,0,0,0.8);
    color:#fff;
    padding:4px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight: 600;
}

/* ICONS */
.reel-icons{
    position:absolute;
    right:10px;
    bottom:80px;
    display:flex;
    flex-direction:column;
    gap:10px;
}

.reel-icons span{
    width:32px;
    height:32px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(0,0,0,0.6);
    border-radius:50%;
    color:#fff;
    font-size:14px;
    cursor:pointer;
    transition:0.2s;
}

.reel-icons span:hover{
    background:#76a33a;
    transform:scale(1.15);
}

/* PRODUCT */
.reel-product{ 
    padding:12px; 
}

.reel-product-name{
    font-size:13px;
    font-weight:700;
    margin-bottom:6px;
    line-height: 1.3;
    height: 34px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.reel-price{
    font-size:14px;
    font-weight:800;
    margin-bottom:10px;
}

.mrp{
    text-decoration:line-through;
    color:#888;
    margin-left:5px;
    font-size:11px;
    font-weight: 600;
}

.off{
    color:#e60023;
    margin-left:5px;
    font-size:10px;
    background: #ffe5e5;
    padding: 2px 5px;
    border-radius: 4px;
}

/* BUTTON */
.buy-btn{
    display:block;
    background:#76a33a;
    color:#fff;
    text-align:center;
    padding:10px 0;
    border-radius:10px;
    text-decoration:none;
    font-weight:700;
    font-size:12px;
    transition: 0.2s;
}
.buy-btn:hover{
    background:#d92400;
}

/* SHARE POPUP */
.share-popup{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.share-box{
    background:#fff;
    padding:25px;
    border-radius:15px;
    width:300px;
    text-align:center;
    position:relative;
}

.share-box a,
.share-box button{
    display:block;
    margin:10px 0;
    padding:10px;
    border-radius:8px;
    text-decoration:none;
    background:#f3f3f3;
    border:none;
    cursor:pointer;
    transition: 0.2s;
}

.share-box a:hover,
.share-box button:hover{
    background:#76a33a;
    color: #fff;
}

.close-share{
    position:absolute;
    top:10px;
    right:15px;
    cursor:pointer;
    font-size: 24px;
    color: #666;
}

/* MOBILE */
@media(max-width:768px){

    .reel-heading-neon{
        font-size:28px;
        margin-bottom: 30px;
    }

    .scroll-arrow{
        width: 35px;
        height: 35px;
        font-size: 16px;
    }

    .scroll-left{
        left: 5px;
    }

    .scroll-right{
        right: 5px;
    }

    .reel-card{
        min-width:140px;
        max-width:140px;
    }

    .reel-video video{
        height:220px;
    }

    .reel-scroll{
        padding:0 50px;
        gap:12px;
    }

    .reel-icons{
        right: 8px;
        bottom: 60px;
        gap: 8px;
    }

    .reel-icons span{
        width: 28px;
        height: 28px;
        font-size: 12px;
    }

    .reel-product{ 
        padding:10px; 
    }

    .reel-product-name{
        font-size:11px;
        height: 28px;
    }

    .reel-price{
        font-size:12px;
    }

    .buy-btn{
        padding:8px 0;
        font-size:11px;
    }
}
</style>

<script>
let currentLink = "";

// Scroll Functions
function scrollReels(direction) {
    const scrollContainer = document.getElementById('reelScroll');
    const scrollAmount = 200; // 1.5 cards approx
    
    if (direction === 'left') {
        scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

// Volume Toggle
function toggleMute(btn) {
    const video = btn.parentElement.querySelector('video');
    const icon = btn.querySelector('.vol-icon');
    
    if (video.muted) {
        video.muted = false;
        icon.textContent = '🔊';
        btn.classList.add('active');
    } else {
        video.muted = true;
        icon.textContent = '🔇';
        btn.classList.remove('active');
    }
}

// Handle volume change from video controls
function handleVolume(video) {
    const btn = video.parentElement.querySelector('.volume-btn');
    const icon = btn.querySelector('.vol-icon');
    
    if (video.muted || video.volume === 0) {
        icon.textContent = '🔇';
        btn.classList.remove('active');
    } else {
        icon.textContent = '🔊';
        btn.classList.add('active');
    }
}

// Share Functions
function openShare(link){
    if (navigator.share) {
        navigator.share({
            title: "Livvra Product",
            url: link
        });
    } else {
        currentLink = link;
        document.getElementById("whatsappShare").href = "https://wa.me/?text=" + encodeURIComponent(link);
        document.getElementById("facebookShare").href = "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(link);
        document.getElementById("twitterShare").href = "https://twitter.com/intent/tweet?url=" + encodeURIComponent(link);
        document.getElementById("sharePopup").style.display = "flex";
    }
}

function closeShare(){
    document.getElementById("sharePopup").style.display = "none";
}

function copyLink(){
    navigator.clipboard.writeText(currentLink);
    alert("Link Copied!");
}

// Close popup on outside click
window.onclick = function(e){
    const popup = document.getElementById("sharePopup");
    if(e.target === popup){
        popup.style.display = "none";
    }
}

// Auto-play videos when in viewport
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.5
};

const videoObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        const video = entry.target;
        if (entry.isIntersecting) {
            video.play();
        } else {
            video.pause();
        }
    });
}, observerOptions);

// Observe all videos
document.querySelectorAll('.reel-video video').forEach(video => {
    video.muted = true; // Start muted
    videoObserver.observe(video);
});
</script>



<section class="pas-section">
    <div class="container">
        <div class="pas-header">
            <div class="pas-header-text">
                <h2 class="pas-title">Ayurveda for a <span>Balanced Life</span></h2>
                <p class="pas-subtitle">Premium quality products trusted by thousands of customers</p>
            </div>
            <div class="pas-nav-btns">
                <button class="pas-prev" onclick="moveCarousel(-1)" aria-label="Previous">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button class="pas-next" onclick="moveCarousel(1)" aria-label="Next">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    
        <div class="pas-grid">
            <!-- LEFT IMAGE - Mobile pe upar dikhega -->
            <div class="pas-left">
                <div class="pas-badge">Naturally Crafted</div>
                <div class="pas-main-img-container">
                    <img id="pasMainImg" src="assets/products-banners/WhatsApp%20Image%202026-03-19%20at%203.36.10%20PM%20(1).jpeg" alt="Main Product">
                </div>
                <div class="pas-thumbs">
                    <img src="assets/products-banners/WhatsApp%20Image%202026-03-19%20at%203.36.10%20PM%20(2).jpeg" class="active" data-image="assets/products-banners/WhatsApp%20Image%202026-03-19%20at%203.36.10%20PM%20(2).jpeg" alt="Thumb 1">
                    <img src="assets/products-banners/WhatsApp%20Image%202026-03-19%20at%203.36.10%20PM%20(3).jpeg" data-image="assets/products-banners/WhatsApp%20Image%202026-03-19%20at%203.36.10%20PM%20(3).jpeg" alt="Thumb 2">
                    <img src="assets/products-banners/WhatsApp%20Image%202026-03-19%20at%203.36.10%20PM%20(4).jpeg" data-image="assets/products-banners/WhatsApp%20Image%202026-03-19%20at%203.36.10%20PM%20(4).jpeg" alt="Thumb 3">
                </div>
            </div>
    
            <!-- RIGHT PRODUCTS -->
            <div class="pas-right">
                <div class="pas-filter-wrapper">
                    <div class="pas-pills">
                        <span class="active" data-filter="all" onclick="filterProducts('all')">All Products</span>
                        <?php foreach($allCategories as $cat): ?>
                            <span data-filter="<?= trim(strtolower($cat['slug'])) ?>" onclick="filterProducts('<?= trim(strtolower($cat['slug'])) ?>')">
                                <?= htmlspecialchars($cat['name']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
    
                <div class="pas-products-viewport">
                    <div class="pas-products" id="pasTrack">
                        <?php foreach($featuredProducts as $p):
                            $img = !empty($p['image']) ? "uploads/products/".$p['image'] : "assets/images/placeholder.png";
                            $cat_slug = !empty($p['category_slug']) ? trim(strtolower($p['category_slug'])) : '';
                            $discount = isset($p['discount']) ? $p['discount'] : 10;
                            $original_price = $p['price'];
                            $discounted_price = $original_price - ($original_price * $discount / 100);
                        ?>
                        <div class="pas-card" data-category="<?= $cat_slug ?>" data-slug="<?= htmlspecialchars($p['slug'] ?? '') ?>" onclick="openProductModal(<?= $p['id'] ?>)">
                            <div class="pas-card-img">
                                <?php if($discount > 0): ?>
                                    <div class="pas-discount-badge">-<?= $discount ?>%</div>
                                <?php endif; ?>
                                <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                                <div class="pas-card-overlay">
                                    <button class="pas-action-btn" onclick="event.stopPropagation(); quickView(<?= $p['id'] ?>)" title="Quick View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="pas-action-btn" onclick="event.stopPropagation(); addToCart(<?= $p['id'] ?>)" title="Add to Cart">
                                        <i class="fas fa-shopping-basket"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="pas-card-info">
                                <div class="pas-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span>(4.9)</span>
                                </div>
                                <h4 class="pas-card-title"><?= htmlspecialchars($p['name']) ?></h4>
                                <p class="pas-card-desc"><?= htmlspecialchars(substr($p['description'] ?? 'Premium Ayurvedic product for your wellness', 0, 60)) ?>...</p>
                                <div class="pas-price-wrap">
                                    <?php if($discount > 0): ?>
                                        <span class="pas-original-price">₹<?= number_format($original_price) ?></span>
                                    <?php endif; ?>
                                    <span class="pas-price">₹<?= number_format($discounted_price) ?></span>
                                </div>
                                <div class="pas-btns">
                                    <button class="pas-buy-btn" onclick="event.stopPropagation(); buyNow(<?= $p['id'] ?>)">
                                        <span>Buy Now</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                    <button class="pas-view-btn" onclick="event.stopPropagation(); openProductModal(<?= $p['id'] ?>)">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Mobile Scroll Indicator -->
                <div class="pas-scroll-indicator">
                    <span>Swipe to explore</span>
                    <i class="fas fa-hand-pointer"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div id="productModal" class="pas-modal">
        <div class="pas-modal-content">
            <button class="pas-modal-close" onclick="closeProductModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="pas-modal-grid">
                <div class="pas-modal-img-section">
                    <div class="pas-modal-discount" id="modalDiscount">-10%</div>
                    <img id="modalImg" src="" alt="Product Detail">
                </div>
                <div class="pas-modal-info">
                    <div class="pas-modal-category" id="modalCategory">Category</div>
                    <h2 class="pas-modal-title" id="modalTitle">Product Name</h2>
                    <div class="pas-modal-rating">
                        <div class="pas-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span>(4.8) 128 Reviews</span>
                    </div>
                    <div class="pas-modal-price-box">
                        <span class="pas-modal-original" id="modalOriginalPrice">₹1,999</span>
                        <span class="pas-modal-price" id="modalPrice">₹1,799</span>
                        <span class="pas-modal-save">Save 10%</span>
                    </div>
                    <p class="pas-modal-desc" id="modalDesc">Product description goes here...</p>
                    
                    <div class="pas-modal-actions">
                        <div class="pas-qty-selector">
                            <button onclick="changeModalQty(-1)">-</button>
                            <span id="modalQty">1</span>
                            <button onclick="changeModalQty(1)">+</button>
                        </div>
                        <button class="pas-add-cart-btn" onclick="addToCartFromModal()">
                            <i class="fas fa-shopping-bag"></i>
                            Add to Cart
                        </button>
                        <button class="pas-wishlist-btn" onclick="toggleWishlist()">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="pasToast" class="pas-toast">
        <div class="pas-toast-icon"><i class="fas fa-check"></i></div>
        <div class="pas-toast-content">
            <h4>Success!</h4>
            <p id="toastMessage">Added to cart</p>
        </div>
    </div>
</section>
    
<style>
/* ============================================
   MOBILE-FIRST RESPONSIVE AYURVEDA SHOWCASE
   ============================================ */

/* CSS Variables for easy theming */
:root {
    --pas-primary: #4A7C59;
    --pas-secondary: #C9A227;
    --pas-accent: #e74c3c;
    --pas-bg: #fdfaf3;
    --pas-cream: #f5f1e8;
    --pas-text: #2C3E50;
    --pas-muted: #6b7280;
    --pas-white: #ffffff;
    --pas-shadow: rgba(26, 77, 58, 0.08);
    --pas-gold-glow: rgba(201, 162, 39, 0.2);
    
    /* Spacing scale */
    --space-xs: 0.5rem;
    --space-sm: 1rem;
    --space-md: 1.5rem;
    --space-lg: 2rem;
    --space-xl: 3rem;
    
    /* Border radius */
    --radius-sm: 12px;
    --radius-md: 20px;
    --radius-lg: 25px;
    --radius-xl: 30px;
}

/* Base Section Styles - Mobile First */
.pas-section {
    padding: 60px 0;
    background: linear-gradient(135deg, var(--pas-bg) 0%, var(--pas-cream) 100%);
    overflow: hidden;
    position: relative;
    font-family: 'Inter', sans-serif;
}

.pas-section::before {
    content: '';
    position: absolute;
    top: -10%;
    right: -5%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, var(--pas-gold-glow) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

/* Container - Fluid width on mobile */
.pas-section .container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 15px;
    position: relative;
    z-index: 1;
}

/* Header - Stack on mobile */
.pas-header {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
    margin-bottom: var(--space-lg);
    text-align: center;
}

.pas-header-text {
    width: 100%;
}

.pas-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.75rem, 5vw, 3rem);
    color: var(--pas-text);
    margin-bottom: 10px;
    line-height: 1.2;
    font-weight: 600;
}

.pas-title span {
    color: var(--pas-primary);
    font-style: italic;
    position: relative;
}

.pas-title span::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--pas-secondary), transparent);
}

.pas-subtitle {
    color: var(--pas-muted);
    font-size: clamp(0.9rem, 2vw, 1.1rem);
    line-height: 1.6;
    max-width: 100%;
}

/* Navigation Buttons - Center on mobile */
.pas-nav-btns {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.pas-nav-btns button {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 2px solid #e0e0e0;
    background: var(--pas-white);
    cursor: pointer;
    transition: all 0.3s ease;
    color: var(--pas-text);
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.pas-nav-btns button:hover,
.pas-nav-btns button:active {
    background: var(--pas-primary);
    color: var(--pas-white);
    border-color: var(--pas-primary);
    transform: scale(1.05);
}

/* Main Grid - Single column on mobile */
.pas-grid {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

/* Left Section - Full width on mobile */
.pas-left {
    width: 100%;
    order: 1;
}

.pas-badge {
    display: inline-block;
    padding: 8px 16px;
    background: rgba(74, 124, 89, 0.1);
    color: var(--pas-primary);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
    border: 1px solid rgba(74, 124, 89, 0.2);
}

/* Main Image Container - Mobile optimized */
.pas-main-img-container {
    width: 100%;
    height: 300px;
    background: linear-gradient(135deg, var(--pas-white) 0%, #f8f9fa 100%);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-shadow: 0 15px 40px var(--pas-shadow);
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(201, 162, 39, 0.1);
}

#pasMainImg {
    width: 100%;
    height: 100%;
    object-fit: contain;
    max-height: 260px;
    transition: transform 0.5s ease;
    filter: drop-shadow(0 8px 16px rgba(0,0,0,0.1));
}

/* Thumbnails - Horizontal scroll on mobile */
.pas-thumbs {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.pas-thumbs img {
    width: 70px;
    height: 70px;
    border-radius: var(--radius-sm);
    border: 2px solid transparent;
    cursor: pointer;
    background: var(--pas-white);
    padding: 6px;
    object-fit: contain;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.pas-thumbs img.active {
    border-color: var(--pas-secondary);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px var(--pas-gold-glow);
}

/* Right Section */
.pas-right {
    width: 100%;
    order: 2;
}

/* Filter Pills - Horizontal scroll */
.pas-filter-wrapper {
    margin-bottom: 25px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.pas-filter-wrapper::-webkit-scrollbar { 
    display: none; 
}

.pas-pills {
    display: flex;
    gap: 10px;
    padding-bottom: 5px;
    min-width: max-content;
}

.pas-pills span {
    padding: 10px 20px;
    border-radius: 25px;
    background: var(--pas-white);
    color: var(--pas-muted);
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    border: 2px solid #eee;
    transition: all 0.3s ease;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.pas-pills span:active {
    transform: scale(0.95);
}

.pas-pills span.active {
    background: var(--pas-primary);
    color: var(--pas-white);
    border-color: var(--pas-primary);
    box-shadow: 0 8px 20px rgba(74, 124, 89, 0.25);
}

/* Products Viewport - CSS Scroll Snap for mobile */
.pas-products-viewport {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x mandatory;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 10px 0 20px;
}

.pas-products-viewport::-webkit-scrollbar { 
    display: none; 
}

.pas-products {
    display: flex;
    gap: 20px;
    padding: 0 5px;
}

/* Product Card - Mobile optimized */
.pas-card {
    flex: 0 0 280px;
    scroll-snap-align: start;
    background: var(--pas-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    transition: all 0.4s ease;
    border: 1px solid rgba(0,0,0,0.04);
    position: relative;
    cursor: pointer;
}

.pas-card:active {
    transform: scale(0.98);
}

.pas-card-img {
    height: 200px;
    background: linear-gradient(135deg, #fcfcfc 0%, #f5f5f5 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
}

.pas-card-img img {
    width: 90%;
    height: 90%;
    object-fit: contain;
    transition: transform 0.5s ease;
    filter: drop-shadow(0 6px 12px rgba(0,0,0,0.1));
}

/* Discount Badge */
.pas-discount-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, var(--pas-accent), #c0392b);
    color: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.75rem;
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    z-index: 5;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Card Overlay - Always visible on mobile for touch */
.pas-card-overlay {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    opacity: 1;
    z-index: 10;
}

.pas-action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--pas-white);
    color: var(--pas-primary);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.pas-action-btn:active {
    transform: scale(0.9);
    background: var(--pas-primary);
    color: var(--pas-white);
}

/* Card Info */
.pas-card-info {
    padding: 20px;
}

.pas-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 10px;
    color: var(--pas-secondary);
    font-size: 0.8rem;
}

.pas-rating span {
    color: var(--pas-muted);
    margin-left: 5px;
    font-weight: 500;
}

.pas-card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    color: var(--pas-text);
    margin-bottom: 8px;
    line-height: 1.3;
    font-weight: 600;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.6em;
}

.pas-card-desc {
    color: var(--pas-muted);
    font-size: 0.85rem;
    line-height: 1.5;
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.pas-price-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.pas-original-price {
    font-size: 0.9rem;
    color: #999;
    text-decoration: line-through;
    font-weight: 500;
}

.pas-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--pas-primary);
}

.pas-btns {
    display: flex;
    gap: 10px;
}

.pas-buy-btn {
    flex: 1;
    padding: 12px;
    border-radius: var(--radius-sm);
    border: 2px solid var(--pas-primary);
    background: transparent;
    color: var(--pas-primary);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.pas-buy-btn:active {
    background: var(--pas-primary);
    color: var(--pas-white);
    transform: scale(0.98);
}

.pas-view-btn {
    width: 42px;
    height: 42px;
    border-radius: var(--radius-sm);
    border: 2px solid var(--pas-primary);
    background: transparent;
    color: var(--pas-primary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.pas-view-btn:active {
    background: var(--pas-primary);
    color: var(--pas-white);
}

/* Mobile Scroll Indicator */
.pas-scroll-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
    color: var(--pas-muted);
    font-size: 0.85rem;
    animation: bounce-hint 2s infinite;
}

.pas-scroll-indicator i {
    font-size: 1.2rem;
}

@keyframes bounce-hint {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(10px); }
}

/* Product Modal - Mobile optimized */
.pas-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(10px);
    z-index: 1000;
    display: none;
    align-items: flex-end;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.pas-modal.active {
    display: flex;
    opacity: 1;
}

.pas-modal-content {
    background: var(--pas-white);
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    transform: translateY(100%);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 -20px 60px rgba(0,0,0,0.3);
}

.pas-modal.active .pas-modal-content {
    transform: translateY(0);
}

.pas-modal-close {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: rgba(0,0,0,0.05);
    color: var(--pas-text);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    z-index: 10;
    transition: all 0.3s ease;
}

.pas-modal-close:active {
    background: var(--pas-accent);
    color: white;
    transform: rotate(90deg);
}

.pas-modal-grid {
    display: flex;
    flex-direction: column;
}

.pas-modal-img-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    position: relative;
    min-height: 300px;
}

.pas-modal-discount {
    position: absolute;
    top: 20px;
    left: 20px;
    background: linear-gradient(135deg, var(--pas-accent), #c0392b);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
}

.pas-modal-img-section img {
    width: 100%;
    max-width: 280px;
    height: auto;
    max-height: 250px;
    object-fit: contain;
    filter: drop-shadow(0 15px 30px rgba(0,0,0,0.15));
}

.pas-modal-info {
    padding: 25px;
}

.pas-modal-category {
    color: var(--pas-secondary);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 0.8rem;
    margin-bottom: 10px;
}

.pas-modal-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: var(--pas-text);
    margin-bottom: 12px;
    line-height: 1.3;
}

.pas-modal-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}

.pas-stars {
    color: var(--pas-secondary);
    font-size: 0.9rem;
}

.pas-modal-rating span {
    color: var(--pas-muted);
    font-size: 0.9rem;
}

.pas-modal-price-box {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding: 15px;
    background: linear-gradient(135deg, rgba(201, 162, 39, 0.1), rgba(201, 162, 39, 0.05));
    border-radius: var(--radius-sm);
    border-left: 3px solid var(--pas-secondary);
    flex-wrap: wrap;
}

.pas-modal-original {
    font-size: 1.1rem;
    color: #999;
    text-decoration: line-through;
    font-weight: 500;
}

.pas-modal-price {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--pas-primary);
}

.pas-modal-save {
    background: var(--pas-accent);
    color: white;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: auto;
}

.pas-modal-desc {
    color: var(--pas-muted);
    line-height: 1.7;
    margin-bottom: 25px;
    font-size: 0.95rem;
}

.pas-modal-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.pas-qty-selector {
    display: flex;
    align-items: center;
    border: 2px solid #e0e0e0;
    border-radius: var(--radius-sm);
    overflow: hidden;
    height: 50px;
}

.pas-qty-selector button {
    width: 45px;
    height: 100%;
    border: none;
    background: transparent;
    color: var(--pas-text);
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pas-qty-selector button:active {
    background: rgba(74, 124, 89, 0.1);
}

.pas-qty-selector span {
    width: 50px;
    text-align: center;
    font-weight: 700;
    color: var(--pas-text);
    font-size: 1.1rem;
}

.pas-add-cart-btn {
    flex: 1;
    min-width: 140px;
    height: 50px;
    background: linear-gradient(135deg, var(--pas-primary), #3d6b4a);
    color: var(--pas-white);
    border: none;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(74, 124, 89, 0.3);
}

.pas-add-cart-btn:active {
    transform: scale(0.98);
    box-shadow: 0 4px 15px rgba(74, 124, 89, 0.2);
}

.pas-wishlist-btn {
    width: 50px;
    height: 50px;
    border-radius: var(--radius-sm);
    border: 2px solid #e0e0e0;
    background: var(--pas-white);
    color: var(--pas-text);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.pas-wishlist-btn:active {
    border-color: var(--pas-accent);
    color: var(--pas-accent);
    transform: scale(0.95);
}

/* Toast Notification - Mobile optimized */
.pas-toast {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: var(--pas-primary);
    color: white;
    padding: 15px 25px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 10px 30px rgba(74, 124, 89, 0.4);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 2000;
    max-width: 90%;
    width: max-content;
}

.pas-toast.show {
    transform: translateX(-50%) translateY(0);
}

.pas-toast-icon {
    width: 35px;
    height: 35px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.pas-toast-content h4 {
    margin: 0 0 3px 0;
    font-size: 0.95rem;
}

.pas-toast-content p {
    margin: 0;
    font-size: 0.85rem;
    opacity: 0.9;
}

/* ============================================
   TABLET BREAKPOINT (768px and up)
   ============================================ */
@media (min-width: 768px) {
    .pas-section {
        padding: 80px 0;
    }
    
    .pas-section .container {
        padding: 0 30px;
    }
    
    .pas-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-end;
        text-align: left;
    }
    
    .pas-title {
        font-size: 2.5rem;
    }
    
    .pas-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 40px;
        align-items: start;
    }
    
    .pas-left {
        position: sticky;
        top: 30px;
        order: unset;
    }
    
    .pas-main-img-container {
        height: 350px;
    }
    
    #pasMainImg {
        max-height: 310px;
    }
    
    .pas-card {
        flex: 0 0 300px;
    }
    
    .pas-modal {
        align-items: center;
        padding: 20px;
    }
    
    .pas-modal-content {
        border-radius: var(--radius-xl);
        max-width: 800px;
        max-height: 85vh;
        transform: scale(0.9) translateY(20px);
    }
    
    .pas-modal.active .pas-modal-content {
        transform: scale(1) translateY(0);
    }
    
    .pas-modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
    
    .pas-modal-img-section {
        min-height: 400px;
    }
    
    .pas-modal-img-section img {
        max-width: 100%;
        max-height: 350px;
    }
    
    .pas-modal-info {
        padding: 40px;
    }
    
    .pas-modal-title {
        font-size: 2rem;
    }
    
    .pas-scroll-indicator {
        display: none;
    }
}

/* ============================================
   DESKTOP BREAKPOINT (1024px and up)
   ============================================ */
@media (min-width: 1024px) {
    .pas-section {
        padding: 100px 0;
    }
    
    .pas-title {
        font-size: 3rem;
    }
    
    .pas-grid {
        grid-template-columns: 380px 1fr;
        gap: 60px;
    }
    
    .pas-main-img-container {
        height: 400px;
        padding: 30px;
    }
    
    #pasMainImg {
        max-height: 340px;
    }
    
    .pas-thumbs img {
        width: 80px;
        height: 80px;
    }
    
    .pas-pills span {
        padding: 12px 25px;
        font-size: 0.9rem;
    }
    
    .pas-card {
        flex: 0 0 320px;
        scroll-snap-align: center;
    }
    
    .pas-card-img {
        height: 240px;
    }
    
    /* Hover effects only on desktop */
    .pas-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.1);
    }
    
    .pas-card-overlay {
        opacity: 0;
        transform: translateX(-50%) translateY(10px);
    }
    
    .pas-card:hover .pas-card-overlay {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    
    .pas-action-btn:hover {
        background: var(--pas-primary);
        color: var(--pas-white);
        transform: scale(1.1);
    }
    
    .pas-buy-btn:hover {
        background: var(--pas-primary);
        color: var(--pas-white);
        transform: translateY(-2px);
    }
    
    .pas-modal-content {
        max-width: 900px;
    }
    
    .pas-modal-info {
        padding: 50px;
    }
}

/* ============================================
   LARGE DESKTOP (1440px and up)
   ============================================ */
@media (min-width: 1440px) {
    .pas-grid {
        grid-template-columns: 400px 1fr;
        gap: 80px;
    }
    
    .pas-card {
        flex: 0 0 340px;
    }
}

/* Touch device optimizations */
@media (hover: none) and (pointer: coarse) {
    .pas-card-overlay {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    
    .pas-action-btn {
        width: 44px;
        height: 44px;
    }
    
    .pas-nav-btns button {
        width: 52px;
        height: 52px;
    }
}

/* Reduced motion preference */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

<script>
// Product Data Storage
let productData = {};
let currentModalQty = 1;
let currentProductId = null;
let touchStartX = 0;
let touchEndX = 0;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Thumbnail switching with touch support
    document.querySelectorAll('.pas-thumbs img').forEach(thumb => {
        thumb.addEventListener('click', function() {
            switchMainImage(this);
        });
        
        // Touch feedback
        thumb.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.95)';
        });
        thumb.addEventListener('touchend', function() {
            this.style.transform = '';
        });
    });
    
    // Store product data
    document.querySelectorAll('.pas-card').forEach(card => {
        const onclick = card.getAttribute('onclick');
        if (onclick) {
            const id = onclick.match(/\d+/)?.[0];
            if (id) {
                const name = card.querySelector('.pas-card-title')?.textContent || '';
                const priceText = card.querySelector('.pas-price')?.textContent || '';
                const price = parseInt(priceText.replace(/[^0-9]/g, '')) || 0;
                const img = card.querySelector('.pas-card-img img')?.src || '';
                const category = card.dataset.category || '';
                
                productData[id] = {
                    id: id,
                    name: name,
                    price: price,
                    image: img,
                    category: category,
                    slug: card.dataset.slug || '',
                    description: 'Premium Ayurvedic product crafted with natural ingredients for your wellness journey.'
                };
            }
        }
    });
    
    // Touch swipe for carousel
    const track = document.getElementById('pasTrack');
    if (track) {
        track.addEventListener('touchstart', handleTouchStart, {passive: true});
        track.addEventListener('touchend', handleTouchEnd, {passive: true});
    }
    
    // Modal touch handling
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.addEventListener('touchstart', function(e) {
            if (e.target === modal) closeProductModal();
        });
    }
});

// Image Switching
function switchMainImage(thumb) {
    document.querySelectorAll('.pas-thumbs img').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
    
    const mainImg = document.getElementById('pasMainImg');
    mainImg.style.opacity = '0';
    setTimeout(() => {
        mainImg.src = thumb.dataset.image;
        mainImg.style.opacity = '1';
    }, 200);
}

// Carousel Functions
let currentSlide = 0;

function moveCarousel(direction) {
    const track = document.getElementById('pasTrack');
    const viewport = track.parentElement;
    const cards = track.querySelectorAll('.pas-card:not([style*="display: none"])');
    
    if (cards.length === 0) return;
    
    const cardWidth = cards[0].offsetWidth + 20; // Including gap
    const viewportWidth = viewport.clientWidth;
    const maxScroll = Math.max(0, (cards.length * cardWidth) - viewportWidth);
    
    currentSlide += direction * cardWidth;
    currentSlide = Math.max(0, Math.min(currentSlide, maxScroll));
    
    track.style.transform = `translateX(-${currentSlide}px)`;
}

// Touch Handlers
function handleTouchStart(e) {
    touchStartX = e.changedTouches[0].screenX;
}

function handleTouchEnd(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
}

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            moveCarousel(1); // Swipe left - next
        } else {
            moveCarousel(-1); // Swipe right - prev
        }
    }
}

// Filter Products
function filterProducts(category) {
    // Update active pill
    document.querySelectorAll('.pas-pills span').forEach(pill => {
        pill.classList.remove('active');
        if(pill.dataset.filter === category) pill.classList.add('active');
    });
    
    // Filter cards with animation
    const cards = document.querySelectorAll('.pas-card');
    cards.forEach((card, index) => {
        const cardCategory = card.dataset.category || '';
        if (category === 'all' || cardCategory === category) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }, index * 50);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.style.display = 'none', 300);
        }
    });
    
    // Reset carousel position
    currentSlide = 0;
    const track = document.getElementById('pasTrack');
    if (track) track.style.transform = 'translateX(0)';
}

// Product Modal
function openProductModal(productId) {
    const product = productData[productId];
    if (!product) return;
    
    currentProductId = productId;
    currentModalQty = 1;
    
    const originalPrice = Math.round(product.price * 1.1);
    
    document.getElementById('modalImg').src = product.image;
    document.getElementById('modalCategory').textContent = product.category || 'Ayurvedic Product';
    document.getElementById('modalTitle').textContent = product.name;
    document.getElementById('modalPrice').textContent = '₹' + product.price.toLocaleString();
    document.getElementById('modalOriginalPrice').textContent = '₹' + originalPrice.toLocaleString();
    document.getElementById('modalDesc').textContent = product.description;
    document.getElementById('modalQty').textContent = currentModalQty;
    
    const modal = document.getElementById('productModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function changeModalQty(delta) {
    currentModalQty += delta;
    currentModalQty = Math.max(1, Math.min(currentModalQty, 10));
    document.getElementById('modalQty').textContent = currentModalQty;
}

function addToCartFromModal() {
    const product = productData[currentProductId];
    if (product) {
        showToast(`${product.name} (Qty: ${currentModalQty}) added to cart!`);
        closeProductModal();
    }
}

// Quick Actions
function quickView(productId) {
    openProductModal(productId);
}

function addToCart(productId) {
    const product = productData[productId];
    if (product) {
        showToast(`${product.name} added to cart!`);
        
        // AJAX call
        if (typeof fetch !== 'undefined') {
            fetch('add-to-cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({product_id: productId, quantity: 1})
            }).catch(err => console.log('Cart update:', err));
        }
    }
}

function buyNow(productId) {
    const product = productData[productId];
    if (product) {
        showToast(`Processing: ${product.name}...`);
        setTimeout(() => {
            const url = product.slug ? `/${product.slug}` : `product-detail.php?id=${productId}`;
            window.location.href = url;
        }, 600);
    }
}

function toggleWishlist() {
    const btn = document.querySelector('.pas-wishlist-btn i');
    if (btn) {
        if (btn.classList.contains('far')) {
            btn.classList.remove('far');
            btn.classList.add('fas');
            btn.style.color = '#e74c3c';
            showToast('Added to wishlist!');
        } else {
            btn.classList.remove('fas');
            btn.classList.add('far');
            btn.style.color = '';
            showToast('Removed from wishlist!');
        }
    }
}

// Toast Notification
function showToast(message) {
    const toast = document.getElementById('pasToast');
    const msgEl = document.getElementById('toastMessage');
    if (toast && msgEl) {
        msgEl.textContent = message;
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('productModal');
    if (e.target === modal) {
        closeProductModal();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeProductModal();
    if (e.key === 'ArrowLeft') moveCarousel(-1);
    if (e.key === 'ArrowRight') moveCarousel(1);
});

// Handle window resize
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        currentSlide = 0;
        const track = document.getElementById('pasTrack');
        if (track) track.style.transform = 'translateX(0)';
    }, 250);
});
</script>
    
    
    
    <!-- STEP WISE IMAGE SECTION -->
    <section class="livvra-steps">
    
      <div class="livvra-step">
        <img src="assets/images/banners/1.jpg (3).jpeg" alt="Step 01">
      </div>
    
      <div class="livvra-step">
        <img src="assets/images/banners/2 copy.jpg (3).jpeg" alt="Step 02">
      </div>
    
      <div class="livvra-step">
        <img src="assets/images/banners/3 copy.jpg (1).jpeg" alt="Step 03">
      </div>
    
    </section>
    <style>
        /* ===== LIVVRA VERTICAL STEPS ===== */
    .livvra-steps {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 0; /* no space between steps */
      background: #fff;
    }
    
    /* Each step */
    .livvra-step {
      width: 100%;
      overflow: hidden;
    }
    
    /* Images */
    .livvra-step img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: contain; /* 🔥 image poori dikhe */
    }
    
    /* Mobile safe */
    @media (max-width: 768px) {
      .livvra-step img {
        width: 100%;
        height: auto;
      }
    }
    
    </style>
     <hr>
     
     
     
     
    <section style="width:100%; padding:40px 20px; background:#f5f5f5; box-sizing:border-box; font-family:Arial, sans-serif;">

  <div style="max-width:1400px; margin:auto; display:flex; gap:25px; flex-wrap:wrap;">

    <!-- LEFT SIDE -->
    <div style="flex:1; min-width:300px; display:flex; flex-direction:column; gap:25px;">

   <!-- CARD 1 -->
<div style="position:relative; border-radius:20px; overflow:hidden; padding:40px; color:#222;
background:url('assets/products-banners/87.png') center/cover no-repeat;
animation:fadeUp 1s ease;">
        
        <span style="background:#ffd54f; padding:8px 18px; border-radius:20px; font-size:14px; font-weight:bold;">
          Enjoy 10% savings
        </span>
<br>
        <h2 style="margin:20px 0 10px; font-size:28px; font-weight:700;">
        </h2>
<br>
        <p style="margin-bottom:20px; font-size:16px; color:#555;">
         
        </p>
<br>
        <a href="https://livvra.in/product-detail.php?id=5"
           style="display:inline-flex; align-items:center; gap:10px; 
           background:#00897b; color:#fff; padding:12px 22px;
           border-radius:30px; text-decoration:none;
           font-weight:bold; transition:0.3s;">
           
           Shop Now →
        </a>
      </div>


      <!-- CARD 2 -->
   <!-- CARD 2 -->
<div style="position:relative; border-radius:20px; overflow:hidden; padding:40px; color:#222;
background:url('assets/products-banners/85.png') center/cover no-repeat;
animation:fadeUp 1.4s ease;">
        
        <span style="background:#ffd54f; padding:8px 18px; border-radius:20px; font-size:14px; font-weight:bold;">
          Enjoy 10% savings
        </span>
<br>
        <h2 style="margin:20px 0 10px; font-size:28px; font-weight:700;">
         
        </h2>
<br>
        <p style="margin-bottom:20px; font-size:16px; color:#555;">
       
        </p>
<br>
        <a href="https://livvra.in/product-detail.php?id=6"
           style="display:inline-flex; align-items:center; gap:10px; 
           background:#00897b; color:#fff; padding:12px 22px;
           border-radius:30px; text-decoration:none;
           font-weight:bold; transition:0.3s;">
           
           Shop Now →
        </a>
      </div>

    </div>


    <!-- RIGHT BIG BANNER -->
 <!-- RIGHT BIG -->
<div style="flex:2; min-width:320px; position:relative; 
border-radius:25px; overflow:hidden;
background:url('assets/products-banners/86.png') center/cover no-repeat;
padding:60px 40px;
color:white;
animation:fadeIn 1.2s ease;">
      <h1 style="font-size:60px; font-weight:900; margin:0;">
    
      </h1>
<br>
      <h3 style="margin-top:10px; font-size:22px; font-weight:600;">
      
      </h3>
<br>
      <p style="margin:20px 0; font-size:20px; letter-spacing:2px;">
     
      </p>
<br>
      <div style=" margin-top:100px; background:white; color:#2e7d32;
      padding:12px 25px; border-radius:50px;
      display:inline-block; font-weight:bold;">
        Natural Flavor
      </div>

      <a href="https://livvra.in/product-detail.php?id=23"
         style="display:inline-block;
         margin-top:25px;
         background:#fff;
         color:#ff6f00;
         padding:14px 28px;
         border-radius:30px;
         text-decoration:none;
         font-weight:bold;
         transition:0.3s;">
         
         Buy Now
      </a>

    </div>

  </div>


  <!-- Animation -->
  <style>
    @keyframes fadeUp {
      from {opacity:0; transform:translateY(40px);}
      to {opacity:1; transform:translateY(0);}
    }

    @keyframes fadeIn {
      from {opacity:0;}
      to {opacity:1;}
    }

    @media (max-width: 768px) {
      section div[style*="display:flex"] {
        flex-direction:column !important;
      }

      h1 {
        font-size:38px !important;
      }

      h2 {
        font-size:22px !important;
      }
    }

    a:hover {
      transform:scale(1.05);
    }
  </style>

</section>

<hr>
<br>

<!-- LIVVRA Unique Trust Section - BEM Methodology -->
<section class="lvv-trust-section">
    <div class="lvv-container">
        <div class="lvv-trust__grid">
            
            <!-- Left Side: Trust Content -->
            <div class="lvv-trust__content">
                <div class="lvv-trust__brand-badge">
                    <img src="assets/images/logo/logo.png" alt="LIVVRA" class="lvv-trust__logo-img">
                    <span class="lvv-trust__badge-text">India's Modern Ayurvedic Wellness Brand</span>
                </div>
                
                <h2 class="lvv-trust__headline">
                    Experience the <span class="lvv-trust__highlight">Power of Ayurveda</span> in Your Pocket
                </h2>
                
                <p class="lvv-trust__subheadline">
                    Join <strong>10+ Lakh Happy Customers</strong> who have transformed their health with LIVVRA's science-backed Ayurvedic supplements. Watch real stories, real results.
                </p>

                <!-- Trust Stats -->
                <div class="lvv-trust__stats">
                    <div class="lvv-stat__item">
                        <div class="lvv-stat__number">10K+</div>
                        <div class="lvv-stat__label">Happy Customers</div>
                    </div>
                    <div class="lvv-stat__item">
                        <div class="lvv-stat__number">10+</div>
                        <div class="lvv-stat__label">Ayurvedic Products</div>
                    </div>
                    <div class="lvv-stat__item">
                        <div class="lvv-stat__number">4.9★</div>
                        <div class="lvv-stat__label">Average Rating</div>
                    </div>
                    <div class="lvv-stat__item">
                        <div class="lvv-stat__number">100%</div>
                        <div class="lvv-stat__label">Natural & Safe</div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="lvv-trust__badges">
                    <div class="lvv-badge__item">
                        <div class="lvv-badge__icon-wrap">
                            <svg class="lvv-badge__svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <div class="lvv-badge__text">
                            <strong class="lvv-badge__title">GMP Certified</strong>
                            <span class="lvv-badge__desc">Good Manufacturing Practice</span>
                        </div>
                    </div>
                    
                    <div class="lvv-badge__item">
                        <div class="lvv-badge__icon-wrap">
                            <svg class="lvv-badge__svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                        <div class="lvv-badge__text">
                            <strong class="lvv-badge__title">100% Natural</strong>
                            <span class="lvv-badge__desc">No Chemicals or Additives</span>
                        </div>
                    </div>

                    <div class="lvv-badge__item">
                        <div class="lvv-badge__icon-wrap">
                            <svg class="lvv-badge__svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="lvv-badge__text">
                            <strong class="lvv-badge__title">FSSAI Approved</strong>
                            <span class="lvv-badge__desc">Food Safety Standards</span>
                        </div>
                    </div>

                    <div class="lvv-badge__item">
                        <div class="lvv-badge__icon-wrap">
                            <svg class="lvv-badge__svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                        </div>
                        <div class="lvv-badge__text">
                            <strong class="lvv-badge__title">Ayush Certified</strong>
                            <span class="lvv-badge__desc">Traditional Medicine Authority</span>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="lvv-trust__ctas">
                    <a href="https://www.livvra.in/products.php" class="lvv-btn lvv-btn--primary">
                        <span class="lvv-btn__text">Explore Products</span>
                        <svg class="lvv-btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="https://www.livvra.in/livvrareview.php" class="lvv-btn lvv-btn--secondary">
                        <span class="lvv-btn__text">Read Reviews</span>
                        <div class="lvv-btn__avatars">
                            <img src="https://i.pravatar.cc/150?img=11" alt="Customer" class="lvv-btn__avatar-img">
                            <img src="https://i.pravatar.cc/150?img=5" alt="Customer" class="lvv-btn__avatar-img">
                            <img src="https://i.pravatar.cc/150?img=9" alt="Customer" class="lvv-btn__avatar-img">
                            <span class="lvv-btn__avatar-count">+10k</span>
                        </div>
                    </a>
                </div>

            
            </div>

            <!-- Right Side: 3D iPhone Frame -->
            <div class="lvv-showcase">
                <div class="lvv-iphone__wrapper">
                    <!-- 3D iPhone 15 Pro Frame -->
                    <div class="lvv-iphone__frame">
                        <!-- Dynamic Island -->
                        <div class="lvv-iphone__notch">
                            <div class="lvv-iphone__camera"></div>
                            <div class="lvv-iphone__speaker"></div>
                        </div>
                        
                        <!-- Screen -->
                        <div class="lvv-iphone__screen">
                            <!-- Instagram Reels Interface -->
                            <div class="lvv-reels">
                                <!-- Reels Header -->
                                <div class="lvv-reels__header">
                                    <span class="lvv-reels__title">Reels</span>
                                    <div class="lvv-reels__actions">
                                        <button class="lvv-reels__btn">🔍</button>
                                        <button class="lvv-reels__btn">📷</button>
                                    </div>
                                </div>

                                <!-- Video Content -->
                                <div class="lvv-reels__video-wrap">
                                    <video class="lvv-reels__video" id="lvvVideo" loop muted playsinline poster="https://images.unsplash.com/photo-1544367563-12123d8965cd?w=400&h=700&fit=crop">
                                        <source src="uploads/reels/reel_699feefbd290f7.77736062.mp4" type="video/mp4">
                                    </video>
                                    
                                    <!-- Play Button Overlay -->
                                    <div class="lvv-reels__overlay" id="lvvOverlay">
                                        <button class="lvv-reels__play" onclick="lvvToggleVideo()">
                                            <svg class="lvv-reels__play-icon" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Video Info -->
                                    <div class="lvv-reels__audio">
                                        <span class="lvv-reels__audio-icon">🎵</span>
                                        <span class="lvv-reels__audio-text">Original Audio - @livvra</span>
                                    </div>
                                </div>

                                <!-- Side Actions -->
                                <div class="lvv-reels__side">
                                    <div class="lvv-reels__action">
                                        <img src="assets/images/logo/logo.png" alt="LIVVRA" class="lvv-reels__profile">
                                        <button class="lvv-reels__follow">+</button>
                                    </div>
                                    <div class="lvv-reels__action">
                                        <button class="lvv-reels__action-btn">❤️</button>
                                        <span class="lvv-reels__count">45.2K</span>
                                    </div>
                                    <div class="lvv-reels__action">
                                        <button class="lvv-reels__action-btn">💬</button>
                                        <span class="lvv-reels__count">1,234</span>
                                    </div>
                                    <div class="lvv-reels__action">
                                        <button class="lvv-reels__action-btn">📤</button>
                                    </div>
                                    <div class="lvv-reels__action">
                                        <button class="lvv-reels__action-btn">⋯</button>
                                    </div>
                                    <div class="lvv-reels__album">
                                        <div class="lvv-reels__album-cover">
                                            <img src="https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=100&h=100&fit=crop" alt="Album">
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom Info -->
                                <div class="lvv-reels__bottom">
                                    <div class="lvv-reels__user">
                                        <img src="assets/images/logo/logo.png" alt="LIVVRA" class="lvv-reels__user-img">
                                        <span class="lvv-reels__username">livvra</span>
                                        <button class="lvv-reels__follow-btn">Follow</button>
                                    </div>
                                    <p class="lvv-reels__caption">
                                        Transform your wellness journey with Ayurveda 🌿✨ #LIVVRA #Ayurveda #Wellness
                                    </p>
                                </div>

                                <!-- Navigation Bar -->
                                <div class="lvv-reels__nav">
                                    <button class="lvv-reels__nav-item">🏠</button>
                                    <button class="lvv-reels__nav-item">🔍</button>
                                    <button class="lvv-reels__nav-item lvv-reels__nav-item--active">▶️</button>
                                    <button class="lvv-reels__nav-item">🛒</button>
                                    <button class="lvv-reels__nav-item">👤</button>
                                </div>
                            </div>
                        </div>

                        <!-- Side Buttons -->
                        <div class="lvv-iphone__vol-up"></div>
                        <div class="lvv-iphone__vol-down"></div>
                        <div class="lvv-iphone__power"></div>
                    </div>

                    <!-- Shadow -->
                    <div class="lvv-iphone__shadow"></div>
                </div>

                <!-- Floating Trust Elements -->
                <div class="lvv-float lvv-float--1">
                    <span class="lvv-float__icon">⭐</span>
                    <div class="lvv-float__text">
                        <strong class="lvv-float__num">4.9/5</strong>
                        <span class="lvv-float__label">App Rating</span>
                    </div>
                </div>
                
                <div class="lvv-float lvv-float--2">
                    <span class="lvv-float__icon">🎥</span>
                    <div class="lvv-float__text">
                        <strong class="lvv-float__num">10K+</strong>
                        <span class="lvv-float__label">Reels Views</span>
                    </div>
                </div>

                <div class="lvv-float lvv-float--3">
                    <span class="lvv-float__icon">❤️</span>
                    <div class="lvv-float__text">
                        <strong class="lvv-float__num">50K+</strong>
                        <span class="lvv-float__label">Likes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ===== LIVVRA TRUST SECTION - UNIQUE BEM CLASSES ===== */
.lvv-trust-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8faf9 0%, #f0f4f3 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    overflow: hidden;
    position: relative;
    z-index: 1;
}

.lvv-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

.lvv-trust__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
    position: relative;
}

/* ===== LEFT SIDE: TRUST CONTENT ===== */
.lvv-trust__content {
    max-width: 600px;
    position: relative;
    z-index: 3;
}

.lvv-trust__brand-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: linear-gradient(135deg, #0f3d2e 0%, #1a5f4a 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 24px;
    box-shadow: 0 4px 15px rgba(15, 61, 46, 0.3);
}

.lvv-trust__logo-img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.3);
}

.lvv-trust__badge-text {
    white-space: nowrap;
}

.lvv-trust__headline {
    font-size: 3rem;
    font-weight: 800;
    color: #1a1a1a;
    line-height: 1.2;
    margin-bottom: 20px;
    letter-spacing: -0.02em;
}

.lvv-trust__highlight {
    background: linear-gradient(135deg, #0f3d2e 0%, #2d8a6e 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.lvv-trust__subheadline {
    font-size: 1.125rem;
    color: #555;
    line-height: 1.7;
    margin-bottom: 40px;
}

.lvv-trust__subheadline strong {
    color: #0f3d2e;
}

/* Trust Stats */
.lvv-trust__stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
    padding: 24px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.lvv-stat__item {
    text-align: center;
}

.lvv-stat__number {
    font-size: 1.75rem;
    font-weight: 800;
    color: #0f3d2e;
    margin-bottom: 4px;
}

.lvv-stat__label {
    font-size: 0.8rem;
    color: #666;
    font-weight: 500;
}

/* Trust Badges */
.lvv-trust__badges {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 40px;
}

.lvv-badge__item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.lvv-badge__item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #0f3d2e;
}

.lvv-badge__icon-wrap {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #f0f7f0 0%, #e0f0e8 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f3d2e;
    flex-shrink: 0;
}

.lvv-badge__svg {
    width: 24px;
    height: 24px;
}

.lvv-badge__text {
    display: flex;
    flex-direction: column;
}

.lvv-badge__title {
    font-size: 0.95rem;
    color: #1a1a1a;
    margin-bottom: 2px;
}

.lvv-badge__desc {
    font-size: 0.8rem;
    color: #666;
}

/* CTA Buttons */
.lvv-trust__ctas {
    display: flex;
    gap: 16px;
    margin-bottom: 32px;
}

.lvv-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 16px 32px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
}

.lvv-btn--primary {
    background: linear-gradient(135deg, #0f3d2e 0%, #1a5f4a 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(15, 61, 46, 0.4);
}

.lvv-btn--primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(15, 61, 46, 0.5);
}

.lvv-btn__icon {
    width: 20px;
    height: 20px;
    transition: transform 0.3s;
}

.lvv-btn--primary:hover .lvv-btn__icon {
    transform: translateX(4px);
}

.lvv-btn--secondary {
    background: white;
    color: #1a1a1a;
    border: 2px solid #e5e7eb;
}

.lvv-btn--secondary:hover {
    border-color: #0f3d2e;
    background: #f8faf9;
}

.lvv-btn__avatars {
    display: flex;
    align-items: center;
}

.lvv-btn__avatar-img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid white;
    margin-left: -8px;
    object-fit: cover;
}

.lvv-btn__avatar-img:first-child {
    margin-left: 0;
}

.lvv-btn__avatar-count {
    margin-left: 8px;
    font-size: 0.8rem;
    color: #666;
    font-weight: 500;
}

/* Trust Footer */
.lvv-trust__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}

.lvv-payment {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.85rem;
    color: #666;
}

.lvv-payment__icons {
    display: flex;
    gap: 8px;
}

.lvv-payment__icon {
    width: 32px;
    height: 32px;
    background: #f3f4f6;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.lvv-guarantee {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: #0f3d2e;
    font-weight: 600;
}

.lvv-guarantee__icon {
    font-size: 1.2rem;
}

/* ===== RIGHT SIDE: 3D iPhone Frame ===== */
.lvv-showcase {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 700px;
    z-index: 4;
}

.lvv-iphone__wrapper {
    position: relative;
    transform-style: preserve-3d;
    perspective: 1500px;
}

/* iPhone Frame */
.lvv-iphone__frame {
    width: 320px;
    height: 650px;
    background: linear-gradient(145deg, #2a2a2a 0%, #1a1a1a 100%);
    border-radius: 50px;
    padding: 12px;
    position: relative;
    box-shadow: 
        0 50px 100px -20px rgba(0, 0, 0, 0.5),
        0 30px 60px -30px rgba(0, 0, 0, 0.4),
        inset 0 0 0 2px #3a3a3a,
        inset 0 0 0 4px #1a1a1a;
    transform: rotateY(-15deg) rotateX(5deg);
    transition: transform 0.5s ease;
    animation: lvvFloat 6s ease-in-out infinite;
}

@keyframes lvvFloat {
    0%, 100% { transform: rotateY(-15deg) rotateX(5deg) translateY(0); }
    50% { transform: rotateY(-15deg) rotateX(5deg) translateY(-20px); }
}

.lvv-iphone__frame:hover {
    transform: rotateY(-10deg) rotateX(3deg) scale(1.02);
    animation-play-state: paused;
}

/* Dynamic Island */
.lvv-iphone__notch {
    position: absolute;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 90px;
    height: 28px;
    background: #000;
    border-radius: 20px;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.lvv-iphone__camera {
    width: 10px;
    height: 10px;
    background: #1a1a1a;
    border-radius: 50%;
    border: 1px solid #333;
}

.lvv-iphone__speaker {
    width: 40px;
    height: 4px;
    background: #333;
    border-radius: 2px;
}

/* iPhone Screen */
.lvv-iphone__screen {
    width: 100%;
    height: 100%;
    background: #000;
    border-radius: 40px;
    overflow: hidden;
    position: relative;
}

/* Instagram Reels Interface */
.lvv-reels {
    width: 100%;
    height: 100%;
    position: relative;
    background: #000;
    display: flex;
    flex-direction: column;
}

/* Reels Header */
.lvv-reels__header {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    padding: 50px 16px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 10;
    background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, transparent 100%);
}

.lvv-reels__title {
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
}

.lvv-reels__actions {
    display: flex;
    gap: 16px;
}

.lvv-reels__btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 4px;
}

/* Video Container */
.lvv-reels__video-wrap {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1a1a1a;
}

.lvv-reels__video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lvv-reels__overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.3);
    cursor: pointer;
    transition: opacity 0.3s;
}

.lvv-reels__overlay--hidden {
    opacity: 0;
    pointer-events: none;
}

.lvv-reels__play {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    color: white;
}

.lvv-reels__play:hover {
    transform: scale(1.1);
    background: rgba(255, 255, 255, 0.3);
}

.lvv-reels__play-icon {
    width: 32px;
    height: 32px;
    margin-left: 4px;
}

.lvv-reels__audio {
    position: absolute;
    top: 100px;
    left: 16px;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 6px;
    color: white;
    font-size: 0.8rem;
    background: rgba(0, 0, 0, 0.5);
    padding: 6px 12px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
}

/* Side Actions */
.lvv-reels__side {
    position: absolute;
    right: 8px;
    bottom: 100px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    z-index: 10;
}

.lvv-reels__action {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.lvv-reels__profile {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid white;
    object-fit: cover;
}

.lvv-reels__follow {
    width: 20px;
    height: 20px;
    background: #ff3040;
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: -10px;
    cursor: pointer;
    border: 2px solid white;
}

.lvv-reels__action-btn {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 1.3rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lvv-reels__action-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.lvv-reels__count {
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
}

.lvv-reels__album {
    margin-top: 8px;
}

.lvv-reels__album-cover {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid white;
    animation: lvvRotate 10s linear infinite;
}

.lvv-reels__album-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@keyframes lvvRotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Bottom Info */
.lvv-reels__bottom {
    position: absolute;
    bottom: 80px;
    left: 16px;
    right: 80px;
    z-index: 10;
    color: white;
}

.lvv-reels__user {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.lvv-reels__user-img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid white;
    object-fit: cover;
}

.lvv-reels__username {
    font-weight: 600;
    font-size: 0.9rem;
}

.lvv-reels__follow-btn {
    background: none;
    border: 1px solid white;
    color: white;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
}

.lvv-reels__caption {
    font-size: 0.85rem;
    line-height: 1.4;
    opacity: 0.9;
}

/* Navigation Bar */
.lvv-reels__nav {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60px;
    background: #000;
    display: flex;
    justify-content: space-around;
    align-items: center;
    border-top: 1px solid #333;
    z-index: 10;
}

.lvv-reels__nav-item {
    background: none;
    border: none;
    color: #888;
    font-size: 1.3rem;
    cursor: pointer;
    padding: 8px;
    transition: color 0.3s;
}

.lvv-reels__nav-item--active {
    color: white;
}

/* Side Buttons */
.lvv-iphone__vol-up,
.lvv-iphone__vol-down,
.lvv-iphone__power {
    position: absolute;
    width: 4px;
    background: #2a2a2a;
    border-radius: 2px;
}

.lvv-iphone__vol-up {
    left: -4px;
    top: 100px;
    height: 40px;
}

.lvv-iphone__vol-down {
    left: -4px;
    top: 150px;
    height: 40px;
}

.lvv-iphone__power {
    right: -4px;
    top: 120px;
    height: 60px;
}

/* iPhone Shadow */
.lvv-iphone__shadow {
    position: absolute;
    bottom: -50px;
    left: 50%;
    transform: translateX(-50%) rotateX(75deg);
    width: 280px;
    height: 100px;
    background: radial-gradient(ellipse at center, rgba(0,0,0,0.3) 0%, transparent 70%);
    filter: blur(20px);
    z-index: -1;
}

/* Floating Badges */
.lvv-float {
    position: absolute;
    background: white;
    padding: 12px 16px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    animation: lvvFloatBadge 3s ease-in-out infinite;
    z-index: 50;
}

@keyframes lvvFloatBadge {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.lvv-float--1 {
    top: 50px;
    left: -30px;
    animation-delay: 0s;
}

.lvv-float--2 {
    top: 200px;
    right: -20px;
    animation-delay: 0.5s;
}

.lvv-float--3 {
    bottom: 150px;
    left: -20px;
    animation-delay: 1s;
}

.lvv-float__icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f0f7f0 0%, #e0f0e8 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.lvv-float__text {
    display: flex;
    flex-direction: column;
}

.lvv-float__num {
    font-size: 1rem;
    color: #1a1a1a;
}

.lvv-float__label {
    font-size: 0.75rem;
    color: #666;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1200px) {
    .lvv-trust__grid {
        gap: 40px;
    }
    
    .lvv-trust__headline {
        font-size: 2.5rem;
    }
}

@media (max-width: 992px) {
    .lvv-trust__grid {
        grid-template-columns: 1fr;
        gap: 60px;
    }
    
    .lvv-trust__content {
        max-width: 100%;
        text-align: center;
    }
    
    .lvv-trust__stats,
    .lvv-trust__badges {
        max-width: 600px;
        margin: 0 auto 40px;
    }
    
    .lvv-trust__ctas {
        justify-content: center;
    }
    
    .lvv-trust__footer {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .lvv-showcase {
        min-height: 600px;
    }
    
    .lvv-iphone__frame {
        width: 280px;
        height: 570px;
        transform: rotateY(-10deg) rotateX(5deg);
    }
    
    .lvv-float {
        display: none;
    }
}

@media (max-width: 576px) {
    .lvv-trust-section {
        padding: 60px 0;
    }
    
    .lvv-trust__headline {
        font-size: 2rem;
    }
    
    .lvv-trust__stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        padding: 16px;
    }
    
    .lvv-stat__number {
        font-size: 1.5rem;
    }
    
    .lvv-trust__badges {
        grid-template-columns: 1fr;
    }
    
    .lvv-trust__ctas {
        flex-direction: column;
        width: 100%;
    }
    
    .lvv-btn {
        width: 100%;
        justify-content: center;
    }
    
    .lvv-iphone__frame {
        width: 260px;
        height: 530px;
        border-radius: 40px;
    }
    
    .lvv-iphone__notch {
        width: 80px;
        height: 24px;
    }
    
    .lvv-reels__header {
        padding-top: 40px;
    }
    
    .lvv-reels__side {
        right: 4px;
        gap: 12px;
    }
    
    .lvv-reels__action-btn {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
}
</style>

<script>
// LIVVRA Video Toggle Functionality
function lvvToggleVideo() {
    const video = document.getElementById('lvvVideo');
    const overlay = document.getElementById('lvvOverlay');
    
    if (video.paused) {
        video.play();
        overlay.classList.add('lvv-reels__overlay--hidden');
    } else {
        video.pause();
        overlay.classList.remove('lvv-reels__overlay--hidden');
    }
}

// Auto-play video when in view
const lvvObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        const video = entry.target.querySelector('#lvvVideo');
        const overlay = entry.target.querySelector('#lvvOverlay');
        if (entry.isIntersecting) {
            video.play().catch(e => console.log('Autoplay prevented'));
            overlay.classList.add('lvv-reels__overlay--hidden');
        } else {
            video.pause();
            overlay.classList.remove('lvv-reels__overlay--hidden');
        }
    });
}, { threshold: 0.5 });

lvvObserver.observe(document.querySelector('.lvv-reels__video-wrap'));

// 3D Tilt Effect on Mouse Move (Desktop only)
const lvvFrame = document.querySelector('.lvv-iphone__frame');
const lvvShowcase = document.querySelector('.lvv-showcase');

if (window.innerWidth > 992) {
    lvvShowcase.addEventListener('mousemove', (e) => {
        const rect = lvvShowcase.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateY = ((x - centerX) / centerX) * -10;
        const rotateX = ((y - centerY) / centerY) * 10;
        
        lvvFrame.style.transform = `rotateY(${rotateY}deg) rotateX(${rotateX}deg)`;
        lvvFrame.style.animation = 'none';
    });
    
    lvvShowcase.addEventListener('mouseleave', () => {
        lvvFrame.style.transform = 'rotateY(-15deg) rotateX(5deg)';
        lvvFrame.style.animation = 'lvvFloat 6s ease-in-out infinite';
    });
}
</script>

   <!-- ===================== TODAY BEST DEAL START ===================== -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<div class="deal-head-left">
  <h2>Today's Best Deal</h2>

  <p>
    Up to 20% discount for limited time
    <span class="deal-fire">🔥</span>
  </p>

  <div class="deal-underline"></div>
</div>


<style>
.deal-head{
  display:flex;
  justify-content:space-between;
  align-items:center;
  flex-wrap:wrap;
  gap:20px;
}

.deal-head-left{
  text-align:center;
  width:100%;
}

.deal-head-left h2{
  font-size:32px;
  font-weight:700;
  margin-bottom:8px;
  color:#1f2937;
  letter-spacing:0.3px;
  background: linear-gradient(135deg, #147a6c 0%, #d4af37 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.deal-head-left p{
  font-size:16px;
  color:#6b7280;
  display:flex;
  justify-content:center;
  align-items:center;
  gap:6px;
  font-weight:500;
}

.deal-fire{
  animation:dealFire 1s infinite alternate;
  display:inline-block;
}

.deal-underline{
  width:80px;
  height:4px;
  background: linear-gradient(90deg, #147a6c, #d4af37);
  margin:16px auto 0;
  border-radius:10px;
}

@keyframes dealFire{
  from{transform:scale(1) rotate(0deg);}
  to{transform:scale(1.2) rotate(5deg);}
}

/* Mobile */
@media(max-width:768px){
  .deal-head-left h2{
    font-size:24px;
  }

  .deal-head-left p{
    font-size:14px;
  }
  
  .deal-underline{
    width:60px;
    height:3px;
  }
}
</style>
<br>
  <div class="deal-head-right">
    <div class="deal-count">
      Ends in:
      <span class="h">00</span> :
      <span class="m">00</span> :
      <span class="s">00</span>
    </div>

    <!--<div class="deal-arrows">-->
    <!--  <div class="swiper-button-prev dealPrev"></div>-->
    <!--  <div class="swiper-button-next dealNext"></div>-->
    <!--</div>-->
  </div>
</div>

<br>


<div class="swiper dealSwiper">
<div class="swiper-wrapper">

<?php foreach ($newArrivals as $product): 
  $price = (float)$product['price'];
  $offer = (float)($product['final_price'] ?? $price);
  $discount = (int)($product['discount_percent'] ?? 0);
?>

<div class="swiper-slide">
<div class="deal-card">

<div class="deal-img">

<span class="sale-tag">SALE</span>

<img src="uploads/products/<?php echo $product['image']; ?>">

<div class="hover-box">
  <span class="compare-tip">Compare</span>
  <button onclick="addToWishlist(<?php echo $product['id']; ?>)" class="hover-btn wishlist-btn">♡</button>
  <button onclick="quickView(<?php echo $product['id']; ?>)" class="hover-btn quickview-btn">⟳</button>
  <button onclick="openProduct(<?php echo $product['id']; ?>)" class="hover-btn view-btn">👁</button>
</div>

</div>

<div class="deal-info">

<p class="cat">Livvra</p>

<h4><?php echo htmlspecialchars($product['name']); ?></h4>

<div class="price">
₹<?php echo number_format($offer,0); ?>
<span>₹<?php echo number_format($price,0); ?></span>
<b><?php echo $discount; ?>% OFF</b>
</div>

<div class="stars">★★★★★ <span>(118)</span></div>

<div class="bar-wrap">
  <div class="bar-fill"></div>
</div>

<div class="stock">
  Sold: 4
  <span>Available: 200</span>
</div>

<div class="bottom">
  <button class="wish-btn" onclick="addToWishlist(<?php echo $product['id']; ?>)">♡</button>
  <div class="btn-group">
    <button class="cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
      <span class="cart-icon">🛒</span>
      <span class="cart-text">Add to Cart</span>
    </button>
    <button class="buy-btn" onclick="buyNow(<?php echo $product['id']; ?>)">
      <span class="buy-icon">⚡</span>
      <span class="buy-text">Buy Now</span>
    </button>
  </div>
</div>

</div>
</div>
</div>

<?php endforeach; ?>

</div>
</div>

</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function(){

new Swiper(".dealSwiper", {
  slidesPerView:5,
  spaceBetween:30,
  loop:true,
  autoplay:{
    delay:3500,
    disableOnInteraction:false,
    reverseDirection: true,
    pauseOnMouseEnter: true
  },
  navigation:{nextEl:".dealNext",prevEl:".dealPrev"},
  breakpoints:{
    0:{slidesPerView:1},
    480:{slidesPerView:2},
    768:{slidesPerView:3},
    1024:{slidesPerView:4},
    1400:{slidesPerView:5}
  }
});

/* Countdown - 12 hours from now */
function timer(){
let end=new Date().getTime()+12*60*60*1000;
setInterval(function(){
let now=new Date().getTime();
let diff=end-now;
if(diff<=0){
  document.querySelector(".h").innerHTML="00";
  document.querySelector(".m").innerHTML="00";
  document.querySelector(".s").innerHTML="00";
  return;
}
document.querySelector(".h").innerHTML=
String(Math.floor(diff/3600000)).padStart(2,'0');
document.querySelector(".m").innerHTML=
String(Math.floor((diff%3600000)/60000)).padStart(2,'0');
document.querySelector(".s").innerHTML=
String(Math.floor((diff%60000)/1000)).padStart(2,'0');
},1000);
}
timer();

});
</script>

<style>
/* ===== SECTION ===== */

.deal-wrapper{
  background:#fcfcf2;
  padding:60px 20px;
}

.deal-head{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:40px;
  flex-wrap:wrap;
  gap:20px;
}

.deal-head-left{
  text-align:center;
  width:100%;
}

.deal-head-left h2{
  font-size:32px;
  font-weight:700;
  margin-bottom:8px;
  color:#1f2937;
}

.deal-head-left p{
  font-size:16px;
  color:#6b7280;
  display:flex;
  justify-content:center;
  align-items:center;
  gap:6px;
}

.deal-head-right{
  display:flex;
  align-items:center;
  gap:15px;
  flex-wrap:wrap;
  justify-content:center;
}

.deal-count{
  background: linear-gradient(135deg, #147a6c 0%, #1a9a88 100%);
  color: #fff;
  padding:10px 24px;
  border-radius:30px;
  font-weight:600;
  font-size:15px;
  box-shadow: 0 4px 15px rgba(20, 122, 108, 0.3);
  letter-spacing: 0.5px;
}

.deal-count span{
  background: rgba(255,255,255,0.2);
  padding: 2px 8px;
  border-radius: 4px;
  margin: 0 2px;
  font-family: 'Courier New', monospace;
  font-weight: 700;
}

/* ===== CARD ===== */

.deal-card{
  background:#fff;
  border-radius:20px;
  overflow:hidden;
  box-shadow:0 10px 30px rgba(0,0,0,0.05);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  height:100%;
  display:flex;
  flex-direction:column;
  border: 1px solid rgba(20, 122, 108, 0.1);
}

.deal-card:hover{
  transform:translateY(-8px);
  box-shadow:0 20px 40px rgba(20, 122, 108, 0.15);
  border-color: rgba(20, 122, 108, 0.3);
}

.deal-img{
  background:#f0f1f3;
  padding:25px;
  text-align:center;
  position:relative;
  overflow: hidden;
}

.deal-img img{
  max-height:170px;
  width:100%;
  object-fit:contain;
  transition: transform 0.4s ease;
}

.deal-card:hover .deal-img img{
  transform: scale(1.08);
}

/* ===== SALE TAG ===== */

.sale-tag{
  position:absolute;
  top:12px;
  left:12px;
  background: linear-gradient(135deg, #ff2b2b 0%, #ff6b6b 100%);
  color:#fff;
  font-size:11px;
  padding:6px 12px;
  border-radius:20px;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:0.5px;
  box-shadow: 0 4px 10px rgba(255, 43, 43, 0.3);
  z-index: 2;
}

/* ===== HOVER ICONS ===== */

.hover-box{
  position:absolute;
  bottom:15px;
  left:50%;
  transform:translateX(-50%);
  display:flex;
  gap:12px;
  opacity:0;
  transition: all 0.3s ease;
  z-index: 3;
}

.deal-img:hover .hover-box{
  opacity:1;
  bottom: 20px;
}

.hover-btn{
  width:40px;
  height:40px;
  border-radius:50%;
  border:none;
  background:#fff;
  cursor:pointer;
  box-shadow:0 4px 15px rgba(0,0,0,0.15);
  font-size:16px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.hover-btn:hover{
  transform: translateY(-3px) scale(1.1);
  box-shadow:0 6px 20px rgba(20, 122, 108, 0.3);
}

.wishlist-btn:hover{
  background: #ff2b2b;
  color: white;
}

.quickview-btn:hover{
  background: #147a6c;
  color: white;
}

.view-btn:hover{
  background: #d4af37;
  color: white;
}

.compare-tip{
  position:absolute;
  top:-35px;
  left:50%;
  transform:translateX(-50%);
  background:#222;
  color:#fff;
  font-size:11px;
  padding:4px 10px;
  border-radius:4px;
  white-space: nowrap;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.hover-box:hover .compare-tip{
  opacity: 1;
}

/* ===== CONTENT ===== */

.deal-info{
  padding:20px;
  display:flex;
  flex-direction:column;
  flex:1;
}

.cat{
  font-size:12px;
  color:#147a6c;
  margin-bottom:6px;
  font-weight:600;
  text-transform:uppercase;
  letter-spacing:1px;
}

.deal-info h4{
  font-size:16px;
  font-weight:600;
  margin-bottom:10px;
  min-height:42px;
  color: #1f2937;
  line-height: 1.4;
}

.price{
  font-size:18px;
  font-weight:700;
  color: #147a6c;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.price span{
  text-decoration:line-through;
  color:#999;
  font-weight:400;
  font-size:14px;
}

.price b{
  color:#ff2b2b;
  font-size:12px;
  background: rgba(255, 43, 43, 0.1);
  padding: 2px 8px;
  border-radius: 12px;
}

.stars{
  color:#f5b301;
  margin:10px 0;
  font-size:14px;
  font-weight:600;
}

.stars span{
  color: #888;
  font-weight: 400;
  margin-left: 5px;
}

.bar-wrap{
  height:6px;
  background:#eee;
  border-radius:6px;
  margin:10px 0;
  overflow: hidden;
}

.bar-fill{
  width:60%;
  height:6px;
  background: linear-gradient(90deg, #f5b301, #d4af37);
  border-radius:6px;
  position: relative;
  overflow: hidden;
}

.bar-fill::after{
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  animation: shimmer 2s infinite;
}

@keyframes shimmer{
  0%{left: -100%;}
  100%{left: 100%;}
}

.stock{
  font-size:12px;
  margin-bottom:15px;
  display:flex;
  justify-content:space-between;
  color: #666;
  font-weight: 500;
}

.stock span{
  color: #147a6c;
}

/* ===== BUTTONS ===== */

.bottom{
  display:flex;
  gap:10px;
  margin-top:auto;
  align-items: stretch;
}

.wish-btn{
  width:45px;
  height:45px;
  border-radius:50%;
  border:2px solid #e0e0e0;
  background:#fff;
  cursor:pointer;
  flex-shrink:0;
  font-size:18px;
  color: #666;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.wish-btn:hover{
  border-color: #ff2b2b;
  color: #ff2b2b;
  transform: scale(1.1);
  box-shadow: 0 4px 12px rgba(255, 43, 43, 0.2);
}

.wish-btn.active{
  background: #ff2b2b;
  color: white;
  border-color: #ff2b2b;
}

.btn-group{
  flex:1;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.cart-btn{
  flex:1;
  background: linear-gradient(135deg, #147a6c 0%, #1a9a88 100%);
  color:#fff;
  border:none;
  border-radius:25px;
  font-weight:600;
  cursor:pointer;
  font-size:14px;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(20, 122, 108, 0.3);
}

.cart-btn::before{
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  transition: left 0.5s ease;
}

.cart-btn:hover{
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(20, 122, 108, 0.4);
}

.cart-btn:hover::before{
  left: 100%;
}

.cart-btn:active{
  transform: translateY(0);
}

.buy-btn{
  flex:1;
  background: linear-gradient(135deg, #d4af37 0%, #f5b301 100%);
  color:#1f2937;
  border:none;
  border-radius:25px;
  font-weight:700;
  cursor:pointer;
  font-size:14px;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
}

.buy-btn::before{
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  transition: left 0.5s ease;
}

.buy-btn:hover{
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

.buy-btn:hover::before{
  left: 100%;
}

.buy-btn:active{
  transform: translateY(0);
}

.cart-icon, .buy-icon{
  font-size: 16px;
  transition: transform 0.3s ease;
}

.cart-btn:hover .cart-icon{
  transform: rotate(-10deg) scale(1.2);
}

.buy-btn:hover .buy-icon{
  transform: scale(1.2);
}

/* ===== ARROWS ===== */

.deal-arrows .swiper-button-next,
.deal-arrows .swiper-button-prev{
  width:40px;
  height:40px;
  background:#e9edf2;
  border-radius:50%;
  color:#333;
  transition: all 0.3s ease;
}

.deal-arrows .swiper-button-next:hover,
.deal-arrows .swiper-button-prev:hover{
  background: #147a6c;
  color: white;
  transform: scale(1.1);
}

/* ===== RESPONSIVE ===== */

@media (max-width:1024px){
  .deal-head{
    flex-direction:column;
    align-items:center;
    text-align:center;
  }

  .deal-head-right{
    justify-content:center;
  }
}

@media (max-width:768px){

  .deal-wrapper{
    padding:40px 15px;
  }

  .deal-info h4{
    font-size:15px;
  }

  .price{
    font-size:16px;
  }

  .bottom{
    flex-direction:column;
  }

  .wish-btn{
    width:100%;
    border-radius:25px;
    height:45px;
  }

  .btn-group{
    width: 100%;
  }

  .cart-btn, .buy-btn{
    width:100%;
    height:45px;
  }

}

@media (max-width:480px){

  .deal-head-left h2{
    font-size:22px;
  }

  .deal-head-left p{
    font-size:13px;
  }

  .deal-count{
    font-size:12px;
    padding:8px 16px;
  }

  .deal-img{
    padding: 15px;
  }

  .deal-info{
    padding: 15px;
  }

}
</style>

<!-- ===================== TODAY BEST DEAL END ===================== -->
  
  
  
  <!-- Google Reviews Infinite Marquee Section -->
<section class="reviews-marquee-section">
    <div class="container">
        <!-- Header -->
        <div class="reviews-header">
            <h2>Over <b>10 </b>Lakh happy customers and counting</h2>
        </div>

        <div class="marquee-wrapper">
            <!-- Google Badge (Left Side) -->
            <div class="google-badge">
                <div class="google-logo">
                    <span class="g-blue">G</span><span class="g-red">o</span><span class="g-yellow">o</span><span class="g-blue">g</span><span class="g-green">l</span><span class="g-red">e</span>
                </div>
                <div class="rating-summary">
                    <p>Based on 493 Reviews</p>
                    <div class="stars">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star half">★</span>
                    </div>
                </div>
            </div>

            <!-- Infinite Marquee Container -->
            <div class="marquee-container">
                <div class="marquee-track" id="marqueeTrack">
                    <!-- Original Set of 15 Reviews -->
                    <!-- Review 1 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=11" alt="Deepak Trivedi" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Deepak Trivedi <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 11, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Using Rasayanam supplements for the past few weeks. So far the experience has been good and the results are visible. Highly recommended!</p>
                    </div>

                    <!-- Review 2 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=5" alt="Achala Gupta" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Achala Gupta <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 11, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">I have been using Rasayanam's D3K2 and Iron from past two months and have observed quite significant improvement in my energy levels.</p>
                    </div>

                    <!-- Review 3 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=9" alt="Aditi Goyal" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Aditi Goyal <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 10, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Great Products for General Wellness. The quality is amazing and delivery was super fast. Will definitely order again!</p>
                    </div>

                    <!-- Review 4 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=3" alt="Balneet Bedi" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Balneet Bedi <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 19, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★<span class="empty-star">★</span></div>
                        <p class="review-text">Good products - just looking for quicker customer service response. Overall quality is excellent though.</p>
                    </div>

                    <!-- Review 5 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=12" alt="Rahul Sharma" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Rahul Sharma <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 8, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Excellent quality Ayurvedic products. Highly recommended for everyone looking for natural health solutions and wellness.</p>
                    </div>

                    <!-- Review 6 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=8" alt="Priya Patel" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Priya Patel <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 5, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Amazing results! My energy levels have improved significantly after using these supplements for just one month.</p>
                    </div>

                    <!-- Review 7 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=15" alt="Vikram Singh" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Vikram Singh <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 3, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Best Ayurvedic brand in India. Authentic herbs and visible results. My whole family is using their products now.</p>
                    </div>

                    <!-- Review 8 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=20" alt="Neha Gupta" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Neha Gupta <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 28, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Love the purity of these products! No chemicals, no side effects. Just pure Ayurvedic goodness. Thank you Rasayanam!</p>
                    </div>

                    <!-- Review 9 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=33" alt="Arjun Kumar" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Arjun Kumar <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 25, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Finally found a brand that delivers what it promises. My digestion has improved and I feel more active throughout the day.</p>
                    </div>

                    <!-- Review 10 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=44" alt="Sneha Reddy" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Sneha Reddy <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 22, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">The Shilajit resin is pure gold! Best quality I've ever used. Fast shipping and great packaging too.</p>
                    </div>

                    <!-- Review 11 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=52" alt="Mohit Agarwal" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Mohit Agarwal <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 20, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Using their Ashwagandha for 3 months now. Stress levels are down and sleep quality has improved dramatically.</p>
                    </div>

                    <!-- Review 12 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=59" alt="Ananya Desai" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Ananya Desai <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 18, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">As a yoga instructor, I recommend Rasayanam to all my students. Authentic, effective and reasonably priced.</p>
                    </div>

                    <!-- Review 13 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=68" alt="Rajesh Khanna" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Rajesh Khanna <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 15, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★<span class="empty-star">★</span></div>
                        <p class="review-text">Good products but delivery took 5 days. Quality is excellent though. Will order again for sure.</p>
                    </div>

                    <!-- Review 14 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=73" alt="Kavita Mishra" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Kavita Mishra <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 12, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Their immunity booster saved my family during flu season. No one fell sick! Truly magical Ayurvedic formulation.</p>
                    </div>

                    <!-- Review 15 -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=81" alt="Suresh Iyer" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Suresh Iyer <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 10, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Been using Ayurvedic products for 20 years. Rasayanam is by far the most authentic brand I've come across.</p>
                    </div>

                    <!-- DUPLICATE SET for Seamless Loop -->
                    <!-- Review 1 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=11" alt="Deepak Trivedi" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Deepak Trivedi <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 11, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Using Rasayanam supplements for the past few weeks. So far the experience has been good and the results are visible. Highly recommended!</p>
                    </div>

                    <!-- Review 2 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=5" alt="Achala Gupta" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Achala Gupta <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 11, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">I have been using Rasayanam's D3K2 and Iron from past two months and have observed quite significant improvement in my energy levels.</p>
                    </div>

                    <!-- Review 3 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=9" alt="Aditi Goyal" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Aditi Goyal <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 10, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Great Products for General Wellness. The quality is amazing and delivery was super fast. Will definitely order again!</p>
                    </div>

                    <!-- Review 4 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=3" alt="Balneet Bedi" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Balneet Bedi <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 19, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★<span class="empty-star">★</span></div>
                        <p class="review-text">Good products - just looking for quicker customer service response. Overall quality is excellent though.</p>
                    </div>

                    <!-- Review 5 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=12" alt="Rahul Sharma" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Rahul Sharma <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 8, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Excellent quality Ayurvedic products. Highly recommended for everyone looking for natural health solutions and wellness.</p>
                    </div>

                    <!-- Review 6 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=8" alt="Priya Patel" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Priya Patel <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 5, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Amazing results! My energy levels have improved significantly after using these supplements for just one month.</p>
                    </div>

                    <!-- Review 7 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=15" alt="Vikram Singh" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Vikram Singh <span class="verified">✓</span></h4>
                                <span class="review-date">Mar 3, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Best Ayurvedic brand in India. Authentic herbs and visible results. My whole family is using their products now.</p>
                    </div>

                    <!-- Review 8 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=20" alt="Neha Gupta" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Neha Gupta <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 28, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Love the purity of these products! No chemicals, no side effects. Just pure Ayurvedic goodness. Thank you Rasayanam!</p>
                    </div>

                    <!-- Review 9 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=33" alt="Arjun Kumar" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Arjun Kumar <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 25, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Finally found a brand that delivers what it promises. My digestion has improved and I feel more active throughout the day.</p>
                    </div>

                    <!-- Review 10 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=44" alt="Sneha Reddy" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Sneha Reddy <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 22, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">The Shilajit resin is pure gold! Best quality I've ever used. Fast shipping and great packaging too.</p>
                    </div>

                    <!-- Review 11 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=52" alt="Mohit Agarwal" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Mohit Agarwal <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 20, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Using their Ashwagandha for 3 months now. Stress levels are down and sleep quality has improved dramatically.</p>
                    </div>

                    <!-- Review 12 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=59" alt="Ananya Desai" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Ananya Desai <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 18, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">As a yoga instructor, I recommend Rasayanam to all my students. Authentic, effective and reasonably priced.</p>
                    </div>

                    <!-- Review 13 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=68" alt="Rajesh Khanna" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Rajesh Khanna <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 15, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★<span class="empty-star">★</span></div>
                        <p class="review-text">Good products but delivery took 5 days. Quality is excellent though. Will order again for sure.</p>
                    </div>

                    <!-- Review 14 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=73" alt="Kavita Mishra" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Kavita Mishra <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 12, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Their immunity booster saved my family during flu season. No one fell sick! Truly magical Ayurvedic formulation.</p>
                    </div>

                    <!-- Review 15 Copy -->
                    <div class="review-card">
                        <div class="reviewer-header">
                            <img src="https://i.pravatar.cc/150?img=81" alt="Suresh Iyer" class="reviewer-img">
                            <div class="reviewer-info">
                                <h4>Suresh Iyer <span class="verified">✓</span></h4>
                                <span class="review-date">Feb 10, 2026</span>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Been using Ayurvedic products for 20 years. Rasayanam is by far the most authentic brand I've come across.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ===== GOOGLE REVIEWS INFINITE MARQUEE ===== */
.reviews-marquee-section {
    background-color: #f5f0d8;
    padding: 60px 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    overflow: hidden;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header */
.reviews-header {
    text-align: center;
    margin-bottom: 50px;
}

.reviews-header h2 {
    font-size: 2.5rem;
    color: #2c2419;
    font-weight: 600;
    margin: 0;
}

.highlight {
    font-size: 3.5rem;
    font-weight: 700;
    color: #2c2419;
    position: relative;
    display: inline-block;
    line-height: 1;
}

.highlight::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -5px;
    right: -5px;
    height: 2px;
    background: #2c2419;
    transform: translateY(-50%);
}

.highlight::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: -5px;
    right: -5px;
    height: 2px;
    background: #2c2419;
}

/* Marquee Layout */
.marquee-wrapper {
    display: flex;
    align-items: center;
    gap: 40px;
    position: relative;
}

/* Google Badge */
.google-badge {
    flex-shrink: 0;
    text-align: center;
    padding: 20px;
    min-width: 200px;
}

.google-logo {
    font-size: 3rem;
    font-weight: 600;
    font-family: 'Product Sans', Arial, sans-serif;
    margin-bottom: 15px;
    letter-spacing: -2px;
}

.g-blue { color: #4285F4; }
.g-red { color: #EA4335; }
.g-yellow { color: #FBBC05; }
.g-green { color: #34A853; }

.rating-summary p {
    color: #5a5248;
    font-size: 0.9rem;
    margin: 0 0 8px 0;
    font-weight: 500;
}

.rating-summary .stars {
    font-size: 1.2rem;
    letter-spacing: 2px;
}

.star.filled {
    color: #fbbc05;
}

.star.half {
    background: linear-gradient(90deg, #fbbc05 50%, #ccc 50%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Marquee Container */
.marquee-container {
    flex: 1;
    overflow: hidden;
    position: relative;
    mask-image: linear-gradient(to right, transparent 0%, white 5%, white 95%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0%, white 5%, white 95%, transparent 100%);
}

/* Marquee Track - GPU Accelerated */
.marquee-track {
    display: flex;
    gap: 30px;
    width: max-content;
    /* Slow infinite animation - 60 seconds for full cycle */
    animation: scroll 60s linear infinite;
    /* GPU Acceleration for smooth performance */
    transform: translate3d(0, 0, 0);
    will-change: transform;
    backface-visibility: hidden;
}

/* Pause on hover */
.marquee-container:hover .marquee-track {
    animation-play-state: paused;
}

@keyframes scroll {
    0% {
        transform: translate3d(0, 0, 0);
    }
    100% {
        transform: translate3d(-50%, 0, 0);
    }
}

/* Review Cards */
.review-card {
    background: rgba(255, 255, 255, 0.6);
    border-radius: 16px;
    padding: 24px;
    min-width: 320px;
    max-width: 320px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.review-card:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.reviewer-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.reviewer-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.reviewer-info h4 {
    margin: 0;
    font-size: 0.95rem;
    color: #2c2419;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.verified {
    color: #1a73e8;
    font-size: 0.8rem;
    background: #e8f0fe;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.review-date {
    font-size: 0.8rem;
    color: #7a7268;
    display: block;
    margin-top: 2px;
}

.review-stars {
    color: #fbbc05;
    font-size: 1rem;
    margin-bottom: 12px;
    letter-spacing: 1px;
}

.empty-star {
    color: #ccc;
}

.review-text {
    color: #5a5248;
    font-size: 0.9rem;
    line-height: 1.6;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1200px) {
    .marquee-wrapper {
        flex-direction: column;
        gap: 30px;
    }

    .google-badge {
        display: flex;
        align-items: center;
        gap: 20px;
        text-align: left;
        min-width: auto;
    }

    .review-card {
        min-width: 300px;
        max-width: 300px;
    }
}

@media (max-width: 768px) {
    .reviews-marquee-section {
        padding: 40px 0;
    }

    .reviews-header h2 {
        font-size: 1.5rem;
    }

    .highlight {
        font-size: 2.2rem;
    }

    .google-logo {
        font-size: 2rem;
    }

    .review-card {
        min-width: 280px;
        max-width: 280px;
        padding: 20px;
    }

    .marquee-track {
        animation-duration: 40s; /* Faster on mobile */
    }
}

@media (max-width: 480px) {
    .review-card {
        min-width: 260px;
        max-width: 260px;
        padding: 16px;
    }

    .reviewer-img {
        width: 40px;
        height: 40px;
    }

    .review-text {
        font-size: 0.85rem;
    }
}

/* Reduced motion preference */
@media (prefers-reduced-motion: reduce) {
    .marquee-track {
        animation: none;
    }
}
</style>

<script>
// Optional: Dynamic speed adjustment based on screen width
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('marqueeTrack');
    const isMobile = window.innerWidth <= 768;
    
    // Adjust animation speed for mobile
    if (isMobile) {
        track.style.animationDuration = '40s';
    }
    
    // Handle visibility change to save resources
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            track.style.animationPlayState = 'paused';
        } else {
            track.style.animationPlayState = 'running';
        }
    });
});
</script>



<!-- LIVVRA Premium FAQ Section -->
<section class="livvra-faq-section" style="padding: 100px 20px; background: linear-gradient(180deg, #f8fdf9 0%, #ffffff 50%, #f0f7f2 100%); position: relative; overflow: hidden;">
  
  <!-- Decorative Background Elements -->
  <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(74,124,89,0.08) 0%, transparent 70%); border-radius: 50%;"></div>
  <div style="position: absolute; bottom: -150px; left: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(126,200,160,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
  
  <div class="livvra-faq-container" style="max-width: 1000px; margin: 0 auto; position: relative; z-index: 1;">
    
    <!-- Header Section -->
    <div class="livvra-faq-header" style="text-align: center; margin-bottom: 60px;">
      
      <!-- Badge -->
      <div style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(74,124,89,0.3);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
          <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        <span style="color: white; font-size: 13px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase;">Got Questions?</span>
      </div>

      <!-- Title -->
      <h2 style="font-size: 42px; font-weight: 800; margin: 0 0 15px; color: #1f3d2b; line-height: 1.2; font-family: 'Segoe UI', system-ui, sans-serif;">
        Frequently Asked <span style="background: linear-gradient(135deg, #4A7C59, #7ec8a0); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Questions</span>
      </h2>

      <p style="color: #5a6b5f; font-size: 17px; max-width: 550px; margin: 0 auto; line-height: 1.7; font-weight: 400;">
        Everything you need to know about LIVVRA products. Can't find what you're looking for? <a href="#" style="color: #4A7C59; text-decoration: none; font-weight: 600; border-bottom: 2px solid #4A7C59;">Contact us</a>
      </p>
    </div>

    <!-- FAQ Grid -->
    <div class="livvra-faq-grid" style="display: grid; gap: 16px;">
      
      <!-- FAQ Item 1 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">Are LIVVRA products 100% natural and organic?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Yes, LIVVRA products are made from pure, natural herbal ingredients sourced from organic farms across India. We ensure no harmful chemicals, preservatives, or artificial additives are used in our formulations.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 2 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">How long does it take to see results?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Most customers notice improvements within 2–4 weeks of regular usage. However, individual results may vary based on body type, lifestyle, and consistency of use.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 3 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">Are there any side effects?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              LIVVRA products use safe Ayurvedic formulations and are generally well tolerated. Our ingredients are carefully selected and tested to ensure maximum safety.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 4 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">Can I take LIVVRA products daily?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Yes, our products are designed for daily consumption as part of a healthy routine. Follow the recommended dosage mentioned on the packaging for best results.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 5 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">Is LIVVRA suitable for all age groups?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Most products are suitable for adults. Please consult a doctor for children or elderly individuals before starting any new supplement regimen.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 6 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">Are your products clinically tested?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Yes, all products go through rigorous quality and safety testing before market release. We adhere to GMP standards and conduct third-party lab testing.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 7 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">Can pregnant women use LIVVRA products?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Pregnant women should consult a healthcare professional before use. While our products are natural, it's always best to seek medical advice during pregnancy.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 8 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">Do you offer cash on delivery?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Yes, we offer COD and multiple secure online payment options including UPI, Credit/Debit cards, Net Banking, and Wallets.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 9 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">How long does delivery take?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Delivery usually takes 3–7 working days across India. Metro cities typically receive orders within 3-4 days, while remote areas may take up to 7 days.
            </p>
          </div>
        </div>
      </div>

      <!-- FAQ Item 10 -->
      <div class="livvra-faq-item" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(74,124,89,0.1); overflow: hidden; transition: all 0.3s ease;">
        <div class="livvra-faq-question" onclick="toggleLivvraFAQ(this)" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 28px; cursor: pointer; background: linear-gradient(90deg, #ffffff, #f8fdf9); transition: all 0.3s ease;">
          <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
              </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f3d2b; line-height: 1.4;">Can I return or replace products?</h3>
          </div>
          <div class="livvra-faq-icon" style="width: 28px; height: 28px; background: linear-gradient(135deg, #4A7C59, #7ec8a0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.4s ease;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
        </div>
        <div class="livvra-faq-answer" style="max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="padding: 0 28px 24px 79px;">
            <p style="margin: 0; color: #5a6b5f; line-height: 1.8; font-size: 15px;">
              Yes, we offer easy return & replacement within 7 days of delivery. Products must be unused and in original packaging for returns to be processed.
            </p>
          </div>
        </div>
      </div>

    </div>

    <!-- Bottom CTA -->
    <div style="text-align: center; margin-top: 50px; padding: 40px; background: linear-gradient(135deg, #1f3d2b, #2d5a40); border-radius: 20px; position: relative; overflow: hidden;">
      <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
      <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
      
      <h3 style="color: white; font-size: 24px; margin: 0 0 10px; font-weight: 700; position: relative; z-index: 1;">Still have questions?</h3>
      <p style="color: rgba(255,255,255,0.8); margin: 0 0 25px; font-size: 15px; position: relative; z-index: 1;">Our team is here to help you 24/7</p>
      
      <a href="contact.php" style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 32px; background: white; color: #1f3d2b; text-decoration: none; border-radius: 50px; font-weight: 600; font-size: 15px; transition: all 0.3s ease; position: relative; z-index: 1; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
          <polyline points="22,6 12,13 2,6"></polyline>
        </svg>
        Contact Support
      </a>
    </div>

  </div>
</section>

<script>
function toggleLivvraFAQ(element) {
  const item = element.parentElement;
  const answer = item.querySelector('.livvra-faq-answer');
  const icon = item.querySelector('.livvra-faq-icon svg');
  const isActive = item.classList.contains('livvra-active');

  // Close all other items
  document.querySelectorAll('.livvra-faq-item').forEach(i => {
    i.classList.remove('livvra-active');
    i.querySelector('.livvra-faq-answer').style.maxHeight = '0';
    i.querySelector('.livvra-faq-icon svg').style.transform = 'rotate(0deg)';
    i.style.boxShadow = '0 4px 20px rgba(0,0,0,0.04)';
  });

  // Toggle current item
  if (!isActive) {
    item.classList.add('livvra-active');
    answer.style.maxHeight = answer.scrollHeight + 'px';
    icon.style.transform = 'rotate(45deg)';
    item.style.boxShadow = '0 8px 30px rgba(74,124,89,0.15)';
  }
}
</script>

<style>
.livvra-faq-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}

.livvra-faq-question:hover {
  background: linear-gradient(90deg, #f0f7f2, #e8f5e9) !important;
}

@media (max-width: 768px) {
  .livvra-faq-section {
    padding: 60px 15px !important;
  }
  
  .livvra-faq-header h2 {
    font-size: 28px !important;
  }
  
  .livvra-faq-question {
    padding: 18px 20px !important;
  }
  
  .livvra-faq-answer > div {
    padding: 0 20px 20px 71px !important;
  }
  
  .livvra-faq-question h3 {
    font-size: 14px !important;
  }
}
</style>
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlock Offers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .unlock2-section {
            position: relative;
            width: 100%;
            min-height: 520px;
            background: url("assets/images/offer%20banner.jpg") center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .unlock2-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.45);
        }

        .unlock2-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 700px;
            text-align: center;
            padding: 40px 20px;
        }

        .unlock2-title {
            font-size: 34px;
            font-weight: 500;
            line-height: 1.2;
            margin-bottom: 20px;
            text-transform: lowercase;
        }

        .unlock2-form {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .unlock2-form input {
            flex: 1;
            height: 50px;
            border-radius: 6px;
            border: none;
            padding: 0 15px;
            font-size: 15px;
            outline: none;
            color: #333; /* Dark text for visibility */
            font-weight: 500;
        }

        .unlock2-form input::placeholder {
            color: #999;
        }

        .unlock2-form input:focus {
            box-shadow: 0 0 0 3px rgba(188, 222, 208, 0.5);
        }

        .unlock2-form button {
            width: 52px;
            height: 50px;
            border: none;
            border-radius: 6px;
            background: #bcded0;
            font-size: 26px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .unlock2-form button:hover {
            background: #a8d4c3;
            transform: translateX(2px);
        }

        .unlock2-form button:active {
            transform: scale(0.95);
        }

        .unlock2-mail-title {
            font-size: 28px;
            font-weight: 500;
            margin: 10px 0 14px;
        }

        .unlock2-links {
            margin-top: 30px;
            text-align: left;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .unlock2-links a {
            display: block;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.4);
            font-size: 14px;
            letter-spacing: 1px;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .unlock2-links a:hover {
            color: #bcded0;
            padding-left: 10px;
            border-bottom-color: #bcded0;
        }

        .unlock2-social {
            margin-top: 20px;
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .unlock2-social a {
            color: #fff;
            font-size: 22px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .unlock2-social a:hover {
            color: #bcded0;
            transform: translateY(-3px);
            background: rgba(255,255,255,0.1);
        }

        .form-message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
            display: none;
        }

        .form-message.success {
            background: rgba(188, 222, 208, 0.9);
            color: #2c5f4e;
            display: block;
        }

        .form-message.error {
            background: rgba(255, 107, 107, 0.9);
            color: #fff;
            display: block;
        }

        @media(max-width:768px){
            .unlock2-title { font-size: 26px; }
            .unlock2-mail-title { font-size: 22px; }
            .unlock2-form { flex-direction: column; }
            .unlock2-form button { width: 100%; }
        }
    </style>
</head>
<body>

<section class="unlock2-section">
    <div class="unlock2-overlay"></div>

    <div class="unlock2-container">

        <h2 class="unlock2-title">
            unlock offers & <br>
            subscribe for content
        </h2>

        <!-- Phone Form with WhatsApp Integration -->
        <form class="unlock2-form" id="phoneForm" onsubmit="sendToWhatsApp(event)">
            <input type="tel" id="phoneInput" placeholder="Enter Your Phone Number" required pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            <button type="submit">→</button>
        </form>
        <div id="phoneMessage" class="form-message"></div>

        <h3 class="unlock2-mail-title">JOIN OUR MAILING LIST</h3>

        <form class="unlock2-form unlock2-form-email" id="emailForm" onsubmit="handleEmailSubmit(event)">
            <input type="email" id="emailInput" placeholder="Enter Email" required>
            <button type="submit">→</button>
        </form>
        <div id="emailMessage" class="form-message"></div>

        <div class="unlock2-links">
            <a href="products.php">SHOP ALL</a>
            <a href="about.php">ABOUT</a>
            <a href="contact.php">OTHER LINKS</a>
        </div>

        <div class="unlock2-social">
            <a href="https://www.instagram.com/livvra.india/" target="_blank" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.facebook.com/profile.php?id=61552761808638" target="_blank" aria-label="Facebook">
                <i class="fab fa-facebook"></i>
            </a>
            <a href="https://youtube.com/shorts/utlejtcyFEI?si=YJQBeS8qFfT9x0h4" class="social-icon-link">
                <i class="fab fa-youtube"></i>
            </a>
         
        </div>

    </div>
</section>






<script>
    // WhatsApp Number
    const WHATSAPP_NUMBER = "918958489684"; // 91 is India code + your number
    
    function sendToWhatsApp(event) {
        event.preventDefault();
        const phone = document.getElementById('phoneInput').value;
        const messageDiv = document.getElementById('phoneMessage');
        
        // Validation - exactly 10 digits
        if (phone.length === 10 && /^\d{10}$/.test(phone)) {
            
            // Create WhatsApp message
            const text = `Hello! New Offer Unlock Request:%0A%0APhone Number: ${phone}%0A%0ATimestamp: ${new Date().toLocaleString()}`;
            
            // Open WhatsApp with pre-filled message
            const whatsappURL = `https://wa.me/${WHATSAPP_NUMBER}?text=${text}`;
            
            // Show success message
            messageDiv.textContent = 'Redirecting to WhatsApp...';
            messageDiv.className = 'form-message success';
            
            // Open WhatsApp in new tab
            window.open(whatsappURL, '_blank');
            
            // Clear input
            document.getElementById('phoneInput').value = '';
            
        } else {
            messageDiv.textContent = 'Please enter valid 10 digit mobile number';
            messageDiv.className = 'form-message error';
        }
        
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }

    function handleEmailSubmit(event) {
        event.preventDefault();
        const email = document.getElementById('emailInput').value;
        const messageDiv = document.getElementById('emailMessage');
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (emailRegex.test(email)) {
            messageDiv.textContent = 'Successfully subscribed to mailing list!';
            messageDiv.className = 'form-message success';
            document.getElementById('emailInput').value = '';
            console.log('Email submitted:', email);
        } else {
            messageDiv.textContent = 'Please enter a valid email address.';
            messageDiv.className = 'form-message error';
        }
        
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }
</script>






</body>
</html>
    <?php require_once __DIR__ . '/includes/footer.php'; ?>