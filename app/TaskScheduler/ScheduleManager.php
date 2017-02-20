<?php

namespace Unified\TaskScheduler;

use Log;
use Carbon\Carbon;
use Unified\Models\Schedule;
use Unified\TaskScheduler\RunTimes\GeneratorRunTime;

/**
 * @author susannah.dube
 */
class ScheduleManager
{

    public function readSchedule($scheduleObject)
    {
        Log::debug(__METHOD__ . "::Reading", (array) $scheduleObject);

        $qSchedule = Schedule::where('node_id', $scheduleObject->node_id)->first();

        if ($qSchedule !== null) {
            return $qSchedule;
        }
    }

    public function createSchedule($scheduleObject)
    {
        Log::debug(__METHOD__ . "::Creating", (array) $scheduleObject);

        $now = Carbon::now();

        // Get the next run time
        if ($scheduleObject->type == "Generator") {
            $runTime = new GeneratorRunTime($scheduleObject);
        }

        $nextRun = $runTime->getRunTime();
        $nextRunTime = $runTime->validateExpirationTime($nextRun);

        Schedule::insert([
            'node_id' => $scheduleObject->node_id,
            'type' => $scheduleObject->type,
            'command' => $scheduleObject->command,
            'next_run_time' => $nextRunTime,
            'mode' => $scheduleObject->mode,
            'enabled' => $scheduleObject->enabled,
            'duration' => $scheduleObject->duration,
            'recurrence' => $scheduleObject->recurrence,
            'start_time' => $scheduleObject->start_time,
            'stop_time' => $scheduleObject->stop_time,
            'day_of_week' => $scheduleObject->day_of_week,
            'month_of_year' => $scheduleObject->month_of_year,
            'on_the_mode' => $scheduleObject->on_the_mode,
            'each_mode' => $scheduleObject->each_mode,
            'repeat_mode' => $scheduleObject->repeat_mode,
            'date_updated' => $now,
            'queued' => "0000-00-00 00:00:00",
            'executed' => "0000-00-00 00:00:00"
        ]);

        $qResult = $this->readSchedule($scheduleObject);
        return $qResult;
    }

    public function updateSchedule($scheduleObject)
    {
        Log::debug(__METHOD__ . "::Updating", (array) $scheduleObject);

        // Get the schedule to be updated
        $schedule = $this->readSchedule($scheduleObject);

        if ($schedule !== null) {
            $now = Carbon::now();

            // Get the next run time
            if ($scheduleObject->type == "Generator") {
                $runTime = new GeneratorRunTime($scheduleObject);
            }

            $nextRun = $runTime->getRunTime();
            $nextRunTime = $runTime->validateExpirationTime($nextRun);

            Schedule::where('id', $schedule->id)
                    ->update([
                        'type' => $scheduleObject->type,
                        'command' => $scheduleObject->command,
                        'next_run_time' => $nextRunTime,
                        'mode' => $scheduleObject->mode,
                        'enabled' => $scheduleObject->enabled,
                        'duration' => $scheduleObject->duration,
                        'recurrence' => $scheduleObject->recurrence,
                        'start_time' => $scheduleObject->start_time,
                        'stop_time' => $scheduleObject->stop_time,
                        'day_of_week' => $scheduleObject->day_of_week,
                        'month_of_year' => $scheduleObject->month_of_year,
                        'on_the_mode' => $scheduleObject->on_the_mode,
                        'each_mode' => $scheduleObject->each_mode,
                        'repeat_mode' => $scheduleObject->repeat_mode,
                        'date_updated' => $now,
                        'queued' => "0000-00-00 00:00:00",
                        'executed' => "0000-00-00 00:00:00"
            ]);
        } else {
            // The schedule does not already exist for some reason, create it
            $this->createSchedule($scheduleObject);
        }

        $qResult = $this->readSchedule($scheduleObject);
        return $qResult;
    }

    public function deleteSchedule($scheduleObject)
    {
        $schedule = $this->readSchedule($scheduleObject);
        $scheduleId = $schedule->id;

        Schedule::where('id', $scheduleId)
                ->delete();

        return true;
    }

    // Get the next task to pass to the TaskExecuter for Queuing
    public function getNextTask()
    {
        $now = Carbon::now();
        $nextTask = Schedule::where('next_run_time', '<', $now)
                ->where('enabled', '=', 1)
                ->orderBy('next_run_time', 'asc')
                ->first();
        return $nextTask;
    }

    // Set the task's queue time
    public function setQueuedTask($task, $now)
    {
        Schedule::where('id', $task->id)
                ->update([
                    'queued' => $now,
                    'date_updated' => $now
        ]);
    }

    // Set the task's complete time
    public function setCompletedTask($task, $now)
    {
        Schedule::where('node_id', $task->nodeId)
                ->update([
                    'executed' => $now,
                    'date_updated' => $now
        ]);

        Schedule::where('node_id', $task->nodeId)->increment('repeat_counter');
    }

    // Update the task's next run time
    public function updateRunTime($task, $runTime, $now)
    {
        Schedule::where('id', $task->id)
                ->update([
                    'next_run_time' => $runTime,
                    'date_updated' => $now
        ]);
    }

}
