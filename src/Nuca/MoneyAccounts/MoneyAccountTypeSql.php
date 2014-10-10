<?php

namespace Nuca\MoneyAccounts;


use Nuca\Core\Base;

class MoneyAccountTypeSql extends Base
{
    /**
     * @throws \Exception
     */
    public function createMoneyAccountsTable()
    {
        $db = $this->getComponent('database');

        $sql = "
            CREATE TABLE IF NOT EXISTS {$this->getPrefixedTableName('moneyAccountType')} (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR (64),
                {$db->timestamps()},
                {$db->softDelete()}
            );
        ";

        $response = $db->execute($sql);

        if ($db->getVersion() < 5.6) {
            $db->createModifiedAtTrigger($this->getPrefixedTableName('moneyAccountType'));
        }

        return $response;
    }
} 