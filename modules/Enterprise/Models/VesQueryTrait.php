<?php

namespace Modules\Enterprise\Models;

use DB;
use stdClass;

trait VesQueryTrait {
    
    /**
     * Return property name from the string formatted as "prefix.propertyName as userName"
     *
     * @param unknown $fieldDescr
     *            Target description
     */
    private static function getPropName($fieldDescr) {
        $fieldDescr = self::skipPrefix ( $fieldDescr );
        return substr ( $fieldDescr, 0, strpos ( $fieldDescr, " as " ) );
    }
    
    /**
     * Returns property value by searching for propertyName in the provided property array
     *
     * @param unknown $propDescr
     *            String formatted as "prefix.propertyName as userName"
     * @param unknown $props
     *            Array of properties
     * @return unknown Property value or default value
     */
    private static function getPropValue($propDescr, $props) {
        $propName = self::getPropName ( $propDescr );
        if (isset ( $props [$propName] )) {
            return $props [$propName];
        }
        if (config('app.debug')) {
            return "DEBUG:----Missing property----";
        }
        return "";
    }
    
    /**
     * Return user friendly name from the string formatted as "prefix.propertyName as userFriendlyName"
     *
     * @param unknown $fieldDescr
     *            Target description
     */
    private static function getFieldName($fieldDescr) {
        return substr ( $fieldDescr, strpos ( $fieldDescr, " as " ) + 4 );
    }
    
    /**
     * Skipp prefix from the variable name formatted as "prefix.name"
     * 
     * @param unknown $string
     *            Target string
     * @return unknown Variable name
     */
    private static function skipPrefix($string) {
        $pos = strpos ( $string, '.' );
        return $pos == false ? $string : substr ( $string, $pos + 1 );
    }
    /**
     * Validate object against provided list of filters.
     * Each filter present in the following format:
     * filter[0] - object memeber to be verified
     * filter[1] - condition
     * filter[2] - value to be verified against
     * 
     * @param unknown $filters
     *            List of filters
     * @param unknown $obj
     *            Target object
     * @return boolean True if filters are not present or matched, otherwise false.
     */
    private static function checkFilters($filters, $obj) {
        if (empty ( $filters )) {
            return true;
        }
        foreach ( $filters as $filter ) {
            $propName = self::skipPrefix ( $filter [0] );
            $propValue = (isset ( $obj [$propName] ) ? $obj [$propName] : null);
            switch ($filter [1]) {
                case '=' :
                    $retVal = ($propValue == $filter [2]);
                    break;
                case '<>' :
                    $retVal = ($propValue != $filter [2]);
                    break;
                case '>' :
                    $retVal = ($propValue > $filter [2]);
                    break;
                case '>=' :
                    $retVal = ($propValue >= $filter [2]);
                    break;
                case '<' :
                    $retVal = ($propValue < $filter [2]);
                    break;
                case '<=' :
                    $retVal = ($propValue <= $filter [2]);
                    break;
                case 'isnull' :
                    $retVal = isnull ( $propValue );
                    break;
                case 'isnotnull' :
                    $retVal = isnotnull ( $propValue );
                    break;
                default :
                    Log::error ( 'Invalid condition: ' . $filter [1] );
                    $retVal = false;
            }
            if (! $retVal) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Returns array of subdevices with type $subDeviceType.
     *
     * @param unknown $device_id
     *            Device ID
     * @param unknown $node_map
     *            Device node map (just for an optimization to do nor request node map inside the function)
     * @param unknown $subDeviceTypeId
     * @param unknown $fields
     *            Requested fields
     * @param unknown $filters
     *            Filters to be matched agains
     * @param unknown $matchFilters
     *            Result of matching filkters
     */
    private static function getSubDevices($device_id, $node_map, $subDeviceTypeId, $fields, $filters, &$matchFilters) {
        // Init array of sub devices
        $subDeviceRetval = array ();
    
        // Get list of sub devices
        $subDevices = self::getChildrenByDeviceTypeAndNodeMap ( $node_map, $subDeviceTypeId );
        if (empty ( $subDevices )) {
            continue;
        }
        // Init match filters flag
        $matchFilters = false;
    
        // Loop through the list and collect necessary data
        foreach ( $subDevices as &$subDevice ) {
    
            if (! empty ( $fields ) || ! empty ( $filters )) {
                // Init sub device objects
                $sdElement = new stdClass ();
                // Get device properties by deviceId
                $subDeviceProps = self::getProperties ( $subDevice->id );
                // Add native Id to the list of properties
                $subDeviceProps ['native_id'] = $subDevice->native_id;
    
                $matchFilters |= self::checkFilters ( $filters, $subDeviceProps );
    
                if (! empty ( $fields )) {
    
                    // Add requested properties
                    foreach ( $fields as $sdrField ) {
                        $fieldName = self::getFieldName ( $sdrField );
                        $sdElement->$fieldName = self::getPropValue ( $sdrField, $subDeviceProps );
                    }
                    // Corner case. Add debId and nodeMap to the radioNode elements
                    // They may be used in the search for band objects
                    if ($subDeviceTypeId == 5057) {
                        $sdElement->__device_id__ = $subDevice->id;
                        $sdElement->__node_map__ = $subDevice->node_map;
                    }
                    $subDeviceRetval [] = $sdElement;
                }
            }
        }
        return $subDeviceRetval;
    }
    
    /**
     * Get all properties for device with particular device ID
     *
     * @param unknown $deviceId
     *            Target deviceId
     * @return unknown
     */
    private static function getProperties($deviceId) {
        $props = DB::table ( 'css_networking_device_prop as p' )->select ( 'pd.variable_name', 'p.value' )->join (
                'css_networking_device_prop_def as pd',
                'p.prop_def_id',
                '=',
                'pd.id' )->where ( 'p.device_id', '=', $deviceId )->get ();
        $propsRetval = array ();
        // Remove support fields
        foreach ( $props as &$prop ) {
            $propsRetVal [$prop->variable_name] = $prop->value;
        }
        unset ( $props );
        return $propsRetVal;
    }
    
    /**
     * Get children devices by device type and node map
     *
     * @param unknown $nodeMap
     *            target node map
     * @param unknown $deviceType
     *            target device type
     * @return unknown List of childrens
     */
    private static function getChildrenByDeviceTypeAndNodeMap($nodeMap, $deviceType) {
        $childs = DB::table ( 'css_networking_network_tree_map as nntm' )->select ( 'nd.id',
                'nntm.node_map',
                'nd.native_id' )->join ( 'css_networking_network_tree as nnt', 'nnt.id', '=', 'nntm.node_id' )->join (
                        'css_networking_device as nd',
                        'nd.id',
                        '=',
                        'nnt.device_id' )->where ( 'nd.type_id', '=', $deviceType )->where ( 'nntm.node_map',
                                'LIKE',
                                $nodeMap . '%' )->get ();
                                return $childs;
    }
    
}
