<?php

namespace Unified\Http\Controllers;

use Input;
use Auth;
use Unified\VirtualDeviceManager\VirtualDeviceType\ExternalRTU;
use \InvalidArgumentException;
use \RuntimeException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VirtualDeviceController extends Controller
{

    public function checkPropertyThresholds(\Unified\Models\VirtualDevice\VirtualDevice $virtualDevice)
    {
        $ownedProperties = $virtualDevice->propertyFilter;
        $propertyThresholds = $virtualDevice->propertyThresholds;
        $textThresholds = $virtualDevice->textThresholds;
        $violators = [];

        foreach ($ownedProperties as $myPropFilterElement) {
            $property = \Unified\Models\DeviceProp::where('prop_def_id', $myPropFilterElement->prop_def_id)->first();
            $rangeMap = \Unified\Models\DevicePropRange::find($myPropFilterElement->custom_range_map);
            $value = (!$rangeMap ? $property->value : $property->mapToRange($rangeMap));

            $targetPropThresholds = $propertyThresholds->where('prop_def_id', $myPropFilterElement->prop_def_id);
            $targetTextThresholds = $textThresholds->where('prop_def_id', $myPropFilterElement->prop_def_id);
            foreach($targetPropThresholds as $propThreshold) {
                if($propThreshold->exceedsThreshold($value)) {
                    $violators[] = ['node_id'=> $virtualDevice->virtual_node_id,
                                    'prop_def' => $myPropFilterElement->prop_def_id,
                                    'message' => $propThreshold->exceedMessageToString($value)
                    ];
                }
            }
            foreach($targetTextThresholds as $propThreshold) {
                if($propThreshold->exceedsThreshold($value)) {
                    $violators[] = ['node_id'=> $virtualDevice->virtual_node_id,
                                    'prop_def' => $myPropFilterElement->prop_def_id,
                                    'message' => $propThreshold->exceedMessageToString($value)
                    ];
                }
            }
        }
        return $violators;
    }

    public function linkVirtualDevice($virtualNodeId, $realNodeId, $propertyList = true)
    {
        if (!is_numeric($virtualNodeId)) {
            throw new InvalidArgumentException("\$virtualNodeId is not numeric");
        }
        if (!is_numeric($realNodeId)) {
            throw new InvalidArgumentException("\$realNodeId is not numeric");
        }

        $virtualDevice = \Unified\Models\VirtualDevice\VirtualDevice::where('node_id', $virtualNodeId)->first();
        if (!$virtualDevice) {
            throw new InvalidArgumentException("Node '$virtualNodeId' does not exist or is not a virtual device");
        }

        $physicalDeviceNode = \Unified\Models\NetworkTree::find($realNodeId);
        if (!$physicalDeviceNode) {
            throw new InvalidArgumentException("Node '$realNodeId' does not exist");
        }
        //create new virtual device map entry
        $map = new \Unified\Models\VirtualDevice\VirtualDeviceMap();
        $map->device_node_id = $realNodeId;
        $map->virtual_node_id = $virtualDevice->node_id;
        $map->save();

        //link properties
        $this->linkProperties($virtualDevice, $propertyList);
    }

    public function writeDeviceSetting($virtualNodeId, $newSettingData) {

        if (!is_numeric($virtualNodeId)) {
            throw new InvalidArgumentException("\$virtualNodeId is not numeric");
        }
        $validator = Validator::make($newSettingData, [
            'property_definition' => 'required|Integer',
            'new_setting' => 'required|String'
        ]);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            throw new InvalidArgumentException("Error validating setting data: " . $errorMessage);
        }
        $virtualDevice = \Unified\Models\VirtualDevice\VirtualDevice::find($virtualNodeId);
        if(!$virtualDevice) {
            throw new InvalidArgumentException("\$virtualNodeId does not exist");
        }
        $propDef = \Unified\Models\DevicePropDef::find($newSettingData['property_definition']);
        if(!$propDef) {
            throw new InvalidArgumentException("PropDef '{$newSettingData['property_definition']}' does not exist");
        }

        $realDeviceNode = $virtualDevice->realDeviceNode;
        $realDevice = $realDeviceNode->device;

        $property = \Unified\Models\DeviceProp::where('device_id', $realDevice->id)->where('prop_def_id', $propDef->id)->first();
        if(!$property) {
            //prop_def but no prop - device may have built incorrectly
            throw new \Exception("Property does not exist for DID={$realDevice->id} and {$propDef->id}");
        }

        $requireSettingData = new \stdClass();
        $requireSettingData->variable_name = $propDef->variable_name;
        $requireSettingData->newVal = $newSettingData['new_setting'];
        $requireSettingData->prop_def_id = $propDef->id;

        require_once ENV('CSWAPI_ROOT') . '/common/class/CssUtil.php';
        require_once ENV('CSWAPI_ROOT') . '/networking/class/DeviceTable.php';
        require_once ENV('CSWAPI_ROOT') . '/networking/class/NetworkTreeTable.php';
        require_once ENV('CSWAPI_ROOT') . '/networking/class/NetworkTreeMapTable.php';
        //below is the deviceWrite procedure as defined by cswapi
        $mainDeviceInfo = \DeviceTable::GetMainDeviceByChildId($realDevice->id);
        $mainNode = \NetworkTreeTable::GetNodeById($mainDeviceInfo->node_id);
        $mainDevice = $mainNode->device;
        $ctrlFile = \CssUtil::GetControllerFile($mainDevice->type->model, $mainDevice->type->vendor);
        include_once(ENV('CSWAPI_ROOT') . "/networking/controllers/$ctrlFile");
        $result = \writeSettings([$requireSettingData], $realDeviceNode->id, Auth::id());

        return ['success' => ($result == true ? true : false)];

    }

    public function buildVirtualDevice($parentNodeId, $deviceStructure)
    {
        Log::debug(__CLASS__ . "::" . __FUNCTION__ . " Called with ParentNodeId=$parentNodeId, DeviceStructure=", $deviceStructure);

        //very, very unfourtunatey, we need cswapi's node building functions
        require_once ENV('CSWAPI_ROOT') . '/networking/class/Device.php';
        require_once ENV('CSWAPI_ROOT') . '/networking/class/NetworkTreeTable.php';
        require_once ENV('CSWAPI_ROOT') . '/networking/class/NetworkTreeMapTable.php';
        //first build the nodes & devies into the tree
        $parent = \NetworkTreeTable::GetNodeById($parentNodeId);
        if (!$parent) {
            throw new InvalidArgumentException("'parent_node_id' does not exist");
        }
        $this->buildChildren($parent, [$deviceStructure]);
        return "VirtualDevice build successful";
    }

    private function buildChildren($parent, $currentData)
    {
        Log::info("Subdevice has " . count($currentData) . " children");

        foreach ($currentData as $child) {
            Log::debug("Starting child iteration, child data: ", $child);
            $childValidator = Validator::make($child, [
                'name' => 'required',
                'real_device_node' => 'required|Integer',
                'property_list' => 'required',
                'property_mapping' => 'sometimes|array',
                'property_thresholds' => 'sometimes|array',
                'text_thresholds' => 'sometimes|array',
                'children' => 'array' /* cant use 'exists', see comment below */
            ]);
            if ($childValidator->fails()) {
                $errorMessage = $childValidator->errors()->first();
                throw new InvalidArgumentException("Error validating virtual device structure: " . $errorMessage);
            }
            /* Laravel 5.2 supports the 'exists' validator, which is exactly what we need above.
               Unfortunately we're stuck on 5.1 now and have to use this work around */
            if (!array_key_exists('children', $child)) {
                $errorMessage = "Property 'children' does not exist";
                throw new InvalidArgumentException("Error validating virtual device structure: " . $errorMessage);
            }

            $isMainNode = ($child['real_device_node'] != -1) ? FALSE : TRUE;
            $device = new \Device();
            if ($isMainNode) {
                $device->type_id = '5063'; //TODO: bring device type class into Unified an use here
            } else {
                $realDeviceNode = \NetworkTreeTable::GetNodeById($child['real_device_node']);
                $realDevice = $realDeviceNode->device;
                $device->type_id = $realDevice->type_id;
            }
            $device->name = $child['name'];
            $device->save();
            $newNode = \NetworkTreeTable::NewDeviceNode($parent, $device->id);
            $newVirtualDevice = new \Unified\Models\VirtualDevice\VirtualDevice();
            $newVirtualDevice->node_id = $newNode->id;
            $newVirtualDevice->save();
            
            if ($isMainNode && $device->type_id === '5063') {
                // Copy the property over to main node from the sensor to make it a "generator"
                $externalRTU = new ExternalRTU('1077');
                $externalRTU->createGeneratorProps($child, $newVirtualDevice->node_id);
            }     
            
            //because this isnt a 'true' device builder, we have to set the flags below manually
            $q = \Doctrine_Query::create()
               ->from("NetworkTreeMap tm")
               ->where("tm.node_id = $newNode->id");
            $newNodeMapEntry = $q->fetchOne();
            $newNodeMapEntry->build_in_progress = 0;
            $newNodeMapEntry->deleted = 0;
            $newNodeMapEntry->save();

            Log::info("Linking virtual device to real device");
            if (!$isMainNode) {
                $this->linkVirtualDevice($newVirtualDevice->node_id, $child['real_device_node'], $child['property_list']);
            }
            //if node contains property value translation data, set this up
            if (array_key_exists('property_value_translation', $child)) {
                foreach ($child['property_value_translation'] as $translationEntry) {
                    //validate the input data
                    $rangeValidator = Validator::make($translationEntry, [
                        'property_definition' => 'required|Integer|min:1',
                        'original_minimum' => 'required|Numeric',
                        'original_maximum' => 'required|Numeric',
                        'new_minimum' => 'required|Numeric',
                        'new_maximum' => 'required|Numeric'
                    ]);
                    if ($rangeValidator->fails()) {
                        $errorMessage = $rangeValidator->errors()->first();
                        throw new InvalidArgumentException(
                            "Error validating property translation: " . $errorMessage);
                    }
                    //save the range record to the database
                    $rangeMap = new \Unified\Models\DevicePropRange();
                    $rangeMap->prop_def_id = $translationEntry['property_definition'];
                    $rangeMap->original_minimum = $translationEntry['original_minimum'];
                    $rangeMap->original_maximum = $translationEntry['original_maximum'];
                    $rangeMap->new_minimum = $translationEntry['new_minimum'];
                    $rangeMap->new_maximum = $translationEntry['new_maximum'];
                    $rangeMap->save();
                    //now we have to link the virtual device's prop_filter entry (if it exists), 
                    //to this range record
                    $filterEntry = $newVirtualDevice->propertyFilter()
                                 ->where('prop_def_id', $translationEntry['property_definition'])
                                 ->first();
                    if ($filterEntry) {
                        $filterEntry->custom_range_map = $rangeMap->id;
                        $filterEntry->save();
                    }
                }
            }
            //if node contains custom threshold data, save it
            if (array_key_exists('property_thresholds', $child)) {
                foreach ($child['property_thresholds'] as $threshold) {
                    $thresholdValidator = Validator::make($threshold, [
                        'property_definition' => 'required|Integer|min:1',
                        'lower_bound' => 'required|numeric',
                        'upper_bound' => 'required|numeric',
                        'alarm_inclusive' => 'required|Integer',
                    ]);
                    if ($thresholdValidator->fails()) {
                        $errorMessage = $thresholdValidator->errors()->first();
                        throw new InvalidArgumentException(
                            "Error validating property threshold: " . $errorMessage);
                    }
                    $customThreshold = new \Unified\Models\VirtualDevice\VirtualDeviceThreshold();
                    $customThreshold->lower_bound = $threshold['lower_bound'];
                    $customThreshold->upper_bound = $threshold['upper_bound'];
                    $customThreshold->alarm_inclusive = $threshold['alarm_inclusive'];
                    $customThreshold->prop_def_id = $threshold['property_definition'];
                    $customThreshold->virtual_device_id = $newVirtualDevice->id;
                    $customThreshold->save();
                }
            }
            //if node contains custom threshold data, save it
            if (array_key_exists('text_thresholds', $child)) {
                foreach ($child['text_thresholds'] as $threshold) {
                    $thresholdValidator = Validator::make($threshold, [
                        'property_definition' => 'required|Integer|min:1',
                        'case_sensitive' => 'required|Integer',
                        'alarm_on_match' => 'required|Integer',
                        'text' => 'required|String',
                    ]);
                    if ($thresholdValidator->fails()) {
                        $errorMessage = $thresholdValidator->errors()->first();
                        throw new InvalidArgumentException(
                            "Error validating text threshold: " . $errorMessage);
                    }
                    $customThreshold = new \Unified\Models\VirtualDevice\VirtualDeviceTextThreshold();
                    $customThreshold->case_sensitive = $threshold['case_sensitive'];
                    $customThreshold->alarm_on_match = $threshold['alarm_on_match'];
                    $customThreshold->text = $threshold['text'];
                    $customThreshold->prop_def_id = $threshold['property_definition'];
                    $customThreshold->virtual_device_id = $newVirtualDevice->id;
                    $customThreshold->save();
                }
            }
            if (count($child['children']) != 0 && $child['children'] !== ['']) {
                Log::info("Node has " . count($child['children']) . " child nodes, decending into children");
                $this->buildChildren($newNode, $child['children']);
            }
        }
        Log::info("Completed build on child devices");
    }

    private function linkProperties($virtualDevice, $propDefList = true)
    {

        //get the prop def list for this device type
        $device = $virtualDevice->realDeviceNode->device;
        if (is_array($propDefList)) {
            $existingPropertyDefs = \Unified\Models\DevicePropDef::where('device_type_id', $device->type_id)->whereIn('id', $propDefList)->get();
            if (count($existingPropertyDefs) !== count($propDefList)) {
                throw new InvalidArgumentException("Not all provided property IDs exist on the device type '{$device->type_id}'");
            }
        } else {
            $existingPropertyDefs = \Unified\Models\DevicePropDef::where('device_type_id', $device->type_id)->get();
        }
        foreach ($existingPropertyDefs as $propDef) {
            $propFilterElement = new \Unified\Models\VirtualDevice\PropertyFilter();
            $propFilterElement->virtual_device_id = $virtualDevice->id;
            $propFilterElement->prop_def_id = $propDef->id;
            $propFilterElement->save();
        }
    }

    /**
     * Intended to be used by the VirtualDevice API service 
     * @param Number $virtualDeviceId
     * @param Array $requiredFields Array of fields to gather from the virtual device
     * @return mixed Associateive array of properties, or false on failure
     */
    public function virtualDeviceData($virtualDeviceId, $requiredFields = [])
    {

        $getDefaultFields = count($requiredFields) == 0 ? true : false;

        $virtualDevice = \Unified\Models\VirtualDevice\VirtualDevice::find($virtualDeviceId);
        if (!$virtualDevice) {
            return false;
        }

        $virtualDeviceNode = $virtualDevice->virtualDeviceNode;
        $realDeviceNode = $virtualDevice->realDeviceNode;

        $data = [];
        if ($getDefaultFields || array_search('name', $requiredFields) !== FALSE) {
            $data['name'] = $virtualDeviceNode->device->name;
        }
        if (array_search('original_properties', $requiredFields) !== FALSE) {
            $data['original_properties'] = $virtualDevice->getProperties(false);
        }

        if (array_search('properties', $requiredFields) !== FALSE) {
            $data['properties'] = $virtualDevice->getProperties();
        }

        if ($getDefaultFields || array_search('virtual_node_id', $requiredFields) !== FALSE) {
            $data['virtual_node_id'] = $virtualDeviceNode->id;
        }
        
        if ($getDefaultFields || array_search('real_node_id', $requiredFields) !== FALSE) {
            $data['real_node_id'] = $realDeviceNode->id;
        }
        
        if (array_search('property_thresholds', $requiredFields) !== FALSE) {
            $data['property_thresholds'] = $virtualDevice->propertyThresholds;
        }
        if (array_search('text_thresholds', $requiredFields) !== FALSE) {
            $data['text_thresholds'] = $virtualDevice->textThresholds;
        }
        if ($getDefaultFields || array_search('property_value_translation', $requiredFields) !== FALSE) {
            $customRanges = [];
            $props = $virtualDevice->getProperties(FALSE);
            foreach($props as $prop) {
                $range = \Unified\Models\DevicePropRange::find($prop->custom_range_map);
                if($range) {
                    $customRanges[] = $range;
                }
            }
            $data['property_value_translation'] = $customRanges;
        }

        return $data;
    }

    public function getVirtualDeviceThresholds($virtualDeviceId)
    {

        $virtualDevice = \Unified\Models\VirtualDevice\VirtualDevice::find($virtualDeviceId);
        if (!$virtualDevice) {
            return false;
        }

        return $virtualDevice->propertyThresholds;
    }

    public function deleteVirtualDeviceThresholdById($thresholdId)
    {

        $virtualDevice = \Unified\Models\VirtualDevice\VirtualDeviceThreshold::find($thresholdId);
        if (!$virtualDevice) {
            throw new InvalidArgumentException("Threshold $thresholdId does not exist");
        }

        $virtualDevice->delete();
    }

    public function createVirtualDeviceThreshold($virtualDeviceId, $propDefId, $thresholdData)
    {

        /** @var \Unified\Models\VirtualDevice\VirtualDevice $virtualDevice The virtual Device */
        $virtualDevice = \Unified\Models\VirtualDevice\VirtualDevice::find($virtualDeviceId);
        if (!$virtualDevice) {
            throw new InvalidArgumentException("VirtualDevice $virtualDeviceId does not exist");
        }

        if (!$virtualDevice->ownsProperty($propDefId)) {
            throw new InvalidArgumentException("VirtualDevice ID=$virtualDeviceId does not own prop_def_id=$propDefId");
        }

        $threshold = new \Unified\Models\VirtualDevice\VirtualDeviceThreshold();
        $threshold->upper_bound = $thresholdData->upper_bound;
        $threshold->lower_bound = $thresholdData->lower_bound;
        $threshold->alarm_inclusive = $thresholdData->alarm_inclusive;
        $threshold->prop_def_id = $propDefId;
        $threshold->virtual_device_id = $virtualDeviceId;
        $threshold->save();
    }

    public function getAllTemplates()
    {
        $returnData = [];
        $rawTemplates = \Unified\Models\VirtualDevice\VirtualDeviceTemplate::get();
        foreach ($rawTemplates as $rawTemplate) {
            $returnData[] = $rawTemplate->inflatedTemplate();
        }
        return $returnData;
    }

    public function index()
    {
        //
    }
    
    public function getVirtualDevices($deviceType)
    {
        $virtualDeviceList = \Unified\Models\VirtualDevice\VirtualDeviceWizardTemplates::getDeviceList($deviceType);
        return $virtualDeviceList;
    }
    
    public function getSensorTypes($virtualDeviceType)
    {
        $sensorTypeList = \Unified\Models\VirtualDevice\VirtualDeviceSensorList::getSensorTypes($virtualDeviceType);
        return $sensorTypeList;
    }

    public function getSensors($typeId)
    {
        $sensorList = \Unified\Models\VirtualDevice\VirtualDeviceSensorList::getSensorList($typeId);
        return $sensorList;
    }

    public function pollForAlarms($virtualDeviceId) {
        $mainVirtualDevice = \Unified\Models\VirtualDevice\VirtualDevice::find($virtualDeviceId);
        if(!$mainVirtualDevice) {
            throw new InvalidArgumentException("Virtual device does not exist");
        }
        $mainNode = $mainVirtualDevice->virtualDeviceNode;
        $descendants = $mainNode->getChildren();
        $returnData = [];
        foreach($descendants as $child) {
            $virtualDevice = \Unified\Models\VirtualDevice\VirtualDevice::where('node_id', $child->id)->first();
            if(!$virtualDevice) {
                Log::warning("NodeID '{$child->id}' can't be linked back to VirtualDevice!");
                continue;
            }
            $returnData = array_merge( $returnData, $this->checkPropertyThresholds($virtualDevice));
        }
        return $returnData;
    }

    public function buildVirtualDeviceWizard($deviceClass)
    {
        $rootNode = (string) Input::get('rootNode');
        $deviceStructure = (array) Input::get('deviceStructure');
        $virtualDevice = new ExternalRTU($deviceClass);

        // Translate the property names into definition id's from the database
        foreach ($deviceStructure['children'] as &$childDevice) {
            if (array_key_exists('text_thresholds', $childDevice)) {
                foreach ($childDevice['text_thresholds'] as &$propertyValue) {
                    $propDefId = $virtualDevice->configureSensor($childDevice['real_device_node'], $propertyValue['property_definition']);
                    $propertyValue['property_definition'] = $propDefId;
                }
            }
            if (array_key_exists('property_thresholds', $childDevice)) {
                foreach ($childDevice['property_thresholds'] as &$propertyValue) {
                    $propDefId = $virtualDevice->configureSensor($childDevice['real_device_node'], $propertyValue['property_definition']);
                    $propertyValue['property_definition'] = $propDefId;
                }
            }
            if (array_key_exists('[property_value_translation', $childDevice)) {
                foreach ($childDevice['property_value_translation'] as &$propertyValue) {
                    $propDefId = $virtualDevice->configureSensor($childDevice['real_device_node'], $propertyValue['property_definition']);
                    $propertyValue['property_definition'] = $propDefId;
                }
            }
        }

        // Send the object to the builder
        $return = $this->buildVirtualDevice($rootNode, $deviceStructure);
        return $return;
    }

}
