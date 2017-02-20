<?php
namespace tests\unit\Services\Api;

use Unified\Http\Helpers\QueryParameters;
use Tests\unit\UnitTestCase;
use Unified\Services\API\ServiceRequest;
use Unified\Services\API\ServiceResponse;

class RoleServiceTest extends UnitTestCase
{
    /**
     *  test: addRole returns successful serviceResponse for positive status returns
     */
    public function testAddRoleReturnsSuccessfulServiceResponseForPositiveStatusReturns()
    {
        // mock
        $type = 'a_type';
        $action = 'an_action';
        $modelData = [
            'content' => [
                'id' => 23,
                'whitelist_node_ids' => '.3.4.5.',
                'blacklist_node_ids' => '.13.14.15.',
                'permissions' => ['a_permission', 'b_permission']
            ]
        ];
        $request = new ServiceRequest($type, $action, $modelData, null, null);

        $unaliasedModelData = [
            'content' => [
                'id' => 23,
                'whitelist_node_ids' => '.3.4.5.',
                'blacklist_node_ids' => '.13.14.15.'
            ]
        ];
        
        $unaliasedRequest = new ServiceRequest($type, $action, $unaliasedModelData, null, null);
        
        $contentToAdd = [
            'id' => 23,
            'whitelist_node_ids' => '.3.4.5.',
            'blacklist_node_ids' => '.13.14.15.',
            'permissions' => ['a_permission', 'b_permission']
        ];

        $responseRecord = [
            'status' => 1,
            'roleId' => 23
        ];

        $validator = $this->getMockBuilder('Unified\Services\API\RequestValidators\RequestValidator')
            ->disableOriginalConstructor()
            ->setMethods(['unaliasContent'])
            ->getMock();
        $validator->expects($this->any())
            ->method('unaliasContent')
            ->with($this->equalTo($unaliasedRequest))
            ->willReturn([
                'id' => 23,
                'whitelist_node_ids' => '.3.4.5.',
                'blacklist_node_ids' => '.13.14.15.',
            ]);
            
        $role = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['addRole'])
            ->getMock();
        $role->expects($this->once())
            ->method('addRole')
            ->with($this->equalTo($contentToAdd))
            ->willReturn($responseRecord);

        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->setConstructorArgs([$request, $validator, $role])
            ->setMethods(['getValidator', 'checkWhitelistBlacklistAccess'])
            ->getMock();
        $roleService->expects($this->once())
            ->method('checkWhitelistBlacklistAccess')
            ->with($this->equalTo('.3.4.5.'), $this->equalTo('.13.14.15.'));

        // run
        $results = $roleService->addRole();

        // post-run assertions
        $expectedResults = new ServiceResponse(ServiceResponse::SUCCESS, ['roleId' => 23]);
        $this->assertEquals($expectedResults, $results);
     
    }
    
    /**
     *  test: addRole returns error serviceResponse for negative status returns
     */
    public function testAddRoleReturnsErrorServiceResponseForNegativeStatusReturns()
    {
        // mock
        $type = 'TheType';
        $action = 'anAction';
        $handlerResults = 'some_results';
        $request = new ServiceRequest($type, $action, null, null, null);

        $unaliasedContent = [
            'id' => 23,
            'whitelist_node_ids' => '.3.4.5.',
            'blacklist_node_ids' => '.13.14.15.',
            'permissions' => []
        ];
        
        $responseRecord = [
            'status' => 0,
            'roleId' => 23,
            'error' => 'Code fall down go boom.'
        ];

        $validator = $this->getMockBuilder('Unified\Services\API\RequestValidators\RequestValidator')
            ->disableOriginalConstructor()
            ->setMethods(array('unaliasContent'))
            ->getMock();
        $validator->expects($this->once())
            ->method('unaliasContent')
            ->with($this->equalTo($request))
            ->willReturn($unaliasedContent);

        $role = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['addRole'])
            ->getMock();
        $role->expects($this->once())
            ->method('addRole')
            ->with($this->equalTo($unaliasedContent))
            ->willReturn($responseRecord);

        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->setConstructorArgs(array($request, $validator, $role))
            ->setMethods(array('getValidator', 'checkWhitelistBlacklistAccess'))
            ->getMock();
        $roleService->expects($this->once())
            ->method('checkWhitelistBlacklistAccess')
            ->with($this->equalTo('.3.4.5.'), $this->equalTo('.13.14.15.'));
        
        // run
        $results = $roleService->addRole();

        // post-run assertions
        $expectedResults = new ServiceResponse(ServiceResponse::UNPROCESSABLE_ENTITY, ['error' => 'Code fall down go boom.' ]);
        $this->assertEquals($expectedResults, $results);
    }
    
    /**
     * test: deleteRole sends unaliased contents to handler
     */
    public function testDeleteRoleSendsUnaliasedContentsToHandler()
    {
        // mock
        $type = 'TheType';
        $action = 'anAction';
        $handlerResults = 'some_results';
        $request = new ServiceRequest($type, $action, null, null, null);

        $roleId = 123;
        $unaliasedContent = [
            'id' => $roleId
        ];
        
        $responseRecord = [
          'status' => true
        ];

        $validator = $this->getMockBuilder('Unified\Services\API\RequestValidators\RequestValidator')
            ->disableOriginalConstructor()
            ->setMethods(array('unaliasContent'))
            ->getMock();
        $validator->expects($this->once())
            ->method('unaliasContent')
            ->with($this->equalTo($request))
            ->willReturn($unaliasedContent);

        $role = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['deleteRole'])
            ->getMock();
        $role->expects($this->once())
            ->method('deleteRole')
            ->with($this->equalTo($unaliasedContent))
            ->willReturn($responseRecord);

        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->setConstructorArgs(array($request, $validator, $role))
            ->setMethods(array('getValidator', 'checkRoleAccess'))
            ->getMock();
        $roleService->expects($this->once())
            ->method('checkRoleAccess')
            ->with($this->equalTo($roleId))
            ->willReturn($responseRecord);

        // run
        $results = $roleService->deleteRole();

        // post-run assertions
        $expectedResults = new ServiceResponse(ServiceResponse::SUCCESS);
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: getRoleById
     */
    public function testGetRoleByIdReturnsSuccessfulServiceResponse()
    {
        // mock
        $type = 'TheType';
        $action = 'anAction';
        $handlerResults = 'some_results';
        $request = new ServiceRequest($type, $action, null, null, null);

        $config = new QueryParameters(['data'], null, [], null);
        $foundRoles = [
           'roles' => ['wheat', 'kaiser', 'tank', 'dps', 'healer']
        ];
        
        
        $allowedNodeIds = [4,5,6,7];

        $role = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['getRoles'])
            ->getMock();
        $role->expects($this->once())
            ->method('getRoles')
            ->with($this->equalTo($config), $this->equalTo($allowedNodeIds))
            ->willReturn($foundRoles);

        $validator = $this->getMockBuilder('Unified\Services\API\RequestValidators\RequestValidator')
            ->disableOriginalConstructor()
            ->setMethods(['getQueryParameters'])
            ->getMock();
        $validator->expects($this->once())
            ->method('getQueryParameters')
            ->with($this->equalTo($request))
            ->willReturn($config);
        
        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->setConstructorArgs([$request, $validator, $role])
            ->setMethods(['getValidator', 'getAllowedNodeIds'])
            ->getMock();
        $roleService->expects($this->once())
            ->method('getAllowedNodeIds')
            ->willReturn($allowedNodeIds);

        // run
        $results = $roleService->getRoleById();

        // post-run assertions
        $expectedResults = new ServiceResponse(ServiceResponse::SUCCESS, $foundRoles);
        $this->assertEquals($expectedResults, $results);
    }
    
    /**
     * test: getRoles returns queried results
     */
    public function testGetRolesReturnsQueriedResults()
    {
        // mock
        $type = 'TheType';
        $action = 'anAction';
        $handlerResults = 'some_results';
        $request = new ServiceRequest($type, $action, null, null, null);

        $config = new QueryParameters(['data'], null, [], null);
        $foundRoles = [
            'roles' => ['wheat', 'kaiser', 'tank', 'dps', 'healer']
        ];
        
        $validator = $this->getMockBuilder('Unified\Services\API\RequestValidators\RequestValidator')
            ->disableOriginalConstructor()
            ->setMethods(['getQueryParameters'])
            ->getMock();
        $validator->expects($this->once())
            ->method('getQueryParameters')
            ->with($this->equalTo($request))
            ->willReturn($config);

        $allowedNodeIds = [4,5,6,7];
            
        $role = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['getRoles'])
            ->getMock();
        $role->expects($this->once())
            ->method('getRoles')
            ->with($this->equalTo($config), $this->equalTo($allowedNodeIds))
            ->willReturn($foundRoles);

        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->setConstructorArgs([$request, $validator, $role])
            ->setMethods(['getValidator', 'getAllowedNodeIds' ])
            ->getMock();
        $roleService->expects($this->once())
            ->method('getAllowedNodeIds')
            ->willReturn($allowedNodeIds);

        // run
        $results = $roleService->getRoles();

        // post-run assertions
        $expectedResults = new ServiceResponse(ServiceResponse::SUCCESS, $foundRoles);
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: modifyRole sends unalias contents to handler
     */
    public function testModifyRoleSendsUnaliasContentsToHandler()
    {
        // mock
        $type = 'TheType';
        $action = 'anAction';
        $handlerResults = 'some_results';
        $request = new ServiceRequest($type, $action, null, null, null);

        $roleId = 2342342;
        $config = [
            'id' => $roleId
        ];
        
        $responseRecord = [
            'status' => true
        ];

        $validator = $this->getMockBuilder('Unified\Services\API\RequestValidators\RequestValidator')
            ->disableOriginalConstructor()
            ->setMethods(['unaliasContent'])
            ->getMock();
        $validator->expects($this->once())
            ->method('unaliasContent')
            ->with($this->equalTo($request))
            ->willReturn($config);

        $role = $this->getMockBuilder('Unified\Models\Role')
            ->disableOriginalConstructor()
            ->setMethods(['modifyRole'])
            ->getMock();
        $role->expects($this->once())
            ->method('modifyRole')
            ->with($this->equalTo($config))
            ->willReturn($responseRecord);

        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->setConstructorArgs([$request, $validator, $role])
            ->setMethods(['getValidator', 'checkRoleAccess'])
            ->getMock();
        $roleService->expects($this->once())
            ->method('checkRoleAccess')
            ->with($this->equalTo($roleId));

        // run
        $results = $roleService->modifyRole();

        // post-run assertions
        $expectedResults = new ServiceResponse(ServiceResponse::SUCCESS);
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: validate
     */
    public function testValidate()
    {
        // mock
        $type = 'TheType';
        $action = 'anAction';
        $request = new ServiceRequest($type, $action, null, null, null);

        $validationResults = 'validation_results';

        $validator = $this->getMockBuilder('Unified\Services\API\RequestValidators\RequestValidator')
            ->disableOriginalConstructor()
            ->setMethods(array('validate'))
            ->getMock();
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($request))
            ->willReturn($validationResults);

        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->setConstructorArgs(array($request, $validator, null))
            ->setMethods(array('getValidator', 'getHandler'))
            ->getMock();

        // run
        $results = $roleService->validate();

        // post-run assertions
        $this->assertEquals($validationResults, $results);
    }
    
    /**
     * test: checkWhitelistBlacklistAccess checks both lists together
     */
    public function testCheckWhitelistBlacklistAccessChecksBothListsTogether()
    {
        // param
        $whitelist = '.5.5000.';
        $blacklist = '.5003.';
        
        // mock
        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->disableOriginalConstructor()
            ->setMethods(array('checkNodeIdsAccess'))
            ->getMock();
        $roleService->expects($this->once())
            ->method('checkNodeIdsAccess')
            ->with($this->equalTo([5, 5000, 5003]));
        
        // run
        $roleService->checkWhitelistBlacklistAccess($whitelist, $blacklist);
    }
    
    /**
     * test: checkRoleAccess checks a role's white and black list
     */
    public function testCheckRoleAccessChecksARolesWhiteAndBlackList()
    {
        // params
        $roleId = 23;
        
        // mock
        $whitelist = '.5.5000.';
        $blacklist = '.5003.';
        $role = new \stdClass();
        $role->whitelist_node_ids = $whitelist;
        $role->blacklist_node_ids = $blacklist;
        
        $roleService = $this->getMockBuilder('Unified\Services\API\RoleService')
            ->disableOriginalConstructor()
            ->setMethods(array('checkNodeIdsAccess'))
            ->getMock();
        $roleService->expects($this->once())
            ->method('checkNodeIdsAccess')
            ->with($this->equalTo([5, 5000, 5003]));
        
        // run
        $roleService->checkWhitelistBlacklistAccess($whitelist, $blacklist);
    }
}
