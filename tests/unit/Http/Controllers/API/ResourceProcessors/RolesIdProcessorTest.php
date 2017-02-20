<?php
namespace tests\unit\Http\Controllers\API\ResourceProcessors;

use Tests\unit\UnitTestCase;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceProcessors\ApiServiceRequest;
use Illuminate\Http\Request;

class RolesIdProcessorTest extends UnitTestCase
{
    /**
     * test: getServiceRequest
     */
    public function testGetServiceRequest()
    {
        // mock and params
        $nodeId = 234;
        $segment = 2;

        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->setMethods(['segment'])
            ->getMock();
        $request->expects($this->once())
            ->method('segment')
            ->with($segment)
            ->willReturn($nodeId);

        $requestParameters = $this->getMockBuilder('Unified\Http\Controllers\Api\RequestParameters')
            ->disableOriginalConstructor()
            ->setMethods(['getRequest', 'getSegment'])
            ->getMock();
        $requestParameters->expects($this->once())
            ->method('getRequest')
            ->willReturn($request);
        $requestParameters->expects($this->once())
            ->method('getSegment')
            ->willReturn($segment);

        $rolesIdProcessor = $this->getMockBuilder('Unified\Http\Controllers\Api\ResourceProcessors\RolesIdProcessor')
            ->disableOriginalConstructor()
            ->setMethods(['buildServiceRequest'])
            ->getMock();
        $rolesIdProcessor->expects($this->once())
            ->method('buildServiceRequest')
            ->with($this->equalTo($requestParameters), $this->equalTo($nodeId))
            ->willReturn('some_service_request_object');

        // run
        $results = $rolesIdProcessor->getServiceRequest($requestParameters);

        // post-run assertions
        $this->assertEquals('some_service_request_object', $results);
    }
}