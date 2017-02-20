<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Unified\VirtualDeviceManager\VirtualDeviceType;

use Unified\Models\Device;
use Unified\Models\NetworkTree;
use Unified\Models\DeviceProp;
use Unified\Models\DevicePropDef;
use Unified\Models\NetworkTreeMap;
/**
 * Description of ExternalRTU
 *
 * @author Susannah.Dube
 */
class ExternalRTU
{

    public $classId;
    public $propertyTranslations = array(
        "1620" => array(// SHJ - Contact Closure
            "generator_running" => "DI Value"),
        "1621" => array(// SHJ - Relay
            "setRelayState" => "RE Value"),
        "1622" => array(// SHJ - Analog Input
            "volume_percentlevel" => "AI Value")
    );

    public function __construct($deviceClass)
    {
        $this->classId = $deviceClass;
    }

    public function configureSensor($sensorNodeId, $propertyName)
    {
        $node = NetworkTree::find($sensorNodeId);
        $device = Device::find($node->device_id);
        $propDefId = DevicePropDef::getPropDefID($sensorNodeId, $this->propertyTranslations[$device->type_id][$propertyName]);

        if (is_object($propDefId)) {
            return $propDefId->id;
        } else {
            return null;
        }
    }
    
    public function deconfigureSensors($node)
    {
        // Get the child devices
        $children = NetworkTreeMap::where('node_map', 'LIKE', '%.'.$node->node_id.'.%')->get(array('node_id'));
        foreach ($children as $child) {
            $childNode = NetworkTree::find($child->node_id);
            $device = Device::find($childNode->device_id);
            if ($device->type_id !== "5063") {
                if ($device->type_id === '1620') {
                    $key = array_search('DI Value', $this->propertyTranslations[$device->type_id]);
                    $propDef = DevicePropDef::getPropDefID($child->node_id, $key);
                    DeviceProp::deleteVirtualDeviceProp($propDef->id, $device->id);
                }
                if ($device->type_id === '1621') {
                    $key = array_search('RE Value', $this->propertyTranslations[$device->type_id]);
                    $propDef = DevicePropDef::getPropDefID($child->node_id, $key);
                    DeviceProp::deleteVirtualDeviceProp($propDef->id, $device->id);
                }
                if ($device->type_id === '1622') {
                    $key = array_search('AI Value', $this->propertyTranslations[$device->type_id]);
                    $propDef = DevicePropDef::getPropDefID($child->node_id, $key);
                    DeviceProp::deleteVirtualDeviceProp($propDef->id, $device->id);
                }
            }
        }
    }

    public function createGeneratorProps($child, $nodeId)
    {
        $parentNode = NetworkTree::find($nodeId);
        foreach ($child['children'] as $subDevice) {
            $childNode = NetworkTree::find($subDevice['real_device_node']);
            if (array_key_exists('property_value_translation', $subDevice)) {
                foreach ($subDevice['text_thresholds'] as $propertyValue) {
                    $propDef = DevicePropDef::find($propertyValue['property_definition']);
                    $this->configureProps($parentNode, $propDef, $childNode);
                }
            }
            if (array_key_exists('property_thresholds', $subDevice)) {
                foreach ($subDevice['text_thresholds'] as $propertyValue) {
                    $propDef = DevicePropDef::find($propertyValue['property_definition']);
                    $this->configureProps($parentNode, $propDef, $childNode);
                }
            }
            if (array_key_exists('text_thresholds', $subDevice)) {
                foreach ($subDevice['text_thresholds'] as $propertyValue) {
                    $propDef = DevicePropDef::find($propertyValue['property_definition']);
                    $this->configureProps($parentNode, $propDef, $childNode);
                }
            }
        }
    }

    public function configureProps($parentNode, $propDef, $childNode)
    {
        if ($propDef) {
            $key = array_search($propDef->variable_name, $this->propertyTranslations[$propDef->device_type_id]);
            $newPropDefId = DevicePropDef::createBaseSensorProperty($propDef->id, $key);
            DeviceProp::createVirtualDeviceProp($newPropDefId, $parentNode->device_id);
            DeviceProp::createVirtualDeviceProp($newPropDefId, $childNode->device_id);
        }
    }

}
