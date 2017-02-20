<?php
namespace Tests\functional\Models;

use Tests\functional\AbstractDatabaseTestCase;
use Unified\Models\NetworkTreeMap;

class NetworkTreeMapTest extends AbstractDatabaseTestCase
{
	/**
	 * test: are nodes accessible by home node ids without blacklisting
	 */
	public function testAreNodesAccessibleByHomeNodeIdsWithoutBlacklisting()
	{
		// Login
		$this->loadFixture('LoadLoginUser');
		$authenticationToken = $this->login();
		
		// Pre-load Tree Map
		$this->loadFixture('LoadCssNetworkingNetworkTreeMap');
		
		
		$treeMap = new NetworkTreeMap();
		
		//=======================================
		//
		// Case #1: all nodes are accessible
		//
		$nodeIds = [ 5007, 5016, 5003 ];
		$homeNodeIds = [ 5000, 5016 ];
		$this->assertTrue($treeMap->areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds));
		

		//=======================================
		//
		// Case #2: nodes are not accessible
		//
		$nodeIds = [ 5 ];
		$homeNodeIds = [ 5000, 5016 ];
		$this->assertFalse($treeMap->areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds));

		//=======================================
		//
		// Case #3: a node is not accessible
		//
		$nodeIds = [ 5002, 5000, 5016, 5003 ];
		$homeNodeIds = [ 5000, 5016 ];
		$this->assertFalse($treeMap->areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds));
	}
	
	/**
	 * test: are nodes accessible by home node ids while blacklisting
	 */
	public function testAreNodesAccessibleByHomeNodeIdsWhileBlacklisting()
	{
		// Login
		$this->loadFixture('LoadLoginUser');
		$authenticationToken = $this->login();
		
		// Pre-load Tree Map
		$this->loadFixture('LoadCssNetworkingNetworkTreeMap');
		
		
		$treeMap = new NetworkTreeMap();
		
		//=======================================
		//
		// Case #1: all nodes are accessible
		//
		$nodeIds = [ 5007, 5016, 5003 ];
		$homeNodeIds = [ 5000, 5016 ];
		$blacklistNodeIds = [ 5 ];
		$this->assertTrue($treeMap->areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds, $blacklistNodeIds));
		

		//=======================================
		//
		// Case #2: nodes are not accessible
		//
		$nodeIds = [ 5004 ]; 
		$homeNodeIds = [ 5000, 5016 ];
		$blacklistNodeIds = [ 5003 ]; // parent of 5004
		$this->assertFalse($treeMap->areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds, $blacklistNodeIds));

		//=======================================
		//
		// Case #3: a node is not accessible
		//
		$nodeIds = [ 5002, 5000, 5016, 5004 ];
		$homeNodeIds = [ 5000, 5016 ];
		$blacklistNodeIds = [ 5003 ];
		$this->assertFalse($treeMap->areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds, $blacklistNodeIds));
	}
}