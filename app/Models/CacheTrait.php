<?php
namespace Unified\Models;

use \Cache;

trait CacheTrait
{
    protected static $cache = null;
    
    /**
     * getCache
     *
     * Get connection to the cache.
     *
     * @return \Illuminate\Cache\Repository
     */
    protected function getCache()
    {
        if (is_null(static::$cache)) {
            // instantiates \Illuminate\Cache\Repository
            static::$cache = Cache::getFacadeRoot();
        }
    
        return static::$cache;
    }

    /**
     * buildCacheKey
     *
     * @return string Cache Key
     */
    protected function buildCacheKey($prefix, $config, $suffix = '')
    {
        return $prefix . ':' . $config->toJsonString() . ':' . $suffix;
    }

    /**
     * isCacheRedis
     */
    public function isCacheRedis()
    {
        return (config('cache.default') == 'redis');
    }
}