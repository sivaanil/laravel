<?php
namespace Unified\Models;

class AclRolePermissionFactory extends BaseFactory
{
    const CACHE_ROLE_PREFIX = 'permissions_for_role_';
    const CACHE_PERMISSION_PREFIX = 'permissions_for_permission_';
    const CACHE_TTL = 1440;

    private $tableName = 'acl_role_permission';

    /**
     * find by role slug
     */
    public function findByRoleSlug($roleSlug)
    {
        // check the cache first
        $permissionSlugs = null;
        if ($this->isCacheRedis()) {
            $cache = $this->getCache();
            $cacheKey = self::CACHE_ROLE_PREFIX . $roleSlug;
            $permissionSlugs = $cache->get($cacheKey);
        }

        // if not there, check the db
        if ($permissionSlugs == null) {

            $rows = $this->getDb()
                ->table($this->tableName)
                ->where('role_slug', $roleSlug)
                ->get();

            $permissionSlugs = [];
            foreach ($rows as $row) {
                $permissionSlugs[] = $row->permission_slug;
            }

            // save results to the cache
            if ($this->isCacheRedis()) {
                $cache->put($cacheKey, $permissionSlugs, self::CACHE_TTL);
            }
        }

        // return results
        return $permissionSlugs;
    }
    
    /**
     * find by role slugs
     */
    public function findByRoleSlugs($roleSlugs)
    {
        // find from caches
        $cachedResults = [];
        if ($this->isCacheRedis()) {
            $uncachedRoleSlugs = [];
            $cache = $this->getCache();
            foreach ($roleSlugs as $roleSlug) {
                $foundCache = $cache->get(self::CACHE_ROLE_PREFIX . $roleSlug);
                if ($foundCache != null) {
                    $cachedResults[$roleSlug] = $foundCache;
                } else {
                    $uncachedRoleSlugs[] = $roleSlug;
                }
            }
        } else {
            $uncachedRoleSlugs = $roleSlugs;
        }

        // find what's left from database
        $uncachedResults = [];
        if ($uncachedRoleSlugs != []) {

            $records = $this->getDb()->table($this->tableName)
                ->whereIn('role_slug', $uncachedRoleSlugs)
                ->get();

            $foundRoleSlugs = [];
            foreach ($records as $record) {
                $foundRoleSlugs[$record->role_slug] = $record->role_slug;
                $uncachedResults[$record->role_slug][] = $record->permission_slug;
            }

            // include empty arrays for roles without permissions in db
            $unfoundRoleSlugs = array_diff($uncachedRoleSlugs, $foundRoleSlugs);
            foreach ($unfoundRoleSlugs as $roleSlug) {
                $uncachedResults[$roleSlug] = [];
            }

            // cache what is found
            if ($this->isCacheRedis()) {
                foreach ($uncachedResults as $roleSlug => $permissionSlugs) {
                    $cache->put(self::CACHE_ROLE_PREFIX . $roleSlug, $permissionSlugs, self::CACHE_TTL);
                }
            }
        }

        // return the cached and uncached results
        return array_merge($cachedResults, $uncachedResults);
    }

    /**
     * set permissions for role
     */
    public function setPermissionsForRole($roleSlug, array $permissionSlugs)
    {
        // get the current permissions
        $currentPermissions = $this->findByRoleSlug($roleSlug);

        // get db
        $db = $this->getDb();

        // add permissions to be wanted but not already in the db       
        $addPermissions = array_diff($permissionSlugs, $currentPermissions);
        if ($addPermissions != []) {
            $insertRecords = [];
            foreach ($addPermissions as $permissionSlug) {
                $insertRecords[] = [
                    'role_slug' => $roleSlug,
                    'permission_slug' => $permissionSlug
                ];
            }

            $db->table($this->tableName)
                ->insert($insertRecords);
        }

        // remove permissions in the db but not wanted
        $removePermissions = array_diff($currentPermissions, $permissionSlugs);

        if ($removePermissions != []) {
            $db->table($this->tableName)
                ->where('role_slug', $roleSlug)
                ->whereIn('permission_slug', array_values($removePermissions))
                ->delete();
        }

        // update the cache listing the permissions in the role
        if ($this->isCacheRedis()) {
            $cache = $this->getCache();
            $cache->put(self::CACHE_ROLE_PREFIX . $roleSlug, $permissionSlugs, self::CACHE_TTL);
        }
    }

    /**
     * findByPermissionSlug
     */
    public function findByPermissionSlug($permissionSlug)
    {
        // check the cache first
        $roleSlugs = null;
        if ($this->isCacheRedis()) {
            $cache = $this->getCache();
            $cacheKey = self::CACHE_PERMISSION_PREFIX . $permissionSlug;
            $roleSlugs = $cache->get($cacheKey);
        }

        // if not there, check the db
        if ($roleSlugs == null) {

            $rows = $this->getDb()
                ->table($this->tableName)
                ->where('permission_slug', $permissionSlug)
                ->get();

            $roleSlugs = [];
            foreach ($rows as $row) {
                $roleSlugs[] = $row->role_slug;
            }

            // save results to the cache
            if ($this->isCacheRedis()) {
                $cache->put($cacheKey, $roleSlugs, self::CACHE_TTL);
            }
        }

        // return results
        return $roleSlugs;
    }

    /**
     * remove permission slug
     */
    public function removePermissionSlug($permissionSlug)
    {
        // get all roles with the permission
        $roleSlugs = $this->findByPermissionSlug($permissionSlug);

        // delete all role-permissions with the given permission slug
        $this->getDb()
            ->table($this->tableName)
            ->where('permission_slug', $permissionSlug)
            ->delete();

        // clear the cache for each role that had the permission
        if ($this->isCacheRedis()) {
            $cache = $this->getCache();
            $cache->forget(self::CACHE_PERMISSION_PREFIX . $permissionSlug);
            foreach ($roleSlugs as $roleSlug) {
                $cache->forget(self::CACHE_ROLE_PREFIX . $roleSlug);
            }
        }
    }
}
