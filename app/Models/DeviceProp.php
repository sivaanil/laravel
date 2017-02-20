<?php

namespace Unified\Models;

use Auth;
use DB;
use Eloquent;
use Unified\Http\Helpers\QueryParameters;

class DeviceProp extends Eloquent {
    use QueryTrait;

    protected $table = "css_networking_device_prop";
    const MAX_UPDATE   = 500;
    public $timestamps = false;
    
    public static function updateDevices($deviceIDs) {
        $output = DB::table('css_networking_device_prop')
                    ->whereIn('device_id', $deviceIDs)
                    ->limit(self::MAX_UPDATE)
                    ->update([
                        'use_defaults' => DB::raw('use_defaults'),
                    ]);
         return $output;
    }

    public static function getStata($deviceId) {
        $props = DB::table('css_networking_device_prop as p')
            ->select(//'p.id',
                'pd.name',
		'pd.variable_name',
                //'pd.prop_type_id as type',
                'p.value'
            /*'pd.visible as visible',
            'p.min as prop_min',
            'p.min_val as prop_min_val',
            'p.max as prop_max',
            'p.max_val as prop_max_val',
            'p.severity_id as prop_sev',
            'p.prop_def_id',
            'p.use_defaults as use_def',
            'pd.min as def_min',
            'pd.prop_group_id',
            'pd.min_val as def_min_val',
            'pd.max as def_max',
            'pd.max_val as def_max_val',
            'pd.severity_id as def_sev',
            'pd.tooltip',
            'pd.variable_name',
            'pd.secure as secure',
            'pd.thresh_enable as enable',
            'pg.name as group_name'*/)
            //'case when pd.graph_type = 1 then 'true' else 'false' end as 'binary'')
            ->join('css_networking_device_prop_def as pd', 'p.prop_def_id', '=', 'pd.id')
            ->join('css_networking_device_prop_group as pg', 'pd.prop_group_id', '=', 'pg.id')
            ->where('p.device_id', '=', $deviceId)
            ->where('p.value', '!=', 'Not In Use')
            ->where('p.value', '!=', '""')
            ->where('pd.prop_type_id', '=', 2)
            ->where('pd.visible', '=', 1)
            ->orderBy('pd.name', 'asc')
            ->get();
        return $props;

    }

    public static function getProps($deviceId) {
        $props = DB::table('css_networking_device_prop as p')
            ->select( 'p.id',
                'p.value',
                'pd.id as prop_def_id',
		'pd.variable_name',
                /*'p.alarm_change',
                'p.alarm_siteportal_change',
                'p.user_id_last_updated',*/
                'pd.name'
            /*'pd.prop_type_id',
            'pd.id as prop_def_id',
            'pd.data_type',
            'pd.editable',
            'pd.tooltip',
            'pd.variable_name',
            'pg.group_breadCrumb',
            'p.severity_id as prop_sev'*/
            )
            ->join('css_networking_device_prop_def as pd', 'p.prop_def_id', '=', 'pd.id')
            ->leftjoin('def_prop_groups_map as pgm', function ($join) {
                $join->on('pgm.prop_def_variable_name', '=', 'pd.variable_name')->where('pd.device_type_id',
                    '=', '`pgm`.`device_type_id`');
            })
            ->leftJoin('def_prop_groups as pg', 'pg.group_var_name', '=', 'pgm.group_var_name')
            ->where('p.device_id', '=', $deviceId)
            ->where('p.value', '!=', 'Not In Use')
            ->where('p.value', '!=', '""')
            ->where('pd.prop_type_id', '=', 1)
            ->where('pd.visible', '=', 1)
            ->get();
        return $props;
    }
    public function mapToRange(\Unified\Models\DevicePropRange $propertyRange) {
        $currentValue = $this->value;
        //in the usual definition of this algorithm, the original range is denoted as a1 -> a2, 
        //the new range by b1 -> b2, and the current value and new value as s and t, respectively
        $s = $currentValue;
        $a1 = $propertyRange->original_minimum;
        $a2 = $propertyRange->original_maximum;
        $b1 = $propertyRange->new_minimum;
        $b2 = $propertyRange->new_maximum;

        $s *= 1.0; //make sure we're dealing with floats and not ints
        $t = (($s - $a1)*($b2 - $b1)) / ($a2 - $a1);
        return $t;
    }

    /**
     * Returns list of properties matching provided query parameters
     * @param QueryParameters $config
     */
    public static function getProperties(QueryParameters $config) {
        $filters = $config->getFilters();
        $fields = $config->getFields();
        $control = $config->getControl();
        // Get more control parameters (page, etc.) if necessary
        $updated = $config->getControlParam ( 'updated');
        $parentNode = $config->getControlParam ( 'root');
    
        if (!isset($parentNode) || ($parentNode == 0)) {
            // Get parent node from user information
            // TODO FIXME Remove Auth from models
            $parentNode = Auth::user()->home_node_id;
        }
    
        $node = NetworkTreeMap::where('node_id', '=', $parentNode)->first();
        if (empty($node)) {
            return new ServiceResponse ( ServiceResponse::FAIL, ['error' => 'Node $parentNode does not exist.'] );
        }
        $nodeMap = $node->node_map;
        $query = DB::table('css_networking_device_prop as ndp')
        ->join('css_networking_network_tree as nnt', 'nnt.device_id', '=', 'ndp.device_id')
        // TODO check if nodeType is requested
        ->join('css_networking_device as nd', 'nnt.device_id', '=', 'nd.id')
        ->join('css_networking_network_tree_map as nntm', function ($join) use ($nodeMap) {
            $join->on('nntm.node_id', '=', 'nnt.id')
            ->where('nntm.node_map', 'like', "$nodeMap%")->where('nntm.deleted','=',0);
        });

        // Join definition table
        if (QueryParameters::isPresent($fields, 'ndpd.')) {
            $query = $query->leftjoin ( 'css_networking_device_prop_def as ndpd', 'ndpd.id', '=', 'ndp.prop_def_id' );
        }
    
        // set fields
        $query = self::setFields($query, $fields, $config->isCount());
        // Apply sequence filters if present
        if (! is_null ( $updated )) {
            // add sequence filter
            $query->where ( 'ndp.sequence', '>', $updated );
            // add order by sequence
            $query->orderBy ( 'ndp.sequence' );
        }
        // Apply filters
        $query = self::setFilters ( $query, $filters );
        
        // Apply sortby
        $query = self::setSortby ( $query, $config->getSortby() );
        
        // Set pagination parameters
        $query = self::setPagination ( $query, $config->getOffset (), $config->getLimit () );

        // Execute query
        $retVal = self::getResults ( $query, $config->isCount (), 'properties' );
        
        if (! is_null ( $updated )) {
            if (count ( $retVal['properties'] ) > 0) {
                $newSequence = $retVal['properties'] [count ( $retVal['properties'] ) - 1]->sequence;
            } else {
                $newSequence = $updated;
            }
            $retVal['updated'] = $newSequence;
        }

        return $retVal;
    }
    
    /**
     * Modify property attributes
     *
     * @param unknown $content
     *            Attributes to be modified
     */
    public function modifyAttributes(&$content) {
        try {
            DB::beginTransaction ();
            
            QueryTrait::setEntityAttributes ( $this, $content );
            $this->save();
            
            $retVal = self::checkFinalContent ( $content );
            if ($retVal ['status']) {
                DB::commit ();
            } else {
                DB::rollback ();
            }
        } catch ( Exception $e ) {
            $retVal = QueryTrait::error ( 'Unable to modify property: ' . $e->getMessage );
            DB::rollback ();
        }
        
        return $retVal;
    }
    
    public function propdef() {
        $this->hasOne('Unified\Models\PropDef', 'id', 'prop_def_id');
    }

    public function createVirtualDeviceProp($propDefId, $deviceId)
    {
        $propExists = DeviceProp::where('prop_def_id', '=', $propDefId)
                    ->where('device_id', '=', $deviceId)
                    ->first();
        
        // See if the prop already exists
        if (is_object($propExists)) {
            return;
        } else {
            // If not, create it
            $prop = new DeviceProp();
            $prop->prop_def_id = $propDefId;
            $prop->device_id = $deviceId;
            $prop->value = 'Not In Use';
            $prop->save();
        }
    }
    
    public static function deleteVirtualDeviceProp($propDefId, $deviceId)
    {
        DeviceProp::where('prop_def_id', '=', $propDefId)
                ->where('device_id', '=', $deviceId)
                ->delete();
    }

}
