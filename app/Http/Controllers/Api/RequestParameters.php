<?php

namespace Unified\Http\Controllers\Api;

use Exception;
use Input;

/**
 * API request parameters parser.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
class RequestParameters {
    private $request = [ ];
    private $params = [ ];
    private $content = [ ];
    private $user = [ ];
    private $segment = 0;
    public function __construct($request, $segment, $user) {
        $this->content = $this->decodeJsonContent ( trim($request->getContent ()) );
        // parse query string
        parse_str ( $_SERVER ['QUERY_STRING'], $this->params );
        $this->request = $request;
        $this->segment = $segment;
        $this->user = $user;
    }
    private function decodeJsonContent($content) {
        if (empty ( $content )) {
            return [ ];
        }

        // Return decode JSON data as associative array
        $decoded = json_decode ( $content, true );
        if (! ($decoded === false || $decoded === null)) {
            return $decoded;
        }

        if (!$decoded) {
            $decoded = Input::json();
            if ($decoded) {
                return $decoded;
            }
        }

        $errorMsg = "Could not decode JSON.";
        if (function_exists ( 'json_last_error' ) && function_exists ( 'json_last_error_msg' )) {
            $errorMsg .= " Error: " . json_last_error () . " " . json_last_error_msg ();
        }
        throw new Exception ( $errorMsg );
    }

    /**
     * Return request method.
     */
    public function getMethod() {
        return $this->request->getMethod ();
    }

    /**
     * Return resource path.
     */
    public function getPath() {
        return $this->request->path ();
    }

    /**
     * Return query.
     */
    public function getQuery() {
        return $_SERVER ['QUERY_STRING'];
    }

    /**
     * Return content.
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Return content by key.
     *
     * @param unknown $key
     * @return mixed|NULL
     */
    public function getContentByKey($key) {
        if (is_array ( $this->content ) && array_key_exists ( $key, $this->content )) {
            return $this->content [$key];
        }
        return null;
    }

    /**
     * Return request parameters.
     */
    public function getParameters() {
        return $this->params;
    }

    /**
     * Return request.
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Return user.
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Return segment.
     */
    public function getSegment() {
        return $this->segment;
    }
}
