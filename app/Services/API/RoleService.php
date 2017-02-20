<?php
namespace Unified\Services\API;

use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceResponse;
use Unified\Models\Role;

class RoleService extends APIService
{
    private $allowedNodeIds = null;
    private $model = null;

    /**
     * construct
     */
    public function __construct($request, $validator = null, $model = null)
    {
        $this->model = (is_null($model)) ? new Role() : $model;

        $validator = ($validator == null)
            ? RequestValidator::getValidator($request->getType(), $request->getAction())
            : $validator;

        // sets $this->request and $this->validator
        parent::__construct($request, $validator);
    }

    /**
     * addRole
     */
    public function addRole()
    {
        $data = $this->request->getData();

        $permissions = [];
        if (isset($data['content']['permissions'])) {
            $permissions = $data['content']['permissions'];
            unset($data['content']['permissions']);
            $this->request->setData($data);
        }

        $content = $this->validator->unaliasContent($this->request);
        $content['permissions'] = $permissions;

        $this->checkWhitelistBlacklistAccess($content['whitelist_node_ids'], $content['blacklist_node_ids']);
        $responseRecord = $this->model->addRole($content);
        
        return (isset($responseRecord['status']) && $responseRecord['status'])
            ? new ServiceResponse(ServiceResponse::SUCCESS, ['roleId' => $responseRecord['roleId']])
            : new ServiceResponse(ServiceResponse::UNPROCESSABLE_ENTITY, ['error' => $responseRecord['error']]);
    }

    /**
     * deleteRole
     */
    public function deleteRole()
    {
        $content = $this->validator->unaliasContent($this->request);
        
        $this->checkRoleAccess($content['id']);
        
        $responseRecord = $this->model->deleteRole($content);

        return (isset($responseRecord['status']) && $responseRecord['status'])
            ? $this->buildServiceResponse(ServiceResponse::SUCCESS)
            : $this->buildServiceResponse(ServiceResponse::UNPROCESSABLE_ENTITY, ['error' => $responseRecord['error']]);
    }

    /**
     * getRoleById
     */
    public function getRoleById()
    {
        $config = $this->validator->getQueryParameters($this->request);
        
        $roles = $this->model->getRoles($config, $this->getAllowedNodeIds());
        
        return ($roles['roles'] == array())
            ? $this->buildServiceResponse(ServiceResponse::NOT_FOUND)
            : $this->buildServiceResponse(ServiceResponse::SUCCESS, $roles);
    }

    /**
     * getRoles
     *
     * Return list of roles
     */
    public function getRoles()
    {
        $queryParameters = $this->validator->getQueryParameters($this->request);

        $roles = $this->model->getRoles($queryParameters, $this->getAllowedNodeIds());
        
        return $this->buildServiceResponse(ServiceResponse::SUCCESS, $roles);
    }

    /**
     * modifyRole
     */
    public function modifyRole()
    {
        $data = $this->request->getData();
        $permissions = null;
        if (isset($data['content']['permissions'])) {
            $permissions = $data['content']['permissions'];
            unset($data['content']['permissions']);
            $this->request->setData($data);
        }
        $content = $this->validator->unaliasContent($this->request);
        
        if ($permissions != null) {
            $content['permissions'] = $permissions;
        }    
        
        $this->checkRoleAccess($content['id']);
        $responseRecord = $this->model->modifyRole($content);
        
        return (isset($responseRecord['status']) && $responseRecord['status'])
            ? new ServiceResponse(ServiceResponse::SUCCESS)
            : new ServiceResponse(
                    ServiceResponse::UNPROCESSABLE_ENTITY,
                    ['error' => $responseRecord['error']]
                );
    }

    /**
     * validate
     */
    public function validate()
    {
        return $this->validator->validate($this->request);
    }
    
    /*
     * buildServiceResponse
     *
     * Build a service response for the given response data.
     *
     * Note: public to allow unit test mocking
     */
    public function buildServiceResponse($responseStatus, $data = [])
    {
        RequestValidator::structurizeObject($data);
        return new ServiceResponse($responseStatus, $data);
    }

    /**
     * checkWhitelistBlacklistAccess
     */
    public function checkWhitelistBlacklistAccess($whitelist, $blacklist)
    {
        $whitelistNodeIds = array_filter(explode('.', $whitelist));
        $blacklistNodeIds = array_filter(explode('.', $blacklist));
        
        $listNodeIds = array_merge($whitelistNodeIds, $blacklistNodeIds);
        
        $this->checkNodeIdsAccess($listNodeIds);
    }

    /**
     * checkRoleAccess
     */
    public function checkRoleAccess($roleId)
    {
        $role = $this->model->findById($roleId);
        
        $this->checkWhitelistBlacklistAccess($role->whitelist_node_ids, $role->blacklist_node_ids);
    }
}
