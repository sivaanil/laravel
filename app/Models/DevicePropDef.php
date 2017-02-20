<?php

namespace Unified\Models;

use DB;
use Eloquent;
use Unified\Http\Helpers\QueryParameters;

/**
 * Device property definition model.
 */
final class DevicePropDef extends Eloquent {
    use QueryTrait;
    public $timestamps  = false;
    protected $table = "css_networking_device_prop_def";
    
    /**
     * Returns list of property definitions matching provided query parameters
     * @param QueryParameters $config
     */
    public static function getPropertyDefinitions(QueryParameters $config) {
        $fields = $config->getFields();
       
        // Start query construction
        $query = DB::table ( 'css_networking_device_prop_def as ndpd' );
        // set fields
        $query = self::setFields($query, $fields, $config->isCount());
        // Apply filters
        $query = self::setFilters ( $query, $config->getFilters() );
        // Apply sortby
        $query = self::setSortby ( $query, $config->getSortby() );
        
        // Set pagination parameters
        $query = self::setPagination ( $query, $config->getOffset (), $config->getLimit () );

        // Execute query
        return self::getResults ( $query, $config->isCount (), 'propertyDefinitions' );
    }

    public static function getPropDefID($nodeId, $propertyName)
    {
        return DB::Table('css_networking_device_prop_def AS dpd')
                        ->select('dpd.id AS id')
                        ->where('dpd.variable_name', '=', DB::raw('?'))
                        ->whereIn('dpd.device_type_id', function ($query) {
                            $query->select('d.type_id')
                            ->from('css_networking_device AS d')
                            ->join('css_networking_network_tree AS nt', 'nt.device_id', '=', 'd.id')
                            ->where('nt.id', '=', DB::raw('?'));
                        })
                        ->setBindings([$propertyName, $nodeId])
                        ->first();
    }

    public static function getPropInfo($propDefId)
    {
        return DB::Table('css_networking_device_prop_def AS dpd')
                        ->select('dpd.device_type_id AS typeId', 'dpd.variable_name AS name')
                        ->where('dpd.id', '=', DB::raw('?'))
                        ->setBindings([$propDefId])
                        ->get();
    }

    public static function createBaseSensorProperty($propDefId, $propName)
    {
        // Get the property definition ID for the name
        $oldProp = DevicePropDef::find($propDefId);
        
        // Check if the base property we are looking to create already exists
        $property = DevicePropDef::where('device_type_id', '=', $oldProp->device_type_id)
                                ->where('variable_name', '=', $propName)
                                ->first();
        // If it does exist, return the id, otherwise, create it
        if (is_object($property)) {
           return $property->id;
        } else {
            $newProp = new DevicePropDef();
            $newProp->prop_type_id = $oldProp->prop_type_id;
            $newProp->device_type_id = $oldProp->device_type_id;
            $newProp->variable_name = $propName;
            $newProp->name = $oldProp->name;
            $newProp->data_type = $oldProp->data_type;
            $newProp->editable = $oldProp->editable;
            $newProp->visible = $oldProp->visible;
            $newProp->thresh_enable = $oldProp->thresh_enable;
            $newProp->save();
            return $newProp->id;
        }
    }
    
}
