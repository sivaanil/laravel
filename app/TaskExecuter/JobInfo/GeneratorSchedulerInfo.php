<?php

namespace Unified\TaskExecuter\JobInfo;

/**
 * This Class Holds The Required Inofrmation For The Task Type = Generator.
 * Every Task Type Generator That Gets Pushed To The Queue Should Have The Reqiored Fields.
 * 
 * @author Golnaz Rouhi <Golnaz.Rouhi@csquaredsystems.com>
 */
class GeneratorSchedulerInfo
{

    /**
     * Generator Info variables
     */
    public $nodeId;
    public $command;
    public $duration;
    public $nodeList = array();
    public $requiredFields = array(
        'nodeId',
        'command'
    );
    public $errorArray = array();

    public function __construct($fields)
    {
        $validData = $this->validateData($fields);

        if (!$validData) {
            throw new InvalidArgumentException("Invalid data passed to BaseAlarmRule constructor:" . $this->errorArray);
        }

        $this->nodeId = $fields['nodeId'];
        $this->command = $fields['command'];

        if (array_key_exists('duration', $fields)) {

            $this->duration = $fields['duration'];
        }
    }

    protected function validateData($data)
    {
        $self = $this;
        if (in_array(FALSE, array_map(function($req) use ($data, $self) {
                            if (!array_key_exists($req, $data)) {
                                $self->errorArray .= "\n\tInput Missing Required Field '$req'";
                                return false;
                            } else {
                                return true;
                            }
                        }, $this->requiredFields)
                )
        ) {
            return false;
        } else {
            return true;
        }
    }

    public function setNodeId($id)
    {

        $this->nodeId = $id;
    }

    public function getNodeId()
    {

        return $this->nodeId;
    }

    public function setCommand($command)
    {

        $this->command = $command;
    }

    public function getCommand()
    {

        return $this->command;
    }

    public function setDuration($duration)
    {

        $this->duration = $duration;
    }

    public function getDuration()
    {

        return $this->duration;
    }

    public function setNodeList($ids)
    {
        foreach ($ids as $id) {
            array_push($this->nodeList, $id);
        }
    }

    public function getNodeList()
    {

        return $this->nodeList;
    }

}
