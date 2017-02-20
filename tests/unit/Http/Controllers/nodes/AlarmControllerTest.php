<?php
namespace Tests\unit\Http\Controllers\nodes;

use \Tests\unit\UnitTestCase;

class AlarmControllerTest extends UnitTestCase
{
    
    /**
     * test: createFilterGroupList
     */
    public function testCreateFilterGroupList()
    {
        // param
        $filterGroup = [
            [
                'id' => '13',
                'value' => 'true',
                'list' => [
                    [
                        'id' => '14',
                        'value' => 'false'
                    ],
                    [
                        'id' => '15',
                        'value' => 'true'
                    ]
                ]
            ],
            [
                'id' => '17',
                'value' => 'false',
                'list' => [
                    [
                        'id' => '18',
                        'value' => 'false'
                    ],
                    [
                        'id' => '19',
                        'value' => 'true'
                    ]
                ]
            ],
            [
                'id' => '20',
                'value' => 'false',
                'list' => [
                    [
                        'id' => '21',
                        'value' => 'false'
                    ],
                    [
                        'id' => '22',
                        'value' => 'false'
                    ]
                ]
            ]
        ];
        $module = 'a_module';

        // mock
        $finalResults = 'final results';
        $expectedIdList = [
            13,
            19
        ];
        $expectedNoneTrue = [
            '20' => 20
        ];

        $alarmController = $this->getMockBuilder('Unified\Http\Controllers\nodes\AlarmController')
            ->disableOriginalConstructor()
            ->setMethods(['buildQueryListFromFilterIdList'])
            ->getMock();
        $alarmController->expects($this->once())
            ->method('buildQueryListFromFilterIdList')
            ->with(
                $this->equalTo($expectedIdList),
                $this->equalTo($expectedNoneTrue),
                $this->equalTo($module)
            )
            ->willReturn($finalResults);

        // run
        $results = $alarmController->createFilterGroupList($filterGroup, $module);

        // post-run assertions
        $this->assertEquals($finalResults, $results);
    }
}