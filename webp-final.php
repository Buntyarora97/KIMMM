<?php

$baseDir = __DIR__ . '/uploads';

function convertToWebP($filePath) {

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg','jpeg','png'])) return;

    $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $filePath);

    // Skip if already exists
    if (file_exists($webpPath)) return;

    $imageData = file_get_contents($filePath);
    $image = imagecreatefromstring($imageData);

    if (!$image) return;

    imagewebp($image, $webpPath, 85); // high quality + optimized
    imagedestroy($image);

    echo "Converted: " . str_replace(__DIR__, '', $filePath) . "<br>";
}

function scanFolder($dir) {

    foreach (scandir($dir) as $file) {

        if ($file == '.' || $file == '..') continue;

        $path = $dir . '/' . $file;

        if (is_dir($path)) {
            scanFolder($path);
        } else {
            convertToWebP($path);
        }
    }
}

scanFolder($baseDir);

echo "<h2>✅ ALL IMAGES CONVERTED</h2>";