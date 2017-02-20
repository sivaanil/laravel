<?php

namespace Modules\Sensors\Http\Controllers;

use Unified\Http\Controllers\Api\ResourceProcessor;
use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\ResourceDescription;
use Input;

class ContactClosureProcessor extends ResourceProcessor {

    public function __construct() {
        $description = [
            ResourceDescription::GET => [],
        ];
        parent::__construct($description);
    }

    public function processRequest(RequestParameters $params) {

        // Get all of the contact closures under the parent node
        $parent_node = Input::get('parent_node');
        $controller = new SensorsController();
        $data = $controller->ccGridData($parent_node);
        return new OKResponse($data);
    }
}
