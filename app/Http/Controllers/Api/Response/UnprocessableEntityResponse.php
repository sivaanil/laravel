<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 422 Unprocessable error response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class UnprocessableEntityResponse extends ErrorResponse {
    function __construct($message, $errors) {
        parent::__construct ( ResponseCode::UNPROCESSABLE_ENTITY, $message, $errors );
    }
}
