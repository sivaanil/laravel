<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 204 No content.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NoContentResponse extends ApiResponse {
    function __construct() {
        $this->code = ResponseCode::NO_CONTENT;
    }
    public function getContent() {
        return null;
    }
}
