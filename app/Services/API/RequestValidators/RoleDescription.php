<?php
namespace Unified\Services\API\RequestValidators;

class RoleDescription
{
    private $fields = null;

    /**
     * construct
     */
    public function __construct()
    {
        $this->fields = new RoleFields();
    }

    /**
     * addRoleValidator
     */
    public function addRoleValidator()
    {
        $description = [
            RequestValidator::MANDATORY => $this->fields->getAddRoleParameters()
        ];

        return new RequestValidator($description);
    }

    /**
     * deleteRoleValidator
     */
    public function deleteRoleValidator()
    {
        $description = [
            RequestValidator::MANDATORY => ['id' => 'id']
        ];
        $validator = new RequestValidator($description);
        $validator->validateContent();
        return $validator;
    }
    
    /**
     * getRoleByIdValidator
     * 
     * @return \Unified\Services\API\RequestValidators\RequestValidator
     */
    public function getRoleByIdValidator() {
        $description = [
                RequestValidator::OPTIONAL => $this->fields->getRolesParameters()
        ];
        $validator = new RequestValidator($description);
        $validator->validateFields();
        return $validator;
    }

    /**
     * getRoleValidator
     */
    public function getRolesValidator()
    {
        $description = [
            RequestValidator::OPTIONAL => $this->fields->getRolesParameters()
        ];
        $validator = new RequestValidator($description);
        $validator->validateFilters();
        return $validator;
    }

    /**
     * modifyRole
     */
    public function modifyRoleValidator()
    {
        $description = [
            RequestValidator::MANDATORY => ['id' => 'id'],
            RequestValidator::OPTIONAL => $this->fields->getOptionalModifiedRoleParameters()
        ];

        return new RequestValidator($description);
    }
}