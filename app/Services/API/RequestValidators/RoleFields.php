<?php
namespace Unified\Services\API\RequestValidators;

/**
 * Role fields.
 */
class RoleFields
{
    /**
     * getRolesParameters
     */
    public function getRolesParameters()
    {
        return [
            'id' => 'acl_roles.id',
            'title' => 'acl_roles.title',
            'slug' => 'acl_roles.slug',
            'description' => 'acl_roles.description',
        	'whitelistNodeIds' => 'acl_roles.whitelist_node_ids',
        	'blacklistNodeIds' => 'acl_roles.blacklist_node_ids'
        ];
    }

    /**
     * getAddRoleParameters
     */
    public function getAddRoleParameters()
    {
        return [
            'title' => 'title',
            'slug' => 'slug',
            'description' => 'description',
        	'whitelistNodeIds' => 'whitelist_node_ids',
        	'blacklistNodeIds' => 'blacklist_node_ids'
        ];
    }

    /**
     * getOptionalModifiedRoleParameters
     */
    public function getOptionalModifiedRoleParameters()
    {
        return [
            'title' => 'acl_roles.title',
            'description' => 'acl_roles.description',
        	'whitelistNodeIds' => 'acl_roles.whitelist_node_ids',
        	'blacklistNodeIds' => 'acl_roles.blacklist_node_ids'
        ];
    }
}
