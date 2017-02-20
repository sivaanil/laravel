<?php

namespace Unified\Console\Commands;

use Illuminate\Console\Command;

class SetTimezone extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timezone:set {timezone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets the timezone for the application environment.';



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $timezone = $this->argument('timezone');
        $path = base_path()."/.env";

        $newContent = [];
        $found = false;
        // Get the contents of the .env file
        foreach (file($path) as $line) {
            if (preg_match('/TIMEZONE/', $line)) {
                $line = "TIMEZONE=".$timezone . PHP_EOL;
                $found = true;
            }
            $newContent[] = $line;
        }


        if (!$found) {
            $newContent[] = "TIMEZONE=".$timezone . PHP_EOL;
        }

        // Write file contents back to .env
        file_put_contents($path, $newContent);

        $this->info("Timezone changed to '$timezone'");
    }
}
