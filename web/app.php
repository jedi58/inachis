<?php

use Inachis\Component\CoreBundle\Application;

$loader = require __DIR__ . '/../vendor/autoload.php';

$app = Application::getInstance('prod');
$app->router->dispatch();
