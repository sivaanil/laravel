<?php
namespace Tests\unit;

use \Illuminate\Support\Facades\Artisan;
use \Unified\Models\BaseSingleton;

class UnitTestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('DB_DEFAULT=xxxxx');
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }

    /**
     * reset state before/between/after tests
     */
    public function setUp()
    {
        BaseSingleton::clearInstances();
        parent::setUp();
    }

    public function tearDown()
    {
        BaseSingleton::clearInstances();
        parent::tearDown();
    }
}
