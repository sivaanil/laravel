<?php
namespace tests\unit\Http\Controllers\API\ResourceProcessors;

use Tests\unit\UnitTestCase;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceProcessors\ApiServiceRequest;

class RolesProcessorTest extends UnitTestCase
{
    /**
     * test: getServiceRequest
     */
    public function testGetServiceRequest()
    {
        // param
        $methodName = 'aMethodName';
        $builtServiceRequest = 'a_built_service_request';

        $requestParameters = $this->getMockBuilder('Unified\Http\Controllers\Api\RequestParameters')
            ->disableOriginalConstructor()
            ->setMethods(['getMethod'])
            ->getMock();
        $requestParameters->expects($this->any())
            ->method('getMethod')
            ->willReturn($methodName);

        // mock

        // Note: serviceRequest's fromRequest method is not mocked
        // due to being set "final"
        $serviceRequest = $this->getMockBuilder('Unified\Http\Controllers\Api\ServiceRequest')
            ->disableOriginalConstructor()
            ->setMethods(['build', 'fromRequest'])
            ->getMock();
        $serviceRequest->expects($this->once())
            ->method('build')
            ->willReturn($builtServiceRequest);

        $rolesProcessor = $this->getMockBuilder('Unified\Http\Controllers\Api\ResourceProcessors\RolesProcessor')
            ->setMethods(['generateServiceRequest', 'getApiService', 'getApiServiceAction'])
            ->getMock();
        $rolesProcessor->expects($this->once())
            ->method('generateServiceRequest')
            ->with($this->equalTo($methodName))
            ->willReturn($serviceRequest);
        
        // run
        $results = $rolesProcessor->getServiceRequest($requestParameters);

        // post-run assertions
        $this->assertEquals($builtServiceRequest, $results);
    }
}