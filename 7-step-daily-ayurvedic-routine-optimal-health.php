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
    <title>7-Step Daily Ayurvedic Routine for Optimal Health and Wellness</title>
    <meta name="description" content="Follow a 7-step daily Ayurvedic routine designed for modern life to improve digestion, immunity, energy, and long-term holistic wellness.">
    <meta name="keywords" content="Daily Ayurvedic routine, Ayurvedic daily routine for health, Ayurvedic lifestyle routine, Ayurvedic morning routine, Ayurvedic routine for immunity">
    
    <style>
        :root {
            --livvra-deep-green: #556B2F;
            --livvra-olive-green: #6B8E23;
            --livvra-light-green: #9ACD32;
            --livvra-gold: #FFD700;
            --livvra-cream: #FDF6E3;
            --livvra-text-dark: #2C3E50;
            --livvra-shadow: 0 12px 45px rgba(85, 107, 47, 0.25);
            --livvra-gold-glow: 0 10px 35px rgba(255, 215, 0, 0.35);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, var(--livvra-cream) 0%, #f0f7f4 100%);
            color: var(--livvra-text-dark);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.8;
            overflow-x: hidden;
        }
        
        .livvra-article-container {
            max-width: 1250px;
            margin: 0 auto;
            padding: 0 30px;
        }
        
        .livvra-hero-section {
            background: linear-gradient(135deg, var(--livvra-deep-green) 0%, var(--livvra-olive-green) 40%, var(--livvra-light-green) 100%);
            color: white;
            padding: 130px 70px;
            text-align: center;
            border-radius: 35px;
            margin: 40px 0 90px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--livvra-shadow);
        }
        
        .livvra-hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,215,0,0.2) 0%, transparent 70%);
            animation: livvra-sunrise 20s ease-in-out infinite;
        }
        
        @keyframes livvra-sunrise {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.7; }
            50% { transform: scale(1.1) rotate(180deg); opacity: 1; }
        }
        
        .livvra-hero-title {
            font-size: 3.4rem;
            font-weight: 800;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
            line-height: 1.2;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }
        
        .livvra-hero-subtitle {
            font-size: 1.45rem;
            opacity: 0.98;
            max-width: 850px;
            margin: 0 auto 50px;
            position: relative;
            z-index: 2;
        }
        
        .livvra-hero-image {
            width: 100%;
            max-width: 1100px;
            /* height: 500px; */
            object-fit: cover;
            border-radius: 30px;
            margin: 0 auto;
            display: block;
            box-shadow: 0 30px 80px rgba(0,0,0,0.4);
            position: relative;
            z-index: 2;
        }
        
        .livvra-section {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(25px);
            padding: 70px;
            margin-bottom: 60px;
            border-radius: 30px;
            box-shadow: var(--livvra-shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .livvra-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(180deg, var(--livvra-gold), var(--livvra-light-green));
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
        }
        
        .livvra-section-title {
            color: var(--livvra-deep-green);
            font-size: 2.8rem;
            margin-bottom: 40px;
            font-weight: 700;
            position: relative;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .livvra-section-title::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100px;
            height: 6px;
            background: linear-gradient(90deg, var(--livvra-gold), var(--livvra-light-green));
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(255, 215, 0, 0.4);
        }
        
        .livvra-steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 40px;
            margin: 50px 0;
        }
        
        .livvra-step-card {
            background: linear-gradient(135deg, rgba(107,142,35,0.08), rgba(154,205,50,0.06));
            border: 3px solid rgba(107,142,35,0.2);
            border-radius: 25px;
            padding: 50px 40px;
            position: relative;
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .livvra-step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--livvra-gold), var(--livvra-light-green));
            box-shadow: 0 2px 15px rgba(255, 215, 0, 0.4);
        }
        
        .livvra-step-card:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: 0 35px 80px rgba(85,107,47,0.3);
            border-color: var(--livvra-gold);
        }
        
        .livvra-step-number {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--livvra-gold), #FFA500);
            color: var(--livvra-deep-green);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.8rem;
            box-shadow: var(--livvra-gold-glow);
            z-index: 2;
        }
        
        .livvra-step-icon {
            font-size: 4rem;
            margin-bottom: 25px;
            display: block;
            text-align: center;
        }
        
        .livvra-step-title {
            color: var(--livvra-deep-green);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .livvra-benefits-list {
            list-style: none;
            padding: 0;
            flex-grow: 1;
        }
        
        .livvra-benefits-list li {
            background: rgba(107,142,35,0.1);
            margin-bottom: 12px;
            padding: 12px 20px;
            border-radius: 15px;
            border-left: 4px solid var(--livvra-light-green);
            transition: all 0.3s ease;
        }
        
        .livvra-benefits-list li:hover {
            background: rgba(107,142,35,0.2);
            transform: translateX(8px);
        }
        
        .livvra-faq-item {
            margin-bottom: 35px;
        }
        
        .livvra-faq-question {
            background: linear-gradient(135deg, var(--livvra-deep-green), var(--livvra-olive-green));
            color: white;
            padding: 30px 45px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.2rem;
            cursor: pointer;
            margin-bottom: 20px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(85,107,47,0.3);
        }
        
        .livvra-faq-question::after {
            content: '+';
            position: absolute;
            right: 30px;
            font-size: 1.8rem;
            font-weight: 300;
            transition: all 0.4s ease;
        }
        
        .livvra-faq-question.active::after {
            content: '−';
            transform: rotate(180deg);
        }
        
        .livvra-faq-answer {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(253,246,227,0.9));
            padding: 40px 45px;
            border-radius: 25px;
            border-left: 8px solid var(--livvra-gold);
            display: none;
            animation: livvra-slideDown 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            backdrop-filter: blur(20px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        
        .livvra-faq-answer.active {
            display: block;
        }
        
        @keyframes livvra-slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .livvra-cta-section {
            text-align: center;
            background: linear-gradient(135deg, var(--livvra-gold) 0%, #FFB347 40%, #FF8C00 100%);
            color: var(--livvra-deep-green);
            padding: 90px 70px;
            border-radius: 35px;
            margin: 90px 0 70px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--livvra-gold-glow);
        }
        
        .livvra-cta-buttons {
            display: flex;
            gap: 25px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        
        .livvra-cta-button {
            background: linear-gradient(135deg, var(--livvra-deep-green), var(--livvra-olive-green));
            color: white;
            padding: 25px 55px;
            border-radius: 70px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.35rem;
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 20px 50px rgba(85,107,47,0.4);
            position: relative;
            overflow: hidden;
        }
        
        .livvra-cta-button:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 70px rgba(85,107,47,0.6);
        }
        
        .livvra-cta-button.secondary {
            background: linear-gradient(135deg, var(--livvra-light-green), var(--livvra-olive-green));
        }
        
        @media (max-width: 768px) {
            .livvra-article-container {
                padding: 0 20px;
            }
            .livvra-hero-title {
                font-size: 2.5rem;
            }
            .livvra-hero-section {
                padding: 90px 40px;
            }
            .livvra-section {
                padding: 50px 30px;
            }
            .livvra-steps-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            .livvra-hero-image {
                /* height: 350px; */
            }
        }
    </style>
</head>
<body>
    <div class="livvra-article-container">
        <!-- Premium Hero Section -->
        <section class="livvra-hero-section">
            <h1 class="livvra-hero-title">🕰️ 7-Step Daily Ayurvedic Routine</h1>
            <p class="livvra-hero-subtitle">For Optimal Health and Wellness - Modern life adapted Dinacharya</p>
            <img src="assets/images/blogs/blog3.png" 
                 alt="Daily Ayurvedic routine Dinacharya" class="livvra-hero-image">
        </section>

        <!-- Introduction -->
        <section class="livvra-section">
            <p>Modern life rarely slows down, yet the body still craves rhythm. Irregular meals, screen-heavy mornings, poor sleep cycles, and chronic stress silently disrupt internal balance.</p>
            <p style="margin-top: 35px; font-size: 1.15rem;">Ayurveda offers a simple solution. Not through extreme detoxes or rigid rules, but through a <strong>daily routine</strong> that aligns the body with its natural clock. This daily practice is known as <strong>Dinacharya</strong>, a structured yet flexible approach to maintaining long-term health.</p>
            <p style="margin-top: 25px;">Below is a practical <strong>7-step Ayurvedic routine</strong>, adapted for modern lifestyles, that supports digestion, immunity, mental clarity, and overall wellness.</p>
        </section>

        <!-- Why Routine Matters -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">🌱 Why a Daily Routine Matters in Ayurveda</h2>
            <p>Ayurveda believes the body functions best when daily activities follow consistent timing. A stable routine helps regulate digestion, hormones, sleep, and stress responses. Over time, this consistency builds resilience and prevents disease before it begins.</p>
            <p style="margin-top: 30px; font-style: italic; font-size: 1.1rem; text-align: center; padding: 30px; background: rgba(154,205,50,0.1); border-radius: 20px; border-left: 6px solid var(--livvra-light-green);">
                A <strong>daily Ayurvedic routine</strong> is not about perfection. It is about alignment.
            </p>
        </section>

        <!-- 7 Steps Grid -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">🌞 The 7-Step Daily Ayurvedic Routine</h2>
            
            <div class="livvra-steps-grid">
                <div class="livvra-step-card">
                    <div class="livvra-step-number">1</div>
                    <div class="livvra-step-icon">☀️</div>
                    <h3 class="livvra-step-title">Wake Up with the Sun</h3>
                    <p>Waking up early allows the body to synchronize with natural energy cycles. Early mornings are calm, clear, and ideal for mental focus.</p>
                    <ul class="livvra-benefits-list">
                        <li>✅ Improves mental clarity</li>
                        <li>✅ Supports hormonal balance</li>
                        <li>✅ Enhances daily energy levels</li>
                    </ul>
                </div>

                <div class="livvra-step-card">
                    <div class="livvra-step-number">2</div>
                    <div class="livvra-step-icon">🧼</div>
                    <h3 class="livvra-step-title">Cleanse the Body Gently</h3>
                    <p>Begin your day with warm water to stimulate digestion and eliminate toxins. Traditional practices like tongue cleaning and oil pulling support oral and digestive health.</p>
                    <ul class="livvra-benefits-list">
                        <li>✅ Activates digestion</li>
                        <li>✅ Reduces toxin buildup</li>
                        <li>✅ Improves gut health</li>
                    </ul>
                </div>

                <div class="livvra-step-card">
                    <div class="livvra-step-number">3</div>
                    <div class="livvra-step-icon">🧘</div>
                    <h3 class="livvra-step-title">Move & Breathe Mindfully</h3>
                    <p>Gentle movement such as stretching, yoga, or walking followed by controlled breathing helps circulate energy and oxygen throughout the body.</p>
                    <ul class="livvra-benefits-list">
                        <li>✅ Boosts circulation</li>
                        <li>✅ Reduces stress</li>
                        <li>✅ Improves lung capacity</li>
                    </ul>
                </div>

                <div class="livvra-step-card">
                    <div class="livvra-step-number">4</div>
                    <div class="livvra-step-icon">🍲</div>
                    <h3 class="livvra-step-title">Eat According to Digestive Strength</h3>
                    <p>Ayurveda emphasizes eating when digestion is strongest. Breakfast should be light but nourishing, while lunch remains the main meal of the day.</p>
                    <ul class="livvra-benefits-list">
                        <li>✅ Prevents bloating and fatigue</li>
                        <li>✅ Improves nutrient absorption</li>
                        <li>✅ Supports metabolic health</li>
                    </ul>
                </div>

                <div class="livvra-step-card">
                    <div class="livvra-step-number">5</div>
                    <div class="livvra-step-icon">🛁</div>
                    <h3 class="livvra-step-title">Practice Self-Care Through Abhyanga</h3>
                    <p>Abhyanga, or self-massage with warm oil, nourishes the skin and calms the nervous system. Even a few minutes can create noticeable benefits.</p>
                    <ul class="livvra-benefits-list">
                        <li>✅ Improves circulation</li>
                        <li>✅ Reduces anxiety</li>
                        <li>✅ Supports joint and skin health</li>
                    </ul>
                </div>

                <div class="livvra-step-card">
                    <div class="livvra-step-number">6</div>
                    <div class="livvra-step-icon">⚖️</div>
                    <h3 class="livvra-step-title">Balance Work and Rest</h3>
                    <p>Ayurveda encourages focused work balanced with short breaks. Overexertion weakens immunity and digestion.</p>
                    <ul class="livvra-benefits-list">
                        <li>✅ Improves productivity</li>
                        <li>✅ Prevents burnout</li>
                        <li>✅ Maintains mental balance</li>
                    </ul>
                </div>

                <div class="livvra-step-card">
                    <div class="livvra-step-number">7</div>
                    <div class="livvra-step-icon">🌙</div>
                    <h3 class="livvra-step-title">Wind Down Before Sleep</h3>
                    <p>Evenings should be calming. Light meals, reduced screen time, and quiet activities prepare the body for deep rest.</p>
                    <ul class="livvra-benefits-list">
                        <li>✅ Improves sleep quality</li>
                        <li>✅ Supports emotional health</li>
                        <li>✅ Enhances recovery and repair</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Adaptation Section -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">🔄 Adapting Ayurveda to Modern Life</h2>
            <p>You do not need to follow every step perfectly. Ayurveda works best when adapted realistically. Even implementing 3–4 steps consistently can lead to noticeable improvements in energy, digestion, and stress management.</p>
            <p style="margin-top: 30px; font-size: 1.15rem; font-weight: 600; text-align: center;">
                <em>Consistency matters more than complexity.</em>
            </p>
        </section>

        <!-- FAQs -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">❓ Frequently Asked Questions (FAQs)</h2>
            
            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Is a daily Ayurvedic routine suitable for busy professionals?</div>
                <div class="livvra-faq-answer">Yes. Ayurveda is flexible. Even small habits like mindful eating and early sleep can create meaningful health benefits.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">How long does it take to see results?</div>
                <div class="livvra-faq-answer">Many people notice better digestion and energy within 2–3 weeks of consistent practice.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Do I need to follow all 7 steps daily?</div>
                <div class="livvra-faq-answer">No. Start with what feels manageable and build gradually. Ayurveda encourages sustainability, not pressure.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Can this routine improve immunity?</div>
                <div class="livvra-faq-answer">Yes. A stable daily routine supports digestion, sleep, and stress regulation, which directly impact immune health.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Is this routine safe for all age groups?</div>
                <div class="livvra-faq-answer">In general, yes. However, specific practices can be adjusted based on age, health condition, and lifestyle.</div>
            </div>
        </section>

        <!-- CTA Section with LIVVRA Links -->
        <section class="livvra-cta-section">
            <h2 style="font-size: 3rem; margin-bottom: 30px; font-weight: 800;">🌟 Small Daily Habits, Lasting Wellness</h2>
            <p style="font-size: 1.45rem; max-width: 750px; margin: 0 auto 50px; line-height: 1.7;">
                Optimal health is not achieved through occasional effort. It is built through daily alignment. 
                A 7-step Ayurvedic routine creates structure in a chaotic world, helping the body restore balance naturally.
            </p>
            <p style="font-size: 1.2rem; margin-bottom: 20px; font-weight: 600;">
                When practiced consistently, Ayurveda becomes less of a routine and more of a way of living.
            </p>
            <div class="livvra-cta-buttons">
                <a href="https://livvra.in/index.php" class="livvra-cta-button" target="_blank">🌿 Visit LIVVRA Home</a>
                <a href="https://livvra.in/contact.php" class="livvra-cta-button secondary" target="_blank">📞 Contact LIVVRA</a>
                <a href="/ayurveda-wellness-blog" class="livvra-cta-button">← More Ayurveda Articles</a>
            </div>
        </section>
    </div>

    <script>
        // Enhanced FAQ interactions
        document.querySelectorAll('.livvra-faq-question').forEach((question, index) => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const isActive = answer.classList.contains('active');
                
                document.querySelectorAll('.livvra-faq-answer').forEach(ans => ans.classList.remove('active'));
                document.querySelectorAll('.livvra-faq-question').forEach(q => q.classList.remove('active'));
                
                if (!isActive) {
                    answer.classList.add('active');
                    question.classList.add('active');
                }
            });
        });

        // Step card stagger animation on load
        window.addEventListener('load', () => {
            document.querySelectorAll('.livvra-step-card').forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(50px)';
                    card.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    
                    requestAnimationFrame(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                }, index * 150);
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
