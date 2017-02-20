<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\ResourceProcessor;
use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\Response\ErrorResponse;
use Unified\Http\Controllers\Api\Response\NotFoundResponse;
use Log;

/**
 * API V1 Virtual Device processor
 *
 * @author Ira Fellows <ira.fellows@csquaredsystems.com>
 */
final class VirtualDeviceProcessor extends /* ApiServiceRequest */ ResourceProcessor
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
        $targetVirtualDevice = $request->segment($segment - 1);

        $targetOperation = $request->segment($segment); 
        Log::debug("Entered routing with targetOp=$targetOperation");
        try {

            switch ($targetOperation)  {
            case "poll-for-alarms": {
                $content = $this->pollForAlarms($targetVirtualDevice);
                return new OkResponse(['alarms' => $content]);
            }
            case 'write-device-settings': {
                $postData = $request->only(['property_definition', 'new_setting']);
                $content = $this->writeDeviceSetting($targetVirtualDevice, $postData);
                return new OkResponse(['results' => $content]);
            }
            default:
                return new NotFoundResponse("The operation specified: '". $targetOperation ."' is not implemented");
            }
        } catch(\Exception $e) {
            return new ErrorResponse("Encountered error while processing request. Error: " . ((string) $e));
        }
    }



    //function that will get the supplied virtual device and all of its children and find any property values that exceed thresholds
    private function pollForAlarms($virtualDeviceId) {
        $controller = new \Unified\Http\Controllers\VirtualDeviceController();
        $results = $controller->pollForAlarms($virtualDeviceId);
        return $results;
    }

    private function writeDeviceSetting($virtualDeviceId, $newSettingData) {
        $controller = new \Unified\Http\Controllers\VirtualDeviceController();
        $results = $controller->writeDeviceSetting($virtualDeviceId, $newSettingData);
        return $results;
    }

}
