<?php
require_once 'includes/config.php';
$pageTitle = 'Benefits of Ayurveda';
require_once 'includes/header.php';
?>
<!-- Page Banner - 1270px Width × 350px Height -->
<section class="page-banner" style="
    position: relative; 
    width: 100%; 
    max-width: 100%;
    height: 350px; 
    margin: 0 auto;
    background: url('assets/images/herobanner/2.jpg (1).jpeg') center/cover no-repeat; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    overflow: hidden;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
">
    
    <div class="page-banner-content" style="
        position: relative; 
        z-index: 2; 
        width: 100%; 
        padding: 0 40px; 
        text-align: center; 
        color: white;
    ">
        <h1 style="
            font-family: var(--font-display), serif; 
            font-size: clamp(2.8rem, 6vw, 4.2rem); 
            font-weight: 900; 
            margin: 0 0 15px 0; 
            text-shadow: 3px 3px 15px rgba(0,0,0,0.9); 
            line-height: 1.1; 
            letter-spacing: -0.02em;
        ">Ayurvedic Wellness Benefits</h1>
        
        <div class="breadcrumb" style="
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 10px; 
            font-size: 15px; 
            font-weight: 400; 
            opacity: 0.95;
        ">
            <a href="index.php" style="
                color: rgba(255,255,255,0.95); 
                text-decoration: none; 
                font-weight: 500;
            ">
                <i class="fas fa-home" style="margin-right: 6px;"></i>Home
            </a>
            <span style="color: rgba(255,255,255,0.8);">/</span>
            <span style="color: rgba(255,255,255,0.9); font-weight: 600;">Benefits</span>
        </div>
    </div>
</section>

<style>
.page-banner {
    margin-bottom: 40px !important;
}

/* Container centering */
.container .page-banner {
    max-width: 1270px !important;
    margin-left: auto !important;
    margin-right: auto !important;
}

/* Responsive scaling */
@media (max-width: 1300px) {
    .page-banner {
        max-width: 100% !important;
        margin: 0 15px !important;
        height: 320px !important;
    }
}

@media (max-width: 768px) {
    .page-banner {
        height: 280px !important;
        margin: 0 10px !important;
        border-radius: 15px !important;
    }
    
    .page-banner-content {
        padding: 0 25px !important;
    }
    
    .page-banner-content h1 {
        font-size: clamp(2.2rem, 8vw, 3.5rem) !important;
        margin-bottom: 12px !important;
    }
}

@media (max-width: 480px) {
    .page-banner {
        height: 240px !important;
        border-radius: 12px !important;
    }
    
    .page-banner-content {
        padding: 0 20px !important;
    }
    
    .page-banner-content h1 {
        text-shadow: 4px 4px 20px rgba(0,0,0,1) !important;
        font-size: clamp(2rem, 9vw, 3rem) !important;
    }
    
    .breadcrumb {
        font-size: 13px !important;
        gap: 8px !important;
    }
}
</style>



<!-- Introduction Section -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-badge"><i class="fas fa-leaf"></i> Ancient Wisdom</span>
            <h2 class="section-title">The Power of <span>Ayurveda</span></h2>
            <p class="section-subtitle">5,000 years of healing wisdom for modern wellness</p>
        </div>
        <div class="benefits-intro reveal">
            <p>Ayurveda, the ancient Indian science of life, teaches that true wellness comes from achieving balance between the body, mind, and spirit. Unlike modern medicine which treats symptoms, Ayurveda works to restore the root cause of imbalance, naturally and gently.</p>
        </div>
    </div>
</section>

<!-- Three Doshas Section -->
<section class="section section-light">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-badge"><i class="fas fa-balance-scale"></i> Three Doshas</span>
            <h2 class="section-title">Understanding <span>Balance</span></h2>
            <p class="section-subtitle">The foundation of Ayurvedic wellness</p>
        </div>
        <div class="benefits-grid">
            <div class="benefit-card reveal">
                <div class="benefit-icon" style="background: linear-gradient(135deg, #FFB88C, #FFA366);">
                    <i class="fas fa-wind"></i>
                </div>
                <h3>Vata Dosha</h3>
                <p><strong>Element:</strong> Air & Space</p>
                <p>Governs movement, flexibility, creativity, and communication. When balanced, brings vitality and enthusiasm. When imbalanced, causes anxiety and restlessness.</p>
                <ul style="text-align: left; margin: 15px 0;">
                    <li>✓ Supports nervous system</li>
                    <li>✓ Enhances circulation</li>
                    <li>✓ Improves mental clarity</li>
                </ul>
            </div>
            <div class="benefit-card reveal delay-100">
                <div class="benefit-icon" style="background: linear-gradient(135deg, #FFD93D, #FFB627);">
                    <i class="fas fa-fire"></i>
                </div>
                <h3>Pitta Dosha</h3>
                <p><strong>Element:</strong> Fire & Water</p>
                <p>Controls digestion, metabolism, intelligence, and courage. When balanced, brings confidence and intelligence. When imbalanced, causes inflammation and irritability.</p>
                <ul style="text-align: left; margin: 15px 0;">
                    <li>✓ Supports digestion</li>
                    <li>✓ Enhances metabolism</li>
                    <li>✓ Promotes mental focus</li>
                </ul>
            </div>
            <div class="benefit-card reveal delay-200">
                <div class="benefit-icon" style="background: linear-gradient(135deg, #6BCB77, #4D96FF);">
                    <i class="fas fa-water"></i>
                </div>
                <h3>Kapha Dosha</h3>
                <p><strong>Element:</strong> Earth & Water</p>
                <p>Provides structure, stability, and nourishment. When balanced, brings strength and compassion. When imbalanced, causes heaviness and stagnation.</p>
                <ul style="text-align: left; margin: 15px 0;">
                    <li>✓ Strengthens immunity</li>
                    <li>✓ Nourishes tissues</li>
                    <li>✓ Promotes stability</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Product Benefits Section -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-badge"><i class="fas fa-heart"></i> LIVVRA Benefits</span>
            <h2 class="section-title">How Our Products <span>Support Your Wellness</span></h2>
            <p class="section-subtitle">Holistic support for your health journey</p>
        </div>
        <div class="benefits-grid">
            <div class="benefit-card reveal">
                <div class="benefit-icon">
                    <i class="fas fa-spa"></i>
                </div>
                <h3>Natural & Pure</h3>
                <p>100% herbal ingredients sourced from organic farms, free from chemicals, additives, and harmful preservatives.</p>
            </div>
            <div class="benefit-card reveal delay-100">
                <div class="benefit-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Lab Tested</h3>
                <p>Every batch undergoes rigorous quality testing for safety, potency, and purity in certified laboratories.</p>
            </div>
            <div class="benefit-card reveal delay-200">
                <div class="benefit-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>GMP Certified</h3>
                <p>Manufactured in state-of-the-art GMP certified facilities following strict international quality standards.</p>
            </div>
            <div class="benefit-card reveal delay-300">
                <div class="benefit-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Root Cause Healing</h3>
                <p>We focus on addressing the root cause of imbalance rather than just treating symptoms, for lasting wellness.</p>
            </div>
            <div class="benefit-card reveal">
                <div class="benefit-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Preventive Health</h3>
                <p>Our products support preventive care, helping you maintain health before illness develops.</p>
            </div>
            <div class="benefit-card reveal delay-100">
                <div class="benefit-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3>Expert Formulated</h3>
                <p>Created by experienced Ayurvedic doctors and researchers with deep knowledge of traditional wisdom.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section section-light">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-badge"><i class="fas fa-question-circle"></i> FAQ</span>
            <h2 class="section-title">Frequently Asked <span>Questions</span></h2>
            <p class="section-subtitle">Learn more about Ayurvedic wellness</p>
        </div>
        <div class="faq-container">
            <div class="faq-item reveal">
                <div class="faq-question">
                    <h4>What is Ayurveda and how does it work?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Ayurveda is a 5,000-year-old holistic healing system from India that focuses on balancing the three doshas (Vata, Pitta, and Kapha) to achieve optimal health. It works by addressing the root cause of imbalance rather than just treating symptoms.</p>
                </div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question">
                    <h4>Are LIVVRA products safe for daily use?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, LIVVRA products are formulated for safe, long-term use. All ingredients are natural, lab-tested, and GMP certified. However, if you are pregnant, nursing, or under medical treatment, consult an Ayurvedic physician before use.</p>
                </div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question">
                    <h4>How long does it take to see results?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Ayurvedic products work holistically and naturally. While some benefits may be felt within 2-4 weeks, optimal results typically require consistent use for 2-3 months as your body rebalances naturally.</p>
                </div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question">
                    <h4>Can I use multiple LIVVRA products together?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, many LIVVRA products are designed to complement each other for holistic wellness. However, for personalized guidance on combining products based on your dosha type, consult our Ayurvedic team.</p>
                </div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question">
                    <h4>What makes LIVVRA different from other brands?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>LIVVRA combines authentic Ayurvedic wisdom with modern quality standards. Our products are formulated by expert Ayurvedic doctors, made with premium natural ingredients, and backed by rigorous testing and certifications.</p>
                </div>
            </div>
            <div class="faq-item reveal">
                <div class="faq-question">
                    <h4>Are your products suitable for vegetarians and vegans?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Most LIVVRA products are vegetarian and vegan-friendly. We use plant-based ingredients and natural excipients. Check individual product labels for specific dietary information.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.faq-container {
    max-width: 900px;
    margin: 0 auto;
}

.faq-item {
    background: white;
    margin: 15px 0;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.faq-question {
    padding: 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s ease;
}

.faq-question:hover {
    background: var(--light-bg);
}

.faq-question h4 {
    margin: 0;
    color: var(--text-dark);
    font-size: 16px;
    font-weight: 600;
}

.faq-question i {
    color: var(--primary-gold);
    transition: transform 0.3s ease;
}

.faq-answer {
    padding: 0 20px 20px;
    display: none;
    color: var(--text-light);
    line-height: 1.8;
}

.faq-item.active .faq-answer {
    display: block;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-item.active .faq-question {
    background: var(--light-bg);
}
</style>

<!-- CTA Section -->
<section class="newsletter">
    <div class="container">
        <div class="newsletter-content" style="flex-direction: column; text-align: center; gap: 25px;">
            <div class="newsletter-text">
                <h3>Start Your Wellness Journey Today</h3>
                <p>Discover the transformative power of Ayurvedic wellness with LIVVRA</p>
            </div>
            <a href="products.php" class="view-all-btn" style="margin-top: 0;">
                Explore Products <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<script>
// FAQ Toggle
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', function() {
        this.parentElement.classList.toggle('active');
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
