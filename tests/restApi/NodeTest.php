<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class NodeTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/nodes', 'nodes' );
    }
    public function testBasicGet() {
        // request data
        $response = TestHelper::authenticateAndSendGet ( $this->getUri () );
        // verify response
        $this->assertEquals ( 200, $response->getStatusCode () );
        // parse response body
        $data = json_decode ( $response->getBody ( true ), true );
        
        // Verify received data
        $this->assertEquals ( true, array_key_exists ( 'nodes', $data ), 'Check if nodes are present in response' );
        $nodes = $data ['nodes'];
        // Just check that count of nodes is greater than 1.
        $this->assertGreaterThanOrEqual ( 1, count ( $nodes ), "Check number of available nodes ." );
    }
    
    public function testConditionQuery() {
        $query = [ 
                'all' => '1',
                'id:gt' => '20476',
                'id:lt' => '20478',
                'all' => '1' 
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
                'id' => '20477',
                'nodeMap' => '.321.5000.20477.',
                'parent' => '5000',
                'uuid' => 'dceff46b-8688-11e6-990a-080027d6c7bf',
                'visible' => '1',
                'currentStatusId' => '1',
                'deleted' => '0',
                'class' => 'Supervisor Unit (DAS)',
                'classId' => '10',
                'vendor' => 'TEKO',
                'model' => 'TSPV',
                'typeId' => '2008',
                'userId' => '1',
                'isSiteportalDevice' => '0',
                'webEnabled' => '1',
                'name' => 'AT&T_T-Mobile_Rack1',
                'dateAdded' => null,
                'dateCreated' => '1453121967',
                'dateUpdated' => TestHelper::SKIP_VALUE_VALIDATION,
                'sequence' => TestHelper::SKIP_VALUE_VALIDATION,
                'ports' => [ 
                        [ 
                                'devicePort' => '80',
                                'portDefId' => '281',
                                'variable' => 'http',
                                'name' => 'HTTP',
                                'modemPort' => '80' 
                        ],
                        [ 
                                'devicePort' => '161',
                                'portDefId' => '277',
                                'variable' => 'snmp',
                                'name' => 'SNMP',
                                'modemPort' => '161' 
                        ],
                        [ 
                                'devicePort' => '5556',
                                'portDefId' => '279',
                                'variable' => 'telnet',
                                'name' => 'TEKO',
                                'modemPort' => '5556' 
                        ] 
                ],
                'stats' => [ 
                        'failedScanCount' => '0',
                        'failedAlarmsScanCount' => '0',
                        'failedPropertiesScanCount' => '0',
                        'stuckCount' => '0',
                        'scanning' => '0',
                        'lastHeartbeat' => '946702800',
                        'lastScan' => '1470294878',
                        'lastAlarmsScan' => '1470294878',
                        'lastPropertiesScan' => '1470292375',
                        'lastFailedScan' => '1470281845',
                        'lastFailedAlarmsScan' => '1470281845',
                        'lastFailedPropertiesScan' => '946702800' 
                ],
                'config' => [ 
                        'scanPropertiesEnabled' => '1',
                        'propertiesScanInterval' => null,
                        'stopPropertyNotes' => null,
                        'alarmsScanEnabled' => '1',
                        'alarmsScanInterval' => 5,
                        'offlineAlarmExempt' => '0',
                        'alarmExempt' => '0',
                        'stopAlarmNotes' => null,
                        'scanEnabled' => '0',
                        'stopScanNotes' => null,
                        'scanInterval' => 5,
                        'heartbeatEnabled' => '0',
                        'trapEnabled' => '1',
                        'queueDevice' => null,
                        'secure' => '0',
                        'inheritContact' => '1',
                        'siteLicense' => '0',
                        'stopPropertyUntil' => '946702800',
                        'stopAlarmUntil' => '946702800',
                        'stopScanUntil' => '946702800',
                        'autoTicketEnabled' => '0' 
                ],
                'info' => [ 
                        'ipAddress' => '10.0.3.10',
                        'ipAddress2' => null,
                        'macAddress' => null,
                        'firmware' => hex2bin ( '332e312e33000000000000000000000000000000' ),
                        'coordinates' => [ 
                                'coordMode' => '1',
                                'latitudeOrigin' => '0',
                                'longitudeOrigin' => '0',
                                'perimeter' => '0',
                                'longitude' => null,
                                'latitude' => null 
                        ],
                        'description' => '',
                        'contactNotes' => '',
                        'comments' => null,
                        'notes' => '',
                        'address' => [ 
                                'street' => null,
                                'city' => null,
                                'state' => null,
                                'zip' => null,
                                'country' => null 
                        ],
                        'contactName' => null,
                        'contactPhone' => null,
                        'contactEmail' => null,
                        'mobile' => null,
                        'fax' => null 
                ],
                'webUi' => [ 
                        'webUiLink' => null,
                        'username' => 'admin',
                        'password' => 'Password1' 
                ],
                'snmp' => [ 
                        'snmpVersion' => '3',
                        'snmpNativeId' => null,
                        'snmpRead' => 'public',
                        'snmpWrite' => 'private',
                        'authType' => 'authPriv',
                        'username' => 'admin',
                        'authPassword' => 'Password1',
                        'authEncryption' => 'SHA',
                        'privPassword' => null,
                        'privEncryption' => null 
                ] 
        ];
    }
}
