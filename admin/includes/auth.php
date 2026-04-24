<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models/Admin.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function adminRequired() {
    if (!isAdminLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}
?>