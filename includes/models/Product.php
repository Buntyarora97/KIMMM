<?php
require_once __DIR__ . '/../database.php';

class Product {

    /* =========================
       INTERNAL PRICE PROCESSOR
    ========================= */
    private static function processProduct($product) {
        if (!$product) return null;

        $price       = (float)($product['price'] ?? 0);
        $offerPrice  = (float)($product['offer_price'] ?? 0);

        // Price Processing
        $product['final_price'] = $price;
        $product['discount_percent'] = 0;

        if ($offerPrice > 0 && $offerPrice < $price) {
            $product['final_price'] = $offerPrice;
            $product['discount_percent'] = round((($price - $offerPrice) / $price) * 100);
        }

        // Rating Safety
        $product['rating'] = (float)($product['rating'] ?? 0);
        $product['reviews_count'] = (int)($product['reviews_count'] ?? 0);
        
        // 🚀 SHIPROCKET DIMENSIONS - Set default values if null
        $product['weight_kg'] = $product['weight_kg'] ?? 0.5; // Default 500g
        $product['length_cm'] = $product['length_cm'] ?? 15;  // Default 15cm
        $product['width_cm'] = $product['width_cm'] ?? 10;   // Default 10cm
        $product['height_cm'] = $product['height_cm'] ?? 8;   // Default 8cm
        
        // Calculate volumetric weight for shipping
        $l = (float)($product['length_cm'] ?? 0);
        $w = (float)($product['width_cm'] ?? 0);
        $h = (float)($product['height_cm'] ?? 0);
        $product['volumetric_weight'] = ($l * $w * $h) / 5000;
        $product['dead_weight'] = (float)($product['weight_kg'] ?? 0);

        return $product;
    }   

    private static function processProducts($products) {
        if (!$products) return [];
        foreach ($products as &$p) {
            $p = self::processProduct($p);
        }
        return $products;
    }

    /* =========================
       GET SINGLE PRODUCT
    ========================= */
    public static function getBySlug($slug) {
        try {
            $stmt = db()->prepare("SELECT * FROM products WHERE slug = ? AND is_active = 1 LIMIT 1");
            $stmt->execute([trim($slug)]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$product) return null;

            $cat = db()->prepare("SELECT name FROM categories WHERE id = ?");
            $cat->execute([$product['category_id']]);
            $product['category_name'] = $cat->fetchColumn();

            $ratingStmt = db()->prepare("SELECT COALESCE(AVG(rating),0) as rating, COUNT(id) as reviews_count FROM reviews WHERE product_id = ?");
            $ratingStmt->execute([$product['id']]);
            $ratingData = $ratingStmt->fetch(PDO::FETCH_ASSOC);
            $product['rating'] = $ratingData['rating'];
            $product['reviews_count'] = $ratingData['reviews_count'];

            return self::processProduct($product);
        } catch (Exception $e) {
            error_log("getBySlug Error: " . $e->getMessage());
            return null;
        }
    }

    public static function getById($id) {
        try {
            // Basic product fetch first
            $stmt = db()->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) return null;

            // Category name
            $cat = db()->prepare("SELECT name FROM categories WHERE id = ?");
            $cat->execute([$product['category_id']]);
            $product['category_name'] = $cat->fetchColumn();

            // Rating
            $ratingStmt = db()->prepare("SELECT COALESCE(AVG(rating),0) as rating, COUNT(id) as reviews_count FROM reviews WHERE product_id = ?");
            $ratingStmt->execute([$id]);
            $ratingData = $ratingStmt->fetch(PDO::FETCH_ASSOC);

            $product['rating'] = $ratingData['rating'];
            $product['reviews_count'] = $ratingData['reviews_count'];

            return self::processProduct($product);

        } catch (Exception $e) {
            error_log("getById Error: " . $e->getMessage());
            return null;
        }
    }

    /* =========================    
       FEATURED PRODUCTS
    ========================= */
    public static function getFeatured() {
        try {
            $stmt = db()->prepare("
                SELECT p.*, 
                       c.name AS category_name,
                       COALESCE(AVG(r.rating),0) AS rating,
                       COUNT(r.id) AS reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE p.is_featured = 1 AND p.is_active = 1
                GROUP BY p.id, c.name
                ORDER BY 
                    CASE 
                        WHEN p.name LIKE '%Combo%' THEN 2
                        WHEN p.name LIKE '%Pack%' THEN 2
                        ELSE 1
                    END,
                    p.id DESC
                LIMIT 8
            ");
            $stmt->execute();
            return self::processProducts($stmt->fetchAll());
        } catch (Exception $e) {
            error_log("Featured Products Error: " . $e->getMessage());
            return [];
        }
    }

    /* =========================    
       NEW ARRIVALS
    ========================= */
    public static function getNewArrivals($limit = 8) {
        try {
            $stmt = db()->prepare("
                SELECT p.*, 
                       c.name AS category_name,
                       COALESCE(AVG(r.rating),0) AS rating,
                       COUNT(r.id) AS reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE p.is_active = 1
                GROUP BY p.id, c.name
                ORDER BY 
                    CASE 
                        WHEN p.name LIKE '%Combo%' THEN 2
                        WHEN p.name LIKE '%Pack%' THEN 2
                        ELSE 1
                    END,
                    p.id DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return self::processProducts($stmt->fetchAll());
        } catch (Exception $e) {
            error_log("New Arrivals Error: " . $e->getMessage());
            return [];
        }
    }

    /* =========================
       CREATE PRODUCT
    ========================= */
    public static function create($data)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));

        $sql = "INSERT INTO products (
            category_id, name, slug, sku,
            price, mrp, offer_price, offer_label,
            stock_qty, stock_status,
            short_description, long_description, benefits,
            usage_instructions, testing_info, reward_coins,
            is_featured, is_active,
            weight_kg, length_cm, width_cm, height_cm
        ) VALUES (
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?,
            ?, ?, ?, ?
        )";

        $stmt = db()->prepare($sql);

        $stmt->execute([
            $data['category_id'],
            $data['name'],
            $slug,
            $data['sku'] ?? '',
            $data['price'],
            $data['mrp'] ?? 0,
            $data['offer_price'] ?? 0,
            $data['offer_label'] ?? '',
            $data['stock_qty'] ?? 0,
            $data['stock_status'] ?? 'in_stock',
            $data['short_description'] ?? '',
            $data['long_description'] ?? '',
            $data['benefits'] ?? '',
            $data['usage_instructions'] ?? '',
            $data['testing_info'] ?? '',
            $data['reward_coins'] ?? 0,
            $data['is_featured'] ?? 0,
            $data['is_active'] ?? 1,
            // 🚀 SHIPROCKET DIMENSIONS
            $data['weight_kg'] ?? 0.5,
            $data['length_cm'] ?? 15,
            $data['width_cm'] ?? 10,
            $data['height_cm'] ?? 8
        ]);

        return db()->lastInsertId();
    }

    /* =========================
       UPDATE PRODUCT
    ========================= */
    public static function update($id, $data) {

        $sql = "
            UPDATE products SET
                category_id = ?,
                name = ?,
                sku = ?,
                price = ?,
                mrp = ?,
                offer_price = ?,
                offer_label = ?,
                short_description = ?,
                long_description = ?,
                benefits = ?,
                usage_instructions = ?,
                testing_info = ?,
                stock_qty = ?,
                stock_status = ?,
                reward_coins = ?,
                is_featured = ?,
                is_active = ?,
                weight_kg = ?,
                length_cm = ?,
                width_cm = ?,
                height_cm = ?
        ";

        $params = [
            $data['category_id'],
            $data['name'],
            $data['sku'] ?? null,
            $data['price'],
            $data['mrp'] ?? 0,
            $data['offer_price'] ?? 0,
            $data['offer_label'] ?? '',
            $data['short_description'] ?? '',
            $data['long_description'] ?? '',
            $data['benefits'] ?? '',
            $data['usage_instructions'] ?? '',
            $data['testing_info'] ?? '',
            $data['stock_qty'] ?? 0,
            $data['stock_status'] ?? 'in_stock',
            $data['reward_coins'] ?? 0,
            isset($data['is_featured']) ? (int)$data['is_featured'] : 0,
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            // 🚀 SHIPROCKET DIMENSIONS
            $data['weight_kg'] ?? 0.5,
            $data['length_cm'] ?? 15,
            $data['width_cm'] ?? 10,
            $data['height_cm'] ?? 8
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

        try {
            $stmt = db()->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Product::update Error: " . $e->getMessage());
            return false;
        }
    }

    public static function getAll($onlyActive = false) {
        try {
            $sql = "
                SELECT p.*, 
                       c.name AS category_name,
                       COALESCE(AVG(r.rating),0) AS rating,
                       COUNT(r.id) AS reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id
            ";

            if ($onlyActive) {
                $sql .= " WHERE p.is_active = 1";
            }

            $sql .= "
                GROUP BY p.id, c.name
                ORDER BY 
                  CASE 
                    WHEN p.name LIKE '%Combo%' THEN 2
                    WHEN p.name LIKE '%Pack%' THEN 2
                    ELSE 1
                  END,
                  p.id DESC
            ";

            $stmt = db()->prepare($sql);
            $stmt->execute();
            return self::processProducts($stmt->fetchAll());
        } catch (Exception $e) {
            error_log("Product::getAll Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getByCategory($slug) {
        try {
            $stmt = db()->prepare("
                SELECT p.*, 
                       c.name AS category_name,
                       COALESCE(AVG(r.rating),0) AS rating,
                       COUNT(r.id) AS reviews_count
                FROM products p
                JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE c.slug = ? AND p.is_active = 1
                GROUP BY p.id, c.name
                ORDER BY p.id DESC
            ");
            $stmt->execute([$slug]);
            return self::processProducts($stmt->fetchAll());
        } catch (Exception $e) {
            return [];
        }
    }

    public static function search($query) {
        try {
            $q = "%$query%";
            $stmt = db()->prepare("
                SELECT p.*, 
                       c.name AS category_name,
                       COALESCE(AVG(r.rating),0) AS rating,
                       COUNT(r.id) AS reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE (p.name LIKE ? OR p.short_description LIKE ? OR p.long_description LIKE ?)
                AND p.is_active = 1
                GROUP BY p.id, c.name
                ORDER BY p.id DESC
            ");
            $stmt->execute([$q, $q, $q]);
            return self::processProducts($stmt->fetchAll());
        } catch (Exception $e) {
            return [];
        }
    }
    
    /* =========================
       SHIPROCKET HELPER METHOD
    ========================= */
    public static function getShippingDimensions($productId) {
        $product = self::getById($productId);
        if (!$product) return null;
        
        return [
            'weight' => $product['weight_kg'],
            'length' => $product['length_cm'],
            'width' => $product['width_cm'],
            'height' => $product['height_cm'],
            'volumetric_weight' => $product['volumetric_weight'],
            'chargeable_weight' => max($product['weight_kg'], $product['volumetric_weight'])
        ];
    }
}