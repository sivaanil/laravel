<?php namespace Modules\Sensors\Http\Controllers;

use Event;
use Pingpong\Modules\Routing\Controller;
use Unified\Http\Helpers\GridParamParser;
use Illuminate\Support\Facades\Input;
use Unified\Models\DeviceProp;
use Modules\Sensors\Events as sensorEvents;

use View;
use Log;

// Classes for interop with models
use Modules\Sensors\Entities\ContactClosure;
use Modules\Sensors\Entities\AnalogSensor;
use Modules\Sensors\Entities\Relay;


class SensorsController extends Controller {

	public function index() {
		$view = View::make('sensors::index');
        return $view->render();
	}

    public function allcontactClosures($nodeId) {
        $cc = new ContactClosure();
        return $cc->findByNodeId($nodeId);
    }

    public function ccGridData($nodeId = null) {
        $output = array();

        Log::info($nodeId);

        // Fetch contact closure grid data
        if ($nodeId) {
            $gpp = new GridParamParser(Input::get());
            $cc = new ContactClosure();
            $output = $cc->getForGrid($gpp, $nodeId);
        }

        return $output;
    }

    /**
     * Fetches details about a single contact closure object
     * @param int $id The device_id of the contact closure to fetch details for.
     * @return Array
     */
    public function getContactClosure($id) {

        $cc = ContactClosure::where('id', '=', $id)
            ->select('id', 'name', 'type_id')
            ->first();

        $props = DeviceProp::getProps($cc->id);
        $properties = [];
        foreach ($props as $property) {
            $properties[$property->variable_name] = $property->value;
        }


        return [
            'name'       => $cc->name,
            'id'         => $cc->id,
            'properties' => $properties,
        ];

    }

    function saveContactClosure() {
        $fields = Input::all();

        // Update the device (name is the only thing we can update on the device side)
        $cc = ContactClosure::find($fields['id']);
        $cc->name = $fields['name'];


        $cc->save();

        // Update properties for this contact closure in the database
        $cc->setProperties([
            'DI Register Alarm Alias'   => $fields['alarmStateAlias'],
            'DI Register Description'   => $fields['name'],
            'DI Register Normal Alias'  => $fields['normalStateAlias'],
            'DI Register Normal State'  => $fields['normalState'],
            'DI Register Severity'      => $fields['alarmSeverity'],
        ]);

	//Fire this Event to update the client side
        Log::info("update is done! updating the client side now.");
	Event::fire(new sensorEvents\SensorEvent());
	  

        // Get the controller for this device type TODO - Talk to Tareq on how to use the shjController.

        // Update the properties via the controller

        // Return positive status response
        return [
            'status' => 1,
        ];
    }

    public function  analogSensorGridData() {

    }

    public function relayGridData() {

    }
}
