<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 501 NOT_IMPLEMENTED response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NotImplementedResponse extends ErrorResponse {
    function __construct($message, $errors = null) {
        parent::__construct ( ResponseCode::NOT_IMPLEMENTED, $message, $errors );
    }
}
