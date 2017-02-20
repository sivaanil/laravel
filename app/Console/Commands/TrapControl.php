<?php

    namespace Unified\Console\Commands;

    use Illuminate\Console\Command;

    class TrapControl extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'csquared:trap-control';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Process Traps.';

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
            return exec('/usr/bin/php /usr/local/bin/trapControl.php >/dev/null 2>&1');
        }
    }
