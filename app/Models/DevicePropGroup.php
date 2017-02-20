<?php

namespace Unified\Models;

use DB;
use Eloquent;
use Unified\Http\Helpers\QueryParameters;

/**
 * Property group model.
 */
class DevicePropGroup extends Eloquent {
    use QueryTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device_prop_group';
    
    /**
     * Returns list of property groups matching provided query parameters
     * 
     * @param QueryParameters $config            
     */
    public static function getPropertyGroups(QueryParameters $config) {
        return self::getRecords($config, get_called_class(), 'propertyGroups');
    }
}
