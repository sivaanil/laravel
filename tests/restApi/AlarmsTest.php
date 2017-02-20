<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class AlarmsTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/alarms', 'alarms' );
    }
    public function testBasicGet() {
        // request data
        $response = TestHelper::authenticateAndSendGet ( $this->getUri () );
        // verify response
        $this->assertEquals ( 200, $response->getStatusCode () );
        // parse response body
        $data = json_decode ( $response->getBody ( true ), true );
        
        // Verify received data
        $this->assertEquals ( true, array_key_exists ( 'alarms', $data ), 'Check if alarms are present in response' );
        $nodeClasses = $data ["alarms"];
        // Just check that count of alarms is greater than or equal 0.
        $this->assertGreaterThanOrEqual ( 0, count ( $nodeClasses ), "Check number of available alarms." );
    }
    public function getExpectedData() {
        return [ 
                'id' => '24284',
                'nodeId' => '20477',
                'severityId' => '1',
                'description' => 'The device`s telnet settings could not be validated.',
                'sequence' => '1470365631',
                'uuid' => '94f963fe-5a86-11e6-adfd-080027d6c7bf',
                'isOffline' => '1',
                'ignored' => '0',
                'isTrap' => '0',
                'isHeartbeat' => '0',
                'isThreshold' => '0',
                'ignoreNotes' => null,
                'hasNotes' => '0',
                'notes' => 'Could not connect to the telnet for the device, check the telnet information for the device.',
                'isChronic' => '0',
                'isPerimeter' => '0',
                'permitNotifications' => '1',
                'canAcknowledge' => '0',
                'acknowledged' => '0',
                'snmpObjectId' => null,
                'durationExempt' => '0',
                'propertyAlarm' => '0',
                'logDateTime' => '0',
                'clearedBit' => '1',
                'clearedOrder' => '2647828321',
                'raised' => '1454529923',
                'cleared' => '1454530079',
                'ignoreUntil' => null,
                'dateUpdated' => '1470348175' 
        ];
    }
}
