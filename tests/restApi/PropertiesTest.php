<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class PropertiesTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/properties', 'properties' );
    }
    public function testGet() {
        // request data
        $response = TestHelper::authenticateAndSendGet ( $this->getUri() );
        // verify response
        $this->assertEquals ( 200, $response->getStatusCode () );
        // parse response body
        $data = json_decode ( $response->getBody ( true ), true );
        
        // Verify received data
        $this->assertEquals ( true, 
                array_key_exists ( 'properties', $data ), 
                'Check if properties are present in response' );
        $properties = $data ['properties'];
        $this->assertGreaterThanOrEqual ( 0, count ( $properties ), 'Check number of available properties.' );
        // Just check that count of properties is greater than or equal 0.
        // Additional tests should be added later
    }
    public function getExpectedData() {
        return [ 
                'id' => '511812',
                'nodeId' => '5000',
                'propDefId' => '803897',
                'value' => '53',
                'uuid' => '688746c7-5a8f-11e6-adfd-080027d6c7bf',
                'nodeTypeId' => '5000',
                'name' => 'Memory_Usage_Percentage',
                'variableName' => 'Memory_Usage_Percentage',
                'isMinValue' => '0',
                'minValue' => null,
                'isMaxValue' => '0',
                'maxValue' => null,
                'severityId' => '4',
                'severityIdTwo' => '4',
                'alarmId' => '0',
                'alarmChange' => '0',
                'alarmSiteportalChange' => '0',
                'useDefaults' => '1',
                'sequence' => null,
                'lastUpdatedUserId' => '0',
                'dateUpdated' => '1470348252',
                'dateCreated' => '1442581327' 
        ];
    }
}
