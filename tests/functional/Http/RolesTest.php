<?php
namespace Tests\functional\Http;

use Tests\functional\AbstractDatabaseTestCase;

class RolesTest extends AbstractDatabaseTestCase
{
	/**
	 * test: roles
	 */
	public function testCrudRoles()
	{
		//==========================================================
		//
		// Login
		//
		
		$this->loadFixture('LoadLoginUser');
		$this->loadFixture('LoadCssNetworkingNetworkTreeMap');
		$this->loadFixture('LoadAclPermissions');
		$authenticationToken = $this->login();
		
		//==========================================================
		//
		// Roles should start off empty (verify test area is sanitized)
		//
		// involves calls:
		// GET /v1/roles - get all roles back
		//
		
		$headers = [ 'Authorization' => $authenticationToken ];
		$response = $this->callGet('/v1/roles', $headers);
		
		if (!isset($response['roles'])) {
			$this->fail('Roles missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$roles = $response['roles'];
		
		$this->assertEquals([], $roles);
		
		
		//==========================================================
		//
		// Create a role
		//
		// involves calls:
		// POST /v1/roles - create a new role
		// GET /v1/roles - get all roles back 
		//
		
		$data = [
			'title' => 'Customer Service',
			'slug' => 'customer_service',
			'description' => 'One who services the customer.',
			'whitelistNodeIds' => '.5003.5006.',
			'blacklistNodeIds' => '',
		    'permissions' => [ 'a_permission', 'b_permission']
		];
		$response = $this->callPost('/v1/roles', $headers, $data);
		
		if (!isset($response['roleId'])) {
		    $this->fail('Role Id missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$roleId = $response['roleId']; 
		
		$response = $this->callGet('/v1/roles', $headers);
		
		if (!isset($response['roles'])) {
			$this->fail('Roles missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		$roles = $response['roles'];
		
		$this->assertEquals(1, count($roles));
		
		$role = $roles[0];

		$this->assertEquals($roleId, $role['id']);
		$this->assertEquals($data['title'], $role['title']);
		$this->assertEquals($data['slug'], $role['slug']);
		$this->assertEquals($data['description'], $role['description']);
		$this->assertEquals($data['whitelistNodeIds'], $role['whitelistNodeIds']);
		$this->assertEquals($data['blacklistNodeIds'], $role['blacklistNodeIds']);
		$this->assertEquals($data['permissions'], $role['permissions']);

		//==========================================================
		//
		// Read a role
		//
		// involves calls:
		// GET /v1/roles/{id} - Get a single role
		//
		
		$data = [];
		$response = $this->callGet('/v1/roles/' . $roleId, $headers, $data);
		
		if (!isset($response['roles'])) {
			$this->fail('Roles missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		$roles = $response['roles'];
		
		$this->assertEquals(1, count($roles));
		
		$role = $roles[0];
		
		$expectedRole = [
			'id' => $roleId,
			'title' => 'Customer Service',
			'slug' => 'customer_service',
			'description' => 'One who services the customer.',
			'whitelistNodeIds' => '.5003.5006.',
			'blacklistNodeIds' => '',
		    'permissions' => ['a_permission', 'b_permission']
		];

		$this->assertEquals($expectedRole['id'], $role['id']);
		$this->assertEquals($expectedRole['title'], $role['title']);
		$this->assertEquals($expectedRole['slug'], $role['slug']);
		$this->assertEquals($expectedRole['description'], $role['description']);
		$this->assertEquals($expectedRole['whitelistNodeIds'], $role['whitelistNodeIds']);
		$this->assertEquals($expectedRole['blacklistNodeIds'], $role['blacklistNodeIds']);
		$this->assertEquals($expectedRole['permissions'], $role['permissions']);

		//==========================================================
		//
		// Update a role
		//
		// involves calls:
		// PUT /v1/roles/{id} - Update a single role
		// GET /v1/roles/{id} - Get a single role
		//
		
		// change critical data describing the role
		$data = [
			'description' => 'One whom services the customer.',
		    'permissions' => ['a_permission', 'c_permission']
		];
		$response = $this->callPut('/v1/roles/' . $roleId, $headers, $data);

		// check to see if the data has changed
		$response = $this->callGet('/v1/roles/' . $roleId, $headers);
		if (!isset($response['roles'])) {
			$this->fail('Roles missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		$roles = $response['roles'];
		
		$this->assertEquals(1, count($roles));
		
		$role = $roles[0];
		
		$expectedRole = [
			'id' => $roleId,
			'title' => 'Customer Service',
			'slug' => 'customer_service',
			'description' => 'One whom services the customer.',
			'whitelistNodeIds' => '.5003.5006.',
			'blacklistNodeIds' => '',
		    'permissions' => ['a_permission', 'c_permission']
		];

		$this->assertEquals($expectedRole['id'], $role['id']);
		$this->assertEquals($expectedRole['title'], $role['title']);
		$this->assertEquals($expectedRole['slug'], $role['slug']);
		$this->assertEquals($expectedRole['description'], $role['description']);
		$this->assertEquals($expectedRole['whitelistNodeIds'], $role['whitelistNodeIds']);
		$this->assertEquals($expectedRole['blacklistNodeIds'], $role['blacklistNodeIds']);
		$this->assertEquals($expectedRole['permissions'], $role['permissions']);
		
		//==========================================================
		//
		// Delete a role
		//
		// involves calls:
		// DELETE /v1/roles/{id} - Delete a single role
		// GET /v1/roles/{id} - Try to get non-existent role
		// GET /v1/roles - All roles
		//
		
		// change critical data describing the role
		$response = $this->callDelete('/v1/roles/' . $roleId, $headers);
		
		// check to see if the data has changed (for id specific call)
		//
		// The roles should not be present (since the one searched for is deleted),
		// and an error message saying is was not found should be returned.
		$response = $this->callGet('/v1/roles/' . $roleId . '', $headers);
		if (isset($response['roles'])) {
			$this->fail('Roles not expected in response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		
		if (!isset($response['message'])) {
			$this->fail('Message missing from response (line: ' . __LINE__ . '): ' . print_r($response, true));
		}
		
		$this->assertEquals('GET api/v1/roles/' . $roleId . ' is not found', $response['message']);

		// make sure the "/roles" call returns an empty array again
		$response = $this->callGet('/v1/roles', $headers);
		if (!isset($response['roles'])) {
			$this->fail('Roles missing from response (line: '. __LINE__ . '): ' . print_r($response, true));
		}
		$roles = $response['roles'];
		
		$this->assertEquals([], $roles);
	}
}