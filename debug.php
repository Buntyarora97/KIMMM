<?php
/**
 * LIVVRA System Debugger & Auto-Repair Tool
 * 
 * This script checks for common issues and attempts to fix them automatically.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>LIVVRA System Diagnostic Tool</h1>";
echo "<pre>";

// 1. Check PHP Version
echo "[1] Checking PHP Version: " . PHP_VERSION . " ... " . (PHP_VERSION_ID >= 70400 ? "OK" : "WARNING (Recommended 7.4+)") . "\n";

// 2. Check Database Connection
echo "[2] Testing Database Connection...\n";
try {
    require_once 'includes/database.php';
    $db = db();
    echo "    - Connection Successful!\n";
    
    // 3. Check Tables
    echo "[3] Verifying Database Tables...\n";
    $required_tables = ['categories', 'products', 'orders', 'order_items', 'reviews', 'promo_codes', 'settings', 'pincodes', 'hero_slides'];
    $missing_tables = [];
    
    foreach ($required_tables as $table) {
        try {
            $db->query("SELECT 1 FROM $table LIMIT 1");
            echo "    - Table '$table': FOUND\n";
        } catch (Exception $e) {
            echo "    - Table '$table': MISSING!\n";
            $missing_tables[] = $table;
        }
    }
    
    if (!empty($missing_tables)) {
        echo "    !!! ALERT: Some tables are missing. Please import 'database_setup.sql' in phpMyAdmin.\n";
    }

} catch (Exception $e) {
    echo "    - FAILED: " . $e->getMessage() . "\n";
    echo "    - Check includes/database.php for correct credentials.\n";
}

// 4. Check Folder Permissions
echo "[4] Checking Upload Directories...\n";
$dirs = ['uploads/', 'uploads/products/', 'uploads/categories/', 'uploads/hero/'];
foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "    - Directory '$dir': CREATED\n";
        } else {
            echo "    - Directory '$dir': FAILED TO CREATE\n";
        }
    } else {
        echo "    - Directory '$dir': EXISTS & " . (is_writable($dir) ? "WRITABLE" : "NOT WRITABLE") . "\n";
    }
}

// 5. Check Crucial Files
echo "[5] Verifying Core Files...\n";
$core_files = ['includes/config.php', 'includes/header.php', 'includes/footer.php', 'includes/models/Product.php', 'includes/models/Category.php'];
foreach ($core_files as $file) {
    echo "    - File '$file': " . (file_exists($file) ? "OK" : "MISSING!") . "\n";
}

echo "\n--- Diagnostic Complete ---";
echo "</pre>";
