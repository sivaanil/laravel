<?php
namespace Tests\unit\Models;

use Tests\unit\UnitTestCase;

use \DateTime;
use \DateTimeZone;
use Unified\Models\Permission;

class PermissionTest extends UnitTestCase
{
    /**
     * test: toArray returns expected format for permissions that have not been deleted
     */
    public function testToArrayReturnsExpectedFormatForPermissionsThatHaveNotBeenDeleted()
    {
        // param
        $attributes = (object) [
            'id' => 123,
            'slug' => 'a_slug',
            'title' => 'Your Majesty',
            'description' => 'Vague, with some details.',
            'created_at' => new DateTime('2016-01-01 10:00:00', new DateTimeZone('America/New_York')),
            'updated_at' => new DateTime('2016-01-01 12:00:00', new DateTimeZone('America/New_York')),
            'deleted' => 0
        ];
        $permission = new Permission($attributes);
        
        // run
        $results = $permission->toArray();
        
        // post-run assertions
        $expectedResults = [
            'id' => 123,
            'slug' => 'a_slug',
            'title' => 'Your Majesty',
            'description' => 'Vague, with some details.',
            'updatedAt' => 1451667600,
            'deleted' => 0
        ];
        $this->assertEquals($expectedResults, $results);
    }

    /**
     * test: toArray returns expected format for deleted permissions
     */
    public function testToArrayReturnsExpectedFormatForDeletedPermissions()
    {
        // param
        $attributes = (object) [
            'id' => 123,
            'slug' => 'a_slug',
            'title' => 'Your Majesty',
            'description' => 'Vague, with some details.',
            'created_at' => new DateTime('2016-01-01 10:00:00', new DateTimeZone('America/New_York')),
            'updated_at' => new DateTime('2016-01-01 12:00:00', new DateTimeZone('America/New_York')),
            'deleted' => 1
        ];
        $permission = new Permission($attributes);
        
        // run
        $results = $permission->toArray();
        
        // post-run assertions
        $expectedResults = [
            'id' => 123,
            'slug' => 'a_slug',
            'updatedAt' => 1451667600,
            'deleted' => 1
        ];
        $this->assertEquals($expectedResults, $results);
    }
    
    /**
     * test: construct converts times to unixtime if possible
     */
    public function testConstructConvertsTimesToUnixtimeIfPossible()
    {   
        //
        // Case #1: DateTime is converted to unixtime
        //
        $attributes = (object) [
            'id' => 123,
            'slug' => 'a_slug',
            'title' => 'Your Majesty',
            'description' => 'Vague, with some details.',
            'created_at' => new DateTime('2016-01-01 10:00:00', new DateTimeZone('America/New_York')),
            'updated_at' => new DateTime('2016-01-01 12:00:00', new DateTimeZone('America/New_York')),
            'deleted' => 1
        ];
        $permission = new Permission($attributes);

        $this->assertEquals(1451660400, $permission->getCreatedAt());
        $this->assertEquals(1451667600, $permission->getUpdatedAt());

        //
        // Case #2: Mysql String is converted to unixtime (using the server timezone)
        //
        $attributes = (object) [
            'id' => 123,
            'slug' => 'a_slug',
            'title' => 'Your Majesty',
            'description' => 'Vague, with some details.',
            'created_at' => '2016-01-01 10:00:00',  // in local time
            'updated_at' => '2016-01-01 12:00:00',  // in local time
            'deleted' => 1
        ];
        $permission = new Permission($attributes);
        
        // get the current server offset
        $serverTimezone = date_default_timezone_get();
        $time = new \DateTime('now', new DateTimeZone($serverTimezone));
        $timezoneOffset = (integer) (($time->format('O') / 100.0) * 3600);
        
        // Since the test times are being converted from local time to UTC Unixtime,
        // take the unixtime of those times if they were in UTC and subtract the 
        // timezone offset in seconds to see if they match.
        $this->assertEquals(1451642400 - $timezoneOffset, $permission->getCreatedAt());
        $this->assertEquals(1451649600 - $timezoneOffset, $permission->getUpdatedAt());

        //
        // Case #3 : null remains null
        //
        $attributes = (object) [
            'id' => 123,
            'slug' => 'a_slug',
            'title' => 'Your Majesty',
            'description' => 'Vague, with some details.',
            'created_at' => null,
            'updated_at' => null,
            'deleted' => 1
        ];
        $permission = new Permission($attributes);
        
        $this->assertNull($permission->getCreatedAt());
        $this->assertNull($permission->getUpdatedAt());

        //
        // Case #3 : integers are taken as the unixtime (UTC)
        //
        $attributes = (object) [
            'id' => 123,
            'slug' => 'a_slug',
            'title' => 'Your Majesty',
            'description' => 'Vague, with some details.',
            'created_at' => 1500000000,
            'updated_at' => 1500000001,
            'deleted' => 1
        ];
        $permission = new Permission($attributes);

        $this->assertEquals(1500000000, $permission->getCreatedAt());
        $this->assertEquals(1500000001, $permission->getUpdatedAt());
        
    }
}