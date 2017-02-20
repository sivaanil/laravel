<?php

namespace Unified\Devices\GeneratorScheduling;

use Unified\TaskExecuter\JobInfo\GeneratorSchedulerInfo;
use Unified\Models\NetworkTree;
use Unified\TaskExecuter\TaskManager;
use Unified\TaskScheduler\ScheduleManager;
use Log;
use Carbon\Carbon;

//use some database models

/**
 * Description: GeneratorScheduler Class Holds The Function That The Generator Scheduler Job Calls
 * This Function Has Acces to the JOBINFO Object.
 *
 * @author Golnaz Rouhi <golnaz.rouhi@csquaredsystems.com>
 */
class GeneratorScheduler extends TaskManager
{

    private $schedulerInfo;
    private $deviceNode = null;
    public $controllerFile = null;
    public $commandResult;
    public $functionArguments;

    /*
     * This Dictionary Hold a map between the Generators Command and function calls
     * please Note that the order of varibales matter in this section
     */
    protected $Generator_Command_Map = array(
        'run' => array("functioncall" => "writeGeneratorState", "arguments" => array('NodeList', 'Duration', 'Command')),
        'stop' => array("functioncall" => "writeGeneratorState", "arguments" => array('NodeList', 'Command'))
    );

    public function __construct(GeneratorSchedulerInfo $schedulerInfo)
    {
        $this->schedulerInfo = $schedulerInfo;
    }

    public function ScheduleGenerator()
    {
        //Job Is Being Processed
        log::debug(__METHOD__ . "::ScheduleGenerator-> the JOB is being processed.");

        $this->deviceNode = NetworkTree::getDeviceNode($this->schedulerInfo->getNodeId());
        Log::debug(__METHOD__ . "Device Node Is:" . print_r($this->deviceNode, true) . "\n");

        if ($this->deviceNode === null) {
            throw new GeneratorScheduleException("GeneratorSchedule", "Device not found for node {$this->schedulerInfo->getNodeId()}");
        }

        //set the generatorInfo nodeList
        if (count($this->deviceNode) > 0) {
            $this->schedulerInfo->setNodeList($this->deviceNode);
        }

        //Find The Function
        if (array_key_exists($this->schedulerInfo->getCommand(), $this->Generator_Command_Map)) {
            $functionCall = $this->Generator_Command_Map[$this->schedulerInfo->getCommand()]['functioncall'];
        } else {
            throw new GeneratorScheduleException("GeneratorSchedule", "Command Doesn't Have The Function Call.");
        }

        $cswapiRoot = env('CSWAPI_ROOT');

        $this->controllerFile = $cswapiRoot . '/networking/class/GeneratorService.php';
        if (!file_exists($this->controllerFile)) {
            throw new GeneratorScheduleException("GeneratorSchedule", "Controller File doesn't exist on cswapi.");
        }

        require_once($cswapiRoot . "/common/doctrine.php");
        require_once($cswapiRoot . "/common/class/CssUtil.php");
        require_once($cswapiRoot . "/networking/class/DeviceAlarm.php");
        require_once($cswapiRoot . "/networking/class/DeviceAlarmTable.php");
        require_once($cswapiRoot . "/networking/class/NetworkTree.php");
        require_once($cswapiRoot . "/networking/class/NetworkTreeTable.php");

        set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/');
        require_once $this->controllerFile;

        //get the function arguments from the parent class
        parent::__construct($this->schedulerInfo, $this->Generator_Command_Map[$this->schedulerInfo->getCommand()]['arguments']);
        $this->functionArguments = $this->getRequiredArguments();

        $class = "GeneratorService";
        $this->commandResult = $this->executeTask($class, $functionCall);

        // Set Successes in the Database
        $now = Carbon::now();
        $scheduleManager = new ScheduleManager();
        if ($this->commandResult) {
            $scheduleManager->setCompletedTask($this->schedulerInfo, $now);
        }
    }

}
