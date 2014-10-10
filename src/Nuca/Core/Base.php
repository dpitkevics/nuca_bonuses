<?php


namespace Nuca\Core;


use Symfony\Component\Yaml\Parser;

class Base 
{
    /**
     * @var string
     */
    private static $dbPrefix;

    private static $loadedComponents = array();

    /**
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        self::$dbPrefix = $prefix;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return self::$dbPrefix;
    }

    /**
     * @param $component
     * @return \Nuca\User\UserSql|\Nuca\Core\Database|\Nuca\Core\Cache|\Nuca\MoneyAccounts\MoneyAccountsSql
     * @throws \Exception
     */
    public function getComponent($component)
    {
        if (isset(self::$loadedComponents[$component])) {
            return self::$loadedComponents[$component];
        } else {
            if (isset(self::$loadedComponents['cache'])) {

                /** @var \Nuca\Core\Cache $cache */
                $cache = self::$loadedComponents['cache'];
            } else {
                $cache = null;
            }

            if ($cache !== null) {
                $components = $cache->get('components', false);
                if ($components) {
                    $components = $components->getData();
                }
            } else {
                $components = null;
            }

            if ($components === null) {
                $parser = new Parser();
                $yml = file_get_contents(_ROOT_ . '/Nuca/Config/component-map.yml');

                $data = $parser->parse($yml);

                $components = $data['components'];

                if ($cache !== null) {
                    $cache->set('components', $components);
                }
            }

            if (!array_key_exists($component, $components)) {
                throw new \Exception("Component '{$component}' not found");
            }

            $componentClass = $components[$component];
            $instance = new $componentClass;

            self::$loadedComponents[$component] = $instance;

            return $instance;
        }
    }

    /**
     * @param $component
     * @return mixed
     * @throws \Exception
     */
    protected function getTableName($component)
    {
        $cache = $this->getComponent('cache');
        $tableName = $cache->get('tableName-' . $component);

        if ($tableName) {
            return $tableName;
        }

        $parser = new Parser();
        $tableNamesYmlPath = _ROOT_ . '/Nuca/Config/table-names.yml';
        $ymlData = $parser->parse(file_get_contents($tableNamesYmlPath));

        if (!isset($ymlData[$component])) {
            throw new \Exception("Table name for '{$component}' not found");
        }

        $tableName = $ymlData[$component];

        $cache->set('tableName-' . $component, $tableName);

        return $tableName;
    }

    protected function getPrefixedTableName($component)
    {
        $cache = $this->getComponent('cache');
        $prefixedTableName = $cache->get($component . 'TableName');
        if ($prefixedTableName) {
            return $prefixedTableName;
        }

        $prefix = $this->getPrefix();

        $tableName = $this->getTableName($component);
        if ($prefix !== null) {
            $tableName = $prefix . '_' . $tableName;
        }
        $cache->set($component . 'TableName', $tableName);

        return $tableName;
    }

} 