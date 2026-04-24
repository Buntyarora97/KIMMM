<?php

$source = "unused_images";
$destination = "uploads";

function restoreImages($dir, $destination) {

    $files = scandir($dir);

    foreach ($files as $file) {

        if ($file == '.' || $file == '..') continue;

        $path = $dir . '/' . $file;

        if (is_dir($path)) {

            restoreImages($path, $destination);

        } else {

            $filename = basename($file);

            // Decide folder based on filename
            if (strpos($filename, 'cat_') !== false) {
                $targetDir = $destination . '/categories/';
            } elseif (strpos($filename, 'gallery_') !== false) {
                $targetDir = $destination . '/products/';
            } elseif (strpos($filename, 'img_') !== false) {
                $targetDir = $destination . '/products/';
            } elseif (strpos($filename, 'story') !== false || strpos($dir, 'stories') !== false) {
                $targetDir = $destination . '/stories/';
            } else {
                $targetDir = $destination . '/products/';
            }

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $newPath = $targetDir . $filename;

            rename($path, $newPath);

            echo "Restored: $newPath <br>";
        }
    }
}

restoreImages($source, $destination);

echo "<h2>✅ ALL IMAGES RESTORED</h2>";

?>