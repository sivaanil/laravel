<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 405 Method not allowed.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class MethodNotAllowedResponse extends ErrorResponse {
    function __construct($message) {
        parent::__construct ( ResponseCode::METHOD_NOT_ALLOWED, $message, null );
    }
}
