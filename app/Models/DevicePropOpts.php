<?php

namespace Unified\Models;

use DB;
use Eloquent;
use Unified\Http\Helpers\QueryParameters;

/**
 * Property option model.
 */
final class DevicePropOpts extends Eloquent {
    use QueryTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device_prop_opts';
    
    /**
     * Returns list of property options matching provided query parameters
     * 
     * @param QueryParameters $config            
     */
    public static function getPropertyOptions(QueryParameters $config) {
        return self::getRecords($config, get_called_class(), 'propertyOptions');
    }
}
