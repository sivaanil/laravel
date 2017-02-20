<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class AlarmSeveritiesTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/alarmSeverities', 'alarmSeverities' );
    }
    public function testBasicGet() {
        // request data
        $response = TestHelper::authenticateAndSendGet ( $this->getUri () );
        // verify response
        $this->assertEquals ( 200, $response->getStatusCode () );
        // parse response body
        $data = json_decode ( $response->getBody ( true ), true );
        
        // Verify received data
        $this->assertEquals ( true, 
                array_key_exists ( 'alarmSeverities', $data ), 
                'Check if alarm severities are present in response.' );
        $alarmSeverities = $data ["alarmSeverities"];
        // Just check that count of alarm severities is greater than 5.
        $this->assertGreaterThan ( 5, count ( $alarmSeverities ), "Check number of available alarm severities." );
    }
    public function getExpectedData() {
        return [ 
                "id" => "1",
                "name" => "Critical" 
        ];
    }
}
