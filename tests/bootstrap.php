<?php
$autoload_file = dirname(__DIR__) . '/vendor/autoload.php';
if (! file_exists($autoload_file)) {
    echo "Run composer install and try again";
    exit(1);
}
$loader = require $autoload_file;
