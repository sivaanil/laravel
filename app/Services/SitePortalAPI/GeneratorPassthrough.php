<?php

namespace Unified\Services\SitePortalAPI;

use DB;

class GeneratorPassthrough
{

    public function handle($deviceList, $duration, $setting)
    {
        
        require_once ENV('CSWAPI_ENV') . '/networking/class/GeneratorService.php';
        $generatorService = new \GeneratorService();
        return $generatorService->WriteGeneratorState($deviceList, $duration, $setting);
        
    }

}
