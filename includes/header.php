<?php
// Define constants if not defined elsewhere
if (!defined('SITE_NAME')) define('SITE_NAME', 'LIVVRA');
if (!defined('SITE_TAGLINE')) define('SITE_TAGLINE', 'Modern Ayurveda');
// SEO defaults — pages can override these before require'ing this file
if (!isset($pageTitle))       $pageTitle       = 'Livvra | Ayurvedic Wellness, Herbal Health & Beauty Products';
if (!isset($metaDescription)) $metaDescription = 'Buy the best ayurvedic products online in India. Shop our premium range of natural health supplements, pure shilajit & organic skin care. Order yours today!';
if (!isset($metaKeywords))    $metaKeywords    = 'ayurvedic products india, herbal wellness products, natural health supplements, ayurvedic oils online, organic supplements';
if (!isset($canonicalUrl))    $canonicalUrl    = 'https://livvra.in';
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1265862368533598');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1265862368533598&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    
    <meta name="google-site-verification" content="nmqg3fDFRuo0XfuEyGu3WervckvnsBO5gPM-77UxwO8" />

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FYB3NPJ83C"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FYB3NPJ83C');
</script>
    
    
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($metaKeywords); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl); ?>">
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Livvra">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.cdnfonts.com/css/geometr-415" rel="stylesheet">
    <!-- Font Awesome -->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    
    <meta name="google-site-verification" content="ARNeVNFf1ApnIIgXTBiKLyrnqU8JKGpusJ3i3MqbnHY" />
    
    
    

    
    
    
    
    
   <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5GSLXP4C');</script>
<!-- End Google Tag Manager -->
    
    <script>(function(w, d) { w.CollectId = "696b333ff95d00686a32fba4"; var h = d.head || d.getElementsByTagName("head")[0]; var s = d.createElement("script"); s.setAttribute("type", "text/javascript"); s.async=true; s.setAttribute("src", "https://collectcdn.com/launcher.js"); h.appendChild(s); })(window, document);</script>
    
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            padding-top: 100px; /* Space for sticky header */
        }
        @media (max-width: 768px) {
            body { padding-top: 90px; }
        }

        /* Mobile Menu Transitions */
        #menu-overlay.hidden { display: none; }
        #menu-overlay.show { display: block; opacity: 1; }
        
        #mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        #mobile-menu.open {
            transform: translateX(0);
        }

        /* Custom Scrollbar */
        #mobile-menu::-webkit-scrollbar {
            width: 4px;
        }
        #mobile-menu::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-white">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5GSLXP4C');</script>
<!-- End Google Tag Manager -->



    <!-- Mobile Menu Overlay -->
    <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-[100] hidden opacity-0 transition-opacity duration-300"></div>

    <!-- Sidebar Menu -->
<div id="mobile-menu" class="fixed top-0 right-0 h-full w-[85%] max-w-[400px] bg-white z-[110] overflow-y-auto pb-20 shadow-2xl">

    <!-- Close Button -->
    <div class="flex justify-end p-4">
        <button id="close-menu" class="text-gray-500 hover:text-black p-2">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <div class="px-6">

        <!-- Login Button -->
        <a href="/login.php" class="w-full bg-[#5d7234] text-white py-3 px-4 rounded-lg flex items-center gap-3 font-semibold text-lg mb-6 shadow-sm">
            <i data-lucide="hand" class="w-6 h-6 transform -rotate-12"></i>
            Login
        </a>

        <!-- SHOP Section -->
        <div class="mb-6">
            <h3 class="text-black font-bold text-lg mb-4 uppercase tracking-tight">SHOP</h3>
            
            <div class="space-y-0 border-t border-gray-100">

                <!-- Summer Essentials -->
                <a href="/products.php" class="flex items-center justify-between py-4 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#fce8e4] flex items-center justify-center">
                            <i data-lucide="sun" class="w-5 h-5 text-[#b57d62]"></i>
                        </div>
                        <span class="text-gray-800 font-medium">Summer Essentials</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </a>

                <!-- All Products -->
                <a href="/products.php" class="flex items-center justify-between py-4 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#e9f0df] flex items-center justify-center">
                            <i data-lucide="leaf" class="w-5 h-5 text-[#5d7234]"></i>
                        </div>
                        <span class="text-gray-800 font-medium">All Products</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </a>

                <!-- Ingredients (Blog Page for now) -->
                <a href="/blog.php" class="flex items-center justify-between py-4 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#f3f4f6] flex items-center justify-center">
                            <i data-lucide="database" class="w-5 h-5 text-gray-600"></i>
                        </div>
                        <span class="text-gray-800 font-medium">Ingredients</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </a>

                <!-- Track Order -->
                <a href="/track-order.php" class="flex items-center justify-between py-4 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#fce8e4] flex items-center justify-center">
                            <i data-lucide="package" class="w-5 h-5 text-[#b57d62]"></i>
                        </div>
                        <span class="text-gray-800 font-medium">Track Order</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </a>

            </div>
        </div>

        <!-- Other Links -->
        <div class="space-y-4 mb-8">
            <a href="/blog.php" class="block text-black font-bold text-lg">Blog</a>
            <a href="/about.php" class="block text-black font-bold text-lg">About Us</a>
            <a href="/contact.php" class="block text-black font-bold text-lg">Contact Us</a>
            <a href="/shipping-policy.php" class="block text-black font-bold text-lg">Shipping Policy</a>
            <a href="/terms-and-conditions.php" class="block text-black font-bold text-lg">Terms & Conditions</a>
            <a href="/refund-cancellation.php" class="block text-black font-bold text-lg">Refund Policy</a>
        </div>

        <!-- Bottom Section -->
        <div class="flex items-center gap-4 text-gray-600 py-6 border-t border-gray-100">
            <a href="/account.php" class="font-medium">My Account</a>
            <span class="text-gray-300">|</span>
            <a href="/cart.php" class="font-medium">Cart</a>
        </div>

    </div>
</div>

    <!-- Header Wrapper (Sticky) -->
    <div class="fixed top-0 left-0 w-full z-50 shadow-sm bg-white">
        <!-- 1. Announcement Bar -->
        <div id="announcement-bar" class="relative h-10 flex items-center justify-center bg-[#1a252f] overflow-hidden" 
             style="background-image: url('https://storage.googleapis.com/kapiva-assets/public/images/lush%20Rectangle%201840.png'); background-size: cover;">
            <button id="close-announcement" class="absolute left-4 text-white/80 hover:text-white">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="flex items-center gap-2 sm:gap-4">
                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                     <i data-lucide="coins" class="w-3 h-3 text-white"></i>
                </div>
                <span class="text-white text-[10px] sm:text-xs font-bold tracking-tight uppercase">ADDITIONAL 10% OFF WITH LIVVRA COINS</span>
                <button class="bg-[#76a33a] text-white text-[10px] px-3 py-1 rounded font-bold uppercase hover:bg-opacity-90 transition-all"></button>
            </div>
        </div>

        <!-- 2. Main Navigation Header -->
        <header class="bg-white px-4 py-2 lg:px-8 border-b border-gray-100">
            <div class="max-w-[1400px] mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                
                <!-- Logo & Location (Container for Desktop layout) -->
                <div class="w-full flex items-center justify-between md:w-auto md:flex md:items-center md:gap-4 lg:gap-10">
                    
                    <!-- Mobile Hamburger (Left on Mobile) -->
                    <button id="open-menu-mobile" class="md:hidden p-1 hover:bg-gray-100 rounded-full transition-colors">
                        <i data-lucide="menu" class="w-6 h-6 cursor-pointer"></i>
                    </button>

                    <a href="index.php" class="flex-shrink-0 mx-auto md:mx-0">
                        <img src="assets/images/logo/logo.png" alt="LIVVRA" class="h-8 lg:h-12 w-auto">
                    </a>

                    <!-- Desktop Location (Hidden on mobile) -->
                    <div class="hidden sm:flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer hover:text-[#76a33a] transition-colors">
                        <i data-lucide="map-pin" class="w-4 h-4 text-[#76a33a]"></i>
                        <div class="flex flex-col leading-tight">
                            <span class="font-bold text-[#76a33a]">141120</span>
                            <span class="text-[10px] whitespace-nowrap">: Ludhiana, Punjab</span>
                        </div>
                    </div>

                    <!-- Mobile Icons (Right on Mobile) -->
                    <div class="flex items-center gap-3 md:hidden">
                        <i data-lucide="search" class="w-6 h-6 cursor-pointer hover:text-[#76a33a]"></i>
                        <a href="cart.php" class="relative group">
                            <i data-lucide="shopping-cart" class="w-6 h-6 group-hover:text-[#76a33a] transition-colors"></i>
                            <span class="cart-count absolute -top-1.5 -right-1.5 bg-[#b57d62] text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center"><?php echo $cart_count; ?></span>
                        </a>
                    </div>
                </div>

                <!-- Delivery Bar for Mobile (Always visible between Header and Categories on mobile) -->
                <div class="md:hidden w-full bg-white border-b border-gray-100 py-3 px-4 flex items-center justify-between cursor-pointer" onclick="openLocationPopup()">
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-[#76a33a]"></i>
                        <div class="flex items-center gap-1 text-[14px]">
                            <span class="font-bold text-[#76a33a]" id="mobile-pincode-display">141120</span>
                            <span class="text-gray-600">: <span id="mobile-location-display">Ludhiana, Punjab</span></span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="flex-grow max-w-[600px] relative hidden md:block">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="text" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:border-[#76a33a] focus:ring-1 focus:ring-[#76a33a] transition-all" 
                               placeholder='Search for "Hormonal health"'>
                    </div>
                </div>

                <!-- Right Actions (Desktop) -->
                <div class="hidden md:flex items-center gap-3 lg:gap-6">
                    <a href="login.php" class="border border-gray-900 text-[10px] font-bold px-5 py-2 rounded uppercase hover:bg-gray-900 hover:text-white transition-all">LOGIN</a>
                    <button class="hidden lg:block border border-gray-900 text-[10px] font-bold px-5 py-2 rounded uppercase hover:bg-gray-900 hover:text-white transition-all">GET APP</button>
                    
                    <div class="flex items-center gap-4 lg:gap-6 text-gray-700">
                        <a href="track-order.php" class="hover:text-[#76a33a]">
                            <i data-lucide="truck" class="w-6 h-6"></i>
                        </a>
                        
                        <a href="cart.php" class="relative group">
                            <i data-lucide="shopping-cart" class="w-6 h-6 group-hover:text-[#76a33a] transition-colors"></i>
                            <span class="cart-count absolute -top-1.5 -right-1.5 bg-[#b57d62] text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center"><?php echo $cart_count; ?></span>
                        </a>
                        
                        <button id="open-menu" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                            <i data-lucide="menu" class="w-6 h-6 cursor-pointer"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
    </div>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NG35DBWR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Announcement Bar Close
        const announcementBar = document.getElementById('announcement-bar');
        const closeAnnouncement = document.getElementById('close-announcement');
        if (closeAnnouncement) {
            closeAnnouncement.addEventListener('click', () => {
                announcementBar.style.display = 'none';
                document.body.style.paddingTop = '60px'; // Adjust body padding
            });
        }

        // Mobile Menu Logic
        const openMenuBtn = document.getElementById('open-menu');
        const openMenuBtnMobile = document.getElementById('open-menu-mobile');
        const closeMenuBtn = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuOverlay = document.getElementById('menu-overlay');

        function toggleMenu() {
            mobileMenu.classList.toggle('open');
            menuOverlay.classList.toggle('hidden');
            setTimeout(() => {
                menuOverlay.classList.toggle('opacity-0');
            }, 10);
            document.body.classList.toggle('overflow-hidden');
        }

        if (openMenuBtn) openMenuBtn.addEventListener('click', toggleMenu);
        if (openMenuBtnMobile) openMenuBtnMobile.addEventListener('click', toggleMenu);
        if (closeMenuBtn) closeMenuBtn.addEventListener('click', toggleMenu);
        if (menuOverlay) menuOverlay.addEventListener('click', toggleMenu);

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('py-1');
                header.classList.remove('py-2');
            } else {
                header.classList.add('py-2');
                header.classList.remove('py-1');
            }
        });
    </script>

    <!-- Delivery Location Popup -->
    <div id="location-popup" class="fixed inset-0 z-[200] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeLocationPopup()"></div>
        <div class="absolute bottom-0 left-0 w-full bg-white rounded-t-2xl p-6 transition-transform duration-300 translate-y-full" id="location-popup-content">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">Select Delivery Location</h3>
                <button onclick="closeLocationPopup()" class="p-1 hover:bg-gray-100 rounded-full">
                    <i data-lucide="x" class="w-6 h-6 text-gray-500"></i>
                </button>
            </div>
            
            <div class="relative mb-6">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-lg">
                    <input type="text" id="pincode-input" placeholder="Enter pin code" 
                           class="flex-grow bg-transparent border-none py-3 px-4 text-sm focus:outline-none"
                           maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <button onclick="checkPincode()" id="apply-btn" class="text-[#76a33a] font-bold px-4 text-sm">Apply</button>
                </div>
                <p id="pincode-error" class="text-red-500 text-xs mt-2 hidden"></p>
                <div id="delivery-estimate" class="mt-4 p-3 bg-green-50 rounded-lg border border-green-100 hidden">
                    <div class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-5 h-5 text-[#76a33a] mt-0.5"></i>
                        <div>
                            <p class="text-gray-900 text-sm font-bold" id="estimate-location"></p>
                            <p class="text-green-800 text-xs mt-1 flex items-center gap-1">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                Estimated delivery: <span id="estimate-days" class="font-bold"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 mb-6">
                <div class="flex-grow h-px bg-gray-200"></div>
                <span class="text-gray-400 text-sm">Or</span>
                <div class="flex-grow h-px bg-gray-200"></div>
            </div>

            <a href="login.php" class="block w-full bg-[#8ba358] text-white text-center py-4 rounded-lg font-bold uppercase tracking-wide shadow-lg hover:bg-[#76a33a] transition-all">
                LOG IN TO ADD NEW ADDRESS
            </a>
        </div>
    </div>

    <script>
        function openLocationPopup() {
            const popup = document.getElementById('location-popup');
            const content = document.getElementById('location-popup-content');
            popup.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('translate-y-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeLocationPopup() {
            const popup = document.getElementById('location-popup');
            const content = document.getElementById('location-popup-content');
            content.classList.add('translate-y-full');
            setTimeout(() => {
                popup.classList.add('hidden');
            }, 300);
            document.body.style.overflow = '';
        }

        // Attach click event to the existing location bar in header (desktop/tablet)
        document.querySelectorAll('.sm\\:flex.items-center.gap-1\\.5.text-xs.text-gray-500').forEach(el => {
            el.onclick = openLocationPopup;
        });

        async function checkPincode() {
            const pincode = document.getElementById('pincode-input').value;
            const errorEl = document.getElementById('pincode-error');
            const estimateEl = document.getElementById('delivery-estimate');
            const daysEl = document.getElementById('estimate-days');
            const locEl = document.getElementById('estimate-location');
            const applyBtn = document.getElementById('apply-btn');
            
            if (pincode.length !== 6 || isNaN(pincode)) {
                errorEl.textContent = "Please enter a valid 6-digit pincode";
                errorEl.classList.remove('hidden');
                estimateEl.classList.add('hidden');
                return;
            }

            applyBtn.textContent = "Checking...";
            applyBtn.disabled = true;

            try {
                const response = await fetch(`includes/pincode_check.php?pincode=${pincode}`);
                const result = await response.json();

                if (result.status === 'success') {
                    errorEl.classList.add('hidden');
                    locEl.textContent = `${result.data.city}, ${result.data.state}`;
                    daysEl.textContent = result.data.days;
                    estimateEl.classList.remove('hidden');
                    
                    // Update displays
                    document.getElementById('mobile-pincode-display').textContent = pincode;
                    document.getElementById('mobile-location-display').textContent = `${result.data.city}, ${result.data.state}`;
                    
                    // Update desktop display too if it exists
                    const desktopPin = document.querySelector('.sm\\:flex .font-bold.text-\\[\\#76a33a\\]');
                    const desktopLoc = document.querySelector('.sm\\:flex .text-\\[10px\\]');
                    if (desktopPin) desktopPin.textContent = pincode;
                    if (desktopLoc) desktopLoc.textContent = `: ${result.data.city}, ${result.data.state}`;

                    // Auto close after 2.5 seconds
                    setTimeout(closeLocationPopup, 2500);
                } else {
                    errorEl.textContent = result.message;
                    errorEl.classList.remove('hidden');
                    estimateEl.classList.add('hidden');
                }
            } catch (error) {
                console.error("Pincode check error:", error);
                errorEl.textContent = "Unable to verify pincode. Please try again.";
                errorEl.classList.remove('hidden');
            } finally {
                applyBtn.textContent = "Apply";
                applyBtn.disabled = false;
            }
        }
    </script>
    <div id="video-popup-container"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('ajax/video_popup_handler.php?action=get_active')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.popup) {
                    const popup = data.popup;
                    const container = document.getElementById('video-popup-container');
                    
                    const popupHtml = `
                        <div id="floating-video-popup" style="position: fixed; bottom: 100px; left: 20px; width: 150px; height: 250px; z-index: 9999; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3); background: #000; cursor: move; transition: transform 0.1s ease-out;">
                            <button id="close-video-popup" style="position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.5); color: #fff; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center; font-size: 18px;">&times;</button>
                            <video id="popup-video" autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover; pointer-events: none;">
                                <source src="${popup.video_url}" type="video/mp4">
                            </video>
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 10px; pointer-events: none;">
                                <div style="display: flex; align-items: center; gap: 5px; color: #fff; font-size: 10px; margin-bottom: 5px;">
                                    <i data-lucide="eye" style="width: 12px; height: 12px;"></i>
                                    <span>${popup.view_count}</span>
                                </div>
                                <a href="${popup.buy_link || '#'}" style="display: block; background: #76a33a; color: #fff; text-align: center; padding: 6px; border-radius: 4px; font-size: 11px; text-decoration: none; font-weight: bold; pointer-events: auto; text-transform: uppercase;">BUY NOW</a>
                            </div>
                        </div>
                    `;
                    container.innerHTML = popupHtml;
                    if (window.lucide) lucide.createIcons();

                    const el = document.getElementById('floating-video-popup');
                    const closeBtn = document.getElementById('close-video-popup');

                    closeBtn.onclick = (e) => {
                        e.stopPropagation();
                        el.remove();
                    };

                    fetch('ajax/video_popup_handler.php?action=increment_view', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `id=${popup.id}`
                    });

                    let isDragging = false;
                    let currentX;
                    let currentY;
                    let initialX;
                    let initialY;
                    let xOffset = 0;
                    let yOffset = 0;

                    el.addEventListener("mousedown", dragStart);
                    el.addEventListener("touchstart", dragStart, {passive: false});
                    document.addEventListener("mouseup", dragEnd);
                    document.addEventListener("touchend", dragEnd);
                    document.addEventListener("mousemove", drag);
                    document.addEventListener("touchmove", drag, {passive: false});

                    function dragStart(e) {
                        if (e.type === "touchstart") {
                            initialX = e.touches[0].clientX - xOffset;
                            initialY = e.touches[0].clientY - yOffset;
                        } else {
                            initialX = e.clientX - xOffset;
                            initialY = e.clientY - yOffset;
                        }
                        if (e.target === el || el.contains(e.target)) {
                            isDragging = true;
                        }
                    }

                    function dragEnd(e) {
                        initialX = currentX;
                        initialY = currentY;
                        isDragging = false;
                    }

                    function drag(e) {
                        if (isDragging) {
                            e.preventDefault();
                            if (e.type === "touchmove") {
                                currentX = e.touches[0].clientX - initialX;
                                currentY = e.touches[0].clientY - initialY;
                            } else {
                                currentX = e.clientX - initialX;
                                currentY = e.clientY - initialY;
                            }
                            xOffset = currentX;
                            yOffset = currentY;
                            el.style.transform = "translate3d(" + currentX + "px, " + currentY + "px, 0)";
                        }
                    }
                }
            });
    });
    </script>
