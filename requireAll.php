<?php

function requireFiles($dir) {
    $files = scandir($dir);
    foreach($files as $f){
        if($f == "." || $f == "..") continue;
        if(is_dir($dir . "/" .$f))  requireFiles($dir . "/" . $f);
        else {
            if(explode(".", $f)[1] == "php"){
                require_once $dir ."/". $f;
            }
        }
    }
}

requireFiles("requires");
