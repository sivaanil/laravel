<?php

namespace Unified\Http\Controllers;

use Unified\Http\Requests;
use Unified\Http\Controllers\Controller;
use Unified\System\Network\WANConfig;
use Unified\System\Network\WANStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Validator;
use Input;

class WANSettingController extends Controller
{

    private $wanStatus;
    private $wanConfig;

    public function __construct(WANStatus $wanStatus, WANConfig $wanConfig)
    {
		// Example: This is how you inject the acl middleware into a resource.
        //$this->middleware('acl:wan-settings');
        $this->wanStatus = $wanStatus;
        $this->wanConfig = $wanConfig;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = [];
        $data['macAddress'] = $this->wanStatus->getMacAddress();
        $data['linkStatus'] = $this->wanStatus->getLinkStatus();
        $data['ipAddress'] = $this->wanConfig->getIpAddress();
        $data['dhcp'] = $this->wanConfig->getDhcp();
        $data['netmask'] = $this->wanConfig->getNetmask();
        $data['gateway'] = $this->wanConfig->getGateway();
        $data['dns1'] = $this->wanConfig->getDns1();
        $data['dns2'] = $this->wanConfig->getDns2();

        return [
            'data' => $data
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // validate the posted form data
        $result = WANSettingController::validateForm();

        if ($result['success']) { // validition successful - store the record
            $this->wanConfig->setIpAddress(Input::get('ipAddress'));
            $this->wanConfig->setDhcp((int) Input::get('dhcp'));
            $this->wanConfig->setNetmask(Input::get('netmask'));
            $this->wanConfig->setGateway(Input::get('gateway'));
            $this->wanConfig->setDns1(Input::get('dns1'));
            $this->wanConfig->setDns2(Input::get('dns2'));
            try {
                $this->wanConfig->Save();
            } catch (\Unified\System\Network\NetworkConfigException $e) {
                return [
                    'success' => false,
                    'errors' => ['formError' => $e->getMessage()]
                ];
            }
        } else { // validation failed
            return $result;
        }
        return ['success' => true];
    }

    public function validateForm()
    {
        // Setup the validator
        $rules = [
            'ipAddress' => 'required|ip',
            'netmask' => 'required',
            'gateway' => 'required|ip',
            'dns1' => 'required|ip',
            'dns2' => 'required|ip',
        ];
        $validator = Validator::make(Input::all(), $rules);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }
        return ['success' => true];
    }

}
