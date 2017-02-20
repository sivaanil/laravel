<?php

namespace Unified\Console\Commands\Browser;

use Illuminate\Console\Command;
use Unified\Browser\BrowserManager;

/**
 * Sends a command to the TCP server to garbage collect old Guacamole / VNC sessions
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class GarbageCollect extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:guac-gc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a command to the TCP server to garbage collect old Guacamole / VNC sessions.';

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
        $resetIds = $browserManager->GarbageCollect();

        if (empty($resetIds)) {
            $this->info('Nothing to reset');
        } else {
            $this->info('Reset slots: ' . implode(',', $resetIds));
        }
    }

}
