<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class AlarmRuleTypesTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/alarmRuleTypes', 'alarmRuleTypes' );
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
                array_key_exists ( 'alarmRuleTypes', $data ), 
                'Check if alarm rule types are present in response' );
        $alarmRuleTypes = $data ["alarmRuleTypes"];
        $this->assertGreaterThan ( 2, count ( $alarmRuleTypes ), "Check number of available alarm rule types." );
        // Just check that count of alarm rule types is greater than 2.
        // Additional tests should be added later
    }
    public function getExpectedData() {
        return [ 
                "id" => "1",
                "name" => "Single Value Compare" 
        ];
    }
}
