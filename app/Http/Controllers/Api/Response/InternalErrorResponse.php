<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 500 Internal error response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class InternalErrorResponse extends ErrorResponse {
    function __construct($message, $errors) {
        $errors[]="SiteGate Rest API error";
        parent::__construct ( ResponseCode::INTERNAL_ERROR, $message, $errors );
    }
}
