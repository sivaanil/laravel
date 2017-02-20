<?php
use \DB;

class LoadCssNetworkingNetworkTreeMapFixture
{
    public function run($db)
    {
    	try {

	    	$db->table('css_networking_network_tree_map')
	    		->insert([
	    			[
	    				'node_id' => 321,
	    				'node_map' => '.321.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5,
	    				'node_map' => '.321.5.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5000,
	    				'node_map' => '.321.5000.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5001,
	    				'node_map' => '.321.5000.5001.',
	    				'deleted' => 1,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5002,
	    				'node_map' => '.321.5000.5002.',
	    				'deleted' => 0,
	    				'visible' => 0
	    			],
	    			[
	    				'node_id' => 5003,
	    				'node_map' => '.321.5000.5003.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5004,
	    				'node_map' => '.321.5000.5003.5004.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5005,
	    				'node_map' => '.321.5000.5003.5004.5005.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5006,
	    				'node_map' => '.321.5000.5003.5004.5005.5006.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5007,
	    				'node_map' => '.321.5000.5003.5004.5005.5007.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5008,
	    				'node_map' => '.321.5000.5003.5004.5008.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5009,
	    				'node_map' => '.5009.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5010,
	    				'node_map' => '.5009.5010.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5011,
	    				'node_map' => '.5009.5011.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5015,
	    				'node_map' => '.5015.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5016,
	    				'node_map' => '.5015.5016.',
	    				'deleted' => 0,
	    				'visible' => 1
	    			],
	    			[
	    				'node_id' => 5017,
	    				'node_map' => '.5015.5017.',
	    				'deleted' => 1,
	    				'visible' => 0
	    			]	
	    		]);
	    		
    	} catch (\Exception $e) {
    		echo $e->getMessage() . PHP_EOL;
    		exit;
    	}
    	
    }
}