<?php

namespace Unified\Services\API;

use Exception;
use Auth;
use Unified\Models\NetworkTreeMap;
use Unified\Services\API\ServiceRequest;
use Unified\Services\API\ServiceResponse;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\RequestValidators\ValidationException;

/**
 * Parent class for all API services
 *
 * @author Anthony Levensalor <anthony.levensalor@csquaredsystems.com>
 */
abstract class APIService
{
    CONST ACCESS_DENIED_ERROR_MESSAGE = 'access_denied';
    
    /** @var ServiceRequest Service request information */
    protected $request = null;
    /** @var RequestValidator RequestValidator object (null if validation is not necessary) */
    protected $validator = null;
    
    /**
     * Standard method to create, handle, and respond to a request.
     * 
     * @param ServiceRequest $request
     *            the service request to execute
     *            
     * @return ServiceResponse object
     *
     */
    public static function process(ServiceRequest $request)
    {
        // Instantiate service
        $classname = $request->getServicePath() . $request->getType () . "Service";
        $instance = new $classname ( $request );
        
        try {
            // Validate request parameterrs 
            $validationResults = $instance->validate ();
            if (! empty ( $validationResults )) {
                return new ServiceResponse ( ServiceResponse::BAD_REQUEST, $validationResults );
            }
            
        } catch ( ValidationException $ve ) {
            return (new ServiceResponse ( ServiceResponse::BAD_REQUEST,
                    [ "Request validation error: {$ve->getMessage()}" ] ));
        }
            
        // Execute action
        return $instance->execute ();
    }
    
    /**
     * @param ServiceRequest $request
     *            Service request
     */
    public function __construct(ServiceRequest $request, RequestValidator $validator = null) 
    {
        if (empty($request)) {
            throw new Exception ( "Invalid request" );
        }
        
        $this->request = $request;
        $this->validator = ($validator == null) ? RequestValidator::getDefaultValidator($request) : $validator;
    }
    
    final private function execute()
    {
        $action =  $this->request->getAction();
        if (is_callable ( array ( $this, $action) )) {
            
            // Check if object based on ServiceResponse is returned from action
            $response = $this->$action();
            if (empty ( $response ) || ! ($response instanceof ServiceResponse)) {
                return new ServiceResponse ( ServiceResponse::INTERNAL_ERROR, $response );
            }
            return $response;
            
        } else {
            throw new Exception ( "Invalid action ".get_class($this).'::'.$this->request->getAction() );
        }
    }
    
    /**
     * isUserAC2Admin
     * 
     * Is the user a C2Admin?
     */
    public function isUserAC2Admin($user)
    {
        return ($user->role == 'Administrator');
    }
    
    /**
     * verifyC2AdminAccess
     */
    protected function verifyC2AdminAccess()
    {
        $user = Auth::user();
            
        if (!$this->isUserAC2Admin($user)) {
            $privateErrorMessage =  'User ' . $user->first_name. ' ' . $user->last_name . ' (id: ' . $user->id . ') attempted to access a C2Admin-only call.';
            throw new AppException(self::ACCESS_DENIED_ERROR_MESSAGE, $privateErrorMessage, ServiceResponse::FORBIDDEN);
        }
    }

    /**
     * getAllowedNodeIds
     *
     * TODO: when we switch over to using /roles, instead of using the user home_node_ids, use the role white/black-list.
     */
    public function getAllowedNodeIds()
    {
        $whitelistNodeIds = [
            Auth::user()->home_node_id
        ];
        $blacklistNodeIds = [];
    
        $networkTreeMap = new NetworkTreeMap();
    
        return $networkTreeMap->getNodesAccessibleByHomeNodeIds($whitelistNodeIds, $blacklistNodeIds);
    }

    /**
     * checkNodeIdsAccess
     */
    public function checkNodeIdsAccess($listNodeIds)
    {
        $deniedNodeIds = array_diff($listNodeIds, $this->getAllowedNodeIds());
        
        if ($deniedNodeIds !== []) {
            \Log::error(__METHOD__ . ': requested node ids\' access denied to user. Denied id(s): ' . implode(', ', $deniedNodeIds));
            throw new \Exception ('denied_node_access');
        }
    } 
    
    /**
     * Validates request.
     * @return error list or empty list on success
     */
    public function validate() {
        return $this->validator->validate ( $this->request );
    }

    /**
     * Returns unaliased HTTP GET query parameters (fields, filters, control)
     * @return \Unified\Http\Helpers\QueryParameters|NULL
     */
    final public function getQueryParameters() {
        return $this->validator->getQueryParameters($this->request);
    }
    
    /**
     * Returns unaliased HTTP POST|PUT body content
     */
    final public function getContent() {
        return $this->validator->getContent($this->request);
    }
    
}
