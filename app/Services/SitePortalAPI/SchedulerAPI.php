<?php

namespace Unified\Services\SitePortalAPI;

use Log;
use Unified\TaskScheduler\ScheduleManager;

class SchedulerAPI
{

    // This function determines what the user wants to do to the schedule they have submitted and sends it to the appropriate call
    public function handle($schedule, $execution)
    {
        $scheduleManager = new ScheduleManager();
        switch ($execution) {
            case 'CREATE':
                Log::debug(__METHOD__ . "::handling the SchedulerAPI CREATE", (array) $schedule);

                $readResults = $scheduleManager->readSchedule($schedule);
                if ($readResults !== null) {
                    // If the device already has a schedule created, send it back to SitePortal
                    $createResults = $readResults;
                } else {
                    // If the device does not, proceed with creating a new one
                    $createResults = $scheduleManager->createSchedule($schedule);
                }
                return $createResults;
            case 'READ':
                Log::debug(__METHOD__ . "::handling the SchedulerAPI READ", (array) $schedule);

                $readResults = $scheduleManager->readSchedule($schedule);
                return $readResults;
            case 'UPDATE':
                Log::debug(__METHOD__ . "::handling the SchedulerAPI UPDATE", (array) $schedule);

                $updateResults = $scheduleManager->updateSchedule($schedule);
                return $updateResults;
            case 'DELETE':
                // Currently, users are unable to delete SiteGate scheduled items through the SitePortal UI, they just disable them
                Log::debug(__METHOD__ . "::handling the SchedulerAPI DELETE", (array) $schedule);
                $deleteResults = $scheduleManager->deleteSchedule($schedule);
                return $deleteResults;
        }
    }

}
