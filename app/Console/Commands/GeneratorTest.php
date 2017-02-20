<?php

namespace Unified\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;  
use Unified\TaskExecuter\Worker;

class GeneratorTest extends Command
{
	use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GeneratorScheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        $fields = ['nodeId' => 321, 'type'=>'Generator'];
        
        $worker = new Worker('Generator', 'start' ,$fields);
        $worker->execute();
	print_r('Pushing Jobs to the Queue.');
    }
}