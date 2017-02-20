<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class ClearStuckScans extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:stuck-scans';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Clears All stuck scans.';

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
            exec('/usr/bin/php /var/www/'.env('CSWAPI_ENV').'/cron/stuck_scans.php > /dev/null 2>&1');
        }
    }
