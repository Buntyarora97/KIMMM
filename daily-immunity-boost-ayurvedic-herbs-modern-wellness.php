<?php  
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
    <title>Daily Immunity Boost: The Role of Ayurvedic Herbs in Modern Wellness</title>
    <meta name="description" content="Discover how Ayurvedic herbs support daily immunity and overall wellness. Learn how ancient remedies fit seamlessly into modern healthy living.">
    <meta name="keywords" content="Daily immunity boost, Ayurvedic herbs for immunity, Natural immunity boosters, Ayurvedic immunity supplements, Herbal immunity support">
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--livvra-cream);
            color: var(--livvra-text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.7;
            overflow-x: hidden;
        }
        
        .livvra-article-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .livvra-hero-section {
            background: linear-gradient(135deg, var(--livvra-deep-green), var(--livvra-olive-green));
            color: white;
            padding: 100px 40px;
            text-align: center;
            border-radius: 25px;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--livvra-shadow);
        }
        
        .livvra-hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
            animation: livvra-float 20s ease-in-out infinite;
        }
        
        @keyframes livvra-float {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }
        
        .livvra-hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }
        
        .livvra-hero-image {
            width: 100%;
            max-width: 800px;
            /* height: 400px; */
            object-fit: cover;
            border-radius: 20px;
            margin: 40px auto;
            display: block;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .livvra-section {
            background: white;
            padding: 50px;
            margin-bottom: 40px;
            border-radius: 20px;
            box-shadow: var(--livvra-shadow);
            border-left: 6px solid var(--livvra-gold);
        }
        
        .livvra-section-title {
            color: var(--livvra-deep-green);
            font-size: 2.2rem;
            margin-bottom: 30px;
            font-weight: 700;
            position: relative;
        }
        
        .livvra-section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--livvra-gold);
            border-radius: 2px;
        }
        
        .livvra-herb-card {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 30px;
            align-items: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8fff5, #e8f5e8);
            border-radius: 15px;
            margin-bottom: 25px;
            border: 2px solid var(--livvra-light-green);
            transition: all 0.3s ease;
        }
        
        .livvra-herb-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(107, 142, 35, 0.2);
        }
        
        .livvra-herb-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--livvra-olive-green), var(--livvra-light-green));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(107, 142, 35, 0.3);
        }
        
        .livvra-herb-info h4 {
            color: var(--livvra-deep-green);
            font-size: 1.4rem;
            margin-bottom: 10px;
        }
        
        .livvra-best-for {
            background: var(--livvra-gold);
            color: var(--livvra-deep-green);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
        }
        
        .livvra-routine-list {
            list-style: none;
            padding-left: 0;
        }
        
        .livvra-routine-list li {
            background: rgba(107, 142, 35, 0.1);
            margin-bottom: 15px;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid var(--livvra-olive-green);
            transition: all 0.3s ease;
        }
        
        .livvra-routine-list li:hover {
            background: rgba(107, 142, 35, 0.2);
            transform: translateX(10px);
        }
        
        .livvra-faq-item {
            margin-bottom: 25px;
        }
        
        .livvra-faq-question {
            background: linear-gradient(135deg, var(--livvra-deep-green), var(--livvra-olive-green));
            color: white;
            padding: 20px 30px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .livvra-faq-answer {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            border-left: 5px solid var(--livvra-gold);
            display: none;
            animation: livvra-slideDown 0.4s ease;
        }
        
        .livvra-faq-answer.active {
            display: block;
        }
        
        @keyframes livvra-slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .livvra-cta-section {
            text-align: center;
            background: linear-gradient(135deg, var(--livvra-gold), #FFA500);
            color: var(--livvra-deep-green);
            padding: 60px 40px;
            border-radius: 25px;
            margin-top: 60px;
        }
        
        .livvra-cta-button {
            background: var(--livvra-deep-green);
            color: white;
            padding: 18px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(85, 107, 47, 0.4);
        }
        
        .livvra-cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(85, 107, 47, 0.6);
        }
        
        @media (max-width: 768px) {
            .livvra-hero-title {
                font-size: 2rem;
            }
            .livvra-hero-section {
                padding: 60px 20px;
            }
            .livvra-section {
                padding: 30px 20px;
            }
            .livvra-herb-card {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="livvra-article-container">
        <!-- Hero Section -->
        <section class="livvra-hero-section">
            <h1 class="livvra-hero-title">Daily Immunity Boost: The Role of Ayurvedic Herbs in Modern Wellness</h1>
            <img src="assets/images/blogs/blog1.png" alt="Ayurvedic herbs for daily immunity boost" class="livvra-hero-image">
        </section>

        <!-- Introduction -->
        <section class="livvra-section">
            <p>In today's fast-paced world, immunity is no longer a seasonal concern. It is a daily priority. Long work hours, irregular meals, environmental stress, and digital fatigue quietly weaken the body's natural defense system.</p>
            <p style="margin-top: 25px;">This is where Ayurveda steps in, not as a trend, but as a time-tested science of balance. Ayurveda does not promise instant fixes. Instead, it focuses on <strong>daily immunity building</strong>, strengthening the body gradually through natural herbs, lifestyle alignment, and internal harmony.</p>
            <p style="margin-top: 25px;">When ancient wisdom meets modern wellness, the result is sustainable health that fits seamlessly into contemporary life.</p>
        </section>

        <!-- Understanding Immunity -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">🧠 Understanding Immunity Through Ayurveda</h2>
            <p>In Ayurveda, immunity is referred to as <strong>Ojas</strong>. Ojas represents vitality, resilience, and the body's ability to protect and repair itself. Strong Ojas means better resistance to illness, faster recovery, and stable energy levels.</p>
            <p style="margin-top: 20px;">Unlike modern medicine, which often reacts after illness occurs, Ayurveda emphasizes <strong>preventive immunity care</strong>. The goal is to nourish the body every day so it becomes less vulnerable to stress, infections, and fatigue.</p>
        </section>

        <!-- Top 5 Herbs -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">🌿 Top 5 Ayurvedic Herbs for Daily Immunity Boost</h2>
            
            <div class="livvra-herb-card">
                <div class="livvra-herb-icon">🌿</div>
                <div class="livvra-herb-info">
                    <h4>1. Ashwagandha – The Stress Shield</h4>
                    <p>Ashwagandha is an adaptogenic herb known for helping the body manage physical and mental stress. Chronic stress is one of the biggest immunity suppressors. By balancing cortisol levels, Ashwagandha indirectly strengthens immune response while improving sleep and energy.</p>
                    <div class="livvra-best-for">Stress-related fatigue • Low energy • Modern lifestyle burnout</div>
                </div>
            </div>

            <div class="livvra-herb-card">
                <div class="livvra-herb-icon">🍀</div>
                <div class="livvra-herb-info">
                    <h4>2. Giloy – The Immunity Climber</h4>
                    <p>Often called Amrita in Ayurveda, Giloy is known for its immune-modulating properties. It supports the body's natural defense mechanism and helps maintain internal balance, especially during seasonal changes.</p>
                    <div class="livvra-best-for">Recurrent infections • Seasonal immunity dips • Overall immune resilience</div>
                </div>
            </div>

            <div class="livvra-herb-card">
                <div class="livvra-herb-icon">🌱</div>
                <div class="livvra-herb-info">
                    <h4>3. Tulsi – The Everyday Protector</h4>
                    <p>Tulsi, or Holy Basil, has antimicrobial and antioxidant properties. It supports respiratory health and helps the body adapt to environmental stressors like pollution and allergens.</p>
                    <div class="livvra-best-for">Respiratory wellness • Daily immunity maintenance • Urban living environments</div>
                </div>
            </div>

            <div class="livvra-herb-card">
                <div class="livvra-herb-icon">🍈</div>
                <div class="livvra-herb-info">
                    <h4>4. Amla – The Vitamin C Powerhouse</h4>
                    <p>Amla is one of the richest natural sources of Vitamin C. Unlike synthetic supplements, Amla supports immunity without overheating the body, making it suitable for long-term daily use.</p>
                    <div class="livvra-best-for">Antioxidant protection • Skin and gut health • Long-term immune support</div>
                </div>
            </div>

            <div class="livvra-herb-card">
                <div class="livvra-herb-icon">🫚</div>
                <div class="livvra-herb-info">
                    <h4>5. Turmeric – The Inflammation Balancer</h4>
                    <p>Turmeric contains curcumin, which helps manage inflammation and oxidative stress. Healthy immunity depends on balanced inflammation, not complete suppression.</p>
                    <div class="livvra-best-for">Joint health • Inflammation control • Metabolic wellness</div>
                </div>
            </div>
        </section>

        <!-- Daily Routine -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">🕰️ How to Build a Daily Immunity Routine with Ayurveda</h2>
            <p>A strong immune system is built through small daily habits:</p>
            <ul class="livvra-routine-list">
                <li>✅ Start the day with warm water or herbal infusions</li>
                <li>✅ Include immunity-supporting herbs consistently</li>
                <li>✅ Maintain regular sleep cycles</li>
                <li>✅ Eat seasonal, whole foods</li>
                <li>✅ Manage stress through movement or mindfulness</li>
            </ul>
            <p style="margin-top: 25px;"><strong>Consistency matters more than quantity.</strong> Ayurveda works best when followed gently but regularly.</p>
        </section>

        <!-- FAQs -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">❓ Frequently Asked Questions (FAQs)</h2>
            
            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Q1. Can Ayurvedic herbs be taken daily for immunity?</div>
                <div class="livvra-faq-answer active">Yes. Many Ayurvedic herbs are designed for long-term daily use when taken in appropriate doses and formats.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Q2. Are Ayurvedic immunity boosters safe with modern supplements?</div>
                <div class="livvra-faq-answer">In most cases, yes. However, it is best to consult a healthcare professional to avoid interactions.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Q3. How long does it take to see results?</div>
                <div class="livvra-faq-answer">Ayurveda focuses on gradual improvement. Benefits such as better energy, digestion, and resilience may be noticed within a few weeks of consistent use.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Q4. Can Ayurveda help with stress-related immunity issues?</div>
                <div class="livvra-faq-answer">Absolutely. Herbs like Ashwagandha and Tulsi directly support stress management, which plays a major role in immune health.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Q5. Is Ayurveda suitable for all age groups?</div>
                <div class="livvra-faq-answer">Yes, but formulations and dosages may vary depending on age, health condition, and lifestyle.</div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="livvra-cta-section">
            <h2 style="font-size: 2.5rem; margin-bottom: 20px; font-weight: 700;">🌟 Build Immunity the Natural Way</h2>
            <p style="font-size: 1.3rem; margin-bottom: 30px;">Daily immunity is cultivated through mindful choices, consistent habits, and respect for the body's natural rhythm.</p>
            <a href="/ayurveda-wellness-blog" class="livvra-cta-button">Explore More Ayurveda Articles</a>
        </section>
    </div>

    <script>
        // Simple FAQ toggle functionality
        document.querySelectorAll('.livvra-faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                answer.classList.toggle('active');
            });
        });
    </script>

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
