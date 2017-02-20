<?php

namespace Modules\Snmpforward\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Unified\Models\SNMPDestination;
use Unified\Http\Helpers\GridParamParser;
use Illuminate\Support\Facades\Input;
use View;

class SNMPForwardController extends Controller {


	public function index() {
		$view = View::make('snmpforward::index');
        return $view->render();
	}


    public function all() {
        return SNMPDestination::all();
    }

    public function gridData() {
        // There will be a ton of GET parameters here, we need to handle sorting/filtering and
        // the acquisition of data

        $gpp = new GridParamParser(Input::get());
        $dest = new SNMPDestination();
        $output = $dest->getForGrid($gpp);
        return $output;
    }

    /**
     * Get information about an SNMP destination by ID
     * @param integer $id Primary key of the SNMPDestination record
     */
    public function getDestination($id) {
        $dest = SNMPDestination::find($id);
        return $dest->toArray();
    }

    public function save($id = null) {
        // Populate the SNMPDestination with the fields we have
        $dest = new SNMPDestination();

        $id = is_null($id) ? Input::get('id') : $id;

        if ($id) {
            $dest = SNMPDestination::find(Input::get('id'));
        }
        $dest->name             = Input::get('name');
        $dest->write_community  = Input::get('write_community');
        $dest->read_community   = Input::get('read_community');
        $dest->ip_address       = Input::get('ip_address');
        $dest->home_node_id     = 5000;
        $dest->format           = Input::get('format');
        $dest->snmp_version_id  = Input::get('snmp_version_id');
        $status = $dest->save();

        return [
            "status" => $status,
        ];

    }

    public function delete($id) {
        // Delete the SNMP destination
        $status = SNMPDestination::destroy($id);

        if ($status) {
            $output = 'true';
        } else {
            $output = 'false';
        }

        return [
            "status" => $output,
        ];
    }

}
