<?php

namespace Unified\Devices\GeneratorScheduling;

/**
 * Description of GeneratorScheduleException
 *
 * @author Golnaz.Rouhi
 */
class GeneratorScheduleException extends \Exception
{

    protected $title;

    public function __construct($title, $message, $code = 0, $previous = null)
    {
        $this->title = $title;
        parent::__construct($message, $code, $previous);
    }

}
