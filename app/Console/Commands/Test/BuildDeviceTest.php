<?php

namespace Unified\Console\Commands\Test;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Unified\Devices\Build\BuildInfo;
use Unified\Devices\Build\BuildManager;
use Unified\Devices\Build\BuildProgress;
use Unified\Devices\Build\DeviceBuilder;
use Unified\Jobs\BuildDeviceJob;

class BuildDeviceTest extends Command
{

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'css:builddevicetest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test device build.';

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
        $info = new BuildInfo();

        $info->setName('Frylock');
        $info->setIpAddress('192.168.11.227');
        $info->setParentNodeId(5000);
        $info->setTypeId(1179);
        $info->setPorts(['ping' => 1]);
        $info->setReadCommunity('public');
        $info->setWriteCommunity('private');
        $info->setSnmpVersion('2c');

        $deviceToken = $info->getDeviceToken();

        $this->dispatch(new BuildDeviceJob($info));
        $i = 0;

        do {
            $bm = new BuildManager();
            $result = $bm->getBuildProgress($deviceToken);
            print_r($result);
            usleep(250000);

//            if ($result->getStatus() === BuildProgress::STATUS_BUILDING) {
//                $i++;
//                if ($i === 5) {
//                    $bm->CancelBuild($deviceToken);
//                }
//            }

        } while ($result->getStatus() === BuildProgress::STATUS_NOT_FOUND || $result->getStatus() === BuildProgress::STATUS_BUILDING);
    }

}
