<?php

/**
 * @author susannah.dube

 * This is a test script that when run returns the next time the schedule needs to run
 * for command line testing on calculating the next run time, just modify the $taskObject
 */

namespace Unified\Console\Commands\Test;

use Illuminate\Console\Command;
use Unified\TaskScheduler\RunTimes\GeneratorRunTime;

class GetTimeTest extends Command
{

    protected $signature = 'csquared:GetTimeTest';
    protected $description = 'Quick test script for getting the next run time for a schedule object.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $taskObject = new \stdClass;

        // Next Run Time variables
        $taskObject->start_time = "2016-05-13 13:00:00";
        $taskObject->recurrence = "2";
        $taskObject->day_of_week = "0, 0, 0, 0, 0, 0, 0";
        $taskObject->month_of_year = "0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0";
        $taskObject->on_the_mode = null;
        $taskObject->each_mode = null;
        $taskObject->mode = "DAILY";

        // Expiration Validation variables
        $taskObject->repeat_counter = 0;
        $taskObject->repeat_mode = "AFTER 5";
        $taskObject->stop_time = "0000-00-00 00:00:00";

        // Type of Schedule
        $type = "Generator";

        // Get the run time calculator for the type of schedule we have
        if ($type == "Generator") {
            $runTime = new GeneratorRunTime($taskObject);
        }

        // Get the next time the schedule should run
        $nextRunTime = $runTime->getRunTime();
        echo $nextRunTime . "\n\n";
    }

}
