<?php
require_once 'TestHelper.php';
require_once 'RestApiTest.php';
class SnmpNotificationsTest extends RestApiTest {
    public function __construct() {
        parent::setTestResourse ( '/api/v1/notifications', 'notifications' );
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
                array_key_exists ( 'notifications', $data ), 
                'Check if SNMP notifications are present in response' );
        $nodeClasses = $data ["notifications"];
        // Just check that count of alarms is greater than or equal 0.
        $this->assertGreaterThanOrEqual ( 0, count ( $nodeClasses ), "Check number of SNMP notifications alarms." );
    }
    public function getExpectedData() {
        return [ 
                'id' => '2',
                'snmpDestId' => '1',
                'nodeId' => '39305',
                'readCommunity' => '1',
                'writeCommunity' => '1',
                'companyId' => '1',
                'format' => '1',
                'includeCustomThresholds' => '1',
                'username' => null,
                'authType' => '0',
                'authPassword' => '0',
                'authEncryption' => '0',
                'privPassword' => '0',
                'privEncryption' => '0',
                'hours' => [ 
                        [ 
                                'id' => '8',
                                'dayOfWeek' => '1',
                                'active' => '1',
                                'allDay' => '0',
                                'startTime' => '08:30:00',
                                'endTime' => '17:30:00',
                                'updated' => '2011-11-07 11:19:12',
                                'created' => '2011-11-07 11:19:12' 
                        ],
                        [ 
                                'id' => '9',
                                'dayOfWeek' => '2',
                                'active' => '1',
                                'allDay' => '0',
                                'startTime' => '08:30:00',
                                'endTime' => '17:30:00',
                                'updated' => '2011-11-07 11:19:12',
                                'created' => '2011-11-07 11:19:12' 
                        ],
                        [ 
                                'id' => '10',
                                'dayOfWeek' => '3',
                                'active' => '1',
                                'allDay' => '0',
                                'startTime' => '08:30:00',
                                'endTime' => '17:30:00',
                                'updated' => '2011-11-07 11:19:12',
                                'created' => '2011-11-07 11:19:12' 
                        ],
                        [ 
                                'id' => '11',
                                'dayOfWeek' => '4',
                                'active' => '1',
                                'allDay' => '0',
                                'startTime' => '08:30:00',
                                'endTime' => '17:30:00',
                                'updated' => '2011-11-07 11:19:12',
                                'created' => '2011-11-07 11:19:12' 
                        ],
                        [ 
                                'id' => '12',
                                'dayOfWeek' => '5',
                                'active' => '1',
                                'allDay' => '0',
                                'startTime' => '08:30:00',
                                'endTime' => '17:30:00',
                                'updated' => '2011-11-07 11:19:12',
                                'created' => '2011-11-07 11:19:12' 
                        ],
                        [ 
                                'id' => '13',
                                'dayOfWeek' => '6',
                                'active' => '0',
                                'allDay' => '0',
                                'startTime' => '08:30:00',
                                'endTime' => '17:30:00',
                                'updated' => '2011-11-07 11:19:12',
                                'created' => '2011-11-07 11:19:12' 
                        ],
                        [ 
                                'id' => '14',
                                'dayOfWeek' => '7',
                                'active' => '0',
                                'allDay' => '0',
                                'startTime' => '08:30:00',
                                'endTime' => '17:30:00',
                                'updated' => '2011-11-07 11:19:12',
                                'created' => '2011-11-07 11:19:12' 
                        ] 
                ] 
        ];
    }
}
