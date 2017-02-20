<?php

namespace Unified\Console\Commands;

use Log;
use Illuminate\Console\Command;

class ServiceRestart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:service-restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restarts all services pertaining to Site Portal Environment.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $callArray = [
            "ClearCache"    => 'cache:clear',
            "ConfigClear"   => 'config:clear',
            "ClearCompiled" => 'clear-compiled',
            "RouteClear" => 'route:clear',
        ];

        foreach ($callArray as $key => $cmd)
        {
            try
            {
                Log::info(__METHOD__ . "::Running Artisan $cmd");
                $this->call($cmd);
                Log::debug(__METHOD__ . "::$key Output = ", (array) $this->getOutput());
                Log::info("");
                sleep(2);
            } catch ( \Exception $e )
            {
                Log::error(__METHOD__ . "::Exception", (array) $e->getMessage());
            }
        }
        // APACHE
        exec('/usr/sbin/service apache2 restart >/dev/null 2>&1');
        // SUPERVISOR
        exec('/usr/sbin/service supervisor stop >/dev/null 2>&1');
        sleep(10); // added as workaround for intermittent restart issue described in bug 7283
        exec('/usr/sbin/service supervisor start >/dev/null 2>&1');
        //REDIS
        //
    }
}
