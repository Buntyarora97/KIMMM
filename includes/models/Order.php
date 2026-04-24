<?php
require_once __DIR__ . '/../database.php';

class Order {
    public static function create($data) {
        $db = db();
        
        // ✅ FIX: Hamesha naya order create karo, kabhi bhi existing pending order update mat karo
        // Yeh logic hatana zaroori hai kyunki isse same order baar baar update hota hai
        
        // ✅ DEBUG: Log karo ki kya data aa raha hai
        error_log("=== ORDER CREATE DEBUG ===");
        error_log("Data received: " . print_r($data, true));
        error_log("Session pending_order_id: " . ($_SESSION['pending_order_id'] ?? 'NOT SET'));

        // ✅ FIX: Agar intentionally update karna hai toh alag method use karo
        // Yahan sirf NEW order create karo
        
        $sql = "INSERT INTO orders (
            order_number, 
            user_id, 
            customer_name, 
            customer_email, 
            customer_phone, 
            shipping_address, 
            city, 
            state, 
            pincode, 
            subtotal, 
            shipping_fee, 
            discount_amount, 
            total_amount, 
            payment_method, 
            payment_status, 
            order_status,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'processing', NOW())";
        
        $stmt = $db->prepare($sql);
        $orderNumber = 'LIV' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        
        // ✅ FIX: Ensure all fields are properly extracted from $data
        $params = [
            $orderNumber,
            $data['user_id'] ?? null,
            $data['customer_name'] ?? '',
            $data['customer_email'] ?? '',
            $data['customer_phone'] ?? '',
            $data['shipping_address'] ?? '',  // ✅ Yeh complete address hona chahiye
            $data['city'] ?? '',
            $data['state'] ?? '',
            $data['pincode'] ?? '',
            $data['subtotal'] ?? 0,
            $data['shipping_fee'] ?? 0,
            (float)($data['discount_amount'] ?? 0),
            $data['total_amount'] ?? 0,
            $data['payment_method'] ?? 'cashfree'
        ];
        
        error_log("SQL Params: " . print_r($params, true));
        
        $stmt->execute($params);
        $orderId = $db->lastInsertId();
        
        error_log("New Order Created - ID: $orderId, Order No: $orderNumber");
        
        if ($orderId && isset($data['items'])) {
            // ✅ Items add karo
            foreach ($data['items'] as $item) { 
                self::addItem($orderId, $item); 
            }
        }
        
        // ✅ FIX: Pending order ID sirf payment processing ke liye use karo
        // Lekin ensure karo ki agar user wapas aaye toh naya order bane
        // Isliye isse yahan set mat karo - checkout.php mein set karo jab zaroorat ho
        
        return $orderId;
    }

    // ✅ NEW: Agar kisi reason se existing order update karna ho toh alag method
    public static function updatePendingOrder($orderId, $data) {
        $db = db();
        
        // Sirf pending orders ko update karne do
        $existing = self::getById($orderId);
        if (!$existing || $existing['payment_status'] !== 'pending') {
            error_log("Cannot update order $orderId - not pending or not found");
            return false;
        }
        
        $sql = "UPDATE orders SET 
            customer_name=?, 
            customer_email=?, 
            customer_phone=?, 
            shipping_address=?, 
            city=?, 
            state=?, 
            pincode=?, 
            subtotal=?, 
            shipping_fee=?, 
            discount_amount=?, 
            total_amount=?, 
            payment_method=?,
            updated_at = NOW()
            WHERE id=?";
            
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $data['customer_name'] ?? '', 
            $data['customer_email'] ?? '', 
            $data['customer_phone'] ?? '', 
            $data['shipping_address'] ?? '', 
            $data['city'] ?? '', 
            $data['state'] ?? '', 
            $data['pincode'] ?? '', 
            $data['subtotal'] ?? 0, 
            $data['shipping_fee'] ?? 0, 
            (float)($data['discount_amount'] ?? 0), 
            $data['total_amount'] ?? 0, 
            $data['payment_method'] ?? 'cashfree', 
            $orderId
        ]);
        
        if ($result) {
            error_log("Updated pending order: $orderId");
        }
        
        return $result ? $orderId : false;
    }

    public static function addItem($orderId, $item) {
        $stmt = db()->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price, total) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$orderId, $item['id'], $item['name'], $item['quantity'], $item['price'], $item['price'] * $item['quantity']]);
    }

    public static function getAll() {
        $stmt = db()->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $stmt = db()->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getItems($orderId) {
        $stmt = db()->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public static function updateStatus($id, $status) {
        $stmt = db()->prepare("UPDATE orders SET order_status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function updateShiprocketId($id, $shiprocketId) {
        $stmt = db()->prepare("UPDATE orders SET notes = CONCAT(COALESCE(notes, ''), '\nShiprocket ID: ', ?) WHERE id = ?");
        return $stmt->execute([$shiprocketId, $id]);
    }

    public static function updatePaymentStatus($id, $status, $data = []) {
        $sql = "UPDATE orders SET payment_status = ?, updated_at = CURRENT_TIMESTAMP";
        $params = [$status];
        
        if (isset($data['payment_id'])) {
            $sql .= ", payment_id = ?";
            $params[] = $data['payment_id'];
        }
        if (isset($data['bank_ref'])) {
            $sql .= ", bank_ref = ?";
            $params[] = $data['bank_ref'];
        }
        if (isset($data['payment_response'])) {
            $sql .= ", payment_response = ?";
            $params[] = $data['payment_response'];
        }
        if (isset($data['transaction_id'])) {
            $sql .= ", transaction_id = ?";
            $params[] = $data['transaction_id'];
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = db()->prepare($sql);
        return $stmt->execute($params);
    }

    public static function getRecent($limit = 10) {
        $stmt = db()->prepare("SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getTotalCount($status = null) {
        try {
            $sql = "SELECT COUNT(*) FROM orders";
            $params = [];
            if ($status) { $sql .= " WHERE order_status = ?"; $params[] = $status; }
            $stmt = db()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    // ✅ NEW: Method to debug what was actually saved
    public static function debugOrder($orderId) {
        $order = self::getById($orderId);
        if ($order) {
            error_log("=== ORDER DEBUG ID: $orderId ===");
            error_log("Shipping Address: " . ($order['shipping_address'] ?? 'EMPTY'));
            error_log("City: " . ($order['city'] ?? 'EMPTY'));
            error_log("State: " . ($order['state'] ?? 'EMPTY'));
            error_log("Pincode: " . ($order['pincode'] ?? 'EMPTY'));
        }
        return $order;
    }
}