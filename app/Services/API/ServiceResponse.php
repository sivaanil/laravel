<?php

namespace Unified\Services\API;

/**
 * API service response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class ServiceResponse  {

    const SUCCESS   = 'SUCCESS';
    const FAIL      = 'FAIL';
    const FORBIDDEN = 'FORBIDDEN';
    const BAD_REQUEST = 'BAD_REQUEST';
    const UNPROCESSABLE_ENTITY = 'UNPROCESSABLE_ENTITY';
    const INTERNAL_ERROR = 'INTERNAL_ERROR';
    const NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    const NOT_FOUND = 'NOT_FOUND';
    
    protected $status;
    protected $content;

    public function __construct($status, $content = []) {
        $this->status = $status;
        $this->content = $content;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getContent() {
        return $this->content;
    }
}
