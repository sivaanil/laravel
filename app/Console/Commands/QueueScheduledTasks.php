<?php

namespace Unified\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Unified\TaskExecuter\Worker;
use Unified\TaskScheduler\RunTimes\GeneratorRunTime;
use Unified\TaskScheduler\ScheduleManager;

/**
 * @author susannah.dube
 */
class QueueScheduledTasks extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'csquared:QueueScheduledTasks';
    protected $signature = 'csquared:QueueScheduledTasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue Scheduled Tasks.';

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
        // First we need to check if there are any tasks that need to be queued
        $scheduleManager = new ScheduleManager();
        $task = $scheduleManager->getNextTask();
        $now = Carbon::now();

        // We have a task, proceed to process it
        if ($task !== null) {
            // Send the task to the TaskExecuter
            $fields = ['nodeId' => $task->node_id, 'command' => $task->command, 'duration' => $task->duration];
            $worker = new Worker($task->type, $task->command, $fields);
            $worker->execute();

            // Mark the time the task was sent
            $scheduleManager->setQueuedTask($task, $now);

            // Get the run time class for the schedule type
            if ($task->type == "Generator") {
                $runTime = new GeneratorRunTime($task);
            }

            $nextRun = $runTime->getRunTime();
            $nextRunTime = $runTime->validateExpirationTime($nextRun);
            $scheduleManager->updateRunTime($task, $nextRunTime, $now);
        }
    }

}
