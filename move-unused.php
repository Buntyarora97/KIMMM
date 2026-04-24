<?php

$imageDirs = ['uploads','assets'];
$unusedDir = 'unused_images';

if(!is_dir($unusedDir)){
    mkdir($unusedDir,0777,true);
}

$used = [];

// scan used images
function scan($dir,&$used){
    foreach(scandir($dir) as $f){
        if($f=='.'||$f=='..') continue;
        $p="$dir/$f";

        if(is_dir($p)){
            scan($p,$used);
        } else {
            if(preg_match('/\.(php|html|js|css)$/',$p)){
                $c=file_get_contents($p);
                preg_match_all('/(uploads\/[^"\']+\.(jpg|jpeg|png|webp))/i',$c,$m);
                foreach($m[1] as $img){
                    $used[]=$img;
                }
            }
        }
    }
}

scan('.', $used);
$used=array_unique($used);

$log = fopen("unused_log.txt","w");

// move unused
foreach($imageDirs as $dir){

    if(!is_dir($dir)) continue;

    foreach(scandir($dir) as $f){

        if($f=='.'||$f=='..') continue;

        $path="$dir/$f";

        if(is_file($path)){

            if(!in_array($path,$used)){

                $new = $unusedDir.'/'.basename($path);

                rename($path,$new);

                fwrite($log,"Moved: $path\n");
                echo "Moved: $path<br>";
            }
        }
    }
}

fclose($log);

echo "<h2>DONE SAFE</h2>";