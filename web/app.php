<?php

use Inachis\Component\Common\Application;

$loader = require __DIR__ . '/../vendor/autoload.php';

$app = Application::getInstance();
$app->config->loadAll();
$app->router->dispatch();
