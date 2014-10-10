<?php

namespace Nuca\Core;


use Sonata\Cache\Adapter\Cache\BaseCacheHandler;
use Sonata\Cache\CacheElement;

class Cache extends Base
{
    /**
     * @var BaseCacheHandler
     */
    private static $adapter;

    /**
     * @param BaseCacheHandler $adapter
     */
    public function setCache(BaseCacheHandler $adapter)
    {
        self::$adapter = $adapter;
    }

    /**
     * @return BaseCacheHandler
     */
    public function getCache()
    {
        return self::$adapter;
    }

    /**
     * @param $key
     * @param $value
     * @param int $timeout
     */
    public function set($key, $value, $timeout = CacheElement::DAY)
    {
        if (is_string($key)) {
            $keysToSet = array(
                $key => $key
            );
        } else {
            $keysToSet = $key;
        }
        $this->getCache()->set($keysToSet, $value, $timeout);
    }

    /**
     * @param $key
     * @return CacheElement
     */
    public function get($key, $data = true)
    {
        if (is_string($key)) {
            $keysToGet = array(
                $key => $key
            );
        } else {
            $keysToGet = $key;
        }

        $return = $this->getCache()->get($keysToGet);
        if (!$return) {
            return null;
        }
        if ($data) {
            $return = $return->getData();
        }

        return $return;
    }

    public function delete($key)
    {
        if (is_string($key)) {
            $keysToGet = array(
                $key => $key
            );
        } else {
            $keysToGet = $key;
        }

        $this->getCache()->flush($keysToGet);
    }

    public function flush()
    {
        $this->getCache()->flushAll();
    }
} 