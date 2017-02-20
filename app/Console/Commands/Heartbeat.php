<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class Heartbeat extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:heartbeat';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Sends Heartbeat to C2 NOC.';

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
            exec('/usr/bin/snmptrap -v 2c -c ' . env('HEARTBEAT_COMMUNITY') . ' 10.195.14.136 "" .1.3.6.1.4.1.35509.1.4.8');
            exec('/usr/bin/snmptrap -v 2c -c ' . env('HEARTBEAT_COMMUNITY') . ' 10.215.210.246 "" .1.3.6.1.4.1.35509.1.4.8');
        }
    }
