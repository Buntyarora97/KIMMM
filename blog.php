<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle       = 'Ayurvedic Wellness Blogs | Natural Health Tips & Guides';
$metaDescription = 'Read the latest ayurvedic wellness blogs. Learn about pure shilajit, kumkumadi tailam benefits, vegan protein tips, and natural health products in India.';
$metaKeywords    = 'wellness ayurveda articles, ayurvedic blog, holistic living tips, herbal remedies guide';
$canonicalUrl    = 'https://livvra.in/ayurvedic-health-blog';
$header_path = __DIR__ . '/includes/header.php';
if (file_exists($header_path)) {
    include $header_path;
} else {
    include 'header.php';
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIVVRA Ayurveda Blog - Wellness & Health</title>
    <meta name="description" content="Discover Ayurvedic wisdom for modern wellness. Immunity boosting herbs, daily routines, preventive healthcare, and holistic balance.">
    
    <style>
        :root {
            --livvra-deep-green: #556B2F;
            --livvra-olive-green: #6B8E23;
            --livvra-light-green: #9ACD32;
            --livvra-gold: #FFD700;
            --livvra-cream: #FDF6E3;
            --livvra-text-dark: #2C3E50;
            --livvra-shadow: 0 8px 32px rgba(85, 107, 47, 0.2);
        }
        
        body {
            background: var(--livvra-cream);
            color: var(--livvra-text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .livvra-blog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .livvra-page-hero {
            text-align: center;
            background: linear-gradient(135deg, var(--livvra-deep-green), var(--livvra-olive-green));
            color: white;
            padding: 80px 20px;
            border-radius: 20px;
            margin-bottom: 60px;
            box-shadow: var(--livvra-shadow);
        }
        
        .livvra-hero-title {
            font-size: 3rem;
            margin: 0 0 20px;
            font-weight: 700;
        }
        
        .livvra-hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .livvra-blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .livvra-blog-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--livvra-shadow);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            height: 510px; /* Increased from 420px */
            display: flex;
            flex-direction: column;
        }
        
        .livvra-blog-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 48px rgba(85, 107, 47, 0.3);
        }
        
        .livvra-card-image {
            height: 250px; /* Increased from 200px - ab bada image! */
            background-size: cover;
            background-position: center;
            position: relative;
            flex-shrink: 0;
        }
   
        
        .livvra-card-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* background: linear-gradient(45deg, var(--livvra-deep-green), var(--livvra-gold)); */
            opacity: 0.7;
        }
        
        .livvra-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.8);
        }
        
        .livvra-card-date {
            position: absolute;
            bottom: -243px;
            right: 15px;
            background: var(--livvra-gold);
            color: var(--livvra-deep-green);
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.4);
            z-index: 2;
        }
        
        .livvra-card-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .livvra-card-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 12px;
            color: var(--livvra-deep-green);
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .livvra-card-excerpt {
            color: var(--livvra-text-dark);
            font-size: 0.95rem;
            margin-bottom: 20px;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .livvra-read-more {
            display: inline-block;
            background: linear-gradient(135deg, var(--livvra-olive-green), var(--livvra-light-green));
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            align-self: flex-start;
        }
        
        .livvra-read-more:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 24px rgba(107, 142, 35, 0.4);
        }
        
        @media (max-width: 768px) {
            .livvra-hero-title {
                font-size: 2.2rem;
            }
            .livvra-blog-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .livvra-blog-card {
                height: 540px; /* Mobile me bhi bada */
            }
            .livvra-card-image {
                height: 250px; /* Mobile me aur bada */
            }
        }
        
        .livvra-section-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--livvra-deep-green);
            margin-bottom: 10px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="livvra-blog-container">
        <section class="livvra-page-hero">
            <h1 class="livvra-hero-title">Ayurveda Wellness Blog</h1>
            <p class="livvra-hero-subtitle">Ancient wisdom meets modern living. Discover herbal remedies, daily routines, and holistic health principles for immunity, balance, and vitality.</p>
        </section>
        
        <h2 class="livvra-section-title">Latest Articles</h2>
        <!-- Internal CSS -->
<style>
/* Blog Listing Page Styles */
.blog-section {
    padding: 60px 0;
    background-color: #f8f9fa;
}

.blog-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.blog-header {
    text-align: center;
    margin-bottom: 50px;
}

.blog-header h1 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 15px;
    font-weight: 700;
}

.blog-header p {
    font-size: 1.1rem;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto;
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.blog-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.blog-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.blog-card-image {
    width: 100%;
    height: 220px;
    overflow: hidden;
    position: relative;
}

.blog-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.blog-card:hover .blog-card-image img {
    transform: scale(1.08);
}

.blog-card-content {
    padding: 25px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.blog-card-date {
    font-size: 0.85rem;
    color: #888;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.blog-card-date::before {
    content: "📅";
    font-size: 0.9rem;
}

.blog-card-title {
    font-size: 1.3rem;
    color: #2c3e50;
    margin-bottom: 12px;
    font-weight: 600;
    line-height: 1.4;
}

.blog-card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.blog-card-title a:hover {
    color: #27ae60;
}

.blog-card-excerpt {
    font-size: 0.95rem;
    color: #555;
    line-height: 1.7;
    margin-bottom: 20px;
    flex-grow: 1;
}

.blog-card-footer {
    margin-top: auto;
}

.read-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: #fff;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.read-more-btn:hover {
    background: linear-gradient(135deg, #229954, #27ae60);
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
}

.read-more-btn::after {
    content: "→";
    transition: transform 0.3s ease;
}

.read-more-btn:hover::after {
    transform: translateX(4px);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .blog-section {
        padding: 40px 0;
    }
    
    .blog-header h1 {
        font-size: 2rem;
    }
    
    .blog-grid {
        grid-template-columns: 1fr;
        gap: 25px;
    }
    
    .blog-card-image {
        height: 200px;
    }
    
    .blog-card-content {
        padding: 20px;
    }
    
    .blog-card-title {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .blog-header h1 {
        font-size: 1.75rem;
    }
    
    .blog-card-image {
        height: 180px;
    }
}
</style>

<!-- Blog Listing Section -->
        <div class="livvra-blog-grid">
                        <article class="blog-card">
                <div class="blog-card-image">
                    <a href="dy-b-fuel-ras-ayurvedic-support.php">
                        <img src="https://livvra.in/uploads/products/img_698adf7837ca60.85398271.jpg" alt="Dy-B-Fuel Ras: Ayurvedic Support for Energy & Digestion" loading="lazy">
                    </a>
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-date">February 20, 2025</div>
                    <h2 class="blog-card-title">
                        <a href="dy-b-fuel-ras-ayurvedic-support.php">Dy-B-Fuel Ras: Ayurvedic Support for Energy & Digestion</a>
                    </h2>
                    <p class="blog-card-excerpt">Discover Dy-B-Fuel Ras, an Ayurvedic formulation enriched with Gudmar, Paneer Phool, and 17+ powerful herbs designed to support daily energy, digestion, and long-term metabolic harmony naturally...</p>
                    <div class="blog-card-footer">
                        <a href="dy-b-fuel-ras-ayurvedic-support.php" class="read-more-btn">Read More</a>
                    </div>
                </div>
            </article>
            
            <!-- Blog Card 2: Kumkumadi Beauty Oil -->
            <article class="blog-card">
                <div class="blog-card-image">
                    <a href="benefits-of-kumkumadi-beauty-oil.php">
                        <img src="https://livvra.in/uploads/products/img_698ae5c60ede40.95178653.jpg" alt="Benefits of Kumkumadi Beauty Oil for Radiant Skin" loading="lazy">
                    </a>
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-date">February 18, 2025</div>
                    <h2 class="blog-card-title">
                        <a href="benefits-of-kumkumadi-beauty-oil.php">Benefits of Kumkumadi Beauty Oil for Radiant Skin</a>
                    </h2>
                    <p class="blog-card-excerpt">Explore the benefits of Kumkumadi Beauty Oil by Livvra, enriched with pure Saffron, Manjistha, and 20+ rare herbs to brighten skin tone, reduce pigmentation, and restore natural glow...</p>
                    <div class="blog-card-footer">
                        <a href="benefits-of-kumkumadi-beauty-oil.php" class="read-more-btn">Read More</a>
                    </div>
                </div>
            </article>
            
            <!-- Blog Card 3: Yeast-Based Protein -->
            <article class="blog-card">
                <div class="blog-card-image">
                    <a href="is-yeast-based-protein-safe-for-daily-consumption.php">
                        <img src="https://livvra.in/uploads/products/img_699d5d6eda2658.36450188.png" alt="Is Yeast-Based Protein Safe for Daily Consumption?" loading="lazy">
                    </a>
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-date">February 15, 2025</div>
                    <h2 class="blog-card-title">
                        <a href="is-yeast-based-protein-safe-for-daily-consumption.php">Is Yeast-Based Protein Safe for Daily Consumption?</a>
                    </h2>
                    <p class="blog-card-excerpt">Learn about yeast-based protein benefits, safety, digestion, and how Livvra Yeast-Based Protein with 25g protein per scoop supports muscle recovery, energy, and gut health naturally...</p>
                    <div class="blog-card-footer">
                        <a href="is-yeast-based-protein-safe-for-daily-consumption.php" class="read-more-btn">Read More</a>
                    </div>
                </div>
            </article>
            
            <!-- Blog Card 4: Daily Energy & Skin Care -->
            <article class="blog-card">
                <div class="blog-card-image">
                    <a href="daily-energy-nutrition-and-skin-care-problems-explained.php">
                        <img src="/assets/images/about/WhatsApp%20Image%202026-03-12%20at%203.06.40%20PM.jpeg" alt="Daily Energy, Nutrition & Skin Care Problems Explained" loading="lazy">
                    </a>
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-date">February 13, 2025</div>
                    <h2 class="blog-card-title">
                        <a href="daily-energy-nutrition-and-skin-care-problems-explained.php">Daily Energy, Nutrition & Skin Care Problems Explained</a>
                    </h2>
                    <p class="blog-card-excerpt">Struggling with low energy, poor nutrition, and skin issues? Learn how Dy-B-Fuel Ras, Yeast-Based Protein, and Kumkumadi Beauty Oil work together for complete inside-out wellness...</p>
                    <div class="blog-card-footer">
                        <a href="daily-energy-nutrition-and-skin-care-problems-explained.php" class="read-more-btn">Read More</a>
                    </div>
                </div>
            </article>
            <!-- Blog Card 1 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog1.png');">
                    <div class="livvra-card-date">📅 8 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/daily-immunity-boost-ayurvedic-herbs-modern-wellness.php" style="color: inherit; text-decoration: none;">Daily Immunity Boost: The Role of Ayurvedic Herbs in Modern Wellness</a>
                    </h3>
                    <p class="livvra-card-excerpt">Discover how Ayurvedic herbs support daily immunity and overall wellness. Learn how ancient remedies fit seamlessly into modern healthy living.</p>
                    <a href="/daily-immunity-boost-ayurvedic-herbs-modern-wellness.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Card 2 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog2.png');">
                    <div class="livvra-card-date">📅 12 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/ayurveda-2026-modern-preventive-healthcare.php" style="color: inherit; text-decoration: none;">Ayurveda in 2026: How Ancient Herbal Science Fits Modern Preventive Healthcare</a>
                    </h3>
                    <p class="livvra-card-excerpt">Explore how Ayurveda in 2026 is shaping modern preventive healthcare through herbal science, immunity building, lifestyle balance, and holistic wellness.</p>
                    <a href="/ayurveda-2026-modern-preventive-healthcare.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Card 3 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog3.png');">
                    <div class="livvra-card-date">📅 18 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/7-step-daily-ayurvedic-routine-optimal-health.php" style="color: inherit; text-decoration: none;">7-Step Daily Ayurvedic Routine for Optimal Health and Wellness</a>
                    </h3>
                    <p class="livvra-card-excerpt">Follow a 7-step daily Ayurvedic routine designed for modern life to improve digestion, immunity, energy, and long-term holistic wellness.</p>
                    <a href="/7-step-daily-ayurvedic-routine-optimal-health.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Card 4 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog4.png');">
                    <div class="livvra-card-date">📅 22 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/ayurveda-modern-diets-balanced-weight-management.php" style="color: inherit; text-decoration: none;">Ayurveda and Modern Diets: A Balanced Approach to Weight Management</a>
                    </h3>
                    <p class="livvra-card-excerpt">Learn how Ayurveda complements modern diets for sustainable weight management through digestion balance, mindful eating, and lifestyle alignment.</p>
                    <a href="/ayurveda-modern-diets-balanced-weight-management.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Card 5 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog5.png');">
                    <div class="livvra-card-date">📅 25 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/core-principles-of-ayurveda-modern-life.php" style="color: inherit; text-decoration: none;">Core Principles of Ayurveda: Their Importance and Relevance in Today's Life</a>
                    </h3>
                    <p class="livvra-card-excerpt">Understand the core principles of Ayurveda and why they remain deeply relevant in today's fast-paced lifestyle for health, balance, and well-being.</p>
                    <a href="/core-principles-of-ayurveda-modern-life.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
        </div>
    </div>

<?php  
$footer_path = __DIR__ . '/includes/footer.php';  
if (file_exists($footer_path)) {  
    include $footer_path;  
} else {  
    include 'footer.php';  
}  
?>  
</body>
</html>
