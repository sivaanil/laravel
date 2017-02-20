<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class NodeClassesTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/nodeClasses', 'nodeClasses' );
    }
    public function testBasicGet() {
        // request data
        // For some reasons TestHelper::authenticateAndSendGet sometimes fails only for this resource
        $header = TestHelper::getHeaderWithToken ();
        $response = TestHelper::sendGet ( TestHelper::API_TEST_SERVER_IP, $this->getUri (), $header );
        
        $response = TestHelper::authenticateAndSendGet ( $this->getUri () );
        // verify response
        $this->assertEquals ( 200, $response->getStatusCode () );
        // parse response body
        $data = json_decode ( $response->getBody ( true ), true );
        
        // Verify received data
        $this->assertEquals ( true, 
                array_key_exists ( 'nodeClasses', $data ), 
                'Check if node classes are present in response' );
        $nodeClasses = $data ["nodeClasses"];
        // Just check that count of nodeClasses is greater than 100.
        $this->assertGreaterThan ( 100, count ( $nodeClasses ), "Check number of available node classes." );
    }
    public function getExpectedData() {
        return [ 
                'id' => '1',
                'name' => 'Radio' 
        ];
    }
}
