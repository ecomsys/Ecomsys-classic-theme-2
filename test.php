<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$app = new Application('Test', '1.0');
var_dump(method_exists($app, 'add'));
var_dump(method_exists($app, 'addCommands'));