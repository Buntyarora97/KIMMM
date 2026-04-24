<?php
require_once 'includes/config.php';
require_once 'includes/models/ContactInquiry.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            ContactInquiry::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message
            ]);
            // Redirect to thank you page on success
            header('Location: thank-you.php');
            exit();
        } catch (Exception $e) {
            $error = 'Failed to submit your inquiry. Please try again.';
        }
    }
}

$pageTitle       = 'Contact Us | Best Ayurvedic Store Online in India';
$metaDescription = 'Have questions about our premium ayurvedic products? Contact India\'s trusted organic ayurvedic store today. We\'re here to help you on your wellness journey.';
$metaKeywords    = 'contact livvra india, ayurvedic support team, customer care herbal products, livvra help, wellness support';
$canonicalUrl    = 'https://livvra.in/contact-ayurvedic-support';
require_once 'includes/header.php';
?>

<section class="simple-page-header">
  <h1>Contact Us</h1>

  <div class="breadcrumb">
    <a href="index.php">
      <i class="fas fa-home"></i> Home
    </a>
    <span>/</span>
    <span>Contact Us</span>
  </div>
</section>

<style>
.simple-page-header {
  padding: 60px 20px 40px;
  text-align: center;
  background: linear-gradient(135deg, #f7f5ee 0%, #e8e6df 100%);
  position: relative;
  overflow: hidden;
}

.simple-page-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="%239bb83d" stroke-width="0.5" opacity="0.1"/></svg>');
  background-size: 100px 100px;
  opacity: 0.5;
}

.simple-page-header h1 {
  font-size: 42px;
  font-weight: 800;
  margin-bottom: 12px;
  color: #1e1e1e;
  position: relative;
}

.breadcrumb {
  display: flex;
  justify-content: center;
  gap: 8px;
  font-size: 14px;
  position: relative;
}

.breadcrumb a {
  color: #6a8f2d;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.breadcrumb a:hover {
  color: #9bb83d;
}

.breadcrumb span {
  color: #555;
}

/* Responsive */
@media (max-width: 768px) {
  .simple-page-header h1 {
    font-size: 32px;
  }
}
</style>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-wrapper reveal-left">
                <h3>Send Us a Message</h3>
                <p style="color: var(--text-light); margin-bottom: 25px;">Have a question or feedback? We'd love to hear from you. Fill out the form below and we'll get back to you as soon as possible.</p>
                
                <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>
                
                <form action="contact.php" method="post" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" required 
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="Enter your phone"
                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject">
                                <option value="">Select a subject</option>
                                <option value="General Inquiry" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                                <option value="Order Related" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Order Related') ? 'selected' : ''; ?>>Order Related</option>
                                <option value="Product Information" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Product Information') ? 'selected' : ''; ?>>Product Information</option>
                                <option value="Feedback" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Feedback') ? 'selected' : ''; ?>>Feedback</option>
                                <option value="Complaint" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Complaint') ? 'selected' : ''; ?>>Complaint</option>
                                <option value="Business Partnership" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Business Partnership') ? 'selected' : ''; ?>>Business Partnership</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" placeholder="Write your message here..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> 
                        <span>Send Message</span>
                        <div class="spinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info-wrapper reveal-right">
                <h3>Get In Touch</h3>
                <p style="color: var(--text-light); margin-bottom: 25px;">We're here to help and answer any question you might have. We look forward to hearing from you!</p>
                
                <div class="contact-info-cards">
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Our Address</h4>
                            <p><?php echo SITE_ADDRESS; ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Email Us</h4>
                            <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
                            <p>We reply within 24 hours</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Call Us</h4>
                            <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a>
                            <p>Mon - Sat: 9:00 AM - 7:00 PM</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-text">
                            <h4>WhatsApp</h4>
                            <a href="https://wa.me/918958489684" target="_blank">+91 8958489684</a>
                            <p>Quick responses on WhatsApp</p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Response Badge -->
                <div class="quick-response-badge">
                    <i class="fas fa-bolt"></i>
                    <div>
                        <strong>Quick Response</strong>
                        <span>Avg. response time: 2 hours</span>
                    </div>
                </div>
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
        </div>
        <div class="faq-grid">
            <div class="faq-card reveal">
                <div class="faq-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <h4>What is your return policy?</h4>
                <p>We offer a 07-day hassle-free return policy on all products. If you're not satisfied, contact us for a full refund.</p>
            </div>
            <div class="faq-card reveal delay-100">
                <div class="faq-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h4>How long does shipping take?</h4>
                <p>Standard delivery takes 3-5 business days. Express delivery is available for metro cities with 1-2 day delivery.</p>
            </div>
            <div class="faq-card reveal delay-200">
                <div class="faq-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4>Are your products safe?</h4>
                <p>Yes, all our products are 100% natural, lab-tested, and manufactured in GMP-certified facilities.</p>
            </div>
            <div class="faq-card reveal delay-300">
                <div class="faq-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h4>Do you offer COD?</h4>
                <p>Yes, we offer Cash on Delivery across India for your convenience.</p>
            </div>
        </div>
    </div>
</section>


<style>
    /* ==============================
   CONTACT PAGE BASE
================================ */
.contact-section {
  padding: 80px 0;
  background: #f7f5ee;
}

.contact-grid {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 60px;
  align-items: flex-start;
}

/* ==============================
   CONTACT FORM
================================ */
.contact-form-wrapper {
  background: #fff;
  padding: 45px;
  border-radius: 18px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.08);
  transition: transform 0.3s ease;
}

.contact-form-wrapper:hover {
  transform: translateY(-5px);
}

.contact-form-wrapper h3 {
  font-size: 30px;
  margin-bottom: 10px;
  color: #1e1e1e;
}

.contact-form-wrapper p {
  font-size: 15px;
  line-height: 1.7;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 22px;
}

.form-group label {
  font-size: 14px;
  margin-bottom: 8px;
  font-weight: 500;
  color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 14px 16px;
  border-radius: 10px;
  border: 1px solid #cfd6a3;
  font-size: 15px;
  outline: none;
  transition: all 0.3s ease;
  background: #fff;
}

.form-group textarea {
  resize: none;
  min-height: 130px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #9bb83d;
  box-shadow: 0 0 0 3px rgba(155,184,61,0.2);
}

/* ==============================
   SUBMIT BUTTON
================================ */
.submit-btn {
  margin-top: 10px;
  background: linear-gradient(135deg, #9bb83d 0%, #7a942f 100%);
  color: #fff;
  border: none;
  padding: 14px 28px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  width: 100%;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.submit-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(155,184,61,0.4);
}

.submit-btn:active {
  transform: translateY(0);
}

.submit-btn .spinner {
  position: absolute;
  right: 20px;
}

/* ==============================
   ALERTS
================================ */
.alert {
  padding: 14px 18px;
  border-radius: 10px;
  font-size: 14px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.alert-success {
  background: #eaf5d4;
  color: #557a18;
  border-left: 4px solid #9bb83d;
}

.alert-error {
  background: #ffe3e3;
  color: #b71c1c;
  border-left: 4px solid #e53935;
}

/* ==============================
   CONTACT INFO
================================ */
.contact-info-wrapper {
  background: #fff;
  padding: 45px;
  border-radius: 18px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.08);
  transition: transform 0.3s ease;
}

.contact-info-wrapper:hover {
  transform: translateY(-5px);
}

.contact-info-wrapper h3 {
  font-size: 28px;
  margin-bottom: 10px;
}

.contact-info-cards {
  display: grid;
  gap: 22px;
  margin-top: 30px;
}

.contact-info-card {
  display: flex;
  gap: 18px;
  align-items: flex-start;
  padding: 15px;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.contact-info-card:hover {
  background: #f7f5ee;
  transform: translateX(5px);
}

.contact-icon {
  width: 46px;
  height: 46px;
  background: linear-gradient(135deg, #9bb83d 0%, #7a942f 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 18px;
  flex-shrink: 0;
  box-shadow: 0 4px 15px rgba(155,184,61,0.3);
}

.contact-text h4 {
  font-size: 16px;
  margin-bottom: 5px;
  color: #1e1e1e;
}

.contact-text p,
.contact-text a {
  font-size: 14px;
  color: #555;
  line-height: 1.6;
  text-decoration: none;
}

.contact-text a:hover {
  color: #9bb83d;
}

/* Quick Response Badge */
.quick-response-badge {
  margin-top: 25px;
  padding: 20px;
  background: linear-gradient(135deg, #f7f5ee 0%, #e8e6df 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 15px;
  border: 2px solid #cfd6a3;
}

.quick-response-badge i {
  font-size: 24px;
  color: #9bb83d;
}

.quick-response-badge strong {
  display: block;
  color: #1e1e1e;
  font-size: 15px;
  margin-bottom: 3px;
}

.quick-response-badge span {
  color: #666;
  font-size: 13px;
}

/* ==============================
   FAQ SECTION
================================ */
.section-light {
  background: #fff;
  padding: 80px 0;
}

.section-header {
  text-align: center;
  margin-bottom: 50px;
}

.section-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #f7f5ee;
  color: #9bb83d;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 15px;
}

.section-title {
  font-size: 36px;
  color: #1e1e1e;
  margin: 0;
}

.section-title span {
  color: #9bb83d;
}

.faq-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 25px;
  max-width: 1000px;
  margin: 0 auto;
}

.faq-card {
  padding: 30px;
  border-radius: 16px;
  background: #f7f5ee;
  text-align: left;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.faq-card:hover {
  transform: translateY(-5px);
  border-color: #9bb83d;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.faq-icon {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #9bb83d 0%, #7a942f 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 20px;
  margin-bottom: 15px;
}

.faq-card h4 {
  margin-bottom: 10px;
  color: #1e1e1e;
  font-size: 18px;
}

.faq-card p {
  color: #666;
  line-height: 1.6;
  margin: 0;
  font-size: 14px;
}

/* ==============================
   ANIMATIONS
================================ */
.reveal, .reveal-left, .reveal-right {
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.6s ease-out;
}

.reveal.active, .reveal-left.active, .reveal-right.active {
  opacity: 1;
  transform: translateY(0);
}

.reveal-left {
  transform: translateX(-50px);
}

.reveal-right {
  transform: translateX(50px);
}

.reveal-left.active, .reveal-right.active {
  transform: translateX(0);
}

.delay-100 { transition-delay: 0.1s; }
.delay-200 { transition-delay: 0.2s; }
.delay-300 { transition-delay: 0.3s; }

/* ==============================
   RESPONSIVE
================================ */
@media (max-width: 992px) {
  .contact-grid {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  
  .faq-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 576px) {
  .contact-form-wrapper,
  .contact-info-wrapper {
    padding: 30px;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .section-title {
    font-size: 28px;
  }
  
  .contact-info-card {
    padding: 10px;
  }
  
  .quick-response-badge {
    flex-direction: column;
    text-align: center;
  }
}

/* Loading State */
.submit-btn.loading {
  opacity: 0.8;
  cursor: not-allowed;
}

.submit-btn.loading span {
  opacity: 0.7;
}
</style>

<script>
// Form submission handling
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    const spinner = btn.querySelector('.spinner');
    const btnText = btn.querySelector('span');
    
    btn.classList.add('loading');
    spinner.style.display = 'block';
    btnText.textContent = 'Sending...';
});

// Scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, observerOptions);

document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
    observer.observe(el);
});
</script>

<?php require_once 'includes/footer.php'; ?>


