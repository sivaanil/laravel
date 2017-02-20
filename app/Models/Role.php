<?php
namespace Unified\Models;

use \Exception;
use Unified\Http\Helpers\QueryParameters;

class Role extends BaseModel
{
    const ROLES_CACHE_PREFIX = '/roles';
    const ROLES_CACHE_TAG = '/roles';
    const ROLE_BY_ID_PREFIX = 'role_by_id:';
    const CACHE_TTL = 1440; // one day (in minutes)

    private $aclRolePermissionFactory = null;
    protected $table = 'acl_roles';
    public $timestamps = false;

    /**
     * Add role to database/cache
     *
     * @param array $content New roles content (already verified)
     *
     * @return array
     */
    public function addRole($content)
    {
        try {
            // start transaction to database
            $this->beginTransaction();
            
            // separate permissions from data
            $permissions = isset($content['permissions']) ? $content['permissions'] : [];
            unset($content['permissions']);
            
            // save content to role's table
            $role = $this->createRole($content);
            
            // set permissions
            AclRolePermissionFactory::getInstance()->setPermissionsForRole($role->slug, $permissions);
            
            // set return values
            $returnValues = [
                'status' => 1,
                'roleId' => $role->id
            ];

            // commit transaction
            $this->commitTransaction();

            // clear cache
            if ($this->isCacheRedis()) {
                $this->getCache()->tags(self::ROLES_CACHE_TAG)->flush();
            }

        } catch (Exception $e) {
            
            // Rollback transaction and return error
            $this->rollbackTransaction();
            $returnValues = $this->generateError('Unable to add role: ' . $e->getMessage());
        }

        // return the return values
        return $returnValues;
    }

    /**
     * deleteRole
     */
    public function deleteRole($content)
    {
        try {
            // start transaction to database
            $this->beginTransaction();

            $role = $this->findById($content['id']);
            
            //
            // TODO: remove role from users/entities/etc
            //
            
            // set permissions
            AclRolePermissionFactory::getInstance()->setPermissionsForRole($role->slug, []);
            
            // remove role from db
            $this->removeRole($content['id']);

            // commit the transaction, and set return values
            $this->commitTransaction();
            $returnValues = ['status' => 1];

            // if the cache is available, clear the roles cache
            if ($this->isCacheRedis()) {
                $this->getCache()->tags(self::ROLES_CACHE_TAG)->flush();
            }

        } catch (Exception $e) {
            
            // throw the transaction away and send back and error message.
            $this->rollbackTransaction();
            $returnValues = $this->generateError('Unable to remove role: ' . $e->getMessage());
        }

        // return the return values
        return $returnValues;
    }

    /**
     * findById
     */
    public function findById($roleId)
    {
        $record = null;

        // get from cache, if possible
        if ($this->isCacheRedis()) {
            $cache = $this->getCache();
            $cacheKey = self::ROLE_BY_ID_PREFIX . $roleId;
            $record = $cache->tags(self::ROLES_CACHE_TAG)->get(self::ROLE_BY_ID_PREFIX . $roleId);
        }

        if (is_null($record)) {

            // get from database;
            $records = $this->getTableConnection()
                ->where('id', $roleId)
                ->limit(1)
                ->get();


            // if the record wasn't found, throw an exception
            if (!isset($records[0])) {
                throw new AppException('role_id_not_found', 'Role of Id ' . $roleId . ' does not exist.');
            }

            $record = $records[0];
            
            // get permissions
            $record->permissions = AclRolePermissionFactory::getInstance()->findByRoleSlug($record->slug);

            // set cache
            if ($this->isCacheRedis()) {
                $cache->tags(self::ROLES_CACHE_TAG)->put($cacheKey, $record, self::CACHE_TTL);
            }
        }

        return $record;
    }

    /**
     * getRoles
     *
     * Get the roles data from the database/cache.
     *
     * @return array Roles Data
     */
    public function getRoles(QueryParameters $queryParameters, $allowedNodeIds = [])
    {
        // check the cache, if available
        $results = null;
        if ($this->isCacheRedis()) {
            $cache = $this->getCache();
            $cacheKey = $this->buildRolesCacheKey($queryParameters, $allowedNodeIds);
            $results = $cache->tags(self::ROLES_CACHE_TAG)->get($cacheKey);
        }
        
        // if the cache doesn't have the results, get it from the database
        if ($results == null) {

            // get roles from db
            $results = $this->findRolesInDatabase($queryParameters, $allowedNodeIds);

            // get the permissions for each role
            $roleSlugs = [];
            foreach ($results['roles'] as $row) {
                $roleSlugs[] = $row->slug;
            }
            
            $rolePermissions = AclRolePermissionFactory::getInstance()->findByRoleSlugs($roleSlugs);
            
            // attach permissions to roles
            foreach ($results['roles'] as &$result) {
                $result->permissions = isset($rolePermissions[$result->slug]) ? $rolePermissions[$result->slug] : [];
            }

            // save results to the cache
            if ($this->isCacheRedis()) {
                $cache->tags(self::ROLES_CACHE_TAG)->put($cacheKey, $results, self::CACHE_TTL);
            }
        }

        return $results;
    }

    /**
     * modifyRole
     *
     * @param array $content New roles content (already verified)
     */
    public function modifyRole($content)
    {
        try {
            // start transaction to database
            $this->beginTransaction();
            
            $permissions = (isset($content['permissions'])) ? $content['permissions'] : null;
            unset($content['permissions']);
            
            // update role
            $roleSlug = $this->updateRole($content);

            // set permissions (if presented)
            if (is_array($permissions)) {
                AclRolePermissionFactory::getInstance()->setPermissionsForRole($roleSlug, $permissions);
            }
            
            // commit the transaction, and set return values
            $this->commitTransaction();
            $returnValues = ['status' => 1];

            // if the cache is available, clear the roles cache
            if ($this->isCacheRedis()) {
                $this->getCache()->tags(self::ROLES_CACHE_TAG)->flush();
            }
            
        } catch (Exception $e) {
            // throw the transaction away and send back and error message.
            $this->rollbackTransaction();
            $returnValues = $this->generateError('Unable to modify role: ' . $e->getMessage());
        }

        // return the return values
        return $returnValues;
    }

    /**
     * generateTableQuery
     *
     * Returns database table connection.
     */
    public function generateTableQuery($tableQuery = null)
    {
        return new Query(($tableQuery == null) ? $this->getTableConnection() : $tableQuery);
    }
    
    /**
     * getTable
     */
    public function getTableConnection()
    {
        return $this->getDb()->table($this->table);
    }
   
    /**
     * buildRolesCacheKey
     */
    protected function buildRolesCacheKey(QueryParameters $config, $allowedNodeIds)
    {
        return $this->buildCacheKey(self::ROLES_CACHE_PREFIX, $config, 'nodes:' .  implode('.', $allowedNodeIds));
    }

    /**
     * createRole
     *
     * Wrapper function for mockability
     */
    public function createRole($content)
    {
        return Role::create($content);
    }

    /**
     * findRolesInDatabase
     */
    protected function findRolesInDatabase(QueryParameters $queryParameters, array $allowedNodeIds)
    {
        if ($allowedNodeIds == []) {
            return [ 'roles' => [] ];
        }

        $includeCount = $queryParameters->isCount();
        $query = $this->getTableConnection();

        // Find nodes where at least one allowed node id is in a role's
        // whitelist and not in the same node's blaclist.
        $query->where(function ($queryParameter) use ($allowedNodeIds) {
            foreach ($allowedNodeIds as $nodeId) {
                $queryParameter->orWhere(function ($queryParam) use ($nodeId) {
                    $queryParam->where('whitelist_node_ids', 'LIKE', "%.{$nodeId}.%")
                    ->where('blacklist_node_ids', 'NOT LIKE', "%.{$nodeId}.%");
                });
            }
        });

        return $this->generateTableQuery($query)
            ->setQueryFields($queryParameters->getFields(), $includeCount)
            ->filter($queryParameters->getFilters())
            ->sortBy($queryParameters->getSortby())
            ->paginate($queryParameters->getOffset(), $queryParameters->getLimit())
            ->getQueryResults($includeCount, 'roles');
    }

    /**
     * removeRole
     */
    protected function removeRole($roleId)
    {
        // delete database record
        $this->getTableConnection()
            ->where(['id' => $roleId])
            ->limit(1)
            ->delete();
    }

    /**
     * update role
     * 
     * Note: slugs cannot be updated.
     *
     * @param array $content New roles content (already verified)
     */
    protected function updateRole($content)
    {
        // initialize
        $roleId = $content['id'];
        $roleSlug = (isset($content['slug'])) ? $content['slug'] : null;

        // Verify the record exists,
        // if it doesn't, this throws an exception
        $role = $this->findById($roleId);
        
        // create update list (everything given except the criteria)
        $update = $content;
        unset($update['id']);
        unset($update['slug']);

        // update database
        $query = $this->getTableConnection()
            ->where('id', $roleId)
            ->limit(1)
            ->update($update);

        // return the slug
        return $role->slug;
    }
}