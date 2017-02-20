<?php

namespace Unified\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Unified\Devices\Scan\DeviceScanner;
use Unified\Devices\Scan\ScanInfo;
use Unified\Jobs\Job;

class ScanDeviceJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ScanInfo $scanInfo)
    {
        $this->scanInfo = $scanInfo;
    }

    private $scanInfo;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $scanner = new DeviceScanner($this->scanInfo);
        $scanner->Scan();
    }
}
