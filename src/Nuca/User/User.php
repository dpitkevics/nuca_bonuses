<?php


namespace Nuca\User;


use Nuca\Core\Base;

class User extends Base
{
    /**
     * @throws \Exception
     */
    public function createUserTable()
    {
        $db = $this->getComponent('database');

        $sql = "
            CREATE TABLE IF NOT EXISTS {$this->getPrefixedTableName()} (
                id INT PRIMARY KEY AUTO_INCREMENT,
                login VARCHAR (32) NOT NULL,
                {$db->timestamps()},
                {$db->softDelete()}
            );
        ";

        return $db->execute($sql);
    }

    /**
     * @param $login
     * @return mixed
     * @throws \Exception
     */
    public function registerUser($login)
    {
        $db = $this->getComponent('database');

        $sql = "
            INSERT INTO {$this->getPrefixedTableName()} (login) VALUES ('{$login}');
        ";

        return $db->execute($sql);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function deleteUser($userId)
    {
        $db = $this->getComponent('database');

        $sql = "
            UPDATE {$this->getPrefixedTableName()} SET deleted_at = now() WHERE id = {$userId};
        ";

        return $db->execute($sql);
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    private function getPrefixedTableName()
    {
        // TODO: pievienot cache
        $prefix = $this->getPrefix();

        $tableName = $this->getTableName('user');
        if ($prefix !== null) {
            $tableName = $prefix . '_' . $tableName;
        }

        return $tableName;
    }
} 