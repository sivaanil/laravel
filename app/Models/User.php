<?php namespace Unified\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Log;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    public $timestamps = false;

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_authentication_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'pwd_modified_date', 'force_pwd_change', 'pwd_modified_date'];


    /**
     * Persistence for permissions for the user, to avoid looking them up repeatedly
     */
    protected $allPermissions = [];

    /*
    |-------------------------------------------------------|
    | ACL/Security Methods                                  |
    |-------------------------------------------------------|
    */

    /**
     * Determines whether a user can perform an action based on a permission
     *
     * @param String permission slug of a permission
     * @return Boolean
     */
    public function can($permission = null) {
        if (is_null($permission)) {
            return true;
        }
        return $this->checkPermission($permission);
    }

    /**
     * Checks to see if a permission matches any permission the user has
     *
     * @param String  $permission permission slug
     * @return Boolean
     */
    protected function checkPermission($permission) {
        $permissions = $this->getAllPermissions();
        $requestedPermissions = is_array($permission) ? $permission : [$permission];
        $output = count(array_intersect($permissions, $requestedPermissions));

        // All permissions override (admin, superuser, etc)
        if (in_array('all-permissions', $permissions)) {
            $output = true;
        }

        return boolval($output);
    }

    /**
     * Gets all permissions available to this user.
     *
     * @return Array<String> array of permission slugs this user is authorized for.
     */
    protected function getAllPermissions() {
        $permissions = [];

        $userPermissions = $this->permissions;
        foreach ($userPermissions as $p) {
            $permissions[] = $p->slug;
        }

        $roles = $this->roles;

        // Get permissions for each role
        foreach ($roles as $role) {
            $rolePermissions = $role->permissions;
            foreach ($rolePermissions as $rp) {
                $permissions[] = $rp->slug;
            }
        }

        $permissions = array_unique($permissions);

        return $permissions;
    }


    /**
     * Accessor for many-to-many rolesa
     *
     * @return QueryBuilder
     */
    public function roles() {
        return $this->belongsToMany('Unified\Models\Role', 'acl_role_user');
    }

    /**
     * Accessor for many-to-many permissions
     */
    public function permissions() {
        return $this->belongsToMany('Unified\Models\Permission', 'acl_user_permission');
    }
}
