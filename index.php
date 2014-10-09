<?php

use Nuca\Core\Initializer;

require_once __DIR__ . '/src/autoload.php';

$dbConnection = array(
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'db' => 'nuca_bonuses',
    'user' => 'nuca_bonuses',
    'pass' => 'bonuses',
    'prefix' => 'nuca',
);

$init = new Initializer($dbConnection);

$user = $init->getComponent('user');
