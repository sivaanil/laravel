<?php
namespace Unified\Models;

class BaseSingleton
{
    static $instance = [];
    
    /**
     * getInstance
     */
    public static function getInstance()
    {
        $className = get_called_class();
        
        if (!array_key_exists($className, self::$instance)) {
            self::$instance[$className] = new $className();
        }
        
        return self::$instance[$className];
    }
    
    /**
     * clear instances
     */
    public static function clearInstances()
    {
        static::$instance = [];
    }
    
    /**
     * set instances 
     * @param unknown $singleton
     */
    public static function setInstance($singleton)
    {
        $className = get_called_class();
        
        if ($singleton == null) {
            unset(self::$instance[$className]);
        } else {
            self::$instance[$className] = $singleton;
        }
    }
}