<?php
namespace tests\unit\Services\Api;

use Tests\unit\UnitTestCase;
use Unified\Services\API\ApiService;
use Unified\Services\API\ServiceRequest;
use Unified\Services\API\ServiceResponse;
use Unified\Models\PermissionFactory;
use Unified\Models\AclRolePermissionFactory;
use Unified\Models\AppException;
use \Exception;

class PermissionServiceTest extends UnitTestCase
{
    //==============================================================================================
    //
    // test: add method
    //
    
    /**
     * test: add returns success response if permission is added without any issues
     */
    public function testAddReturnsSuccessReponseIfPermissionIsAddedWithoutAnyIssues()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $title = 'Sir';
        $slug = 'ew_slugs_are_gross';
        $description = 'Has a big nose.';
        
        $content = [
            'title' => $title,
            'description' => $description,
            'slug' => $slug
        ];

        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);

        // mock
        $permissionId = 123123;
        
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['upsert'])
            ->getMock();
        $permissionFactory->expects($this->once())
            ->method('upsert')
            ->with(
                $this->equalTo($slug),
                $this->equalTo($title),
                $this->equalTo($description)
            )
            ->willReturn($permissionId);
        PermissionFactory::setInstance($permissionFactory);
        
        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['generateErrorResponse', 'buildServiceResponse', 'verifyC2AdminAccess'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess');
        $service->expects($this->never())
            ->method('generateErrorResponse');
        $service->expects($this->once())
            ->method('buildServiceResponse')
            ->with(
                $this->equalTo(ServiceResponse::SUCCESS),
                $this->equalTo(['permission_id' => $permissionId])
            )
            ->willReturn('successful_response');
        
        // run
        $results = $service->add();
        
        // post-run assertions
        $this->assertEquals('successful_response', $results);
    }

    /**
     * test: add returns error response if permission fails to be added
     */
    public function testAddReturnsErrorReponseIfPermissionFailsToBeAdded()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $title = 'Sir';
        $slug = 'ew_slugs_are_gross';
        $description = 'Has a big nose.';
        
        $content = [
            'title' => $title,
            'description' => $description,
            'slug' => $slug
        ];
        
        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);

        // mock
        $publicErrorMessage = 'error_occurred';
        $privateErrorMessage = 'AAAAHHHH!!!! *splut*';
        $serviceResponseStatus = 'ugh';
        $exception = new Exception($publicErrorMessage);
        
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['upsert'])
            ->getMock();
        $permissionFactory->expects($this->once())
            ->method('upsert')
            ->with(
                $this->equalTo($slug),
                $this->equalTo($title),
                $this->equalTo($description)
            )
            ->willThrowException($exception);
        PermissionFactory::setInstance($permissionFactory);

        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['verifyC2AdminAccess', 'buildServiceResponse'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess');
        $service->expects($this->once())
            ->method('buildServiceResponse')
            ->with(
                $this->equalTo(ServiceResponse::INTERNAL_ERROR)
            )
            ->willReturn('failure_response');
        
        // run
        $results = $service->add();
        
        // post-run assertions
        $this->assertEquals('failure_response', $results);
    }
    
    /**
     * test: add returns error response if access is denied
     */
    public function testAddReturnsErrorResponseIfAccessIsDenied()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $title = 'Sir';
        $slug = 'ew_slugs_are_gross';
        $description = 'Has a big nose.';
        
        $content = [
            'title' => $title,
            'description' => $description,
            'slug' => $slug
        ];
        
        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);

        // mock
        $validator = $this->getMockBuilder('Unified\Services\API\RequestValidators\RequestValidator')
            ->disableOriginalConstructor()
            ->setMethods(['unaliasContent'])
            ->getMock();
        
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['upsert'])
            ->getMock();
        $permissionFactory->expects($this->never())
            ->method('upsert');
        PermissionFactory::setInstance($permissionFactory);
        
        $errorMessage = ApiService::ACCESS_DENIED_ERROR_MESSAGE;
        $exception = new AppException($errorMessage);

        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['verifyC2AdminAccess', 'buildServiceResponse'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess')
            ->willThrowException($exception);
        
        // run
        $results = $service->add();
        
        // post-run assertions
        $this->assertEquals($exception->toServiceResponse(), $results);
    }

    //==============================================================================================
    //
    // test: delete method
    //
    
    /**
     * test: delete returns success response if permission is removed without any issues
     */
    public function testDeleteReturnsSuccessResponseIfPermissionIsRemovedWithoutAnyIssues()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $permissionId = 123123123;
        
        $content = [
            'permission_id' => $permissionId
        ];

        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);

        // mock
        $permissionSlug = 'a_slug';
        
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['remove'])
            ->getMock();
        $permissionFactory->expects($this->once())
            ->method('remove')
            ->with(
                $this->equalTo($permissionId)
            )
            ->willReturn($permissionSlug);
        PermissionFactory::setInstance($permissionFactory);
        
        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['buildServiceResponse', 'verifyC2AdminAccess'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess');
        $service->expects($this->once())
            ->method('buildServiceResponse')
            ->with(
                $this->equalTo(ServiceResponse::SUCCESS)
            )
            ->willReturn('successful_response');
        
        // run
        $results = $service->delete();
        
        // post-run assertions
        $this->assertEquals('successful_response', $results);
    }

    /**
     * test: delete returns error response if permission fails to be deleted
     */
    public function testDeleteReturnsErrorReponseIfPermissionFailsToBeDeleted()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $permissionId = 123123123;
        
        $content = [
            'permission_id' => $permissionId
        ];

        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);
    
        // mock
        $errorMessage = 'AAAAHHHH!!!! *splut*';
        $exception = new Exception($errorMessage);

        $aclRolePermissionFactory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['removePermissionSlug'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->never())
            ->method('removePermissionSlug');
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
        
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['remove'])
            ->getMock();
        $permissionFactory->expects($this->once())
            ->method('remove')
            ->with(
                $this->equalTo($permissionId)
            )
            ->willThrowException($exception);
        PermissionFactory::setInstance($permissionFactory);

        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['verifyC2AdminAccess', 'buildServiceResponse'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess');
        $service->expects($this->once())
            ->method('buildServiceResponse')
            ->with(
                $this->equalTo(ServiceResponse::INTERNAL_ERROR),
                $this->equalTo(['error' => $errorMessage])
            )
            ->willReturn('failure_response');
        
        // run
        $results = $service->delete();
        
        // post-run assertions
        $this->assertEquals('failure_response', $results);
    }
    
    /**
     * test: delete returns error response if access is denied
     */
    public function testDeleteReturnsErrorResponseIfAccessIsDenied()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $permissionId = 123123123;
        
        $content = [
            'id' => $permissionId
        ];

        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);
    
        // mock
        $aclRolePermissionFactory = $this->getMockBuilder('Unified\Models\AclRolePermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['removePermissionSlug'])
            ->getMock();
        $aclRolePermissionFactory->expects($this->never())
            ->method('removePermissionSlug');
        AclRolePermissionFactory::setInstance($aclRolePermissionFactory);
            
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['remove'])
            ->getMock();
        $permissionFactory->expects($this->never())
            ->method('remove');
        PermissionFactory::setInstance($permissionFactory);
        
        $errorMessage = ApiService::ACCESS_DENIED_ERROR_MESSAGE;
        $exception = new AppException($errorMessage);

        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['verifyC2AdminAccess', 'buildServiceResponse'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess')
            ->willThrowException($exception);
        
        // run
        $results = $service->delete();
        
        // post-run assertions
        $this->assertEquals($exception->toServiceResponse(), $results);
    }

    //==============================================================================================
    //
    // test: getPermission method
    //

    /**
     * test: getPermissions without since unixtime provided
     */
    public function testGetPermissionsWithoutSinceUnixtimeProvided()
    {
        // param
        $type = 'a_type';
        $action = 'an_action';
        $modelData = [
            'control' => []
        ];
        $request = new ServiceRequest($type, $action, $modelData, null, null);

        // mock
        $permissionRecords = [
            'a_stub' => [
                'stub' => 'a_stub'
            ],
            'b_stub' => [
                'stub' => 'b_stub'
            ]
        ];
        
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['getAllSince'])
            ->getMock();
        $permissionFactory->expects($this->once())
            ->method('getAllSince')
            ->with($this->equalTo(null))
            ->willReturn($permissionRecords);
        PermissionFactory::setInstance($permissionFactory);
        
        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['buildServiceResponse'])
            ->getMock();
        $service->expects($this->once())
            ->method('buildServiceResponse')
            ->with(
                $this->equalTo(ServiceResponse::SUCCESS),
                $this->equalTo([
                    'permissions' => $permissionRecords
                ])
             )
             ->willReturn('a_service_response');
        
        // run
        $results = $service->getPermissions();
        
        // post-run assertions
        $this->assertEquals('a_service_response', $results);
    }
    
    /**
     * test: getPermissions with since unixtime provided
     */
    public function testGetPermissionsWithSinceUnixtimeProvided()
    {
        // param
        $since = 1500000000;
        
        $type = 'a_type';
        $action = 'an_action';
        $modelData = [
            'control' => [
                'since' => $since
            ]
        ];
        $request = new ServiceRequest($type, $action, $modelData, null, null);

        // mock
        $permissionRecords = [
            'a_stub' => [
                'stub' => 'a_stub'
            ],
            'b_stub' => [
                'stub' => 'b_stub'
            ]
        ];
        
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['getAllSince'])
            ->getMock();
        $permissionFactory->expects($this->once())
            ->method('getAllSince')
            ->with($this->equalTo($since))
            ->willReturn($permissionRecords);
        PermissionFactory::setInstance($permissionFactory);
        
        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['buildServiceResponse'])
            ->getMock();
        $service->expects($this->once())
            ->method('buildServiceResponse')
            ->with(
                $this->equalTo(ServiceResponse::SUCCESS),
                $this->equalTo([
                    'permissions' => $permissionRecords
                ])
             )
             ->willReturn('a_service_response');
        
        // run
        $results = $service->getPermissions();
        
        // post-run assertions
        $this->assertEquals('a_service_response', $results);
    }

    //==============================================================================================
    //
    // test: modify method
    //
    
    /**
     * test: modify returns success response if permission is modified without any issues
     */
    public function testModifyReturnsSuccessResponseIfPermissionIsModifiedWithoutAnyIssues()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $permissionId = 123123123;
        
        $content = [
            'permission_id' => $permissionId,
            'title' => 'How to Avoid Huge Ships'
        ];

        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);

        // mock
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['modify'])
            ->getMock();
        $permissionFactory->expects($this->once())
            ->method('modify')
            ->with(
                $this->equalTo($permissionId)
            );
        PermissionFactory::setInstance($permissionFactory);
        
        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['generateErrorResponse', 'buildServiceResponse', 'verifyC2AdminAccess'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess');
        $service->expects($this->never())
            ->method('generateErrorResponse');
        $service->expects($this->once())
            ->method('buildServiceResponse')
            ->with(
                $this->equalTo(ServiceResponse::SUCCESS)
            )
            ->willReturn('successful_response');
        
        // run
        $results = $service->modify();
        
        // post-run assertions
        $this->assertEquals('successful_response', $results);
    }

    /**
     * test: modify returns error response if permission fails to be modified
     */
    public function testModifyReturnsErrorReponseIfPermissionFailsToBeModified()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $permissionId = 123123123;
        
        $content = [
            'permission_id' => $permissionId
        ];

        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);
    
        // mock
        $errorMessage = 'AAAAHHHH!!!! *splut*';
        $exception = new Exception($errorMessage);
        
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['modify'])
            ->getMock();
        $permissionFactory->expects($this->once())
            ->method('modify')
            ->with(
                $this->equalTo($permissionId)
            )
            ->willThrowException($exception);
        PermissionFactory::setInstance($permissionFactory);

        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['verifyC2AdminAccess', 'buildServiceResponse'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess');
        $service->expects($this->once())
            ->method('buildServiceResponse')
            ->with(
                $this->equalTo(ServiceResponse::INTERNAL_ERROR),
                $this->equalTo(['error' => $errorMessage])
            )
            ->willReturn('failure_response');
        
        // run
        $results = $service->modify();
        
        // post-run assertions
        $this->assertEquals('failure_response', $results);
    }
    
    /**
     * test: modify returns error response if access is denied
     */
    public function testModifyReturnsErrorResponseIfAccessIsDenied()
    {
        // params (sent into construct)
        $type = 'a_type';
        $action = 'an_action';
        
        $permissionId = 123123123;
        
        $content = [
            'id' => $permissionId
        ];

        $requestData = [
            'content' => $content
        ];
        $request = new ServiceRequest($type, $action, $requestData, null, null);
    
        // mock
        $permissionFactory = $this->getMockBuilder('Unified\Models\PermissionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['modify'])
            ->getMock();
        $permissionFactory->expects($this->never())
            ->method('modify');
        PermissionFactory::setInstance($permissionFactory);
        
        $errorMessage = ApiService::ACCESS_DENIED_ERROR_MESSAGE;
        $exception = new AppException($errorMessage);

        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->setConstructorArgs([$request])
            ->setMethods(['verifyC2AdminAccess', 'buildServiceResponse'])
            ->getMock();
        $service->expects($this->once())
            ->method('verifyC2AdminAccess')
            ->willThrowException($exception);
        
        // run
        $results = $service->modify();
        
        // post-run assertions
        $this->assertEquals($exception->toServiceResponse(), $results);
    }

    //==============================================================================================
    //
    // test: build service response
    //
    
    /**
     * test: buildServiceResponse returns response
     */
    public function testBuildServiceResponseReturnsResponse()
    {
        // params
        $status = 'a_status';
        $message = 'a_message';
        
        // mock
        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->disableOriginalConstructor()
            ->setMethods(['dummy'])
            ->getMock();
        
        // run
        $results = $service->buildServiceResponse($status, $message);
        
        // post-run assertions
        $expectedResults = new ServiceResponse($status, $message);
        $this->assertEquals($expectedResults, $results);
    }

    //==============================================================================================
    //
    // test: validate
    //
    
    /**
     * test: validate exists
     */
    public function testValidateExists()
    {
        // mock
        $service = $this->getMockBuilder('Unified\Services\API\PermissionService')
            ->disableOriginalConstructor()
            ->setMethods(['dummy'])
            ->getMock();
        
        // run
        $service->validate();
    }
}