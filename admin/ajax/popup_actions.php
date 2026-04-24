<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

/* =========================
   SAVE POPUP
========================= */
if ($action === 'save_popup') {

    $buy_link  = $_POST['buy_link'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $video_url = '';

    // Upload directory (CORRECT PATH)
    $uploadDir = __DIR__ . '/../../uploads/popups/';

    // Folder auto create if not exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Check file upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] === 0) {

        $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        $filename = time() . '_' . uniqid() . '.' . $ext;

        // IMPORTANT: target path define
        $target = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($_FILES['video']['tmp_name'], $target)) {
            $video_url = 'uploads/popups/' . $filename;
        } else {
            header('Location: ../video-popups.php?error=upload_failed');
            exit;
        }
    }

    // Insert into database
    if ($video_url) {

        $db = db();

        // OPTIONAL: only one active popup
        if ($is_active == 1) {
            $db->query("UPDATE video_popups SET is_active = 0");
        }

        $stmt = $db->prepare("
            INSERT INTO video_popups (video_url, buy_link, is_active)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$video_url, $buy_link, $is_active]);

        header('Location: ../video-popups.php?success=1');
        exit;
    }

    header('Location: ../video-popups.php?error=no_video');
    exit;
}

/* =========================
   DELETE POPUP
========================= */
elseif ($action === 'delete_popup') {

    $id = $_POST['id'] ?? 0;

    $db = db();
    $stmt = $db->prepare("DELETE FROM video_popups WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
    exit;
}

/* =========================
   TOGGLE STATUS
========================= */
elseif ($action === 'toggle_status') {

    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? 0;

    $db = db();

    // Only one active popup allowed
    if ($status == 1) {
        $db->query("UPDATE video_popups SET is_active = 0");
    }

    $stmt = $db->prepare("UPDATE video_popups SET is_active = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    echo json_encode(['success' => true]);
    exit;
}