<?php

namespace Unified\Services\API\RequestValidators;

use Exception;

/**
 * Define a validation exception class to differentiate validation exception from execution exception
 */
class ValidationException extends Exception {
    // Redefine the exception so message isn't optional
    public function __construct($message) {
        parent::__construct ( $message );
    }
}