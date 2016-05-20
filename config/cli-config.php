<?php
use Inachis\Component\CoreBundle\Application;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/../vendor/autoload.php';

$app = Application::getInstance('dev');
return ConsoleRunner::createHelperSet($app->getService('em'));

