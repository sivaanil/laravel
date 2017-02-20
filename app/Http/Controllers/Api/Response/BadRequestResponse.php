<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 400 Bad request response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class BadRequestResponse extends ErrorResponse {
    function __construct($message, $errors = null) {
        parent::__construct ( ResponseCode::BAD_REQUEST, $message, $errors);
    }
}
