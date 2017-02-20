<?php

namespace Unified\Jobs;

use Log;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Unified\TaskExecuter\JobInfo\GeneratorSchedulerInfo;
use Unified\Devices\GeneratorScheduling\GeneratorScheduler;
use Unified\Jobs\Job;

class GeneratorScheduleJob extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue; 
    private $schedulerInfo;

    /**
     *
     * @return void
     */
    public function __construct(GeneratorSchedulerInfo $schedulerInfo)
    {
        $this->schedulerInfo = $schedulerInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $scheduler = new GeneratorScheduler($this->schedulerInfo);
        $scheduler->ScheduleGenerator();
    }

    /**
     * Handle a job failure 
     * Gets called when the job fails
     * 
     */
    public function failed()
    {

        Log::info(__METHOD__ . "::End Of The Process, The Job Failed. Job Information" . print_r($this->schedulerInfo, true));
    }

}
