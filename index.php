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

$cacheConnection = array(
    'host'     => '127.0.0.1',
    'port'     => 6379,
    'database' => 42,
    'driver' => 'PRedis',
);

$init = new Initializer($dbConnection, $cacheConnection);

$moneyAccountSql = $init->getComponent('moneyAccountSql');
$moneyAccountSql->createMoneyAccountsTable();
