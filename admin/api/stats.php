<?php
require_once __DIR__ . '/../../includes/config.php';
header('Content-Type: application/json');
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false]);
    exit;
}
echo json_encode([
    'success' => true,
    'orders' => Order::getTotalCount(),
    'revenue' => db()->query("SELECT SUM(total_amount) FROM orders WHERE payment_status = 'paid'")->fetchColumn() ?: 0,
    'inquiries' => ContactInquiry::getNewCount()
]);
