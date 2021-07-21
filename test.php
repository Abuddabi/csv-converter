<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Converter\Converter;

$converter = new Converter();
$test = $converter->convert('test.csv');

var_dump($test);