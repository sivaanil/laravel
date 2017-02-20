<?php

namespace Unified\Http\Controllers\Api\Response;

use Illuminate\Http\Response;

/**
 * API response base class.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
abstract class ApiResponse {
    protected $code;
    
    /**
     * Returns HTTP response code.
     */
    final public function getCode() {
        return $this->code;
    }
    
    /**
     * Returns HTTP response content object
     */
    abstract public function getContent();
    
    /**
     * Returns Laravel Response object
     */
    final public function response() {
        // Current veresion af API returns responses only in JSON format.
        return Response::create ( $this->getContent (), $this->getCode () )->header ( 'Content-Type', 'application/json' );
    }
}
