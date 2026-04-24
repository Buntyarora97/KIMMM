<?php
require_once __DIR__ . '/../database.php';

class ProductImage {
    public static function getByProductId($productId) {
        $stmt = db()->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY display_order ASC, created_at ASC");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function getPrimaryImage($productId) {
        $stmt = db()->prepare("SELECT * FROM product_images WHERE product_id = ? AND is_primary = 1 LIMIT 1");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    public static function add($productId, $imagePath, $isPrimary = false) {
        // If this is primary, unset all others
        if ($isPrimary) {
            $stmt = db()->prepare("UPDATE product_images SET is_primary = 0 WHERE product_id = ?");
            $stmt->execute([$productId]);
        }
        
        $stmt = db()->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)");
        return $stmt->execute([$productId, $imagePath, $isPrimary ? 1 : 0]);
    }

    public static function setPrimary($imageId, $productId) {
        // Unset all others
        $stmt = db()->prepare("UPDATE product_images SET is_primary = 0 WHERE product_id = ?");
        $stmt->execute([$productId]);
        
        // Set this one as primary
        $stmt = db()->prepare("UPDATE product_images SET is_primary = 1 WHERE id = ?");
        return $stmt->execute([$imageId]);
    }

    public static function delete($imageId) {
        $stmt = db()->prepare("DELETE FROM product_images WHERE id = ?");
        return $stmt->execute([$imageId]);
    }

    public static function deleteByProduct($productId) {
        $stmt = db()->prepare("DELETE FROM product_images WHERE product_id = ?");
        return $stmt->execute([$productId]);
    }
}
?>
