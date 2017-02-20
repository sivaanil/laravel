<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class BuildQueuedDevicesError extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:build-queued-error';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Error Check for the Build Queue Devices.';

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
            return exec('/usr/bin/php /var/www/cswapi_example/cron/build_queued_device_error_check.php 2&>1');
        }
    }
