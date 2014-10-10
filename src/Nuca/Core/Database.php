<?php


namespace Nuca\Core;


class Database extends Base
{
    /**
     * @var \PDO
     */
    private static $dbh;

    public static $sqls = array();

    /**
     * @param \PDO $dbh
     */
    public function setDb(\PDO $dbh)
    {
        self::$dbh = $dbh;
    }

    /**
     * @return \PDO
     */
    public function getDb()
    {
        return self::$dbh;
    }

    /**
     * @return string
     */
    public function timestamps()
    {
        if ($this->getVersion() < 5.6) {
            $modifiedAtSql = "modified_at TIMESTAMP DEFAULT 0, ";
        } else {
            $modifiedAtSql = "modified_at TIMESTAMP DEFAULT now(), ";
        }
        $createdAtSql = "created_at TIMESTAMP DEFAULT now()";
        $sql = $modifiedAtSql . $createdAtSql;
        return $sql;
    }

    public function createModifiedAtTrigger($table)
    {
        $sql = "DROP TRIGGER IF EXISTS `update_{$table}_trigger`;";
        $this->execute($sql);
        $sql = "
            CREATE TRIGGER `update_{$table}_trigger` BEFORE UPDATE ON `{$table}`
            FOR EACH ROW SET NEW.`modified_at` = NOW()
        ";

        return $this->execute($sql);
    }

    /**
     * @return string
     */
    public function softDelete()
    {
        $sql = "deleted_at TIMESTAMP NULL";
        return $sql;
    }

    /**
     * @param $sql
     * @param array $params
     * @return \PDOStatement
     */
    public function query($sql, array $params = array())
    {
        $this->logSql($sql);

        $dbh = $this->getDb();
        $statement = $dbh->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    /**
     * @param $sql
     * @return int
     */
    public function execute($sql)
    {
        $this->logSql($sql);

        $dbh = $this->getDb();
        $result = $dbh->exec($sql);
        return $result;
    }

    public function getVersion()
    {
        $cache = $this->getComponent('cache');
        $version = $cache->get('sqlVersion');

        if ($version) {
            return $version;
        } else {
            $sql = "
            SHOW VARIABLES LIKE '%version%'
        ";

            $result = $this->query($sql);
            $row = $result->fetch();
            $versionString = $row['Value'];
            $versionParts = explode('-', $versionString);
            $version = current($versionParts);

            $cache->set('sqlVersion', $version);

            return $version;
        }
    }

    public function checkIfTableExists($table)
    {
        $cache = $this->getComponent('cache');
        $tables = $cache->get('existingTables');
        if ($tables) {
            if (array_key_exists($table, $tables)) {
                return true;
            }
        }

        $sql = "SHOW TABLES LIKE '%{$table}%'";
        $result = $this->query($sql);
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param $sql
     */
    private function logSql($sql)
    {
        self::$sqls[microtime()] = $sql;
    }
} 