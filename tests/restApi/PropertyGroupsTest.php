<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class PropertyGroupsTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/propertyGroups', 'propertyGroups' );
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
                array_key_exists ( 'propertyGroups', $data ), 
                'Check if property groups are present in response' );
        $propertyGroups = $data ["propertyGroups"];
        // Just check that count of propertyGroups is greater than 100.
        $this->assertGreaterThan ( 100, count ( $propertyGroups ), "Check number of available property groups." );
    }
    public function getExpectedData() {
        return [ 
                'id' => '57',
                'name' => 'Active/Inactive Event' 
        ];
    }
}
