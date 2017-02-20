<?php
namespace Unified\Models;

use \Exception;
use Unified\Models\AppException;
use Unified\Services\API\ServiceResponse;
use Unified\Models\AclRolePermissionFactory;

class PermissionFactory extends BaseFactory
{
    const ALL_CACHE_TAG = 'all_permissions';
    const CACHE_PREFIX = 'permissions_since_';
    const VERY_SHORT_CACHE_TTL = 1440;
    
    private $tableName = 'acl_permissions';
    
    /**
     * getAllSince
     */   
    public function getAllSince($since = null)
    {
        // get from cache
        $keyedRecords = null;
        if ($this->isCacheRedis()) {
            $cache = $this->getCache();
            $cacheKey = self::CACHE_PREFIX . $since;
            $keyedRecords = $cache->tags(self::ALL_CACHE_TAG)->get($cacheKey);
        }

        // if not found...
        if ($keyedRecords == null) {

            // get from database
            $query = $this->getDb()
                ->table($this->tableName);

            if ($since != null) {
                $sinceMysqlDate = date('Y-m-d H:i:s', $since); // assuming php timezone = mysql timezone
                $query->where('updated_at', '>=', $sinceMysqlDate);
            } else {
                $query->where('deleted', false);
            }

            $results = $query->get();
            
            // key by slug
            $keyedRecords = [];
            foreach ($results as $row) {
                $permission = $this->generatePermission($row);
                $keyedRecords[$row->slug] = $permission->toArray();
            }
            
            // save to the cache
            if ($this->isCacheRedis()) {
                $cache->tags(self::ALL_CACHE_TAG)->put($cacheKey, $keyedRecords, self::VERY_SHORT_CACHE_TTL);
            }
        }
        
        return $keyedRecords;
    }

    /**
     * modify
     * 
     * Update the permission of the given id.
     * 
     * @param $permissionId integer Permission Id
     * @param $updates array List of fields to be updated (only 'updatable columns' will be affected)
     */
    public function modify($permissionId, $updates)
    {
        $updatableColumns = ['title', 'description'];
        
        $filteredUpdate = array_intersect_key($updates, array_flip($updatableColumns));

        $numberOfAffectedRows = $this->getDb()
            ->table($this->tableName)
            ->where('id', $permissionId)
            ->where('deleted', false)
            ->limit(1)
            ->update($filteredUpdate);

        // flush the cache
        if ($this->isCacheRedis() && $numberOfAffectedRows > 0) {
            $this->getCache()->tags(self::ALL_CACHE_TAG)->flush();
        }
    }

    /**
     * remove
     * 
     * Mark the permission as "deleted".
     */
    public function remove($id)
    {
        // make sure the permission exists
        $results = $this->getDb()
            ->table($this->tableName)
            ->where('id', $id)
            ->limit(1)
            ->get();
        
        if (count($results) == 0) {
            throw new AppException('id_not_found', 'Permission of id ' . $id . ' not found.', ServiceResponse::NOT_FOUND);   
        }
        
        $permission = $results[0];

        // transaction block
        try {
            // start db transaction
            $this->beginTransaction();

            // clear out the role/permissions that use the removed permission
            AclRolePermissionFactory::getInstance()->removePermissionSlug($permission->slug);

            // remove permission from database
            $this->getDb()
                ->table($this->tableName)
                ->where('id', $id)
                ->limit(1)
                ->update(['deleted' => true]);

            // commit db transaction
            $this->commitTransaction();
            
            // flush the cache
            if ($this->isCacheRedis()) {
                $this->getCache()->tags(self::ALL_CACHE_TAG)->flush();
            }

        } catch (Exception $e) {
            // rollback the transaction
            $this->rollbackTransaction();
            throw $e;
        }
    }

    /**
     * upsert
     * 
     * If the slug already exists, update it with the new data (even if it was "deleted" before).
     * If the slug doesn't exist, create a new one.
     */
    public function upsert($slug, $title, $description)
    {
        $database = $this->getDb();
        
        // does the record already exist?
        $permissionRecords = $database->table($this->tableName)
            ->where('slug', $slug)
            ->limit(1)
            ->get();
        
        $count = count($permissionRecords);
        
        // insert if it doesn't
        if ($count == 0) {
            $permissionId = $database->table($this->tableName)
                ->insertGetId([
                    'slug' => $slug,
                    'title' => $title, 
                    'description' => $description
                ]);

        // if it does...
        } else {
            
            $permissionRecord = $permissionRecords[0];
            
            // See if it is in active use.
            // If it is, throw an exception.
            if (!$permissionRecord->deleted) {
                throw new AppException('slug_unavailable', 'Slug ' . $slug . ' is already in use', ServiceResponse::UNPROCESSABLE_ENTITY);   
            }
            
            // If not, claim it.
            $database->table($this->tableName)
                ->where('slug', $slug)
                ->limit(1)
                ->update([
                    'title' => $title, 
                    'description' => $description, 
                    'deleted' => false
                ]);

            $permissionId = $permissionRecord->id;
        }
        
        // flush the cache
        if ($this->isCacheRedis()) {
            $this->getCache()->tags(self::ALL_CACHE_TAG)->flush();
        }
        
        return $permissionId;
    }
    
    /**
     * generatePermission
     */
    public function generatePermission($data)
    {
        return new Permission($data);
    }
}