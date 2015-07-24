<?php
$dir = getcwd();

function traversalDir($dir)
{
    if (is_dir($dir)) {
        $dh = opendir($dir);

        while (($file = readdir($dh)) !== false) {
            // 剔除特殊目录
            if ($file == '.' || $file == '..') {
                continue;
            }
            
            $file = $dir.DIRECTORY_SEPARATOR.$file;
            $getfiletype = filetype($file);

            if ($getfiletype == 'dir') {
                traversalDir($file);
            } else {
                echo $file, "\n";
            }
        }

        closedir($dh);
    }
}

traversalDir($dir);