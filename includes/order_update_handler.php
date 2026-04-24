<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/models/Order.php';

http_response_code(200);
header('Content-Type: application/json');

// Read raw input
$payload = file_get_contents('php://input');

// Log everything (debug)
error_log("Shiprocket Webhook RAW: " . $payload);

// ✅ IMPORTANT: Accept empty payload (Shiprocket test webhook)
if (empty($payload)) {
    echo json_encode([
        'success' => true,
        'note' => 'Empty payload accepted'
    ]);
    exit;
}

// Decode JSON
$data = json_decode($payload, true);

// If JSON invalid, still return 200 (do NOT break webhook)
if (!is_array($data)) {
    echo json_encode([
        'success' => true,
        'note' => 'Invalid JSON ignored'
    ]);
    exit;
}

// Extract fields (Shiprocket correct keys)
$orderNumber = $data['order_id'] ?? null;
$status = $data['current_status'] ?? null;

if ($orderNumber && $status) {

    $statusMap = [
        'PICKED UP' => 'shipped',
        'DELIVERED' => 'delivered',
        'CANCELLED' => 'cancelled',
        'RETURNED'  => 'returned'
    ];

    $localStatus = $statusMap[strtoupper($status)] ?? null;

    if ($localStatus) {

        $stmt = db()->prepare("SELECT id FROM orders WHERE order_number = ?");
        $stmt->execute([$orderNumber]);
        $order = $stmt->fetch();

        if ($order) {
            Order::updateStatus($order['id'], $localStatus);

            $note = "\nStatus updated by Shiprocket: " . $status;
            $stmt = db()->prepare(
                "UPDATE orders 
                 SET notes = CONCAT(COALESCE(notes, ''), ?) 
                 WHERE id = ?"
            );
            $stmt->execute([$note, $order['id']]);
        }
    }
}

// Always respond SUCCESS
echo json_encode(['success' => true]);
exit;
