<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'get_active') {
    $db = db();
    $popup = $db->query("SELECT * FROM video_popups WHERE is_active = TRUE ORDER BY created_at DESC LIMIT 1")->fetch();
    echo json_encode(['success' => true, 'popup' => $popup]);
} elseif ($action === 'increment_view') {
    $id = $_POST['id'] ?? 0;
    if ($id) {
        $db = db();
        $stmt = $db->prepare("UPDATE video_popups SET view_count = view_count + 1 WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    }
}
