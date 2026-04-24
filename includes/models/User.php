<?php
require_once __DIR__ . '/../database.php';

class User {
    public static function create($data) {
        $stmt = db()->prepare("INSERT INTO users (name, email, phone, password, address, pincode, city, state) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? '',
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['address'] ?? '',
            $data['pincode'] ?? '',
            $data['city'] ?? '',
            $data['state'] ?? ''
        ]);
    }

    public static function authenticate($email, $password) {
        $stmt = db()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public static function getById($id) {
        $stmt = db()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function updateRewardCoins($id, $amount, $type = 'earned') {
        $db = db();
        $db->beginTransaction();
        try {
            if ($type == 'earned') {
                $stmt = $db->prepare("UPDATE users SET reward_coins = reward_coins + ? WHERE id = ?");
            } else {
                $stmt = $db->prepare("UPDATE users SET reward_coins = reward_coins - ? WHERE id = ?");
            }
            $stmt->execute([$amount, $id]);
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
?>