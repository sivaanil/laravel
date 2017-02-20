<?php namespace Modules\Sensors\Entities;

use Log;

use Unified\Models\Device;
use Unified\Models\DeviceType;
use Unified\Models\GridModel;
use Unified\Http\Helpers\GridParamParser;
use Unified\Models\Sensor;
use Unified\Models\DeviceProp;
use Unified\Models\PropDef;
use Unified\Models\NetworkTree;

class ContactClosure extends Sensor implements GridModel {

    protected $device_type_id = DeviceType::CONTACT_CLOSURE;
    protected $fillable = [];


    /**
     * Fetch contact closure sensors from grid ajax request
     * @param GridParamParser $gpp Grid Paramter Parser instance
     * @param int $nodeId The node id under which we need to find Contact Closures
     *
     * @return Array<StdClass> Grid-ready data objects
     */
    public function getForGrid(GridParamParser $parser, $nodeId = null) {
        $output = [];
        $parser->parse();

        Log::info("Node id: $nodeId");

        $filters    = $parser->getFilters();
        $sort       = $parser->getSort();

        $cc = new ContactClosure();

        // Build the query based on the parameters above
        // This is just the devices, filtering will be handled after we get the
        // devices by correct type.

        $closures = $cc->findByNodeId($nodeId);

        // Once we have the closure objects, we need to initialize them with all
        // properties, so we can filter, sort, and paginate
        foreach ($closures as $closure) {
            $props = DeviceProp::getProps($closure->deviceId);
            $properties = [];

            foreach ($props as $prop) {
                $properties[$prop->variable_name] = $prop->value;
            }


            $c = ContactClosure::find($closure->deviceId);

            $row = new \StdClass;
            $row->name = empty($properties["DI Register Description"]) ? $c->name : $properties["DI Register Description"];
            $row->path = $c->getPath();
            $row->current_state = empty($properties["DI Register Status"]) ? "Unavailable" : $properties["DI Register Status"];
            //$row->normal_state  = empty($properties["DI Register Normal Alias"]) ? "Unavailable" : $properties["DI Register Normal Alias"];
        // Use normal alias, then normal state, then unavailable if neither are present
            if (!empty($properties['DI Register Normal Alias'])) {
                $row->normal_state = $properties['DI Register Normal Alias'];
            } elseif (!empty($properties['DI Register Normal State'])) {
                $row->normal_state = $properties['DI Register Normal State'];
            } else {
                $row->normal_state = "Unavailable";
            }	

            $row->severity      = empty($properties["DI Register Severity"]) ? "Unavailable" : $properties["DI Register Severity"];
            // Device ID of contact closure
            $row->id = $c->id;

            // Filter the row based on passed filters
            $include = true;
            if ($filters) {
                foreach ($filters as $filter) {
                    if (stripos($row->{$filter->datafield}, $filter->value) === false) {
                        $include = false;
                    }
                }

                // Add the row to the output
                if ($include) {
                    $output[] = $row;
                }
            } else {
                $output[] = $row;
            }
        }

        // Sort the rows based on the field and direction in the sort
        if ($sort) {
            $field = $sort->datafield;
            $order = $sort->order;
            $this->sortRows($field, $order, $output);
        }

        return $output;
    }

    /**
     * Sort rows based on sort from grid
     * @param $field string datafield for sort
     * @param $order string asc or desc sort
     * @param $rows Array<\StdClass> rows to be sorted
     */
    protected function sortRows($field, $order, &$rows) {
        usort($rows, function($a, $b) use ($field, $order) {
            if ($a->$field == $b->$field) {
                return 0;
            }

            if (strtolower($order) == "asc") {
                return ($a->$field < $b->$field ? -1 : 1);
            } else {
                return ($a->$field > $b->$field ? -1 : 1);
           }
        });
    }

    /**
     * Write properties from this record to the underlying device
     *
     * @return void
     */
    public function writeSettings($nodeId) {
        // Get all the properties for this device
        $props = DeviceProp::getProps($this->id);

        // Write the settings to the device
        $cswapiRoot = env('CSWAPI_ROOT');
        require_once($cswapiRoot . '/common/doctrine.php');
        require_once($cswapiRoot . '/networking/controllers/shj_controller.php');

        $newPropSettings = [];
        $activeUserId = 7;

        foreach ($props as $prop) {
            $propObj = DeviceProp::find($prop->id);
            $propDef = PropDef::find($prop->prop_def_id);

            $newPropSettings[] = (object)[
                'id'            => $propObj->id,
                'name'          => $propDef->name,
                'variable_name' => $propDef->variable_name,
                'prop_type_id'  => $propDef->prop_type_id,
                'value'         => $propObj->value,
                'newVal'        => $propObj->value,
                'prop_def_id'   => $propDef->id,
            ];
        }

        writeSettings($newPropSettings, $nodeId, $activeUserId);
    }


    /**
     * Find all contact closures under a specific node id
     *
     * @param int $nodeId Node ID under which to find Contact Closures
     * @return Array<ContactClosure>
     */
    public function findByNodeId($nodeId) {
        $output = Device::getDevicesByType($this->device_type_id, $nodeId);
        return $output;
    }


}
