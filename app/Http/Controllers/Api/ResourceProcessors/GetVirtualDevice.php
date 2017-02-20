<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\ResourceProcessor;
use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\Response\NotFoundResponse;
use Log;

/**
 * API V1 Virtual Device processor
 *
 * @author Ira Fellows <ira.fellows@csquaredsystems.com>
 */
class GetVirtualDevice extends ResourceProcessor
{
    public function __construct()
    {
        $description = [
            ResourceDescription::GET => [
                ApiServiceRequest::API_SERVICE => "VirtualDevice",
                ApiServiceRequest::API_SERVICE_ACTION => "getVirtualDevice",
                ApiServiceRequest::CONTROL => [
                    "page",
                    "perPage"
                ],
                ApiServiceRequest::DEFAULT_FIELDS => [
                    "propId",
                    "value",
                    "created"
                ]
            ]
        ];
        parent::__construct($description);
    }

    public function processRequest(RequestParameters $rp)
    {
        $request = $rp->getRequest();
        $segment = $rp->getSegment();
        $targetVirtualDevice = $request->segment($segment);
        $params = $rp->getParameters();
        if (!array_key_exists('fields', $params)) {
            $displayOps = [];
        } else {
            $rawDisplay = $params['fields'];
            $displayOps = is_string($rawDisplay) ? explode(",", $rawDisplay) : [];
        }
        $content = $this->getVirtualDeviceById($targetVirtualDevice, $displayOps);
        if ($content == null) {
            return new NotFoundResponse("VirtualDeviceID '$targetVirtualDevice' does not exist");
        } else {
            return new OkResponse($content);
        }
    }

    private function getVirtualDeviceById($targetVirtualDevice, $params) {

        $viewDeviceByIdController = new \Unified\Http\Controllers\VirtualDeviceController();
        $content = $viewDeviceByIdController->virtualDeviceData($targetVirtualDevice, $params);
        return $content;
    }
}
