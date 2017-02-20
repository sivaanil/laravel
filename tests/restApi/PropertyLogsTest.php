<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class PropertyLogsTest extends RestApiTest {
    public function __construct() {
        $defaultFilters = [ 
                'propId' => '2315000',
                'created' => '1470182616' 
        ];
        parent::setTestResourse ( '/api/v1/propertyLogs', 'propertyLogs', $defaultFilters );
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
                array_key_exists ( 'propertyLogs', $data ), 
                'Check if property logs are present in response' );
        $propertyLogs = $data ["propertyLogs"];
        $this->assertGreaterThanOrEqual ( 0, count ( $propertyLogs ), "Check number of available property logs." );
        // Just check that count of propertyLogs is greater than or equal 0.
        // Additional tests should be added later
    }
    public function testGetWithCountBug755() {
        $query = [ 
                'count' => '1',
                'root' => '5000',
                'all' => '1',
                'limit' => '45000',
                'updated' => 0 
        ];
        // request data
        $response = TestHelper::authenticateAndSendGet ( $this->uri, $query );
        // verify response only
        $this->assertEquals ( 200, 
                $response->getStatusCode (), 
                'Resource: ' . $this->uri . ' Query: ' . print_r ( $query, 1 ) );
    }
    public function getExpectedData() {
        return [ 
                'propId' => '2315000',
                'nodeId' => '30737',
                'uuid' => '95f78791-5a8f-11e6-adfd-080027d6c7bf',
                'value' => '11',
                'created' => '1470182616' 
        ];
    }
}
