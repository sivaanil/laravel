<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class NodeTypesTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/nodeTypes', 'nodeTypes' );
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
                array_key_exists ( 'nodeTypes', $data ), 
                'Check if node types are present in response' );
        $nodeTypes = $data ['nodeTypes'];
        // Just check that count of $nodeTypes is greater than 800.
        $this->assertGreaterThan ( 800, count ( $nodeTypes ), "Check number of available node types." );
    }
    
    public function getExpectedData() {
        return [ 
                'id' => '35',
                'classId' => '9',
                'vendor' => 'Andrew',
                'model' => 'Node A',
                'class' => 'Repeater',
                'autoBuild' => '1',
                'canAddChildren' => '0',
                'mainDevice' => '1',
                'usesDefaultValue' => '1',
                'usesSnmp' => '1',
                'hasWebInterface' => '1',
                'ports' => [ 
                        [ 
                                'portDefId' => '17',
                                'name' => 'HTTP',
                                'port' => '80' 
                        ],
                        [ 
                                'portDefId' => '18',
                                'name' => 'SNMP',
                                'port' => '161' 
                        ] 
                ],
                'snmp' => [ 
                        'snmpVersion' => '2c',
                        'snmpRead' => 'public',
                        'snmpWrite' => 'private',
                        'authType' => 'authPriv',
                        'username' => null,
                        'authPassword' => null,
                        'authEncryption' => 'SHA',
                        'privPassword' => null,
                        'privEncryption' => 'AES' 
                ],
                'webUi' => [ 
                        'webUiLink' => null,
                        'username' => 'Node_A',
                        'password' => 'Golden_Node' 
                ] 
        ];
    }
}
