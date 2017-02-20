<?php

namespace Unified\Devices\Rebuild;

use DB;
use ReflectionFunction;
use stdClass;
use Unified\Devices\Rebuild\RebuildInfo;
use Unified\Models\Device;
use Unified\Models\DevicePortDef;
use Unified\Models\DeviceRebuild;
use Unified\Models\DeviceType;
use Unified\Models\NetworkTree;
use Unified\Models\NetworkTreeMap;

/**
 * Call cswapi to rebuild a Device
 *
 * @author Franz Honer <franz.honer@csquaredsystems.com>
 */
class DeviceRebuilder
{

    private $rebuildInfo;

    public function __construct(RebuildInfo $rebuildInfo)
    {
        $this->rebuildInfo = $rebuildInfo;
    }

    public function Rebuild()
    {
        $cswapiRoot = env('CSWAPI_ROOT');

        $deviceId = $this->rebuildInfo->getDeviceId();
        $typeId = Device::find($deviceId)->type_id;
        $deviceType = DeviceType::find($typeId);

        if ($deviceType === null) {
            throw new RebuildDeviceException("Device type {$typeId} not found.");
        }

        // try to get the build file from the device type table.
        $rebuildFile = $deviceType->rebuilder_file;

        if (empty($rebuildFile)) {
            throw new RebuildDeviceException("No rebuild file for {$typeId}.");
        }

        $rebuildFile = $cswapiRoot . '/networking/builders/' . $rebuildFile;

        if (!file_exists($rebuildFile)) {
            throw new RebuildDeviceException("Rebuild file: {$rebuildFile} does not exist.");
        }

        require_once($cswapiRoot . '/common/doctrine.php');
        require_once($cswapiRoot . '/networking/class/Build.php');
        require_once($cswapiRoot . '/networking/class/BuildTable.php');

        set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/');
        require_once $rebuildFile;

        // record start time

        // call the rebuilder's
        $rebuildResult = rebuildDevice($deviceId);

        $rebuild = DeviceRebuild::find($this->rebuildInfo->getRebuildId());

        // record end time
        $rebuild->end_timestamp = new \DateTime();


        $success = isset($rebuildResult['success']) && $rebuildResult['success'] == 1;

        if ($success) {
            $rebuild->success = 1;
            $rebuild->message = 'Rebuild complete.';
        }

        $rebuild->save();

    }
}
