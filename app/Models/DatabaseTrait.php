<?php
namespace Unified\Models;

use \DB;

trait DatabaseTrait
{
    private static $db = null;
    
    /**
     * beginTransaction
     *
     * Begin Transaction in database.
     */
    protected function beginTransaction()
    {
        // make sure the db is instantiated
        $this->getDb()->beginTransaction();
    }
    
    /**
     * commitTransaction
     *
     * Commit Transaction to database.
     */
    protected function commitTransaction()
    {
        $this->getDb()->commit();
    }
    
    /**
     * rollbackTransaction
     *
     * Rollback transaction to database.
     */
    protected function rollbackTransaction()
    {
        // make sure the db is instantiateds
        $this->getDb()->rollback();
    }
    
    /**
     * getDb
     *
     * Get the database connection
     */
    protected function getDb()
    {
        if (self::$db == null) {
            $this->initDb();
        }
    
        return self::$db;
    }
    
    /**
     * initDb
     *
     * initialize database connection
     */
    protected function initDb()
    {
        if (is_null(self::$db)) {
            self::$db = (property_exists($this, 'connection') && $this->connection != null) 
                ? $this->connection : Db::connection();
            self::$db->enableQueryLog();
        }
    }

    /**
     * setDb
     *
     * Sets the database connection for the model
     */
    public function setDb($connection)
    {
        self::$db = $connection;
    }
    
    /**
     * getLastQuery
     */
    public function getLastQuery()
    {
        $db = $this->getDb();
        
        $logs = $db->getQueryLog();
        
        $sql = end($logs);
        if (!empty($sql['bindings'])) {
            $pdo = $db->getPdo();
            foreach ($sql['bindings'] as $binding) {
                $sql['query'] = preg_replace('/\?/', $pdo->quote($binding), $sql['query'], 1);
            }
        }
        
        return $sql['query'];
    }
}