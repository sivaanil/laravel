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

/**
 * Description of VirtualNodeIdProcessor
 *
 * @author Ira Fellows
 */
class VirtualDeviceTemplate extends ResourceProcessor
{
    public function __construct()
    {
        $description = [
            ResourceDescription::GET => [
                
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
        
        $virtualDeviceController = new \Unified\Http\Controllers\VirtualDeviceController();
        $content = $virtualDeviceController->getAllTemplates();
        
        return new OkResponse($content);
    }

}
