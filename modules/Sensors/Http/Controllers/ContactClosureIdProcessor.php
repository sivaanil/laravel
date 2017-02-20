<?php

namespace Modules\Sensors\Http\Controllers;

use Unified\Http\Controllers\Api\ResourceProcessor;
use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\ResourceDescription;

class ContactClosureIdProcessor extends ResourceProcessor {

    public function __construct() {
        $description = [
            ResourceDescription::GET => [],
            ResourceDescription::PUT => [],
        ];
        parent::__construct($description);
    }

    public function processRequest(RequestParameters $params) {
        $controller = new SensorsController();
        $id         = $params->getRequest()->segment($params->getSegment());

        switch ($params->getMethod()) {
            case ResourceDescription::GET:
                $data = $controller->getContactClosure($id);
                break;
            case ResourceDescription::PUT:
                $data = $controller->saveContactClosure($id);
                break;
        }

        return new OKResponse($data);
    }
}
