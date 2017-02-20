<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class PropertyTypesTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/propertyTypes', 'propertyTypes' );
    }
    public function testGet() {
        // request data
        $response = TestHelper::authenticateAndSendGet ( $this->getUri () );
        // verify response
        $this->assertEquals ( 200, $response->getStatusCode () );
        // parse response body
        $data = json_decode ( $response->getBody ( true ), true );
        
        // Verify received data
        $this->assertEquals ( true, 
                array_key_exists ( 'propertyTypes', $data ), 
                'Check if property types are present in response' );
        $propertyTypes = $data ["propertyTypes"];
        // Just check that count of propertyTypes is greater than or equal 2.
        $this->assertGreaterThanOrEqual ( 2, count ( $propertyTypes ), "Check number of available property types." );
    }
    public function getExpectedData() {
        return [ 
                'id' => '1',
                'name' => 'property' 
        ];
    }
}
