<?php namespace Unified\Console\Commands\Browser;

use Illuminate\Console\Command;
use Unified\System\CommandHelper;

/**
 * Sends a command to the TCP server to fully reset the c2-guest user
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class ResetGuest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:resetguest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets the c2-guest. Kills VNC, Firefox etc, repopulates c2-guest home directory';

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
        CommandHelper::CallWrapper('resetguest');
    }
}
