<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class ProcessPop3 extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:process-pop3';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Process POP3.';

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
            return exec('/usr/bin/php /var/www/'.env('CSWAPI_ENV').'/cron/process_pop3.php > /dev/null 2>&1');
        }
    }
