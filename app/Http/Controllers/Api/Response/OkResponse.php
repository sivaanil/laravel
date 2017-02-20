<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * 200 OK response.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class OkResponse extends ApiResponse {
    private $content;
    function __construct($content) {
        $this->code = ResponseCode::OK;
        $this->content = $content;
    }
    public function getContent() {
        return $this->content;
    }
}
