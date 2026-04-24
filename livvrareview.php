<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews | Livvra - Live Better Live Strong</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #1a5f2a;
            --light-green: #2d8a3e;
            --accent-gold: #c9a227;
            --light-gold: #e8d5a3;
            --dark-bg: #0f1f0f;
            --card-bg: rgba(255, 255, 255, 0.03);
            --text-light: #ffffff;
            --text-muted: #a0b8a0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--dark-bg);
            color: var(--text-light);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #0a1f0a 0%, #0f2f1f 50%, #0a1f0a 100%);
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(45, 138, 62, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(201, 162, 39, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(45, 138, 62, 0.1) 0%, transparent 40%);
            animation: bgPulse 8s ease-in-out infinite;
        }

        @keyframes bgPulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        /* Floating Leaves Animation */
        .leaves-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .leaf {
            position: absolute;
            font-size: 20px;
            color: var(--light-green);
            opacity: 0.3;
            animation: float 15s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        /* Header */
        header {
            padding: 20px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            background: rgba(15, 31, 15, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            animation: logoPulse 2s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(45, 138, 62, 0.4); }
            50% { box-shadow: 0 0 20px 10px rgba(45, 138, 62, 0.2); }
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--light-green), var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-tagline {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Hero Section */
        .hero {
            padding: 150px 20px 80px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(201, 162, 39, 0.2);
            border: 1px solid var(--accent-gold);
            border-radius: 50px;
            color: var(--accent-gold);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 30px;
            animation: badgeGlow 2s ease-in-out infinite alternate;
        }

        @keyframes badgeGlow {
            from { box-shadow: 0 0 5px rgba(201, 162, 39, 0.3); }
            to { box-shadow: 0 0 20px rgba(201, 162, 39, 0.6); }
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero h1 span {
            background: linear-gradient(135deg, var(--light-green), var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 40px;
        }

        /* Stats Section */
        .stats-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 50px;
        }

        .stat-box {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px 40px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            animation: statFloat 3s ease-in-out infinite;
        }

        .stat-box:nth-child(2) { animation-delay: 0.5s; }
        .stat-box:nth-child(3) { animation-delay: 1s; }

        @keyframes statFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .stat-box:hover {
            transform: translateY(-5px) scale(1.05);
            border-color: var(--accent-gold);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent-gold), #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Filter Section */
        .filter-section {
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .filter-container {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-btn {
            padding: 12px 25px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--light-green);
            border-color: var(--light-green);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(45, 138, 62, 0.3);
        }

        /* Reviews Grid */
        .reviews-section {
            padding: 40px 20px 100px;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .product-category {
            margin-bottom: 60px;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .category-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 10px 30px rgba(45, 138, 62, 0.3);
        }

        .category-info h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .category-meta {
            display: flex;
            gap: 20px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(201, 162, 39, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            color: var(--accent-gold);
            font-weight: 600;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .review-card {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .review-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .review-card:hover::before {
            left: 100%;
        }

        .review-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--accent-gold);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(201, 162, 39, 0.1);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
            color: white;
        }

        .reviewer-name {
            font-weight: 600;
            font-size: 1rem;
        }

        .review-date {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .stars {
            color: var(--accent-gold);
            font-size: 0.9rem;
        }

        .review-content {
            color: var(--text-muted);
            line-height: 1.8;
            font-size: 0.95rem;
            position: relative;
            padding-left: 20px;
            border-left: 3px solid var(--light-green);
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 15px;
            font-size: 0.8rem;
            color: var(--light-green);
            background: rgba(45, 138, 62, 0.1);
            padding: 5px 12px;
            border-radius: 20px;
        }

        /* Testimonial Marquee */
        .marquee-section {
            padding: 60px 0;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .marquee-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 40px;
        }

        .marquee-container {
            display: flex;
            gap: 30px;
            animation: marquee 40s linear infinite;
        }

        .marquee-container:hover {
            animation-play-state: paused;
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .marquee-card {
            min-width: 400px;
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
        }

        /* Footer */
        footer {
            background: rgba(0, 0, 0, 0.3);
            padding: 60px 20px 30px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
        }

        .footer-logo {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--light-green), var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .footer-tagline {
            color: var(--text-muted);
            margin-bottom: 30px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--light-green);
            border-color: var(--light-green);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(45, 138, 62, 0.3);
        }

        .copyright {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Scroll to Top */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            box-shadow: 0 10px 30px rgba(45, 138, 62, 0.4);
        }

        .scroll-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .scroll-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(45, 138, 62, 0.5);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .reviews-grid {
                grid-template-columns: 1fr;
            }

            .stats-container {
                gap: 20px;
            }

            .stat-box {
                padding: 20px 30px;
            }

            .stat-number {
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--accent-gold);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
<base target="_blank">
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation"></div>

    <!-- Floating Leaves -->
    <div class="leaves-container" id="leavesContainer"></div>

   

   

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="filter-container">
            <button class="filter-btn active" onclick="filterReviews('all')">All Reviews</button>
            <button class="filter-btn" onclick="filterReviews('36')">Yeast Protein Powder</button>
            <button class="filter-btn" onclick="filterReviews('37')">Nabhi Amrit Oil</button>
            <button class="filter-btn" onclick="filterReviews('6')">DY-B Fuel (Diabetes Care)</button>
            <button class="filter-btn" onclick="filterReviews('5')">Kumkumadi Oil</button>
            <button class="filter-btn" onclick="filterReviews('35')">Yeast Protein Powder</button>
            <button class="filter-btn" onclick="filterReviews('24')">Gold Shilajit Resin</button>
            <button class="filter-btn" onclick="filterReviews('4')">Yeast Protein Powder</button>
            <button class="filter-btn" onclick="filterReviews('25')">Gold Shilajit Resin</button>
            <button class="filter-btn" onclick="filterReviews('14')">Yeast Protein Powder</button>
            <button class="filter-btn" onclick="filterReviews('13')">DY-B Fuel (Diabetes Care)</button>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section" id="reviews">

        <div class="product-category" data-product="36">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div class="category-info">
                    <h2>Yeast Protein Powder</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 5.0
                        </span>
                        <span><i class="fas fa-comment"></i> 82 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">A</div>
                            <div>
                                <div class="reviewer-name">Aashish</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I’ve tried many proteins but this one feels completely different 🔥"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rahul</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "2 weeks using this and my energy level is on another level 💪"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">G</div>
                            <div>
                                <div class="reviewer-name">Garry</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Finally a protein that doesn’t feel heavy on the stomach"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">H</div>
                            <div>
                                <div class="reviewer-name">Harman</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "This is the first time a protein actually suits my body"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">RS</div>
                            <div>
                                <div class="reviewer-name">Rajbeer Singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Livvra make good products"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">G</div>
                            <div>
                                <div class="reviewer-name">Geji</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good flavour mango. 
Baby me mere to use krte hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Ram</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Product bahutt accha bna hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">TS</div>
                            <div>
                                <div class="reviewer-name">Tanveer singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Livvra make good product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">B</div>
                            <div>
                                <div class="reviewer-name">Bhullar</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good Products for Livvra"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">K</div>
                            <div>
                                <div class="reviewer-name">Karan</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Superstar Se Bhi Upper 👌👌Livvra Protien 👌👌"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">AS</div>
                            <div>
                                <div class="reviewer-name">Ajay singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Very good product good for health and best in taste"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">DS</div>
                            <div>
                                <div class="reviewer-name">Davinder Sidhu</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I like that it’s natural yeast protein instead of regular whey. 👏🏻👏🏻👏🏻"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="37">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-droplet"></i>
                </div>
                <div class="category-info">
                    <h2>Nabhi Amrit Oil</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 5.0
                        </span>
                        <span><i class="fas fa-comment"></i> 72 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">A</div>
                            <div>
                                <div class="reviewer-name">Ashu</div>
                                <div class="review-date">2026-03-20</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Amazing Product 👍"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">A</div>
                            <div>
                                <div class="reviewer-name">Avneet</div>
                                <div class="review-date">2026-03-21</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I am order today due to Livvra make good quality products 🪴"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rajesh</div>
                                <div class="review-date">2026-03-21</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good product.. nabhi k lie acche ingredients k sath"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Ramesh</div>
                                <div class="review-date">2026-03-21</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Livvra de sare product vdea ne. Vaise"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Raman</div>
                                <div class="review-date">2026-03-21</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Livvra Nabhi ch pa k aram kria tn bhut bdia Relaxation feel hoia🪴"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">K</div>
                            <div>
                                <div class="reviewer-name">Kulwinder</div>
                                <div class="review-date">2026-03-21</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Wooow Amazing Product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">B</div>
                            <div>
                                <div class="reviewer-name">Bhullar</div>
                                <div class="review-date">2026-03-21</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Best Product Livvra"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">DC</div>
                            <div>
                                <div class="reviewer-name">Deepak choudhry</div>
                                <div class="review-date">2026-03-22</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Amazing result livvra product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">MR</div>
                            <div>
                                <div class="reviewer-name">Mala ram</div>
                                <div class="review-date">2026-03-22</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Suresh</div>
                                <div class="review-date">2026-03-22</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Better feel use this product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">D</div>
                            <div>
                                <div class="reviewer-name">Deep</div>
                                <div class="review-date">2026-03-22</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Livvra abhyam amrit oil really amrit"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">M</div>
                            <div>
                                <div class="reviewer-name">Mujesh</div>
                                <div class="review-date">2026-03-22</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Sch me amrit h"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="6">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-heart-pulse"></i>
                </div>
                <div class="category-info">
                    <h2>DY-B Fuel (Diabetes Care)</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 4.9
                        </span>
                        <span><i class="fas fa-comment"></i> 65 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">B</div>
                            <div>
                                <div class="reviewer-name">bunty</div>
                                <div class="review-date">2026-01-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "nice"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">G</div>
                            <div>
                                <div class="reviewer-name">Garry</div>
                                <div class="review-date">2026-01-24</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I had Purchased this product for my mother. Because of Diabetic Patient she was feeling weakness all the time. But after using this product she feels better than before."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">K</div>
                            <div>
                                <div class="reviewer-name">Kajal</div>
                                <div class="review-date">2026-02-10</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Amazing Product. Using since from last 2 weeks. Feeling better."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">HG</div>
                            <div>
                                <div class="reviewer-name">Harry Gill</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Sugar control plus nutrition sounds like a good idea."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">G</div>
                            <div>
                                <div class="reviewer-name">Geji</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Bahutt vdea product... Meri mother use kar rahi."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">G</div>
                            <div>
                                <div class="reviewer-name">Geji</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Bukh bi mamma nu vdea lgdi"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">RS</div>
                            <div>
                                <div class="reviewer-name">Rajbeer Singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I drink livvra dy b fuel every day for good health"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">MR</div>
                            <div>
                                <div class="reviewer-name">Mamta Rani</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "DY-B Fuel Ras is a natural Ayurvedic supplement that helps support balanced blood sugar and better digestion."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Sukhdeep</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good herbal formula for sugar control. Works well with a healthy diet and regular use."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">KD</div>
                            <div>
                                <div class="reviewer-name">Kamla Devi</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Effective Ayurvedic drink for maintaining healthy sugar levels and overall wellness."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Sapna</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Natural ingredients and good results. A helpful product for daily blood sugar support."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">J</div>
                            <div>
                                <div class="reviewer-name">Jasveer</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Great product 
Help my mother"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="5">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-spa"></i>
                </div>
                <div class="category-info">
                    <h2>Kumkumadi Oil</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 4.9
                        </span>
                        <span><i class="fas fa-comment"></i> 59 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">B</div>
                            <div>
                                <div class="reviewer-name">bunty</div>
                                <div class="review-date">2026-01-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "very nice"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">sahil</div>
                                <div class="review-date">2026-01-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "very use ful product thank you livvra"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">NT</div>
                            <div>
                                <div class="reviewer-name">Neha thakur</div>
                                <div class="review-date">2026-01-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "wahh very nyc"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">K</div>
                            <div>
                                <div class="reviewer-name">Kajal</div>
                                <div class="review-date">2026-02-24</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I had uneven skin tone and slight pigmentation. After 1 month of using Livvra Kumkumadi Oil at night, my complexion looks more even and brighter. No irritation at all. Will repurchase!"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Simran</div>
                                <div class="review-date">2026-02-24</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Natural glow in a bottle ✨
Lightweight, absorbs well, no stickiness. Skin looks fresh every morning. Totally worth it!"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">H</div>
                            <div>
                                <div class="reviewer-name">Harpreet</div>
                                <div class="review-date">2026-02-24</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Amazing product!
Fine lines thodi smooth hui hain aur face pe healthy glow aa gaya hai. Chemical-free feel karta hai. Thoda sa oil hi kaafi hota hai, so bottle long time chalti hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Sakshi</div>
                                <div class="review-date">2026-02-24</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Livvra Kumkumadi Oil sach mein kaafi effective hai.
Mere face ke dark spots aur dullness mein improvement dikha. Skin soft aur bright lagti hai. Daily raat ko 3–4 drops use karta/karti hoon. Bahut accha Ayurvedic product hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Raman</div>
                                <div class="review-date">2026-02-24</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I Got today my parcel. Lets Try to get best results."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rani</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "My skin feels so soft and hydrated after applying it 🌸"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">B</div>
                            <div>
                                <div class="reviewer-name">Beant</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Natural ingredients always work better than chemicals 👍"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Ramanveer</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Livvra make natural beauty products 🪴"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">RS</div>
                            <div>
                                <div class="reviewer-name">Rajbeer Singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I am use for black tone skin"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="35">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div class="category-info">
                    <h2>Yeast Protein Powder</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 5.0
                        </span>
                        <span><i class="fas fa-comment"></i> 58 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">H</div>
                            <div>
                                <div class="reviewer-name">Harbans</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "My gym recovery has improved a lot after starting this Protein powder."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Somil</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I was skeptical but honestly this protein surprised me"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">RS</div>
                            <div>
                                <div class="reviewer-name">Rajbeer Singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good product good quality"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">JB</div>
                            <div>
                                <div class="reviewer-name">Jaspreet Brar</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Tasty and effective protein supplement. Worth the price."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">KS</div>
                            <div>
                                <div class="reviewer-name">Kulwant Singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Nice flavor and good results. A reliable protein for fitness lovers."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">G</div>
                            <div>
                                <div class="reviewer-name">Geji</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Mango favourite"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Ram</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">AS</div>
                            <div>
                                <div class="reviewer-name">Ajay singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Very good product good for health and best in taste"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">GS</div>
                            <div>
                                <div class="reviewer-name">Gurmit Sidhu</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "My stamina during workouts has improved a lot. Awesome 🤩"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">SN</div>
                            <div>
                                <div class="reviewer-name">Somi Nakai</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "My trainer suggested this and I’m glad I tried it. Taste is Awesome 🤩"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Ravi</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good combination of protein, vitamins and natural ingredients 💥"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">B</div>
                            <div>
                                <div class="reviewer-name">Bunny</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Much better than heavy protein powders. Wonderful taste Mango, Vanilla & cold coffee . 💪💪💪"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="24">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="category-info">
                    <h2>Gold Shilajit Resin</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 4.9
                        </span>
                        <span><i class="fas fa-comment"></i> 50 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rahul</div>
                                <div class="review-date">2026-02-22</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Excellent Shilajit"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">A</div>
                            <div>
                                <div class="reviewer-name">Amit</div>
                                <div class="review-date">2026-02-22</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Very good quality"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">TG</div>
                            <div>
                                <div class="reviewer-name">Tarsem Gill</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "My fatigue reduced after regular use."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">K</div>
                            <div>
                                <div class="reviewer-name">kuljit</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Been using for almost a month and overall experience is positive."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">AS</div>
                            <div>
                                <div class="reviewer-name">Ajay singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Very good product for health and for energy"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">A</div>
                            <div>
                                <div class="reviewer-name">Abhey</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "I started using Gold Shilajit Resin a few weeks ago and honestly the energy difference is amazing. Feeling more active, stronger and less tired throughout the day. Looks like a pure and authentic product. Definitely adding it to my daily routine."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">JG</div>
                            <div>
                                <div class="reviewer-name">Jagdish Gakkhar</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Pehle mujhe doubt tha, par Gold Shilajit Resin try karne ke baad energy kaafi improve hui hai. Din bhar thakan kam lagdi hai. Lagda hai product asli hai. Daily routine vich add kar liya."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">SS</div>
                            <div>
                                <div class="reviewer-name">Sandip Sharma</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Using Gold Shilajit Resin daily with warm milk. Energy levels improved and I feel more active throughout the day. Looks like authentic Himalayan quality. Definitely continuing it for long term health."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">A</div>
                            <div>
                                <div class="reviewer-name">Anuj</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Main pichhle 2 hafte se Gold Shilajit Resin use kar reha haan. Gym vich stamina kaafi better ho gaya. Body thodi energetic feel hundi hai. 100 % Natural product hai sarya nu use karna chaida. 🫡"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">I</div>
                            <div>
                                <div class="reviewer-name">Ishan</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Sach bolu taan pehle lagda si marketing zyada hai. Par Gold Shilajit Resin use karke feel hoya energy sach vich vadhi hai. Hun roz subah dudh naal le reha haan."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">G</div>
                            <div>
                                <div class="reviewer-name">Goldy</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Honestly didn’t expect results so quickly. Gold Shilajit Resin helped reduce my tiredness after long work hours. Feeling stronger and more focused. Packaging and quality also look very premium."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rahul</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Shilajit power"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="4">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div class="category-info">
                    <h2>Yeast Protein Powder</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 5.0
                        </span>
                        <span><i class="fas fa-comment"></i> 42 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">G</div>
                            <div>
                                <div class="reviewer-name">Goldy</div>
                                <div class="review-date">2026-02-23</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Livvra Yeast Protein is now my daily energy partner 💪 Clean, powerful & effective!"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rahul</div>
                                <div class="review-date">2026-02-23</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Highly recommended for daily wellness & recovery 💪"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">M</div>
                            <div>
                                <div class="reviewer-name">Mohit</div>
                                <div class="review-date">2026-02-23</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "A smart protein choice for modern lifestyle ⚡"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">B</div>
                            <div>
                                <div class="reviewer-name">Baba</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Not going back to my old protein after using this Protein. What a Great Taste😋"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">KB</div>
                            <div>
                                <div class="reviewer-name">Karamjit Brar</div>
                                <div class="review-date">2026-03-15</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Feeling stronger and more active these days 💯"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">JK</div>
                            <div>
                                <div class="reviewer-name">Jogesh Kumar</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good quality protein powder with a nice taste. It mixes well and helps support muscle recovery. Overall, a great choice for daily fitness nutrition."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">AY</div>
                            <div>
                                <div class="reviewer-name">Amitesh Yadav</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Great protein powder with smooth texture and good taste. Perfect for daily workouts."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">JK</div>
                            <div>
                                <div class="reviewer-name">Jaspinder Kumar</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good quality and easy to mix. Helps with muscle recovery and energy."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">AS</div>
                            <div>
                                <div class="reviewer-name">Ajay singh</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Very good product good for health and best in taste"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">PS</div>
                            <div>
                                <div class="reviewer-name">Paramjit Sandhu</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "This protein actually feels clean and natural 🌿"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Raju</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Raju</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Sirra product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="25">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="category-info">
                    <h2>Gold Shilajit Resin</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 5.0
                        </span>
                        <span><i class="fas fa-comment"></i> 12 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">HS</div>
                            <div>
                                <div class="reviewer-name">Harish Singla</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "This Gold Shilajit Resin feels very premium. Taste and texture show it’s real resin, not powder. After two weeks my stamina and focus improved a lot. Definitely one of the best natural supplements I’ve tried."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rozal</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Roz subah Gold Shilajit Resin le ke din energetic start hunda hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Sahib</div>
                                <div class="review-date">2026-03-16</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Gold Shilajit Resin di quality sach vich premium lagdi hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Som</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Sonu</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Sonu</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Product best aa"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rahul</div>
                                <div class="review-date">2026-03-17</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good product"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">P</div>
                            <div>
                                <div class="reviewer-name">Pargat</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Gold Shilajit Resin di consistency thick hai, jo asli resin di sign hundi hai. 💚💚"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">S</div>
                            <div>
                                <div class="reviewer-name">Sidhu</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Gym wale dost ne recommend kiti si Gold Shilajit Resin. Result positive lag rahe ne."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">SK</div>
                            <div>
                                <div class="reviewer-name">Santosh Kumar</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Happy with the purity and results of Gold Shilajit Resin."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">C</div>
                            <div>
                                <div class="reviewer-name">Chandan</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "The purity of this Gold Shilajit Resin is impressive. Feeling more active daily."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 1.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">SC</div>
                            <div>
                                <div class="reviewer-name">Sunil Chourasiya</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "The texture of this Gold Shilajit Resin shows it’s authentic. Within two weeks I noticed better strength and stamina. Worth trying if you want natural energy."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="14">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div class="category-info">
                    <h2>Yeast Protein Powder</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 5.0
                        </span>
                        <span><i class="fas fa-comment"></i> 10 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">K</div>
                            <div>
                                <div class="reviewer-name">karamjit</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Pehlan thakaan jaldi ho jandi si 😓
Hun stamina strong aa
Workout lamba chal janda
Amazing product 🔥"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">AK</div>
                            <div>
                                <div class="reviewer-name">Anil Kushwaha</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Taste thik hai but result zabardast 🔥
Energy boost
Muscle recovery fast
Recommend karunga 💪💪"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">PM</div>
                            <div>
                                <div class="reviewer-name">Pradeep Mishra</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Har din better feel ho raha hai 💪
Stamina increase
No digestion issue
Best protein"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">H</div>
                            <div>
                                <div class="reviewer-name">Harbans</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Veere eh ta kamaal aa 💪 full energy"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">R</div>
                            <div>
                                <div class="reviewer-name">Rimple</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Natural protein finally mil gaya 🙌"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">AS</div>
                            <div>
                                <div class="reviewer-name">Anil Sangwan</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Best decision ever 🙌"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">SD</div>
                            <div>
                                <div class="reviewer-name">Satish Dalal</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Gym performance improved 📈"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">PP</div>
                            <div>
                                <div class="reviewer-name">Pawan Punia</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Natural energy boost ⚡"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">RP</div>
                            <div>
                                <div class="reviewer-name">Rajesh Phogat</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Must try product 💯"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">PM</div>
                            <div>
                                <div class="reviewer-name">Pooja Malik</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Totally satisfied 👍"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>

        <div class="product-category" data-product="13">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-heart-pulse"></i>
                </div>
                <div class="category-info">
                    <h2>DY-B Fuel (Diabetes Care)</h2>
                    <div class="category-meta">
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> 5.0
                        </span>
                        <span><i class="fas fa-comment"></i> 10 Reviews</span>
                    </div>
                </div>
            </div>
            <div class="reviews-grid">

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.0s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">RS</div>
                            <div>
                                <div class="reviewer-name">Ramesh Soni</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Taste herbal hai par quality achi lag rahi hai. Livvra Dy-B Fuel use karke thodi energy aur stamina better feel ho rahi hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.1s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">VL</div>
                            <div>
                                <div class="reviewer-name">Vijay Lodhi</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Roz subah Livvra Dy-B Fuel lene se body active feel hoti hai. Sugar patients ke liye natural support lagta hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.2s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">MP</div>
                            <div>
                                <div class="reviewer-name">Mahesh Prajapati</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Good to see brands focusing on diabetic wellness."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.30000000000000004s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">LP</div>
                            <div>
                                <div class="reviewer-name">Lokesh Patel</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "My mother has diabetes, this might help her nutrition."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.4s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">P</div>
                            <div>
                                <div class="reviewer-name">Pankaj</div>
                                <div class="review-date">2026-03-25</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "The formula looks interesting."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.5s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">MM</div>
                            <div>
                                <div class="reviewer-name">Manjeet Mor</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Pehle mujhe doubt tha par Dy-B Fuel ka experience acha raha. Regular use se digestion aur energy agge se bahut better feel ho rahi hai.. 💚💚"
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.6000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">RK</div>
                            <div>
                                <div class="reviewer-name">Rahul Kadian</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Dy-B Fuel ka herbal combination kaafi interesting lagta hai. Natural approach acha hai."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.7000000000000001s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">JM</div>
                            <div>
                                <div class="reviewer-name">Joginder Malik</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Dy-B Fuel daily health routine ke liye acha option hai. Regular use kare."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.8s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">ND</div>
                            <div>
                                <div class="reviewer-name">Narender Dahiya</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Managing diabetes with proper nutrition is very important."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>

                <div class="review-card" style="animation: fadeInUp 0.5s ease-out 0.9s both;">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar">SH</div>
                            <div>
                                <div class="reviewer-name">Surender Hooda</div>
                                <div class="review-date">2026-03-26</div>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <div class="review-content">
                        "Definitely curious to know more about this formula. Already Placed Order of 2 bottles."
                    </div>
                    <div class="verified-badge">
                        <i class="fas fa-check-circle"></i> Verified Purchase
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-logo">LIVVRA</div>
        <div class="footer-tagline">Live Better Live Strong</div>
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        <div class="copyright">
            © 2026 Livvra. All rights reserved. | Made with <i class="fas fa-heart" style="color: var(--accent-gold);"></i> in India
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="scroll-top" id="scrollTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Create floating leaves
        function createLeaves() {
            const container = document.getElementById('leavesContainer');
            const leafSymbols = ['🍃', '🌿', '🍀', '🌱'];

            for (let i = 0; i < 15; i++) {
                const leaf = document.createElement('div');
                leaf.className = 'leaf';
                leaf.textContent = leafSymbols[Math.floor(Math.random() * leafSymbols.length)];
                leaf.style.left = Math.random() * 100 + '%';
                leaf.style.animationDuration = (15 + Math.random() * 10) + 's';
                leaf.style.animationDelay = Math.random() * 15 + 's';
                leaf.style.fontSize = (15 + Math.random() * 15) + 'px';
                container.appendChild(leaf);
            }
        }
        createLeaves();

        // Filter functionality
        function filterReviews(productId) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const categories = document.querySelectorAll('.product-category');
            categories.forEach(cat => {
                if (productId === 'all' || cat.dataset.product === productId) {
                    cat.style.display = 'block';
                    cat.style.animation = 'fadeInUp 0.5s ease-out';
                } else {
                    cat.style.display = 'none';
                }
            });
        }

        // Scroll to top functionality
        const scrollTopBtn = document.getElementById('scrollTop');
        const header = document.getElementById('header');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }

            if (window.pageYOffset > 50) {
                header.style.padding = '10px 0';
                header.style.background = 'rgba(15, 31, 15, 0.98)';
            } else {
                header.style.padding = '20px 0';
                header.style.background = 'rgba(15, 31, 15, 0.9)';
            }
        });

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.review-card').forEach(card => {
            observer.observe(card);
        });

        // Add parallax effect to hero
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            hero.style.transform = `translateY(${scrolled * 0.5}px)`;
        });
    </script>
</body>
</html>