<?php

namespace Unified\TaskScheduler\RunTimes;

use Log;

/**
 * @author susannah.dube
 */
class GeneratorRunTime
{

    public $mode;
    public $repeat_num;
    public $repeat_mode;
    public $start_time;
    public $stop_time;
    public $each_mode;
    public $recurrence;
    public $day_of_week;
    public $month_of_year;
    public $on_the_mode;
    public $repeat_counter;

    public function __construct($taskObject)
    {
        $repeatMode = explode(" ", $taskObject->repeat_mode);
        $this->repeat_mode = $repeatMode[0];
        if (key_exists(1, $repeatMode)) {
            $this->repeat_num = $repeatMode[1];
        }
        $this->mode = $taskObject->mode;
        $this->start_time = strtotime($taskObject->start_time);
        $this->stop_time = strtotime($taskObject->stop_time);
        $this->each_mode = $taskObject->each_mode;
        $this->recurrence = $taskObject->recurrence;
        $this->day_of_week = $taskObject->day_of_week;
        $this->month_of_year = $taskObject->month_of_year;
        $this->on_the_mode = $taskObject->on_the_mode;
        $this->repeat_counter = $taskObject->repeat_counter;
    }

    public function getRunTime()
    {
        Log::debug(__METHOD__ . "::Getting the next run time");
        $now = strtotime("now");

        if ($this->mode == 'NONE') {
            // The schedule will run at the start time, all other input is irrelevant
            if ($now < $this->start_time) {
                $nextRunTime = $this->start_time;
            } else {
                // The schedule is already past its run time, it is no longer running
                $nextRunTime = "0000-00-00 00:00:00";
            }
            return $nextRunTime;
        } else if ($this->mode == 'DAILY') {
            if ($now < $this->start_time) {
                $nextRunTime = $this->start_time;
            } else {
                // The schedule will run every X number of days
                $checkTime = strtotime(date("Y-m-d", $now) . " " . date("H:i:s", $this->start_time));
                if ($now < $checkTime) {
                    $nextRunTime = date("Y-m-d H:i:s", $checkTime);
                } else {
                    $increment = " + {$this->recurrence} days";
                    $nextRunTime = date("Y-m-d H:i:s", strtotime($increment, $checkTime));
                }
            }
            return $nextRunTime;
        } else if ($this->mode == 'WEEKLY') {
            // The schedule will run weekly on specific days
            $nextRunTime = $this->calcWeekly();
            return $nextRunTime;
        } else if ($this->mode == 'MONTHLY') {
            // MONTHLY can have additional options, it can have an ON THE or an EACH
            if ($this->on_the_mode !== null) {
                // Schedule runs one specific day according to monthly recurrence
                $nextRunTime = $this->calcMonthlyOnThe();
            } else if ($this->each_mode !== null) {
                // Schedule runs on specific dates according to monthly recurrence
                $nextRunTime = $this->calcMonthlyEach();
            } else {
                // Schedule doesn't have any info set, it is no longer running
                $nextRunTime = "0000-00-00 00:00:00";
            }
            return $nextRunTime;
        } else if ($this->mode == 'YEARLY') {
            // YEARLY doesn't have as many options
            if ($this->on_the_mode !== null) {
                $nextRunTime = $this->calcYearly();
            } else {
                // Schedule doesn't have any info set, it is no longer running
                $nextRunTime = "0000-00-00 00:00:00";
            }
            return $nextRunTime;
        }
    }

    public function validateExpirationTime($nextRunTime)
    {
        Log::debug(__METHOD__ . "::Validating schedule expiration");
        if ($this->repeat_mode == "NEVER") {
            // Schedule never ends, so next run time will always be ok
            Log::debug(__METHOD__ . "::Schedule runs indefinitely");
            $nextRun = $nextRunTime;
        } else if ($this->repeat_mode == "AFTER") {
            // Schedule ends after X number of runs, check how many times it has run
            if ($this->repeat_counter >= $this->repeat_num) {
                Log::debug(__METHOD__ . "::Schedule has run maximum number of times");
                $nextRun = "0000-00-00 00:00:00";
            } else {
                Log::debug(__METHOD__ . "::Schedule still has more iterations");
                $nextRun = $nextRunTime;
            }
        } else if ($this->repeat_mode == "ON DATE") {
            // Schedule ends on the saved date, make sure it is before then, otherwise schedule is dead
            $run = strtotime($nextRunTime);
            $stop = strtotime($this->stop_time);
            if ($run > $stop) {
                Log::debug(__METHOD__ . "::Schedule has reached the end date");
                $nextRun = "0000-00-00 00:00:00";
            } else {
                Log::debug(__METHOD__ . "::Schedule has not yet reached the end date");
                $nextRun = $nextRunTime;
            }
        }
        return $nextRun;
    }

    public function calcWeekly()
    {
        Log::debug(__METHOD__ . "::Calculating weekly run time");
        $now = strtotime("now");
        $dayList = explode(", ", $this->day_of_week);
        $dayKey = array_keys($dayList, 1);

        if (count($dayKey) < 1) {
            // No days have been selected, schedule is not running
            $nextRunTime = "0000-00-00 00:00:00";
            return $nextRunTime;
        } else {
            // Schedule is running, find the next run date after the start day
            if ($now > $this->start_time) {
                $nextDay = $this->getNextDay($now, $this->recurrence, $dayList);
                $date = strtotime("1 {$nextDay}", $now);
                $nextRunTime = date("Y-m-d", $date) . " " . date("H:i:s", $this->start_time);
                return $nextRunTime;
            } else {
                $nextDay = $this->getNextDay($this->start_time, $this->recurrence, $dayList);
                $date = strtotime("1 {$nextDay}", $this->start_time);
                $nextRunTime = date("Y-m-d", $date) . " " . date("H:i:s", $this->start_time);
                return $nextRunTime;
            }
        }
    }

    public function getNextDay($currentDay, $recurrence, $list)
    {
        $date = date("w", $currentDay);
        $count = count($list) + 1;
        $week = 0;
        $day = 0;
        for ($i = $date; $i < $count; $i++) {
            if ($i == 7) {
                $i = 0;
                $week += $recurrence;
            }
            if ($list[$i] == 1) {
                $increment = "+ {$week} weeks + {$day} days";
                $nextDay = date("l", strtotime($increment, $currentDay));
                return $nextDay;
            }
            $day++;
        }
    }

    public function calcMonthlyOnThe()
    {
        Log::debug(__METHOD__ . "::Calculating monthly run time");
        $now = strtotime("now");

        // First we have to start at the beginning of the month to have the mode work correctly
        $thisMonthsStart = strtotime("FIRST DAY OF THIS MONTH");

        // STRTOTIME starts searching just after midnight of the first day of the month, so it DOESN'T count that first
        // occurrence if its the same day as the one you are looking for, we have to handle that with an adjustment
        $thisMonthsDate = $this->getAccurateDate($this->on_the_mode, $thisMonthsStart);

        if ($now < $thisMonthsDate) {
            $nextDate = date("Y-m-d", $thisMonthsDate);
            // Unfortunately, we lose the time setting when we get the first day of the month, so put it back
            $nextTime = date("H:i:s", $this->start_time);
            $nextRunTime = $nextDate . " " . $nextTime;
        } else {
            // We need to jump forward to the next month the schedule runs
            $runMonthStart = strtotime("+ " . $this->recurrence . " month", $thisMonthsStart);
            $runMonthDate = $this->getAccurateDate($this->on_the_mode, $runMonthStart);
            $nextDate = date("Y-m-d", $runMonthDate);
            // Unfortunately, we lose the time setting when we get the first day of the month, so put it back
            $nextTime = date("H:i:s", $this->start_time);
            $nextRunTime = $nextDate . " " . $nextTime;
        }
        return $nextRunTime;
    }

    public function getAccurateDate($interval, $startDate)
    {
        $date = strtotime($interval, $startDate);
        $onMode = explode(" ", $interval);
        if ($onMode[1] == strtoupper(date("l", $startDate))) {
            $date = strtotime("- 7 days", $date);
        }
        return $date;
    }

    public function calcMonthlyEach()
    {
        Log::debug(__METHOD__ . "::Calculating monthly run time");
        // The variable each_mode will contain a list of the days the generator should run
        $dates = explode(",", $this->each_mode);
        $count = count($dates);

        if ($count < 1) {
            // No dates have been selected, schedule is not running
            $nextRunTime = "0000-00-00 00:00:00";
            return $nextRunTime;
        } else {
            // First thing we want to do is get the first "date" from the count list that is after the start date of the schedule
            $day = strftime("%d", $this->start_time);
            $month = strftime("%m", $this->start_time);
            $start = $count + 1;
            for ($i = 1; $i < $start; $i++) {
                if ($i == $count) {
                    // Setting to 1 instead of 0, because 0 is always the first day in the list
                    $i = 1;
                    $month += $this->recurrence;
                }
                $day = $dates[$i];
                $checkDate = mktime(strftime("%H", $this->start_time), strftime("%M", $this->start_time), strftime("%S", $this->start_time), $month, $day, strftime("%y", $this->start_time));
                if ($checkDate > $this->start_time) {
                    // If the check date is greater than today
                    $nextRunTime = date("Y-m-d H:i:s", $checkDate);
                    return $nextRunTime;
                }
            }
        }
    }

    public function calcYearly()
    {
        Log::debug(__METHOD__ . "::Calculating yearly run time");
        // Schedule runs one specific day of month according to yearly recurrence
        $months = explode(", ", $this->month_of_year);
        $count = count($months);

        $monthKey = array_keys($months, 1);
        if (count($monthKey) < 1) {
            // No days have been selected, schedule is not running
            $nextRunTime = "0000-00-00 00:00:00";
            return $nextRunTime;
        } else {
            $day = 1;
            $month = 1;
            $start = $count + 1;
            $year = strftime("%y", $this->start_time);

            for ($i = 0; $i < $start; $i++) {
                if ($i == 12) {
                    $i = 0;
                    $year += $this->recurrence;
                }
                if ($months[$i] == 1) {
                    $month = $i;
                    $checkDate = mktime(strftime("%H", $this->start_time), strftime("%M", $this->start_time), strftime("%S", $this->start_time), $month, $day, $year);
                    // Unfortunately, we lose the time setting when we ask for a specific day name, so put it back 
                    $nextDate = date("Y-m-d", strtotime($this->on_the_mode, $checkDate));
                    $nextTime = date("H:i:s", $this->start_time);
                    $finalCheck = strtotime($nextDate . " " . $nextTime);
                    // If the check date is greater than today
                    if ($finalCheck > $this->start_time) {
                        $nextRunTime = date("Y-m-d H:i:s", $finalCheck);
                        return $nextRunTime;
                    }
                }
            }
        }
    }

}
