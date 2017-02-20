<?php
use GuzzleHttp\Client;
use GuzzleHttp\Query;
class TestHelper {
    const API_TEST_SERVER_IP = '192.168.9.74';
    const LOGIN = '/api/v1/login';
    const SKIP_VALUE_VALIDATION = "__c2__any__";
    /**
     * Construct SiteGate URI
     * @param unknown $ip SiteGate IP
     * @param unknown $resource Endpoint
     */
    private static function getSiteGateRestUri($ip, $resource) {
        return 'https://' . $ip . $resource;
    }
    /**
     * Create Guzzle client and preset some default values.
     * @param unknown $header
     * @return \GuzzleHttp\Client
     */
    private static function presetClient($header) {
        $client = new Client();
        $client->setDefaultOption ( 'verify', false );
        $client->setDefaultOption ( 'exceptions', false );
        if ($header != null) {
            // Set a single header using path syntax
            $client->setDefaultOption ( 'headers', $header );
        }
        return $client;
    }
    /**
     * Send HTTP POST
     * @param unknown $ip Destination IP
     * @param unknown $resource Destination endpoint
     * @param unknown $header Header
     * @param unknown $data Body parameters
     */
    public static function sendPost($ip, $resource, $header, $data) {
        $client = self::presetClient($header);
        
        return $client->post ( self::getSiteGateRestUri ( $ip, $resource ), 
                [ 
                        'json' => $data 
                ] );
    }
    /**
     * Create authentication header based on provided token
     * @param unknown $token Authentication token
     * @return string[]
     */
    public static function createAuthHeader($token) {
        return [ 
                'Authorization' => 'Bearer ' . $token 
        ];
    }
    /**
     * Request token using default credentials and create header based on provided token
     */
    public static function getHeaderWithToken() {
        $data = [
                'username' => 'g8keeper',
                'password' => '123456'
        ];
        
        $response = self::sendPost ( self::API_TEST_SERVER_IP, self::LOGIN, null, $data );
        $data = json_decode ( $response->getBody ( true ), true );
        $token = $data ['token'];
        return self::createAuthHeader ( $token );
        
    }
    /**
     * Request default token and send HTTP get to test server
     * @param unknown $resource Endpoint
     * @param unknown $query Query parameters
     */
    public static function authenticateAndSendGet($resource, $query = null) {
        $header = self::getHeaderWithToken();
        return self::sendGet(self::API_TEST_SERVER_IP, $resource, $header, $query );
    }
    /**
     * Send HTTP get
     * @param unknown $ip Server IP address
     * @param unknown $resource Endpoint
     * @param unknown $header Message header
     */
    public static function sendGet($ip, $resource, $header, $query = null) {
        $client = self::presetClient($header);
        if (! empty ( $query )) {
            // Set a single header using path syntax
            $guzzleQuery = new Query($query);
            $guzzleQuery->setEncodingType(false);
            $client->setDefaultOption ( 'query', $guzzleQuery );
        }
        
        return $client->get ( self::getSiteGateRestUri ( $ip, $resource ) );
    }
    
    /**
     * Strictly validate received array against provided expected array.
     * @param unknown $test Test object 
     * @param unknown $expected Expected data
     * @param unknown $received Received data
     */
    public static function validateData($test, $expected, $received) {
        //Start recursive array comparisson
        self::compareOneArrayLevel ($test, $expected, $received);
        // Each verified element will be removed from the list of received elements
        // So, after verification $received will contain only unexpected elements.
        $test->assertEmpty ( $received, 'Received unexpectred data ' . print_r ( $received, 1 ) );
    }
    
    /**
     * Recursively validate received data against provided expected data.
     * @param unknown $test Test object
     * @param unknown $expected Expected data
     * @param unknown $received Received data
     */
    private static function compareOneArrayLevel ($test, $expected, &$received) {
        if (! is_array ( $received )) {
            $test->assertFalse ( true, "Received data is not array." );
        }
        if (! is_array ( $expected )) {
            $test->assertFalse ( true, "Internal error. Expected data is empty." );
        }
        // Check expected parameters
        foreach ( $expected as $key => $value ) {
            // Check if expected element is present
            $test->assertEquals ( true, array_key_exists ( $key, $received ), 'Missing expected parameter ' . $key . ' in '.print_r($received,1) .' Expected parameters:'.print_r($expected,1));
            // Check if type of the element is matching (array vs parameter)
            $test->assertEquals ( is_array ( $value ), 
                    is_array ( $received [$key] ), 
                    'Invalid expected parameter ' . $key );
            if (! is_array ( $value )) {
                if ($value !== self::SKIP_VALUE_VALIDATION) {
                    //  Check value of the element
                    $test->assertEquals ( $value, $received [$key], 'Invalid value of expected parameter ' . $key );
                }
                unset ( $received [$key] );
            } else {
                // Recursivly check sub object
                self::compareOneArrayLevel ( $test, $value, $received [$key] );
                if (empty ( $received [$key] )) {
                    unset ( $received [$key] );
                }
            }
        }
    }
    
    /**
     * Define if array is indexed.
     * Function is used to distinguish between
     * subobject and list of the same type objects during parameter validation.
     *
     * @param unknown $array
     */
    public function isIndexedArray($array) {
        return array_keys ( $array ) === range ( 0, count ( $array ) - 1 );
    }
}
