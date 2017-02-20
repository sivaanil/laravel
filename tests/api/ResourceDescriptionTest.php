<?php
use Unified\Http\Controllers\Api\ApiErrorCodes;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\Response\MethodNotAllowedResponse;
use Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse;
class ResourceDescriptionTest extends TestCase {
    private $putGetDescription;
    private $postDeleteDescription;
    public function setUp() {
        $this->postDeleteDescription = [ 
                ResourceDescription::POST => [ 
                        ResourceDescription::MANDATORY => [ 
                                "username",
                                "password" 
                        ],
                        ResourceDescription::OPTIONAL => [ 
                                "address" 
                        ] 
                ],
                ResourceDescription::DELETE 
        ];
        $ports = [ 
                    "port",
                    "name" 
            ];
        $this->putGetDescription = [ 
                ResourceDescription::PUT => [ 
                        ResourceDescription::OPTIONAL => [ 
                                "username",
                                "password",
                                "address",
                                "ports" => [$ports],
                                "object" => ["objectParam1","objectParam2"]
                        ] 
                ],
                ResourceDescription::GET => [ 
                        ResourceDescription::OPTIONAL => [ 
                                "username",
                                "password", 
                                "page",
                                "fields"
                        ] 
                ] 
        ];
    }
    public function tearDown() {
    }
    private function mockRequest($method, $content = null, $query = "") {
        $requestMock = $this->getMockBuilder ( 'Unified\Http\Controllers\Api\RequestParameters' )->disableOriginalConstructor ()->getMock ();
        $requestMock->expects ( $this->any () )->method ( 'getMethod' )->will ( $this->returnValue ( $method ) );
        $requestMock->expects ( $this->any () )->method ( 'getContent' )->will ( $this->returnValue ( $content ) );
        $requestMock->expects ( $this->any () )->method ( 'getQuery' )->will ( $this->returnValue ( $query ) );
        $params = [ ];
        $fields = [ ];
        if (! empty ( $query )) {
            parse_str ( $query, $params );
            // parse fields
            $fields = [ ];
            if (isset ( $params ) and isset ( $params ["fields"] )) {
                $fields = array_fill_keys ( explode ( ",", $params ["fields"] ), true );
            }
        }
        $requestMock->expects ( $this->any () )->method ( 'getParameters' )->will ( $this->returnValue ( $params ) );
        $requestMock->expects ( $this->any () )->method ( 'getFields' )->will ( $this->returnValue ( $fields ) );
        return $requestMock;
    }
    public function test_post_ok() {
        $rd = new resourceDescription ( $this->postDeleteDescription );
        $requestMock = $this->mockRequest ( "POST", 
                [ 
                        "username" => "username",
                        "password" => "pwd",
                        "address" => "addr" 
                ] );
        $response = $rd->validate ( $requestMock );
        
        $this->assertEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
    }
    public function test_post_methodNotAllowed() {
        $rd = new resourceDescription ( $this->postDeleteDescription );
        $requestMock = $this->mockRequest ( "PUT", [ 
                "username" => "username" 
        ] );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\MethodNotAllowedResponse" );
        $this->assertRegexp ( '/PUT is not allowed/', $response->getMessage (), "Check error message" );
    }
    public function test_post_missingMandatory() {
        $rd = new resourceDescription ( $this->postDeleteDescription );
        $requestMock = $this->mockRequest ( "POST", [ 
                "username" => "username" 
        ] );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertRegexp ( '/Unable to process POST/', $response->getMessage (), "Check error message" );
        $this->assertSame ( 'Missing mandatory parameter [password]', 
                $response->getErrors () [0], 
                "Check error message" );
    }
    public function test_post_unknownParameter() {
        $rd = new resourceDescription ( $this->postDeleteDescription );
        $requestMock = $this->mockRequest ( "POST", 
                [ 
                        "username" => "username",
                        "password" => "pwd",
                        "address1" => "addr" 
                ] );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertSame ( 'Unknown content parameter [address1]', $response->getErrors () [0], "Check error message" );
    }
    public function test_post_withQuery() {
        $rd = new resourceDescription ( $this->postDeleteDescription );
        $requestMock = $this->mockRequest ( "POST", 
                [ 
                        "username" => "username",
                        "password" => "pwd",
                        "address" => "addr" 
                ], 
                "id=1" );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertSame ( 'Unexpected query parameters id=1', $response->getErrors () [0], "Check error message" );
    }
    public function test_delete_withQuery() {
        $rd = new resourceDescription ( $this->postDeleteDescription );
        $requestMock = $this->mockRequest ( "DELETE", 
                [ 
                        "username" => "username",
                        "password" => "pwd",
                        "address" => "addr" 
                ], 
                "id=1" );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertSame ( 'Unexpected query parameters id=1', $response->getErrors () [0], "Check error message" );
        $this->assertSame ( 'Unexpected content parameters username,password,address', 
                $response->getErrors () [1], 
                "Check error message" );
    }
    public function test_delete_ok() {
        $rd = new resourceDescription ( $this->postDeleteDescription );
        $requestMock = $this->mockRequest ( "DELETE" );
        $response = $rd->validate ( $requestMock );
        $this->assertEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
    }
    public function test_put_ok() {
        $rd = new resourceDescription ( $this->putGetDescription );
        $requestMock = $this->mockRequest ( "PUT", 
                [ 
                        "username" => "username",
                        "password" => "pwd",
                        "address" => "addr",
                        "ports" => [ 
                                [ 
                                        "port" => 1,
                                        "name" => "name1" 
                                ],
                                [ 
                                        "port" => 2,
                                        "name" => "name2" 
                                ],
                                [ 
                                        "port" => 3,
                                        "name" => "name3" 
                                ] 
                        ],
                        "object" => [ 
                                "objectParam1" => "1",
                                "objectParam2" => "2" 
                        ] 
                ] );
        $response = $rd->validate ( $requestMock );
        $this->assertEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
    }
    public function test_put_UnexpectedParameter() {
        $rd = new resourceDescription ( $this->putGetDescription );
        $requestMock = $this->mockRequest ( "PUT", [ 
                "strangeParam" => "username" 
        ] );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertSame ( 'Unknown content parameter [strangeParam]', 
                $response->getErrors () [0], 
                "Check error message" );
    }
    public function test_put_fail_expectedArray() {
        $rd = new resourceDescription ( $this->putGetDescription );
        $requestMock = $this->mockRequest ( "PUT", [ 
                "ports" => [ "port" => "25", "name"=>"34"]
        ] );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertSame ( 'Expected array of objects ports', 
                $response->getErrors () [0], 
                "Check error message" );
    }
    public function test_put_fail_unexpectedArray() {
        $rd = new resourceDescription ( $this->putGetDescription );
        $requestMock = $this->mockRequest ( "PUT", [ 
                        "object" => [ 
                                [ 
                                        "objectParam1" => 1,
                                        "objectParam2" => "name1" 
                                ],
                                [ 
                                        "objectParam1" => 2,
                                        "objectParam2" => "name2" 
                                ],
                                [ 
                                        "objectParam1" => 3,
                                        "objectParam2" => "name3" 
                                ] 
                        ]
                ] );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertSame ( 'Unexpected array of objects object', 
                $response->getErrors () [0], 
                "Check error message" );
    }
    public function test_get_ok_without_params() {
        $rd = new resourceDescription ( $this->putGetDescription );
        $requestMock = $this->mockRequest ( "GET" );
        $response = $rd->validate ( $requestMock );
        $this->assertEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
    }
    public function test_get_ok_with_params() {
        $rd = new resourceDescription ( $this->putGetDescription );
        $requestMock = $this->mockRequest ( "GET", null, "page=2&password=1&fields=password,username" );
        $response = $rd->validate ( $requestMock );
        $this->assertEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
    }
    public function test_get_fail_with_bodyparams() {
        $rd = new resourceDescription ( $this->putGetDescription );
        $requestMock = $this->mockRequest ( "GET", 
                [ 
                        "somePAram" => "someValue",
                        "password" => "pwd",
                        "address" => "addr" 
                ] );
        $response = $rd->validate ( $requestMock );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertSame ( 'Unexpected body parameters somePAram,password,address', 
                $response->getErrors () [0], 
                "Check error message" );
    }
    public function test_get_with_unknown_parameter() {
        $rd = new resourceDescription ( $this->putGetDescription );
        $requestMock = $this->mockRequest ( "GET", null, "idd=1" );
        $response = $rd->validate ( $requestMock );
//        echo print_r ( $response, true );
        $this->assertNotEquals ( $response, ApiErrorCodes::SUCCESS, "Validate return value" );
        $this->assertEquals ( get_class ( $response ), 
                "Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse" );
        $this->assertSame ( 'Unknown parameter [idd]', $response->getErrors () [0], "Check error message" );
    }
}