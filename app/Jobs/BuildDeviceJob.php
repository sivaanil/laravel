<?php

namespace Unified\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Unified\Devices\Build\BuildInfo;
use Unified\Devices\Build\DeviceBuilder;
use Unified\Jobs\Job;

class BuildDeviceJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BuildInfo $buildInfo)
    {
        $this->buildInfo = $buildInfo;
    }

    private $buildInfo;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $builder = new DeviceBuilder($this->buildInfo);
        $builder->Build();
    }
}
