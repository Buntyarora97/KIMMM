<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/User.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = User::authenticate($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/includes/header.php';
?>

<style>
/* ===== Modern Login Design ===== */

body {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
}

.login-wrapper {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 15px;
}

.login-card {
    background: #ffffff;
    width: 100%;
    max-width: 420px;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
    position: relative;
    overflow: hidden;
    transition: 0.4s ease;
}

.login-card:hover {
    transform: translateY(-5px);
}

.login-card h2 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 30px;
    color: #222;
}

.form-group {
    margin-bottom: 20px;
    position: relative;
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
    border-color: #0d6efd;
    outline: none;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
}

.login-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(90deg, #0d6efd, #4f46e5);
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s ease;
}

.login-btn:hover {
    background: linear-gradient(90deg, #4f46e5, #0d6efd);
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

.register-text {
    margin-top: 20px;
    text-align: center;
    font-size: 14px;
}

.register-text a {
    color: #0d6efd;
    font-weight: 600;
    text-decoration: none;
}

.register-text a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 480px) {
    .login-card {
        padding: 30px 20px;
    }
}
</style>

<section class="login-wrapper">
    <div class="login-card">
        <h2>Welcome Back</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required class="form-control">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-control">
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="register-text">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>