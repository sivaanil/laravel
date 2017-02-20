<?php
require_once 'TestHelper.php';
class RestApiTest extends TestCase {
    public function setTestResourse($uri, $dataName, $defaultQueryParameters = null) {
        $this->uri = $uri;
        $this->dataName = $dataName;
        $this->defaultQueryParameters = (is_null ( $defaultQueryParameters ) ? [ 
                'id' => $this->getExpectedData () ['id'] 
        ] : $defaultQueryParameters);
    }
    public function getUri() {
        return $this->uri;
    }
    public function getDataName() {
        return $this->dataName;
    }
    public function getDefaultQueryParameters() {
        return $this->defaultQueryParameters;
    }
    public function setUp() {
    }
    public function tearDown() {
    }
    public function testGetAllFields() {
        // Workaround issue with phpunit sensing this class as separate test class
        // Just return from "default" test function if getExpectedData is not implemented.
        if (! method_exists ( $this, 'getExpectedData' )) {
            return;
        }
        
        // add all to default query
        $defQuery = $this->getDefaultQueryParameters ();
        $defQuery ['all'] = 1;
        
        $this->checkQuery ( $defQuery, $this->getExpectedData () );
    }
    public function testAllPossibleFilters() {
        // Workaround issue with phpunit sensing this class as separate test class
        // Just return from "default" test function if getExpectedData is not implemented.
        if (! method_exists ( $this, 'getExpectedData' )) {
            return;
        }
        // add all to default query
        $defQuery = $this->getDefaultQueryParameters ();
        $defQuery ['all'] = 1;
        $this->recursevlyCheckFilters ( $defQuery, $this->getExpectedData () );
    }
    public function testAllPossibleSortby() {
        // Workaround issue with phpunit sensing this class as separate test class
        // Just return from "default" test function if getExpectedData is not implemented.
        if (! method_exists ( $this, 'getExpectedData' )) {
            return;
        }
        // add all to default query
        $defQuery = $this->getDefaultQueryParameters ();
        $defQuery ['all'] = 1;
        $this->recursevlyCheckSortby ( $defQuery, $this->getExpectedData () );
    }
    public function testFirstLevelFields() {
        // Workaround issue with phpunit sensing this class as separate test class
        // Just return from "default" test function if getExpectedData is not implemented.
        if (! method_exists ( $this, 'getExpectedData' )) {
            return;
        }
        
        $dataToBeVerified = $this->getExpectedData ();
        foreach ( $dataToBeVerified as $key => $value ) {
            if (is_array ( $value )) {
                // Check only first level non array items
                // Test can be modified later to cover all cases
                continue;
            }
            
            $query = $this->getDefaultQueryParameters ();
            $query ['fields'] = rawurlencode ( $key );
            // Create array of expected values
            $ev = [ ];
            $ev [$key] = $value;
            $this->checkQuery ( $query, $ev );
        }
    }
    public function checkQuery($query, $expectedData) {
        // request data
        $response = TestHelper::authenticateAndSendGet ( $this->uri, $query );
        // verify response
        $this->assertEquals ( 200, 
                $response->getStatusCode (), 
                'Resource: ' . $this->uri . ' Query: ' . print_r ( $query, 1 ) );
        // parse response body
        $data = json_decode ( $response->getBody ( true ), true );
        // Verify received data
        $this->assertEquals ( true, 
                array_key_exists ( $this->dataName, $data ), 
                'Check if ' . $this->dataName . ' are present in response' );
        if (! array_key_exists ( $this->dataName, $data )) {
            // Nothing to verify. Data elements are not present. Just return.
            return;
        }
        $elements = $data [$this->dataName];
        // Verify that opnly one record is received
        $this->assertEquals ( 1, 
                count ( $elements ), 
                'Verify that response contain just one node. Resource: ' . $this->uri .
                         ' Query: ' .
                         print_r ( $query, 1 ) );
        $element = $elements [0];
        // Verify all expected parameters
        TestHelper::validateData ( $this, $expectedData, $element );
    }
    private function recursevlyCheckFilters($query, $currentArrayLevel, $filterPrefix = '', $filterPostfix = '') {
        foreach ( $currentArrayLevel as $key => $value ) {
            if (is_array ( $value )) {
                $newFilterPrefix = $filterPrefix;
                $newFilterPostfix = $filterPostfix;
                // Parent is indexed array. No need to add anything
                if (! TestHelper::isIndexedArray ( $currentArrayLevel )) {
                    // Child is indexed array = add []
                    if (TestHelper::isIndexedArray ( $value )) {
                        $newFilterPrefix = $key . '[]';
                        $newFilterPostfix .= ']';
                    } else if (! empty ( $filterPrefix )) {
                        $newFilterPrefix = '[' . $key;
                        $newFilterPostfix .= ']';
                    } else {
                        $newFilterPrefix = $key;
                        $newFilterPostfix .= ']';
                    }
                }
                $this->recursevlyCheckFilters ( $query, $value, $newFilterPrefix, $newFilterPostfix );
                continue;
            }
            
            // We cannot filter null values, so just skip them
            if (is_null ( $value )) {
                continue;
            }
            
            // Skip values that should not be validated
            if (TestHelper::SKIP_VALUE_VALIDATION == $value) {
                continue;
            }
            
            // Create filter name
            if (empty ( $filterPrefix )) {
                $filterName = $key;
            } else {
                $filterName = $filterPrefix . '[' . $key . $filterPostfix;
            }
            $query [$filterName] = rawurlencode ( $value );
            $this->checkQuery ( $query, $this->getExpectedData () );
        }
    }
    
    // This function just validates if query returns the same expected data when sortby is usrd in the query.
    private function recursevlyCheckSortBy($query, $currentArrayLevel, $filterPrefix = '', $filterPostfix = '') {
        foreach ( $currentArrayLevel as $key => $value ) {
            if (is_array ( $value )) {
                $newFilterPrefix = $filterPrefix;
                $newFilterPostfix = $filterPostfix;
                // Parent is indexed array. No need to add anything
                if (! TestHelper::isIndexedArray ( $currentArrayLevel )) {
                    // Child is indexed array = add []
                    if (TestHelper::isIndexedArray ( $value )) {
                        $newFilterPrefix = $key . '[]';
                        $newFilterPostfix .= ']';
                    } else if (! empty ( $filterPrefix )) {
                        $newFilterPrefix = '[' . $key;
                        $newFilterPostfix .= ']';
                    } else {
                        $newFilterPrefix = $key;
                        $newFilterPostfix .= ']';
                    }
                }
                $this->recursevlyCheckSortBy ( $query, $value, $newFilterPrefix, $newFilterPostfix );
                continue;
            }
            
            
            // Create filter name
            if (empty ( $filterPrefix )) {
                $sortKey = $key;
            } else {
                $sortKey = $filterPrefix . '[' . $key . $filterPostfix;
            }
            $query ["sortby"] = $sortKey;
            $this->checkQuery ( $query, $this->getExpectedData () );
        }
    }
}
