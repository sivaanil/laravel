<?php

namespace Unified\Console\Commands;

use App;
use Illuminate\Console\Command;

/**
 * Performs various cleanup tasks on reboot.
 * This should be run on the one minute cron before schedule:run
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class OnReboot extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csquared:on-reboot';
    protected $name = 'csquared:on-reboot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs various cleanup tasks on reboot.';

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
        // file is created by @reboot cron
        $flagFile = storage_path('framework/rebooted');

        if (file_exists($flagFile)) {
            $service = App::make('\Unified\Services\Reboot');

            if ($service->Process()) {
                $this->info('Reboot processed');
            }
            else {
                $this->info('Could not lock');
            }
        }
        else {
            $this->info('No reboot to process');
        }
    }
}
