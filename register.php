#76a33a<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/User.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'phone' => $_POST['phone'] ?? ''
    ];
    
    try {
        if (User::create($data)) {
            header('Location: login.php?registered=1');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Registration failed. Email might already be in use.';
    }
}

$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';
?>

<style>
/* ===== Modern Register Design ===== */

body {
    background: #76a33a;
}

.register-wrapper {
    min-height: 85vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 15px;
}

.register-card {
    background: #ffffff;
    width: 100%;
    max-width: 450px;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 25px 70px rgba(0,0,0,0.08);
    transition: 0.4s ease;
}

.register-card:hover {
    transform: translateY(-6px);
}#76a33a

.register-card h2 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 30px;
    color: #222;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #555;
}

.form-control {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: 0.3s ease;
}

.form-control:focus {
    border-color: #4f46e5;
    outline: none;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

.register-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    background: #76a33a;
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s ease;
    margin-top: 5px;
}

.register-btn:hover {
    background: #76a33a;
    transform: scale(1.02);
}

.error-msg {
    background: #ffe5e5;
    color: #d10000;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 14px;
    text-align: center;
}

.login-text {
    margin-top: 20px;
    text-align: center;
    font-size: 14px;
}

.login-text a {
    color: #4f46e5;
    font-weight: 600;
    text-decoration: none;
}

.login-text a:hover {
    text-decoration: underline;
}

/* Mobile Responsive */
@media (max-width: 480px) {
    .register-card {
        padding: 30px 20px;
    }
}
</style>

<section class="register-wrapper">
    <div class="register-card">
        <h2>Create Account</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required class="form-control">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required class="form-control">
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required class="form-control">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-control">
            </div>

            <button type="submit" class="register-btn">Register</button>
        </form>

        <div class="login-text">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>