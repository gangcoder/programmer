<?php

define('BASEDIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

include BASEDIR.DS.'IMooc'.DS.'Loader.php';
\IMooc\Loader::register();

IMooc\Test::test();