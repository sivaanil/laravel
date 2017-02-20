<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class SystemMonitor extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:sysmon';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'System Monitoring.';

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
            return exec('/usr/bin/php /var/www/'.env('CSWAPI_ENV').'/cron/system_monitor.php > /dev/null 2>&1');
        }
    }
