<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class TimezoneMonitor extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:timezone-monitor';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Timezone Monitor.';

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
            return exec('/usr/bin/php /var/www/'.env('CSWAPI_ENV').'/cron/server_timezone_monitor.php > /dev/null 2>&1');
        }
    }
