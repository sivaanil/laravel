<?php

require_once 'TestHelper.php';
class LoginTest extends TestCase {
    const LOGIN = '/api/v1/login';
    const LOGOUT = '/api/v1/logout';
    const SYSTEM = '/api/v1/system';
                
    public function setUp() {
    }
    public function tearDown() {
    }
    public function testLogin() {
        $data=['username'=>'g8keeper', 'password'=>'123456'];
        
        $response = TestHelper::sendPost( TestHelper::API_TEST_SERVER_IP, self::LOGIN, null, $data); 
        $this->assertEquals ( 200, $response->getStatusCode () );
        $data = json_decode ( $response->getBody ( true ), true );
        $this->assertArrayHasKey ( 'token', $data );
        $token = $data['token'];
        $header = TestHelper::createAuthHeader($token);
        
        // verify that token works by requesting system data
        $response = TestHelper::sendGet( TestHelper::API_TEST_SERVER_IP, self::SYSTEM, $header, null); 
        $this->assertEquals ( 200, $response->getStatusCode () );
        
        // Disable token
        $response = TestHelper::sendPost( TestHelper::API_TEST_SERVER_IP, self::LOGOUT, $header, null);
        $this->assertEquals ( 204, $response->getStatusCode () );
        
        // verify that disabled token does not work
        $response = TestHelper::sendGet( TestHelper::API_TEST_SERVER_IP, self::SYSTEM, $header, null); 
        $this->assertEquals ( 401, $response->getStatusCode () );
        
    }
    
    public function testInvalidCredentials() {
        $data=['username'=>'g8keeper', 'password'=>'1234567'];
        
        $response = TestHelper::sendPost( TestHelper::API_TEST_SERVER_IP, self::LOGIN, null, $data); 
        $this->assertEquals ( 401, $response->getStatusCode () );
    }
    
    public function testInvalidToken() {
        $header = TestHelper::createAuthHeader('HandMadeTokenThatIsNotSupposeToWork');
        
        $response = TestHelper::sendGet( TestHelper::API_TEST_SERVER_IP, self::SYSTEM, $header, null); 
        $this->assertEquals ( 401, $response->getStatusCode () );
    }
}
