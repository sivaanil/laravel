<?php

namespace Unified\Http\Controllers;

use Unified\Http\Requests;
use Unified\Http\Controllers\Controller;
use Unified\System\Network\LANConfig;
use Unified\System\Network\LANStatus;
use Unified\System\Network\ConsoleConfig;
use Unified\System\Network\ConsoleStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Validator;
use Input;

class LANSettingController extends Controller
{

    private $consoleConfig;
    private $lanConfig;
    
    public function __construct(ConsoleConfig $consoleConfig, LANConfig $lanConfig)
    {
        $this->consoleConfig = $consoleConfig;
        $this->lanConfig = $lanConfig;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = [];
        $data['ipAddress'] = $this->lanConfig->getIpAddress();
        $data['netmask'] = $this->lanConfig->getNetmask();
        $data['consoleIpAddress'] = $this->consoleConfig->getIpAddress();
        $data['consoleNetmask'] = $this->consoleConfig->getNetmask();

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
        $result = LANSettingController::validateForm();

        if ($result['success']) { // validition successful - store the record
            $this->lanConfig->setIpAddress(Input::get('ipAddress'));
            $this->lanConfig->setNetmask(Input::get('netmask'));
            $this->consoleConfig->setIpAddress(Input::get('consoleIpAddress'));
            $this->consoleConfig->setNetmask(Input::get('consoleNetmask'));
            try {
                $this->lanConfig->Save($this->consoleConfig);
                $this->consoleConfig->Save();
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
            'consoleIpAddress' => 'required|ip',
            'consoleNetmask' => 'required',
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
