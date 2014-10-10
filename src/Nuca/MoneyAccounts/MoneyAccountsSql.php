<?php

namespace Nuca\MoneyAccounts;


use Nuca\Core\Base;

class MoneyAccountsSql extends Base
{
    /**
     * @throws \Exception
     */
    public function createMoneyAccountsTable()
    {
        $db = $this->getComponent('database');

        if (!$db->checkIfTableExists($this->getPrefixedTableName('user'))) {
            // TODO: pielikt izveidošanu
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS {$this->getPrefixedTableName('moneyAccount')} (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT,
                input DECIMAL(18, 2) DEFAULT 0.00,
                output DECIMAL(18, 2) DEFAULT 0.00,
                balance DECIMAL(18, 2) DEFAULT 0.00,
                money_account_type_id INT,
                {$db->timestamps()},
                {$db->softDelete()},
                FOREIGN KEY (user_id) REFERENCES {$this->getPrefixedTableName('user')} (id),
                FOREIGN KEY (money_account_type_id) REFERENCES {$this->getPrefixedTableName('moneyAccountType')} (id)
            );
        ";

        $response = $db->execute($sql);

        if ($db->getVersion() < 5.6) {
            $db->createModifiedAtTrigger($this->getPrefixedTableName('moneyAccount'));
        }

        return $response;
    }
} 