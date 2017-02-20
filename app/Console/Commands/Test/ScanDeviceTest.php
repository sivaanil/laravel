<?php

namespace Unified\Console\Commands\Test;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Unified\Devices\Scan\ScanInfo;
use Unified\Devices\Scan\ScanManager;
use Unified\Devices\Scan\ScanProgress;
use Unified\Devices\Scan\ScanStarter;
use Unified\Jobs\ScanDeviceJob;

class ScanDeviceTest extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'css:scandevicetest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test device scan.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $scanStarter = new ScanStarter();
        $scanInfo = $scanStarter->StartScan(5001, ScanInfo::SCAN_TYPE_ALARM);

        // scanId is needed to get progress or cancel a scan
        $scanId = $scanInfo->getScanId();
        
        $i = 0;
        
        do {
            $sm = new ScanManager();
            $result = $sm->getScanProgress($scanId);
            print_r($result);
            usleep(250000);

//            if ($result->getStatus() === ScanProgress::STATUS_SCANNING) {
//                $i++;
//                if ($i === 5) {
//                    $sm->CancelScan($scanId);
//                }
//            }

        } while ($result->getStatus() === ScanProgress::STATUS_NOT_FOUND
                || $result->getStatus() === ScanProgress::STATUS_STARTING
                || $result->getStatus() === ScanProgress::STATUS_SCANNING);

    }
}
