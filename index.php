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

$userSql = $init->getComponent('userSql');
$userSql->createUserTable();

echo "<pre>";
var_dump(\Nuca\Core\Database::$sqls);