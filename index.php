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

$userSql = $init->getComponent('userSql');
//$userSql->createUserTable();

$db = $init->getComponent('database');
echo $db->getVersion();
echo $db->getVersion();
echo $db->getVersion();

echo "<pre>";
var_dump(\Nuca\Core\Database::$sqls);