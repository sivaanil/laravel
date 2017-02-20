<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * API error response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
class ErrorResponse extends ApiResponse {
    private $message;
    private $errors;
    function __construct($code, $message, $errors) {
        $this->code = $code;
        $this->message = $message;
        $this->errors = $errors;
    }
    final public function getContent() {
        $content = array ();
        if ($this->message) {
            $content ['message'] = $this->message;
        }
        
        if ($this->errors) {
            $errList = array ();
            foreach ( $this->errors as $error ) {
                $errList [] ['error'] = $error;
            }
            
            $content ['errors'] = $errList;
        }
        return $content;
    }
    /**
     * Returns error message
     */
    final public function getMessage() {
        return $this->message;
    }
    /**
     * Returns list of errors
     */
    final public function getErrors() {
        return $this->errors;
    }
}
