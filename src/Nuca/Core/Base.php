<?php


namespace Nuca\Core;


use Symfony\Component\Yaml\Parser;

class Base 
{
    /**
     * @var string
     */
    private static $dbPrefix;

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
     * @return mixed
     * @throws \Exception
     */
    public function getComponent($component)
    {
        $parser = new Parser();
        $yml = file_get_contents(_ROOT_ . '/Nuca/Config/component-map.yml');

        $data = $parser->parse($yml);

        $components = $data['components'];
        // TODO: Cache components

        if (!array_key_exists($component, $components)) {
            throw new \Exception("Component not found");
        }

        $componentClass = $components[$component];
        $instance = new $componentClass;

        return $instance;
    }

    /**
     * @param $component
     * @return mixed
     * @throws \Exception
     */
    protected function getTableName($component)
    {
        $parser = new Parser();
        $tableNamesYmlPath = _ROOT_ . '/Nuca/Config/table-names.yml';
        $ymlData = $parser->parse(file_get_contents($tableNamesYmlPath));

        if (!isset($ymlData[$component])) {
            throw new \Exception("Table name for '{$component}' not found");
        }

        $tableName = $ymlData[$component];

        return $tableName;
    }

} 