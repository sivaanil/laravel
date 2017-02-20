<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class Rollback extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:rollback';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Rollback previous Sitegate upgrade.';

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
            //
        }
    }
