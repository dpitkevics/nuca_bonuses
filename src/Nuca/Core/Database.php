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
        $sql = "modified_at TIMESTAMP DEFAULT now(),
                created_at TIMESTAMP DEFAULT now()";
        return $sql;
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

    /**
     * @param $sql
     */
    private function logSql($sql)
    {
        self::$sqls[microtime()] = $sql;
    }
} 