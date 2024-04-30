<?php

$files = glob(__DIR__.'/../app/*/*/Migrations/*');
$prefix = '2023_10_24_000000_';

foreach ($files as $file) {
    $dir = dirname($file);
    $name = basename($file, '.php');
    $name = mb_substr($name, mb_strlen($prefix));
    rename($file, $dir.'/'.$prefix.$name.'.php');
}
