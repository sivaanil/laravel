<?php

namespace Unified\Services\API;

use Auth;
use DB;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceResponse;
use Illuminate\Support\Facades\Log;

/**
 * Handles VirtualDevice related functions for the API.
 */
class VirtualDeviceService extends APIService
{

    /**
     * Virtual Device Service constructor.
     *
     * @param ServiceRequest $request
     *            Service request
     */
    public function __construct(ServiceRequest $request)
    {
        parent::__construct($request, RequestValidator::getValidator($request->getType(), $request->getAction()));
    }

    /**
     * Return list of virtual devices
     *
     * @return Service response object with the following status codes:
     */
    public function getVirtualDevices()
    {
        // This is HTTP GET Request, so in order to reach GET parameters
        // we need to use the following finction:
        $params = $this->getQueryParameters();
        // Provided QueryParameters object allow to reach the following get QUERY parameters:
        // Filers: arrays of the arrays [[0]-field name, [1]- condition, [2] - value]
        $filters = $params->getFilters(); 
        // Fields: array of requested fields in format [UnaliasedDataVariable as userFriendlyName]
        $fields = $params->getFields();
        // sortby: array of sortBy condition  [[0]-field name, [1]- sort condition (ASC/DESC)]
        $sortBy = $params->getSortby();
        // Controle parameters is all other parameters besides filters, fields, and sortBy
        // Usually they include pagination parameters and some global parameters, 
        // such as "count" (to return total count of the available records)
        $control = $params->getControl();


        $controller = new \Unified\Http\Controllers\VirtualDeviceController();
        $virtualDevices = [];
        $virtualDevices[] = $controller->virtualDeviceData(7);
        $virtualDevices[] = $filters;
        //$virtualDevices[] = $fields;
        //$virtualDevices[] = $control;


        return new ServiceResponse(ServiceResponse::SUCCESS, $virtualDevices);
    }

    public function constructVirtualDevice()
    {
        Log::debug("made it to constructor");
        $filters = $this->validator->unaliasFilters($this->request);
        $fields = $this->validator->unaliasFields($this->request);
        $control = $this->request->getDataParameter("control");
    
        $receiveThis = "VirtualDeviceRequest::Filters=" . print_r($filters, true)
        . "Control=" . print_r($control, true) . "Fields=" . print_r($fields, true);
        return new ServiceResponse(ServiceResponse::SUCCESS, ['data' => 'OK', 'andthis:' => 
            print_r($this->validator->unaliasContent( $this->request ), true) , 'received' => $receiveThis,
            'request' => json_encode($this->request)]);
    }

}
