<?php
namespace Tests\unit\Models;

use Tests\unit\UnitTestCase;
use Unified\Models\Permission;
use Unified\Models\PermissionFactory;
use Unified\Models\AclRolePermissionFactory;
use \Exception;

class PermissionFactoryTest extends UnitTestCase
{
    //======================================================
    //
    // test: getAllSince
    //
    
    /**
     * test: getAllSince returns data from cache
     */
    public function testGetAllSinceReturnsDataFromCacheIfAvailable()
    {
        // param
        $since = 1500000000;
        
        // mock
        $keyedRecords = [
            'a_slug' => ['a_key' => 'a_value']  
        ];
        
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'get', 'put'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with(PermissionFactory::ALL_CACHE_TAG)
            ->willReturn($cache);
        $cache->expects($this->once())
            ->method('get')
            ->with($this->equalTo(PermissionFactory::CACHE_PREFIX . $since))
            ->willReturn($keyedRecords);
        $cache->expects($this->never())
            ->method('put');
        
        $factory = $this->getMockBuilder('\Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb', 'generatePermission'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->once())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->never())
            ->method('getDb');
        $factory->expects($this->never())
            ->method('generatePermission');
        
        // run
        $results = $factory->getAllSince($since);
        
        // post-run assertions
        $this->assertEquals($keyedRecords, $results);
    }
    
    /**
     * test: getAllSince returns data from the database if the cache does not have it
     */
    public function testGetAllSinceReturnsDataFromTheDatabaseIfTheCacheDoesNotHaveIt()
    {
        // param
        $since = 1500000000;
        
        // mock
        $firstRecord = (object) [
            'data' => 'value',
            'slug' => 'a_slug'
        ];
        
        $queryResults = [
            $firstRecord
        ];
        
        $keyedRecords = [
            'a_slug' => [
                'output' => 'data'
            ]  
        ];
        
        $generatedPermission = $this->getMockBuilder('Unified\Models\Permission')
            ->disableOriginalConstructor()
            ->setMethods(['toArray'])
            ->getMock();
        $generatedPermission->expects($this->any())
            ->method('toArray')
            ->willReturn(['output' => 'data']);

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'get', 'put'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with(PermissionFactory::ALL_CACHE_TAG)
            ->willReturn($cache);
        $cache->expects($this->once())
            ->method('get')
            ->with($this->equalTo(PermissionFactory::CACHE_PREFIX . $since))
            ->willReturn(null);
        $cache->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo(PermissionFactory::CACHE_PREFIX . $since),
                $this->equalTo($keyedRecords),
                $this->equalTo(PermissionFactory::VERY_SHORT_CACHE_TTL)
            );

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'get'])
        	->getMock();
        $query->expects($this->at(0))
        	->method('where')
        	->with(
        	    $this->equalTo('updated_at'),
        	    $this->equalTo('>='),
        	    $this->equalTo('2017-07-13 22:40:00')
        	)
        	->willReturn($query);
        $query->expects($this->at(1))
        	->method('get')
        	->willReturn($queryResults);
        
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->once())
        	->method('table')
        	->with($this->equalTo('acl_permissions'))
        	->willReturn($query);
        
        $factory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb', 'generatePermission'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->once())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);
        $factory->expects($this->once())
            ->method('generatePermission')
            ->with($this->equalTo($firstRecord))
            ->willReturn($generatedPermission);
        
        // run
        $results = $factory->getAllSince($since);
        
        // post-run assertions
        $this->assertEquals($keyedRecords, $results);
    }
    
    /**
     * test: getAllSince returns non-since data from the database if the cache does not have it
     */
    public function testGetAllSinceReturnsNonSinceDataFromTheDatabaseIfTheCacheDoesNotHaveIt()
    {
        // mock
        $firstRecord = (object) [
            'data' => 'value',
            'slug' => 'a_slug'
        ];
        
        $queryResults = [
            $firstRecord
        ];
        
        $keyedRecords = [
            'a_slug' => [
                'output' => 'data'
            ]  
        ];
        
        $generatedPermission = $this->getMockBuilder('Unified\Models\Permission')
            ->disableOriginalConstructor()
            ->setMethods(['toArray'])
            ->getMock();
        $generatedPermission->expects($this->any())
            ->method('toArray')
            ->willReturn(['output' => 'data']);

        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'get', 'put'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with(PermissionFactory::ALL_CACHE_TAG)
            ->willReturn($cache);
        $cache->expects($this->once())
            ->method('get')
            ->with($this->equalTo(PermissionFactory::CACHE_PREFIX))
            ->willReturn(null);
        $cache->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo(PermissionFactory::CACHE_PREFIX),
                $this->equalTo($keyedRecords),
                $this->equalTo(PermissionFactory::VERY_SHORT_CACHE_TTL)
            );

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'get'])
        	->getMock();
        $query->expects($this->at(0))
        	->method('where')
        	->with(
        	    $this->equalTo('deleted'),
        	    $this->equalTo(false)
        	)
        	->willReturn($query);
        $query->expects($this->at(1))
        	->method('get')
        	->willReturn($queryResults);
        
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->once())
        	->method('table')
        	->with($this->equalTo('acl_permissions'))
        	->willReturn($query);
        
        $factory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb', 'generatePermission'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->once())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);
        $factory->expects($this->once())
            ->method('generatePermission')
            ->with($this->equalTo($firstRecord))
            ->willReturn($generatedPermission);
        
        // run
        $results = $factory->getAllSince();
        
        // post-run assertions
        $this->assertEquals($keyedRecords, $results);
    }
    
    //======================================================
    //
    // test: modify
    //
    
    /**
     * test: modify
     */
    public function testModify()
    {
        // params
        $permissionId = 123123123;
        $updates = [
            'id' => $permissionId,
            'slug' => 'a_slug_that_should_not_be_edited',
            'description' => 'a_new_description',
            'invalid' => 'data'
        ];
        
        // mock
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with(PermissionFactory::ALL_CACHE_TAG)
            ->willReturn($cache);
        $cache->expects($this->once())
            ->method('flush');

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'update'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('id'),
                $this->equalTo($permissionId)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('where')
            ->with(
                $this->equalTo('deleted'),
                $this->equalTo(false)
            )
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(3))
            ->method('update')
            ->with($this->equalTo([
                'description' => 'a_new_description'
            ]))
            ->willReturn(1);

        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->any())
            ->method('table')
            ->with($this->equalTo('acl_permissions'))
            ->willReturn($query);
            
        $factory = $this->getMockBuilder('\Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->once())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);
            
        // run
        $factory->modify($permissionId, $updates);
    }
    
    //======================================================
    //
    // test: remove
    //
    
    /**
     * test: remove successfully marks pre-existing record as deleted
     */
    public function testRemoveSuccessfullyMarksPreExistingRecordAsDeleted()
    {
        // params
        $permissionId = 123123123;

        // mock
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with(PermissionFactory::ALL_CACHE_TAG)
            ->willReturn($cache);
        $cache->expects($this->once())
            ->method('flush');
        
        $foundPermission = (object) [
            'id' => $permissionId,
            'slug' => 'a_slug'
        ];
        $foundPermissions = [$foundPermission];

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'get', 'update'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('id'),
                $this->equalTo($permissionId)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('get')
            ->willReturn($foundPermissions);
        $query->expects($this->at(3))
            ->method('where')
            ->with(
                $this->equalTo('id'),
                $this->equalTo($permissionId)
            )
            ->willReturn($query);
        $query->expects($this->at(4))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(5))
            ->method('update')
            ->with($this->equalTo(['deleted' => true]));

        $aclRolePermissionFactory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['removePermissionSlug'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->once())
            ->method('removePermissionSlug')
            ->with($this->equalTo('a_slug'));
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);

        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table', 'beginTransaction', 'commitTransaction', 'rollbackTransaction'])
            ->getMock();
        $database->expects($this->any())
        	->method('table')
        	->with($this->equalTo('acl_permissions'))
        	->willReturn($query);
        $database->expects($this->any())
            ->method('beginTransaction');
        $database->expects($this->any())
            ->method('commitTransaction');
        $database->expects($this->never())
            ->method('removeTransaction');
        
        $factory = $this->getMockBuilder('\Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->once())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->any())
            ->method('getDb')
            ->willReturn($database);

        // run
        $factory->remove($permissionId);
    }
    
    /**
     * test: remove throws exception if the permission does not exist
     * 
     * @expectedException Unified\Models\AppException
     * @expectedExceptionMessage id_not_found
     */
    public function testRemoveThrowsExceptionIfThePermissionDoesNotExist()
    {
        // params
        $permissionId = 123123123;

        // mock
        $foundPermissions = [];

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'get', 'update'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('id'),
                $this->equalTo($permissionId)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('get')
            ->willReturn($foundPermissions);

        $aclRolePermissionFactory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['removePermissionSlug'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->never())
            ->method('removePermissionSlug');
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
            
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table', 'beginTransaction', 'commit', 'rollback'])
            ->getMock();
        $database->expects($this->any())
        	->method('table')
        	->with($this->equalTo('acl_permissions'))
        	->willReturn($query);
        $database->expects($this->never())
            ->method('beginTransaction');
        $database->expects($this->never())
            ->method('commit');
        $database->expects($this->never())
            ->method('rollback');
        
        $factory = $this->getMockBuilder('\Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->never())
            ->method('getCache');
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);

        // run
        $factory->remove($permissionId);
    }
    
    /**
     * test: remove throws exception if rollback is triggered
     * 
     * @expectedException \Exception
     * @expectedExceptionMessage some_exception
     */
    public function testRemoveThrowsExceptionIfRollbackIsTriggered()
    {
        // params
        $permissionId = 123123123;

        // mock
        $foundPermissions = [(object) [
            'id' => $permissionId,
            'slug' => 'a_slug'
        ]];

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'get', 'update'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('id'),
                $this->equalTo($permissionId)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('get')
            ->willReturn($foundPermissions);

        $exception = new Exception('some_exception');
            
        $aclRolePermissionFactory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['removePermissionSlug'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->once())
            ->method('removePermissionSlug')
            ->with($this->equalTo('a_slug'))
            ->willThrowException($exception);
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
            
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table', 'beginTransaction', 'commit', 'rollback'])
            ->getMock();
        $database->expects($this->any())
        	->method('table')
        	->with($this->equalTo('acl_permissions'))
        	->willReturn($query);
        $database->expects($this->once())
            ->method('beginTransaction');
        $database->expects($this->never())
            ->method('commit');
        $database->expects($this->once())
            ->method('rollback');
        
        $factory = $this->getMockBuilder('\Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->never())
            ->method('getCache');
        $factory->expects($this->any())
            ->method('getDb')
            ->willReturn($database);

        // run
        $factory->remove($permissionId);
    }

    //======================================================
    //
    // test: upsert
    //
    
    /**
     * test: upsert inserts if the record doesn't already exist
     */
    public function testUpsertInsertsIfTheRecordDoesntAlreadyExist()
    {
        // params
        $slug = 'a_slug';
        $title = 'a_title';
        $description = 'a_description';

        // mock
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with(PermissionFactory::ALL_CACHE_TAG)
            ->willReturn($cache);
        $cache->expects($this->once())
            ->method('flush');

        $permissionId = 123123123;

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'get', 'insertGetId', 'update'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('slug'),
                $this->equalTo($slug)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('get')
            ->willReturn([]);
        $query->expects($this->at(3))
            ->method('insertGetId')
            ->with($this->equalTo([
                'slug' => $slug,
                'title' => $title,
                'description' => $description
            ]))
            ->willReturn($permissionId);

        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->any())
        	->method('table')
        	->with($this->equalTo('acl_permissions'))
        	->willReturn($query);
        
        $factory = $this->getMockBuilder('\Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->once())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);

        // run
        $results = $factory->upsert($slug, $title, $description);
        
        // post-run assertions
        $this->assertEquals($permissionId, $results);
    }
    
    /**
     * test: upsert updates if the record already exist
     */
    public function testUpsertUpdatesIfTheRecordAlreadyExistAsDeleted()
    {
        // params
        $slug = 'a_slug';
        $title = 'a_title';
        $description = 'a_description';

        // mock
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
            ->disableOriginalConstructor()
            ->setMethods(['tags', 'flush'])
            ->getMock();
        $cache->expects($this->any())
            ->method('tags')
            ->with(PermissionFactory::ALL_CACHE_TAG)
            ->willReturn($cache);
        $cache->expects($this->once())
            ->method('flush');

        $permissionId = 123123123;

        $permissionRecord = (object) [
            'id' => $permissionId,
            'deleted' => true,
            'other' => 'data'
        ];
        
        $foundPermissionRecords = [
            $permissionRecord  
        ];

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'get', 'insertGetId', 'update'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('slug'),
                $this->equalTo($slug)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('get')
            ->willReturn($foundPermissionRecords);
        $query->expects($this->at(3))
            ->method('where')
            ->with(
                $this->equalTo('slug'),
                $this->equalTo($slug)    
            )
            ->willReturn($query);
        $query->expects($this->at(4))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(5))
            ->method('update')
            ->with($this->equalTo([
                'title' => $title,
                'description' => $description,
                'deleted' => false
            ]));

        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->any())
        	->method('table')
        	->with($this->equalTo('acl_permissions'))
        	->willReturn($query);
        
        $factory = $this->getMockBuilder('\Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->once())
            ->method('getCache')
            ->willReturn($cache);
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);

        // run
        $results = $factory->upsert($slug, $title, $description);
        
        // post-run assertions
        $this->assertEquals($permissionId, $results);
    }
    
    /**
     * test: upsert throws exception if the record already exists and is not deleted
     * 
     * @expectedException Unified\Models\AppException
     * @expectedExceptionMessage slug_unavailable
     * 
     */
    public function testUpsertThrowsExceptionIfTheRecordAlreadyExistsAndIsNotDeleted()
    {
        // params
        $slug = 'a_slug';
        $title = 'a_title';
        $description = 'a_description';

        // mock
        $permissionId = 123123123;

        $permissionRecord = (object) [
            'id' => $permissionId,
            'deleted' => false,
            'other' => 'data'
        ];
        
        $foundPermissionRecords = [
            $permissionRecord  
        ];

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['where', 'limit', 'get', 'insertGetId', 'update'])
        	->getMock();
        $query->expects($this->at(0))
            ->method('where')
            ->with(
                $this->equalTo('slug'),
                $this->equalTo($slug)
            )
            ->willReturn($query);
        $query->expects($this->at(1))
            ->method('limit')
            ->with($this->equalTo(1))
            ->willReturn($query);
        $query->expects($this->at(2))
            ->method('get')
            ->willReturn($foundPermissionRecords);

        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->any())
        	->method('table')
        	->with($this->equalTo('acl_permissions'))
        	->willReturn($query);
        
        $factory = $this->getMockBuilder('\Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['isCacheRedis', 'getCache', 'getDb'])
            ->getMock();
        $factory->expects($this->any())
            ->method('isCacheRedis')
            ->willReturn(true);
        $factory->expects($this->never())
            ->method('getCache');
        $factory->expects($this->once())
            ->method('getDb')
            ->willReturn($database);

        // run
        $results = $factory->upsert($slug, $title, $description);
        
        // post-run assertions
        $this->assertEquals($permissionId, $results);
    }


    //======================================================
    //
    // test: generatePermission
    //
    
    /**
     * test: generatePermission
     */
    public function testGeneratePermission()
    {
        // param
        $id = '123123';
        $slug = 'a_slug';
        $createdAt = 1500000000;
        $updatedAt = 1500000000;
        
        $data = (object) [
            'id'  => $id,
            'slug' => $slug,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ];
        
        // run
        $permission = PermissionFactory::getInstance()->generatePermission($data);
        
        // post-run assertion
        $expectedPermission = new Permission($data);
        $this->assertEquals($expectedPermission, $permission);
    }
}