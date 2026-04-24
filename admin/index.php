<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Admin.php';
require_once __DIR__ . '/../includes/models/Setting.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $admin = Admin::verifyPassword($username, $password);
        if ($admin) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid admin credentials';
        }
    } catch (Exception $e) {
        $error = 'Login error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - LIVVRA</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        body { background: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; font-family: 'Poppins', sans-serif; }
        .login-card { background: #fff; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .login-card h2 { text-align: center; color: #2C3E50; margin-bottom: 30px; font-weight: 700; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; }
        .form-control { width: 100%; padding: 12px 15px; border: 1.5px solid #e1e8ed; border-radius: 8px; transition: all 0.3s; }
        .form-control:focus { border-color: #C9A227; outline: none; box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.1); }
        .btn-login { width: 100%; padding: 12px; background: #C9A227; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 1rem; }
        .btn-login:hover { background: #b08d20; transform: translateY(-2px); }
        .error-msg { color: #e74c3c; background: #fdeaea; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Admin Login</h2>
        <?php if ($error): ?><div class="error-msg"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Enter username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter password">
            </div>
            <button type="submit" class="btn-login">Login to Dashboard</button>
        </form>
    </div>
</body>
</html>