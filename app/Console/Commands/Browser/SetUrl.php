<?php namespace Unified\Console\Commands\Browser;

use Illuminate\Console\Command;
use Unified\Browser\BrowserManager;

/**
 * Sends a command to the TCP server to change the URL on the browser
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class SetUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:set-browser-url {slotId} {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a command to the TCP server to change the browser URL.';

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
        $browserManager->SendCmd('url ' . $this->argument('slotId') . ' ' . $this->argument('url'));
    }
}
