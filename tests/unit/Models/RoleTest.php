<?php
namespace Tests\unit\Models;

use Tests\unit\UnitTestCase;
use \Exception;
use \Unified\Models\Role;
use \Unified\Models\AclRolePermissionFactory;
use \Unified\Http\Helpers\QueryParameters;

class RoleTest extends UnitTestCase
{
    /**
     * test: addRole returns successfully
     */
    public function testAddRoleReturnsSuccessfully()
    {
        // param
        $content = [
          'slug' => 'a_slug',
          'permissions' => [ 'a_permission', 'another_permission'],
          'some' => 'data'
        ];

        // mock
        $generatedRoleId = 123654;

        $generatedRole = new \stdClass();
        $generatedRole->id = $generatedRoleId;
        $generatedRole->slug = 'a_slug';

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
                ->disableOriginalConstructor()
                ->setMethods(array('tags', 'flush'))
                ->getMock();
        $cache->expects($this->once())
                ->method('tags')
                ->with($this->equalTo(Role::ROLES_CACHE_TAG))
                ->willReturn($cache);
        $cache->expects($this->once())
                ->method('flush');
        
        $aclRolePermissionFactory = $this->getMockBuilder('\Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['setPermissionsForRole'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->once())
            ->method('setPermissionsForRole')
            ->with(
                $this->equalTo('a_slug'),
                $this->equalTo(['a_permission', 'another_permission'])
            );
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);

        $role = $this->getMockBuilder('\Unified\Models\Role')
                ->disableOriginalConstructor()
                ->setMethods([
                    'beginTransaction', 'createRole', 'isCacheRedis',
                    'getCache', 'commitTransaction', 'rollbackTransaction',
                    'generateError'])
                ->getMock();
        $role->expects($this->once())
            ->method('beginTransaction');
        $role->expects($this->once())
                ->method('createRole')
                ->with($this->equalTo([
                    'slug' => 'a_slug',
                    'some' => 'data'
                ]))
                ->willReturn($generatedRole);
        $role->expects($this->any())
                ->method('isCacheRedis')
                ->willReturn(true);
        $role->expects($this->once())
                ->method('getCache')
                ->willReturn($cache);
        $role->expects($this->once())
                ->method('commitTransaction');
        $role->expects($this->never())
                ->method('rollbackTransaction');
        $role->expects($this->never())
                ->method('generateError');

        // run
        $results = $role->addRole($content);
        
        // post-run assertions
        $expectedResults = [
            'status' => 1,
            'roleId' => $generatedRoleId
        ];
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: addRole - returns rollback if failure occurs
     */
    public function testAddRoleReturnsRollsbackIfFailureOccurs()
    {
        // param
        $content = [
          'some' => 'data'
        ];

        // mock
        $e = new Exception('some_message');

        $fullErrorMessage = 'Unable to add role: some_message';

        $role = $this->getMockBuilder('\Unified\Models\Role')
                ->disableOriginalConstructor()
                ->setMethods([
                    'beginTransaction', 'createRole', 'isCacheRedis',
                    'getCache', 'commitTransaction', 'rollbackTransaction',
                    'generateError'])
                ->getMock();
        $role->expects($this->once())
            ->method('beginTransaction');
        $role->expects($this->once())
                ->method('createRole')
                ->will($this->throwException($e));
        $role->expects($this->any())
                ->method('isCacheRedis');
        $role->expects($this->never())
                ->method('getCache');
        $role->expects($this->never())
                ->method('commitTransaction');
        $role->expects($this->once())
                ->method('rollbackTransaction');
        $role->expects($this->once())
                ->method('generateError')
                ->with($this->equalTo($fullErrorMessage))
                ->willReturn(['status' => 0, 'error' => $fullErrorMessage]);

        // run
        $results = $role->addRole($content);

        // post-run assertions
        $expectedResults = [
            'status' => 0,
            'error' => $fullErrorMessage
        ];
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: deleteRole commits successful transactions
     */
    public function testDeleteRoleCommitsSuccessfulTransactions()
    {
        // param
        $roleId = 123654;
        $content = [
          'id' => $roleId
        ];

        // mock
        $roleSlug = 'a_role_slug';
        
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush', 'get', 'put'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with($this->equalTo(Role::ROLES_CACHE_TAG))
            ->willReturn($cache);
        $cache->expects($this->any())
            ->method('flush');
        
        $aclRolePermissionFactory = $this->getMockBuilder('\Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['setPermissionsForRole'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->once())
            ->method('setPermissionsForRole')
            ->with(
                $this->equalTo($roleSlug),
                $this->equalTo([])
            );
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
     
        $table = $this->getMockBuilder('\stdClass')
            ->disableOriginalConstructor()
            ->setMethods(['where', 'limit', 'delete', 'get'])
            ->getMock();
        $table->expects($this->any())
            ->method('where')
            ->with($this->equalTo(['id' => $roleId]))
            ->willReturn($table);
        $table->expects($this->any())
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($table);
        $table->expects($this->once())
            ->method('delete');
        
        $foundRole = (object) [
            'id' => $roleId,
            'slug' => $roleSlug
        ];

        $role = $this->getMockBuilder('\Unified\Models\Role')
                ->disableOriginalConstructor()
                ->setMethods([
                    'beginTransaction', 'getTableConnection', 'isCacheRedis',
                    'getCache', 'commitTransaction', 'rollbackTransaction',
                    'generateError', 'findById'])
                ->getMock();
        $role->expects($this->once())
            ->method('beginTransaction');
        $role->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($roleId))
            ->willReturn($foundRole);
        $role->expects($this->any())
                ->method('getTableConnection')
                ->willReturn($table);
        $role->expects($this->any())
                ->method('isCacheRedis')
                ->willReturn(true);
        $role->expects($this->any())
                ->method('getCache')
                ->willReturn($cache);
        $role->expects($this->once())
                ->method('commitTransaction');
        $role->expects($this->never())
                ->method('rollbackTransaction');
        $role->expects($this->never())
                ->method('generateError');

        // run
        $results = $role->deleteRole($content);

        // post-run assertions
        $expectedResults = [
            'status' => 1
        ];
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: deleteRole rolls back if record already does not exist
     */
    public function testDeleteRoleRollsBackIfRecordAlreadyDoesNotExist()
    {
        // param
        $roleId = 123654;
        $content = [
          'id' => $roleId
        ];

        // mock
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with($this->equalTo(Role::ROLES_CACHE_TAG))
            ->willReturn($cache);
        $cache->expects($this->any())
            ->method('flush');
        
       $exception = new \Exception('some_error');

        $role = $this->getMockBuilder('\Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods([
                'beginTransaction', 'getTableConnection', 'isCacheRedis',
                'getCache', 'commitTransaction', 'rollbackTransaction',
                'generateError', 'findById'])
            ->getMock();
        $role->expects($this->once())
            ->method('beginTransaction');
        $role->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $role->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        $role->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($roleId))
            ->willThrowException($exception);
        $role->expects($this->never())
            ->method('commitTransaction');
        $role->expects($this->once())
            ->method('rollbackTransaction');
        $role->expects($this->once())
            ->method('generateError')
            ->with($this->equalTo('Unable to remove role: some_error'))
            ->willReturn(['error' => 'Unable to remove role: some_error', 'status' => 0]);

        // run
        $results = $role->deleteRole($content);

        // post-run assertions
        $expectedResults = [
            'error' => 'Unable to remove role: some_error',
            'status' => 0
        ];
        $this->assertEquals($expectedResults, $results);
    }
    
    //=============================================================
    //
    // FindById
    //
    
    /**
     * test: findById successfully returns role if found by cache
     */
    public function testFindByIdSuccessfullyReturnsRoleIfFoundByCache()
    {
        // param
        $roleId = '123123123';
        
        // mock
        $foundRecord = 'a_record';
        
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush', 'get', 'put'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with($this->equalTo(Role::ROLES_CACHE_TAG))
            ->willReturn($cache);
        $cache->expects($this->any())
            ->method('flush');
        $cache->expects($this->any())
            ->method('get')
            ->with(
                $this->equalTo(Role::ROLE_BY_ID_PREFIX . $roleId)
            )
            ->willReturn($foundRecord);
        $cache->expects($this->never())
            ->method('put');
        
        $factory = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        
        // run
        $results = $factory->findById($roleId);
        
        // post-run assertion
        $this->assertEquals($foundRecord, $results);
    }
    
    /**
     * test: findById successfully returns role from database if not found by cache
     */
    public function testFindByIdSuccessfullyReturnsRoleFromDatabaseIfNotFoundByCache()
    {
        // param
        $roleId = '123123123';
        
        // mock
        $roleSlug = 'a_role_slug';
        
        $foundRecord = (object) [
            'id' => $roleId,
            'slug' => $roleSlug,
            'some' => 'data'
        ];
        
        $foundPermissions = ['a_permission', 'b_permission'];

        $aclRolePermissionFactory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['findByRoleSlug'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->once())
            ->method('findByRoleSlug')
            ->with($this->equalTo($roleSlug))
            ->willReturn($foundPermissions);
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
        
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush', 'get', 'put'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with($this->equalTo(Role::ROLES_CACHE_TAG))
            ->willReturn($cache);
        $cache->expects($this->any())
            ->method('flush');
        $cache->expects($this->any())
            ->method('get')
            ->with(
                $this->equalTo(Role::ROLE_BY_ID_PREFIX . $roleId)
            )
            ->willReturn(null);
        $cache->expects($this->once())
            ->method('put');

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'get'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('id'),
                $this->equalTo($roleId)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('get')
            ->willReturn([$foundRecord]);

        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->any())
            ->method('table')
            ->with($this->equalTo('acl_roles'))
            ->willReturn($query);
        
        $factory = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->any())
            ->method('getDb')
            ->willReturn($database);
        
        // run
        $results = $factory->findById($roleId);
        
        // post-run assertion
        $expectedResults = (object) [
            'id' => $roleId,
            'slug' => $roleSlug,
            'some' => 'data',
            'permissions' => $foundPermissions
        ];
        $this->assertEquals($foundRecord, $results);
    }
    
    /**
     * test: findById throws an exception if both the cache and database cannot find the role
     * 
     * @expectedException Unified\Models\AppException
     * @expectedExceptionMessage role_id_not_found
     */
    public function testFindByIdThrowsAnExceptionIfBothTheCacheAndDatabaseCannotFindTheRole()
    {
        // param
        $roleId = '123123123';
        
        // mock
        $roleSlug = 'a_role_slug';
        
        $aclRolePermissionFactory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['findByRoleSlug'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->never())
            ->method('findByRoleSlug');
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
        
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush', 'get', 'put'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with($this->equalTo(Role::ROLES_CACHE_TAG))
            ->willReturn($cache);
        $cache->expects($this->any())
            ->method('flush');
        $cache->expects($this->any())
            ->method('get')
            ->with(
                $this->equalTo(Role::ROLE_BY_ID_PREFIX . $roleId)
            )
            ->willReturn(null);
        $cache->expects($this->never())
            ->method('put');

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'get'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('id'),
                $this->equalTo($roleId)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('get')
            ->willReturn([]);

        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->any())
            ->method('table')
            ->with($this->equalTo('acl_roles'))
            ->willReturn($query);
        
        $factory = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->any())
            ->method('getDb')
            ->willReturn($database);
        
        // run
        $results = $factory->findById($roleId);
        
        // post-run assertion
        $expectedResults = (object) [
            'id' => $roleId,
            'slug' => $roleSlug,
            'some' => 'data',
            'permissions' => $foundPermissions
        ];
        $this->assertEquals($foundRecord, $results);
    }
    
    //=============================================================
    //
    // Get Roles
    //

    /**
     * test: getRoles - returns roles from cache
     */
    public function testGetRolesReturnsRolesFromCache()
    {
        // params
        $fields = [ 'something' => 'blah'];
        $filters = array();
        $control = array(
            'some' => 'data',
            'other' => 'stuff'
        );
        $sortBy = array();

        $config = new QueryParameters($fields, $filters, $control, $sortBy);
        $allowedNodeIds = [3,4,5,7];

        // mocks
        $cachedResults = 'some_results';

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
                ->disableOriginalConstructor()
                ->setMethods(array('tags', 'get', 'put'))
                ->getMock();
        $cache->expects($this->any())
                ->method('tags')
                ->with($this->equalTo(Role::ROLES_CACHE_TAG))
                ->willReturn($cache);
        $cache->expects($this->any())
                ->method('get')
                ->with($this->equalTo(Role::ROLES_CACHE_PREFIX . ':{"fields":{"something":"blah"},"filters":[],"control":{"other":"stuff","some":"data"},"sortby":[]}:nodes:3.4.5.7'))
                ->willReturn($cachedResults);
        $cache->expects($this->never())
                ->method('put');

        $role = $this->getMockBuilder('\Unified\Models\Role')
                ->disableOriginalConstructor()
                ->setMethods(array('getCache', 'generateQuery', 'isCacheRedis'))
                ->getMock();
        $role->expects($this->any())
                ->method('isCacheRedis')
                ->willReturn(true);
        $role->expects($this->once())
                ->method('getCache')
                ->willReturn($cache);
        $role->expects($this->never())
                ->method('generateQuery');

        // run
        $results = $role->getRoles($config, $allowedNodeIds);

        // post-run assertions
        $this->assertEquals($cachedResults, $results);
        
    }

    /**
     * test: getRoles - returns roles from database if cache does not have it
     */
    public function testGetRolesReturnRolesFromDatabaseIfCacheDoesNotHaveIt()
    {
        // params
        $fields = array(
            'something' => 'blah'
        );
        $filters = array();
        $control = array(
            'some' => 'data',
            'other' => 'stuff'
        );
        $sortBy = array();

        $config = new QueryParameters($fields, $filters, $control, $sortBy);
        $allowedNodeIds = [3,4,5,7];

        $count = $config->isCount();
        $configFields = $config->getFields();
        $configFilters = $config->getFilters();
        $configOffset = $config->getOffset();
        $configLimit = $config->getLimit();

        // mocks
        $cachedResults = null;
        $dbResults = [
            'roles' => [
                (object) [
                    'id' => 1,
                    'slug' => 'slug_for_1'
                ]
            ]
        ];

        $roleSlugs = ['slug_for_1'];
        $permissions = ['a_permission', 'b_permission'];

        $resultsToCache = [
            'roles' => [
                (object) [
                    'id' => 1,
                    'slug' => 'slug_for_1',
                    'permissions' => $permissions
                ]
            ]
        ];
        
        $tableQuery = $this->getMockBuilder('\stdClass')
            ->disableOriginalConstructor()
            ->setMethods(['where'])
            ->getMock();
        $tableQuery->expects($this->once())
            ->method('where')
            ->willReturn($tableQuery);

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
                ->disableOriginalConstructor()
                ->setMethods(array('tags', 'get', 'put'))
                ->getMock();
        $cache->expects($this->any())
                ->method('tags')
                ->with($this->equalTo(Role::ROLES_CACHE_TAG))
                ->willReturn($cache);
        $cache->expects($this->any())
                ->method('get')
                ->with($this->equalTo(Role::ROLES_CACHE_PREFIX . ':{"fields":{"something":"blah"},"filters":[],"control":{"other":"stuff","some":"data"},"sortby":[]}:nodes:3.4.5.7'))
                ->willReturn($cachedResults);
        $cache->expects($this->once())
                ->method('put')
                ->with(
                    $this->equalTo(Role::ROLES_CACHE_PREFIX . ':{"fields":{"something":"blah"},"filters":[],"control":{"other":"stuff","some":"data"},"sortby":[]}:nodes:3.4.5.7'),
                    $this->equalTo($resultsToCache),
                    $this->equalTo(Role::CACHE_TTL)
                );
        
        $rolePermissions = [
          'slug_for_1' => ['a_permission', 'b_permission']
        ];

        $aclRolePermissionFactory = $this->getMockBuilder('\Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['findByRoleSlugs'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->once())
            ->method('findByRoleSlugs')
            ->with(
                $this->equalTo($roleSlugs)
            )
            ->willReturn($rolePermissions);
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
                 
        $query = $this->getMockBuilder('\Unified\Models\Query')
                ->disableOriginalConstructor()
                ->setMethods(array(
                    'setQueryFields',
                    'filter',
                    'sortBy',
                    'paginate',
                    'getQueryResults'
                ))
                ->getMock();
        $query->expects($this->once())
                ->method('setQueryFields')
                ->with(
                        $this->equalTo($configFields),
                        $this->equalTo($count)
                )
                ->willReturn($query);
        $query->expects($this->once())
                ->method('filter')
                ->with(
                        $this->equalTo($configFilters)
                )
                ->willReturn($query);
        $query->expects($this->once())
                ->method('sortBy')
                ->with(
                        $this->equalTo($sortBy)
                )
                ->willReturn($query);
        $query->expects($this->once())
                ->method('paginate')
                ->with(
                        $this->equalTo($configOffset),
                        $this->equalTo($configLimit)
                )
                ->willReturn($query);
        $query->expects($this->once())
                ->method('getQueryResults')
                ->with(
                        $this->equalTo($count),
                        $this->equalTo('roles')
                )
                ->willReturn($dbResults);

        $role = $this->getMockBuilder('\Unified\Models\Role')
                ->disableOriginalConstructor()
                ->setMethods(array('getCache', 'getTableConnection', 'generateTableQuery', 'isCacheRedis'))
                ->getMock();
        $role->expects($this->once())
                ->method('getCache')
                ->willReturn($cache);
        $role->expects($this->once())
                ->method('getTableConnection')
                ->willReturn($tableQuery);
        $role->expects($this->once())
                ->method('generateTableQuery')
                ->willReturn($query);
        $role->expects($this->any())
                ->method('isCacheRedis')
                ->willReturn(true);
        
        // run
        $results = $role->getRoles($config, $allowedNodeIds);

        // post-run assertions
        $this->assertEquals($dbResults, $results);
    }

    /**
     * test: getRoles - returns roles from database if cache is not redis
     */
    public function testGetRolesReturnRolesFromDatabaseIfCacheIsNotRedis()
    {
        // params
        $fields = array(
            'something' => 'blah'
        );
        $filters = array();
        $control = array(
            'some' => 'data',
            'other' => 'stuff'
        );
        $sortBy = array();

        $config = new QueryParameters($fields, $filters, $control, $sortBy);
        $allowedNodeIds = [3,4,5,7];

        $count = $config->isCount();
        $configFields = $config->getFields();
        $configFilters = $config->getFilters();
        $configOffset = $config->getOffset();
        $configLimit = $config->getLimit();

        // mocks
        $cachedResults = null;
        $dbResults = [
            'roles' => [
                (object) [
                    'id' => 1,
                    'slug' => 'slug_for_1'
                ]
            ]
        ];

        $roleSlugs = ['slug_for_1'];
        $permissions = ['a_permission', 'b_permission'];
        
        $resultsToCache = [
            'roles' => [
                (object) [
                    'id' => 1,
                    'slug' => 'slug_for_1',
                    'permissions' => $permissions
                ]
            ]
        ];
        
        $tableQuery = $this->getMockBuilder('\stdClass')
            ->disableOriginalConstructor()
            ->setMethods(['where'])
            ->getMock();
        $tableQuery->expects($this->once())
            ->method('where')
            ->willReturn($tableQuery);

        $rolePermissions = [
          'slug_for_1' => ['a_permission', 'b_permission']
        ];

        $aclRolePermissionFactory = $this->getMockBuilder('\Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['findByRoleSlugs'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->once())
            ->method('findByRoleSlugs')
            ->with(
                $this->equalTo($roleSlugs)
            )
            ->willReturn($rolePermissions);
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
                 
        $query = $this->getMockBuilder('\Unified\Models\Query')
                ->disableOriginalConstructor()
                ->setMethods(array(
                    'setQueryFields',
                    'filter',
                    'sortBy',
                    'paginate',
                    'getQueryResults'
                ))
                ->getMock();
        $query->expects($this->once())
                ->method('setQueryFields')
                ->with(
                        $this->equalTo($configFields),
                        $this->equalTo($count)
                )
                ->willReturn($query);
        $query->expects($this->once())
                ->method('filter')
                ->with(
                        $this->equalTo($configFilters)
                )
                ->willReturn($query);
        $query->expects($this->once())
                ->method('sortBy')
                ->with(
                        $this->equalTo($sortBy)
                )
                ->willReturn($query);
        $query->expects($this->once())
                ->method('paginate')
                ->with(
                        $this->equalTo($configOffset),
                        $this->equalTo($configLimit)
                )
                ->willReturn($query);
        $query->expects($this->once())
                ->method('getQueryResults')
                ->with(
                        $this->equalTo($count),
                        $this->equalTo('roles')
                )
                ->willReturn($dbResults);

        $role = $this->getMockBuilder('\Unified\Models\Role')
                ->disableOriginalConstructor()
                ->setMethods(array('getCache', 'getTableConnection', 'generateTableQuery', 'isCacheRedis'))
                ->getMock();
        $role->expects($this->never())
                ->method('getCache');
        $role->expects($this->once())
                ->method('getTableConnection')
                ->willReturn($tableQuery);
        $role->expects($this->once())
                ->method('generateTableQuery')
                ->willReturn($query);
        $role->expects($this->any())
                ->method('isCacheRedis')
                ->willReturn(false);
        
        // run
        $results = $role->getRoles($config, $allowedNodeIds);

        // post-run assertions
        $this->assertEquals($dbResults, $results);
    }

    /**
     * test: modifyRole commits successful transactions
     */
    public function testModifyRoleCommitsSuccessfulTransactions()
    {
        // param
        $roleId = 123654;
        $roleSlug = 'a_role_slug';
        $permissions = ['a_slug', 'b_slug'];
        $content = [
          'id' => $roleId,
          'some' => 'data',
          'other' => 'data',
          'permissions' => $permissions
        ];

        // mock
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with($this->equalTo(Role::ROLES_CACHE_TAG))
            ->willReturn($cache);
        $cache->expects($this->any())
            ->method('flush');

        $aclRolePermissionFactory = $this->getMockBuilder('\Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['setPermissionsForRole'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->once())
            ->method('setPermissionsForRole')
            ->with(
                $this->equalTo($roleSlug),
                $this->equalTo($permissions)
            );
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);

        $foundRole = (object) [
            'id' => $roleId,
            'slug' => $roleSlug
        ];

        $query = $this->getMockBuilder('\Unified\Models\Query')
                ->disableOriginalConstructor()
                ->setMethods(['where', 'limit', 'update'])
                ->getMock();
        $query->expects($this->at(0))
                ->method('where')
                ->with(
                        $this->equalTo('id'),
                        $this->equalTo($roleId)
                )
                ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('update')
            ->with($this->equalTo([
                  'some' => 'data',
                  'other' => 'data',
            ]));
        
        $role = $this->getMockBuilder('\Unified\Models\Role')
                ->disableOriginalConstructor()
                ->setMethods([
                    'beginTransaction', 'findById', 'isCacheRedis',
                    'getCache', 'commitTransaction', 'rollbackTransaction',
                    'generateError', 'getTableConnection'])
                ->getMock();
        $role->expects($this->once())
            ->method('beginTransaction');
        $role->expects($this->any())
                ->method('findById')
                ->willReturn($foundRole);
        $role->expects($this->any())
                ->method('isCacheRedis')
                ->willReturn(true);
        $role->expects($this->any())
                ->method('getCache')
                ->willReturn($cache);
        $role->expects($this->once())
                ->method('commitTransaction');
        $role->expects($this->never())
                ->method('rollbackTransaction');
        $role->expects($this->never())
                ->method('generateError');
        $role->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($roleId))
            ->willReturn($foundRole);
        $role->expects($this->once())
            ->method('getTableConnection')
            ->willReturn($query);

        // run
        $results = $role->modifyRole($content);

        // post-run assertions
        $expectedResults = [
            'status' => 1
        ];
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: modifyRole rolls back if no role is found to be updated
     */
    public function testModifyRolesRollsBackIfNoRoleIsFoundToBeUpdated()
    {
        // param
        $roleId = 123654;
        $content = [
          'id' => $roleId,
          'some' => 'data',
          'other' => 'data'
        ];

        // mock
        $errorStructure = [
            'status' => 0,
            'error' => 'Unable to modify role: Role of Id ' . $roleId . ' does not exist.'
        ];

        $exception = new Exception('some_error');
        
        $role = $this->getMockBuilder('\Unified\Models\Role')
                ->disableOriginalConstructor()
                ->setMethods([
                    'beginTransaction', 'getTableConnection', 'isCacheRedis',
                    'getCache', 'commitTransaction', 'rollbackTransaction',
                    'generateError', 'findById'])
                ->getMock();
        $role->expects($this->once())
            ->method('beginTransaction');
        $role->expects($this->any())
                ->method('isCacheRedis')
                ->willReturn(false);
        $role->expects($this->never())
                ->method('getCache');
        $role->expects($this->never())
                ->method('commitTransaction');
        $role->expects($this->once())
                ->method('rollbackTransaction');
        $role->expects($this->once())
                ->method('generateError')
                ->willReturn($errorStructure);
        $role->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($roleId))
            ->willThrowException($exception);

        // run
        $results = $role->modifyRole($content);

        // post-run assertions
        $this->assertEquals($errorStructure, $results);
    }
}