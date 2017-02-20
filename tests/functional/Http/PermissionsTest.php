<?php
namespace Tests\functional\Http;

use Tests\functional\AbstractDatabaseTestCase;

class PermissionsTest extends AbstractDatabaseTestCase
{
	/**
	 * test: crud permissions
	 */
	public function testCrudPermissions()
	{
		//==========================================================
		//
		// Login
		//
		$this->loadFixture('LoadLoginUser');
		$this->loadFixture('LoadAclPermissions');
		$authenticationToken = $this->login();

		//==========================================================
		//
		// Get all
		//
		
		$headers = [ 'Authorization' => $authenticationToken ];
		$response = $this->callGet('/v1/permissions', $headers);
		
		if (!isset($response['permissions'])) {
			$this->fail('Permissions missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$permissions = $response['permissions'];
		
		$expectedPermissions = [
            'a_permission' => [
                'id' => '321',
                'slug' => 'a_permission',
                'title' => 'A Permission',
                'description' => 'This is a permission',
                'updatedAt' => 1480309200,
                'deleted' => 0
            ],
            'b_permission' => [
                'id' => '323',
                'slug' => 'b_permission',
                'title' => 'B Permission',
                'description' => 'This is not a permission, it\'s b permission. Get it? Get it? It\'s funny.... These are the jokes, people.',
                'updatedAt' => 1480384800,
                'deleted' => 0
            ],
            'd_permission' => [
                'id' => '665',
                'slug' => 'd_permission',
                'title' => 'D Permission',
                'description' => 'This is d permission.',
                'updatedAt' => 1480309200,
                'deleted' => 0
            ]
		];
		
		$this->assertEquals($expectedPermissions, $permissions);

		//==========================================================
		//
		// Get all after a given unixtime
		//
		
		$headers = [ 'Authorization' => $authenticationToken ];
		$response = $this->callGet('/v1/permissions?since=1480366000', $headers);
		
		if (!isset($response['permissions'])) {
			$this->fail('Permissions missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$permissions = $response['permissions'];
		
		$expectedPermissions = [
            'b_permission' => [
                'id' => '323',
                'slug' => 'b_permission',
                'title' => 'B Permission',
                'description' => 'This is not a permission, it\'s b permission. Get it? Get it? It\'s funny.... These are the jokes, people.',
                'updatedAt' => 1480384800,
                'deleted' => 0
            ],
            'c_permission' => [
                'id' => '344',
                'slug' => 'c_permission',
                'updatedAt' => 1480402800,
                'deleted' => 1
            ]
		];
		
		$this->assertEquals($expectedPermissions, $permissions);
		
		//============================================================
		//
		// Create new permission
		//
        $headers = [ 'Authorization' => $authenticationToken ];
		$data = [
		  'slug' => 'e_permission',
		  'title' => 'E Permission',
		  'description' => 'A brand new permission.'
		];
		
		$response = $this->callPost('/v1/permissions', $headers, $data);
		if (!isset($response['permission_id'])) {
			$this->fail('Permission Id missing from create response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$permissionId = $response['permission_id'];

		//==========================================================
		//
		// Get all after a given unixtime (see if the new permission is included
		//
		
		$headers = [ 'Authorization' => $authenticationToken ];
		$response = $this->callGet('/v1/permissions?since=1480366000', $headers);

		if (!isset($response['permissions'])) {
		    $this->fail('Permissions missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		if (!isset($response['permissions']['e_permission'])) {
		    $this->fail('New Permission is missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$permissions = $response['permissions'];
		
		$expectedPermissions = [
		    'b_permission' => [
		        'id' => '323',
		        'slug' => 'b_permission',
		        'title' => 'B Permission',
		        'description' => 'This is not a permission, it\'s b permission. Get it? Get it? It\'s funny.... These are the jokes, people.',
		        'updatedAt' => 1480384800,
		        'deleted' => 0
		    ],
		    'c_permission' => [
		        'id' => '344',
		        'slug' => 'c_permission',
		        'updatedAt' => 1480402800,
		        'deleted' => 1
		    ],
		    'e_permission' => [
		        'id' => $permissionId,
		        'slug' => 'e_permission',
		        'title' => 'E Permission',
		        'description' => 'A brand new permission.',
		        'deleted' => 0
		    ]
		];
		
		// We don't care what the updatedAt value is, so we'll just use the given one.
		$expectedPermissions['e_permission']['updatedAt'] = $permissions['e_permission']['updatedAt'];
		
		$this->assertEquals($expectedPermissions, $permissions);


		//============================================================
		//
		// Update a permission
		//
		$headers = [ 'Authorization' => $authenticationToken ];
		$data = [
		    'title' => 'An updated permission',
		    'description' => 'A brand new old permission, but new.'
		];
		
		$updatedPermissionId = '321';
		
		$response = $this->callPut('/v1/permissions/' . $updatedPermissionId, $headers, $data);

		//==========================================================
		//
		// Get all after a given unixtime (see if the new permission is included
		//
		$headers = [ 'Authorization' => $authenticationToken ];
		$response = $this->callGet('/v1/permissions?since=1480366000', $headers);
		
		if (!isset($response['permissions'])) {
		    $this->fail('Permissions missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$permissions = $response['permissions'];
		
		$expectedPermissions = [
            'a_permission' => [
                'id' => '321',
                'slug' => 'a_permission',
                'title' => 'An updated permission',
                'description' => 'A brand new old permission, but new.',
                'updatedAt' => $permissions['a_permission']['updatedAt'],   // we don't care what this value is
                'deleted' => 0
            ],
		    'b_permission' => [
		        'id' => '323',
		        'slug' => 'b_permission',
		        'title' => 'B Permission',
		        'description' => 'This is not a permission, it\'s b permission. Get it? Get it? It\'s funny.... These are the jokes, people.',
		        'updatedAt' => 1480384800,
		        'deleted' => 0
		    ],
		    'c_permission' => [
		        'id' => '344',
		        'slug' => 'c_permission',
		        'updatedAt' => 1480402800,
		        'deleted' => 1
		    ],
		    'e_permission' => [
		        'id' => $permissionId,
		        'slug' => 'e_permission',
		        'title' => 'E Permission',
		        'description' => 'A brand new permission.',
		        'deleted' => 0
		    ]
		];
		
		// We don't care what the updatedAt value is, so we'll just use the given one.
		$expectedPermissions['e_permission']['updatedAt'] = $permissions['e_permission']['updatedAt'];
		
		$this->assertEquals($expectedPermissions, $permissions);


		//============================================================
		//
		// delete a permission
		//
		$headers = [ 'Authorization' => $authenticationToken ];
		
		$deletedPermissionId = '321';
		$response = $this->callDelete('/v1/permissions/' . $deletedPermissionId, $headers);

		//==========================================================
		//
		// Get all after a given unixtime (see if the new permission is included
		//
		$headers = [ 'Authorization' => $authenticationToken ];
		$response = $this->callGet('/v1/permissions?since=1480366000', $headers);
		
		if (!isset($response['permissions'])) {
		    $this->fail('Permissions missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$permissions = $response['permissions'];
		
		$expectedPermissions = [
		    'a_permission' => [
		        'id' => '321',
		        'slug' => 'a_permission',
		        'updatedAt' => $permissions['a_permission']['updatedAt'],   // we don't care what this value is
		        'deleted' => 1
		    ],
		    'b_permission' => [
		        'id' => '323',
		        'slug' => 'b_permission',
		        'title' => 'B Permission',
		        'description' => 'This is not a permission, it\'s b permission. Get it? Get it? It\'s funny.... These are the jokes, people.',
		        'updatedAt' => 1480384800,
		        'deleted' => 0
		    ],
		    'c_permission' => [
		        'id' => '344',
		        'slug' => 'c_permission',
		        'updatedAt' => 1480402800,
		        'deleted' => 1
		    ],
		    'e_permission' => [
		        'id' => $permissionId,
		        'slug' => 'e_permission',
		        'title' => 'E Permission',
		        'description' => 'A brand new permission.',
		        'deleted' => 0
		    ]
		];
		
		// We don't care what the updatedAt value is, so we'll just use the given one.
		$expectedPermissions['e_permission']['updatedAt'] = $permissions['e_permission']['updatedAt'];
		
		$this->assertEquals($expectedPermissions, $permissions);


		//============================================================
		//
		// Create a permission using a deleted slug will result
		// in the new data acquiring the deleted row.
		//
		$headers = [ 'Authorization' => $authenticationToken ];
		$data = [
		    'slug' => 'a_permission',
		    'title' => 'A new permission with an old id',
		    'description' => 'Short and stubby'
		];
		
		$response = $this->callPost('/v1/permissions', $headers, $data);
		
		//==========================================================
		//
		// Get all after a given unixtime (see if the new permission is included (with the old id)
		//
		$headers = [ 'Authorization' => $authenticationToken ];
		$response = $this->callGet('/v1/permissions?since=1480366000', $headers);
		
		if (!isset($response['permissions'])) {
		    $this->fail('Permissions missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$permissions = $response['permissions'];
		
		$expectedPermissions = [
		    'a_permission' => [
		        'id' => '321',
		        'slug' => 'a_permission',
		        'title' => 'A new permission with an old id',
		        'description' => 'Short and stubby',
		        'updatedAt' => $permissions['a_permission']['updatedAt'],   // we don't care what this value is
		        'deleted' => 0
		    ],
		    'b_permission' => [
		        'id' => '323',
		        'slug' => 'b_permission',
		        'title' => 'B Permission',
		        'description' => 'This is not a permission, it\'s b permission. Get it? Get it? It\'s funny.... These are the jokes, people.',
		        'updatedAt' => 1480384800,
		        'deleted' => 0
		    ],
		    'c_permission' => [
		        'id' => '344',
		        'slug' => 'c_permission',
		        'updatedAt' => 1480402800,
		        'deleted' => 1
		    ],
		    'e_permission' => [
		        'id' => $permissionId,
		        'slug' => 'e_permission',
		        'title' => 'E Permission',
		        'description' => 'A brand new permission.',
		        'deleted' => 0
		    ]
		];
		
		// We don't care what the updatedAt value is, so we'll just use the given one.
		$expectedPermissions['e_permission']['updatedAt'] = $permissions['e_permission']['updatedAt'];
		
		$this->assertEquals($expectedPermissions, $permissions);
	}
}