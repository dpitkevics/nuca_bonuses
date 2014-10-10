<?php


namespace Nuca\Core;


class Initializer extends Base
{
    /**
     * @param $dbConfig
     */
    public function __construct($dbConfig, $cacheConfig)
    {
        $this->init($dbConfig, $cacheConfig);
    }

    /**
     * @param $dbConfig
     * @throws \Exception
     */
    private function init($dbConfig, $cacheConfig)
    {
        $this->setUpCache($cacheConfig);
        $this->setUpDatabaseConnection($dbConfig);
    }

    private function setUpCache($cacheConfig)
    {
        if (!isset($cacheConfig['host'])
            || !isset($cacheConfig['port'])
            || !isset($cacheConfig['database'])
            || !isset($cacheConfig['driver'])) {
            throw new \Exception("Not all requried cache config parameters are passed. Required are: host, port, database, driver");
        }

        $driver = $cacheConfig['driver'];

        unset($cacheConfig['driver']);

        if ($driver != "Apc" && $driver != "Memcached" && $driver != "Mongo" && $driver != "Noop" && $driver != "PRedis") {
            throw new \Exception("Driver '{$driver}' not available. Available cache drivers: Apc, Memcached, Mongo, Noop, PRedis");
        }

        $cacheClass = "Sonata\\Cache\\Adapter\\Cache\\" . $driver . "Cache";
        $adapter = new $cacheClass($cacheConfig);

        $cache = $this->getComponent('cache');
        $cache->setCache($adapter);
    }

    /**
     * @param $dbConfig
     * @throws \Exception
     */
    private function setUpDatabaseConnection($dbConfig)
    {
        if (!isset($dbConfig['driver'])
            || !isset($dbConfig['host'])
            || !isset($dbConfig['db'])
            || !isset($dbConfig['user'])
            || !isset($dbConfig['pass'])) {
            throw new \Exception("Not all required db config parameters are passed. Required are: driver, host, db, user and pass.");
        }

        $driver = $dbConfig['driver'];
        $host = $dbConfig['host'];
        $db = $dbConfig['db'];
        $user = $dbConfig['user'];
        $pass = $dbConfig['pass'];

        try {
            $dbh = new \PDO("{$driver}:dbname={$db};host={$host}", $user, $pass);
        } catch (\PDOException $e) {
            echo "Could not connect to database: " . $e->getMessage();
            exit;
        }

        if (_IS_DEV_) {
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        $database = $this->getComponent('database');
        $database->setDb($dbh);

        $dbPrefix = (isset($dbConfig['prefix']) ? $dbConfig['prefix'] : NULL);
        $this->setPrefix($dbPrefix);
    }
} 