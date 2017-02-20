<?php

namespace Unified\Models;

use DB;
use Eloquent;
use Unified\Http\Helpers\QueryParameters;

/**
 * Property type model.
 */
class DevicePropType extends Eloquent {
    use QueryTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device_prop_type';
    
    /**
     * Returns list of property types matching provided query parameters
     * 
     * @param QueryParameters $config            
     */
    public static function getPropertyTypes(QueryParameters $config) {
        return self::getRecords($config, get_called_class(), 'propertyTypes');
    }
}
