<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class SnmpDestTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/snmpDest', 'snmpDest' );
    }
    public function testBasicGet() {
        // request data
        $response = TestHelper::authenticateAndSendGet ( $this->getUri () );
        // verify response
        $this->assertEquals ( 200, $response->getStatusCode () );
        // parse response body
        $data = json_decode ( $response->getBody ( true ), true );
        
        // Verify received data
        $this->assertEquals ( true, array_key_exists ( 'snmpDest', $data ), 'Check if SNMP destinations are present in response' );
        $nodeClasses = $data ["snmpDest"];
        // Just check that count of alarms is greater than or equal 0.
        $this->assertGreaterThanOrEqual ( 0, count ( $nodeClasses ), "Check number of SNMP destinations alarms." );
    }
    public function getExpectedData() {
        return [ 
                    'id' => '1',
                    'name' => 'NOC',
                    'homeNodeId' => '39305',
                    'ipAddress' => '166.12.12.12',
                    'snmpVersion' => '2c',
                    'readCommunity' => 'read',
                    'writeCommunity' => 'write',
                    'companyId' => '0',
                    'format' => '[LEVEL_2]_CELL_[DONOR_CELL]_[LEVEL_1]_[SITE_NAME][GROUP_PATH]',
                    'includeCustomThresholds' => '1',
                    'username' => null,
                    'authType' => null,
                    'authPassword' => null,
                    'authEncryption' => null,
                    'privPassword' => null,
                    'privEncryption' => null,
                    'engineID' => null,
                    'updated' => '1476381666',
                    'created' => '1476381666'
        ];
    }
}
