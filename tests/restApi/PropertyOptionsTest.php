<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class PropertyOptionsTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/propertyOptions', 'propertyOptions' );
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
                array_key_exists ( 'propertyOptions', $data ), 
                'Check if property options are present in response' );
        $propertyOptions = $data ["propertyOptions"];
        // Just check that count of propertyOptions is greater than 100.
        $this->assertGreaterThan ( 100, count ( $propertyOptions ), "Check number of available property options." );
    }
    public function getExpectedData() {
        return [ 
                'id' => '1',
                'propDefId' => '1784',
                'value' => '0',
                'text' => 'OFF',
                'graphValue' => '0' 
        ];
    }
}
