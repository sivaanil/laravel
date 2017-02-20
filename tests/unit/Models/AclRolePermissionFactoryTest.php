<?php
namespace Tests\unit\Models;

use Tests\unit\UnitTestCase;
use Unified\Models\AclRolePermissionFactory;

class AclRolePermissionFactoryTest extends UnitTestCase
{
    //==================================================================
    //
    // test: findByRoleSlug
    //

    /**
     * test: findByRoleSlug returns permissions from the cache
     */
    public function testFindByRoleSlugReturnsPermissionsFromTheCache()
    {
        // param
        $roleSlug = 'some_role_slug';
        
        // mock
        $permissionSlugs = [ 'one_slug', 'two_slug', 'green_slug', 'blue_slug'];
        
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'put'])
            ->getMock();
        $cache->expects($this->once())
            ->method('get')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . $roleSlug))
            ->willReturn($permissionSlugs);
        $cache->expects($this->never())
            ->method('put');
        
        $factory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->never())
            ->method('getDb');
        
        // run
        $results = $factory->findByRoleSlug($roleSlug);
    
        // post-run assertions
        $this->assertEquals($permissionSlugs, $results);
    }

    /**
     * test: findByRoleSlug returns permissions from the database when cache does not have it
     */
    public function testFindByRoleSlugReturnsPermissionsFromTheDatabaseWhenCacheDoesNotHaveIt()
    {
        // param
        $roleSlug = 'some_role_slug';
        
        // mock
        $foundRecords = [
          (object) [ 'role_slug' => $roleSlug, 'permission_slug' => 'one_slug'],
          (object) [ 'role_slug' => $roleSlug, 'permission_slug' => 'two_slug'],
          (object) [ 'role_slug' => $roleSlug, 'permission_slug' => 'green_slug'],
          (object) [ 'role_slug' => $roleSlug, 'permission_slug' => 'blue_slug']  
        ];
        
        $permissionSlugs = [ 'one_slug', 'two_slug', 'green_slug', 'blue_slug'];

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'put'])
            ->getMock();
        $cache->expects($this->once())
            ->method('get')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . $roleSlug))
            ->willReturn(null);
        $cache->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . $roleSlug),
                $this->equalTo($permissionSlugs),
                $this->equalTo(AclRolePermissionFactory::CACHE_TTL)
            );
        
        $tableQuery = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
            ->disableOriginalConstructor()
            ->setMethods(['where', 'get'])
            ->getMock();
        $tableQuery->expects($this->once())
            ->method('where')
            ->with(
                $this->equalTo('role_slug'),
                $this->equalTo($roleSlug)
            )
            ->willReturn($tableQuery);
        $tableQuery->expects($this->once())
            ->method('get')
            ->willReturn($foundRecords);
        
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->once())
            ->method('table')
            ->with($this->equalTo('acl_role_permission'))
            ->willReturn($tableQuery);
        
        $factory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);
        
        // run
        $results = $factory->findByRoleSlug($roleSlug);
    
        // post-run assertions
        $this->assertEquals($permissionSlugs, $results);
    }

    //==================================================================
    //
    // test: findByRoleSlugs
    //

    /**
     * test: find by role slugs retrieves from both cache and database
     */
    public function testFindByRoleSlugsRetrievesFromBothCacheAndDatabase()
    {
        // param
        $roleSlugs = [ 'one_slug', 'two_slug', 'green_slug', 'blue_slug' ];
        
        // mock
        $cache = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'put'])
            ->getMock();
        $cache->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'one_slug'))
            ->willReturn([ 'a_slug', 'b_slug' ]);
        $cache->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'two_slug'))
            ->willReturn(null);
        $cache->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'green_slug'))
            ->willReturn(null);
        $cache->expects($this->at(3))
            ->method('get')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'blue_slug'))
            ->willReturn(null);
        $cache->expects($this->at(4))
            ->method('put')
            ->with(
                $this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'blue_slug'),
                $this->equalTo(['d_slug']),
                $this->equalTo(AclRolePermissionFactory::CACHE_TTL)
             )
            ->willReturn(null);
        $cache->expects($this->at(5))
            ->method('put')
            ->with(
                $this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'two_slug'),
                $this->equalTo(['c_slug']),
                $this->equalTo(AclRolePermissionFactory::CACHE_TTL)
             )
            ->willReturn(null);
        $cache->expects($this->at(6))
            ->method('put')
            ->with(
                $this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'green_slug'),
                $this->equalTo([]),
                $this->equalTo(AclRolePermissionFactory::CACHE_TTL)
             )
            ->willReturn(null);
        
        $foundPermissionRecords = [
            (object) [
                'role_slug' => 'blue_slug',
                'permission_slug' => 'd_slug'
            ],
            (object) [
                'role_slug' => 'two_slug',
                'permission_slug' => 'c_slug'
            ]  
        ];
        
        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
            ->disableOriginalConstructor()
            ->setMethods(['whereIn', 'get'])
            ->getMock();
        $query->expects($this->once())
            ->method('whereIn')
            ->with(
                $this->equalTo('role_slug'),
                $this->equalTo(['two_slug', 'green_slug', 'blue_slug'])
             )
             ->willReturn($query);
        $query->expects($this->once())
            ->method('get')
            ->willReturn($foundPermissionRecords);
        
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->once())
            ->method('table')
            ->with($this->equalTo('acl_role_permission'))
            ->willReturn($query);
        
        $factory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
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
        $results = $factory->findByRoleSlugs($roleSlugs);
        
        // post-run assertions
        $expectedResults = [
            'one_slug' => [ 'a_slug', 'b_slug' ],
            'two_slug' => [ 'c_slug' ],
            'green_slug' => [],
            'blue_slug' => [ 'd_slug' ]
        ];
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: find by role slugs retrieves from database if cache is not available
     */
    public function testFindByRoleSlugsRetrievesFromDatabaseIfCacheIsNotAvailable()
    {
        // param
        $roleSlugs = [ 'one_slug', 'two_slug', 'green_slug', 'blue_slug' ];
        
        // mock
        $foundPermissionRecords = [
            (object) [
                'role_slug' => 'one_slug',
                'permission_slug' => 'a_slug'
            ],
            (object) [
                'role_slug' => 'one_slug',
                'permission_slug' => 'b_slug'
            ],
            (object) [
                'role_slug' => 'two_slug',
                'permission_slug' => 'c_slug'
            ],
            (object) [
                'role_slug' => 'blue_slug',
                'permission_slug' => 'd_slug'
            ]
        ];
        
        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
            ->disableOriginalConstructor()
            ->setMethods(['whereIn', 'get'])
            ->getMock();
        $query->expects($this->once())
            ->method('whereIn')
            ->with(
                $this->equalTo('role_slug'),
                $this->equalTo(['one_slug', 'two_slug', 'green_slug', 'blue_slug'])
             )
             ->willReturn($query);
        $query->expects($this->once())
            ->method('get')
            ->willReturn($foundPermissionRecords);
        
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->once())
            ->method('table')
            ->with($this->equalTo('acl_role_permission'))
            ->willReturn($query);
        
        $factory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(false);
        $factory->expects($this->any())
            ->method('getDb')
            ->willReturn($database);
        
        // run
        $results = $factory->findByRoleSlugs($roleSlugs);
        
        // post-run assertions
        $expectedResults = [
            'one_slug' => [ 'a_slug', 'b_slug' ],
            'two_slug' => [ 'c_slug' ],
            'green_slug' => [],
            'blue_slug' => [ 'd_slug' ]
        ];
        $this->assertEquals($expectedResults, $results);
    }

    //==================================================================
    //
    // test: setPermissions
    //

    /**
     * test: set permissions for role adds and deletes changes to role's permission slug list
     */
    public function testSetPermissionsForRoleAddsAndDeletesChangesToRolesPermissionSlugList()
    {
        // param
        $roleSlug = 'a_role_slug';
        $permissionSlugs = [ 'a_permission_slug', 'b_permission_slug', 'c_permission_slug' ];
    
        // mock
        $currentPermissionSlugs = [ 'c_permission_slug', 'd_permission_slug', 'e_permission_slug'];
        
        $expectedInsertRecords = [
          ['role_slug' => $roleSlug, 'permission_slug' => 'a_permission_slug'],
          ['role_slug' => $roleSlug, 'permission_slug' => 'b_permission_slug']  
        ];
        
        $addQuery = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
            ->disableOriginalConstructor()
            ->setMethods(['insert'])
            ->getMock();
        $addQuery->expects($this->once())
            ->method('insert')
            ->with($this->equalTo($expectedInsertRecords));
        
        $removeQuery = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
            ->disableOriginalConstructor()
            ->setMethods(['where', 'whereIn', 'delete'])
            ->getMock();
        $removeQuery->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('role_slug'),
                $this->equalTo($roleSlug)    
            )
            ->willReturn($removeQuery);
        $removeQuery->expects($this->at(1))
            ->method('whereIn')
            ->with(
                $this->equalTo('permission_slug'),
                $this->equalTo(['d_permission_slug', 'e_permission_slug'])    
            )
            ->willReturn($removeQuery);
        $removeQuery->expects($this->at(2))
            ->method('delete');

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['put'])
            ->getMock();
        $cache->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . $roleSlug),
                $this->equalTo($permissionSlugs),
                $this->equalTo(AclRolePermissionFactory::CACHE_TTL)
            );
        
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->at(0))
            ->method('table')
            ->with($this->equalTo('acl_role_permission'))
            ->willReturn($addQuery);
        $database->expects($this->at(1))
            ->method('table')
            ->with($this->equalTo('acl_role_permission'))
            ->willReturn($removeQuery);
        
        $factory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb', 'findByRoleSlug'])
            ->getMock();
        $factory->expects($this->once())
            ->method('findByRoleSlug')
            ->with($this->equalTo($roleSlug))
            ->willReturn($currentPermissionSlugs);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        
        // run
        $factory->setPermissionsForRole($roleSlug, $permissionSlugs);   
    }

    //==================================================================
    //
    // test: findByPermissionSlug
    //

    /**
     * test: findByPermissionSlug returns role slugs from cache
     */
    public function testFindByPermissionSlugReturnsRoleSlugsFromCache()
    {
        // param
        $permissionSlug = 'some_permission_slug';

        // mock
        $roleSlugs = [ 'one_slug', 'two_slug', 'green_slug', 'blue_slug'];

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'put'])
            ->getMock();
        $cache->expects($this->once())
            ->method('get')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_PERMISSION_PREFIX . $permissionSlug))
            ->willReturn($roleSlugs);
        $cache->expects($this->never())
            ->method('put');

        $factory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->never())
            ->method('getDb');

        // run
        $results = $factory->findByPermissionSlug($permissionSlug);

        // post-run assertions
        $this->assertEquals($roleSlugs, $results);
    }

    /**
     * test: findByPermissionSlug returns role slugs from database when cache does not have it
     */
    public function testFindByPermissionSlugReturnsRoleSlugsFromDatabaseWhenCacheDoesNotHaveIt()
    {
        // param
        $permissionSlug = 'some_permission_slug';
        
        // mock
        $foundRecords = [
          (object) [ 'permission_slug' => $permissionSlug, 'role_slug' => 'one_slug'],
          (object) [ 'permission_slug' => $permissionSlug, 'role_slug' => 'two_slug'],
          (object) [ 'permission_slug' => $permissionSlug, 'role_slug' => 'green_slug'],
          (object) [ 'permission_slug' => $permissionSlug, 'role_slug' => 'blue_slug']  
        ];
        
        $roleSlugs = [ 'one_slug', 'two_slug', 'green_slug', 'blue_slug'];

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'put'])
            ->getMock();
        $cache->expects($this->once())
            ->method('get')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_PERMISSION_PREFIX . $permissionSlug))
            ->willReturn(null);
        $cache->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo(AclRolePermissionFactory::CACHE_PERMISSION_PREFIX . $permissionSlug),
                $this->equalTo($roleSlugs),
                $this->equalTo(AclRolePermissionFactory::CACHE_TTL)
            );
        
        $tableQuery = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
            ->disableOriginalConstructor()
            ->setMethods(['where', 'get'])
            ->getMock();
        $tableQuery->expects($this->once())
            ->method('where')
            ->with(
                $this->equalTo('permission_slug'),
                $this->equalTo($permissionSlug)
            )
            ->willReturn($tableQuery);
        $tableQuery->expects($this->once())
            ->method('get')
            ->willReturn($foundRecords);
        
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->once())
            ->method('table')
            ->with($this->equalTo('acl_role_permission'))
            ->willReturn($tableQuery);
        
        $factory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->any())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);
        
        // run
        $results = $factory->findByPermissionSlug($permissionSlug);
    
        // post-run assertions
        $this->assertEquals($roleSlugs, $results);
    }

    //=====================================================================
    //
    // test: remove permission slug
    //
    
    /**
     * test: removePermissionSlug
     */
    public function testRemovePermissionSlug()
    {
        // param
        $permissionSlug = 'a_permission_slug';

        // mock
        $roleSlugs = [ 'one_slug', 'two_slug', 'green_slug', 'blue_slug'];

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['forget'])
            ->getMock();
        $cache->expects($this->at(0))
            ->method('forget')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_PERMISSION_PREFIX . $permissionSlug));
        $cache->expects($this->at(1))
            ->method('forget')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'one_slug'));
        $cache->expects($this->at(2))
            ->method('forget')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'two_slug'));
        $cache->expects($this->at(3))
            ->method('forget')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'green_slug'));
        $cache->expects($this->at(4))
            ->method('forget')
            ->with($this->equalTo(AclRolePermissionFactory::CACHE_ROLE_PREFIX . 'blue_slug'));

        $tableQuery = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
            ->disableOriginalConstructor()
            ->setMethods(['where', 'delete'])
            ->getMock();
        $tableQuery->expects($this->once())
            ->method('where')
            ->with(
                $this->equalTo('permission_slug'),
                $this->equalTo($permissionSlug)
            )
            ->willReturn($tableQuery);
        $tableQuery->expects($this->once())
            ->method('delete');

        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->once())
            ->method('table')
            ->with($this->equalTo('acl_role_permission'))
            ->willReturn($tableQuery);

        $factory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getDb', 'getCache', 'findByPermissionSlug'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->once())
            ->method('findByPermissionSlug')
            ->with($this->equalTo($permissionSlug))
            ->willReturn($roleSlugs);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);
        $factory->expects($this->once())
            ->method('getCache')
            ->willReturn($cache);

        // run
        $factory->removePermissionSlug($permissionSlug);
    }
}