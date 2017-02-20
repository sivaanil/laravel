<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class CleanupScanImages extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:cleanup-scan-images';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Cleans up all of the scan images that were taken.';

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
            exec('/usr/bin/php /var/www/'.env('CSWAPI_ENV').'/cron/delete_scan_images.php > /dev/null 2>&1');
        }
    }
