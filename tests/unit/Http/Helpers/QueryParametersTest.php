<?php
namespace tests\Http\Helpers;

use \Tests\unit\UnitTestCase;
use \Unified\Http\Helpers\QueryParameters;

class QueryParametersTest extends UnitTestCase
{
    /**
     * test: toJsonString returns controller arguments as json
     */
    public function testToJsonStringReturnsControllerArgumentsAsJson()
    {
        // param
        $fields = ['some' => 'data'];
        $filters = [
            ['id', '='. '4']
        ];
        $control = [
          'b_key' => 'b_value',
          'a_key' => 'a_value',
          'c_key' => 'c_value'
        ];
        $sortby = [
          'stuff'
        ];

        $queryParameters = new QueryParameters($fields, $filters, $control, $sortby);

        // run
        $results = $queryParameters->toJsonString();

        // post-run assertion
        $expectedResults = '{'
            . '"fields":{"some":"data"},'
            . '"filters":[["id","=4"]],'
            . '"control":{"a_key":"a_value","b_key":"b_value","c_key":"c_value"},'
            . '"sortby":["stuff"]}';
        $this->assertEquals($expectedResults, $results);
    }
}