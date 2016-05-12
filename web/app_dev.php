<?php

use Inachis\Component\CoreBundle\Application;

error_reporting(-1);

$loader = require __DIR__ . '/../vendor/autoload.php';

$app = Application::getInstance('dev');
$app->getRouter()->dispatch();
