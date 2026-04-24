<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Product.php';
require_once __DIR__ . '/includes/models/Review.php';
require_once __DIR__ . '/includes/models/ProductImage.php';

// Support both ?slug=xxx and ?id=xxx
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$id   = isset($_GET['id'])   ? (int)$_GET['id']   : 0;

if ($slug) {
    $product = Product::getBySlug($slug);
    if (!$product) { header('Location: products.php'); exit; }
    $id = $product['id']; // ✅ YEH LINE ADD KARO!
} 


elseif ($id > 0) {
    $product = Product::getById($id);
    if (!$product) { header('Location: products.php'); exit; }
    // 301 redirect to clean slug URL
    if (!empty($product['slug'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /' . $product['slug']);
        exit;
    }
} else {
    header('Location: products.php');
    exit;
}

// ===== PER-PRODUCT SEO MAP (slug-based matching) =====
$prodSlugTemp = strtolower($product['slug'] ?? '');
$prodNameTemp = strtolower($product['name'] ?? '');

// Default SEO
$pageTitle       = $product['name'] . ' | Buy Online in India - Livvra';
$metaDescription = substr(strip_tags($product['description'] ?? ''), 0, 160);
$metaKeywords    = 'ayurvedic products india, herbal supplements online, livvra wellness';
$canonicalUrl    = 'https://livvra.in/ayurvedic-products-collection';

// Match by slug/name and apply specific SEO
if (str_contains($prodSlugTemp, 'nabhyam') || str_contains($prodNameTemp, 'nabhyam')) {
    $pageTitle       = 'Best Nabhi Oil for Health & Digestion | Nabhyam Amrit';
    $metaDescription = 'Buy Nabhyam Amrit, the best belly button oil in Ayurveda. A natural nabhi oil online in India for better digestion, health, and holistic wellness. Order now.';
    $metaKeywords    = 'nabhyam amrit oil benefits, navel oil ayurveda, digestion oil herbal, immunity oil india, navel therapy';
    $canonicalUrl    = 'https://livvra.in/nabhyam-amrit-oil-benefits';

} elseif (str_contains($prodSlugTemp, 'kumkumadi') || str_contains($prodNameTemp, 'kumkumadi')) {
    $pageTitle       = 'Buy Kumkumadi Oil India | Natural Face Glow Ayurveda Oil';
    $metaDescription = 'Discover glowing skin with pure Kumkumadi Tailam online. Our ayurvedic beauty oil reduces pigmentation and naturally brightens your face. Order yours now.';
    $metaKeywords    = 'kumkumadi oil benefits, ayurvedic face oil, glowing skin oil, herbal skincare india, livvra beauty oil';
    $canonicalUrl    = 'https://livvra.in/kumkumadi-face-oil-glow';

} elseif (str_contains($prodSlugTemp, 'shilajit') || str_contains($prodNameTemp, 'shilajit')) {
    if (str_contains($prodSlugTemp, 'combo') || str_contains($prodNameTemp, 'combo')) {
        $pageTitle       = 'Buy Pure Gold Shilajit Resin Combo | Best Shilajit India';
        $metaDescription = 'Save more on the best shilajit brand in India! Buy our Pure Gold Shilajit Resin Combo online. The ultimate ayurvedic supplement for energy and stamina.';
        $metaKeywords    = 'shilajit combo pack, ayurvedic combo products, stamina supplement combo, herbal bundle india, shilajit multipack, discount combo';
        $canonicalUrl    = 'https://livvra.in/shilajit-resin-combo-pack';
    } else {
        $pageTitle       = 'Buy Pure Gold Shilajit Resin in India | Best For Energy';
        $metaDescription = 'Order original shilajit online in India. Our pure Gold Shilajit Resin is the best ayurvedic supplement for energy, strength, men\'s wellness, and stamina.';
        $metaKeywords    = 'gold shilajit resin india, shilajit benefits men, ayurvedic stamina booster, natural energy supplement';
        $canonicalUrl    = 'https://livvra.in/gold-shilajit-resin-india';
    }

} elseif (str_contains($prodSlugTemp, 'dy-b-fuel') || str_contains($prodSlugTemp, 'fuel-ras') || str_contains($prodNameTemp, 'dy b fuel') || str_contains($prodNameTemp, 'dy-b-fuel')) {
    if (str_contains($prodSlugTemp, 'combo') || str_contains($prodNameTemp, 'combo')) {
        $pageTitle       = 'Dy B Fuel Ras Combo Pack | Ayurvedic Energy Tonic India';
        $metaDescription = 'Stock up and save with the Dy B Fuel Ras Combo. India\'s best ayurvedic energy tonic and natural stamina booster. Fight weakness with our herbal health tonic.';
        $metaKeywords    = 'fuel ras combo pack, ayurvedic energy combo, herbal tonic combo, stamina booster combo, livvra ras combo';
        $canonicalUrl    = 'https://livvra.in/dy-b-fuel-ras-energy-combo';
    } else {
        $pageTitle       = 'Dy B Fuel Ras | Best Ayurvedic Energy Tonic in India';
        $metaDescription = 'Boost your stamina with Dy B Fuel Ras, a premium ayurvedic tonic for energy, immunity, and weakness. Buy the best herbal health tonic online in India today.';
        $metaKeywords    = 'ayurvedic energy ras, herbal energy tonic, stamina booster ayurveda, sugar balance product';
        $canonicalUrl    = 'https://livvra.in/ayurvedic-energy-fuel-ras';
    }

} elseif (str_contains($prodSlugTemp, 'protein') || str_contains($prodSlugTemp, 'yeast') || str_contains($prodNameTemp, 'protein') || str_contains($prodNameTemp, 'yeast')) {
    $isCombo = str_contains($prodSlugTemp, 'combo') || str_contains($prodNameTemp, 'combo');
    if (str_contains($prodSlugTemp, 'vanilla') || str_contains($prodNameTemp, 'vanilla')) {
        $pageTitle       = 'Vanilla Vegan Protein Powder India | Plant Based Protein';
        $metaDescription = 'Fuel muscle gain with our vanilla yeast-based vegan protein powder in India. A healthy, natural protein supplement for everyday fitness and immunity. Buy online.';
        $metaKeywords    = 'vanilla plant protein powder, yeast protein supplement, plant protein india, muscle recovery protein';
        $canonicalUrl    = 'https://livvra.in/vanilla-plant-protein-powder';
    } elseif (str_contains($prodSlugTemp, 'mango') || str_contains($prodNameTemp, 'mango')) {
        $pageTitle       = 'Mango Plant Based Protein Powder | Herbal Supplement India';
        $metaDescription = 'Enjoy the taste of health with our mango yeast-based protein powder. The best organic, plant-based protein supplement in India for muscle gain. Order today.';
        $metaKeywords    = 'mango plant protein supplement, yeast protein mango, herbal protein powder, energy protein drink, livvra mango protein';
        $canonicalUrl    = 'https://livvra.in/mango-plant-protein-supplement';
    } elseif (str_contains($prodSlugTemp, 'coffee') || str_contains($prodNameTemp, 'coffee')) {
        if ($isCombo) {
            $pageTitle       = 'Coffee Vegan Protein Powder Combo | Plant Based India';
            $metaDescription = 'Get the best deal on our healthy protein supplement in India! Buy the coffee yeast-based vegan protein powder combo for sustained muscle gain and energy.';
            $metaKeywords    = 'coffee protein combo pack, plant protein bundle, fitness protein combo, energy supplement combo, livvra protein combo';
            $canonicalUrl    = 'https://livvra.in/coffee-protein-combo-pack';
        } else {
            $pageTitle       = 'Coffee Vegan Protein Powder | Healthy Protein Supplement';
            $metaDescription = 'Kickstart your day with our coffee-flavoured yeast-based vegan protein powder. A natural, plant-based protein supplement for muscle gain in India. Buy now.';
            $metaKeywords    = 'coffee plant protein powder, yeast protein coffee, energy protein supplement, plant protein coffee, amino acid supplement';
            $canonicalUrl    = 'https://livvra.in/coffee-plant-protein-powder';
        }
    } else {
        // Generic protein
        $pageTitle       = 'Buy Ayurvedic Products Online | Best Herbal Store India';
        $metaDescription = 'Explore our complete range of premium ayurvedic products online. From pure shilajit to natural skincare and organic protein. Buy the best herbal supplements!';
        $metaKeywords    = 'buy ayurvedic products online, herbal store india, plant protein india, yeast protein supplement';
        $canonicalUrl    = 'https://livvra.in/buy-ayurvedic-products-online';
    }
}
// ===== END PRODUCT SEO MAP =====

// Database Connection
$conn = db();

// Fetch Reviews
$reviews = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
$reviews->execute([$product['id']]); // ✅ $id ki jagah $product['id']
$reviews = $reviews->fetchAll(PDO::FETCH_ASSOC);


try {
    $stmt = $conn->prepare("SELECT * FROM product_media WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$product['id']]); // ✅ $id ki jagah $product['id']
    $galleryMedia = $stmt->fetchAll();
} catch (Exception $e) {
    $galleryMedia = [];
}


require_once __DIR__ . '/includes/header.php';

// Initialize Variables
$ingredients = [];
$nutrition = [];
$faqs = [];
$aminoAcids = []; // SIRF Yeast Protein mein
$product_sections = [];
$product_banners = [];

$prodId = (int)$product['id'];
$prodName = $product['name'];
$prodSlug = $product['slug'] ?? '';

// ================= COMPLETE PRODUCT CONFIGURATION =================

// ================= 1. YEAST PROTEIN PRODUCTS (With Amino Acids) =================
// IDs: 4 (Coffee), 14 (2-Pack Combo), 35 (Mango), 36 (Vanilla)
if (in_array($prodId, [4, 14, 35, 36]) || 
    (stripos($prodName, 'Yeast') !== false && stripos($prodName, 'Protein') !== false)) {
    
    // $product_banners = [
    //     'assets/products-banners/yeast-benefits.jpg',
    //     'assets/products-banners/yeast-how-to-use.jpg',
    //     'assets/products-banners/yeast-ingredients.jpg',
    // ];

    // YEAST PROTEIN INGREDIENTS
    $ingredients = [
        ['name' => 'Fermented Yeast Protein 🍄', 'qty' => '25g per Scoop', 'purpose' => 'Complete dairy-free protein for muscle recovery & strength 💪'],
        ['name' => 'Panax Ginseng Extract 🌿', 'qty' => '285.8mg', 'purpose' => 'Boosts stamina, energy and physical performance ⚡'],
        ['name' => 'Mucuna Pruriens Extract 🌱', 'qty' => '285.8mg', 'purpose' => 'Supports dopamine levels, mood and hormonal balance 🧠'],
        ['name' => 'Bacillus Coagulans 🦠', 'qty' => '1 Billion CFU', 'purpose' => 'Improves gut health & digestion 🛡️'],
        ['name' => 'Black Pepper Extract 🌶️', 'qty' => '11.5mg', 'purpose' => 'Enhances nutrient absorption & metabolism 🔥'],
        ['name' => 'Vitamin B6 🔋', 'qty' => '5.42mg', 'purpose' => 'Supports energy metabolism & brain function ⚡'],
        ['name' => 'Magnesium (Gluconate) 🪨', 'qty' => '102.85mg', 'purpose' => 'Supports muscle function & reduces fatigue 💪'],
        ['name' => 'Zinc (Gluconate) ⚙️', 'qty' => '48.58mg', 'purpose' => 'Supports immunity & hormone balance 🛡️'],
        ['name' => 'Selenium 🧪', 'qty' => '20mcg', 'purpose' => 'Supports antioxidant defense 🛡️'],
        ['name' => 'Vitamin A 👁️', 'qty' => '500mcg', 'purpose' => 'Supports vision, skin health & immunity ✨'],
        ['name' => 'Vitamin B1 (Thiamine) 🔋', 'qty' => '0.7mg', 'purpose' => 'Supports energy production & nerve function ⚡'],
        ['name' => 'Vitamin B2 (Riboflavin) ⚡', 'qty' => '1mg', 'purpose' => 'Helps convert food into energy 🔄'],
        ['name' => 'Vitamin B3 (Niacinamide) 💊', 'qty' => '7mg', 'purpose' => 'Supports metabolism & skin health ✨'],
        ['name' => 'Vitamin B5 (Pantothenic Acid) 🔋', 'qty' => '2.5mg', 'purpose' => 'Helps reduce fatigue & improve metabolism ⚡'],
        ['name' => 'Vitamin B12 🧬', 'qty' => '1.1mcg', 'purpose' => 'Supports nerve health & red blood cell formation ❤️'],
        ['name' => 'Vitamin C 🍊', 'qty' => '40mg', 'purpose' => 'Boosts immunity & antioxidant protection 🛡️'],
        ['name' => 'Vitamin D2 ☀️', 'qty' => '7.5mcg', 'purpose' => 'Supports bone strength & immunity 💎'],
        ['name' => 'Vitamin K2 🦴', 'qty' => '27.5mcg', 'purpose' => 'Supports bone and heart health ❤️'],
        ['name' => 'Biotin 💇', 'qty' => '20mcg', 'purpose' => 'Supports healthy hair, skin & nails ✨'],
        ['name' => 'Folic Acid 🌿', 'qty' => '150mcg', 'purpose' => 'Supports cell growth & red blood cell formation ❤️'],
        ['name' => 'Chromium Picolinate ⚙️', 'qty' => '100mcg', 'purpose' => 'Supports blood sugar metabolism 🔄'],
        ['name' => 'Iodine 🧂', 'qty' => '70mcg', 'purpose' => 'Supports thyroid health ⚙️'],
        ['name' => 'Iron (Ferrous Bisglycinate) 🩸', 'qty' => '10mg', 'purpose' => 'Supports oxygen transport & energy levels ❤️'],
        ['name' => 'Calcium 🦴', 'qty' => '50mg', 'purpose' => 'Supports strong bones & muscle function 💪'],
        ['name' => 'Manganese ⚙️', 'qty' => '2mg', 'purpose' => 'Supports metabolism & bone formation 🔄'],
        ['name' => 'Copper 🧪', 'qty' => '0.85mg', 'purpose' => 'Supports iron metabolism & immunity 🛡️']
    ];

    // AMINO ACIDS - SIRF YEAST PROTEIN MEIN
    $aminoAcids = [
        ['Alanine 🧬', '1.40g'],
        ['Arginine 🧬', '1.55g'],
        ['Aspartic Acid 🧬', '2.56g'],
        ['Cysteine 🧬', '0.18g'],
        ['Glutamic Acid 🧬', '2.67g'],
        ['Glycine 🧬', '1.33g'],
        ['Histidine 🧬', '0.80g'],
        ['Isoleucine 🧬 (BCAA)', '1.41g'],
        ['Leucine 🧬 (BCAA)', '2.18g'],
        ['Lysine 🧬', '2.26g'],
        ['Methionine 🧬', '0.70g'],
        ['Phenylalanine 🧬', '1.34g'],
        ['Proline 🧬', '0.94g'],
        ['Serine 🧬', '1.24g'],
        ['Threonine 🧬', '1.26g'],
        ['Tryptophan 🧬', '0.46g'],
        ['Tyrosine 🧬', '1.11g'],
        ['Valine 🧬 (BCAA)', '1.61g']
    ];

    // NUTRITION TABLE
    $nutrition = [
        ['label' => 'Energy', 'serve' => '131.9 kcal', '100g' => '376.9 kcal'],
        ['label' => 'Protein', 'serve' => '25.0g', '100g' => '71.4g'],
        ['label' => 'Carbohydrates', 'serve' => '6.2g', '100g' => '17.7g'],
        ['label' => 'Fat', 'serve' => '0.8g', '100g' => '2.3g'],
        ['label' => 'Sugar', 'serve' => '0g', '100g' => '0g'],
    ];

    $faqs = [
        ['q' => 'Is it dairy free?', 'a' => 'Yes, it is 100% plant-based yeast protein with no dairy or lactose.'],
        ['q' => 'How many servings per pouch?', 'a' => 'Approximately 29 servings per 1kg pouch.'],
        ['q' => 'When should I take it?', 'a' => 'Best consumed post-workout or in the morning for optimal results.'],
        ['q' => 'Does it cause bloating?', 'a' => 'No, our formula includes digestive enzymes and probiotics to prevent bloating.'],
        ['q' => 'What is PDCAAS score?', 'a' => 'LIVVRA Yeast Protein has PDCAAS score of 1.0 - the highest possible rating.'],
    ];

    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/yeast-oil-1.png',
            'content' => 'Yeast-Based Protein is a next-generation plant protein derived from fermented yeast. Unlike whey or soy, it is naturally light on the stomach and highly bioavailable with PDCAAS score of 1.0 - equivalent to animal proteins like casein and egg white.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/yeast-oil-2.png',
            'content' => 'Mix 1 scoop (35g) with 200–250 ml chilled water or milk alternative. Shake well until fully dissolved. Consume once daily or as advised by your nutrition expert.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/yeast-oil-1.png',
            'content' => '✔ 25g Complete Protein per serving<br>✔ 5.2g BCAA + 12.02g EAA<br>✔ PDCAAS Score 1.0 (Highest)<br>✔ Faster muscle recovery<br>✔ Sustained energy<br>✔ Easy digestion<br>✔ Gut health support<br>✔ Clean & filler-free nutrition'
        ]
    ];

// ================= 2. KUMKUMADI BEAUTY OIL (No Amino Acids) =================
// IDs: 5 (Single), 15 (2-Pack Combo)
} elseif (in_array($prodId, [5, 15]) || stripos($prodName, 'Kumkumadi') !== false) {
    
    // $product_banners = [
    //     'assets/products-banners/kumkumadi-benefits.jpg',
    //     'assets/products-banners/how-to-use-kumkumadi-oil.jpg',
    //     'assets/products-banners/kumkumadi-ingredients.jpg',
    // ];

    // KUMKUMADI INGREDIENTS (20+ Herbs)
    $ingredients = [
        ['name' => 'Kesar (Saffron) 🌸', 'qty' => 'Premium Kashmir', 'purpose' => 'Skin brightening & natural glow enhancement ✨'],
        ['name' => 'RaktChandan (Red Sandalwood) 🌿', 'qty' => '46gm', 'purpose' => 'Cooling, soothing & anti-inflammatory ❄️'],
        ['name' => 'Manjistha (Indian Madder) 🍃', 'qty' => '46gm', 'purpose' => 'Blood purifier & complexion clarifier 🩸'],
        ['name' => 'Laksha (Laccifer Lacca) 🍃', 'qty' => '46gm', 'purpose' => 'Reduces acne & scars, supports healing 💧'],
        ['name' => 'Padmakasht (Wild Himalayan Cherry) 🌼', 'qty' => '46gm', 'purpose' => 'Enhances complexion & skin luminosity 🌟'],
        ['name' => 'Dashmool (Ten Roots) 🌾', 'qty' => '46gm', 'purpose' => 'Anti-inflammatory & skin rejuvenation 💧'],
        ['name' => 'Goat Milk 🥛', 'qty' => 'Pure A2', 'purpose' => 'Natural moisturizing base & skin softener 🛡️'],
        ['name' => 'Mulethi (Licorice/Yashtimadhu) 🫚', 'qty' => '46gm', 'purpose' => 'Skin lightening & dark spot reduction 🌟'],
        ['name' => 'Nil Kamal (Blue Water Lily) 🪷', 'qty' => '46gm', 'purpose' => 'Cooling effect & skin hydration ❄️'],
        ['name' => 'Vatparoh (Banyan) 🌿', 'qty' => '46gm', 'purpose' => 'Healing properties & tissue repair 🩹'],
        ['name' => 'Pakad (Java Fig) 🌿', 'qty' => '46gm', 'purpose' => 'Natural skin tightening & firming 🧖'],
        ['name' => 'Kamal Lakeshar (Lotus Pollen) 🪷', 'qty' => '46gm', 'purpose' => 'Complexion enhancer & glow booster ✨'],
        ['name' => 'Manjith (Indian Madder) 🍂', 'qty' => '12gm', 'purpose' => 'Natural color & skin tone balancing 🎨'],
        ['name' => 'Tila Taila (Sesame Oil) 🛢️', 'qty' => 'Base Oil', 'purpose' => 'Carrier oil for deep penetration & nourishment 🫗'],
        ['name' => 'Kaliyaka (Tree Turmeric) 🌿', 'qty' => 'Traditional', 'purpose' => 'Ayurvedic skin rejuvenation herb 🌿'],
        ['name' => 'Khas (Vetiver) 🌿', 'qty' => 'Traditional', 'purpose' => 'Cooling & skin soothing properties ❄️'],
        ['name' => 'Daruharidra (Indian Barberry) 🍃', 'qty' => 'Traditional', 'purpose' => 'Anti-inflammatory & skin healing 🩹'],
        ['name' => 'Bael (Aegle Marmelos) 🌳', 'qty' => 'Traditional', 'purpose' => 'Cooling & digestive support for skin health 🌿'],
        ['name' => 'Mahua (Madhuca Longifolia) 🌸', 'qty' => '12gm', 'purpose' => 'Skin nourishment & moisturizing 🌸'],
        ['name' => 'Patranga (Sappanwood) 🪵', 'qty' => '12gm', 'purpose' => 'Complexion enhancement & natural color 🎨']
    ];
    
    // NO AMINO ACIDS FOR KUMKUMADI
    $aminoAcids = [];

    $faqs = [
        ['q' => 'Is it suitable for oily skin?', 'a' => 'Yes, use 1-2 drops at night. For very oily skin, wash off after 30 minutes.'],
        ['q' => 'Can men use this oil?', 'a' => 'Absolutely, it is gender-neutral and works for all skin types.'],
        ['q' => 'How long to see results?', 'a' => 'Visible glow in 7-14 days, significant results in 4-6 weeks of regular use.'],
        ['q' => 'Is it safe during pregnancy?', 'a' => 'Yes, 100% natural and safe. However, consult your doctor if unsure.'],
        ['q' => 'Does it contain mineral oil?', 'a' => 'No, it is completely free from mineral oil and synthetic chemicals.'],
        ['q' => 'Can I use it with other skincare products?', 'a' => 'Yes, apply Kumkumadi first, wait 20 mins, then apply other products.'],
    ];
    
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/woman-skincare.png',
            'content' => 'Kumkumadi Beauty Oil is an ancient Ayurvedic formulation enriched with pure Kashmir saffron and 20+ precious herbs. It penetrates deep into the skin through 27-layer cellular nourishment, improving texture, tone, and natural glow.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/woman-skincare.png',
            'content' => 'Apply 2–3 drops on cleansed face at night. Massage gently in upward circular motions until absorbed. Leave overnight for best results. Use daily for 4-6 weeks for visible transformation.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/woman-skincare.png',
            'content' => '✔ Natural radiance & glow<br>✔ Reduces dark spots & pigmentation<br>✔ Fades acne scars<br>✔ Anti-aging support<br>✔ Deep nourishment<br>✔ Evens skin tone<br>✔ Improves skin texture'
        ]
    ];
    
    
    // ================= NABHYAM AMRIT OIL (Navel/Belly Button Oil) =================
// IDs: 37 (Single), [Add Combo ID if exists]
} elseif (in_array($prodId, [37]) || stripos($prodName, 'Nabhyam') !== false || stripos($prodName, 'Amrit') !== false) {
    
    // $product_banners = [
    //     'assets/products-banners/nabhyam-benefits.jpg',
    //     'assets/products-banners/how-to-use-nabhyam-oil.jpg',
    //     'assets/products-banners/nabhyam-ingredients.jpg',
    // ];

    // NABHYAM AMRIT OIL INGREDIENTS (10 Natural Oils)
    $ingredients = [
        
         ['name' => 'Castor Oil (Ricinus communis) 🌿', 'qty' => '3.2%', 'purpose' => 'Detoxification, digestion support & joint health 💧'],
           ['name' => 'Badam Oil (Almond Oil) 🌰', 'qty' => '2%', 'purpose' => 'Rich in Vitamin E, nourishes & strengthens 💪'],
            ['name' => 'Ajwain Oil (Trachyspermum ammi) 🌿', 'qty' => '0.5%', 'purpose' => 'Digestive aid, relieves gas & bloating 🌬️'],
                    ['name' => 'Alsi Oil (Flaxseed Oil) 🌾', 'qty' => '2.05%', 'purpose' => 'Omega-3 rich, anti-inflammatory & joint health 🦴'],
                     ['name' => 'Clove Oil (Syzygium aromaticum) 🌸', 'qty' => '1%', 'purpose' => 'Analgesic, improves digestion & immunity 🛡️'],
                       ['name' => 'Kapoor Oil (Camphor) 🌲', 'qty' => '10%', 'purpose' => 'Cooling, pain relief & anti-inflammatory ❄️'],
                        ['name' => 'Nariyal Oil (Coconut Oil) 🥥', 'qty' => '5%', 'purpose' => 'Moisturizing, antimicrobial & skin healing 🛡️'],
                        ['name' => 'Neem Oil (Azadirachta indica) 🍃', 'qty' => '3%', 'purpose' => 'Antibacterial, antifungal & blood purifier 🛡️'],
                          ['name' => 'Sarso Oil (Mustard Oil) 🌾', 'qty' => '35%', 'purpose' => 'Warming effect, improves circulation & detoxification 🔥'],
            
        ['name' => 'Till Oil (Sesame Oil) 🛢️', 'qty' => '38.25%', 'purpose' => 'Base carrier oil for deep absorption & nourishment 🌿']
      
      
       
       
       
      

       
       
    ];
    
    // NO AMINO ACIDS FOR NABHYAM AMRIT OIL
    $aminoAcids = [];

    $faqs = [
        ['q' => 'What is Nabhyam Amrit Oil used for?', 'a' => 'It is an Ayurvedic navel (belly button) oiling therapy for digestive health, joint pain relief, detoxification, and overall wellness through the Pechoti method.'],
        ['q' => 'How to use this oil?', 'a' => 'Clean belly button with warm water, apply 4-5 drops in the navel, leave for 15 minutes, then massage remaining oil in circular motion for 2-3 minutes. Repeat daily.'],
        ['q' => 'Can I apply this oil on other body parts?', 'a' => 'Yes, you can massage it on joints, muscles, or abdomen for pain relief, but primary use is navel oiling.'],
        ['q' => 'Is it safe for everyone?', 'a' => 'Yes, it is 100% natural and safe. For pregnant women, consult doctor before use.'],
        ['q' => 'How long to see results?', 'a' => 'Digestive benefits in 3-7 days, joint pain relief in 1-2 weeks with regular use.'],
        ['q' => 'Can I use it twice a day?', 'a' => 'Yes, you can use it morning and evening for faster results.'],
        ['q' => 'Does it have any side effects?', 'a' => 'No side effects as it is pure Ayurvedic formulation. Discontinue if any irritation occurs.'],
        ['q' => 'What is the Pechoti method?', 'a' => 'Pechoti method is ancient Ayurvedic therapy where oil is applied to the belly button (navel) as it contains 72,000+ nerve endings connected to vital organs.']
    ];
    
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/nabhyam-oil-about.png',
            'content' => 'Nabhyam Amrit Oil is a powerful Ayurvedic formulation based on the ancient Pechoti method. This unique blend of 10 pure oils works through the belly button (navel) - the center of 72,000+ nerve endings. It helps improve digestion, relieve joint pain, detoxify the body, and promote overall wellness naturally.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/nabhyam-how-to-use.png',
            'content' => '<strong>Step 1:</strong> Clean your belly button with normal warm water.<br><strong>Step 2:</strong> Apply 4-5 drops in your belly button & leave it for 15 minutes.<br><strong>Step 3:</strong> After 15 minutes, massage the remaining oil around your navel in circular motion for 2-3 minutes.<br><strong>Step 4:</strong> Repeat this process daily using Nabhyam Amrit Oil for best results.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/nabhyam-benefits.png',
            'content' => '✔ Improves digestion & relieves constipation<br>✔ Reduces joint pain & inflammation<br>✔ Detoxifies the body naturally<br>✔ Balances Vata, Pitta & Kapha doshas<br>✔ Enhances immunity & overall wellness<br>✔ Relieves menstrual cramps (for women)<br>✔ Promotes better sleep<br>✔ Nourishes skin from within'
        ]
    ];

// ================= 3. DY-B-FUEL RAS (No Amino Acids) =================
// IDs: 6 (Single), 13 (2-Pack Combo)
} elseif (in_array($prodId, [6, 13]) || (stripos($prodName, 'Dy-B-Fuel') !== false || stripos($prodName, 'Dy B Fuel') !== false)) {
    
    // $product_banners = [
    //     'assets/products-banners/dybfuel-ingredients.jpg', 
    //     'assets/products-banners/dybfuel-benefits.jpg',
    //     'assets/products-banners/dybfuel-how-to-use.jpg',
    // ];

    // DY-B-FUEL INGREDIENTS (18 Herbs)
    $ingredients = [
        ['name' => 'Neem (Azadirachta Indica) 🌿', 'qty' => '200mg', 'purpose' => 'Blood purification & natural detoxification 🧪'],
        ['name' => 'Karela (Bitter Gourd) 🥒', 'qty' => '500mg', 'purpose' => 'Regulates glucose absorption naturally ⚖️'],
        ['name' => 'Jamun Seed (Syzygium Cumini) 🍇', 'qty' => '500mg', 'purpose' => 'Supports healthy insulin function 💉'],
        ['name' => 'Giloy (Tinospora Cordifolia) 🌱', 'qty' => '500mg', 'purpose' => 'Boosts immunity & supports detox 🛡️'],
        ['name' => 'Gudmar (Gymnema Sylvestre) 🌿', 'qty' => '200mg', 'purpose' => 'Natural sugar regulator - "Sugar Destroyer" 🍬'],
        ['name' => 'Amla (Indian Gooseberry) 🍊', 'qty' => '750mg', 'purpose' => 'Rich antioxidant, supports metabolism ⚡'],
        ['name' => 'Harad (Haritaki) 🌰', 'qty' => '300mg', 'purpose' => 'Supports digestion & metabolic balance 🔥'],
        ['name' => 'Baheda (Bibhitaki) 🌰', 'qty' => '300mg', 'purpose' => 'Maintains digestive & metabolic health 🔥'],
        ['name' => 'Dana Methi (Fenugreek) 🌾', 'qty' => '150mg', 'purpose' => 'Reduces blood sugar spikes 📉'],
        ['name' => 'Ashwagandha (Withania Somnifera) 🌿', 'qty' => '100mg', 'purpose' => 'Stress balance & metabolic health 🧘‍♂️'],
        ['name' => 'Vijaysar (Pterocarpus Marsupium) 🪵', 'qty' => '100mg', 'purpose' => 'Supports pancreatic health & glucose control 🫀'],
        ['name' => 'Sarpgandha (Rauwolfia Serpentina) 🌿', 'qty' => '50mg', 'purpose' => 'Cardiovascular & metabolic balance ❤️'],
        ['name' => 'Sunth (Dry Ginger/Zingiber Officinale) 🌶️', 'qty' => '100mg', 'purpose' => 'Supports digestion & metabolism 🔥'],
        ['name' => 'Indrajo (Kutaj/Holarrhena Antidysenterica) 🌾', 'qty' => '100mg', 'purpose' => 'Regulates glucose metabolism ⚙️'],
        ['name' => 'Chirayata (Swertia Chirata) 🍃', 'qty' => '200mg', 'purpose' => 'Supports digestion & natural detoxification 🌀'],
        ['name' => 'Paneer Phool (Indian Rennet/Withania Coagulans) 🌸', 'qty' => '100mg', 'purpose' => 'Traditional blood sugar balance support ⚡'],
        ['name' => 'Aloe Vera (Aloe Barbadensis) 🌿', 'qty' => '100mg', 'purpose' => 'Natural blood sugar regulation 🎯'],
        ['name' => 'Babool (Acacia Nilotica) 🌿', 'qty' => '100mg', 'purpose' => 'Traditional Ayurvedic metabolic support 🎯']
    ];
    
    // NO AMINO ACIDS FOR DY-B-FUEL
    $aminoAcids = [];

    $faqs = [
        ['q' => 'When should I take this?', 'a' => 'Take 20-30ml twice daily before meals - 30 mins before breakfast and dinner.'],
        ['q' => 'Is it safe for long term use?', 'a' => 'Yes, it is 100% Ayurvedic and safe for extended use under physician guidance.'],
        ['q' => 'Can I take it with my diabetes medication?', 'a' => 'Yes, but consult your doctor for dosage adjustments and monitoring.'],
        ['q' => 'How soon will I see results?', 'a' => 'Energy improvement in 7 days, glucose stabilization in 2-4 weeks, HbA1c reduction in 90 days.'],
        ['q' => 'Is it safe for pregnant women?', 'a' => 'Not recommended for pregnant or lactating women unless prescribed.'],
        ['q' => 'Does it contain preservatives?', 'a' => 'Contains less than 0.2% Sodium Benzoate as natural preservative.'],
    ];
    
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/ingredients-flat.png',
            'content' => 'Dy-B-Fuel Ras is a time-tested Ayurvedic formulation with 18 powerful herbs designed to regulate blood sugar naturally. It works by improving insulin sensitivity, supporting pancreatic function, and reducing post-meal sugar spikes—without harmful chemicals.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/bottle-juice.png',
            'content' => 'Take 20–30 ml twice daily or as prescribed by Ayurvedic physician. Mix with equal amount of water and consume 30 minutes before breakfast and dinner for optimal results.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/bottle-juice.png',
            'content' => '✔ Supports healthy blood sugar levels<br>✔ Improves insulin sensitivity<br>✔ Reduces post-meal glucose spikes<br>✔ Supports pancreatic function<br>✔ Improves digestion & metabolism<br>✔ Enhances energy levels<br>✔ 100% herbal & safe'
        ]
    ];

// ================= 4. GOLD SHILAJIT RESIN (No Amino Acids) =================
// IDs: 24 (Single), 25 (2-Pack Combo)
} elseif (in_array($prodId, [24, 25]) || stripos($prodName, 'Shilajit') !== false) {
    
    // $product_banners = [
    //     'assets/products-banners/shilajit-benefits.jpg',
    //     'assets/products-banners/shilajit-how-to-use.jpg',
    //     'assets/products-banners/shilajit-purity.jpg',
    // ];

    // GOLD SHILAJIT INGREDIENTS (7X Power Formula)
    $ingredients = [
        ['name' => 'Shudha Shilajit (Purified) 🗻', 'qty' => '550mg per 1g', 'purpose' => '75% Fulvic Acid - Cellular energy & mineral transport ⚡'],
        ['name' => 'Ashwagandha (Withania Somnifera) 🌿', 'qty' => '200mg per 1g', 'purpose' => 'Stress management & muscle endurance support 🧘‍♂️'],
        ['name' => 'Safed Musli (Chlorophytum Borivilianum) ✨', 'qty' => '100mg per 1g', 'purpose' => 'Vitality, strength & reproductive wellness 💪'],
        ['name' => 'Gokshura (Tribulus Terrestris) 🔱', 'qty' => '74mg per 1g', 'purpose' => 'Urinary system health & natural energy production ⚡'],
        ['name' => 'Kaunch Beej (Mucuna Pruriens) 🧠', 'qty' => '74mg per 1g', 'purpose' => 'Natural L-Dopa for mood & cognitive function 🧠'],
        ['name' => 'Saffron/Kesar (Crocus Sativus) 🌷', 'qty' => '2.5mg per 1g', 'purpose' => 'Rejuvenation, skin health & emotional balance ✨'],
        ['name' => 'Swarn Bhasma (Purified Gold) 💎', 'qty' => '0.4mg per 1g', 'purpose' => 'Bio-enhancer for overall vitality & immunity 🛡️'],
      
        ['name' => 'Shilajit Resin Base 🗻', 'qty' => 'Pure Himalayan', 'purpose' => 'Sourced from 18,000ft altitude for maximum potency ⛰️']
    ];
    
    // NO AMINO ACIDS FOR SHILAJIT
    $aminoAcids = [];

    $faqs = [
        ['q' => 'How do I take Shilajit resin?', 'a' => 'Dissolve pea-sized amount (250-500mg) in warm water or milk. Take once daily.'],
        ['q' => 'When will I feel the effects?', 'a' => 'Energy boost in 3-7 days. Full vitality benefits in 4-8 weeks of consistent use.'],
        ['q' => 'Is it safe for daily use?', 'a' => 'Yes, when taken as directed. 100% natural and purified for safety.'],
        ['q' => 'Can women take Shilajit?', 'a' => 'Absolutely! Benefits include energy, immunity, and overall wellness for all genders.'],
        ['q' => 'Does it contain heavy metals?', 'a' => 'No, every batch is lab-tested for purity. Zero heavy metals guaranteed.'],
        ['q' => 'How to store the resin?', 'a' => 'Keep in cool, dry place. It may harden in cold - warm slightly before use.'],
        ['q' => 'What is 75% Fulvic Acid?', 'a' => 'Fulvic acid is the primary active compound. Higher percentage = better absorption & results.'],
    ];
    
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/shilajit-resin.png',
            'content' => 'LIVVRA Gold Shilajit is a premium resin sourced from 18,000ft Himalayan peaks. With 75% Fulvic Acid and enriched with Swarn Bhasma (purified gold), Ashwagandha, and Safed Musli, it supports cellular energy, stamina, strength, and overall vitality for men & women.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/shilajit-spoon.png',
            'content' => 'Take pea-sized amount (250-500mg) with provided spoon. Dissolve in warm water or milk. Consume once daily, preferably morning or 30 mins before workout. Consistent use for 90-120 days recommended.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/shilajit-benefits.png',
            'content' => '✔ 75% Fulvic Acid for cellular energy<br>✔ Supports stamina & endurance<br>✔ Enhances muscle recovery<br>✔ Natural testosterone support<br>✔ Immunity & detoxification<br>✔ Mental clarity & focus<br>✔ 80+ trace minerals'
        ]
    ];

// ================= 5. WELLNESS & GLOW COMBO (No Amino Acids) =================
// ID: 23 (Dy-B-Fuel + Kumkumadi Combo)
} elseif ($prodId === 23 || stripos($prodName, 'Wellness & Glow') !== false) {
    
    // $product_banners = [
    //     'assets/products-banners/combo-wellness-glow.jpg',
    //     'assets/products-banners/combo-inside-out.jpg',
    //     'assets/products-banners/combo-ritual.jpg',
    // ];

    // COMBO INGREDIENTS (Mixed from both products)
    $ingredients = [
        // From Dy-B-Fuel
        ['name' => 'Gudmar (Sugar Destroyer) 🍃', 'qty' => '200mg', 'purpose' => 'Blocks sugar absorption & supports glucose control 🍬'],
        ['name' => 'Jamun Seeds 🍇', 'qty' => '500mg', 'purpose' => 'Supports healthy insulin function 💉'],
        ['name' => 'Karela (Bitter Gourd) 🥒', 'qty' => '500mg', 'purpose' => 'Regulates glucose absorption naturally ⚖️'],
        ['name' => 'Neem 🌿', 'qty' => '200mg', 'purpose' => 'Blood purification & detox 🧪'],
        ['name' => 'Paneer Phool 🌸', 'qty' => '100mg', 'purpose' => 'Traditional pancreatic support ⚡'],
        // From Kumkumadi
        ['name' => 'Kashmir Saffron (Kesar) 🌺', 'qty' => 'Premium', 'purpose' => 'Skin brightening & natural glow ✨'],
        ['name' => 'Manjistha 🌿', 'qty' => '46gm', 'purpose' => 'Complexion clarifier & pigmentation reducer 🩸'],
        ['name' => 'Raktachandan 🌲', 'qty' => '46gm', 'purpose' => 'Cooling & skin soothing ❄️'],
        ['name' => 'Mulethi 🍯', 'qty' => '46gm', 'purpose' => 'Dark spot reduction & skin lightening 🌟'],
        ['name' => 'Goat Milk 🥛', 'qty' => 'Pure', 'purpose' => 'Natural moisturizing base 🛡️']
    ];
    
    // NO AMINO ACIDS FOR COMBO
    $aminoAcids = [];

    $faqs = [
        ['q' => 'How do I use this combo?', 'a' => 'Take Dy-B-Fuel 20-30ml twice daily before meals. Apply Kumkumadi oil 2-3 drops at night.'],
        ['q' => 'Why combine these products?', 'a' => 'True beauty starts with metabolic balance. This combo tackles root causes (blood sugar) and results (skin glow) simultaneously.'],
        ['q' => 'Is it safe for daily use?', 'a' => 'Yes, both products are 100% natural and safe for daily long-term use.'],
        ['q' => 'When will I see results?', 'a' => 'Energy in 7 days, skin radiance in 14 days, significant transformation in 60-90 days.'],
        ['q' => 'Is this pregnancy safe?', 'a' => 'Kumkumadi is safe. For Dy-B-Fuel, consult your doctor during pregnancy.'],
    ];
    
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/combo-wellness.png',
            'content' => 'The ultimate inside-out wellness protocol. Dy-B-Fuel Ras supports healthy blood sugar and metabolic balance, while Kumkumadi Beauty Oil delivers ancient Ayurvedic skin radiance. Together, they create complete holistic transformation.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/combo-ritual.png',
            'content' => '<strong>Morning:</strong> 20-30ml Dy-B-Fuel before breakfast<br><strong>Evening:</strong> 20-30ml Dy-B-Fuel before dinner<br><strong>Night:</strong> 2-3 drops Kumkumadi oil, massage and leave overnight'
        ],
        'benefits' => [
            'image' => 'assets/images/products/combo-benefits.png',
            'content' => '✔ Healthy blood sugar support<br>✔ Natural skin radiance<br>✔ Metabolic balance<br>✔ Even skin tone<br>✔ Sustained energy<br>✔ Anti-aging glow<br>✔ Complete holistic wellness'
        ]
    ];

// ================= DEFAULT/FALLBACK =================
} else {
    // Generic ingredients for any other product
    $ingredients = [
        ['name' => 'Natural Herbal Extracts 🌿', 'qty' => 'Premium Grade', 'purpose' => 'Traditional Ayurvedic wellness support'],
        ['name' => 'Pure Essential Oils 💧', 'qty' => 'Cold Pressed', 'purpose' => 'Natural therapeutic benefits'],
        ['name' => 'Plant-Based Actives 🍃', 'qty' => 'Organic', 'purpose' => 'Safe & effective holistic care']
    ];
    
    $aminoAcids = [];
    
    $faqs = [
        ['q' => 'How do I use this product?', 'a' => 'Please refer to the usage instructions on the product label or consult our team.'],
        ['q' => 'Is it safe for daily use?', 'a' => 'Yes, our products are formulated for safe daily use as directed.'],
        ['q' => 'Are there any side effects?', 'a' => 'Our products are 100% natural. Discontinue if any irritation occurs.'],
    ];
    
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/default.png',
            'content' => 'Premium Ayurvedic formulation crafted with traditional wisdom and modern quality standards.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/default-usage.png',
            'content' => 'Use as directed on the product packaging or consult with our Ayurvedic experts.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/default-benefits.png',
            'content' => '✔ Natural & Safe<br>✔ Traditional Ayurvedic Formula<br>✔ Quality Tested<br>✔ Holistic Wellness Support'
        ]
    ];
}
?>

<!-- ================= COMPLETE HTML OUTPUT ================= -->
<div class="product-page-premium" style="padding: 40px 0; background: #ffffff; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        
        <!-- MAIN PRODUCT GRID -->
        <div class="product-main-grid" style="display: flex; gap: 40px; flex-wrap: wrap;">
            
            <!-- LEFT: PRODUCT GALLERY -->
            <div class="product-gallery-col" style="flex: 1; min-width: 320px; position: sticky; top: 100px; height: fit-content;">
                <div class="main-media-box" style="background:#f7f7f7; border-radius:16px; padding:20px; min-height:450px; box-shadow:0 15px 40px rgba(0,0,0,0.06); position:relative; overflow:hidden;">
                    <div class="main-slider" id="mainSlider">
                        <!-- Main Product Image -->
                        <div class="slide">
                            <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width:100%; max-height:500px; object-fit:contain;">
                        </div>
                        <!-- Gallery Images -->
                        <?php foreach ($galleryMedia as $media): ?>
                            <?php if ($media['media_type'] !== 'video'): ?>
                                <div class="slide">
                                    <img src="uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>" alt="Gallery Image" style="max-width:100%; max-height:500px; object-fit:contain;">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Thumbnail Strip -->
                <div class="thumb-strip" style="display: flex; gap: 12px; margin-top: 20px; overflow-x: auto; padding-bottom: 10px;">
                    <!-- Main Image Thumb -->
                    <div class="thumb-item active" onclick="updateMedia('uploads/products/<?php echo htmlspecialchars($product['image']); ?>', 'image', this)" style="width: 75px; height: 75px; border: 2px solid #4f7c5b; border-radius: 10px; cursor: pointer; flex-shrink: 0; overflow: hidden; background: #fff;">
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <!-- Gallery Thumbs -->
                    <?php foreach ($galleryMedia as $media): ?>
                    <div class="thumb-item" onclick="updateMedia('uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>', '<?php echo $media['media_type']; ?>', this)" style="width: 75px; height: 75px; border: 1px solid #eee; border-radius: 10px; cursor: pointer; flex-shrink: 0; overflow: hidden; background: #fff;">
                        <?php if ($media['media_type'] === 'video'): ?>
                            <video src="uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;"></video>
                        <?php else: ?>
                            <img src="uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- RIGHT: PRODUCT INFO -->
            <div class="product-info-col" style="flex: 1; min-width: 320px;">
                <!-- Breadcrumb -->
                <nav style="font-size: 13px; color: #888; margin-bottom: 15px;">
                    Home / <?php echo htmlspecialchars($product['category_name'] ?? 'Ayurvedic'); ?> / <?php echo htmlspecialchars($product['name']); ?>
                </nav>
                
                <!-- Product Title -->
                <h1 style="font-size: 26px; font-weight: 700; color: #1f2937; line-height: 1.2; margin-bottom: 10px; font-family: 'Poppins', serif;">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>
                
                <!-- Short Description -->
                <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($product['short_description'] ?? 'Pure Ayurvedic Excellence'); ?>
                </p>

                <!-- Rating -->
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px;">
                    <div style="background: #4f7c5b; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 14px; font-weight: 600;">
                        <?php echo $product['rating'] ?? '4.8'; ?> <i class="fas fa-star" style="font-size: 10px;"></i>
                    </div>
                    <span style="color: #888; font-size: 14px;"><?php echo count($reviews); ?> Verified Reviews</span>
                </div>

                <!-- Price Section -->
                <div class="price-section" style="margin-bottom: 30px; background: #fdfdfd; border: 1px solid #f0f0f0; padding: 20px; border-radius: 12px;">
                    <?php 
                    $final_price = (float)($product['offer_price'] ?? $product['price']);
                    $mrp = (float)$product['mrp'];
                    $discount_percent = 0;
                    if ($mrp > $final_price) {
                        $discount_percent = round((($mrp - $final_price) / $mrp) * 100);
                    }
                    $calc_coins = floor($final_price * 0.05);
                    ?>
                    
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 8px; flex-wrap: wrap;">
                        <span style="font-size: 32px; font-weight: 700; color: #000;">
                            ₹<?php echo number_format($final_price, 0); ?>
                        </span>
                        <?php if ($mrp > $final_price): ?>
                            <span style="text-decoration: line-through; color: #999; font-size: 18px;">
                                ₹<?php echo number_format($mrp, 0); ?>
                            </span>
                            <span style="background: #eef7f1; color: #4f7c5b; padding: 4px 10px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                <?php echo $discount_percent; ?>% OFF
                            </span>
                        <?php endif; ?>
                    </div>
                    <p style="font-size: 13px; color: #666;">Inclusive of all taxes</p>
                    
                    <!-- Reward Coins -->
                    <div style="margin-top: 15px; display: flex; align-items: center; gap: 8px; color: #444; font-size: 14px;">
                        <i class="fas fa-coins" style="color: #f1c40f;"></i>
                        <span>Earn <b><?php echo $calc_coins; ?> Livvra coins</b> on this purchase</span>
                    </div>
                </div>

              <!-- Add to Cart Section -->
<div style="display: flex; gap: 15px; margin-bottom: 30px;">
    <?php if ($product['stock_qty'] > 0): ?>
        <!-- Quantity Selector -->
        <div style="display: flex; align-items: center; border: 1px solid #ddd; border-radius: 30px; height: 50px; background: #fff; overflow: hidden; width: 120px;">
            <button onclick="qtyAdj(-1)" style="flex: 1; border: none; background: none; font-size: 20px; cursor: pointer;">-</button>
            <input type="text" id="pQty" value="1" readonly style="width: 30px; text-align: center; border: none; font-weight: 600; font-size: 16px; outline: none;">
            <button onclick="qtyAdj(1)" style="flex: 1; border: none; background: none; font-size: 20px; cursor: pointer;">+</button>
        </div>
        <!-- Add to Cart -->
        <button onclick="pAddToCart()" style="flex: 2; height: 50px; background: #222; color: #fff; border: none; border-radius: 30px; font-weight: 600; cursor: pointer; transition: 0.3s;">
            ADD TO CART
        </button>
        <!-- Buy Now -->
        <button onclick="pBuyNow()" style="flex: 2; height: 50px; background: #4f7c5b; color: #fff; border: none; border-radius: 30px; font-weight: 600; cursor: pointer; transition: 0.3s;">
            BUY NOW
        </button>
    <?php else: ?>
        <!-- Out of Stock Message -->
        <div style="flex: 1; height: 50px; background: #f3f4f6; color: #9ca3af; border: 2px dashed #d1d5db; border-radius: 30px; display: flex; align-items: center; justify-content: center; font-weight: 600; cursor: not-allowed; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            OUT OF STOCK
        </div>
    <?php endif; ?>
</div>

                <!-- Trust Badges -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555;">
                        <div style="width: 35px; height: 35px; background: #f6f6f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f7c5b;">
                            <i class="fas fa-truck"></i>
                        </div>
                        Free Delivery
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555;">
                        <div style="width: 35px; height: 35px; background: #f6f6f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f7c5b;">
                            <i class="fas fa-undo"></i>
                        </div>
                        Easy Returns
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555;">
                        <div style="width: 35px; height: 35px; background: #f6f6f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f7c5b;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        Secure Payment
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555;">
                        <div style="width: 35px; height: 35px; background: #f6f6f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f7c5b;">
                            <i class="fas fa-leaf"></i>
                        </div>
                        100% Ayurvedic
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUCT DETAILS TABS -->
        <div style="margin-top: 60px;">
            <!-- Tab Navigation -->
            <div style="display: flex; border-bottom: 2px solid #eee; margin-bottom: 30px; gap: 30px; overflow-x: auto;">
                <div onclick="showSection('desc')" id="tab-desc" style="padding-bottom: 12px; cursor: pointer; color: #4f7c5b; border-bottom: 3px solid #4f7c5b; font-weight: 700;">
                    Description
                </div>
                <?php if (!empty($ingredients)): ?>
                <div onclick="showSection('ingr')" id="tab-ingr" style="padding-bottom: 12px; cursor: pointer; color: #666; font-weight: 600;">
                    Ingredients (<?php echo count($ingredients); ?>)
                </div>
                <?php endif; ?>
                
                <!-- AMINO ACIDS TAB - SIRF JAB DATA HO -->
                <?php if (!empty($aminoAcids)): ?>
                <div onclick="showSection('amino')" id="tab-amino" style="padding-bottom: 12px; cursor: pointer; color: #666; font-weight: 600;">
                    Amino Acids (<?php echo count($aminoAcids); ?>)
                </div>
                <?php endif; ?>
                
                <div onclick="showSection('revs')" id="tab-revs" style="padding-bottom: 12px; cursor: pointer; color: #666; font-weight: 600;">
                    Reviews (<?php echo count($reviews); ?>)
                </div>
            </div>

            <!-- Tab Content: Description -->
            <div id="sec-desc" class="detail-section" style="line-height: 1.8; color: #444;">
                <h3 style="margin-bottom: 20px; font-family: 'Playfair Display', serif;">About the Product</h3>
                <?php if (!empty($product['long_description'])): ?>
                    <?php echo $product['long_description']; ?>
                <?php else: ?>
                    <p><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available.')); ?></p>
                <?php endif; ?>
            </div>

            <!-- Tab Content: Ingredients -->
            <?php if (!empty($ingredients)): ?>
            <div id="sec-ingr" class="detail-section" style="display: none;">
                <h3 style="margin-bottom: 20px; font-family: 'Playfair Display', serif;">Key Ingredients</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    <?php foreach ($ingredients as $item): ?>
                    <div style="padding: 20px; border: 1px solid #eee; border-radius: 12px; background: #fafafa; transition: transform 0.2s;">
                        <h4 style="color: #4f7c5b; margin-bottom: 5px; font-size: 16px;"><?php echo $item['name']; ?></h4>
                        <p style="font-size: 14px; color: #666; margin-bottom: 8px;"><?php echo $item['purpose']; ?></p>
                        <span style="font-size: 12px; font-weight: 600; color: #888; background: #fff; padding: 2px 8px; border-radius: 4px;"><?php echo $item['qty']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tab Content: Amino Acids (SIRF YEAST PROTEIN) -->
            <?php if (!empty($aminoAcids)): ?>
            <div id="sec-amino" class="detail-section" style="display:none;">
                <h3 style="margin-bottom: 10px; font-family: 'Playfair Display', serif;">Amino Acid Profile</h3>
                <p style="margin-bottom: 20px; color: #666; font-size: 14px;">
                    <strong>Total BCAA:</strong> 5.2g | <strong>Total EAA:</strong> 12.02g | <strong>PDCAAS Score:</strong> 1.0
                </p>
                <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:15px;">
                    <?php foreach ($aminoAcids as $acid): ?>
                    <div style="padding:15px; border:1px solid #eee; border-radius:10px; background:#fafafa; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin:0; color:#4f7c5b; font-size: 14px;"><?php echo $acid[0]; ?></h4>
                        <span style="font-size:14px; color:#666; font-weight: 600;"><?php echo $acid[1]; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Amino Acid Legend -->
                <div style="margin-top: 20px; padding: 15px; background: #f0f7f0; border-radius: 8px; font-size: 13px; color: #555;">
                    <strong>Legend:</strong> 🧬 = Essential Amino Acid | (BCAA) = Branched Chain Amino Acid (Leucine, Isoleucine, Valine)
                </div>
            </div>
            <?php endif; ?>

            <!-- Tab Content: Reviews -->
            <div id="sec-revs" class="detail-section" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <h3 style="font-family: 'Playfair Display', serif;">Customer Reviews</h3>
                    <button onclick="document.getElementById('revForm').style.display='block'" style="background: #4f7c5b; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">
                        Write a Review
                    </button>
                </div>

                <!-- Review Success Message -->
                <?php if (isset($_GET['review']) && $_GET['review'] === 'success'): ?>
                    <div style="background: #eef7f1; color: #4f7c5b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i> Thank you! Your review has been submitted successfully.
                    </div>
                <?php endif; ?>

                <!-- Review Form -->
                <div id="revForm" style="display: none; background: #f9f9f9; padding: 25px; border-radius: 12px; margin-bottom: 40px; border: 1px solid #eee;">
                    <form action="includes/actions/add_review.php" method="POST" onsubmit="return validateReview()">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Your Name</label>
                            <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Rating</label>
                            <select name="rating" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="5">★★★★★ (5 Stars)</option>
                                <option value="4">★★★★☆ (4 Stars)</option>
                                <option value="3">★★★☆☆ (3 Stars)</option>
                                <option value="2">★★☆☆☆ (2 Stars)</option>
                                <option value="1">★☆☆☆☆ (1 Star)</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Your Review</label>
                            <textarea name="comment" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;"></textarea>
                        </div>
                        <button type="submit" style="background: #222; color: #fff; border: none; padding: 12px 25px; border-radius: 30px; cursor: pointer; font-weight: 600;">
                            Submit Review
                        </button>
                    </form>
                </div>

                <!-- Reviews List -->
                <?php if (empty($reviews)): ?>
                    <div style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 12px;">
                        <i class="fas fa-comments" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                        <p style="color: #888; font-size: 16px;">No reviews yet. Be the first to review this product!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                    <div style="border-bottom: 1px solid #eee; padding: 20px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; align-items: center;">
                            <b style="font-size: 16px; color: #333;"><?php echo htmlspecialchars($rev['name']); ?></b>
                            <div style="color: #f1c40f;">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="<?php echo $i <= $rev['rating'] ? 'fas fa-star' : 'far fa-star'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p style="color: #555; line-height: 1.6;"><?php echo htmlspecialchars($rev['comment']); ?></p>
                        <small style="color: #aaa; font-size: 12px;">
                            <i class="far fa-clock"></i> <?php echo date('d M, Y', strtotime($rev['created_at'])); ?>
                        </small>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ================= PRODUCT BANNERS SECTION ================= -->
<?php if (!empty($product_banners)): ?>
<div class="product-multi-banners-vertical" style="width: 100vw; margin-left: calc(-50vw + 50%); margin-top: 60px;">
    <?php foreach ($product_banners as $banner): ?>
        <div class="single-banner-vertical" style="width: 100%; overflow: hidden; position: relative; margin-bottom: 20px;">
            <img src="<?php echo $banner; ?>" alt="Product Banner" style="width: 100%; height: auto; display: block; transition: transform 0.6s ease;">
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ================= JAVASCRIPT ================= -->
<script>
// Update Main Image from Thumbnail
function updateMedia(url, type, thumb) {
    const slides = document.querySelectorAll('#mainSlider .slide');
    const slider = document.getElementById('mainSlider');

    // Reset all thumbnails
    document.querySelectorAll('.thumb-item').forEach(el => {
        el.style.borderColor = '#eee';
        el.style.borderWidth = '1px';
    });

    // Highlight active thumbnail
    thumb.style.borderColor = '#4f7c5b';
    thumb.style.borderWidth = '2px';

    // Scroll to corresponding slide
    slides.forEach((slide, index) => {
        const img = slide.querySelector('img');
        const video = slide.querySelector('video');

        if ((img && img.src.includes(url)) || (video && video.src.includes(url))) {
            slider.scrollTo({
                left: slide.offsetLeft,
                behavior: 'smooth'
            });
        }
    });
}

// Quantity Adjustment
function qtyAdj(val) {
    const el = document.getElementById('pQty');
    let v = parseInt(el.value) + val;
    if (v < 1) v = 1;
    if (v > 10) v = 10; // Max limit
    el.value = v;
}

// Add to Cart
function pAddToCart() {
    const q = document.getElementById('pQty').value;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cart.php';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add';
    form.appendChild(actionInput);
    
    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'product_id';
    idInput.value = '<?php echo $id; ?>';
    form.appendChild(idInput);
    
    const qtyInput = document.createElement('input');
    qtyInput.type = 'hidden';
    qtyInput.name = 'quantity';
    qtyInput.value = q;
    form.appendChild(qtyInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Buy Now
function pBuyNow() {
    const q = document.getElementById('pQty').value;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cart.php';

    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add';
    form.appendChild(actionInput);

    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'product_id';
    idInput.value = '<?php echo $id; ?>';
    form.appendChild(idInput);

    const qtyInput = document.createElement('input');
    qtyInput.type = 'hidden';
    qtyInput.name = 'quantity';
    qtyInput.value = q;
    form.appendChild(qtyInput);

    const redirectInput = document.createElement('input');
    redirectInput.type = 'hidden';
    redirectInput.name = 'redirect_to';
    redirectInput.value = 'checkout';
    form.appendChild(redirectInput);

    document.body.appendChild(form);
    form.submit();
}

// Tab Switching
function showSection(id) {
    // Hide all sections
    document.querySelectorAll('.detail-section').forEach(s => s.style.display = 'none');
    // Show selected section
    document.getElementById('sec-' + id).style.display = 'block';
    
    // Reset all tabs
    document.querySelectorAll('[id^="tab-"]').forEach(t => {
        t.style.color = '#666';
        t.style.borderBottom = 'none';
        t.style.fontWeight = '600';
    });
    
    // Highlight active tab
    const activeTab = document.getElementById('tab-' + id);
    activeTab.style.color = '#4f7c5b';
    activeTab.style.borderBottom = '3px solid #4f7c5b';
    activeTab.style.fontWeight = '700';
}

// Review Form Validation
function validateReview() {
    const name = document.querySelector('input[name="name"]').value.trim();
    const comment = document.querySelector('textarea[name="comment"]').value.trim();
    
    if (name.length < 2) {
        alert('Please enter your name');
        return false;
    }
    if (comment.length < 10) {
        alert('Please write at least 10 characters in your review');
        return false;
    }
    return true;
}

// Mobile Auto-scroll Thumbnails
document.addEventListener("DOMContentLoaded", function () {
    const strip = document.querySelector('.thumb-strip');
    if (window.innerWidth <= 768 && strip) {
        let scrollAmount = 0;
        setInterval(() => {
            scrollAmount += 90;
            if (scrollAmount >= strip.scrollWidth - strip.clientWidth) {
                scrollAmount = 0;
            }
            strip.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }, 3000);
    }
});
</script>

<!-- ================= STYLES ================= -->
<style>
/* Main Slider Styles */
.main-slider {
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}

.slide {
    min-width: 100%;
    scroll-snap-align: start;
    display: flex;
    justify-content: center;
    align-items: center;
}

.main-slider::-webkit-scrollbar {
    display: none;
}

/* Thumbnail Strip */
.thumb-strip::-webkit-scrollbar {
    display: none;
}

/* Product Banners */
.product-multi-banners-vertical .single-banner-vertical:hover img {
    transform: scale(1.04);
}

.single-banner-vertical::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.05), rgba(0,0,0,0.25));
    opacity: 0;
    transition: 0.4s ease;
}

.single-banner-vertical:hover::after {
    opacity: 1;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .product-main-grid { 
        flex-direction: column !important; 
    }
    .product-gallery-col { 
        position: relative !important; 
        top: 0 !important; 
        width: 100% !important; 
    }
    .product-info-col { 
        width: 100% !important; 
    }
    .price-section { 
        padding: 15px !important; 
    }
    h1 { 
        font-size: 20px !important; 
    }
    .thumb-strip {
        width: calc(100vw - 30px);
    }
}

/* Hover Effects */
.thumb-item:hover {
    transform: scale(1.05);
    transition: transform 0.2s;
}

/* Tab Animation */
.detail-section {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>