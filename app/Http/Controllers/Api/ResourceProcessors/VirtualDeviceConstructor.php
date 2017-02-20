<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Unified\Http\Controllers\Api\ResourceProcessors;
use Unified\Http\Controllers\Api\RequestParameters;
//use Unified\Http\Controllers\Api\ServiceRequestBuilder;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\ResourceProcessor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * Description of VirtualNodeIdProcessor
 *
 * @author Ira Fellows
 */
class VirtualDeviceConstructor extends /*ApiServiceRequest too complex!*/ ResourceProcessor
{
    public function __construct()
    {
        $description = [
            ResourceDescription::POST => [
                ApiServiceRequest::API_SERVICE => 'VirtualDevice',
                ApiServiceRequest::API_SERVICE_ACTION => 'constructVirtualDevice',
                ApiServiceRequest::CONTROL => [
                    'parent_node_id'
                ],
                ApiServiceRequest::DEFAULT_FIELDS => [
                    'id',
                    'name'
                ]
            ]
        ];
        parent::__construct($description);
    }

    public function getServiceRequest(RequestParameters $rp)
    {
        // Build service request objest
        /*$sr = new ServiceRequestBuilder($this->getApiService($rp->getMethod()), $this->getApiServiceAction($rp->getMethod()));
        $sr->fromRequest($rp, $this->getDescription());

        return $sr->build();*/
        
    }

    public function processRequest(RequestParameters $request)
    {
        $parameters = $request->getRequest()->all();
        $validator = Validator::make($parameters, [
            'parent_node_id' => 'required|Integer|min:1',
            'device_structure' => 'required|string'
        ]);
        if($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            throw new \InvalidArgumentException($errorMessage);
        }
        
        $parentNodeId = $parameters['parent_node_id'];
        $deviceStructure = json_decode($parameters['device_structure'], true);
        
        $virtualDeviceController = new \Unified\Http\Controllers\VirtualDeviceController();
        $content = $virtualDeviceController->buildVirtualDevice($parentNodeId, $deviceStructure);
        return new OkResponse($content);
    }

}
