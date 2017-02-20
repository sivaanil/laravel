<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Unified\Services\API\RequestValidators;

/**
 * Description of VirtualDeviceDescription
 *
 * @author Ira Fellows
 */
class VirtualDeviceDescription
{
    public function getVirtualDevicesValidator()
    {
        $description = [
            RequestValidator::OPTIONAL => ['nodeId' => 'nodeId'],
        ];
        $validator = new RequestValidator($description);
        $validator->validateFields();
        $validator->validateFilters();
        return $validator;
    }
    public function constructVirtualDeviceValidator()
    {
        $description = [
        RequestValidator::MANDATORY => [
            'device_structure' => 'device_structure',
            'parent_node_id' => 'parent_node_id'
        ],
            RequestValidator::OPTIONAL => ['nodeId' => 'nodeId'],
        ];
        $validator = new RequestValidator($description);
        $validator->validateFields();
        $validator->validateFilters();
        return $validator;
    }

}
