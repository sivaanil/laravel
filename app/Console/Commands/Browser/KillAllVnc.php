<?php namespace Unified\Console\Commands\Browser;

use Illuminate\Console\Command;
use Unified\Browser\BrowserManager;

/**
 * Sends a command to the TCP server to kill all running VNC instances
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class KillAllVnc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:killallvnc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a command to the TCP server to kill all VNC instances running as c2-guest.';

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
    public function handle(BrowserManager $browserManager)
    {
        $browserManager->DeactivateAllSlots();
        $browserManager->SendCmd('killallvnc');
    }
}
