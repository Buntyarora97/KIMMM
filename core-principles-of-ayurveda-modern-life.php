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
    <title>Core Principles of Ayurveda: Their Importance and Relevance in Today's Life</title>
    <meta name="description" content="Understand the core principles of Ayurveda and why they remain deeply relevant in today's fast-paced lifestyle for health, balance, and well-being.">
    <meta name="keywords" content="Core principles of Ayurveda, Importance of Ayurveda in modern life, Ayurveda lifestyle principles, Relevance of Ayurveda today, Ayurveda balance and wellness">
    
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
            background: linear-gradient(135deg, var(--livvra-cream) 0%, #f1f7f2 100%);
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
            background: linear-gradient(135deg, var(--livvra-deep-green) 0%, var(--livvra-olive-green) 50%, var(--livvra-light-green) 100%);
            color: white;
            padding: 140px 80px;
            text-align: center;
            border-radius: 40px;
            margin: 40px 0 100px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--livvra-shadow);
        }
        
        .livvra-hero-section::before {
            content: '';
            position: absolute;
            top: -30%;
            left: -30%;
            width: 160%;
            height: 160%;
            background: radial-gradient(circle, rgba(255,215,0,0.25) 0%, transparent 70%);
            animation: livvra-harmony 25s ease-in-out infinite;
        }
        
        @keyframes livvra-harmony {
            0%, 100% { transform: rotate(0deg) scale(1); opacity: 0.7; }
            33% { transform: rotate(120deg) scale(1.1); opacity: 0.9; }
            66% { transform: rotate(240deg) scale(1.05); opacity: 0.8; }
        }
        
        .livvra-hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 35px;
            position: relative;
            z-index: 2;
            line-height: 1.2;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
        }
        
        .livvra-hero-subtitle {
            font-size: 1.45rem;
            opacity: 0.95;
            max-width: 900px;
            margin: 0 auto 60px;
            position: relative;
            z-index: 2;
        }
        
        .livvra-hero-image {
            width: 100%;
            max-width: 1200px;
            height: 550px;
            object-fit: cover;
            border-radius: 35px;
            margin: 0 auto;
            display: block;
            box-shadow: 0 35px 90px rgba(0,0,0,0.45);
            position: relative;
            z-index: 2;
        }
        
        .livvra-section {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(30px);
            padding: 80px;
            margin-bottom: 70px;
            border-radius: 35px;
            box-shadow: var(--livvra-shadow);
            border: 1px solid rgba(255, 255, 255, 0.35);
            position: relative;
            overflow: hidden;
        }
        
        .livvra-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 10px;
            height: 100%;
            background: linear-gradient(180deg, var(--livvra-gold), var(--livvra-light-green));
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.5);
        }
        
        .livvra-section-title {
            color: var(--livvra-deep-green);
            font-size: 3rem;
            margin-bottom: 45px;
            font-weight: 700;
            position: relative;
            display: flex;
            align-items: center;
            gap: 25px;
        }
        
        .livvra-section-title::after {
            content: '';
            position: absolute;
            bottom: -25px;
            left: 0;
            width: 120px;
            height: 8px;
            background: linear-gradient(90deg, var(--livvra-gold), var(--livvra-light-green));
            border-radius: 5px;
            box-shadow: 0 3px 15px rgba(255, 215, 0, 0.6);
        }
        
        .livvra-principles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 50px;
            margin: 70px 0;
        }
        
        .livvra-principle-card {
            background: linear-gradient(135deg, rgba(107,142,35,0.12), rgba(154,205,50,0.08));
            border: 4px solid rgba(107,142,35,0.3);
            border-radius: 30px;
            padding: 60px 50px;
            position: relative;
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .livvra-principle-card::before {
            content: '';
            position: absolute;
            top: 30px;
            right: 30px;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, var(--livvra-gold), transparent);
            border-radius: 50%;
            opacity: 0.2;
        }
        
        .livvra-principle-card:hover {
            transform: translateY(-20px) scale(1.04);
            box-shadow: 0 40px 100px rgba(85,107,47,0.35);
            border-color: var(--livvra-gold);
        }
        
        .livvra-principle-number {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--livvra-gold), #FFA500);
            color: var(--livvra-deep-green);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 2rem;
            box-shadow: var(--livvra-gold-glow);
            z-index: 2;
        }
        
        .livvra-principle-icon {
            font-size: 4.5rem;
            margin-bottom: 30px;
            display: block;
            text-align: center;
        }
        
        .livvra-principle-title {
            color: var(--livvra-deep-green);
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .livvra-modern-relevance {
            background: rgba(255,215,0,0.15);
            padding: 25px;
            border-radius: 20px;
            border-left: 6px solid var(--livvra-gold);
            margin-top: 30px;
            font-weight: 600;
        }
        
        .livvra-faq-item {
            margin-bottom: 40px;
        }
        
        .livvra-faq-question {
            background: linear-gradient(135deg, var(--livvra-deep-green), var(--livvra-olive-green));
            color: white;
            padding: 35px 50px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.25rem;
            cursor: pointer;
            margin-bottom: 25px;
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(85,107,47,0.4);
        }
        
        .livvra-faq-question::after {
            content: '+';
            position: absolute;
            right: 35px;
            font-size: 2rem;
            font-weight: 300;
            transition: all 0.4s ease;
        }
        
        .livvra-faq-question.active::after {
            content: '−';
            transform: rotate(180deg);
        }
        
        .livvra-faq-answer {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(253,246,227,0.92));
            padding: 45px 50px;
            border-radius: 30px;
            border-left: 10px solid var(--livvra-gold);
            display: none;
            animation: livvra-slideDown 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            backdrop-filter: blur(25px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        @keyframes livvra-slideDown {
            from { opacity: 0; transform: translateY(-35px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .livvra-cta-section {
            text-align: center;
            background: linear-gradient(135deg, var(--livvra-gold) 0%, #FFB347 40%, #FF8C00 100%);
            color: var(--livvra-deep-green);
            padding: 100px 80px;
            border-radius: 40px;
            margin: 100px 0 80px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--livvra-gold-glow);
        }
        
        .livvra-cta-buttons {
            display: flex;
            gap: 35px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 50px;
        }
        
        .livvra-cta-button {
            background: linear-gradient(135deg, var(--livvra-deep-green), var(--livvra-olive-green));
            color: white;
            padding: 28px 60px;
            border-radius: 80px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.4rem;
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 25px 60px rgba(85,107,47,0.45);
            position: relative;
            overflow: hidden;
        }
        
        .livvra-cta-button:hover {
            transform: translateY(-12px);
            box-shadow: 0 35px 80px rgba(85,107,47,0.65);
        }
        
        @media (max-width: 768px) {
            .livvra-article-container {
                padding: 0 20px;
            }
            .livvra-hero-title {
                font-size: 2.6rem;
            }
            .livvra-hero-section {
                padding: 100px 40px;
            }
            .livvra-section {
                padding: 60px 35px;
            }
            .livvra-principles-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .livvra-hero-image {
                height: 400px;
            }
        }
    </style>
</head>
<body>
    <div class="livvra-article-container">
        <!-- Ultimate Hero Section -->
        <section class="livvra-hero-section">
            <h1 class="livvra-hero-title">🧠 Core Principles of Ayurveda</h1>
            <p class="livvra-hero-subtitle">Their Importance and Relevance in Today's Life</p>
            <img src="assets/images/blogs/blog5.png" 
                 alt="Core principles of Ayurveda modern life" class="livvra-hero-image">
        </section>

        <!-- Introduction -->
        <section class="livvra-section">
            <p>In a world driven by speed, convenience, and constant stimulation, health problems have quietly become lifestyle problems. Stress, poor digestion, sleep disorders, and chronic fatigue are now common across all age groups.</p>
            <p style="margin-top: 40px; font-size: 1.2rem;">While modern medicine treats symptoms effectively, many people still search for balance, prevention, and long-term well-being. <strong>Ayurveda</strong> offers that missing foundation. Not as an outdated tradition, but as a practical life science built on principles that remain surprisingly relevant today.</p>
        </section>

        <!-- What Makes Ayurveda Different -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">🌱 What Makes Ayurveda Different?</h2>
            <p>Ayurveda does not define health as the absence of disease. It defines health as a state where the body, mind, digestion, sleep, and emotions function in harmony.</p>
            <p style="margin-top: 30px;">Its principles focus on:</p>
            <ul style="margin: 30px 0; padding-left: 35px; font-size: 1.15rem;">
                <li>✅ Understanding individuality</li>
                <li>✅ Maintaining balance</li>
                <li>✅ Preventing imbalance before disease begins</li>
                <li>✅ Aligning daily life with nature</li>
            </ul>
            <p style="margin-top: 30px; font-style: italic; font-size: 1.15rem; padding: 35px; background: rgba(154,205,50,0.15); border-radius: 25px; border-left: 8px solid var(--livvra-gold);">
                These ideas feel even more necessary in today's lifestyle-driven health challenges.
            </p>
        </section>

        <!-- 7 Core Principles -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">⚖️ The Core Principles of Ayurveda Explained</h2>
            
            <div class="livvra-principles-grid">
                <div class="livvra-principle-card">
                    <div class="livvra-principle-number">1</div>
                    <div class="livvra-principle-icon">⚖️</div>
                    <h3 class="livvra-principle-title">Balance Is the Foundation of Health</h3>
                    <p>Ayurveda teaches that health exists when everything is in balance. This includes digestion, sleep, mental state, physical activity, and emotions.</p>
                    <div class="livvra-modern-relevance">
                        <strong>Modern relevance:</strong> Irregular routines, late nights, and processed foods disturb balance. Ayurvedic thinking encourages restoring rhythm instead of relying only on medication.
                    </div>
                </div>

                <div class="livvra-principle-card">
                    <div class="livvra-principle-number">2</div>
                    <div class="livvra-principle-icon">🔄</div>
                    <h3 class="livvra-principle-title">The Tridosha Principle</h3>
                    <p>Every individual is governed by a unique combination of three energies: Vata (movement), Pitta (digestion), Kapha (structure).</p>
                    <div class="livvra-modern-relevance">
                        <strong>Modern relevance:</strong> This explains why the same diet or fitness plan doesn't work for everyone. Ayurveda promotes personalized health rather than one-size-fits-all solutions.
                    </div>
                </div>

                <div class="livvra-principle-card">
                    <div class="livvra-principle-number">3</div>
                    <div class="livvra-principle-icon">🔥</div>
                    <h3 class="livvra-principle-title">Agni: The Power of Digestion</h3>
                    <p>Agni represents digestive and metabolic strength. Strong digestion means better immunity, energy, and clarity.</p>
                    <div class="livvra-modern-relevance">
                        <strong>Modern relevance:</strong> Even healthy food can cause problems if digestion is weak. Ayurveda prioritizes gut health, which modern science now recognizes as central to wellness.
                    </div>
                </div>

                <div class="livvra-principle-card">
                    <div class="livvra-principle-number">4</div>
                    <div class="livvra-principle-icon">🛡️</div>
                    <h3 class="livvra-principle-title">Prevention Over Cure</h3>
                    <p>Ayurveda focuses on preventing disease through daily habits, diet, and seasonal adjustments.</p>
                    <div class="livvra-modern-relevance">
                        <strong>Modern relevance:</strong> Lifestyle disorders like diabetes, obesity, and stress-related illness can often be prevented with early corrections rather than late treatment.
                    </div>
                </div>

                <div class="livvra-principle-card">
                    <div class="livvra-principle-number">5</div>
                    <div class="livvra-principle-icon">🕰️</div>
                    <h3 class="livvra-principle-title">Living in Harmony with Nature</h3>
                    <p>Ayurveda emphasizes alignment with natural cycles such as day, night, and seasons.</p>
                    <div class="livvra-modern-relevance">
                        <strong>Modern relevance:</strong> Disrupted sleep, screen exposure, and indoor lifestyles disconnect us from natural rhythms. Ayurvedic principles restore this lost alignment.
                    </div>
                </div>

                <div class="livvra-principle-card">
                    <div class="livvra-principle-number">6</div>
                    <div class="livvra-principle-icon">🧠</div>
                    <h3 class="livvra-principle-title">Mind and Body Are Interconnected</h3>
                    <p>Mental stress directly affects physical health. Ayurveda treats the mind and body as one system.</p>
                    <div class="livvra-modern-relevance">
                        <strong>Modern relevance:</strong> Anxiety, burnout, and emotional fatigue are now major health issues. Ayurveda integrates mental calmness into physical wellness.
                    </div>
                </div>

                <div class="livvra-principle-card">
                    <div class="livvra-principle-number">7</div>
                    <div class="livvra-principle-icon">📅</div>
                    <h3 class="livvra-principle-title">Daily Habits Shape Long-Term Health</h3>
                    <p>Ayurveda highlights the power of small, consistent daily actions.</p>
                    <div class="livvra-modern-relevance">
                        <strong>Modern relevance:</strong> Irregular eating, skipped meals, and poor sleep accumulate into long-term health issues. Structured daily habits prevent this slow decline.
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Ayurveda Matters -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">🌍 Why Ayurveda Still Matters Today</h2>
            <p>Ayurveda remains relevant because it:</p>
            <ul style="margin: 40px 0; padding-left: 40px; font-size: 1.2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <li>✅ Supports preventive healthcare</li>
                <li>✅ Encourages self-awareness</li>
                <li>✅ Promotes sustainable habits</li>
                <li>✅ Reduces dependency on quick fixes</li>
                <li>✅ Aligns health with lifestyle</li>
            </ul>
            <p style="margin-top: 35px; font-weight: 600; color: var(--livvra-deep-green); font-size: 1.15rem;">
                It does not reject modern medicine. It complements it by filling gaps that technology alone cannot address.
            </p>
        </section>
<!-- ADD THIS SECTION after "Why Ayurveda Still Matters Today" and before FAQs -->

<section class="livvra-section">
    <h2 class="livvra-section-title">⚖️ Ayurveda as a Lifestyle, Not a Treatment</h2>
    <p>Ayurveda works best when practiced as a way of life. It encourages:</p>
    
    <div class="livvra-daily-habits" style="margin: 45px 0; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
        <div class="livvra-habit-card">
            <strong>🍽️ Mindful eating</strong>
        </div>
        <div class="livvra-habit-card">
            <strong>😴 Adequate rest</strong>
        </div>
        <div class="livvra-habit-card">
            <strong>⚖️ Balanced work</strong>
        </div>
        <div class="livvra-habit-card">
            <strong>🧠 Emotional regulation</strong>
        </div>
        <div class="livvra-habit-card">
            <strong>🌿 Connection with nature</strong>
        </div>
    </div>
    
    <p style="margin-top: 40px; font-size: 1.2rem; font-weight: 700; padding: 40px; background: linear-gradient(135deg, rgba(154,205,50,0.2), rgba(255,215,0,0.15)); border-radius: 25px; border-left: 10px solid var(--livvra-gold); text-align: center;">
        This approach makes health <strong>sustainable rather than reactive</strong>.
    </p>
</section>

        <!-- FAQs -->
        <section class="livvra-section">
            <h2 class="livvra-section-title">❓ Frequently Asked Questions (FAQs)</h2>
            
            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Are Ayurvedic principles applicable in modern lifestyles?</div>
                <div class="livvra-faq-answer">Yes. They can be adapted easily to busy schedules and modern routines.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Does Ayurveda conflict with modern medicine?</div>
                <div class="livvra-faq-answer">No. Ayurveda complements modern healthcare by focusing on prevention and lifestyle balance.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Is Ayurveda only for people with health problems?</div>
                <div class="livvra-faq-answer">No. Ayurveda is primarily preventive and beneficial for maintaining long-term wellness.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">How long does it take to see benefits?</div>
                <div class="livvra-faq-answer">Improvements in digestion, energy, and mental clarity may appear within a few weeks of consistent practice.</div>
            </div>

            <div class="livvra-faq-item">
                <div class="livvra-faq-question">Can Ayurveda help manage stress?</div>
                <div class="livvra-faq-answer">Yes. Ayurveda integrates mental balance as a core principle of health.</div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="livvra-cta-section">
            <h2 style="font-size: 3.2rem; margin-bottom: 40px; font-weight: 800;">🌟 Ancient Principles for Modern Living</h2>
            <p style="font-size: 1.55rem; max-width: 850px; margin: 0 auto 60px; line-height: 1.8;">
                The core principles of Ayurveda are not outdated ideas. They are timeless guidelines for living well. 
                In a world of fast solutions and rising health concerns, Ayurveda offers something rare: balance, awareness, and sustainability.
            </p>
            <div class="livvra-cta-buttons">
                <a href="https://livvra.in/index.php" class="livvra-cta-button" target="_blank">🌿 Visit LIVVRA Home</a>
                <a href="https://livvra.in/contact.php" class="livvra-cta-button" target="_blank">📞 Contact LIVVRA</a>
                <a href="/ayurveda-wellness-blog" class="livvra-cta-button">← Ayurveda Wellness Blog</a>
            </div>
        </section>
    </div>

    <script>
        // Premium FAQ interactions
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

        // Staggered principle cards animation
        window.addEventListener('load', () => {
            document.querySelectorAll('.livvra-principle-card').forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(60px) scale(0.95)';
                    card.style.transition = 'all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    
                    requestAnimationFrame(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) scale(1)';
                    });
                }, index * 250);
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
