<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 401 Unauthorized response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class UnauthorizedResponse extends ErrorResponse {
    function __construct($message = "Unable to authorize") {
        parent::__construct ( ResponseCode::UNAUTHORIZED, $message, null );
    }
}
