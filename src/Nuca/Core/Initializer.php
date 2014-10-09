<?php


namespace Nuca\Core;


class Initializer extends Base
{
    /**
     * @param $dbConfig
     */
    public function __construct($dbConfig)
    {
        $this->init($dbConfig);
    }

    /**
     * @param $dbConfig
     * @throws \Exception
     */
    private function init($dbConfig)
    {
        $this->setUpDatabaseConnection($dbConfig);
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

        $database = $this->getComponent('database');
        $database->setDb($dbh);

        $dbPrefix = (isset($dbConfig['prefix']) ? $dbConfig['prefix'] : NULL);
        $this->setPrefix($dbPrefix);
    }
} 