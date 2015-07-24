<?php 
$image = file_get_contents('./abc.PNG');
header('Content-type:image/png;');
echo $image;