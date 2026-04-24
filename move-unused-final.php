<?php

$imageDir = "uploads";
$unusedDir = "unused_images";

if (!is_dir($unusedDir)) {
    mkdir($unusedDir, 0777, true);
}

// STEP 1: GET USED IMAGES
$used = [];

function scanUsed($dir, &$used) {
    foreach (scandir($dir) as $file) {
        if ($file == '.' || $file == '..') continue;

        $path = "$dir/$file";

        if (is_dir($path)) {
            scanUsed($path, $used);
        } else {
            if (preg_match('/\.(php|html|js|css)$/', $path)) {
                $content = file_get_contents($path);

                preg_match_all('/uploads\/[^"\']+\.(jpg|jpeg|png|webp)/i', $content, $matches);

                foreach ($matches[0] as $img) {
                    $used[] = $img;
                }
            }
        }
    }
}

scanUsed('.', $used);
$used = array_unique($used);

// DEBUG (optional)
// print_r($used);

// STEP 2: SCAN ALL IMAGES (RECURSIVE)
function scanImages($dir) {
    $all = [];

    foreach (scandir($dir) as $file) {
        if ($file == '.' || $file == '..') continue;

        $path = "$dir/$file";

        if (is_dir($path)) {
            $all = array_merge($all, scanImages($path));
        } else {
            if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $path)) {
                $all[] = $path;
            }
        }
    }

    return $all;
}

$allImages = scanImages($imageDir);

// STEP 3: MOVE UNUSED
$log = fopen("unused_log.txt", "w");

foreach ($allImages as $img) {

    $relative = str_replace('./', '', $img);

    if (!in_array($relative, $used)) {

        $newPath = $unusedDir . '/' . basename($img);

        rename($img, $newPath);

        fwrite($log, "Moved: $img\n");
        echo "Moved: $img <br>";
    }
}

fclose($log);

echo "<h2>DONE SAFE ✅</h2>";