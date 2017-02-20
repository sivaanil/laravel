<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 404 NOT_FOUND response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NotFoundResponse extends ErrorResponse {
    function __construct($message, $errors = null) {
        parent::__construct ( ResponseCode::NOT_FOUND, $message, $errors );
    }
}
