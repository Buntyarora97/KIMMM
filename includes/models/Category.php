<?php
require_once __DIR__ . '/../database.php';

class Category {

    public static function create($data) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));

        $stmt = db()->prepare("
            INSERT INTO categories 
            (name, slug, description, image, video, icon_class, icon_upload, sort_order, is_active, 
             show_on_mobile_top_slider, show_on_mobile_concern, show_on_desktop_concern, show_in_top_menu)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['name'],
            $slug,
            $data['description'] ?? '',
            $data['image'] ?? '',
            $data['video'] ?? '',
            $data['icon'] ?? 'fa-leaf',
            $data['icon_upload'] ?? '',
            (int)($data['sort_order'] ?? 0),
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            isset($data['show_on_mobile_top_slider']) ? (int)$data['show_on_mobile_top_slider'] : 0,
            isset($data['show_on_mobile_concern']) ? (int)$data['show_on_mobile_concern'] : 1,
            isset($data['show_on_desktop_concern']) ? (int)$data['show_on_desktop_concern'] : 1,
            isset($data['show_in_top_menu']) ? (int)$data['show_in_top_menu'] : 1
        ]);
    }

    public static function update($id, $data) {
        $sql = "
            UPDATE categories SET
                name = ?,
                description = ?,
                icon_class = ?,
                icon_upload = ?,
                sort_order = ?,
                is_active = ?,
                show_on_mobile_top_slider = ?,
                show_on_mobile_concern = ?,
                show_on_desktop_concern = ?,
                show_in_top_menu = ?
        ";

        $params = [
            $data['name'],
            $data['description'] ?? '',
            $data['icon'] ?? 'fa-leaf',
            $data['icon_upload'] ?? '',
            (int)($data['sort_order'] ?? 0),
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            isset($data['show_on_mobile_top_slider']) ? (int)$data['show_on_mobile_top_slider'] : 0,
            isset($data['show_on_mobile_concern']) ? (int)$data['show_on_mobile_concern'] : 1,
            isset($data['show_on_desktop_concern']) ? (int)$data['show_on_desktop_concern'] : 1,
            isset($data['show_in_top_menu']) ? (int)$data['show_in_top_menu'] : 1
        ];

        if (!empty($data['image'])) {
            $sql .= ", image = ?";
            $params[] = $data['image'];
        }

        if (!empty($data['video'])) {
            $sql .= ", video = ?";
            $params[] = $data['video'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = db()->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete($id) {
        return db()->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    }

    public static function getById($id) {
        $stmt = db()->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getAll($onlyActive = false) {
        $sql = "SELECT * FROM categories";
        if ($onlyActive) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY sort_order ASC, id ASC";

        $stmt = db()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    public static function getBySlug($slug) {
        $stmt = db()->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}
