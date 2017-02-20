<?php
namespace Unified\Services\API;

use Unified\Services\API\ServiceResponse;
use Unified\Models\PermissionFactory;
use Unified\Models\AclRolePermissionFactory;
use Unified\Models\AppException;
use \Exception;

class PermissionService extends APIService
{
    /**
     * construct
     */
    public function __construct($request)
    {
        parent::__construct($request, null);
    }
    
    /**
     * addPermissions
     */
    public function add()
    {
        try {
            // make sure user is a C2Admin
            $this->verifyC2AdminAccess();

            // add a new permission to the system
            $data = $this->request->getData();
            $content = $data['content'];

            $permissionId = PermissionFactory::getInstance()->upsert($content['slug'], $content['title'], $content['description']);

            // respond with the permission id (may be new, may be a reclaimed deleted permission id)
            $response = $this->buildServiceResponse(ServiceResponse::SUCCESS, ['permission_id' => $permissionId]);

        } catch (AppException $e) {
            $response = $e->toServiceResponse();
        } catch (Exception $e) {
            $response = $this->buildServiceResponse(ServiceResponse::INTERNAL_ERROR, ['error' => $e->getMessage()]);
        }
        
        return $response;
    }
    
    /**
     * delete
     */
    public function delete()
    {
        try {
            // make sure user is a C2Admin
            $this->verifyC2AdminAccess();

            // mark the permission as deleted
            $data = $this->request->getData();
            $content = $data['content'];
            
            $permissionSlug = PermissionFactory::getInstance()->remove($content['permission_id']);
            
            // set response
            $response = $this->buildServiceResponse(ServiceResponse::SUCCESS);

        } catch (AppException $e) {
            $response = $e->toServiceResponse();
        } catch (Exception $e) {
            $response = $this->buildServiceResponse(ServiceResponse::INTERNAL_ERROR, ['error' => $e->getMessage()]);
        }
        
        return $response;
    }
    
    /**
     * modify
     */
    public function modify()
    {
        try {
            // make sure use is a C2Admin
            $this->verifyC2AdminAccess();

            // update the permission
            $data = $this->request->getData();
            $content = $data['content'];

            PermissionFactory::getInstance()->modify($content['permission_id'], $content);

            // set response
            $response = $this->buildServiceResponse(ServiceResponse::SUCCESS);
            
        } catch (AppException $e) {
            $response = $e->toServiceResponse();
        } catch (Exception $e) {
            $response = $this->buildServiceResponse(ServiceResponse::INTERNAL_ERROR, ['error' => $e->getMessage()]);
        }
        
        return $response;
    }
    
    /**
     * getPermissions
     */
    public function getPermissions()
    {
        // get "since" if present
        $data = $this->request->getData();
        $since = (isset($data['control']['since']) && is_numeric($data['control']['since'])) 
            ? $data['control']['since'] : null;
        
        // get permission records
        $permissionRecords = PermissionFactory::getInstance()->getAllSince($since);

        // return response
        return $this->buildServiceResponse(ServiceResponse::SUCCESS, ['permissions' => $permissionRecords]);
    }

    /**
     * validate
     */
    public function validate()
    {
        return;
    }
    
    /**
     * buildServiceResponse
     *
     * Build a service response for the given response data.
     */
    public function buildServiceResponse($responseStatus, $data = [])
    {
        return new ServiceResponse($responseStatus, $data);
    }
}