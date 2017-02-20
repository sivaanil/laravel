<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class PropertyDefinitionsTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/propertyDefinitions', 'propertyDefinitions' );
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
                array_key_exists ( 'propertyDefinitions', $data ), 
                'Check if property definitions are present in response' );
        $propertyDefinitions = $data ["propertyDefinitions"];
        // Just check that count of propertyDefinitions is equal default value 1000.
        $this->assertEquals ( 1000, count ( $propertyDefinitions ), "Check number of available property definitions." );
    }
    public function getExpectedData() {
        return [ 
                'id' => '17',
                'typeId' => '2',
                'groupId' => '1',
                'nodeTypeId' => '25',
                'name' => 'Down Link In-Band Input (dBm)',
                'useSnmp' => '0',
                'snmpOID' => null,
                'dataType' => 'DECIMAL',
                'variableName' => 'InBandInputDown',
                'isMinValue' => '0',
                'minValue' => '0',
                'isMaxValue' => '0',
                'maxValue' => '0',
                'severityId' => '4',
                'severityIdTwo' => '4',
                'tooltip' => 'DL input for PCS band',
                'valuetip' => null,
                'editable' => '0',
                'visible' => '1',
                'internal' => '0',
                'secure' => '0',
                'graphType' => '0',
                'enableThreshold' => '1',
                'alarmExempt' => '0',
                'dateUpdated' => '1430418000' 
        ];
    }
}
