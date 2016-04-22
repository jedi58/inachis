<?php

use Inachis\Component\Common\Application;

error_reporting(-1);

$loader = require __DIR__ . '/../vendor/autoload.php';

$app = Application::getInstance('dev');
$app->router->dispatch();
