<?php
require_once __DIR__ . '/../database.php';

class Setting {
    public static function get($key, $default = null) {
        try {
            $stmt = db()->prepare("SELECT value FROM settings WHERE key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch();
            return $result ? $result['value'] : $default;
        } catch (Exception $e) {
            return $default;
        }
    }

    public static function set($key, $value) {
        // Fix for Postgres which doesn't support ON DUPLICATE KEY UPDATE
        $stmt = db()->prepare("SELECT id FROM settings WHERE key = ?");
        $stmt->execute([$key]);
        if ($stmt->fetch()) {
            $stmt = db()->prepare("UPDATE settings SET value = ? WHERE key = ?");
            return $stmt->execute([$value, $key]);
        } else {
            $stmt = db()->prepare("INSERT INTO settings (key, value) VALUES (?, ?)");
            return $stmt->execute([$key, $value]);
        }
    }

    public static function getAll() {
        $stmt = db()->query("SELECT key, value FROM settings");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
    }
}
