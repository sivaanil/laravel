<?php
namespace tests\Models;

use \tests\unit\UnitTestCase;
use \Unified\Models\NetworkTreeMap;

class NetworkTreeMapTest extends UnitTestCase
{
	/**
	 * test: getNodesAccessibleByHomeNodeIdsReturnsArrayFromCache
	 */
	public function testGetNodesAccessibleByHomeNodeIdsReturnsArrayFromCache()
	{
		// param
		$homeNodeIds = [ 4, 5, 10 ];
		$blacklistNodeIds = [ 11, 12 ];
		
		// mocks
		$accessibleNodeIds = [ 4, 5, 7, 8, 10, 11, 12, 24601 ];
		
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
             ->disableOriginalConstructor()
             ->setMethods(array('get', 'put'))
             ->getMock();
        $cache->expects($this->once())
        	->method('get')
        	->with($this->equalTo(NetworkTreeMap::HOME_NODE_ACCESS_LIST_CACHE_PREFIX . '4.5.10:bl:11.12'))
        	->willReturn(json_encode($accessibleNodeIds));
        $cache->expects($this->never())
        	->method('put');
		
		$networkTreeMap = $this->getMockBuilder('Unified\Models\NetworkTreeMap')
			->disableOriginalConstructor()
			->setMethods(['getCache', 'getDb', 'isCacheRedis' ])
			->getMock();
		$networkTreeMap->expects($this->any())
			->method('getCache')
			->willReturn($cache);
		$networkTreeMap->expects($this->any())
			->method('isCacheRedis')
			->willReturn(true);
		$networkTreeMap->expects($this->never())
			->method('getDb');
		
		// run
		$results = $networkTreeMap->getNodesAccessibleByHomeNodeIds($homeNodeIds, $blacklistNodeIds);
	
		// post-run assertion
		$this->assertEquals($accessibleNodeIds, $results);
	}
	
	/**
	 * test: getNodesAccessibleByHomeNodeIdsReturnsArrayFromDatabase
	 */
	public function testGetNodesAccessibleByHomeNodeIdsReturnsArrayFromDatabase()
	{
		// param
		$homeNodeIds = [ 4, 5, 10 ];
		$blacklistNodeIds = [ 110, 120 ];
		
		// mocks
		$accessibleNodeIds = [ 4, 5, 7, 8, 10, 11, 12, 24601 ];
		
        $cache = $this->getMockBuilder('\Illuminate\Cache\Repository')
             ->disableOriginalConstructor()
             ->setMethods(array('get', 'put'))
             ->getMock();
        $cache->expects($this->once())
        	->method('get')
        	->with($this->equalTo(NetworkTreeMap::HOME_NODE_ACCESS_LIST_CACHE_PREFIX . '4.5.10:bl:110.120'))
        	->willReturn(null);
        $cache->expects($this->once())
        	->method('put')
        	->with(
        		$this->equalTo(NetworkTreeMap::HOME_NODE_ACCESS_LIST_CACHE_PREFIX . '4.5.10:bl:110.120'),
        		$this->equalTo(json_encode($accessibleNodeIds)),
        		$this->equalTo(NetworkTreeMap::CACHE_TTL)
        	);
        
        $queryResults = [
        	(object) [ 'node_id' => 4 ],
        	(object) [ 'node_id' => 5 ],
        	(object) [ 'node_id' => 7 ],
        	(object) [ 'node_id' => 8 ],
        	(object) [ 'node_id' => 10 ],
        	(object) [ 'node_id' => 11 ],
        	(object) [ 'node_id' => 12 ],
        	(object) [ 'node_id' => 24601 ]
        ];

        $query = $this->getMockBuilder('\Illuminate\Database\Query\Builder')
        	->disableOriginalConstructor()
        	->setMethods(['select', 'where', 'get'])
        	->getMock();
        $query->expects($this->at(0))
        	->method('select')
        	->with($this->equalTo('node_id'))
        	->willReturn($query);
        $query->expects($this->at(1))
        	->method('where')
        	->with($this->equalTo('visible'), $this->equalTo(1))
        	->willReturn($query);
        $query->expects($this->at(2))
        	->method('where')
        	->with($this->equalTo('deleted'), $this->equalTo(0))
        	->willReturn($query);
        $query->expects($this->at(3))
        	->method('where')  // expects a closure
        	->willReturn($query);
        $query->expects($this->at(4))
        	->method('where')  // expects a closure
        	->willReturn($query);
        $query->expects($this->at(5))
        	->method('get')
        	->willReturn($queryResults);
        
        $database = $this->getMockBuilder('\Illuminate\Database\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();
        $database->expects($this->once())
        	->method('table')
        	->with($this->equalTo('css_networking_network_tree_map'))
        	->willReturn($query);
		
		$networkTreeMap = $this->getMockBuilder('Unified\Models\NetworkTreeMap')
			->disableOriginalConstructor()
			->setMethods(['getCache', 'getDb', 'isCacheRedis' ])
			->getMock();
		$networkTreeMap->expects($this->any())
			->method('getCache')
			->willReturn($cache);
		$networkTreeMap->expects($this->any())
			->method('isCacheRedis')
			->willReturn(true);
		$networkTreeMap->expects($this->any())
			->method('getDb')
			->willReturn($database);
		
		// run
		$results = $networkTreeMap->getNodesAccessibleByHomeNodeIds($homeNodeIds, $blacklistNodeIds);
	
		// post-run assertion
		$this->assertEquals($accessibleNodeIds, $results);
	}
	
	/**
	 * test: areNodesAccessibleByHomeNodeIds if all are accessible
	 */
	public function testAreNodesAccessibleByHomeNodeIdsReturnsTrueIfAllAreAccessible()
	{
		// param
		$nodeIds = [ 1, 2, 3, 4 ];
		$homeNodeIds = [ 1, 3 ];
		$blacklistNodeIds = [ 1, 5, 8 ];
		
		// mock
		$networkTreeMap = $this->getMockBuilder('Unified\Models\NetworkTreeMap')
			->disableOriginalConstructor()
			->setMethods(['getNodesAccessibleByHomeNodeIds' ])
			->getMock();
		$networkTreeMap->expects($this->once())
			->method('getNodesAccessibleByHomeNodeIds')
			->with(
			    $this->equalTo($homeNodeIds),
			    $this->equalTo($blacklistNodeIds)
			)
			->willReturn([1,2,3,4,5,6,7,8,9,10]);
		
		// run
		$results = $networkTreeMap->areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds, $blacklistNodeIds);
		
		// post-run assertions
		$this->assertTrue($results);
	}
	
	/**
	 * test: areNodesAccessibleByHomeNodeIds if even just one is not accessible
	 */
	public function testAreNodesAccessibleByHomeNodeIdsReturnsTrueIfEvenJustOneIsNotAccessible()
	{
		// param
		$nodeIds = [ 1, 2, 3, 4 ];
		$homeNodeIds = [ 1, 3 ];
		$blacklistNodeIds = [ 1, 5, 8 ];
		
		// mock
		$networkTreeMap = $this->getMockBuilder('Unified\Models\NetworkTreeMap')
			->disableOriginalConstructor()
			->setMethods(['getNodesAccessibleByHomeNodeIds' ])
			->getMock();
		$networkTreeMap->expects($this->once())
			->method('getNodesAccessibleByHomeNodeIds')
			->with(
			    $this->equalTo($homeNodeIds),
			    $this->equalTo($blacklistNodeIds)
			)
			->willReturn([1,2,4,5,6,7,8,9,10]);
		
		// run
		$results = $networkTreeMap->areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds, $blacklistNodeIds);
		
		// post-run assertions
		$this->assertFalse($results);
	}
	
}