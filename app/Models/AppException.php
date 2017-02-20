<?php
namespace Unified\Models;

use Psr\Log\LogLevel;
use Unified\Services\API\ServiceResponse;

class AppException extends \Exception
{
    private $data = [];
    private $privateMessage;    // for internal logging
    private $responseStatus;    // to set carry a response status up to a catching response builder
    
    /**
     * construct
     * 
     * @param string    $publicMessage  Publically shown error message
     * @param string    $privateMessage Internally logged error message
     * @param string    $responseStatus ServiceResponse Status
     * @param integer   $code           Error Code
     * @param array     $data           Context Data
     * @param string    $logLevel       Log level to log the private message at (defaults to ERROR)
     */
    public function __construct($publicMessage, $privateMessage = null, $responseStatus = null, $code = 0, $data = [], $logLevel = LogLevel::ERROR)
    {
        parent::__construct($publicMessage, $code, null);

        // Store extra data (not held by \Exception)
        $this->privateMessage = $privateMessage;

        if ($responseStatus !== null) {
            $this->responseStatus = $responseStatus;
        }

        if (is_array($data) && count($data) > 0) {
            $this->data = $data;
        }

        // Log exception
        $callingMethod = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        $header = $callingMethod['class'] . $callingMethod['type'] . $callingMethod['function'];
        if (isset($callingMethod['line'])) {
            $header .= ':' . $callingMethod['line'];
        }
        \Log::$logLevel($header . ' - ' . $privateMessage, $this->data);
    }
    
    /**
     * get data
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * get private message
     */
    public function getPrivateMessage()
    {
        return $this->privateMessage;
    }
    
    /**
     * get private message
     */
    public function getResponseStatus()
    {
        return $this->responseStatus;
    }
    
    /**
     * toArray
     */
    public function toArray()
    {
        $data = $this->getData();
        $data['error'] = $this->getMessage();
        $data['code'] = $this->getCode();
        
        return $data;
    }

    /**
     * toServiceResponse
     */
    public function toServiceResponse()
    {
        return new ServiceResponse($this->responseStatus, ['error' => $this->getMessage()]);
    }
}