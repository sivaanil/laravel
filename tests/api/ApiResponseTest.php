<?php
use Unified\Http\Controllers\Api\Response\BadRequestResponse;
use Unified\Http\Controllers\Api\Response\InternalErrorResponse;
use Unified\Http\Controllers\Api\Response\MethodNotAllowedResponse;
use Unified\Http\Controllers\Api\Response\NoContentResponse;
use Unified\Http\Controllers\Api\Response\NotFoundResponse;
use Unified\Http\Controllers\Api\Response\NotImplementedResponse;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\Response\UnauthorizedResponse;
use Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse;
class ApiResponseTest extends TestCase {
    public function setUp() {
    }
    public function tearDown() {
    }
    public function test_ok() {
        $e = new OkResponse ( "Everything is fine" );
        $this->assertEquals ( $e->getCode (), 200 );
        $this->assertEquals ( $e->getContent (), "Everything is fine" );
    }
    public function test_noContent() {
        $e = new NoContentResponse ();
        $this->assertEquals ( $e->getCode (), 204 );
    }
    public function test_badRequest() {
        $e = new BadRequestResponse ( "What are you doing?" );
        $this->assertEquals ( $e->getCode (), 400 );
        $this->assertEquals ( $e->getMessage (), "What are you doing?" );
    }
    public function test_unauthorized() {
        $e = new UnauthorizedResponse ( "Who are you?" );
        $this->assertEquals ( $e->getCode (), 401 );
        $this->assertEquals ( $e->getMessage (), "Who are you?" );
        $e = new UnauthorizedResponse ();
        $this->assertEquals ( $e->getCode (), 401 );
        $this->assertEquals ( $e->getMessage (), "Unable to authorize" );
    }
    public function test_notFound() {
        $e = new NotFoundResponse ( "Something not found" );
        $this->assertEquals ( $e->getCode (), 404 );
        $this->assertEquals ( $e->getMessage (), "Something not found" );
    }
    public function test_methodNowAllowed() {
        $e = new MethodNotAllowedResponse ( "Method is not allowed" );
        $this->assertEquals ( $e->getCode (), 405 );
        $this->assertEquals ( $e->getMessage (), "Method is not allowed" );
    }
    public function test_unprocessableError() {
        $e = new UnprocessableEntityResponse ( "Something unprocessably bad", 
                [ 
                        "invalid group",
                        "Invalid field" 
                ] );
        $this->assertEquals ( $e->getCode (), 422 );
        $this->assertEquals ( $e->getMessage (), "Something unprocessably bad" );
        $this->assertEquals ( $e->getErrors () [0], "invalid group" );
        $this->assertEquals ( $e->getErrors () [1], "Invalid field" );
    }
    public function test_internalError() {
        $e = new InternalErrorResponse ( "Something bad", 
                [ 
                        "error1",
                        "error2" 
                ] );
        $this->assertEquals ( $e->getCode (), 500 );
        $this->assertEquals ( $e->getMessage (), "Something bad" );
        $this->assertEquals ( $e->getErrors () [0], "error1" );
        $this->assertEquals ( $e->getErrors () [1], "error2" );
    }
    public function test_notImplemented() {
        $e = new NotImplementedResponse ( "Something important is not implemented", 
                [ 
                        "this",
                        "that" 
                ] );
        $this->assertEquals ( $e->getCode (), 501 );
        $this->assertEquals ( $e->getMessage (), "Something important is not implemented" );
        $this->assertEquals ( $e->getErrors () [0], "this" );
        $this->assertEquals ( $e->getErrors () [1], "that" );
        $e = new NotImplementedResponse ( "Something important is not implemented again" );
        $this->assertEquals ( $e->getCode (), 501 );
        $this->assertEquals ( $e->getMessage (), "Something important is not implemented again" );
        $this->assertEquals ( $e->getErrors (), null );
    }
}