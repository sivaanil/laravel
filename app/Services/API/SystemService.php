<?php

namespace Unified\Services\API;

use Unified\Http\Controllers\system\SettingsController;
use Unified\Models\SystemStats;
use Unified\Services\API\ServiceResponse;
use Unified\System\Network\WANStatus;

/**
 * Handles system information related functions for the API.
 */
class SystemService extends APIService {
    
    /**
     * Property Service constructor.
     *
     * @param ServiceRequest $request
     *            Service request
     */
    public function __construct(ServiceRequest $request) {
        parent::__construct ( $request, null );
    }
    
    /**
     * Return system information
     *
     * @return Service response object
     */
    public function getSystem() {
        $wanStatus = new WANStatus ();
        $settingsController = new SettingsController ( $wanStatus );
        $settings = SystemStats::orderBy ( 'timestamp', 'desc' )->first ();
        $retVal ['id'] = sha1 ( $wanStatus->getMacAddress () );
        $retVal ['version'] = $settingsController->getUiVersion ( base_path () );
        $retVal ['diskUsage'] = $settings->dskAvail;
        $retVal ['memoryUsage'] = $settings->memTotalReal;
        $retVal ['cpuUsage'] = $settings->ssCpuUser;
        return new ServiceResponse ( ServiceResponse::SUCCESS, $retVal );
    }
}
