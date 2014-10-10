<?php


namespace Nuca\User;


use Nuca\Core\Base;

class UserSql extends Base
{
    /**
     * @throws \Exception
     */
    public function createUserTable()
    {
        $db = $this->getComponent('database');

        $sql = "
            CREATE TABLE IF NOT EXISTS {$this->getPrefixedTableName('user')} (
                id INT PRIMARY KEY AUTO_INCREMENT,
                login VARCHAR (32) NOT NULL,
                {$db->timestamps()},
                {$db->softDelete()}
            );
        ";

        $response = $db->execute($sql);

        if ($db->getVersion() < 5.6) {
            $db->createModifiedAtTrigger($this->getPrefixedTableName('user'));
        }

        return $response;
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
            INSERT INTO {$this->getPrefixedTableName('user')} (login) VALUES ('{$login}');
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
            UPDATE {$this->getPrefixedTableName('user')} SET deleted_at = now() WHERE id = {$userId};
        ";

        return $db->execute($sql);
    }
} 