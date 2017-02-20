<?php

namespace Unified\TaskExecuter;

use Log;
/*
 * This base Task Manager Class includes the functions that each task
 * would have to call to get the required variables and execute the task.
 * 
 */

/**
 * Description of TaskManager
 *
 * @author Golnaz.Rouhi
 */
abstract class TaskManager
{

    //put your code here
    public $args = array();
    public $infoClass;
    public $retunedValues = array();

    const GET = 'get';

    protected function __construct($infoClass, $arguments)
    {
        $this->infoClass = $infoClass;
        $this->args = $arguments;
    }

    public function getRequiredArguments()
    {

        foreach ($this->args as $arg) {

            //call the get function in the info class
            $functionName = self::GET . $arg;
            if (method_exists($this->infoClass, $functionName)) {
                $argValue = $this->infoClass->{$functionName}();
                array_push($this->retunedValues, $argValue);
            } else {
                Log::error(__METHOD__ . "Invalid data passed to the " . $functionName);
                throw new InvalidArgumentException("Invalid data passed to the " . $functionName);
            }
        }
        Log::debug(__METHOD__ . "Required Variables For The Function Is," . print_r($this->retunedValues, true) . "\n");
        return $this->retunedValues;
    }

    public function executeTask($class, $funtionCall)
    {
        $myClass = new $class();
        Log::debug(__METHOD__ . "Calling Class: $class Function: $funtionCall with variables $this->retunedValues \n");
        $result = call_user_func_array(array($myClass, $funtionCall), $this->retunedValues);
        return $result;
    }

}
