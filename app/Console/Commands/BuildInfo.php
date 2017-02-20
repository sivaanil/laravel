<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class BuildInfo extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:info';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Generates the Build info for the current environment';

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
            return exec('/bin/bash /var/www/'.env('CSWAPI_ENV').'/cron/build-info.sh > /dev/null 2>&1');
        }
    }
