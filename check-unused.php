<?php

$scanDirs = ['.'];
$imageDirs = ['uploads','assets'];

$used = [];

function scan($dir, &$used){
    foreach(scandir($dir) as $f){
        if($f=='.'||$f=='..') continue;
        $path = "$dir/$f";

        if(is_dir($path)){
            scan($path,$used);
        } else {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if(in_array($ext,['php','html','js','css'])){
                $c = file_get_contents($path);
                preg_match_all('/(uploads\/[^"\']+\.(jpg|jpeg|png|webp))/i',$c,$m);
                foreach($m[1] as $img){
                    $used[] = $img;
                }
            }
        }
    }
}

scan('.', $used);
$used = array_unique($used);

echo "<h2>USED IMAGES</h2>";
print_r($used);