<?php
require_once __DIR__ . '/../database.php';

class Review {

    /* =========================
       GET ALL REVIEWS (ADMIN)
    ========================= */
    public static function getAll() {
        try {
            $sql = "
                SELECT r.*, p.name AS product_name
                FROM reviews r
                LEFT JOIN products p ON r.product_id = p.id
                ORDER BY r.created_at DESC
            ";
            return db()->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Review::getAll error: " . $e->getMessage());
            return [];
        }
    }

    /* =========================
       CREATE REVIEW (FRONTEND)
    ========================= */
    public static function create($data) {
        try {
            $stmt = db()->prepare("
                INSERT INTO reviews 
                (product_id, name, rating, comment, is_active, created_at)
                VALUES (?, ?, ?, ?, 1, NOW())
            ");

            return $stmt->execute([
                $data['product_id'],
                $data['name'] ?? 'Customer',
                $data['rating'],
                $data['comment']
            ]);

        } catch (PDOException $e) {
            error_log("Review::create error: " . $e->getMessage());
            return false;
        }
    }

    /* =========================
       GET REVIEWS BY PRODUCT
    ========================= */
    public static function getByProduct($productId) {
        try {
            $stmt = db()->prepare("
                SELECT *
                FROM reviews
                WHERE product_id = ?
                  AND is_active = 1
                ORDER BY created_at DESC
            ");
            $stmt->execute([$productId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Review::getByProduct error: " . $e->getMessage());
            return [];
        }
    }
}
