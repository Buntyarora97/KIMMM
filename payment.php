    <?php
    require_once __DIR__ . '/includes/config.php';
    require_once __DIR__ . '/includes/models/Order.php';
    
    $orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : (isset($_SESSION['pending_order_id']) ? (int)$_SESSION['pending_order_id'] : 0);
    
    if (!$orderId) {
        header('Location: cart.php');
        exit;
    }
    
    $order = Order::getById($orderId);
    if (!$order) {
        header('Location: cart.php');
        exit;
    }
    
    $pageTitle = 'Choose Payment Method';
    require_once 'includes/header.php';
    ?>
    
    <section class="section" style="padding: 120px 0 80px; background: var(--luxury-cream);">
        <div class="container">
            <div class="payment-container">
                <div style="text-align: center; margin-bottom: 40px;">
                    <h2 style="font-family: var(--font-display); color: var(--primary-green-dark); font-size: 2.5rem; margin-bottom: 10px;">Select Payment</h2>
                    <div style="width: 60px; height: 3px; background: var(--primary-gold); margin: 0 auto 20px;"></div>
                    <p style="color: var(--text-light); font-size: 1.1rem;">
                        Order #<?php echo htmlspecialchars($order['order_number']); ?> • 
                        <span style="color: var(--primary-green); font-weight: 700;">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                    </p>
                </div>
    
                <div class="payment-methods">
                    <div class="payment-group-label">Direct UPI Payment</div>
                    
                    <div class="payment-option-static" style="background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                            <span style="font-weight: 700; color: var(--primary-green);">Pay via UPI ID</span>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/UPI-Logo.png/1200px-UPI-Logo.png" style="height: 20px;">
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border: 1px dashed #ccc;">
                            <p style="margin: 0; font-size: 0.9rem; color: #666;">UPI ID:</p>
                            <h4 style="margin: 5px 0; color: var(--text-dark); letter-spacing: 1px;">9779999000@hdfc</h4>
                            <button onclick="copyUPI()" class="btn-sm" style="background: var(--primary-gold); color: #fff; border: none; padding: 5px 15px; border-radius: 5px; font-size: 0.8rem; cursor: pointer; margin-top: 5px;">Copy ID</button>
                        </div>
                        <div style="margin-top: 20px;">
                            <p style="font-size: 0.85rem; color: #666; margin-bottom: 10px;">Steps:</p>
                            <ol style="font-size: 0.85rem; color: #444; padding-left: 20px;">
                                <li>Copy the UPI ID above.</li>
                                <li>Open your UPI App (GPay/PhonePe/Paytm).</li>
                                <li>Pay <strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong>.</li>
                                <li>Take a screenshot and WhatsApp it to <strong>+91 9953835017</strong>.</li>
                            </ol>
                        </div>
                    </div>
    
                    <div class="payment-group-label">Online Gateways</div>
                    
                  <!--<a href="#" id="payuBtn" class="payment-option" onclick="payWith('payu')">-->
                  <!--      <div style="display: flex; align-items: center; gap: 18px;">-->
                  <!--          <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 10px;">-->
                  <!--              <img src="https://www.payu.in/wp-content/uploads/2021/04/PayU-Logo.png" alt="PayU" style="width: 35px; object-fit: contain;">-->
                  <!--          </div>-->
                  <!--          <span style="font-weight: 600; color: var(--text-dark); font-size: 1.1rem;">PayU Money</span>-->
                  <!--      </div>-->
                  <!--      <i class="fas fa-chevron-right" style="color: var(--primary-gold);"></i>-->
                  <!--  </a>-->
                    
                    <!--<a href="#" class="payment-option" onclick="payWith('razorpay')">-->
                    <!--    <div style="display: flex; align-items: center; gap: 18px;">-->
                    <!--        <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 10px;">-->
                    <!--            <img src="https://razorpay.com/favicon.png" alt="Razorpay" style="width: 30px; object-fit: contain;">-->
                    <!--        </div>-->
                    <!--        <div>-->
                    <!--            <span style="font-weight: 600; color: var(--text-dark); font-size: 1.1rem; display: block;">Razorpay Checkout</span>-->
                    <!--            <span style="font-size: 0.8rem; color: var(--text-light);">Credit/Debit Cards, Netbanking</span>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <i class="fas fa-chevron-right" style="color: var(--primary-gold);"></i>-->
                    <!--</a>-->
    
                  <a href="#" id="cashfreeBtn" class="payment-option" onclick="payWith('cashfree')" style="border-color: var(--primary-gold); background: rgba(201, 162, 39, 0.05);">
                        <div style="display: flex; align-items: center; gap: 18px;">
                            <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: #fff; border-radius: 10px;">
                                <img src="https://www.cashfree.com/favicon.ico" alt="Cashfree" style="width: 25px; object-fit: contain;">
                            </div>
                            <div>
                                <span style="font-weight: 700; color: var(--text-dark); font-size: 1.1rem; display: block;">Cashfree Payments</span>
                                <span style="font-size: 0.8rem; color: var(--primary-green); font-weight: 600;">Cards, UPI, Netbanking, Wallets</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: var(--primary-gold);"></i>
                    </a>

                  <a href="#" id="payuBtn" class="payment-option" onclick="payWith('payu')">
                        <div style="display: flex; align-items: center; gap: 18px;">
                            <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 10px;">
                                <img src="assets/images/0023949_payu-money-india.png" alt="PayU" style="width: 35px; object-fit: contain;">
                            </div>
                            <span style="font-weight: 600; color: var(--text-dark); font-size: 1.1rem;">PayU Money</span>
                        </div>
                        <i class="fas fa-chevron-right" style="color: var(--primary-gold);"></i>
                    </a>
                </div>
    
                <div style="margin-top: 40px; padding-top: 30px; border-top: 1px dashed var(--border-color); text-align: center;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 10px; color: var(--primary-green); font-weight: 600; margin-bottom: 20px;">
                        <i class="fas fa-shield-check" style="font-size: 1.4rem;"></i>
                        <span>Bank-grade 256-bit SSL Security</span>
                    </div>
                    <div style="display: flex; justify-content: center; gap: 20px; opacity: 0.6; filter: grayscale(0.5);">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png" style="height: 15px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" style="height: 18px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Rupay-Logo.svg/1200px-Rupay-Logo.svg.png" style="height: 14px;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    
    let paymentProcessing = false;
    
    function payWith(gateway) {
    
        // ✅ prevent multiple clicks
        if (paymentProcessing) return;
        paymentProcessing = true;
    
        // disable payu button UI
        const payuBtn = document.getElementById('payuBtn');
        if (payuBtn) {
            payuBtn.style.pointerEvents = 'none';
            payuBtn.style.opacity = '0.6';
        }
    
        // =============================
        // CASHFREE PAYMENT FLOW
        // =============================
        if (gateway === 'cashfree') {
            window.location.href = "cashfree-init.php?order_id=<?php echo $orderId; ?>";
            return;
        }

        // =============================
        // PAYU PAYMENT FLOW
        // =============================
        if (gateway === 'payu') {
    
            // redirect only once
            window.location.href = "payu-init.php?order_id=<?php echo $orderId; ?>";
            return;
        }
    
        // =============================
        // RAZORPAY PAYMENT FLOW
        // =============================
        if (gateway === 'razorpay') {
            initiateRazorpay();
        }
    }
    
    function copyUPI() {
        navigator.clipboard.writeText("9953835017@ybl");
        alert("UPI ID Copied!");
    }
    
    function initiateRazorpay() {
    
        fetch('ajax/create_razorpay_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'order_id=<?php echo $orderId; ?>'
        })
        .then(r => r.json())
        .then(data => {
    
            if (!data.success) {
                paymentProcessing = false;
                alert(data.message || 'Error initializing payment');
                return;
            }
    
            var options = {
                key: "<?php echo RAZORPAY_KEY_ID; ?>",
                amount: data.amount,
                currency: data.currency,
                name: "LIVVRA",
                description: "Order #<?php echo $order['order_number']; ?>",
                order_id: data.razorpay_order_id,
    
                handler: function (response) {
    
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'verify-payment.php';
    
                    var fields = {
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature,
                        order_id: '<?php echo $orderId; ?>'
                    };
    
                    for (var key in fields) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = fields[key];
                        form.appendChild(input);
                    }
    
                    document.body.appendChild(form);
                    form.submit();
                },
    
                prefill: {
                    name: "<?php echo htmlspecialchars($order['customer_name']); ?>",
                    email: "<?php echo htmlspecialchars($order['customer_email']); ?>",
                    contact: "<?php echo htmlspecialchars($order['customer_phone']); ?>"
                },
    
                theme: { color: "#C9A227" }
            };
    
            var rzp = new Razorpay(options);
            rzp.open();
        });
    }
    
    </script>
    
    <?php require_once 'includes/footer.php'; ?>
    
    
    
    <style>
        /* ===========================
       PAYMENT PAGE STYLING
    =========================== */
    
    .payment-container {
        max-width: 750px;
        margin: auto;
        background: #ffffff;
        border-radius: 18px;
        padding: 40px 35px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        animation: fadeUp 0.6s ease;
    }
    
    /* Headings */
    .payment-container h2 {
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    /* Section Labels */
    .payment-group-label {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--primary-green-dark);
        margin: 30px 0 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Payment Option Card */
    .payment-option {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        padding: 18px 20px;
        border-radius: 14px;
        margin-bottom: 15px;
        text-decoration: none;
        border: 1px solid #eee;
        transition: 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .payment-option::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent, rgba(201,162,39,0.15), transparent);
        opacity: 0;
        transition: 0.3s;
    }
    
    .payment-option:hover::before {
        opacity: 1;
    }
    
    .payment-option:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        border-color: var(--primary-gold);
    }
    
    .payment-option i {
        font-size: 1.2rem;
    }
    
    /* Static UPI Box */
    .payment-option-static {
        background: linear-gradient(135deg, #ffffff, #fdfcf8);
        border-radius: 16px;
        border: 1px solid #eee;
        transition: 0.3s;
    }
    
    .payment-option-static:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        transform: translateY(-3px);
    }
    
    /* UPI ID Box */
    .payment-option-static h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-green-dark);
    }
    
    /* Copy Button */
    .btn-sm {
        transition: 0.3s ease;
    }
    
    .btn-sm:hover {
        background: #b8931d !important;
        transform: scale(1.05);
    }
    
    /* Steps */
    .payment-option-static ol li {
        margin-bottom: 6px;
    }
    
    /* SSL Section */
    .payment-container .fa-shield-check {
        animation: pulse 1.5s infinite;
    }
    
    /* Card Logos */
    .payment-container img {
        transition: 0.3s;
    }
    
    .payment-container img:hover {
        filter: grayscale(0);
        transform: scale(1.1);
    }
    
    /* Mobile Responsive */
    @media(max-width: 768px) {
        .payment-container {
            padding: 25px 20px;
        }
    
        .payment-option {
            padding: 15px;
        }
    
        .payment-option span {
            font-size: 1rem !important;
        }
    }
    
    /* Animations */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.15); }
        100% { transform: scale(1); }
    }
    
    </style>