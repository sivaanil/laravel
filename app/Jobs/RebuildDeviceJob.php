<?php

namespace Unified\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Unified\Devices\Rebuild\RebuildInfo;
use Unified\Devices\Rebuild\DeviceRebuilder;
use Unified\Jobs\Job;

class RebuildDeviceJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RebuildInfo $rebuildInfo)
    {
        $this->rebuildInfo = $rebuildInfo;
    }

    private $rebuildInfo;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rebuilder = new DeviceRebuilder($this->rebuildInfo);
        $rebuilder->Rebuild();
    }
}
