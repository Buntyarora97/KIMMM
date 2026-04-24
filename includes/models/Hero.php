<?php
require_once __DIR__ . '/../database.php';

class Hero {
    public static function getAll($onlyActive = true) {
        try {
            $sql = "SELECT * FROM hero_slides";
            if ($onlyActive) {
                $sql .= " WHERE is_active = 1";
            }
            $sql .= " ORDER BY display_order ASC";
            $stmt = db()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        } catch (Exception $e) {
            error_log("Hero::getAll error: " . $e->getMessage());
            return [];
        }
    }

    public static function create($data) {
        $stmt = db()->prepare("INSERT INTO hero_slides (title, subtitle, media_type, media_url, video_url, button_text, button_link, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'], $data['subtitle'] ?? '', 
            $data['media_type'] ?? 'image', $data['media_url'] ?? '', $data['video_url'] ?? '',
            $data['button_text'] ?? 'SHOP NOW', 
            $data['button_link'] ?? 'products.php', $data['display_order'] ?? 0,
            isset($data['is_active']) ? (bool)$data['is_active'] : true
        ]);
    }

    public static function update($id, $data) {
        $sql = "UPDATE hero_slides SET title = ?, subtitle = ?, media_type = ?, button_text = ?, button_link = ?, display_order = ?, is_active = ?";
        $params = [$data['title'], $data['subtitle'] ?? '', $data['media_type'] ?? 'image', $data['button_text'] ?? 'SHOP NOW', $data['button_link'] ?? 'products.php', $data['display_order'] ?? 0, isset($data['is_active']) ? (bool)$data['is_active'] : true];
        
        if (isset($data['media_url'])) {
            $sql .= ", media_url = ?";
            $params[] = $data['media_url'];
        }
        if (isset($data['video_url'])) {
            $sql .= ", video_url = ?";
            $params[] = $data['video_url'];
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = db()->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete($id) {
        $stmt = db()->prepare("DELETE FROM hero_slides WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getById($id) {
        $stmt = db()->prepare("SELECT * FROM hero_slides WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
