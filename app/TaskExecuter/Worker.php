<?php

namespace Unified\TaskExecuter;

use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Unified\TaskExecuter\JobInfo\GeneratorSchedulerInfo;
use Unified\Jobs;
//use any models?

/**
 * Description: GeneratorSchedulerWorker uses DispatchesJobs to push the schduling jobs into the queue
 *
 * @author Golnaz Rouhi <golnaz.rouhi@csquaredsystems.com>
 */
class Worker
{

    use DispatchesJobs;

    /**
     * worker variables
     */
    public $worker_type;
    public $command;
    public $info_class;
    public $job_class;
    public $fields;

    const Default_Queue = "default";
    const Daemon_Queue = "daemonBeanstalkd";

    static $Info_Class = "infoClass";
    static $Job_Class = "jobClass";
    public $registered_classInfo = array(
        'Generator' => array("infoClass" => "Unified\TaskExecuter\JobInfo\GeneratorSchedulerInfo", "jobClass" => "Unified\Jobs\GeneratorScheduleJob"),
        'Notification' => array("infoClass" => "NotificationInfo", "jobClass" => 'NotificationJob'));

    public function __construct($worker_type, $command, $fields = null)
    {
        if (empty($worker_type) || empty($command)) {
            Log::error(__METHOD__ . "::Required variables Type/Command Could Not Be Found");
            exit();
        }

        //assign the class variables
        $this->worker_type = $worker_type;
        $this->command = $command;

        if (array_key_exists($this->worker_type, $this->registered_classInfo) === FALSE) {
            Log::error(__METHOD__ . "::Info Class For Request Type" . $worker_type . " Doesn't Exist.");
            exit();
        }

        //get the info class
        $this->info_class = $this->registered_classInfo[$this->worker_type][static::$Info_Class];
        $this->job_class = $this->registered_classInfo[$this->worker_type][static::$Job_Class];
        $this->fields = $fields;
    }

    /**
     * Create A New Worker And Push The Job To The Queue
     *
     */
    public function execute()
    {
        $infoClass = $this->info_class;
        $jobInfo = new $infoClass($this->fields);

        $jobClass = $this->job_class;
        Log::debug(__METHOD__ . "::Pushing a" . $jobClass . " To The Queue.");
        $job = (new $jobClass($jobInfo))->onQueue('daemonBeanstalkd');
        //push the job to the queue
        $this->dispatch($job);

        return $jobInfo;
    }

}
